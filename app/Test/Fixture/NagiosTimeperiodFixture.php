<?php
/**
 * NagiosTimeperiod Fixture
 */
class NagiosTimeperiodFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'timeperiod_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'alias' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'timeperiod_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'config_type', 'timeperiod_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Timeperiod definitions')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'timeperiod_id' => 1,
			'instance_id' => 1,
			'config_type' => 1,
			'timeperiod_object_id' => 1,
			'alias' => 'Lorem ipsum dolor sit amet'
		),
	);

}
