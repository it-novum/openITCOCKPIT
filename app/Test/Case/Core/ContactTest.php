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



//run test: oitc test app Core/Contact

App::uses('Contact', 'Model');

class ContactTest extends CakeTestCase {

    public $fixtures = [
        'app.contact',
        'app.contactsContainer',
        'app.container',
        'app.contactgroup',
        'app.timeperiod'
    ];
    public $autoFixtures = true;

    public function setUp() {
        parent::setUp();
        $this->Contact = ClassRegistry::init('Contact');
    }

    public function testContactsByContainerId() {
        $this->Contact = ClassRegistry::init('Contact');
        $this->Contact->unbindModel([

            'belongsTo' => [
                'HostTimeperiod',
                'ServiceTimeperiod'
            ],
            'hasAndBelongsToMany' => [
                'HostCommands',
                'ServiceCommands'
            ]
        ], true);
        $result = $this->Contact->contactsByContainerId([1]);

        $expected = array(
            array(
                'Contact' => array(
                    'id' => '1',
                    'uuid' => 'f70418ac-d646-47b1-a037-7d5a66478421',
                    'name' => 'ContactA',
                    'description' => 'ContactA DESC',
                    'email' => 'contactA@localhost',
                    'phone' => '05647385634',
                    'host_timeperiod_id' => '1',
                    'service_timeperiod_id' => '1',
                    'host_notifications_enabled' => '0',
                    'service_notifications_enabled' => '0',
                    'notify_service_recovery' => '0',
                    'notify_service_warning' => '0',
                    'notify_service_unknown' => '0',
                    'notify_service_critical' => '0',
                    'notify_service_flapping' => '0',
                    'notify_service_downtime' => '0',
                    'notify_host_recovery' => '0',
                    'notify_host_down' => '0',
                    'notify_host_unreachable' => '0',
                    'notify_host_flapping' => '0',
                    'notify_host_downtime' => '0'
                ),
                'Container' => array(
                    0 => array(
                        'id' => '1',
                        'containertype_id' => '1',
                        'name' => 'ROOT',
                        'parent_id' => null,
                        'lft' => '1',
                        'rght' => '12',
                        'ContactsToContainer' => array(
                            'id' => '1',
                            'contact_id' => '1',
                            'container_id' => '1'
                        )
                    ),
                    1 => array(
                        'id' => '3',
                        'containertype_id' => '2',
                        'name' => 'TenantB',
                        'parent_id' => '1',
                        'lft' => '4',
                        'rght' => '5',
                        'ContactsToContainer' => array(
                            'id' => '3',
                            'contact_id' => '1',
                            'container_id' => '3'
                        )
                    )
                )
            )
        );
        $this->assertEquals($expected, $result);
    }
}
