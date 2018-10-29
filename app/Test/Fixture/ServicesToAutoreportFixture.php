<?php

/**
 * ServicesToAutoreport Fixture
 */
class ServicesToAutoreportFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'autoreport_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'host_id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'service_id'           => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'outage_duration'      => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'configuration_option' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'unsigned' => false],
        'created'              => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'             => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'              => [
            'PRIMARY'               => ['column' => 'id', 'unique' => 1],
            'autoreport_id_service' => ['column' => ['autoreport_id', 'service_id'], 'unique' => 1],
            'service_id'            => ['column' => 'service_id', 'unique' => 0]
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'                   => 1,
            'autoreport_id'        => 1,
            'host_id'              => 1,
            'service_id'           => 1,
            'outage_duration'      => 1,
            'configuration_option' => 1,
            'created'              => '2017-01-27 17:52:08',
            'modified'             => '2017-01-27 17:52:08'
        ],
    ];

}
