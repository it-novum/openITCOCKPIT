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

class HostchecksController extends AppController
{
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

    public function index($id = null)
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


        $requestSettings = $this->Hostcheck->listSettings($this->request, $host['Host']['uuid']);

        if (isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);
        } else {
            $this->Paginator->settings['conditions'] = $requestSettings['conditions'];
        }


        $this->Paginator->settings['order'] = $requestSettings['paginator']['order'];
        $this->Paginator->settings['limit'] = $requestSettings['paginator']['limit'];

        $all_hostchecks = $this->Paginator->paginate();

        $hostDocuExists = $this->Documentation->existsForHost($host['Host']['uuid']);

        $this->set(compact(['host', 'all_hostchecks', 'hoststatus', 'hostDocuExists']));
        $this->set('StatehistoryListsettings', $requestSettings['Listsettings']);

        if (isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null) {
            $this->set('isFilter', true);
        } else {
            $this->set('isFilter', false);
        }
    }
}