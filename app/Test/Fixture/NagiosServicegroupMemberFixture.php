<?php

/**
 * NagiosServicegroupMember Fixture
 */
class NagiosServicegroupMemberFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'servicegroup_member_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'servicegroup_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'service_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                => [
            'PRIMARY'     => ['column' => 'servicegroup_member_id', 'unique' => 1],
            'instance_id' => ['column' => ['servicegroup_id', 'service_object_id'], 'unique' => 1]
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Servicegroup members']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'servicegroup_member_id' => 1,
            'instance_id'            => 1,
            'servicegroup_id'        => 1,
            'service_object_id'      => 1
        ],
    ];

}
