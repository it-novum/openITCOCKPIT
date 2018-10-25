<?php

/**
 * NagiosContactAddress Fixture
 */
class NagiosContactAddressFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'contact_address_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contact_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'address_number'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'address'            => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'            => [
            'PRIMARY'    => ['column' => 'contact_address_id', 'unique' => 1],
            'contact_id' => ['column' => ['contact_id', 'address_number'], 'unique' => 1]
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Contact addresses']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'contact_address_id' => 1,
            'instance_id'        => 1,
            'contact_id'         => 1,
            'address_number'     => 1,
            'address'            => 'Lorem ipsum dolor sit amet'
        ],
    ];

}
