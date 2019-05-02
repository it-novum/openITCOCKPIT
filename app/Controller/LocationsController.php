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


use App\Model\Table\ContainersTable;
use App\Model\Table\LocationsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\LocationFilter;

class LocationsController extends AppController {
    public $uses = ['Location', 'Container'];
    public $layout = 'blank';
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

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $LocationsTable LocationsTable */
        $LocationsTable = TableRegistry::getTableLocator()->get('Locations');
        $LocationFilter = new LocationFilter($this->request);

        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $LocationFilter->getPage());
        $all_locations = $LocationsTable->getLocationsIndex($LocationFilter, $PaginateOMat);

        foreach ($all_locations as $key => $location) {
            $all_locations[$key]['Location']['allowEdit'] = false;
            $locationContainerId = $location['Location']['container_id'];
            if (isset($this->MY_RIGHTS_LEVEL[$locationContainerId])) {
                if ((int)$this->MY_RIGHTS_LEVEL[$locationContainerId] === WRITE_RIGHT) {
                    $all_locations[$key]['Location']['allowEdit'] = true;
                }
            }
        }

        $this->set('all_locations', $all_locations);
        $toJson = ['all_locations', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_locations', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    /**
     * @param $id
     */
    public function view($id) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $LocationsTable LocationsTable */
        $LocationsTable = TableRegistry::getTableLocator()->get('Locations');

        if (!$LocationsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid location'));
        }

        $location = $LocationsTable->getLocationById($id);

        if (!$this->allowedByContainerId($location['container_id'])) {
            $this->render403();
            return;
        }

        $this->set('location', $location);
        $this->set('_serialize', ['location']);
    }

    public function add() {
        $this->layout = 'blank';
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $LocationsTable LocationsTable */
        $LocationsTable = TableRegistry::getTableLocator()->get('Locations');

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            $this->request->data['uuid'] = UUID::v4();

            $location = $LocationsTable->newEntity();
            $location = $LocationsTable->patchEntity($location, $this->request->data);
            $location->container->containertype_id = CT_LOCATION;

            $LocationsTable->save($location);
            if ($location->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $location->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'add',
                    'locations',
                    $location->get('id'),
                    OBJECT_LOCATION,
                    $location->get('container')->get('parent_id'),
                    $User->getId(),
                    $location->container->name,
                    [
                        'location' => $location->toArray()
                    ]
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                //@todo refactor with cake4
                Cache::clear(false, 'permissions');

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($location); // REST API ID serialization
                    return;
                }
            }
            $this->set('location', $location);
            $this->set('_serialize', ['location']);
        }
    }

    /**
     * @param null $id
     */
    public function edit($id = null) {
        $this->layout = 'blank';

        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $LocationsTable LocationsTable */
        $LocationsTable = TableRegistry::getTableLocator()->get('Locations');

        if (!$LocationsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid location'));
        }

        if ($this->request->is('get')) {
            $location = $LocationsTable->getLocationById($id);

            if (!$this->allowedByContainerId($location['container_id'])) {
                $this->render403();
                return;
            }

            $this->set('location', $location);
            $this->set('_serialize', ['location']);
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            $oldLocation = $LocationsTable->get($id);
            $oldLocationForChangelog = $oldLocation->toArray();
            if (!$this->allowedByContainerId($oldLocation->get('container_id'))) {
                $this->render403();
                return;
            }

            $location = $LocationsTable->patchEntity($oldLocation, $this->request->data);

            $location->container_id = $oldLocation->get('container_id');
            $location->container->id = $oldLocation->get('container_id');
            $location->container->parent_id = $oldLocation->get('parent_id');
            $location->container->containertype_id = CT_LOCATION;

            $LocationsTable->save($location);
            if ($location->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $location->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    'locations',
                    $location->get('id'),
                    OBJECT_LOCATION,
                    $location->get('container')->get('parent_id'),
                    $User->getId(),
                    $location->container->name,
                    [
                        'location' => $location->toArray()
                    ],
                    [
                        'location' => $oldLocationForChangelog
                    ]
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                //@todo refactor with cake4
                Cache::clear(false, 'permissions');

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($location); // REST API ID serialization
                    return;
                }
            }
            $this->set('location', $location);
            $this->set('_serialize', ['location']);
        }
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Location->exists($id)) {
            throw new NotFoundException(__('Invalid location'));
        }

        $container = $this->Location->findById($id);

        /** @var $LocationsTable LocationsTable */
        $LocationsTable = TableRegistry::getTableLocator()->get('Locations');
        $location = $LocationsTable->getLocationById($id);
        $locationParentId = $location->get('container')->get('parent_id');
        $locationForChangelog = $location;

        if (!$this->allowedByContainerId(Hash::extract($container, 'Container.id'))) {
            $this->render403();

            return;
        }

        if ($this->Location->__allowDelete($container['Location']['container_id'])) {

            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

            if ($ContainersTable->delete($ContainersTable->get($container['Location']['container_id']))) {
                Cache::clear(false, 'permissions');

                $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'delete',
                    'locations',
                    $id,
                    OBJECT_LOCATION,
                    [$locationParentId],
                    $User->getId(),
                    $locationForChangelog['container']['name'],
                    []
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                $this->set('message', __('Location deleted successfully'));
                $this->set('_serialize', ['message']);
                return;
            }
            $this->response->statusCode(400);
            $this->set('message', __('Could not delete location'));
            $this->set('_serialize', ['message']);
        }
        $this->response->statusCode(400);
        $this->set('message', __('Could not delete location'));
        $this->set('_serialize', ['message']);
    }

    /****************************
     *       AJAX METHODS       *
     ****************************/

    /**
     * @throws Exception
     */
    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_LOCATION, [], $this->hasRootPrivileges);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_LOCATION, [], $this->hasRootPrivileges);
        }
        $containers = Api::makeItJavaScriptAble($containers);


        $this->set('containers', $containers);
        $this->set('_serialize', ['containers']);
    }

}
