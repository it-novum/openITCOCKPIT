<?php

/**
 * Location Fixture
 */
class LocationFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'description'     => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'latitude'        => ['type' => 'float', 'null' => true, 'default' => '0', 'unsigned' => false],
        'longitude'       => ['type' => 'float', 'null' => true, 'default' => '0', 'unsigned' => false],
        'timezone'        => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1]
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
            'uuid'         => 'Lorem ipsum dolor sit amet',
            'container_id' => 1,
            'description'  => 'Lorem ipsum dolor sit amet',
            'latitude'     => 1,
            'longitude'    => 1,
            'timezone'     => 'Lorem ipsum dolor sit amet',
            'created'      => '2017-01-27 15:56:25',
            'modified'     => '2017-01-27 15:56:25'
        ],
    ];

}
