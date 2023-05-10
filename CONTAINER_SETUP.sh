#!/bin/bash
# This SETUP Script should ONLY BE USED WHEN running inside of an container like for example Docker

if ! [ $(id -u) = 0 ]; then
    echo "You need to run this script as root user or via sudo!"
    exit 1
fi

# Enable debug mode so that CakePHP will create missing folders
# https://github.com/it-novum/openITCOCKPIT/issues/1446
# https://github.com/cakephp/migrations/issues/565
export OITC_DEBUG=1

APPDIR="/opt/openitc/frontend"

INIFILE=/opt/openitc/etc/mysql/mysql.cnf
DUMPINIFILE=/opt/openitc/etc/mysql/dump.cnf
BASHCONF=/opt/openitc/etc/mysql/bash.conf

PHPVersion=$(php -r "echo substr(PHP_VERSION, 0, 3);")

OSVERSION=$(grep VERSION_CODENAME /etc/os-release | cut -d= -f2)
OS_BASE="debian"

echo "Copy required system files"
rsync -K -a ${APPDIR}/system/etc/. /etc/ # we use rsync because the destination can be a symlink on RHEL
chown root:root /etc
cp -r ${APPDIR}/system/usr/. /usr/
cp ${APPDIR}/system/nginx/ssl_options_$OSVERSION /etc/nginx/openitc/ssl_options.conf
cp ${APPDIR}/system/nginx/ssl_cert.conf /etc/nginx/openitc/ssl_cert.conf
echo "# This file will NOT be overwritten during an update" >> /etc/nginx/openitc/custom.conf
chmod +x /usr/bin/oitc

echo "Create required system folders"
mkdir -p /opt/openitc/etc/{mysql,grafana,carbon,frontend,nagios,nsta}
mkdir -p /opt/openitc/nagios/etc/config
mkdir -p /opt/openitc/etc/nagios/nagios.cfg.d

chown www-data:www-data /opt/openitc/logs/frontend
chown nagios:www-data /opt/openitc/nagios/etc/config
chown nagios:www-data /opt/openitc/etc/nagios/nagios.cfg.d
chmod 775 /opt/openitc/logs/frontend

chown www-data:www-data /opt/openitc/frontend/tmp

mkdir -p /opt/openitc/frontend/webroot/img/charts
chown www-data:www-data /opt/openitc/frontend/webroot/img/charts

if [[ -d /opt/openitc/frontend/plugins/MapModule/webroot/img/ ]]; then
    chown -R www-data:www-data /opt/openitc/frontend/plugins/MapModule/webroot/img/
fi

# It is important that the statusengine-worker container is already up and running and has created all
# the Statusengine related MySQL tables, because the setup will add partitions to the tables
echo "Checking if Statusengine Tables where already created..."
COUNTER=0
MYSQL_ONLINE=0

set +e
while [ "$COUNTER" -lt 30 ]; do
    #Is Grafana Server Online?
    OUTPUT=$(mysql "--defaults-extra-file=$INIFILE" -e "SELECT 1 FROM statusengine_nodes;" -B -s 2>/dev/null)

    if [ "$OUTPUT" ]; then
        echo "Tables are present."
        MYSQL_ONLINE=1
        break
    fi
    echo "Waiting for Statusengine to create tables..."
    COUNTER=$((COUNTER + 1))
    sleep 1
done

if [[ "$MYSQL_ONLINE" == 0 ]]; then
    echo "ERROR!"
    echo "Statusengine tables are missing! ABORT!"
    exit 1
fi
set -e

#mkdir -p /opt/openitc/var/prometheus
#chown nagios:nagios /opt/openitc/var/prometheus
#mkdir -p /opt/openitc/var/prometheus/victoria-metrics

echo "---------------------------------------------------------------"
echo "Import openITCOCKPIT Core database schema"
oitc migrations migrate


echo "---------------------------------------------------------------"
echo "Load default database"
oitc migrations seed

echo "Running openITCOCKPIT Module database migration/s"
for PLUGIN in $(ls -1 "${APPDIR}/plugins"); do
    if [[ "$PLUGIN" == *Module ]]; then
        if [[ -d "${APPDIR}/plugins/${PLUGIN}/config/Migrations" ]]; then
            echo "Running openITCOCKPIT ${PLUGIN} database migration"
            oitc migrations migrate -p "${PLUGIN}"
        fi

        if [[ -d "${APPDIR}/plugins/${PLUGIN}/config/Seeds" ]]; then
            num_files=$(find "${APPDIR}/plugins/${PLUGIN}/config/Seeds" -mindepth 1 -iname "*.php" -type f | wc -l)
            if [[ "$num_files" -gt 0 ]]; then
                echo "Importing default records for ${PLUGIN} into database"
                oitc migrations seed -p "${PLUGIN}"
            fi
        fi

    fi
done

if [ -d "${APPDIR}/plugins/CheckmkModule/src" ]; then
    oitc checkmkNagiosExport --init
fi

echo "---------------------------------------------------------------"
echo "Create new WebSocket Key"
WEBSOCKET_KEY=$(php -r "echo bin2hex(openssl_random_pseudo_bytes(80, \$cstrong));")
mysql "--defaults-extra-file=${INIFILE}" -e "UPDATE systemsettings SET \`systemsettings\`.\`value\`='${WEBSOCKET_KEY}' WHERE \`key\`='SUDO_SERVER.API_KEY';"

if [ ! -f /opt/openitc/etc/grafana/admin_password ]; then
    echo "Generate new Grafana password for user 'admin'"
    pwgen 10 1 > /opt/openitc/etc/grafana/admin_password
fi

oitc config_generator_shell --generate-container

echo "---------------------------------------------------------------"
echo "Configure Grafana"

ADMIN_PASSWORD=$(cat /opt/openitc/etc/grafana/admin_password)

if [ ! -f /opt/openitc/etc/grafana/api_key ]; then
    echo "Create new Grafana API Key for openITCOCKPIT"
    COUNTER=0

    set +e
    while [ "$COUNTER" -lt 30 ]; do
        echo "Try to connect to Grafana API..."
        #Is Grafana Server Online?
        STATUSCODE=$(curl --noproxy 'grafana' "http://$OITC_GRAFANA_URL/api/admin/stats" -XGET -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -I 2>/dev/null | head -n 1 | cut -d$' ' -f2)

        if [ "$STATUSCODE" == "200" ]; then
            API_KEY=$(curl --noproxy 'grafana' "http://$OITC_GRAFANA_URL/api/auth/keys" -XPOST -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -d '{"role":"Editor","name":"openITCOCKPIT"}' | jq -r '.key')
            echo "$API_KEY" >/opt/openitc/etc/grafana/api_key
            break
        fi
        COUNTER=$((COUNTER + 1))
        sleep 1
    done

    if [ ! -f /opt/openitc/etc/grafana/api_key ]; then
        echo "ERROR!"
        echo "Could not create API key for Grafana"
    fi
    set -e
fi

echo "Check if Graphite Datasource exists in Grafana"
DS_STATUSCODE=$(curl --noproxy 'grafana' "http://$OITC_GRAFANA_URL/api/datasources/name/Graphite" -XGET -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -I 2>/dev/null | head -n 1 | cut -d$' ' -f2)
if [ "$DS_STATUSCODE" == "404" ]; then
    echo "Create Graphite as default Datasource for Grafana"
    RESPONSE=$(curl --noproxy 'grafana' "http://$OITC_GRAFANA_URL/api/datasources" -XPOST -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -d '{
      "name":"Graphite",
      "type":"graphite",
      "url":"http://graphite-web:8080",
      "access":"proxy",
      "basicAuth":false,
      "isDefault": true,
      "jsonData": {
        "graphiteVersion": 1.1
      }
    }')
    echo $RESPONSE | jq .
fi
echo "Ok: Graphite datasource exists."

echo "Check if Prometheus/VictoriaMetrics Datasource exists in Grafana"
DS_STATUSCODE=$(curl --noproxy 'grafana' "http://$OITC_GRAFANA_URL/api/datasources/name/Prometheus" -XGET -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -I 2>/dev/null | head -n 1 | cut -d$' ' -f2)
if [ "$DS_STATUSCODE" == "404" ]; then
    echo "Create Prometheus/VictoriaMetrics Datasource for Grafana"
    RESPONSE=$(curl --noproxy 'grafana' "http://$OITC_GRAFANA_URL/api/datasources" -XPOST -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -d '{
      "name":"Prometheus",
      "type":"prometheus",
      "url":"http://victoriametrics:8428",
      "access":"proxy",
      "basicAuth":false,
      "isDefault": false,
      "jsonData": {}
    }')
    echo $RESPONSE | jq .
fi
echo "Ok: Prometheus/VictoriaMetrics datasource exists."

if [ -f /opt/openitc/etc/grafana/api_key ]; then
    echo "Check for Grafana Configuration in openITCOCKPIT database"
    API_KEY=$(cat /opt/openitc/etc/grafana/api_key)
    set +e
    COUNT=$(mysql "--defaults-extra-file=$INIFILE" -e "SELECT COUNT(*) FROM grafana_configurations;" -B -s 2>/dev/null)
    if [ "$?" == 0 ] && [ "$COUNT" == 0 ]; then
        echo "Create missing default configuration."
        # This is different from the default SETUP.sh!
        mysql --defaults-extra-file=${INIFILE} -e "INSERT INTO grafana_configurations (api_url, api_key, graphite_prefix, use_https, use_proxy, ignore_ssl_certificate, dashboard_style, created, modified) VALUES('${OITC_GRAFANA_HOSTNAME}', '${API_KEY}', 'openitcockpit', 1, 0, 1, 'light', '2018-12-05 08:42:55', '2018-12-05 08:42:55');"
    fi
    set -e
fi

echo "---------------------------------------------------------------"
echo "Scan and import ACL objects. This will take a while..."
oitc Acl.acl_extras aco_sync


oitc compress

oitc setup --fast

oitc nagios_export

if [[ -d "/opt/openitc/nagios/rollout" ]]; then
    if [[ ! -f "/opt/openitc/nagios/rollout/resource.cfg" ]]; then
        ln -s /opt/openitc/nagios/etc/resource.cfg /opt/openitc/nagios/rollout/resource.cfg
    fi
fi

echo "Enabling webserver configuration"
ln -s /etc/nginx/sites-available/openitc /etc/nginx/sites-enabled/openitc
rm -f /etc/nginx/sites-enabled/default

#systemctl restart\
# statusengine.service\
# nagios.service\
# nginx.service\
# sudo_server.service\
# oitc_cmd.service\
# gearman_worker.service\
# push_notification.service\
# openitcockpit-node.service\
# oitc_cronjobs.timer

#echo "Detected PHP Version: ${PHPVersion} try to restart php-fpm"
#
#set +e
#systemctl is-enabled --quiet php${PHPVersion}-fpm.service &>/dev/null
#RC=$?
#if [ $RC -eq 0 ]; then
#    #Is it php7.3-fpm-service ?
#    systemctl restart php${PHPVersion}-fpm.service
#else
#    # Is it just php-fpm.service?
#    systemctl is-enabled --quiet php-fpm.service
#    RC=$?
#    if [ $RC -eq 0 ]; then
#        systemctl restart php-fpm.service
#    else
#        echo "ERROR: could not detect php-fpm systemd service file. You need to restart php-fpm manualy"
#    fi
#fi
#set -e

#Set default permissions, check for always allowed permissions and dependencies
oitc roles --enable-defaults --admin

date > /opt/openitc/etc/.installation_done

#/opt/openitc/frontend/UPDATE.sh

