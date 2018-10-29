<?php

/**
 * NagiosHostParenthost Fixture
 */
class NagiosHostParenthostFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'host_parenthost_id'    => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_id'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'parent_host_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'               => [
            'PRIMARY'     => ['column' => 'host_parenthost_id', 'unique' => 1],
            'instance_id' => ['column' => ['host_id', 'parent_host_object_id'], 'unique' => 1]
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Parent hosts']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'host_parenthost_id'    => 1,
            'instance_id'           => 1,
            'host_id'               => 1,
            'parent_host_object_id' => 1
        ],
    ];

}
