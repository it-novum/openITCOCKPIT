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
use App\Form\DowntimereportForm;
use App\Lib\Interfaces\DowntimehistoryHostsTableInterface;
use App\Model\Table\HostsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\DowntimeHostConditions;
use itnovum\openITCOCKPIT\Core\DowntimeServiceConditions;
use itnovum\openITCOCKPIT\Core\FileDebugger;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\Reports\DaterangesCreator;
use itnovum\openITCOCKPIT\Core\Reports\DowntimeReportBarChartWidgetDataPreparer;
use itnovum\openITCOCKPIT\Core\Reports\DowntimeReportPieChartWidgetDataPreparer;
use itnovum\openITCOCKPIT\Core\Reports\StatehistoryConverter;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\StatehistoryHostConditions;
use itnovum\openITCOCKPIT\Core\StatehistoryServiceConditions;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use Statusengine2Module\Model\Table\StatehistoryHostsTable;
use Statusengine2Module\Model\Table\StatehistoryServicesTable;

/**
 * @property Downtimereport $Downtimereport
 * @property Host $Host
 * @property Service $Service
 * @property Timeperiod $Timeperiod
 */
class DowntimereportsController extends AppController {
    public $layout = 'blank';


    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }
        $downtimeReportForm = new DowntimereportForm();
        $downtimeReportForm->execute($this->request->data);

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $UserTime = UserTime::fromUser($User);

        if (!empty($downtimeReportForm->getErrors())) {
            $this->response->statusCode(400);
            $this->set('error', $downtimeReportForm->getErrors());
            $this->set('_serialize', ['error']);
            return;
        } else {
            $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
            $timeperiod = $TimeperiodsTable->getTimeperiodWithTimerangesById($this->request->data('timeperiod_id'));
            $reflectionState = $this->request->data('reflection_state');
            if (empty($timeperiod['Timeperiod']['timeperiod_timeranges'])) {
                $this->response->statusCode(400);
                $this->set('error', [
                    'timeperiod_id' => [
                        'empty' => 'There are no time frames defined. Time evaluation report data is not available for the selected period.'
                    ]
                ]);
                $this->set('_serialize', ['error']);
                return;
            }
            /** @var HostsTable $HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
            $fromDate = strtotime($this->request->data('from_date') . ' 00:00:00');
            $toDate = strtotime($this->request->data('to_date') . ' 23:59:59');

            /**
             * @todo show error message if hostUuids are emtpy
             */
            $hostsUuids = $HostsTable->getHostsByContainerId($this->MY_RIGHTS, 'list', 'uuid');
            $DowntimeHostConditions = new DowntimeHostConditions();
            $DowntimeHostConditions->setFrom($fromDate);
            $DowntimeHostConditions->setTo($toDate);
            $DowntimeHostConditions->setContainerIds($this->MY_RIGHTS);
            $DowntimeHostConditions->setOrder(['DowntimeHosts.scheduled_start_time' => 'asc']);
            $DowntimeHostConditions->setHostUuid(array_keys($hostsUuids));
            /** @var DowntimehistoryHostsTableInterface $DowntimehistoryHostsTable */
            $DowntimehistoryHostsTable = $this->DbBackend->getDowntimehistoryHostsTable();

            $downtimes['Hosts'] = $DowntimehistoryHostsTable->getDowntimes($DowntimeHostConditions);

            $hosts = [];
            $services = [];
            $reportData = [];
            foreach ($downtimes['Hosts'] as $hostDowntime) {
                $hosts[$hostDowntime->get('Hosts')['uuid']] = [
                    'Host' => $hostDowntime->get('Hosts')
                ];
            }

            if ($this->request->data('evaluation_type') === 1) { //Evaluation with services
                $DowntimeServiceConditions = new DowntimeServiceConditions();
                $DowntimeServiceConditions->setFrom($fromDate);
                $DowntimeServiceConditions->setTo($toDate);
                $DowntimeServiceConditions->setContainerIds($this->MY_RIGHTS);
                $DowntimeServiceConditions->setOrder(['DowntimeServices.scheduled_start_time' => 'asc']);
                $DowntimeServiceConditions->setHostUuid(array_keys($hostsUuids));
                /** @var DowntimehistoryHostsTableInterface $DowntimehistoryHostsTable */
                $DowntimehistoryServicesTable = $this->DbBackend->getDowntimehistoryServicesTable();

                $downtimes['Services'] = $DowntimehistoryServicesTable->getDowntimes($DowntimeServiceConditions);
                foreach ($downtimes['Services'] as $serviceDowntime) {
                    $hosts[$serviceDowntime->get('Hosts')['uuid']] = $serviceDowntime->get('Hosts');
                    $services[$serviceDowntime->get('Services')['uuid']] = [
                        'Service'         => $serviceDowntime->get('Services'),
                        'Host'            => $serviceDowntime->get('Hosts'),
                        'Servicetemplate' => $serviceDowntime->get('Servicetemplates')
                    ];
                }
            }
            if (empty($downtimes['Hosts']) && empty($downtimes['Services'])) {
                $this->response->statusCode(400);
                $this->set('error', [
                    'no_downtimes' => [
                        'empty' => __('No downtimes within specified time found (%s - %s) !', $this->request->data('from_date'), $this->request->data('to_date'))
                    ]
                ]);
                $this->set('_serialize', ['error']);
                return;
            }

            $timeSlices = DaterangesCreator::createDateRanges(
                $fromDate,
                $toDate,
                $timeperiod['Timeperiod']['timeperiod_timeranges']
            );
            $totalTime = Hash::apply(
                array_map(function ($timeSlice) {
                    return $timeSlice['end'] - $timeSlice['start'];
                }, $timeSlices),
                '{n}',
                'array_sum'
            );

            foreach (array_keys($hosts) as $uuid) {
                $allStatehistories = [];
                $host = Set::classicExtract(
                    $HostsTable->getHostByUuid($uuid, false),
                    '{(id|address|name|description)}'
                );

                //Process conditions
                $Conditions = new StatehistoryHostConditions();
                $Conditions->setOrder(['StatehistoryHosts.state_time' => 'desc']);
                if ($reflectionState === 2) { // type 2 hard state only
                    $Conditions->setHardStateTypeAndUpState(true); // 1 => Hard State
                }
                $Conditions->setFrom($fromDate);
                $Conditions->setTo($toDate);
                $Conditions->setHostUuid($uuid);

                $StatehistoryHostsTable = $this->DbBackend->getStatehistoryHostsTable();

                $statehistoriesHost = $StatehistoryHostsTable->getStatehistoryIndex($Conditions);
                if (empty($statehistoriesHost)) {
                    $record = $StatehistoryHostsTable->getLastRecord($Conditions);
                    if (!empty($record)) {
                        $statehistoriesHost[] = $record->set('state_time', $fromDate);
                    }
                }
                $statehistoriesHost = [];
                if (empty($statehistoriesHost)) {
                    $HoststatusTable = $this->DbBackend->getHoststatusTable();
                    $HoststatusFields = new HoststatusFields($this->DbBackend);
                    $HoststatusFields->wildcard();
                    $hoststatus = $HoststatusTable->byUuid($uuid, $HoststatusFields);
                    if (!empty($hoststatus)) {
                        $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($hoststatus['Hoststatus']);
                        if ($Hoststatus->getLastStateChange() <= $fromDate) {
                            $stateHistoryHost['StatehistoryHost']['state_time'] = $fromDate;
                            $stateHistoryHost['StatehistoryHost']['state'] = $Hoststatus->currentState();
                            $stateHistoryHost['StatehistoryHost']['last_state'] = $Hoststatus->currentState();
                            $stateHistoryHost['StatehistoryHost']['last_hard_state'] = $Hoststatus->getLastHardState();
                            $stateHistoryHost['StatehistoryHost']['state_type'] = $Hoststatus->getStateType();
                            $StatehistoryHost = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryHost($stateHistoryHost['StatehistoryHost']);
                            $statehistoriesHost[$uuid]['Statehistory'][] = $StatehistoryHost;
                        }
                    }
                }

                /** @var StatehistoryHostsTable $statehistoryHost */
                foreach ($statehistoriesHost as $statehistoryHost) {
                    $StatehistoryHost = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryHost($statehistoryHost->toArray());
                    $allStatehistories[] = $StatehistoryHost->toArray();
                }

                $reportData[$uuid]['Host'] = $host;
                //FileDebugger::varExport('HOST :   '.$uuid);
                $reportData[$uuid]['Host']['reportData'] = StatehistoryConverter::generateReportData(
                    $timeSlices,
                    $allStatehistories,
                    ($reflectionState === 2),
                    true
                );
            }
            foreach (array_keys($services) as $uuid) {
                $allStatehistories = [];
                //Process conditions
                $Conditions = new StatehistoryServiceConditions();
                $Conditions->setOrder(['StatehistoryServices.state_time' => 'desc']);
                if ($reflectionState === 2) { // type 2 hard state only
                    $Conditions->setHardStateTypeAndUpState(true); // 1 => Hard State
                }
                $Conditions->setFrom($fromDate);
                $Conditions->setTo($toDate);
                $Conditions->setServiceUuid($uuid);

                $StatehistoryServicesTable = $this->DbBackend->getStatehistoryServicesTable();

                $statehistoriesService = $StatehistoryServicesTable->getStatehistoryIndex($Conditions);
                if (empty($statehistoriesService)) {
                    $record = $StatehistoryServicesTable->getLastRecord($Conditions);
                    if (!empty($record)) {
                        $statehistoriesService[] = $record->set('state_time', $fromDate);
                    }
                }
                if (empty($statehistoriesService)) {
                    $ServicestatusTable = $this->DbBackend->getServicestatusTable();
                    $ServicestatusFields = new ServicestatusFields($this->DbBackend);
                    $ServicestatusFields->wildcard();
                    $servicestatus = $ServicestatusTable->byUuid($uuid, $ServicestatusFields);
                    if (!empty($servicestatus)) {
                        $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($servicestatus['Servicestatus']);
                        if ($Servicestatus->getLastStateChange() <= $fromDate) {
                            $stateHistoryService['StatehistoryService']['state_time'] = $fromDate;
                            $stateHistoryService['StatehistoryService']['state'] = $Servicestatus->currentState();
                            $stateHistoryService['StatehistoryService']['last_state'] = $Servicestatus->currentState();
                            $stateHistoryService['StatehistoryService']['last_hard_state'] = $Servicestatus->getLastHardState();
                            $stateHistoryService['StatehistoryService']['state_type'] = $Servicestatus->getStateType();
                            $stateHistoryService = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryService($stateHistoryService['StatehistoryService']);
                            $statehistoriesService[$uuid]['Statehistory'][] = $stateHistoryService;
                        }
                    }
                }

                /** @var StatehistoryServicesTable $statehistoryService */
                foreach ($statehistoriesService as $statehistoryService) {
                    $StatehistoryService = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryService($statehistoryService->toArray());
                    $allStatehistories[] = $StatehistoryService->toArray();
                }

                $reportData[$services[$uuid]['Host']['uuid']]['Services'][$uuid] = $services[$uuid];
                $reportData[$services[$uuid]['Host']['uuid']]['Services'][$uuid]['Service']['reportData'] = StatehistoryConverter::generateReportData(
                    $timeSlices,
                    $allStatehistories,
                    ($reflectionState === 2),
                    false
                );

            }
            $downtimeReport = [];
            foreach ($reportData as $reportResult) {
                if ($reportResult['Host']['reportData'][1] > 0) {
                    $downtimeReport['hostsWithOutages'][] = $reportResult;
                } else {
                    $downtimeReport['hostsWithoutOutages'][] = $reportResult;
                }
            }
            $downtimeReport['hostsWithOutages'] = Hash::sort(
                $downtimeReport['hostsWithOutages'],
                '{n}.Host.reportdata.1',
                'desc'
            );

            $hostBarChartData = [];
            $pieChartData = [];
            $downtimeReport['hostsWithOutages'] = array_chunk($downtimeReport['hostsWithOutages'], 10);
            if (!empty($downtimeReport['hostsWithOutages'])) {
                $hostBarChartData = DowntimeReportBarChartWidgetDataPreparer::getDataForHostBarChart($downtimeReport['hostsWithOutages'], $totalTime);
                foreach ($downtimeReport['hostsWithOutages'] as $chunkKey => $hostsArray) {

                    foreach($hostsArray as $key => $hostData){
                        $downtimeReport['hostsWithOutages'][$chunkKey][$key]['Host']['pieChartData'] = DowntimeReportPieChartWidgetDataPreparer::getDataForHostPieChartWidget(
                            $hostData,
                            $totalTime,
                            $UserTime
                        );
                        FileDebugger::varExport($downtimeReport['hostsWithOutages'][$chunkKey][$key]['Host']['pieChartData']);

                        foreach($hostData['Services'] as $uuid => $service){
                            $downtimeReport['hostsWithOutages'][$chunkKey][$key]['Services'][$uuid]['pieChartData'] = DowntimeReportPieChartWidgetDataPreparer::getDataForServicePieChart(
                                $service['Service']['reportData'],
                                $totalTime
                            );
                        }
                    }
                }
            }

            $this->set('downtimes', $downtimes);
            $this->set('_serialize', ['downtimes']);

        }

        return;
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $userContainerIds = $ContainersTable->resolveChildrenOfContainerIds(
            $this->MY_RIGHTS,
            $this->hasRootPrivileges);
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        $timeperiods = $TimeperiodsTable->find('list')
            ->where(['Timeperiods.container_id IN' => $userContainerIds])
            ->toArray();

        $downtimeServices = [];
        $downtimeHosts = [];
        $downtimeReportData = [];
        $hostsUuids = array_keys($this->Host->hostsByContainerId($userContainerIds, 'all', [], 'uuid'));
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Downtimereport->set($this->request->data);
            if ($this->Downtimereport->validates()) {
                $downtimes = ['Hosts' => [], 'Services' => []];
                $startDate = $this->request->data('Downtimereport.start_date') . ' 00:00:00';
                $endDate = $this->request->data('Downtimereport.end_date') . ' 23:59:59';
                $downtimeReportDetails = [
                    'startDate' => $startDate,
                    'endDate'   => $endDate,
                ];

                $startTimeStamp = strtotime($startDate);
                $endTimeStamp = strtotime($endDate);

                $downtimeReportDetails = [
                    'startDate' => $startDate,
                    'endDate'   => $endDate,
                ];
                $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
                $timeperiod = $TimeperiodsTable->find()
                    ->where(['id' => $this->request->data('Downtimereport.timeperiod_id')])
                    ->contain('TimeperiodTimeranges')
                    ->first()
                    ->toArray();

                $DowntimeHostConditions = new DowntimeHostConditions();
                $DowntimeHostConditions->setOrder(['DowntimeHost.scheduled_start_time' => 'asc']);
                $DowntimeHostConditions->setFrom($startTimeStamp);
                $DowntimeHostConditions->setTo($endTimeStamp);
                $DowntimeHostConditions->setHostUuid($hostsUuids);
                $query = $this->DowntimeHost->getQueryForReporting($DowntimeHostConditions);
                $downtimes['Hosts'] = $this->DowntimeHost->find('all', $query);
                if ($this->request->data('Downtimereport.evaluationMethod') == 'DowntimereportService') {
                    $DowntimeServiceConditions = new DowntimeServiceConditions();

                    $DowntimeServiceConditions->setOrder(['DowntimeService.scheduled_start_time' => 'asc']);
                    $DowntimeServiceConditions->setFrom($startTimeStamp);
                    $DowntimeServiceConditions->setTo($endTimeStamp);
                    $DowntimeServiceConditions->setHostUuid($hostsUuids);

                    $query = $this->DowntimeService->getQueryForReporting($DowntimeServiceConditions);
                    $downtimes['Services'] = $this->DowntimeService->find('all', $query);
                }

                if (!empty($downtimes['Hosts']) || !empty($downtimes['Services'])) {
                    $timeSlices = $this->Downtimereport->createDateRanges(
                        $this->request->data('Downtimereport.start_date'),
                        $this->request->data('Downtimereport.end_date'),
                        $timeperiod['timeperiod_timeranges']
                    );
                    unset($timeperiod);
                    $totalTime = Hash::apply(Hash::map($timeSlices, '{n}', ['Downtimereport', 'calculateTotalTime']), '{n}', 'array_sum');
                    $downtimeReportDetails['totalTime'] = $totalTime;

                    $hostUuids = array_unique(
                        Hash::merge(
                            Hash::extract($downtimes['Hosts'], '{n}.Host.uuid'),
                            Hash::extract($downtimes['Services'], '{n}.Host.uuid')
                        )
                    );

                    foreach ($downtimes['Hosts'] as $downtimeHost) {
                        $downtimeHosts[$downtimeHost['Host']['uuid']][] = $downtimeHost['DowntimeHost'];
                    }
                    foreach ($hostUuids as $hostUuid) {
                        $host = $this->Host->find('first', [
                            'recursive'  => -1,
                            'conditions' => [
                                'Host.uuid' => $hostUuid,
                            ],
                            'fields'     => [
                                'Host.id',
                                'Host.uuid',
                                'Host.name',
                                'Host.description',
                                'Host.address'
                            ],
                            'condition'  => [
                                'Host.uuid' => $hostUuid
                            ]
                        ]);
                        if (!empty($host)) {
                            $HostConditions = new StatehistoryHostConditions();
                            $HostConditions->setOrder(['StatehistoryHost.state_time' => 'asc']);
                            if ($this->request->data('Downtimereport.check_hard_state')) {
                                $HostConditions->setHardStateTypeAndUpState(true);
                            }
                            $HostConditions->setFrom($startTimeStamp);
                            $HostConditions->setTo($endTimeStamp);
                            $HostConditions->setHostUuid($hostUuid);
                            $HostConditions->setUseLimit(false);

                            //Query state history records for hosts
                            $query = $this->StatehistoryHost->getQuery($HostConditions);
                            $stateHistoryWithObject = $this->StatehistoryHost->find('all', $query);

                            if (empty($stateHistoryWithObject)) {
                                //Host has no state history record for selected time range
                                //Get last available state history record for this host
                                $query = $this->StatehistoryHost->getLastRecord($HostConditions);
                                $record = $this->StatehistoryHost->find('first', $query);
                                if (!empty($record)) {
                                    $record['StatehistoryHost']['state_time'] = $startTimeStamp;
                                    $StatehistoryHost = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryHost($record['StatehistoryHost']);
                                    $stateHistoryWithObject = $StatehistoryHost->toArray();
                                }
                            }

                            if (!empty($stateHistoryWithObject)) {
                                $downtimeReportData['Hosts'][$hostUuid] = $this->Downtimereport->generateDowntimereportData(
                                    $timeSlices,
                                    $stateHistoryWithObject,
                                    $this->request->data('Downtimereport.check_hard_state'),
                                    true
                                );
                                $downtimeReportData['Hosts'][$hostUuid] = Hash::insert(
                                    $downtimeReportData['Hosts'][$hostUuid],
                                    'Host',
                                    [
                                        'id'          => $host['Host']['id'],
                                        'name'        => $host['Host']['name'],
                                        'description' => $host['Host']['description'],
                                        'address'     => $host['Host']['address'],
                                    ]
                                );
                                //add host name to downtime array
                                if (array_key_exists($hostUuid, $downtimeHosts)) {
                                    $downtimeHosts = Hash::insert($downtimeHosts,
                                        $hostUuid . '.{n}.data',
                                        [
                                            'host' => $host['Host']['name'],
                                        ]
                                    );
                                }
                                unset($stateHistoryWithObject);

                            } else {
                                $downtimeReportData['Hosts'][$hostUuid]['HostsNotMonitored'] = $host;
                                unset($downtimeHosts[$hostUuid]);
                            }
                        }
                    }
                    foreach ($downtimes['Services'] as $downtimeService) {
                        $downtimeServices[$downtimeService['Service']['uuid']][] = $downtimeService['DowntimeService'];
                    }

                    $serviceUuids = array_unique(Hash::extract($downtimes['Services'], '{n}.Service.uuid'));
                    unset($downtimes);
                    foreach ($serviceUuids as $serviceUuid) {
                        $ServiceConditions = new StatehistoryServiceConditions();
                        $ServiceConditions->setOrder(['StatehistoryService.state_time' => 'asc']);
                        if ($this->request->data('Downtimereport.check_hard_state')) {
                            $ServiceConditions->setHardStateTypeAndUpState(true);
                        }

                        $ServiceConditions->setFrom($startTimeStamp);
                        $ServiceConditions->setTo($endTimeStamp);
                        $ServiceConditions->setServiceUuid($serviceUuid);
                        $ServiceConditions->setUseLimit(false);

                        //Query state history records for hosts
                        $query = $this->StatehistoryService->getQuery($ServiceConditions);
                        $stateHistoryWithObject = $this->StatehistoryService->find('all', $query);

                        if (empty($stateHistoryWithObject)) {
                            //Service has no state history record for selected time range
                            //Get last available state history record for this service
                            $query = $this->StatehistoryService->getLastRecord($ServiceConditions);
                            $record = $this->StatehistoryService->find('first', $query);
                            if (!empty($record)) {
                                $record['StatehistoryService']['state_time'] = $startTimeStamp;
                                $StatehistoryService = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryService($record['StatehistoryService']);
                                $stateHistoryWithObject = $StatehistoryService->toArray();
                            }
                        }

                        if (!empty($stateHistoryWithObject)) {
                            $service = $this->Service->find('first', [
                                'recursive'  => -1,
                                'contain'    => [
                                    'Servicetemplate' => [
                                        'fields' => [
                                            'Servicetemplate.id',
                                            'Servicetemplate.name'
                                        ]
                                    ],
                                    'Host'            => [
                                        'fields' => [
                                            'Host.uuid',
                                            'Host.name'
                                        ]
                                    ]
                                ],
                                'fields'     => [
                                    'Service.id',
                                    'Service.name'
                                ],
                                'conditions' => [
                                    'Service.uuid' => $serviceUuid
                                ]
                            ]);
                            if (!empty($service)) {
                                if (array_key_exists($serviceUuid, $downtimeServices)) {
                                    $downtimeServices = Hash::insert($downtimeServices,
                                        $serviceUuid . '.{n}.data',
                                        [
                                            'host'    => $service['Host']['name'],
                                            'service' => ($service['Service']['name']) ? $service['Service']['name'] : $service['Servicetemplate']['name'],
                                        ]
                                    );
                                }
                                $downtimeReportData['Hosts'][$service['Host']['uuid']]['Services'][$serviceUuid] = $this->Downtimereport->generateDowntimereportData(
                                    $timeSlices,
                                    $stateHistoryWithObject,
                                    $this->request->data('Downtimereport.check_hard_state'),
                                    false
                                );
                                $downtimeReportData['Hosts'][$service['Host']['uuid']]['Services'][$serviceUuid] = Hash::insert(
                                    $downtimeReportData['Hosts'][$service['Host']['uuid']]['Services'][$serviceUuid],
                                    'Service',
                                    [
                                        'id'              => $service['Service']['id'],
                                        'name'            => ($service['Service']['name']) ? $service['Service']['name'] : $service['Servicetemplate']['name'],
                                        'Servicetemplate' => [
                                            'id'   => $service['Servicetemplate']['id'],
                                            'name' => $service['Servicetemplate']['name'],
                                        ],
                                    ]
                                );
                            }
                            unset($stateHistoryWithObject);
                        } else {
                            unset($downtimeServices[$serviceUuid]);
                        }
                    }
                    if ($this->request->data('Downtimereport.report_format') == 'pdf') {
                        $this->Session->write('downtimeReportData', $downtimeReportData);
                        $this->Session->write('downtimeReportDetails', $downtimeReportDetails);
                        $this->redirect([
                            'action' => 'createPdfReport',
                            'ext'    => 'pdf',
                        ]);
                    } else {
                        //remove uuid as key from downtime array
                        $filteredHostsDowntimes = Hash::extract($downtimeHosts, '{s}.{n}');
                        $filteredServicesDowntimes = Hash::extract($downtimeServices, '{s}.{n}');
                        unset($downtimeHosts, $downtimeServices);
                        $this->Frontend->setJson('downtimeReportDetails', [
                            'startDate' => CakeTime::format(
                                $downtimeReportDetails['startDate'], '%Y, %m, %d', false, $this->Auth->user('timezone')
                            ),
                            'endDate'   => CakeTime::format(
                                $downtimeReportDetails['endDate'], '%Y, %m, %d', false, $this->Auth->user('timezone')
                            ),
                        ]);
                        $this->Frontend->setJson('hostDowntimes', array_map(
                                function ($filteredHostsDowntimes) {
                                    return [
                                        'author_name'          => $filteredHostsDowntimes['author_name'],
                                        'comment_data'         => $filteredHostsDowntimes['comment_data'],
                                        'host'                 => $filteredHostsDowntimes['data']['host'],
                                        'scheduled_start_time' => CakeTime::format(
                                            $filteredHostsDowntimes['scheduled_start_time'], '%Y %m %d %H:%M', false, $this->Auth->user('timezone')
                                        ),
                                        'scheduled_end_time'   => CakeTime::format(
                                            $filteredHostsDowntimes['scheduled_end_time'], '%Y %m %d %H:%M', false, $this->Auth->user('timezone')
                                        ),
                                    ];
                                },
                                $filteredHostsDowntimes
                            )
                        );
                        unset($filteredHostsDowntimes);
                        $this->Frontend->setJson('serviceDowntimes', array_map(
                                function ($filteredServicesDowntimes) {
                                    return [
                                        'author_name'          => $filteredServicesDowntimes['author_name'],
                                        'comment_data'         => $filteredServicesDowntimes['comment_data'],
                                        'host'                 => $filteredServicesDowntimes['data']['host'],
                                        'service'              => $filteredServicesDowntimes['data']['service'],
                                        'scheduled_start_time' => CakeTime::format(
                                            $filteredServicesDowntimes['scheduled_start_time'], '%Y %m %d %H:%M', false, $this->Auth->user('timezone')
                                        ),
                                        'scheduled_end_time'   => CakeTime::format(
                                            $filteredServicesDowntimes['scheduled_end_time'], '%Y %m %d %H:%M', false, $this->Auth->user('timezone')
                                        ),
                                    ];
                                },
                                $filteredServicesDowntimes
                            )
                        );
                        unset($filteredServicesDowntimes);
                        $this->set(compact(['downtimeReportData', 'downtimeReportDetails']));
                        $this->render('/Elements/load_downtime_report_data');
                    }
                } else {
                    $this->setFlash(
                        __('No downtimes within specified time found (' . $this->request->data('Downtimereport.start_date') . ' - ' . $this->request->data('Downtimereport.end_date') . ') !'),
                        'info'
                    );
                }
            }
        }
        $this->set(compact(['container', 'timeperiods', 'userContainerId']));
    }

    public
    function createPdfReport() {
        $this->set('downtimeReportData', $this->Session->read('downtimeReportData'));
        $this->set('downtimeReportDetails', $this->Session->read('downtimeReportDetails'));
        if ($this->Session->check('downtimeReportData')) {
            $this->Session->delete('downtimeReportData');
        }
        if ($this->Session->check('downtimeReportDetails')) {
            $this->Session->delete('downtimeReportDetails');
        }
        $binary_path = '/usr/bin/wkhtmltopdf';
        if (file_exists('/usr/local/bin/wkhtmltopdf')) {
            $binary_path = '/usr/local/bin/wkhtmltopdf';
        }
        $this->pdfConfig = [
            'engine'             => 'CakePdf.WkHtmlToPdf',
            'margin'             => [
                'bottom' => 15,
                'left'   => 0,
                'right'  => 0,
                'top'    => 15,
            ],
            'encoding'           => 'UTF-8',
            'download'           => true,
            'binary'             => $binary_path,
            'orientation'        => 'portrait',
            'filename'           => 'Downtimereport.pdf',
            'no-pdf-compression' => '*',
            'image-dpi'          => '900',
            'background'         => true,
            'no-background'      => false,
        ];
    }
}
