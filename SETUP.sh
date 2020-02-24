#!/bin/bash

if ! [ $(id -u) = 0 ]; then
    echo "You need to run this script as root user or via sudo!"
    exit 1
fi

echo "Create oitc command"
echo '#!/bin/bash' > /usr/bin/oitc
echo 'sudo -g www-data /opt/openitc/frontend/bin/cake "$@"' >> /usr/bin/oitc

chmod +x /usr/bin/oitc

APPDIR="/usr/share/openitcockpit/app/cake4"

INIFILE=/opt/openitc/etc/mysql/mysql.cnf
DUMPINIFILE=/opt/openitc/etc/mysql/dump.cnf

DEBIANCNF=/etc/mysql/debian.cnf

MYSQL_USER="openitcockpit"
MYSQL_DATABASE="openitcockpit"
MYSQL_PASSWORD=$(pwgen -s -1 16)

if [[ ! -f "$INIFILE" ]]; then
    echo "Create MySQL configuration and database"

    if [[ -f "$DEBIANCNF" ]]; then
        echo "Detected Debian based distribution"

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

    else
        echo "Unsupported distribution or $DEBIANCNF is missing!"
        exit 1
    fi

fi

oitc config_generator_shell --generate

#ln -s /etc/openitcockpit/nagios.cfg /opt/openitc/nagios/etc/nagios.cfg
#sudo -g www-data /usr/share/openitcockpit/app/Console/cake schema update -y --connection default --file schema_itcockpit.php -s 26

echo "---------------------------------------------------------------"
echo "Import openITCOCKPIT Core database schema"
oitc migrations migrate

echo "---------------------------------------------------------------"
echo "Scan and import ACL objects. This will take a while..."
oitc Acl.acl_extras aco_sync

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

#oitc compress

oitc setup

oitc nagios_export

CODENAME=$(lsb_release -sc)
if [ $CODENAME = "jessie" ] || [ $CODENAME = "xenial" ] || [ $CODENAME = "bionic" ] || [ $CODENAME = "stretch" ]; then
    systemctl restart nagios
    systemctl restart sudo_server
fi

if [ $CODENAME = "trusty" ]; then
    service nagios restart
    service sudo_server stop
    service sudo_server start
fi

#Set default permissions, check for always allowed permissions and dependencies
oitc roles --enable-defaults --admin
