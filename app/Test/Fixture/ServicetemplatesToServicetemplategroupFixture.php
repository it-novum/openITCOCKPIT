<?php

/**
 * ServicetemplatesToServicetemplategroup Fixture
 */
class ServicetemplatesToServicetemplategroupFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'servicetemplate_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'servicetemplategroup_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'],
        'indexes'                 => [
            'PRIMARY'                 => ['column' => 'id', 'unique' => 1],
            'servicetemplategroup_id' => ['column' => 'servicetemplategroup_id', 'unique' => 0],
            'servicetemplate_id'      => ['column' => 'servicetemplate_id', 'unique' => 0]
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'                      => 1,
            'servicetemplate_id'      => 1,
            'servicetemplategroup_id' => 1
        ],
    ];

}
