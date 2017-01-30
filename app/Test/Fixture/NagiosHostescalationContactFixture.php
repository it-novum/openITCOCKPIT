<?php
/**
 * NagiosHostescalationContact Fixture
 */
class NagiosHostescalationContactFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'hostescalation_contact_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
		'hostescalation_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'contact_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'hostescalation_contact_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'hostescalation_id', 'contact_object_id'), 'unique' => 1)
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
			'hostescalation_contact_id' => 1,
			'instance_id' => 1,
			'hostescalation_id' => 1,
			'contact_object_id' => 1
		),
	);

}
