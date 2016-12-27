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

class DowntimesController extends AppController
{

    /*
     * Attention! In this case we load an external Model from the monitoring plugin! The Controller
     * use this external model to fetch the required data out of the database
     */
    public $uses = [MONITORING_DOWNTIME, 'Host', 'Service', 'Hostgroup', 'Systemsetting'];

    public $components = ['Paginator', 'ListFilter.ListFilter', 'RequestHandler'];
    public $helpers = ['ListFilter.ListFilter', 'Status', 'Monitoring', 'CustomValidationErrors', 'Uuid'];
    public $layout = 'Admin.default';

    public $listFilters = [
        'host'    => [
            'fields' => [
                'Host.name'             => ['label' => 'Host', 'searchType' => 'wildcard'],
                'Downtime.author_name'  => ['label' => 'User', 'searchType' => 'wildcard'],
                'Downtime.comment_data' => ['label' => 'Comment', 'searchType' => 'wildcard'],
            ],
        ],
        'service' => [
            'fields' => [
                'Host.name'             => ['label' => 'Host', 'searchType' => 'wildcard'],
                'Downtime.author_name'  => ['label' => 'User', 'searchType' => 'wildcard'],
                'Downtime.comment_data' => ['label' => 'Comment', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function host()
    {
        $paginatorLimit = $this->Paginator->settings['limit'];
        $requestSettings = $this->Downtime->hostListSettings($this->request, $this->MY_RIGHTS, $paginatorLimit);

        if (isset($this->paginate['conditions'])) {
            $this->Paginator->settings['conditions'] = Hash::merge($this->paginate['conditions'], $requestSettings['conditions']);
        } else {
            $this->Paginator->settings['conditions'] = $requestSettings['conditions'];
        }

        $this->Paginator->settings['limit'] = $requestSettings['paginator']['limit'];
        $this->Paginator->settings['order'] = $requestSettings['paginator']['order'];
        $this->Paginator->settings['conditions'] = Hash::merge($this->paginate['conditions'], $requestSettings['conditions']);
        $this->Paginator->settings['joins'] = [

        ];
        $this->Paginator->settings = Hash::merge($this->Paginator->settings, $requestSettings['default']);

        //--force --doit --yes-i-know-what-i-do
        // force the order of joined tables
        $all_downtimes = $this->Paginator->paginate(null, [], [key($this->Paginator->settings['order'])]);

        $this->set(compact(['all_downtimes', 'paginatorLimit']));
        $this->set('DowntimeListsettings', $requestSettings['Listsettings']);

        $this->Frontend->setJson('websocket_url', 'wss://'.env('HTTP_HOST').'/sudo_server');
        $key = $this->Systemsetting->findByKey('SUDO_SERVER.API_KEY');
        $this->Frontend->setJson('akey', $key['Systemsetting']['value']);

        if (isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null) {
            $this->set('isFilter', true);
        } else {
            $this->set('isFilter', false);
        }
    }

    public function service()
    {
        $paginatorLimit = $this->Paginator->settings['limit'];
        $requestSettings = $this->Downtime->serviceListSettings($this->request, $this->MY_RIGHTS, $paginatorLimit);

        if (isset($this->paginate['conditions'])) {
            $this->Paginator->settings['conditions'] = Hash::merge($this->paginate['conditions'], $requestSettings['conditions']);
        } else {
            $this->Paginator->settings['conditions'] = $requestSettings['conditions'];
        }

        $this->Paginator->settings['limit'] = $requestSettings['paginator']['limit'];
        $this->Paginator->settings['order'] = $requestSettings['paginator']['order'];
        $this->Paginator->settings['conditions'] = Hash::merge($this->paginate['conditions'], $requestSettings['conditions']);
        $this->Paginator->settings = Hash::merge($this->Paginator->settings, $requestSettings['default']);

        //--force --doit --yes-i-know-what-i-do
        // force the order of joined tables
        $all_downtimes = $this->Paginator->paginate(null, [], [key($this->Paginator->settings['order'])]);
        $this->set(compact(['all_downtimes', 'paginatorLimit']));
        $this->set('DowntimeListsettings', $requestSettings['Listsettings']);

        $this->Frontend->setJson('websocket_url', 'wss://'.env('HTTP_HOST').'/sudo_server');
        $key = $this->Systemsetting->findByKey('SUDO_SERVER.API_KEY');
        $this->Frontend->setJson('akey', $key['Systemsetting']['value']);

        if (isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null) {
            $this->set('isFilter', true);
        } else {
            $this->set('isFilter', false);
        }
    }

    public function index()
    {
        if (isset($this->PERMISSIONS['downtimes']['host'])) {
            $this->redirect(['action' => 'host']);
        }

        if (isset($this->PERMISSIONS['downtimes']['service'])) {
            $this->redirect(['action' => 'service']);
        }
    }

    public function validateDowntimeInputFromBrowser()
    {
        $this->render(false);
        if (isset($this->request->data['from']) && isset($this->request->data['to'])) {
            if (strtotime($this->request->data['from']) !== false && strtotime($this->request->data['to']) !== false
                && strlen($this->request->data['from']) > 0 && strlen($this->request->data['to']) > 0
            ) {
                echo 1;

                return;
            }
        }
        echo 0;
    }
}