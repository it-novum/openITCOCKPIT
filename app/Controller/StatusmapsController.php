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


use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\ModuleManager;
use itnovum\openITCOCKPIT\Filter\HostFilter;

/**
 * Class StatusmapsController
 * @property HoststatusFields $HoststatusFields
 * @property StatusMapsHelper $StatusMaps
 * @property Hoststatus $Hoststatus
 */
class StatusmapsController extends AppController {

    public $layout = 'angularjs';

    public $uses = [
        'Host',
        'Container',
        'Parenthost',
        MONITORING_HOSTSTATUS
    ];

    public $components = ['StatusMap'];

    public function index() {
        if (!$this->isApiRequest()) {
            $SatelliteNames = [];
            $masterInstanceName = $this->Systemsetting->getMasterInstanceName();
            $ModuleManager = new ModuleManager('DistributeModule');
            if ($ModuleManager->moduleExists()) {
                $SatelliteModel = $ModuleManager->loadModel('Satellite');
                $satellites = $SatelliteModel->find('list', [
                    'recursive' => -1,
                    'fields' => [
                        'Satellite.id',
                        'Satellite.name'
                    ],
                    'Satellite.container_id' => $this->MY_RIGHTS
                ]);
            }
            $satellites[0] = $masterInstanceName;
            $this->set('satellites', $satellites);
        }

        if (!$this->isAngularJsRequest()) {
            return;
        }
        session_write_close();
        $parentHostWithChildIds = $this->Parenthost->find('all', [
            'recursive' => -1,
            'joins' => [
                [
                    'table' => 'hosts_to_parenthosts',
                    'alias' => 'HostToParenthost',
                    'type' => 'INNER',
                    'conditions' => [
                        'HostToParenthost.parenthost_id = Parenthost.id',
                    ],
                ],
                [
                    'table' => 'hosts',
                    'alias' => 'Host',
                    'type' => 'INNER',
                    'conditions' => [
                        'HostToParenthost.host_id = Host.id',
                    ],
                ]
            ],
            'fields' => [
                'DISTINCT Parenthost.id',
                'Host.id'
            ]
        ]);

        $allHostIds = [];
        foreach ($parentHostWithChildIds as $parentHostWithChildId) {
            if (!in_array($parentHostWithChildId['Parenthost']['id'], $allHostIds, true)) {
                $allHostIds[] = $parentHostWithChildId['Parenthost']['id'];
            }
            if (!in_array($parentHostWithChildId['Host']['id'], $allHostIds, true)) {
                $allHostIds[] = $parentHostWithChildId['Host']['id'];
            }
        }
        $containerIds = [];
        if ($this->hasRootPrivileges === false) {
            //   $containerIds = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        }
        $HostFilter = new HostFilter($this->request);
        $nodes = [];
        $edges = [];

        $query = [
            'recursive' => -1,
            'contain' => [
                'Parenthost' => [
                    'fields' => [
                        'Parenthost.id'
                    ]
                ]
            ],
            'fields' => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.description',
                'Host.address',
                'Host.disabled',
                'Host.satellite_id'
            ],
            'conditions' => $HostFilter->indexFilter()

        ];

        if (!empty($containerIds)) {
            $query['joins'] = [
                [
                    'table' => 'hosts_to_containers',
                    'alias' => 'HostsToContainers',
                    'type' => 'LEFT',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ]
            ];
            $query['conditions']['HostsToContainers.container_id'] = $containerIds;
        }
        if(!empty($allHostIds)){
            $query['conditions']['Host.id'] = $allHostIds;
        }

        $count = $this->Host->find('count', $query);

        $limit = 100;
        $numberOfSelects = ceil($count / $limit);
        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields
            ->currentState()
            ->isHardstate()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();

        for ($i = 0; $i < $numberOfSelects; $i++) {
            $query['limit'] = $limit;
            $query['offset'] = $limit * $i;


            $tmpHostsResult = $this->Host->find('all', $query);

            //$dbo = $this->Host->getDatasource();
            //$logs = $dbo->getLog();
            //$lastLog = end($logs['log']);
            //  debug( $lastLog['query'] );


            $hostUuids = Hash::extract($tmpHostsResult, '{n}.Host.uuid');
            $hoststatus = $this->Hoststatus->byUuid($hostUuids, $HoststatusFields);
            foreach ($tmpHostsResult as $hostChunk) {
                if (!isset($hoststatus[$hostChunk['Host']['uuid']]['Hoststatus'])) {
                    $hoststatus[$hostChunk['Host']['uuid']] = [
                        'Hoststatus' => []
                    ];
                }
                $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus(
                    $hoststatus[$hostChunk['Host']['uuid']]['Hoststatus']
                );

                $nodes[] = [
                    'id' => 'Host_' . $hostChunk['Host']['id'],
                    'label' => $hostChunk['Host']['name'],
                    'title' => $hostChunk['Host']['name'],
                    'uuid' => $hostChunk['Host']['uuid'],
                    'group' => $this->StatusMap->getNodeGroupName($hostChunk['Host']['disabled'], $Hoststatus)
                ];

                foreach ($hostChunk['Parenthost'] as $parentHost) {
                    $edges[] = [
                        'from' => 'Host_' . $hostChunk['Host']['id'],
                        'to' => 'Host_' . $parentHost['id'],
                        'color' => [
                            'inherit' => 'to',
                        ],
                        'arrows' => 'to'
                    ];
                }
            }
        }

        $statusMap = [
            'nodes' => $nodes,
            'edges' => $edges
        ];


        $this->set(compact(['statusMap']));
        $this->set('_serialize', ['statusMap']);
    }
}

