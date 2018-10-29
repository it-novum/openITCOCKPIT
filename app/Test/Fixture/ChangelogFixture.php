<?php

/**
 * Changelog Fixture
 */
class ChangelogFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
        'model'           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'action'          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
        'objecttype_id'   => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'user_id'         => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
        'data'            => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name'            => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null, 'key' => 'index'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'created' => ['column' => 'created', 'unique' => 0]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB']
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id'            => 1,
            'model'         => 'Lorem ipsum dolor sit amet',
            'action'        => 'Lorem ipsum dolor sit amet',
            'object_id'     => 1,
            'objecttype_id' => 1,
            'user_id'       => 1,
            'data'          => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'name'          => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created'       => '2017-01-27 15:38:28'
        ],
    ];

}
