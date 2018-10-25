<?php

/**
 * Contactgroup Fixture
 */
class ContactgroupFixture extends CakeTestFixture {

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
            'description'  => 'Lorem ipsum dolor sit amet'
        ],
    ];

}
