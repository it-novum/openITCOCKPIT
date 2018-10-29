<?php

/**
 * Maptext Fixture
 */
class MaptextFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'map_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'x'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'y'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'text'            => ['type' => 'string', 'null' => false, 'default' => '0', 'length' => 256, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'font_size'       => ['type' => 'integer', 'null' => false, 'default' => '11', 'unsigned' => false],
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
            'x'         => 1,
            'y'         => 1,
            'text'      => 'Lorem ipsum dolor sit amet',
            'font_size' => 1,
            'created'   => '2017-01-27 16:02:53',
            'modified'  => '2017-01-27 16:02:53'
        ],
    ];

}
