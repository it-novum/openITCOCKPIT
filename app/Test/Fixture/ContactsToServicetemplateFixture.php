<?php

/**
 * ContactsToServicetemplate Fixture
 */
class ContactsToServicetemplateFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'contact_id'         => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'indexes'            => [
            'PRIMARY'            => ['column' => 'id', 'unique' => 1],
            'contact_id'         => ['column' => 'contact_id', 'unique' => 0],
            'servicetemplate_id' => ['column' => 'servicetemplate_id', 'unique' => 0]
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
            'contact_id'         => 1,
            'servicetemplate_id' => 1
        ],
    ];

}
