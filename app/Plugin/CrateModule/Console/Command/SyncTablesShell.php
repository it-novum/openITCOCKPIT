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
     * Call: oitc CrateModule.sync_tables
     */
    public $uses = [
        'Host',
        'Service',
        'Servicetemplate',
        'Contact',
        'Command',
        'CrateModule.CrateHost',
        'CrateModule.CrateContact',
        'CrateModule.CrateCommand',
        'CrateModule.CrateService'

    ];

    public function main(){
        $this->syncHosts();
        $this->syncContacts();
        $this->syncCommands();
        $this->syncServices();

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
                'Host.satellite_id',
                'Host.disabled'
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
                    'hosttemplate_id' => (int)$host['Host']['hosttemplate_id'],
                    'disabled' => (bool)$host['Host']['disabled'],
                ]
            ];
        }

        $this->CrateHost->saveAll($crateHosts);
    }

    public function syncContacts(){
        $contacts = $this->Contact->find('all', [
            'recursive' => -1,
        ]);

        $crateContact = [];
        foreach ($contacts as $contact) {
            $crateContact[] = [
                'CrateContact' => [
                    'id' => (int)$contact['Contact']['id'],
                    'uuid' => $contact['Contact']['uuid'],
                    'name' => $contact['Contact']['name'],
                ]
            ];
        }

        $this->CrateContact->saveAll($crateContact);

    }

    public function syncCommands(){
        $commands = $this->Command->find('all', [
            'recursive' => -1,
            'fields' => [
                'Command.id',
                'Command.name',
                'Command.uuid'
            ],
            'conditions' => [
                'Command.command_type' => NOTIFICATION_COMMAND
            ]
        ]);

        $crateCommand = [];
        foreach ($commands as $command) {
            $crateCommand[] = [
                'CrateCommand' => [
                    'id' => (int)$command['Command']['id'],
                    'uuid' => $command['Command']['uuid'],
                    'name' => $command['Command']['name'],
                ]
            ];
        }

        $this->CrateCommand->saveAll($crateCommand);

    }

    public function syncServices(){
        $services = $this->Service->find('all', [
            'recursive' => -1,
            'fields' => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.servicetemplate_id',
                'Service.active_checks_enabled',
                'Service.tags',
                'Service.host_id',
                'Service.disabled'
            ],
            'contain' => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                        'Servicetemplate.name',
                        'Servicetemplate.active_checks_enabled',
                        'Servicetemplate.tags',
                    ]
                ]
            ]
        ]);

        $crateService = [];
        foreach ($services as $service) {
            $serviceName = $service['Service']['name'];
            $nameFromTemplate = false;
            if ($serviceName === null || $serviceName === '') {
                $serviceName = $service['Servicetemplate']['name'];
                $nameFromTemplate = true;
            }

            $activeChecksEnabled = $service['Service']['active_checks_enabled'];
            $activeChecksEnabledFromTemplate = false;
            if ($activeChecksEnabled === null || $activeChecksEnabled === '') {
                $activeChecksEnabled = $service['Servicetemplate']['active_checks_enabled'];
                $activeChecksEnabledFromTemplate = true;
            }

            $tags = $service['Service']['tags'];
            $tagFromTemplate = false;
            if ($tags === null || $tags === '') {
                $tags = $service['Servicetemplate']['tags'];
                $tagFromTemplate = true;
            }

            $crateService[] = [
                'CrateService' => [
                    'id' => (int)$service['Service']['id'],
                    'uuid' => $service['Service']['uuid'],
                    'name' => $serviceName,
                    'servicetemplate_id' => (int)$service['Service']['servicetemplate_id'],
                    'host_id' => (int)$service['Service']['host_id'],
                    'name_from_template' => $nameFromTemplate,
                    'active_checks_enabled' => $activeChecksEnabled,
                    'active_checks_enabled_from_template' => $activeChecksEnabledFromTemplate,
                    'tags' => $tags,
                    'tags_from_template' => $tagFromTemplate,
                    'disabled' => (bool)$service['Service']['disabled'],
                ]
            ];
        }

        $this->CrateService->saveAll($crateService);
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
