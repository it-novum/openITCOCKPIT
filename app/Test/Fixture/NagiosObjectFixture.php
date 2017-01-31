<?php
/**
 * NagiosObject Fixture
 */
class NagiosObjectFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'objecttype_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'name1' => array('type' => 'string', 'null' => false, 'length' => 128, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'name2' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'is_active' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'object_id', 'unique' => 1),
			'hostobject' => array('column' => array('name1', 'name2', 'objecttype_id'), 'unique' => 0),
			'serviceobject' => array('column' => array('name2', 'objecttype_id'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Current and historical objects of all kinds')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'object_id' => 1,
			'instance_id' => 1,
			'objecttype_id' => 1,
			'name1' => 'Lorem ipsum dolor sit amet',
			'name2' => 'Lorem ipsum dolor sit amet',
			'is_active' => 1
		),
	);

}
