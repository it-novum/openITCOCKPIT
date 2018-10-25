<?php

/**
 * Commandargument Fixture
 */
class CommandargumentFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'command_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'human_name'      => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'export'  => ['column' => ['command_id', 'name', 'human_name'], 'unique' => 0]
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
            'id'         => 1,
            'command_id' => 1,
            'name'       => 'My name',
            'human_name' => 'My human_name',
            'created'    => '2017-01-17 14:24:01',
            'modified'   => '2017-01-17 14:24:01'
        ],
        [
            'id'         => 2,
            'command_id' => 1,
            'name'       => 'My name 2',
            'human_name' => 'My human_name 2',
            'created'    => '2017-01-17 14:24:02',
            'modified'   => '2017-01-17 14:24:02'
        ],
        [
            'id'         => 3,
            'command_id' => 2,
            'name'       => 'My name 3',
            'human_name' => 'My human_name 3',
            'created'    => '2017-01-17 14:24:01',
            'modified'   => '2017-01-17 14:24:01'
        ],
        [
            'id'         => 4,
            'command_id' => 2,
            'name'       => 'My name 4',
            'human_name' => 'My human_name 4',
            'created'    => '2017-01-17 14:24:02',
            'modified'   => '2017-01-17 14:24:02'
        ],
        [
            'id'         => 5,
            'command_id' => 2,
            'name'       => 'My name 5',
            'human_name' => 'My human_name 5',
            'created'    => '2017-01-17 14:24:02',
            'modified'   => '2017-01-17 14:24:02'
        ],
    ];

}
