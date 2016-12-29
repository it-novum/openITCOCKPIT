<?php

class AppSchema extends CakeSchema
{

    public $connection = 'nagios';

    public function before($event = [])
    {
        return true;
    }

    public function after($event = [])
    {
    }

    public $acknowledgements = [
        'acknowledgement_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'entry_time'           => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'entry_time_usec'      => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'acknowledgement_type' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'object_id'            => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'state'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'author_name'          => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment_data'         => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_sticky'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'persistent_comment'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_contacts'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'indexes'              => [
            'PRIMARY'    => ['column' => 'acknowledgement_id', 'unique' => 1],
            'entry_time' => ['column' => 'entry_time', 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $commands = [
        'command_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'config_type'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'command_line'    => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 511, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY'     => ['column' => 'command_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'object_id', 'config_type'], 'unique' => 1],
            'object_id'   => ['column' => 'object_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $commenthistory = [
        'commenthistory_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'entry_time'          => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'entry_time_usec'     => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'comment_type'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'entry_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'comment_time'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'internal_comment_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'author_name'         => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment_data'        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_persistent'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'comment_source'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'expires'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'expiration_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'deletion_time'       => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'deletion_time_usec'  => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'             => [
            'PRIMARY'     => ['column' => 'commenthistory_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'comment_time', 'internal_comment_id'], 'unique' => 1],
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $comments = [
        'comment_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'entry_time'          => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'entry_time_usec'     => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'comment_type'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'entry_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'comment_time'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'internal_comment_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'author_name'         => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment_data'        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_persistent'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'comment_source'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'expires'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'expiration_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'indexes'             => [
            'PRIMARY'     => ['column' => 'comment_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'comment_time', 'internal_comment_id'], 'unique' => 1],
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $configfiles = [
        'configfile_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'configfile_type' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'configfile_path' => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY'     => ['column' => 'configfile_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'configfile_type', 'configfile_path'], 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $configfilevariables = [
        'configfilevariable_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'configfile_id'         => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'varname'               => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'varvalue'              => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'               => [
            'PRIMARY' => ['column' => 'configfilevariable_id', 'unique' => 1],
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $conninfo = [
        'conninfo_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
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
        'bytes_processed'   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'lines_processed'   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'entries_processed' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'           => [
            'PRIMARY' => ['column' => 'conninfo_id', 'unique' => 1],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $contact_addresses = [
        'contact_address_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'contact_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'address_number'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'address'            => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'            => [
            'PRIMARY'    => ['column' => 'contact_address_id', 'unique' => 1],
            'contact_id' => ['column' => ['contact_id', 'address_number'], 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $contact_notificationcommands = [
        'contact_notificationcommand_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'contact_id'                     => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'notification_type'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'command_object_id'              => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'command_args'                   => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                        => [
            'PRIMARY'    => ['column' => 'contact_notificationcommand_id', 'unique' => 1],
            'contact_id' => ['column' => ['contact_id', 'notification_type', 'command_object_id'], 'unique' => 1],
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $contactgroup_members = [
        'contactgroup_member_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'contactgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'contact_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'contactgroup_member_id', 'unique' => 1],
            'instance_id' => ['column' => ['contactgroup_id', 'contact_object_id'], 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $contactgroups = [
        'contactgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'config_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'contactgroup_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'alias'                  => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'contactgroup_object_id'], 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $contactnotificationmethods = [
        'contactnotificationmethod_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'contactnotification_id'       => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'start_time'                   => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'start_time_usec'              => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'end_time'                     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'                => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'command_object_id'            => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'command_args'                 => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                      => [
            'PRIMARY'     => ['column' => 'contactnotificationmethod_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'contactnotification_id', 'start_time', 'start_time_usec'], 'unique' => 1],
            'start_time'  => ['column' => 'start_time', 'unique' => 0],
        ],
        'tableParameters'              => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $contactnotifications = [
        'contactnotification_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'notification_id'        => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'contact_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'start_time'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'start_time_usec'        => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'end_time'               => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'          => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'contactnotification_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'contact_object_id', 'start_time', 'start_time_usec'], 'unique' => 1],
            'start_time'  => ['column' => 'start_time', 'unique' => 0],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $contacts = [
        'contact_id'                    => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'config_type'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'contact_object_id'             => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'alias'                         => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'email_address'                 => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'pager_address'                 => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'host_timeperiod_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'service_timeperiod_object_id'  => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'host_notifications_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'service_notifications_enabled' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'can_submit_commands'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_service_recovery'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_service_warning'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_service_unknown'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_service_critical'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_service_flapping'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_service_downtime'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_host_recovery'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_host_down'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_host_unreachable'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_host_flapping'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_host_downtime'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'minimum_importance'            => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                       => [
            'PRIMARY'     => ['column' => 'contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'contact_object_id'], 'unique' => 1],
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $contactstatus = [
        'contactstatus_id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'contact_object_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'unique'],
        'status_update_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'host_notifications_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'service_notifications_enabled' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'last_host_notification'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_service_notification'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'modified_attributes'           => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'modified_host_attributes'      => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'modified_service_attributes'   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                       => [
            'PRIMARY'           => ['column' => 'contactstatus_id', 'unique' => 1],
            'contact_object_id' => ['column' => 'contact_object_id', 'unique' => 1],
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $customvariables = [
        'customvariable_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'object_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'config_type'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'has_been_modified' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'varname'           => ['type' => 'string', 'null' => false, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'varvalue'          => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'           => [
            'PRIMARY'     => ['column' => 'customvariable_id', 'unique' => 1],
            'object_id_2' => ['column' => ['object_id', 'config_type', 'varname'], 'unique' => 1],
            'varname'     => ['column' => 'varname', 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $customvariablestatus = [
        'customvariablestatus_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'object_id'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'status_update_time'      => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'has_been_modified'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'varname'                 => ['type' => 'string', 'null' => false, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'varvalue'                => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                 => [
            'PRIMARY'     => ['column' => 'customvariablestatus_id', 'unique' => 1],
            'object_id_2' => ['column' => ['object_id', 'varname'], 'unique' => 1],
            'varname'     => ['column' => 'varname', 'unique' => 0],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $dbversion = [
        'name'            => ['type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'version'         => ['type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [

        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $downtimehistory = [
        'downtimehistory_id'     => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'downtime_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'object_id'              => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'entry_time'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'author_name'            => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment_data'           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'internal_downtime_id'   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'triggered_by_id'        => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'is_fixed'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'duration'               => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'scheduled_start_time'   => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'scheduled_end_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'was_started'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'actual_start_time'      => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'actual_start_time_usec' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'actual_end_time'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'actual_end_time_usec'   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'was_cancelled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'downtimehistory_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'object_id', 'entry_time', 'internal_downtime_id'], 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $eventhandlers = [
        'eventhandler_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'eventhandler_type' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'object_id'         => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'state'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'state_type'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'start_time'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'start_time_usec'   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'end_time'          => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'     => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'command_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'command_args'      => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_line'      => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeout'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'early_timeout'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'execution_time'    => ['type' => 'float', 'null' => false, 'default' => '0'],
        'return_code'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'output'            => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'       => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'           => [
            'PRIMARY'     => ['column' => 'eventhandler_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'object_id', 'start_time', 'start_time_usec'], 'unique' => 1],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $externalcommands = [
        'externalcommand_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'entry_time'         => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'command_type'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'command_name'       => ['type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_args'       => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'            => [
            'PRIMARY' => ['column' => 'externalcommand_id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $flappinghistory = [
        'flappinghistory_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'event_time'           => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'event_time_usec'      => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'event_type'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'reason_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'flapping_type'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'object_id'            => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'percent_state_change' => ['type' => 'float', 'null' => false, 'default' => '0'],
        'low_threshold'        => ['type' => 'float', 'null' => false, 'default' => '0'],
        'high_threshold'       => ['type' => 'float', 'null' => false, 'default' => '0'],
        'comment_time'         => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'internal_comment_id'  => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'              => [
            'PRIMARY' => ['column' => 'flappinghistory_id', 'unique' => 1],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $host_contactgroups = [
        'host_contactgroup_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'host_id'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'contactgroup_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'host_contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['host_id', 'contactgroup_object_id'], 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $host_contacts = [
        'host_contact_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'host_id'           => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'contact_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'           => [
            'PRIMARY'     => ['column' => 'host_contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'host_id', 'contact_object_id'], 'unique' => 1],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $host_parenthosts = [
        'host_parenthost_id'    => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'host_id'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'parent_host_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'               => [
            'PRIMARY'     => ['column' => 'host_parenthost_id', 'unique' => 1],
            'instance_id' => ['column' => ['host_id', 'parent_host_object_id'], 'unique' => 1],
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $hostchecks = [
        'hostcheck_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'host_object_id'        => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'is_raw_check'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'current_check_attempt' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'max_check_attempts'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'state'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'state_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'start_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'start_time_usec'       => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'end_time'              => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'         => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'command_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'command_args'          => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_line'          => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeout'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'early_timeout'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'execution_time'        => ['type' => 'float', 'null' => false, 'default' => '0'],
        'latency'               => ['type' => 'float', 'null' => false, 'default' => '0'],
        'return_code'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'output'                => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'           => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'perfdata'              => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'               => [
            'PRIMARY'     => ['column' => 'hostcheck_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'host_object_id', 'start_time', 'start_time_usec'], 'unique' => 1],
            'start_time'  => ['column' => 'start_time', 'unique' => 0],
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $hostdependencies = [
        'hostdependency_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'config_type'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'host_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'dependent_host_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'dependency_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'inherits_parent'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'timeperiod_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'fail_on_up'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'fail_on_down'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'fail_on_unreachable'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'indexes'                  => [
            'PRIMARY'     => ['column' => 'hostdependency_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'host_object_id', 'dependent_host_object_id', 'dependency_type', 'inherits_parent', 'fail_on_up', 'fail_on_down', 'fail_on_unreachable'], 'unique' => 1],
        ],
        'tableParameters'          => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $hostescalation_contactgroups = [
        'hostescalation_contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'hostescalation_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'contactgroup_object_id'         => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                        => [
            'PRIMARY'     => ['column' => 'hostescalation_contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['hostescalation_id', 'contactgroup_object_id'], 'unique' => 1],
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $hostescalation_contacts = [
        'hostescalation_contact_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'hostescalation_id'         => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'contact_object_id'         => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                   => [
            'PRIMARY'     => ['column' => 'hostescalation_contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'hostescalation_id', 'contact_object_id'], 'unique' => 1],
        ],
        'tableParameters'           => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $hostescalations = [
        'hostescalation_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'config_type'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'host_object_id'          => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'timeperiod_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'first_notification'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'last_notification'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notification_interval'   => ['type' => 'float', 'null' => false, 'default' => '0'],
        'escalate_on_recovery'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'escalate_on_down'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'escalate_on_unreachable' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'indexes'                 => [
            'PRIMARY'     => ['column' => 'hostescalation_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'host_object_id', 'timeperiod_object_id', 'first_notification', 'last_notification'], 'unique' => 1],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $hostgroup_members = [
        'hostgroup_member_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'hostgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'host_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'             => [
            'PRIMARY'     => ['column' => 'hostgroup_member_id', 'unique' => 1],
            'instance_id' => ['column' => ['hostgroup_id', 'host_object_id'], 'unique' => 1],
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $hostgroups = [
        'hostgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'config_type'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'hostgroup_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'alias'               => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'             => [
            'PRIMARY'     => ['column' => 'hostgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'hostgroup_object_id'], 'unique' => 1],
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $hosts = [
        'host_id'                           => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'config_type'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'host_object_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'alias'                             => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'display_name'                      => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'address'                           => ['type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_command_args'                => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'eventhandler_command_args'         => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'notification_timeperiod_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_timeperiod_object_id'        => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'failure_prediction_options'        => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_interval'                    => ['type' => 'float', 'null' => false, 'default' => '0'],
        'retry_interval'                    => ['type' => 'float', 'null' => false, 'default' => '0'],
        'max_check_attempts'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'first_notification_delay'          => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notification_interval'             => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notify_on_down'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_on_unreachable'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_on_recovery'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_on_flapping'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_on_downtime'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'stalk_on_up'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'stalk_on_down'                     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'stalk_on_unreachable'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'flap_detection_enabled'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'flap_detection_on_up'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'flap_detection_on_down'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'flap_detection_on_unreachable'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'low_flap_threshold'                => ['type' => 'float', 'null' => false, 'default' => '0'],
        'high_flap_threshold'               => ['type' => 'float', 'null' => false, 'default' => '0'],
        'process_performance_data'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_checks_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_threshold'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8],
        'passive_checks_enabled'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'event_handler_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'active_checks_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'retain_status_information'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'retain_nonstatus_information'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notifications_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'obsess_over_host'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'failure_prediction_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notes'                             => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'notes_url'                         => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'action_url'                        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'icon_image'                        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'icon_image_alt'                    => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'vrml_image'                        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'statusmap_image'                   => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'have_2d_coords'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'x_2d'                              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'y_2d'                              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'have_3d_coords'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'x_3d'                              => ['type' => 'float', 'null' => false, 'default' => '0'],
        'y_3d'                              => ['type' => 'float', 'null' => false, 'default' => '0'],
        'z_3d'                              => ['type' => 'float', 'null' => false, 'default' => '0'],
        'importance'                        => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                           => [
            'PRIMARY'        => ['column' => 'host_id', 'unique' => 1],
            'instance_id'    => ['column' => ['instance_id', 'config_type', 'host_object_id'], 'unique' => 1],
            'host_object_id' => ['column' => 'host_object_id', 'unique' => 0],
        ],
        'tableParameters'                   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $hoststatus = [
        'hoststatus_id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'host_object_id'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'unique'],
        'status_update_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'output'                        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'                   => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'perfdata'                      => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'current_state'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'has_been_checked'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'should_be_scheduled'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'current_check_attempt'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'max_check_attempts'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'last_check'                    => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'next_check'                    => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'check_type'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'last_state_change'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'last_hard_state_change'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_hard_state'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'last_time_up'                  => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_time_down'                => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_time_unreachable'         => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'state_type'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'last_notification'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'next_notification'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'no_more_notifications'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notifications_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'problem_has_been_acknowledged' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'acknowledgement_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'current_notification_number'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'passive_checks_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'active_checks_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'event_handler_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'flap_detection_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'is_flapping'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'percent_state_change'          => ['type' => 'float', 'null' => false, 'default' => '0', 'key' => 'index'],
        'latency'                       => ['type' => 'float', 'null' => false, 'default' => '0', 'key' => 'index'],
        'execution_time'                => ['type' => 'float', 'null' => false, 'default' => '0', 'key' => 'index'],
        'scheduled_downtime_depth'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'failure_prediction_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'process_performance_data'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'obsess_over_host'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'modified_host_attributes'      => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'event_handler'                 => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command'                 => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'normal_check_interval'         => ['type' => 'float', 'null' => false, 'default' => '0'],
        'retry_check_interval'          => ['type' => 'float', 'null' => false, 'default' => '0'],
        'check_timeperiod_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0'],
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
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $instances = [
        'instance_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'key' => 'primary'],
        'instance_name'        => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'instance_description' => ['type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'              => [
            'PRIMARY' => ['column' => 'instance_id', 'unique' => 1],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $logentries = [
        'logentry_id'             => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'logentry_time'           => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'entry_time'              => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'entry_time_usec'         => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'logentry_type'           => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'logentry_data'           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'realtime_data'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'inferred_data_extracted' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'indexes'                 => [
            'PRIMARY'       => ['column' => 'logentry_id', 'unique' => 1],
            'instance_id'   => ['column' => ['instance_id', 'logentry_time', 'entry_time', 'entry_time_usec'], 'unique' => 1],
            'logentry_time' => ['column' => 'logentry_time', 'unique' => 0],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $notifications = [
        'notification_id'     => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'notification_type'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notification_reason' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'start_time'          => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'start_time_usec'     => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'end_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'       => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'state'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'output'              => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'         => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'escalated'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'contacts_notified'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'indexes'             => [
            'PRIMARY'     => ['column' => 'notification_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'object_id', 'start_time', 'start_time_usec'], 'unique' => 1],
            'top10'       => ['column' => ['object_id', 'start_time', 'contacts_notified'], 'unique' => 0],
            'start_time'  => ['column' => 'start_time', 'unique' => 0],
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $objects = [
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'objecttype_id'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'name1'           => ['type' => 'string', 'null' => false, 'length' => 128, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name2'           => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_active'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'indexes'         => [
            'PRIMARY'       => ['column' => 'object_id', 'unique' => 1],
            'objecttype_id' => ['column' => ['objecttype_id', 'name1', 'name2'], 'unique' => 0],
            'achmet'        => ['column' => ['name1', 'name2'], 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $processevents = [
        'processevent_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'event_type'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'event_time'      => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'event_time_usec' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'process_id'      => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'program_name'    => ['type' => 'string', 'null' => false, 'length' => 16, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'program_version' => ['type' => 'string', 'null' => false, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'program_date'    => ['type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'processevent_id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $programstatus = [
        'programstatus_id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'unique'],
        'status_update_time'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'program_start_time'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'program_end_time'               => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'is_currently_running'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'process_id'                     => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'daemon_mode'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'last_command_check'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_log_rotation'              => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'notifications_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'active_service_checks_enabled'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'passive_service_checks_enabled' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'active_host_checks_enabled'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'passive_host_checks_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'event_handlers_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'flap_detection_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'failure_prediction_enabled'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'process_performance_data'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'obsess_over_hosts'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'obsess_over_services'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'modified_host_attributes'       => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'modified_service_attributes'    => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'global_host_event_handler'      => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'global_service_event_handler'   => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                        => [
            'PRIMARY'     => ['column' => 'programstatus_id', 'unique' => 1],
            'instance_id' => ['column' => 'instance_id', 'unique' => 1],
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $runtimevariables = [
        'runtimevariable_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'varname'            => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'varvalue'           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'            => [
            'PRIMARY'     => ['column' => 'runtimevariable_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'varname'], 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $scheduleddowntime = [
        'scheduleddowntime_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'downtime_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'object_id'              => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'entry_time'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'author_name'            => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment_data'           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'internal_downtime_id'   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'triggered_by_id'        => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'is_fixed'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'duration'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'scheduled_start_time'   => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'scheduled_end_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'was_started'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'actual_start_time'      => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'actual_start_time_usec' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'scheduleddowntime_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'object_id', 'entry_time', 'internal_downtime_id'], 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $service_contactgroups = [
        'service_contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'service_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'contactgroup_object_id'  => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                 => [
            'PRIMARY'     => ['column' => 'service_contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['service_id', 'contactgroup_object_id'], 'unique' => 1],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $service_contacts = [
        'service_contact_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'service_id'         => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'contact_object_id'  => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'            => [
            'PRIMARY'     => ['column' => 'service_contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'service_id', 'contact_object_id'], 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $service_parentservices = [
        'service_parentservice_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'service_id'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'parent_service_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                  => [
            'PRIMARY'     => ['column' => 'service_parentservice_id', 'unique' => 1],
            'instance_id' => ['column' => ['service_id', 'parent_service_object_id'], 'unique' => 1],
        ],
        'tableParameters'          => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $servicechecks = [
        'servicecheck_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'service_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'check_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'current_check_attempt' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'max_check_attempts'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'state'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'state_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'start_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'start_time_usec'       => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'end_time'              => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'         => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'command_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'command_args'          => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_line'          => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeout'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'early_timeout'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'execution_time'        => ['type' => 'float', 'null' => false, 'default' => '0'],
        'latency'               => ['type' => 'float', 'null' => false, 'default' => '0'],
        'return_code'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'output'                => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'           => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'perfdata'              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'               => [
            'PRIMARY'           => ['column' => 'servicecheck_id', 'unique' => 1],
            'start_time'        => ['column' => 'start_time', 'unique' => 0],
            'instance_id'       => ['column' => 'instance_id', 'unique' => 0],
            'service_object_id' => ['column' => 'service_object_id', 'unique' => 0],
            'start_time_2'      => ['column' => 'start_time', 'unique' => 0],
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $servicedependencies = [
        'servicedependency_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'config_type'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'service_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'dependent_service_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'dependency_type'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'inherits_parent'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'timeperiod_object_id'        => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'fail_on_ok'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'fail_on_warning'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'fail_on_unknown'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'fail_on_critical'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'indexes'                     => [
            'PRIMARY'     => ['column' => 'servicedependency_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'service_object_id', 'dependent_service_object_id', 'dependency_type', 'inherits_parent', 'fail_on_ok', 'fail_on_warning', 'fail_on_unknown', 'fail_on_critical'], 'unique' => 1],
        ],
        'tableParameters'             => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $serviceescalation_contactgroups = [
        'serviceescalation_contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'serviceescalation_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'contactgroup_object_id'            => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                           => [
            'PRIMARY'     => ['column' => 'serviceescalation_contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['serviceescalation_id', 'contactgroup_object_id'], 'unique' => 1],
        ],
        'tableParameters'                   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $serviceescalation_contacts = [
        'serviceescalation_contact_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'serviceescalation_id'         => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'contact_object_id'            => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                      => [
            'PRIMARY'     => ['column' => 'serviceescalation_contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'serviceescalation_id', 'contact_object_id'], 'unique' => 1],
        ],
        'tableParameters'              => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $serviceescalations = [
        'serviceescalation_id'  => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'config_type'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'service_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'timeperiod_object_id'  => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'first_notification'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'last_notification'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notification_interval' => ['type' => 'float', 'null' => false, 'default' => '0'],
        'escalate_on_recovery'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'escalate_on_warning'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'escalate_on_unknown'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'escalate_on_critical'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'indexes'               => [
            'PRIMARY'     => ['column' => 'serviceescalation_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'service_object_id', 'timeperiod_object_id', 'first_notification', 'last_notification'], 'unique' => 1],
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $servicegroup_members = [
        'servicegroup_member_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'servicegroup_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'service_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'servicegroup_member_id', 'unique' => 1],
            'instance_id' => ['column' => ['servicegroup_id', 'service_object_id'], 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $servicegroups = [
        'servicegroup_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'config_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'servicegroup_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'alias'                  => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'servicegroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'servicegroup_object_id'], 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $services = [
        'service_id'                        => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'config_type'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'host_object_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'service_object_id'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'display_name'                      => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_command_args'                => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'eventhandler_command_args'         => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'notification_timeperiod_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_timeperiod_object_id'        => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'failure_prediction_options'        => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_interval'                    => ['type' => 'float', 'null' => false, 'default' => '0'],
        'retry_interval'                    => ['type' => 'float', 'null' => false, 'default' => '0'],
        'max_check_attempts'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'first_notification_delay'          => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notification_interval'             => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notify_on_warning'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_on_unknown'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_on_critical'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_on_recovery'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_on_flapping'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notify_on_downtime'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'stalk_on_ok'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'stalk_on_warning'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'stalk_on_unknown'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'stalk_on_critical'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'is_volatile'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'flap_detection_enabled'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'flap_detection_on_ok'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'flap_detection_on_warning'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'flap_detection_on_unknown'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'flap_detection_on_critical'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'low_flap_threshold'                => ['type' => 'float', 'null' => false, 'default' => '0'],
        'high_flap_threshold'               => ['type' => 'float', 'null' => false, 'default' => '0'],
        'process_performance_data'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_checks_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8],
        'freshness_threshold'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'passive_checks_enabled'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'event_handler_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'active_checks_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'retain_status_information'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'retain_nonstatus_information'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notifications_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'obsess_over_service'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'failure_prediction_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notes'                             => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'notes_url'                         => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'action_url'                        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'icon_image'                        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'icon_image_alt'                    => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'importance'                        => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                           => [
            'PRIMARY'           => ['column' => 'service_id', 'unique' => 1],
            'instance_id'       => ['column' => ['instance_id', 'config_type', 'service_object_id'], 'unique' => 1],
            'service_object_id' => ['column' => 'service_object_id', 'unique' => 0],
        ],
        'tableParameters'                   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $servicestatus = [
        'servicestatus_id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'service_object_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'unique'],
        'status_update_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'output'                        => ['type' => 'string', 'null' => false, 'length' => 512, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'                   => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'perfdata'                      => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'current_state'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'has_been_checked'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'should_be_scheduled'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'current_check_attempt'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'max_check_attempts'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'last_check'                    => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'next_check'                    => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'check_type'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'last_state_change'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'last_hard_state_change'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_hard_state'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'last_time_ok'                  => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_time_warning'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_time_unknown'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'last_time_critical'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'state_type'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'last_notification'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'next_notification'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'no_more_notifications'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notifications_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'problem_has_been_acknowledged' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'acknowledgement_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'current_notification_number'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'passive_checks_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'active_checks_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'event_handler_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'flap_detection_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'is_flapping'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'percent_state_change'          => ['type' => 'float', 'null' => false, 'default' => '0', 'key' => 'index'],
        'latency'                       => ['type' => 'float', 'null' => false, 'default' => '0', 'key' => 'index'],
        'execution_time'                => ['type' => 'float', 'null' => false, 'default' => '0', 'key' => 'index'],
        'scheduled_downtime_depth'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'failure_prediction_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'process_performance_data'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'obsess_over_service'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'modified_service_attributes'   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'event_handler'                 => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command'                 => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'normal_check_interval'         => ['type' => 'float', 'null' => false, 'default' => '0'],
        'retry_check_interval'          => ['type' => 'float', 'null' => false, 'default' => '0'],
        'check_timeperiod_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0'],
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
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $statehistory = [
        'statehistory_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'state_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'state_time_usec'       => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'object_id'             => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'state_change'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'state'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'state_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'current_check_attempt' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'max_check_attempts'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'last_state'            => ['type' => 'integer', 'null' => false, 'default' => '-1', 'length' => 6],
        'last_hard_state'       => ['type' => 'integer', 'null' => false, 'default' => '-1', 'length' => 6],
        'output'                => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'           => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'               => [
            'PRIMARY' => ['column' => 'statehistory_id', 'unique' => 1],
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $systemcommands = [
        'systemcommand_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'start_time'       => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'start_time_usec'  => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'end_time'         => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'    => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'command_line'     => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeout'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'early_timeout'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'execution_time'   => ['type' => 'float', 'null' => false, 'default' => '0'],
        'return_code'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'output'           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'      => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'          => [
            'PRIMARY'     => ['column' => 'systemcommand_id', 'unique' => 1],
            'instance_id' => ['column' => 'instance_id', 'unique' => 0],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $timedeventqueue = [
        'timedeventqueue_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'event_type'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'queued_time'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'queued_time_usec'   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'scheduled_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'recurring_event'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'object_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'indexes'            => [
            'PRIMARY'        => ['column' => 'timedeventqueue_id', 'unique' => 1],
            'instance_id'    => ['column' => 'instance_id', 'unique' => 0],
            'event_type'     => ['column' => 'event_type', 'unique' => 0],
            'scheduled_time' => ['column' => 'scheduled_time', 'unique' => 0],
            'object_id'      => ['column' => 'object_id', 'unique' => 0],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $timedevents = [
        'timedevent_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'event_type'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'queued_time'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'queued_time_usec'   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'event_time'         => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'event_time_usec'    => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'scheduled_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'recurring_event'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'object_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'deletion_time'      => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'deletion_time_usec' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'            => [
            'PRIMARY'        => ['column' => 'timedevent_id', 'unique' => 1],
            'instance_id'    => ['column' => 'instance_id', 'unique' => 0],
            'event_type'     => ['column' => 'event_type', 'unique' => 0],
            'scheduled_time' => ['column' => 'scheduled_time', 'unique' => 0],
            'object_id'      => ['column' => 'object_id', 'unique' => 0],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $timeperiod_timeranges = [
        'timeperiod_timerange_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'timeperiod_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'day'                     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'start_sec'               => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'end_sec'                 => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'indexes'                 => [
            'PRIMARY'     => ['column' => 'timeperiod_timerange_id', 'unique' => 1],
            'instance_id' => ['column' => ['timeperiod_id', 'day', 'start_sec', 'end_sec'], 'unique' => 1],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $timeperiods = [
        'timeperiod_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instance_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'],
        'config_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'timeperiod_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'alias'                => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'              => [
            'PRIMARY'     => ['column' => 'timeperiod_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'timeperiod_object_id'], 'unique' => 1],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM'],
    ];

}
