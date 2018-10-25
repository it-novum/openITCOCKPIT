<?php

/**
 * IdoitObjecttype Fixture
 */
class IdoitObjecttypeFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'configuration_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'objecttype_id'    => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'hosttemplate_id'  => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'container_id'     => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'indexes'          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'               => 1,
            'configuration_id' => 1,
            'objecttype_id'    => 1,
            'hosttemplate_id'  => 1,
            'container_id'     => 1
        ],
    ];

}
