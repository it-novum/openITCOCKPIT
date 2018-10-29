<?php

/**
 * Register Fixture
 */
class RegisterFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'license'         => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'accepted'        => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'apt'             => ['type' => 'boolean', 'null' => false, 'default' => '0'],
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
            'id'       => 1,
            'license'  => 'Lorem ipsum dolor sit amet',
            'accepted' => 1,
            'apt'      => 1,
            'created'  => '2017-01-27 17:32:00',
            'modified' => '2017-01-27 17:32:00'
        ],
    ];

}
