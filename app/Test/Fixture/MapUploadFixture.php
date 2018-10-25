<?php

/**
 * MapUpload Fixture
 */
class MapUploadFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'upload_type'     => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false],
        'upload_name'     => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'saved_name'      => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'user_id'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false],
        'container_id'    => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
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
            'upload_type'  => 1,
            'upload_name'  => 'Lorem ipsum dolor sit amet',
            'saved_name'   => 'Lorem ipsum dolor sit amet',
            'user_id'      => 1,
            'container_id' => 1,
            'created'      => '2017-01-27 15:57:34'
        ],
    ];

}
