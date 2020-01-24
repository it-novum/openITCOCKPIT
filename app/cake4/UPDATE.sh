#!/bin/bash
if [[ $1 == "--help" ]]; then
    echo "Supported parameters:"
    echo "--rights         Reset file permissions"
    echo "--cc             Clear model cache"
    echo "--no-restart     Do not restart services at the end of the update"

    exit 0
fi
. /etc/dbconfig-common/openitcockpit.conf

APPDIR="/usr/share/openitcockpit/app/cake4"

INIFILE=/etc/openitcockpit/mysql.cnf

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

#sudo -g www-data "${APPDIR}/Console/cake" schema update -y --connection default --file schema_itcockpit.php -s 26
#
#for PLUGIN in $(ls -1 "${APPDIR}/Plugin"); do
#    if [ -f "${APPDIR}/Plugin/${PLUGIN}/Config/Schema/schema.php" ]; then
#        sudo -g www-data "${APPDIR}/Console/cake" schema update -y --connection default --plugin "$PLUGIN" --file schema.php
#    fi
#done

#Update Containertype from Devicegroup to Node
#oitc api --model Containers --action update_container_type --data ""

#Check and create missing cronjobs
#oitc api --model Cronjob --action create_missing_cronjobs --data ""

#Compress and minify javascript files
#oitc compress

#Acc ALC dependencies config for itc core
echo "---------------------------------------------------------------"
echo "Scan for new user permissions. This will take a while..."
oitc4 Acl.acl_extras aco_sync

#Set default permissions, check for always allowed permissions and dependencies
oitc4 roles --enable-defaults --admin

#Check for browser push notification commands
echo "Check for browser push notification commands"
#oitc api --model Commands --action addByUuid --ignore-errors 1 --data 'host-notify-by-browser-notification' '/usr/share/openitcockpit/app/Console/cake send_push_notification --type Host --notificationtype $NOTIFICATIONTYPE$ --hostuuid "$HOSTNAME$" --state "$HOSTSTATEID$" --output "$HOSTOUTPUT$"  --ackauthor "$NOTIFICATIONAUTHOR$" --ackcomment "$NOTIFICATIONCOMMENT$" --user-id $_CONTACTOITCUSERID$' '3' 'cd13d22e-acd4-4a67-997b-6e120e0d3153' 'Send a host notification to the browser window'
#oitc api --model Commands --action addByUuid --ignore-errors 1 --data 'service-notify-by-browser-notification' '/usr/share/openitcockpit/app/Console/cake send_push_notification --type Service --notificationtype $NOTIFICATIONTYPE$ --hostuuid "$HOSTNAME$" --serviceuuid "$SERVICEDESC$" --state "$SERVICESTATEID$" --output "$SERVICEOUTPUT$" --ackauthor "$NOTIFICATIONAUTHOR$" --ackcomment "$NOTIFICATIONCOMMENT$" --user-id $_CONTACTOITCUSERID$' '3' 'c23255b7-5b1a-40b4-b614-17837dc376af ' 'Send a service notification to the browser window'


#Generate documentation
#oitc docu_generator
#oitc systemsettings_import

NORESTART=false
for i in "$@"; do
    case $i in
        --cc)
            echo "Clear out Model Cache /usr/share/openitcockpit/app/cake4/tmp/cache/models/"
            rm -rf /usr/share/openitcockpit/app/cake4/tmp/cache/models/*
        ;;

        --rights)
            oitc4 rights
        ;;

        --no-restart)
            NORESTART=true
        ;;

        *)
            #No default at the moment
        ;;
    esac
done


echo "Flush redis cache"
redis-cli FLUSHALL
echo ""

if [[ "$NORESTART" = "true" ]]; then
    echo "#########################################"
    echo "# RESTART OF SERVICES MANUALLY DISABLED #"
    echo "#########################################"
    echo ""
    echo "Update successfully finished"
    exit 0;
fi

CODENAME=$(lsb_release -sc)
if [[ "$1" = "install" ]]; then
    if [ $CODENAME = "jessie" ] || [ $CODENAME = "xenial" ] || [ $CODENAME = "bionic" ] || [ $CODENAME = "stretch" ]; then
        systemctl restart gearman_worker
    fi
    echo "Update successfully finished"
else

    if [ $CODENAME = "jessie" ] || [ $CODENAME = "xenial" ] || [ $CODENAME = "bionic" ] || [ $CODENAME = "stretch" ]; then
        systemctl restart oitc_cmd
        systemctl restart gearman_worker
        systemctl restart push_notification
        systemctl restart nodejs_server
    fi

    if [ $CODENAME = "xenial" ] || [ $CODENAME = "stretch" ]; then
        systemctl restart php7.0-fpm
    fi

    if [ $CODENAME = "bionic" ]; then
        systemctl restart php7.2-fpm.service
    fi

    if [ $CODENAME = "jessie" ]; then
        systemctl restart php5-fpm
    fi

fi
