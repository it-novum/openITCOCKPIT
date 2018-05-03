<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

use itnovum\openITCOCKPIT\Monitoring\QueryHandler;

class CrateHostsController extends CrateModuleAppController {
    public $layout = 'Admin.default';
    public $components = [
        'ListFilter.ListFilter',
        'AdditionalLinks',
        'Flash'
    ];
    public $helpers = [
        'ListFilter.ListFilter',
        'Status',
        'Monitoring',
        'Flash',
    ];

    public $uses = [
        MONITORING_HOSTSTATUS
    ];


    public $listFilters = [
        'index' => [
            'fields' => [
                'Host.name' => ['label' => 'Hostname', 'searchType' => 'wildcard'],
                'Host.address' => ['label' => 'IP-Address', 'searchType' => 'wildcard'],
                'Hoststatus.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
                'Host.keywords' => ['label' => 'Tag', 'searchType' => 'wildcardMulti', 'hidden' => true],

                'Hoststatus.current_state' => ['label' => 'Current state', 'type' => 'checkbox', 'searchType' => 'nix', 'options' =>
                    [
                        '0' => [
                            'name' => 'Hoststatus.up',
                            'value' => 1,
                            'label' => 'Up',
                            'data' => 'Filter.Hoststatus.current_state',
                        ],
                        '1' => [
                            'name' => 'Hoststatus.down',
                            'value' => 1,
                            'label' => 'Down',
                            'data' => 'Filter.Hoststatus.current_state',
                        ],
                        '2' => [
                            'name' => 'Hoststatus.unreachable',
                            'value' => 1,
                            'label' => 'Unreachable',
                            'data' => 'Filter.Hoststatus.current_state',
                        ],
                    ],
                ],
                'Hoststatus.problem_has_been_acknowledged' => ['label' => 'Acknowledged', 'type' => 'checkbox', 'searchType' => 'nix', 'options' =>
                    [
                        '1' => [
                            'name' => 'Acknowledged',
                            'value' => 1,
                            'label' => 'Acknowledged',
                            'data' => 'Filter.Hoststatus.problem_has_been_acknowledged',
                        ],
                    ],
                ],
                'Hoststatus.scheduled_downtime_depth' => ['label' => 'In Downtime', 'type' => 'checkbox', 'searchType' => 'greater', 'options' =>
                    [
                        '0' => [
                            'name' => 'Downtime',
                            'value' => 0,
                            'label' => 'In Downtime',
                            'data' => 'Filter.Hoststatus.scheduled_downtime_depth',
                        ],
                    ],
                ],
            ],
        ],
        'notMonitored' => [
            'fields' => [
                'Host.name' => ['label' => 'Hostname', 'searchType' => 'wildcard'],
                'Host.address' => ['label' => 'IP-Address', 'searchType' => 'wildcard'],
                'Host.tags' => ['label' => 'Tag', 'searchType' => 'wildcard', 'hidden' => true],
            ],
        ],
        'disabled' => [
            'fields' => [
                'Host.name' => ['label' => 'Hostname', 'searchType' => 'wildcard'],
                'Host.address' => ['label' => 'IP-Address', 'searchType' => 'wildcard'],
                'Host.tags' => ['label' => 'Tag', 'searchType' => 'wildcard', 'hidden' => true],
            ],
        ],
    ];

    public function index(){
        $conditions = [];
        if (!isset($this->request->params['named']['BrowserContainerId'])) {
            $conditions = [
                'Host.disabled' => 0,
                'HostsToContainers.container_id' => $this->MY_RIGHTS,
            ];
        }
        $conditions = $this->ListFilter->buildConditions([], $conditions);
        $query = $this->Hoststatus->getHostIndexQuery($conditions, $this->MY_RIGHTS);

        if ($this->isApiRequest()) {
            $all_hosts = $this->Hoststatus->find('all', $query);
        } else {
            //$this->Paginator->settings = $query;
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $all_hosts = $this->Paginator->paginate();
        }
        $this->set(compact(['all_hosts']));

        $this->set('QueryHandler', new QueryHandler($this->Systemsetting->getQueryHandlerPath()));
        $this->set('userRights', $this->MY_RIGHTS);
        $masterInstance = $this->Systemsetting->findAsArraySection('FRONTEND')['FRONTEND']['FRONTEND.MASTER_INSTANCE'];

        // Distributed Monitoring
        $this->set('masterInstance', $masterInstance);
        $SatelliteModel = false;
        if (is_dir(APP . 'Plugin' . DS . 'DistributeModule')) {
            $SatelliteModel = ClassRegistry::init('DistributeModule.Satellite', 'Model');
        }
        $SatelliteNames = [];
        if ($SatelliteModel !== false) {
            $SatelliteNames = $SatelliteModel->find('list');
        }
        $this->set('SatelliteNames', $SatelliteNames);

    }

}

