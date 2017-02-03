<?php
/**
 * WidgetTacho Fixture
 */
class WidgetTachoFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'widget_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'unique'),
		'min' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'max' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'warn' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'crit' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'data_source' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'KEY' => array('column' => 'widget_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'widget_id' => 1,
			'min' => 1,
			'max' => 1,
			'warn' => 1,
			'crit' => 1,
			'data_source' => 'Lorem ipsum dolor sit amet'
		),
	);

}
