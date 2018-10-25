<?php

/**
 * ContactsToContainer Fixture
 */
class ContactsToContainerFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'indexes'         => [
            'PRIMARY'      => ['column' => 'id', 'unique' => 1],
            'contact_id'   => ['column' => 'contact_id', 'unique' => 0],
            'container_id' => ['column' => 'container_id', 'unique' => 0]
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
            'contact_id'   => 1,
            'container_id' => 1
        ],
    ];

}
