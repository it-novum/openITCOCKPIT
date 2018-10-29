<?php

/**
 * Hostdependency Fixture
 */
class HostdependencyFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'                               => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'uuid'                             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'                     => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'inherits_parent'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false],
        'timeperiod_id'                    => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'execution_fail_on_up'             => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'execution_fail_on_down'           => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'execution_fail_on_unreachable'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'execution_fail_on_pending'        => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'execution_none'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'notification_fail_on_up'          => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'notification_fail_on_down'        => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'notification_fail_on_unreachable' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'notification_fail_on_pending'     => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'notification_none'                => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false],
        'created'                          => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1]
        ],
        'tableParameters'                  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'                               => 1,
            'uuid'                             => 'Lorem ipsum dolor sit amet',
            'container_id'                     => 1,
            'inherits_parent'                  => 1,
            'timeperiod_id'                    => 1,
            'execution_fail_on_up'             => 1,
            'execution_fail_on_down'           => 1,
            'execution_fail_on_unreachable'    => 1,
            'execution_fail_on_pending'        => 1,
            'execution_none'                   => 1,
            'notification_fail_on_up'          => 1,
            'notification_fail_on_down'        => 1,
            'notification_fail_on_unreachable' => 1,
            'notification_fail_on_pending'     => 1,
            'notification_none'                => 1,
            'created'                          => '2017-01-27 15:47:18',
            'modified'                         => '2017-01-27 15:47:18'
        ],
    ];

}
