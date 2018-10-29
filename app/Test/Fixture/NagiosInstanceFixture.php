<?php

/**
 * NagiosInstance Fixture
 */
class NagiosInstanceFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'instance_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false, 'key' => 'primary'],
        'instance_name'        => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'instance_description' => ['type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'              => [
            'PRIMARY' => ['column' => 'instance_id', 'unique' => 1]
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Location names of various Nagios installations']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'instance_id'          => 1,
            'instance_name'        => 'Lorem ipsum dolor sit amet',
            'instance_description' => 'Lorem ipsum dolor sit amet'
        ],
    ];

}
