<?php

/**
 * NagiosContactnotificationmethod Fixture
 */
class NagiosContactnotificationmethodFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'contactnotificationmethod_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'contactnotification_id'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'start_time'                   => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'primary'],
        'start_time_usec'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_time'                     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_object_id'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_args'                 => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                      => [
            'PRIMARY'     => ['column' => ['contactnotificationmethod_id', 'start_time'], 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'contactnotification_id', 'start_time', 'start_time_usec'], 'unique' => 1],
            'start_time'  => ['column' => 'start_time', 'unique' => 0]
        ],
        'tableParameters'              => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical record of contact notification methods']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'contactnotificationmethod_id' => 1,
            'instance_id'                  => 1,
            'contactnotification_id'       => 1,
            'start_time'                   => '2017-01-27 16:21:02',
            'start_time_usec'              => 1,
            'end_time'                     => '2017-01-27 16:21:02',
            'end_time_usec'                => 1,
            'command_object_id'            => 1,
            'command_args'                 => 'Lorem ipsum dolor sit amet'
        ],
    ];

}
