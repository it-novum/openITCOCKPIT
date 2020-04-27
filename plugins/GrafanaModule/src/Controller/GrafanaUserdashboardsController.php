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

namespace GrafanaModule\Controller;

use App\Model\Table\ContainersTable;
use App\Model\Table\ProxiesTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\WidgetsTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use GrafanaModule\Model\Table\GrafanaConfigurationsTable;
use GrafanaModule\Model\Table\GrafanaUserdashboardMetricsTable;
use GrafanaModule\Model\Table\GrafanaUserdashboardPanelsTable;
use GrafanaModule\Model\Table\GrafanaUserdashboardsTable;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\FileDebugger;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\Service;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\GrafanaUserDashboardFilter;
use itnovum\openITCOCKPIT\Grafana\GrafanaApiConfiguration;
use itnovum\openITCOCKPIT\Grafana\GrafanaDashboard;
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
 * @package GrafanaModule\Controller
 */
class GrafanaUserdashboardsController extends AppController {

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        /** @var GrafanaUserdashboardsTable $GrafanaUserdashboardsTable */
        $GrafanaUserdashboardsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboards');

        $skipUnsyncDashboards = $this->request->getQuery('skipUnsyncDashboards', 0) != 0;

        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges === true) {
            $MY_RIGHTS = [];
        }

        $GrafanaUserDashboardFilter = new GrafanaUserDashboardFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $GrafanaUserDashboardFilter->getPage());
        $dashboards = $GrafanaUserdashboardsTable->getGrafanaUserdashboardsIndex($GrafanaUserDashboardFilter, $PaginateOMat, $MY_RIGHTS, $skipUnsyncDashboards);

        foreach ($dashboards as $key => $dashboard) {
            $dashboards[$key]['allowEdit'] = false;
            if ($this->hasRootPrivileges == true) {
                $dashboards[$key]['allowEdit'] = true;
                continue;
            } else {
                if ($this->MY_RIGHTS_LEVEL[$dashboard['container_id']] === WRITE_RIGHT) {
                    $dashboards[$key]['allowEdit'] = true;
                    continue;
                }
            }
        }

        $this->set('all_userdashboards', $dashboards);
        $this->viewBuilder()->setOption('serialize', ['all_userdashboards']);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        /** @var GrafanaConfigurationsTable $GrafanaConfigurationsTable */
        $GrafanaConfigurationsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaConfigurations');

        if ($this->request->is('post')) {
            /** @var GrafanaUserdashboardsTable $GrafanaUserdashboardsTable */
            $GrafanaUserdashboardsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboards');

            $data = $this->request->getData('GrafanaUserdashboard', []);
            $entity = $GrafanaUserdashboardsTable->newEntity($data);
            $entity->set('configuration_id', $GrafanaConfigurationsTable->getConfigurationId());
            $entity->setAccess('configuration_id', false);

            $entity = $GrafanaUserdashboardsTable->patchEntity($entity, $data);
            $GrafanaUserdashboardsTable->save($entity);
            if ($entity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $entity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            $this->set('GrafanaUserdashboard', $entity);
            $this->viewBuilder()->setOption('serialize', ['GrafanaUserdashboard']);
        }
    }


    /**
     * @param int|null $userdashboardId
     */
    public function editor($userdashboardId = null) {
        if (!$this->isApiRequest() && $userdashboardId === null) {
            //Only ship template for AngularJs

            /** @var GrafanaConfigurationsTable $GrafanaConfigurationsTable */
            $GrafanaConfigurationsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaConfigurations');
            $grafanaConfig = $GrafanaConfigurationsTable->getGrafanaConfiguration();
            $hasGrafanaConfig = $grafanaConfig['api_url'] !== '';
            $this->set('hasGrafanaConfig', $hasGrafanaConfig);
            return;
        }

        if (!$this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        /** @var GrafanaUserdashboardsTable $GrafanaUserdashboardsTable */
        $GrafanaUserdashboardsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboards');

        if (!$GrafanaUserdashboardsTable->existsById($userdashboardId) && $userdashboardId !== null) {
            throw new NotFoundException(__('Invalid Userdashboard'));
        }

        $dashboard = $GrafanaUserdashboardsTable->getGrafanaUserDashboardEdit($userdashboardId);
        $dashboard['rows'] = $GrafanaUserdashboardsTable->extractRowsWithPanelsAndMetricsFromFindResult($dashboard);

        $GrafanaUnits = new GrafanaTargetUnits();
        $this->set('userdashboardData', $dashboard);
        $this->set('grafanaUnits', $GrafanaUnits->getUnits());
        $this->viewBuilder()->setOption('serialize', ['userdashboardData', 'grafanaUnits']);

        return;
    }

    /**
     * @param int|null $id
     */
    public function edit($id = null) {
        if ($this->isHtmlRequest()) {
            //Only ship template for AngularJs
            return;
        }

        /** @var GrafanaConfigurationsTable $GrafanaConfigurationsTable */
        $GrafanaConfigurationsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaConfigurations');
        /** @var GrafanaUserdashboardsTable $GrafanaUserdashboardsTable */
        $GrafanaUserdashboardsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboards');
        if (!$GrafanaUserdashboardsTable->existsById($id) && $id !== null) {
            throw new NotFoundException();
        }

        $dashboard = $GrafanaUserdashboardsTable->find()
            ->where([
                'GrafanaUserdashboards.id'              => $id,
                'GrafanaUserdashboards.container_id IN' => $this->MY_RIGHTS
            ])
            ->first();

        if ($dashboard === null) {
            return $this->render403();
        }

        if ($this->request->is('POST')) {
            $dashboard->setAccess('id', false);
            $dashboard = $GrafanaUserdashboardsTable->patchEntity($dashboard, $this->request->getData());

            $GrafanaUserdashboardsTable->save($dashboard);
            if ($dashboard->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $dashboard->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }
        }

        //GET
        $grafanaConfig = $GrafanaConfigurationsTable->getGrafanaConfiguration();
        $hasGrafanaConfig = $grafanaConfig['api_url'] !== '';

        $this->set('hasGrafanaConfig', $hasGrafanaConfig);
        $this->set('dashboard', $dashboard->toArray());
        $this->viewBuilder()->setOption('serialize', ['dashboard', 'hasGrafanaConfig']);
    }

    /**
     * @param int|null $id
     */
    public function view($id = null) {
        if ($this->isHtmlRequest()) {
            //Only ship template for AngularJs
            return;
        }

        /** @var GrafanaUserdashboardsTable $GrafanaUserdashboardsTable */
        $GrafanaUserdashboardsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboards');

        if (!$GrafanaUserdashboardsTable->existsById($id)) {
            throw new NotFoundException();
        }

        $dashboard = $GrafanaUserdashboardsTable->find()
            ->where([
                'GrafanaUserdashboards.id'              => $id,
                'GrafanaUserdashboards.container_id IN' => $this->MY_RIGHTS
            ])
            ->first();

        if ($dashboard === null) {
            return $this->render403();
        }

        /** @var GrafanaConfigurationsTable $GrafanaConfigurationsTable */
        $GrafanaConfigurationsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaConfigurations');

        $grafanaConfiguration = $GrafanaConfigurationsTable->getGrafanaConfiguration();
        /** @var GrafanaApiConfiguration $GrafanaApiConfiguration */
        $GrafanaApiConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);

        $allowEdit = $this->hasRootPrivileges;
        if ($allowEdit === false) {
            $allowEdit = $this->MY_RIGHTS_LEVEL[$dashboard->get('container_id')] == WRITE_RIGHT;
        }

        /** @var ProxiesTable $ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');

        $dashboardFoundInGrafana = false;
        if ($GrafanaConfigurationsTable->existsUserDashboard($GrafanaApiConfiguration, $ProxiesTable->getSettings(), $dashboard->get('grafana_uid'))) {
            $dashboardFoundInGrafana = true;
        }

        $this->set('dashboard', $dashboard->toArray());
        $this->set('allowEdit', $allowEdit);
        $this->set('dashboardFoundInGrafana', $dashboardFoundInGrafana);
        $this->viewBuilder()->setOption('serialize', ['dashboard', 'allowEdit', 'dashboardFoundInGrafana']);
    }

    /**
     * @param int $id
     */
    public function getViewIframeUrl($id) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var GrafanaUserdashboardsTable $GrafanaUserdashboardsTable */
        $GrafanaUserdashboardsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboards');

        /** @var GrafanaConfigurationsTable $GrafanaConfigurationsTable */
        $GrafanaConfigurationsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaConfigurations');

        $grafanaConfiguration = $GrafanaConfigurationsTable->getGrafanaConfiguration();
        /** @var GrafanaApiConfiguration $GrafanaApiConfiguration */
        $GrafanaApiConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);

        if (!$GrafanaUserdashboardsTable->existsById($id)) {
            throw new NotFoundException();
        }

        $dashboard = $GrafanaUserdashboardsTable->find()
            ->where([
                'GrafanaUserdashboards.id'              => $id,
                'GrafanaUserdashboards.container_id IN' => $this->MY_RIGHTS
            ])
            ->first();

        if ($dashboard === null) {
            return $this->render403();
        }


        /** @var ProxiesTable $ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');

        $dashboardFoundInGrafana = false;
        if ($GrafanaConfigurationsTable->existsUserDashboard($GrafanaApiConfiguration, $ProxiesTable->getSettings(), $dashboard->get('grafana_uid'))) {
            $dashboardFoundInGrafana = true;
        }

        $from = $this->request->getQuery('from', null);
        if ($from === null) {
            $from = 'now-3h';
        }
        $refresh = $this->request->getQuery('refresh', null);
        if ($refresh === null) {
            $refresh = 0;
        }
        $iframeUrl = $GrafanaApiConfiguration->getIframeUrlForUserDashboard($dashboard->get('grafana_url'), $from, $refresh);

        $this->set('dashboardFoundInGrafana', $dashboardFoundInGrafana);
        $this->set('iframeUrl', $iframeUrl);
        $this->viewBuilder()->setOption('serialize', ['dashboardFoundInGrafana', 'iframeUrl']);
    }

    /**
     * @param int $id
     */
    public function delete($id) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var GrafanaConfigurationsTable $GrafanaConfigurationsTable */
        $GrafanaConfigurationsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaConfigurations');
        $grafanaConfiguration = $GrafanaConfigurationsTable->getGrafanaConfiguration();
        $hasGrafanaConfig = $grafanaConfiguration['api_url'] !== '';
        $GrafanaApiConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);

        /** @var GrafanaUserdashboardsTable $GrafanaUserdashboardsTable */
        $GrafanaUserdashboardsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboards');

        if (!$GrafanaUserdashboardsTable->existsById($id)) {
            throw new NotFoundException('GrafanaUserdashboard does not exisits');
        }

        $writeContainers = [];
        foreach ($this->MY_RIGHTS_LEVEL as $containerId => $permissionLevel) {
            if ($permissionLevel == WRITE_RIGHT) {
                $writeContainers[] = $containerId;
            }
        }

        $dashboard = $GrafanaUserdashboardsTable->get($id);
        if (!$this->isWritableContainer($dashboard->get('container_id'))) {
            return $this->render403();
        }

        // Delete dashboard at Grafana
        /** @var ProxiesTable $ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');

        $client = $GrafanaConfigurationsTable->testConnection($GrafanaApiConfiguration, $ProxiesTable->getSettings());
        if ($client instanceof Client) {
            $deleteUrl = sprintf(
                '%s/dashboards/uid/%s',
                $GrafanaApiConfiguration->getApiUrl(),
                $dashboard->get('grafana_uid')
            );
            $request = new Request('DELETE', $deleteUrl, ['content-type' => 'application/json']);
            try {
                $response = $client->send($request);
            } catch (\Exception $e) {
                Log::error('GrafanaModule: Error while deleting UserGrafanadashboard from Grafana');
                Log::error($e->getMessage());
            }
        }

        if ($GrafanaUserdashboardsTable->delete($dashboard, ['cascadeCallbacks' => true])) {
            $this->set('success', true);
            $this->set('message', __('User defined Grafana dashboard successfully deleted'));
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
            return;
        }

        $this->response = $this->response->withStatus(400);
        $this->set('success', false);
        $this->set('message', __('Could not delete user defined Grafana dashboard'));
        $this->viewBuilder()->setOption('serialize', ['success', 'message']);
    }

    /**
     * @throws \Exception
     */
    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var GrafanaConfigurationsTable $GrafanaConfigurationsTable */
        $GrafanaConfigurationsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaConfigurations');
        $grafanaConfig = $GrafanaConfigurationsTable->getGrafanaConfiguration();
        $hasGrafanaConfig = $grafanaConfig['api_url'] !== '';

        /** @var ContainersTable $ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, CT_TENANT, [], $this->hasRootPrivileges);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), CT_TENANT, [], $this->hasRootPrivileges);
        }
        $containers = Api::makeItJavaScriptAble($containers);

        $this->set('hasGrafanaConfig', $hasGrafanaConfig);
        $this->set('containers', $containers);
        $this->viewBuilder()->setOption('serialize', ['containers', 'hasGrafanaConfig']);
    }

    public function grafanaRow() {
        return;
    }

    public function grafanaPanel() {
        return;
    }

    /**
     * @param int $serviceId
     * @throws \App\Lib\Exceptions\MissingDbBackendException
     */
    public function getPerformanceDataMetrics($serviceId = 0) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        $ServicestatusTable = $this->DbBackend->getServicestatusTable();

        if (!$ServicesTable->existsById($serviceId)) {
            throw new NotFoundException();
        }


        $service = $ServicesTable->getServiceById($serviceId);


        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->perfdata();
        $servicestatus = $ServicestatusTable->byUuid($service->get('uuid'), $ServicestatusFields);

        if (!empty($servicestatus)) {
            $PerfdataParser = new PerfdataParser($servicestatus['Servicestatus']['perfdata']);
            $this->set('perfdata', $PerfdataParser->parse());
            $this->viewBuilder()->setOption('serialize', ['perfdata']);
            return;
        }
        $this->set('perfdata', []);
        $this->viewBuilder()->setOption('serialize', ['perfdata']);
    }

    public function addMetricToPanel() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        /** @var GrafanaUserdashboardMetricsTable $GrafanaUserdashboardMetricsTable */
        $GrafanaUserdashboardMetricsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboardMetrics');

        $service = $ServicesTable->getServiceById($this->request->getData('GrafanaUserdashboardMetric.service_id', 0));

        $data = $this->request->getData('GrafanaUserdashboardMetric', []);
        if ($service === null) {
            //Trigger validation error
            $data['service_id'] = null;
            $data['host_id'] = null;
        } else {
            $data['host_id'] = $service->get('host_id');
        }

        $metric = $GrafanaUserdashboardMetricsTable->newEntity($data);

        $GrafanaUserdashboardMetricsTable->save($metric);
        if ($metric->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $metric->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $Host = new Host($service->get('host'));
        $Service = new Service($service->toArray());

        $metric = $metric->toArray();
        $metric['Host'] = $Host->toArray();
        $metric['Service'] = $Service->toArray();

        $this->set('metric', $metric);
        $this->viewBuilder()->setOption('serialize', ['metric']);
    }

    public function removeMetricFromPanel() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->getData('id', 0);

        /** @var GrafanaUserdashboardMetricsTable $GrafanaUserdashboardMetricsTable */
        $GrafanaUserdashboardMetricsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboardMetrics');

        if ($GrafanaUserdashboardMetricsTable->existsById($id)) {
            $metric = $GrafanaUserdashboardMetricsTable->get($id);
            if ($GrafanaUserdashboardMetricsTable->delete($metric)) {
                $this->set('success', true);
                $this->viewBuilder()->setOption('serialize', ['success']);
                return;
            }
        }

        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function addPanel() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var GrafanaUserdashboardPanelsTable $GrafanaUserdashboardPanelsTable */
        $GrafanaUserdashboardPanelsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboardPanels');

        $panel = $GrafanaUserdashboardPanelsTable->newEntity($this->request->getData('GrafanaUserdashboardPanel', []));

        $GrafanaUserdashboardPanelsTable->save($panel);
        if ($panel->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $panel->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $this->set('panel', [
            'id'               => $panel->get('id'),
            'row'              => $panel->get('row'),
            'userdashboard_id' => $panel->get('userdashboard_id'),
            'unit'             => '',
            'metrics'          => []
        ]);
        $this->viewBuilder()->setOption('serialize', ['panel']);
    }

    public function removePanel() {
        /** @var GrafanaUserdashboardPanelsTable $GrafanaUserdashboardPanelsTable */
        $GrafanaUserdashboardPanelsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboardPanels');

        $id = $this->request->getData('id', 0);
        if ($GrafanaUserdashboardPanelsTable->existsById($id)) {
            $panel = $GrafanaUserdashboardPanelsTable->get($id);

            if ($GrafanaUserdashboardPanelsTable->delete($panel)) {
                $this->set('success', true);
                $this->viewBuilder()->setOption('serialize', ['success']);
                return;
            }
        }

        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }


    public function addRow() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->getData('id', 0);

        /** @var GrafanaUserdashboardsTable $GrafanaUserdashboardsTable */
        $GrafanaUserdashboardsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboards');
        /** @var GrafanaUserdashboardPanelsTable $GrafanaUserdashboardPanelsTable */
        $GrafanaUserdashboardPanelsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboardPanels');

        if (!$GrafanaUserdashboardsTable->existsById($id)) {
            throw new NotFoundException('GrafanaUserdashboard does not exisits');
        }

        $data = [
            'userdashboard_id' => $id,
            'row'              => $GrafanaUserdashboardPanelsTable->getNextRow($id)
        ];

        $panel = $GrafanaUserdashboardPanelsTable->newEntity($data);

        $GrafanaUserdashboardPanelsTable->save($panel);
        if ($panel->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $panel->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function removeRow() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $success = false;
        $ids = $this->request->getData('ids', []);
        if (!empty($ids) && is_array($ids)) {
            /** @var GrafanaUserdashboardPanelsTable $GrafanaUserdashboardPanelsTable */
            $GrafanaUserdashboardPanelsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboardPanels');
            $success = true;
            foreach ($ids as $id) {
                $panel = $GrafanaUserdashboardPanelsTable->get($id);
                if (!$GrafanaUserdashboardPanelsTable->delete($panel)) {
                    $success = false;
                }
            }
        }

        $this->set('success', $success);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function savePanelUnit() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->getData('id', 0);
        $unit = $this->request->getData('unit', 'none');
        $title = $this->request->getData('title', '');

        /** @var GrafanaUserdashboardPanelsTable $GrafanaUserdashboardPanelsTable */
        $GrafanaUserdashboardPanelsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboardPanels');

        $GrafanaTargetUnits = new GrafanaTargetUnits();
        if ($GrafanaUserdashboardPanelsTable->existsById($id) && $GrafanaTargetUnits->exists($unit)) {
            $panel = $GrafanaUserdashboardPanelsTable->get($id);
            $panel->set('title', $title);
            $panel->set('unit', $unit);

            if ($GrafanaUserdashboardPanelsTable->save($panel)) {
                $this->set('success', true);
                $this->viewBuilder()->setOption('serialize', ['success']);
                return;
            }
        }

        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function synchronizeWithGrafana($id = null) {
        if (!$this->request->is('get') && !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if ($id === null) {
            $id = $this->request->getData('id', 0);
        }
        $success = false;
        $message = '';

        /** @var GrafanaUserdashboardsTable $GrafanaUserdashboardsTable */
        $GrafanaUserdashboardsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboards');
        /** @var GrafanaConfigurationsTable $GrafanaConfigurationsTable */
        $GrafanaConfigurationsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaConfigurations');

        if (!$GrafanaUserdashboardsTable->existsById($id)) {
            throw new NotFoundException();
        }

        $grafanaConfiguration = $GrafanaConfigurationsTable->getGrafanaConfiguration();
        /** @var GrafanaApiConfiguration $GrafanaApiConfiguration */
        $GrafanaApiConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);

        /** @var $ProxiesTable ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');

        $client = $GrafanaConfigurationsTable->testConnection($GrafanaApiConfiguration, $ProxiesTable->getSettings());


        $dashboard = $GrafanaUserdashboardsTable->getGrafanaUserDashboardEdit($id);
        $rows = $GrafanaUserdashboardsTable->extractRowsWithPanelsAndMetricsFromFindResult($dashboard);

        if ($client instanceof Client) {
            $tag = new GrafanaTag();
            $GrafanaDashboard = new GrafanaDashboard();
            $GrafanaDashboard->setTitle($dashboard['name']);
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
                                ),//Alias
                                $metric['color'] ?? null
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
                $request = new Request('POST', $GrafanaApiConfiguration->getApiUrl() . '/dashboards/db', ['content-type' => 'application/json'], $json);
                try {
                    $response = $client->send($request);
                } catch (BadResponseException $e) {
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

                    $enity = $GrafanaUserdashboardsTable->get($id);
                    $enity->set('grafana_uid', $data->uid);
                    $enity->set('grafana_url', $data->url);
                    $GrafanaUserdashboardsTable->save($enity);

                    if (!$enity->hasErrors()) {
                        $message = __('Synchronization finished successfully');
                        $success = true;
                    }
                }
            }
        }

        if($success === false){
            $message = __('An error has been occured while synchronizing');
        }
        $this->set('success', $success);
        $this->set('message', $message);
        $this->viewBuilder()->setOption('serialize', ['success', 'message']);
    }

    public function grafanaWidget() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        /** @var WidgetsTable $WidgetsTable */
        $WidgetsTable = TableRegistry::getTableLocator()->get('Widgets');

        /** @var GrafanaConfigurationsTable $GrafanaConfigurationsTable */
        $GrafanaConfigurationsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaConfigurations');
        /** @var GrafanaUserdashboardsTable $GrafanaUserdashboardsTable */
        $GrafanaUserdashboardsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboards');

        if ($this->request->is('get')) {
            $widgetId = (int)$this->request->getQuery('widgetId', 0);
            if (!$WidgetsTable->existsById($widgetId)) {
                throw new \RuntimeException('Invalid widget id');
            }

            $widget = $WidgetsTable->getWidgetByIdAsCake2($widgetId);

            $grafanaDashboardId = null;
            $iframeUrl = '';
            if ($widget['Widget']['json_data'] !== null && $widget['Widget']['json_data'] !== '') {
                $json = @json_decode($widget['Widget']['json_data'], true);
                if (isset($json['GrafanaUserdashboard']['id'])) {
                    $grafanaDashboardId = $json['GrafanaUserdashboard']['id'];
                }
            }

            if (!empty($grafanaDashboardId)) {
                $dashboard = $GrafanaUserdashboardsTable->get($grafanaDashboardId);

                if (!empty($dashboard)) {
                    $grafanaConfiguration = $GrafanaConfigurationsTable->getGrafanaConfiguration();
                    /** @var ProxiesTable $ProxiesTable */
                    $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');

                    $GrafanaConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);
                    if ($GrafanaConfigurationsTable->existsUserDashboard($GrafanaConfiguration, $ProxiesTable->getSettings(), $dashboard->get('grafana_uid'))) {
                        $iframeUrl = $GrafanaConfiguration->getIframeUrlForUserDashboard($dashboard->get('grafana_url'));
                    }
                }
            }

            $this->set('grafana_userdashboard_id', $grafanaDashboardId);
            $this->set('iframe_url', $iframeUrl);
            $this->viewBuilder()->setOption('serialize', ['grafana_userdashboard_id', 'iframe_url']);
            return;
        }

        if ($this->request->is('post')) {
            $grafanaDashboardId = (int)$this->request->getData('dashboard_id', 0);
            if ($grafanaDashboardId === 0) {
                $grafanaDashboardId = null;
            }

            $widgetId = (int)$this->request->getData('Widget.id', 0);

            if (!$WidgetsTable->existsById($widgetId)) {
                throw new \RuntimeException('Invalid widget id');
            }

            $widget = $WidgetsTable->get($widgetId);

            $widget->set('json_data', json_encode([
                'GrafanaUserdashboard' => [
                    'id' => $grafanaDashboardId
                ]
            ]));
            $WidgetsTable->save($widget);
            if ($widget->hasErrors() === false) {
                $this->set('grafana_userdashboard_id', $grafanaDashboardId);
                $this->viewBuilder()->setOption('serialize', ['grafana_userdashboard_id']);
                return;
            }

            $this->serializeCake4ErrorMessage($widget);
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
                    //'0'   => __('Disabled'), //Does not work via URL because is still in dashboard json :/
                    '5s'  => __('Refresh every 5s'),
                    '10s' => __('Refresh every 10s'),
                    '30s' => __('Refresh every 30s'),
                    '1m'  => __('Refresh every 1m'),
                    '5m'  => __('Refresh every 5m'),
                    '15m' => __('Refresh every 15m')
                ]
            ];
            $this->set('timeranges', $timeranges);
            $this->viewBuilder()->setOption('serialize', ['timeranges']);
        }

    }
}
