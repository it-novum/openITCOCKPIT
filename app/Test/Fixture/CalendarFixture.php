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
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'     => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'     => ['column' => 'id', 'unique' => 1],
            'UNIQUE_NAME' => ['column' => ['container_id', 'name'], 'unique' => 1]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'           => 1,
            'name'         => 'My first calendar',
            'description'  => 'My first calendar description',
            'container_id' => 1,
            'created'      => '2017-01-26 16:19:36',
            'modified'     => '2017-01-26 16:19:36'
        ],
        [
            'id'           => 2,
            'name'         => 'My second calendar',
            'description'  => 'My second calendar description',
            'container_id' => 1,
            'created'      => '2017-01-26 16:19:36',
            'modified'     => '2017-01-26 16:19:36'
        ],
    ];

}
