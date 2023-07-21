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
            if (!empty($requestData['commands']['_ids'])) {
                /** @var $CommandsTable CommandsTable */
                $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
                $commandsForExport = $CommandsTable->getCommandsByIdForExport(
                    $requestData['commands']['_ids']
                );
            }

            if (!empty($requestData['timeperiods']['_ids'])) {
                /** @var $TimeperiodsTable TimeperiodsTable */
                $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
                $timeperiodsForExport = $TimeperiodsTable->getTimeperiodsByIdForExport(
                    $requestData['timeperiods']['_ids']
                );

            }

            /*
            $exportFileName = 'test.json';
            $this->response->setTypeMap('json', 'application/json');
            $this->response->withType('json');
            $response = $this->response->withFile($exportFileName, ['download' => true, 'name' => $this->request->getQuery('filename')]);
            return $response;
            */
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

        $contactgroups = $ContactgroupsTable->getContactgroupsByContainerIdExact(ROOT_CONTAINER, 'list');
        $contactgroups = Api::makeItJavaScriptAble($contactgroups);

        $servicetemplates = $ServicetemplatesTable->getServicetemplatesByContainerId(ROOT_CONTAINER, 'list');
        $servicetemplates = Api::makeItJavaScriptAble($servicetemplates);

        $servicetemplategroups = $ServicetemplategroupsTable->getServicetemplategroupsByContainerIdExact(ROOT_CONTAINER);
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
