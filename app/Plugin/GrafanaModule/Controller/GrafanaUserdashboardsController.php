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

use App\Model\Table\ContainersTable;
use Cake\ORM\TableRegistry;
use GuzzleHttp\Client;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\Service;
use itnovum\openITCOCKPIT\Database\ScrollIndex;
use itnovum\openITCOCKPIT\Filter\GrafanaUserDashboardFilter;
use itnovum\openITCOCKPIT\Grafana\GrafanaApiConfiguration;
use itnovum\openITCOCKPIT\Grafana\GrafanaPanel;
use itnovum\openITCOCKPIT\Grafana\GrafanaRow;
use itnovum\openITCOCKPIT\Grafana\GrafanaSeriesOverrides;
use itnovum\openITCOCKPIT\Grafana\GrafanaTag;
use itnovum\openITCOCKPIT\Grafana\GrafanaTarget;
use itnovum\openITCOCKPIT\Grafana\GrafanaTargetCollection;
use itnovum\openITCOCKPIT\Grafana\GrafanaTargetUnit;
use itnovum\openITCOCKPIT\Grafana\GrafanaTargetUnits;
use itnovum\openITCOCKPIT\Grafana\GrafanaThresholdCollection;
use itnovum\openITCOCKPIT\Grafana\GrafanaThresholds;
use itnovum\openITCOCKPIT\Grafana\GrafanaYAxes;
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
 * @property AppPaginatorComponent $Paginator
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

    public $components = [
        'Paginator' => ['className' => 'AppPaginator'],
    ];

    public function index() {
        $this->layout = 'blank';
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        $skipUnsyncDashboards = $this->request->query('skipUnsyncDashboards');
        $GrafanaUserDashboardFilter = new GrafanaUserDashboardFilter($this->request);
        $conditions = $GrafanaUserDashboardFilter->indexFilter();
        $conditions['GrafanaUserdashboard.container_id'] = $this->MY_RIGHTS;

        if ($skipUnsyncDashboards) {
            $conditions['AND']['NOT'] = [
                'GrafanaUserdashboard.grafana_url IS NULL',
                'GrafanaUserdashboard.grafana_url' => ''
            ];
        }
        $query = [
            'recursive'  => -1,
            'conditions' => $conditions,
            'order'      => $GrafanaUserDashboardFilter->getOrderForPaginator('GrafanaUserdashboard.name', 'ASC'),
            'contain'    => ['Container']
        ];

        if ($this->isScrollRequest()) {
            $this->Paginator->settings['page'] = $GrafanaUserDashboardFilter->getPage();
            $ScrollIndex = new ScrollIndex($this->Paginator, $this);
            $allUserdashboards = $this->GrafanaUserdashboard->find('all', array_merge($this->Paginator->settings, $query));
            $ScrollIndex->determineHasNextPage($allUserdashboards);
            $ScrollIndex->scroll();
        } else {
            $this->Paginator->settings['page'] = $GrafanaUserDashboardFilter->getPage();
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $allUserdashboards = $this->Paginator->paginate('GrafanaUserdashboard', [], [key($this->Paginator->settings['order'])]);
        }
        foreach ($allUserdashboards as $key => $dashboard) {
            $allUserdashboards[$key]['GrafanaUserdashboard']['allowEdit'] = false;
            if ($this->hasRootPrivileges == true) {
                $allUserdashboards[$key]['GrafanaUserdashboard']['allowEdit'] = true;
                continue;
            } else {
                if ($this->MY_RIGHTS_LEVEL[$dashboard['Container']['id']] == WRITE_RIGHT) {
                    $allUserdashboards[$key]['GrafanaUserdashboard']['allowEdit'] = true;
                    continue;
                }
            }
        }

        $this->set('all_userdashboards', $allUserdashboards);
        $toJson = ['all_userdashboards', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_userdashboards', 'scroll'];
        }
        $this->set('_serialize', $toJson);
        $this->set('_serialize', $toJson);

    }

    public function add() {
        $this->layout = 'blank';
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }
        $grafanaConfig = $this->GrafanaConfiguration->find('first', [
            'recursive' => -1,
            'order'     => ['GrafanaConfiguration.id' => 'DESC']
        ]);
        if ($this->request->is('post')) {
            $this->GrafanaUserdashboard->create();
            if (!isset($this->request->data['GrafanaUserdashboard']['configuration_id']) || empty($this->request->data['GrafanaUserdashboard']['configuration_id'])) {
                $this->request->data['GrafanaUserdashboard']['configuration_id'] = $grafanaConfig['GrafanaConfiguration']['id'];;
            }
            if ($this->GrafanaUserdashboard->saveAll($this->request->data)) {

                if ($this->request->ext === 'json') {
                    $this->serializeId();
                }
                return;
            }
            $this->serializeErrorMessage();
        }
    }


    public function editor($userdashboardId = null) {
        $this->layout = 'blank';
        if (!$this->isApiRequest() && $userdashboardId === null) {
            //Only ship template for AngularJs
            $grafanaConfig = $this->GrafanaConfiguration->find('first', [
                'recursive' => -1,
                'order'     => ['GrafanaConfiguration.id' => 'DESC']
            ]);
            $hasGrafanaConfig = false;
            if (!empty($grafanaConfig)) {
                $hasGrafanaConfig = true;
            }
            $this->set('hasGrafanaConfig', $hasGrafanaConfig);
            return;
        }

        if (!$this->request->is('GET')) {
            throw new MethodNotAllowedException();
        }

        if (!$this->GrafanaUserdashboard->exists($userdashboardId) && $userdashboardId !== null) {
            throw new NotFoundException(__('Invalid Userdashboard'));
        }

        $dashboard = $this->GrafanaUserdashboard->find('first', $this->GrafanaUserdashboard->getQuery($userdashboardId));
        $dashboard['rows'] = $this->GrafanaUserdashboard->extractRowsWithPanelsAndMetricsFromFindResult($dashboard);

        $GrafanaUnits = new GrafanaTargetUnits();

        $this->set('userdashboardData', $dashboard);
        $this->set('grafanaUnits', $GrafanaUnits->getUnits());
        $this->set('_serialize', ['userdashboardData', 'grafanaUnits']);

        return;

    }

    /*public function getGrafanaUserdashboardUrl($userdashboardId) {

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
    }*/

    public function edit($id = null) {
        $this->layout = 'blank';
        if (!$this->isApiRequest() && $id === null) {
            //Only ship template for AngularJs
            $grafanaConfig = $this->GrafanaConfiguration->find('first', [
                'recursive' => -1,
                'order'     => ['GrafanaConfiguration.id' => 'DESC']
            ]);
            $hasGrafanaConfig = false;
            if (!empty($grafanaConfig)) {
                $hasGrafanaConfig = true;
            }
            $this->set('hasGrafanaConfig', $hasGrafanaConfig);
            return;
        }

        if (!$this->GrafanaUserdashboard->exists($id) && $id !== null) {
            throw new NotFoundException();
        }

        $dashboard = $this->GrafanaUserdashboard->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'GrafanaUserdashboard.id'           => $id,
                'GrafanaUserdashboard.container_id' => $this->MY_RIGHTS
            ]
        ]);

        if (empty($dashboard)) {
            $this->redirect([
                'controller' => 'Angular',
                'action'     => 'forbidden',
                'plugin'     => ''
            ]);
        }
        $dashboard['GrafanaUserdashboard']['container_id'] = (int)$dashboard['GrafanaUserdashboard']['container_id'];

        if (!$this->isAngularJsRequest()) {
            //Only ship html template
            return;
        }

        if ($this->isAngularJsRequest()) {
            if ($this->request->is('GET')) {
                $this->set('dashboard', $dashboard);
                $this->set('_serialize', ['dashboard']);
                return;
            }

            if ($this->request->is('POST')) {
                if ($this->GrafanaUserdashboard->save($this->request->data)) {
                    $this->set('dashboard', $this->request->data);
                    $this->set('_serialize', ['dashboard']);
                    return;
                }
                $this->serializeErrorMessage();
            }
        }


    }

    public function view($id = null) {
        $this->layout = 'blank';
        if (!$this->isAngularJsRequest() && $id === null) {
            //Only ship html template
            return;
        }

        if (!$this->GrafanaUserdashboard->exists($id) && $id !== null) {
            throw new NotFoundException();
        }

        $dashboard = $this->GrafanaUserdashboard->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'GrafanaUserdashboard.id'           => $id,
                'GrafanaUserdashboard.container_id' => $this->MY_RIGHTS
            ],
            'contain'    => [
                'Container'
            ]
        ]);

        if (empty($dashboard)) {
            $this->redirect([
                'controller' => 'Angular',
                'action'     => 'forbidden',
                'plugin'     => ''
            ]);
        }

        $grafanaConfiguration = $this->GrafanaConfiguration->find('first', [
            'recursive' => -1,
            'contain'   => [
                'GrafanaConfigurationHostgroupMembership'
            ]
        ]);

        if (empty($grafanaConfiguration)) {
            $this->setFlash(__('No Grafana configuration found.'), false);
            return;
        }

        $allowEdit = $this->hasRootPrivileges;
        if ($allowEdit === false) {
            $allowEdit = $this->MY_RIGHTS_LEVEL[$dashboard['Container']['id']] == WRITE_RIGHT;
        }


        /** @var GrafanaApiConfiguration $GrafanaApiConfiguration */
        $GrafanaApiConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);

        /** @var $Proxy App\Model\Table\ProxiesTable */
        $Proxy = TableRegistry::getTableLocator()->get('Proxies');

        $dashboardFoundInGrafana = false;
        if ($this->GrafanaConfiguration->existsUserDashboard($GrafanaApiConfiguration, $Proxy->getSettings(), $dashboard['GrafanaUserdashboard']['grafana_uid'])) {
            $dashboardFoundInGrafana = true;
        }

        $this->set('dashboard', $dashboard);
        $this->set('allowEdit', $allowEdit);
        $this->set('dashboardFoundInGrafana', $dashboardFoundInGrafana);
        $this->set('_serialize', ['dashboard', 'allowEdit', 'dashboardFoundInGrafana']);
    }

    public function getViewIframeUrl($id) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if (!$this->GrafanaUserdashboard->exists($id)) {
            throw new NotFoundException();
        }

        $dashboard = $this->GrafanaUserdashboard->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'GrafanaUserdashboard.id'           => $id,
                'GrafanaUserdashboard.container_id' => $this->MY_RIGHTS
            ],
            'contain'    => [
                'Container'
            ]
        ]);

        if (empty($dashboard)) {
            throw new NotFoundException();
        }

        $grafanaConfiguration = $this->GrafanaConfiguration->find('first', [
            'recursive' => -1,
            'contain'   => [
                'GrafanaConfigurationHostgroupMembership'
            ]
        ]);

        if (empty($grafanaConfiguration)) {
            throw new RuntimeException('No Grafana configuration found.');
        }

        /** @var GrafanaApiConfiguration $GrafanaApiConfiguration */
        $GrafanaApiConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);

        /** @var $Proxy App\Model\Table\ProxiesTable */
        $Proxy = TableRegistry::getTableLocator()->get('Proxies');

        $dashboardFoundInGrafana = false;
        if ($this->GrafanaConfiguration->existsUserDashboard($GrafanaApiConfiguration, $Proxy->getSettings(), $dashboard['GrafanaUserdashboard']['grafana_uid'])) {
            $dashboardFoundInGrafana = true;
        }

        $from = $this->request->query('from');
        if ($from === null) {
            $from = 'now-3h';
        }
        $refresh = $this->request->query('refresh');
        if ($refresh === null) {
            $refresh = 0;
        }
        $iframeUrl = $GrafanaApiConfiguration->getIframeUrlForUserDashboard($dashboard['GrafanaUserdashboard']['grafana_url'], $from, $refresh);

        $this->set('dashboardFoundInGrafana', $dashboardFoundInGrafana);
        $this->set('iframeUrl', $iframeUrl);
        $this->set('_serialize', ['dashboardFoundInGrafana', 'iframeUrl']);
    }

    public function delete($id) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if (!$this->GrafanaUserdashboard->exists($id)) {
            throw new NotFoundException();
        }

        $writeContainers = [];
        foreach ($this->MY_RIGHTS_LEVEL as $containerId => $permissionLevel) {
            if ($permissionLevel == WRITE_RIGHT) {
                $writeContainers[] = $containerId;
            }
        }

        $dashboard = $this->GrafanaUserdashboard->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'GrafanaUserdashboard.id'           => $id,
                'GrafanaUserdashboard.container_id' => $writeContainers
            ]
        ]);

        if (!empty($dashboard)) {
            $grafanaConfiguration = $this->GrafanaConfiguration->find('first', [
                'recursive' => -1,
                'contain'   => [
                    'GrafanaConfigurationHostgroupMembership'
                ]
            ]);
            if ($this->GrafanaUserdashboard->delete($dashboard['GrafanaUserdashboard']['id'])) {
                if (!empty($grafanaConfiguration)) {
                    /** @var $Proxy App\Model\Table\ProxiesTable */
                    $Proxy = TableRegistry::getTableLocator()->get('Proxies');

                    /** @var GrafanaApiConfiguration $GrafanaApiConfiguration */
                    $GrafanaApiConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);
                    $client = $this->GrafanaConfiguration->testConnection($GrafanaApiConfiguration, $Proxy->getSettings());
                    if ($client instanceof Client) {
                        $deleteUrl = sprintf(
                            '%s/dashboards/uid/%s',
                            $GrafanaApiConfiguration->getApiUrl(),
                            $dashboard['GrafanaUserdashboard']['grafana_uid']
                        );
                        $request = new \GuzzleHttp\Psr7\Request('DELETE', $deleteUrl, ['content-type' => 'application/json']);
                        try {
                            $response = $client->send($request);
                        } catch (\Exception $e) {
                            //Error while deleting dashboard form Grafana
                            //$message = $e->getMessage();
                            //$success = false;
                        }
                    }
                }

                $this->set('success', true);
                $this->set('message', __('User defined Grafana dashboard successfully deleted'));
                $this->set('_serialize', ['success', 'message']);
                return;
            }
        }

        $this->response->statusCode(400);
        $this->set('success', false);
        $this->set('message', __('Could not delete user defined Grafana dashboard'));
        $this->set('_serialize', ['success', 'message']);
    }

    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $grafanaConfig = $this->GrafanaConfiguration->find('first', [
            'recursive' => -1,
            'order'     => ['GrafanaConfiguration.id' => 'DESC']
        ]);

        $hasGrafanaConfig = false;
        if (!empty($grafanaConfig)) {
            $hasGrafanaConfig = true;
        }


        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, CT_TENANT, [], $this->hasRootPrivileges);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), CT_TENANT, [], $this->hasRootPrivileges);
        }
        $containers = Api::makeItJavaScriptAble($containers);

        $this->set('hasGrafanaConfig', $hasGrafanaConfig);
        $this->set('containers', $containers);
        $this->set('_serialize', ['containers', 'hasGrafanaConfig']);
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

        $ids = $this->request->data('ids');
        if (!empty($ids) && is_array($ids)) {
            $conditions = [
                'GrafanaUserdashboardPanel.id' => $ids
            ];
            if ($this->GrafanaUserdashboardPanel->deleteAll($conditions)) {
                $this->set('success', true);
                $this->set('_serialize', ['success']);
                return;
            }
        }

        $this->set('success', false);
        $this->set('_serialize', ['success']);
    }

    public function savePanelUnit() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->data('id');
        $unit = $this->request->data('unit');
        $title = $this->request->data('title');

        $GrafanaTargetUnits = new GrafanaTargetUnits();
        if ($this->GrafanaUserdashboardPanel->exists($id) && $GrafanaTargetUnits->exists($unit)) {
            $panel = $this->GrafanaUserdashboardPanel->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'GrafanaUserdashboardPanel.id' => $id
                ],
            ]);

            $panel['GrafanaUserdashboardPanel']['unit'] = $unit;
            $panel['GrafanaUserdashboardPanel']['title'] = $title;
            if ($this->GrafanaUserdashboardPanel->save($panel)) {
                $this->set('success', true);
                $this->set('_serialize', ['success']);
                return;
            }
        }

        $this->set('success', false);
        $this->set('_serialize', ['success']);
    }

    public function synchronizeWithGrafana($id = null) {
        if (!$this->request->is('get') && !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if ($id === null) {
            $id = $this->request->data('id');
        }
        if (!$this->GrafanaUserdashboard->exists($id)) {
            throw new NotFoundException();
        }

        $grafanaConfiguration = $this->GrafanaConfiguration->find('first', [
            'recursive' => -1,
            'contain'   => [
                'GrafanaConfigurationHostgroupMembership'
            ]
        ]);

        if (empty($grafanaConfiguration)) {
            $this->set('success', false);
            $this->set('message', __('No Grafana configuration found.'));
            $this->set('_serialize', ['success', 'message']);
            return;
        }

        /** @var GrafanaApiConfiguration $GrafanaApiConfiguration */
        $GrafanaApiConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);

        /** @var $Proxy App\Model\Table\ProxiesTable */
        $Proxy = TableRegistry::getTableLocator()->get('Proxies');

        $client = $this->GrafanaConfiguration->testConnection($GrafanaApiConfiguration, $Proxy->getSettings());


        $dashboard = $this->GrafanaUserdashboard->find('first', $this->GrafanaUserdashboard->getQuery($id));
        $rows = $this->GrafanaUserdashboard->extractRowsWithPanelsAndMetricsFromFindResult($dashboard);

        if ($client instanceof Client) {
            $tag = new GrafanaTag();
            $GrafanaDashboard = new \itnovum\openITCOCKPIT\Grafana\GrafanaDashboard();
            $GrafanaDashboard->setTitle($dashboard['GrafanaUserdashboard']['name']);
            $GrafanaDashboard->setEditable(true);
            $GrafanaDashboard->setTags($tag->getTag());
            $GrafanaDashboard->setHideControls(false);
            $GrafanaDashboard->setAutoRefresh('1m');
            $GrafanaDashboard->setTimeInHours('3');


            foreach ($rows as $row) {
                $GrafanaRow = new GrafanaRow();
                foreach ($row as $panel) {
                    $GrafanaTargetCollection = new GrafanaTargetCollection();
                    $SpanSize = 12 / sizeof($row);
                    $GrafanaPanel = new GrafanaPanel($panel['id'], $SpanSize);
                    $GrafanaPanel->setTitle($panel['title']);

                    foreach ($panel['metrics'] as $metric) {
                        /**  TODO implement perfdata backends **/
                        $replacedMetricName = preg_replace('/[^a-zA-Z^0-9\-\.]/', '_', $metric['metric']);
                        $GrafanaTargetCollection->addTarget(
                            new GrafanaTarget(
                                sprintf(
                                    '%s.%s.%s.%s',
                                    $GrafanaApiConfiguration->getGraphitePrefix(),
                                    $metric['Host']['uuid'],
                                    $metric['Service']['uuid'],
                                    $replacedMetricName
                                ),
                                new GrafanaTargetUnit($panel['unit'], true),
                                new GrafanaThresholds(null, null),
                                sprintf(
                                    '%s.%s.%s',
                                    $this->replaceUmlauts($metric['Host']['hostname']),
                                    $this->replaceUmlauts($metric['Service']['servicename']),
                                    $this->replaceUmlauts($metric['metric'])
                                )//Alias
                            ));
                    }
                    $GrafanaPanel->addTargets(
                        $GrafanaTargetCollection,
                        new GrafanaSeriesOverrides($GrafanaTargetCollection),
                        new GrafanaYAxes($GrafanaTargetCollection),
                        new GrafanaThresholdCollection($GrafanaTargetCollection)
                    );
                    $GrafanaRow->addPanel($GrafanaPanel);
                }
                $GrafanaDashboard->addRow($GrafanaRow);
            }
            $json = $GrafanaDashboard->getGrafanaDashboardJson();

            if ($json) {
                $request = new \GuzzleHttp\Psr7\Request('POST', $GrafanaApiConfiguration->getApiUrl() . '/dashboards/db', ['content-type' => 'application/json'], $json);
                try {
                    $response = $client->send($request);
                } catch (BadRequestException $e) {
                    $response = $e->getResponse();
                    $responseBody = $response->getBody()->getContents();
                    $message = $responseBody;
                    $success = false;
                } catch (\Exception $e) {
                    $message = $e->getMessage();
                    $success = false;
                }
                if ($response->getStatusCode() == 200) {

                    //Save Grafana URL and GUI to database
                    $responseBody = $response->getBody()->getContents();
                    $data = json_decode($responseBody);

                    $dashboard = $this->GrafanaUserdashboard->find('first', [
                        'recursive'  => -1,
                        'conditions' => [
                            'GrafanaUserdashboard.id' => $id
                        ]
                    ]);
                    $dashboard['GrafanaUserdashboard']['grafana_uid'] = $data->uid;
                    $dashboard['GrafanaUserdashboard']['grafana_url'] = $data->url;
                    if ($this->GrafanaUserdashboard->save($dashboard)) {
                        $message = __('Synchronization finished successfully');
                        $success = true;
                    }
                }
            }

        }

        $this->set('success', $success);
        $this->set('message', $message);
        $this->set('_serialize', ['success', 'message']);
    }

    public function grafanaWidget() {
        $this->layout = 'blank';

        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $this->loadModel('Widget');
        if ($this->request->is('get')) {
            $widgetId = (int)$this->request->query('widgetId');
            if (!$this->Widget->exists($widgetId)) {
                throw new RuntimeException('Invalid widget id');
            }

            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $widgetId
                ],
                'fields'     => [
                    'Widget.json_data'
                ]
            ]);

            $grafanaDashboardId = null;
            if ($widget['Widget']['json_data'] !== null && $widget['Widget']['json_data'] !== '') {
                $json = @json_decode($widget['Widget']['json_data'], true);
                if (isset($json['GrafanaUserdashboard']['id'])) {
                    $grafanaDashboardId = $json['GrafanaUserdashboard']['id'];
                }
            }


            $dashboard = $this->GrafanaUserdashboard->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'GrafanaUserdashboard.id' => $grafanaDashboardId
                ]
            ]);

            $iframeUrl = '';
            if (!empty($grafanaDashboardId) && !empty($dashboard)) {
                $grafanaConfiguration = $this->GrafanaConfiguration->find('first');
                if (!empty($grafanaConfiguration)) {

                    /** @var $Proxy App\Model\Table\ProxiesTable */
                    $Proxy = TableRegistry::getTableLocator()->get('Proxies');

                    $GrafanaConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);
                    if ($this->GrafanaConfiguration->existsUserDashboard($GrafanaConfiguration, $Proxy->getSettings(), $dashboard['GrafanaUserdashboard']['grafana_uid'])) {
                        $iframeUrl = $GrafanaConfiguration->getIframeUrlForUserDashboard($dashboard['GrafanaUserdashboard']['grafana_url']);
                    }
                }
            }


            $this->set('grafana_userdashboard_id', $grafanaDashboardId);
            $this->set('iframe_url', $iframeUrl);
            $this->set('_serialize', ['grafana_userdashboard_id', 'iframe_url']);
            return;
        }

        if ($this->request->is('post')) {
            $grafanaDashboardId = (int)$this->request->data('dashboard_id');
            if ($grafanaDashboardId === 0) {
                $grafanaDashboardId = null;
            }

            $widgetId = (int)$this->request->data('Widget.id');

            if (!$this->Widget->exists($widgetId)) {
                throw new RuntimeException('Invalid widget id');
            }
            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $widgetId
                ],
            ]);

            $widget['Widget']['json_data'] = json_encode([
                'GrafanaUserdashboard' => [
                    'id' => $grafanaDashboardId
                ]
            ]);

            if ($this->Widget->save($widget)) {
                $this->set('grafana_userdashboard_id', $grafanaDashboardId);
                $this->set('_serialize', ['grafana_userdashboard_id']);
                return;
            }

            $this->serializeErrorMessageFromModel('Widget');
            return;
        }
        throw new MethodNotAllowedException();
    }

    /**
     * @param string $str
     * @return string
     */
    private function replaceUmlauts($str) {
        return str_replace(
            ['ä', 'ü', 'ö', 'Ä', 'Ü', 'Ö', 'ß'],
            ['ae', 'ue', 'oe', 'Ae', 'Ue', 'Oe', 'ss'],
            $str
        );
    }

    public function grafanaTimepicker() {
        if (!$this->isAngularJsRequest()) {
            $this->layout = 'blank';
            //Only ship HTML Template
            return;
        }

        if ($this->isAngularJsRequest()) {
            $timeranges = [
                'quick'           => [
                    'now-2d'  => __('Last 2 days'),
                    'now-7d'  => __('Last 7 days'),
                    'now-30d' => __('Last 30 days'),
                    'now-90d' => __('Last 90 days'),
                    'now-6M'  => __('Last 6 months'),
                    'now-1y'  => __('Last year')
                ],
                'today'           => [
                    'now%2Fd' => __('Today so far'),
                    'now%2Fw' => __('This week so far'),
                    'now%2FM' => __('This month so far')
                ],
                'last'            => [
                    'now-5m'  => __('Last 5 minutes'),
                    'now-15m' => __('Last 15 minutes'),
                    'now-30m' => __('Last 30 minutes'),
                    'now-1h'  => __('Last 1 hour'),
                    'now-3h'  => __('Last 3 hours'),
                    'now-6h'  => __('Last 6 hours'),
                    'now-12h' => __('Last 12 hours'),
                    'now-24h' => __('Last 24 hours'),
                ],
                'update_interval' => [
                    //'0'   => __('Disabled'), //Does not work via URL becuase is still in dashboard json :/
                    '5s'  => __('Refresh every 5s'),
                    '10s' => __('Refresh every 10s'),
                    '30s' => __('Refresh every 30s'),
                    '1m'  => __('Refresh every 1m'),
                    '5m'  => __('Refresh every 5m'),
                    '15m' => __('Refresh every 15m')
                ]
            ];
            $this->set('timeranges', $timeranges);
            $this->set('_serialize', ['timeranges']);
        }

    }
}