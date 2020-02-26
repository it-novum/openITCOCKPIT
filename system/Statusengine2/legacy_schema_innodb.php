<?php

class LegacySchema extends CakeSchema {

    /* Useful schema commands:
     *
     * Update to new schema:
     *  ../../cakephp/app/Console/cake schema update
     *
     * Update to snapshot:
     * /opt/statusengine/cakephp/app/Console/cake schema update --plugin Legacy --file legacy_schema.php --connection legacy -s X
     *
     * Generate new snapshot
     * 	/opt/statusengine/cakephp/app/Console/cake schema generate --plugin Legacy --file legacy_schema_X.php --connection legacy
     *
     * Based on NDO database schema
     * Copyright 1999-2009:
     *   Ethan Galstad <egalstad@nagios.org>
     * Copyright 2009 until further notice:
     *   Nagios Core Development Team and Nagios Community Contributors
     * GNU GENERAL PUBLIC LICENSE Version 2, June 1991
     *
     */

    public $connection = 'legacy';

    public function before($event = []) {
        return true;
    }

    public function after($event = []) {
    }

    public $acknowledgements = [
        'acknowledgement_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'entry_time'           => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'],
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
            'PRIMARY'     => ['column' => 'acknowledgement_id', 'unique' => 1],
            'entry_time'  => ['column' => 'entry_time', 'unique' => 0],
            'instance_id' => ['column' => ['instance_id', 'object_id'], 'unique' => 0]
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $commands = [
        'command_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'command_line'    => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 511, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY'     => ['column' => 'command_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'object_id', 'config_type'], 'unique' => 1],
            'object_id'   => ['column' => 'object_id', 'unique' => 0]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $commenthistory = [
        'commenthistory_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => true, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'entry_time'          => ['type' => 'datetime', 'null' => true, 'default' => '1970-01-01 00:00:00'],
        'entry_time_usec'     => ['type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false],
        'comment_type'        => ['type' => 'integer', 'null' => true, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'entry_type'          => ['type' => 'integer', 'null' => true, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'           => ['type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false],
        'comment_time'        => ['type' => 'datetime', 'null' => true, 'default' => '1970-01-01 00:00:00'],
        'internal_comment_id' => ['type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false],
        'author_name'         => ['type' => 'string', 'null' => true, 'length' => 64, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
        'comment_data'        => ['type' => 'string', 'null' => true, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
        'is_persistent'       => ['type' => 'integer', 'null' => true, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'comment_source'      => ['type' => 'integer', 'null' => true, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'expires'             => ['type' => 'integer', 'null' => true, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'expiration_time'     => ['type' => 'datetime', 'null' => true, 'default' => '1970-01-01 00:00:00'],
        'deletion_time'       => ['type' => 'datetime', 'null' => true, 'default' => '1970-01-01 00:00:00'],
        'deletion_time_usec'  => ['type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false],
        'indexes'             => [
            'PRIMARY'                       => ['column' => 'commenthistory_id', 'unique' => 1],
            'object_id_internal_comment_id' => ['column' => ['object_id', 'internal_comment_id'], 'unique' => 1],
            'object_id'                     => ['column' => ['object_id'], 'unique' => 0]
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical host and service comments']
    ];

    public $comments = [
        'comment_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'entry_time'          => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'entry_time_usec'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'comment_type'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'entry_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'comment_time'        => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'internal_comment_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'author_name'         => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment_data'        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_persistent'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'comment_source'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'expires'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'expiration_time'     => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'indexes'             => [
            'PRIMARY'                       => ['column' => 'comment_id', 'unique' => 1],
            'object_id_internal_comment_id' => ['column' => ['object_id', 'internal_comment_id'], 'unique' => 1]
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $configfiles = [
        'configfile_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'configfile_type' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'configfile_path' => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY'     => ['column' => 'configfile_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'configfile_type', 'configfile_path'], 'unique' => 1]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $configfilevariables = [
        'configfilevariable_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'configfile_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'varname'               => ['type' => 'string', 'null' => false, 'length' => 255, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'varvalue'              => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8', 'length' => 1024],
        'indexes'               => [
            'PRIMARY'     => ['column' => 'configfilevariable_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'configfile_id'], 'unique' => 0]
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $contact_addresses = [
        'contact_address_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contact_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'address_number'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'address'            => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'            => [
            'PRIMARY'    => ['column' => 'contact_address_id', 'unique' => 1],
            'contact_id' => ['column' => ['contact_id', 'address_number'], 'unique' => 1]
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $contact_notificationcommands = [
        'contact_notificationcommand_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contact_id'                     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'notification_type'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'command_object_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_args'                   => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                        => [
            'PRIMARY'    => ['column' => 'contact_notificationcommand_id', 'unique' => 1],
            'contact_id' => ['column' => ['contact_id', 'notification_type', 'command_object_id'], 'unique' => 1]
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $contactgroup_members = [
        'contactgroup_member_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contactgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'contact_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'contactgroup_member_id', 'unique' => 1],
            'instance_id' => ['column' => ['contactgroup_id', 'contact_object_id'], 'unique' => 1]
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $contactgroups = [
        'contactgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contactgroup_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'alias'                  => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'contactgroup_object_id'], 'unique' => 1]
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /*
    public $contactnotificationmethods = array(
        'contactnotificationmethod_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
        'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
        'contactnotification_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'start_time' => array('type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'),
        'start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'end_time' => array('type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'),
        'end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'command_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'command_args' => array('type' => 'string', 'null' => true, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'indexes' => array(
            'PRIMARY' => array('column' => array('contactnotificationmethod_id', 'start_time'), 'unique' => 1),
            //'instance_id' => array('column' => array('instance_id', 'contactnotification_id', 'start_time', 'start_time_usec'), 'unique' => 1),
            'start_time' => array('column' => 'start_time', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
    );
    */

    /*
    public $contactnotifications = array(
        'contactnotification_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
        'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'notification_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'contact_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'start_time' => array('type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'),
        'start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'end_time' => array('type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'),
        'end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'indexes' => array(
            'PRIMARY' => array('column' => array('contactnotification_id', 'start_time'), 'unique' => 1),
            'start_time' => array('column' => 'start_time', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
    );
    */

    public $contacts = [
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
            'instance_id' => ['column' => ['instance_id', 'config_type', 'contact_object_id'], 'unique' => 1]
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $contactstatus = [
        'contactstatus_id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contact_object_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'unique'],
        'status_update_time'            => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'host_notifications_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'service_notifications_enabled' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_host_notification'        => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'last_service_notification'     => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'modified_attributes'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'modified_host_attributes'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'modified_service_attributes'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                       => [
            'PRIMARY'           => ['column' => 'contactstatus_id', 'unique' => 1],
            'contact_object_id' => ['column' => 'contact_object_id', 'unique' => 1],
            //'instance_id' => array('column' => 'contactstatus_id', 'unique' => 0)
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $customvariables = [
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
            'varname'     => ['column' => 'varname', 'unique' => 0]
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $dbversion = [
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 12, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8', 'key' => 'primary'],
        'version'         => ['type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [

        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $downtimehistory = [
        'downtimehistory_id'     => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'downtime_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'entry_time'             => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'author_name'            => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment_data'           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'internal_downtime_id'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'triggered_by_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'is_fixed'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'duration'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'scheduled_start_time'   => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'],
        'scheduled_end_time'     => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'],
        'was_started'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'actual_start_time'      => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'actual_start_time_usec' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'actual_end_time'        => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'actual_end_time_usec'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'was_cancelled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'                => [
            'PRIMARY'              => ['column' => 'downtimehistory_id', 'unique' => 1],
            //'instance_id' => array('column' => array('instance_id', 'object_id', 'entry_time', 'internal_downtime_id'), 'unique' => 1),
            'scheduled_start_time' => ['column' => 'scheduled_start_time', 'unique' => 0],
            'scheduled_end_time'   => ['column' => 'scheduled_end_time', 'unique' => 0],
            'object_id'            => ['column' => 'object_id', 'unique' => 0]
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $eventhandlers = [
        'eventhandler_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'eventhandler_type' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'state'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'state_type'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'start_time'        => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'start_time_usec'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_time'          => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'end_time_usec'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_args'      => ['type' => 'string', 'null' => true, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_line'      => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeout'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'early_timeout'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'execution_time'    => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'return_code'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'output'            => ['type' => 'string', 'null' => true, 'length' => 4096, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'       => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'           => [
            'PRIMARY' => ['column' => 'eventhandler_id', 'unique' => 1]
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $externalcommands = [
        'externalcommand_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'entry_time'         => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'],
        'command_type'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'command_name'       => ['type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_args'       => ['type' => 'string', 'null' => true, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'            => [
            'PRIMARY'     => ['column' => 'externalcommand_id', 'unique' => 1],
            'instance_id' => ['column' => 'instance_id', 'unique' => 0],
            'entry_time'  => ['column' => 'entry_time', 'unique' => 0]
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $flappinghistory = [
        'flappinghistory_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'event_time'           => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'event_time_usec'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'event_type'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'reason_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flapping_type'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'percent_state_change' => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'low_threshold'        => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'high_threshold'       => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'comment_time'         => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'internal_comment_id'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'              => [
            'PRIMARY'     => ['column' => 'flappinghistory_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'object_id'], 'unique' => 0],
            'object_id'   => ['column' => ['object_id'], 'unique' => 0]
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $host_contactgroups = [
        'host_contactgroup_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_id'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'contactgroup_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'host_contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['host_id', 'contactgroup_object_id'], 'unique' => 1]
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $host_contacts = [
        'host_contact_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'host_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'contact_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'           => [
            'PRIMARY'     => ['column' => 'host_contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'host_id', 'contact_object_id'], 'unique' => 1]
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $host_parenthosts = [
        'host_parenthost_id'    => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_id'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'parent_host_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'               => [
            'PRIMARY'     => ['column' => 'host_parenthost_id', 'unique' => 1],
            'instance_id' => ['column' => ['host_id', 'parent_host_object_id'], 'unique' => 1]
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /*
    public $hostchecks = array(
        'hostcheck_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
        'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'host_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'check_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'is_raw_check' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'current_check_attempt' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'state_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'start_time' => array('type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'),
        'start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'end_time' => array('type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'),
        'end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'command_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'command_args' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'command_line' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'timeout' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'early_timeout' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'execution_time' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
        'latency' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
        'return_code' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'output' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'long_output' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'perfdata' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'indexes' => array(
            'PRIMARY' => array('column' => array('hostcheck_id', 'start_time'), 'unique' => 1),
            'start_time' => array('column' => 'start_time', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
    );
    */

    public $hostdependencies = [
        'hostdependency_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'dependent_host_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'dependency_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'inherits_parent'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'timeperiod_object_id'     => ['type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false],
        'fail_on_up'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'fail_on_down'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'fail_on_unreachable'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'                  => [
            'PRIMARY'     => ['column' => 'hostdependency_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'host_object_id', 'dependent_host_object_id', 'dependency_type', 'inherits_parent', 'fail_on_up', 'fail_on_down', 'fail_on_unreachable'], 'unique' => 1]
        ],
        'tableParameters'          => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $hostescalation_contactgroups = [
        'hostescalation_contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'hostescalation_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'contactgroup_object_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                        => [
            'PRIMARY'     => ['column' => 'hostescalation_contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['hostescalation_id', 'contactgroup_object_id'], 'unique' => 1]
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $hostescalation_contacts = [
        'hostescalation_contact_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'hostescalation_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'contact_object_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                   => [
            'PRIMARY'     => ['column' => 'hostescalation_contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'hostescalation_id', 'contact_object_id'], 'unique' => 1]
        ],
        'tableParameters'           => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $hostescalations = [
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
            'instance_id' => ['column' => ['instance_id', 'config_type', 'host_object_id', 'timeperiod_object_id', 'first_notification', 'last_notification'], 'unique' => 1]
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $hostgroup_members = [
        'hostgroup_member_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'hostgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'host_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'             => [
            'PRIMARY'     => ['column' => 'hostgroup_member_id', 'unique' => 1],
            'instance_id' => ['column' => ['hostgroup_id', 'host_object_id'], 'unique' => 1]
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $hostgroups = [
        'hostgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'hostgroup_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'alias'               => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'             => [
            'PRIMARY'     => ['column' => 'hostgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'hostgroup_object_id'], 'unique' => 1]
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $hosts = [
        'host_id'                           => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_object_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'alias'                             => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'display_name'                      => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'address'                           => ['type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'check_command_args'                => ['type' => 'string', 'null' => true, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'eventhandler_command_args'         => ['type' => 'string', 'null' => true, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
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
            'host_object_id' => ['column' => 'host_object_id', 'unique' => 0]
        ],
        'tableParameters'                   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $hoststatus = [
        'hoststatus_id'                 => ['type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => ture, 'key' => 'primary'],
        'instance_id'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'host_object_id'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'unique'],
        'status_update_time'            => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'],
        'output'                        => ['type' => 'string', 'null' => true, 'length' => 4096, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'                   => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'perfdata'                      => ['type' => 'string', 'null' => true, 'length' => 4096, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'current_state'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'has_been_checked'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'should_be_scheduled'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'current_check_attempt'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'max_check_attempts'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_check'                    => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'],
        'next_check'                    => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'check_type'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'last_state_change'             => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'],
        'last_hard_state_change'        => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'last_hard_state'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_time_up'                  => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'last_time_down'                => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'last_time_unreachable'         => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'state_type'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'last_notification'             => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'next_notification'             => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
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
        'event_handler'                 => ['type' => 'string', 'null' => true, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command'                 => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'normal_check_interval'         => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'retry_check_interval'          => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'check_timeperiod_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                       => [
            'PRIMARY'               => ['column' => 'hoststatus_id', 'unique' => 1],
            'object_id_instance_id' => ['column' => ['host_object_id', 'instance_id'], 'unique' => 1],
            'hoststatus'            => ['column' => ['host_object_id', 'current_state'], 'unique' => 0]
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $instances = [
        'instance_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false, 'key' => 'primary'],
        'instance_name'        => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'instance_description' => ['type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'              => [
            'PRIMARY' => ['column' => 'instance_id', 'unique' => 1]
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /*
    public $logentries = array(
        'logentry_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
        'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'logentry_time' => array('type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'),
        'entry_time' => array('type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'),
        'entry_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
        'logentry_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'logentry_data' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'realtime_data' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'inferred_data_extracted' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'indexes' => array(
            'PRIMARY' => array('column' => array('logentry_id', 'entry_time'), 'unique' => 1),
            'logentry_time' => array('column' => 'logentry_time', 'unique' => 0),
            'legentry_time' => array('column' => 'logentry_time', 'unique' => 0),
            'entry_time' => array('column' => 'entry_time', 'unique' => 0),
            'entry_time_usec' => array('column' => 'entry_time_usec', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
    );
    */

    /*
    public $notifications = array(
        'notification_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
        'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'notification_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'notification_reason' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
        'start_time' => array('type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'),
        'start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'end_time' => array('type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'),
        'end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'output' => array('type' => 'string', 'null' => true, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'long_output' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'escalated' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'contacts_notified' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'indexes' => array(
            'PRIMARY' => array('column' => array('notification_id', 'start_time'), 'unique' => 1),
            'start_time' => array('column' => 'start_time', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
    );
    */

    public $objects = [
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'objecttype_id'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'name1'           => ['type' => 'string', 'null' => false, 'length' => 128, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name2'           => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_active'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'         => [
            'PRIMARY'       => ['column' => 'object_id', 'unique' => 1],
            'hostobject'    => ['column' => ['name1', 'name2', 'objecttype_id'], 'unique' => 0],
            'serviceobject' => ['column' => ['name2', 'objecttype_id'], 'unique' => 0]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $processevents = [
        'processevent_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'event_type'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'event_time'      => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'],
        'event_time_usec' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'process_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'program_name'    => ['type' => 'string', 'null' => false, 'length' => 16, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'program_version' => ['type' => 'string', 'null' => false, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'program_date'    => ['type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY'    => ['column' => 'processevent_id', 'unique' => 1],
            'event_time' => ['column' => ['event_time', 'event_time_usec'], 'unique' => 0]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $programstatus = [
        'programstatus_id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'unique'],
        'status_update_time'             => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'program_start_time'             => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'program_end_time'               => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'is_currently_running'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'process_id'                     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'daemon_mode'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_command_check'             => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'last_log_rotation'              => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
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
        'modified_host_attributes'       => ['type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false],
        'modified_service_attributes'    => ['type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false],
        'global_host_event_handler'      => ['type' => 'string', 'null' => true, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'global_service_event_handler'   => ['type' => 'string', 'null' => true, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                        => [
            'PRIMARY'     => ['column' => 'programstatus_id', 'unique' => 1],
            'instance_id' => ['column' => 'instance_id', 'unique' => 1]
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $scheduleddowntime = [
        'scheduleddowntime_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'downtime_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'entry_time'             => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'author_name'            => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment_data'           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'internal_downtime_id'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'triggered_by_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'is_fixed'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'duration'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'scheduled_start_time'   => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'scheduled_end_time'     => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'was_started'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'actual_start_time'      => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'actual_start_time_usec' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                => [
            'PRIMARY' => ['column' => 'scheduleddowntime_id', 'unique' => 1],
            //'instance_id' => array('column' => array('instance_id', 'object_id', 'entry_time', 'internal_downtime_id'), 'unique' => 1)
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $service_contactgroups = [
        'service_contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'service_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'contactgroup_object_id'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                 => [
            'PRIMARY'     => ['column' => 'service_contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['service_id', 'contactgroup_object_id'], 'unique' => 1]
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $service_contacts = [
        'service_contact_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'service_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'contact_object_id'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'            => [
            'PRIMARY'     => ['column' => 'service_contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'service_id', 'contact_object_id'], 'unique' => 1]
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $service_parentservices = [
        'service_parentservice_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'service_id'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'parent_service_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                  => [
            'PRIMARY'     => ['column' => 'service_parentservice_id', 'unique' => 1],
            'instance_id' => ['column' => ['service_id', 'parent_service_object_id'], 'unique' => 1]
        ],
        'tableParameters'          => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /*
    public $servicechecks = array(
        'servicecheck_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
        'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
        'service_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
        'check_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'current_check_attempt' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'state_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'start_time' => array('type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'),
        'start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'end_time' => array('type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'),
        'end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'command_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'command_args' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'command_line' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'timeout' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'early_timeout' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'execution_time' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
        'latency' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
        'return_code' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'output' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'long_output' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'perfdata' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'indexes' => array(
            'PRIMARY' => array('column' => array('servicecheck_id', 'start_time'), 'unique' => 1),
            'start_time' => array('column' => 'start_time', 'unique' => 0),
            'service_object_id' => array('column' => 'service_object_id', 'unique' => 0),
            'start_time_2' => array('column' => 'start_time', 'unique' => 0),
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
    );
    */

    public $servicedependencies = [
        'servicedependency_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'service_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'dependent_service_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'dependency_type'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'inherits_parent'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'timeperiod_object_id'        => ['type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false],
        'fail_on_ok'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'fail_on_warning'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'fail_on_unknown'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'fail_on_critical'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'                     => [
            'PRIMARY'     => ['column' => 'servicedependency_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'service_object_id', 'dependent_service_object_id', 'dependency_type', 'inherits_parent', 'fail_on_ok', 'fail_on_warning', 'fail_on_unknown', 'fail_on_critical'], 'unique' => 1]
        ],
        'tableParameters'             => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $serviceescalation_contactgroups = [
        'serviceescalation_contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'serviceescalation_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'contactgroup_object_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                           => [
            'PRIMARY'     => ['column' => 'serviceescalation_contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['serviceescalation_id', 'contactgroup_object_id'], 'unique' => 1]
        ],
        'tableParameters'                   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $serviceescalation_contacts = [
        'serviceescalation_contact_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'serviceescalation_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'contact_object_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                      => [
            'PRIMARY'     => ['column' => 'serviceescalation_contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'serviceescalation_id', 'contact_object_id'], 'unique' => 1]
        ],
        'tableParameters'              => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $serviceescalations = [
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
            'instance_id' => ['column' => ['instance_id', 'config_type', 'service_object_id', 'timeperiod_object_id', 'first_notification', 'last_notification'], 'unique' => 1]
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $servicegroup_members = [
        'servicegroup_member_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'servicegroup_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'service_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'servicegroup_member_id', 'unique' => 1],
            'instance_id' => ['column' => ['servicegroup_id', 'service_object_id'], 'unique' => 1]
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $servicegroups = [
        'servicegroup_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'servicegroup_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'alias'                  => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'servicegroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'servicegroup_object_id'], 'unique' => 1]
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $services = [
        'service_id'                        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'config_type'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_object_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'service_object_id'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'display_name'                      => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'check_command_args'                => ['type' => 'string', 'null' => true, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'eventhandler_command_args'         => ['type' => 'string', 'null' => true, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
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
            'service_object_id' => ['column' => 'service_object_id', 'unique' => 0]
        ],
        'tableParameters'                   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $servicestatus = [
        'servicestatus_id'              => ['type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'],
        'instance_id'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'service_object_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'unique'],
        'status_update_time'            => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'],
        'output'                        => ['type' => 'string', 'null' => true, 'length' => 4096, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'                   => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'perfdata'                      => ['type' => 'string', 'null' => true, 'length' => 4096, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'current_state'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'has_been_checked'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'should_be_scheduled'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'current_check_attempt'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'max_check_attempts'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_check'                    => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'],
        'next_check'                    => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'check_type'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'last_state_change'             => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'],
        'last_hard_state_change'        => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'last_hard_state'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_time_ok'                  => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'last_time_warning'             => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'last_time_unknown'             => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'last_time_critical'            => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'state_type'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'last_notification'             => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'next_notification'             => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
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
        'event_handler'                 => ['type' => 'string', 'null' => true, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command'                 => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'normal_check_interval'         => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'retry_check_interval'          => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'check_timeperiod_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                       => [
            'PRIMARY'               => ['column' => 'servicestatus_id', 'unique' => 1],
            'object_id_instance_id' => ['column' => ['service_object_id', 'instance_id'], 'unique' => 1],
            'servicestatus'         => ['column' => ['service_object_id', 'current_state', 'last_check', 'next_check', 'last_hard_state_change', 'output', 'scheduled_downtime_depth', 'active_checks_enabled', 'state_type', 'problem_has_been_acknowledged', 'is_flapping'], 'unique' => 0, 'length' => ['output' => '255']]
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /*
    public $statehistory = array(
        'statehistory_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
        'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'state_time' => array('type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00', 'key' => 'index'),
        'state_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
        'state_change' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'state_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'current_check_attempt' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
        'last_state' => array('type' => 'integer', 'null' => false, 'default' => '-1', 'length' => 6, 'unsigned' => false),
        'last_hard_state' => array('type' => 'integer', 'null' => false, 'default' => '-1', 'length' => 6, 'unsigned' => false),
        'output' => array('type' => 'string', 'null' => true, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'long_output' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'indexes' => array(
            'PRIMARY' => array('column' => array('statehistory_id', 'state_time'), 'unique' => 1),
            'state_time' => array('column' => array('state_time', 'state_time_usec'), 'unique' => 0),
            'object_id' => array('column' => array('object_id'), 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
    );
    */

    public $systemcommands = [
        'systemcommand_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'start_time'       => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'start_time_usec'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_time'         => ['type' => 'datetime', 'null' => false, 'default' => '1970-01-01 00:00:00'],
        'end_time_usec'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_line'     => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeout'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'early_timeout'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'execution_time'   => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'return_code'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'output'           => ['type' => 'string', 'null' => true, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'      => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'          => [
            'PRIMARY'     => ['column' => 'systemcommand_id', 'unique' => 1],
            'instance_id' => ['column' => 'instance_id', 'unique' => 0]
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $timeperiod_timeranges = [
        'timeperiod_timerange_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'timeperiod_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'day'                     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'start_sec'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_sec'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                 => [
            'PRIMARY'     => ['column' => 'timeperiod_timerange_id', 'unique' => 1],
            'instance_id' => ['column' => ['timeperiod_id', 'day', 'start_sec', 'end_sec'], 'unique' => 1]
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    public $timeperiods = [
        'timeperiod_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'timeperiod_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'alias'                => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'              => [
            'PRIMARY'     => ['column' => 'timeperiod_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'timeperiod_object_id'], 'unique' => 1]
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

}
