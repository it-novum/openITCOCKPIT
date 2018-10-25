<?php

/**
 * HostsToHostescalation Fixture
 */
class HostsToHostescalationFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'host_id'           => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'hostescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'excluded'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'unsigned' => false],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'host_id'           => ['column' => ['host_id', 'excluded'], 'unique' => 0],
            'hostescalation_id' => ['column' => ['hostescalation_id', 'excluded'], 'unique' => 0]
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
            'id'                => 1,
            'host_id'           => 1,
            'hostescalation_id' => 1,
            'excluded'          => 1
        ],
    ];

}
