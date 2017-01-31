<?php
/**
 * NagiosConfigfile Fixture
 */
class NagiosConfigfileFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'configfile_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
		'configfile_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'configfile_path' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'configfile_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'configfile_type', 'configfile_path'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Configuration files')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'configfile_id' => 1,
			'instance_id' => 1,
			'configfile_type' => 1,
			'configfile_path' => 'Lorem ipsum dolor sit amet'
		),
	);

}
