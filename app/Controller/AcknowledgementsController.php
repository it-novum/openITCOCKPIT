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

use itnovum\openITCOCKPIT\Core\AcknowledgedHostConditions;
use itnovum\openITCOCKPIT\Core\AcknowledgedServiceConditions;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\ScrollIndex;

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
        MONITORING_HOSTSTATUS
    ];

    public $components = ['RequestHandler', 'Bbcode'];
    public $helpers = ['Status', 'Monitoring', 'Bbcode'];
    public $layout = 'Admin.default';

    public function service($id = null) {
        $this->layout = "blank";

        if (!$this->Service->exists($id) && $id !== null) {
            throw new NotFoundException(__('Invalid service'));
        }

        if (!$this->isAngularJsRequest() && $id === null) {
            //Service for .html requests
            return;
        }

        //Service for .json requests
        $service = $this->Service->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.service_type',
                'Service.service_url'
            ],
            'contain'    => [
                'Host'            => [
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


        $AngularAcknowledgementsControllerRequest = new \itnovum\openITCOCKPIT\Core\AngularJS\Request\AcknowledgementsControllerRequest($this->request);

        //Process conditions
        $Conditions = new AcknowledgedServiceConditions();
        $Conditions->setLimit($this->Paginator->settings['limit']);
        $Conditions->setFrom($AngularAcknowledgementsControllerRequest->getFrom());
        $Conditions->setTo($AngularAcknowledgementsControllerRequest->getTo());
        $Conditions->setStates($AngularAcknowledgementsControllerRequest->getServiceStates());
        $Conditions->setOrder($AngularAcknowledgementsControllerRequest->getOrderForPaginator('AcknowledgedService.entry_time', 'desc'));
        $Conditions->setServiceUuid($service['Service']['uuid']);


        $query = $this->AcknowledgedService->getQuery($Conditions, $AngularAcknowledgementsControllerRequest->getServiceFilters());

        $this->Paginator->settings = $query;
        $this->Paginator->settings['page'] = $AngularAcknowledgementsControllerRequest->getPage();

        if ($this->isScrollRequest()) {
            $ScrollIndex = new ScrollIndex($this->Paginator, $this);
            $acknowledgements = $this->AcknowledgedService->find('all', $this->Paginator->settings);
            $ScrollIndex->determineHasNextPage($acknowledgements);
            $ScrollIndex->scroll();
        } else {
            $acknowledgements = $this->Paginator->paginate(
                $this->AcknowledgedService->alias,
                [],
                [key($this->Paginator->settings['order'])]
            );
        }

        $all_acknowledgements = [];
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        foreach ($acknowledgements as $acknowledgement) {
            $Acknowledgement = new itnovum\openITCOCKPIT\Core\Views\AcknowledgementService($acknowledgement['AcknowledgedService'], $UserTime);
            $acknowledgementArray = $Acknowledgement->toArray();
            $acknowledgementArray['comment_data'] = $this->Bbcode->nagiosNl2br($this->Bbcode->asHtml($acknowledgementArray['comment_data'], true));
            $all_acknowledgements[] = [
                'AcknowledgedService' => $acknowledgementArray
            ];
        }

        $this->set(compact(['all_acknowledgements']));
        $toJson = ['all_acknowledgements', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_acknowledgements', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    public function host($id = null) {
        $this->layout = 'blank';

        if (!$this->Host->exists($id) && $id !== null) {
            throw new NotFoundException(__('Invalid host'));
        }

        if (!$this->isAngularJsRequest() && $id === null) {
            //Host for .html requests
            return;
        }

        //Host for .json requests
        $host = $this->Host->find('first', [
            'fields'     => [
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
            'contain'    => [
                'Container',
            ],
        ]);

        $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $host['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        $AngularAcknowledgementsControllerRequest = new \itnovum\openITCOCKPIT\Core\AngularJS\Request\AcknowledgementsControllerRequest($this->request);

        //Process conditions
        $Conditions = new AcknowledgedHostConditions();
        $Conditions->setLimit($this->Paginator->settings['limit']);
        $Conditions->setFrom($AngularAcknowledgementsControllerRequest->getFrom());
        $Conditions->setTo($AngularAcknowledgementsControllerRequest->getTo());
        $Conditions->setStates($AngularAcknowledgementsControllerRequest->getHostStates());
        $Conditions->setOrder($AngularAcknowledgementsControllerRequest->getOrderForPaginator('AcknowledgedHost.entry_time', 'desc'));
        $Conditions->setHostUuid($host['Host']['uuid']);

        //Query state history records
        $query = $this->AcknowledgedHost->getQuery($Conditions, $AngularAcknowledgementsControllerRequest->getHostFilters());

        $this->Paginator->settings = $query;
        $this->Paginator->settings['page'] = $AngularAcknowledgementsControllerRequest->getPage();

        if ($this->isScrollRequest()) {
            $ScrollIndex = new ScrollIndex($this->Paginator, $this);
            $acknowledgements = $this->AcknowledgedHost->find('all', $this->Paginator->settings);
            $ScrollIndex->determineHasNextPage($acknowledgements);
            $ScrollIndex->scroll();
        } else {
            $acknowledgements = $this->Paginator->paginate(
                $this->AcknowledgedHost->alias,
                [],
                [key($this->Paginator->settings['order'])]
            );
        }

        $all_acknowledgements = [];
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        foreach ($acknowledgements as $acknowledgement) {
            $Acknowledgement = new itnovum\openITCOCKPIT\Core\Views\AcknowledgementHost($acknowledgement['AcknowledgedHost'], $UserTime);
            $acknowledgementArray = $Acknowledgement->toArray();
            $acknowledgementArray['comment_data'] = $this->Bbcode->nagiosNl2br($this->Bbcode->asHtml($acknowledgementArray['comment_data'], true));
            $all_acknowledgements[] = [
                'AcknowledgedHost' => $acknowledgementArray
            ];
        }

        $this->set(compact(['all_acknowledgements']));
        $toJson = ['all_acknowledgements', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_acknowledgements', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }
}
