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

use Acl\Controller\Component\AclComponent;
use Acl\Model\Table\AcosTable;
use Acl\Model\Table\ArosTable;
use App\Lib\AclDependencies;
use App\Model\Table\ArosAcosTable;
use App\Model\Table\UsergroupsTable;
use Cake\Cache\Cache;
use Cake\Controller\ComponentRegistry;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\GenericFilter;

/**
 * Class UsergroupsController
 * @package App\Controller
 */
class UsergroupsController extends AppController {

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        $GenericFilter = new GenericFilter($this->request);
        $GenericFilter->setFilters([
            'like' => [
                'Usergroups.name',
                'Usergroups.description'
            ]
        ]);

        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $GenericFilter->getPage());

        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');
        $allUsergroups = $UsergroupsTable->getUsergroups($PaginateOMat, $GenericFilter);

        $this->set('allUsergroups', $allUsergroups);
        $this->viewBuilder()->setOption('serialize', ['allUsergroups']);
    }

    /**
     * @param int|null $id
     */
    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');

        if (!$UsergroupsTable->exists($id)) {
            throw new NotFoundException(__('Invalid usergroup'));
        }
        $usergroup = $UsergroupsTable->getUsergroupById($id);

        $this->set('usergroup', $usergroup);
        $this->viewBuilder()->setOption('serialize', ['usergroup']);
    }

    /**
     * @throws \Exception
     */
    public function add() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var AcosTable $AcosTable */
        $AcosTable = TableRegistry::getTableLocator()->get('Acl.Acos');
        if ($this->request->is('get') && $this->isJsonRequest()) {
            $acos = $AcosTable->find('threaded')
                ->disableHydration()
                ->all();
            $AclDependencies = new AclDependencies();
            $acos = $AclDependencies->filterAcosForFrontend($acos->toArray());
            $this->set('acos', $acos);
            $this->viewBuilder()->setOption('serialize', ['acos']);
            return;
        }

        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');
        $usergroup = $UsergroupsTable->newEmptyEntity();
        $usergroup = $UsergroupsTable->patchEntity($usergroup, $this->request->getData('Usergroup'));
        $UsergroupsTable->save($usergroup);

        if ($usergroup->hasErrors()) {
            //This throws the body content away :(
            $this->response = $this->response->withStatus(400);
            $this->set('error', $usergroup->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        //Save Acos
        /** @var ArosTable $ArosTable */
        $ArosTable = TableRegistry::getTableLocator()->get('Acl.Aros');
        /** @var Table $ArosAcos */
        $ArosAcos = TableRegistry::getTableLocator()->get('ArosAcos');
        $aro = $ArosTable->find()
            ->where([
                'Aros.foreign_key' => $usergroup->get('id')
            ])
            ->first();


        $AclDependencies = new AclDependencies();
        $selectedAcos = $this->request->getData('Acos', []);
        $selectedAcos = $AclDependencies->getDependentAcos($AcosTable, $selectedAcos);

        // This is the CakePHP way, but it is super slow for many ACOs...
        //
        //$registry = new ComponentRegistry();
        //$Acl = new AclComponent($registry);
        //foreach ($selectedAcos as $acoId => $state) {
        //    if ($state === 1) {
        //        $Acl->allow($aro->get('id'), $acoId, '*');
        //    } else {
        //        $Acl->deny($aro->get('id'), $acoId, '*');
        //    }
        //}

        // Lightning fast workaround (we also done this in cake 2)
        // https://github.com/it-novum/openITCOCKPIT/blob/openITCOCKPIT-3.7.2/app/Controller/UsergroupsController.php#L213-L221
        /** @var ArosAcosTable $ArosAcosTable */
        $ArosAcosTable = TableRegistry::getTableLocator()->get('ArosAcos');

        $arosToAcos = [];
        foreach ($selectedAcos as $acoId => $state) {
            $arosToAcos[] = $ArosAcosTable->newEntity([
                'aro_id'  => $aro->get('id'),
                'aco_id'  => $acoId,
                '_create' => (int)($state === 1),
                '_read'   => (int)($state === 1),
                '_update' => (int)($state === 1),
                '_delete' => (int)($state === 1),
            ]);
        }
        $ArosAcosTable->saveMany($arosToAcos);

        $this->set('usergroup', $usergroup);
        $this->viewBuilder()->setOption('serialize', ['usergroup']);
    }

    /**
     * @param int|null $id
     * @throws \Exception
     */
    public function edit($id = null) {
        if (!$this->isJsonRequest()) {
            //Only ship html template
            return;
        }

        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');

        if (!$UsergroupsTable->existsById($id)) {
            throw new NotFoundException(__('User group not found'));
        }

        $usergroup = $UsergroupsTable->find()
            ->contain([
                'Aros' => [
                    'Acos'
                ]
            ])
            ->where([
                'Usergroups.id' => $id
            ])
            ->firstOrFail();

        /** @var AcosTable $AcosTable */
        $AcosTable = TableRegistry::getTableLocator()->get('Acl.Acos');
        if ($this->request->is('get')) {
            $acos = $AcosTable->find('threaded')
                ->disableHydration()
                ->all();
            $AclDependencies = new AclDependencies();
            $acos = $AclDependencies->filterAcosForFrontend($acos->toArray());

            $this->set('usergroup', $usergroup);
            $this->set('acos', $acos);
            $this->viewBuilder()->setOption('serialize', ['usergroup', 'acos']);
            return;
        }

        if ($this->request->is('post')) {
            $usergroup->setAccess('id', false);
            $usergroup = $UsergroupsTable->patchEntity($usergroup, $this->request->getData());
            $UsergroupsTable->save($usergroup);

            if ($usergroup->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $usergroup->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            //Save Acos
            $AclDependencies = new AclDependencies();
            $selectedAcos = $this->request->getData('Acos');
            $selectedAcos = $AclDependencies->getDependentAcos($AcosTable, $selectedAcos);

            /** @var ArosTable $ArosTable */
            $ArosTable = TableRegistry::getTableLocator()->get('Acl.Aros');
            /** @var ArosAcosTable c */
            $ArosAcosTable = TableRegistry::getTableLocator()->get('ArosAcos');
            $aro = $ArosTable->find()
                ->where([
                    'Aros.foreign_key' => $usergroup->get('id')
                ])
                ->first();

            //Drop old permissions
            $ArosAcosTable->deleteAll([
                'ArosAcos.aro_id' => $aro->get('id')
            ]);

            $arosToAcos = [];
            foreach ($selectedAcos as $acoId => $state) {
                $arosToAcos[] = $ArosAcosTable->newEntity([
                    'aro_id'  => $aro->get('id'),
                    'aco_id'  => $acoId,
                    '_create' => (int)($state === 1),
                    '_read'   => (int)($state === 1),
                    '_update' => (int)($state === 1),
                    '_delete' => (int)($state === 1),
                ]);
            }
            $ArosAcosTable->saveMany($arosToAcos);
            $this->set('usergroup', $usergroup);
            $this->viewBuilder()->setOption('serialize', ['usergroup']);
        }
    }

    /**
     * @param int|null $id
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');

        if (!$UsergroupsTable->existsById($id)) {
            throw new NotFoundException(__('User group not found'));
        }

        $user = $this->getUser();
        if ((int)$user->get('usergroup_id') === (int)$id) {
            throw new \RuntimeException('You cannot delete your own user group!');
        }

        $usergroup = $UsergroupsTable->get($id);

        if ($UsergroupsTable->delete($usergroup)) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->response = $this->response->withStatus(500);
        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function loadUsergroups() {
        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');
        $usergroups = $UsergroupsTable->getUsergroupsList();

        $usergroups = Api::makeItJavaScriptAble($usergroups);

        $this->set('usergroups', $usergroups);
        $this->viewBuilder()->setOption('serialize', ['usergroups']);
    }

}
