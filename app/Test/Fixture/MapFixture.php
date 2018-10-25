<?php

/**
 * Map Fixture
 */
class MapFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'name'             => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'title'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'background'       => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'refresh_interval' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'created'          => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'               => 1,
            'name'             => 'Lorem ipsum dolor sit amet',
            'title'            => 'Lorem ipsum dolor sit amet',
            'background'       => 'Lorem ipsum dolor sit amet',
            'refresh_interval' => 1,
            'created'          => '2017-01-27 16:00:51',
            'modified'         => '2017-01-27 16:00:51'
        ],
    ];

}
