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

use itnovum\openITCOCKPIT\ApiShell\OptionParser;

// run test with: oitc test app Shell/Api/OptionParser
class OptionParserTest extends CakeTestCase {
    public function testParseNormal() {
        $parameters = [
            'model'  => 'Systemsettings',
            'action' => 'add',
            'data'   => 'This is a test',
        ];

        $optionParser = new OptionParser();
        $optionParser->parse($parameters);
        $this->assertInstanceOf('\itnovum\openITCOCKPIT\ApiShell\OptionParser', $optionParser);
    }

    public function testThrowMissingModel() {
        $parameters = [
            'action' => 'add',
            'data'   => 'This is a test',
        ];

        $optionParser = new OptionParser();
        $this->setExpectedException('itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions');
        $optionParser->parse($parameters);
    }

    public function testThrowMissingAction() {
        $parameters = [
            'model' => 'Systemsettings',
            'data'  => 'This is a test',
        ];

        $optionParser = new OptionParser();
        $this->setExpectedException('itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions');
        $optionParser->parse($parameters);
    }

    public function testThrowMissingData() {
        $parameters = [
            'model'  => 'Systemsettings',
            'action' => 'add',
        ];

        $optionParser = new OptionParser();
        $this->setExpectedException('itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions');
        $optionParser->parse($parameters);
    }

    public function testGetOptions() {
        $parameters = [
            'model'  => 'systemsettings',
            'plugin' => 'Foobar',
            'action' => 'add',
            'data'   => 'This is a test',
        ];

        $optionParser = new OptionParser();
        $optionParser->parse($parameters);

        $this->assertEquals('Systemsettings', $optionParser->getModel());
        $this->assertEquals($parameters['plugin'], $optionParser->getPlugin());
        $this->assertEquals($parameters['action'], $optionParser->getAction());
        $this->assertEquals([$parameters['data']], $optionParser->getData());

    }
}
