<?php
/**
 * Automap Fixture
 */
class AutomapFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'host_regex' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'service_regex' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'show_ok' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_warning' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_critical' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_unknown' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_acknowledged' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_downtime' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_label' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'group_by_host' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'font_size' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'recursive' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
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
			'name' => 'Lorem ipsum dolor sit amet',
			'container_id' => 1,
			'description' => 'Lorem ipsum dolor sit amet',
			'host_regex' => 'Lorem ipsum dolor sit amet',
			'service_regex' => 'Lorem ipsum dolor sit amet',
			'show_ok' => 1,
			'show_warning' => 1,
			'show_critical' => 1,
			'show_unknown' => 1,
			'show_acknowledged' => 1,
			'show_downtime' => 1,
			'show_label' => 1,
			'group_by_host' => 1,
			'font_size' => 'Lorem ipsum dolor sit amet',
			'recursive' => 1,
			'created' => '2017-01-27 15:38:13',
			'modified' => '2017-01-27 15:38:13'
		),
	);

}
