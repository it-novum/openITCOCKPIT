<?php

/**
 * NagiosTimedevent Fixture
 */
class NagiosTimedeventFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'timedevent_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'event_type'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'queued_time'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'queued_time_usec'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'event_time'         => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'event_time_usec'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'scheduled_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'],
        'recurring_event'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'deletion_time'      => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'deletion_time_usec' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'            => [
            'PRIMARY'        => ['column' => 'timedevent_id', 'unique' => 1],
            'instance_id'    => ['column' => 'instance_id', 'unique' => 0],
            'event_type'     => ['column' => 'event_type', 'unique' => 0],
            'scheduled_time' => ['column' => 'scheduled_time', 'unique' => 0],
            'object_id'      => ['column' => 'object_id', 'unique' => 0]
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical events from the Nagios event queue']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'timedevent_id'      => 1,
            'instance_id'        => 1,
            'event_type'         => 1,
            'queued_time'        => '2017-01-27 17:23:21',
            'queued_time_usec'   => 1,
            'event_time'         => '2017-01-27 17:23:21',
            'event_time_usec'    => 1,
            'scheduled_time'     => '2017-01-27 17:23:21',
            'recurring_event'    => 1,
            'object_id'          => 1,
            'deletion_time'      => '2017-01-27 17:23:21',
            'deletion_time_usec' => 1
        ],
    ];

}
