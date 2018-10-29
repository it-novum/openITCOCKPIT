<?php

/**
 * NagiosServicegroup Fixture
 */
class NagiosServicegroupFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'servicegroup_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'servicegroup_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'alias'                  => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'servicegroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'servicegroup_object_id'], 'unique' => 1]
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Servicegroup definitions']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'servicegroup_id'        => 1,
            'instance_id'            => 1,
            'config_type'            => 1,
            'servicegroup_object_id' => 1,
            'alias'                  => 'Lorem ipsum dolor sit amet'
        ],
    ];

}
