<?php
/**
 * GraphgenCollectionItem Fixture
 */
class GraphgenCollectionItemFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'graphgen_tmpl_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'graphgen_colletion_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
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
			'graphgen_tmpl_id' => 1,
			'graphgen_colletion_id' => 1
		),
	);

}
