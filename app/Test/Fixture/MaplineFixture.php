<?php
/**
 * Mapline Fixture
 */
class MaplineFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'map_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'startX' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'startY' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'endX' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'endY' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'limit' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
		'iconset' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'object_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
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
			'startX' => 1,
			'startY' => 1,
			'endX' => 1,
			'endY' => 1,
			'limit' => 1,
			'iconset' => 'Lorem ipsum dolor sit amet',
			'type' => 'Lorem ipsum dolor ',
			'object_id' => 1,
			'created' => '2017-01-27 16:00:10',
			'modified' => '2017-01-27 16:00:10'
		),
	);

}
