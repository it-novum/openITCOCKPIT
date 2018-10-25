<?php

class ContactgroupsServicetemplateFixture extends CakeTestFixture {

    public $table = 'contactgroups_to_servicetemplates';

    public $fields = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY'            => ['column' => 'id', 'unique' => 1],
            'contactgroup_id'    => ['column' => 'contactgroup_id', 'unique' => 0],
            'servicetemplate_id' => ['column' => 'servicetemplate_id', 'unique' => 0],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];
}