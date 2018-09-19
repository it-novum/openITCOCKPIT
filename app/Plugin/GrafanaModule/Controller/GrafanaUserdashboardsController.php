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


class GrafanaUserdashboardsController extends GrafanaModuleAppController {

    public $layout = 'angularjs';

    public $uses = [
        'Host',
        'Service',
        'Rrd',
        'GrafanaModule.GrafanaConfiguration',
        'GrafanaModule.GrafanaDashboard',
        'GrafanaModule.GrafanaUserdashboard',
        'GrafanaModule.GrafanaUserdashboardData',
    ];

    public $components = [
        'CustomValidationErrors'
    ];

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }
        $allUserdashboards = $this->GrafanaUserdashboard->find('all',[
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
                    $this->setFlash(__('Grafana Userdashboard Name successfully saved'));
                }

                if ($this->request->ext === 'json') {
                    $this->serializeId();
                    return;
                }
            } else {
                $this->serializeErrorMessage();
                return;
            }
        }
    }


    public function editor($userdashboardId = null) {
        if (!$this->GrafanaUserdashboard->exists($userdashboardId)) {
            throw new NotFoundException(__('Invalid Userdashboard'));
        }

        if ($this->request->is('post')) {
            //non-transformed data for the Grafana dashboard creation

            //data to save in DB
            $dataToSave = $this->GrafanaUserdashboardData->flattenData($this->request->data);
            foreach ($dataToSave as $key => $data) {
                $dataToSave[$key]['userdashboard_id'] = $userdashboardId;
            }

            //delete old records first
            $this->GrafanaUserdashboardData->deleteAll([
                'userdashboard_id' => $userdashboardId
            ], false);

            if ($this->GrafanaUserdashboardData->saveAll($dataToSave)) {
                if ($this->isAngularJsRequest()) {
                    $this->setFlash(__('Grafana Userdashboard Data successfully saved'));
                }
                if ($this->request->ext === 'json') {
                    $this->serializeId();
                    return;
                }
            } else {
                $this->serializeErrorMessage();
                return;
            }
        }
    }

    public function view() {

    }

    public function delete() {

    }

    /**
     * Loads the Services Rules of a given Host and Service by host and service UUID.
     *
     * @param string $host_uuid
     * @param string $service_uuid
     */
    public function loadServiceruleFromService() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $perfdataStructure = [];
        if (!empty($this->request->query['hostUuid']) && !empty($this->request->query['serviceUuid'])) {
            $service_uuid = $this->request->query['serviceUuid'];
            $host_uuid = $this->request->query['hostUuid'];

            $userContainerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
            if ($this->Host->hostsByContainerId($userContainerIds, 'first', ['Host.uuid' => $host_uuid])) {
                $perfdataStructure = $this->Rrd->getPerfDataStructureByHostAndServiceUuid($host_uuid, $service_uuid);
            }
        }

        $this->set('sizeof', sizeof($perfdataStructure));
        $this->set('perfdataStructure', $perfdataStructure);
        $this->set('_serialize', ['perfdataStructure', 'sizeof']);
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























    /**
     * do we need this ?
     */
    public function grafanaWidget() {
        $this->layout = 'blank';

        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $this->loadModel('Widget');
        $this->loadModel('Host');

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
                    'Widget.host_id'
                ]
            ]);

            //Check host permissions
            $host = $this->Host->find('first', [
                'recursive'  => -1,
                'contain'    => [
                    'Container'
                ],
                'fields'     => [
                    'Host.id',
                    'Host.uuid',
                    'Host.name'
                ],
                'conditions' => [
                    'Host.id'       => $widget['Widget']['host_id'],
                    'Host.disabled' => 0
                ]
            ]);

            $hostId = null;
            $iframeUrl = '';
            if (!empty($host)) {
                $hostId = (int)$widget['Widget']['host_id'];
                if ($this->hasRootPrivileges === false) {
                    if (!$this->allowedByContainerId(Hash::extract($host, 'Container.{n}.HostsToContainer.container_id'))) {
                        $hostId = null;
                    }
                }


                $grafanaConfiguration = $this->GrafanaConfiguration->find('first');
                if (!empty($grafanaConfiguration) && $this->GrafanaDashboard->existsForUuid($host['Host']['uuid'])) {
                    $GrafanaConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);
                    $GrafanaConfiguration->setHostUuid($host['Host']['uuid']);
                    $iframeUrl = $GrafanaConfiguration->getIframeUrl();
                } else {
                    $hostId = null;
                    $iframeUrl = '';
                }
            }


            $this->set('host_id', $hostId);
            $this->set('iframe_url', $iframeUrl);
            $this->set('_serialize', ['host_id', 'iframe_url']);
            return;
        }


        if ($this->request->is('post')) {
            $hostId = (int)$this->request->data('host_id');
            if ($hostId === 0) {
                $hostId = null;
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

            $widget['Widget']['host_id'] = $hostId;

            if ($this->Widget->save($widget)) {
                $this->set('host_id', $hostId);
                $this->set('_serialize', ['host_id']);
                return;
            }

            $this->serializeErrorMessageFromModel('Widget');
            return;
        }
        throw new MethodNotAllowedException();
    }

    public function getGrafanaDashboards() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $grafanaDashboards = [];
        $rawGrafanaDashboards = $this->GrafanaDashboard->find('all', [
            'fields'     => [
                'GrafanaDashboard.id',
                'GrafanaDashboard.host_id',
                'GrafanaDashboard.host_uuid',
                'Host.name'
            ],
            'joins'      => [
                [
                    'table'      => 'hosts',
                    'alias'      => 'Host',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'Host.id = GrafanaDashboard.host_id',
                    ],
                ],
                [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'INNER',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ]
            ],
            'conditions' => [
                'HostsToContainers.container_id' => $this->MY_RIGHTS
            ],
            'group'      => [
                'Host.id',
            ],
            'order'      => [
                'Host.name' => 'ASC'
            ]
        ]);

        foreach ($rawGrafanaDashboards as $rawGrafanaDashboard) {
            $grafanaDashboards[] = [
                'GrafanaDashboard' => [
                    'id'        => (int)$rawGrafanaDashboard['GrafanaDashboard']['id'],
                    'host_id'   => (int)$rawGrafanaDashboard['GrafanaDashboard']['host_id'],
                    'host_uuid' => $rawGrafanaDashboard['GrafanaDashboard']['host_uuid']
                ],
                'Host'             => [
                    'name' => $rawGrafanaDashboard['Host']['name']
                ]
            ];
        }

        $this->set('grafana_dashboards', $grafanaDashboards);
        $this->set('_serialize', ['grafana_dashboards']);

    }
}
