#!/bin/bash

. /etc/dbconfig-common/openitcockpit.conf

APPDIR="/usr/share/openitcockpit/app/cake4"

INIFILE=/etc/openitcockpit/mysql.cnf

#ln -s /etc/openitcockpit/nagios.cfg /opt/openitc/nagios/etc/nagios.cfg
#sudo -g www-data /usr/share/openitcockpit/app/Console/cake schema update -y --connection default --file schema_itcockpit.php -s 26

echo "---------------------------------------------------------------"
echo "Import openITCOCKPIT Core database schema"
oitc4 migrations migrate

echo "---------------------------------------------------------------"
echo "Scan and import ACL objects. This will take a while..."
oitc4 Acl.acl_extras aco_sync

echo "---------------------------------------------------------------"
echo "Load default database"
oitc4 migrations seed

echo "Running openITCOCKPIT Module database migration/s"
for PLUGIN in $(ls -1 "${APPDIR}/plugins"); do
    if [[ "$PLUGIN" == *Module ]]; then
        if [[ -d "${APPDIR}/plugins/${PLUGIN}/config/Migrations" ]]; then
            echo "Running openITCOCKPIT ${PLUGIN} database migration"
            oitc4 migrations migrate -p "${PLUGIN}"
        fi

        if [[ -d "${APPDIR}/plugins/${PLUGIN}/config/Seeds" ]]; then
            num_files=$(find "${APPDIR}/plugins/${PLUGIN}/config/Seeds" -mindepth 1 -iname "*.php" -type f | wc -l)
            if [[ "$num_files" -gt 0 ]]; then
                echo "Importing default records for ${PLUGIN} into database"
                oitc4 migrations seed -p "${PLUGIN}"
            fi
        fi

    fi
done

#oitc4 compress

oitc4 setup

oitc4 nagios_export

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
oitc4 roles --enable-defaults --admin
