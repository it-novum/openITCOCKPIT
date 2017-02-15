<?php
/**
 * ServicetemplatesToServicetemplategroup Fixture
 */
class ServicetemplatesToServicetemplategroupFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'servicetemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'servicetemplategroup_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'servicetemplategroup_id' => array('column' => 'servicetemplategroup_id', 'unique' => 0),
			'servicetemplate_id' => array('column' => 'servicetemplate_id', 'unique' => 0)
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
			'servicetemplate_id' => 1,
			'servicetemplategroup_id' => 1
		),
	);

}
