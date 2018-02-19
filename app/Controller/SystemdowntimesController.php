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
use itnovum\openITCOCKPIT\Core\AngularJS\Request\AngularRequest;
use itnovum\openITCOCKPIT\Core\SystemdowntimesConditions;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Filter\SystemdowntimesFilter;


/**
 * @property Systemdowntime $Systemdowntime
 * @property Host $Host
 * @property Service $Service
 * @property Hostgroup $Hostgroup
 */
class SystemdowntimesController extends AppController {
    public $uses = [
        'Systemdowntime',
        'Host',
        'Service',
        'Hostgroup',
        'Container',
    ];
    public $components = [
        'Paginator',
        'ListFilter.ListFilter',
        'RequestHandler',
        'CustomValidationErrors',
        'GearmanClient',
    ];
    public $helpers = [
        'ListFilter.ListFilter',
        'Status',
        'Monitoring',
        'CustomValidationErrors',
        'Uuid',
    ];
    public $layout = 'Admin.default';


    public function index() {
        if (isset($this->PERMISSIONS['systemdowntimes']['host'])) {
            $this->redirect(['action' => 'host']);
        }

        if (isset($this->PERMISSIONS['systemdowntimes']['service'])) {
            $this->redirect(['action' => 'service']);
        }

        if (isset($this->PERMISSIONS['systemdowntimes']['hostgroup'])) {
            $this->redirect(['action' => 'hostgroup']);
        }

        if (isset($this->PERMISSIONS['systemdowntimes']['node'])) {
            $this->redirect(['action' => 'node']);
        }

        $this->render403();
    }

    public function host() {
        $this->layout = 'angularjs';

        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        $SystemdowntimesFilter = new SystemdowntimesFilter($this->request);
        $AngularRequest = new AngularRequest($this->request);
        $Conditions = new SystemdowntimesConditions();

        $Conditions->setContainerIds($this->MY_RIGHTS);
        $Conditions->setOrder($AngularRequest->getOrderForPaginator('Systemdowntime.from_time', 'desc'));


        $this->Paginator->settings = $this->Systemdowntime->getRecurringHostDowntimesQuery($Conditions, $SystemdowntimesFilter->hostFilter());
        $this->Paginator->settings['page'] = $AngularRequest->getPage();

        $hostRecurringDowntimes = $this->Paginator->paginate(
            $this->Systemdowntime->alias,
            [],
            [key($this->Paginator->settings['order'])]
        );

        $all_host_recurring_downtimes = [];
        foreach ($hostRecurringDowntimes as $hostRecurringDowntime) {
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($hostRecurringDowntime);
            $Systemdowntime = new \itnovum\openITCOCKPIT\Core\Views\Systemdowntime($hostRecurringDowntime);
            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $Host->getContainerIds());
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $tmpRecord = [
                'Host'           => $Host->toArray(),
                'Systemdowntime' => $Systemdowntime->toArray()
            ];
            $tmpRecord['Host']['allow_edit'] = $allowEdit;
            $all_host_recurring_downtimes[] = $tmpRecord;
        }

        $this->set('all_host_recurring_downtimes', $all_host_recurring_downtimes);
        $this->set('_serialize', ['all_host_recurring_downtimes', 'paging']);

    }

    public function service() {
        $this->layout = 'angularjs';
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        $SystemdowntimesFilter = new SystemdowntimesFilter($this->request);
        $AngularRequest = new AngularRequest($this->request);
        $Conditions = new SystemdowntimesConditions();

        $Conditions->setContainerIds($this->MY_RIGHTS);
        $Conditions->setOrder($AngularRequest->getOrderForPaginator('Systemdowntime.from_time', 'desc'));


        $this->Paginator->settings = $this->Systemdowntime->getRecurringServiceDowntimesQuery($Conditions, $SystemdowntimesFilter->serviceFilter());
        $this->Paginator->settings['page'] = $AngularRequest->getPage();

        $serviceRecurringDowntimes = $this->Paginator->paginate(
            $this->Systemdowntime->alias,
            [],
            [key($this->Paginator->settings['order'])]
        );


        $hostContainers = [];
        if (!empty($serviceRecurringDowntimes) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
            $hostIds = array_unique(Hash::extract($serviceRecurringDowntimes, '{n}.Host.id'));
            $_hostContainers = $this->Host->find('all', [
                'contain'    => [
                    'Container',
                ],
                'fields'     => [
                    'Host.id',
                    'Container.*',
                ],
                'conditions' => [
                    'Host.id' => $hostIds,
                ],
            ]);
            foreach ($_hostContainers as $host) {
                $hostContainers[$host['Host']['id']] = Hash::extract($host['Container'], '{n}.id');
            }
        }


        $all_service_recurring_downtimes = [];
        foreach ($serviceRecurringDowntimes as $serviceRecurringDowntime) {
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($serviceRecurringDowntime);
            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($serviceRecurringDowntime);
            $Systemdowntime = new \itnovum\openITCOCKPIT\Core\Views\Systemdowntime($serviceRecurringDowntime);
            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $containerIds = [];
                if (isset($hostContainers[$service['Host']['id']])) {
                    $containerIds = $hostContainers[$serviceRecurringDowntime['Host']['id']];
                }
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIds);
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $tmpRecord = [
                'Host'           => $Host->toArray(),
                'Service'        => $Service->toArray(),
                'Systemdowntime' => $Systemdowntime->toArray()
            ];
            $tmpRecord['Host']['allow_edit'] = $allowEdit;
            $tmpRecord['Service']['allow_edit'] = $allowEdit;
            $all_service_recurring_downtimes[] = $tmpRecord;
        }

        $this->set('all_service_recurring_downtimes', $all_service_recurring_downtimes);
        $this->set('_serialize', ['all_service_recurring_downtimes', 'paging']);
    }

    public function hostgroup() {
        $this->layout = 'angularjs';

        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        $SystemdowntimesFilter = new SystemdowntimesFilter($this->request);
        $AngularRequest = new AngularRequest($this->request);
        $Conditions = new SystemdowntimesConditions();

        $Conditions->setContainerIds($this->MY_RIGHTS);
        $Conditions->setOrder($AngularRequest->getOrderForPaginator('Systemdowntime.from_time', 'desc'));


        $this->Paginator->settings = $this->Systemdowntime->getRecurringHostgroupDowntimesQuery($Conditions, $SystemdowntimesFilter->hostgroupFilter());
        $this->Paginator->settings['page'] = $AngularRequest->getPage();

        $hostgroupRecurringDowntimes = $this->Paginator->paginate(
            $this->Systemdowntime->alias,
            [],
            [key($this->Paginator->settings['order'])]
        );

        $all_hostgroup_recurring_downtimes = [];
        foreach ($hostgroupRecurringDowntimes as $hostgroupRecurringDowntime) {
            $Systemdowntime = new \itnovum\openITCOCKPIT\Core\Views\Systemdowntime($hostgroupRecurringDowntime);
            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, [$hostgroupRecurringDowntime['Hostgroup']['container_id']]);
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $tmpRecord = [
                'Systemdowntime' => $Systemdowntime->toArray(),
                'Container'      => $hostgroupRecurringDowntime['Container'],
                'Hostgroup'      => $hostgroupRecurringDowntime['Hostgroup']
            ];
            $tmpRecord['Hostgroup']['allow_edit'] = $allowEdit;
            $all_hostgroup_recurring_downtimes[] = $tmpRecord;
        }

        $this->set('all_hostgroup_recurring_downtimes', $all_hostgroup_recurring_downtimes);
        $this->set('_serialize', ['all_hostgroup_recurring_downtimes', 'paging']);
    }

    public function node() {
        $this->layout = 'angularjs';

        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        $SystemdowntimesFilter = new SystemdowntimesFilter($this->request);
        $AngularRequest = new AngularRequest($this->request);
        $Conditions = new SystemdowntimesConditions();

        $Conditions->setContainerIds($this->MY_RIGHTS);
        $Conditions->setOrder($AngularRequest->getOrderForPaginator('Systemdowntime.from_time', 'desc'));


        $this->Paginator->settings = $this->Systemdowntime->getRecurringNodeDowntimesQuery($Conditions, $SystemdowntimesFilter->nodeFilter());
        $this->Paginator->settings['page'] = $AngularRequest->getPage();

        $nodeRecurringDowntimes = $this->Paginator->paginate(
            $this->Systemdowntime->alias,
            [],
            [key($this->Paginator->settings['order'])]
        );

        $all_node_recurring_downtimes = [];
        foreach ($nodeRecurringDowntimes as $nodeRecurringDowntime) {
            $Systemdowntime = new \itnovum\openITCOCKPIT\Core\Views\Systemdowntime($nodeRecurringDowntime);
            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, [$nodeRecurringDowntime['Container']['id']]);
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $tmpRecord = [
                'Systemdowntime' => $Systemdowntime->toArray(),
                'Container'      => $nodeRecurringDowntime['Container'],
            ];
            $tmpRecord['Container']['allow_edit'] = $allowEdit;
            $all_node_recurring_downtimes[] = $tmpRecord;
        }

        $this->set('all_node_recurring_downtimes', $all_node_recurring_downtimes);
        $this->set('_serialize', ['all_node_recurring_downtimes', 'paging']);
    }

    public function addHostdowntime() {
        $this->layout = 'angularjs';

        if (!$this->isAngularJsRequest()) {
            $this->set('back_url', $this->referer());
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['Systemdowntime']['weekdays']) && is_array($this->request->data['Systemdowntime']['weekdays'])) {
                $this->request->data['Systemdowntime']['weekdays'] = implode(',', $this->request->data['Systemdowntime']['weekdays']);
            }

            $isRecurringDowntime = ($this->request->data('Systemdowntime.is_recurring') == 1);
            $this->request->data = $this->_rewritePostData();

            if ($isRecurringDowntime) {
                $this->Systemdowntime->validate = $this->Systemdowntime->getValidationRulesForRecurringDowntimes();
                $this->Systemdowntime->set($this->request->data);
                if ($this->Systemdowntime->validateMany($this->request->data)) {
                    $this->Systemdowntime->create();
                    if ($this->Systemdowntime->saveAll($this->request->data)) {
                        if ($this->isAngularJsRequest()) {
                            $this->setFlash(__('Recurring Downtime successfully saved'));
                            $this->set('success', true);
                            $this->set('_serialize', ['success']);
                        }
                        $this->serializeId();
                    }
                } else {
                    $this->serializeErrorMessage();
                }
                return;
            }

            if ($isRecurringDowntime === false) {
                $this->Systemdowntime->set($this->request->data);
                if ($this->Systemdowntime->validateMany($this->request->data)) {
                    foreach ($this->request->data as $request) {
                        $start = strtotime(
                            sprintf(
                                '%s %s',
                                $request['Systemdowntime']['from_date'],
                                $request['Systemdowntime']['from_time']
                            ));
                        $end = strtotime(
                            sprintf('%s %s',
                                $request['Systemdowntime']['to_date'],
                                $request['Systemdowntime']['to_time']
                            ));

                        $host = $this->Host->find('first', [
                            'recursive'  => -1,
                            'fields'     => [
                                'Host.uuid'
                            ],
                            'conditions' => [
                                'Host.id' => $request['Systemdowntime']['object_id']
                            ]
                        ]);
                        $payload = [
                            'hostUuid'     => $host['Host']['uuid'],
                            'downtimetype' => $request['Systemdowntime']['downtimetype_id'],
                            'start'        => $start,
                            'end'          => $end,
                            'comment'      => $request['Systemdowntime']['comment'],
                            'author'       => $this->Auth->user('full_name'),
                        ];
                        $this->GearmanClient->sendBackground('createHostDowntime', $payload);
                    }
                    $this->setFlash(__('Downtime/s successfully created'));
                    $this->set('success', true);
                    $this->set('_serialize', ['success']);
                    return;
                }
                $this->serializeErrorMessage();
                return;
            }
        }
    }


    public function addHostgroupdowntime() {
        $this->layout = 'angularjs';
        if (!$this->isAngularJsRequest()) {
            $this->set('back_url', $this->referer());
        }


        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['Systemdowntime']['weekdays']) && is_array($this->request->data['Systemdowntime']['weekdays'])) {
                $this->request->data['Systemdowntime']['weekdays'] = implode(',', $this->request->data['Systemdowntime']['weekdays']);
            }

            $isRecurringDowntime = ($this->request->data('Systemdowntime.is_recurring') == 1);
            $this->request->data = $this->_rewritePostData();

            if ($isRecurringDowntime) {
                $this->Systemdowntime->validate = $this->Systemdowntime->getValidationRulesForRecurringDowntimes();
                $this->Systemdowntime->set($this->request->data);
                if ($this->Systemdowntime->validateMany($this->request->data)) {
                    $this->Systemdowntime->create();
                    if ($this->Systemdowntime->saveAll($this->request->data)) {
                        if ($this->isAngularJsRequest()) {
                            $this->setFlash(__('Recurring Downtime successfully saved'));
                            $this->set('success', true);
                            $this->set('_serialize', ['success']);
                        }
                        $this->serializeId();
                    }
                } else {
                    $this->serializeErrorMessage();
                }
                return;
            }

            if ($isRecurringDowntime === false) {
                $this->Systemdowntime->set($this->request->data);
                if ($this->Systemdowntime->validateMany($this->request->data)) {
                    foreach ($this->request->data as $request) {
                        $start = strtotime(
                            sprintf(
                                '%s %s',
                                $request['Systemdowntime']['from_date'],
                                $request['Systemdowntime']['from_time']
                            ));
                        $end = strtotime(
                            sprintf('%s %s',
                                $request['Systemdowntime']['to_date'],
                                $request['Systemdowntime']['to_time']
                            ));

                        $hostgroup = $this->Hostgroup->find('first', [
                            'recursive'  => -1,
                            'conditions' => [
                                'Hostgroup.id' => $request['Systemdowntime']['object_id'],
                            ],
                            'fields'     => [
                                'Hostgroup.uuid',
                            ],
                        ]);

                        $payload = [
                            'hostgroupUuid' => $hostgroup['Hostgroup']['uuid'],
                            'downtimetype'  => $request['Systemdowntime']['downtimetype_id'],
                            'start'         => $start,
                            'end'           => $end,
                            'comment'       => $request['Systemdowntime']['comment'],
                            'author'        => $this->Auth->user('full_name'),
                        ];

                        $this->GearmanClient->sendBackground('createHostgroupDowntime', $payload);
                    }
                    $this->setFlash(__('Downtime/s successfully created'));
                    $this->set('success', true);
                    $this->set('_serialize', ['success']);
                    return;
                }
                $this->serializeErrorMessage();
                return;
            }
        }
    }

    public function addServicedowntime() {
        $this->layout = 'angularjs';
        if (!$this->isAngularJsRequest()) {
            $this->set('back_url', $this->referer());
        }


        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['Systemdowntime']['weekdays']) && is_array($this->request->data['Systemdowntime']['weekdays'])) {
                $this->request->data['Systemdowntime']['weekdays'] = implode(',', $this->request->data['Systemdowntime']['weekdays']);
            }

            $isRecurringDowntime = ($this->request->data('Systemdowntime.is_recurring') == 1);
            $this->request->data = $this->_rewritePostData();

            if ($isRecurringDowntime) {
                $this->Systemdowntime->validate = $this->Systemdowntime->getValidationRulesForRecurringDowntimes();
                $this->Systemdowntime->set($this->request->data);
                if ($this->Systemdowntime->validateMany($this->request->data)) {
                    $this->Systemdowntime->create();
                    if ($this->Systemdowntime->saveAll($this->request->data)) {
                        if ($this->isAngularJsRequest()) {
                            $this->setFlash(__('Recurring Downtime successfully saved'));
                            $this->set('success', true);
                            $this->set('_serialize', ['success']);
                        }
                        $this->serializeId();
                    }
                } else {
                    $this->serializeErrorMessage();
                }
                return;
            }


            if ($isRecurringDowntime === false) {
                $this->Systemdowntime->set($this->request->data);
                if ($this->Systemdowntime->validateMany($this->request->data)) {
                    foreach ($this->request->data as $request) {
                        $start = strtotime(
                            sprintf(
                                '%s %s',
                                $request['Systemdowntime']['from_date'],
                                $request['Systemdowntime']['from_time']
                            ));
                        $end = strtotime(
                            sprintf('%s %s',
                                $request['Systemdowntime']['to_date'],
                                $request['Systemdowntime']['to_time']
                            ));

                        $service = $this->Service->find('first', [
                            'recursive'  => -1,
                            'contain'    => [
                                'Host' => [
                                    'fields' => [
                                        'Host.uuid'
                                    ]
                                ]
                            ],
                            'fields'     => [
                                'Service.uuid'
                            ],
                            'conditions' => [
                                'Service.id' => $request['Systemdowntime']['object_id']
                            ]
                        ]);
                        $payload = [
                            'hostUuid'    => $service['Host']['uuid'],
                            'serviceUuid' => $service['Service']['uuid'],
                            'start'       => $start,
                            'end'         => $end,
                            'comment'     => $request['Systemdowntime']['comment'],
                            'author'      => $this->Auth->user('full_name'),
                        ];
                        $this->GearmanClient->sendBackground('createServiceDowntime', $payload);
                    }
                    $this->setFlash(__('Downtime/s successfully created'));
                    $this->set('success', true);
                    $this->set('_serialize', ['success']);
                    return;
                }
                $this->serializeErrorMessage();
                return;
            }
        }
    }


    public function addContainerdowntime() {
        $this->layout = 'angularjs';

        if (!$this->isAngularJsRequest()) {
            $this->set('back_url', $this->referer());
        }


        $childrenContainers = [];

        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['Systemdowntime']['weekdays']) && is_array($this->request->data['Systemdowntime']['weekdays'])) {
                $this->request->data['Systemdowntime']['weekdays'] = implode(',', $this->request->data['Systemdowntime']['weekdays']);
            }

            $isRecurringDowntime = ($this->request->data('Systemdowntime.is_recurring') == 1);

            if ($this->request->data('Systemdowntime.inherit_downtime') == 1) {
                $childrenContainers = [];

                foreach($this->request->data('Systemdowntime.object_id') as $containerId){
                    if($containerId == ROOT_CONTAINER) {
                        $childrenContainers = $this->Tree->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
                    }else{
                        $childrenContainers = $this->Tree->resolveChildrenOfContainerIds($this->request->data('Systemdowntime.object_id'));
                        $childrenContainers = $this->Tree->removeRootContainer($childrenContainers);
                    }
                }


                $objectIds = [];
                foreach ($childrenContainers as $childrenContainer) {
                    if (isset($this->MY_RIGHTS_LEVEL[$childrenContainer])) {
                        if ((int)$this->MY_RIGHTS_LEVEL[$childrenContainer] === WRITE_RIGHT) {
                            $objectIds[] = (int)$childrenContainer;
                        }
                    }
                }
                $this->request->data['Systemdowntime']['object_id'] = $objectIds;
            }


            if ($isRecurringDowntime) {
                $this->request->data = $this->_rewritePostData();
                $this->Systemdowntime->validate = $this->Systemdowntime->getValidationRulesForRecurringDowntimes();
                $this->Systemdowntime->set($this->request->data);
                if ($this->Systemdowntime->validateMany($this->request->data)) {
                    $this->Systemdowntime->create();
                    if ($this->Systemdowntime->saveAll($this->request->data)) {
                        if ($this->isAngularJsRequest()) {
                            $this->setFlash(__('Recurring Downtime successfully saved'));
                            $this->set('success', true);
                            $this->set('_serialize', ['success']);
                        }
                        $this->serializeId();
                    }
                } else {
                    $this->serializeErrorMessage();
                }
                return;
            }

            if ($isRecurringDowntime === false) {

                $postDataForValidate = $this->_rewritePostData();
                $this->Systemdowntime->set($postDataForValidate);
                if ($this->Systemdowntime->validateMany($postDataForValidate)) {
                    $hosts = $this->Host->hostsByContainerId(
                        $this->request->data['Systemdowntime']['object_id'],
                        'list',
                        ['Host.disabled' => 0],
                        'uuid'
                    );
                    foreach ($hosts as $hostUuid => $hostName) {
                        $start = strtotime(
                            sprintf(
                                '%s %s',
                                $this->request->data['Systemdowntime']['from_date'],
                                $this->request->data['Systemdowntime']['from_time']
                            ));
                        $end = strtotime(
                            sprintf('%s %s',
                                $this->request->data['Systemdowntime']['to_date'],
                                $this->request->data['Systemdowntime']['to_time']
                            ));

                        $payload = [
                            'hostUuid'     => $hostUuid,
                            'downtimetype' => $this->request->data['Systemdowntime']['downtimetype_id'],
                            'start'        => $start,
                            'end'          => $end,
                            'comment'      => $this->request->data['Systemdowntime']['comment'],
                            'author'       => $this->Auth->user('full_name'),
                        ];
                        $this->GearmanClient->sendBackground('createHostDowntime', $payload);
                    }
                    $this->setFlash(__('Downtime/s successfully created'));
                    $this->set('success', true);
                    $this->set('_serialize', ['success']);
                    return;
                }
                $this->serializeErrorMessage();
                return;
            }
        }
    }


    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }


        $this->Systemdowntime->id = $id;
        if (!$this->Systemdowntime->exists()) {
            throw new NotFoundException(__('Invalide downtime'));
        }

        $systemdowntime = $this->Systemdowntime->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Systemdowntime.id' => $id
            ]
        ]);

        if (empty($systemdowntime)) {
            return;
        }


        if ($this->Systemdowntime->delete($systemdowntime['Systemdowntime']['id'])) {
            $this->set('success', true);
            $this->set('message', __('Systemdowntime successfully deleted'));
            $this->set('_serialize', ['success', 'message']);
            return;
        }
        $this->set('success', false);
        $this->set('message', __('Error while deleting systemdowntime'));
        $this->set('_serialize', ['success', 'message']);

    }

    private function _rewritePostData() {
        /*
        why we need this function? The problem is, may be a user want to save the downtime for more that one host. the array we get from $this->request->data looks like this:
            array(
                'Systemdowntime' => array(
                    'downtimetype' => 'host',
                    'object_id' => array(
                        (int) 0 => '1',
                        (int) 1 => '2'
                    ),
                    'downtimetype_id' => '0',
                    'comment' => 'In maintenance',
                    'is_recurring' => '1',
                    'weekdays' => '1',
                    'recurring_days_month' => '1',
                    'from_date' => '11.09.2014',
                    'from_time' => '99:99',
                    'to_date' => '14.09.2014',
                    'to_time' => '06:09'
                )
            )

        the big problem is the object_id, this throws us an "Array to string conversion". So we need to rewrite the post array fo some like this:

        array(
            (int) 0 => array(
                'Systemdowntime' => array(
                    'downtimetype' => 'host',
                    'object_id' => '2',
                    'downtimetype_id' => '0',
                    'comment' => 'In maintenance',
                    'is_recurring' => '1',
                    'weekdays' => '',
                    'recurring_days_month' => 'asdadasd',
                    'from_date' => '11.09.2014',
                    'from_time' => '06:09',
                    'to_date' => '14.09.2014',
                    'to_time' => '06:09'
                )
            ),
            (int) 1 => array(
                'Systemdowntime' => array(
                    'downtimetype' => 'host',
                    'object_id' => '3',
                    'downtimetype_id' => '0',
                    'comment' => 'In maintenance',
                    'is_recurring' => '1',
                    'weekdays' => '',
                    'recurring_days_month' => 'asdadasd',
                    'from_date' => '11.09.2014',
                    'from_time' => '06:09',
                    'to_date' => '14.09.2014',
                    'to_time' => '06:09'
                )
            )
        )

        */
        if (empty($this->request->data('Systemdowntime.object_id'))) {
            return [$this->request->data];
        }
        $return = [];
        if (is_array($this->request->data['Systemdowntime']['object_id'])) {
            foreach ($this->request->data['Systemdowntime']['object_id'] as $object_id) {
                $tmp['Systemdowntime'] = $this->request->data['Systemdowntime'];
                $tmp['Systemdowntime']['object_id'] = $object_id;
                $tmp['Systemdowntime']['author'] = $this->Auth->user('full_name');
                $return[] = $tmp;
            }
        }
        return $return;

    }
}
