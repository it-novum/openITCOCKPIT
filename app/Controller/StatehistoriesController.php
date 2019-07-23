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

use App\Model\Table\HostsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Request\StatehistoryControllerRequest;
use itnovum\openITCOCKPIT\Core\StatehistoryHostConditions;
use itnovum\openITCOCKPIT\Core\StatehistoryServiceConditions;
use itnovum\openITCOCKPIT\Core\Views\StatehistoryHost;
use itnovum\openITCOCKPIT\Core\Views\StatehistoryService;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Database\ScrollIndex;

/**
 * Class StatehistoriesController
 * @property AppAuthComponent $Auth
 * @property AppPaginatorComponent $Paginator
 */
class StatehistoriesController extends AppController {

    public $uses = [
        MONITORING_STATEHISTORY_SERVICE,
        'Service'
    ];

    public $layout = 'blank';

    public function host($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        session_write_close();

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if (!$HostsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        /** @var \App\Model\Entity\Host $host */
        $host = $HostsTable->getHostByIdForPermissionCheck($id);
        if (!$this->allowedByContainerId($host->getContainerIds(), false)) {
            $this->render403();
            return;
        }

        $AngularStatehistoryControllerRequest = new StatehistoryControllerRequest($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $AngularStatehistoryControllerRequest->getPage());


        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $UserTime = $User->getUserTime();

        //Process conditions
        $Conditions = new StatehistoryHostConditions();
        $Conditions->setOrder($AngularStatehistoryControllerRequest->getOrderForPaginator('StatehistoryHosts.state_time', 'desc'));
        $Conditions->setStates($AngularStatehistoryControllerRequest->getHostStates());
        $Conditions->setStateTypes($AngularStatehistoryControllerRequest->getHostStateTypes());
        $Conditions->setFrom($AngularStatehistoryControllerRequest->getFrom());
        $Conditions->setTo($AngularStatehistoryControllerRequest->getTo());
        $Conditions->setHostUuid($host->get('uuid'));
        $Conditions->setConditions($AngularStatehistoryControllerRequest->getHostFilters());


        $StatehistoryHostsTable = $this->DbBackend->getStatehistoryHostsTable();

        $all_statehistories = [];
        foreach ($StatehistoryHostsTable->getStatehistoryIndex($Conditions, $PaginateOMat) as $statehistory) {
            $StatehistoryHost = new StatehistoryHost($statehistory, $UserTime);
            $all_statehistories[] = [
                'StatehistoryHost' => $StatehistoryHost->toArray()
            ];
        }

        $this->set('all_statehistories', $all_statehistories);

        $toJson = ['all_statehistories', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_statehistories', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function service($id = null) {
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

        $containerIdsToCheck = Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $service['Host']['container_id'];

        //Check if user is permitted to see this object
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        $AngularStatehistoryControllerRequest = new \itnovum\openITCOCKPIT\Core\AngularJS\Request\StatehistoryControllerRequest($this->request);


        //Process conditions
        $Conditions = new StatehistoryServiceConditions();
        $Conditions->setLimit($this->Paginator->settings['limit']);
        $Conditions->setOrder($AngularStatehistoryControllerRequest->getOrderForPaginator('StatehistoryService.state_time', 'desc'));
        $Conditions->setStates($AngularStatehistoryControllerRequest->getServiceStates());
        $Conditions->setStateTypes($AngularStatehistoryControllerRequest->getServiceStateTypes());
        $Conditions->setFrom($AngularStatehistoryControllerRequest->getFrom());
        $Conditions->setTo($AngularStatehistoryControllerRequest->getTo());
        $Conditions->setServiceUuid($service['Service']['uuid']);

        //Query state history records
        $query = $this->StatehistoryService->getQuery($Conditions, $AngularStatehistoryControllerRequest->getServiceFilters());

        $this->Paginator->settings = $query;
        $this->Paginator->settings['page'] = $AngularStatehistoryControllerRequest->getPage();

        if ($this->isScrollRequest()) {
            $ScrollIndex = new ScrollIndex($this->Paginator, $this);
            $statehistories = $this->StatehistoryService->find('all', $this->Paginator->settings);
            $ScrollIndex->determineHasNextPage($statehistories);
            $ScrollIndex->scroll();
        } else {
            $statehistories = $this->Paginator->paginate(
                $this->StatehistoryService->alias,
                [],
                [key($this->Paginator->settings['order'])]
            );
        }

        $all_statehistories = [];
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        foreach ($statehistories as $statehistory) {
            $Statehistory = new StatehistoryService($statehistory['StatehistoryService'], $UserTime);
            $all_statehistories[] = [
                'StatehistoryService' => $Statehistory->toArray()
            ];
        }


        $this->set(compact(['all_statehistories']));
        $toJson = ['all_statehistories', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_statehistories', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }
}
