<?php
/**
 * Map Fixture
 */
class MapFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'background' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'refresh_interval' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
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
			'name' => 'Lorem ipsum dolor sit amet',
			'title' => 'Lorem ipsum dolor sit amet',
			'background' => 'Lorem ipsum dolor sit amet',
			'refresh_interval' => 1,
			'created' => '2017-01-30 09:37:53',
			'modified' => '2017-01-30 09:37:53'
		),
		array(
			'id' => 2,
			'name' => 'Lorem ipsum dolor sit 2',
			'title' => 'Lorem ipsum dolor sit 2',
			'background' => 'Lorem ipsum dolor sit 2',
			'refresh_interval' => 1,
			'created' => '2017-01-30 09:37:53',
			'modified' => '2017-01-30 09:37:53'
		),
		array(
			'id' => 3,
			'name' => 'Lorem ipsum dolor sit 3',
			'title' => 'Lorem ipsum dolor sit 3',
			'background' => 'Lorem ipsum dolor sit 3',
			'refresh_interval' => 3,
			'created' => '2017-01-30 09:37:53',
			'modified' => '2017-01-30 09:37:53'
		),
	);

}
