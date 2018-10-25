<?php

/**
 * Aco Fixture
 */
class AcoFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'key' => 'primary'],
        'parent_id'       => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false],
        'model'           => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'foreign_key'     => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false],
        'alias'           => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'lft'             => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false],
        'rght'            => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false],
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
            'id'          => 1,
            'parent_id'   => 1,
            'model'       => 'Lorem ipsum dolor sit amet',
            'foreign_key' => 1,
            'alias'       => 'Lorem ipsum dolor sit amet',
            'lft'         => 1,
            'rght'        => 1
        ],
    ];

}
