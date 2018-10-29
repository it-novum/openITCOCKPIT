<?php

/**
 * NagiosHostescalationContactgroup Fixture
 */
class NagiosHostescalationContactgroupFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'hostescalation_contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'hostescalation_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'contactgroup_object_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                        => [
            'PRIMARY'     => ['column' => 'hostescalation_contactgroup_id', 'unique' => 1],
            'instance_id' => ['column' => ['hostescalation_id', 'contactgroup_object_id'], 'unique' => 1]
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Host escalation contact groups']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'hostescalation_contactgroup_id' => 1,
            'instance_id'                    => 1,
            'hostescalation_id'              => 1,
            'contactgroup_object_id'         => 1
        ],
    ];

}
