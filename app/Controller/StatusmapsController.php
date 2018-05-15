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
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Filter\HostFilter;
use itnovum\openITCOCKPIT\Filter\StatusmapFilter;

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
        'Service',
        'Container',
        'Parenthost',
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS
    ];

    public $components = ['StatusMap'];

    public function index() {
        if (!$this->isApiRequest()) {
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
                    'Satellite.container_id' => $this->MY_RIGHTS,
                    'order' => [
                        'Satellite.name' => 'asc'
                    ]
                ]);
            }
            $satellites[0] = $masterInstanceName;
            $this->set('satellites', $satellites);
        }

        if (!$this->isAngularJsRequest()) {
            return;
        }
        session_write_close();

        $allHostIds = [];
        $hasBrowserRight = $this->hasPermission('browser', 'hosts');
        if ($this->request->query('showAll') === 'false') {

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
                ],
            ]);


            foreach ($parentHostWithChildIds as $parentHostWithChildId) {
                if (!in_array($parentHostWithChildId['Parenthost']['id'], $allHostIds, true)) {
                    $allHostIds[] = $parentHostWithChildId['Parenthost']['id'];
                }
                if (!in_array($parentHostWithChildId['Host']['id'], $allHostIds, true)) {
                    $allHostIds[] = $parentHostWithChildId['Host']['id'];
                }
            }
        }
        $containerIds = [];
        if ($this->hasRootPrivileges === false) {
            $containerIds = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        }
        $StatusmapFilter = new StatusmapFilter($this->request);
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
            'conditions' => $StatusmapFilter->indexFilter()

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
        if (!empty($allHostIds)) {
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
                    'hostId' => $hostChunk['Host']['id'],
                    'label' => $hostChunk['Host']['name'],
                    'title' => $hostChunk['Host']['name'] . ' (' . $hostChunk['Host']['address'] . ')',
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


        $this->set(compact(['statusMap', 'hasBrowserRight']));
        $this->set('_serialize', ['statusMap', 'hasBrowserRight']);
    }

    /**
     * @param int | null $hostId
     * @property HoststatusFields $HoststatusFields
     * @property ServicestatusFields $ServicestatusFields
     *
     */
    public function hostAndServicesSummaryStatus($hostId = null) {
        $this->layout = 'blank';
        if (!$hostId) {
            throw new NotFoundException(__('Invalid request parameters'));
        }
        $serviceUuids = Hash::extract(
            $this->Service->find('all', [
                    'recursive' => -1,
                    'fields' => [
                        'Service.uuid'
                    ],
                    'conditions' => [
                        'Service.host_id' => $hostId
                    ]
                ]
            ),
            '{n}.Service.uuid'
        );
        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->currentState()
            ->problemHasBeenAcknowledged()
            ->activeChecksEnabled()
            ->scheduledDowntimeDepth();
        $servicestatus = $this->Servicestatus->byUuids($serviceUuids, $ServicestatusFields);

        $serviceStateSummary = $this->Service->getServiceStateSummary($servicestatus);

        $this->set(compact(['serviceStateSummary', 'hostId']));
        $this->set('_serialize', ['serviceStateSummary']);
    }
}
