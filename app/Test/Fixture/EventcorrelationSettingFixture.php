<?php

/**
 * EventcorrelationSetting Fixture
 */
class EventcorrelationSettingFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'configuration_option' => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'monitoring_system'    => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'              => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'             => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'              => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'                   => 1,
            'configuration_option' => 'Lorem ipsum dolor sit amet',
            'monitoring_system'    => 'Lorem ipsum dolor sit amet',
            'created'              => '2017-01-27 15:43:53',
            'modified'             => '2017-01-27 15:43:53'
        ],
    ];

}
