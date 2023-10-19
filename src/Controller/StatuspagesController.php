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

use App\Lib\Interfaces\HoststatusTableInterface;
use App\Lib\Interfaces\ServicestatusTableInterface;
use App\Model\Entity\Host;
use App\Model\Entity\Service;
use App\Model\Table\ContainersTable;
use App\Model\Table\ServicesTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\HoststatusFields;

use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\HostFilter;
use itnovum\openITCOCKPIT\Filter\HostgroupFilter;
use itnovum\openITCOCKPIT\Filter\ServiceFilter;
use itnovum\openITCOCKPIT\Filter\StatuspagesFilter;
use App\Model\Table\StatuspagesTable;
use itnovum\openITCOCKPIT\Core\HostConditions;
use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\HostgroupConditions;


/**
 * Statuspages Controller
 *
 * @property StatuspagesTable $Statuspages
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StatuspagesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $StatuspagesTable StatuspagesTable */
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');

        $statuspagesFilter = new StatuspagesFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $statuspagesFilter->getPage());
        $all_statuspages = $StatuspagesTable->getStatuspagesIndex($statuspagesFilter, $PaginateOMat, $this->MY_RIGHTS);
        $statuspagesWithContainers = [];
        foreach ($all_statuspages as $key => $statuspage) {
            $statuspagesWithContainers[$statuspage['id']] = [];
            foreach ($statuspage['containers'] as $container) {
                $statuspagesWithContainers[$statuspage['id']][] = $container['id'];
            }

            $all_statuspages[$key]['allow_edit'] = true;
            if ($this->hasRootPrivileges === false) {
                $all_statuspages[$key]['allow_edit'] = false;
                if (empty(array_diff($statuspagesWithContainers[$statuspage['id']], $this->getWriteContainers()))) {
                    $all_statuspages[$key]['allow_edit'] = true;
                }
            }

        }

        $this->set('all_statuspages', $all_statuspages);
        $this->viewBuilder()->setOption('serialize', ['all_statuspages']);
    }

    /**
     * View method
     *
     * @param string|null $id Statuspage id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }
        if(empty($id)){
            throw new NotFoundException('Statuspage not found');
        }
        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();

        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');
        if (!$StatuspagesTable->existsById($id)) {
            throw new NotFoundException('Statuspage not found');
        }
        //$UserTime = new UserTime(date_default_timezone_get(), 'd.m.Y H:i:s');
        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();
        $statuspageViewData = $StatuspagesTable->getStatuspageView( $id, $UserTime);

        $this->set('Statuspage', $statuspageViewData);
        $this->viewBuilder()->setOption('serialize', ['Statuspage']);
    }

    public function public ($id = null) {
        if(empty($id)){
            throw new NotFoundException('Statuspage not found');
        }
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');
        if (!$StatuspagesTable->existsById($id)) {
            throw new NotFoundException('Statuspage not found');
        }
        if(!$StatuspagesTable->isPublic($id)){
            throw new  MethodNotAllowedException('Statuspage not public');
        }
        $UserTime = new UserTime(date_default_timezone_get(), 'd.m.Y H:i:s');
        $statuspageViewData = $StatuspagesTable->getStatuspageView( $id, $UserTime);

        $this->set('Statuspage', $statuspageViewData);
        $this->viewBuilder()->setOption('serialize', ['Statuspage']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        $data = $this->request->getData();
        if (($this->request->is('post') || $this->request->is('put')) && isset($data)) {
            $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');
            $statuspage = $StatuspagesTable->newEmptyEntity();
            $statuspage = $StatuspagesTable->patchEntity($statuspage, $data);
            $StatuspagesTable->save($statuspage);
            if ($statuspage->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $statuspage->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($statuspage); // REST API ID serialization
                    return;
                }

            }
            $this->set('statuspage', $statuspage);
            $this->viewBuilder()->setOption('serialize', ['statuspage']);

        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Statuspage id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');
        if (!$StatuspagesTable->existsById($id)) {
            throw new NotFoundException('Statuspage not found');
        }

        $statuspage = $StatuspagesTable->get($id, [
            'contain' => [
                'Containers',
                'Hosts',
                'Services',
                'Hostgroups',
                'Servicegroups'
            ]
        ]);


        $statuspage['containers'] = [
            '_ids' => Hash::extract($statuspage, 'containers.{n}.id')
        ];

        $statuspage['hosts'] = [
            '_ids' => Hash::extract($statuspage, 'hosts.{n}.id')
        ];

        $statuspage['hostgroups'] = [
            '_ids' => Hash::extract($statuspage, 'hostgroups.{n}.id')
        ];

        $statuspage['services'] = [
            '_ids' => Hash::extract($statuspage, 'services.{n}.id')
        ];

        $statuspage['servicegroups'] = [
            '_ids' => Hash::extract($statuspage, 'servicegroups.{n}.id')
        ];


        if ($this->request->is('post')) {
            $statuspageData = $this->request->getData();
            $statuspage = $StatuspagesTable->patchEntity($statuspage, $statuspageData['Statuspage']);
            $StatuspagesTable->save($statuspage);
            if ($statuspage->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $statuspage->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($statuspage); // REST API ID serialization
                    return;
                }
            }

        }

        $this->set('Statuspage', $statuspage);
        $this->viewBuilder()->setOption('serialize', ['Statuspage']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Statuspage id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $statuspage = $this->Statuspages->get($id);

        if ($this->Statuspages->delete($statuspage)) {
            $this->set('success', true);
            $this->set('id', $id);
            $this->viewBuilder()->setOption('serialize', ['success', 'id']);

        } else {
            $this->response = $this->response->withStatus(400);
            $this->set('success', false);
            $this->set('id', $id);
            $this->viewBuilder()->setOption('serialize', ['success', 'id']);
        }
    }

    /**
     * @return void
     */
    public function loadContainers()
    {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var ContainersTable $ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, CT_TENANT, [], $this->hasRootPrivileges);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), CT_TENANT, [], true);
        }
        $containers = Api::makeItJavaScriptAble($containers);


        $this->set('containers', $containers);
        $this->viewBuilder()->setOption('serialize', ['containers']);
    }

    /**
     * @return void
     */
    public function loadHostsByContainerIds()
    {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerIds = $this->request->getQuery('containerIds');
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        foreach ($containerIds as $containerId) {
            $subIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);
            $containerIds = array_merge($containerIds, $subIds);
        }
        $containerIds = array_unique($containerIds);

        /*  if (!in_array(ROOT_CONTAINER, $containerIds)){
               $containerIds = array_merge($containerIds, [ROOT_CONTAINER]);
           } */
        $selected = $this->request->getQuery('selected');

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        $HostFilter = new HostFilter($this->request);
        $HostConditions = $HostFilter->ajaxFilter();
        $HostCondition = new HostConditions($HostConditions);
        $HostCondition->setContainerIds($containerIds);

        $hosts = Api::makeItJavaScriptAble(
            $HostsTable->getHostsForAngular($HostCondition, $selected)
        );

        $this->set('hosts', $hosts);
        $this->viewBuilder()->setOption('serialize', ['hosts']);
    }

    /**
     * @return void
     */
    public function loadServicesByContainerIds()
    {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->getQuery('selected');
        $containerIds = $this->request->getQuery('containerIds');
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        foreach ($containerIds as $containerId) {
            $subIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);
            $containerIds = array_merge($containerIds, $subIds);
        }
        $containerIds = array_unique($containerIds);
        /* if (!in_array(ROOT_CONTAINER, $containerIds)){
             $containerIds = array_merge($containerIds, [ROOT_CONTAINER]);
         } */
        $ServiceFilter = new ServiceFilter($this->request);


        // $serviceConditions = ['Services.disabled' => 0];
        //if(!empty($ServiceFilter->indexFilter())){
        $serviceConditions = $ServiceFilter->indexFilter();
        //}
        $ServiceCondition = new ServiceConditions($serviceConditions);
        $ServiceCondition->setContainerIds($containerIds);

        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $services = Api::makeItJavaScriptAble(
            $ServicesTable->getServicesForAngular($ServiceCondition, $selected)
        );

        $this->set('services', $services);
        $this->viewBuilder()->setOption('serialize', ['services']);
    }

    /**
     * @return void
     */
    public function loadServicegroupsByContainerIds()
    {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

        $containerIds = $this->request->getQuery('containerIds');
        if (!in_array(ROOT_CONTAINER, $containerIds)) {
            $containerIds = array_merge($containerIds, [ROOT_CONTAINER]);
        }

        $servicegroups = $ServicegroupsTable->getServicegroupsByContainerId($containerIds, 'list');
        $servicegroups = Api::makeItJavaScriptAble($servicegroups);

        $this->set('servicegroups', $servicegroups);
        $this->viewBuilder()->setOption('serialize', ['servicegroups']);
    }

    /**
     * @return void
     */
    public function loadHostgroupsByContainerIds()
    {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerIds = $this->request->getQuery('containerIds');
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        foreach ($containerIds as $containerId) {
            $subIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);
            $containerIds = array_merge($containerIds, $subIds);
        }
        $containerIds = array_unique($containerIds);
        $HostgroupFilter = new HostgroupFilter($this->request);

        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');


        $HostgroupCondition = new HostgroupConditions($HostgroupFilter->indexFilter());
        $HostgroupCondition->setContainerIds($containerIds);

        $hostgroups = $HostgroupsTable->getHostgroupsByContainerIdNew($HostgroupCondition);
        $hostgroups = Api::makeItJavaScriptAble($hostgroups);

        $this->set('hostgroups', $hostgroups);
        $this->viewBuilder()->setOption('serialize', ['hostgroups']);
    }

    /**
     *
     * edit items
     *
     * @param string|null $id Statuspage id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function setAlias($id = null)
    {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $StatuspagesTable StatuspagesTable */
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');
        if (!$StatuspagesTable->existsById($id)) {
            throw new NotFoundException('Statuspage not found');
        }
        $statuspage = $StatuspagesTable->getStatuspageObjects($id);

        if ($this->request->is('post')) {
            $statuspage = $StatuspagesTable->get($id, [
                'contain' => [
                    'Containers',
                    'Hosts',
                    'Services',
                    'Hostgroups',
                    'Servicegroups'
                ]
            ]);
            $statuspageData = $this->request->getData()['Statuspage'];
            $statuspage = $StatuspagesTable->patchEntity($statuspage, $statuspageData, [
                'validate' => 'alias'
            ]);
            $StatuspagesTable->save($statuspage);
            if ($statuspage->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $statuspage->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($statuspage); // REST API ID serialization
                    return;
                }
            }
        }

        $this->set('Statuspage', $statuspage);
        $this->viewBuilder()->setOption('serialize', ['Statuspage']);

    }


}
