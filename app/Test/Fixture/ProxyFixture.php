<?php

/**
 * Proxy Fixture
 */
class ProxyFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'ipaddress'       => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'port'            => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 5, 'unsigned' => false],
        'enabled'         => ['type' => 'boolean', 'null' => false, 'default' => '1'],
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
            'id'        => 1,
            'ipaddress' => 'Lorem ipsum dolor sit amet',
            'port'      => 1,
            'enabled'   => 1
        ],
    ];

}
