<?php

use itnovum\openITCOCKPIT\InitialDatabase;

class MapModuleSchema extends CakeSchema {

	public function before($event = array()) {
		$db = ConnectionManager::getDataSource($this->connection);
		$db->cacheSources = false;
		return true;
	}

	public function after($event = array()) {
		if(isset($event['update'])) {
			switch ($event['update']) {
				case 'map_uploads':
					$data = [
						[
							[
								'MapUpload' =>
									[
										'id' => '1',
										'upload_type' => '2',
										'upload_name' => 'arrows_128px',
										'saved_name' => 'arrows_128px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '2',
										'upload_type' => '2',
										'upload_name' => 'arrows_16px',
										'saved_name' => 'arrows_16px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '3',
										'upload_type' => '2',
										'upload_name' => 'arrows_32px',
										'saved_name' => 'arrows_32px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '4',
										'upload_type' => '2',
										'upload_name' => 'arrows_64px',
										'saved_name' => 'arrows_64px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '5',
										'upload_type' => '2',
										'upload_name' => 'arrows_h_128px',
										'saved_name' => 'arrows_h_128px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '6',
										'upload_type' => '2',
										'upload_name' => 'arrows_h_16px',
										'saved_name' => 'arrows_h_16px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '7',
										'upload_type' => '2',
										'upload_name' => 'arrows_h_32px',
										'saved_name' => 'arrows_h_32px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '8',
										'upload_type' => '2',
										'upload_name' => 'arrows_h_64px',
										'saved_name' => 'arrows_h_64px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '9',
										'upload_type' => '2',
										'upload_name' => 'arrows_v_128px',
										'saved_name' => 'arrows_v_128px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '10',
										'upload_type' => '2',
										'upload_name' => 'arrows_v_16px',
										'saved_name' => 'arrows_v_16px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '11',
										'upload_type' => '2',
										'upload_name' => 'arrows_v_32px',
										'saved_name' => 'arrows_v_32px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '12',
										'upload_type' => '2',
										'upload_name' => 'arrows_v_64px',
										'saved_name' => 'arrows_v_64px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '13',
										'upload_type' => '2',
										'upload_name' => 'file_128px',
										'saved_name' => 'file_128px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '14',
										'upload_type' => '2',
										'upload_name' => 'file_16px',
										'saved_name' => 'file_16px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '15',
										'upload_type' => '2',
										'upload_name' => 'file_32px',
										'saved_name' => 'file_32px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '16',
										'upload_type' => '2',
										'upload_name' => 'file_64px',
										'saved_name' => 'file_64px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '17',
										'upload_type' => '2',
										'upload_name' => 'file_text_128px',
										'saved_name' => 'file_text_128px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '18',
										'upload_type' => '2',
										'upload_name' => 'file_text_16px',
										'saved_name' => 'file_text_16px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '19',
										'upload_type' => '2',
										'upload_name' => 'file_text_32px',
										'saved_name' => 'file_text_32px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '20',
										'upload_type' => '2',
										'upload_name' => 'file_text_64px',
										'saved_name' => 'file_text_64px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '21',
										'upload_type' => '2',
										'upload_name' => 'globe_128px',
										'saved_name' => 'globe_128px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '22',
										'upload_type' => '2',
										'upload_name' => 'globe_16px',
										'saved_name' => 'globe_16px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '23',
										'upload_type' => '2',
										'upload_name' => 'globe_32px',
										'saved_name' => 'globe_32px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '24',
										'upload_type' => '2',
										'upload_name' => 'globe_64px',
										'saved_name' => 'globe_64px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '25',
										'upload_type' => '2',
										'upload_name' => 'missing',
										'saved_name' => 'missing',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '26',
										'upload_type' => '2',
										'upload_name' => 'std_big_128px',
										'saved_name' => 'std_big_128px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '27',
										'upload_type' => '2',
										'upload_name' => 'std_mid_64px',
										'saved_name' => 'std_mid_64px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '28',
										'upload_type' => '2',
										'upload_name' => 'std_mini_32px',
										'saved_name' => 'std_mini_32px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '29',
										'upload_type' => '2',
										'upload_name' => 'tile_lg_128px',
										'saved_name' => 'tile_lg_128px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '30',
										'upload_type' => '2',
										'upload_name' => 'tile_md_64px',
										'saved_name' => 'tile_md_64px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '31',
										'upload_type' => '2',
										'upload_name' => 'tile_s_32px',
										'saved_name' => 'tile_s_32px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
							[
								'MapUpload' =>
									[
										'id' => '32',
										'upload_type' => '2',
										'upload_name' => 'tile_xs_16px',
										'saved_name' => 'tile_xs_16px',
										'user_id' => NULL,
										'container_id' => '1',
										'created' => '0000-00-00 00:00:00',
									],
							],
						]
					];

					$Model = ClassRegistry::init('MapModule.MapUpload');
					foreach($data as $record){
						$Model->create();
						$Model->saveAll($record);
					}



			}
		}
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

	public $map_uploads = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'upload_type' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'upload_name' => array('type' => 'string', 'null' => false, 'length' => 255, 'charset' => 'utf8'),
		'saved_name' => array('type' => 'string', 'null' => false, 'length' => 255, 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'container_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);
}
