<?php

/**
 * Cronjob Fixture
 */
class CronjobFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'task'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'plugin'          => ['type' => 'string', 'null' => false, 'default' => 'Core', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'interval'        => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
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
            'id'       => 1,
            'task'     => 'Lorem ipsum dolor sit amet',
            'plugin'   => 'Lorem ipsum dolor sit amet',
            'interval' => 1
        ],
    ];

}
