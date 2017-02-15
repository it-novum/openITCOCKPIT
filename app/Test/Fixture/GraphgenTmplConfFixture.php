<?php
/**
 * GraphgenTmplConf Fixture
 */
class GraphgenTmplConfFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'graphgen_tmpl_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'data_sources' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 256, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
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
			'graphgen_tmpl_id' => 1,
			'service_id' => 1,
			'data_sources' => 'Lorem ipsum dolor sit amet'
		),
	);

}
