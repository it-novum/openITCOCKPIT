<?php

/**
 * Command Fixture
 */
class CommandFixture extends CakeTestFixture {
    /**
     * Fields
     *
     * @var array
     */

    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_line'    => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_type'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'human_args'      => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'uuid'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'     => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
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
            'id'           => 1,
            'name'         => 'My first command',
            'command_line' => 'My first command_line',
            'command_type' => 1,
            'human_args'   => 'My first human_args',
            'uuid'         => '1234567890',
            'description'  => 'My first human_args'
        ],
        [
            'id'           => 2,
            'name'         => 'My second command',
            'command_line' => 'My second command_line',
            'command_type' => 2,
            'human_args'   => 'My second human_args',
            'uuid'         => '0987654321',
            'description'  => 'My second human_args'
        ],
    ];

}
