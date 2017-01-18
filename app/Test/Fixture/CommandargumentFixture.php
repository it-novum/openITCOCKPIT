<?php
/**
 * Commandargument Fixture
 */
class CommandargumentFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'command_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'human_name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'export' => array('column' => array('command_id', 'name', 'human_name'), 'unique' => 0)
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
			'command_id' => 1,
			'name' => 'My name',
			'human_name' => 'My human_name',
			'created' => '2017-01-17 14:24:01',
			'modified' => '2017-01-17 14:24:01'
		),
		array(
			'id' => 2,
			'command_id' => 1,
			'name' => 'My name 2',
			'human_name' => 'My human_name 2',
			'created' => '2017-01-17 14:24:02',
			'modified' => '2017-01-17 14:24:02'
		),
	);

}
