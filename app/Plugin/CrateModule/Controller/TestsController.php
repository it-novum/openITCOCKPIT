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

class TestsController extends CrateModuleAppController {

    public $uses = [
        'CrateModule.Test',
        'CrateModule.CrateHost'
    ];

    public function index(){


        debug($this->CrateHost->find('all', [
            'joins' => [
                [
                    'table' => 'statusengine_hoststatus',
                    'type' => 'INNER',
                    'alias' => 'Hoststatus',
                    'conditions' => 'CrateHost.uuid = Hoststatus.hostname',
                ]
            ],
            'conditions' => [
                'Hoststatus.current_state' => 1
            ]
        ]));

        return;

        debug($this->Test->find('all', [
            'fields' => [
                '*'
            ],
            'conditions' => [
                //'Test.node_version' => ['3.0.0', '3.0.1'],
                'Test.node_version' => '3.0.0'
            ],
            'order' => [
                'node_start_time' => 'asc'
            ],
            'limit' => 50,
            'offset' => 0,
            'order' => [
                'Test.node_name' => 'asc',
                'node_start_time' => 'desc'
            ],
            //'group' => [
            //    'Test.node_name',
            //    'node_start_time'
            //]
        ]));


        debug($this->Test->find('count', [

            'conditions' => [
                //'Test.node_version' => ['3.0.0', '3.0.1'],
                'Test.node_version' => '3.0.0'
            ],
            'order' => [
                'node_start_time' => 'asc'
            ],
            'limit' => 50,
            'offset' => 0,
            'order' => [
                'Test.node_name' => 'asc',
                'node_start_time' => 'desc'
            ],
            //'group' => [
            //    'Test.node_name',
            //    'node_start_time'
            //]
        ]));


        debug('this is index');
    }

}
