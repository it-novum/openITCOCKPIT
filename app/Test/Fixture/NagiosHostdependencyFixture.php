<?php

/**
 * NagiosHostdependency Fixture
 */
class NagiosHostdependencyFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'hostdependency_id'        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'dependent_host_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'dependency_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'inherits_parent'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'timeperiod_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'fail_on_up'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'fail_on_down'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'fail_on_unreachable'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'                  => [
            'PRIMARY'     => ['column' => 'hostdependency_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'host_object_id', 'dependent_host_object_id', 'dependency_type', 'inherits_parent', 'fail_on_up', 'fail_on_down', 'fail_on_unreachable'], 'unique' => 1]
        ],
        'tableParameters'          => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Host dependency definitions']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'hostdependency_id'        => 1,
            'instance_id'              => 1,
            'config_type'              => 1,
            'host_object_id'           => 1,
            'dependent_host_object_id' => 1,
            'dependency_type'          => 1,
            'inherits_parent'          => 1,
            'timeperiod_object_id'     => 1,
            'fail_on_up'               => 1,
            'fail_on_down'             => 1,
            'fail_on_unreachable'      => 1
        ],
    ];

}
