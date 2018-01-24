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
        $paginatorLimit = $this->Paginator->settings['limit'];
        $requestSettings = $this->Systemdowntime->listSettings($this->request, $paginatorLimit);

        if (isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);
        } else {
            $this->Paginator->settings['conditions'] = $requestSettings['conditions'];
        }

        $this->Paginator->settings['limit'] = $requestSettings['paginator']['limit'];
        $this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);
        $this->Paginator->settings = Hash::merge($this->Paginator->settings, $requestSettings['default']);

        $containerList = $this->Container->find("list");
        $all_systemdowntimes = $this->Paginator->paginate();
        foreach ($all_systemdowntimes as $dKey => $systemdowntime) {
            switch ($systemdowntime['Systemdowntime']['objecttype_id']) {
                case OBJECT_HOST:
                    if (isset($this->MY_RIGHTS_LEVEL[$systemdowntime['Host']['container_id']]) &&
                        $this->MY_RIGHTS_LEVEL[$systemdowntime['Host']['container_id']] == WRITE_RIGHT) {
                        $all_systemdowntimes[$dKey]['canDelete'] = true;
                    } else {
                        $all_systemdowntimes[$dKey]['canDelete'] = false;
                    }
                    break;

                case OBJECT_SERVICE:
                    if (isset($this->MY_RIGHTS_LEVEL[$systemdowntime['ServiceHost']['container_id']]) &&
                        $this->MY_RIGHTS_LEVEL[$systemdowntime['ServiceHost']['container_id']] == WRITE_RIGHT) {
                        $all_systemdowntimes[$dKey]['canDelete'] = true;
                    } else {
                        $all_systemdowntimes[$dKey]['canDelete'] = false;
                    }
                    break;

                case OBJECT_HOSTGROUP:
                    if (isset($this->MY_RIGHTS_LEVEL[$systemdowntime['Hostgroup']['container_id']]) &&
                        $this->MY_RIGHTS_LEVEL[$systemdowntime['Hostgroup']['container_id']] == WRITE_RIGHT) {
                        $all_systemdowntimes[$dKey]['canDelete'] = true;
                    } else if (isset($this->MY_RIGHTS_LEVEL[$systemdowntime['Hostgroup']['container_id']]) &&
                        $this->MY_RIGHTS_LEVEL[$systemdowntime['Hostgroup']['container_id']] == READ_RIGHT) {
                        $all_systemdowntimes[$dKey]['canDelete'] = false;
                    } else {
                        unset($all_systemdowntimes[$dKey]);
                    }
                    break;

                case OBJECT_NODE:
                    $all_systemdowntimes[$dKey]['Container']['id'] = $systemdowntime['Systemdowntime']['object_id'];
                    $systemdowntime['Container']['id'] = $systemdowntime['Systemdowntime']['object_id'];
                    $all_systemdowntimes[$dKey]['Container']['name'] = $containerList[$systemdowntime['Container']['id']];

                    if (isset($this->MY_RIGHTS_LEVEL[$systemdowntime['Container']['id']]) &&
                        $this->MY_RIGHTS_LEVEL[$systemdowntime['Container']['id']] == WRITE_RIGHT) {
                        $all_systemdowntimes[$dKey]['canDelete'] = true;
                    } else if (isset($this->MY_RIGHTS_LEVEL[$systemdowntime['Container']['id']]) &&
                        $this->MY_RIGHTS_LEVEL[$systemdowntime['Container']['id']] == READ_RIGHT) {
                        $all_systemdowntimes[$dKey]['canDelete'] = false;
                    } else {
                        unset($all_systemdowntimes[$dKey]);
                    }
                    break;

                default:
                    $all_systemdowntimes[$dKey]['canDelete'] = false;
            }

        }

        $this->set('DowntimeListsettings', $requestSettings['Listsettings']);
        $this->set('all_systemdowntimes', $all_systemdowntimes);
        $this->set('paginatorLimit', $paginatorLimit);
    }

    public function getDowntimeData() {
        $this->layout = 'angularjs';
        if (!$this->isAngularJsRequest()) {
            return;
        }

        $refill = [
            'from_date' => date('d.m.Y'),
            'from_time' => date('H:i'),
            'to_date'   => date('d.m.Y'),
            'to_time'   => date('H:i', time() + 60 * 15),
            'duration'  => "15",
            'comment'   => __('In maintenance')
        ];

        $this->set('refill', $refill);
        $this->set('_serialize', ['refill']);
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


    public function delete($id = null, $cascade = true) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Systemdowntime->id = $id;
        if (!$this->Systemdowntime->exists()) {
            throw new NotFoundException(__('Invalide downtime'));
        }

        $systemdowntime = $this->Systemdowntime->findById($id);

        if ($this->Systemdowntime->delete()) {
            $this->setFlash(__('Recurring downtime deleted'));
            $this->redirect(['action' => 'index']);
        }
        $this->setFlash(__('Could not delete recurring downtime'));
        $this->redirect(['action' => 'index']);

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
