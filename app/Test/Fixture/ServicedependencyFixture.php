<?php
/**
 * Servicedependency Fixture
 */
class ServicedependencyFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'inherits_parent' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'timeperiod_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'execution_fail_on_ok' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
		'execution_fail_on_warning' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
		'execution_fail_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
		'execution_fail_on_critical' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
		'execution_fail_on_pending' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
		'execution_none' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
		'notification_fail_on_ok' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
		'notification_fail_on_warning' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
		'notification_fail_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
		'notification_fail_on_critical' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
		'notification_fail_on_pending' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
		'notification_none' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'uuid' => 'Lorem ipsum dolor sit amet',
			'container_id' => 1,
			'inherits_parent' => 1,
			'timeperiod_id' => 1,
			'execution_fail_on_ok' => 1,
			'execution_fail_on_warning' => 1,
			'execution_fail_on_unknown' => 1,
			'execution_fail_on_critical' => 1,
			'execution_fail_on_pending' => 1,
			'execution_none' => 1,
			'notification_fail_on_ok' => 1,
			'notification_fail_on_warning' => 1,
			'notification_fail_on_unknown' => 1,
			'notification_fail_on_critical' => 1,
			'notification_fail_on_pending' => 1,
			'notification_none' => 1,
			'created' => '2017-01-27 17:39:19',
			'modified' => '2017-01-27 17:39:19'
		),
	);

}
