<?php

/**
 * ContactgroupsToServiceescalation Fixture
 */
class ContactgroupsToServiceescalationFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'contactgroup_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'serviceescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'contactgroup_id'      => ['column' => 'contactgroup_id', 'unique' => 0],
            'serviceescalation_id' => ['column' => 'serviceescalation_id', 'unique' => 0]
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
            'contactgroup_id'      => 1,
            'serviceescalation_id' => 1
        ],
    ];

}
