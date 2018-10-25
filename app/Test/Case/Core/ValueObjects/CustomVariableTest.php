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

//run test: oitc test app Core/ValueObjects/CustomVariable

use itnovum\openITCOCKPIT\Core\ValueObjects\CustomVariable;

class CustomVariableTest extends \CakeTestCase {
    public function testInstance() {
        $CustomVariable = new CustomVariable('key', 'value');
        $this->assertInstanceOf('\itnovum\openITCOCKPIT\Core\ValueObjects\CustomVariable', $CustomVariable);
    }

    public function testGetValuesBack() {
        $CustomVariable = new CustomVariable('key', 'value', 10, 2048);
        $this->assertEquals('key', $CustomVariable->getName());
        $this->assertEquals('value', $CustomVariable->getValue());
        $this->assertEquals(10, $CustomVariable->getId());
        $this->assertEquals(2048, $CustomVariable->getObjecttypeId());
    }

    public function testGetCustomVariableAsArrayWithId() {
        $CustomVariable = new CustomVariable('key', 'value', 10);
        $assert = [
            'name'  => 'key',
            'value' => 'value',
            'id'    => 10,
        ];

        $this->assertEquals($assert, $CustomVariable->asArray());
    }

    public function testGetCustomVariableAsArrayWithObjecttypeId() {
        $CustomVariable = new CustomVariable('key', 'value', 0, 2048);
        $assert = [
            'name'          => 'key',
            'value'         => 'value',
            'objecttype_id' => 2048,
        ];

        $this->assertEquals($assert, $CustomVariable->asArray());
    }

    public function testGetCustomVariableAsArrayWithIdAndObjecttypeId() {
        $CustomVariable = new CustomVariable('key', 'value', 10, 2048);
        $assert = [
            'name'          => 'key',
            'value'         => 'value',
            'id'            => 10,
            'objecttype_id' => 2048,
        ];

        $this->assertEquals($assert, $CustomVariable->asArray());
    }
}
