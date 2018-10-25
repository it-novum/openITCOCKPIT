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

// run test with: oitc test app Shell/Setup/MailConfigValue
namespace itnovum\openITCOCKPIT\SetupShell;


class MailConfigValueTest extends \PHPUnit_Framework_TestCase {
    public function testGetEmptyValueForConfig() {
        $configValue = new MailConfigValue('');
        $this->assertEquals('null', $configValue->getValueForConfig());
    }

    public function testGetStringValueForConfig() {
        $configValue = new MailConfigValue('string');
        $this->assertEquals('"string"', $configValue->getValueForConfig());
    }

    public function testGetZeroValueForConfig() {
        $configValue = new MailConfigValue(0);
        $this->assertEquals('null', $configValue->getValueForConfig());
    }

    public function testGetFalseValueForConfig() {
        $configValue = new MailConfigValue(false);
        $this->assertEquals('null', $configValue->getValueForConfig());
    }

    public function testGetEmptyValue() {
        $configValue = new MailConfigValue('');
        $this->assertEquals('', $configValue->getValue());
    }

    public function testGetStringValue() {
        $configValue = new MailConfigValue('string');
        $this->assertEquals('string', $configValue->getValue());
    }

    public function testGetZeroValue() {
        $configValue = new MailConfigValue(0);
        $this->assertEquals(0, $configValue->getValue());
    }

    public function testGetFalseValue() {
        $configValue = new MailConfigValue(false);
        $this->assertEquals(false, $configValue->getValue());
    }

    public function testIsEmptyValue() {
        $configValue = new MailConfigValue('');
        $this->assertTrue($configValue->isEmpty());
    }

    public function testIsEmptyStringValue() {
        $configValue = new MailConfigValue('string');
        $this->assertFalse($configValue->isEmpty());
    }

    public function testIsEmptyZeroValue() {
        $configValue = new MailConfigValue(0);
        $this->assertTrue($configValue->isEmpty());
    }

    public function testisEmptyFalseValue() {
        $configValue = new MailConfigValue(false);
        $this->assertTrue($configValue->isEmpty());
    }
}
