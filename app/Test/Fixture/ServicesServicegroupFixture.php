<?php

class ServicesServicegroupFixture extends CakeTestFixture {

    public $table = 'services_to_servicegroups';

    public $fields = [
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
}

