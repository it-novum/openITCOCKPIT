<?php

class ServicesServicedependencyFixture extends CakeTestFixture {

    public $table = 'services_to_servicedependencies';

    public $fields = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'service_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicedependency_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'dependent'            => ['type' => 'integer', 'null' => false, 'default' => 0],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'service_id'           => ['column' => 'service_id', 'unique' => 0],
            'servicedependency_id' => ['column' => 'servicedependency_id', 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];
}
