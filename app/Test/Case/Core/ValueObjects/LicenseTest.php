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

namespace itnovum\openITCOCKPIT\Core\ValueObjects;

class LicenseTest extends \PHPUnit_Framework_TestCase
{
    //run test: oitc test app Core/ValueObjects/License

    public function testInstance()
    {
        $License = new License([]);
        $this->assertInstanceOf('\itnovum\openITCOCKPIT\Core\ValueObjects\License', $License);
    }

    public function testGetLicense()
    {
        $License = new License($this->getLicense());
        $this->assertEquals('1234-5678-901234-567890', $License->getLicense());
    }

    public function testGetLicenseFromEmptyArray()
    {
        $License = new License([]);
        $this->assertEquals('', $License->getLicense());
    }

    /**
     * Retunr a CakePHP's find like license result
     * @return array
     */
    private function getLicense()
    {
        return ['Register' => ['license' => '1234-5678-901234-567890']];
    }
}
