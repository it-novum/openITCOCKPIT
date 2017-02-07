<?php
/**
 * DashboardTab Fixture
 */
class DashboardTabFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'position' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'shared' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'source_tab_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'check_for_updates' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'source_last_modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'position' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'shared' => 1,
			'source_tab_id' => 1,
			'check_for_updates' => 1,
			'source_last_modified' => '2017-01-27 15:42:09',
			'created' => '2017-01-27 15:42:09',
			'modified' => '2017-01-27 15:42:09'
		),
	);

}
