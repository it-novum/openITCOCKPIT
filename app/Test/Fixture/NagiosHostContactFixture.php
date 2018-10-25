<?php

/**
 * NagiosHostContact Fixture
 */
class NagiosHostContactFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'host_contact_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'host_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'contact_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'           => [
            'PRIMARY'     => ['column' => 'host_contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'host_id', 'contact_object_id'], 'unique' => 1]
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'host_contact_id'   => 1,
            'instance_id'       => 1,
            'host_id'           => 1,
            'contact_object_id' => 1
        ],
    ];

}
