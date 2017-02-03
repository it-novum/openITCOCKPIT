<?php
/**
 * NagiosHostcheck Fixture
 */
class NagiosHostcheckFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'hostcheck_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'host_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'check_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'is_raw_check' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'current_check_attempt' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'state_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'primary'),
		'start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'end_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'command_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'command_args' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'command_line' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'timeout' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'early_timeout' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'execution_time' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'latency' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'return_code' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'output' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'long_output' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'perfdata' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => array('hostcheck_id', 'start_time'), 'unique' => 1),
			'start_time' => array('column' => 'start_time', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical host checks')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'hostcheck_id' => 1,
			'instance_id' => 1,
			'host_object_id' => 1,
			'check_type' => 1,
			'is_raw_check' => 1,
			'current_check_attempt' => 1,
			'max_check_attempts' => 1,
			'state' => 1,
			'state_type' => 1,
			'start_time' => '2017-01-27 16:37:04',
			'start_time_usec' => 1,
			'end_time' => '2017-01-27 16:37:04',
			'end_time_usec' => 1,
			'command_object_id' => 1,
			'command_args' => 'Lorem ipsum dolor sit amet',
			'command_line' => 'Lorem ipsum dolor sit amet',
			'timeout' => 1,
			'early_timeout' => 1,
			'execution_time' => 1,
			'latency' => 1,
			'return_code' => 1,
			'output' => 'Lorem ipsum dolor sit amet',
			'long_output' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'perfdata' => 'Lorem ipsum dolor sit amet'
		),
	);

}
