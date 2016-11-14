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



namespace itnovum\openITCOCKPIT\Core;

//run test: oitc test app CakePHP/RequestParams
\App::uses('AppController', 'Controller');

class RequestParamsTest extends \ControllerTestCase {

    public function testEqualParams() {
        $this->testAction('/hosts/index/Filter.Hoststatus.current_state[0]:1/Filter.Hoststatus.current_state[2]:1/q:1');
        $expectedResult = [
            'Filter' => [
                'Hoststatus' => [
                    'current_state' => [0=>'1',2=>'1']
                ]
            ]
        ];
        $this->assertEquals($expectedResult, $this->vars['myNamedFilters']);
    }

    public function testNotEqualParams() {
        $this->testAction('/hosts/index/Filter.Hoststatus.current_state[0]:1/Filter.Hoststatus.current_state[2]:1/q:1');
        $expectedResult = [
            'Filter' => [
                'Hoststatus' => [
                    'current_state' => [0=>'1',1=>'1']
                ]
            ]
        ];
        $this->assertNotEquals($expectedResult, $this->vars['myNamedFilters']);
    }

}
