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

//run test: oitc test app Core/CustomVariableRepository

use itnovum\openITCOCKPIT\Core\ValueObjects\CustomVariable;

class CustomVariableRepositoryTest extends \CakeTestCase
{
    public function testInstance()
    {
        $CustomVariableRepository = new CustomVariablesRepository();
        $this->assertInstanceOf('\itnovum\openITCOCKPIT\Core\CustomVariablesRepository', $CustomVariableRepository);
    }

    public function testAddAndGet()
    {
        $CustomVariable1 = new CustomVariable('FOO', 'BAR', 1, 2048);
        $CustomVariable2 = new CustomVariable('BAR', 'FOO', 1, 2048);
        $CustomVariable3 = new CustomVariable('FOOBAR', 'BARFOO', 1, 2048);

        $CustomVarialeRepository = new CustomVariablesRepository();
        $CustomVarialeRepository->addCustomVariable($CustomVariable1);
        $CustomVarialeRepository->addCustomVariable($CustomVariable2);
        $CustomVarialeRepository->addCustomVariable($CustomVariable3);

        $assert = [
            $CustomVariable1,
            $CustomVariable2,
            $CustomVariable3,
        ];

        $this->assertEquals($assert, $CustomVarialeRepository->getAllCustomVariables());
    }

    public function testAddAndGetCustomVariableByName()
    {
        $CustomVariable1 = new CustomVariable('FOO', 'BAR', 1, 2048);
        $CustomVariable2 = new CustomVariable('BAR', 'FOO', 1, 2048);
        $CustomVariable3 = new CustomVariable('FOOBAR', 'BARFOO', 1, 2048);

        $CustomVarialeRepository = new CustomVariablesRepository();
        $CustomVarialeRepository->addCustomVariable($CustomVariable1);
        $CustomVarialeRepository->addCustomVariable($CustomVariable2);
        $CustomVarialeRepository->addCustomVariable($CustomVariable3);

        $assert = $CustomVariable2;

        $this->assertEquals($assert, $CustomVarialeRepository->getByVariableName('BAR'));
    }

    public function testAddAndGetSize()
    {
        $CustomVariable1 = new CustomVariable('FOO', 'BAR', 1, 2048);
        $CustomVariable2 = new CustomVariable('BAR', 'FOO', 1, 2048);
        $CustomVariable3 = new CustomVariable('FOOBAR', 'BARFOO', 1, 2048);

        $CustomVarialeRepository = new CustomVariablesRepository();
        $CustomVarialeRepository->addCustomVariable($CustomVariable1);
        $CustomVarialeRepository->addCustomVariable($CustomVariable2);
        $CustomVarialeRepository->addCustomVariable($CustomVariable3);

        $this->assertEquals(3, $CustomVarialeRepository->getSize());
    }

    public function getAllCustomVariablesAsArray()
    {
        $CustomVariable1 = new CustomVariable('FOO', 'BAR', 1, 2048);
        $CustomVariable2 = new CustomVariable('BAR', 'FOO', 1, 2048);
        $CustomVariable3 = new CustomVariable('FOOBAR', 'BARFOO', 1, 2048);

        $CustomVariableRepository = new CustomVariablesRepository();
        $CustomVariableRepository->addCustomVariable($CustomVariable1);
        $CustomVariableRepository->addCustomVariable($CustomVariable2);
        $CustomVariableRepository->addCustomVariable($CustomVariable3);

        $assert = [
            [
                'name'          => 'FOO',
                'value'         => 'BAR',
                'id'            => 1,
                'objecttype_id' => 2048,
            ],
            [
                'name'          => 'BAR',
                'value'         => 'FOO',
                'id'            => 1,
                'objecttype_id' => 2048,
            ],
            [
                'name'          => 'FOOBAR',
                'value'         => 'BARFOO',
                'id'            => 1,
                'objecttype_id' => 2048,
            ],
        ];

        $this->assertEquals($assert, $CustomVariableRepository->getAllCustomVariablesAsArray());
    }

    public function testDeleteVariableByBVarName()
    {
        $CustomVariable1 = new CustomVariable('FOO', 'BAR', 1, 2048);
        $CustomVariable2 = new CustomVariable('BAR', 'FOO', 1, 2048);
        $CustomVariable3 = new CustomVariable('FOOBAR', 'BARFOO', 1, 2048);

        $CustomVariableRepository = new CustomVariablesRepository();
        $CustomVariableRepository->addCustomVariable($CustomVariable1);
        $CustomVariableRepository->addCustomVariable($CustomVariable2);
        $CustomVariableRepository->addCustomVariable($CustomVariable3);

        $assert = [
            $CustomVariable1,
            $CustomVariable3,
        ];

        $CustomVariableRepository->deleteByVariableName($CustomVariable2->getName());
        $this->assertEquals($assert, $CustomVariableRepository->getAllCustomVariables());
    }
}
