<?php

/**
 * NagiosCommand Fixture
 */
class NagiosCommandFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'command_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'command_line'    => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 511, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY'     => ['column' => 'command_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'object_id', 'config_type'], 'unique' => 1],
            'object_id'   => ['column' => 'object_id', 'unique' => 0]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Command definitions']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'command_id'   => 1,
            'instance_id'  => 1,
            'config_type'  => 1,
            'object_id'    => 1,
            'command_line' => 'Lorem ipsum dolor sit amet'
        ],
    ];

}
