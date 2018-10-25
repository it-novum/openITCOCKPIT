<?php

/**
 * CalendarHoliday Fixture
 */
class CalendarHolidayFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'calendar_id'     => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'default_holiday' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false],
        'date'            => ['type' => 'date', 'null' => false, 'default' => null],
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
            'id'              => 1,
            'calendar_id'     => 1,
            'name'            => 'Lorem ipsum dolor sit amet',
            'default_holiday' => 1,
            'date'            => '2017-01-27'
        ],
    ];

}
