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


class UserDefinedMacroReplacerTest extends \PHPUnit_Framework_TestCase
{

    //run test: oitc test app Core/UserDefinedMacroReplacer

    public function testInstance()
    {
        $userDefinedMacroReplacer = new UserDefinedMacroReplacer([]);
        $this->assertInstanceOf('\itnovum\openITCOCKPIT\Core\UserDefinedMacroReplacer', $userDefinedMacroReplacer);
    }

    public function testReplaceSimpleMacro()
    {
        $macros = $this->getMacros();
        $userDefinedMacroReplacer = new UserDefinedMacroReplacer($macros);
        $assert = '/opt/openitc/nagios/libexec';
        $result = $userDefinedMacroReplacer->replaceMacros('$USER1$');
        $this->assertEquals($assert, $result);
    }

    public function testReplaceMultiMacro()
    {
        $macros = $this->getMacros();
        $userDefinedMacroReplacer = new UserDefinedMacroReplacer($macros);
        $assert = '/opt/openitc/nagios/libexec public security';
        $result = $userDefinedMacroReplacer->replaceMacros('$USER1$ $USER2$ $USER3$');
        $this->assertEquals($assert, $result);
    }

    public function testReplaceMacroInCommand()
    {
        $macros = $this->getMacros();
        $userDefinedMacroReplacer = new UserDefinedMacroReplacer($macros);
        $assert = '/opt/openitc/nagios/libexec/check_snmp -H 127.0.0.1 -c public';
        $result = $userDefinedMacroReplacer->replaceMacros('$USER1$/check_snmp -H 127.0.0.1 -c $USER2$');
        $this->assertEquals($assert, $result);
    }

    public function testPrintMacroIfNotFoundInMacrosArray()
    {
        $macros = $this->getMacros();
        $userDefinedMacroReplacer = new UserDefinedMacroReplacer($macros);
        $assert = '/opt/openitc/nagios/libexec $USER5$ $USER100$';
        $result = $userDefinedMacroReplacer->replaceMacros('$USER1$ $USER5$ $USER100$');
        $this->assertEquals($assert, $result);
    }

    private function getMacros()
    {
        $macros = [
            [
                'Macro' => [
                    'password'    => 0,
                    'id'          => '1',
                    'name'        => '$USER1$',
                    'value'       => '/opt/openitc/nagios/libexec',
                    'description' => 'Path to monitoring plugins',
                    'created'     => '2015-01-05 15:17:23',
                    'modified'    => '2016-10-25 10:50:39',
                ],
            ],

            [
                'Macro' => [
                    'password'    => 1,
                    'id'          => '3',
                    'name'        => '$USER2$',
                    'value'       => 'public',
                    'description' => 'snmp cummunity',
                    'created'     => '2016-10-25 10:50:39',
                    'modified'    => '2016-10-25 10:50:39',
                ],
            ],

            [
                'Macro' => [
                    'password'    => 1,
                    'id'          => '4',
                    'name'        => '$USER3$',
                    'value'       => 'security',
                    'description' => 'Monitoring Password',
                    'created'     => '2016-10-25 10:50:39',
                    'modified'    => '2016-10-25 10:50:39',
                ],
            ],
        ];

        return $macros;
    }


}
