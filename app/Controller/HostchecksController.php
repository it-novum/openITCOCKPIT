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

use itnovum\openITCOCKPIT\Core\HostcheckConditions;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\ScrollIndex;

class HostchecksController extends AppController {
    /*
     * Attention! In this case we load an external Model from the monitoring plugin! The Controller
     * use this external model to fetch the required data out of the database
     */
    public $uses = [
        MONITORING_HOSTCHECK,
        MONITORING_HOSTSTATUS,
        'Host',
        'Documentation'
    ];

    public $components = ['RequestHandler'];
    public $helpers = ['Status', 'Monitoring'];
    public $layout = 'Admin.default';

    public function index($id = null){
        $this->layout="angularjs";

        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('invalid host'));
        }

        if (!$this->isAngularJsRequest()) {
            //Host for .html requests
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

            //Get meta data and push to front end
            $HoststatusFields = new HoststatusFields($this->DbBackend);
            $HoststatusFields->currentState()->isFlapping();

            $hoststatus = $this->Hoststatus->byUuid($host['Host']['uuid'], $HoststatusFields);
            $docuExists = $this->Documentation->existsForUuid($host['Host']['uuid']);
            $this->set(compact(['host', 'hoststatus', 'docuExists']));
            return;
        }


        //Host for .json requests
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
            ]
        ]);

        $AngularHostchecksControllerRequest = new \itnovum\openITCOCKPIT\Core\AngularJS\Request\HostchecksControllerRequest($this->request);

        //Process conditions
        $Conditions = new HostcheckConditions();
        $Conditions->setLimit($this->Paginator->settings['limit']);
        $Conditions->setFrom($AngularHostchecksControllerRequest->getFrom());
        $Conditions->setTo($AngularHostchecksControllerRequest->getTo());
        $Conditions->setStates($AngularHostchecksControllerRequest->getHostStates());
        $Conditions->setStateTypes($AngularHostchecksControllerRequest->getHostStateTypes());
        $Conditions->setOrder($AngularHostchecksControllerRequest->getOrderForPaginator('Hostcheck.start_time', 'desc'));
        $Conditions->setHostUuid($host['Host']['uuid']);

        //Query host check records
        $query = $this->Hostcheck->getQuery($Conditions, $AngularHostchecksControllerRequest->getIndexFilters());

        $this->Paginator->settings = $query;
        $this->Paginator->settings['page'] = $AngularHostchecksControllerRequest->getPage();

        $ScrollIndex = new ScrollIndex($this->Paginator, $this);
        if($this->isScrollRequest()) {
            $hostchecks = $this->Hostcheck->find('all', $this->Paginator->settings);
            $ScrollIndex->determineHasNextPage($hostchecks);
            $ScrollIndex->scroll();
        }else {
            $hostchecks = $this->Paginator->paginate(
                $this->Hostcheck->alias,
                [],
                [key($this->Paginator->settings['order'])]
            );
        }

        $all_hostchecks = [];
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        foreach ($hostchecks as $hostcheck) {
            $Hostcheck = new itnovum\openITCOCKPIT\Core\Views\Servicecheck($hostcheck['Hostcheck'], $UserTime);
            $all_hostchecks[] = [
                'Hostcheck' => $Hostcheck->toArray()
            ];
        }

        $this->set(compact(['all_hostchecks']));
        $toJson = ['all_hostchecks', 'paging'];
        if($this->isScrollRequest()){
            $toJson = ['all_hostchecks', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }
}