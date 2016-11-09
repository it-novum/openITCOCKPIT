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


class CustomMacroReplacerTest extends \PHPUnit_Framework_TestCase
{

	//run test: oitc test app Core/CustomMacroReplacer

	public function testInstance(){
		$customMacroReplacer = new CustomMacroReplacer([], OBJECT_SERVICE);
		$this->assertInstanceOf('\itnovum\openITCOCKPIT\Core\CustomMacroReplacer', $customMacroReplacer);
	}

	public function testGetServicePrefix(){
		$customMacroReplacer = new CustomMacroReplacer([], OBJECT_SERVICE);
		$assert = '$_SERVICE';
		$result = $customMacroReplacer->getMacroPrefix();
		$this->assertEquals($assert, $result);
	}

	public function testGetServicePrefixByServicetemplate(){
		$customMacroReplacer = new CustomMacroReplacer([], OBJECT_SERVICETEMPLATE);
		$assert = '$_SERVICE';
		$result = $customMacroReplacer->getMacroPrefix();
		$this->assertEquals($assert, $result);
	}

	public function testGetHostPrefix(){
		$customMacroReplacer = new CustomMacroReplacer([], OBJECT_HOST);
		$assert = '$_HOST';
		$result = $customMacroReplacer->getMacroPrefix();
		$this->assertEquals($assert, $result);
	}

	public function testGetHostPrefixByHosttemplate(){
		$customMacroReplacer = new CustomMacroReplacer([], OBJECT_HOSTTEMPLATE);
		$assert = '$_HOST';
		$result = $customMacroReplacer->getMacroPrefix();
		$this->assertEquals($assert, $result);
	}

	public function testBuildMappingForServices(){
		$customMacroReplacer = new CustomMacroReplacer($this->getServiceMacros(), OBJECT_SERVICE);
		$result = $customMacroReplacer->buildMapping();

		$assert = [
			'search' => [
				'$_SERVICESNMP_COMMUNITY$',
				'$_SERVICESNMP_PASSWORD$'
			],
			'replace' => [
				'foobar',
				'passw0rd'
			]
		];

		$this->assertEquals($assert, $result);
	}

	public function testBuildMappingForHosts(){
		$customMacroReplacer = new CustomMacroReplacer($this->getHostMacros(), OBJECT_HOST);
		$result = $customMacroReplacer->buildMapping();

		$assert = [
			'search' => [
				'$_HOSTFOO$'
			],
			'replace' => [
				'bar',
			]
		];

		$this->assertEquals($assert, $result);
	}

	public function testReplaceForServices(){
		$customMacroReplacer = new CustomMacroReplacer($this->getServiceMacros(), OBJECT_SERVICE);
		$input =  'My SNMP Community is $_SERVICESNMP_COMMUNITY$ with the password $_SERVICESNMP_PASSWORD$';
		$assert = 'My SNMP Community is foobar with the password passw0rd';
		$result = $customMacroReplacer->replaceAllMacros($input);
		$this->assertEquals($assert, $result);
	}

	public function testReplaceForHosts(){
		$customMacroReplacer = new CustomMacroReplacer($this->getHostMacros(), OBJECT_HOST);
		$input =  'Hello my name is host. $_HOSTFOO$ host';
		$assert = 'Hello my name is host. bar host';
		$result = $customMacroReplacer->replaceAllMacros($input);
		$this->assertEquals($assert, $result);
	}

	public function testReplaceOnlyKnownMacros(){
		$customMacroReplacer = new CustomMacroReplacer($this->getHostMacros(), OBJECT_HOST);
		$input =  'Hello my name is $_HOST007$. $_HOSTFOO$ host';
		$assert = 'Hello my name is $_HOST007$. bar host';
		$result = $customMacroReplacer->replaceAllMacros($input);
		$this->assertEquals($assert, $result);
	}

	/**
	 * @return array
	 */
	private function getServiceMacros(){
		return array(
			(int) 0 => array(
				'id' => '2',
				'name' => 'SNMP_COMMUNITY',
				'value' => 'foobar',
				'objecttype_id' => '2048',
				'object_id' => '1'
			),
			(int) 1 => array(
				'id' => '3',
				'name' => 'SNMP_PASSWORD',
				'value' => 'passw0rd',
				'objecttype_id' => '2048',
				'object_id' => '1'
			)
		);
	}

	/**
	 * @return array
	 */
	private function getHostMacros(){
		return array(
			(int) 0 => array(
				'id' => '1',
				'name' => 'FOO',
				'value' => 'bar',
				'objecttype_id' => '256',
				'object_id' => '1'
			)
		);
	}
}
