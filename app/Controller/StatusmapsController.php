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

/**
 * Class StatusmapsController
 * @property HoststatusFields HoststatusFields
 * @property StatusMapsHelper StatusMaps
 */
class StatusmapsController extends AppController {

    public $layout = 'angularjs';

    public $uses = [
        'Host',
        'Container',
        MONITORING_HOSTSTATUS
    ];

    public $components = ['StatusMap'];

    public function index() {
        $nodes = [];
        $edges = [];
        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS, $this->hasRootPrivileges);
        $allHosts = $this->Host->find('all', [
            'recursive' => -1,
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
                'HostsToContainers.container_id' => $containerIds,
            ],
            'contain' => [
                'Parenthost' => [
                    'Container' => [
                        'fields' => [
                            'Container.id'
                        ]
                    ],
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
                'Host.disabled'
            ]
        ]);
        $allHosts = Hash::combine($allHosts, '{n}.Host.id', '{n}');
        $nodes[0] = [
            'id' => 0,
            'label' => $this->systemname,
            'uuid' => null,
            'currentState' => 0,
            'isHardState' => true,
            'group' => 'root'
        ];
        foreach($allHosts as $host){
            $HoststatusFields = new HoststatusFields($this->DbBackend);
            $HoststatusFields
                ->currentState()
                ->isHardstate()
                ->scheduledDowntimeDepth()
                ->problemHasBeenAcknowledged()
               ;
            $hoststatus = $this->Hoststatus->byUuid($host['Host']['uuid'], $HoststatusFields);
            if(empty($hoststatus)){
                $hoststatus['Hoststatus'] = [];
            }
            $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus(($hoststatus['Hoststatus']));

            $nodes[] = [
                'id'            => (int)$host['Host']['id'],
                'label'         => $host['Host']['name'].' ('.$host['Host']['id'].') ' ,
                'uuid'          => $host['Host']['uuid'],
                'group'         => $this->StatusMap->getNodeGroupName($host['Host']['disabled'], $Hoststatus)
            ];
            $edges[] = [
                'from'  => 0, // --> root instance , systemname
                'to'    =>  (int)$host['Host']['id'],
            ];
            foreach($host['Parenthost'] as $parentHost){
                $edges[] = [
                    'from'  => (int)$host['Host']['id'],
                    'to'    => (int)$parentHost['id'],
                    'color' => [
                        'inherit' => 'to',
                    ],
                    'arrows' => 'to'
                ];
            }
        }
        debug(json_encode($nodes));
        debug(json_encode($edges));

        debug($edges);

    }
}

