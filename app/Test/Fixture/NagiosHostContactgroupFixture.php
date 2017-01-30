<?php
/**
 * NagiosHostContactgroup Fixture
 */
class NagiosHostContactgroupFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'host_contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
		'contactgroup_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'host_contactgroup_id', 'unique' => 1),
			'instance_id' => array('column' => array('host_id', 'contactgroup_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Host contact groups')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'host_contactgroup_id' => 1,
			'instance_id' => 1,
			'host_id' => 1,
			'contactgroup_object_id' => 1
		),
	);

}
