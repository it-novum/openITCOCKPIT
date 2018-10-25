<?php

class HostsParenthostFixture extends CakeTestFixture {

    public $table = 'hosts_to_parenthosts';

    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'parenthost_id'   => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'       => ['column' => 'id', 'unique' => 1],
            'host_id'       => ['column' => 'host_id', 'unique' => 0],
            'parenthost_id' => ['column' => 'parenthost_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];
}





