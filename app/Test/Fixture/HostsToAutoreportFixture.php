<?php
/**
 * HostsToAutoreport Fixture
 */
class HostsToAutoreportFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'autoreport_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'outage_duration' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'configuration_option' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'autoreport_id_host' => array('column' => array('autoreport_id', 'host_id'), 'unique' => 1),
			'host_id' => array('column' => 'host_id', 'unique' => 0)
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
			'autoreport_id' => 1,
			'host_id' => 1,
			'outage_duration' => 1,
			'configuration_option' => 1,
			'created' => '2017-01-27 15:49:46',
			'modified' => '2017-01-27 15:49:46'
		),
	);

}
