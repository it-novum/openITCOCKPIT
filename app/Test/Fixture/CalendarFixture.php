<?php

/**
 * Calendar Fixture
 */
class CalendarFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
        'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'description' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'container_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
            'UNIQUE_NAME' => array('column' => array('container_id', 'name'), 'unique' => 1)
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
            'name' => 'My first calendar',
            'description' => 'My first calendar description',
            'container_id' => 1,
            'created' => '2017-01-26 16:19:36',
            'modified' => '2017-01-26 16:19:36'
        ),
        array(
            'id' => 2,
            'name' => 'My second calendar',
            'description' => 'My second calendar description',
            'container_id' => 1,
            'created' => '2017-01-26 16:19:36',
            'modified' => '2017-01-26 16:19:36'
        ),
    );

}
