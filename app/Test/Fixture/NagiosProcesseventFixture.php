<?php
/**
 * NagiosProcessevent Fixture
 */
class NagiosProcesseventFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'processevent_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'event_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'event_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'event_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'process_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'program_name' => array('type' => 'string', 'null' => false, 'length' => 16, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'program_version' => array('type' => 'string', 'null' => false, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'program_date' => array('type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'processevent_id', 'unique' => 1),
			'event_time' => array('column' => array('event_time', 'event_time_usec'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical Nagios process events')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'processevent_id' => 1,
			'instance_id' => 1,
			'event_type' => 1,
			'event_time' => '2017-01-27 16:54:06',
			'event_time_usec' => 1,
			'process_id' => 1,
			'program_name' => 'Lorem ipsum do',
			'program_version' => 'Lorem ipsum dolor ',
			'program_date' => 'Lorem ip'
		),
	);

}
