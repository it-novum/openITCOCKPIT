<?php

class ServicetemplatesServicetemplategroupFixture extends CakeTestFixture {

    public $table = 'servicetemplates_to_servicetemplategroups';

    public $fields = [
        'id'                      => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'servicetemplate_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicetemplategroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'                 => [
            'PRIMARY'                 => ['column' => 'id', 'unique' => 1],
            'servicetemplate_id'      => ['column' => 'servicetemplate_id', 'unique' => 0],
            'servicetemplategroup_id' => ['column' => 'servicetemplategroup_id', 'unique' => 0],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];
}




