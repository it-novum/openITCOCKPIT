<?php
/**
 * NagHost Fixture
 */
class NagHostFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id_host' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'host_object_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'host_name' => array('type' => 'string', 'null' => false, 'length' => 80, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'id_tenant' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'id_location' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'id_devicegroup' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'alias' => array('type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'address' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'id_checkcommand' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
		'checkcommand_args' => array('type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'notify_interval' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'id_notify_period' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
		'notify_on_down' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'notify_on_unreachable' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'notify_on_recovery' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'notify_on_flapping' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'id_satellite' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'host_url' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'host_keywords' => array('type' => 'string', 'null' => true, 'default' => null, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'flap_detection_enabled' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 6, 'unsigned' => false),
		'flap_detection_on_up' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 6, 'unsigned' => false),
		'flap_detection_on_down' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 6, 'unsigned' => false),
		'flap_detection_on_unreachable' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 6, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id_host', 'unique' => 1),
			'host_name' => array('column' => 'host_name', 'unique' => 1),
			'id_devicegroup' => array('column' => 'id_devicegroup', 'unique' => 0),
			'host_object_id' => array('column' => 'host_object_id', 'unique' => 0),
			'id_mandator' => array('column' => 'id_tenant', 'unique' => 0),
			'id_location' => array('column' => 'id_location', 'unique' => 0),
			'id_checkcommand' => array('column' => 'id_checkcommand', 'unique' => 0),
			'id_notify_period' => array('column' => 'id_notify_period', 'unique' => 0),
			'host_keywords' => array('column' => 'host_keywords', 'type' => 'fulltext')
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
			'id_host' => 1,
			'host_object_id' => 1,
			'host_name' => 'Lorem ipsum dolor sit amet',
			'id_tenant' => 1,
			'id_location' => 1,
			'id_devicegroup' => 1,
			'alias' => 'Lorem ipsum dolor sit amet',
			'address' => 'Lorem ipsum dolor sit amet',
			'id_checkcommand' => 1,
			'checkcommand_args' => 'Lorem ipsum dolor sit amet',
			'max_check_attempts' => 1,
			'notify_interval' => 1,
			'id_notify_period' => 1,
			'notify_on_down' => 1,
			'notify_on_unreachable' => 1,
			'notify_on_recovery' => 1,
			'notify_on_flapping' => 1,
			'id_satellite' => 1,
			'host_url' => 'Lorem ipsum dolor sit amet',
			'host_keywords' => 'Lorem ipsum dolor sit amet',
			'flap_detection_enabled' => 1,
			'flap_detection_on_up' => 1,
			'flap_detection_on_down' => 1,
			'flap_detection_on_unreachable' => 1
		),
	);

}
