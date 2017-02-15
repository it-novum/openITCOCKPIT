<?php
/**
 * IdoitObjecttype Fixture
 */
class IdoitObjecttypeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'configuration_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'objecttype_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'hosttemplate_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'container_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
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
			'configuration_id' => 1,
			'objecttype_id' => 1,
			'hosttemplate_id' => 1,
			'container_id' => 1
		),
	);

}
