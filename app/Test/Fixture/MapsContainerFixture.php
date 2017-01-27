<?php
class MapsContainerFixture extends CakeTestFixture{

    public $table = 'maps_to_containers';

    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'map_id'          => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'      => ['column' => 'id', 'unique' => 1],
            'container_id' => ['column' => 'container_id', 'unique' => 0],
            'map_id'       => ['column' => 'map_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];
}
