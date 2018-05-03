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
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\UserTime;

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


    public $components = ['RequestHandler'];
    public $helpers = ['Status', 'Monitoring'];
    public $layout = 'Admin.default';

    public function index($id = null){
        $this->layout="angularjs";

        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        if (!$this->isAngularJsRequest()) {
            //Service for .html requests
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

            //Get meta data and push to front end
            $ServicestatusFields = new ServicestatusFields($this->DbBackend);
            $ServicestatusFields->currentState()->isFlapping();
            $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusFields);
            $docuExists = $this->Documentation->existsForUuid($service['Service']['uuid']);
            $this->set(compact(['service', 'servicestatus', 'docuExists', 'allowEdit']));
            return;
        }


        //Service for .json requests
        $service = $this->Service->find('first', [
            'recursive' => -1,
            'fields' => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.service_type',
                'Service.service_url'
            ],
            'conditions' => [
                'Service.id' => $id,
            ],
        ]);

        $AngularServicechecksControllerRequest = new \itnovum\openITCOCKPIT\Core\AngularJS\Request\ServicechecksControllerRequest($this->request);

        //Process conditions
        $Conditions = new ServicechecksConditions();
        $Conditions->setLimit($this->Paginator->settings['limit']);
        $Conditions->setFrom($AngularServicechecksControllerRequest->getFrom());
        $Conditions->setTo($AngularServicechecksControllerRequest->getTo());
        $Conditions->setOrder($AngularServicechecksControllerRequest->getOrderForPaginator('Servicecheck.start_time', 'desc'));
        $Conditions->setStates($AngularServicechecksControllerRequest->getServiceStates());
        $Conditions->setStateTypes($AngularServicechecksControllerRequest->getServiceStateTypes());
        $Conditions->setServiceUuid($service['Service']['uuid']);

        //Query host notification records
        $query = $this->Servicecheck->getQuery($Conditions, $AngularServicechecksControllerRequest->getIndexFilters());

        $this->Paginator->settings = $query;
        $this->Paginator->settings['page'] = $AngularServicechecksControllerRequest->getPage();

        $servicechecks = $this->Paginator->paginate(
            $this->Servicecheck->alias,
            [],
            [key($this->Paginator->settings['order'])]
        );

        $all_servicechecks = [];
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        foreach ($servicechecks as $servicecheck) {
            $Servicecheck = new itnovum\openITCOCKPIT\Core\Views\Servicecheck($servicecheck['Servicecheck'], $UserTime);
            $all_servicechecks[] = [
                'Servicecheck' => $Servicecheck->toArray()
            ];
        }

        $this->set(compact(['all_servicechecks']));
        $this->set('_serialize', ['all_servicechecks', 'paging']);
    }
}
