<?php
/**
 * Hosttemplatecommandargumentvalue Fixture
 */
class HosttemplatecommandargumentvalueFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'commandargument_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'hosttemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
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
			'commandargument_id' => 1,
			'hosttemplate_id' => 1,
			'value' => 'Lorem ipsum dolor sit amet',
			'created' => '2017-01-19 17:25:06',
			'modified' => '2017-01-19 17:25:06'
		),
	);

}
