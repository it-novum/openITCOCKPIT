<?php

/**
 * NagiosContactnotification Fixture
 */
class NagiosContactnotificationFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'contactnotification_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notification_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'contact_object_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'start_time'             => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'primary'],
        'start_time_usec'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_time'               => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                => [
            'PRIMARY'    => ['column' => ['contactnotification_id', 'start_time'], 'unique' => 1],
            'start_time' => ['column' => 'start_time', 'unique' => 0]
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical record of contact notifications']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'contactnotification_id' => 1,
            'instance_id'            => 1,
            'notification_id'        => 1,
            'contact_object_id'      => 1,
            'start_time'             => '2017-01-27 16:22:07',
            'start_time_usec'        => 1,
            'end_time'               => '2017-01-27 16:22:07',
            'end_time_usec'          => 1
        ],
    ];

}
