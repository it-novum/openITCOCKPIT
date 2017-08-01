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

use itnovum\openITCOCKPIT\Core\AcknowledgedServiceConditions;
use itnovum\openITCOCKPIT\Core\AcknowledgedServiceControllerRequest;
use itnovum\openITCOCKPIT\Core\ValueObjects\HostStates;
use itnovum\openITCOCKPIT\Core\AcknowledgedHostControllerRequest;
use itnovum\openITCOCKPIT\Core\AcknowledgedHostConditions;
use itnovum\openITCOCKPIT\Core\ValueObjects\ServiceStates;

class AcknowledgementsController extends AppController {
    /*
     * Attention! In this case we load an external Model from the monitoring plugin! The Controller
     * use this external model to fetch the required data out of the database
     */
    public $uses = [
        MONITORING_ACKNOWLEDGED_HOST,
        MONITORING_ACKNOWLEDGED_SERVICE,
        MONITORING_ACKNOWLEDGED,
        MONITORING_SERVICESTATUS,
        'Host',
        'Service',
        MONITORING_HOSTSTATUS,
        'Documentation'
    ];


    public $components = ['Paginator', 'ListFilter.ListFilter', 'RequestHandler'];
    public $helpers = ['ListFilter.ListFilter', 'Status', 'Monitoring'];
    public $layout = 'Admin.default';

    public $listFilters = [
        'service' => [
            'fields' => [
                'AcknowledgedService.comment_data' => ['label' => 'Comment', 'searchType' => 'wildcard'],
                'AcknowledgedService.author_name' => ['label' => 'Author', 'searchType' => 'wildcard'],
            ],
        ],
        'host' => [
            'fields' => [
                'AcknowledgedHost.comment_data' => ['label' => 'Comment', 'searchType' => 'wildcard'],
                'AcknowledgedHost.author_name' => ['label' => 'Author', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function service($id = null){
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        //Process request and set request settings back to front end
        $ServiceStates = new ServiceStates();
        $AcknowledgedServiceControllerRequest = new AcknowledgedServiceControllerRequest(
            $this->request,
            $ServiceStates,
            $this->userLimit
        );

        $service = $this->Service->find('first', [
            'recursive' => -1,
            'fields' => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.service_type',
                'Service.service_url'
            ],
            'contain' => [
                'Host' => [
                    'fields' => [
                        'Host.id',
                        'Host.name',
                        'Host.uuid',
                        'Host.address'
                    ],
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
        //Check if user is permitted to see this object
        if (!$this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
            $this->render403();
            return;
        }

        $allowEdit = false;
        if ($this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
            $allowEdit = true;
        }

        //Process conditions
        $Conditions = new AcknowledgedServiceConditions();
        $Conditions->setLimit($AcknowledgedServiceControllerRequest->getLimit());
        $Conditions->setFrom($AcknowledgedServiceControllerRequest->getFrom());
        $Conditions->setTo($AcknowledgedServiceControllerRequest->getTo());
        $Conditions->setStates($AcknowledgedServiceControllerRequest->getServiceStates());
        $Conditions->setOrder($AcknowledgedServiceControllerRequest->getOrder());
        $Conditions->setServiceUuid($service['Service']['uuid']);

        //Query state history records
        $query = $this->AcknowledgedService->getQuery($Conditions, $this->Paginator->settings['conditions']);
        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
        $all_acknowledgements = $this->Paginator->paginate(
            $this->AcknowledgedService->alias,
            [],
            [key($this->Paginator->settings['order'])]
        );

        $docuExists = $this->Documentation->existsForUuid($service['Service']['uuid']);

        //Get meta data and push to front end
        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], [
            'fields' => [
                'Servicestatus.current_state',
                'Servicestatus.is_flapping'
            ],
        ]);
        $this->set(compact(['service', 'all_acknowledgements', 'servicestatus', 'docuExists', 'allowEdit']));
        $this->set('AcknowledgementListsettings', $AcknowledgedServiceControllerRequest->getRequestSettingsForListSettings());
    }

    public function host($id = null){
        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }


        //Process request and set request settings back to front end
        $HostStates = new HostStates();
        $AcknowledgedHostControllerRequest = new AcknowledgedHostControllerRequest(
            $this->request,
            $HostStates,
            $this->userLimit
        );

        $host = $this->Host->find('first', [
            'fields' => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.address',
                'Host.host_url',
                'Host.host_type',
                'Host.container_id'
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
        $Conditions = new AcknowledgedHostConditions();
        $Conditions->setLimit($AcknowledgedHostControllerRequest->getLimit());
        $Conditions->setFrom($AcknowledgedHostControllerRequest->getFrom());
        $Conditions->setTo($AcknowledgedHostControllerRequest->getTo());
        $Conditions->setStates($AcknowledgedHostControllerRequest->getHostStates());
        $Conditions->setOrder($AcknowledgedHostControllerRequest->getOrder());
        $Conditions->setHostUuid($host['Host']['uuid']);

        //Query state history records
        $query = $this->AcknowledgedHost->getQuery($Conditions, $this->Paginator->settings['conditions']);
        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
        $all_acknowledgements = $this->Paginator->paginate(null, [], [key($this->Paginator->settings['order'])]);

        $docuExists = $this->Documentation->existsForUuid($host['Host']['uuid']);

        //Get meta data and push to front end
        $hoststatus = $this->Hoststatus->byUuid($host['Host']['uuid'], [
            'fields' => [
                'Hoststatus.current_state',
                'Hoststatus.is_flapping'
            ],
        ]);
        $this->set(compact(['host', 'all_acknowledgements', 'hoststatus', 'docuExists']));
        $this->set('AcknowledgementListsettings', $AcknowledgedHostControllerRequest->getRequestSettingsForListSettings());
    }
}
