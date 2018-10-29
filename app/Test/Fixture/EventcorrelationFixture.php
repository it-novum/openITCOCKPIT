<?php

/**
 * Eventcorrelation Fixture
 */
class EventcorrelationFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'parent_id'       => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'lft'             => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'rght'            => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'host_id'         => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'service_id'      => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'operator'        => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 12, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY'    => ['column' => 'id', 'unique' => 1],
            'host_id'    => ['column' => 'host_id', 'unique' => 0],
            'lft'        => ['column' => 'lft', 'unique' => 0],
            'rght'       => ['column' => 'rght', 'unique' => 0],
            'parent_id'  => ['column' => 'parent_id', 'unique' => 0],
            'service_id' => ['column' => 'service_id', 'unique' => 0]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'         => 1,
            'parent_id'  => 1,
            'lft'        => 1,
            'rght'       => 1,
            'host_id'    => 1,
            'service_id' => 1,
            'operator'   => 'Lorem ipsu'
        ],
    ];

}
