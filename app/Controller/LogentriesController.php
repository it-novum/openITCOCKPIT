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

use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\ScrollIndex;
use itnovum\openITCOCKPIT\Filter\LogentryFilter;

class LogentriesController extends AppController {

    /*
     * Attention! In this case we load an external Model from the monitoring plugin! The Controller
     * use this external model to fetch the required data out of the database
     */
    public $uses = [MONITORING_LOGENTRY, 'Host', 'Service'];

    public $components = ['ListFilter.ListFilter', 'RequestHandler', 'Uuid'];
    public $helpers = ['ListFilter.ListFilter', 'Status', 'Monitoring', 'CustomValidationErrors', 'Uuid'];
    public $layout = 'angularjs';


    public function index() {

        if (!$this->isAngularJsRequest()) {
            //Ship .html request
            $this->set('logentry_types', $this->Logentry->types());
            return;
        }

        $LogentryFilter = new LogentryFilter($this->request);


        $this->Paginator->settings['order'] = $LogentryFilter->getOrderForPaginator('Logentry.entry_time', 'desc');
        $this->Paginator->settings['page'] = $LogentryFilter->getPage();
        $this->Paginator->settings['conditions'] = $LogentryFilter->indexFilter();

        if (isset($this->request->query['filter']['Host.id']) && !empty($this->request->query['filter']['Host.id'])) {
            $hosts = $this->Host->find('all', [
                'recursive'  => -1,
                'fields'     => [
                    'Host.id',
                    'Host.uuid'
                ],
                'conditions' => [
                    'Host.id' => $this->request->query['filter']['Host.id']
                ]
            ]);
            if(!empty($hosts)) {
                $orConditions = [];
                foreach($hosts as $host){
                    $orConditions[] = $host['Host']['uuid'];
                }
                $this->Paginator->settings['conditions']['Logentry.logentry_data rlike'] = sprintf('.*(%s).*', implode('|', $orConditions));
            }
        }

        $ScrollIndex = new ScrollIndex($this->Paginator, $this);
        if($this->isScrollRequest()){
            $logentries = $this->Logentry->find('all', $this->Paginator->settings);
            $ScrollIndex->determineHasNextPage($logentries);
            $ScrollIndex->scroll();
        }else{
            $logentries = $this->Paginator->paginate();
        }


        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        App::uses('UUID', 'Lib');


        $all_logentries = [];
        $foundUuids = [];
        foreach ($logentries as $logentry) {
            $matches = [];
            preg_match_all(UUID::regex(), $logentry['Logentry']['logentry_data'], $matches);
            foreach ($matches[0] as $uuid) {
                $foundUuids[$uuid] = $uuid;
            }
        }


        $uuidToName = $this->Uuid->getNameForUuids($foundUuids, false);

        foreach ($logentries as $logentry) {
            $logentry['Logentry']['logentry_data'] = preg_replace_callback(UUID::regex(), function ($matches) use ($uuidToName) {
                foreach ($matches as $match) {
                    if (isset($uuidToName[$match])) {
                        return $uuidToName[$match];
                    }
                }
            }, $logentry['Logentry']['logentry_data']);

            $Logentry = new \itnovum\openITCOCKPIT\Core\Views\Logentry($logentry, $UserTime);

            $all_logentries[] = [
                'Logentry' => $Logentry->toArray()
            ];
        }


        $this->set('all_logentries', $all_logentries);

        $toJson = ['all_logentries', 'paging'];
        if($this->isScrollRequest()){
            $toJson = ['all_logentries', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

}
