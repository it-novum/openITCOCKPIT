<?php

class AppSchema extends CakeSchema {

    public $connection = 'default';

    public function before($event = []) {
        return true;
    }

    public function after($event = []) {
    }

    /*
     * Generate a new Snapshot:
     * Console/cake schema generate -s --file schema_itcockpit_X.php --connection default --exclude 'nagios_acknowledgements,nagios_commands,nagios_commenthistory,nagios_comments,nagios_configfiles,nagios_configfilevariables,nagios_conninfo,nagios_contact_addresses,nagios_contact_notificationcommands,nagios_contactgroup_members,nagios_contactgroups,nagios_contactnotificationmethods,nagios_contactnotifications,nagios_contacts,nagios_contactstatus,nagios_customvariables,nagios_customvariablestatus,nagios_dbversion,nagios_downtimehistory,nagios_eventhandlers,nagios_externalcommands,nagios_flappinghistory,nagios_host_contactgroups,nagios_host_contacts,nagios_host_parenthosts,nagios_hostchecks,nagios_hostdependencies,nagios_hostescalation_contactgroups,nagios_hostescalation_contacts,nagios_hostescalations,nagios_hostgroup_members,nagios_hostgroups,nagios_hosts,nagios_hoststatus,nagios_instances,nagios_logentries,nagios_notifications,nagios_objects,nagios_processevents,nagios_programstatus,nagios_runtimevariables,nagios_scheduleddowntime,nagios_service_contactgroups,nagios_service_contacts,nagios_service_parentservices,nagios_servicechecks,nagios_servicedependencies,nagios_serviceescalation_contactgroups,nagios_serviceescalation_contacts,nagios_serviceescalations,nagios_servicegroup_members,nagios_servicegroups,nagios_services,nagios_servicestatus,nagios_statehistory,nagios_systemcommands,nagios_timedeventqueue,nagios_timedevents,nagios_timeperiod_timeranges,nagios_timeperiods'
     */

    /*
     * Generate update SQL statements
     * sudo -g www-data Console/cake schema update --connection default --file schema_itcockpit.php -s 2  (2 is the number of the snapshot)
     */

    /*
     * Generate SQL Dump:
     * Console/cake schema dump --file schema_itcockpit.php
     */

    public $contacts = [
        'id'                            => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                          => ['type' => 'string', 'null' => false, 'length' => 37],
        'name'                          => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'                   => ['type' => 'string', 'null' => false, 'length' => 255],
        'email'                         => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'phone'                         => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'host_timeperiod_id'            => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'service_timeperiod_id'         => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'host_notifications_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'service_notifications_enabled' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'can_submit_commands'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_recovery'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_warning'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_unknown'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_critical'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_flapping'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_downtime'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_recovery'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_down'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_unreachable'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_flapping'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_downtime'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'indexes'                       => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'length' => 37],
        'container_id'    => ['type' => 'integer', 'null' => false],
        'description'     => ['type' => 'string', 'null' => false, 'length' => 255],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];


    public $contacts_to_containers = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false],
        'container_id'    => ['type' => 'integer', 'null' => false],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_contactgroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false],
        'contactgroup_id' => ['type' => 'integer', 'null' => false],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hostcommands = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false],
        'command_id'      => ['type' => 'integer', 'null' => false],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_servicecommands = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false],
        'command_id'      => ['type' => 'integer', 'null' => false],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hostgroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'description'     => ['type' => 'string', 'null' => false, 'length' => 255],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $containertypes = [
        'id'                           => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'                         => ['type' => 'string', 'null' => false, 'length' => 255],
        'can_contain_contacts'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'can_contain_hosts'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'can_contain_timeperiods'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'can_contain_hosttemplates'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'can_contain_servicetemplates' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'tableParameters'              => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $containers = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'containertype_id' => ['type' => 'integer', 'null' => false],
        'name'             => ['type' => 'string', 'null' => false, 'length' => 255],
        'parent_id'        => ['type' => 'integer', 'null' => true],
        'lft'              => ['type' => 'integer', 'null' => false],
        'rght'             => ['type' => 'integer', 'null' => false],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_containers = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'         => ['type' => 'integer', 'null' => false],
        'container_id'    => ['type' => 'integer', 'null' => false],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_hostgroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'         => ['type' => 'integer', 'null' => false],
        'hostgroup_id'    => ['type' => 'integer', 'null' => false],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosttemplates = [
        'id'                             => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                           => ['type' => 'string', 'null' => false, 'length' => 37],
        'container_id'                   => ['type' => 'integer', 'null' => false],
        'name'                           => ['type' => 'string', 'null' => false, 'length' => 255],
        'check_period_id'                => ['type' => 'integer', 'null' => false, 'length' => 11],
        'notify_period_id'               => ['type' => 'integer', 'null' => false, 'length' => 11],
        'description'                    => ['type' => 'string', 'null' => false, 'length' => 255],
        'command_id'                     => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_command_args'             => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'eventhandler_command_args'      => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeperiod_id'                  => ['type' => 'integer', 'null' => false, 'length' => 11],
        'check_interval'                 => ['type' => 'integer', 'null' => false, 'default' => 1, 'length' => 5],
        'retry_interval'                 => ['type' => 'integer', 'null' => false, 'default' => 3, 'length' => 5],
        'max_check_attempts'             => ['type' => 'integer', 'null' => false, 'default' => 1, 'length' => 3],
        'first_notification_delay'       => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notification_interval'          => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notify_on_down'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_unreachable'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_recovery'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_flapping'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_downtime'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_up'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_down'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_unreachable'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'low_flap_threshold'             => ['type' => 'float', 'null' => false, 'default' => '0'],
        'high_flap_threshold'            => ['type' => 'float', 'null' => false, 'default' => '0'],
        'process_performance_data'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_checks_enabled'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_threshold'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8],
        'passive_checks_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'event_handler_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'active_checks_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'retain_status_information'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'retain_nonstatus_information'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notifications_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notes'                          => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'tags'                           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'priority'                       => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 2],
        'created'                        => ['type' => 'datetime', 'null' => false],
        'modified'                       => ['type' => 'datetime', 'null' => false],
        'indexes'                        => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];


    public $hosts = [
        'id'                             => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                           => ['type' => 'string', 'null' => false, 'length' => 37],
        'container_id'                   => ['type' => 'integer', 'null' => false],
        'name'                           => ['type' => 'string', 'null' => false, 'length' => 255],
        'description'                    => ['type' => 'string', 'null' => false, 'length' => 255],
        'hosttemplate_id'                => ['type' => 'integer', 'null' => false, 'length' => 11],
        'address'                        => ['type' => 'string', 'null' => false, 'length' => 128],
        'check_command_object_id'        => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_command_args'             => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'eventhandler_command_args'      => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeperiod_id'                  => ['type' => 'integer', 'null' => false, 'length' => 11],
        'check_interval'                 => ['type' => 'integer', 'null' => false, 'default' => 1, 'length' => 5],
        'retry_interval'                 => ['type' => 'integer', 'null' => false, 'default' => 3, 'length' => 5],
        'max_check_attempts'             => ['type' => 'integer', 'null' => false, 'default' => 1, 'length' => 3],
        'first_notification_delay'       => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notification_interval'          => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notify_on_down'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_unreachable'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_recovery'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_flapping'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_downtime'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_up'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_down'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_unreachable'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'low_flap_threshold'             => ['type' => 'float', 'null' => false, 'default' => '0'],
        'high_flap_threshold'            => ['type' => 'float', 'null' => false, 'default' => '0'],
        'process_performance_data'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_checks_enabled'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_threshold'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8],
        'passive_checks_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'event_handler_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'active_checks_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'retain_status_information'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'retain_nonstatus_information'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notifications_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notes'                          => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'priority'                       => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 2],
        'indexes'                        => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicetemplates = [
        'id'                             => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                           => ['type' => 'string', 'null' => false, 'length' => 37],
        'name'                           => ['type' => 'string', 'null' => false, 'length' => 255],
        'check_period_id'                => ['type' => 'integer', 'null' => false, 'length' => 11],
        'notify_period_id'               => ['type' => 'integer', 'null' => false, 'length' => 11],
        'description'                    => ['type' => 'string', 'null' => false, 'length' => 255],
        'command_id'                     => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_command_args'             => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'checkcommand_info'              => ['type' => 'string', 'null' => false, 'length' => 255],
        'eventhandler_command_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'eventhandler_command_args'      => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeperiod_id'                  => ['type' => 'integer', 'null' => false, 'length' => 11],
        'check_interval'                 => ['type' => 'integer', 'null' => false, 'default' => 1, 'length' => 5],
        'retry_interval'                 => ['type' => 'integer', 'null' => false, 'default' => 3, 'length' => 5],
        'max_check_attempts'             => ['type' => 'integer', 'null' => false, 'default' => 1, 'length' => 3],
        'first_notification_delay'       => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notification_interval'          => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notify_on_warning'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_unknown'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_critical'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_recovery'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_flapping'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_downtime'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_ok'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_warning'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_unknown'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_critical'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'low_flap_threshold'             => ['type' => 'float', 'null' => false, 'default' => '0'],
        'high_flap_threshold'            => ['type' => 'float', 'null' => false, 'default' => '0'],
        'process_performance_data'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_checks_enabled'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_threshold'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8],
        'passive_checks_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'event_handler_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'active_checks_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'retain_status_information'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'retain_nonstatus_information'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notifications_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notes'                          => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'tags'                           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'priority'                       => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 2],
        'indexes'                        => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $services = [
        'id'                                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                              => ['type' => 'string', 'null' => false, 'length' => 37],
        'servicetemplate_id'                => ['type' => 'integer', 'null' => false, 'length' => 11],
        'host_id'                           => ['type' => 'integer', 'null' => false, 'length' => 11],
        'name'                              => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'                       => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_command_args'                => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'eventhandler_command_args'         => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'notification_timeperiod_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_timeperiod_object_id'        => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_interval'                    => ['type' => 'float', 'null' => false, 'default' => '0'],
        'retry_interval'                    => ['type' => 'float', 'null' => false, 'default' => '0'],
        'max_check_attempts'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'first_notification_delay'          => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notification_interval'             => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notify_on_warning'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_unknown'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_critical'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_recovery'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_flapping'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_downtime'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'is_volatile'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_enabled'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_ok'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_warning'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_unknown'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_critical'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'low_flap_threshold'                => ['type' => 'float', 'null' => false, 'default' => '0'],
        'high_flap_threshold'               => ['type' => 'float', 'null' => false, 'default' => '0'],
        'process_performance_data'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_checks_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8],
        'freshness_threshold'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'passive_checks_enabled'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'event_handler_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'active_checks_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notifications_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notes'                             => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                           => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'                   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicetemplates_to_containers = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false],
        'container_id'       => ['type' => 'integer', 'null' => false],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];


    public $timeperiods = [
        'id'              => ['type' => 'integer', 'null' => false, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'length' => 37],
        'container_id'    => ['type' => 'integer', 'null' => false, 'length' => 6],
        'name'            => ['type' => 'string', 'null' => false, 'length' => 255],
        'description'     => ['type' => 'string', 'length' => 255],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $timeperiod_timeranges = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'timeperiod_id'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 11],
        'day'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'start_sec'       => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'end_sec'         => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $tenants = [
        'id'              => ['type' => 'integer', 'null' => false, 'key' => 'primary'],
        'container_id'    => ['type' => 'integer', 'null' => false],
        'description'     => ['type' => 'string', 'length' => 255],
        'is_active'       => ['type' => 'integer', 'null' => false, 'length' => 1],
        'max_users'       => ['type' => 'integer', 'null' => false, 'length' => 6],
        'max_hosts'       => ['type' => 'integer', 'null' => false, 'length' => 6],
        'max_services'    => ['type' => 'integer', 'null' => false, 'length' => 6],
        'number_users'    => ['type' => 'integer', 'null' => false, 'length' => 6],
        'number_hosts'    => ['type' => 'integer', 'null' => false, 'length' => 6],
        'number_services' => ['type' => 'integer', 'null' => false, 'length' => 6],
        'firstname'       => ['type' => 'string', 'length' => 255],
        'lastname'        => ['type' => 'string', 'length' => 255],
        'street'          => ['type' => 'string', 'length' => 255],
        'zipcode'         => ['type' => 'integer', 'length' => 6],
        'city'            => ['type' => 'string', 'length' => 255],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];


    public $commands = [
        'id'              => ['type' => 'integer', 'null' => false, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'length' => 37],
        'name'            => ['type' => 'string', 'length' => 255],
        'command_line'    => ['type' => 'text'],
        'command_type'    => ['type' => 'integer', 'null' => false, 'length' => 1],
        'human_args'      => ['type' => 'text'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $locations = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'length' => 37],
        'container_id'    => ['type' => 'integer', 'null' => false],
        'description'     => ['type' => 'string', 'null' => false, 'length' => 255],
        'latitude'        => ['type' => 'float', 'default' => 0],
        'longitude'       => ['type' => 'float', 'default' => 0],
        'timezone'        => ['type' => 'string', 'null' => false, 'length' => 255],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_hosttemplates = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id' => ['type' => 'integer', 'null' => false],
        'hosttemplate_id' => ['type' => 'integer', 'null' => false],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hosts = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false],
        'host_id'         => ['type' => 'integer', 'null' => false],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_hosts = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id' => ['type' => 'integer', 'null' => false],
        'host_id'         => ['type' => 'integer', 'null' => false],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hosttemplates = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false],
        'hosttemplate_id' => ['type' => 'integer', 'null' => false],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_servicetemplates = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'         => ['type' => 'integer', 'null' => false],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_servicetemplates = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id'    => ['type' => 'integer', 'null' => false],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $customvariables = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'object_id'       => ['type' => 'integer', 'null' => false],
        'objecttype_id'   => ['type' => 'integer', 'null' => false],
        'name'            => ['type' => 'string', 'null' => false, 'length' => 255],
        'value'           => ['type' => 'string', 'null' => false, 'length' => 255],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];
    public $hostescalations = [
        'id'                      => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'timeperiod_id'           => ['type' => 'integer', 'null' => false],
        'first_notification'      => ['type' => 'integer', 'null' => false, 'length' => 6],
        'last_notification'       => ['type' => 'integer', 'null' => false, 'length' => 6],
        'notification_interval'   => ['type' => 'integer', 'null' => false, 'length' => 6],
        'escalate_on_recovery'    => ['type' => 'integer', 'null' => false, 'length' => 1],
        'escalate_on_down'        => ['type' => 'integer', 'null' => false, 'length' => 1],
        'escalate_on_unreachable' => ['type' => 'integer', 'null' => false, 'length' => 1],
        'indexes'                 => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $macros = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => false, 'length' => 10],
        'value'           => ['type' => 'string', 'null' => false],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
    ];

    public $commandarguments = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'command_id'      => ['type' => 'integer', 'null' => false],
        'name'            => ['type' => 'string', 'null' => false, 'length' => 10],
        'human_name'      => ['type' => 'string', 'null' => false, 'length' => 255],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
    ];

    public $hostcommandargumentvalues = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false],
        'host_id'            => ['type' => 'integer', 'null' => false],
        'value'              => ['type' => 'string', 'null' => false, 'length' => 500],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
    ];

    public $hosttemplatecommandargumentvalues = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false],
        'hosttemplate_id'    => ['type' => 'integer', 'null' => false],
        'value'              => ['type' => 'string', 'null' => false, 'length' => 500],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
    ];

    public $servicecommandargumentvalues = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false],
        'service_id'         => ['type' => 'integer', 'null' => false],
        'value'              => ['type' => 'string', 'null' => false, 'length' => 500],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
    ];

    public $servicetemplatecommandargumentvalues = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false],
        'value'              => ['type' => 'string', 'null' => false, 'length' => 500],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
    ];

    public $contacts_to_hostescalations = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'        => ['type' => 'integer', 'null' => false],
        'hostescalation_id' => ['type' => 'integer', 'null' => false],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_hostescalations = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id'   => ['type' => 'integer', 'null' => false],
        'hostescalation_id' => ['type' => 'integer', 'null' => false],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];
}
