<?php
/**
 * NagiosContactnotificationmethod Fixture
 */
class NagiosContactnotificationmethodFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'contactnotificationmethod_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
		'contactnotification_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'primary'),
		'start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'end_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'command_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'command_args' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => array('contactnotificationmethod_id', 'start_time'), 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'contactnotification_id', 'start_time', 'start_time_usec'), 'unique' => 1),
			'start_time' => array('column' => 'start_time', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical record of contact notification methods')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'contactnotificationmethod_id' => 1,
			'instance_id' => 1,
			'contactnotification_id' => 1,
			'start_time' => '2017-01-27 16:21:02',
			'start_time_usec' => 1,
			'end_time' => '2017-01-27 16:21:02',
			'end_time_usec' => 1,
			'command_object_id' => 1,
			'command_args' => 'Lorem ipsum dolor sit amet'
		),
	);

}
