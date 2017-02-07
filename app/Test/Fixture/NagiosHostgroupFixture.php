<?php
/**
 * NagiosHostgroup Fixture
 */
class NagiosHostgroupFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'hostgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'hostgroup_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'alias' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'hostgroup_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'hostgroup_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Hostgroup definitions')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'hostgroup_id' => 1,
			'instance_id' => 1,
			'config_type' => 1,
			'hostgroup_object_id' => 1,
			'alias' => 'Lorem ipsum dolor sit amet'
		),
	);

}
