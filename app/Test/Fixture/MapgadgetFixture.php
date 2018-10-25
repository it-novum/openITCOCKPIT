<?php

/**
 * Mapgadget Fixture
 */
class MapgadgetFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                     => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'map_id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'x'                      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'y'                      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'limit'                  => ['type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false],
        'gadget'                 => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'type'                   => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'object_id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'transparent_background' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5, 'unsigned' => false],
        'created'                => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'               => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'                     => 1,
            'map_id'                 => 1,
            'x'                      => 1,
            'y'                      => 1,
            'limit'                  => 1,
            'gadget'                 => 'Lorem ipsum dolor sit amet',
            'type'                   => 'Lorem ipsum dolor ',
            'object_id'              => 1,
            'transparent_background' => 1,
            'created'                => '2017-01-27 15:58:11',
            'modified'               => '2017-01-27 15:58:11'
        ],
    ];

}
