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

use App\Model\Table\AutomapsTable;
use App\Model\Table\ContainersTable;
use App\Model\Table\HostsTable;
use App\Model\Table\ServicesTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\ContainerRepository;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\HostConditions;
use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\AutomapsFilter;
use itnovum\openITCOCKPIT\Filter\HostFilter;

/**
 * Class AutomapsController
 * @property DbBackend $DbBackend
 * @property AppPaginatorComponent $Paginator
 * @property AppAuthComponent $Auth
 *
 * @property Servicestatus $Servicestatus
 */
class AutomapsController extends AppController {

    public $layout = 'blank';

    public $uses = [
        'Automap',
        'Host',
        'Service',
        'Container',
        MONITORING_SERVICESTATUS,
        MONITORING_ACKNOWLEDGED
    ];

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $AutomapsTable AutomapsTable */
        $AutomapsTable = TableRegistry::getTableLocator()->get('Automaps');

        $AutomapsFilter = new AutomapsFilter($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $AutomapsFilter->getPage());

        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }
        $automaps = $AutomapsTable->getAutomapsIndex($AutomapsFilter, $PaginateOMat, $MY_RIGHTS);
        $all_automaps = [];

        foreach ($automaps as $automap) {
            /** @var \App\Model\Entity\Automap $automap */
            $automap = $automap->toArray();
            $automap['allow_edit'] = $this->hasRootPrivileges;

            if ($this->hasRootPrivileges === true) {
                $automap['allow_edit'] = $this->isWritableContainer($automap['container']['id']);
            }

            $all_automaps[] = $automap;
        }

        $this->set('all_automaps', $all_automaps);
        $toJson = ['all_automaps', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_automaps', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $AutomapsTable AutomapsTable */
        $AutomapsTable = TableRegistry::getTableLocator()->get('Automaps');

        if ($this->request->is('post') || $this->request->is('put')) {
            $automap = $AutomapsTable->newEntity();
            $automap = $AutomapsTable->patchEntity($automap, $this->request->data('Automap'));
            $AutomapsTable->save($automap);
            if ($automap->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $automap->getErrors());
                $this->set('_serialize', ['error']);
                return;
            }

            $this->set('automap', $automap);
            $this->set('_serialize', ['automap']);
        }
    }

    /**
     * @param int|null $id
     * @throws Exception
     */
    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $AutomapsTable AutomapsTable */
        $AutomapsTable = TableRegistry::getTableLocator()->get('Automaps');

        if (!$AutomapsTable->existsById($id)) {
            throw new NotFoundException(__('Automap not found'));
        }

        $automap = $AutomapsTable->get($id);

        if (!$this->allowedByContainerId($automap->get('container_id'), true)) {
            $this->render403();
            return;
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return contact information
            $automap = $automap->toArray();
            $toIntFields = [
                'recursive',
                'show_ok',
                'show_warning',
                'show_critical',
                'show_unknown',
                'show_acknowledged',
                'show_downtime',
                'show_label',
                'group_by_host'
            ];
            foreach ($toIntFields as $intField) {
                $automap[$intField] = (int)$automap[$intField];
            }

            $this->set('automap', $automap);
            $this->set('_serialize', ['automap']);
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            //Update automap data
            $automap->setAccess('id', false);
            $automap = $AutomapsTable->patchEntity($automap, $this->request->data('Automap'));
            $AutomapsTable->save($automap);
            if ($automap->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $automap->getErrors());
                $this->set('_serialize', ['error']);
                return;
            }
            $this->set('automap', $automap);
            $this->set('_serialize', ['automap']);
        }
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function view($id = null) {
        $this->layout = 'blank';

        if (!$this->isApiRequest() && $id === null) {
            return;
        }

        if (!$this->Automap->exists($id) && $id !== null) {
            throw new NotFoundException(__('Invalid automap'));
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

        $automap = $this->Automap->findById($id);
        $automap['Automap']['font_size_html'] = $fontSizes[$automap['Automap']['font_size']];

        if (!$this->allowedByContainerId($automap['Automap']['container_id'], false)) {
            $this->render403();
            return;
        }

        if (!$this->isAngularJsRequest()) {
            return;
        }


        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $ContainerRepository = new ContainerRepository($automap['Automap']['container_id']);
        if ($automap['Automap']['recursive'] === true) {
            if ($automap['Automap']['container_id'] == ROOT_CONTAINER) {
                $childContainers = $ContainersTable->resolveChildrenOfContainerIds($ContainerRepository->getContainer(), true);
            } else {
                $childContainers = $ContainersTable->resolveChildrenOfContainerIds($ContainerRepository->getContainer(), false);
            }
            $ContainerRepository->addContainer($childContainers);

            //Remove root container, if the parent container of the Automap is not root
            if ($automap['Automap']['container_id'] != ROOT_CONTAINER) {
                $ContainerRepository->removeContainerId(ROOT_CONTAINER);
            }
        }


        $current_stateConditions = [];
        $state_types = [
            'show_unknown'  => 3,
            'show_critical' => 2,
            'show_warning'  => 1,
            'show_ok'       => 0,
        ];
        $ServicestatusConditions = new ServicestatusConditions($this->DbBackend);
        foreach ($state_types as $stateName => $stateNumber) {
            if ($automap['Automap'][$stateName]) {
                $current_stateConditions[] = $stateNumber;
            }
        }
        if (sizeof($current_stateConditions) < 4) {
            $ServicestatusConditions->currentState($current_stateConditions);
        }

        if ($automap['Automap']['show_acknowledged'] == false) {
            $ServicestatusConditions->setProblemHasBeenAcknowledged(0);
        }

        if ($automap['Automap']['show_downtime'] == false) {
            $ServicestatusConditions->setScheduledDowntimeDepth(0);
        }

        $hosts = $this->Host->find('list', [
            'recursive'  => -1,
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

        $serviceRecords = $this->Service->find('all', [
            'recursive'  => -1,
            'contain'    => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.name'
                    ]
                ]
            ],
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.host_id'
            ],
            'conditions' => [
                'Service.host_id'                                                      => array_keys($hosts),
                'Service.disabled'                                                     => 0,
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name) REGEXP ' => $automap['Automap']['service_regex']
            ],
            'order'      => [
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name) ASC'
            ]
        ]);


        $serviceUuids = [];
        foreach ($serviceRecords as $serviceRecord) {
            $serviceUuids[] = $serviceRecord['Service']['uuid'];
        }

        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields
            ->currentState()
            ->problemHasBeenAcknowledged()
            ->scheduledDowntimeDepth();
        $servicestatusRecords = $this->Servicestatus->byUuid(
            $serviceUuids,
            $ServicestatusFields,
            $ServicestatusConditions
        );

        $hostAndServices = [];
        foreach ($serviceRecords as $serviceRecord) {
            $serviceUuid = $serviceRecord['Service']['uuid'];
            //Has Service a service status record?
            if (!isset($servicestatusRecords[$serviceUuid])) {
                continue;
            }

            $hostId = $serviceRecord['Service']['host_id'];
            if ($serviceRecord['Service']['name'] === '' || $serviceRecord['Service']['name'] === null) {
                $serviceRecord['Service']['name'] = $serviceRecord['Servicetemplate']['name'];
            }

            if (!isset($hostAndServices[$hostId])) {
                $hostAndServices[$hostId] = [
                    'Host'     => [
                        'id'   => $hostId,
                        'name' => $hosts[$hostId]
                    ],
                    'Services' => []
                ];
            }

            $hostAndServices[$hostId]['Services'][] = [
                'Service'       => $serviceRecord['Service'],
                'Servicestatus' => [
                    'currentState'               => (int)$servicestatusRecords[$serviceUuid]['Servicestatus']['current_state'],
                    'problemHasBeenAcknowledged' => (bool)$servicestatusRecords[$serviceUuid]['Servicestatus']['problem_has_been_acknowledged'],
                    'scheduledDowntimeDepth'     => (int)$servicestatusRecords[$serviceUuid]['Servicestatus']['scheduled_downtime_depth']
                ]
            ];
        }

        $this->set(compact(['automap', 'hostAndServices']));
        $this->set('_serialize', ['automap', 'hostAndServices']);
    }

    /**
     * @param null $serviceId
     * @deprecated
     */
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
                'state_type'                    => ($Servicestatus->isHardState()) ? __('Hard') : __('Soft'),
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

    /**
     * @param int|null $id
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $AutomapsTable AutomapsTable */
        $AutomapsTable = TableRegistry::getTableLocator()->get('Automaps');

        if (!$AutomapsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Automap'));
        }

        $automap = $AutomapsTable->get($id);

        if (!$this->allowedByContainerId($automap->get('container_id'), true)) {
            $this->render403();
            return;
        }

        if (!$AutomapsTable->delete($automap)) {
            $this->response->statusCode(400);
            $this->set('success', false);
            $this->set('id', $id);
            $this->set('_serialize', ['success', 'id']);
            return;
        }

        $this->set('success', true);
        $this->set('id', $id);
        $this->set('_serialize', ['success', 'id']);
    }

    /**
     * @deprecated
     */
    public function icon() {
        $this->layout = 'blank';
        //Only ship HTML Template
        return;
    }

    /****************************
     *       AJAX METHODS       *
     ****************************/

    public function loadContainers() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        $containers = Api::makeItJavaScriptAble($containers);

        $this->set('containers', $containers);
        $this->set('_serialize', ['containers']);
    }

    public function getMatchingHostAndServices() {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        $defaults = [
            'container_id'  => 0,
            'recursive'     => 0,
            'host_regex'    => '',
            'service_regex' => ''
        ];

        $hostCount = 0;
        $serviceCount = 0;

        $post = $this->request->data('Automap');
        $post = \Cake\Utility\Hash::merge($defaults, $post);

        if ($post['container_id'] > 0) {
            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
            /** @var $HostsTable HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
            /** @var $ServicesTable ServicesTable */
            $ServicesTable = TableRegistry::getTableLocator()->get('Services');

            $containerIds = [
                $post['container_id']
            ];

            if ($post['recursive']) {
                if ($post['container_id'] == ROOT_CONTAINER) {
                    $containerIds = $this->MY_RIGHTS;
                } else {
                    $tmpContainerIds = $ContainersTable->resolveChildrenOfContainerIds($post['container_id'], false);
                    $containerIds = $ContainersTable->removeRootContainer($tmpContainerIds);
                }
            }

            $HostConditions = new HostConditions();
            $HostConditions->setContainerIds($containerIds);
            $HostConditions->setHostnameRegex($post['host_regex']);
            $HostFilter = new HostFilter($this->request); //Only used for order right now

            if ($post['host_regex'] != '') {
                try {
                    $hostCount = $HostsTable->getHostsByRegularExpression($HostFilter, $HostConditions, null, 'count');
                } catch (\Exception $e) {
                    $hostCount = 0;
                }
            }

            $ServicesConditions = new ServiceConditions();
            $ServicesConditions->setContainerIds($containerIds);
            $ServicesConditions->setHostnameRegex($post['host_regex']);
            $ServicesConditions->setServicenameRegex($post['service_regex']);
            if ($post['service_regex'] != '') {
                try {
                    $serviceCount = $ServicesTable->getServicesByRegularExpression($ServicesConditions, null, 'count');
                } catch (\Exception $e) {
                    $serviceCount = 0;
                }
            }
        }


        $this->set('hostCount', $hostCount);
        $this->set('serviceCount', $serviceCount);
        $this->set('_serialize', ['hostCount', 'serviceCount']);
    }

}
