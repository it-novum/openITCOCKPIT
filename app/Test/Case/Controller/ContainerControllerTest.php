<?php
App::uses('ContainerController', 'Controller');

/**
 * ContainerController Test Case
 * Console/cake bake test Controller Container
 */


//run test: oitc test app Controller/ContainerController

App::uses('AppController', 'Controller');

class ContainerControllerTest extends ControllerTestCase {
    public $fixtures = [
        'app.container',
        'app.systemsetting',
        'app.tenant',
        'app.contactgroup',
        'app.location',
        'app.hosttemplate',
        'app.hostgroup',
        'app.servicegroup',
        'app.calendar',
        'app.usersToContainer',
        'app.host',
        'app.timeperiod',
        'app.autoreport',
        'app.mapsContainer',
    ];

    public function setUp() {
        parent::setUp();
        $this->Container = ClassRegistry::init('Container');
    }

    public function testIndex() {
        $this->testAction('/containers/index', ['method' => 'get']);
        $expectedContainers = [
            [
                'Container' => [
                    'id' => 1,
                    'containertype_id' => CT_GLOBAL,
                    'name' => 'ROOT',
                    'parent_id' => NULL,
                    'lft' => '1',
                    'rght' => '6',
                ]
            ],
            [
                'Container' => [
                    'id' => 2,
                    'containertype_id' => CT_TENANT,
                    'name' => 'TenantA',
                    'parent_id' => 1,
                    'lft' => '2',
                    'rght' => '3',
                ]
            ],
            [
                'Container' => [
                    'id' => 3,
                    'containertype_id' => CT_TENANT,
                    'name' => 'TenantB',
                    'parent_id' => 1,
                    'lft' => '4',
                    'rght' => '5',
                ]
            ]
        ];
        $this->assertEquals($expectedContainers, $this->vars['all_containers']);
    }

    public function testDelete(){
        $this->testAction('/containers/delete/3', ['method' => 'post']);
        $result = $this->Container->find('all', [
            'recursive' => -1
        ]);
        $expectedContainers = [
            [
                'Container' => [
                    'id' => 1,
                    'containertype_id' => CT_GLOBAL,
                    'name' => 'ROOT',
                    'parent_id' => NULL,
                    'lft' => '1',
                    'rght' => '4',
                ]
            ],
            [
                'Container' => [
                    'id' => 2,
                    'containertype_id' => CT_TENANT,
                    'name' => 'TenantA',
                    'parent_id' => 1,
                    'lft' => '2',
                    'rght' => '3',
                ]
            ],
        ];
        $this->assertEquals($expectedContainers, $result);
    }
}

