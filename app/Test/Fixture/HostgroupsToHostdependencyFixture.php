<?php

/**
 * HostgroupsToHostdependency Fixture
 */
class HostgroupsToHostdependencyFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'hostgroup_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'hostdependency_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'dependent'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'hostgroup_id'      => ['column' => ['hostgroup_id', 'dependent'], 'unique' => 0],
            'hostdependency_id' => ['column' => ['hostdependency_id', 'dependent'], 'unique' => 0]
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
            'hostdependency_id' => 1,
            'dependent'         => 1
        ],
    ];

}
