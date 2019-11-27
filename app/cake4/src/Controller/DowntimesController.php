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

declare(strict_types=1);

namespace App\Controller;

use App\Lib\Interfaces\DowntimehistoryHostsTableInterface;
use App\Lib\Interfaces\DowntimehistoryServicesTableInterface;
use App\Model\Table\HostsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Request\HostDowntimesControllerRequest;
use itnovum\openITCOCKPIT\Core\AngularJS\Request\ServiceDowntimesControllerRequest;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\DowntimeHostConditions;
use itnovum\openITCOCKPIT\Core\DowntimeServiceConditions;
use itnovum\openITCOCKPIT\Core\System\Gearman;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;

/**
 * Class DowntimesController
 * @property AppPaginatorComponent $Paginator
 * @property AppAuthComponent $Auth
 * @property DbBackend $DbBackend
 */
class DowntimesController extends AppController {


    public $layout = 'blank';

    public function host() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML template
            return;
        }

        $HostDowntimesControllerRequest = new HostDowntimesControllerRequest($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $HostDowntimesControllerRequest->getPage());

        //Process conditions
        $DowntimeHostConditions = new DowntimeHostConditions();
        $DowntimeHostConditions->setFrom($HostDowntimesControllerRequest->getFrom());
        $DowntimeHostConditions->setTo($HostDowntimesControllerRequest->getTo());
        $DowntimeHostConditions->setHideExpired($HostDowntimesControllerRequest->hideExpired());
        $DowntimeHostConditions->setIsRunning($HostDowntimesControllerRequest->isRunning());
        $DowntimeHostConditions->setContainerIds($this->MY_RIGHTS);
        $DowntimeHostConditions->setOrder($HostDowntimesControllerRequest->getOrderForPaginator('DowntimeHosts.scheduled_start_time', 'desc'));
        $DowntimeHostConditions->setConditions($HostDowntimesControllerRequest->getIndexFilters());

        /** @var DowntimehistoryHostsTableInterface $DowntimehistoryHostsTable */
        $DowntimehistoryHostsTable = $this->DbBackend->getDowntimehistoryHostsTable();
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        $hostDowntimes = $DowntimehistoryHostsTable->getDowntimes($DowntimeHostConditions, $PaginateOMat);

        //Load containers for hosts, for non root users
        $hostContainers = [];
        $hostIds = [];
        if (!empty($hostDowntimes) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'hosts')) {
            foreach ($hostDowntimes as $record) {
                $hostIds[] = $record->get('Hosts')['id'];
            }

            $hostIds = array_unique($hostIds);

            foreach ($hostIds as $hostId) {
                $host = $HostsTable->getHostByIdForPermissionCheck($hostId);
                $hostContainers[$host->get('id')] = $host->getContainerIds();
            }
        }


        //Prepare data for API
        $all_host_downtimes = [];
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $UserTime = $User->getUserTime();

        foreach ($hostDowntimes as $hostDowntime) {
            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $containerIds = [];
                if (isset($hostContainers[$hostDowntime->get('Hosts')['id']])) {
                    $containerIds = $hostContainers[$hostDowntime->get('Hosts')['id']];
                }
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIds);
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($hostDowntime->get('Hosts'), $allowEdit);
            $HostDowntime = new \itnovum\openITCOCKPIT\Core\Views\Downtime($hostDowntime->toArray(), $allowEdit, $UserTime);

            $all_host_downtimes[] = [
                'Host'         => $Host->toArray(),
                'DowntimeHost' => $HostDowntime->toArray()
            ];
        }


        $this->set('all_host_downtimes', $all_host_downtimes);
        $toJson = ['all_host_downtimes', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_host_downtimes', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    public function service() {
        if (!$this->isAngularJsRequest()) {
            return;
        }

        $ServiceDowntimesControllerRequest = new ServiceDowntimesControllerRequest($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $ServiceDowntimesControllerRequest->getPage());

        //Process conditions
        $DowntimeServiceConditions = new DowntimeServiceConditions();
        $DowntimeServiceConditions->setLimit($this->Paginator->settings['limit']);
        $DowntimeServiceConditions->setFrom($ServiceDowntimesControllerRequest->getFrom());
        $DowntimeServiceConditions->setTo($ServiceDowntimesControllerRequest->getTo());
        $DowntimeServiceConditions->setHideExpired($ServiceDowntimesControllerRequest->hideExpired());
        $DowntimeServiceConditions->setIsRunning($ServiceDowntimesControllerRequest->isRunning());
        $DowntimeServiceConditions->setContainerIds($this->MY_RIGHTS);
        $DowntimeServiceConditions->setOrder($ServiceDowntimesControllerRequest->getOrderForPaginator('DowntimeServices.scheduled_start_time', 'desc'));
        $DowntimeServiceConditions->setConditions($ServiceDowntimesControllerRequest->getIndexFilters());

        /** @var DowntimehistoryServicesTableInterface $DowntimehistoryServicesTable */
        $DowntimehistoryServicesTable = $this->DbBackend->getDowntimehistoryServicesTable();
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        $serviceDowntimes = $DowntimehistoryServicesTable->getDowntimes($DowntimeServiceConditions, $PaginateOMat);

        //Load containers for hosts, for non root users
        $hostContainers = [];
        $hostIds = [];
        if (true) {
            //if (!empty($serviceDowntimes) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'services')) {
            foreach ($serviceDowntimes as $record) {
                $hostIds[] = $record->get('Hosts')['id'];
            }

            $hostIds = array_unique($hostIds);

            foreach ($hostIds as $hostId) {
                $host = $HostsTable->getHostByIdForPermissionCheck($hostId);
                $hostContainers[$host->get('id')] = $host->getContainerIds();
            }
        }


        //Prepare data for API
        $all_service_downtimes = [];
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $UserTime = $User->getUserTime();

        foreach ($serviceDowntimes as $serviceDowntime) {
            if ($this->hasRootPrivileges === 'asdasdsd') { //Remove!!
                $allowEdit = true;
            } else {
                $containerIds = [];
                if (isset($hostContainers[$serviceDowntime->get('Hosts')['id']])) {
                    $containerIds = $hostContainers[$serviceDowntime->get('Hosts')['id']];
                }
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIds);
                $allowEdit = $ContainerPermissions->hasPermission();
            }


            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($serviceDowntime->get('Hosts'), $allowEdit);
            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($serviceDowntime->get('Services'), $serviceDowntime->get('servicename'), $allowEdit);
            $ServiceDowntime = new \itnovum\openITCOCKPIT\Core\Views\Downtime($serviceDowntime->toArray(), $allowEdit, $UserTime);

            $all_service_downtimes[] = [
                'Host'            => $Host->toArray(),
                'Service'         => $Service->toArray(),
                'DowntimeService' => $ServiceDowntime->toArray()
            ];
        }

        $this->set('all_service_downtimes', $all_service_downtimes);
        $toJson = ['all_service_downtimes', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_service_downtimes', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
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
        $this->viewBuilder()->setOption('serialize', ['error', 'success']);
    }

    /**
     * @param int $internalDowntimeId
     * @throws \App\Lib\Exceptions\MissingDbBackendException
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

        $GearmanClient = new Gearman();

        $DowntimeHostsTable = $this->DbBackend->getDowntimehistoryHostsTable();
        $DowntimeServicesTable = $this->DbBackend->getDowntimehistoryServicesTable();
        /** @var $HostsTable HostsTable */

        switch ($this->request->data['type']) {
            case 'host':
                $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

                $includeServices = true;
                if (isset($this->request->data['includeServices'])) {
                    $includeServices = (bool)$this->request->data['includeServices'];
                }

                if ($includeServices) {
                    //Find current downtime
                    $downtime = $DowntimeHostsTable->getHostUuidWithDowntimeByInternalDowntimeId($internalDowntimeId);
                    if (!empty($downtime)) {
                        $Downtime = new \itnovum\openITCOCKPIT\Core\Views\Downtime($downtime['DowntimeHosts']);
                        try {
                            $host = $HostsTable->getHostByUuid($downtime['Hosts']['uuid']);
                            $servicesInternalDowntimeIds = $DowntimeServicesTable->getServiceDowntimesByHostAndDowntime(
                                $host->get('id'),
                                $Downtime
                            );

                            //Delete Host downtime
                            $GearmanClient->sendBackground('deleteHostDowntime', [
                                'internal_downtime_id' => $internalDowntimeId
                            ]);

                            //Delete corresponding service downtimes
                            foreach ($servicesInternalDowntimeIds as $serviceInternalDowntimeId) {
                                $GearmanClient->sendBackground('deleteServiceDowntime', [
                                    'internal_downtime_id' => $serviceInternalDowntimeId
                                ]);
                            }

                        } catch (RecordNotFoundException $e) {
                            $this->set('success', false);
                            $this->set('message', $e->getMessage());
                            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
                            return;
                        }

                    }
                } else {
                    //Only delete Host downtime
                    $GearmanClient->sendBackground('deleteHostDowntime', [
                        'internal_downtime_id' => $internalDowntimeId
                    ]);
                }
                break;

            default:
                //Only delete Service downtime
                $GearmanClient->sendBackground('deleteServiceDowntime', [
                    'internal_downtime_id' => $internalDowntimeId
                ]);
                break;
        }
        $this->set('success', true);
        $this->set('message', __('Successfully'));
        $this->viewBuilder()->setOption('serialize', ['success', 'message']);
    }

    public function icon() {
        //Only ship template
        return;
    }
}
