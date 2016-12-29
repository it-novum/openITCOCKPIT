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


class HostMacroReplacerTest extends \PHPUnit_Framework_TestCase
{

    //run test: oitc test app Core/HostMacroReplacer

    public function testInstance()
    {
        $hostMacroReplacer = new HostMacroReplacer([]);
        $this->assertInstanceOf('\itnovum\openITCOCKPIT\Core\HostMacroReplacer', $hostMacroReplacer);
    }

    public function testReplaceHostId()
    {
        $host = $this->getHost();
        $hostMacroReplacer = new HostMacroReplacer($host);
        $assert = 1;
        $result = $hostMacroReplacer->replaceBasicMacros('$HOSTID$');
        $this->assertEquals($assert, $result);
    }

    public function testReplaceHostname()
    {
        $host = $this->getHost();
        $hostMacroReplacer = new HostMacroReplacer($host);
        $assert = 'c8e3785a-9872-4feb-b1f2-78b50af2fe90';
        $result = $hostMacroReplacer->replaceBasicMacros('$HOSTNAME$');
        $this->assertEquals($assert, $result);
    }

    public function testReplaceHostdisplayname()
    {
        $host = $this->getHost();
        $hostMacroReplacer = new HostMacroReplacer($host);
        $assert = 'my super duper test host';
        $result = $hostMacroReplacer->replaceBasicMacros('$HOSTDISPLAYNAME$');
        $this->assertEquals($assert, $result);
    }

    public function testReplaceHostaddress()
    {
        $host = $this->getHost();
        $hostMacroReplacer = new HostMacroReplacer($host);
        $assert = '10.10.10.10';
        $result = $hostMacroReplacer->replaceBasicMacros('$HOSTADDRESS$');
        $this->assertEquals($assert, $result);
    }

    public function testReplaceMultiMacros()
    {
        $host = $this->getHost();
        $hostMacroReplacer = new HostMacroReplacer($host);
        $stringToReplace = 'Hello my name is $HOSTDISPLAYNAME$ i am reachable at $HOSTADDRESS$ and like my uuid $HOSTNAME$';
        $assert = 'Hello my name is my super duper test host i am reachable at 10.10.10.10 and like my uuid c8e3785a-9872-4feb-b1f2-78b50af2fe90';
        $result = $hostMacroReplacer->replaceBasicMacros($stringToReplace);
        $this->assertEquals($assert, $result);
    }

    public function testReplaceHoststateid()
    {
        $host = $this->getHost();
        $hoststatus = $this->getHoststatus();
        $hostMacroReplacer = new HostMacroReplacer($host, $hoststatus);
        $assert = 0;
        $result = $hostMacroReplacer->replaceStatusMacros('$HOSTSTATEID$');
        $this->assertEquals($assert, $result);
    }

    public function testReplaceLasthoststateid()
    {
        $host = $this->getHost();
        $hoststatus = $this->getHoststatus();
        $hostMacroReplacer = new HostMacroReplacer($host, $hoststatus);
        $assert = 1;
        $result = $hostMacroReplacer->replaceStatusMacros('$LASTHOSTSTATEID$');
        $this->assertEquals($assert, $result);
    }

    public function testReplaceHostoutput()
    {
        $host = $this->getHost();
        $hoststatus = $this->getHoststatus();
        $hostMacroReplacer = new HostMacroReplacer($host, $hoststatus);
        $assert = 'OK - 127.0.0.1: rta 0.033ms, lost 0%';
        $result = $hostMacroReplacer->replaceStatusMacros('$HOSTOUTPUT$');
        $this->assertEquals($assert, $result);
    }

    public function testCombineMacros()
    {
        $host = $this->getHost();
        $hoststatus = $this->getHoststatus();
        $hostMacroReplacer = new HostMacroReplacer($host, $hoststatus);
        $stringToReplace = 'Hello my name is $HOSTDISPLAYNAME$ i am reachable at $HOSTADDRESS$ my state is $HOSTSTATEID$ and my last state was $LASTHOSTSTATEID$ my output is $HOSTOUTPUT$';
        $assert = 'Hello my name is my super duper test host i am reachable at 10.10.10.10 my state is 0 and my last state was 1 my output is OK - 127.0.0.1: rta 0.033ms, lost 0%';
        $result = $hostMacroReplacer->replaceAllMacros($stringToReplace);
        $this->assertEquals($assert, $result);
    }

    public function testPrintMacroIfNotFoundInHostOrHoststatus()
    {
        $host = [];
        $hoststatus = [];
        $hostMacroReplacer = new HostMacroReplacer($host, $hoststatus);
        $stringToReplace = '$HOSTNAME$ $HOSTDISPLAYNAME$ $HOSTADDRESS$ $HOSTSTATEID$ $LASTHOSTSTATEID$ $HOSTOUTPUT$';
        $assert = '$HOSTNAME$ $HOSTDISPLAYNAME$ $HOSTADDRESS$ $HOSTSTATEID$ $LASTHOSTSTATEID$ $HOSTOUTPUT$';
        $result = $hostMacroReplacer->replaceAllMacros($stringToReplace);
        $this->assertEquals($assert, $result);
    }

    private function getHost()
    {
        $host = [
            'Host' => [
                'id'      => 1,
                'uuid'    => 'c8e3785a-9872-4feb-b1f2-78b50af2fe90',
                'name'    => 'my super duper test host',
                'address' => '10.10.10.10',
            ],
        ];

        return $host;
    }

    private function getHoststatus()
    {
        $hoststatus = [
            'Hoststatus' => [
                'current_state'   => 0,
                'last_hard_state' => 1,
                'output'          => 'OK - 127.0.0.1: rta 0.033ms, lost 0%',
            ],
        ];

        return $hoststatus;
    }

}
