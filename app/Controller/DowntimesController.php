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

use itnovum\openITCOCKPIT\Core\AngularJS\Request\HostDowntimesControllerRequest;
use itnovum\openITCOCKPIT\Core\AngularJS\Request\ServiceDowntimesControllerRequest;
use itnovum\openITCOCKPIT\Core\DowntimeHostConditions;
use itnovum\openITCOCKPIT\Core\DowntimeServiceConditions;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Core\Views\UserTime;

class DowntimesController extends AppController {

    /*
     * Attention! In this case we load an external Model from the monitoring plugin! The Controller
     * use this external model to fetch the required data out of the database
     */
    public $uses = [
        MONITORING_DOWNTIME_HOST,
        MONITORING_DOWNTIME_SERVICE,
        'Host',
        'Service'
    ];

    public $helpers = ['Uuid'];
    public $layout = 'Admin.default';

    public $components = ['GearmanClient'];

    public function host() {
        $this->layout = 'angularjs';
        if (!$this->isAngularJsRequest()) {
            return;
        }

        $AngularHostDowntimesControllerRequest = new HostDowntimesControllerRequest($this->request);

        //Process conditions
        $DowntimeHostConditions = new DowntimeHostConditions();
        $DowntimeHostConditions->setLimit($this->Paginator->settings['limit']);
        $DowntimeHostConditions->setFrom($AngularHostDowntimesControllerRequest->getFrom());
        $DowntimeHostConditions->setTo($AngularHostDowntimesControllerRequest->getTo());
        $DowntimeHostConditions->setHideExpired($AngularHostDowntimesControllerRequest->hideExpired());
        $DowntimeHostConditions->setIsRunning($AngularHostDowntimesControllerRequest->isRunning());
        $DowntimeHostConditions->setContainerIds($this->MY_RIGHTS);
        $DowntimeHostConditions->setOrder($AngularHostDowntimesControllerRequest->getOrderForPaginator('DowntimeHost.scheduled_start_time', 'desc'));

        $this->Paginator->settings = $this->DowntimeHost->getQuery($DowntimeHostConditions, $AngularHostDowntimesControllerRequest->getIndexFilters());
        $this->Paginator->settings['page'] = $AngularHostDowntimesControllerRequest->getPage();

        $hostDowntimes = $this->Paginator->paginate(
            $this->DowntimeHost->alias,
            [],
            [key($this->Paginator->settings['order'])]
        );


        //Load containers for hosts, for non root users
        $hostContainers = [];
        if (!empty($hostDowntimes) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'hosts')) {
            $hostIds = array_unique(Hash::extract($hostDowntimes, '{n}.Host.id'));
            $_hostContainers = $this->Host->find('all', [
                'contain'    => [
                    'Container',
                ],
                'fields'     => [
                    'Host.id',
                    'Container.*',
                ],
                'conditions' => [
                    'Host.id' => $hostIds,
                ],
            ]);
            foreach ($_hostContainers as $host) {
                $hostContainers[$host['Host']['id']] = Hash::extract($host['Container'], '{n}.id');
            }
        }

        //Prepare data for API
        $all_host_downtimes = [];
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        foreach ($hostDowntimes as $hostDowntime) {
            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $containerIds = [];
                if (isset($hostContainers[$hostDowntime['Host']['id']])) {
                    $containerIds = $hostContainers[$hostDowntime['Host']['id']];
                }
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIds);
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($hostDowntime, $allowEdit);
            $HostDowntime = new \itnovum\openITCOCKPIT\Core\Views\Downtime($hostDowntime['DowntimeHost'], $allowEdit, $UserTime);

            $all_host_downtimes[] = [
                'Host'         => $Host->toArray(),
                'DowntimeHost' => $HostDowntime->toArray()
            ];
        }


        $this->set('all_host_downtimes', $all_host_downtimes);
        $this->set('_serialize', ['all_host_downtimes', 'paging']);

    }


    public function service() {
        $this->layout = 'angularjs';
        if (!$this->isAngularJsRequest()) {
            return;
        }

        $AngularServiceDowntimesControllerRequest = new ServiceDowntimesControllerRequest($this->request);

        //Process conditions
        $DowntimeServiceConditions = new DowntimeServiceConditions();
        $DowntimeServiceConditions->setLimit($this->Paginator->settings['limit']);
        $DowntimeServiceConditions->setFrom($AngularServiceDowntimesControllerRequest->getFrom());
        $DowntimeServiceConditions->setTo($AngularServiceDowntimesControllerRequest->getTo());
        $DowntimeServiceConditions->setHideExpired($AngularServiceDowntimesControllerRequest->hideExpired());
        $DowntimeServiceConditions->setIsRunning($AngularServiceDowntimesControllerRequest->isRunning());
        $DowntimeServiceConditions->setContainerIds($this->MY_RIGHTS);
        $DowntimeServiceConditions->setOrder($AngularServiceDowntimesControllerRequest->getOrderForPaginator('DowntimeService.scheduled_start_time', 'desc'));

        $this->Paginator->settings = $this->DowntimeService->getQuery($DowntimeServiceConditions, $AngularServiceDowntimesControllerRequest->getIndexFilters());
        $this->Paginator->settings['page'] = $AngularServiceDowntimesControllerRequest->getPage();

        $serviceDowntimes = $this->Paginator->paginate(
            $this->DowntimeService->alias,
            [],
            [key($this->Paginator->settings['order'])]
        );

        //Load containers for hosts, for non root users
        $hostContainers = [];
        if (!empty($serviceDowntimes) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'hosts')) {
            $hostIds = array_unique(Hash::extract($serviceDowntimes, '{n}.Host.id'));
            $_hostContainers = $this->Host->find('all', [
                'contain'    => [
                    'Container',
                ],
                'fields'     => [
                    'Host.id',
                    'Container.*',
                ],
                'conditions' => [
                    'Host.id' => $hostIds,
                ],
            ]);
            foreach ($_hostContainers as $host) {
                $hostContainers[$host['Host']['id']] = Hash::extract($host['Container'], '{n}.id');
            }
        }

        //Prepare data for API
        $all_service_downtimes = [];
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        foreach ($serviceDowntimes as $serviceDowntime) {
            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $containerIds = [];
                if (isset($hostContainers[$serviceDowntime['Host']['id']])) {
                    $containerIds = $hostContainers[$serviceDowntime['Host']['id']];
                }
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIds);
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($serviceDowntime, $allowEdit);
            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($serviceDowntime, null, $allowEdit);
            $ServiceDowntime = new \itnovum\openITCOCKPIT\Core\Views\Downtime($serviceDowntime['DowntimeService'], $allowEdit, $UserTime);

            $all_service_downtimes[] = [
                'Host'            => $Host->toArray(),
                'Service'         => $Service->toArray(),
                'DowntimeService' => $ServiceDowntime->toArray()
            ];
        }


        $this->set('all_service_downtimes', $all_service_downtimes);
        $this->set('_serialize', ['all_service_downtimes', 'paging']);
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

        if (!isset($data['from_date']) || strlen($data['from_date']) === 0) {
            $error['Downtime']['from_date'][] = __('Start date can not be empty');
        }

        if (!isset($data['from_time']) || strlen($data['from_time']) === 0) {
            $error['Downtime']['from_time'][] = __('Start time can not be empty');
        }

        if (!isset($data['to_date']) || strlen($data['to_date']) === 0) {
            $error['Downtime']['to_date'][] = __('End date can not be empty');
        }

        if (!isset($data['to_time']) || strlen($data['to_time']) === 0) {
            $error['Downtime']['to_time'][] = __('End time can not be empty');
        }

        if (empty($error['Downtime'])) {
            $start = sprintf('%s %s', $data['from_date'], $data['from_time']);
            $end = sprintf('%s %s', $data['to_date'], $data['to_time']);
            if (strtotime($start) === false) {
                $error['Downtime']['from_date'][] = __('Date is not valid');
            }

            if (strtotime($end) === false) {
                $error['Downtime']['to_date'][] = __('Date is not valid');
            }
        }

        if (!empty($error['Downtime'])) {
            $this->response->statusCode(400);
            $this->set('success', false);
        } else {
            $this->set('success', true);
        }

        $this->set('error', $error);
        $this->set('_serialize', ['error', 'success']);
    }

    /**
     * @param int $internalDowntimeId
     */
    public function delete($internalDowntimeId = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if ($internalDowntimeId === null || !is_numeric($internalDowntimeId)) {
            throw new InvalidArgumentException('$internalDowntimeId needs to be an integer!');
        }

        if (!isset($this->request->data['type'])) {
            throw new InvalidArgumentException('Parameter type is missing');
        }

        Configure::load('gearman');
        $this->Config = Configure::read('gearman');

        $this->GearmanClient->client->setTimeout(5000);
        $gearmanReachable = @$this->GearmanClient->client->ping(true);

        switch ($this->request->data['type']) {
            case 'host':
                //host dinge
                $includeServices = true;
                if (isset($this->request->data['includeServices'])) {
                    $includeServices = (bool)$this->request->data['includeServices'];
                }

                $servicesInternalDowntimeIds = [];
                if ($includeServices) {
                    //Find current downtime
                    $downtime = $this->DowntimeHost->getHostUuidWithDowntimeByInternalDowntimeId($internalDowntimeId);
                    $Downtime = new \itnovum\openITCOCKPIT\Core\Views\Downtime($downtime['DowntimeHost']);
                    $host = $this->Host->find('first', [
                        'recursive'  => -1,
                        'conditions' => [
                            'Host.uuid' => $downtime['Host']['uuid']
                        ],
                        'fields'     => [
                            'Host.id'
                        ],
                    ]);

                    if (!empty($host)) {
                        $servicesInternalDowntimeIds = $this->DowntimeService->getServiceDowntimesByHostAndDowntime(
                            $host['Host']['id'],
                            $Downtime
                        );
                    }
                }

                //Delete Host downtime
                $this->GearmanClient->client->doBackground(
                    "oitc_gearman",
                    Security::cipher(serialize([
                        'task'                 => 'deleteHostDowntime',
                        'internal_downtime_id' => $internalDowntimeId
                    ]), $this->Config['password'])
                );

                //Delete corresponding service downtimes
                foreach ($servicesInternalDowntimeIds as $serviceInternalDowntimeId) {
                    $this->GearmanClient->client->doBackground(
                        "oitc_gearman",
                        Security::cipher(serialize([
                            'task'                 => 'deleteServiceDowntime',
                            'internal_downtime_id' => $serviceInternalDowntimeId
                        ]), $this->Config['password'])
                    );
                }


                break;

            default:
                $this->GearmanClient->client->doBackground(
                    "oitc_gearman",
                    Security::cipher(serialize([
                        'task'                 => 'deleteServiceDowntime',
                        'internal_downtime_id' => $internalDowntimeId
                    ]), $this->Config['password'])
                );
                break;
        }
        $this->set('success', true);
        $this->set('message', __('Successfully'));
        $this->set('_serialize', ['success', 'message']);
    }

    public function icon() {
        $this->layout = 'blank';
        //Only ship template
        return;
    }
}
