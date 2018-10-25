<?php

/**
 * NagiosProcessevent Fixture
 */
class NagiosProcesseventFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'processevent_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'event_type'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'event_time'      => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'event_time_usec' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'process_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'program_name'    => ['type' => 'string', 'null' => false, 'length' => 16, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'program_version' => ['type' => 'string', 'null' => false, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'program_date'    => ['type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY'    => ['column' => 'processevent_id', 'unique' => 1],
            'event_time' => ['column' => ['event_time', 'event_time_usec'], 'unique' => 0]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical Nagios process events']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'processevent_id' => 1,
            'instance_id'     => 1,
            'event_type'      => 1,
            'event_time'      => '2017-01-27 16:54:06',
            'event_time_usec' => 1,
            'process_id'      => 1,
            'program_name'    => 'Lorem ipsum do',
            'program_version' => 'Lorem ipsum dolor ',
            'program_date'    => 'Lorem ip'
        ],
    ];

}
