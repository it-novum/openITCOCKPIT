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

use itnovum\openITCOCKPIT\Core\ContainerRepository;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\UserTime;

class AutomapsController extends AppController {
    public $layout = 'Admin.default';

    public $uses = [
        'Automap',
        'Host',
        'Service',
        'Container',
        MONITORING_SERVICESTATUS,
        MONITORING_ACKNOWLEDGED
    ];

    public $components = [
        'CustomValidationErrors',
        'ListFilter.ListFilter'
    ];

    public $helpers = [
        'CustomValidationErrors',
        'Status',
        'ListFilter.ListFilter'
    ];

    public $listFilters = [
        'index' => [
            'fields' => [
                'Automap.name'        => ['label' => 'Name', 'searchType' => 'wildcard'],
                'Automap.description' => ['label' => 'Description', 'searchType' => 'wildcard'],
            ],
        ]
    ];

    public function index() {
        $options = [
            'conditions' => [
                'Automap.container_id' => $this->MY_RIGHTS,
            ],
        ];

        $query = Hash::merge($options, $this->Paginator->settings);

        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_automaps = $this->Automap->find('all', $query);
        } else {
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $all_automaps = $this->Paginator->paginate();
        }
        $this->set(compact(['all_automaps']));
        $this->set('_serialize', ['all_automaps']);
    }

    public function add() {
        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        $this->set(compact(['containers']));

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Automap->create();
            if ($this->Automap->save($this->request->data)) {
                $this->setFlash(__('Automap saved successfully'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Data could not be saved'), false);
                $this->CustomValidationErrors->loadModel($this->Automap);
                $this->CustomValidationErrors->customFields(['show_ok']);
                $this->CustomValidationErrors->fetchErrors();
            }
        }
    }

    public function edit($id) {
        if (!$this->Automap->exists($id)) {
            throw new NotFoundException(__('Invalid automap'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Automap->save($this->request->data)) {
                $this->setFlash(__('Automap saved successfully'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Data could not be saved'), false);
                $this->CustomValidationErrors->loadModel($this->Automap);
                $this->CustomValidationErrors->customFields(['show_ok']);
                $this->CustomValidationErrors->fetchErrors();
            }
        }

        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        $automap = $this->Automap->findById($id);

        if (!$this->allowedByContainerId($automap['Automap']['container_id'])) {
            $this->render403();

            return;
        }

        $this->set(compact(['automap', 'containers']));
        $this->request->data = Hash::merge($automap, $this->request->data);
    }

    public function view($id) {
        if (!$this->Automap->exists($id)) {
            throw new NotFoundException(__('Invalid automap'));
        }

        $automap = $this->Automap->findById($id);

        if (!$this->allowedByContainerId($automap['Automap']['container_id'], false)) {
            $this->render403();

            return;
        }

        $ContainerRepository = new ContainerRepository($automap['Automap']['container_id']);
        if ((bool)$automap['Automap']['recursive'] == true) {
            if ($automap['Automap']['container_id'] == ROOT_CONTAINER) {
                $childContainers = $this->Tree->resolveChildrenOfContainerIds($ContainerRepository->getContainer(), true);
            } else {
                $childContainers = $this->Tree->resolveChildrenOfContainerIds($ContainerRepository->getContainer(), false);
            }
            $ContainerRepository->addContainer($childContainers);

            //Remove root container, if the parent container of the Automap is not root
            if ($automap['Automap']['container_id'] != ROOT_CONTAINER) {
                $ContainerRepository->removeContainerId(ROOT_CONTAINER);
            }
        }


        $fontSizes = [
            1 => 'xx-small',
            2 => 'x-small',
            3 => 'small',
            4 => 'medium',
            5 => 'large',
            6 => 'x-large',
            7 => 'xx-large',
        ];


        $conditions = [];

        $current_stateConditions = [];

        $state_types = [
            'show_unknown'  => 3,
            'show_critical' => 2,
            'show_warning'  => 1,
            'show_ok'       => 0,
        ];

        foreach ($state_types as $stateName => $stateNumber) {
            if ($automap['Automap'][$stateName]) {
                $current_stateConditions[] = $stateNumber;
            }
        }
        if (sizeof($current_stateConditions) !== 4) {
            $conditions['Servicestatus.current_state'] = $current_stateConditions;
        }

        if ($automap['Automap']['show_acknowledged'] == false) {
            $conditions['Servicestatus.problem_has_been_acknowledged'] = 0;
        }

        if ($automap['Automap']['show_downtime'] == false) {
            $conditions['Servicestatus.scheduled_downtime_depth'] = 0;
        }

        $hosts = $this->Host->find('list', [
            'contain'    => [],
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
            'conditions' => [
                'HostsToContainers.container_id' => $ContainerRepository->getContainer(),
                'Host.disabled'                  => 0,
                'Host.name REGEXP'               => $automap['Automap']['host_regex'],
            ],
            'order'      => [
                'Host.name ASC'
            ]
        ]);

        $services = $this->Service->find('all', [
            'recursive'  => -1,
            'joins'      => [
                [
                    'table'      => 'hosts',
                    'alias'      => 'Host',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Host.id = Service.host_id',
                    ],
                ],
                [
                    'table'      => 'servicetemplates',
                    'alias'      => 'Servicetemplate',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Servicetemplate.id = Service.servicetemplate_id',
                    ],
                ],
                [
                    'table'      => 'nagios_objects',
                    'alias'      => 'ServiceObject',
                    'type'       => 'INNER',
                    'conditions' => [
                        'ServiceObject.name2 = Service.uuid',
                        'ServiceObject.objecttype_id' => 2,
                    ],
                ],
                [
                    'table'      => 'nagios_servicestatus',
                    'alias'      => 'Servicestatus',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Servicestatus.service_object_id = ServiceObject.object_id',
                    ],
                ],
            ],
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.host_id',
                'Servicetemplate.name',

                'ServiceObject.object_id',

                'Servicestatus.current_state',
                'Servicestatus.problem_has_been_acknowledged',
                'Servicestatus.scheduled_downtime_depth',

                'Host.id',
                'Host.name',
            ],
            'conditions' => [
                'Service.host_id'                                                      => array_keys($hosts),
                'Service.disabled'                                                     => 0,
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name) REGEXP ' => $automap['Automap']['service_regex'],
                $conditions,
            ],
            'order'      => [
                'Host.name ASC',
                ' IF(Service.name IS NULL, Servicetemplate.name, Service.name) ASC'
            ]
        ]);

        $username = $this->Auth->user('full_name');

        $this->set(compact(['fontSizes', 'automap', 'hosts', 'services', 'username']));
        $this->set('_serialize', ['automap', 'hosts', 'services']);
    }

    public function loadServiceDetails($serviceId = null) {
        $this->allowOnlyAjaxRequests();

        if (!$this->Service->exists($serviceId)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $service = $this->Service->find('first', [
            'contain'    => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                        'Servicetemplate.name',
                    ],
                ],
                'Host'            => [
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name',
                    ],
                ],
            ],
            'conditions' => [
                'Service.id' => $serviceId,
            ],
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.name',
            ],
        ]);

        $serviceName = $service['Servicetemplate']['name'];
        if ($service['Service']['name'] !== null || $service['Service']['name'] != '') {
            $serviceName = $service['Service']['name'];
        }

        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields
            ->currentState()
            ->isHardstate()
            ->lastStateChange()
            ->perfdata()
            ->output()
            ->lastCheck()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();
        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusFields);


        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($servicestatus['Servicestatus'], $UserTime);

        $exitCodes = [
            0 => __('Ok'),
            1 => __('Warning'),
            2 => __('Critical'),
            3 => __('Unknown'),
        ];

        $servicestatus = [
            'Servicestatus' => [
                'current_state'                 => $exitCodes[$Servicestatus->currentState()],
                'state_type'                    => ($Servicestatus->isHardState())?__('Hard'):__('Soft'),
                'last_state_change'             => $Servicestatus->getLastStateChange(),
                'perfdata'                      => h($Servicestatus->getPerfdata()),
                'output'                        => h($Servicestatus->getOutput()),
                'last_check'                    => $Servicestatus->getLastCheck(),
                'scheduled_downtime_depth'      => $Servicestatus->isInDowntime(),
                'problem_has_been_acknowledged' => $Servicestatus->isAcknowledged(),
            ],
        ];

        $acknowledged = [];
        if ($Servicestatus->isAcknowledged()) {
            $acknowledged = $this->Acknowledged->byUuid($service['Service']['uuid']);
            $acknowledged = __('The current status was acknowledged by') . ' <strong>' . h($acknowledged[0]['Acknowledged']['author_name']) . '</strong> ' . __('with the comment') . ' "' . h($acknowledged[0]['Acknowledged']['comment_data']) . '"';
        }


        //Check for Graph
        $hasRrdGraph = false;
        Configure::load('rrd');
        if (file_exists(Configure::read('rrd.path') . $service['Host']['uuid'] . DS . $service['Service']['uuid'] . '.rrd')) {
            $hasRrdGraph = true;
        }

        $this->set(compact(['service', 'servicestatus', 'serviceName', 'hasRrdGraph', 'acknowledged']));
        $this->set('_serialize', ['service', 'servicestatus', 'serviceName', 'hasRrdGraph', 'acknowledged']);
    }

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Automap->exists($id)) {
            throw new NotFoundException(__('Invalid Automap'));
        }

        $automap = $this->Automap->findById($id);

        if (!$this->allowedByContainerId($automap['Automap']['container_id'])) {
            $this->render403();

            return;
        }

        if ($this->Automap->delete($id)) {
            $this->setFlash(__('Automap deleted'));
            $this->redirect(['action' => 'index']);
        }
        $this->setFlash(__('Could not delete Automap'), false);
        $this->redirect(['action' => 'index']);
    }

}
