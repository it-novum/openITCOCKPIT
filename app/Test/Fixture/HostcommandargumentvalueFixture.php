<?php

/**
 * Hostcommandargumentvalue Fixture
 */
class HostcommandargumentvalueFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'host_id'            => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'value'              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'                 => 1,
            'commandargument_id' => 1,
            'host_id'            => 1,
            'value'              => 'Lorem ipsum dolor sit amet',
            'created'            => '2017-01-27 15:46:56',
            'modified'           => '2017-01-27 15:46:56'
        ],
    ];

}
