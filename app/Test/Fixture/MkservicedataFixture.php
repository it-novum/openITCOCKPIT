<?php
/**
 * Mkservicedata Fixture
 */
class MkservicedataFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'is_process' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'check_name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'check_item' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
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
			'service_id' => 1,
			'host_id' => 1,
			'is_process' => 1,
			'check_name' => 'Lorem ipsum dolor sit amet',
			'check_item' => 'Lorem ipsum dolor sit amet',
			'created' => '2017-01-27 16:06:27',
			'modified' => '2017-01-27 16:06:27'
		),
	);

}
