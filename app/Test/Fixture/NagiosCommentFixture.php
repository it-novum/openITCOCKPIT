<?php

/**
 * NagiosComment Fixture
 */
class NagiosCommentFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'comment_id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'instance_id'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'entry_time'          => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'entry_time_usec'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'comment_type'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'entry_type'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'object_id'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'comment_time'        => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'internal_comment_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false],
        'author_name'         => ['type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'comment_data'        => ['type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_persistent'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'comment_source'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'expires'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'unsigned' => false],
        'expiration_time'     => ['type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'],
        'indexes'             => [
            'PRIMARY' => ['column' => 'comment_id', 'unique' => 1]
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'comment_id'          => 1,
            'instance_id'         => 1,
            'entry_time'          => '2017-01-27 16:13:11',
            'entry_time_usec'     => 1,
            'comment_type'        => 1,
            'entry_type'          => 1,
            'object_id'           => 1,
            'comment_time'        => '2017-01-27 16:13:11',
            'internal_comment_id' => 1,
            'author_name'         => 'Lorem ipsum dolor sit amet',
            'comment_data'        => 'Lorem ipsum dolor sit amet',
            'is_persistent'       => 1,
            'comment_source'      => 1,
            'expires'             => 1,
            'expiration_time'     => '2017-01-27 16:13:11'
        ],
    ];

}
