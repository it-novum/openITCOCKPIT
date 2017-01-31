<?php
/**
 * Mapicon Fixture
 */
class MapiconFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'map_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'x' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'y' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'icon' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
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
			'map_id' => 1,
			'x' => 1,
			'y' => 1,
			'icon' => 'Lorem ipsum dolor sit amet',
			'created' => '2017-01-27 15:58:52',
			'modified' => '2017-01-27 15:58:52'
		),
	);

}
