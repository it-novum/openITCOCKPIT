<?php

/**
 * NagiosSystemcommand Fixture
 */
class NagiosSystemcommandFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'systemcommand_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false, 'key' => 'index'],
        'start_time'       => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'start_time_usec'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'end_time'         => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'end_time_usec'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'command_line'     => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'timeout'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'early_timeout'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'execution_time'   => ['type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false],
        'return_code'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'output'           => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'long_output'      => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'          => [
            'PRIMARY'     => ['column' => 'systemcommand_id', 'unique' => 1],
            'instance_id' => ['column' => 'instance_id', 'unique' => 0]
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB', 'comment' => 'Historical system commands that are executed']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'systemcommand_id' => 1,
            'instance_id'      => 1,
            'start_time'       => '2017-01-27 17:20:08',
            'start_time_usec'  => 1,
            'end_time'         => '2017-01-27 17:20:08',
            'end_time_usec'    => 1,
            'command_line'     => 'Lorem ipsum dolor sit amet',
            'timeout'          => 1,
            'early_timeout'    => 1,
            'execution_time'   => 1,
            'return_code'      => 1,
            'output'           => 'Lorem ipsum dolor sit amet',
            'long_output'      => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
        ],
    ];

}
