<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.


namespace itnovum\openITCOCKPIT\Core;


class HostSharingPermissionsTest extends \PHPUnit_Framework_TestCase
{
	//run test: oitc test app Core/HostSharingPermissions

	public function testInstance(){
		$hostSharingPermissions = new HostSharingPermissions(0, false, [], []);
		$this->assertInstanceOf('\itnovum\openITCOCKPIT\Core\HostSharingPermissions', $hostSharingPermissions);
	}

	public function testIsRootHostAndRestrictedUserTrue(){
		$hostSharingPermissions = new HostSharingPermissions(1, false, [], []);
		$this->assertTrue($hostSharingPermissions->isRootHostAndRestrictedUser());
	}

	public function testIsRootHostAndRestrictedUserFalse(){
		$hostSharingPermissions = new HostSharingPermissions(1, true, [], []);
		$this->assertFalse($hostSharingPermissions->isRootHostAndRestrictedUser());
	}

	public function testIsNotRootHostAndRestrictedUserFalse(){
		$hostSharingPermissions = new HostSharingPermissions(5, false, [], []);
		$this->assertFalse($hostSharingPermissions->isRootHostAndRestrictedUser());
	}

	public function testCleanHostContainerArray(){
		$host = $this->getHostInRootContainerSharedToNonRootContainer();
		$hostSharingPermissions = new HostSharingPermissions($host['Host']['container_id'], false, $host['Container'], []);
		$result = $hostSharingPermissions->cleanHostContainerArray($host['Host']['container_id'], $host['Container']);
		$assert = [4];
		$this->assertEquals($assert, $result);
	}

	public function testIsSharedToNotPermittedContainersFalse(){
		$host = $this->getHostInRootContainerSharedToNonRootContainer();
		$hostSharingPermissions = new HostSharingPermissions($host['Host']['container_id'], false, $host['Container'], [4, 8,20]);
		$this->assertFalse($hostSharingPermissions->isSharedToNotPermittedContainers());
	}

	public function testIsSharedToNotPermittedContainersTrue(){
		$host = $this->getHostInRootContainerSharedToNonRootContainer();
		$hostSharingPermissions = new HostSharingPermissions($host['Host']['container_id'], false, $host['Container'], [14, 8,20]);
		$this->assertTrue($hostSharingPermissions->isSharedToNotPermittedContainers());
	}

	public function testAllowSharingIfRootHostAndRestrictedUser(){
		$rootHost = $this->getHostInRootContainerSharedToNonRootContainer();
		$hostSharingPermissions = new HostSharingPermissions($rootHost['Host']['container_id'], false, $rootHost['Container'], []);
		$this->assertFalse($hostSharingPermissions->allowSharing());
	}

	public function testAllowSharingIfNotRootHostAndRestrictedUserWithContainerPermissions(){
		$nonRootHost = $this->getHostNotRootContainerSharedToNonRootContainer();
		$hostSharingPermissions = new HostSharingPermissions($nonRootHost['Host']['container_id'], false, $nonRootHost['Container'], [2,10,50,100,4]);
		$this->assertTrue($hostSharingPermissions->allowSharing());
	}

	public function testAllowSharingIfRootHostAndRootUser(){
		$rootHost = $this->getHostInRootContainerSharedToNonRootContainer();
		$hostSharingPermissions = new HostSharingPermissions($rootHost['Host']['container_id'], true, $rootHost['Container'], [1,2,3,4,5,6]);
		$this->assertTrue($hostSharingPermissions->allowSharing());
	}

	public function testAllowSharingIfNotRootHostAndRestrictedUserWithSharingToRoot(){
		$nonRootHost = $this->getHostNotInRootButSharedToRoot();
		$hostSharingPermissions = new HostSharingPermissions($nonRootHost['Host']['container_id'], false, $nonRootHost['Container'], [2,10,50,100,4]);
		$this->assertFalse($hostSharingPermissions->allowSharing());
	}

	public function testAllowSharingIfPrimaryContainerHostNotPermittedForRestrictedUsert(){
		$nonRootHost = $this->getHostNotInRootButSharedToRoot();
                $hostSharingPermissions = new HostSharingPermissions($nonRootHost['Host']['container_id'],  false, $nonRootHost['Container'], [14, 20]);
                $this->assertFalse($hostSharingPermissions->allowSharing());
        }

	private function getHostNotRootContainerSharedToNonRootContainer(){
		$data = [
			'Host' => [
				'container_id' => '10'
			],
			'Container' => array(
				'id' => '1',
				'containertype_id' => '1',
				'name' => 'root',
				'parent_id' => null,
				'lft' => '1',
				'rght' => '10',
				0 => array(
					'id' => '4',
					'containertype_id' => '1',
					'name' => 'root',
					'parent_id' => null,
					'lft' => '1',
					'rght' => '10',
					'HostsToContainer' => array(
						'id' => '26',
						'host_id' => '3',
						'container_id' => '4'
					)
				),
				1 => array(
					'id' => '10',
					'containertype_id' => '5',
					'name' => 'Container 10',
					'parent_id' => '3',
					'lft' => '5',
					'rght' => '8',
					'HostsToContainer' => array(
						'id' => '27',
						'host_id' => '3',
						'container_id' => '10'
					)
				),
			)
		];
		return $data;
	}

	private function getHostNotInRootButSharedToRoot(){
		$host = $this->getHostNotRootContainerSharedToNonRootContainer();
		$host['Container'][] = array(
			'id' => '1',
			'containertype_id' => '5',
			'name' => 'root',
			'parent_id' => null,
			'lft' => '5',
			'rght' => '8',
			'HostsToContainer' => array(
				'id' => '27',
				'host_id' => '3',
				'container_id' => '1'
			)
		);
		return $host;
	}

	private function getHostInRootContainerSharedToNonRootContainer(){
		$data = [
			'Host' => [
				'container_id' => '1'
			],
			'Container' => array(
				'id' => '1',
				'containertype_id' => '1',
				'name' => 'root',
				'parent_id' => null,
				'lft' => '1',
				'rght' => '10',
				0 => array(
					'id' => '1',
					'containertype_id' => '1',
					'name' => 'root',
					'parent_id' => null,
					'lft' => '1',
					'rght' => '10',
					'HostsToContainer' => array(
						'id' => '26',
						'host_id' => '3',
						'container_id' => '1'
					)
				),
				1 => array(
					'id' => '4',
					'containertype_id' => '5',
					'name' => 'subContainerTenantA',
					'parent_id' => '3',
					'lft' => '5',
					'rght' => '8',
					'HostsToContainer' => array(
						'id' => '27',
						'host_id' => '3',
						'container_id' => '4'
					)
				)
			)
		];
		return $data;
	}
}
