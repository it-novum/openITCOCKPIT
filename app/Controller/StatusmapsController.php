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

class StatusmapsController extends AppController
{
    public $layout = 'Admin.default';

    public $uses = ['Parenthost', MONITORING_OBJECTS, MONITORING_HOSTSTATUS, MONITORING_SERVICESTATUS];

    public $helpers = ['StatusMaps'];

    public function index()
    {

    }

    private function getAllHosts()
    {
        $allParenthosts = $this->Parenthost->find('all', [
            'fields'     => [
                'Parenthost.id',
                'Parenthost.uuid',
                'Parenthost.name',
                'Parenthost.address',
            ],
            'contain'    => [
                'Host' => [
                    'fields'     => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name',
                        'Host.address',
                    ],
                    'conditions' => [
                        'Host.disabled' => 0,
                    ],
                ],
            ],
            'conditions' => [
                'Parenthost.disabled' => 0,
            ],
        ]);

        return ($allParenthosts);
    }

    public function getHostsAndConnections()
    {
        if ($this->request->ext != 'json') {
            throw new MethodNotAllowedException('Only .json allowed');
        }
        $masterparent = [
            'Parenthost' => [
                'id'      => 0,
                'uuid'    => '123456',
                'name'    => $this->systemname,
                'address' => '127.0.0.1',
            ],
            'Host'       => [],
        ];

        $allParenthosts = $this->getAllHosts();

        $allParenthosts = array_reverse($allParenthosts);
        $allParenthosts[] = $masterparent;
        $allParenthosts = array_reverse($allParenthosts);
        $allChildHostIds = Hash::extract($allParenthosts, "{n}.Host.{n}.id");

        $mystatusmap = [
            'nodes' => [],
            'links' => [],
        ];

        foreach ($allParenthosts as $key => $parenthost) {
            $status = 2;
            if ($currentState = $this->clickHostStatus($parenthost['Parenthost']['uuid'], 1)) {
                $status = $currentState[0]['Hoststatus']['current_state'];
            }
            //$status = $this->clickHostStatus($parenthost['Parenthost']['uuid'], 1);
            $mystatusmap['nodes'][$key] = [
                'id'            => $parenthost['Parenthost']['id'],
                'label'         => $parenthost['Parenthost']['name'],
                'ip'            => $parenthost['Parenthost']['address'],
                'uuid'          => $parenthost['Parenthost']['uuid'],
                'size'          => ($key < 3) ? 5 : 3,
                'current_state' => ($key == 0) ? 0 : $status,
            ];
            if ($key > 0 && !in_array($parenthost['Parenthost']['id'], $allChildHostIds)) {
                $mystatusmap['links'][] = [
                    'id'     => uniqid(),
                    'source' => 0,
                    'target' => $parenthost['Parenthost']['id'],
                    'value'  => 1,
                ];
            }

            foreach ($parenthost['Host'] as $childHost) {
                $mystatusmap['links'][] = [
                    'id'     => uniqid(),
                    'source' => $childHost['HostsToParenthost']['parenthost_id'],
                    'target' => $childHost['HostsToParenthost']['host_id'],
                    'value'  => 1,
                ];
            }
        }
        $this->set('json', $mystatusmap);
        $this->autoRender = false;

        header('Content-Type: application/json');
        $this->render('getHostsAndConnections');
    }

    public function clickHostStatus($uuid = null, $return = null)
    {
        $hoststatus = $this->Objects->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'name1'         => $uuid,
                'objecttype_id' => 1,
            ],
            'fields'     => [
                'Objects.*',
                'Hoststatus.*',
                'Host.name',
                'Host.description',
                'Host.address',
            ],
            'joins'      => [
                [
                    'table'      => 'hosts',
                    'alias'      => 'Host',
                    'conditions' => [
                        'Objects.name1 = Host.uuid',
                    ],
                ],
                [
                    'table'      => 'nagios_hoststatus',
                    'type'       => 'LEFT OUTER',
                    'alias'      => 'Hoststatus',
                    'conditions' => 'Objects.object_id = Hoststatus.host_object_id',
                ],
            ],
        ]);
        if ($return) {
            return ($hoststatus);
        }
        //$this->set('hoststatus', $hoststatus);
        $this->set('_serialize', ['hoststatus']);
        //debug($hoststatus);
        $servicestatus = $this->Objects->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'name1'         => $uuid,
                'objecttype_id' => 2,
            ],
            'fields'     => [
                'Objects.*',
                'Servicestatus.*',
                'Service.name',
                'Service.description',
                'Servicetemplate.name',
                'Servicetemplate.description',
            ],
            'joins'      => [
                [
                    'table'      => 'services',
                    'alias'      => 'Service',
                    'conditions' => [
                        'Objects.name2 = Service.uuid',
                    ],
                ],
                [
                    'table'      => 'servicetemplates',
                    'type'       => 'INNER',
                    'alias'      => 'Servicetemplate',
                    'conditions' => [
                        'Servicetemplate.id = Service.servicetemplate_id',
                    ],
                ],
                [
                    'table'      => 'nagios_servicestatus',
                    'type'       => 'LEFT OUTER',
                    'alias'      => 'Servicestatus',
                    'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                ],
            ],
            'order'      => [
                'Servicestatus.current_state DESC',
            ],
        ]);
        //debug($servicestatus);
        $this->set(compact(['uuid', 'hoststatus', 'servicestatus']));
    }

    public function view($id = null)
    {
        $allParenthosts = $this->getAllHosts();

        $hostUuids = Hash::extract($allParenthosts, '{n}.Parenthost.uuid');
        //debug(count($hostUuids));
        $serviceUuids = Hash::extract($allParenthosts, '{n}.Service.uuid');
        $hostgroupUuids = Hash::extract($allParenthosts, '{n}.Hostgroup.uuid');
        $servicegroupUuids = Hash::extract($allParenthosts, '{n}.Servicegroup.uuid');


        $this->__unbindAssociations('Objects');

        //just the Hosts
        if (count($hostUuids) > 0) {
            $hoststatus = $this->Objects->find('all', [
                'conditions' => [
                    'name1'         => $hostUuids,
                    'objecttype_id' => 1,
                ],
                'fields'     => [
                    'Objects.*',
                    'Hoststatus.*',
                ],
                'joins'      => [
                    [
                        'table'      => 'nagios_hoststatus',
                        'type'       => 'LEFT OUTER',
                        'alias'      => 'Hoststatus',
                        'conditions' => 'Objects.object_id = Hoststatus.host_object_id',
                    ],
                ],
            ]);

            $currentHostUuids = Hash::extract($hoststatus, '{n}.Objects.name1');

            foreach ($currentHostUuids as $key => $currentHostUuid) {
                $hostServiceStatus = $this->Objects->find('all', [
                    'recursive'  => -1,
                    'conditions' => [
                        'name1'         => $currentHostUuid,
                        'objecttype_id' => 2,
                    ],
                    'fields'     => [
                        'Objects.*',
                        'Servicetemplate.name',
                        'Servicetemplate.description',
                        'Servicestatus.*',
                        'Service.name',
                        'Service.description',
                    ],
                    'joins'      => [
                        [
                            'table'      => 'services',
                            'alias'      => 'Service',
                            'conditions' => [
                                'Objects.name2 = Service.uuid',
                            ],
                        ],
                        [
                            'table'      => 'servicetemplates',
                            'type'       => 'INNER',
                            'alias'      => 'Servicetemplate',
                            'conditions' => [
                                'Servicetemplate.id = Service.servicetemplate_id',
                            ],
                        ],
                        [
                            'table'      => 'nagios_servicestatus',
                            'type'       => 'LEFT OUTER',
                            'alias'      => 'Servicestatus',
                            'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                        ],
                    ],
                ]);
                $hoststatus[$key]['Hoststatus']['Servicestatus'] = $hostServiceStatus;
            }
            //debug($hoststatus);
        }

        //just the Services
        if (count($serviceUuids) > 0) {
            $this->loadModel('Service');
            $servicestatus = $this->Objects->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'name2'         => $serviceUuids,
                    'objecttype_id' => 2,
                ],
                'fields'     => [
                    'Objects.*',
                    'Servicetemplate.name',
                    'Servicetemplate.description',
                    'Servicestatus.*',
                    'Service.name',
                    'Service.description',
                ],
                'joins'      => [
                    [
                        'table'      => 'services',
                        'alias'      => 'Service',
                        'conditions' => [
                            'Objects.name2 = Service.uuid',
                        ],
                    ],
                    [
                        'table'      => 'servicetemplates',
                        'type'       => 'INNER',
                        'alias'      => 'Servicetemplate',
                        'conditions' => [
                            'Servicetemplate.id = Service.servicetemplate_id',
                        ],
                    ],
                    [
                        'table'      => 'nagios_servicestatus',
                        'type'       => 'LEFT OUTER',
                        'alias'      => 'Servicestatus',
                        'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                    ],
                ],
            ]);
        }

        //insert the Host UUID into the servicegadgets (eg. for RRDs)
        /*foreach ($serviceGadgetUuids as $key => $serviceGadgetUuid) {
            $map_gadgets[$key]['Service']['host_uuid'] = $this->hostUuidFromServiceUuid($serviceGadgetUuid)[0];
        }*/

        //$backgroundThumbs = $this->Background->findBackgrounds();
        //$iconSets = $this->Background->findIconsets();
        //$icons = $this->Background->findIcons();
        if (!empty($map_lines)) {
            $this->Frontend->setJson('map_lines', Hash::Extract($map_lines, '{n}.Mapline'));
        }

        if (!empty($map_gadgets)) {
            $this->Frontend->setJson('map_gadgets', Hash::Extract($map_gadgets, '{n}.Mapgadget'));
        }
        $this->set(compact([
            'map',
            'map_items',
            'mapstatus',
            'map_lines',
            'map_gadgets',
            'map_texts',
            'backgroundThumbs',
            'iconSets',
            'hoststatus',
            'servicestatus',
            'hostgroup',
            'servicegroup',
            'isFullscreen',
            'icons',
        ]));
    }

}
