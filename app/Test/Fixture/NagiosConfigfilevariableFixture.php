<?php
/**
 * NagiosConfigfilevariable Fixture
 */
class NagiosConfigfilevariableFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'configfilevariable_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
		'configfile_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'varname' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'varvalue' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'configfilevariable_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'configfile_id'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Configuration file variables')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'configfilevariable_id' => 1,
			'instance_id' => 1,
			'configfile_id' => 1,
			'varname' => 'Lorem ipsum dolor sit amet',
			'varvalue' => 'Lorem ipsum dolor sit amet'
		),
	);

}
