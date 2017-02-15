<?php
/**
 * WidgetServiceStatusList Fixture
 */
class WidgetServiceStatusListFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'widget_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'unique'),
		'animation' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'animation_interval' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'show_ok' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_warning' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_critical' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_unknown' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_acknowledged' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'show_downtime' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
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
			'animation' => 'Lorem ipsum dolor sit amet',
			'animation_interval' => 1,
			'show_ok' => 1,
			'show_warning' => 1,
			'show_critical' => 1,
			'show_unknown' => 1,
			'show_acknowledged' => 1,
			'show_downtime' => 1
		),
	);

}
