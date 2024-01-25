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
use App\Model\Table\StatuspagesTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\StatuspagesFilter;
use Cake\Event\EventInterface;


class StatuspagesController extends AppController {

    //https://discourse.cakephp.org/t/bypass-authentication/9197/3
    //a condition in src/Policy/RequestPolicy->canAccess is alsonecessary here
    public function beforeFilter(EventInterface $event) {
        parent::beforeFilter($event);
        //$this->Authentication->addUnauthenticatedActions(['publicView']);
        $this->Authentication->allowUnauthenticated(['publicView']);
    }


    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index($withState = false) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();

        /** @var StatuspagesTable $StatuspagesTable */
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
            if ($withState) {
                $statuspageViewData = $StatuspagesTable->getStatuspageForView((int)$statuspage['id'], $this->MY_RIGHTS, $UserTime);
                $all_statuspages[$key]['cumulatedState'] = $statuspageViewData['statuspage']['cumulatedColorId'];
                $all_statuspages[$key]['color'] = 'bg-' . $statuspageViewData['statuspage']['cumulatedColor'];
                $all_statuspages[$key]['css_color'] = $statuspageViewData['statuspage']['cumulatedColor'];
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
     * @throws \Cake\Http\Exception\NotFoundException; When record not found.
     */
    public function view($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        $id = (int)$id;

        /** @var StatuspagesTable $StatuspagesTable */
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');
        if (!$StatuspagesTable->existsById($id)) {
            throw new NotFoundException('Statuspage not found');
        }
        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();

        $statuspageViewData = $StatuspagesTable->getStatuspageForView($id, $this->MY_RIGHTS, $UserTime, true);
        $this->set('Statuspage', $statuspageViewData);
        $this->viewBuilder()->setOption('serialize', ['Statuspage']);
    }


    /**
     * Public View method
     *
     * @param string|null $id Statuspage id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Http\Exception\NotFoundException
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     */
    public function publicView($id = null) {
        if (empty($id)) {
            throw new NotFoundException('Statuspage not found');
        }

        /** @var StatuspagesTable $StatuspagesTable */
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');
        if (!$StatuspagesTable->existsById($id)) {
            throw new NotFoundException('Statuspage not found');
        }
        if (!$StatuspagesTable->isPublic($id)) {
            // We don't want to be too honest at this point so that it is not possible to bruteforce existing Statuspages
            // GitHub does this the same way, if you are not logged in you get a Not found error on private repisitories.
            throw new NotFoundException('Statuspage not found');
        }
        $this->viewBuilder()->setLayout('statuspage_public');
        $UserTime = new UserTime(date_default_timezone_get(), 'd.m.Y H:i:s');
        $statuspageViewData = $StatuspagesTable->getStatuspageForView((int)$id, [], $UserTime, true);
        $this->set('statuspage', $statuspageViewData);
        $this->set('systemname', $this->getSystemname());
        $this->set('id', $id);
        $this->viewBuilder()->setOption('serialize', ['Statuspage']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        $data = $this->request->getData();
        if (($this->request->is('post') || $this->request->is('put')) && isset($data)) {

            /** @var StatuspagesTable $StatuspagesTable */
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
                    $this->serializeCake4Id($statuspage);
                    return;
                }

            }
            $this->set('statuspage', $statuspage);
            $this->viewBuilder()->setOption('serialize', ['statuspage']);

        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Statuspage id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var StatuspagesTable $StatuspagesTable */
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');
        if (!$StatuspagesTable->existsById($id)) {
            throw new NotFoundException('Statuspage not found');
        }

        $statuspage = $StatuspagesTable->get($id, [
            'contain' => [
                'Containers'
            ]
        ]);


        if (!$this->isWritableContainer($statuspage['containers'][0]['id'])) {
            $this->render403();
            return;
        }

        if ($StatuspagesTable->delete($statuspage)) {
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
     * @throws \Exception
     */
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

    /**
     *
     * edit
     *
     * @param string|null $id Statuspage id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }
        /** @var StatuspagesTable $StatuspagesTable */
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');
        if (!$StatuspagesTable->existsById($id)) {
            throw new NotFoundException('Statuspage not found');
        }
        $statuspage = $StatuspagesTable->getStatuspageWithAllObjects((int)$id, $this->MY_RIGHTS);

        if ($this->request->is('post')) {
            $statuspage = $StatuspagesTable->get($id, [
                'contain' => [
                    'Containers', 'Hosts', 'Services', 'Hostgroups', 'Servicegroups'
                ]
            ]);
            if (!$this->isWritableContainer($statuspage['containers'][0]['id'])) {
                $this->render403();
                return;
            }

            $statuspageData = $this->request->getData();
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
