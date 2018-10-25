<?php

/**
 * Serviceescalation Fixture
 */
class ServiceescalationFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                    => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'uuid'                  => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'          => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'timeperiod_id'         => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'first_notification'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false],
        'last_notification'     => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false],
        'notification_interval' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false],
        'escalate_on_recovery'  => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'escalate_on_warning'   => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'escalate_on_unknown'   => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'escalate_on_critical'  => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'created'               => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'              => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'               => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'                    => 1,
            'uuid'                  => 'Lorem ipsum dolor sit amet',
            'container_id'          => 1,
            'timeperiod_id'         => 1,
            'first_notification'    => 1,
            'last_notification'     => 1,
            'notification_interval' => 1,
            'escalate_on_recovery'  => 1,
            'escalate_on_warning'   => 1,
            'escalate_on_unknown'   => 1,
            'escalate_on_critical'  => 1,
            'created'               => '2017-01-27 17:41:15',
            'modified'              => '2017-01-27 17:41:15'
        ],
    ];

}
