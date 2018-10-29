<?php

class NagiosModuleSchema extends CakeSchema {

    //Command to create this file:
    //oitc schema generate -f --plugin NagiosModule --file ndo.php --connection default

    //Command to import to db
    //oitc schema update --plugin NagiosModule --file ndo.php --connection default

    public function before($event = []) {
        return true;
    }

    public function after($event = []) {
    }

    public $nagios_acknowledgements = [
        'acknowledgement_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'entry_time'           => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'entry_time_usec'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'acknowledgement_type' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'state'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'author_name'          => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment_data'         => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_sticky'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'persistent_comment'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_contacts'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'              => [
            'PRIMARY'    => ['column' => 'acknowledgement_id', 'unique' => 1],
            'entry_time' => ['column' => 'entry_time', 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Current and historical host and service acknowledgements'],
    ];

    public $nagios_commands = [
        'command_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'command_line'    => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 511, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY'     => ['column' => 'command_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'object_id', 'config_type'], 'unique' => 1],
            'object_id'   => ['column' => 'object_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Command definitions'],
    ];

    public $nagios_commenthistory = [
        'commenthistory_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'entry_time'          => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'entry_time_usec'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'comment_type'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'entry_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'comment_time'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'internal_comment_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'author_name'         => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment_data'        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_persistent'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'comment_source'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'expires'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'expiration_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'deletion_time'       => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'deletion_time_usec'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'             => [
            'PRIMARY'     => ['column' => 'commenthistory_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'comment_time', 'internal_comment_id'], 'unique' => 1],
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical host and service comments'],
    ];

    public $nagios_comments = [
        'comment_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'entry_time'          => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'entry_time_usec'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'comment_type'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'entry_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'comment_time'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'internal_comment_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'author_name'         => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment_data'        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_persistent'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'comment_source'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'expires'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'expiration_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'indexes'             => [
            'PRIMARY'     => ['column' => 'comment_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'comment_time', 'internal_comment_id'], 'unique' => 1],
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $nagios_configfiles = [
        'configfile_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'configfile_type' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'configfile_path' => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY'     => ['column' => 'configfile_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'configfile_type', 'configfile_path'], 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Configuration files'],
    ];

    public $nagios_configfilevariables = [
        'configfilevariable_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'configfile_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'varname'               => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'varvalue'              => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'               => [
            'PRIMARY' => ['column' => 'configfilevariable_id', 'unique' => 1],
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Configuration file variables'],
    ];

    public $nagios_conninfo = [
        'conninfo_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'agent_name'        => ['type' => 'string', 'null' => false, 'length' => 32, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'agent_version'     => ['type' => 'string', 'null' => false, 'length' => 8, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'disposition'       => ['type' => 'string', 'null' => false, 'length' => 16, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'connect_source'    => ['type' => 'string', 'null' => false, 'length' => 16, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'connect_type'      => ['type' => 'string', 'null' => false, 'length' => 16, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'connect_time'      => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'disconnect_time'   => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_checkin_time' => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'data_start_time'   => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'data_end_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'bytes_processed'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'lines_processed'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'entries_processed' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'           => [
            'PRIMARY' => ['column' => 'conninfo_id', 'unique' => 1],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'NDO2DB daemon connection information'],
    ];

    public $nagios_contact_addresses = [
        'contact_address_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contact_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'address_number'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'address'            => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'            => [
            'PRIMARY'    => ['column' => 'contact_address_id', 'unique' => 1],
            'contact_id' => ['column' => ['contact_id', 'address_number'], 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Contact addresses'],
    ];

    public $nagios_contact_notificationcommands = [
        'contact_notificationcommand_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contact_id'                     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'notification_type'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'command_object_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_args'                   => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                        => [
            'PRIMARY'    => ['column' => 'contact_notificationcommand_id', 'unique' => 1],
            'contact_id' => ['column' => ['contact_id', 'notification_type', 'command_object_id'], 'unique' => 1],
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Contact host and service notification commands'],
    ];

    public $nagios_contactgroup_members = [
        'contactgroup_member_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contactgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'contact_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'contactgroup_member_id', 'unique' => 1],
            'instance_id' => ['column' => ['contactgroup_id', 'contact_object_id'], 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Contactgroup members'],
    ];

    public $nagios_contactgroups = [
        'contactgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contactgroup_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'alias'                  => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'contactgroup_object_id'], 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Contactgroup definitions'],
    ];

    /*
    public $nagios_contactnotificationmethods = [
        'contactnotificationmethod_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contactnotification_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'start_time'                   => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'primary'],
        'start_time_usec'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_time'                     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_object_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_args'                 => ['type' => 'string', 'null' => true, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                      => [
            'PRIMARY'    => ['column' => ['contactnotificationmethod_id', 'start_time'], 'unique' => 1],
            'start_time' => ['column' => 'start_time', 'unique' => 0],
        ],
        'tableParameters'              => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical record of contact notification methods'],
    ];
    */

    /*
    public $nagios_contactnotifications = [
        'contactnotification_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notification_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'contact_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'start_time'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'primary'],
        'start_time_usec'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_time'               => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                => [
            'PRIMARY'    => ['column' => ['contactnotification_id', 'start_time'], 'unique' => 1],
            'start_time' => ['column' => 'start_time', 'unique' => 0],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical record of contact notifications'],
    ];
    */

    public $nagios_contacts = [
        'contact_id'                    => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contact_object_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'alias'                         => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'email_address'                 => ['type' => 'string', 'null' => true, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'pager_address'                 => ['type' => 'string', 'null' => true, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'host_timeperiod_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'service_timeperiod_object_id'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'host_notifications_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'service_notifications_enabled' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'can_submit_commands'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_service_recovery'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_service_warning'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_service_unknown'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_service_critical'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_service_flapping'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_service_downtime'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_host_recovery'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_host_down'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_host_unreachable'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_host_flapping'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_host_downtime'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'minimum_importance'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                       => [
            'PRIMARY'     => ['column' => 'contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'contact_object_id'], 'unique' => 1],
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Contact definitions'],
    ];

    public $nagios_contactstatus = [
        'contactstatus_id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contact_object_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'unique'],
        'status_update_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'host_notifications_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'service_notifications_enabled' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_host_notification'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_service_notification'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'modified_attributes'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'modified_host_attributes'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'modified_service_attributes'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                       => [
            'PRIMARY'           => ['column' => 'contactstatus_id', 'unique' => 1],
            'contact_object_id' => ['column' => 'contact_object_id', 'unique' => 1],
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Contact status'],
    ];

    public $nagios_customvariables = [
        'customvariable_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'config_type'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'has_been_modified' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'varname'           => ['type' => 'string', 'null' => false, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'varvalue'          => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'           => [
            'PRIMARY'     => ['column' => 'customvariable_id', 'unique' => 1],
            'object_id_2' => ['column' => ['object_id', 'config_type', 'varname'], 'unique' => 1],
            'varname'     => ['column' => 'varname', 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Custom variables'],
    ];

    public $nagios_customvariablestatus = [
        'customvariablestatus_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'status_update_time'      => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'has_been_modified'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'varname'                 => ['type' => 'string', 'null' => false, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'varvalue'                => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                 => [
            'PRIMARY'     => ['column' => 'customvariablestatus_id', 'unique' => 1],
            'object_id_2' => ['column' => ['object_id', 'varname'], 'unique' => 1],
            'varname'     => ['column' => 'varname', 'unique' => 0],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Custom variable status information'],
    ];

    public $nagios_dbversion = [
        'name'            => ['type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'version'         => ['type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [

        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $nagios_downtimehistory = [
        'downtimehistory_id'     => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'downtime_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'entry_time'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'author_name'            => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment_data'           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'internal_downtime_id'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'triggered_by_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'is_fixed'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'duration'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'scheduled_start_time'   => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'scheduled_end_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'was_started'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'actual_start_time'      => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'actual_start_time_usec' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'actual_end_time'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'actual_end_time_usec'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'was_cancelled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'downtimehistory_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'object_id', 'entry_time', 'internal_downtime_id'], 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical scheduled host and service downtime'],
    ];

    public $nagios_eventhandlers = [
        'eventhandler_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'eventhandler_type' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'state'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'state_type'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'start_time'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'start_time_usec'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_time'          => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_args'      => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_line'      => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeout'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'early_timeout'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'execution_time'    => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'return_code'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'output'            => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'       => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'           => [
            'PRIMARY'     => ['column' => 'eventhandler_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'object_id', 'start_time', 'start_time_usec'], 'unique' => 1],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical host and service event handlers'],
    ];

    public $nagios_externalcommands = [
        'externalcommand_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'entry_time'         => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'command_type'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'command_name'       => ['type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_args'       => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'            => [
            'PRIMARY' => ['column' => 'externalcommand_id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical record of processed external commands'],
    ];

    public $nagios_flappinghistory = [
        'flappinghistory_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'event_time'           => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'event_time_usec'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'event_type'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'reason_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flapping_type'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'percent_state_change' => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'low_threshold'        => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'high_threshold'       => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'comment_time'         => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'internal_comment_id'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'              => [
            'PRIMARY' => ['column' => 'flappinghistory_id', 'unique' => 1],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Current and historical record of host and service flapping'],
    ];

    public $nagios_host_contactgroups = [
        'host_contactgroup_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_id'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'contactgroup_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'host_contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['host_id', 'contactgroup_object_id'], 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Host contact groups'],
    ];

    public $nagios_host_contacts = [
        'host_contact_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'host_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'contact_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'           => [
            'PRIMARY'     => ['column' => 'host_contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'host_id', 'contact_object_id'], 'unique' => 1],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $nagios_host_parenthosts = [
        'host_parenthost_id'    => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_id'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'parent_host_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'               => [
            'PRIMARY'     => ['column' => 'host_parenthost_id', 'unique' => 1],
            'instance_id' => ['column' => ['host_id', 'parent_host_object_id'], 'unique' => 1],
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Parent hosts'],
    ];

    /*
    public $nagios_hostchecks = [
        'hostcheck_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'host_object_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'check_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'is_raw_check'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'current_check_attempt' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'max_check_attempts'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'state'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'state_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'start_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'primary'],
        'start_time_usec'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_time'              => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_args'          => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_line'          => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeout'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'early_timeout'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'execution_time'        => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'latency'               => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'return_code'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'output'                => ['type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'long_output'           => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'perfdata'              => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'               => [
            'PRIMARY'     => ['column' => ['hostcheck_id', 'start_time'], 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'host_object_id', 'start_time', 'start_time_usec'], 'unique' => 1],
            'start_time'  => ['column' => 'start_time', 'unique' => 0],
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical host checks'],
    ];
    */

    public $nagios_hostdependencies = [
        'hostdependency_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'dependent_host_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'dependency_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'inherits_parent'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'timeperiod_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'fail_on_up'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'fail_on_down'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'fail_on_unreachable'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'                  => [
            'PRIMARY'     => ['column' => 'hostdependency_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'host_object_id', 'dependent_host_object_id', 'dependency_type', 'inherits_parent', 'fail_on_up', 'fail_on_down', 'fail_on_unreachable'], 'unique' => 1],
        ],
        'tableParameters'          => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Host dependency definitions'],
    ];

    public $nagios_hostescalation_contactgroups = [
        'hostescalation_contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'hostescalation_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'contactgroup_object_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                        => [
            'PRIMARY'     => ['column' => 'hostescalation_contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['hostescalation_id', 'contactgroup_object_id'], 'unique' => 1],
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Host escalation contact groups'],
    ];

    public $nagios_hostescalation_contacts = [
        'hostescalation_contact_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'hostescalation_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'contact_object_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                   => [
            'PRIMARY'     => ['column' => 'hostescalation_contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'hostescalation_id', 'contact_object_id'], 'unique' => 1],
        ],
        'tableParameters'           => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $nagios_hostescalations = [
        'hostescalation_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_object_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'timeperiod_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'first_notification'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_notification'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notification_interval'   => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'escalate_on_recovery'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'escalate_on_down'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'escalate_on_unreachable' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'                 => [
            'PRIMARY'     => ['column' => 'hostescalation_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'host_object_id', 'timeperiod_object_id', 'first_notification', 'last_notification'], 'unique' => 1],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Host escalation definitions'],
    ];

    public $nagios_hostgroup_members = [
        'hostgroup_member_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'hostgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'host_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'             => [
            'PRIMARY'     => ['column' => 'hostgroup_member_id', 'unique' => 1],
            'instance_id' => ['column' => ['hostgroup_id', 'host_object_id'], 'unique' => 1],
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Hostgroup members'],
    ];

    public $nagios_hostgroups = [
        'hostgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'hostgroup_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'alias'               => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'             => [
            'PRIMARY'     => ['column' => 'hostgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'hostgroup_object_id'], 'unique' => 1],
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Hostgroup definitions'],
    ];

    public $nagios_hosts = [
        'host_id'                           => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_object_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'alias'                             => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'display_name'                      => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'address'                           => ['type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'check_command_args'                => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'eventhandler_command_args'         => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'notification_timeperiod_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'check_timeperiod_object_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'failure_prediction_options'        => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_interval'                    => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'retry_interval'                    => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'max_check_attempts'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'first_notification_delay'          => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'notification_interval'             => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'notify_on_down'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_on_unreachable'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_on_recovery'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_on_flapping'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_on_downtime'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'stalk_on_up'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'stalk_on_down'                     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'stalk_on_unreachable'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_enabled'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_on_up'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_on_down'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_on_unreachable'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'low_flap_threshold'                => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'high_flap_threshold'               => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'process_performance_data'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'freshness_checks_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'freshness_threshold'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
        'passive_checks_enabled'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'event_handler_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'active_checks_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'retain_status_information'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'retain_nonstatus_information'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notifications_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'obsess_over_host'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'failure_prediction_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notes'                             => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'notes_url'                         => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'action_url'                        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'icon_image'                        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'icon_image_alt'                    => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'vrml_image'                        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'statusmap_image'                   => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'have_2d_coords'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'x_2d'                              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'y_2d'                              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'have_3d_coords'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'x_3d'                              => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'y_3d'                              => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'z_3d'                              => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'importance'                        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                           => [
            'PRIMARY'        => ['column' => 'host_id', 'unique' => 1],
            'instance_id'    => ['column' => ['instance_id', 'config_type', 'host_object_id'], 'unique' => 1],
            'host_object_id' => ['column' => 'host_object_id', 'unique' => 0],
        ],
        'tableParameters'                   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Host definitions'],
    ];

    public $nagios_hoststatus = [
        'hoststatus_id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'host_object_id'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'unique'],
        'status_update_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'output'                        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'long_output'                   => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'perfdata'                      => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'current_state'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'has_been_checked'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'should_be_scheduled'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'current_check_attempt'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'max_check_attempts'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_check'                    => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'next_check'                    => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'check_type'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'last_state_change'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'last_hard_state_change'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_hard_state'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_time_up'                  => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_time_down'                => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_time_unreachable'         => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'state_type'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'last_notification'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'next_notification'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'no_more_notifications'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notifications_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'problem_has_been_acknowledged' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'acknowledgement_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'current_notification_number'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'passive_checks_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'active_checks_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'event_handler_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'flap_detection_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'is_flapping'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'percent_state_change'          => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'latency'                       => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'execution_time'                => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'scheduled_downtime_depth'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'failure_prediction_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'process_performance_data'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'obsess_over_host'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'modified_host_attributes'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'event_handler'                 => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command'                 => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'normal_check_interval'         => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'retry_check_interval'          => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'check_timeperiod_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                       => [
            'PRIMARY'                       => ['column' => 'hoststatus_id', 'unique' => 1],
            'object_id'                     => ['column' => 'host_object_id', 'unique' => 1],
            'instance_id'                   => ['column' => 'instance_id', 'unique' => 0],
            'status_update_time'            => ['column' => 'status_update_time', 'unique' => 0],
            'current_state'                 => ['column' => 'current_state', 'unique' => 0],
            'check_type'                    => ['column' => 'check_type', 'unique' => 0],
            'state_type'                    => ['column' => 'state_type', 'unique' => 0],
            'last_state_change'             => ['column' => 'last_state_change', 'unique' => 0],
            'notifications_enabled'         => ['column' => 'notifications_enabled', 'unique' => 0],
            'problem_has_been_acknowledged' => ['column' => 'problem_has_been_acknowledged', 'unique' => 0],
            'active_checks_enabled'         => ['column' => 'active_checks_enabled', 'unique' => 0],
            'passive_checks_enabled'        => ['column' => 'passive_checks_enabled', 'unique' => 0],
            'event_handler_enabled'         => ['column' => 'event_handler_enabled', 'unique' => 0],
            'flap_detection_enabled'        => ['column' => 'flap_detection_enabled', 'unique' => 0],
            'is_flapping'                   => ['column' => 'is_flapping', 'unique' => 0],
            'percent_state_change'          => ['column' => 'percent_state_change', 'unique' => 0],
            'latency'                       => ['column' => 'latency', 'unique' => 0],
            'execution_time'                => ['column' => 'execution_time', 'unique' => 0],
            'scheduled_downtime_depth'      => ['column' => 'scheduled_downtime_depth', 'unique' => 0],
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Current host status information'],
    ];

    public $nagios_instances = [
        'instance_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false, 'key' => 'primary'],
        'instance_name'        => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'instance_description' => ['type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'              => [
            'PRIMARY' => ['column' => 'instance_id', 'unique' => 1],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Location names of various Nagios installations'],
    ];

    /*
    public $nagios_logentries = [
        'logentry_id'             => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'logentry_time'           => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'entry_time'              => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'primary'],
        'entry_time_usec'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'logentry_type'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'logentry_data'           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'realtime_data'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'inferred_data_extracted' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'                 => [
            'PRIMARY'       => ['column' => ['logentry_id', 'entry_time'], 'unique' => 1],
            'instance_id'   => ['column' => ['instance_id', 'logentry_time', 'entry_time', 'entry_time_usec'], 'unique' => 1],
            'logentry_time' => ['column' => 'logentry_time', 'unique' => 0],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical record of log entries'],
    ];
    */

    /*
    public $nagios_notifications = [
        'notification_id'     => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'notification_type'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notification_reason' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'start_time'          => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'primary'],
        'start_time_usec'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'state'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'output'              => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'         => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'escalated'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contacts_notified'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'             => [
            'PRIMARY'     => ['column' => ['notification_id', 'start_time'], 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'object_id', 'start_time', 'start_time_usec'], 'unique' => 1],
            'top10'       => ['column' => ['object_id', 'start_time', 'contacts_notified'], 'unique' => 0],
            'start_time'  => ['column' => 'start_time', 'unique' => 0],
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical record of host and service notifications'],
    ];
    */

    public $nagios_objects = [
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'objecttype_id'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'name1'           => ['type' => 'string', 'null' => false, 'length' => 128, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name2'           => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_active'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'         => [
            'PRIMARY'       => ['column' => 'object_id', 'unique' => 1],
            'objecttype_id' => ['column' => ['objecttype_id', 'name1', 'name2'], 'unique' => 0],
            'achmet'        => ['column' => ['name1', 'name2'], 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Current and historical objects of all kinds'],
    ];

    public $nagios_processevents = [
        'processevent_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'event_type'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'event_time'      => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'event_time_usec' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'process_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'program_name'    => ['type' => 'string', 'null' => false, 'length' => 16, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'program_version' => ['type' => 'string', 'null' => false, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'program_date'    => ['type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'processevent_id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical Nagios process events'],
    ];

    public $nagios_programstatus = [
        'programstatus_id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'unique'],
        'status_update_time'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'program_start_time'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'program_end_time'               => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'is_currently_running'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'process_id'                     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'daemon_mode'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_command_check'             => ['type' => 'datetime', 'null' => true, 'default' => '0000-00-00 00:00:00'],
        'last_log_rotation'              => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'notifications_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'active_service_checks_enabled'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'passive_service_checks_enabled' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'active_host_checks_enabled'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'passive_host_checks_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'event_handlers_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'failure_prediction_enabled'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'process_performance_data'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'obsess_over_hosts'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'obsess_over_services'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'modified_host_attributes'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'modified_service_attributes'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'global_host_event_handler'      => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'global_service_event_handler'   => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                        => [
            'PRIMARY'     => ['column' => 'programstatus_id', 'unique' => 1],
            'instance_id' => ['column' => 'instance_id', 'unique' => 1],
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Current program status information'],
    ];

    public $nagios_runtimevariables = [
        'runtimevariable_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'varname'            => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'varvalue'           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'            => [
            'PRIMARY'     => ['column' => 'runtimevariable_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'varname'], 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Runtime variables from the Nagios daemon'],
    ];

    public $nagios_scheduleddowntime = [
        'scheduleddowntime_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'downtime_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'entry_time'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'author_name'            => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment_data'           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'internal_downtime_id'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'triggered_by_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'is_fixed'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'duration'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'scheduled_start_time'   => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'scheduled_end_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'was_started'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'actual_start_time'      => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'actual_start_time_usec' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'scheduleddowntime_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'object_id', 'entry_time', 'internal_downtime_id'], 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Current scheduled host and service downtime'],
    ];

    public $nagios_service_contactgroups = [
        'service_contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'service_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'contactgroup_object_id'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                 => [
            'PRIMARY'     => ['column' => 'service_contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['service_id', 'contactgroup_object_id'], 'unique' => 1],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Service contact groups'],
    ];

    public $nagios_service_contacts = [
        'service_contact_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'service_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'contact_object_id'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'            => [
            'PRIMARY'     => ['column' => 'service_contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'service_id', 'contact_object_id'], 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $nagios_service_parentservices = [
        'service_parentservice_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'service_id'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'parent_service_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                  => [
            'PRIMARY'     => ['column' => 'service_parentservice_id', 'unique' => 1],
            'instance_id' => ['column' => ['service_id', 'parent_service_object_id'], 'unique' => 1],
        ],
        'tableParameters'          => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Parent services'],
    ];

    /*
    public $nagios_servicechecks = [
        'servicecheck_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'service_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'check_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'current_check_attempt' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'max_check_attempts'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'state'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'state_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'start_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'primary'],
        'start_time_usec'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_time'              => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_args'          => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_line'          => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeout'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'early_timeout'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'execution_time'        => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'latency'               => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'return_code'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'output'                => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'           => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'perfdata'              => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'               => [
            'PRIMARY'           => ['column' => ['servicecheck_id', 'start_time'], 'unique' => 1],
            'start_time'        => ['column' => 'start_time', 'unique' => 0],
            'instance_id'       => ['column' => 'instance_id', 'unique' => 0],
            'service_object_id' => ['column' => 'service_object_id', 'unique' => 0],
            'start_time_2'      => ['column' => 'start_time', 'unique' => 0],
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical service checks'],
    ];
    */

    public $nagios_servicedependencies = [
        'servicedependency_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'service_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'dependent_service_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'dependency_type'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'inherits_parent'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'timeperiod_object_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'fail_on_ok'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'fail_on_warning'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'fail_on_unknown'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'fail_on_critical'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'                     => [
            'PRIMARY'     => ['column' => 'servicedependency_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'service_object_id', 'dependent_service_object_id', 'dependency_type', 'inherits_parent', 'fail_on_ok', 'fail_on_warning', 'fail_on_unknown', 'fail_on_critical'], 'unique' => 1],
        ],
        'tableParameters'             => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Service dependency definitions'],
    ];

    public $nagios_serviceescalation_contactgroups = [
        'serviceescalation_contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'serviceescalation_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'contactgroup_object_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                           => [
            'PRIMARY'     => ['column' => 'serviceescalation_contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['serviceescalation_id', 'contactgroup_object_id'], 'unique' => 1],
        ],
        'tableParameters'                   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Service escalation contact groups'],
    ];

    public $nagios_serviceescalation_contacts = [
        'serviceescalation_contact_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'serviceescalation_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'contact_object_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                      => [
            'PRIMARY'     => ['column' => 'serviceescalation_contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'serviceescalation_id', 'contact_object_id'], 'unique' => 1],
        ],
        'tableParameters'              => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $nagios_serviceescalations = [
        'serviceescalation_id'  => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'service_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'timeperiod_object_id'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'first_notification'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_notification'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notification_interval' => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'escalate_on_recovery'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'escalate_on_warning'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'escalate_on_unknown'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'escalate_on_critical'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'               => [
            'PRIMARY'     => ['column' => 'serviceescalation_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'service_object_id', 'timeperiod_object_id', 'first_notification', 'last_notification'], 'unique' => 1],
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Service escalation definitions'],
    ];

    public $nagios_servicegroup_members = [
        'servicegroup_member_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'servicegroup_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'service_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'servicegroup_member_id', 'unique' => 1],
            'instance_id' => ['column' => ['servicegroup_id', 'service_object_id'], 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Servicegroup members'],
    ];

    public $nagios_servicegroups = [
        'servicegroup_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'servicegroup_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'alias'                  => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'servicegroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'servicegroup_object_id'], 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Servicegroup definitions'],
    ];

    public $nagios_services = [
        'service_id'                        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_object_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'service_object_id'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'display_name'                      => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'check_command_args'                => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'eventhandler_command_args'         => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'notification_timeperiod_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'check_timeperiod_object_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'failure_prediction_options'        => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_interval'                    => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'retry_interval'                    => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'max_check_attempts'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'first_notification_delay'          => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'notification_interval'             => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'notify_on_warning'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_on_unknown'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_on_critical'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_on_recovery'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_on_flapping'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_on_downtime'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'stalk_on_ok'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'stalk_on_warning'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'stalk_on_unknown'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'stalk_on_critical'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'is_volatile'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_enabled'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_on_ok'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_on_warning'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_on_unknown'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_on_critical'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'low_flap_threshold'                => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'high_flap_threshold'               => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'process_performance_data'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'freshness_checks_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
        'freshness_threshold'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'passive_checks_enabled'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'event_handler_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'active_checks_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'retain_status_information'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'retain_nonstatus_information'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notifications_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'obsess_over_service'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'failure_prediction_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notes'                             => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'notes_url'                         => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'action_url'                        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'icon_image'                        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'icon_image_alt'                    => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'importance'                        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                           => [
            'PRIMARY'           => ['column' => 'service_id', 'unique' => 1],
            'instance_id'       => ['column' => ['instance_id', 'config_type', 'service_object_id'], 'unique' => 1],
            'service_object_id' => ['column' => 'service_object_id', 'unique' => 0],
        ],
        'tableParameters'                   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Service definitions'],
    ];

    public $nagios_servicestatus = [
        'servicestatus_id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'service_object_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'unique'],
        'status_update_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'output'                        => ['type' => 'string', 'null' => false, 'length' => 512, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'                   => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'perfdata'                      => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'current_state'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'has_been_checked'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'should_be_scheduled'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'current_check_attempt'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'max_check_attempts'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_check'                    => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'next_check'                    => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'check_type'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'last_state_change'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'last_hard_state_change'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_hard_state'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_time_ok'                  => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_time_warning'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_time_unknown'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_time_critical'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'state_type'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'last_notification'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'next_notification'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'no_more_notifications'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notifications_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'problem_has_been_acknowledged' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'acknowledgement_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'current_notification_number'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'passive_checks_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'active_checks_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'event_handler_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'flap_detection_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'is_flapping'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'percent_state_change'          => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'latency'                       => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'execution_time'                => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'scheduled_downtime_depth'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'failure_prediction_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'process_performance_data'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'obsess_over_service'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'modified_service_attributes'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'event_handler'                 => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command'                 => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'normal_check_interval'         => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'retry_check_interval'          => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'check_timeperiod_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                       => [
            'PRIMARY'                       => ['column' => 'servicestatus_id', 'unique' => 1],
            'object_id'                     => ['column' => 'service_object_id', 'unique' => 1],
            'instance_id'                   => ['column' => 'instance_id', 'unique' => 0],
            'status_update_time'            => ['column' => 'status_update_time', 'unique' => 0],
            'current_state'                 => ['column' => 'current_state', 'unique' => 0],
            'check_type'                    => ['column' => 'check_type', 'unique' => 0],
            'state_type'                    => ['column' => 'state_type', 'unique' => 0],
            'last_state_change'             => ['column' => 'last_state_change', 'unique' => 0],
            'notifications_enabled'         => ['column' => 'notifications_enabled', 'unique' => 0],
            'problem_has_been_acknowledged' => ['column' => 'problem_has_been_acknowledged', 'unique' => 0],
            'active_checks_enabled'         => ['column' => 'active_checks_enabled', 'unique' => 0],
            'passive_checks_enabled'        => ['column' => 'passive_checks_enabled', 'unique' => 0],
            'event_handler_enabled'         => ['column' => 'event_handler_enabled', 'unique' => 0],
            'flap_detection_enabled'        => ['column' => 'flap_detection_enabled', 'unique' => 0],
            'is_flapping'                   => ['column' => 'is_flapping', 'unique' => 0],
            'percent_state_change'          => ['column' => 'percent_state_change', 'unique' => 0],
            'latency'                       => ['column' => 'latency', 'unique' => 0],
            'execution_time'                => ['column' => 'execution_time', 'unique' => 0],
            'scheduled_downtime_depth'      => ['column' => 'scheduled_downtime_depth', 'unique' => 0],
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Current service status information'],
    ];

    /*
    public $nagios_statehistory = [
        'statehistory_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'state_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'primary'],
        'state_time_usec'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'object_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'state_change'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'state'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'state_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'current_check_attempt' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'max_check_attempts'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_state'            => ['type' => 'integer', 'null' => false, 'default' => '-1', 'length' => 6, 'unsigned' => false],
        'last_hard_state'       => ['type' => 'integer', 'null' => false, 'default' => '-1', 'length' => 6, 'unsigned' => false],
        'output'                => ['type' => 'string', 'null' => true, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'           => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'               => [
            'PRIMARY' => ['column' => ['statehistory_id', 'state_time'], 'unique' => 1],
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical host and service state changes'],
    ];
    */

    public $nagios_systemcommands = [
        'systemcommand_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'start_time'       => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'start_time_usec'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_time'         => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_line'     => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeout'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'early_timeout'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'execution_time'   => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'return_code'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'output'           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'      => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'          => [
            'PRIMARY'     => ['column' => 'systemcommand_id', 'unique' => 1],
            'instance_id' => ['column' => 'instance_id', 'unique' => 0],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical system commands that are executed'],
    ];

    public $nagios_timedeventqueue = [
        'timedeventqueue_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'event_type'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'queued_time'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'queued_time_usec'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'scheduled_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'recurring_event'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'indexes'            => [
            'PRIMARY'        => ['column' => 'timedeventqueue_id', 'unique' => 1],
            'instance_id'    => ['column' => 'instance_id', 'unique' => 0],
            'event_type'     => ['column' => 'event_type', 'unique' => 0],
            'scheduled_time' => ['column' => 'scheduled_time', 'unique' => 0],
            'object_id'      => ['column' => 'object_id', 'unique' => 0],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Current Nagios event queue'],
    ];

    public $nagios_timedevents = [
        'timedevent_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'event_type'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'queued_time'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'queued_time_usec'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'event_time'         => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'event_time_usec'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'scheduled_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'recurring_event'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'deletion_time'      => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'deletion_time_usec' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'            => [
            'PRIMARY'        => ['column' => 'timedevent_id', 'unique' => 1],
            'instance_id'    => ['column' => 'instance_id', 'unique' => 0],
            'event_type'     => ['column' => 'event_type', 'unique' => 0],
            'scheduled_time' => ['column' => 'scheduled_time', 'unique' => 0],
            'object_id'      => ['column' => 'object_id', 'unique' => 0],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical events from the Nagios event queue'],
    ];

    public $nagios_timeperiod_timeranges = [
        'timeperiod_timerange_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'timeperiod_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'day'                     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'start_sec'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_sec'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                 => [
            'PRIMARY'     => ['column' => 'timeperiod_timerange_id', 'unique' => 1],
            'instance_id' => ['column' => ['timeperiod_id', 'day', 'start_sec', 'end_sec'], 'unique' => 1],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Timeperiod definitions'],
    ];

    public $nagios_timeperiods = [
        'timeperiod_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'timeperiod_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'alias'                => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'              => [
            'PRIMARY'     => ['column' => 'timeperiod_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'timeperiod_object_id'], 'unique' => 1],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Timeperiod definitions'],
    ];

}
