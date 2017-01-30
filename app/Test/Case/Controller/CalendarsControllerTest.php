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

//run test: oitc test app Controller/CalendarsController

App::uses('AppController', 'Controller');

class CalendarsControllerTest extends ControllerTestCase {
    public $fixtures = [
        'app.calendar',
        'app.calendarHoliday',
        'app.systemsetting',
        'app.tenant',
        'app.user',
        'app.usergroup',
        'app.usersToContainer',
        'app.container'
    ];

    public function setUp() {
        parent::setUp();
        $this->Calendar = ClassRegistry::init('Calendar');
    }

    public function testIndex(){
        $this->testAction('/calendars/index', ['method' => 'get']);
        $expectedCalendars = [
            [
                'Calendar' => [
                    'id' => '1',
                    'name' => 'My first calendar',
                    'description' => 'My first calendar decription',
                    'container_id' => '1'
                ],
                'Container' => [
                    'id' => '1'
                ]
            ]
        ];
        $this->assertEquals($expectedCalendars, $this->vars['calendars']);
    }

    public function testAdd(){
        $data = [
            [
                'Calendar' => [
                    'id' => '2',
                    'name' => 'My second calendar',
                    'description' => 'My second calendar decription',
                    'container_id' => '1'
                ],
                'Container' => [
                    'id' => '1'
                ]
            ]
        ];
        $this->testAction('/calendars/add', ['data' => $data, 'method' => 'post']);

        $myCalendar = $this->Calendar->find('first', [
            'fields'     => [
                'Calendar.id',
                'Calendar.name',
                'Calendar.description',
                'Calendar.container_id',
                'Container.id',
            ],
            'order' => ['Calendar.id' => 'desc']
        ]);

        $expectedCalendar = [
            'Calendar' => [
                'id' => '2',
                'name' => 'My second calendar',
                'description' => 'My second calendar decription',
                'container_id' => '1'
            ],
            'Container' => [
                'id' => '1'
            ],
            'CalendarHoliday' => [

            ]
        ];
        $this->assertEquals($expectedCalendar, $myCalendar);
    }

    public function testGetEdit() {
        $data = array();
        $data['LoginUser']['email'] = 'admin@it-novum.com';
        $data['LoginUser']['password'] = 'asdf12';

        //test login action
        $this->testAction('/login/login', array(
                "method" => "post",
                "return" => "contents",
                "data" => $data
            )
        );
        debug($this->vars);
        $this->testAction('/calendars/edit/1', ['method' => 'get']);
        $expectedCalendar = [
            'Calendar' => [
                'id' => '1',
                'name' => 'My first calendar',
                'description' => 'My first calendar decription',
                'container_id' => '1'
            ],
            'Container' => [
                'id' => '1',
                'containertype_id' => '1',
                'name' => 'ROOT',
                'parent_id' => null,
                'lft' => '1',
                'rght' => '12'
            ],
            'CalendarHoliday' => [

            ]
        ];
        unset($this->vars['calendar']['Calendar']['created']);
        unset($this->vars['calendar']['Calendar']['modified']);
        $this->assertEquals($expectedCalendar, $this->vars['calendar']);
    }
}