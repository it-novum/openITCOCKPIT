<?php
/**
 * NagiosAcknowledgement Fixture
 */
class NagiosAcknowledgementFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'acknowledgement_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'),
		'entry_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'entry_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'acknowledgement_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'author_name' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'comment_data' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'is_sticky' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'persistent_comment' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'notify_contacts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'acknowledgement_id', 'unique' => 1),
			'entry_time' => array('column' => 'entry_time', 'unique' => 0),
			'instance_id' => array('column' => array('instance_id', 'object_id'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Current and historical host and service acknowledgements')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'acknowledgement_id' => 1,
			'instance_id' => 1,
			'entry_time' => '2017-01-27 16:10:30',
			'entry_time_usec' => 1,
			'acknowledgement_type' => 1,
			'object_id' => 1,
			'state' => 1,
			'author_name' => 'Lorem ipsum dolor sit amet',
			'comment_data' => 'Lorem ipsum dolor sit amet',
			'is_sticky' => 1,
			'persistent_comment' => 1,
			'notify_contacts' => 1
		),
	);

}
