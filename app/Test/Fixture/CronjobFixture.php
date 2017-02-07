<?php
/**
 * Cronjob Fixture
 */
class CronjobFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'task' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'plugin' => array('type' => 'string', 'null' => false, 'default' => 'Core', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'interval' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
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
			'task' => 'Lorem ipsum dolor sit amet',
			'plugin' => 'Lorem ipsum dolor sit amet',
			'interval' => 1
		),
	);

}
