<?php
/**
 * Service Fixture
 */
class ServiceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'servicetemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'command_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'check_command_args' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'eventhandler_command_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'notify_period_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'check_period_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'check_interval' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'retry_interval' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'max_check_attempts' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'first_notification_delay' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'notification_interval' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'notify_on_warning' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'notify_on_unknown' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'notify_on_critical' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'notify_on_recovery' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'notify_on_flapping' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'notify_on_downtime' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'is_volatile' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'flap_detection_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'flap_detection_on_ok' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'flap_detection_on_warning' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'flap_detection_on_unknown' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'flap_detection_on_critical' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'low_flap_threshold' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'high_flap_threshold' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'process_performance_data' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'freshness_checks_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 8, 'unsigned' => false),
		'freshness_threshold' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'passive_checks_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'event_handler_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'active_checks_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'notifications_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'notes' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'priority' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 2, 'unsigned' => false),
		'tags' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'own_contacts' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'own_contactgroups' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'own_customvariables' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
		'service_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'service_type' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'disabled' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1),
			'export' => array('column' => array('uuid', 'host_id', 'disabled'), 'unique' => 0),
			'host_id' => array('column' => array('host_id', 'disabled'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

}
