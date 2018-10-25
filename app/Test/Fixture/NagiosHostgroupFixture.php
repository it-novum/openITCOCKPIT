<?php

/**
 * NagiosHostgroup Fixture
 */
class NagiosHostgroupFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'hostgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'hostgroup_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'alias'               => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'             => [
            'PRIMARY'     => ['column' => 'hostgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'hostgroup_object_id'], 'unique' => 1]
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Hostgroup definitions']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'hostgroup_id'        => 1,
            'instance_id'         => 1,
            'config_type'         => 1,
            'hostgroup_object_id' => 1,
            'alias'               => 'Lorem ipsum dolor sit amet'
        ],
    ];

}
