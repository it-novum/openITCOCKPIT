<?php

/**
 * NagiosServiceescalation Fixture
 */
class NagiosServiceescalationFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'serviceescalation_id'  => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'service_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'timeperiod_object_id'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'first_notification'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'last_notification'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notification_interval' => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'escalate_on_recovery'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'escalate_on_warning'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'escalate_on_unknown'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'escalate_on_critical'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'indexes'               => [
            'PRIMARY'     => ['column' => 'serviceescalation_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'service_object_id', 'timeperiod_object_id', 'first_notification', 'last_notification'], 'unique' => 1]
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Service escalation definitions']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'serviceescalation_id'  => 1,
            'instance_id'           => 1,
            'config_type'           => 1,
            'service_object_id'     => 1,
            'timeperiod_object_id'  => 1,
            'first_notification'    => 1,
            'last_notification'     => 1,
            'notification_interval' => 1,
            'escalate_on_recovery'  => 1,
            'escalate_on_warning'   => 1,
            'escalate_on_unknown'   => 1,
            'escalate_on_critical'  => 1
        ],
    ];

}
