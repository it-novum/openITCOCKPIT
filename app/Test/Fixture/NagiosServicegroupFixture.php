<?php
/**
 * NagiosServicegroup Fixture
 */
class NagiosServicegroupFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'servicegroup_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'servicegroup_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'alias' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'servicegroup_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'config_type', 'servicegroup_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Servicegroup definitions')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'servicegroup_id' => 1,
			'instance_id' => 1,
			'config_type' => 1,
			'servicegroup_object_id' => 1,
			'alias' => 'Lorem ipsum dolor sit amet'
		),
	);

}
