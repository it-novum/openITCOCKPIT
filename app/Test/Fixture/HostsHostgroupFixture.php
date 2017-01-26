<?php
class HostsHostgroupFixture extends CakeTestFixture{

    public $table = 'hosts_to_hostgroups';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'host_id' => ['column' => 'host_id', 'unique' => 0],
            'hostgroup_id' => ['column' => 'hostgroup_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];
}
