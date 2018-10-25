<?php

/**
 * MapsToRotation Fixture
 */
class MapsToRotationFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'map_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'rotation_id'     => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'indexes'         => [
            'PRIMARY'     => ['column' => 'id', 'unique' => 1],
            'map_id'      => ['column' => 'map_id', 'unique' => 0],
            'rotation_id' => ['column' => 'rotation_id', 'unique' => 0]
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
            'map_id'      => 1,
            'rotation_id' => 1
        ],
    ];

}
