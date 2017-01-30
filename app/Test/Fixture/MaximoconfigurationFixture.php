<?php
/**
 * Maximoconfiguration Fixture
 */
class MaximoconfigurationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'element_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'unique'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'maximo_service_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'maximo_ownergroup_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'impact_level' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'urgency_level' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'element_id' => array('column' => 'element_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'element_id' => 1,
			'type' => 'Lorem ipsum dolor sit amet',
			'maximo_service_id' => 1,
			'maximo_ownergroup_id' => 1,
			'impact_level' => 1,
			'urgency_level' => 1
		),
	);

}
