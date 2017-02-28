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

class LogentriesController extends AppController
{

    /*
     * Attention! In this case we load an external Model from the monitoring plugin! The Controller
     * use this external model to fetch the required data out of the database
     */
    public $uses = [MONITORING_LOGENTRY, 'Host', 'Service'];

    public $components = ['Paginator', 'ListFilter.ListFilter', 'RequestHandler', 'Uuid'];
    public $helpers = ['ListFilter.ListFilter', 'Status', 'Monitoring', 'CustomValidationErrors', 'Uuid'];
    public $layout = 'Admin.default';

    public $listFilters = [
        'index' => [
            'fields' => [
                'Logentry.logentry_data' => ['label' => 'Logentry', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function index()
    {
        $requestSettings = $this->Logentry->listSettings($this->request->params['named']);
        if($this->request->is('post')){
            $requestSettings = $this->Logentry->listSettings($this->request->data);
        }
        if (!is_array($this->Paginator->settings)) {
            $this->Paginator->settings = [];
        }
        if (!isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = [];
        }
        if (!isset($this->Paginator->settings['order'])) {
            $this->Paginator->settings['order'] = ['logentry_time' => 'desc'];
        }
        if (isset($this->paginate['conditions'])) {
            $this->Paginator->settings['conditions'] = Hash::merge($this->paginate['conditions'], $requestSettings['conditions']);
        } else {
            $this->Paginator->settings['conditions'] = $requestSettings['conditions'];
        }

        if(!empty($requestSettings['paginator']['limit']) && $requestSettings['paginator']['limit'] > 100){
            $this->Paginator->settings['maxLimit'] = $requestSettings['paginator']['limit'];
        }

        $this->Paginator->settings = Hash::merge($this->Paginator->settings, $requestSettings['paginator']);

        $paginatorLimit = $this->Paginator->settings['limit'];


        $this->Uuid->buildCache();
        $this->set('uuidCache', $this->Uuid->getCache());

        $all_logentries = $this->Paginator->paginate(null, [], [key($this->Paginator->settings['order'])]);

        $this->set(compact(['all_logentries', 'paginatorLimit']));
        $this->set('LogentiresListsettings', $requestSettings['Listsettings']);
        $this->set('logentry_types', $this->Logentry->types());


        if (isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null) {
            $this->set('isFilter', true);
        } else {
            $this->set('isFilter', false);
        }
    }
}
