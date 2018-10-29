<?php

/**
 * NagiosHostgroupMember Fixture
 */
class NagiosHostgroupMemberFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'hostgroup_member_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'hostgroup_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'host_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'             => [
            'PRIMARY'     => ['column' => 'hostgroup_member_id', 'unique' => 1],
            'instance_id' => ['column' => ['hostgroup_id', 'host_object_id'], 'unique' => 1]
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Hostgroup members']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'hostgroup_member_id' => 1,
            'instance_id'         => 1,
            'hostgroup_id'        => 1,
            'host_object_id'      => 1
        ],
    ];

}
