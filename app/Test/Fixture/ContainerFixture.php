<?php

/**
 * Container Fixture
 * app/Console/cake bake fixture Container
 */
class ContainerFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'containertype_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'name'             => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'parent_id'        => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'lft'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'rght'             => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'indexes'          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public function init() {
        $this->records = [
            [
                'id'               => 1,
                'containertype_id' => CT_GLOBAL,
                'name'             => 'ROOT',
                'parent_id'        => null,
                'lft'              => '1',
                'rght'             => '6',
            ],
            [
                'id'               => 2,
                'containertype_id' => CT_TENANT,
                'name'             => 'TenantA',
                'parent_id'        => 1,
                'lft'              => '2',
                'rght'             => '3',
            ],
            [
                'id'               => 3,
                'containertype_id' => CT_TENANT,
                'name'             => 'TenantB',
                'parent_id'        => 1,
                'lft'              => '4',
                'rght'             => '5',
            ]
        ];
        parent::init();
    }
}
