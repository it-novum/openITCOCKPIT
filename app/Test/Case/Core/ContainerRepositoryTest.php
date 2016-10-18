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

//run test: oitc test app Core/ContainerRepository

class ContainerRepositoryTest extends \CakeTestCase
{
	public function testInstance(){
		$ContainerRepository = new ContainerRepository();
		$this->assertInstanceOf('\itnovum\openITCOCKPIT\Core\ContainerRepository', $ContainerRepository);
	}

	public function testAddContainerWithInt(){
		$ContainerRepository = new ContainerRepository([1,2]);
		$ContainerRepository->addContainer(3);

		$assert = [1,2,3];
		$this->assertEquals($assert, $ContainerRepository->getContainer());
	}

	public function testAddContainerWithStrings(){
		$ContainerRepository = new ContainerRepository(['1',2]);
		$ContainerRepository->addContainer('3');

		$assert = [1,2,3];
		$this->assertEquals($assert, $ContainerRepository->getContainer());
	}


	public function testAddContainerWithArray(){
		$ContainerRepository = new ContainerRepository([1,2]);
		$ContainerRepository->addContainer([1,3,5,10]);

		$assert = [1,2,3,5,10];
		$this->assertEquals($assert, $ContainerRepository->getContainer());
	}

	public function testRemoveContainerInt(){
		$ContainerRepository = new ContainerRepository([1,2,3]);
		$ContainerRepository->removeContainerId(2);

		$assert = [1,3];
		$this->assertEquals($assert, $ContainerRepository->getContainer());
	}

	public function testRemoveContainerArray(){
		$ContainerRepository = new ContainerRepository([1,2,3,5,6]);
		$ContainerRepository->removeContainerId([2,5]);

		$assert = [1,3,6];
		$this->assertEquals($assert, $ContainerRepository->getContainer());
	}

	public function testAddContainerExistsTrue(){
		$ContainerRepository = new ContainerRepository([1,2]);
		$result = $ContainerRepository->exists(1);

		$this->assertTrue($result);
	}

	public function testAddContainerExistsFalse(){
		$ContainerRepository = new ContainerRepository([]);
		$result = $ContainerRepository->exists(1);

		$this->assertFalse($result);
	}
}
