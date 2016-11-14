#!/bin/bash

. /etc/dbconfig-common/openitcockpit.conf

APPDIR="/usr/share/openitcockpit/app"

echo "Create mysqldump of your current database"
BACKUP_TIMESTAMP=`date '+%Y-%m-%d_%H-%M-%S'`
BACKUP_DIR='/opt/openitc/nagios/backup'
mkdir -p $BACKUP_DIR
#If you have mysql binlog enabled uses this command:
#mysqldump --defaults-extra-file=/etc/mysql/debian.cnf --databases $dbc_dbname --flush-privileges --single-transaction --master-data=1 --flush-logs --triggers --routines --events --hex-blob \
mysqldump --defaults-extra-file=/etc/mysql/debian.cnf --databases $dbc_dbname --flush-privileges --single-transaction --triggers --routines --events --hex-blob \
	--ignore-table=$dbc_dbname.nagios_acknowledgements \
	--ignore-table=$dbc_dbname.nagios_commands \
	--ignore-table=$dbc_dbname.nagios_commenthistory \
	--ignore-table=$dbc_dbname.nagios_comments \
	--ignore-table=$dbc_dbname.nagios_configfiles \
	--ignore-table=$dbc_dbname.nagios_configfilevariables \
	--ignore-table=$dbc_dbname.nagios_conninfo \
	--ignore-table=$dbc_dbname.nagios_contact_addresses \
	--ignore-table=$dbc_dbname.nagios_contact_notificationcommands \
	--ignore-table=$dbc_dbname.nagios_contactgroup_members \
	--ignore-table=$dbc_dbname.nagios_contactgroups \
	--ignore-table=$dbc_dbname.nagios_contactnotificationmethods \
	--ignore-table=$dbc_dbname.nagios_contactnotifications \
	--ignore-table=$dbc_dbname.nagios_contacts \
	--ignore-table=$dbc_dbname.nagios_contactstatus \
	--ignore-table=$dbc_dbname.nagios_customvariables \
	--ignore-table=$dbc_dbname.nagios_customvariablestatus \
	--ignore-table=$dbc_dbname.nagios_dbversion \
	--ignore-table=$dbc_dbname.nagios_downtimehistory \
	--ignore-table=$dbc_dbname.nagios_eventhandlers \
	--ignore-table=$dbc_dbname.nagios_externalcommands \
	--ignore-table=$dbc_dbname.nagios_flappinghistory \
	--ignore-table=$dbc_dbname.nagios_host_contactgroups \
	--ignore-table=$dbc_dbname.nagios_host_contacts \
	--ignore-table=$dbc_dbname.nagios_host_parenthosts \
	--ignore-table=$dbc_dbname.nagios_hostchecks \
	--ignore-table=$dbc_dbname.nagios_hostdependencies \
	--ignore-table=$dbc_dbname.nagios_hostescalation_contactgroups \
	--ignore-table=$dbc_dbname.nagios_hostescalation_contacts \
	--ignore-table=$dbc_dbname.nagios_hostescalations \
	--ignore-table=$dbc_dbname.nagios_hostgroup_members \
	--ignore-table=$dbc_dbname.nagios_hostgroups \
	--ignore-table=$dbc_dbname.nagios_hosts \
	--ignore-table=$dbc_dbname.nagios_hoststatus \
	--ignore-table=$dbc_dbname.nagios_instances \
	--ignore-table=$dbc_dbname.nagios_logentries \
	--ignore-table=$dbc_dbname.nagios_notifications \
	--ignore-table=$dbc_dbname.nagios_processevents \
	--ignore-table=$dbc_dbname.nagios_programstatus \
	--ignore-table=$dbc_dbname.nagios_runtimevariables \
	--ignore-table=$dbc_dbname.nagios_scheduleddowntime \
	--ignore-table=$dbc_dbname.nagios_service_contactgroups \
	--ignore-table=$dbc_dbname.nagios_service_contacts \
	--ignore-table=$dbc_dbname.nagios_service_parentservices \
	--ignore-table=$dbc_dbname.nagios_servicechecks \
	--ignore-table=$dbc_dbname.nagios_servicedependencies \
	--ignore-table=$dbc_dbname.nagios_serviceescalation_contactgroups \
	--ignore-table=$dbc_dbname.nagios_serviceescalation_contacts \
	--ignore-table=$dbc_dbname.nagios_serviceescalations \
	--ignore-table=$dbc_dbname.nagios_servicegroup_members \
	--ignore-table=$dbc_dbname.nagios_servicegroups \
	--ignore-table=$dbc_dbname.nagios_services \
	--ignore-table=$dbc_dbname.nagios_servicestatus \
	--ignore-table=$dbc_dbname.nagios_statehistory \
	--ignore-table=$dbc_dbname.nagios_systemcommands \
	--ignore-table=$dbc_dbname.nagios_timedeventqueue \
	--ignore-table=$dbc_dbname.nagios_timedevents \
	--ignore-table=$dbc_dbname.nagios_timeperiod_timeranges \
	--ignore-table=$dbc_dbname.nagios_timeperiods \
> $BACKUP_DIR/openitcockpit_dump_$BACKUP_TIMESTAMP.sql


sudo -g www-data "${APPDIR}/Console/cake" schema update --connection default --file schema_itcockpit.php -s 26

for PLUGIN in $(ls -1 "${APPDIR}/Plugin"); do
	if [ -f "${APPDIR}/Plugin/${PLUGIN}/Config/Schema/schema.php" ]; then
		sudo -g www-data "${APPDIR}/Console/cake" schema update --connection default --plugin "$PLUGIN" --file schema.php
	fi
done

#Update Containertype from Devicegroup to Node
oitc api --model Containers --action update_container_type --data ""

#Check and create missing cronjobs
oitc api --model Cronjob --action create_missing_cronjobs --data ""

#Compress and minify javascript files
oitc compress

#Acc ALC dependencies config for itc core
oitc AclExtras.AclExtras aco_sync

#Set missing new ACL permissions
#for always sllowed allowd and dependend ALC action
oitc set_permissions

service sudo_server stop
service sudo_server start

service oitc_cmd stop
service oitc_cmd start

service gearman_worker stop
service gearman_worker start

service php5-fpm stop
service php5-fpm start
