<?php
class ServicesServiceescalationFixture extends CakeTestFixture{

    public $table = 'services_to_serviceescalations';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'service_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'serviceescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'excluded' => ['type' => 'integer', 'null' => false, 'default' => 0],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'service_id' => ['column' => 'service_id', 'unique' => 0],
            'serviceescalation_id' => ['column' => 'serviceescalation_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];
}


