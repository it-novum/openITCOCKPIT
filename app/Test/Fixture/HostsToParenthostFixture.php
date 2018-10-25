<?php

/**
 * HostsToParenthost Fixture
 */
class HostsToParenthostFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'host_id'         => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'parenthost_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'indexes'         => [
            'PRIMARY'       => ['column' => 'id', 'unique' => 1],
            'host_id'       => ['column' => 'host_id', 'unique' => 0],
            'parenthost_id' => ['column' => 'parenthost_id', 'unique' => 0]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'            => 1,
            'host_id'       => 1,
            'parenthost_id' => 1
        ],
    ];

}
