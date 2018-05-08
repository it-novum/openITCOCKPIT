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


/**
 * @property Hostdependency                    $Hostdependency
 * @property Timeperiod                        $Timeperiod
 * @property Host                              $Host
 * @property Hostgroup                         $Hostgroup
 * @property HostdependencyHostMembership      $HostdependencyHostMembership
 * @property HostdependencyHostgroupMembership $HostdependencyHostgroupMembership
 * @property Container                         $Container
 */
class HostdependenciesController extends AppController
{
    public $uses = [
        'Hostdependency',
        'Timeperiod',
        'Host',
        'Hostgroup',
        'HostdependencyHostMembership',
        'HostdependencyHostgroupMembership',
        'Container',
    ];
    public $layout = 'Admin.default';
    public $components = [
        'ListFilter.ListFilter',
        'RequestHandler',
        'CustomValidationErrors',
    ];
    public $helpers = [
        'ListFilter.ListFilter',
        'CustomValidationErrors',
    ];

    public function index()
    {
        $options = [
            'conditions' => [
                'Hostdependency.container_id' => $this->MY_RIGHTS,
            ],
            'contain'    => [
                'HostdependencyHostMembership'      => [
                    'Host' => [
                        'fields' => [
                            'name',
                            'disabled'
                        ],
                    ],
                ],
                'HostdependencyHostgroupMembership' => [
                    'Hostgroup' => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                ],
                'Timeperiod'                        => [
                    'fields' => 'name',
                ],
            ],
        ];

        $query = Hash::merge($this->Paginator->settings, $options);

        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_hostdependencies = $this->Hostdependency->find('all', $query);
        } else {
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $all_hostdependencies = $this->Paginator->paginate();
        }

        $this->set(compact('all_hostdependencies'));
        $this->set('_serialize', ['all_hostdependencies']);
    }

    public function view($id = null)
    {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }
        if (!$this->Hostdependency->exists($id)) {
            throw new NotFoundException(__('Invalid hostdependency'));
        }
        $hostdependency = $this->Hostdependency->findById($id);
        if (!$this->allowedByContainerId($hostdependency['Hostdependency']['container_id'])) {
            $this->render403();

            return;
        }

        $this->set('hostdependency', $hostdependency);
        $this->set('_serialize', ['hostdependency']);
    }

    public function edit($id = null)
    {
        if (!$this->Hostdependency->exists($id)) {
            throw new NotFoundException(__('Invalid hostdependency'));
        }
        $hostdependency = $this->Hostdependency->findById($id);
        if (!$hostdependency) {
            throw new NotFoundException(__('Invalid hostdependency'));
        }

        if (!$this->allowedByContainerId($hostdependency['Hostdependency']['container_id'])) {
            $this->render403();

            return;
        }

        $this->Frontend->set('data_placeholder', __('Please choose'));
        $this->Frontend->set('data_placeholder_empty', __('No entries found'));

        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOSTDEPENDENCY, [], $this->hasRootPrivileges);
        $containerIds = $this->Tree->resolveChildrenOfContainerIds($hostdependency['Hostdependency']['container_id']);

        $hosts = $this->Host->hostsByContainerId($containerIds, 'list');

        $hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
        $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');

        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['Hostdependency']['container_id']) && $hostdependency['Hostdependency']['container_id'] != $this->request->data['Hostdependency']['container_id']) {
                $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->request->data['Hostdependency']['container_id']);
                $hosts = $this->Host->hostsByContainerId($containerIds, 'list');
                $hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
                $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
            }

            $_hosts = (is_array($this->request->data['Hostdependency']['Host'])) ? $this->request->data['Hostdependency']['Host'] : [];
            $dependent_hosts = (is_array($this->request->data['Hostdependency']['HostDependent'])) ? $this->request->data['Hostdependency']['HostDependent'] : [];
            $this->request->data['HostdependencyHostMembership'] = $this->Hostdependency->parseHostMembershipData($_hosts, $dependent_hosts);

            $_hostgroups = (is_array($this->request->data['Hostdependency']['Hostgroup'])) ? $this->request->data['Hostdependency']['Hostgroup'] : [];
            $dependent_hostgroups = (is_array($this->request->data['Hostdependency']['HostgroupDependent'])) ? $this->request->data['Hostdependency']['HostgroupDependent'] : [];
            $this->request->data['HostdependencyHostgroupMembership'] = $this->Hostdependency->parseHostgroupMembershipData($_hostgroups, $dependent_hostgroups);

            $this->Hostdependency->set($this->request->data);
            $this->Hostdependency->id = $id;

            if ($this->Hostdependency->validates()) {
                $old_membership_hosts = $this->HostdependencyHostMembership->find('all', [
                    'conditions' => [
                        'HostdependencyHostMembership.hostdependency_id' => $id],
                ]);
                /* Delete old host associations */
                foreach ($old_membership_hosts as $old_membership_host) {
                    $this->HostdependencyHostMembership->delete($old_membership_host['HostdependencyHostMembership']['id']);

                }
                $old_membership_hostgroups = $this->HostdependencyHostgroupMembership->find('all', [
                    'conditions' => [
                        'HostdependencyHostgroupMembership.hostdependency_id' => $id],
                ]);
                /* Delete old hostgroup associations */
                foreach ($old_membership_hostgroups as $old_membership_hostgroup) {
                    $this->HostdependencyHostgroupMembership->delete($old_membership_hostgroup['HostdependencyHostgroupMembership']['id']);

                }
            }

            if ($this->Hostdependency->saveAll($this->request->data)) {
                $this->setFlash(__('Hostdependency successfully saved'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Hostdependency could not be saved'), false);
            }
        } else {
            $hostdependency['Hostdependency']['Host'] = Hash::combine($hostdependency['HostdependencyHostMembership'], '{n}[dependent=0].host_id', '{n}[dependent=0].host_id');
            $hostdependency['Hostdependency']['HostDependent'] = Hash::combine($hostdependency['HostdependencyHostMembership'], '{n}[dependent=1].host_id', '{n}[dependent=1].host_id');
            $hostdependency['Hostdependency']['Hostgroup'] = Hash::combine($hostdependency['HostdependencyHostgroupMembership'], '{n}[dependent=0].hostgroup_id', '{n}[dependent=0].hostgroup_id');
            $hostdependency['Hostdependency']['HostgroupDependent'] = Hash::combine($hostdependency['HostdependencyHostgroupMembership'], '{n}[dependent=1].hostgroup_id', '{n}[dependent=1].hostgroup_id');
        }

        $this->request->data = Hash::merge($hostdependency, $this->request->data);

        $this->set(compact(['hostdependency', 'hosts', 'hostgroups', 'timeperiods', 'containers']));
    }

    public function add()
    {

        $hosts = [];
        $hostgroups = [];
        $timeperiods = [];

        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOSTDEPENDENCY, [], $this->hasRootPrivileges);


        $customFildsToRefill = [
            'Hostdependency' => [
                'inherits_parent',
                'execution_fail_on_up',
                'execution_fail_on_down',
                'execution_fail_on_unreachable',
                'execution_fail_on_pending',
                'execution_none',
                'notification_fail_on_up',
                'notification_fail_on_down',
                'notification_fail_on_unreachable',
                'notification_fail_on_pending',
                'notification_none',
            ],
        ];
        $this->CustomValidationErrors->checkForRefill($customFildsToRefill);

        $this->Frontend->set('data_placeholder', __('Please choose'));
        $this->Frontend->set('data_placeholder_empty', __('No entries found'));

        if ($this->request->is('post') || $this->request->is('put')) {
            App::uses('UUID', 'Lib');
            $this->request->data['Hostdependency']['uuid'] = UUID::v4();
            $hosts = (is_array($this->request->data['Hostdependency']['Host'])) ? $this->request->data['Hostdependency']['Host'] : [];
            $dependent_hosts = (is_array($this->request->data['Hostdependency']['HostDependent'])) ? $this->request->data['Hostdependency']['HostDependent'] : [];
            $this->request->data['HostdependencyHostMembership'] = $this->Hostdependency->parseHostMembershipData($hosts, $dependent_hosts);

            $hostgroups = (is_array($this->request->data['Hostdependency']['Hostgroup'])) ? $this->request->data['Hostdependency']['Hostgroup'] : [];
            $dependent_hostgroups = (is_array($this->request->data['Hostdependency']['HostgroupDependent'])) ? $this->request->data['Hostdependency']['HostgroupDependent'] : [];
            $this->request->data['HostdependencyHostgroupMembership'] = $this->Hostdependency->parseHostgroupMembershipData($hostgroups, $dependent_hostgroups);
            if ($this->Hostdependency->saveAll($this->request->data)) {

                $this->serializeId(); // REST API ID serialization

                if ($this->request->ext != 'json') {
                    $this->setFlash(__('Hostdependency successfully saved'));
                    $this->redirect(['action' => 'index']);
                }
            } else {
                $containerId = $this->request->data('Hostdependency.container_id');
                if ($containerId > 0) {
                    $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerId);
                    $hosts = $this->Host->hostsByContainerId($containerIds, 'list');
                    $hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
                    $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
                }
                $this->setFlash(__('Hostdependency could not be saved'), false);
            }
        }
        $this->set(compact(['hosts', 'hostgroups', 'timeperiods', 'containers']));
    }

    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Hostdependency->exists($id)) {
            throw new NotFoundException(__('Invalid hostdependency'));
        }

        $hostdependency = $this->Hostdependency->findById($id);

        if (!$this->allowedByContainerId($hostdependency['Hostdependency']['container_id'])) {
            $this->render403();

            return;
        }

        if ($this->Hostdependency->delete($id)) {
            $this->setFlash(__('Hostdependency deleted'));
            $this->redirect(['action' => 'index']);
        }
        $this->setFlash(__('Could not delete hostdependency'), false);
        $this->redirect(['action' => 'index']);
    }

    public function loadElementsByContainerId($containerId = null)
    {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Container->exists($containerId)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerId);

        $hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
        $hostgroups = $this->Host->makeItJavaScriptAble($hostgroups);
        $hostgroupsDependent = $hostgroups;

        $hosts = $this->Host->hostsByContainerId($containerIds, 'list');
        $hosts = $this->Host->makeItJavaScriptAble($hosts);
        $hostsDependent = $hosts;

        $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
        $timeperiods = $this->Host->makeItJavaScriptAble($timeperiods);

        $this->set(compact(['hosts', 'hostsDependent', 'hostgroups', 'hostgroupsDependent', 'timeperiods']));
        $this->set('_serialize', ['hosts', 'hostsDependent', 'hostgroups', 'hostgroupsDependent', 'timeperiods']);
    }
}
