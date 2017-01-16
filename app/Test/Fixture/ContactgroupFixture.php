<?php
class ContactgroupFixture extends CakeTestFixture {
    public $import = [
        'model' => 'Contactgroup'
    ];

    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'description'     => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public function init() {
        $this->records = array(
            array(
                'id' => 1,
                'uuid' => 'f70418ac-d646-47b1-a037-7d5a66323421',
                'container_id' => '4',
                'description' => 'ContactgroupA DESC'
            ),
            array(
                'id' => 2,
                'uuid' => 'cbd04963-faaa-4448-87a0-da7cdffd7207',
                'container_id' => '5',
                'description' => 'ContactgroupB DESC'
            ),
            array(
                'id' => 3,
                'uuid' => 'e3a314ff-18a8-4fb1-baa6-caa422892e2b',
                'container_id' => '6',
                'description' => 'ContactgroupC DESC'
            )
        );
        parent::init();
    }
}