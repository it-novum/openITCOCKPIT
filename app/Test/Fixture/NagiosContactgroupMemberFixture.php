<?php
/**
 * NagiosContactgroupMember Fixture
 */
class NagiosContactgroupMemberFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'contactgroup_member_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'),
		'contact_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'contactgroup_member_id', 'unique' => 1),
			'instance_id' => array('column' => array('contactgroup_id', 'contact_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Contactgroup members')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'contactgroup_member_id' => 1,
			'instance_id' => 1,
			'contactgroup_id' => 1,
			'contact_object_id' => 1
		),
	);

}
