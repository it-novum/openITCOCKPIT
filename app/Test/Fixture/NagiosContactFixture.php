<?php

/**
 * NagiosContact Fixture
 */
class NagiosContactFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'contact_id'                    => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'config_type'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contact_object_id'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'alias'                         => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'email_address'                 => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'pager_address'                 => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'host_timeperiod_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'service_timeperiod_object_id'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'host_notifications_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'service_notifications_enabled' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'can_submit_commands'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_service_recovery'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_service_warning'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_service_unknown'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_service_critical'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_service_flapping'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_service_downtime'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_host_recovery'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_host_down'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_host_unreachable'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_host_flapping'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_host_downtime'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'minimum_importance'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                       => [
            'PRIMARY'     => ['column' => 'contact_id', 'unique' => 1],
            'instance_id' => ['column' => ['instance_id', 'config_type', 'contact_object_id'], 'unique' => 1]
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Contact definitions']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'contact_id'                    => 1,
            'instance_id'                   => 1,
            'config_type'                   => 1,
            'contact_object_id'             => 1,
            'alias'                         => 'Lorem ipsum dolor sit amet',
            'email_address'                 => 'Lorem ipsum dolor sit amet',
            'pager_address'                 => 'Lorem ipsum dolor sit amet',
            'host_timeperiod_object_id'     => 1,
            'service_timeperiod_object_id'  => 1,
            'host_notifications_enabled'    => 1,
            'service_notifications_enabled' => 1,
            'can_submit_commands'           => 1,
            'notify_service_recovery'       => 1,
            'notify_service_warning'        => 1,
            'notify_service_unknown'        => 1,
            'notify_service_critical'       => 1,
            'notify_service_flapping'       => 1,
            'notify_service_downtime'       => 1,
            'notify_host_recovery'          => 1,
            'notify_host_down'              => 1,
            'notify_host_unreachable'       => 1,
            'notify_host_flapping'          => 1,
            'notify_host_downtime'          => 1,
            'minimum_importance'            => 1
        ],
    ];

}
