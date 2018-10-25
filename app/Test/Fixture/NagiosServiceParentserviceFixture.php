<?php

/**
 * NagiosServiceParentservice Fixture
 */
class NagiosServiceParentserviceFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'service_parentservice_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'service_id'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'parent_service_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                  => [
            'PRIMARY'     => ['column' => 'service_parentservice_id', 'unique' => 1],
            'instance_id' => ['column' => ['service_id', 'parent_service_object_id'], 'unique' => 1]
        ],
        'tableParameters'          => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Parent services']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'service_parentservice_id' => 1,
            'instance_id'              => 1,
            'service_id'               => 1,
            'parent_service_object_id' => 1
        ],
    ];

}
