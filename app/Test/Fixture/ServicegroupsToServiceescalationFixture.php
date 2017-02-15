<?php
/**
 * ServicegroupsToServiceescalation Fixture
 */
class ServicegroupsToServiceescalationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'servicegroup_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'serviceescalation_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'excluded' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'servicegroup_id' => array('column' => array('servicegroup_id', 'excluded'), 'unique' => 0),
			'serviceescalation_id' => array('column' => array('serviceescalation_id', 'excluded'), 'unique' => 0)
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
			'id' => 1,
			'servicegroup_id' => 1,
			'serviceescalation_id' => 1,
			'excluded' => 1
		),
	);

}
