<?php

/**
 * ServicegroupsToServicedependency Fixture
 */
class ServicegroupsToServicedependencyFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'servicegroup_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'servicedependency_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'dependent'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'servicegroup_id'      => ['column' => ['servicegroup_id', 'dependent'], 'unique' => 0],
            'servicedependency_id' => ['column' => ['servicedependency_id', 'dependent'], 'unique' => 0]
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
            'servicegroup_id'      => 1,
            'servicedependency_id' => 1,
            'dependent'            => 1
        ],
    ];

}
