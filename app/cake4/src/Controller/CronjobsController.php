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

use App\Model\Table\CronjobsTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\UserTime;

class CronjobsController extends AppController {

    public function index() {
        if (!$this->isApiRequest()) {
            return;
        }

        /** @var CronjobsTable $CronjobsTable */
        $CronjobsTable = TableRegistry::getTableLocator()->get('Cronjobs');
        $cronjobs = $CronjobsTable->getCronjobs();

        //add start_time as last_scheduled_usertime in usertime format
        foreach ($cronjobs as $key => $cronjob) {
            if (isset($cronjob['Cronschedule'])) {
                $cronjobs[$key]['Cronschedule']['last_scheduled_usertime'] = null;
                if (!empty($cronjob['Cronschedule']['start_time'])) {
                    $User = new User($this->getUser());
                    $UserTime = $User->getUserTime();
                    $cronjobs[$key]['Cronschedule']['last_scheduled_usertime'] = $UserTime->format($cronjob['Cronschedule']['start_time']);
                }
            }
        }
        $this->set(compact('cronjobs'));
        $this->viewBuilder()->setOption('serialize', ['cronjobs']);
    }

    public function getPlugins() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        /** @var CronjobsTable $CronjobsTable */
        $CronjobsTable = TableRegistry::getTableLocator()->get('Cronjobs');
        $include = $this->request->getQuery('include');
        $plugins = array_values($CronjobsTable->fetchPlugins());
        if ($include !== '') {
            $plugins[] = $include;
        }

        $this->set(compact('plugins'));
        $this->viewBuilder()->setOption('serialize', ['plugins']);
    }

    public function getTasks() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        /** @var CronjobsTable $CronjobsTable */
        $CronjobsTable = TableRegistry::getTableLocator()->get('Cronjobs');
        $include = $this->request->getQuery('include');
        $pluginName = 'Core';
        if ($this->request->getQuery('pluginName') != null || $this->request->getQuery('pluginName') != '') {
            $pluginName = $this->request->getQuery('pluginName');
        }

        $coreTasks = $CronjobsTable->fetchTasks($pluginName);
        if ($include !== '') {
            $coreTasks[] = $include;
        }

        $this->set(compact('coreTasks'));
        $this->viewBuilder()->setOption('serialize', ['coreTasks']);
    }

    public function add() {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        /** @var CronjobsTable $CronjobsTable */
        $CronjobsTable = TableRegistry::getTableLocator()->get('Cronjobs');
        $data = $this->request->data['Cronjob'];
        $cronjob = $CronjobsTable->newEmptyEntity();
        $cronjob = $CronjobsTable->patchEntity($cronjob, $data);
        $CronjobsTable->save($cronjob);

        if ($cronjob->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $cronjob->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $this->set('cronjob', $cronjob);
        $this->viewBuilder()->setOption('serialize', ['cronjob']);
    }

    public function edit($id = null) {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var CronjobsTable $CronjobsTable */
        $CronjobsTable = TableRegistry::getTableLocator()->get('Cronjobs');
        $data = $this->request->data['Cronjob'];
        $cronjob = $CronjobsTable->get($id);
        $cronjob = $CronjobsTable->patchEntity($cronjob, $data);
        $CronjobsTable->save($cronjob);

        if ($cronjob->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $cronjob->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $this->set('cronjob', $cronjob);
        $this->viewBuilder()->setOption('serialize', ['cronjob']);
    }

    public function delete($id = null) {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var CronjobsTable $CronjobsTable */
        $CronjobsTable = TableRegistry::getTableLocator()->get('Cronjobs');
        $cronjob = $CronjobsTable->get($id);

        if (empty($cronjob)) {
            throw new NotFoundException(__('Invalid cronjob'));
        }

        $CronjobsTable->delete($cronjob);
        if ($cronjob->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $cronjob->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $this->set('cronjob', $cronjob);
        $this->viewBuilder()->setOption('serialize', ['cronjob']);
    }
}
