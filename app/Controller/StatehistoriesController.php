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

class StatehistoriesController extends AppController
{
    /*
     * Attention! In this case we load an external Model from the monitoring plugin! The Controller
     * use this external model to fetch the required data out of the database
     */
    public $uses = [
        MONITORING_STATEHISTORY,
        MONITORING_SERVICESTATUS,
        MONITORING_HOSTSTATUS,
        'Host',
        'Service',
        'Documentation'
    ];


    public $components = ['Paginator', 'ListFilter.ListFilter', 'RequestHandler'];
    public $helpers = ['ListFilter.ListFilter', 'Status', 'Monitoring'];
    public $layout = 'Admin.default';

    public $listFilters = [
        'service' => [
            'fields' => [
                'Statehistory.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
            ],
        ],
        'host' => [
            'fields' => [
                'Statehistory.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function service($id = null)
    {
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('invalid service'));
        }

        $service = $this->Service->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'Host'            => [
                    'Container',
                ],
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                        'Servicetemplate.name',
                    ],
                ],
            ],
            'conditions' => [
                'Service.id' => $id,
            ],
        ]);

        $containerIdsToCheck = Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $service['Host']['container_id'];

        //Check if user is permitted to see this object
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();

            return;
        }


        $allowEdit = false;
        if ($this->allowedByContainerId($containerIdsToCheck)) {
            $allowEdit = true;
        }

        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], [
            'fields' => [
                'Objects.name2',
                'Servicestatus.current_state',
            ],
        ]);


        $requestSettings = $this->Statehistory->listSettings($this->request, $service['Service']['uuid'], $service['Host']['uuid']);

        if (isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);
        } else {
            $this->Paginator->settings['conditions'] = $requestSettings['conditions'];
        }

        $this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);

        //SQL Clenup
        if (isset($this->Paginator->settings['conditions']['Statehistory.state'])) {
            $this->Paginator->settings['conditions']['Statehistory.state'] = array_unique($this->Paginator->settings['conditions']['Statehistory.state']);
        }
        if (isset($this->Paginator->settings['conditions']['Statehistory.state_type'])) {
            $this->Paginator->settings['conditions']['Statehistory.state_type'] = array_unique($this->Paginator->settings['conditions']['Statehistory.state_type']);
        }

        $this->Paginator->settings['order'] = $requestSettings['paginator']['order'];
        $this->Paginator->settings['limit'] = $requestSettings['paginator']['limit'];

        $all_statehistories = $this->Paginator->paginate();
        $docuExists = $this->Documentation->existsForUuid($service['Service']['uuid']);

        $this->set(compact(['service', 'all_statehistories', 'servicestatus', 'allowEdit', 'docuExists']));
        $this->set('StatehistoryListsettings', $requestSettings['Listsettings']);
    }

    public function host($id = null)
    {
        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('invalid host'));
        }

        $host = $this->Host->find('first', [
            'fields'     => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.address',
                'Host.host_url',
                'Host.container_id',
                'Host.host_type'
            ],
            'conditions' => [
                'Host.id' => $id,
            ],
            'contain'    => [
                'Container',
            ],
        ]);


        $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $host['Host']['container_id'];

        //Check if user is permitted to see this object
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();

            return;
        }

        $hoststatus = $this->Hoststatus->byUuid($host['Host']['uuid'], [
            'fields' => [
                'Objects.name1',
                'Hoststatus.current_state',
            ],
        ]);


        $requestSettings = $this->Statehistory->listSettingsHost($this->request, $host['Host']['uuid']);

        if (isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);
        } else {
            $this->Paginator->settings['conditions'] = $requestSettings['conditions'];
        }


        $this->Paginator->settings['order'] = $requestSettings['paginator']['order'];
        $this->Paginator->settings['limit'] = $requestSettings['paginator']['limit'];

        $all_statehistories = $this->Paginator->paginate();

        $docuExists = $this->Documentation->existsForUuid($host['Host']['uuid']);

        $this->set(compact(['host', 'all_statehistories', 'hoststatus', 'docuExists']));
        $this->set('StatehistoryListsettings', $requestSettings['Listsettings']);
    }
}
