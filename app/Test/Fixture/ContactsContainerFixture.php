<?php

class ContactsContainerFixture extends CakeTestFixture {

    public $table = 'contacts_to_containers';

    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'      => ['column' => 'id', 'unique' => 1],
            'contact_id'   => ['column' => 'contact_id', 'unique' => 0],
            'container_id' => ['column' => 'container_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public function init() {
        $this->records = [
            [
                'id'           => 1,
                'contact_id'   => '1',
                'container_id' => '1'
            ],
            [
                'id'           => 2,
                'contact_id'   => '2',
                'container_id' => '2'
            ],
            [
                'id'           => 3,
                'contact_id'   => '1',
                'container_id' => '3'
            ]
        ];
        parent::init();
    }
}