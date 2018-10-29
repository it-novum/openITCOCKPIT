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

class CronjobsController extends AppController {
    public $layout = 'Admin.default';

    public function index() {
        //$cronjobs = $this->Cronjob->find('all');
        $cronjobs = $this->Paginator->paginate();
        $this->set(compact('cronjobs'));
        $this->set('_serialize', ['cronjobs']);
    }

    public function add() {
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Cronjob->save($this->request->data)) {
                $this->setFlash(__('Cronjob added successfully'));

                return $this->redirect(['action' => 'index']);
            }
            $this->setFlash(__('Data could not be saved'), false);
        }

        $plugins = $this->Cronjob->fetchPlugins();
        $coreTasks = $this->Cronjob->fetchTasks('Core');
        $this->set(compact('coreTasks', 'plugins'));
    }

    public function edit($id) {
        if (!$this->Cronjob->exists($id)) {
            throw new NotFoundException(__('Invalid cronjob'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Cronjob->save($this->request->data)) {
                $this->setFlash(__('Cronjob modified successfully'));

                return $this->redirect(['action' => 'index']);
            }
            $this->setFlash(__('Data could not be saved'), false);
        }

        $cronjob = $this->Cronjob->findById($id);

        $plugins = $this->Cronjob->fetchPlugins();
        $pluginTasks = $this->Cronjob->fetchTasks($cronjob['Cronjob']['plugin']);
        $this->set(compact('pluginTasks', 'plugins', 'cronjob'));
    }

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Cronjob->id = $id;
        if (!$this->Cronjob->exists()) {
            throw new NotFoundException(__('Invalid cronjob'));
        }

        $cronjob = $this->Cronjob->findById($id);

        if ($this->Cronjob->delete()) {
            $this->setFlash(__('Cronjob deleted'));

            return $this->redirect(['action' => 'index']);
        }
        $this->setFlash(__('Could not delete cronjob'));
        $this->redirect(['action' => 'index']);
    }

    public function loadTasksByPlugin($pluginName) {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        $tasks = $this->Cronjob->fetchTasks($pluginName);
        $this->set('tasks', $tasks);
        $this->set('_serialize', ['tasks']);
    }

}