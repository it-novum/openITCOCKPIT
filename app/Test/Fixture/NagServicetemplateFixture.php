<?php
/**
 * NagServicetemplate Fixture
 */
class NagServicetemplateFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id_servicetemplate' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'servicetemplate_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'service_description' => array('type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'id_check_period' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
		'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'normal_check_interval' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'retry_check_interval' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'notify_interval' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'id_notify_period' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
		'id_checkcommand' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
		'checkcommand_args' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 2048, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'notify_on_warning' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'notify_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'notify_on_critical' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'notify_on_recovery' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'notify_on_flapping' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'process_perf_data' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'active_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'is_volatile' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'id_eventhandler' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
		'eventhandler_args' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'check_freshness' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'freshness_threshold' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'checkcommand_info' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'flap_detection_enabled' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 6, 'unsigned' => false),
		'flap_detection_on_ok' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 6, 'unsigned' => false),
		'flap_detection_on_warning' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 6, 'unsigned' => false),
		'flap_detection_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 6, 'unsigned' => false),
		'flap_detection_on_critical' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 6, 'unsigned' => false),
		'id_tenant' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id_servicetemplate', 'unique' => 1),
			'servicetemplate_name' => array('column' => 'servicetemplate_name', 'unique' => 1),
			'id_check_period' => array('column' => 'id_check_period', 'unique' => 0),
			'id_notify_period' => array('column' => 'id_notify_period', 'unique' => 0),
			'id_checkcommand' => array('column' => 'id_checkcommand', 'unique' => 0),
			'id_eventhandler' => array('column' => 'id_eventhandler', 'unique' => 0)
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
			'id_servicetemplate' => 1,
			'servicetemplate_name' => 'Lorem ipsum dolor sit amet',
			'service_description' => 'Lorem ipsum dolor sit amet',
			'id_check_period' => 1,
			'max_check_attempts' => 1,
			'normal_check_interval' => 1,
			'retry_check_interval' => 1,
			'notify_interval' => 1,
			'id_notify_period' => 1,
			'id_checkcommand' => 1,
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
			'checkcommand_info' => 'Lorem ipsum dolor sit amet',
			'flap_detection_enabled' => 1,
			'flap_detection_on_ok' => 1,
			'flap_detection_on_warning' => 1,
			'flap_detection_on_unknown' => 1,
			'flap_detection_on_critical' => 1,
			'id_tenant' => 1
		),
	);

}
