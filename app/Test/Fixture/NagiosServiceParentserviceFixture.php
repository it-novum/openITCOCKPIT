<?php
/**
 * NagiosServiceParentservice Fixture
 */
class NagiosServiceParentserviceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'service_parentservice_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
		'parent_service_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'service_parentservice_id', 'unique' => 1),
			'instance_id' => array('column' => array('service_id', 'parent_service_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Parent services')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'service_parentservice_id' => 1,
			'instance_id' => 1,
			'service_id' => 1,
			'parent_service_object_id' => 1
		),
	);

}
