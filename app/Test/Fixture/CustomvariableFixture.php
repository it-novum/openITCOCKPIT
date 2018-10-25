<?php

/**
 * Customvariable Fixture
 */
class CustomvariableFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'objecttype_id'   => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'value'           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
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
            'id'            => 1,
            'object_id'     => 1,
            'objecttype_id' => 1,
            'name'          => 'Lorem ipsum dolor sit amet',
            'value'         => 'Lorem ipsum dolor sit amet',
            'created'       => '2017-01-27 15:41:53',
            'modified'      => '2017-01-27 15:41:53'
        ],
    ];

}
