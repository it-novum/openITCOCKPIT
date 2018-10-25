<?php

/**
 * IdoitLinklist Fixture
 */
class IdoitLinklistFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'configuration_id'        => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'regex'                   => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'servicetemplategroup_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'indexes'                 => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'                      => 1,
            'configuration_id'        => 1,
            'regex'                   => 'Lorem ipsum dolor sit amet',
            'servicetemplategroup_id' => 1
        ],
    ];

}
