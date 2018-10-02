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

use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\Service;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use Statusengine\PerfdataParser;

/**
 * Class GrafanaUserdashboardsController
 * @property GrafanaConfiguration $GrafanaConfiguration
 * @property GrafanaUserdashboard $GrafanaUserdashboard
 * @property GrafanaUserdashboardPanel $GrafanaUserdashboardPanel
 * @property GrafanaUserdashboardMetric $GrafanaUserdashboardMetric
 * @property \Host $Host
 * @property \Service $Service
 * @property Servicestatus $Servicestatus
 */
class GrafanaUserdashboardsController extends GrafanaModuleAppController {

    public $layout = 'angularjs';

    public $uses = [
        'GrafanaModule.GrafanaConfiguration',
        'GrafanaModule.GrafanaUserdashboard',
        'GrafanaModule.GrafanaUserdashboardPanel',
        'GrafanaModule.GrafanaUserdashboardMetric',
        'Host',
        'Service',
        MONITORING_SERVICESTATUS
    ];

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }
        $allUserdashboards = $this->GrafanaUserdashboard->find('all', [
            // 'recursive' => -1,
            'conditions' => [
                'container_id' => $this->MY_RIGHTS
            ]
        ]);


        foreach ($allUserdashboards as $key => $dashboard) {
            $allUserdashboards[$key]['GrafanaUserdashboard']['allowEdit'] = false;
            if ($this->hasRootPrivileges == true) {
                $allUserdashboards[$key]['GrafanaUserdashboard']['allowEdit'] = true;
                continue;
            }
            foreach ($dashboard['Container'] as $cKey => $container) {
                if ($this->MY_RIGHTS_LEVEL[$container['id']] == WRITE_RIGHT) {
                    $allUserdashboards[$key]['GrafanaUserdashboard']['allowEdit'] = true;
                    continue;
                }
            }
        }

        $this->set('allUserdashboards', $allUserdashboards);
        $this->set('_serialize', ['allUserdashboards']);

    }

    public function add() {
        $grafanaConfig = $this->GrafanaConfiguration->find('first', [
            'recursive' => -1,
            'order'     => 'id DESC'
        ]);

        if (empty($grafanaConfig)) {
            //grafana is not yet configurated
        }

        if ($this->request->is('post')) {
            if (!isset($this->request->data['GrafanaUserdashboard']['configuration_id']) || empty($this->request->data['GrafanaUserdashboard']['configuration_id'])) {
                $this->request->data['GrafanaUserdashboard']['configuration_id'] = $grafanaConfig['GrafanaConfiguration']['id'];;
            }
            if ($this->GrafanaUserdashboard->saveAll($this->request->data)) {


                if ($this->isAngularJsRequest()) {
                    $this->setFlash(__('User defined Grafana dashboard created successfully.'));
                }

                if ($this->request->ext === 'json') {
                    $this->serializeId();
                }
                return;
            }
            $this->serializeErrorMessage();
        }
    }


    public function editor($userdashboardId = null) {
        if (!$this->request->is('GET')) {
            throw new MethodNotAllowedException();
        }

        if (!$this->GrafanaUserdashboard->exists($userdashboardId)) {
            throw new NotFoundException(__('Invalid Userdashboard'));
        }

        $dashboard = $this->GrafanaUserdashboard->find('first', [
            'conditions' => [
                'GrafanaUserdashboard.id' => $userdashboardId
            ],
            'contain'    => [
                'GrafanaUserdashboardPanel' => [
                    'GrafanaUserdashboardMetric' => [
                        'Host'    => [
                            'fields' => [
                                'Host.id',
                                'Host.name'
                            ]
                        ],
                        'Service' => [
                            'fields'          => [
                                'Service.id',
                                'Service.name'
                            ],
                            'Servicetemplate' => [
                                'fields' => [
                                    'Servicetemplate.name'
                                ]
                            ]
                        ]
                    ],
                    'order'                      => [
                        'GrafanaUserdashboardPanel.row' => 'ASC'
                    ]
                ]
            ]
        ]);

        $rowsWithPanelsAndMetrics = [];
        foreach ($dashboard['GrafanaUserdashboardPanel'] as $k => $panel) {
            $rowsWithPanelsAndMetrics[$panel['row']][$k] = [
                'id'               => $panel['id'],
                'userdashboard_id' => $panel['userdashboard_id'],
                'row'              => $panel['row'],
                'unit'             => $panel['unit'],
                'metrics'          => []
            ];
            foreach ($panel['GrafanaUserdashboardMetric'] as $metric) {
                $metric['Servicetemplate'] = [];
                if (isset($metric['Service']['Servicetemplate'])) {
                    $metric['Servicetemplate'] = $metric['Service']['Servicetemplate'];
                }
                $host = new Host($metric);
                $service = new Service($metric);
                $metric['Host'] = $host->toArray();
                $metric['Service'] = $service->toArray();
                $rowsWithPanelsAndMetrics[$panel['row']][$k]['metrics'][] = $metric;
            };
        }

        $dashboard['rows'] = $rowsWithPanelsAndMetrics;
        //debug($userdashboardData);
        $this->set('userdashboardData', $dashboard);
        $this->set('_serialize', ['userdashboardData']);

        return;

    }

    public function getGrafanaUserdashboardUrl($userdashboardId) {

        $userdashboardData = $this->GrafanaUserdashboardData->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'GrafanaUserdashboardData.userdashboard_id' => $userdashboardId
            ]
        ]);
        debug($userdashboardData);
        $userdashboardDataForGrafana = $this->GrafanaUserdashboardData->expandData($userdashboardData, true);
        debug($userdashboardDataForGrafana);

        $userdashboard = new \itnovum\openITCOCKPIT\Grafana\GrafanaUserdashboard();
        $userdashboard->setRows($userdashboardDataForGrafana);
        $userdashboard->setTitle('cooler title');
        $userdashboard->createUserdashboard();

        $this->set('userdashboardDataForGrafana', $userdashboardDataForGrafana);
        $this->set('_serialize', ['userdashboardDataForGrafana']);
    }


    public function view() {

    }

    public function delete() {

    }

    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, CT_TENANT, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), CT_TENANT, [], $this->hasRootPrivileges);
        }
        $containers = $this->Container->makeItJavaScriptAble($containers);


        $this->set('containers', $containers);
        $this->set('_serialize', ['containers']);
    }

    public function grafanaRow() {
        $this->layout = 'blank';
        return;
    }

    public function grafanaPanel() {
        $this->layout = 'blank';
        return;
    }

    public function getPerformanceDataMetrics($serviceId) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Service->exists($serviceId)) {
            throw new NotFoundException();
        }

        $service = $this->Service->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Service.id',
                'Service.uuid'
            ],
            'conditions' => [
                'Service.id' => $serviceId,
            ],
        ]);


        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->perfdata();
        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusFields);

        if (!empty($servicestatus)) {
            $PerfdataParser = new PerfdataParser($servicestatus['Servicestatus']['perfdata']);
            $this->set('perfdata', $PerfdataParser->parse());
            $this->set('_serialize', ['perfdata']);
            return;
        }
        $this->set('perfdata', []);
        $this->set('_serialize', ['perfdata']);
    }

    public function addMetricToPanel() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $service = $this->Service->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Service.id',
                'Service.host_id'
            ],
            'contain'    => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.name'
                    ]
                ],
                'Host'            => [
                    'fields' => [
                        'Host.name'
                    ]
                ]
            ],
            'conditions' => [
                'Service.id' => $this->request->data('GrafanaUserdashboardMetric.service_id'),
            ],
        ]);

        if (empty($service)) {
            //Trigger validation error
            $this->request->data['GrafanaUserdashboardMetric']['service_id'] = null;
            $this->request->data['GrafanaUserdashboardMetric']['host_id'] = null;
        }

        if (!isset($this->request->data['GrafanaUserdashboardMetric'])) {
            throw new NotFoundException('Key GrafanaUserdashboardMetric not found in dataset');
        }

        $metric = $this->request->data;
        if (isset($service['Service']['host_id'])) {
            $metric['GrafanaUserdashboardMetric']['host_id'] = (int)$service['Service']['host_id'];
        }

        $this->GrafanaUserdashboardMetric->create();
        if ($this->GrafanaUserdashboardMetric->save($metric)) {
            $metric = $this->request->data['GrafanaUserdashboardMetric'];
            $metric['id'] = $this->GrafanaUserdashboardMetric->id;

            $host = new Host($service);
            $metric['Host'] = $host->toArray();

            $service = new Service($service);
            $metric['Service'] = $service->toArray();

            $this->set('metric', $metric);
            $this->set('_serialize', ['metric']);
            return;
        }
        $this->serializeErrorMessageFromModel('GrafanaUserdashboardMetric');
    }

    public function removeMetricFromPanel() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if ($this->GrafanaUserdashboardMetric->exists($this->request->data('id'))) {
            $id = $this->request->data('id');
            if ($this->GrafanaUserdashboardMetric->delete($id)) {
                $this->set('success', true);
                $this->set('_serialize', ['success']);
                return;
            }
        }

        $this->set('success', false);
        $this->set('_serialize', ['success']);
    }

    public function addPanel() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $this->GrafanaUserdashboardPanel->create();
        if ($this->GrafanaUserdashboardPanel->save($this->request->data)) {
            $id = $this->GrafanaUserdashboardPanel->id;
            $this->set('panel', [
                'id'               => $id,
                'row'              => $this->request->data['GrafanaUserdashboardPanel']['row'],
                'userdashboard_id' => $this->request->data['GrafanaUserdashboardPanel']['userdashboard_id'],
                'unit'             => '',
                'metrics'          => []
            ]);
            $this->set('_serialize', ['panel']);
            return;
        }
        $this->serializeErrorMessageFromModel('GrafanaUserdashboardPanel');
    }

    public function removePanel() {
        if ($this->GrafanaUserdashboardPanel->exists($this->request->data('id'))) {
            $id = $this->request->data('id');
            if ($this->GrafanaUserdashboardPanel->delete($id)) {
                $this->set('success', true);
                $this->set('_serialize', ['success']);
                return;
            }
        }

        $this->set('success', false);
        $this->set('_serialize', ['success']);
    }

    public function addRow() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->data('id');
        if (!$this->GrafanaUserdashboard->exists($id)) {
            throw new NotFoundException('GrafanaUserdashboard does not exisits');
        }

        $this->GrafanaUserdashboardPanel->create();
        $data = [
            'GrafanaUserdashboardPanel' => [
                'userdashboard_id' => $id,
                'row'              => $this->GrafanaUserdashboardPanel->getNextRow($id)
            ]
        ];
        if ($this->GrafanaUserdashboardPanel->save($data)) {
            $id = $this->GrafanaUserdashboardPanel->id;
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }
        $this->serializeErrorMessageFromModel('GrafanaUserdashboardPanel');
    }

    public function removeRow() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->data('id');
        if (!$this->GrafanaUserdashboard->exists($id)) {
            throw new NotFoundException('GrafanaUserdashboard does not exisits');
        }

        throw new NotImplementedException();

        $this->GrafanaUserdashboardPanel->create();
        $data = [
            'GrafanaUserdashboardPanel' => [
                'userdashboard_id' => $id,
                'row'              => $this->GrafanaUserdashboardPanel->getNextRow($id)
            ]
        ];
        if ($this->GrafanaUserdashboardPanel->save($data)) {
            $id = $this->GrafanaUserdashboardPanel->id;
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }
        $this->serializeErrorMessageFromModel('GrafanaUserdashboardPanel');
    }
}
