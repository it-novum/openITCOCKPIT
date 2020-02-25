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

echo "Copy required system files"
cp -r ${APPDIR}/system/etc/. /etc/
cp -r ${APPDIR}/system/lib/. /lib/
cp -r ${APPDIR}/system/fpm/. /etc/php/${PHPVersion}/fpm/
cp -r ${APPDIR}/system/usr/. /usr/
chmod +x /usr/bin/oitc

echo "Enable new systemd services"
systemctl daemon-reload
systemctl enable sudo_server
systemctl enable oitc_cmd
systemctl enable gearman_worker
systemctl enable push_notification
systemctl enable nodejs_server
systemctl enable openitcockpit-graphing.service

if [[ ! -f "$INIFILE" ]]; then
    echo "Create local MySQL configuration and database"

    if [[ -f "$DEBIANCNF" ]]; then
        echo "Detected Debian based distribution"

        # set sql_mode to enable group by like it was in the good old times :)
        mysql --defaults-extra-file=${DEBIANCNF} -e "SET GLOBAL sql_mode = '';"

        mysql --defaults-extra-file=${DEBIANCNF} -e "CREATE USER '${MYSQL_DATABASE}'@'localhost' IDENTIFIED BY '${MYSQL_PASSWORD}';" -B
        mysql --defaults-extra-file=${DEBIANCNF} -e "CREATE DATABASE IF NOT EXISTS \`${MYSQL_DATABASE}\` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_swedish_ci;" -B
        mysql --defaults-extra-file=${DEBIANCNF} -e "GRANT ALL PRIVILEGES ON \`${MYSQL_DATABASE}\`.* TO '\`${MYSQL_DATABASE}\`'@'localhost';" -B

        echo "# Automatically generated for openITCOCKPIT scripts. DO NOT TOUCH!" >$INIFILE
        echo "[client]" >>$INIFILE
        echo "database = ${MYSQL_DATABASE}" >>$INIFILE
        echo "host = localhost" >>$INIFILE
        echo "user = ${MYSQL_USER}" >>$INIFILE
        echo "password = ${MYSQL_PASSWORD}" >>$INIFILE
        echo "port = 3306" >>$INIFILE

        echo " # Automatically generated for mysqldump. DO NOT TOUCH!" >$DUMPINIFILE
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

    else
        echo "Unsupported distribution or $DEBIANCNF is missing!"
        exit 1
    fi

fi

echo "---------------------------------------------------------------"
echo "Import openITCOCKPIT Core database schema"
oitc migrations migrate

oitc config_generator_shell --generate

echo "---------------------------------------------------------------"
echo "Load Statusengine DB Schema"
STATUSENGINE_VERSION=$(oitc StatusengineVersion)
if [[ $STATUSENGINE_VERSION == "Statusengine2" ]]; then
    cp -r ${APPDIR}/system/Statusengine2/legacy_schema_innodb.php /opt/openitc/statusengine2/cakephp/app/Plugin/Legacy/Config/Schema/legacy_schema.php
    chmod +x /opt/openitc/statusengine2/cakephp/app/Console/cake
    /opt/openitc/statusengine2/cakephp/app/Console/cake schema update --plugin Legacy --file legacy_schema_innodb.php --connection legacy --yes
fi

echo "---------------------------------------------------------------"
echo "Load default database"
oitc migrations seed

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
        STATUSCODE=$(NO_PROXY="127.0.0.1" curl 'http://127.0.0.1:3033/api/admin/stats' -XGET -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -I 2>/dev/null | head -n 1 | cut -d$' ' -f2)

        if [ "$STATUSCODE" == "200" ]; then
            API_KEY=$(NO_PROXY="127.0.0.1" curl 'http://127.0.0.1:3033/api/auth/keys' -XPOST -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -d '{"role":"Editor","name":"openITCOCKPIT"}' | jq -r '.key')
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
DS_STATUSCODE=$(NO_PROXY="127.0.0.1" curl 'http://127.0.0.1:3033/api/datasources/name/Graphite' -XGET -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -I 2>/dev/null | head -n 1 | cut -d$' ' -f2)
if [ "$DS_STATUSCODE" == "404" ]; then
    echo "Create Graphite as default Datasource for Grafana"
    export NO_PROXY="127.0.0.1"
    RESPONSE=$(NO_PROXY="127.0.0.1" curl 'http://127.0.0.1:3033/api/datasources' -XPOST -uadmin:$ADMIN_PASSWORD -H 'Content-Type: application/json' -d '{
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

if [ -f /opt/openitc/etc/grafana/api_key ]; then
    echo "Check for Grafana Configuration in openITCOCKPIT database"
    API_KEY=$(cat /opt/openitc/etc/grafana/api_key)
    set +e
    COUNT=$(mysql "--defaults-extra-file=$INIFILE" -e "SELECT COUNT(*) FROM grafana_configurations;" -B -s 2>/dev/null)
    if [ "$?" == 0 ] && [ "$COUNT" == 0 ]; then
        echo "Create missing default configuration."
        $(mysql "--defaults-extra-file=$INIFILE" -e "INSERT INTO grafana_configurations (api_url, api_key, graphite_prefix, use_https, use_proxy, ignore_ssl_certificate, dashboard_style, created, modified) VALUES('grafana.docker', '$API_KEY', 'openitcockpit', 1, 0, 1, 'light', '2018-12-05 08:42:55', '2018-12-05 08:42:55');" -B -s 2>/dev/null)
    fi
    set -e
fi

echo "---------------------------------------------------------------"
echo "Scan and import ACL objects. This will take a while..."
oitc Acl.acl_extras aco_sync

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

#oitc compress

oitc setup

oitc nagios_export

CODENAME=$(lsb_release -sc)
if [ $CODENAME = "jessie" ] || [ $CODENAME = "xenial" ] || [ $CODENAME = "bionic" ] || [ $CODENAME = "stretch" ]; then
    systemctl restart statusengine
    systemctl restart nagios
    systemctl restart nginx

    systemctl restart sudo_server
    systemctl restart oitc_cmd
    systemctl restart gearman_worker
    systemctl restart push_notification
    systemctl restart nodejs_server
fi

if [ $CODENAME = "trusty" ]; then
    service statusengine restart
    service nagios restart
    service nginx restart

    service sudo_server stop
    service sudo_server start

    service oitc_cmd restart

    service gearman_worker stop
    service gearman_worker stop

    service push_notification restart
    service nodejs_server restart
fi

echo "Detected PHP Version: ${PHPVersion} try to restart php-fpm"

systemctl is-enabled --quiet php${PHPVersion}-fpm.service
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

#Set default permissions, check for always allowed permissions and dependencies
oitc roles --enable-defaults --admin

chown -R www-data:www-data /opt/openitc/frontend
