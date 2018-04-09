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

    public function main() {
        $this->syncHosts();
        $this->syncContacts();
        $this->syncCommands();
        $this->syncServices();

    }

    public function syncHosts() {
        $hosts = $this->Host->find('all', [
            'recursive' => -1,
            'contain'   => [
                'Container',
                'Hosttemplate' => [
                    'fields' => [
                        'Hosttemplate.active_checks_enabled',
                        'Hosttemplate.tags',
                    ]
                ]
            ],
            'fields'    => [
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
            $CrateHost = new \itnovum\openITCOCKPIT\Crate\CrateHost($host['Host']['id']);
            $CrateHost->setDataFromFindResult($host);

            $crateHosts[] = $CrateHost->getDataForSave();
        }

        $this->CrateHost->saveAll($crateHosts);
    }

    public function syncContacts() {
        $contacts = $this->Contact->find('all', [
            'recursive' => -1,
        ]);

        $crateContact = [];
        foreach ($contacts as $contact) {
            $crateContact[] = [
                'CrateContact' => [
                    'id'   => (int)$contact['Contact']['id'],
                    'uuid' => $contact['Contact']['uuid'],
                    'name' => $contact['Contact']['name'],
                ]
            ];
        }

        $this->CrateContact->saveAll($crateContact);

    }

    public function syncCommands() {
        $commands = $this->Command->find('all', [
            'recursive'  => -1,
            'fields'     => [
                'Command.id',
                'Command.name',
                'Command.uuid',
                'Command.command_type'
            ],
            'conditions' => [
                'Command.command_type' => NOTIFICATION_COMMAND
            ]
        ]);

        $crateCommand = [];
        foreach ($commands as $command) {
            $CrateCommand = new \itnovum\openITCOCKPIT\Crate\CrateCommand($command['Command']['id']);
            $CrateCommand->setDataFromFindResult($command);
            $crateCommand[] = $CrateCommand->getDataForSave();
        }

        $this->CrateCommand->saveAll($crateCommand);

    }

    public function syncServices() {
        $services = $this->Service->find('all', [
            'recursive' => -1,
            'fields'    => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.servicetemplate_id',
                'Service.active_checks_enabled',
                'Service.tags',
                'Service.host_id',
                'Service.disabled'
            ],
            'contain'   => [
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
            $CrateService = new \itnovum\openITCOCKPIT\Crate\CrateService($service['Service']['id']);
            $CrateService->setDataFromFindResult($service);

            $crateService[] = $CrateService->getDataForSave();
        }

        $this->CrateService->saveAll($crateService);
    }


    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'type'     => ['short' => 't', 'help' => __d('oitc_console', 'Type of the notification host or service')],
            'hostname' => ['help' => __d('oitc_console', 'The uuid of the host')],
        ]);

        return $parser;
    }
}
