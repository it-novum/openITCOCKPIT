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


class PackagemanagerRequestBuilderTest extends \PHPUnit_Framework_TestCase {
    //run test: oitc test app Core/PackagemanagerRequestBuilder

    public function testInstance() {
        $packagemanagerRequestBuilder = new PackagemanagerRequestBuilder('development');
        $this->assertInstanceOf('\itnovum\openITCOCKPIT\Core\PackagemanagerRequestBuilder', $packagemanagerRequestBuilder);
    }

    public function testGetInternalAddressWithoutLicense() {
        $packagemanagerRequestBuilder = new PackagemanagerRequestBuilder('development');
        $this->assertEquals('http://172.16.2.87/modules/fetch/.json', $packagemanagerRequestBuilder->getUrl());
    }

    public function testGetExternalAddressWithoutLicense() {
        $packagemanagerRequestBuilder = new PackagemanagerRequestBuilder('production');
        $this->assertEquals('https://packagemanager.it-novum.com/modules/fetch/.json', $packagemanagerRequestBuilder->getUrl());
    }

    public function testGetInternalAddressWitLicense() {
        $packagemanagerRequestBuilder = new PackagemanagerRequestBuilder('development', $this->getLicense());
        $this->assertEquals('http://172.16.2.87/modules/fetch/1234-5678-901234-567890.json', $packagemanagerRequestBuilder->getUrl());
    }

    public function testGetExternalAddressWithLicense() {
        $packagemanagerRequestBuilder = new PackagemanagerRequestBuilder('production', $this->getLicense());
        $this->assertEquals('https://packagemanager.it-novum.com/modules/fetch/1234-5678-901234-567890.json', $packagemanagerRequestBuilder->getUrl());
    }

    /**
     * @return string
     */
    private function getLicense() {
        return '1234-5678-901234-567890';
    }

}
