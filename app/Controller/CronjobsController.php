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

use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Views\UserTime;

class CronjobsController extends AppController {
    public $layout = 'blank';

    public function index() {
        if (!$this->isApiRequest()) {
            return;
        }

        /** @var $Cronjobs App\Model\Table\CronjobsTable */
        $Cronjobs = TableRegistry::getTableLocator()->get('Cronjobs');
        $cronjobs = $Cronjobs->getCronjobs();

        //add start_time as last_scheduled_usertime in usertime format
        foreach ($cronjobs as $key => $cronjob){
            if(isset($cronjob['Cronschedule'])){
                $cronjobs[$key]['Cronschedule']['last_scheduled_usertime'] = null;
                if(!empty($cronjob['Cronschedule']['start_time'])){
                    $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
                    $UserTime = $UserTime->format($cronjob['Cronschedule']['start_time']);
                    $cronjobs[$key]['Cronschedule']['last_scheduled_usertime'] = $UserTime;
                }
            }
        }
        $this->set(compact('cronjobs'));
        $this->set('_serialize', ['cronjobs']);
    }

    public function getPlugins() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        /** @var $Cronjobs App\Model\Table\CronjobsTable */
        $Cronjobs = TableRegistry::getTableLocator()->get('Cronjobs');
        $include = $this->request->query('include');
        $plugins = array_values($Cronjobs->fetchPlugins());
        if ($include !== '') {
            $plugins[] = $include;
        }

        $this->set(compact('plugins'));
        $this->set('_serialize', ['plugins']);
    }

    public function getTasks() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        /** @var $Cronjobs App\Model\Table\CronjobsTable */
        $Cronjobs = TableRegistry::getTableLocator()->get('Cronjobs');
        $include = $this->request->query('include');
        $pluginName = 'Core';
        if ($this->request->query('pluginName') != null || $this->request->query('pluginName') != '') {
            $pluginName = $this->request->query('pluginName');
        }

        $coreTasks = $Cronjobs->fetchTasks($pluginName);
        if ($include !== '') {
            $coreTasks[] = $include;
        }

        $this->set(compact('coreTasks'));
        $this->set('_serialize', ['coreTasks']);
    }

    public function add() {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        /** @var $Cronjobs App\Model\Table\CronjobsTable */
        $Cronjobs = TableRegistry::getTableLocator()->get('Cronjobs');
        $data = $this->request->data['Cronjob'];
        $cronjob = $Cronjobs->newEmptyEntity();
        $cronjob = $Cronjobs->patchEntity($cronjob, $data);
        $Cronjobs->save($cronjob);

        if ($cronjob->hasErrors()) {
            $this->response->statusCode(400);
            $this->set('error', $cronjob->getErrors());
            $this->set('_serialize', ['error']);
            return;
        }
        $this->set('cronjob', $cronjob);
        $this->set('_serialize', ['cronjob']);
    }

    public function edit($id = null) {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $Cronjobs App\Model\Table\CronjobsTable */
        $Cronjobs = TableRegistry::getTableLocator()->get('Cronjobs');
        $data = $this->request->data['Cronjob'];
        $cronjob = $Cronjobs->get($id);
        $cronjob = $Cronjobs->patchEntity($cronjob, $data);
        $Cronjobs->save($cronjob);

        if ($cronjob->hasErrors()) {
            $this->response->statusCode(400);
            $this->set('error', $cronjob->getErrors());
            $this->set('_serialize', ['error']);
            return;
        }
        $this->set('cronjob', $cronjob);
        $this->set('_serialize', ['cronjob']);
    }

    public function delete($id = null) {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $Cronjobs = TableRegistry::getTableLocator()->get('Cronjobs');
        $cronjob = $Cronjobs->get($id);

        if (empty($cronjob)) {
            throw new NotFoundException(__('Invalid cronjob'));
        }

        $Cronjobs->delete($cronjob);
        if ($cronjob->hasErrors()) {
            $this->response->statusCode(400);
            $this->set('error', $cronjob->getErrors());
            $this->set('_serialize', ['error']);
            return;
        }
        $this->set('cronjob', $cronjob);
        $this->set('_serialize', ['cronjob']);
    }
}