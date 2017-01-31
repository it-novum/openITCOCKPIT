<?php
/**
 * Maptext Fixture
 */
class MaptextFixture extends CakeTestFixture {

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
		'text' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 256, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'font_size' => array('type' => 'integer', 'null' => false, 'default' => '11', 'unsigned' => false),
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
			'text' => 'Lorem ipsum dolor sit amet',
			'font_size' => 1,
			'created' => '2017-01-31 14:38:16',
			'modified' => '2017-01-31 14:38:16'
		),
	);

}
