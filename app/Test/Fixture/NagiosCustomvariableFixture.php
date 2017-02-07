<?php
/**
 * NagiosCustomvariable Fixture
 */
class NagiosCustomvariableFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'customvariable_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'has_been_modified' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'varname' => array('type' => 'string', 'null' => false, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'varvalue' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'customvariable_id', 'unique' => 1),
			'object_id_2' => array('column' => array('object_id', 'config_type', 'varname'), 'unique' => 1),
			'varname' => array('column' => 'varname', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Custom variables')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'customvariable_id' => 1,
			'instance_id' => 1,
			'object_id' => 1,
			'config_type' => 1,
			'has_been_modified' => 1,
			'varname' => 'Lorem ipsum dolor sit amet',
			'varvalue' => 'Lorem ipsum dolor sit amet'
		),
	);

}
