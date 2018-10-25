<?php

/**
 * NagiosRuntimevariable Fixture
 */
class NagiosRuntimevariableFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'runtimevariable_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'varname'            => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'varvalue'           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'            => [
            'PRIMARY'     => ['column' => 'runtimevariable_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'varname'], 'unique' => 1]
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Runtime variables from the Nagios daemon']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'runtimevariable_id' => 1,
            'instance_id'        => 1,
            'varname'            => 'Lorem ipsum dolor sit amet',
            'varvalue'           => 'Lorem ipsum dolor sit amet'
        ],
    ];

}
