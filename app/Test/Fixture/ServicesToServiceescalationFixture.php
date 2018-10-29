<?php

/**
 * ServicesToServiceescalation Fixture
 */
class ServicesToServiceescalationFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'service_id'           => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'serviceescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'excluded'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'unsigned' => false],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'service_id'           => ['column' => ['service_id', 'excluded'], 'unique' => 0],
            'serviceescalation_id' => ['column' => ['serviceescalation_id', 'excluded'], 'unique' => 0]
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
            'service_id'           => 1,
            'serviceescalation_id' => 1,
            'excluded'             => 1
        ],
    ];

}
