<?php

/**
 * GraphgenCollectionItem Fixture
 */
class GraphgenCollectionItemFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                    => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'graphgen_tmpl_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'graphgen_colletion_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'indexes'               => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
        ],
        'tableParameters'       => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'                    => 1,
            'graphgen_tmpl_id'      => 1,
            'graphgen_colletion_id' => 1
        ],
    ];

}
