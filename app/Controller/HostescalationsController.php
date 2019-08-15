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
use App\Model\Table\ContactgroupsTable;
use App\Model\Table\ContactsTable;
use App\Model\Table\ContainersTable;
use App\Model\Table\HostescalationsTable;
use App\Model\Table\TimeperiodsTable;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\HostescalationsFilter;

/**
 * @property Hostescalation $Hostescalation
 * @property Timeperiod $Timeperiod
 * @property Host $Host
 * @property Hostgroup $Hostgroup
 * @property Contact $Contact
 * @property Contactgroup $Contactgroup
 * @property HostescalationHostMembership $HostescalationHostMembership
 * @property HostescalationHostgroupMembership $HostescalationHostgroupMembership
 * @property Container $Container
 */
class HostescalationsController extends AppController {

    public $layout = 'blank';


    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $HostescalationsTable HostescalationsTable */
        $HostescalationsTable = TableRegistry::getTableLocator()->get('Hostescalations');

        $HostescalationsFilter = new HostescalationsFilter($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $HostescalationsFilter->getPage());

        $MY_RIGHTS = [];
        if ($this->hasRootPrivileges === false) {
            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
            $MY_RIGHTS = $ContainersTable->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        }
        $hostescalations = $HostescalationsTable->getHostescalationsIndex($HostescalationsFilter, $PaginateOMat, $MY_RIGHTS);
        foreach ($hostescalations as $index => $hostescalation) {
            $hostescalations[$index]['allowEdit'] = $this->isWritableContainer($hostescalation['container_id']);
        }


        $this->set('all_hostescalations', $hostescalations);
        $toJson = ['all_hostescalations', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_hostescalations', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }
        if (!$this->Hostescalation->exists($id)) {
            throw new NotFoundException(__('Invalid hostescalation'));
        }
        $hostescalation = $this->Hostescalation->find('first', [
            'conditions' => [
                'Hostescalation.id' => $id,
            ],
            'contain'    => [
                'HostescalationHostMembership'      => [
                    'Host',
                ],
                'Contact',
                'Contactgroup'                      => [
                    'Container',
                ],
                'HostescalationHostgroupMembership' => [
                    'Hostgroup',
                ],
                'Timeperiod',
            ],
        ]);
        if (!$this->allowedByContainerId($hostescalation['Hostescalation']['container_id'])) {
            $this->render403();

            return;
        }

        $this->set('hostescalation', $hostescalation);
        $this->set('_serialize', ['hostescalation']);
    }

    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $HostescalationsTable HostescalationsTable */
        $HostescalationsTable = TableRegistry::getTableLocator()->get('Hostescalations');
        if (!$HostescalationsTable->existsById($id)) {
            throw new NotFoundException('Host escalation not found');
        }
        $hostescalation = $HostescalationsTable->get($id, [
            'contain' => [
                'hosts'         => function (Query $q) {
                    return $q->enableAutoFields(false)
                        ->select(['id', 'name']);
                },
                'hostgroups'    => function (Query $q) {
                    return $q->enableAutoFields(false)
                        ->select(['id']);
                },
                'contacts'      => function (Query $q) {
                    return $q->enableAutoFields(false)
                        ->select(['id']);
                },
                'contactgroups' => function (Query $q) {
                    return $q->enableAutoFields(false)
                        ->select(['id']);
                },
            ]
        ]);

        if (!$this->allowedByContainerId($hostescalation->get('container_id'))) {
            $this->render403();
            return;
        }

        if ($this->request->is('post')) {
            /** @var $HostescalationsTable HostescalationsTable */
            $HostescalationsTable = TableRegistry::getTableLocator()->get('Hostescalations');
            $data['hosts'] = $HostescalationsTable->parseHostMembershipData(
                $this->request->data('Hostescalation.hosts._ids'),
                $this->request->data('Hostescalation.hosts_excluded._ids')
            );
            $data['hostgroups'] = $HostescalationsTable->parseHostgroupMembershipData(
                $this->request->data('Hostescalation.hostgroups._ids'),
                $this->request->data('Hostescalation.hostgroups_excluded._ids')
            );

            $data = array_merge($this->request->data('Hostescalation'), $data);
            $hostescalation = $HostescalationsTable->patchEntity($hostescalation, $data);
            $HostescalationsTable->save($hostescalation);

            if ($hostescalation->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $hostescalation->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($hostescalation); // REST API ID serialization
                    return;
                }
            }
        }
        $this->set('hostescalation', $hostescalation);
        $this->set('_serialize', ['hostescalation']);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            /** @var $HostescalationsTable HostescalationsTable */
            $HostescalationsTable = TableRegistry::getTableLocator()->get('Hostescalations');
            $this->request->data['Hostescalation']['uuid'] = \itnovum\openITCOCKPIT\Core\UUID::v4();
            $data['hosts'] = $HostescalationsTable->parseHostMembershipData(
                $this->request->data('Hostescalation.hosts._ids'),
                $this->request->data('Hostescalation.hosts_excluded._ids')
            );
            $data['hostgroups'] = $HostescalationsTable->parseHostgroupMembershipData(
                $this->request->data('Hostescalation.hostgroups._ids'),
                $this->request->data('Hostescalation.hostgroups_excluded._ids')
            );

            $data = array_merge($this->request->data('Hostescalation'), $data);
            $hostescalation = $HostescalationsTable->newEntity($data);
            $HostescalationsTable->save($hostescalation);

            if ($hostescalation->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $hostescalation->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($hostescalation); // REST API ID serialization
                    return;
                }
            }
            $this->set('hostescalation', $hostescalation);
            $this->set('_serialize', ['hostescalation']);
        }
    }

    public function delete($id = null) {
        if (!$this->Hostescalation->exists($id)) {
            throw new NotFoundException(__('Invalid hostescalation'));
        }
        $hostescalation = $this->Hostescalation->findById($id);
        if (!$this->allowedByContainerId($hostescalation['Hostescalation']['container_id'])) {
            $this->render403();

            return;
        }

        if ($this->Hostescalation->delete($id)) {
            $this->set('message', __('Hostescalation deleted'));
            $this->set('_serialize', ['message']);
        }
        $this->set('message', __('Could not delete hostescalation'));
        $this->set('_serialize', ['message']);
    }

    public function loadElementsByContainerId($containerId = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException(__('This is only allowed via API.'));
            return;
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if (!$ContainersTable->existsById($containerId)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);

        $hostgroups = $HostgroupsTable->hostgroupsByContainerId($containerIds, 'list', 'id');
        $hostgroups = Api::makeItJavaScriptAble($hostgroups);
        $hostgroupsExcluded = $hostgroups;

        $hosts = $HostsTable->getHostsByContainerId($containerIds, 'list');
        $hosts = Api::makeItJavaScriptAble($hosts);
        $hostsExcluded = $hosts;

        $timeperiods = $TimeperiodsTable->timeperiodsByContainerId($containerIds, 'list');
        $timeperiods = Api::makeItJavaScriptAble($timeperiods);

        $contacts = $ContactsTable->contactsByContainerId($containerIds, 'list');
        $contacts = Api::makeItJavaScriptAble($contacts);

        $contactgroups = $ContactgroupsTable->getContactgroupsByContainerId($containerIds, 'list', 'id');
        $contactgroups = Api::makeItJavaScriptAble($contactgroups);

        $this->set(compact(['hosts', 'hostsExcluded', 'hostgroups', 'hostgroupsExcluded', 'timeperiods', 'contacts', 'contactgroups']));
        $this->set('_serialize', ['hosts', 'hostsExcluded', 'hostgroups', 'hostgroupsExcluded', 'timeperiods', 'contacts', 'contactgroups']);
    }

    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP, CT_CONTACTGROUP]);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP, CT_CONTACTGROUP]);
        }

        $this->set('containers', Api::makeItJavaScriptAble($containers));
        $this->set('_serialize', ['containers']);
    }

}
