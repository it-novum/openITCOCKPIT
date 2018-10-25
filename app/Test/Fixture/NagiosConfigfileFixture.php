<?php

/**
 * NagiosConfigfile Fixture
 */
class NagiosConfigfileFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'configfile_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'configfile_type' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'configfile_path' => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY'     => ['column' => 'configfile_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'configfile_type', 'configfile_path'], 'unique' => 1]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Configuration files']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'configfile_id'   => 1,
            'instance_id'     => 1,
            'configfile_type' => 1,
            'configfile_path' => 'Lorem ipsum dolor sit amet'
        ],
    ];

}
