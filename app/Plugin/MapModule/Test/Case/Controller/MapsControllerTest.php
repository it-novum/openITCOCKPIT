<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

//run test: oitc test app Controller/MapsController
//testing the whole MapsModule

App::uses('AppController', 'Controller');


class MapsControllerTest extends ControllerTestCase{
    public $fixtures = [
        'plugin.map_module.map',
        'plugin.map_module.mapsToContainer',
        'plugin.map_module.mapitem',
        'plugin.map_module.mapline',
        'plugin.map_module.mapgadget',
        'plugin.map_module.mapicon',
        'plugin.map_module.maptext',
        'plugin.map_module.mapsToRotation',
        'app.systemsetting',
        'app.container',
        'app.user',
        'app.usergroup',
        'app.usersToContainer',
        'app.rotation',
        'app.tenant',
    ];

    public function setUp(){
        parent::setUp();
        $this->Map = ClassRegistry::init('MapModule.Map');
    }

    public function testIndex(){
        $this->testAction('/map_module/maps/index', ['method' => 'get']);
        $expectedMaps = [
            [
                'Map' => [
                    'id' => 1,
                    'name' => 'Lorem ipsum dolor sit amet',
                    'title' => 'Lorem ipsum dolor sit amet',
                    'background' => 'Lorem ipsum dolor sit amet',
                    'refresh_interval' => '1',
                    'created' => '2017-01-30 09:37:53',
                    'modified' => '2017-01-30 09:37:53'
                ],
                'Container' => [
                    [
                        'id' => '1',
                        'MapsToContainer' => [
                            'id' => '1',
                            'map_id' => '1',
                            'container_id' => '1'
                        ]
                    ]
                ]
            ]
        ];
        $this->assertEquals($expectedMaps, $this->vars['all_maps']);
    }

    public function testAdd() {
        $data = [
            'Map' => [
                'container_id' => [1],
                'name' => 'New map',
                'title' => 'New map title',
                'refresh_interval' => '90'
            ]
        ];
        $this->testAction('/map_module/maps/add', ['data' => $data, 'method' => 'post']);
        $myMap = $this->Map->find('first', [
            'order' => ['id' => 'desc'],
            'contain' => ['Container', 'MapsToContainer'],
            'fields' => ['Map.name', 'Map.title', 'Map.refresh_interval'],
            'recursive' => -1
        ]);
        unset($myMap['Map']['id']);
        unset($myMap['Container'][0]['MapsToContainer']['map_id']);
        unset($myMap['Container'][0]['MapsToContainer']['id']);

        $expectedMap = [
            'Map' => [
                'name' => 'New map',
                'title' => 'New map title',
                'refresh_interval' => '90000'
            ],
            'Container' => [
                [
                    'id' => '1',
                    'containertype_id' => '1',
                    'name' => 'ROOT',
                    'parent_id' => null,
                    'lft' => '1',
                    'rght' => '12',
                    'MapsToContainer' => [
                        'container_id' => '1'
                    ]
                ]
            ]
        ];
        $this->assertEquals($expectedMap, $myMap);
    }

    public function testGetEdit() {
        $this->testAction('/map_module/maps/edit/1', ['method' => 'get']);
        var_dump($this->vars);
        $expectedCommand = [
            'Command' => [
                'id' => 1,
                'name' => 'My first command',
                'command_line' => 'My first command_line',
                'command_type' => 1,
                'human_args' => 'My first human_args',
                'uuid' => '1234567890',
                'description' => 'My first human_args'
            ],
            'Commandargument' => [
                [
                    'id' => 1,
                    'command_id' => 1,
                    'name' => 'My name',
                    'human_name' => 'My human_name',
                    'created' => '2017-01-17 14:24:01',
                    'modified' => '2017-01-17 14:24:01'
                ],
                [
                    'id' => 2,
                    'command_id' => 1,
                    'name' => 'My name 2',
                    'human_name' => 'My human_name 2',
                    'created' => '2017-01-17 14:24:02',
                    'modified' => '2017-01-17 14:24:02'
                ]
            ]
        ];
        $this->assertEquals($expectedCommand, $this->vars['command']);
    }
}