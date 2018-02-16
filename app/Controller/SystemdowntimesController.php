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
use itnovum\openITCOCKPIT\Core\SystemdowntimesHostConditions;
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


        $all_host_recurring_downtimes = [];
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
            $all_host_recurring_downtimes[] = $tmpRecord;
        }

        $this->set('all_host_recurring_downtimes', $all_host_recurring_downtimes);
        $this->set('_serialize', ['all_host_recurring_downtimes', 'paging']);
    }

    public function hostgroup() {

    }

    public function node() {

    }

    public function addHostdowntime() {
        $this->layout = 'angularjs';
        $flashmessage = "";

        $selected = $this->request->data('Systemdowntime.object_id');
        if (empty($selected) && !empty($this->request->params['named']['host_id'])) {
            $selected[] = $this->request->params['named']['host_id'];
        }

        $preselectedDowntimetype = $this->Systemsetting->findByKey("FRONTEND.PRESELECTED_DOWNTIME_OPTION");
        $this->set('preselectedDowntimetype', $preselectedDowntimetype['Systemsetting']['value']);

        $this->Frontend->setJson('dateformat', MY_DATEFORMAT);

        $customFildsToRefill = [
            'Systemdowntime' => [
                'from_date',
                'from_time',
                'to_date',
                'to_time',
                'duration',
                'is_recurring',
                'weekdays',
                'day_of_month'
            ],
        ];
        $this->CustomValidationErrors->checkForRefill($customFildsToRefill);

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $writeContainerIds = [];
        foreach ($containerIds as $containerId) {
            if (isset($this->MY_RIGHTS_LEVEL[$containerId]) && $this->MY_RIGHTS_LEVEL[$containerId] == WRITE_RIGHT) {
                $writeContainerIds[] = $containerId;
            }

        }

        //$hosts = $this->Host->hostsByContainerId($writeContainerIds, 'list');
        //$this->set(compact(['hosts', 'selected']));
        $this->set('back_url', $this->referer());

        if ($this->request->is('post') || $this->request->is('put')) {

            if (isset($this->request->data['Systemdowntime']['weekdays']) && is_array($this->request->data['Systemdowntime']['weekdays'])) {
                $this->request->data['Systemdowntime']['weekdays'] = implode(',', $this->request->data['Systemdowntime']['weekdays']);
            }
            $this->request->data = $this->_rewritePostData();

            //Try validate the data:
            foreach ($this->request->data as $request) {
                if ($request['Systemdowntime']['is_recurring']) {
                    $this->Systemdowntime->validate = Hash::merge(
                        $this->Systemdowntime->validate,
                        [
                            'from_date' => [
                                'notBlank' => [
                                    'required'   => false,
                                    'allowEmpty' => true,
                                ],
                            ],
                            'to_date'   => [
                                'notBlank' => [
                                    'required'   => false,
                                    'allowEmpty' => true,
                                ]
                            ],
                            'from_time' => [
                                'notBlank' => [
                                    'required'   => true,
                                    'allowEmpty' => false,
                                ],
                            ],
                            'to_time'   => [
                                'notBlank' => [
                                    'required'   => false,
                                    'allowEmpty' => true,
                                ],
                            ]
                        ]
                    );
                }
                $this->Systemdowntime->set($request);

                if ($this->Systemdowntime->validates()) {
                    /* The data is valide and we can save it.
                     * We need to use the foreach, becasue validates() cant handel saveAll() data :(
                     *
                     * How ever, at this point we passed the validation, so the data is valid.
                     * Now we need to check, if this is just a downtime, or an recurring downtime, because
                     * these guys we want to save in our systemdowntimestable
                     * Normal downtimes, will be sent to sudo_servers unix socket.
                     */
                    if ($request['Systemdowntime']['is_recurring'] == 1) {
                        $this->Systemdowntime->create();
                        if ($this->Systemdowntime->save($request) && $this->request->ext === 'json') {
                            if ($this->isAngularJsRequest()) {
                                $flashmessage = __('Recurring Downtime successfully saved');
                            }
                            $this->serializeId();
                        } else {
                            $this->serializeErrorMessage();
                        }
                    } else {
                        $start = strtotime($request['Systemdowntime']['from_date'] . ' ' . $request['Systemdowntime']['from_time']);
                        $end = strtotime($request['Systemdowntime']['to_date'] . ' ' . $request['Systemdowntime']['to_time']);

                        //Just a normal nagios downtime
                        if ($request['Systemdowntime']['downtimetype'] == 'host') {

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
                        if ($this->request->ext === 'json') {
                            if ($this->isAngularJsRequest()) {
                                $flashmessage = __('Downtime successfully saved');
                            }
                            $this->serializeId();
                        }
                    }
                } else {
                    $this->CustomValidationErrors->loadModel($this->Systemdowntime);
                    $this->CustomValidationErrors->customFields(['from_date', 'from_time', 'to_date', 'to_time', 'downtimetype']);
                    $this->CustomValidationErrors->fetchErrors();
                    $this->serializeErrorMessage();
                    return;
                }
            }
            $this->setFlash($flashmessage);
            // $this->redirect(['controller' => 'downtimes','action' => 'index']);
        }
    }

    public function addHostgroupdowntime() {
        $this->layout = 'angularjs';
        $flashmessage = "";

        $this->Frontend->setJson('dateformat', MY_DATEFORMAT);
        $selected = $this->request->data('Systemdowntime.object_id');

        $customFildsToRefill = [
            'Systemdowntime' => [
                'from_date',
                'from_time',
                'to_date',
                'to_time',
                'duration',
                'is_recurring',
                'weekdays',
                'day_of_month'
            ],
        ];
        $this->CustomValidationErrors->checkForRefill($customFildsToRefill);

        $preselectedDowntimetype = $this->Systemsetting->findByKey("FRONTEND.PRESELECTED_DOWNTIME_OPTION");
        $this->set('preselectedDowntimetype', $preselectedDowntimetype['Systemsetting']['value']);

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $writeContainerIds = [];
        foreach ($containerIds as $containerId) {
            if (isset($this->MY_RIGHTS_LEVEL[$containerId]) && $this->MY_RIGHTS_LEVEL[$containerId] == WRITE_RIGHT) {
                $writeContainerIds[] = $containerId;
            }

        }
        //$hostgroups = $this->Hostgroup->hostgroupsByContainerId($writeContainerIds,'list','id');
        //$this->set(compact(['hostgroups','selected']));
        $this->set('back_url', $this->referer());

        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['Systemdowntime']['weekdays']) && is_array($this->request->data['Systemdowntime']['weekdays'])) {
                $this->request->data['Systemdowntime']['weekdays'] = implode(',', $this->request->data['Systemdowntime']['weekdays']);
            }
            $this->request->data = $this->_rewritePostData();
            //Try validate the data:
            foreach ($this->request->data as $request) {
                if ($request['Systemdowntime']['is_recurring']) {
                    $this->Systemdowntime->validate = Hash::merge(
                        $this->Systemdowntime->validate,
                        [
                            'from_date' => [
                                'notBlank' => [
                                    'required'   => false,
                                    'allowEmpty' => true,
                                ],
                            ],
                            'to_date'   => [
                                'notBlank' => [
                                    'required'   => false,
                                    'allowEmpty' => true,
                                ]
                            ],
                            'from_time' => [
                                'notBlank' => [
                                    'required'   => true,
                                    'allowEmpty' => false,
                                ],
                            ],
                            'to_time'   => [
                                'notBlank' => [
                                    'required'   => false,
                                    'allowEmpty' => true,
                                ],
                            ]
                        ]
                    );
                }
                $this->Systemdowntime->set($request);
                if ($this->Systemdowntime->validates()) {
                    /* The data is valide and we can save it.
                     * We need to use the foreach, becasue validates() cant handel saveAll() data :(
                     *
                     * How ever, at this point we passed the validation, so the data is valid.
                     * Now we need to check, if this is just a downtime, or an recurring downtime, because
                     * these guys we want to save in our systemdowntimestable
                     * Normal downtimes, will be sent to sudo_servers unix socket.
                     */

                    if ($request['Systemdowntime']['is_recurring'] == 1) {
                        $this->Systemdowntime->create();
                        if ($this->Systemdowntime->save($request) && $this->request->ext === 'json') {
                            if ($this->isAngularJsRequest()) {
                                $flashmessage = __('Recurring hg Downtime successfully saved');
                            }
                            $this->serializeId();
                        } else {
                            $this->serializeErrorMessage();
                        }
                    } else {
                        $start = strtotime($request['Systemdowntime']['from_date'] . ' ' . $request['Systemdowntime']['from_time']);
                        $end = strtotime($request['Systemdowntime']['to_date'] . ' ' . $request['Systemdowntime']['to_time']);
                        //Just a normal nagios downtime
                        if ($request['Systemdowntime']['downtimetype'] == 'hostgroup') {
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
                        if ($this->request->ext === 'json') {
                            if ($this->isAngularJsRequest()) {
                                $flashmessage = __('Downtime successfully saved');
                            }
                            $this->serializeId();
                        }
                    }

                } else {
                    //$flashmessage=__('Downtime could not be saved');
                    $this->CustomValidationErrors->loadModel($this->Systemdowntime);
                    $this->CustomValidationErrors->customFields(['from_date', 'from_time', 'to_date', 'to_time', 'downtimetype']);
                    $this->CustomValidationErrors->fetchErrors();
                    $this->serializeErrorMessage();
                    return;
                }
            }
            $this->setFlash($flashmessage);
            //$this->redirect(['controller' => 'downtimes','action' => 'index']);
        }
    }

    public function addServicedowntime() {
        $this->layout = 'angularjs';
        $flashmessage = "";

        $this->Frontend->setJson('dateformat', MY_DATEFORMAT);
        $selected = $this->request->data('Systemdowntime.object_id');
        if (empty($selected) && !empty($this->request->params['named']['service_id'])) {
            $selected[] = $this->request->params['named']['service_id'];
        }

        $customFildsToRefill = [
            'Systemdowntime' => [
                'from_date',
                'from_time',
                'to_date',
                'to_time',
                'duration',
                'is_recurring',
                'weekdays',
                'day_of_month'
            ],
        ];
        $this->CustomValidationErrors->checkForRefill($customFildsToRefill);

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $writeContainerIds = [];
        foreach ($containerIds as $containerId) {
            if (isset($this->MY_RIGHTS_LEVEL[$containerId]) && $this->MY_RIGHTS_LEVEL[$containerId] == WRITE_RIGHT) {
                $writeContainerIds[] = $containerId;
            }

        }

        //$services = $this->Service->servicesByHostContainerIds($writeContainerIds);
        //$services = Hash::combine($services, '{n}.Service.id', ['%s/%s', '{n}.Host.name', '{n}.{n}.ServiceDescription'], '{n}.Host.name');

        //$this->set(compact(['services','selected']));
        $this->set('back_url', $this->referer());

        if ($this->request->is('post') || $this->request->is('put')) {

            if (isset($this->request->data['Systemdowntime']['weekdays']) && is_array($this->request->data['Systemdowntime']['weekdays'])) {
                $this->request->data['Systemdowntime']['weekdays'] = implode(',', $this->request->data['Systemdowntime']['weekdays']);
            }

            $this->request->data = $this->_rewritePostData();
            //Try validate the data:
            foreach ($this->request->data as $request) {
                if ($request['Systemdowntime']['is_recurring']) {
                    $this->Systemdowntime->validate = Hash::merge(
                        $this->Systemdowntime->validate,
                        [
                            'from_date' => [
                                'notBlank' => [
                                    'required'   => false,
                                    'allowEmpty' => true,
                                ],
                            ],
                            'to_date'   => [
                                'notBlank' => [
                                    'required'   => false,
                                    'allowEmpty' => true,
                                ]
                            ],
                            'from_time' => [
                                'notBlank' => [
                                    'required'   => true,
                                    'allowEmpty' => false,
                                ],
                            ],
                            'to_time'   => [
                                'notBlank' => [
                                    'required'   => false,
                                    'allowEmpty' => true,
                                ],
                            ]
                        ]
                    );
                }
                $this->Systemdowntime->set($request);
                if ($this->Systemdowntime->validates()) {
                    /* The data is valide and we can save it.
                     * We need to use the foreach, becasue validates() cant handel saveAll() data :(
                     *
                     * How ever, at this point we passed the validation, so the data is valid.
                     * Now we need to check, if this is just a downtime, or an recurring downtime, because
                     * these guys we want to save in our systemdowntimestable
                     * Normal downtimes, will be sent to sudo_servers unix socket.
                     */
                    if ($request['Systemdowntime']['is_recurring'] == 1) {
                        $this->Systemdowntime->create();
                        if ($this->Systemdowntime->save($request) && $this->request->ext === 'json') {
                            if ($this->isAngularJsRequest()) {
                                $flashmessage = __('Recurring Downtime successfully saved');
                            }
                            $this->serializeId();
                        } else {
                            $this->serializeErrorMessage();
                        }
                    } else {
                        $start = strtotime($request['Systemdowntime']['from_date'] . ' ' . $request['Systemdowntime']['from_time']);
                        $end = strtotime($request['Systemdowntime']['to_date'] . ' ' . $request['Systemdowntime']['to_time']);
                        //Just a normal nagios downtime
                        if ($request['Systemdowntime']['downtimetype'] == 'service') {
                            $service = $this->Service->findById($request['Systemdowntime']['object_id']);
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
                        if ($this->request->ext === 'json') {
                            if ($this->isAngularJsRequest()) {
                                $flashmessage = __('Downtime successfully saved');
                            }
                            $this->serializeId();
                        }
                    }

                } else {
                    //$flashmessage=__('Downtime could not be saved');
                    $this->CustomValidationErrors->loadModel($this->Systemdowntime);
                    $this->CustomValidationErrors->customFields(['from_date', 'from_time', 'to_date', 'to_time', 'downtimetype']);
                    $this->CustomValidationErrors->fetchErrors();
                    $this->serializeErrorMessage();
                    return;
                }
            }
            $this->setFlash($flashmessage);
            //$this->redirect(['controller' => 'downtimes','action' => 'service']);
        }
    }

    public function addContainerdowntime() {
        $this->layout = 'angularjs';
        $flashmessage = "";

        $this->Frontend->setJson('dateformat', MY_DATEFORMAT);
        $selected = $this->request->data('Systemdowntime.object_id');

        $customFieldsToRefill = [
            'Systemdowntime' => [
                'from_date',
                'from_time',
                'to_date',
                'to_time',
                'duration',
                'is_recurring',
                'weekdays',
                'day_of_month',
                'inherit_downtime'
            ],
        ];
        $this->CustomValidationErrors->checkForRefill($customFieldsToRefill);

        $this->set('back_url', $this->referer());

        /*
        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        }
        */

        $preselectedDowntimetype = $this->Systemsetting->findByKey("FRONTEND.PRESELECTED_DOWNTIME_OPTION")['Systemsetting']['value'];
        $this->set(compact('containers', 'selected', 'preselectedDowntimetype'));
        $childrenContainers = [];

        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['Systemdowntime']['weekdays']) && is_array($this->request->data['Systemdowntime']['weekdays'])) {
                $this->request->data['Systemdowntime']['weekdays'] = implode(',', $this->request->data['Systemdowntime']['weekdays']);
            }
            $this->request->data = $this->_rewritePostData();


            //get all host UUIDS
            $hostUuids = [];
            foreach ($this->request->data as $request) {

                if ($request['Systemdowntime']['object_id'] == ROOT_CONTAINER && $request['Systemdowntime']['inherit_downtime'] == 1) {
                    $childrenContainers = $this->Tree->resolveChildrenOfContainerIds($request['Systemdowntime']['object_id'], true);
                } else if ($request['Systemdowntime']['object_id'] != ROOT_CONTAINER && $request['Systemdowntime']['inherit_downtime'] == 1) {
                    $childrenContainers = $this->Tree->resolveChildrenOfContainerIds($request['Systemdowntime']['object_id']);
                    $childrenContainers = $this->Tree->removeRootContainer($childrenContainers);
                }

                //check if the user has rights for each children container
                $myChildrenContainer = [];
                foreach ($childrenContainers as $childrenContainer) {
                    if (in_array($childrenContainer, $this->MY_RIGHTS)) {
                        $myChildrenContainer[] = $childrenContainer;
                    }
                }

                switch ($request['Systemdowntime']['inherit_downtime']) {
                    case 0:
                        //only hosts in the selected containers will be considered (also wich are shared in these containers)
                        $result = $this->Host->find('all', [
                            'recursive'  => -1,
                            'conditions' => [
                                'Host.disabled'           => 0,
                                'Containers.container_id' => $request['Systemdowntime']['object_id']
                            ],
                            'fields'     => [
                                'Host.uuid'
                            ],
                            'joins'      => [
                                [
                                    'table'      => 'hosts_to_containers',
                                    'type'       => 'LEFT',
                                    'alias'      => 'Containers',
                                    'conditions' => 'Containers.host_id = Host.id',
                                ],
                            ]
                        ]);

                        if (!empty($result)) {
                            $hostUuids[] = Hash::extract($result, '{n}.Host.uuid');
                        }

                        break;
                    case 1:
                        //all hosts in the selected and the children container will be considered
                        $lookupContainerIds = [];
                        if (!empty($myChildrenContainer)) {
                            $lookupContainerIds = array_merge([$request['Systemdowntime']['object_id']], $myChildrenContainer);
                        }

                        $conditions = [
                            'HostsToContainers.container_id' => $lookupContainerIds,
                            'Host.disabled'                  => 0,
                        ];

                        $result = $this->Host->find('all', [
                            'recursive'  => -1,
                            'joins'      => [
                                [
                                    'table'      => 'hosts_to_containers',
                                    'alias'      => 'HostsToContainers',
                                    'type'       => 'LEFT',
                                    'conditions' => [
                                        'HostsToContainers.host_id = Host.id',
                                    ],
                                ],
                            ],
                            'conditions' => $conditions,
                            'order'      => [
                                'Host.name' => 'ASC',
                            ],
                            'fields'     => [
                                'Host.uuid',
                            ],
                        ]);
                        if (!empty($result)) {
                            $hostUuids[] = Hash::extract($result, '{n}.Host.uuid');
                        }
                        break;
                }
            }

            //get rid of same uuids
            $allHostUuids = [];
            $mapping = [];
            foreach ($hostUuids as $key => $data) {
                foreach ($data as $hostUuid) {
                    if (!in_array($hostUuid, $mapping)) {
                        $mapping[] = $hostUuid;
                        $allHostUuids[$key][] = $hostUuid;
                    }
                }
            }

            //Try validate the data:
            foreach ($this->request->data as $key => $request) {
                if ($request['Systemdowntime']['is_recurring']) {
                    $this->Systemdowntime->validate = Hash::merge(
                        $this->Systemdowntime->validate,
                        [
                            'from_date' => [
                                'notBlank' => [
                                    'required'   => false,
                                    'allowEmpty' => true,
                                ],
                            ],
                            'to_date'   => [
                                'notBlank' => [
                                    'required'   => false,
                                    'allowEmpty' => true,
                                ]
                            ],
                            'from_time' => [
                                'notBlank' => [
                                    'required'   => true,
                                    'allowEmpty' => false,
                                ],
                            ],
                            'to_time'   => [
                                'notBlank' => [
                                    'required'   => false,
                                    'allowEmpty' => true,
                                ],
                            ]
                        ]
                    );
                }

                $this->Systemdowntime->set($request);
                if ($this->Systemdowntime->validates()) {
                    /* The data is valide and we can save it.
                     * We need to use the foreach, becasue validates() cant handle saveAll() data :(
                     *
                     * How ever, at this point we passed the validation, so the data is valid.
                     * Now we need to check, if this is just a downtime, or an recurring downtime, because
                     * these guys we want to save in our systemdowntimestable
                     * Normal downtimes, will be sent to sudo_servers unix socket.
                     */

                    if ($request['Systemdowntime']['is_recurring'] == 1) {
                        $this->Systemdowntime->create();
                        if ($this->Systemdowntime->save($request) && $this->request->ext === 'json') {
                            $parentContainerId = $request['Systemdowntime']['object_id'];
                            if (!empty($childrenContainers)) {
                                foreach ($childrenContainers as $childrenContainerId) {
                                    if ($parentContainerId != $childrenContainerId) {
                                        $request['Systemdowntime']['object_id'] = $childrenContainerId;
                                        $this->Systemdowntime->create();
                                        $this->Systemdowntime->save($request);
                                    }
                                }
                            }
                            if ($this->isAngularJsRequest()) {
                                $flashmessage = __('Recurring Downtime successfully saved');
                            }
                            $this->serializeId();
                        } else {
                            $this->serializeErrorMessage();
                        }
                    } else {
                        $start = strtotime($request['Systemdowntime']['from_date'] . ' ' . $request['Systemdowntime']['from_time']);
                        $end = strtotime($request['Systemdowntime']['to_date'] . ' ' . $request['Systemdowntime']['to_time']);
                        //Just a normal nagios downtime
                        if ($request['Systemdowntime']['downtimetype'] == 'container') {

                            $payload = [
                                'containerId'      => $request['Systemdowntime']['object_id'],
                                'hostUuids'        => isset($allHostUuids[$key]) ? $allHostUuids[$key] : [],
                                'inherit_downtime' => $request['Systemdowntime']['inherit_downtime'],
                                'downtimetype'     => $request['Systemdowntime']['downtimetype_id'],
                                'start'            => $start,
                                'end'              => $end,
                                'comment'          => $request['Systemdowntime']['comment'],
                                'author'           => $this->Auth->user('full_name'),
                            ];

                            $this->GearmanClient->sendBackground('createContainerDowntime', $payload);
                        }
                        if ($this->request->ext === 'json') {
                            if ($this->isAngularJsRequest()) {
                                $flashmessage = __('Downtime successfully saved');
                            }
                            $this->serializeId();
                        }
                    }

                } else {
                    //$flashmessage=__('Downtime could not be saved');
                    $this->CustomValidationErrors->loadModel($this->Systemdowntime);
                    $this->CustomValidationErrors->customFields(['from_date', 'from_time', 'to_date', 'to_time', 'downtimetype']);
                    $this->CustomValidationErrors->fetchErrors();
                    $this->serializeErrorMessage();
                    return;
                }
            }
            $this->setFlash($flashmessage);
            //$this->redirect(['controller' => 'downtimes', 'action' => 'index']);
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
