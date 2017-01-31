<?php
/**
 * NagiosCommand Fixture
 */
class NagiosCommandFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'command_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
		'command_line' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 511, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'command_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'object_id', 'config_type'), 'unique' => 1),
			'object_id' => array('column' => 'object_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Command definitions')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'command_id' => 1,
			'instance_id' => 1,
			'config_type' => 1,
			'object_id' => 1,
			'command_line' => 'Lorem ipsum dolor sit amet'
		),
	);

}
