<?php
class MapsToContainerFixture extends CakeTestFixture {

    public $table = 'maps_to_containers';

    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'map_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'      => ['column' => 'id', 'unique' => 1],
            'map_id'   => ['column' => 'map_id', 'unique' => 0],
            'container_id' => ['column' => 'container_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $records = array(
        array(
            'id' => 1,
            'map_id' => '1',
            'container_id' => '1'
        ),
        array(
            'id' => 2,
            'map_id' => '2',
            'container_id' => '1'
        ),
        array(
            'id' => 3,
            'map_id' => '3',
            'container_id' => '1'
        ),
    );

}