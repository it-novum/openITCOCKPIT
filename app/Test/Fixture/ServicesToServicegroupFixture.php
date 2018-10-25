<?php

/**
 * ServicesToServicegroup Fixture
 */
class ServicesToServicegroupFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'service_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'servicegroup_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'indexes'         => [
            'PRIMARY'         => ['column' => 'id', 'unique' => 1],
            'service_id'      => ['column' => 'service_id', 'unique' => 0],
            'servicegroup_id' => ['column' => 'servicegroup_id', 'unique' => 0]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'              => 1,
            'service_id'      => 1,
            'servicegroup_id' => 1
        ],
    ];

}
