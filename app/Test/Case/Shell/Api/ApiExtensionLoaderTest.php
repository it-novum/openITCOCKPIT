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

use itnovum\openITCOCKPIT\ApiShell\ApiExtensionLoader;

//run test with: oitc test app Shell/Api/ApiExtensionLoader

class ApiExtensionLoaderTest extends CakeTestCase
{
    public function testInstance(){
        $apiExtensionLoader = new ApiExtensionLoader(null, $this->getOptionParser(''));
        $this->assertInstanceOf('\itnovum\openITCOCKPIT\ApiShell\ApiExtensionLoader', $apiExtensionLoader);
    }

    public function testIsAvailable1(){
        $apiExtensionLoader = new ApiExtensionLoader(null, $this->getOptionParser('Systemsettings'));
        $this->assertTrue($apiExtensionLoader->isAvailable());
    }

    public function testIsAvailable2(){
        $apiExtensionLoader = new ApiExtensionLoader(null, $this->getOptionParser('SomeNonExistingApiExtension'));
        $this->assertFalse($apiExtensionLoader->isAvailable());
    }

    public function testGetApiForSystemsettings(){
        $apiExtensionLoader = new ApiExtensionLoader($this->getShell(), $this->getOptionParser('Systemsettings'), '');
        $api = $apiExtensionLoader->getApi();
        $this->assertInstanceOf('\itnovum\openITCOCKPIT\ApiShell\Systemsettings\Api', $api);
    }

    private function getShell(){
        $shell = $this->getMockBuilder('Shell')
            ->disableOriginalConstructor()
            ->getMock();
        $shell->expects($this->any())->method('loadModel')->will($this->returnValue(true));
        return $shell;
    }

    private function getOptionParser($modelName){
        $check = $this->getMockBuilder('itnovum\openITCOCKPIT\ApiShell\OptionParser')
            ->disableOriginalConstructor()
            ->getMock();
        $check->expects($this->any())->method('getModel')->will($this->returnValue($modelName));
        $check->expects($this->any())->method('getPlugin')->will($this->returnValue(''));

        return $check;
    }
}