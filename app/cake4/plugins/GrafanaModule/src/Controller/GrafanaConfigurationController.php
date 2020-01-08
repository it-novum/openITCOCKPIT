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

use App\Model\Table\HostgroupsTable;
use Cake\ORM\TableRegistry;
use GuzzleHttp\Client;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Grafana\GrafanaApiConfiguration;

/**
 * Class GrafanaConfigurationController
 * @package GrafanaModule\Controller
 */
class GrafanaConfigurationController extends AppController {

    public function index() {
        $this->layout = 'blank';
        if (!$this->isAngularJsRequest()) {
            //Only ship html template
            return;
        }

        //$this->request->data = Hash::merge($grafanaConfiguration, $this->request->data);

        //Save POST||PUT Request
        if ($this->request->is('post') || $this->request->is('put')) {
            $_hostgroups = (is_array($this->request->data('GrafanaConfiguration.Hostgroup'))) ? $this->request->data('GrafanaConfiguration.Hostgroup') : [];
            $_hostgroups_excluded = (is_array($this->request->data('GrafanaConfiguration.Hostgroup_excluded'))) ? $this->request->data('GrafanaConfiguration.Hostgroup_excluded') : [];

            $this->GrafanaConfiguration->set($this->request->data);
            if ($this->GrafanaConfiguration->validates()) {
                $this->request->data['GrafanaConfiguration']['id'] = 1;
                $this->request->data['GrafanaConfigurationHostgroupMembership'] = $this->GrafanaConfiguration->parseHostgroupMembershipData(
                    $_hostgroups,
                    $_hostgroups_excluded
                );

                /* Delete old hostgroup associations */
                $this->GrafanaConfigurationHostgroupMembership->deleteAll(true);

                if ($this->GrafanaConfiguration->saveAll($this->request->data)) {
                    $this->serializeId();
                    return;
                }
            } else {
                $this->serializeErrorMessage();
                return;
            }
        }

        //Ship data for GET requests
        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        $hostgroups = $HostgroupsTable->getHostgroupsAsList([], $this->MY_RIGHTS);

        $customFieldsToRefill = [
            'GrafanaConfiguration' => [
                'use_https',
                'ignore_ssl_certificate',
                'use_proxy'
            ]
        ];
        $this->CustomValidationErrors->checkForRefill($customFieldsToRefill);

        $grafanaConfiguration = $this->GrafanaConfiguration->find('first', [
            'recursive' => -1,
            'contain'   => [
                'GrafanaConfigurationHostgroupMembership'
            ]
        ]);

        if (empty($grafanaConfiguration)) {
            //Default GrafanaConfiguration
            $grafanaConfiguration = [
                'GrafanaConfiguration' => [
                    'id'                     => 1, //its 1 every time
                    'api_url'                => '',
                    'api_key'                => '',
                    'graphite_prefix'        => '',
                    'use_https'              => '1',
                    'use_proxy'              => '0',
                    'ignore_ssl_certificate' => '0',
                    'dashboard_style'        => 'light',
                    'Hostgroup'              => [],
                    'Hostgroup_excluded'     => []
                ]
            ];
        }

        if (!empty($grafanaConfiguration['GrafanaConfigurationHostgroupMembership'])) {
            $grafanaConfiguration['GrafanaConfiguration']['Hostgroup'] = Hash::combine($grafanaConfiguration['GrafanaConfigurationHostgroupMembership'], '{n}[excluded=0].hostgroup_id', '{n}[excluded=0].hostgroup_id');
            $grafanaConfiguration['GrafanaConfiguration']['Hostgroup_excluded'] = Hash::combine($grafanaConfiguration['GrafanaConfigurationHostgroupMembership'], '{n}[excluded=1].hostgroup_id', '{n}[excluded=1].hostgroup_id');
        }

        $this->set('grafanaConfiguration', $grafanaConfiguration);
        $this->set('_serialize', ['grafanaConfiguration']);
    }

    public function loadHostgroups() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        $hostgroups = $HostgroupsTable->getHostgroupsAsList([], $this->MY_RIGHTS);

        $hostgroups = Api::makeItJavaScriptAble($hostgroups);

        $this->set('hostgroups', $hostgroups);
        $this->set('_serialize', ['hostgroups']);
    }

    public function testGrafanaConnection() {
        //$this->autoRender = false;
        //$this->allowOnlyAjaxRequests();
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $config = $this->request->data;
            /*$this->set('config', $config);
            $this->set('_serialize', ['config']); */
            //$config = json_decode($config);

            $GrafanaApiConfiguration = GrafanaApiConfiguration::fromArray($config);

            /** @var $Proxy App\Model\Table\ProxiesTable */
            $Proxy = TableRegistry::getTableLocator()->get('Proxies');


            $client = $this->GrafanaConfiguration->testConnection($GrafanaApiConfiguration, $Proxy->getSettings());
            if ($client instanceof Client) {
                $status = ['status' => true];
            } else {

                $client = (json_decode($client)) ? json_decode($client) : ['message' => (string)$client];

                //Resolve: ITC-2169 RVID: 5-445b21 - Medium - Server-Side Request Forgery
                $message = __('Error while connecting to Grafana server.');
                $message = __('For detailed information, please uncomment line %s in %s. Detailed output is disabled due to security reasons.', (__LINE__ + 1), __FILE__);
                //$message = $client;

                if (is_object($client) && property_exists($client, 'message')) {
                    if ($client->message === 'Invalid API key') {
                        $message = 'Invalid API key';
                    }
                }

                if (is_array($client) && isset($client['message'])) {
                    if (strpos($client['message'], 'cURL error') === 0) {
                        $message = $client['message'];
                    }
                }


                $status = [
                    'status' => false,
                    'msg'    => [
                        'message' => $message
                    ]
                ];
            }


            $this->set('status', $status);
            $this->set('_serialize', ['status']);
        }
    }

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
