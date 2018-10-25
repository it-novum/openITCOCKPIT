<?php

class HostsHostdependencyFixture extends CakeTestFixture {

    public $table = 'hosts_to_hostdependencies';

    public $fields = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostdependency_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'dependent'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'host_id'           => ['column' => 'host_id', 'unique' => 0],
            'hostdependency_id' => ['column' => 'hostdependency_id', 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];
}



