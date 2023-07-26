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
use itnovum\openITCOCKPIT\Core\AngularJS\Api;

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
                    $confiurationItemsArray['commands'] = $commandsForExport;
                }
            }

            if (!empty($requestData['timeperiods']['_ids'])) {
                /** @var $TimeperiodsTable TimeperiodsTable */
                $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
                $timeperiodsForExport = $TimeperiodsTable->getTimeperiodsByIdsForExport(
                    $requestData['timeperiods']['_ids']
                );
                if (!empty($timeperiodsForExport)) {
                    $confiurationItemsArray['timeperiods'] = $timeperiodsForExport;
                }

            }

            if (!empty($requestData['contacts']['_ids'])) {
                /** @var $ContactsTable ContactsTable */
                $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
                $contactsForExport = $ContactsTable->getContactsByIdsForExport(
                    $requestData['contacts']['_ids']
                );
                if (!empty($contactsForExport)) {
                    $confiurationItemsArray['contacts'] = $contactsForExport;
                }
            }

            if (!empty($requestData['contactgroups']['_ids'])) {
                /** @var $ContactgroupsTable ContactgroupsTable */
                $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
                $contactgroupsForExport = $ContactgroupsTable->getContactgroupsByIdsForExport(
                    $requestData['contactgroups']['_ids']
                );
                if (!empty($contactgroupsForExport)) {
                    $confiurationItemsArray['contactgroups'] = $contactgroupsForExport;
                }
            }

            if (!empty($requestData['servicetemplates']['_ids'])) {
                /** @var $ServicetemplatesTable ServicetemplatesTable */
                $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
                $servicetemplatesForExport = $ServicetemplatesTable->getServicetemplatesByIdsForExport(
                    $requestData['servicetemplates']['_ids']
                );
                if (!empty($servicetemplatesForExport)) {
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

            if (!empty($confiurationItemsArray)) {
                $exportFileName = '/tmp/test.json';

                $exportJsonFile = fopen($exportFileName, 'w+');
                fwrite($exportJsonFile, json_encode($confiurationItemsArray));
                fclose($exportJsonFile);

                $this->response->setTypeMap('json', 'application/json');
                $this->response->withType('json');
                $response = $this->response->withFile($exportFileName, [
                    'download' => true,
                    'name'     => $this->request->getQuery('filename')
                ]);
                return $response;
            }
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
