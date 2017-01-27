<?php

/**
 * Container Fixture
 * app/Console/cake bake fixture Container
 */
class ContainerFixture extends CakeTestFixture {

    /**
     * Fields
     *
     * @var array
     */
    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
        'containertype_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
        'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
        'lft' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
        'rght' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1)
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
    );

    /**
     * Records
     *
     * @var array
     */
    public function init() {
        $this->records = array(
            array(
                'id' => 1,
                'containertype_id' => CT_GLOBAL,
                'name' => 'ROOT',
                'parent_id' => NULL,
                'lft' => '1',
                'rght' => '6',
            ),
            array(
                'id' => 2,
                'containertype_id' => CT_TENANT,
                'name' => 'TenantA',
                'parent_id' => 1,
                'lft' => '2',
                'rght' => '3',
            ),
            array(
                'id' => 3,
                'containertype_id' => CT_TENANT,
                'name' => 'TenantB',
                'parent_id' => 1,
                'lft' => '4',
                'rght' => '5',
            )
        );
        parent::init();
    }
}
