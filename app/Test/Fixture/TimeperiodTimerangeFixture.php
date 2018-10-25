<?php

/**
 * TimeperiodTimerange Fixture
 */
class TimeperiodTimerangeFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'timeperiod_id'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'day'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'start'           => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 5, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'end'             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 5, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
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
            'id'            => 1,
            'timeperiod_id' => 1,
            'day'           => 1,
            'start'         => '00:00',
            'end'           => '24:00'
        ],
    ];

}
