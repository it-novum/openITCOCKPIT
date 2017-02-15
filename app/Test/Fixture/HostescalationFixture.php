<?php
/**
 * Hostescalation Fixture
 */
class HostescalationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'timeperiod_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'first_notification' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'last_notification' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'notification_interval' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'escalate_on_recovery' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
		'escalate_on_down' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
		'escalate_on_unreachable' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false),
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
			'timeperiod_id' => 1,
			'first_notification' => 1,
			'last_notification' => 1,
			'notification_interval' => 1,
			'escalate_on_recovery' => 1,
			'escalate_on_down' => 1,
			'escalate_on_unreachable' => 1,
			'created' => '2017-01-27 15:47:41',
			'modified' => '2017-01-27 15:47:41'
		),
	);

}
