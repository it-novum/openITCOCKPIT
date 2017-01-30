<?php
/**
 * NagiosInstance Fixture
 */
class NagiosInstanceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false, 'key' => 'primary'),
		'instance_name' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'instance_description' => array('type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'instance_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Location names of various Nagios installations')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'instance_id' => 1,
			'instance_name' => 'Lorem ipsum dolor sit amet',
			'instance_description' => 'Lorem ipsum dolor sit amet'
		),
	);

}
