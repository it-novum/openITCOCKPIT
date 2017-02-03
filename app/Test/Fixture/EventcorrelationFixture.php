<?php
/**
 * Eventcorrelation Fixture
 */
class EventcorrelationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'lft' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'rght' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'host_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'service_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'operator' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 12, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'host_id' => array('column' => 'host_id', 'unique' => 0),
			'lft' => array('column' => 'lft', 'unique' => 0),
			'rght' => array('column' => 'rght', 'unique' => 0),
			'parent_id' => array('column' => 'parent_id', 'unique' => 0),
			'service_id' => array('column' => 'service_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'parent_id' => 1,
			'lft' => 1,
			'rght' => 1,
			'host_id' => 1,
			'service_id' => 1,
			'operator' => 'Lorem ipsu'
		),
	);

}
