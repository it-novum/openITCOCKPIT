<?php

/**
 * NagiosHostescalation Fixture
 */
class NagiosHostescalationFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'hostescalation_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_object_id'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'timeperiod_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'first_notification'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_notification'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notification_interval'   => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'escalate_on_recovery'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'escalate_on_down'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'escalate_on_unreachable' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'                 => [
            'PRIMARY'     => ['column' => 'hostescalation_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'host_object_id', 'timeperiod_object_id', 'first_notification', 'last_notification'], 'unique' => 1]
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Host escalation definitions']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'hostescalation_id'       => 1,
            'instance_id'             => 1,
            'config_type'             => 1,
            'host_object_id'          => 1,
            'timeperiod_object_id'    => 1,
            'first_notification'      => 1,
            'last_notification'       => 1,
            'notification_interval'   => 1,
            'escalate_on_recovery'    => 1,
            'escalate_on_down'        => 1,
            'escalate_on_unreachable' => 1
        ],
    ];

}
