<?php

/**
 * AutoreportQuarterly Fixture
 */
class AutoreportQuarterlyFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'year'            => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'quarter'         => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'filename'        => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
        ],
        'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'       => 1,
            'year'     => 1,
            'quarter'  => 1,
            'filename' => 'Lorem ipsum dolor sit amet',
            'created'  => '2017-01-27 15:38:15'
        ],
    ];

}
