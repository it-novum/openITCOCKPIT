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

//run test: oitc test app Core/CustomVariableMerger

use itnovum\openITCOCKPIT\Core\ValueObjects\CustomVariable;

class CustomVariableMergerTest extends \CakeTestCase {
    public function testInstance() {
        $CustomVariableMerger = new CustomVariableMerger([], []);
        $this->assertInstanceOf('\itnovum\openITCOCKPIT\Core\CustomVariableMerger', $CustomVariableMerger);
    }

    public function testMergeCustomVariablesOfHosttemplateAndHost() {
        $CustomVariableMerger = new CustomVariableMerger($this->getHostWithDifferentMacros(), $this->getHosttemplateCustomVariables());

        $hosttemplateVariable1 = new CustomVariable('EINSTEMPLATE', '1', 0, OBJECT_HOSTTEMPLATE);
        $hosttemplateVariable2 = new CustomVariable('ZWEITEMPLATE', '2', 0, OBJECT_HOSTTEMPLATE);
        $hosttemplateVariable3 = new CustomVariable('DREITEMPLATE', '3', 0, OBJECT_HOSTTEMPLATE);

        $hostVariable1 = new CustomVariable('FOO', 'BAR', 0, OBJECT_HOST);

        $AssertedRepository = new CustomVariablesRepository();
        $AssertedRepository->addCustomVariable($hostVariable1);
        $AssertedRepository->addCustomVariable($hosttemplateVariable1);
        $AssertedRepository->addCustomVariable($hosttemplateVariable2);
        $AssertedRepository->addCustomVariable($hosttemplateVariable3);

        $this->assertEquals($AssertedRepository, $CustomVariableMerger->getCustomVariablesMergedAsRepository());
    }

    public function testMergeCustomVariablesOfHosttemplateAndHost2() {
        $CustomVariableMerger = new CustomVariableMerger($this->getHostWithDifferentMacrosValues(), $this->getHosttemplateCustomVariables());

        $hosttemplateVariable3 = new CustomVariable('DREITEMPLATE', '3', 0, OBJECT_HOSTTEMPLATE);

        $hostVariable1 = new CustomVariable('FOO', 'BAR', 0, OBJECT_HOST);
        $hostVariable2 = new CustomVariable('EINSTEMPLATE', '8', 0, OBJECT_HOST);
        $hostVariable3 = new CustomVariable('ZWEITEMPLATE', '9', 0, OBJECT_HOST);

        $AssertedRepository = new CustomVariablesRepository();
        $AssertedRepository->addCustomVariable($hostVariable1);
        $AssertedRepository->addCustomVariable($hostVariable2);
        $AssertedRepository->addCustomVariable($hostVariable3);
        $AssertedRepository->addCustomVariable($hosttemplateVariable3);

        $this->assertEquals($AssertedRepository, $CustomVariableMerger->getCustomVariablesMergedAsRepository());
    }

    public function testMergeCustomVariablesOfHostWithEmptyHosttemplate() {
        $CustomVariableMerger = new CustomVariableMerger($this->getHostWithDifferentMacrosValues(), $this->getHosttemplateWhitoutCustomVariables());

        $hostVariable1 = new CustomVariable('FOO', 'BAR', 0, OBJECT_HOST);
        $hostVariable2 = new CustomVariable('EINSTEMPLATE', '8', 0, OBJECT_HOST);
        $hostVariable3 = new CustomVariable('ZWEITEMPLATE', '9', 0, OBJECT_HOST);

        $AssertedRepository = new CustomVariablesRepository();
        $AssertedRepository->addCustomVariable($hostVariable1);
        $AssertedRepository->addCustomVariable($hostVariable2);
        $AssertedRepository->addCustomVariable($hostVariable3);

        $this->assertEquals($AssertedRepository, $CustomVariableMerger->getCustomVariablesMergedAsRepository());
    }


    private function getHosttemplateWhitoutCustomVariables() {
        return [];
    }

    private function getHosttemplateCustomVariables() {
        return [
            (int)0 => [
                'name'          => 'EINSTEMPLATE',
                'value'         => '1',
                'objecttype_id' => OBJECT_HOSTTEMPLATE,
            ],
            (int)1 => [
                'name'          => 'ZWEITEMPLATE',
                'value'         => '2',
                'objecttype_id' => OBJECT_HOSTTEMPLATE,
            ],
            (int)2 => [
                'name'          => 'DREITEMPLATE',
                'value'         => '3',
                'objecttype_id' => OBJECT_HOSTTEMPLATE,
            ],
        ];
    }

    private function getHostWithDifferentMacros() {
        return [
            (int)2 => [
                'name'          => 'FOO',
                'value'         => 'BAR',
                'objecttype_id' => OBJECT_HOST,
            ],
        ];
    }

    private function getHostWithDifferentMacrosValues() {
        return [
            (int)0 => [
                'name'          => 'FOO',
                'value'         => 'BAR',
                'objecttype_id' => OBJECT_HOST,
            ],
            (int)1 => [
                'name'          => 'EINSTEMPLATE',
                'value'         => '8',
                'objecttype_id' => OBJECT_HOST,
            ],
            (int)2 => [
                'name'          => 'ZWEITEMPLATE',
                'value'         => '9',
                'objecttype_id' => OBJECT_HOST,
            ],
        ];
    }

}
