<?php

/**
 * Systemfailure Fixture
 */
class SystemfailureFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'start_time'      => ['type' => 'datetime', 'null' => false, 'default' => null],
        'end_time'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'comment'         => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'user_id'         => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
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
            'id'         => 1,
            'start_time' => '2017-01-27 18:13:59',
            'end_time'   => '2017-01-27 18:13:59',
            'comment'    => 'Lorem ipsum dolor sit amet',
            'user_id'    => 1,
            'created'    => '2017-01-27 18:13:59',
            'modified'   => '2017-01-27 18:13:59'
        ],
    ];

}
