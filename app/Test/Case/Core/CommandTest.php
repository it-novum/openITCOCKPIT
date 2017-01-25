<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.



//run test: oitc test app Core/Command

App::uses('Command', 'Model');

class CommandTest extends CakeTestCase{

    public $fixtures = ['app.command', 'app.commandargument'];

    public function setUp() {
        parent::setUp();
        $this->Command = ClassRegistry::init('Command');
    }

    public function testView() {
        $myCommand = $this->Command->find('first', [
            'conditions' => ['Command.id' => 1]
        ]);

        $expected = [
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
                ],
            ]
        ];

        $this->assertEquals($expected, $myCommand);
    }

}