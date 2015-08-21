#!/bin/bash
ln -s /etc/openitcockpit/nagios.cfg /opt/openitc/nagios/etc/nagios.cfg
sudo -g www-data /usr/share/openitcockpit/app/Console/cake schema update --connection default --file schema_itcockpit.php -s 26
oitc AclExtras.AclExtras aco_sync
oitc compress
oitc nagios_export --all
service nagios restart
sudo -g www-data /usr/share/openitcockpit/app/Console/cake setup
