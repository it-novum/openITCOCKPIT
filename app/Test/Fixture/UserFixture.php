<?php

/**
 * User Fixture
 */
class UserFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                     => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'key' => 'primary'],
        'usergroup_id'           => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'status'                 => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3, 'unsigned' => false],
        'email'                  => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'password'               => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'firstname'              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'lastname'               => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'position'               => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'company'                => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'phone'                  => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'timezone'               => ['type' => 'string', 'null' => true, 'default' => 'Europe/Berlin', 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'dateformat'             => ['type' => 'string', 'null' => true, 'default' => '%H:%M:%S - %d.%m.%Y', 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'image'                  => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'onetimetoken'           => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'samaccountname'         => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'showstatsinmenu'        => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'dashboard_tab_rotation' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'unsigned' => false],
        'paginatorlength'        => ['type' => 'integer', 'null' => false, 'default' => '25', 'length' => 4, 'unsigned' => false],
        'created'                => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified'               => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes'                => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'                     => 1,
            'usergroup_id'           => 1,
            'status'                 => 1,
            'email'                  => 'Lorem ipsum dolor sit amet',
            'password'               => 'Lorem ipsum dolor sit amet',
            'firstname'              => 'Lorem ipsum dolor sit amet',
            'lastname'               => 'Lorem ipsum dolor sit amet',
            'position'               => 'Lorem ipsum dolor sit amet',
            'company'                => 'Lorem ipsum dolor sit amet',
            'phone'                  => 'Lorem ipsum dolor sit amet',
            'timezone'               => 'Lorem ipsum dolor sit amet',
            'dateformat'             => 'Lorem ipsum dolor sit amet',
            'image'                  => 'Lorem ipsum dolor sit amet',
            'onetimetoken'           => 'Lorem ipsum dolor sit amet',
            'samaccountname'         => 'Lorem ipsum dolor sit amet',
            'showstatsinmenu'        => 1,
            'dashboard_tab_rotation' => 1,
            'paginatorlength'        => 1,
            'created'                => '2017-01-30 09:22:17',
            'modified'               => '2017-01-30 09:22:17'
        ],
    ];

}
