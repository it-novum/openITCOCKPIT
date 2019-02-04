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

//run test: oitc test app Controller/CommandsController

App::uses('AppController', 'Controller');

class CommandsControllerTest extends ControllerTestCase {
    public $fixtures = [
        'app.command',
        'app.commandargument',
        'app.contact',
        'app.service',
        'app.host',
        'app.systemsetting',
        'app.servicetemplate',
        'app.hosttemplate',
        'app.servicecommandargumentvalue',
        'app.hostcommandargumentvalue',
        'app.servicetemplatecommandargumentvalue',
        'app.servicetemplateeventcommandargumentvalue',
        'app.hosttemplatecommandargumentvalue',
        'app.contactsServicecommand',
        'app.contactsHostcommand',
        'app.contactsServicetemplate',
        'app.Macro'
    ];

    public function setUp() {
        parent::setUp();
//        $user = $this->User->find('first', ['conditions' => $conditions]);
//        $this->Auth->login($user)
        $this->Command = ClassRegistry::init('Command');
    }

    public function testIndex() {
        $this->testAction('/commands/index', ['method' => 'get']);
        $expectedCommands = [
            [
                'Command' => [
                    'id'           => 1,
                    'name'         => 'My first command',
                    'command_line' => 'My first command_line',
                    'command_type' => 1,
                    'human_args'   => 'My first human_args',
                    'uuid'         => '1234567890',
                    'description'  => 'My first human_args'
                ]
            ]
        ];
        $this->assertEquals($expectedCommands, $this->vars['all_commands']);
    }

    public function testDelete() {
        $this->testAction('/commands/delete/1', ['method' => 'post']);
        $myCommand = $this->Command->find('first', [
            'conditions' => ['id' => '1']
        ]);
        $this->assertEquals([], $myCommand);
    }

    public function testMassDelete() {
        $data = [
            'Command' => [
                'delete' => [1, 2]
            ]
        ];
        $this->testAction('/commands/mass_delete/1/2', ['data' => $data, 'method' => 'post']);
        $myCommands = $this->Command->find('first', [
            'conditions' => ['id' => [1, 2]]
        ]);
        $this->assertEquals([], $myCommands);
    }

    public function testGetEdit() {
        $this->testAction('/commands/edit/1', ['method' => 'get']);
        $expectedCommand = [
            'Command'         => [
                'id'           => 1,
                'name'         => 'My first command',
                'command_line' => 'My first command_line',
                'command_type' => 1,
                'human_args'   => 'My first human_args',
                'uuid'         => '1234567890',
                'description'  => 'My first human_args'
            ],
            'Commandargument' => [
                [
                    'id'         => 1,
                    'command_id' => 1,
                    'name'       => 'My name',
                    'human_name' => 'My human_name',
                    'created'    => '2017-01-17 14:24:01',
                    'modified'   => '2017-01-17 14:24:01'
                ],
                [
                    'id'         => 2,
                    'command_id' => 1,
                    'name'       => 'My name 2',
                    'human_name' => 'My human_name 2',
                    'created'    => '2017-01-17 14:24:02',
                    'modified'   => '2017-01-17 14:24:02'
                ]
            ]
        ];
        $this->assertEquals($expectedCommand, $this->vars['command']);
    }

    public function testPostEdit() {
        $data = [
            'Command'         => [
                'id'           => '1',
                'command_type' => '2',
                'name'         => 'Changed name',
                'command_line' => 'Changed Command line test',
                'description'  => 'Changed Description'
            ],
            'Commandargument' => [
                'hash1' => [
                    'command_id' => '1',
                    'name'       => '$ARG1$',
                    'human_name' => 'Changed Human_name'
                ],
                'hash2' => [
                    'command_id' => '1',
                    'name'       => '$ARG2$',
                    'human_name' => 'New Human_name'
                ]
            ]
        ];
        $this->testAction('/commands/edit/1', ['data' => $data, 'method' => 'post']);
        $expectedCommand = [
            'Command'         => [
                'id'           => 1,
                'name'         => 'Changed name',
                'command_line' => 'Changed Command line test',
                'command_type' => '2',
                'human_args'   => 'My first human_args',
                'uuid'         => '1234567890',
                'description'  => 'Changed Description'
            ],
            'Commandargument' => [
                [
                    'command_id' => '1',
                    'name'       => '$ARG1$',
                    'human_name' => 'Changed Human_name'
                ],
                [
                    'command_id' => '1',
                    'name'       => '$ARG2$',
                    'human_name' => 'New Human_name'
                ]
            ]
        ];
        $changedCommand = $this->Command->find('first', [
            'conditions' => ['id' => '1']
        ]);
        unset($changedCommand['Commandargument'][0]['id']);
        unset($changedCommand['Commandargument'][0]['created']);
        unset($changedCommand['Commandargument'][0]['modified']);
        unset($changedCommand['Commandargument'][1]['id']);
        unset($changedCommand['Commandargument'][1]['created']);
        unset($changedCommand['Commandargument'][1]['modified']);
        $this->assertEquals($expectedCommand, $changedCommand);
    }


//    public function testView() {
//        $this->testAction('/commands/view/1.json');
//        debug($this->vars['command']);
//        $expectedCommand = [
//            [
//                'Command' => [
//                    'id' => 1,
//                    'name' => 'My first command',
//                    'command_line' => 'My first command_line',
//                    'command_type' => 1,
//                    'human_args' => 'My first human_args',
//                    'uuid' => '1234567890',
//                    'description' => 'My first human_args'
//                ]
//            ]
//        ];
//
//        $this->assertEquals($expectedCommand, $this->vars['command']);
//    }

    public function testAdd() {
        $data = [
            'Command'         => [
                'command_type' => '3',
                'name'         => 'Test name',
                'command_line' => 'Command line test',
                'description'  => 'Description test'
            ],
            'Commandargument' => [
                'hash1' => [
                    'command_id' => '',
                    'name'       => '$ARG1$',
                    'human_name' => 'ARG TEST 1'
                ],
                'hash2' => [
                    'command_id' => '',
                    'name'       => '$ARG2$',
                    'human_name' => 'ARG TEST 2'
                ]
            ]
        ];
        $this->testAction('/commands/add', ['data' => $data, 'method' => 'post']);
        $myCommand = $this->Command->find('first', [
            'order' => ['id' => 'desc']
        ]);

        unset($myCommand['Command']['uuid']);
        unset($myCommand['Commandargument'][0]['id']);
        unset($myCommand['Commandargument'][1]['id']);
        unset($myCommand['Commandargument'][0]['created']);
        unset($myCommand['Commandargument'][1]['created']);
        unset($myCommand['Commandargument'][0]['modified']);
        unset($myCommand['Commandargument'][1]['modified']);

        $expectedCommand = [
            'Command'         => [
                'id'           => $myCommand['Command']['id'],
                'command_type' => '3',
                'name'         => 'Test name',
                'command_line' => 'Command line test',
                'description'  => 'Description test',
                'human_args'   => null
            ],
            'Commandargument' => [
                [
                    'command_id' => $myCommand['Command']['id'],
                    'name'       => '$ARG1$',
                    'human_name' => 'ARG TEST 1'
                ],
                [
                    'command_id' => $myCommand['Command']['id'],
                    'name'       => '$ARG2$',
                    'human_name' => 'ARG TEST 2'
                ]
            ]
        ];
        $this->assertEquals($expectedCommand, $myCommand);
    }


    public function testGetCopy() {
        $this->testAction('/commands/copy/1/2', ['method' => 'get']);
        $expectedCommands = [
            1 => [
                'Command'         => [
                    'id'           => '1',
                    'name'         => 'My first command',
                    'command_line' => 'My first command_line',
                    'command_type' => 1,
                    'description'  => 'My first human_args'
                ],
                'Commandargument' => [
                    [
                        'name'       => 'My name',
                        'human_name' => 'My human_name'
                    ],
                    [
                        'name'       => 'My name 2',
                        'human_name' => 'My human_name 2'
                    ]
                ],
            ],
            2 => [
                'Command'         => [
                    'id'           => '2',
                    'name'         => 'My second command',
                    'command_line' => 'My second command_line',
                    'command_type' => 2,
                    'description'  => 'My second human_args'
                ],
                'Commandargument' => [
                    [
                        'name'       => 'My name 3',
                        'human_name' => 'My human_name 3',
                    ],
                    [
                        'name'       => 'My name 4',
                        'human_name' => 'My human_name 4',
                    ],
                    [
                        'name'       => 'My name 5',
                        'human_name' => 'My human_name 5',
                    ]
                ],
            ]
        ];
        $this->assertEquals($expectedCommands, $this->vars['commands']);
    }

    public function testPostCopy() {
        $data = [
            'Command' => [
                1 => [
                    'name'         => 'Copied name 1',
                    'command_line' => 'Copied command_line 1',
                    'description'  => 'Copied description 1'
                ],
                2 => [
                    'name'         => 'Copied name 2',
                    'command_line' => 'Copied command_line 2',
                    'description'  => 'Copied description 2'
                ]
            ]
        ];
        $this->testAction('/commands/copy/1/2', ['data' => $data, 'method' => 'post']);
        $copiedCommands = $this->Command->find('all', [
            'order' => ['id' => 'desc'],
            'limit' => 2
        ]);
        $expectedCommands = [
            [
                'Command'         => [
                    'name'         => 'Copied name 2',
                    'command_line' => 'Copied command_line 2',
                    'command_type' => 2,
                    'description'  => 'Copied description 2',
                    'human_args'   => null,
                ],
                'Commandargument' => [
                    [
                        'command_id' => $copiedCommands[0]['Command']['id'],
                        'name'       => 'My name 3',
                        'human_name' => 'My human_name 3'
                    ],
                    [
                        'command_id' => $copiedCommands[0]['Command']['id'],
                        'name'       => 'My name 4',
                        'human_name' => 'My human_name 4'
                    ],
                    [
                        'command_id' => $copiedCommands[0]['Command']['id'],
                        'name'       => 'My name 5',
                        'human_name' => 'My human_name 5'
                    ]
                ],
            ],
            [
                'Command'         => [
                    'name'         => 'Copied name 1',
                    'command_line' => 'Copied command_line 1',
                    'command_type' => 1,
                    'description'  => 'Copied description 1',
                    'human_args'   => null,
                ],
                'Commandargument' => [
                    [
                        'command_id' => $copiedCommands[1]['Command']['id'],
                        'name'       => 'My name',
                        'human_name' => 'My human_name'
                    ],
                    [
                        'command_id' => $copiedCommands[1]['Command']['id'],
                        'name'       => 'My name 2',
                        'human_name' => 'My human_name 2'
                    ],
                ],
            ]
        ];
        unset($copiedCommands[0]['Command']['id']);
        unset($copiedCommands[0]['Command']['uuid']);

        unset($copiedCommands[0]['Commandargument'][0]['id']);
        unset($copiedCommands[0]['Commandargument'][0]['created']);
        unset($copiedCommands[0]['Commandargument'][0]['modified']);

        unset($copiedCommands[0]['Commandargument'][1]['id']);
        unset($copiedCommands[0]['Commandargument'][1]['created']);
        unset($copiedCommands[0]['Commandargument'][1]['modified']);

        unset($copiedCommands[0]['Commandargument'][2]['id']);
        unset($copiedCommands[0]['Commandargument'][2]['created']);
        unset($copiedCommands[0]['Commandargument'][2]['modified']);

        unset($copiedCommands[1]['Command']['id']);
        unset($copiedCommands[1]['Command']['uuid']);

        unset($copiedCommands[1]['Commandargument'][0]['id']);
        unset($copiedCommands[1]['Commandargument'][0]['created']);
        unset($copiedCommands[1]['Commandargument'][0]['modified']);

        unset($copiedCommands[1]['Commandargument'][1]['id']);
        unset($copiedCommands[1]['Commandargument'][1]['created']);
        unset($copiedCommands[1]['Commandargument'][1]['modified']);

        $this->assertEquals($expectedCommands, $copiedCommands);
    }

}