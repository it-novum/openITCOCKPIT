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

use App\Model\Table\ContainersTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\HostFilter;
use itnovum\openITCOCKPIT\Filter\HostgroupFilter;
use itnovum\openITCOCKPIT\Filter\ServiceFilter;
use itnovum\openITCOCKPIT\Filter\StatuspagesFilter;
use App\Model\Table\StatuspagesTable;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;

use itnovum\openITCOCKPIT\Core\HostConditions;
use itnovum\openITCOCKPIT\Core\HostgroupConditions;
use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\Views\UserTime;


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
                    //statuspage has no containers where user has no edit permission
                    $all_statuspages[$key]['allow_edit'] = true;
                }
            }

            $all_statuspages[$key]['allow_view'] = true;
            if ($all_statuspages[$key]['public'] === false) {
                if ($this->hasRootPrivileges === false) {
                    $all_statuspages[$key]['allow_view'] = false;
                    if (empty(array_diff($statuspagesWithContainers[$statuspage['id']], $this->MY_RIGHTS))) {
                        //statuspage has no containers where user has no edit permission
                        $all_statuspages[$key]['allow_view'] = true;
                    }
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
        $statuspage = $this->Statuspages->get($id, [
            'contain' => ['Containers', 'StatuspageItems'],
        ]);

        $this->set(compact('statuspage'));
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
        if (($this->request->is('post') || $this->request->is('put')) && isset($data['Statuspage'])) {
            $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');

            $statuspage = $StatuspagesTable->newEmptyEntity();
            $statuspage = $StatuspagesTable->patchEntity($statuspage, $data['Statuspage']);
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
           /* $statuspage = $StatuspagesTable->get($statuspage->id, [
                'contain' => [
                    'Containers',
                    'Hosts',
                    'Services',
                    'Hostgroups',
                    'Servicegroups'
                ]
            ]); */
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
            throw new NotFoundException(__('Invalid Statuspage'));
        }

        $statuspage = $this->Statuspages->get($id, [
            'contain' => [
                'Containers',
                'StatuspageItems'
            ],
        ]);
        $statuspage['containers'] = [
            '_ids' => Hash::extract($statuspage, 'containers.{n}.id')
        ];
        if ($this->request->is(['patch', 'post', 'put']) ) {
            $data = $this->request->getData();
            $statuspage = $StatuspagesTable->patchEntity($statuspage, $data['Statuspage']);
            if ($StatuspagesTable->save($statuspage)) {
               // $this->Flash->success(__('The statuspage has been saved.'));

                //return $this->redirect(['action' => 'index']);
            }
           // $this->Flash->error(__('The statuspage could not be saved. Please, try again.'));
        }
        $this->set('statuspage', $statuspage);
        $this->viewBuilder()->setOption('serialize', ['statuspage']);
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
            $this->Flash->success(__('The statuspage has been deleted.'));
        } else {
            $this->Flash->error(__('The statuspage could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    /**
     *
     * edit items
     *
     * @param string|null $id Statuspage id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
   /* public function items($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');
        if (!$StatuspagesTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Statuspage'));
        }

        $statuspage = $this->Statuspages->get($id, [
            'contain' => [
                'Containers',
                'StatuspageItems'
            ],
        ]);
        $statuspage['containers'] = [
            '_ids' => Hash::extract($statuspage, 'containers.{n}.id')
        ];
    } */

    public function loadContainers() {
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

    public function loadHostsByContainerIds() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerIds = $this->request->getQuery('containerIds');
        $selected = $this->request->getQuery('selected');

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        $HostFilter = new HostFilter($this->request);

        $containerIds = array_merge($containerIds, [ROOT_CONTAINER]);
        $containerIds = array_unique($containerIds);

        $HostCondition = new HostConditions($HostFilter->ajaxFilter());
        $HostCondition->setContainerIds($containerIds);

        $hosts = Api::makeItJavaScriptAble(
            $HostsTable->getHostsForAngular($HostCondition, $selected)
        );

        $this->set('hosts', $hosts);
        $this->viewBuilder()->setOption('serialize', ['hosts']);
    }

    public function loadServicesByContainerIds() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->getQuery('selected');
        $containerIds = $this->request->getQuery('containerIds');

        $ServiceFilter = new ServiceFilter($this->request);
        $containerIds = array_merge($containerIds, [ROOT_CONTAINER]);
        $containerIds = array_unique($containerIds);


        // as $ServiceCondition->setIncludeDisabled(false); has no function at all we need to exclude the disabled services here
        $serviceConditions = ['Services.disabled' => 0];
        if(!empty($ServiceFilter->indexFilter())){
            $serviceConditions = array_merge($ServiceFilter->indexFilter(), $serviceConditions);
        }
        $ServiceCondition = new ServiceConditions($serviceConditions);
        $ServiceCondition->setContainerIds($containerIds);
        //$ServiceCondition->setIncludeDisabled(false); // this has no function

        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $services = Api::makeItJavaScriptAble(
            $ServicesTable->getServicesForAngular($ServiceCondition, $selected)
        );

        $this->set('services', $services);
        $this->viewBuilder()->setOption('serialize', ['services']);
    }
    public function loadServicegroupsByContainerIds() {}
    public function loadHostgroupsByContainerIds() {}

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
                'validate' => 'stepTwo'
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
