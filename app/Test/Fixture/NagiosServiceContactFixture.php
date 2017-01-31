<?php
/**
 * NagiosServiceContact Fixture
 */
class NagiosServiceContactFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'service_contact_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'contact_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'service_contact_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'service_id', 'contact_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'service_contact_id' => 1,
			'instance_id' => 1,
			'service_id' => 1,
			'contact_object_id' => 1
		),
	);

}
