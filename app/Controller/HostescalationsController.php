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
 * @property Hostescalation                    $Hostescalation
 * @property Timeperiod                        $Timeperiod
 * @property Host                              $Host
 * @property Hostgroup                         $Hostgroup
 * @property Contact                           $Contact
 * @property Contactgroup                      $Contactgroup
 * @property HostescalationHostMembership      $HostescalationHostMembership
 * @property HostescalationHostgroupMembership $HostescalationHostgroupMembership
 * @property Container                         $Container
 */
class HostescalationsController extends AppController
{
    public $uses = [
        'Hostescalation',
        'Timeperiod',
        'Host',
        'Hostgroup',
        'Contact',
        'Contactgroup',
        'HostescalationHostMembership',
        'HostescalationHostgroupMembership',
        'Container',
    ];
    public $layout = 'Admin.default';
    public $components = [
        'ListFilter.ListFilter',
        'RequestHandler',
    ];
    public $helpers = ['ListFilter.ListFilter'];

    public function index()
    {
        $options = [
            'recursive'  => -1,
            'conditions' => [
                'Hostescalation.container_id' => $this->MY_RIGHTS,
            ],
            'contain'    => [
                'HostescalationHostMembership'      => [
                    'Host' => [
                        'fields' => [
                            'name',
                            'id',
                            'disabled'
                        ],
                    ],
                ],
                'Contact'                           => [
                    'fields' => [
                        'name', 'id',
                    ],
                ],
                'Contactgroup'                      => [
                    'Container' => [
                        'fields' => 'name',
                    ],
                    'fields'    => [
                        'id',
                    ],
                ],
                'HostescalationHostgroupMembership' => [
                    'Hostgroup' => [
                        'Container' => [
                            'fields' => 'name',
                        ],
                        'fields'    => [
                            'id',
                        ],
                    ],
                ],
                'Timeperiod'                        => [
                    'fields' => [
                        'name', 'id',
                    ],
                ],
            ],
        ];

        $query = Hash::merge($this->Paginator->settings, $options);

        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_hostescalations = $this->Hostescalation->find('all', $query);
        } else {
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $all_hostescalations = $this->Paginator->paginate();
        }

        $this->set('all_hostescalations', $all_hostescalations);

        $this->set('_serialize', ['all_hostescalations']);
    }

    public function view($id = null)
    {
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

    public function edit($id = null)
    {
        if (!$this->Hostescalation->exists($id)) {
            throw new NotFoundException(__('Invalid hostescalation'));
        }
        $hostescalation = $this->Hostescalation->findById($id);

        if (!$this->allowedByContainerId($hostescalation['Hostescalation']['container_id'])) {
            $this->render403();

            return;
        }

        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOSTESCALATION, [], $this->hasRootPrivileges);

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($hostescalation['Hostescalation']['container_id']);
        $hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
        $hosts = $this->Host->hostsByContainerId($containerIds, 'list');
        $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
        $contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
        $contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');

        $this->Frontend->set('data_placeholder', __('Please choose'));
        $this->Frontend->set('data_placeholder_empty', __('No entries found'));


        if ($this->request->is('post') || $this->request->is('put')) {

            if ($this->request->data('Hostescalation.container_id') > 0 && $this->request->data('Hostescalation.container_id') != $hostescalation['Hostescalation']['container_id']) {
                $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->request->data('Hostescalation.container_id'));
                $hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
                $hosts = $this->Host->hostsByContainerId($containerIds, 'list');
                $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
                $contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
                $contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
            }

            $this->request->data['Contact']['Contact'] = $this->request->data['Hostescalation']['Contact'];
            $this->request->data['Contactgroup']['Contactgroup'] = $this->request->data['Hostescalation']['Contactgroup'];
            $_hosts = ($this->request->data['Hostescalation']['Host']) ? $this->request->data['Hostescalation']['Host'] : [];
            $_hosts_excluded = ($this->request->data('Hostescalation.Host_excluded')) ? $this->request->data['Hostescalation']['Host_excluded'] : [];
            $this->request->data['HostescalationHostMembership'] = [];
            $this->request->data['HostescalationHostMembership'] = $this->Hostescalation->parseHostMembershipData($_hosts, $_hosts_excluded);

            $_hostgroups = (is_array($this->request->data['Hostescalation']['Hostgroup'])) ? $this->request->data['Hostescalation']['Hostgroup'] : [];
            $_hostgroups_excluded = ($this->request->data('Hostescalation.Hostgroup_excluded')) ? $this->request->data['Hostescalation']['Hostgroup_excluded'] : [];
            $this->request->data['HostescalationHostgroupMembership'] = [];
            $this->request->data['HostescalationHostgroupMembership'] = $this->Hostescalation->parseHostgroupMembershipData($_hostgroups, $_hostgroups_excluded);

            $this->Hostescalation->set($this->request->data);
            if ($this->Hostescalation->validates()) {
                $this->Hostescalation->id = $id;
                $old_membership_hosts = $this->HostescalationHostMembership->find('all', [
                    'conditions' => [
                        'HostescalationHostMembership.hostescalation_id' => $id],
                ]);
                /* Delete old host associations */
                foreach ($old_membership_hosts as $old_membership_host) {
                    $this->HostescalationHostMembership->delete($old_membership_host['HostescalationHostMembership']['id']);

                }
                $old_membership_hostgroups = $this->HostescalationHostgroupMembership->find('all', [
                    'conditions' => [
                        'HostescalationHostgroupMembership.hostescalation_id' => $id],
                ]);
                /* Delete old hostgroup associations */
                foreach ($old_membership_hostgroups as $old_membership_hostgroup) {
                    $this->HostescalationHostgroupMembership->delete($old_membership_hostgroup['HostescalationHostgroupMembership']['id']);

                }
            }
            if ($this->Hostescalation->saveAll($this->request->data)) {
                $this->setFlash(__('Hostescalation successfully saved'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Hostescalation could not be saved'), false);
            }
        } else {
            $hostescalation['Hostescalation']['Host'] = Hash::combine($hostescalation['HostescalationHostMembership'], '{n}[excluded=0].host_id', '{n}[excluded=0].host_id');
            $hostescalation['Hostescalation']['Host_excluded'] = Hash::combine($hostescalation['HostescalationHostMembership'], '{n}[excluded=1].host_id', '{n}[excluded=1].host_id');
            $hostescalation['Hostescalation']['Hostgroup'] = Hash::combine($hostescalation['HostescalationHostgroupMembership'], '{n}[excluded=0].hostgroup_id', '{n}[excluded=0].hostgroup_id');
            $hostescalation['Hostescalation']['Hostgroup_excluded'] = Hash::combine($hostescalation['HostescalationHostgroupMembership'], '{n}[excluded=1].hostgroup_id', '{n}[excluded=1].hostgroup_id');
            $hostescalation['Hostescalation']['Contact'] = Hash::extract($hostescalation['Contact'], '{n}.id');
            $hostescalation['Hostescalation']['Contactgroup'] = Hash::extract($hostescalation['Contactgroup'], '{n}.id');
        }
        $this->request->data = Hash::merge($hostescalation, $this->request->data);

        $this->set(compact(['hostescalation', 'hosts', 'hostgroups', 'timeperiods', 'contactgroups', 'contacts', 'containers']));
    }

    public function add()
    {
        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOSTESCALATION, [], $this->hasRootPrivileges);

        $hosts = [];
        $hostgroups = [];
        $timeperiods = [];
        $contactgroups = [];
        $contacts = [];

        $this->Frontend->set('data_placeholder', __('Please choose'));
        $this->Frontend->set('data_placeholder_empty', __('No entries found'));

        if ($this->request->is('post') || $this->request->is('put')) {
            App::uses('UUID', 'Lib');
            $this->request->data['Hostescalation']['uuid'] = UUID::v4();

            $arrayKeys = [
                'Contact',
                'Contactgroup',
                'Host',
                'Host_excluded',
                'Hostgroup',
                'Hostgroup_excluded',
            ];
            foreach ($arrayKeys as $key) {
                if (!array_key_exists($key, $this->request->data['Hostescalation'])) {
                    $this->request->data['Hostescalation'][$key] = [];
                }
            }

            $this->request->data['Contact']['Contact'] = $this->request->data['Hostescalation']['Contact'];
            $this->request->data['Contactgroup']['Contactgroup'] = $this->request->data['Hostescalation']['Contactgroup'];
            $hosts = (is_array($this->request->data['Hostescalation']['Host'])) ? $this->request->data['Hostescalation']['Host'] : [];
            $hosts_excluded = (is_array($this->request->data['Hostescalation']['Host_excluded'])) ? $this->request->data['Hostescalation']['Host_excluded'] : [];
            $this->request->data['HostescalationHostMembership'] = [];
            $this->request->data['HostescalationHostMembership'] = $this->Hostescalation->parseHostMembershipData($hosts, $hosts_excluded);

            $hostgroups = (is_array($this->request->data['Hostescalation']['Hostgroup'])) ? $this->request->data['Hostescalation']['Hostgroup'] : [];
            $hostgroups_excluded = (is_array($this->request->data['Hostescalation']['Hostgroup_excluded'])) ? $this->request->data['Hostescalation']['Hostgroup_excluded'] : [];
            $this->request->data['HostescalationHostgroupMembership'] = $this->Hostescalation->parseHostgroupMembershipData($hostgroups, $hostgroups_excluded);

            $this->Hostescalation->set($this->request->data);

            if ($this->Hostescalation->saveAll($this->request->data)) {
                if ($this->isJsonRequest()) {
                    $this->serializeId();

                    return;
                } else {
                    $this->setFlash(__('Hostescalation successfully saved'));
                    $this->redirect(['action' => 'index']);
                }
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeErrorMessage();

                    return;
                } else {
                    $this->setFlash(__('Hostescalation could not be saved'), false);
                    $containerIds = $this->request->data('Hostescalation.container_id');
                    if ($containerIds > 0) {
                        $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerIds);
                        $hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
                        $hosts = $this->Host->hostsByContainerId($containerIds, 'list');
                        $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
                        $contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
                        $contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
                    }
                }
            }
        }
        $this->set(compact(['containers', 'hosts', 'hostgroups', 'timeperiods', 'contactgroups', 'contacts']));
    }

    public function delete($id = null)
    {
        if (!$this->Hostescalation->exists($id)) {
            throw new NotFoundException(__('Invalid hostescalation'));
        }
        $hostescalation = $this->Hostescalation->findById($id);
        if (!$this->allowedByContainerId($hostescalation['Hostescalation']['container_id'])) {
            $this->render403();

            return;
        }

        if ($this->Hostescalation->delete($id)) {
            $this->setFlash(__('Hostescalation deleted'));
            $this->redirect(['action' => 'index']);
        }
        $this->setFlash(__('Could not delete hostescalation'), false);
        $this->redirect(['action' => 'index']);
    }

    public function loadElementsByContainerId($containerId = null)
    {
        $this->allowOnlyAjaxRequests();
        if (!$this->Container->exists($containerId)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerId);

        $hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
        $hostgroups = $this->Host->makeItJavaScriptAble($hostgroups);
        $hostgroupsExcluded = $hostgroups;

        $hosts = $this->Host->hostsByContainerId($containerIds, 'list');
        $hosts = $this->Host->makeItJavaScriptAble($hosts);
        $hostsExcluded = $hosts;

        $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
        $timeperiods = $this->Host->makeItJavaScriptAble($timeperiods);

        $contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
        $contacts = $this->Host->makeItJavaScriptAble($contacts);

        $contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
        $contactgroups = $this->Host->makeItJavaScriptAble($contactgroups);

        $this->set(compact(['hosts', 'hostsExcluded', 'hostgroups', 'hostgroupsExcluded', 'timeperiods', 'contacts', 'contactgroups']));
        $this->set('_serialize', ['hosts', 'hostsExcluded', 'hostgroups', 'hostgroupsExcluded', 'timeperiods', 'contacts', 'contactgroups']);
    }
}
