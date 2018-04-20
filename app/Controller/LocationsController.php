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


class LocationsController extends AppController
{
    public $uses = ['Location', 'Container'];
    public $layout = 'Admin.default';
    public $components = ['ListFilter.ListFilter', 'RequestHandler'];
    public $helpers = ['ListFilter.ListFilter'];
    public $listFilters = [
        'index' => [
            'fields' => [
                'Container.name'       => ['label' => 'Name', 'searchType' => 'wildcard'],
                'Location.description' => ['label' => 'description', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function index()
    {

        if ($this->hasRootPrivileges === true) {
            $container = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_LOCATION, [], $this->hasRootPrivileges);
        } else {
            $container = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_LOCATION, [], $this->hasRootPrivileges);
        }
        $options = [
            'order'      => [
                'Container.name' => 'asc',
            ],
            'conditions' => [
                'Container.parent_id' => $this->MY_RIGHTS,
            ],
        ];

        $query = Hash::merge($this->Paginator->settings, $options);

        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_locations = $this->Location->find('all', $query);
        } else {
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $all_locations = $this->Paginator->paginate();
        }
        $this->set(compact(['all_locations', 'container']));
        $this->set('_serialize', ['all_locations']);
    }

    public function view($id = null)
    {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }
        if (!$this->Location->exists($id)) {
            throw new NotFoundException(__('Invalid location'));
        }
        $location = $this->Location->findById($id);
        if (!$this->allowedByContainerId(Hash::extract($location, 'Container.parent_id'))) {
            $this->render403();

            return;
        }

        $this->set('location', $location);
        $this->set('_serialize', ['location']);
    }

    public function add()
    {
        if ($this->hasRootPrivileges === true) {
            $container = $this->Tree->easyPath($this->MY_RIGHTS, CT_LOCATION, [], $this->hasRootPrivileges);
        } else {
            $container = $this->Tree->easyPath($this->getWriteContainers(), CT_LOCATION, [], $this->hasRootPrivileges);
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Location->create();
            App::uses('UUID', 'Lib');
            $this->request->data['Location']['uuid'] = UUID::v4();
            $this->request->data['Container']['containertype_id'] = CT_LOCATION;
            if ($this->Location->saveAll($this->request->data)) {
                Cache::clear(false, 'permissions');
                if ($this->request->ext == 'json') {
                    $this->serializeId();

                    return;
                } else {
                    $this->setFlash(__('Location successfully saved.'));
                    $this->redirect(['action' => 'index']);
                }
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();

                    return;
                } else {
                    $this->setFlash(__('Could not save data'), false);
                }
            }
        }

        $this->set(compact(['container']));
    }

    public function edit($id = null)
    {
        if (!$this->Location->exists($id)) {
            throw new NotFoundException(__('Invalid location'));
        }

        $location = $this->Location->findById($id);

        if (!$this->allowedByContainerId(Hash::extract($location, 'Container.parent_id'))) {
            $this->render403();

            return;
        }

        if ($this->hasRootPrivileges === true) {
            $container = $this->Tree->easyPath($this->MY_RIGHTS, CT_LOCATION, [], $this->hasRootPrivileges);
        } else {
            $container = $this->Tree->easyPath($this->getWriteContainers(), CT_LOCATION, [], $this->hasRootPrivileges);
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Location']['id'] = $id;
            $this->request->data['Container']['id'] = $location['Container']['id'];
            $this->request->data['Container']['containertype_id'] = CT_LOCATION;
            if ($this->Location->saveAll($this->request->data)) {
                Cache::clear(false, 'permissions');
                $this->setFlash(__('Location successfully saved'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Could not save data'), false);
            }
        }
        $this->Frontend->set('latitude', $location['Location']['latitude']);
        $this->Frontend->set('longitude', $location['Location']['longitude']);
        $this->set(compact(['location', 'container']));
    }

    public function delete($id = null)
    {
        if (!$this->Location->exists($id)) {
            throw new NotFoundException(__('Invalid location'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $location = $this->Location->findById($id);

        if (!$this->allowedByContainerId(Hash::extract($location, 'Container.parent_id'))) {
            $this->render403();

            return;
        }

        if ($this->Location->__delete($location, $this->Auth->user('id'))) {
            Cache::clear(false, 'permissions');
            $this->setFlash(__('Location successfully deleted'));
            $this->redirect(['action' => 'index']);
        } else {
            $this->setFlash(__('Could not delete data'), false);
            $this->redirect(['action' => 'index']);
        }
    }

}
