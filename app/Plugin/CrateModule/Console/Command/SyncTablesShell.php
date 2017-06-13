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

class SyncTablesShell extends AppShell {
    /*
     * This is a test and debuging shell for development purposes
     */
    public $uses = [
        'Host',
        'Contact',
        'CrateModule.CrateHost',
        'CrateModule.CrateContact'
    ];

    public function main(){
        //$this->syncHosts();
        $this->syncContacts();

    }

    public function syncHosts(){
        $hosts = $this->Host->find('all', [
            'recursive' => -1,
            'contain' => [
                'Container',
                'Hosttemplate' => [
                    'fields' => [
                        'Hosttemplate.active_checks_enabled',
                        'Hosttemplate.tags',
                    ]
                ]
            ],
            'fields' => [
                'Host.id',
                'Host.name',
                'Host.uuid',
                'Host.address',
                'Host.hosttemplate_id',
                'Host.container_id',
                'Host.active_checks_enabled',
                'Host.tags',
                'Host.satellite_id'
            ]
        ]);

        $crateHosts = [];
        foreach ($hosts as $host) {
            $containerIds = [];
            $_containerIds = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
            foreach ($_containerIds as $_containerId) {
                $containerIds[] = (int)$_containerId;
            }

            if (!in_array((int)$host['Host']['container_id'], $containerIds, true)) {
                $containerIds[] = (int)$host['Host']['container_id'];
            }

            $active_checks_enabled = $host['Hosttemplate']['active_checks_enabled'];
            if ($host['Host']['active_checks_enabled'] !== null) {
                $active_checks_enabled = $host['Host']['active_checks_enabled'];
            }

            $tags = $host['Hosttemplate']['tags'];
            if ($host['Host']['tags']) {
                $tags = $host['Host']['tags'];
            }

            $crateHosts[] = [
                'CrateHost' => [
                    'id' => $host['Host']['id'],
                    'name' => $host['Host']['name'],
                    'uuid' => $host['Host']['uuid'],
                    'address' => $host['Host']['address'],
                    'active_checks_enabled' => (int)$active_checks_enabled,
                    'satellite_id' => (int)$host['Host']['satellite_id'],
                    'container_ids' => $containerIds,
                    'container_id' => (int)$host['Host']['container_id'],
                    'tags' => $tags,
                    'hosttemplate_id' => (int)$host['Host']['hosttemplate_id']
                ]
            ];
        }

        $this->CrateHost->saveAll($crateHosts);
    }

    public function syncContacts(){
        $contacts = $this->Contact->find('all', [
            'contain' => [
                'HostCommands',
                'ServiceCommands'
            ]
        ]);

        foreach ($contacts as $contact) {
            foreach ($contact['HostCommands'] as $hostCommand) {
                $crateContact[] = [
                    'CrateContact' => [
                        'contact_id' => (int)$contact['Contact']['id'],
                        'command_id' => (int)$hostCommand['id'],
                        'contact_uuid' => $contact['Contact']['uuid'],
                        'command_uuid' => $hostCommand['uuid'],
                        'contact_name' => $contact['Contact']['name'],
                        'command_name' => $hostCommand['name'],
                        'is_host_command' => true
                    ]
                ];
            }

            foreach ($contact['ServiceCommands'] as $serviceCommand) {
                $crateContact[] = [
                    'CrateContact' => [
                        'contact_id' => (int)$contact['Contact']['id'],
                        'command_id' => (int)$serviceCommand['id'],
                        'contact_uuid' => $contact['Contact']['uuid'],
                        'command_uuid' => $serviceCommand['uuid'],
                        'contact_name' => $contact['Contact']['name'],
                        'command_name' => $serviceCommand['name'],
                        'is_host_command' => false
                    ]
                ];
            }
        }

        $this->CrateContact->saveAll($crateContact);

    }

    public function getOptionParser(){
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'type' => ['short' => 't', 'help' => __d('oitc_console', 'Type of the notification host or service')],
            'hostname' => ['help' => __d('oitc_console', 'The uuid of the host')],
        ]);

        return $parser;
    }
}
