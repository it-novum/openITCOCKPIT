<?php
/**
 * NagService Fixture
 */
class NagServiceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id_service' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'service_object_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'id_host' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
		'id_servicetemplate' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'service_description' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'id_check_period' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'max_check_attempts' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'normal_check_interval' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'retry_check_interval' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'notify_interval' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'id_notify_period' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'checkcommand_args' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 2048, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'notify_on_warning' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'notify_on_unknown' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'notify_on_critical' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'notify_on_recovery' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'notify_on_flapping' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'process_perf_data' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'active_checks_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'is_volatile' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'id_eventhandler' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'eventhandler_args' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'check_freshness' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'freshness_threshold' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'service_url' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'service_keywords' => array('type' => 'string', 'null' => true, 'default' => null, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'own_contactgroups' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'own_contacts' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'flap_detection_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'flap_detection_on_ok' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'flap_detection_on_warning' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'flap_detection_on_unknown' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'flap_detection_on_critical' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id_service', 'unique' => 1),
			'id_host' => array('column' => 'id_host', 'unique' => 0),
			'service_object_id' => array('column' => 'service_object_id', 'unique' => 0),
			'id_servicetemplate' => array('column' => 'id_servicetemplate', 'unique' => 0),
			'id_check_period' => array('column' => 'id_check_period', 'unique' => 0),
			'id_notify_period' => array('column' => 'id_notify_period', 'unique' => 0),
			'id_eventhandler' => array('column' => 'id_eventhandler', 'unique' => 0),
			'service_keywords' => array('column' => 'service_keywords', 'type' => 'fulltext')
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id_service' => 1,
			'service_object_id' => 1,
			'id_host' => 1,
			'id_servicetemplate' => 1,
			'service_description' => 'Lorem ipsum dolor sit amet',
			'id_check_period' => 1,
			'max_check_attempts' => 1,
			'normal_check_interval' => 1,
			'retry_check_interval' => 1,
			'notify_interval' => 1,
			'id_notify_period' => 1,
			'checkcommand_args' => 'Lorem ipsum dolor sit amet',
			'notify_on_warning' => 1,
			'notify_on_unknown' => 1,
			'notify_on_critical' => 1,
			'notify_on_recovery' => 1,
			'notify_on_flapping' => 1,
			'process_perf_data' => 1,
			'active_checks_enabled' => 1,
			'is_volatile' => 1,
			'id_eventhandler' => 1,
			'eventhandler_args' => 'Lorem ipsum dolor sit amet',
			'check_freshness' => 1,
			'freshness_threshold' => 1,
			'service_url' => 'Lorem ipsum dolor sit amet',
			'service_keywords' => 'Lorem ipsum dolor sit amet',
			'own_contactgroups' => 1,
			'own_contacts' => 1,
			'flap_detection_enabled' => 1,
			'flap_detection_on_ok' => 1,
			'flap_detection_on_warning' => 1,
			'flap_detection_on_unknown' => 1,
			'flap_detection_on_critical' => 1
		),
	);

}
