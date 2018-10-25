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


class ServiceMacroReplacerTest extends \PHPUnit_Framework_TestCase {

    //run test: oitc test app Core/ServiceMacroReplacer

    public function testInstance() {
        $serviceMacroReplacer = new ServiceMacroReplacer([]);
        $this->assertInstanceOf('\itnovum\openITCOCKPIT\Core\ServiceMacroReplacer', $serviceMacroReplacer);
    }

    public function testReplaceServiceId() {
        $service = $this->getService();
        $serviceMacroReplacer = new ServiceMacroReplacer($service);
        $assert = 1;
        $result = $serviceMacroReplacer->replaceBasicMacros('$SERVICEID$');
        $this->assertEquals($assert, $result);
    }

    public function testReplaceServicedesc() {
        $service = $this->getService();
        $serviceMacroReplacer = new ServiceMacroReplacer($service);
        $assert = 'c8e3785a-9872-4feb-b1f2-78b50af2fe90';
        $result = $serviceMacroReplacer->replaceBasicMacros('$SERVICEDESC$');
        $this->assertEquals($assert, $result);
    }

    public function testReplaceServicedisplaynameFromService() {
        $service = $this->getService();
        $serviceMacroReplacer = new ServiceMacroReplacer($service);
        $assert = 'Ping';
        $result = $serviceMacroReplacer->replaceBasicMacros('$SERVICEDISPLAYNAME$');
        $this->assertEquals($assert, $result);
    }

    public function testReplaceServicedisplaynameFromServicetemplate() {
        $service = $this->getServiceWithServicetemplate();
        $serviceMacroReplacer = new ServiceMacroReplacer($service);
        $assert = 'Foobar';
        $result = $serviceMacroReplacer->replaceBasicMacros('$SERVICEDISPLAYNAME$');
        $this->assertEquals($assert, $result);
    }

    public function testReplaceMultiMacros() {
        $service = $this->getServiceWithServicetemplate();
        $serviceMacroReplacer = new ServiceMacroReplacer($service);
        $stringToReplace = 'Hello my name is $SERVICEDISPLAYNAME$ with the uuid $SERVICEDESC$ and the id $SERVICEID$';
        $assert = 'Hello my name is Foobar with the uuid c8e3785a-9872-4feb-b1f2-78b50af2fe90 and the id 1';
        $result = $serviceMacroReplacer->replaceBasicMacros($stringToReplace);
        $this->assertEquals($assert, $result);
    }

    public function testReplaceServicestateid() {
        $service = $this->getService();
        $servicestatus = $this->getServicestatus();
        $serviceMacroReplacer = new ServiceMacroReplacer($service, $servicestatus);
        $assert = 0;
        $result = $serviceMacroReplacer->replaceStatusMacros('$SERVICESTATEID$');
        $this->assertEquals($assert, $result);
    }

    public function testReplaceLastservicestateid() {
        $service = $this->getService();
        $servicestatus = $this->getServicestatus();
        $serviceMacroReplacer = new ServiceMacroReplacer($service, $servicestatus);
        $assert = 1;
        $result = $serviceMacroReplacer->replaceStatusMacros('$LASTSERVICESTATEID$');
        $this->assertEquals($assert, $result);
    }

    public function testReplaceServiceoutput() {
        $service = $this->getService();
        $servicestatus = $this->getServicestatus();
        $serviceMacroReplacer = new ServiceMacroReplacer($service, $servicestatus);
        $assert = 'PING OK - Packet loss = 0%, RTA = 0.04 ms';
        $result = $serviceMacroReplacer->replaceStatusMacros('$SERVICEOUTPUT$');
        $this->assertEquals($assert, $result);
    }

    public function testCombineMacros() {
        $service = $this->getServiceWithServicetemplate();
        $servicestatus = $this->getServicestatus();
        $serviceMacroReplacer = new ServiceMacroReplacer($service, $servicestatus);
        $stringToReplace = 'Hello my name is $SERVICEDISPLAYNAME$ my uuid is $SERVICEDESC$ my state is $SERVICESTATEID$ and my id $SERVICEID$';
        $assert = 'Hello my name is Foobar my uuid is c8e3785a-9872-4feb-b1f2-78b50af2fe90 my state is 0 and my id 1';
        $result = $serviceMacroReplacer->replaceAllMacros($stringToReplace);
        $this->assertEquals($assert, $result);
    }

    public function testPrintMacroIfNotFoundInServiceOrServicestatus() {
        $service = [];
        $servicestatus = [];
        $serviceMacroReplacer = new ServiceMacroReplacer($service, $servicestatus);
        $stringToReplace = '$SERVICEDESC$ $SERVICEDISPLAYNAME$ $SERVICESTATEID$ $HOSTSTATEID$ $LASTSERVICESTATEID$ $SERVICEOUTPUT$ $SERVICEID$';
        $assert = '$SERVICEDESC$ $SERVICEDISPLAYNAME$ $SERVICESTATEID$ $HOSTSTATEID$ $LASTSERVICESTATEID$ $SERVICEOUTPUT$ $SERVICEID$';
        $result = $serviceMacroReplacer->replaceAllMacros($stringToReplace);
        $this->assertEquals($assert, $result);
    }

    private function getService() {
        $service = [
            'Service' => [
                'id'   => 1,
                'uuid' => 'c8e3785a-9872-4feb-b1f2-78b50af2fe90',
                'name' => 'Ping',
            ],
        ];

        return $service;
    }

    private function getServiceWithServicetemplate() {
        $service = [
            'Service'         => [
                'id'                 => 1,
                'uuid'               => 'c8e3785a-9872-4feb-b1f2-78b50af2fe90',
                'name'               => null,
                'servicetemplate_id' => 5,
            ],
            'Servicetemplate' => [
                'id'   => 5,
                'name' => 'Foobar',
            ],
        ];

        return $service;
    }

    private function getServicestatus() {
        $servicestatus = [
            'Servicestatus' => [
                'current_state'   => 0,
                'last_hard_state' => 1,
                'output'          => 'PING OK - Packet loss = 0%, RTA = 0.04 ms',
            ],
        ];

        return $servicestatus;
    }

}
