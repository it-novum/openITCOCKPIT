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

namespace itnovum\openITCOCKPIT\HostgroupsController;

//run test with: oitc test app Controller/Hostgroups/CumulatedServicestatusCollection

class CumulatedServicestatusCollectionTest extends \PHPUnit_Framework_TestCase {

    public function testInstance() {
        $collection = new CumulatedServicestatusCollection($this->servicesExtendedLoaderFake());
        $this->assertInstanceOf('\itnovum\openITCOCKPIT\HostgroupsController\CumulatedServicestatusCollection', $collection);
    }

    public function testExistsByExistingHostId() {
        $collection = new CumulatedServicestatusCollection($this->servicesExtendedLoaderFake());
        $result = $collection->existsByHostId(6);
        $this->assertTrue($result);
    }

    public function testNotExistsByNonExistingHostId() {
        $collection = new CumulatedServicestatusCollection($this->servicesExtendedLoaderFake());
        $result = $collection->existsByHostId(12345678);
        $this->assertFalse($result);
    }

    public function testGetStatusByHostId() {
        $collection = new CumulatedServicestatusCollection($this->servicesExtendedLoaderFake());
        $cumulatedServicestatus = $collection->getByHostId(6);
        $this->assertEquals(2, $cumulatedServicestatus);
    }

    public function testAssertMissingRecord() {
        $collection = new CumulatedServicestatusCollection($this->servicesExtendedLoaderFake());
        $this->setExpectedException('\itnovum\openITCOCKPIT\HostgroupsController\Exceptions\RecordExistsException');
        $result = $collection->getByHostId(12345678);
    }

    public function testEvenIfNotExistsOnExisting() {
        $collection = new CumulatedServicestatusCollection($this->servicesExtendedLoaderFake());
        $cumulatedServicestatus = $collection->getByHostIdEvenIfNotExists(6);
        $this->assertEquals(2, $cumulatedServicestatus);
    }

    public function testEvenIfNotExistsOnNonExisting() {
        $collection = new CumulatedServicestatusCollection($this->servicesExtendedLoaderFake());
        $cumulatedServicestatus = $collection->getByHostIdEvenIfNotExists(12345678);
        $this->assertEquals(-1, $cumulatedServicestatus);
    }

    public function testGetCollectionAsArray() {
        $data = [
            [
                'Host' => [
                    'id' => 10,
                ],
                0      => [
                    'cumulated' => 0,
                ],
            ],
            [
                'Host' => [
                    'id' => 15,
                ],
                0      => [
                    'cumulated' => 3,
                ],
            ],
            [
                'Host' => [
                    'id' => '1337',
                ],
                0      => [
                    'cumulated' => '2',
                ],
            ],
        ];
        $collection = new CumulatedServicestatusCollection($data);
        $asserted = [
            10   => 0,
            15   => 3,
            1337 => 2,
        ];
        $this->assertEquals($asserted, $collection->getCollectionAsArray());
    }

    /**
     * @return array
     */
    private function servicesExtendedLoaderFake() {
        return [
            (int)0 => [
                'Host' => [
                    'id' => '2',
                ],
                (int)0 => [
                    'cumulated' => '0',
                ],
            ],
            (int)1 => [
                'Host' => [
                    'id' => '3',
                ],
                (int)0 => [
                    'cumulated' => '0',
                ],
            ],
            (int)2 => [
                'Host' => [
                    'id' => '4',
                ],
                (int)0 => [
                    'cumulated' => '0',
                ],
            ],
            (int)3 => [
                'Host' => [
                    'id' => '6',
                ],
                (int)0 => [
                    'cumulated' => '2',
                ],
            ],
            (int)4 => [
                'Host' => [
                    'id' => '7',
                ],
                (int)0 => [
                    'cumulated' => '0',
                ],
            ],
            (int)5 => [
                'Host' => [
                    'id' => '8',
                ],
                (int)0 => [
                    'cumulated' => '0',
                ],
            ],
        ];
    }

}
