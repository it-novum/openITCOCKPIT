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

use itnovum\openITCOCKPIT\Core\ServicechecksConditions;
use itnovum\openITCOCKPIT\Core\ServicechecksControllerRequest;
use itnovum\openITCOCKPIT\Core\ValueObjects\ServiceStates;

class ServicechecksController extends AppController {
    /*
     * Attention! In this case we load an external Model from the monitoring plugin! The Controller
     * use this external model to fetch the required data out of the database
     */
    public $uses = [
        MONITORING_SERVICECHECK,
        MONITORING_SERVICESTATUS,
        'Host',
        'Service',
        'Documentation'
    ];


    public $components = ['Paginator', 'ListFilter.ListFilter', 'RequestHandler'];
    public $helpers = ['ListFilter.ListFilter', 'Status', 'Monitoring'];
    public $layout = 'Admin.default';

    public $listFilters = [
        'index' => [
            'fields' => [
                'Servicecheck.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function index($id = null){
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        //Check if user is permitted to see this object
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
        if (!$this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
            $this->render403();
            return;
        }
        $allowEdit = false;
        if ($this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
            $allowEdit = true;
        }

        //Process request and set request settings back to front end
        $ServicechecksControllerRequest = new ServicechecksControllerRequest(
            $this->request,
            new ServiceStates(),
            $this->userLimit
        );

        //Process conditions
        $Conditions = new ServicechecksConditions();
        $Conditions->setLimit($ServicechecksControllerRequest->getLimit());
        $Conditions->setFrom($ServicechecksControllerRequest->getFrom());
        $Conditions->setTo($ServicechecksControllerRequest->getTo());
        $Conditions->setOrder($ServicechecksControllerRequest->getOrder());
        $Conditions->setStates($ServicechecksControllerRequest->getServiceStates());
        $Conditions->setServiceUuid($service['Service']['uuid']);

        //Query host notification records
        $query = $this->Servicecheck->getQuery($Conditions, $this->Paginator->settings['conditions']);
        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
        $all_servicechecks = $this->Paginator->paginate(
            $this->Servicecheck->alias,
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
        $this->set(compact(['service', 'all_servicechecks', 'servicestatus', 'docuExists', 'allowEdit']));
        $this->set('ServicecheckListsettings', $ServicechecksControllerRequest->getRequestSettingsForListSettings());
    }
}
