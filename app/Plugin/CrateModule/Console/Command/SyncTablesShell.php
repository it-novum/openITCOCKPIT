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
     * This shell synchronize hosts, contacts, commands and services from MySQL to CrateDB
     * You can run this shell as often as you want.
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

        $this->info('Synchronization done.');
    }

    public function syncHosts() {
        $this->info('Start synchronization for host objects');

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
        $sizeof = sizeof($hosts);

        $crateHosts = [];
        foreach ($hosts as $i => $host) {
            $this->out(sprintf(
                'Synchronize host %s/%s. (%s)            %s',
                ($i + 1),
                $sizeof,
                substr($host['Host']['name'], 0, 25),
                "\r"
            ), false);

            $CrateHost = new \itnovum\openITCOCKPIT\Crate\CrateHost($host['Host']['id']);
            $CrateHost->setDataFromFindResult($host);

            $crateHosts[] = $CrateHost->getDataForSave();
        }

        $this->CrateHost->saveAll($crateHosts);
        $this->out('');
    }

    public function syncContacts() {
        $this->info('Start synchronization for contact objects');


        $contacts = $this->Contact->find('all', [
            'recursive' => -1,
        ]);
        $sizeof = sizeof($contacts);

        $crateContact = [];
        foreach ($contacts as $i => $contact) {
            $this->out(sprintf(
                'Synchronize contact %s/%s. (%s)            %s',
                ($i + 1),
                $sizeof,
                substr($contact['Contact']['name'], 0, 25),
                "\r"
            ), false);

            $CrateContact = new \itnovum\openITCOCKPIT\Crate\CrateContact($contact['Contact']['id']);
            $CrateContact->setDataFromFindResult($contact);
            $crateContact[] = $CrateContact->getDataForSave();
        }

        $this->CrateContact->saveAll($crateContact);
        $this->out('');
    }

    public function syncCommands() {
        $this->info('Start synchronization for command objects');


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
        $sizeof = sizeof($commands);

        $crateCommand = [];
        foreach ($commands as $i => $command) {
            $this->out(sprintf(
                'Synchronize command %s/%s. (%s)            %s',
                ($i + 1),
                $sizeof,
                substr($command['Command']['name'], 0, 25),
                "\r"
            ), false);

            $CrateCommand = new \itnovum\openITCOCKPIT\Crate\CrateCommand($command['Command']['id']);
            $CrateCommand->setDataFromFindResult($command);
            $crateCommand[] = $CrateCommand->getDataForSave();
        }

        $this->CrateCommand->saveAll($crateCommand);
        $this->out('');
    }

    public function syncServices() {
        $this->info('Start synchronization for service objects');

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
        $sizeof = sizeof($services);

        $crateService = [];
        foreach ($services as $i => $service) {
            $serviceName = $service['Service']['name'];
            if ($serviceName === null || $serviceName === '') {
                $serviceName = $service['Servicetemplate']['name'];
            }

            $this->out(sprintf(
                'Synchronize service %s/%s. (%s/%s)            %s',
                ($i + 1),
                $sizeof,
                substr($service['Service']['host_id'], 0, 25),
                substr($serviceName, 0, 25),
                "\r"
            ), false);
            $CrateService = new \itnovum\openITCOCKPIT\Crate\CrateService($service['Service']['id']);
            $CrateService->setDataFromFindResult($service);

            $crateService[] = $CrateService->getDataForSave();
        }

        $this->CrateService->saveAll($crateService);
        $this->out('');
    }

    public function info($msg) {
        $this->out('<info>[' . date('H:i:s') . '] ' . $msg . '</info>');
    }

    public function getOptionParser() {
        $parser = parent::getOptionParser();
        return $parser;
    }

    public function _welcome() {
        $this->out('');
        $this->out('<info>openITCOCKPIT - Synchronize MySQL with CrateDB</info>');
        $this->hr();
        $this->out('');
    }
}
