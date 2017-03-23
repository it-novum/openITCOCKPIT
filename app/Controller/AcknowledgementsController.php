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

class AcknowledgementsController extends AppController
{
    /*
     * Attention! In this case we load an external Model from the monitoring plugin! The Controller
     * use this external model to fetch the required data out of the database
     */
    public $uses = [MONITORING_ACKNOWLEDGED, MONITORING_SERVICESTATUS, 'Host', 'Service', MONITORING_HOSTSTATUS, 'Documentation'];


    public $components = ['Paginator', 'ListFilter.ListFilter', 'RequestHandler'];
    public $helpers = ['ListFilter.ListFilter', 'Status', 'Monitoring'];
    public $layout = 'Admin.default';

    public $listFilters = [
        'service' => [
            'fields' => [
                'Acknowledged.comment_data' => ['label' => 'Comment', 'searchType' => 'wildcard'],
                'Acknowledged.author_name'  => ['label' => 'Author', 'searchType' => 'wildcard'],
            ],
        ],
        'host'    => [
            'fields' => [
                'Acknowledged.comment_data' => ['label' => 'Comment', 'searchType' => 'wildcard'],
                'Acknowledged.author_name'  => ['label' => 'Author', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function service($id = null)
    {
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        /*
        $service = $this->Service->find('first', [
            'fields' => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.servicetemplate_id',
                'Service.service_url'
            ],
            'conditions' => [
                'Service.id' => $id
            ],
            'contain' => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                        'Servicetemplate.name'
                    ]
                ],
                'Host' => [
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name',
                        'Host.address'
                    ]
                ]
            ]
        ]);*/

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

        if (!$this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
            $this->render403();

            return;
        }

        $allowEdit = false;
        if ($this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
            $allowEdit = true;
        }

        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], [
            'fields' => [
                'Objects.name2',
                'Servicestatus.current_state',
            ],
        ]);

        $requestSettings = $this->Acknowledged->listSettingsService($this->request, $service['Service']['uuid']);

        if (isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);
        } else {
            $this->Paginator->settings['conditions'] = $requestSettings['conditions'];
        }


        $this->Paginator->settings['order'] = $requestSettings['paginator']['order'];
        $this->Paginator->settings['limit'] = $requestSettings['paginator']['limit'];

        $all_acknowledgements = $this->Paginator->paginate();

        $this->set('AcknowledgementListsettings', $requestSettings['Listsettings']);
        $this->set(compact(['service', 'all_acknowledgements', 'servicestatus', 'allowEdit']));


        if (isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null) {
            $this->set('isFilter', true);
        } else {
            $this->set('isFilter', false);
        }
    }

    public function host($id = null)
    {
        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $host = $this->Host->find('first', [
            'fields'     => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.address',
                'Host.host_url',
            ],
            'conditions' => [
                'Host.id' => $id,
            ],
            'contain'    => [
                'Container',
            ],
        ]);

        if (!$this->allowedByContainerId(Hash::extract($host, 'Container.{n}.id'))) {
            $this->render403();

            return;
        }

        $hoststatus = $this->Hoststatus->byUuid($host['Host']['uuid'], [
            'fields' => [
                'Objects.name1',
                'Hoststatus.current_state',
            ],
        ]);

        $hostDocuExists = $this->Documentation->existsForHost($host['Host']['uuid']);

        $requestSettings = $this->Acknowledged->listSettingsHost($this->request, $host['Host']['uuid']);

        if (isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);
        } else {
            $this->Paginator->settings['conditions'] = $requestSettings['conditions'];
        }


        $this->Paginator->settings['order'] = $requestSettings['paginator']['order'];
        $this->Paginator->settings['limit'] = $requestSettings['paginator']['limit'];

        $all_acknowledgements = $this->Paginator->paginate();

        $this->set('AcknowledgementListsettings', $requestSettings['Listsettings']);
        $this->set(compact(['host', 'all_acknowledgements', 'hoststatus', 'hostDocuExists']));


        if (isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null) {
            $this->set('isFilter', true);
        } else {
            $this->set('isFilter', false);
        }
    }
}
