<?php

/**
 * NagiosService Fixture
 */
class NagiosServiceFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'service_id'                        => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'config_type'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'host_object_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'service_object_id'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'display_name'                      => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_command_object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'check_command_args'                => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_object_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'eventhandler_command_args'         => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'notification_timeperiod_object_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'check_timeperiod_object_id'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'failure_prediction_options'        => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'check_interval'                    => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'retry_interval'                    => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'max_check_attempts'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'first_notification_delay'          => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'notification_interval'             => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'notify_on_warning'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_on_unknown'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_on_critical'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_on_recovery'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_on_flapping'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notify_on_downtime'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'stalk_on_ok'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'stalk_on_warning'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'stalk_on_unknown'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'stalk_on_critical'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'is_volatile'                       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_enabled'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_on_ok'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_on_warning'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_on_unknown'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'flap_detection_on_critical'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'low_flap_threshold'                => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'high_flap_threshold'               => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'process_performance_data'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'freshness_checks_enabled'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
        'freshness_threshold'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'passive_checks_enabled'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'event_handler_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'active_checks_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'retain_status_information'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'retain_nonstatus_information'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notifications_enabled'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'obsess_over_service'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'failure_prediction_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'notes'                             => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'notes_url'                         => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'action_url'                        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'icon_image'                        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'icon_image_alt'                    => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'importance'                        => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'indexes'                           => [
            'PRIMARY'           => ['column' => 'service_id', 'unique' => 1],
            'service_object_id' => ['column' => 'service_object_id', 'unique' => 0]
        ],
        'tableParameters'                   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Service definitions']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'service_id'                        => 1,
            'instance_id'                       => 1,
            'config_type'                       => 1,
            'host_object_id'                    => 1,
            'service_object_id'                 => 1,
            'display_name'                      => 'Lorem ipsum dolor sit amet',
            'check_command_object_id'           => 1,
            'check_command_args'                => 'Lorem ipsum dolor sit amet',
            'eventhandler_command_object_id'    => 1,
            'eventhandler_command_args'         => 'Lorem ipsum dolor sit amet',
            'notification_timeperiod_object_id' => 1,
            'check_timeperiod_object_id'        => 1,
            'failure_prediction_options'        => 'Lorem ipsum dolor sit amet',
            'check_interval'                    => 1,
            'retry_interval'                    => 1,
            'max_check_attempts'                => 1,
            'first_notification_delay'          => 1,
            'notification_interval'             => 1,
            'notify_on_warning'                 => 1,
            'notify_on_unknown'                 => 1,
            'notify_on_critical'                => 1,
            'notify_on_recovery'                => 1,
            'notify_on_flapping'                => 1,
            'notify_on_downtime'                => 1,
            'stalk_on_ok'                       => 1,
            'stalk_on_warning'                  => 1,
            'stalk_on_unknown'                  => 1,
            'stalk_on_critical'                 => 1,
            'is_volatile'                       => 1,
            'flap_detection_enabled'            => 1,
            'flap_detection_on_ok'              => 1,
            'flap_detection_on_warning'         => 1,
            'flap_detection_on_unknown'         => 1,
            'flap_detection_on_critical'        => 1,
            'low_flap_threshold'                => 1,
            'high_flap_threshold'               => 1,
            'process_performance_data'          => 1,
            'freshness_checks_enabled'          => 1,
            'freshness_threshold'               => 1,
            'passive_checks_enabled'            => 1,
            'event_handler_enabled'             => 1,
            'active_checks_enabled'             => 1,
            'retain_status_information'         => 1,
            'retain_nonstatus_information'      => 1,
            'notifications_enabled'             => 1,
            'obsess_over_service'               => 1,
            'failure_prediction_enabled'        => 1,
            'notes'                             => 'Lorem ipsum dolor sit amet',
            'notes_url'                         => 'Lorem ipsum dolor sit amet',
            'action_url'                        => 'Lorem ipsum dolor sit amet',
            'icon_image'                        => 'Lorem ipsum dolor sit amet',
            'icon_image_alt'                    => 'Lorem ipsum dolor sit amet',
            'importance'                        => 1
        ],
    ];

}
