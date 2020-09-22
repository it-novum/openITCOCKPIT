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


use App\Form\WizardMysqlServerForm;
use App\itnovum\openITCOCKPIT\Wizard\CreateService;
use App\itnovum\openITCOCKPIT\Wizard\UpdateHost;
use App\Model\Entity\Host;
use App\Model\Table\ContainersTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplatesTable;
use App\Model\Table\WizardAssignmentsTable;
use Cake\Core\Plugin;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;

/**
 * Class WizardsController
 * @package App\Controller
 */
class WizardsController extends AppController {

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        /** @var WizardAssignmentsTable $WizardAssignmentsTable */
        $WizardAssignmentsTable = TableRegistry::getTableLocator()->get('WizardAssignments');
        $wizards = $WizardAssignmentsTable->getAvailableWizards($this->PERMISSIONS);
        $possibleWizards = $WizardAssignmentsTable->getPossibleWizardsOfModules($wizards);
        $this->set('wizards', $wizards);
        $this->set('possibleWizards', $possibleWizards);
        $this->viewBuilder()->setOption('serialize', ['wizards', 'possibleWizards']);
    }

    public function assignments() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        /** @var WizardAssignmentsTable $WizardAssignmentsTable */
        $WizardAssignmentsTable = TableRegistry::getTableLocator()->get('WizardAssignments');
        $wizards = $WizardAssignmentsTable->getAvailableWizards($this->PERMISSIONS);
        $this->set('wizards', $wizards);
        $this->viewBuilder()->setOption('serialize', ['wizards']);
    }

    /**
     * @param null $uuid
     */
    public function edit($uuid = null) {
        if (!$this->isAngularJsRequest()) {
            return;
        }
        if (!$uuid) {
            throw new NotFoundException(__('Invalid request parameters'));
        }

        /** @var WizardAssignmentsTable $WizardAssignmentsTable */
        $WizardAssignmentsTable = TableRegistry::getTableLocator()->get('WizardAssignments');

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            $wizard = $this->request->getData();

            if (!$WizardAssignmentsTable->existsByUuidAndTypeId($wizard['uuid'], $wizard['type_id'])) {
                $entity = $WizardAssignmentsTable->newEmptyEntity();
                $wizard = $WizardAssignmentsTable->patchEntity($entity, $wizard);
                $WizardAssignmentsTable->save($entity);

            } else {
                $existingAssignment = $WizardAssignmentsTable->getWizardByUuidForEdit($wizard['uuid']);
                $entity = $WizardAssignmentsTable->get($existingAssignment['id']);
                $entity = $WizardAssignmentsTable->patchEntity($entity, $wizard);
                $WizardAssignmentsTable->save($entity);
            }
            if ($entity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $wizard->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($entity); // REST API ID serialization
                    return;
                }
            }
            $this->set('wizardAssignments', $wizard);
            $this->viewBuilder()->setOption('serialize', ['wizardAssignments']);
        }

        $wizards = $WizardAssignmentsTable->getAvailableWizards($this->PERMISSIONS);
        $wizardAssignments = [];
        $wizard = Hash::extract($wizards, '{n}[uuid=' . $uuid . ']');
        if (!$wizard) {
            throw new NotFoundException(__('Wizard not found'));
        }
        $wizard = $wizard[0];
        if ($wizard['necessity_of_assignment'] === true) {
            if ($WizardAssignmentsTable->existsByUuidAndTypeId($wizard['uuid'], $wizard['type_id'])) {
                $wizardAssignments = Hash::merge($wizard, $WizardAssignmentsTable->getWizardByUuidForEdit($wizard['uuid']));
            } else {
                $wizardAssignments = $wizard;
                $wizardAssignments['servicetemplates']['_ids'] = [];
            }
        }

        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        $servicetemplates = $ServicetemplatesTable->getServicetemplatesByContainerId($this->MY_RIGHTS, 'list');
        $servicetemplates = Api::makeItJavaScriptAble($servicetemplates);

        $this->set('wizardAssignments', $wizardAssignments);
        $this->set('servicetemplates', $servicetemplates);
        $this->viewBuilder()->setOption('serialize', ['wizardAssignments', 'servicetemplates']);
    }

    public function hostConfiguration() {
        //Only ship HTML template
        return;
    }

    public function agent() {
        //Only ship HTML template
        return;
    }

    public function linuxserverssh() {
        //Only ship HTML template
        return;
    }

    public function mysqlserver() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }
        /** @var WizardAssignmentsTable $WizardAssignmentsTable */
        $WizardAssignmentsTable = TableRegistry::getTableLocator()->get('WizardAssignments');
        $wizards = $WizardAssignmentsTable->getAvailableWizards($this->PERMISSIONS);

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return mysql wizard data
            $servicetemplates = [];
            $wizardAssignments = [];
            $mysqlWizardData = Hash::extract($wizards, '{n}[type_id=mysql-server]');
            if ($mysqlWizardData) {
                $mysqlWizardData = $mysqlWizardData[0];
                if ($WizardAssignmentsTable->existsByUuidAndTypeId($mysqlWizardData['uuid'], $mysqlWizardData['type_id'])) {
                    $wizardAssignments = Hash::merge($mysqlWizardData, $WizardAssignmentsTable->getWizardByUuidForEdit($mysqlWizardData['uuid']));
                }
            }

            if (!empty($wizardAssignments['servicetemplates']['_ids'])) {
                /** @var ServicetemplatesTable $ServicetemplatesTable */
                $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
                $servicetemplates = $ServicetemplatesTable->getServicetemplatesFoWizardDeploy(
                    $wizardAssignments['servicetemplates']['_ids'],
                    $this->MY_RIGHTS
                );
            }

            $this->set('servicetemplates', $servicetemplates);

            $this->viewBuilder()->setOption('serialize', ['servicetemplates']);
            return;
        }
        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            /** @var HostsTable $HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

            $hostId = $this->request->getData('host_id', 0);
            if (!$HostsTable->existsById($hostId)) {
                throw new NotFoundException();
            }

            $host = $HostsTable->getHostByIdForPermissionCheck($hostId);
            if (!$this->allowedByContainerId($host->getContainerIds())) {
                $this->render403();
                return;
            }


            $WizardMysqlServerForm = new WizardMysqlServerForm();
            $data = $this->request->getData(null, []);
            $WizardMysqlServerForm->execute($data);

            if (!empty($WizardMysqlServerForm->getErrors())) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $WizardMysqlServerForm->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            //Wizard validation successfully - update host and create services
            $User = new User($this->getUser());

            /** @var HosttemplatesTable $HosttemplatesTable */
            $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');

            // Get host and host template for merge
            $host = $HostsTable->getHostForEdit($hostId);
            $hosttemplate = $HosttemplatesTable->getHosttemplateForDiff($host['Host']['hosttemplate_id']);

            //Update custom variables
            $hostCustomVariablestoCheck = [
                'MYSQL_USER'     => 'username',
                'MYSQL_PASSWORD' => 'password'
            ];

            //Update host with changelog
            $hostEntity = UpdateHost::save(
                $host,
                $hosttemplate,
                $data,
                $hostCustomVariablestoCheck,
                $HostsTable,
                $User
            );

            if ($hostEntity instanceof Host) {
                //Host updated successfully - create services

                /** @var ServicesTable $ServicesTable */
                $ServicesTable = TableRegistry::getTableLocator()->get('Services');
                /** @var ServicetemplatesTable $ServicetemplatesTable */
                $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

                //Add custom variables to services
                $serviceCustomVariablestoCheck = [
                    'MYSQL_DATABASE' => 'database'
                ];

                CreateService::saveMany(
                    $hostEntity,
                    $data['services'],
                    $data,
                    $serviceCustomVariablestoCheck,
                    $HostsTable,
                    $HosttemplatesTable,
                    $ServicesTable,
                    $ServicetemplatesTable,
                    $User
                );
            }
        }
        return;
    }

    public function wizardHostConfiguration() {
        //Only ship HTML template
        return;
    }

    public function validateInputFromAngular() {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $error = ['Host' => []];
        $data = $this->request->getData();
        if (!isset($data['Host']['id']) || is_null($data['Host']['id'])) {
            $error['Host']['id'] = __('This field cannot be left blank.');
        }

        if (!empty($error['Host'])) {
            $this->response = $this->response->withStatus(400);
            $this->set('success', false);
            $this->set('error', $error);
            $this->viewBuilder()->setOption('serialize', ['error', 'success']);
            return;
        }

        $this->set('success', true);
        $this->set('error', $error);
        $this->viewBuilder()->setOption('serialize', ['error', 'success']);
    }

    /****************************
     *       AJAX METHODS       *
     ****************************/

    public function loadServicetemplatesByWizardType() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        $type = $this->request->getQuery('type');
    }

    /**
     * @param $containerId
     * @throws \Exception
     */
    public function loadElementsByContainerId($containerId) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $hosttemplateType = GENERIC_HOST;

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');

        if (!$ContainersTable->existsById($containerId)) {
            throw new NotFoundException(__('Invalid container'));
        }

        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);

        $hosttemplates = $HosttemplatesTable->getHosttemplatesByContainerId($containerIds, 'list', $hosttemplateType);
        $hosttemplates = Api::makeItJavaScriptAble($hosttemplates);


        $exporters = [];
        if (Plugin::isLoaded('PrometheusModule')) {
            /** @var \PrometheusModule\Model\Table\PrometheusExportersTable $PrometheusExportersTable */
            $PrometheusExportersTable = TableRegistry::getTableLocator()->get('PrometheusModule.PrometheusExporters');

            $exporters = $PrometheusExportersTable->getExportersByContainerId($containerIds, 'list', 'id');
            $exporters = Api::makeItJavaScriptAble($exporters);
        }

        $this->set('hosttemplates', $hosttemplates);
        $this->set('exporters', $exporters);

        $this->viewBuilder()->setOption('serialize', [
            'hosttemplates',
            'exporters'
        ]);
    }
}
