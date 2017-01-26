<?php
class HostsHostescalationFixture extends CakeTestFixture
{

    public $table = 'hosts_to_hostescalations';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'excluded' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'host_id' => ['column' => 'host_id', 'unique' => 0],
            'hostescalation_id' => ['column' => 'hostescalation_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];
}

