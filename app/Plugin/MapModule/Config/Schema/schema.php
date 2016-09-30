<?php
class MapModuleSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}


	public $maps = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'background' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'refresh_interval' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $maps_to_containers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'map_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'container_id' => array('column' => 'container_id', 'unique' => 0),
			'map_id' => array('column' => 'map_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $mapitems = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'map_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'x' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'y' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'limit' => array('type' => 'integer', 'null' => true, 'default' => 0),
		'iconset' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $maplines = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'map_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'startX' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'startY' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'endX' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'endY' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'limit' => array('type' => 'integer', 'null' => true, 'default' => 0),
		'iconset' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'object_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $mapgadgets = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'map_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'x' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'y' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'limit' => array('type' => 'integer', 'null' => true, 'default' => 0),
		'gadget' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'transparent_background' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 5),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);


	public $rotations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'interval' => array('type' => 'integer', 'null' => false, 'default' => 60),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $maps_to_rotations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'map_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'rotation_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'map_id' => array('column' => 'map_id', 'unique' => 0),
			'rotation_id' => array('column' => 'rotation_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $mapicons = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'map_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'x' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'y' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'icon' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $maptexts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'map_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'x' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'y' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'text' => array('type' => 'string', 'length' => 256, 'null' => false, 'default' => 0, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'font_size' => array('type' => 'integer', 'null' => false, 'default' => 11),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);
}
