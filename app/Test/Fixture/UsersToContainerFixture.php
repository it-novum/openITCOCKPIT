<?php

/**
 * UsersToContainer Fixture
 */
class UsersToContainerFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'user_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'container_id'     => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'permission_level' => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 1, 'unsigned' => false],
        'indexes'          => [
            'PRIMARY'      => ['column' => 'id', 'unique' => 1],
            'user_id'      => ['column' => 'user_id', 'unique' => 0],
            'container_id' => ['column' => 'container_id', 'unique' => 0]
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
            'user_id'          => 1,
            'container_id'     => 1,
            'permission_level' => 1
        ],
    ];
}
