<?php

/**
 * NagiosContactNotificationcommand Fixture
 */
class NagiosContactNotificationcommandFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'contact_notificationcommand_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'contact_id'                     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'notification_type'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'command_object_id'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_args'                   => ['type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'                        => [
            'PRIMARY'    => ['column' => 'contact_notificationcommand_id', 'unique' => 1],
            'contact_id' => ['column' => ['contact_id', 'notification_type', 'command_object_id'], 'unique' => 1]
        ],
        'tableParameters'                => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Contact host and service notification commands']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'contact_notificationcommand_id' => 1,
            'instance_id'                    => 1,
            'contact_id'                     => 1,
            'notification_type'              => 1,
            'command_object_id'              => 1,
            'command_args'                   => 'Lorem ipsum dolor sit amet'
        ],
    ];

}
