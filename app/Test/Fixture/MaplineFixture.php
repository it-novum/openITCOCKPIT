<?php

/**
 * Mapline Fixture
 */
class MaplineFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'map_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'startX'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'startY'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'endX'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'endY'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'limit'           => ['type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false],
        'iconset'         => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'type'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'object_id'       => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
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
            'id'        => 1,
            'map_id'    => 1,
            'startX'    => 1,
            'startY'    => 1,
            'endX'      => 1,
            'endY'      => 1,
            'limit'     => 1,
            'iconset'   => 'Lorem ipsum dolor sit amet',
            'type'      => 'Lorem ipsum dolor ',
            'object_id' => 1,
            'created'   => '2017-01-27 16:00:10',
            'modified'  => '2017-01-27 16:00:10'
        ],
    ];

}
