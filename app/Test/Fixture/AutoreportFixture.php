<?php
/**
 * Autoreport Fixture
 */
class AutoreportFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'timeperiod_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'report_interval' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'report_send_interval' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'consider_downtimes' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'last_send_date' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'min_availability_percent' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'min_availability' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '8,3', 'unsigned' => false),
		'check_hard_state' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'use_start_time' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'unsigned' => false),
		'report_start_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'last_percent_value' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'last_absolut_value' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'show_time' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4, 'unsigned' => false),
		'last_number_of_outages' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'failure_statistic' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'unsigned' => false),
		'consider_holidays' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'unsigned' => false),
		'calendar_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'max_number_of_outages' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'name' => 'Lorem ipsum dolor sit amet',
			'description' => 'Lorem ipsum dolor sit amet',
			'container_id' => 1,
			'timeperiod_id' => 1,
			'report_interval' => 'Lorem ipsum dolor ',
			'report_send_interval' => 'Lorem ipsum dolor ',
			'consider_downtimes' => 1,
			'last_send_date' => '2017-01-27 14:53:21',
			'min_availability_percent' => 1,
			'min_availability' => 1,
			'check_hard_state' => 1,
			'use_start_time' => 1,
			'report_start_date' => '2017-01-27',
			'last_percent_value' => 1,
			'last_absolut_value' => 1,
			'show_time' => 1,
			'last_number_of_outages' => 1,
			'failure_statistic' => 1,
			'consider_holidays' => 1,
			'calendar_id' => 1,
			'max_number_of_outages' => 1,
			'created' => '2017-01-27 14:53:21',
			'modified' => '2017-01-27 14:53:21'
		),
	);

}
