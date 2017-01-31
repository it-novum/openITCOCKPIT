<?php
/**
 * Servicetemplate Fixture
 */
class ServicetemplateFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'servicetemplatetype_id' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'check_period_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'notify_period_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'command_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'check_command_args' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'checkcommand_info' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'eventhandler_command_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'timeperiod_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'check_interval' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 5, 'unsigned' => false),
		'retry_interval' => array('type' => 'integer', 'null' => false, 'default' => '3', 'length' => 5, 'unsigned' => false),
		'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3, 'unsigned' => false),
		'first_notification_delay' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'notification_interval' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'notify_on_warning' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'notify_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'notify_on_critical' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'notify_on_recovery' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'notify_on_flapping' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'notify_on_downtime' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'flap_detection_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'flap_detection_on_ok' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'flap_detection_on_warning' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'flap_detection_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'flap_detection_on_critical' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'low_flap_threshold' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'high_flap_threshold' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'process_performance_data' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'freshness_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'freshness_threshold' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 8, 'unsigned' => false),
		'passive_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'event_handler_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'active_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'retain_status_information' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'retain_nonstatus_information' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'notes' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'priority' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 2, 'unsigned' => false),
		'tags' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'service_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'is_volatile' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'check_freshness' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'uuid' => 'Lorem ipsum dolor sit amet',
			'name' => 'Lorem ipsum dolor sit amet',
			'container_id' => 1,
			'servicetemplatetype_id' => 1,
			'check_period_id' => 1,
			'notify_period_id' => 1,
			'description' => 'Lorem ipsum dolor sit amet',
			'command_id' => 1,
			'check_command_args' => 'Lorem ipsum dolor sit amet',
			'checkcommand_info' => 'Lorem ipsum dolor sit amet',
			'eventhandler_command_id' => 1,
			'timeperiod_id' => 1,
			'check_interval' => 1,
			'retry_interval' => 1,
			'max_check_attempts' => 1,
			'first_notification_delay' => 1,
			'notification_interval' => 1,
			'notify_on_warning' => 1,
			'notify_on_unknown' => 1,
			'notify_on_critical' => 1,
			'notify_on_recovery' => 1,
			'notify_on_flapping' => 1,
			'notify_on_downtime' => 1,
			'flap_detection_enabled' => 1,
			'flap_detection_on_ok' => 1,
			'flap_detection_on_warning' => 1,
			'flap_detection_on_unknown' => 1,
			'flap_detection_on_critical' => 1,
			'low_flap_threshold' => 1,
			'high_flap_threshold' => 1,
			'process_performance_data' => 1,
			'freshness_checks_enabled' => 1,
			'freshness_threshold' => 1,
			'passive_checks_enabled' => 1,
			'event_handler_enabled' => 1,
			'active_checks_enabled' => 1,
			'retain_status_information' => 1,
			'retain_nonstatus_information' => 1,
			'notifications_enabled' => 1,
			'notes' => 'Lorem ipsum dolor sit amet',
			'priority' => 1,
			'tags' => 'Lorem ipsum dolor sit amet',
			'service_url' => 'Lorem ipsum dolor sit amet',
			'is_volatile' => 1,
			'check_freshness' => 1,
			'created' => '2017-01-27 18:08:02',
			'modified' => '2017-01-27 18:08:02'
		),
	);

}
