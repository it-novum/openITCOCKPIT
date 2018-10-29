<?php

/**
 * GraphgenTmplConf Fixture
 */
class GraphgenTmplConfFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'graphgen_tmpl_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'service_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'data_sources'     => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 256, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
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
            'graphgen_tmpl_id' => 1,
            'service_id'       => 1,
            'data_sources'     => 'Lorem ipsum dolor sit amet'
        ],
    ];

}
