<?php
/**
 * NagiosRuntimevariable Fixture
 */
class NagiosRuntimevariableFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'runtimevariable_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
		'varname' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'varvalue' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'runtimevariable_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'varname'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Runtime variables from the Nagios daemon')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'runtimevariable_id' => 1,
			'instance_id' => 1,
			'varname' => 'Lorem ipsum dolor sit amet',
			'varvalue' => 'Lorem ipsum dolor sit amet'
		),
	);

}
