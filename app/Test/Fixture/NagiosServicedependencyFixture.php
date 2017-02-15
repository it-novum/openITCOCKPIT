<?php
/**
 * NagiosServicedependency Fixture
 */
class NagiosServicedependencyFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'servicedependency_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'service_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'dependent_service_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'dependency_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'inherits_parent' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'fail_on_ok' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'fail_on_warning' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'fail_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'fail_on_critical' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'servicedependency_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'config_type', 'service_object_id', 'dependent_service_object_id', 'dependency_type', 'inherits_parent', 'fail_on_ok', 'fail_on_warning', 'fail_on_unknown', 'fail_on_critical'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Service dependency definitions')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'servicedependency_id' => 1,
			'instance_id' => 1,
			'config_type' => 1,
			'service_object_id' => 1,
			'dependent_service_object_id' => 1,
			'dependency_type' => 1,
			'inherits_parent' => 1,
			'timeperiod_object_id' => 1,
			'fail_on_ok' => 1,
			'fail_on_warning' => 1,
			'fail_on_unknown' => 1,
			'fail_on_critical' => 1
		),
	);

}
