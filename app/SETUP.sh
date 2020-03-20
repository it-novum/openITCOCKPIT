#!/bin/bash

INIFILE=/etc/openitcockpit/mysql.cnf

ln -s /etc/openitcockpit/nagios.cfg /opt/openitc/nagios/etc/nagios.cfg
sudo -g www-data /usr/share/openitcockpit/app/Console/cake schema update -y --connection default --file schema_itcockpit.php -s 26
#sudo -g www-data /usr/share/openitcockpit/app/Console/cake schema update --plugin NagiosModule --file ndo.php --connection default

echo "---------------------------------------------------------------"
echo "Create new WebSocket Key"
WEBSOCKET_KEY=$(php -r "echo bin2hex(openssl_random_pseudo_bytes(80, \$cstrong));")
mysql "--defaults-extra-file=${INIFILE}" -e "UPDATE systemsettings SET \`systemsettings\`.\`value\`='${WEBSOCKET_KEY}' WHERE \`key\`='SUDO_SERVER.API_KEY';"

oitc AclExtras.AclExtras aco_sync
oitc compress
oitc nagios_export --all

CODENAME=$(lsb_release -sc)
if [ $CODENAME = "jessie" ] || [ $CODENAME = "xenial" ] || [ $CODENAME = "bionic" ] || [ $CODENAME = "stretch" ]; then
    systemctl restart nagios
    systemctl restart sudo_server
    systemctl restart push_notification
fi

if [ $CODENAME = "trusty" ]; then
    service nagios restart
fi

sudo -g www-data /usr/share/openitcockpit/app/Console/cake setup

oitc docu_generator
oitc roles
