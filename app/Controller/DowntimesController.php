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

class DowntimesController extends AppController {

    /*
     * Attention! In this case we load an external Model from the monitoring plugin! The Controller
     * use this external model to fetch the required data out of the database
     */
    public $uses = [MONITORING_DOWNTIME, 'Host', 'Service', 'Hostgroup'];

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

    public function host() {
        $paginatorLimit = $this->Paginator->settings['limit'];
        $requestSettings = $this->Downtime->hostListSettings($this->request, $this->MY_RIGHTS, $paginatorLimit);

        if (isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);
        } else {
            $this->Paginator->settings['conditions'] = $requestSettings['conditions'];
        }

        $this->Paginator->settings['limit'] = $requestSettings['paginator']['limit'];
        $this->Paginator->settings['order'] = $requestSettings['paginator']['order'];
        $this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);
        $this->Paginator->settings['joins'] = [

        ];
        $this->Paginator->settings = Hash::merge($this->Paginator->settings, $requestSettings['default']);

        //--force --doit --yes-i-know-what-i-do
        // force the order of joined tables
        $all_downtimes = $this->Paginator->paginate(null, [], [key($this->Paginator->settings['order'])]);
        foreach ($all_downtimes as $dKey => $downtime) {
            if (isset($this->MY_RIGHTS_LEVEL[$downtime['HostsToContainers']['container_id']]) && $this->MY_RIGHTS_LEVEL[$downtime['HostsToContainers']['container_id']] == WRITE_RIGHT) {
                $all_downtimes[$dKey]['canDelete'] = true;
                $serviceDowntimes = $this->Downtime->getServiceDowntimesForHost($downtime['Host']['id'], $downtime['Downtime']['scheduled_start_time'], $downtime['Downtime']['scheduled_end_time']);
                $all_downtimes[$dKey]['servicesDown'] = '0';
                if (count($serviceDowntimes) > 0) {
                    foreach ($serviceDowntimes as $serviceDowntime) {
                        $all_downtimes[$dKey]['servicesDown'] .= ',' . $serviceDowntime['Downtime']['internal_downtime_id'];
                    }
                }
            } else {
                $all_downtimes[$dKey]['canDelete'] = false;
            }

        }

        $this->set(compact(['all_downtimes', 'paginatorLimit']));
        $this->set('DowntimeListsettings', $requestSettings['Listsettings']);
    }


    public function service() {
        $paginatorLimit = $this->Paginator->settings['limit'];
        $requestSettings = $this->Downtime->serviceListSettings($this->request, $this->MY_RIGHTS, $paginatorLimit);

        if (isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);
        } else {
            $this->Paginator->settings['conditions'] = $requestSettings['conditions'];
        }

        $this->Paginator->settings['limit'] = $requestSettings['paginator']['limit'];
        $this->Paginator->settings['order'] = $requestSettings['paginator']['order'];
        $this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);
        $this->Paginator->settings = Hash::merge($this->Paginator->settings, $requestSettings['default']);

        //--force --doit --yes-i-know-what-i-do
        // force the order of joined tables
        $all_downtimes = $this->Paginator->paginate(null, [], [key($this->Paginator->settings['order'])]);
        foreach ($all_downtimes as $dKey => $downtime) {
            if (isset($this->MY_RIGHTS_LEVEL[$downtime['HostsToContainers']['container_id']]) && $this->MY_RIGHTS_LEVEL[$downtime['HostsToContainers']['container_id']] == WRITE_RIGHT) {
                $all_downtimes[$dKey]['canDelete'] = true;
            } else {
                $all_downtimes[$dKey]['canDelete'] = false;
            }

        }
        $this->set(compact(['all_downtimes', 'paginatorLimit']));
        $this->set('DowntimeListsettings', $requestSettings['Listsettings']);

    }

    public function index() {
        if (isset($this->PERMISSIONS['downtimes']['host'])) {
            $this->redirect(['action' => 'host']);
        }

        if (isset($this->PERMISSIONS['downtimes']['service'])) {
            $this->redirect(['action' => 'service']);
        }
    }

    public function validateDowntimeInputFromBrowser() {
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

    public function validateDowntimeInputFromAngular() {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $error = ['Downtime' => []];
        $data = $this->request->data;
        if (!isset($data['comment']) || strlen($data['comment']) === 0) {
            $error['Downtime']['comment'][] = __('Comment can not be empty');
        }

        if(!isset($data['from_date']) || strlen($data['from_date']) === 0){
            $error['Downtime']['from_date'][] = __('Start date can not be empty');
        }

        if(!isset($data['from_time']) || strlen($data['from_time']) === 0){
            $error['Downtime']['from_time'][] = __('Start time can not be empty');
        }

        if(!isset($data['to_date']) || strlen($data['to_date']) === 0){
            $error['Downtime']['to_date'][] = __('End date can not be empty');
        }

        if(!isset($data['to_time']) || strlen($data['to_time']) === 0){
            $error['Downtime']['to_time'][] = __('End time can not be empty');
        }

        if(empty($error['Downtime'])) {
            $start = sprintf('%s %s', $data['from_date'], $data['from_time']);
            $end = sprintf('%s %s', $data['to_date'], $data['to_time']);
            if(strtotime($start) === false){
                $error['Downtime']['from_date'][] = __('Date is not valid');
            }

            if(strtotime($end) === false){
                $error['Downtime']['to_date'][] = __('Date is not valid');
            }
        }

        if(!empty($error['Downtime'])){
            $this->response->statusCode(400);
            $this->set('success', false);
        }else{
            $this->set('success', true);
        }

        $this->set('error', $error);
        $this->set('_serialize', ['error', 'success']);
    }

    public function delete() {
        // creating rights downtimes.delete
    }
}