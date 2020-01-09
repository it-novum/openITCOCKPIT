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

namespace MapModule\Controller;

use App\Model\Table\ContainersTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\MapFilter;
use itnovum\openITCOCKPIT\Filter\RotationFilter;
use MapModule\Model\Table\MapsTable;
use MapModule\Model\Table\RotationsTable;

class RotationsController extends AppController {

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        /** @var RotationsTable $RotationsTable */
        $RotationsTable = TableRegistry::getTableLocator()->get('MapModule.Rotations');

        $RotationFilter = new RotationFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $RotationFilter->getPage());

        $limit = $PaginateOMat->getHandler()->getLimit();
        $Paginator = null;
        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            $limit = null;
        } else {
            $Paginator = $PaginateOMat;
        }

        $all_rotations = $RotationsTable->getAll(
            $RotationFilter->indexFilter(),
            $RotationFilter->getOrderForPaginator('Rotations.name', 'asc'),
            $limit,
            $Paginator,
            $this->hasRootPrivileges ? [] : $this->MY_RIGHTS);

        foreach ($all_rotations as $key => $rotation) {
            $all_rotations[$key]['allowEdit'] = false;
            if ($this->hasRootPrivileges == true) {
                $all_rotations[$key]['allowEdit'] = true;
                continue;
            }
            foreach ($rotation['containers'] as $cKey => $container) {
                if ($this->MY_RIGHTS_LEVEL[$container['id']] == WRITE_RIGHT) {
                    $all_rotations[$key]['allowEdit'] = true;
                    continue;
                }
            }
        }

        //build rotation link
        foreach ($all_rotations as $key => $rotation) {
            $all_rotations[$key]['ids'] = [];
            foreach ($rotation['maps'] as $rKey => $map) {
                if (!isset($all_rotations[$key]['first_id'])) {
                    $all_rotations[$key]['first_id'] = $map['id'];
                }
                $all_rotations[$key]['ids'][] = $map['id'];
            }
            $all_rotations[$key]['ids'] = implode(',', $all_rotations[$key]['ids']);
        }

        $this->set('all_rotations', $all_rotations);
        $this->viewBuilder()->setOption('serialize', ['all_rotations', 'paging']);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();
            $data['Rotation']['containers']['_ids'] = $data['Rotation']['container_id'];
            $data['Rotation']['maps']['_ids'] = $data['Rotation']['Map'];

            /** @var RotationsTable $RotationsTable */
            $RotationsTable = TableRegistry::getTableLocator()->get('MapModule.Rotations');

            $rotationsEntity = $RotationsTable->newEntity($data['Rotation']);
            $RotationsTable->save($rotationsEntity);
            if (!$rotationsEntity->hasErrors()) {
                $this->serializeCake4Id($rotationsEntity);
                return;
            } else {
                $this->serializeCake4ErrorMessage($rotationsEntity);
                return;
            }
        }
    }

    public function loadMaps() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $MapFilter = new MapFilter($this->request);

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

        $maps = $MapsTable->getMapsForRotations($MapFilter->indexFilter(), $this->hasRootPrivileges ? [] : $this->MY_RIGHTS);

        $maps = Hash::combine($maps, '{n}.id', '{n}.name');
        $maps = Api::makeItJavaScriptAble($maps);

        $this->set('maps', $maps);
        $this->viewBuilder()->setOption('serialize', ['maps']);
    }

    /**
     * @throws \Exception
     */
    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, CT_TENANT, [], $this->hasRootPrivileges);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), CT_TENANT, [], $this->hasRootPrivileges);
        }
        $containers = Api::makeItJavaScriptAble($containers);

        $this->set('containers', $containers);
        $this->viewBuilder()->setOption('serialize', ['containers']);
    }

    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var RotationsTable $RotationsTable */
        $RotationsTable = TableRegistry::getTableLocator()->get('MapModule.Rotations');

        if (!$RotationsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Map rotation'));
        }

        $rotation = $RotationsTable->get($id, [
            'contain' => [
                'Maps',
                'Containers'
            ]
        ]);

        $this->viewBuilder()->setOption('serialize', ['rotation']);
        $this->set(compact('rotation'));

        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();
            $data['Rotation']['id'] = $id;

            if (empty($data['Rotation']['interval'])) {
                $data['Rotation']['interval'] = 90;
            } else {
                if ($data['Rotation']['interval'] < 10) {
                    $data['Rotation']['interval'] = 10;
                }
            }

            $data['Rotation']['containers']['_ids'] = $data['Rotation']['container_id'];
            $data['Rotation']['maps']['_ids'] = $data['Rotation']['Map'];


            $rotationEntity = $rotation;
            $rotationEntity = $RotationsTable->patchEntity($rotationEntity, $data['Rotation']);
            $RotationsTable->save($rotationEntity);
            if (!$rotationEntity->hasErrors()) {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($rotationEntity);
                }
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4ErrorMessage($rotationEntity);
                }
            }
        }
    }

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var RotationsTable $RotationsTable */
        $RotationsTable = TableRegistry::getTableLocator()->get('MapModule.Rotations');

        if (!$RotationsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Map rotation'));
        }

        $rotation = $RotationsTable->get($id, [
            'contain' => [
                'Maps',
                'Containers'
            ]
        ]);
        $containerIdsToCheck = Hash::extract($rotation, 'containers.{n}.id');
        if (!$this->allowedByContainerId($containerIdsToCheck)) {
            $this->render403();
            return;
        }

        if ($RotationsTable->delete($rotation)) {
            $this->set('message', __('Map rotation deleted successfully'));
            $this->viewBuilder()->setOption('serialize', ['message']);
            return;
        }

        $this->response->withStatus(400);
        $this->set('message', __('Could not delete map rotation'));
        $this->viewBuilder()->setOption('serialize', ['message']);
    }
}
