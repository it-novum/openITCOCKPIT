<?php

/**
 * HostgroupsToHostescalation Fixture
 */
class HostgroupsToHostescalationFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'hostgroup_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'hostescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'excluded'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'unsigned' => false],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'hostgroup_id'      => ['column' => ['hostgroup_id', 'excluded'], 'unique' => 0],
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
            'hostgroup_id'      => 1,
            'hostescalation_id' => 1,
            'excluded'          => 1
        ],
    ];

}
