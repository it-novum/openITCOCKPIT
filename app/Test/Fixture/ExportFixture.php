<?php

/**
 * Export Fixture
 */
class ExportFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'task'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'text'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'finished'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false],
        'successfully'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false],
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
            'id'           => 1,
            'task'         => 'Lorem ipsum dolor sit amet',
            'text'         => 'Lorem ipsum dolor sit amet',
            'finished'     => 1,
            'successfully' => 1,
            'created'      => '2017-01-27 15:44:32',
            'modified'     => '2017-01-27 15:44:32'
        ],
    ];

}
