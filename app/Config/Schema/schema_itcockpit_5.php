<?php

class AppSchema extends CakeSchema {

    public function before($event = []) {
        return true;
    }

    public function after($event = []) {
    }

    public $changelogs = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'controller'      => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
        'action'          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
        'data'            => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $commandarguments = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'command_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'human_name'      => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $commands = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_line'    => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_type'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'human_args'      => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'uuid'            => ['type' => 'string', 'null' => false, 'length' => 37, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'description'     => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_hostescalations = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id'   => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'           => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_hosts = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'host_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_hosttemplates = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'hosttemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_servicetemplates = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts = [
        'id'                            => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name'                          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'                   => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'email'                         => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'phone'                         => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
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

    public $contacts_to_contactgroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_containers = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hostcommands = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'command_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hostescalations = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'        => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'           => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hosts = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'host_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hosttemplates = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'hosttemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_servicecommands = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'command_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_servicetemplates = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $containers = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'containertype_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'             => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'parent_id'        => ['type' => 'integer', 'null' => true, 'default' => null],
        'lft'              => ['type' => 'integer', 'null' => false, 'default' => null],
        'rght'             => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $customvariables = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => null],
        'objecttype_id'   => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'value'           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $exports = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => null],
        'objecttype_id'   => ['type' => 'integer', 'null' => false, 'default' => null],
        'action'          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hostcommandargumentvalues = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'host_id'            => ['type' => 'integer', 'null' => false, 'default' => null],
        'value'              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hostescalations = [
        'id'                      => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'timeperiod_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'first_notification'      => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'last_notification'       => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'notification_interval'   => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'escalate_on_recovery'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'escalate_on_down'        => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'escalate_on_unreachable' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'created'                 => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                 => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hostgroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'description'     => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hostgroups_to_hostescalations = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'hostgroup_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'excluded'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4],
        'created'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'          => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'           => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'   => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts = [
        'id'                             => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                           => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'                   => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'                           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'                    => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'hosttemplate_id'                => ['type' => 'integer', 'null' => false, 'default' => null],
        'address'                        => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command_object_id'        => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_command_args'             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'eventhandler_command_args'      => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeperiod_id'                  => ['type' => 'integer', 'null' => false, 'default' => null],
        'check_interval'                 => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 5],
        'retry_interval'                 => ['type' => 'integer', 'null' => false, 'default' => '3', 'length' => 5],
        'max_check_attempts'             => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3],
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
        'notes'                          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'priority'                       => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 2],
        'created'                        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                       => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                        => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_containers = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_hostescalations = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'excluded'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4],
        'created'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'          => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'           => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'   => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_hostgroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostgroup_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosttemplatecommandargumentvalues = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'hosttemplate_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'value'              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosttemplates = [
        'id'                             => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                           => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name'                           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'                    => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_id'                     => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_command_args'             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'eventhandler_command_args'      => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeperiod_id'                  => ['type' => 'integer', 'null' => false, 'default' => null],
        'check_interval'                 => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 5],
        'retry_interval'                 => ['type' => 'integer', 'null' => false, 'default' => '3', 'length' => 5],
        'max_check_attempts'             => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3],
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
        'notes'                          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'priority'                       => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 2],
        'created'                        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                       => ['type' => 'datetime', 'null' => false, 'default' => null],
        'check_period_id'                => ['type' => 'integer', 'null' => false, 'default' => null],
        'notify_period_id'               => ['type' => 'integer', 'null' => false, 'default' => null],
        'tags'                           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'                   => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'                        => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $locations = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'description'     => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'latitude'        => ['type' => 'float', 'null' => true, 'default' => '0'],
        'longitude'       => ['type' => 'float', 'null' => true, 'default' => '0'],
        'timezone'        => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $macros = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'value'           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $proxies = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'ipaddress'       => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
        'port'            => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 5],
        'enabled'         => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicecommandargumentvalues = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'service_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'value'              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $services = [
        'id'                                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'servicetemplate_id'                => ['type' => 'integer', 'null' => false, 'default' => null],
        'host_id'                           => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'                              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'                       => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_command_args'                => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'eventhandler_command_args'         => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
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
        'notes'                             => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'                           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                          => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                           => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'                   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicetemplatecommandargumentvalues = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'value'              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicetemplates = [
        'id'                             => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                           => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name'                           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'                   => ['type' => 'integer', 'null' => true, 'default' => null],
        'description'                    => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_id'                     => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_command_args'             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'checkcommand_info'              => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'eventhandler_command_args'      => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeperiod_id'                  => ['type' => 'integer', 'null' => false, 'default' => null],
        'check_interval'                 => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 5],
        'retry_interval'                 => ['type' => 'integer', 'null' => false, 'default' => '3', 'length' => 5],
        'max_check_attempts'             => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3],
        'first_notification_delay'       => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notification_interval'          => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notify_on_warning'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_unknown'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_critical'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_recovery'             => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'notify_on_flapping'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_downtime'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_ok'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_warning'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_unknown'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_critical'     => ['type' => 'boolean', 'null' => false, 'default' => '0'],
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
        'notes'                          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'priority'                       => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 2],
        'check_period_id'                => ['type' => 'integer', 'null' => true, 'default' => null],
        'notify_period_id'               => ['type' => 'integer', 'null' => true, 'default' => null],
        'tags'                           => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 1500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_volatile'                    => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'check_freshness'                => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'eventhandler_id'                => ['type' => 'integer', 'null' => true, 'default' => null],
        'indexes'                        => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $tenants = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'description'     => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_active'       => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'number_users'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'max_users'       => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'number_hosts'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'max_hosts'       => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'number_services' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'max_services'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'firstname'       => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'lastname'        => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'street'          => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'zipcode'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'city'            => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $timeperiod_timeranges = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'timeperiod_id'   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'day'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'start'           => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 5, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'end'             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 5, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $timeperiods = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'     => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $users = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'status'          => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3],
        'role'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'email'           => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'password'        => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'firstname'       => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'lastname'        => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'position'        => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'company'         => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'phone'           => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'linkedin_id'     => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

}
