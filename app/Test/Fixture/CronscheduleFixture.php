<?php
/**
 * Cronschedule Fixture
 */
class CronscheduleFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'cronjob_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'is_running' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'end_time' => array('type' => 'datetime', 'null' => false, 'default' => null),
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
			'cronjob_id' => 1,
			'is_running' => 1,
			'start_time' => '2017-01-27 15:41:38',
			'end_time' => '2017-01-27 15:41:38'
		),
	);

}
