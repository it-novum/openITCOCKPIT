<?php

/**
 * HostsToHostdependency Fixture
 */
class HostsToHostdependencyFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'host_id'           => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'hostdependency_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'dependent'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'host_id'           => ['column' => ['host_id', 'dependent'], 'unique' => 0],
            'hostdependency_id' => ['column' => ['hostdependency_id', 'dependent'], 'unique' => 0]
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'                => 1,
            'host_id'           => 1,
            'hostdependency_id' => 1,
            'dependent'         => 1
        ],
    ];

}
