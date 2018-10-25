<?php

/**
 * NagiosContactgroupMember Fixture
 */
class NagiosContactgroupMemberFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'contactgroup_member_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contactgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'contact_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'contactgroup_member_id', 'unique' => 1],
            'instance_id' => ['column' => ['contactgroup_id', 'contact_object_id'], 'unique' => 1]
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Contactgroup members']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'contactgroup_member_id' => 1,
            'instance_id'            => 1,
            'contactgroup_id'        => 1,
            'contact_object_id'      => 1
        ],
    ];

}
