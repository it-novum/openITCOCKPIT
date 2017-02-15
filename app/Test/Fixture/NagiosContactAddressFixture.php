<?php
/**
 * NagiosContactAddress Fixture
 */
class NagiosContactAddressFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'contact_address_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
		'address_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'address' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'contact_address_id', 'unique' => 1),
			'contact_id' => array('column' => array('contact_id', 'address_number'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Contact addresses')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'contact_address_id' => 1,
			'instance_id' => 1,
			'contact_id' => 1,
			'address_number' => 1,
			'address' => 'Lorem ipsum dolor sit amet'
		),
	);

}
