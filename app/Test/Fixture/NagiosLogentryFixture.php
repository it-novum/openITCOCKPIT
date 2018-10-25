<?php

/**
 * NagiosLogentry Fixture
 */
class NagiosLogentryFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'logentry_id'             => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'logentry_time'           => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'entry_time'              => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'primary'],
        'entry_time_usec'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'logentry_type'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'logentry_data'           => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'realtime_data'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'inferred_data_extracted' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'                 => [
            'PRIMARY'         => ['column' => ['logentry_id', 'entry_time'], 'unique' => 1],
            'logentry_time'   => ['column' => 'logentry_time', 'unique' => 0],
            'legentry_time'   => ['column' => 'logentry_time', 'unique' => 0],
            'entry_time'      => ['column' => 'entry_time', 'unique' => 0],
            'entry_time_usec' => ['column' => 'entry_time_usec', 'unique' => 0]
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical record of log entries']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'logentry_id'             => 1,
            'instance_id'             => 1,
            'logentry_time'           => '2017-01-27 16:49:45',
            'entry_time'              => '2017-01-27 16:49:45',
            'entry_time_usec'         => 1,
            'logentry_type'           => 1,
            'logentry_data'           => 'Lorem ipsum dolor sit amet',
            'realtime_data'           => 1,
            'inferred_data_extracted' => 1
        ],
    ];

}
