<?php
/**
 * NagiosServiceescalation Fixture
 */
class NagiosServiceescalationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'serviceescalation_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'service_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'first_notification' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'last_notification' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'notification_interval' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'escalate_on_recovery' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'escalate_on_warning' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'escalate_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'escalate_on_critical' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'serviceescalation_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'config_type', 'service_object_id', 'timeperiod_object_id', 'first_notification', 'last_notification'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Service escalation definitions')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'serviceescalation_id' => 1,
			'instance_id' => 1,
			'config_type' => 1,
			'service_object_id' => 1,
			'timeperiod_object_id' => 1,
			'first_notification' => 1,
			'last_notification' => 1,
			'notification_interval' => 1,
			'escalate_on_recovery' => 1,
			'escalate_on_warning' => 1,
			'escalate_on_unknown' => 1,
			'escalate_on_critical' => 1
		),
	);

}
