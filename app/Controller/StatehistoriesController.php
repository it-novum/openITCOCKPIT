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

use itnovum\openITCOCKPIT\Core\StatehistoryControllerRequest;
use itnovum\openITCOCKPIT\Core\StatehistoryHostConditions;
use itnovum\openITCOCKPIT\Core\StatehistoryServiceConditions;
use itnovum\openITCOCKPIT\Core\StatehistoryServiceControllerRequest;
use itnovum\openITCOCKPIT\Core\ValueObjects\HostStates;
use itnovum\openITCOCKPIT\Core\ValueObjects\ServiceStates;
use itnovum\openITCOCKPIT\Core\ValueObjects\StateTypes;

class StatehistoriesController extends AppController {
    /*
     * Attention! In this case we load an external Model from the monitoring plugin! The Controller
     * use this external model to fetch the required data out of the database
     */
    public $uses = [
        MONITORING_STATEHISTORY_HOST,
        MONITORING_STATEHISTORY_SERVICE,
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
                'StatehistoryService.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
            ],
        ],
        'host' => [
            'fields' => [
                'StatehistoryHost.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function service($id = null){
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('invalid service'));
        }

        //Process request and set request settings back to front end
        $ServiceStates = new ServiceStates();
        $StateTypes = new StateTypes();
        $StatehistoryRequest = new StatehistoryServiceControllerRequest(
            $this->request,
            $ServiceStates,
            $StateTypes,
            $this->userLimit
        );

        $service = $this->Service->find('first', [
            'recursive' => -1,
            'contain' => [
                'Host' => [
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


        //Process conditions
        $Conditions = new StatehistoryServiceConditions();
        $Conditions->setLimit($StatehistoryRequest->getLimit());
        $Conditions->setOrder($StatehistoryRequest->getOrder());
        $Conditions->setStates($StatehistoryRequest->getServiceStates());
        $Conditions->setStateTypes($StatehistoryRequest->getStateTypes());
        $Conditions->setFrom($StatehistoryRequest->getFrom());
        $Conditions->setTo($StatehistoryRequest->getTo());
        $Conditions->setServiceUuid($service['Service']['uuid']);

        //Query state history records
        $query = $this->StatehistoryService->getQuery($Conditions, $this->Paginator->settings['conditions']);
        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
        $all_statehistories = $this->Paginator->paginate(null,[], [key($this->Paginator->settings['order'])]);

        //Get meta data and push to front end
        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], [
            'fields' => [
                'Servicestatus.current_state',
                'Servicestatus.is_flapping'
            ],
        ]);
        $docuExists = $this->Documentation->existsForUuid($service['Service']['uuid']);
        $this->set(compact(['service', 'all_statehistories', 'servicestatus', 'docuExists', 'allowEdit']));
        $this->set('StatehistoryListsettings', $StatehistoryRequest->getRequestSettingsForListSettings());
    }

    public function host($id = null){
        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('invalid host'));
        }

        //Process request and set request settings back to front end
        $HostStates = new HostStates();
        $StateTypes = new StateTypes();
        $StatehistoryRequest = new StatehistoryControllerRequest(
            $this->request,
            $HostStates,
            $StateTypes,
            $this->userLimit
        );

        $host = $this->Host->find('first', [
            'fields' => [
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
            'contain' => [
                'Container',
            ],
        ]);

        //Check if user is permitted to see this object
        $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $host['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        //Process conditions
        $Conditions = new StatehistoryHostConditions();
        $Conditions->setLimit($StatehistoryRequest->getLimit());
        $Conditions->setOrder($StatehistoryRequest->getOrder());
        $Conditions->setStates($StatehistoryRequest->getHostStates());
        $Conditions->setStateTypes($StatehistoryRequest->getStateTypes());
        $Conditions->setFrom($StatehistoryRequest->getFrom());
        $Conditions->setTo($StatehistoryRequest->getTo());
        $Conditions->setHostUuid($host['Host']['uuid']);

        //Query state history records
        $query = $this->StatehistoryHost->getQuery($Conditions, $this->Paginator->settings['conditions']);
        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
        $all_statehistories = $this->Paginator->paginate(null,[], [key($this->Paginator->settings['order'])]);

        //Get meta data and push to front end
        $hoststatus = $this->Hoststatus->byUuid($host['Host']['uuid'], [
            'fields' => [
                'Hoststatus.current_state',
                'Hoststatus.is_flapping'
            ],
        ]);
        $docuExists = $this->Documentation->existsForUuid($host['Host']['uuid']);
        $this->set(compact(['host', 'all_statehistories', 'hoststatus', 'docuExists']));
        $this->set('StatehistoryListsettings', $StatehistoryRequest->getRequestSettingsForListSettings());
    }
}
