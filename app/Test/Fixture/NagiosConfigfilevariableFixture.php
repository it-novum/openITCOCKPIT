<?php

/**
 * NagiosConfigfilevariable Fixture
 */
class NagiosConfigfilevariableFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'configfilevariable_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'configfile_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'varname'               => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'varvalue'              => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'               => [
            'PRIMARY'     => ['column' => 'configfilevariable_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'configfile_id'], 'unique' => 0]
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Configuration file variables']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'configfilevariable_id' => 1,
            'instance_id'           => 1,
            'configfile_id'         => 1,
            'varname'               => 'Lorem ipsum dolor sit amet',
            'varvalue'              => 'Lorem ipsum dolor sit amet'
        ],
    ];

}
