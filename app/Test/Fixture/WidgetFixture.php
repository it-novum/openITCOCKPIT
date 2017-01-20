<?php
/**
 * Widget Fixture
 */
class WidgetFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'dashboard_tab_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'type_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'service_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'host_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'map_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'graph_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'row' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'col' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'width' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'height' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'color' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'dashboard_tab_id' => 1,
			'type_id' => 1,
			'service_id' => 1,
			'host_id' => 1,
			'map_id' => 1,
			'graph_id' => 1,
			'row' => 1,
			'col' => 1,
			'width' => 1,
			'height' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'color' => 'Lorem ipsum dolor sit amet',
			'created' => '2017-01-20 11:27:06',
			'modified' => '2017-01-20 11:27:06'
		),
	);

}
