<?php

class AppSchema extends CakeSchema {

    public function before($event = []) {
        return true;
    }

    public function after($event = []) {
    }

    public $changelogs = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'model'           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'action'          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => null],
        'objecttype_id'   => ['type' => 'integer', 'default' => null],
        'command_id'      => ['type' => 'integer', 'default' => null],
        'user_id'         => ['type' => 'integer', 'default' => null],
        'data'            => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name'            => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'created' => ['column' => 'created'],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
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
        'description'     => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
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
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'contactgroup_id'   => ['column' => 'contactgroup_id', 'unique' => 0],
            'hostescalation_id' => ['column' => 'hostescalation_id', 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_hosts = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'host_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'         => ['column' => 'id', 'unique' => 1],
            'contactgroup_id' => ['column' => 'contactgroup_id', 'unique' => 0],
            'host_id'         => ['column' => 'host_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_hosttemplates = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'hosttemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'         => ['column' => 'id', 'unique' => 1],
            'contactgroup_id' => ['column' => 'contactgroup_id', 'unique' => 0],
            'hosttemplate_id' => ['column' => 'hosttemplate_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_serviceescalations = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'serviceescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'contactgroup_id'      => ['column' => 'contactgroup_id', 'unique' => 0],
            'serviceescalation_id' => ['column' => 'serviceescalation_id', 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_services = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'service_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'         => ['column' => 'id', 'unique' => 1],
            'contactgroup_id' => ['column' => 'contactgroup_id', 'unique' => 0],
            'service_id'      => ['column' => 'service_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_servicetemplates = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY'            => ['column' => 'id', 'unique' => 1],
            'contactgroup_id'    => ['column' => 'contactgroup_id', 'unique' => 0],
            'servicetemplate_id' => ['column' => 'servicetemplate_id', 'unique' => 0],
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
            'PRIMARY'         => ['column' => 'id', 'unique' => 1],
            'contact_id'      => ['column' => 'contact_id', 'unique' => 0],
            'contactgroup_id' => ['column' => 'contactgroup_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_containers = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'      => ['column' => 'id', 'unique' => 1],
            'contact_id'   => ['column' => 'contact_id', 'unique' => 0],
            'container_id' => ['column' => 'container_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hostcommands = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'command_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'    => ['column' => 'id', 'unique' => 1],
            'contact_id' => ['column' => 'contact_id', 'unique' => 0],
            'command_id' => ['column' => 'command_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hostescalations = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'        => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'contact_id'        => ['column' => 'contact_id', 'unique' => 0],
            'hostescalation_id' => ['column' => 'hostescalation_id', 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hosts = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'host_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'    => ['column' => 'id', 'unique' => 1],
            'contact_id' => ['column' => 'contact_id', 'unique' => 0],
            'host_id'    => ['column' => 'host_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hosttemplates = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'hosttemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'         => ['column' => 'id', 'unique' => 1],
            'contact_id'      => ['column' => 'contact_id', 'unique' => 0],
            'hosttemplate_id' => ['column' => 'hosttemplate_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_servicecommands = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'command_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'    => ['column' => 'id', 'unique' => 1],
            'contact_id' => ['column' => 'contact_id', 'unique' => 0],
            'command_id' => ['column' => 'command_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_serviceescalations = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'serviceescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'contact_id'           => ['column' => 'contact_id', 'unique' => 0],
            'serviceescalation_id' => ['column' => 'serviceescalation_id', 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_services = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'service_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'    => ['column' => 'id', 'unique' => 1],
            'contact_id' => ['column' => 'contact_id', 'unique' => 0],
            'service_id' => ['column' => 'service_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_servicetemplates = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY'            => ['column' => 'id', 'unique' => 1],
            'contact_id'         => ['column' => 'contact_id', 'unique' => 0],
            'servicetemplate_id' => ['column' => 'servicetemplate_id', 'unique' => 0],
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

    public $dashboards = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $documentations = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'content'         => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $exports = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => null],
        'objecttype_id'   => ['type' => 'integer', 'null' => false, 'default' => null],
        'action'          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
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
        'uuid'                    => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
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
        'uuid'            => ['type' => 'string', 'null' => false, 'length' => 37],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'description'     => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'hostgroup_url'   => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
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
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'hostgroup_id'      => ['column' => ['hostgroup_id', 'excluded'], 'unique' => 0],
            'hostescalation_id' => ['column' => ['hostescalation_id', 'excluded'], 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts = [
        'id'                            => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'                  => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'                          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'                   => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'hosttemplate_id'               => ['type' => 'integer', 'null' => false, 'default' => null],
        'address'                       => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_id'                    => ['type' => 'integer', 'null' => true, 'default' => null],
        'eventhandler_command_id'       => ['type' => 'integer', 'null' => true, 'default' => null],
        'timeperiod_id'                 => ['type' => 'integer', 'null' => true, 'default' => null],
        'check_interval'                => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 5],
        'retry_interval'                => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 5],
        'max_check_attempts'            => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 3],
        'first_notification_delay'      => ['type' => 'float', 'null' => true, 'default' => null],
        'notification_interval'         => ['type' => 'float', 'null' => true, 'default' => null],
        'notify_on_down'                => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_unreachable'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_recovery'            => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_flapping'            => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_downtime'            => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_enabled'        => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_on_up'          => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_on_down'        => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_on_unreachable' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'low_flap_threshold'            => ['type' => 'float', 'null' => true, 'default' => null],
        'high_flap_threshold'           => ['type' => 'float', 'null' => true, 'default' => null],
        'process_performance_data'      => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'freshness_checks_enabled'      => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'freshness_threshold'           => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 8],
        'passive_checks_enabled'        => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'event_handler_enabled'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'active_checks_enabled'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'retain_status_information'     => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'retain_nonstatus_information'  => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'notifications_enabled'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'notes'                         => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'priority'                      => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 2],
        'check_period_id'               => ['type' => 'integer', 'null' => true, 'default' => null],
        'notify_period_id'              => ['type' => 'integer', 'null' => true, 'default' => null],
        'tags'                          => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'own_contacts'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'own_contactgroups'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'own_customvariables'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'host_url'                      => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'satellite_id'                  => ['type' => 'integer', 'default' => 0],
        'host_type'                     => ['type' => 'integer', 'null' => false, 'default' => 1],
        'disabled'                      => ['type' => 'integer', 'null' => true, 'default' => 0, 'length' => 1],
        'created'                       => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                      => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                       => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_containers = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'      => ['column' => 'id', 'unique' => 1],
            'host_id'      => ['column' => 'host_id', 'unique' => 0],
            'container_id' => ['column' => 'container_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_hostescalations = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'excluded'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'host_id'           => ['column' => ['host_id', 'excluded'], 'unique' => 0],
            'hostescalation_id' => ['column' => ['hostescalation_id', 'excluded'], 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_hostgroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostgroup_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'      => ['column' => 'id', 'unique' => 1],
            'host_id'      => ['column' => 'host_id', 'unique' => 0],
            'hostgroup_id' => ['column' => 'hostgroup_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_parenthosts = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'         => ['type' => 'integer', 'null' => false],
        'parenthost_id'   => ['type' => 'integer', 'null' => false],
        'indexes'         => [
            'PRIMARY'       => ['column' => 'id', 'unique' => 1],
            'host_id'       => ['column' => 'host_id', 'unique' => 0],
            'parenthost_id' => ['column' => 'parenthost_id', 'unique' => 0],
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
        'id'                            => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name'                          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'                   => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_command_args'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_id'       => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'timeperiod_id'                 => ['type' => 'integer', 'null' => false, 'default' => null],
        'check_interval'                => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 5],
        'retry_interval'                => ['type' => 'integer', 'null' => false, 'default' => '3', 'length' => 5],
        'max_check_attempts'            => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3],
        'first_notification_delay'      => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notification_interval'         => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notify_on_down'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_unreachable'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_recovery'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_flapping'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_downtime'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_up'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_down'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_unreachable' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'low_flap_threshold'            => ['type' => 'float', 'null' => false, 'default' => '0'],
        'high_flap_threshold'           => ['type' => 'float', 'null' => false, 'default' => '0'],
        'process_performance_data'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_checks_enabled'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_threshold'           => ['type' => 'integer', 'null' => true, 'default' => '0', 'length' => 8],
        'passive_checks_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'event_handler_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'active_checks_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'retain_status_information'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'retain_nonstatus_information'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notifications_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notes'                         => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'priority'                      => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 2],
        'check_period_id'               => ['type' => 'integer', 'null' => false, 'default' => null],
        'notify_period_id'              => ['type' => 'integer', 'null' => false, 'default' => null],
        'tags'                          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'                  => ['type' => 'integer', 'null' => false, 'default' => null],
        'host_url'                      => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'                       => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                      => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                       => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
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
        'description'     => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $maps = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'title'           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'background'      => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $proxies = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'ipaddress'       => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'port'            => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 5],
        'enabled'         => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $reports = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'some'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
        'some2'           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
        'some3'           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
        'user_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
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

    public $serviceescalations = [
        'id'                    => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                  => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeperiod_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'first_notification'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'last_notification'     => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'notification_interval' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'escalate_on_recovery'  => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'escalate_on_warning'   => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'escalate_on_unknown'   => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'escalate_on_critical'  => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'created'               => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'              => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'               => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicegroups = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'             => ['type' => 'string', 'null' => false, 'length' => 37],
        'container_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'description'      => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'servicegroup_url' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicegroups_to_serviceescalations = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'servicegroup_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'serviceescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'excluded'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'servicegroup_id'      => ['column' => ['servicegroup_id', 'excluded'], 'unique' => 0],
            'serviceescalation_id' => ['column' => ['serviceescalation_id', 'excluded'], 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $services = [
        'id'                         => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                       => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'servicetemplate_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'host_id'                    => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'                       => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'                => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_id'                 => ['type' => 'integer', 'null' => true, 'default' => null],
        'check_command_args'         => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_id'    => ['type' => 'integer', 'null' => true, 'default' => null],
        'notify_period_id'           => ['type' => 'integer', 'null' => true, 'default' => null],
        'check_period_id'            => ['type' => 'integer', 'null' => true, 'default' => null],
        'check_interval'             => ['type' => 'float', 'null' => true, 'default' => null],
        'retry_interval'             => ['type' => 'float', 'null' => true, 'default' => null],
        'max_check_attempts'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'first_notification_delay'   => ['type' => 'float', 'null' => true, 'default' => null],
        'notification_interval'      => ['type' => 'float', 'null' => true, 'default' => null],
        'notify_on_warning'          => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_unknown'          => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_critical'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_recovery'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_flapping'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_downtime'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'is_volatile'                => ['type' => 'integer', 'null' => true, 'length' => 1],
        'flap_detection_enabled'     => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_on_ok'       => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_on_warning'  => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_on_unknown'  => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_on_critical' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'low_flap_threshold'         => ['type' => 'float', 'null' => true, 'default' => null],
        'high_flap_threshold'        => ['type' => 'float', 'null' => true, 'default' => null],
        'process_performance_data'   => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'freshness_checks_enabled'   => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 8],
        'freshness_threshold'        => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'passive_checks_enabled'     => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'event_handler_enabled'      => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'active_checks_enabled'      => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'notifications_enabled'      => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'notes'                      => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'priority'                   => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 2],
        'tags'                       => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'own_contacts'               => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'own_contactgroups'          => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'own_customvariables'        => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'service_url'                => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'service_type'               => ['type' => 'integer', 'null' => false, 'default' => 1],
        'disabled'                   => ['type' => 'integer', 'null' => true, 'default' => 0, 'length' => 1],
        'created'                    => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                   => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                    => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'            => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $services_to_serviceescalations = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'service_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'serviceescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'excluded'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'service_id'           => ['column' => ['service_id', 'excluded'], 'unique' => 0],
            'serviceescalation_id' => ['column' => ['serviceescalation_id', 'excluded'], 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $services_to_servicegroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'service_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicegroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'         => ['column' => 'id', 'unique' => 1],
            'service_id'      => ['column' => 'service_id', 'unique' => 0],
            'servicegroup_id' => ['column' => 'servicegroup_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
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
        'id'                           => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                         => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name'                         => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'                 => ['type' => 'integer', 'null' => true, 'default' => null],
        'servicetemplatetype_id'       => ['type' => 'integer', 'null' => false, 'default' => 1],
        'check_period_id'              => ['type' => 'integer', 'null' => true, 'default' => null],
        'notify_period_id'             => ['type' => 'integer', 'null' => true, 'default' => null],
        'description'                  => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_id'                   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_command_args'           => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'checkcommand_info'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_id'      => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'timeperiod_id'                => ['type' => 'integer', 'null' => false, 'default' => null],
        'check_interval'               => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 5],
        'retry_interval'               => ['type' => 'integer', 'null' => false, 'default' => '3', 'length' => 5],
        'max_check_attempts'           => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3],
        'first_notification_delay'     => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notification_interval'        => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notify_on_warning'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_unknown'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_critical'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_recovery'           => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'notify_on_flapping'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_downtime'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_enabled'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_ok'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_warning'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_unknown'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_critical'   => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'low_flap_threshold'           => ['type' => 'float', 'null' => false, 'default' => '0'],
        'high_flap_threshold'          => ['type' => 'float', 'null' => false, 'default' => '0'],
        'process_performance_data'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_checks_enabled'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_threshold'          => ['type' => 'integer', 'null' => true, 'default' => '0', 'length' => 8],
        'passive_checks_enabled'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'event_handler_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'active_checks_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'retain_status_information'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'retain_nonstatus_information' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notifications_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notes'                        => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'priority'                     => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 2],
        'tags'                         => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 1500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'service_url'                  => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_volatile'                  => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'check_freshness'              => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'service_url'                  => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'                      => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                     => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                      => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'              => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $systemfailures = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'start_time'      => ['type' => 'datetime', 'null' => false, 'default' => null],
        'end_time'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'comment'         => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'user_id'         => ['type' => 'integer', 'null' => true, 'default' => null],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
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

    public $tenants_to_maps = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'tenant_id'       => ['type' => 'integer', 'null' => false, 'default' => null],
        'map_id'          => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'   => ['column' => 'id', 'unique' => 1],
            'tenant_id' => ['column' => 'tenant_id', 'unique' => 0],
            'map_id'    => ['column' => 'map_id', 'unique' => 0],
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
        'timezone'        => ['type' => 'string', 'null' => true, 'default' => 'Europe/Berlin', 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'dateformat'      => ['type' => 'string', 'null' => true, 'default' => '%H:%M:%S - %d.%m.%Y', 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'image'           => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'onetimetoken'    => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'samaccountname'  => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $systemsettings = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'key'             => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'value'           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'info'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8', 'length' => 1500,],
        'section'         => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hostdependencies = [
        'id'                               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'inherits_parent'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'timeperiod_id'                    => ['type' => 'integer', 'null' => true, 'default' => null],
        'execution_fail_on_up'             => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_fail_on_down'           => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_fail_on_unreachable'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_fail_on_pending'        => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_none'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_up'          => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_down'        => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_unreachable' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_pending'     => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_none'                => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'created'                          => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'                  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_hostdependencies = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostdependency_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'dependent'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'host_id'           => ['column' => ['host_id', 'dependent'], 'unique' => 0],
            'hostdependency_id' => ['column' => ['hostdependency_id', 'dependent'], 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hostgroups_to_hostdependencies = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'hostgroup_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostdependency_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'dependent'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'hostgroup_id'      => ['column' => ['hostgroup_id', 'dependent'], 'unique' => 0],
            'hostdependency_id' => ['column' => ['hostdependency_id', 'dependent'], 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicedependencies = [
        'id'                            => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'inherits_parent'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'timeperiod_id'                 => ['type' => 'integer', 'null' => true, 'default' => null],
        'execution_fail_on_ok'          => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_fail_on_warning'     => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_fail_on_unknown'     => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_fail_on_critical'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_fail_on_pending'     => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_none'                => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_ok'       => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_on_warning'       => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_unknown'  => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_critical' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_pending'  => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_none'             => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'created'                       => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                      => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                       => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $services_to_servicedependencies = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'service_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicedependency_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'dependent'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'service_id'           => ['column' => ['service_id', 'dependent'], 'unique' => 0],
            'servicedependency_id' => ['column' => ['servicedependency_id', 'dependent'], 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicegroups_to_servicedependencies = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'servicegroup_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicedependency_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'dependent'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'servicegroup_id'      => ['column' => ['servicegroup_id', 'dependent'], 'unique' => 0],
            'servicedependency_id' => ['column' => ['servicedependency_id', 'dependent'], 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $systemdowntimes = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'objecttype_id'   => ['type' => 'integer', 'default' => null],
        'object_id'       => ['type' => 'integer', 'default' => null],
        'downtimetype_id' => ['type' => 'integer', 'default' => 0],
        'weekdays'        => ['type' => 'string', 'default' => null],
        'day_of_month'    => ['type' => 'string', 'default' => null],
        'from_time'       => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'to_time'         => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment'         => ['type' => 'string', 'default' => null],
        'author'          => ['type' => 'string', 'default' => null],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $cronjobs = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'task'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'plugin'          => ['type' => 'string', 'null' => false, 'default' => 'Core', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'interval'        => ['type' => 'integer', 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $cronschedules = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'cronjob_id'      => ['type' => 'integer', 'default' => null],
        'is_running'      => ['type' => 'integer', 'default' => null],
        'start_time'      => ['type' => 'datetime', 'null' => false, 'default' => null],
        'end_time'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $users_to_containers = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'user_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'      => ['column' => 'id', 'unique' => 1],
            'user_id'      => ['column' => 'user_id', 'unique' => 0],
            'container_id' => ['column' => 'container_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $mkchecks = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'               => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'servicetemplate_id' => ['type' => 'integer', 'default' => null],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $mkservicedata = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'service_id'      => ['type' => 'integer', 'null' => false],
        'host_id'         => ['type' => 'integer', 'null' => false],
        'is_process'      => ['type' => 'integer', 'null' => false],
        'service_id'      => ['type' => 'integer', 'null' => false],
        'check_name'      => ['type' => 'string', 'null' => false],
        'check_item'      => ['type' => 'string', 'null' => false],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $autoreports = [
        'id'                       => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'                     => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'              => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'             => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'timeperiod_id'            => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'report_interval'          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'report_send_interval'     => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'consider_downtimes'       => ['type' => 'boolean', 'null' => false, 'default' => null],
        'last_send_date'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'min_availability_percent' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'min_availability'         => ['type' => 'float', 'null' => true, 'default' => null, 'length' => '8,3'],
        'check_hard_state'         => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'use_start_time'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4],
        'report_start_date'        => ['type' => 'date', 'null' => false, 'default' => null],
        'last_percent_value'       => ['type' => 'float', 'null' => false, 'default' => null],
        'last_absolut_value'       => ['type' => 'integer', 'null' => false, 'default' => null],
        'show_time'                => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4],
        'last_number_of_outages'   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'failure_statistic'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4],
        'consider_holidays'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4],
        'calendar_id'              => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'max_number_of_outages'    => ['type' => 'integer', 'null' => true, 'default' => null],
        'created'                  => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                 => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                  => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'          => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_autoreports = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'autoreport_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'host_id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'outage_duration'      => ['type' => 'integer', 'null' => true, 'default' => null],
        'configuration_option' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4],
        'created'              => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'             => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'              => [
            'PRIMARY'            => ['column' => 'id', 'unique' => 1],
            'autoreport_id_host' => ['column' => ['autoreport_id', 'host_id'], 'unique' => 1],
            'host_id'            => ['column' => 'host_id', 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $services_to_autoreports = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'autoreport_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'host_id'              => ['type' => 'integer', 'null' => false, 'default' => null],
        'service_id'           => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'outage_duration'      => ['type' => 'integer', 'null' => true, 'default' => null],
        'configuration_option' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4],
        'created'              => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'             => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'              => [
            'PRIMARY'               => ['column' => 'id', 'unique' => 1],
            'autoreport_id_service' => ['column' => ['autoreport_id', 'service_id'], 'unique' => 1],
            'service_id'            => ['column' => 'service_id', 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $users_to_autoreports = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'autoreport_id'   => ['type' => 'integer', 'null' => false, 'default' => null],
        'user_id'         => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'user_id' => ['column' => 'user_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $satellites = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => false, 'length' => 255, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'address'         => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $deleted_services = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'               => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'host_uuid'          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null], 'host_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'               => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'        => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'deleted_perfdata'   => ['type' => 'integer', 'null' => true, 'default' => 0, 'length' => 1],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $deleted_hosts = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'hosttemplate_id'  => ['type' => 'integer', 'null' => false, 'default' => null], 'host_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'      => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'deleted_perfdata' => ['type' => 'integer', 'null' => true, 'default' => 0, 'length' => 1],
        'created'          => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicetemplategroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicetemplates_to_servicetemplategroups = [
        'id'                      => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'servicetemplate_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicetemplategroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'                 => [
            'PRIMARY'                 => ['column' => 'id', 'unique' => 1],
            'servicetemplategroup_id' => ['column' => 'servicetemplategroup_id', 'unique' => 0],
            'servicetemplate_id'      => ['column' => 'servicetemplate_id', 'unique' => 0],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $registers = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'license'         => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'accepted'        => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];


    public $mapitems = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'map_id'          => ['type' => 'integer', 'null' => false, 'default' => null],
        'x'               => ['type' => 'integer', 'null' => false, 'default' => 0],
        'y'               => ['type' => 'integer', 'null' => false, 'default' => 0],
        'limit'           => ['type' => 'integer', 'null' => true, 'default' => 0],
        'iconset'         => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'type'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => null],
        'itemType'        => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'itemTypeId'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $maplines = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'map_id'          => ['type' => 'integer', 'null' => false, 'default' => null],
        'startX'          => ['type' => 'integer', 'null' => false, 'default' => 0],
        'startY'          => ['type' => 'integer', 'null' => false, 'default' => 0],
        'endX'            => ['type' => 'integer', 'null' => false, 'default' => 0],
        'endY'            => ['type' => 'integer', 'null' => false, 'default' => 0],
        'limit'           => ['type' => 'integer', 'null' => true, 'default' => 0],
        'mapitem_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'iconset'         => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'type'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => null],
        'itemType'        => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'itemTypeId'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $mapgadgets = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'map_id'          => ['type' => 'integer', 'null' => false, 'default' => null],
        'x'               => ['type' => 'integer', 'null' => false, 'default' => 0],
        'y'               => ['type' => 'integer', 'null' => false, 'default' => 0],
        'limit'           => ['type' => 'integer', 'null' => true, 'default' => 0],
        'gadget'          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'type'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => null],
        'itemType'        => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'itemTypeId'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $graphgen_tmpls = [
        'id'            => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'          => ['type' => 'string', 'null' => false, 'length' => 128],
        'relative_time' => ['type' => 'integer', 'null' => false],
        'indexes'       => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
    ];

    public $graphgen_tmpl_confs = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'graphgen_tmpl_id' => ['type' => 'integer', 'null' => false],
        'service_id'       => ['type' => 'integer', 'null' => false],
        'data_sources'     => ['type' => 'string', 'length' => 256, 'null' => false],
        'indexes'          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
    ];

    public $autoreport_settings = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'configuration_option' => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'store_path'           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'              => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'             => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'              => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    // INSERT INTO `calendars` (name, description, user_id, tenant_id, created,
    // modified) VALUES
    // ('First Cal', 'First Desc', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
    public $calendars = [
        'id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'        => ['type' => 'string', 'null' => false],
        'description' => ['type' => 'string', 'null' => false, 'default' => ''],
        'user_id'     => ['type' => 'integer', 'null' => false], // ID of the user who created or changed this date.
        'tenant_id'   => ['type' => 'integer', 'null' => false], // ID from the tentant of the user.
        'created'     => ['type' => 'datetime', 'null' => false, 'default' => null], // CakePHP will update this field automatically (if not set with data).
        'modified'    => ['type' => 'datetime', 'null' => false, 'default' => null], // CakePHP will update this field automatically (if not set with data).
        'indexes'     => [
            'PRIMARY'     => ['column' => 'id', 'unique' => 1],
            'UNIQUE_NAME' => ['column' => ['tenant_id', 'name'], 'unique' => 1],
        ],
    ];

    // INSERT INTO calendar_holidays (`calendar_id`, `name`, `date`) VALUES
    // (1, '1. Weihnachtsfeiertag', '2015-12-25');
    public $calendar_holidays = [
        'id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'calendar_id' => ['type' => 'integer', 'null' => false],
        'name'        => ['type' => 'string', 'null' => false], // The name of the holiday.
        'date'        => ['type' => 'date', 'null' => false], // The date of the holiday.
        'indexes'     => [
            'PRIMARY'     => ['column' => 'id', 'unique' => 1],
            'UNIQUE_DATE' => ['column' => ['calendar_id', 'date'], 'unique' => 1],
        ],
    ];

    public $graphgen_collections = [
        'id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'        => ['type' => 'string', 'null' => false],
        'description' => ['type' => 'string', 'null' => false],
        'indexes'     => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
    ];

    public $graphgen_collection_items = [
        'id'                    => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'graphgen_tmpl_id'      => ['type' => 'integer', 'null' => false],
        'graphgen_colletion_id' => ['type' => 'integer', 'null' => false],
        'indexes'               => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
    ];
}
