<?php
/**
 * NagiosTimeperiodTimerange Fixture
 */
class NagiosTimeperiodTimerangeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'timeperiod_timerange_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'timeperiod_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
		'day' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'start_sec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'end_sec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'timeperiod_timerange_id', 'unique' => 1),
			'instance_id' => array('column' => array('timeperiod_id', 'day', 'start_sec', 'end_sec'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Timeperiod definitions')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'timeperiod_timerange_id' => 1,
			'instance_id' => 1,
			'timeperiod_id' => 1,
			'day' => 1,
			'start_sec' => 1,
			'end_sec' => 1
		),
	);

}
