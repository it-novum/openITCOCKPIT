<?php

/**
 * DeletedHost Fixture
 */
class DeletedHostFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'uuid'             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'hosttemplate_id'  => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'host_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'name'             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'      => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'deleted_perfdata' => ['type' => 'integer', 'null' => true, 'default' => '0', 'length' => 1, 'unsigned' => false],
        'created'          => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1]
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'               => 1,
            'uuid'             => 'Lorem ipsum dolor sit amet',
            'hosttemplate_id'  => 1,
            'host_id'          => 1,
            'name'             => 'Lorem ipsum dolor sit amet',
            'description'      => 'Lorem ipsum dolor sit amet',
            'deleted_perfdata' => 1,
            'created'          => '2017-01-27 15:42:42',
            'modified'         => '2017-01-27 15:42:42'
        ],
    ];

}
