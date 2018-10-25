<?php

/**
 * NagiosCustomvariable Fixture
 */
class NagiosCustomvariableFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'customvariable_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'config_type'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'has_been_modified' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'varname'           => ['type' => 'string', 'null' => false, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'varvalue'          => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'           => [
            'PRIMARY'     => ['column' => 'customvariable_id', 'unique' => 1],
            'object_id_2' => ['column' => ['object_id', 'config_type', 'varname'], 'unique' => 1],
            'varname'     => ['column' => 'varname', 'unique' => 0]
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Custom variables']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'customvariable_id' => 1,
            'instance_id'       => 1,
            'object_id'         => 1,
            'config_type'       => 1,
            'has_been_modified' => 1,
            'varname'           => 'Lorem ipsum dolor sit amet',
            'varvalue'          => 'Lorem ipsum dolor sit amet'
        ],
    ];

}
