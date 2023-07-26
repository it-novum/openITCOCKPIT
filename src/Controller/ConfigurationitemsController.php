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

declare(strict_types=1);

namespace App\Controller;

use App\Form\ConfigurationitemsExportForm;
use App\Model\Table\CommandsTable;
use App\Model\Table\ContactgroupsTable;
use App\Model\Table\ContactsTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplategroupsTable;
use App\Model\Table\ServicetemplatesTable;
use App\Model\Table\TimeperiodsTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\FileDebugger;

class ConfigurationitemsController extends AppController {
    public function import() {
        if (!$this->isJsonRequest()) {
            return;
        }
    }

    public function export() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $configurationitemsExportForm = new ConfigurationitemsExportForm();
        $requestData = $this->request->getData('Configurationitems', []);
        $configurationitemsExportForm->execute($requestData);

        if (!empty($configurationitemsExportForm->getErrors())) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $configurationitemsExportForm->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        } else {
            $confiurationItemsArray = [];
            if (!empty($requestData['commands']['_ids'])) {
                /** @var $CommandsTable CommandsTable */
                $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
                $commandsForExport = $CommandsTable->getCommandsByIdsForExport(
                    $requestData['commands']['_ids']
                );
                if (!empty($commandsForExport)) {
                    $confiurationItemsArray['commands'] = Hash::combine(
                        $commandsForExport, '{n}.uuid', '{n}'
                    );
                }
            }

            if (!empty($requestData['timeperiods']['_ids'])) {
                /** @var $TimeperiodsTable TimeperiodsTable */
                $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
                $timeperiodsForExport = $TimeperiodsTable->getTimeperiodsByIdsForExport(
                    $requestData['timeperiods']['_ids']
                );
                if (!empty($timeperiodsForExport)) {
                    $confiurationItemsArray['timeperiods'] = Hash::combine(
                        $timeperiodsForExport, '{n}.uuid', '{n}'
                    );
                }
            }

            if (!empty($requestData['contacts']['_ids'])) {
                /** @var $ContactsTable ContactsTable */
                $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
                $contactsForExport = $ContactsTable->getContactsByIdsForExport(
                    $requestData['contacts']['_ids']
                );
                if (!empty($contactsForExport)) {
                    foreach ($contactsForExport as $key => $contactForExport) {
                        $hostCommandsUuids = [];
                        foreach ($contactForExport['host_commands'] as $hostCommand) {
                            if (isset($confiurationItemsArray['commands'][$hostCommand['uuid']])) {
                                continue;
                            }
                            $confiurationItemsArray['commands'][$hostCommand['uuid']] = $hostCommand;
                            $hostCommandsUuids[] = $hostCommand['uuid'];
                        }
                        $contactsForExport[$key]['host_commands'] = $hostCommandsUuids;

                        $serviceCommandsUuids = [];
                        foreach ($contactForExport['service_commands'] as $serviceCommand) {
                            if (isset($confiurationItemsArray['commands'][$serviceCommand['uuid']])) {
                                continue;
                            }
                            $confiurationItemsArray['commands'][$serviceCommand['uuid']] = $serviceCommand;
                            $serviceCommandsUuids[] = $serviceCommand['uuid'];
                        }
                        $contactsForExport[$key]['service_commands'] = $serviceCommandsUuids;

                        $confiurationItemsArray['timeperiods'][$contactForExport['host_timeperiod']['uuid']] = $contactForExport['host_timeperiod'];
                        $contactsForExport[$key]['host_timeperiod'] = $contactForExport['host_timeperiod']['uuid'];

                        $confiurationItemsArray['timeperiods'][$contactForExport['service_timeperiod']['uuid']] = $contactForExport['service_timeperiod'];
                        $contactsForExport[$key]['service_timeperiod'] = $contactForExport['service_timeperiod']['uuid'];
                    }
                    $confiurationItemsArray['contacts'] = Hash::combine(
                        $contactsForExport, '{n}.uuid', '{n}'
                    );
                }
            }

            if (!empty($requestData['contactgroups']['_ids'])) {
                /** @var $ContactgroupsTable ContactgroupsTable */
                $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
                $contactgroupsForExport = $ContactgroupsTable->getContactgroupsByIdsForExport(
                    $requestData['contactgroups']['_ids']
                );
                if (!empty($contactgroupsForExport)) {
                    foreach ($contactgroupsForExport as $key => $contactgroupForExport) {
                        $contactUuids = [];
                        foreach ($contactgroupForExport['contacts'] as $contactForExport) {
                            if (!isset($confiurationItemsArray['contacts'][$contactForExport['uuid']])) {

                                $hostCommandsUuids = [];
                                foreach ($contactForExport['host_commands'] as $hostCommand) {
                                    if (!isset($confiurationItemsArray['commands'][$hostCommand['uuid']])) {
                                        $confiurationItemsArray['commands'][$hostCommand['uuid']] = $hostCommand;
                                    }
                                    $hostCommandsUuids[] = $hostCommand['uuid'];
                                }
                                $contactsForExport[$key]['host_commands'] = $hostCommandsUuids;

                                $serviceCommandsUuids = [];
                                foreach ($contactForExport['service_commands'] as $serviceCommand) {
                                    if (!isset($confiurationItemsArray['commands'][$hostCommand['uuid']])) {
                                        $confiurationItemsArray['commands'][$serviceCommand['uuid']] = $serviceCommand;
                                    }
                                    $serviceCommandsUuids[] = $serviceCommand['uuid'];
                                }
                                $contactsForExport[$key]['service_commands'] = $serviceCommandsUuids;

                                $confiurationItemsArray['timeperiods'][$contactForExport['host_timeperiod']['uuid']] = $contactForExport['host_timeperiod'];
                                $contactsForExport[$key]['host_timeperiod'] = $contactForExport['host_timeperiod']['uuid'];

                                $confiurationItemsArray['timeperiods'][$contactForExport['service_timeperiod']['uuid']] = $contactForExport['service_timeperiod'];
                                $contactsForExport[$key]['service_timeperiod'] = $contactForExport['service_timeperiod']['uuid'];

                            }
                            $contactUuids[] = $contactForExport['uuid'];
                        }
                        $contactgroupsForExport[$key]['contacts'] = $contactUuids;
                    }
                    $confiurationItemsArray['contactgroups'] = Hash::combine(
                        $contactgroupsForExport, '{n}.uuid', '{n}'
                    );
                }
            }

            if (!empty($requestData['servicetemplates']['_ids'])) {
                /** @var $ServicetemplatesTable ServicetemplatesTable */
                $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
                $servicetemplatesForExport = $ServicetemplatesTable->getServicetemplatesByIdsForExport(
                    $requestData['servicetemplates']['_ids']
                );
                if (!empty($servicetemplatesForExport)) {
                    FileDebugger::dump($servicetemplatesForExport);
                    $confiurationItemsArray['servicetemplates'] = $servicetemplatesForExport;
                }
            }

            if (!empty($requestData['servicetemplategroups']['_ids'])) {
                /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
                $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');
                $servicetemplategroupsForExport = $ServicetemplategroupsTable->getServicetemplategroupsByIdsForExport(
                    $requestData['servicetemplategroups']['_ids']
                );
                if (!empty($servicetemplategroupsForExport)) {
                    $confiurationItemsArray['servicetemplategroups'] = $servicetemplategroupsForExport;
                }
            }

            $this->set('export', $confiurationItemsArray);
            $this->set('checksum', hash('sha256', json_encode($confiurationItemsArray)));
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['export', 'checksum', 'success']);
        }
    }


    public function loadElementsForExport() { // ROOT container only
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        /** @var ServicesTable $ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
        /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
        $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');

        $commands = $CommandsTable->getAllCommandsAsList();
        $commands = Api::makeItJavaScriptAble($commands);

        $timeperiods = $TimeperiodsTable->getTimeperiodsByContainerIdExact(ROOT_CONTAINER, 'list');
        $timeperiods = Api::makeItJavaScriptAble($timeperiods);

        $contacts = $ContactsTable->contactsByContainerId(ROOT_CONTAINER, 'list');
        $contacts = Api::makeItJavaScriptAble($contacts);

        $contactgroups = $ContactgroupsTable->getContactgroupsByContainerIdExact(ROOT_CONTAINER, 'list', 'id');
        $contactgroups = Api::makeItJavaScriptAble($contactgroups);

        $servicetemplates = $ServicetemplatesTable->getServicetemplatesByContainerId(ROOT_CONTAINER, 'list');
        $servicetemplates = Api::makeItJavaScriptAble($servicetemplates);

        $servicetemplategroups = $ServicetemplategroupsTable->getServicetemplategroupsByContainerIdExact(ROOT_CONTAINER, 'list', 'id');
        $servicetemplategroups = Api::makeItJavaScriptAble($servicetemplategroups);

        $this->set('commands', $commands);
        $this->set('timeperiods', $timeperiods);
        $this->set('contacts', $contacts);
        $this->set('contactgroups', $contactgroups);
        $this->set('servicetemplates', $servicetemplates);
        $this->set('servicetemplategroups', $servicetemplategroups);

        $this->viewBuilder()->setOption('serialize', [
            'commands',
            'timeperiods',
            'contacts',
            'contactgroups',
            'servicetemplates',
            'servicetemplategroups'
        ]);
    }
}
