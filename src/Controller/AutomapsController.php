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

use App\Lib\Exceptions\MissingDbBackendException;
use App\Lib\Interfaces\ServicestatusTableInterface;
use App\Model\Entity\Automap;
use App\Model\Table\AutomapsTable;
use App\Model\Table\ContainersTable;
use App\Model\Table\HostsTable;
use App\Model\Table\ServicesTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Exception;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\HostConditions;
use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\Service;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\AutomapsFilter;
use itnovum\openITCOCKPIT\Filter\HostFilter;
use itnovum\openITCOCKPIT\Filter\ServiceFilter;

/**
 * Class AutomapsController
 * @package App\Controller
 */
class AutomapsController extends AppController {

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var AutomapsTable $AutomapsTable */
        $AutomapsTable = TableRegistry::getTableLocator()->get('Automaps');

        $AutomapsFilter = new AutomapsFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $AutomapsFilter->getPage());

        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }
        $automaps = $AutomapsTable->getAutomapsIndex($AutomapsFilter, $PaginateOMat, $MY_RIGHTS);
        $all_automaps = [];

        foreach ($automaps as $automap) {
            /** @var Automap $automap */
            $automap = $automap->toArray();
            $automap['allow_edit'] = $this->hasRootPrivileges;

            if ($this->hasRootPrivileges === true) {
                $automap['allow_edit'] = $this->isWritableContainer($automap['container']['id']);
            }

            $all_automaps[] = $automap;
        }

        $this->set('all_automaps', $all_automaps);
        $toJson = ['all_automaps', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_automaps', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $AutomapsTable AutomapsTable */
        $AutomapsTable = TableRegistry::getTableLocator()->get('Automaps');

        if ($this->request->is('post') || $this->request->is('put')) {
            $automap = $AutomapsTable->newEmptyEntity();
            $automap = $AutomapsTable->patchEntity($automap, $this->request->getData('Automap', []));
            $AutomapsTable->save($automap);
            if ($automap->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $automap->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            $this->set('automap', $automap);
            $this->viewBuilder()->setOption('serialize', ['automap']);
        }
    }

    /**
     * @param int|null $id
     * @throws Exception
     */
    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var AutomapsTable $AutomapsTable */
        $AutomapsTable = TableRegistry::getTableLocator()->get('Automaps');

        if (!$AutomapsTable->existsById($id)) {
            throw new NotFoundException(__('Automap not found'));
        }

        $automap = $AutomapsTable->get($id);

        if (!$this->allowedByContainerId($automap->get('container_id'), true)) {
            $this->render403();
            return;
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return contact information
            $automap = $automap->toArray();
            $toIntFields = [
                'recursive',
                'show_ok',
                'show_warning',
                'show_critical',
                'show_unknown',
                'show_acknowledged',
                'show_downtime',
                'show_label',
                'group_by_host',
                'use_paginator'
            ];
            foreach ($toIntFields as $intField) {
                $automap[$intField] = (int)$automap[$intField];
            }

            $this->set('automap', $automap);
            $this->viewBuilder()->setOption('serialize', ['automap']);
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            //Update automap data
            $automap = $AutomapsTable->patchEntity($automap, $this->request->getData('Automap', []));
            $automap->id = $id;
            $AutomapsTable->save($automap);
            if ($automap->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $automap->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }
            $this->set('automap', $automap);
            $this->viewBuilder()->setOption('serialize', ['automap']);
        }
    }

    /**
     * @param int|null $id
     * @throws MissingDbBackendException
     */
    public function view($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        $fontSizes = [
            1 => 'xx-small',
            2 => 'x-small',
            3 => 'small',
            4 => 'medium',
            5 => 'large',
            6 => 'x-large',
            7 => 'xx-large',
        ];

        /** @var $AutomapsTable AutomapsTable */
        $AutomapsTable = TableRegistry::getTableLocator()->get('Automaps');

        if (!$AutomapsTable->existsById($id)) {
            throw new NotFoundException(__('Automap not found'));
        }

        $automap = $AutomapsTable->get($id);

        if (!$this->allowedByContainerId($automap->get('container_id'), false)) {
            $this->render403();
            return;
        }
        $automap = $automap->toArray();
        $automap['font_size_html'] = $fontSizes[$automap['font_size']];

        $automap['allow_edit'] = $this->hasRootPrivileges;
        if ($this->hasRootPrivileges === true) {
            $automap['allow_edit'] = $this->isWritableContainer($automap['container_id']);
        }

        // Query host and services
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var $ServicestatusTable ServicestatusTableInterface */
        $ServicestatusTable = $this->DbBackend->getServicestatusTable();

        $containerIds = [
            $automap['container_id']
        ];
        if ($automap['recursive']) {
            if ($automap['container_id'] == ROOT_CONTAINER) {
                $containerIds = $this->MY_RIGHTS;
            } else {
                $tmpContainerIds = $ContainersTable->resolveChildrenOfContainerIds($automap['container_id'], false);
                $containerIds = $ContainersTable->removeRootContainer($tmpContainerIds);
            }
        }

        $ServicesConditions = new ServiceConditions();
        $ServicesConditions->setContainerIds($containerIds);
        $ServicesConditions->setHostnameRegex($automap['host_regex']);
        $ServicesConditions->setServicenameRegex($automap['service_regex']);

        $ServiceFilter = new ServiceFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $ServiceFilter->getPage());

        if ($automap['use_paginator'] === false) {
            $PaginateOMat = null;
        }

        try {
            $services = $ServicesTable->getServicesByRegularExpression($ServicesConditions, $PaginateOMat, 'all');
        } catch (Exception $e) {
            $services = null;
        }
        if (!is_array($services)) {
            $services = [];
        }
        $servicesByHost = [];
        $serviceUuids = Hash::extract($services, '{n}.uuid');

        // Load service status information
        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusConditions = new ServicestatusConditions($this->DbBackend);
        $ServicestatusFields
            ->currentState()
            ->problemHasBeenAcknowledged()
            ->scheduledDowntimeDepth();

        $current_stateConditions = [];
        $state_types = [
            'show_unknown'  => 3,
            'show_critical' => 2,
            'show_warning'  => 1,
            'show_ok'       => 0,
        ];
        foreach ($state_types as $stateName => $stateNumber) {
            if ($automap[$stateName]) {
                $current_stateConditions[] = $stateNumber;
            }
        }
        if (sizeof($current_stateConditions) < 4) {
            $ServicestatusConditions->currentState($current_stateConditions);
        }

        if ($automap['show_acknowledged'] === false) {
            $ServicestatusConditions->setProblemHasBeenAcknowledged(0);
        }

        if ($automap['show_downtime'] === false) {
            $ServicestatusConditions->setScheduledDowntimeDepth(0, false);
        }

        $servicestatus = $ServicestatusTable->byUuids($serviceUuids, $ServicestatusFields, $ServicestatusConditions);

        foreach ($services as $service) {
            /** @var \App\Model\Entity\Service $Service */
            $service = $service->toArray();

            if (!isset($servicestatus[$service['uuid']])) {
                //No service status for given service - may be filtered?
                continue;
            }

            $Service = new Service($service);
            $Host = new Host($service['_matchingData']['Hosts']);
            $Servicestatus = new Servicestatus($servicestatus[$Service->getUuid()]['Servicestatus']);

            if (!isset($servicesByHost[$Host->getId()])) {
                $servicesByHost[$Host->getId()] = [
                    'host'     => $Host->toArray(),
                    'services' => []
                ];
            }

            $servicesByHost[$Host->getId()]['services'][] = [
                'service'       => $Service->toArray(),
                'servicestatus' => $Servicestatus->toArray()
            ];
        }

        //Drop keys to make in a array [] for javascript - not a hash {} !
        $servicesByHost = array_values($servicesByHost);


        $this->set('automap', $automap);
        $this->set('servicesByHost', $servicesByHost);
        $toJson = ['automap', 'servicesByHost'];
        if ($this->isScrollRequest()) {
            $toJson[] = 'scroll';
        } else {
            $toJson[] = 'paging';
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    /**
     * @param int|null $id
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $AutomapsTable AutomapsTable */
        $AutomapsTable = TableRegistry::getTableLocator()->get('Automaps');

        if (!$AutomapsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Automap'));
        }

        $automap = $AutomapsTable->get($id);

        if (!$this->allowedByContainerId($automap->get('container_id'), true)) {
            $this->render403();
            return;
        }

        if (!$AutomapsTable->delete($automap)) {
            $this->response = $this->response->withStatus(400);
            $this->set('success', false);
            $this->set('id', $id);
            $this->viewBuilder()->setOption('serialize', ['success', 'id']);
            return;
        }

        $this->set('success', true);
        $this->set('id', $id);
        $this->viewBuilder()->setOption('serialize', ['success', 'id']);
    }

    public function icon() {
        //Only ship HTML Template
        return;
    }

    /****************************
     *       AJAX METHODS       *
     ****************************/

    public function loadContainers() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        $containers = Api::makeItJavaScriptAble($containers);

        $this->set('containers', $containers);
        $this->viewBuilder()->setOption('serialize', ['containers']);
    }

    public function getMatchingHostAndServices() {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        $defaults = [
            'container_id'  => 0,
            'recursive'     => 0,
            'host_regex'    => '',
            'service_regex' => ''
        ];

        $hostCount = 0;
        $serviceCount = 0;

        $post = $this->request->getData('Automap', []);
        $post = Hash::merge($defaults, $post);

        if ($post['container_id'] > 0) {
            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
            /** @var $HostsTable HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
            /** @var $ServicesTable ServicesTable */
            $ServicesTable = TableRegistry::getTableLocator()->get('Services');

            $containerIds = [
                $post['container_id']
            ];

            if ($post['recursive']) {
                if ($post['container_id'] == ROOT_CONTAINER) {
                    $containerIds = $this->MY_RIGHTS;
                } else {
                    $tmpContainerIds = $ContainersTable->resolveChildrenOfContainerIds($post['container_id'], false);
                    $containerIds = $ContainersTable->removeRootContainer($tmpContainerIds);
                }
            }

            $HostConditions = new HostConditions();
            $HostConditions->setContainerIds($containerIds);
            $HostConditions->setHostnameRegex($post['host_regex']);
            $HostFilter = new HostFilter($this->request); //Only used for order right now

            if ($post['host_regex'] != '') {
                try {
                    $hostCount = $HostsTable->getHostsByRegularExpression($HostFilter, $HostConditions, null, 'count');
                } catch (Exception $e) {
                    $hostCount = 0;
                }
            }

            $ServicesConditions = new ServiceConditions();
            $ServicesConditions->setContainerIds($containerIds);
            $ServicesConditions->setHostnameRegex($post['host_regex']);
            $ServicesConditions->setServicenameRegex($post['service_regex']);
            if ($post['service_regex'] != '') {
                try {
                    $serviceCount = $ServicesTable->getServicesByRegularExpression($ServicesConditions, null, 'count');
                } catch (Exception $e) {
                    $serviceCount = 0;
                }
            }
        }


        $this->set('hostCount', $hostCount);
        $this->set('serviceCount', $serviceCount);
        $this->viewBuilder()->setOption('serialize', ['hostCount', 'serviceCount']);
    }

}
