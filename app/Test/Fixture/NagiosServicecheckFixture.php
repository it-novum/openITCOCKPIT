<?php

/**
 * NagiosServicecheck Fixture
 */
class NagiosServicecheckFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'servicecheck_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'service_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'index'],
        'check_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'current_check_attempt' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'max_check_attempts'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'state'                 => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'state_type'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'start_time'            => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'primary'],
        'start_time_usec'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_time'              => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_object_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_args'          => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_line'          => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeout'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'early_timeout'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'execution_time'        => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'latency'               => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'return_code'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'output'                => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'           => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'perfdata'              => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'               => [
            'PRIMARY'           => ['column' => ['servicecheck_id', 'start_time'], 'unique' => 1],
            'start_time'        => ['column' => 'start_time', 'unique' => 0],
            'service_object_id' => ['column' => 'service_object_id', 'unique' => 0],
            'start_time_2'      => ['column' => 'start_time', 'unique' => 0]
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical service checks']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'servicecheck_id'       => 1,
            'instance_id'           => 1,
            'service_object_id'     => 1,
            'check_type'            => 1,
            'current_check_attempt' => 1,
            'max_check_attempts'    => 1,
            'state'                 => 1,
            'state_type'            => 1,
            'start_time'            => '2017-01-27 17:04:10',
            'start_time_usec'       => 1,
            'end_time'              => '2017-01-27 17:04:10',
            'end_time_usec'         => 1,
            'command_object_id'     => 1,
            'command_args'          => 'Lorem ipsum dolor sit amet',
            'command_line'          => 'Lorem ipsum dolor sit amet',
            'timeout'               => 1,
            'early_timeout'         => 1,
            'execution_time'        => 1,
            'latency'               => 1,
            'return_code'           => 1,
            'output'                => 'Lorem ipsum dolor sit amet',
            'long_output'           => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'perfdata'              => 'Lorem ipsum dolor sit amet'
        ],
    ];

}
