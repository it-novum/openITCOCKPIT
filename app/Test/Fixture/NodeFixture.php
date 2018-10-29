<?php

/**
 * Node Fixture
 */
class NodeFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'operator'        => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 12, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'evk_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'evk_id'  => ['column' => 'evk_id', 'unique' => 0]
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
            'id'       => 1,
            'operator' => 'Lorem ipsu',
            'evk_id'   => 1
        ],
    ];

}
