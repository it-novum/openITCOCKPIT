#!/bin/bash

if ! [ $(id -u) = 0 ]; then
    echo "You need to run this script as root user or via sudo!"
    exit 1
fi

APPDIR="/opt/openitc/frontend"

INIFILE=/opt/openitc/etc/mysql/mysql.cnf
DUMPINIFILE=/opt/openitc/etc/mysql/dump.cnf
BASHCONF=/opt/openitc/etc/mysql/bash.conf

DEBIANCNF=/etc/mysql/debian.cnf

MYSQL_USER="openitcockpit"
MYSQL_DATABASE="openitcockpit"
MYSQL_PASSWORD=$(pwgen -s -1 16)

PHPVersion=$(php -r "echo substr(PHP_VERSION, 0, 3);")

OSVERSION=$(grep VERSION_CODENAME /etc/os-release | cut -d= -f2)
OS_BASE="debian"

if [[ -f "/etc/redhat-release" ]]; then
    echo "Detected RedHat based operating system."
    OS_BASE="RHEL"
    OSVERSION=$(source /etc/os-release && echo $VERSION_ID | cut -d. -f1) # e.g. 8
fi

if [[ "$OS_BASE" == "RHEL" ]]; then
    echo "Make RedHat look more like it's Debian ;)"

    # sudo - allow root to use sudo
    # sudo is needed by the "oitc" command
    usermod -aG wheel root
    echo "root    ALL=(ALL:ALL) ALL" > /etc/sudoers.d/openitcockpit
    chmod 0440 /etc/sudoers.d/openitcockpit

    # php-fpm
    mkdir -p "/etc/php/${PHPVersion}/fpm"
    mkdir -p /run/php

    mkdir -p /etc/systemd/system/php-fpm.service.d/
    echo "[Service]" > /etc/systemd/system/php-fpm.service.d/override.conf
    echo "# Create folder for openITCOCKPIT default php-fpm socket path" >> /etc/systemd/system/php-fpm.service.d/override.conf
    echo "ExecStartPre=-mkdir -p /run/php" >> /etc/systemd/system/php-fpm.service.d/override.conf


    # Link RedHat config to where they are on Debian
    ln -s /etc/php-fpm.conf "/etc/php/${PHPVersion}/fpm/php-fpm.conf"
    ln -s /etc/php.d "/etc/php/${PHPVersion}/fpm/conf.d"
    ln -s /etc/php-fpm.d "/etc/php/${PHPVersion}/fpm/pool.d"

    # nginx
    if [[ -f "/etc/nginx/nginx.conf" ]]; then
        echo "Move /etc/nginx/nginx.conf to /etc/nginx/nginx.conf.orig"
        mv /etc/nginx/nginx.conf /etc/nginx/nginx.conf.orig
    fi
    mkdir -p /etc/nginx/sites-enabled
    mkdir -p /var/lib/nginx/tmp
    chown www-data:root /var/lib/nginx -R
    chown www-data:root /var/lib/nginx/tmp -R
    cp ${APPDIR}/system/nginx/nginx.rhel${OSVERSION}.conf /etc/nginx/nginx.conf

    # myqsl
    mkdir -p /etc/mysql
    ln -s /etc/my.cnf.d /etc/mysql/conf.d

    # create snakeoil ssl certificate if no exists
    mkdir -p /etc/ssl/private
    if [[ ! -f "/etc/ssl/private/ssl-cert-snakeoil.key" ]]; then
        openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/ssl-cert-snakeoil.key -out /etc/ssl/certs/ssl-cert-snakeoil.pem -subj "/C=US/ST=Denial/L=Springfield/O=Dis/CN=localhost"
        openssl dhparam -out /etc/ssl/certs/dhparam.pem 2048
        cat /etc/ssl/certs/dhparam.pem | tee -a /etc/ssl/certs/ssl-cert-snakeoil.pem
    fi

    systemctl daemon-reload

    systemctl enable\
     nginx.service\
     gearmand.service\
     mysqld.service\
     redis.service\
     docker.service\
     php-fpm.service

    systemctl start\
    nginx.service\
     gearmand.service\
     mysqld.service\
     redis.service\
     docker.service\
     php-fpm.service

fi # End RHEL section

echo "Copy required system files"
rsync -K -a ${APPDIR}/system/etc/. /etc/ # we use rsync because the destination can be a symlink on RHEL
chown root:root /etc
cp -r ${APPDIR}/system/lib/. /lib/
rsync -K -a ${APPDIR}/system/fpm/. /etc/php/${PHPVersion}/fpm/
cp -r ${APPDIR}/system/usr/. /usr/
cp ${APPDIR}/system/nginx/ssl_options_$OSVERSION /etc/nginx/openitc/ssl_options.conf
cp ${APPDIR}/system/nginx/ssl_cert.conf /etc/nginx/openitc/ssl_cert.conf
echo "# This file will NOT be overwritten during an update" >> /etc/nginx/openitc/custom.conf
chmod +x /usr/bin/oitc

echo "Create required system folders"
mkdir -p /opt/openitc/etc/{mysql,grafana,carbon,frontend,nagios,nsta,statusengine} /opt/openitc/etc/statusengine/Config
mkdir -p /opt/openitc/nagios/etc/config
mkdir -p /opt/openitc/etc/nagios/nagios.cfg.d

mkdir -p /opt/openitc/logs/frontend/nagios
chown www-data:www-data /opt/openitc/logs/frontend
chown nagios:nagios /opt/openitc/logs/frontend/nagios
chown nagios:www-data /opt/openitc/nagios/etc/config
chown nagios:www-data /opt/openitc/etc/nagios/nagios.cfg.d
chmod 775 /opt/openitc/logs/frontend
chmod 775 /opt/openitc/logs/frontend/nagios

mkdir -p /opt/openitc/logs/nagios/archives
chown nagios:www-data /opt/openitc/logs/nagios /opt/openitc/logs/nagios/archives
chmod 775 /opt/openitc/logs/nagios /opt/openitc/logs/nagios/archives

chmod u+s /opt/openitc/nagios/libexec/check_icmp
chmod u+s /opt/openitc/nagios/libexec/check_dhcp

mkdir -p /opt/openitc/frontend/tmp/nagios
chown www-data:www-data /opt/openitc/frontend/tmp
chown nagios:nagios /opt/openitc/frontend/tmp/nagios

mkdir -p /opt/openitc/frontend/webroot/img/charts
chown www-data:www-data /opt/openitc/frontend/webroot/img/charts

if [[ -d /opt/openitc/frontend/plugins/MapModule/webroot/img/ ]]; then
    chown -R www-data:www-data /opt/openitc/frontend/plugins/MapModule/webroot/img/
fi

mkdir -p /opt/openitc/var/prometheus
chown nagios:nagios /opt/openitc/var/prometheus
mkdir -p /opt/openitc/var/prometheus/victoria-metrics

echo "Enable new systemd services"
systemctl daemon-reload
systemctl enable\
 sudo_server.service\
 oitc_cmd.service\
 gearman_worker.service\
 push_notification.service\
 openitcockpit-node.service\
 openitcockpit-graphing.service\
 oitc_cronjobs.timer

if [[ ! -f "$INIFILE" ]]; then
    echo "Create local MySQL configuration and database"

    if [[ "$OS_BASE" == "RHEL" ]]; then
        systemctl restart mysqld.service
    else
        # Restart MySQL to load openitcockpit.cnf to disable MySQL Binary Logs on Ubuntu Focal
        systemctl restart mysql.service
    fi
    echo "Waiting 3 seconds for MySQL / MariaDB..."
    sleep 3

    if [[ -f "$DEBIANCNF" ]]; then
        echo "Detected Debian based distribution"

        # set sql_mode to enable group by like it was in the good old times :)
        mysql --defaults-extra-file=${DEBIANCNF} -e "SET GLOBAL sql_mode = '';"

        if [[ "$OS_BASE" == "RHEL" ]]; then
            # Set Password policy to LOW on RHEL systems with MySQL 8
            # https://dev.mysql.com/doc/refman/8.0/en/validate-password-options-variables.html#sysvar_validate_password.policy
            mysql --defaults-extra-file=${DEBIANCNF} -e "SET GLOBAL validate_password.policy=LOW;" -B
        fi

        mysql --defaults-extra-file=${DEBIANCNF} -e "CREATE USER '${MYSQL_DATABASE}'@'localhost' IDENTIFIED BY '${MYSQL_PASSWORD}';" -B
        mysql --defaults-extra-file=${DEBIANCNF} -e "CREATE DATABASE IF NOT EXISTS \`${MYSQL_DATABASE}\` DEFAULT CHARACTER SET utf8mb4 DEFAULT COLLATE utf8mb4_general_ci;" -B
        mysql --defaults-extra-file=${DEBIANCNF} -e "GRANT ALL PRIVILEGES ON \`${MYSQL_DATABASE}\`.* TO '${MYSQL_DATABASE}'@'localhost';" -B

        echo "; Automatically generated for openITCOCKPIT scripts. DO NOT TOUCH!" >$INIFILE
        echo "[client]" >>$INIFILE
        echo "database = ${MYSQL_DATABASE}" >>$INIFILE
        echo "host = localhost" >>$INIFILE
        echo "user = ${MYSQL_USER}" >>$INIFILE
        echo "password = ${MYSQL_PASSWORD}" >>$INIFILE
        echo "port = 3306" >>$INIFILE

        echo "; Automatically generated for mysqldump. DO NOT TOUCH!" >$DUMPINIFILE
        echo "[client]" >>$DUMPINIFILE
        echo "host     = localhost" >>$DUMPINIFILE
        echo "user     = ${MYSQL_USER}" >>$DUMPINIFILE
        echo "password = ${MYSQL_PASSWORD}" >>$DUMPINIFILE
        echo "port     = 3306" >>$DUMPINIFILE

        echo "dbc_dbuser='${MYSQL_USER}'" >$BASHCONF
        echo "dbc_dbpass='${MYSQL_PASSWORD}'" >>$BASHCONF
        echo "dbc_dbserver='127.0.0.1'" >>$BASHCONF
        echo "dbc_dbport='3306'" >>$BASHCONF
        echo "dbc_dbname='${MYSQL_DATABASE}'" >>$BASHCONF

        chown www-data:nagios $INIFILE
        chmod 660 $INIFILE

        chown www-data:nagios $DUMPINIFILE
        chmod 660 $DUMPINIFILE

        chown www-data:nagios $BASHCONF
        chmod 660 $BASHCONF

    else
        echo "Unsupported distribution or $DEBIANCNF is missing!"
        exit 1
    fi

fi

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

STATUSENGINE_VERSION=$(cat /opt/openitc/etc/statusengine/statusengine_version)

if [[ $STATUSENGINE_VERSION == "Statusengine2" ]]; then
    mysql "--defaults-extra-file=${INIFILE}" -e "INSERT INTO \`configuration_files\` (\`config_file\`, \`key\`, \`value\`)VALUES('DbBackend', 'dbbackend', 'Nagios');"
    echo "<?php return ['dbbackend' => 'Nagios',];" > /opt/openitc/frontend/config/dbbackend.php
fi

oitc config_generator_shell --generate

echo "---------------------------------------------------------------"
echo "Load Statusengine DB Schema"
if [[ $STATUSENGINE_VERSION == "Statusengine2" ]]; then
    echo "Setup Statusengine 2"

    cp -r ${APPDIR}/system/Statusengine2/legacy_schema_innodb.php /opt/openitc/statusengine2/cakephp/app/Plugin/Legacy/Config/Schema/legacy_schema_innodb.php
    cp -r ${APPDIR}/system/Statusengine2/database.php /opt/openitc/statusengine2/cakephp/app/Config/database.php
    chmod +x /opt/openitc/statusengine2/cakephp/app/Console/cake
    /opt/openitc/statusengine2/cakephp/app/Console/cake schema update --plugin Legacy --file legacy_schema_innodb.php --connection legacy --yes
fi

if [[ $STATUSENGINE_VERSION == "Statusengine3" ]]; then
    echo "Setup Statusengine 3"

    cp -r ${APPDIR}/system/Statusengine3/mysql.php /opt/openitc/statusengine3/worker/lib/mysql.php

    chmod +x /opt/openitc/statusengine3/worker/bin/Console.php
    chmod +x /opt/openitc/statusengine3/worker/bin/StatusengineWorker.php

    /opt/openitc/statusengine3/worker/bin/Console.php database --update
fi

echo "---------------------------------------------------------------"
echo "Configure Grafana"
systemctl restart openitcockpit-graphing.service

ADMIN_PASSWORD=$(cat /opt/openitc/etc/grafana/admin_password)

if [ ! -f /opt/openitc/etc/grafana/api_key ]; then
    echo "Create new Grafana API Key for openITCOCKPIT"
    COUNTER=0

    set +e
    while [ "$COUNTER" -lt 30 ]; do
        echo "Try to connect to Grafana API..."
        #Is Grafana Server Online?
        STATUSCODE=$(curl --noproxy '127.0.0.1' 'http://127.0.0.1:3033/api/admin/stats' -XGET -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -I 2>/dev/null | head -n 1 | cut -d$' ' -f2)

        if [ "$STATUSCODE" == "200" ]; then
            API_KEY=$(curl --noproxy '127.0.0.1' 'http://127.0.0.1:3033/api/auth/keys' -XPOST -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -d '{"role":"Editor","name":"openITCOCKPIT"}' | jq -r '.key')
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
DS_STATUSCODE=$(curl --noproxy '127.0.0.1' 'http://127.0.0.1:3033/api/datasources/name/Graphite' -XGET -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -I 2>/dev/null | head -n 1 | cut -d$' ' -f2)
if [ "$DS_STATUSCODE" == "404" ]; then
    echo "Create Graphite as default Datasource for Grafana"
    RESPONSE=$(curl --noproxy '127.0.0.1' 'http://127.0.0.1:3033/api/datasources' -XPOST -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -d '{
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
DS_STATUSCODE=$(curl --noproxy '127.0.0.1' 'http://127.0.0.1:3033/api/datasources/name/Prometheus' -XGET -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -I 2>/dev/null | head -n 1 | cut -d$' ' -f2)
if [ "$DS_STATUSCODE" == "404" ]; then
    echo "Create Prometheus/VictoriaMetrics Datasource for Grafana"
    RESPONSE=$(curl --noproxy '127.0.0.1' 'http://127.0.0.1:3033/api/datasources' -XPOST -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -d '{
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
        mysql --defaults-extra-file=${INIFILE} -e "INSERT INTO grafana_configurations (api_url, api_key, graphite_prefix, use_https, use_proxy, ignore_ssl_certificate, dashboard_style, created, modified) VALUES('grafana.docker', '${API_KEY}', 'openitcockpit', 1, 0, 1, 'light', '2018-12-05 08:42:55', '2018-12-05 08:42:55');"
    fi
    set -e
fi

echo "---------------------------------------------------------------"
echo "Scan and import ACL objects. This will take a while..."
oitc Acl.acl_extras aco_sync


oitc compress

oitc setup

oitc nagios_export

if [[ -d "/opt/openitc/nagios/rollout" ]]; then
    if [[ ! -f "/opt/openitc/nagios/rollout/resource.cfg" ]]; then
        ln -s /opt/openitc/nagios/etc/resource.cfg /opt/openitc/nagios/rollout/resource.cfg
    fi
fi

echo "Enabling webserver configuration"
ln -s /etc/nginx/sites-available/openitc /etc/nginx/sites-enabled/openitc
rm -f /etc/nginx/sites-enabled/default

systemctl restart\
 statusengine.service\
 nagios.service\
 nginx.service\
 sudo_server.service\
 oitc_cmd.service\
 gearman_worker.service\
 push_notification.service\
 openitcockpit-node.service\
 oitc_cronjobs.timer

for srv in supervisor.service; do
  if systemctl is-active --quiet $srv; then
    systemctl restart $srv
  fi
done

echo "Detected PHP Version: ${PHPVersion} try to restart php-fpm"

set +e
systemctl is-enabled --quiet php${PHPVersion}-fpm.service &>/dev/null
RC=$?
if [ $RC -eq 0 ]; then
    #Is it php7.3-fpm-service ?
    systemctl restart php${PHPVersion}-fpm.service
else
    # Is it just php-fpm.service?
    systemctl is-enabled --quiet php-fpm.service
    RC=$?
    if [ $RC -eq 0 ]; then
        systemctl restart php-fpm.service
    else
        echo "ERROR: could not detect php-fpm systemd service file. You need to restart php-fpm manualy"
    fi
fi
set -e

#Set default permissions, check for always allowed permissions and dependencies
oitc roles --enable-defaults --admin

date > /opt/openitc/etc/.installation_done

/opt/openitc/frontend/UPDATE.sh

