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

//run test: oitc test app Core/CustomVariableDiffer

use itnovum\openITCOCKPIT\Core\ValueObjects\CustomVariable;

class CustomVariableDifferTest extends \CakeTestCase {
    public function testInstance() {
        $CustomVariableDiffer = new CustomVariableDiffer([], []);
        $this->assertInstanceOf('\itnovum\openITCOCKPIT\Core\CustomVariableDiffer', $CustomVariableDiffer);
    }

    public function testConvertCustomVariablesToRepository() {
        $CustomVariableDiffer = new CustomVariableDiffer([], []);

        $CustomVariablesRepository = new CustomVariablesRepository();
        $CustomVariablesRepository->addCustomVariable(new CustomVariable('EINSTEMPLATE', 1));
        $CustomVariablesRepository->addCustomVariable(new CustomVariable('ZWEITEMPLATE', 2));
        $CustomVariablesRepository->addCustomVariable(new CustomVariable('DREITEMPLATE', 3));


        $result = $CustomVariableDiffer->convertCustomVariablesToRepository($this->getHosttemplateCustomVariables());
        $this->assertEquals($CustomVariablesRepository, $result);
    }


    public function testGetCustomVariablesToSaveAsRepositoryByDifferentKeys() {
        $hostMacros = $this->getHostWithDifferentMacros();
        $hosttemplateMacros = $this->getHosttemplateCustomVariables();

        $CustomVariableDiffer = new CustomVariableDiffer($hostMacros, $hosttemplateMacros);

        $CustomVariablesRepository = new CustomVariablesRepository();
        $CustomVariablesRepository->addCustomVariable(new CustomVariable('FOO', 'BAR'));

        $this->assertEquals($CustomVariablesRepository, $CustomVariableDiffer->getCustomVariablesToSaveAsRepository());
    }

    public function testGetCustomVariablesToSaveAsRepositoryByDifferentValues() {
        $hostMacros = $this->getHostWithDifferentMacrosValues();
        $hosttemplateMacros = $this->getHosttemplateCustomVariables();

        $CustomVariableDiffer = new CustomVariableDiffer($hostMacros, $hosttemplateMacros);

        $CustomVariablesRepository = new CustomVariablesRepository();
        $CustomVariablesRepository->addCustomVariable(new CustomVariable('DREITEMPLATE', '5'));

        $this->assertEquals($CustomVariablesRepository, $CustomVariableDiffer->getCustomVariablesToSaveAsRepository());
    }


    public function testGetCustomVariablesToSaveAsRepositoryWithoutDifferences() {
        $hostMacros = $this->getHostWithoutDifferences();
        $hosttemplateMacros = $this->getHosttemplateCustomVariables();

        $CustomVariableDiffer = new CustomVariableDiffer($hostMacros, $hosttemplateMacros);

        $CustomVariablesRepository = new CustomVariablesRepository();

        //Both repository should be empty
        $this->assertEquals($CustomVariablesRepository, $CustomVariableDiffer->getCustomVariablesToSaveAsRepository());
    }

    public function testGetCustomVariablesToSaveAsRepositoryFromHosttemplateWithoutVariables() {
        $hostMacros = $this->getHostWithDifferentMacros();
        $hosttemplateMacros = $this->getHosttemplateWhitoutCustomVariables();

        $CustomVariableDiffer = new CustomVariableDiffer($hostMacros, $hosttemplateMacros);

        $CustomVariablesRepository = new CustomVariablesRepository();
        $CustomVariablesRepository->addCustomVariable(new CustomVariable('EINSTEMPLATE', '1'));
        $CustomVariablesRepository->addCustomVariable(new CustomVariable('ZWEITEMPLATE', '2'));
        $CustomVariablesRepository->addCustomVariable(new CustomVariable('FOO', 'BAR'));

        $this->assertEquals($CustomVariablesRepository, $CustomVariableDiffer->getCustomVariablesToSaveAsRepository());
    }

    private function getHosttemplateWhitoutCustomVariables() {
        return [];
    }

    private function getHosttemplateCustomVariables() {
        return [
            (int)0 => [
                'name'  => 'EINSTEMPLATE',
                'value' => '1',
            ],
            (int)1 => [
                'name'  => 'ZWEITEMPLATE',
                'value' => '2',
            ],
            (int)2 => [
                'name'  => 'DREITEMPLATE',
                'value' => '3',
            ],
        ];
    }

    private function getHostWithDifferentMacros() {
        return [
            (int)0 => [
                'name'  => 'EINSTEMPLATE',
                'value' => '1',
            ],
            (int)1 => [
                'name'  => 'ZWEITEMPLATE',
                'value' => '2',
            ],
            (int)2 => [
                'name'  => 'FOO',
                'value' => 'BAR',
            ],
        ];
    }

    private function getHostWithDifferentMacrosValues() {
        return [
            (int)0 => [
                'name'  => 'EINSTEMPLATE',
                'value' => '1',
            ],
            (int)1 => [
                'name'  => 'ZWEITEMPLATE',
                'value' => '2',
            ],
            (int)2 => [
                'name'  => 'DREITEMPLATE',
                'value' => '5',
            ],
        ];
    }

    private function getHostWithoutDifferences() {
        return [
            (int)0 => [
                'name'  => 'EINSTEMPLATE',
                'value' => '1',
            ],
            (int)1 => [
                'name'  => 'ZWEITEMPLATE',
                'value' => '2',
            ],
            (int)2 => [
                'name'  => 'DREITEMPLATE',
                'value' => '3',
            ],
        ];
    }

}
