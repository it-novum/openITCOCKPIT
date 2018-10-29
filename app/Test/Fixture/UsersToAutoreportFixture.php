<?php

/**
 * UsersToAutoreport Fixture
 */
class UsersToAutoreportFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'autoreport_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'user_id'         => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'user_id' => ['column' => 'user_id', 'unique' => 0]
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
            'id'            => 1,
            'autoreport_id' => 1,
            'user_id'       => 1
        ],
    ];

}
