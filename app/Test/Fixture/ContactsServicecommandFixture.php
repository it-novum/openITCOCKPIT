<?php
class ContactsServicecommandFixture extends CakeTestFixture
{

    public $table = 'contacts_to_servicecommands';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'command_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'contact_id' => ['column' => 'contact_id', 'unique' => 0],
            'command_id' => ['column' => 'command_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];
}