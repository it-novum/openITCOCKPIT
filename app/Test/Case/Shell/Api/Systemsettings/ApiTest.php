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

use itnovum\openITCOCKPIT\ApiShell\OptionParser;
use itnovum\openITCOCKPIT\ApiShell\Systemsettings\Api;

//run with: oitc test app Shell/Api/Systemsettings/Api

class ApiTest extends CakeTestCase {
    public function testInstanceOfApi() {
        $api = new Api($this->getShell(), 'Systemsettings');
        $this->assertInstanceOf('\itnovum\openITCOCKPIT\ApiShell\Systemsettings\Api', $api);
    }

    public function testIfRecordExists() {
        $api = new Api($this->getRealShell(), 'Systemsettings');
        $api->setOptionsFromOptionParser($this->getOptionParser(
            null,
            'ARCHIVE.AGE.HOSTCHECKS',
            []
        ));

        $this->assertTrue($api->exists());
    }

    public function testAddAndUpdateAndDeleteRecord() {
        $api = new Api($this->getRealShell(), 'Systemsettings');
        $api->setOptionsFromOptionParser($this->getOptionParser(
            'add',
            'FOOBAR',
            [
                'value for foobar',
                'description for foobar',
                'section of foobar',
            ]
        ));

        $api->setOptionsFromOptionParser($this->getOptionParser(
            'add',
            'FOOBAR',
            [
                'new value for foobar',
                'new description for foobar',
                'new section of foobar',
            ]
        ));

        $this->assertTrue($api->update());

        $record = $api->getRecordByKey('FOOBAR');
        $fildsToRemove = ['id', 'created', 'modified'];
        foreach ($fildsToRemove as $field) {
            unset($record['Systemsettings'][$field]);
        }

        $assertedRecord = [
            'Systemsettings' => [
                'key'     => 'FOOBAR',
                'value'   => 'new value for foobar',
                'info'    => 'new description for foobar',
                'section' => 'new section of foobar',
            ],
        ];

        $this->assertEquals($assertedRecord, $record);

        $api->setOptionsFromOptionParser($this->getOptionParser(
            'delete',
            'FOOBAR',
            []
        ));

        $this->assertTrue($api->delete());
    }

    private function getRealShell() {
        $shell = new Shell();
        $shell->loadModel('Systemsettings');

        return $shell;
    }

    public function getShell() {
        $shell = $this->getMockBuilder('Shell')
            ->disableOriginalConstructor()
            ->getMock();
        $shell->expects($this->any())->method('loadModel')->will($this->returnValue(true));

        return $shell;
    }

    public function getOptionParser($action, $data, $args) {
        $parameters = [
            'plugin' => '',
            'model'  => 'systemsettings',
            'action' => $action,
            'data'   => $data,
        ];
        $optionParser = new OptionParser();
        $optionParser->parse($parameters, $args);

        return $optionParser;
    }
}