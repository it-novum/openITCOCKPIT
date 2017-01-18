<?php
class ContactFixture extends CakeTestFixture {
    public $fields = [
        'id'                            => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name'                          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'                   => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'email'                         => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'phone'                         => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'host_timeperiod_id'            => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'service_timeperiod_id'         => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'host_notifications_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'service_notifications_enabled' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'can_submit_commands'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_recovery'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_warning'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_unknown'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_critical'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_flapping'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_downtime'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_recovery'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_down'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_unreachable'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_flapping'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_downtime'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'indexes'                       => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $records = array(
            array(
                'id' => 1,
                'uuid' => 'f70418ac-d646-47b1-a037-7d5a66478421',
                'name' => 'ContactA',
                'description' => 'ContactA DESC',
                'email' => 'contactA@localhost',
                'phone' => '05647385634',
                'host_timeperiod_id' => '1',
                'service_timeperiod_id' => '1'
            ),
            array(
                'id' => 2,
                'uuid' => 'cbd04963-faaa-4448-87a0-da7abffd7207',
                'name' => 'ContactB',
                'description' => 'ContactB DESC',
                'email' => 'contactB@localhost',
                'phone' => '05647865948674967',
                'host_timeperiod_id' => '1',
                'service_timeperiod_id' => '1'
            ),
            array(
                'id' => 3,
                'uuid' => 'e3a314ff-81e8-4fb1-baa6-caa422892e2b',
                'name' => 'ContactC',
                'description' => 'ContactC DESC',
                'email' => 'contactC@localhost',
                'phone' => '032455948989',
                'host_timeperiod_id' => '1',
                'service_timeperiod_id' => '1'
            )
        );
}