<?php

/**
 * Cronschedule Fixture
 */
class CronscheduleFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'cronjob_id'      => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'is_running'      => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'start_time'      => ['type' => 'datetime', 'null' => false, 'default' => null],
        'end_time'        => ['type' => 'datetime', 'null' => false, 'default' => null],
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
            'id'         => 1,
            'cronjob_id' => 1,
            'is_running' => 1,
            'start_time' => '2017-01-27 15:41:38',
            'end_time'   => '2017-01-27 15:41:38'
        ],
    ];

}
