<?php

/**
 * Tenant Fixture
 */
class TenantFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'description'     => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_active'       => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'date'            => ['type' => 'date', 'null' => true, 'default' => null],
        'number_users'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false],
        'max_users'       => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false],
        'number_hosts'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false],
        'max_hosts'       => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false],
        'number_services' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false],
        'max_services'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false],
        'firstname'       => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'lastname'        => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'street'          => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'zipcode'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false],
        'city'            => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
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
            'id'              => 1,
            'container_id'    => 1,
            'description'     => 'Lorem ipsum dolor sit amet',
            'is_active'       => 1,
            'date'            => '2017-01-27',
            'number_users'    => 1,
            'max_users'       => 1,
            'number_hosts'    => 1,
            'max_hosts'       => 1,
            'number_services' => 1,
            'max_services'    => 1,
            'firstname'       => 'Lorem ipsum dolor sit amet',
            'lastname'        => 'Lorem ipsum dolor sit amet',
            'street'          => 'Lorem ipsum dolor sit amet',
            'zipcode'         => 1,
            'city'            => 'Lorem ipsum dolor sit amet',
            'created'         => '2017-01-27 14:13:37',
            'modified'        => '2017-01-27 14:13:37'
        ],
    ];

}
