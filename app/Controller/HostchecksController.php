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

use itnovum\openITCOCKPIT\Core\ValueObjects\HostStates;
use itnovum\openITCOCKPIT\Core\HostchecksControllerRequest;
use itnovum\openITCOCKPIT\Core\HostcheckConditions;

class HostchecksController extends AppController {
    /*
     * Attention! In this case we load an external Model from the monitoring plugin! The Controller
     * use this external model to fetch the required data out of the database
     */
    public $uses = [MONITORING_HOSTCHECK, MONITORING_HOSTSTATUS, 'Host', 'Documentation'];


    public $components = ['Paginator', 'ListFilter.ListFilter', 'RequestHandler'];
    public $helpers = ['ListFilter.ListFilter', 'Status', 'Monitoring'];
    public $layout = 'Admin.default';

    public $listFilters = [
        'index' => [
            'fields' => [
                'Hostcheck.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function index($id = null){
        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('invalid host'));
        }

        //Process request and set request settings back to front end
        $HostStates = new HostStates();
        $HostchecksControllerRequest = new HostchecksControllerRequest(
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
        $Conditions = new HostcheckConditions();
        $Conditions->setLimit($HostchecksControllerRequest->getLimit());
        $Conditions->setFrom($HostchecksControllerRequest->getFrom());
        $Conditions->setTo($HostchecksControllerRequest->getTo());
        $Conditions->setStates($HostchecksControllerRequest->getHostStates());
        $Conditions->setOrder($HostchecksControllerRequest->getOrder());
        $Conditions->setHostUuid($host['Host']['uuid']);

        //Query host check records
        $query = $this->Hostcheck->getQuery($Conditions, $this->Paginator->settings['conditions']);
        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
        $all_hostchecks = $this->Paginator->paginate(null, [], [key($this->Paginator->settings['order'])]);

        $docuExists = $this->Documentation->existsForUuid($host['Host']['uuid']);

        //Get meta data and push to front end
        $hoststatus = $this->Hoststatus->byUuid($host['Host']['uuid'], [
            'fields' => [
                'Hoststatus.current_state',
                'Hoststatus.is_flapping'
            ],
        ]);
        $this->set(compact(['host', 'all_hostchecks', 'hoststatus', 'docuExists']));
        $this->set('HostcheckListsettings', $HostchecksControllerRequest->getRequestSettingsForListSettings());
    }
}