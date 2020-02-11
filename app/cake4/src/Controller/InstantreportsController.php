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

use App\Form\InstantreportForm;
use App\Lib\Exceptions\MissingDbBackendException;
use App\Lib\Interfaces\DowntimehistoryHostsTableInterface;
use App\Model\Table\ContainersTable;
use App\Model\Table\InstantreportsTable;
use App\Model\Table\SystemfailuresTable;
use App\Model\Table\TimeperiodsTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\DowntimeHostConditions;
use itnovum\openITCOCKPIT\Core\DowntimeServiceConditions;
use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\Reports\DaterangesCreator;
use itnovum\openITCOCKPIT\Core\Reports\DowntimesMerger;
use itnovum\openITCOCKPIT\Core\Reports\StatehistoryConverter;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\StatehistoryHostConditions;
use itnovum\openITCOCKPIT\Core\StatehistoryServiceConditions;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\Downtime;
use itnovum\openITCOCKPIT\Core\Views\StatehistoryHost;
use itnovum\openITCOCKPIT\Core\Views\StatehistoryService;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\InstantreportFilter;
use Statusengine2Module\Model\Entity\DowntimeHost;
use Statusengine2Module\Model\Table\StatehistoryHostsTable;
use Statusengine2Module\Model\Table\StatehistoryServicesTable;


/**
 * @property Instantreport $Instantreport
 * @property Host $Host
 * @property Service $Service
 * @property Timeperiod $Timeperiod
 * @property StatehistoryHost $StatehistoryHost
 * @property DowntimeHost $DowntimeHost
 * @property StatehistoryService $StatehistoryService
 * @property DbBackend $DbBackend
 * @property AppPaginatorComponent $Paginator
 */
class InstantreportsController extends AppController {

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }
        $InstantreportFilter = new InstantreportFilter($this->request);
        /** @var $InstantreportsTable InstantreportsTable */
        $InstantreportsTable = TableRegistry::getTableLocator()->get('Instantreports');

        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $InstantreportFilter->getPage());
        $MY_RIGHTS = [];
        if ($this->hasRootPrivileges === false) {
            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
            $MY_RIGHTS = $ContainersTable->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        }
        $instantreports = $InstantreportsTable->getInstantreportsIndex($InstantreportFilter, $PaginateOMat, $MY_RIGHTS);
        foreach ($instantreports as $index => $instantreport) {
            $instantreports[$index]['allowEdit'] = $this->isWritableContainer($instantreport['Instantreport']['container_id']);
        }
        $this->set('instantreports', $instantreports);
        $toJson = ['instantreports', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['instantreports', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }
        /** @var $InstantreportsTable InstantreportsTable */
        $InstantreportsTable = TableRegistry::getTableLocator()->get('Instantreports');
        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            $instantreport = $InstantreportsTable->newEmptyEntity();
            $instantreport = $InstantreportsTable->patchEntity($instantreport, $this->request->getData('Instantreport'));
            $InstantreportsTable->save($instantreport);
            if ($instantreport->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->serializeCake4ErrorMessage($instantreport);
                return;
            } else {
                //No errors
                $this->serializeCake4Id($instantreport);
            }
            $this->set('instantreport', $instantreport);
            $this->viewBuilder()->setOption('serialize', ['instantreport']);
        }
    }

    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $InstantreportsTable InstantreportsTable */
        $InstantreportsTable = TableRegistry::getTableLocator()->get('Instantreports');

        if (!$InstantreportsTable->existsById($id)) {
            throw new NotFoundException(__('Instant report not found'));
        }
        $instantreport = $InstantreportsTable->getInstantreportForEdit($id);
        if (!$this->allowedByContainerId($instantreport['Instantreport']['container_id'])) {
            $this->render403();
            return;
        }
        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return instant report information
            $this->set('instantreport', $instantreport);
            $this->viewBuilder()->setOption('serialize', ['instantreport']);
            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData('Instantreport');
            $instantreport = $InstantreportsTable->get($id);
            $instantreport = $InstantreportsTable->patchEntity($instantreport, $data);
            $InstantreportsTable->save($instantreport);
            if ($instantreport->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $instantreport->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            $this->set('instantreport', $instantreport);
            $this->viewBuilder()->setOption('serialize', ['instantreport']);
        }
    }

    public function generate($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $instantreportForm = new InstantreportForm();
        $instantreportForm->execute($this->request->getData());

        if (!empty($instantreportForm->getErrors())) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $instantreportForm->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $instantreportId = $this->request->getData('instantreport_id');
        $fromDate = strtotime($this->request->getData('from_date') . ' 00:00:00');
        $toDate = strtotime($this->request->getData('to_date') . ' 23:59:59');
        $instantReport = $this->createReport(
            $instantreportId,
            $fromDate,
            $toDate
        );


        if ($instantReport === null) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', [
                'no_data' => [
                    'empty' => __('No report data specified time found (%s - %s) !',
                        date('d.m.Y', $fromDate),
                        date('d.m.Y', $toDate)
                    )
                ]
            ]);
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $this->set('instantReport', $instantReport);
        $this->viewBuilder()->setOption('serialize', ['instantReport']);

    }


    /**
     * @param $instantReportId
     * @param $fromDate
     * @param $toDate
     * @return array|void
     * @throws MissingDbBackendException
     */
    private function createReport($instantReportId, $fromDate, $toDate) {
        $User = new User($this->getUser());
        $UserTime = new UserTime($User->getTimezone(), $User->getDateformat());
        $offset = $UserTime->getUserTimeToServerOffset();

        $reportData = [];
        /** @var $InstantreportsTable InstantreportsTable */
        $InstantreportsTable = TableRegistry::getTableLocator()->get('Instantreports');
        $MY_RIGHTS = [];
        if (!$this->hasRootPrivileges) {
            $MY_RIGHTS = $this->MY_RIGHTS;
        }
        if (!$InstantreportsTable->existsById($instantReportId)) {
            throw new NotFoundException(__('Instant report not found'));
        }
        $instantReport = $InstantreportsTable->getInstantreportByIdCake4($instantReportId);
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        $timeperiod = $TimeperiodsTable->getTimeperiodWithTimerangesById($instantReport->get('timeperiod_id'));
        if (empty($timeperiod['Timeperiod']['timeperiod_timeranges'])) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', [
                'timeperiod_id' => [
                    'empty' => 'There are no time frames defined. Time evaluation report data is not available for the selected period.'
                ]
            ]);
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $instantReportObjects = $InstantreportsTable->getHostsAndServicesByInstantreport($instantReport, $MY_RIGHTS);
        if (empty($instantReportObjects)) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', [
                'instantreport_objects' => [
                    'empty' => 'There are no elements for instant report available.'
                ]
            ]);
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $timeSlices = Hash::insert(
            DaterangesCreator::createDateRanges(
                $fromDate,
                $toDate,
                $timeperiod['Timeperiod']['timeperiod_timeranges']
            ), '{n}.is_downtime', false
        );
        $totalTime = Hash::apply(
            array_map(function ($timeSlice) {
                return $timeSlice['end'] - $timeSlice['start'];
            }, $timeSlices),
            '{n}',
            'array_sum'
        );

        $reportDetails = [
            'name'       => $instantReport->get('name'),
            'evaluation' => $instantReport->get('evaluation'),
            'type'       => $instantReport->get('type'),
            'summary'    => $instantReport->get('summary'),
            'totalTime'  => $totalTime,
            'from'       => $UserTime->format($fromDate - $offset),
            'to'         => $UserTime->format($toDate - $offset)
        ];
        $globalDowntimes = [];
        if ($instantReport->get('downtimes') === 1) {
            /** @var $SystemfailuresTable SystemfailuresTable */
            $SystemfailuresTable = TableRegistry::getTableLocator()->get('Systemfailures');
            $globalDowntimes = $SystemfailuresTable->getSystemfailuresForReporting(
                $fromDate,
                $toDate
            );
        }
        $evalutionTypes = [
            'only_hosts'        => 1,
            'host_and_services' => 2,
            'only_services'     => 3
        ];

        $instantReportObjects['Hosts'] = Hash::sort($instantReportObjects['Hosts'], '{n}.name', 'ASC');
        foreach ($instantReportObjects['Hosts'] as $hostId => $instantReportHostData) {
            $hostUuid = $instantReportHostData['uuid'];

            if ($reportDetails['evaluation'] !== $evalutionTypes['only_services'] ||
                ($reportDetails['evaluation'] === $evalutionTypes['only_services'] && !empty($instantReportHostData['Services']))) {
                $reportData[$hostUuid]['Host'] = [
                    'id'   => $instantReportHostData['id'],
                    'name' => $instantReportHostData['name']
                ];
            }
            if ($reportDetails['evaluation'] !== $evalutionTypes['only_services']) {
                if ($instantReport->get('downtimes') === 1) {
                    $DowntimeHostConditions = new DowntimeHostConditions();
                    $DowntimeHostConditions->setFrom($fromDate);
                    $DowntimeHostConditions->setTo($toDate);
                    $DowntimeHostConditions->setContainerIds($this->MY_RIGHTS);
                    $DowntimeHostConditions->includeCancelledDowntimes(false);
                    $DowntimeHostConditions->setOrder(['DowntimeHosts.scheduled_start_time' => 'ASC']);
                    $DowntimeHostConditions->setHostUuid($instantReportHostData['uuid']);
                    /** @var DowntimehistoryHostsTableInterface $DowntimehistoryHostsTable */
                    $DowntimehistoryHostsTable = $this->DbBackend->getDowntimehistoryHostsTable();
                    $instantReportObjects['Hosts'][$hostId]['downtimes'] = $DowntimehistoryHostsTable->getDowntimesForReporting(
                        $DowntimeHostConditions
                    );
                    $hostDowntimeFormatted = [];
                    /** @var  $hostDowntimeObject DowntimeHost */
                    foreach ($instantReportObjects['Hosts'][$hostId]['downtimes'] as $hostDowntimeObject) {
                        $hostDowntimeFormatted[] = [
                            'DowntimeHost' => [
                                'id'                   => $hostDowntimeObject->get('downtimehistory_id'),
                                'author_name'          => $hostDowntimeObject->get('author_name'),
                                'scheduled_start_time' => $hostDowntimeObject->get('scheduled_start_time')->i18nFormat(Time::UNIX_TIMESTAMP_FORMAT),
                                'scheduled_end_time'   => $hostDowntimeObject->get('scheduled_end_time')->i18nFormat(Time::UNIX_TIMESTAMP_FORMAT),
                                'comment_data'         => $hostDowntimeObject->get('comment_data'),
                                'was_started'          => true,
                                'was_cancelled'        => false
                            ]
                        ];
                    }
                    /** @var  $downtimes DowntimesMerger */
                    $downtimes = DowntimesMerger::mergeDowntimesWithSystemfailures(
                        'DowntimeHost',
                        $hostDowntimeFormatted,
                        $globalDowntimes
                    );
                    $downtimesAndSystemfailures = [];
                    foreach ($downtimes as $downtime) {
                        $DowntimeHost = new Downtime($downtime['DowntimeHost']);
                        $downtimesAndSystemfailures[] = [
                            'DowntimeHost' => $DowntimeHost->toArray()
                        ];
                    }

                    $downtimesFiltered = DaterangesCreator::mergeTimeOverlapping(
                        array_map(
                            function ($downtime) {
                                return [
                                    'start_time' => $downtime['DowntimeHost']['scheduledStartTime'],
                                    'end_time'   => $downtime['DowntimeHost']['scheduledEndTime'],
                                ];
                            },
                            $downtimesAndSystemfailures
                        )
                    );


                    $timeSlices = DaterangesCreator::setDowntimesInTimeslices(
                        $timeSlices,
                        $downtimesFiltered
                    );
                }
                //Process conditions
                /** @var $StatehistoryHostConditions StatehistoryHostConditions */
                $StatehistoryHostConditions = new StatehistoryHostConditions();
                $StatehistoryHostConditions->setOrder(['StatehistoryHosts.state_time' => 'asc']);
                if ($instantReport->get('reflection') === 2) { // type 2 hard state only
                    $StatehistoryHostConditions->setHardStateTypeAndUpState(true); // 1 => Hard State
                }
                $StatehistoryHostConditions->setFrom($fromDate);
                $StatehistoryHostConditions->setTo($toDate);
                $StatehistoryHostConditions->setHostUuid($instantReportHostData['uuid']);

                /** @var StatehistoryHostsTable $StatehistoryHostsTable */
                $StatehistoryHostsTable = $this->DbBackend->getStatehistoryHostsTable();

                /** @var \Statusengine2Module\Model\Entity\StatehistoryHost[] $statehistoriesHost */
                $statehistoriesHost = $StatehistoryHostsTable->getStatehistoryIndex($StatehistoryHostConditions);

                if (empty($statehistoriesHost)) {
                    $record = $StatehistoryHostsTable->getLastRecord($StatehistoryHostConditions);
                    if (!empty($record)) {
                        $statehistoriesHost[] = $record->set('state_time', $fromDate);
                    }
                }

                if (empty($statehistoriesHost)) {
                    $HoststatusTable = $this->DbBackend->getHoststatusTable();
                    $HoststatusFields = new HoststatusFields($this->DbBackend);
                    $HoststatusFields
                        ->currentState()
                        ->lastHardState()
                        ->isHardstate()
                        ->lastStateChange();
                    $hoststatus = $HoststatusTable->byUuid($instantReportHostData['uuid'], $HoststatusFields);
                    if (!empty($hoststatus)) {
                        /** @var Hoststatus $Hoststatus */
                        $Hoststatus = new Hoststatus($hoststatus['Hoststatus']);
                        if ($Hoststatus->getLastStateChange() <= $fromDate) {
                            $stateHistoryHostTmp = [
                                'StatehistoryHost' => [
                                    'state_time'      => $fromDate,
                                    'state'           => $Hoststatus->currentState(),
                                    'last_state'      => $Hoststatus->currentState(),
                                    'last_hard_state' => $Hoststatus->getLastHardState(),
                                    'state_type'      => (int)$Hoststatus->isHardState()
                                ]
                            ];

                            /** @var StatehistoryHost $StatehistoryHost */
                            $StatehistoryHost = new StatehistoryHost($stateHistoryHostTmp['StatehistoryHost']);
                            $statehistoriesHost[] = $StatehistoryHost;
                        }
                    }
                }

                foreach ($statehistoriesHost as $statehistoryHost) {
                    /** @var StatehistoryHostsTable|StatehistoryHost $statehistoryHost */
                    $StatehistoryHost = new StatehistoryHost($statehistoryHost->toArray());
                    $allStatehistories[] = $StatehistoryHost->toArray();
                }


                $reportData[$hostUuid]['Host']['reportData'] = StatehistoryConverter::generateReportData(
                    $timeSlices,
                    $allStatehistories,
                    ($instantReport->get('reflection') === 2),
                    true
                );

                $reportData[$hostUuid]['Host']['reportData']['percentage'] = StatehistoryConverter::getPercentageValues(
                    $reportData[$hostUuid]['Host']['reportData'],
                    $totalTime,
                    true
                );
            }
            if (!empty($instantReportHostData['Services'])) {
                $instantReportHostData['Services'] = Hash::sort($instantReportHostData['Services'], '{n}.name', 'ASC');
                foreach ($instantReportHostData['Services'] as $serviceId => $service) {
                    if ($instantReport->get('downtimes') === 1) {
                        $DowntimeServiceConditions = new DowntimeServiceConditions();
                        $DowntimeServiceConditions->setFrom($fromDate);
                        $DowntimeServiceConditions->setTo($toDate);
                        $DowntimeServiceConditions->setContainerIds($this->MY_RIGHTS);
                        $DowntimeServiceConditions->includeCancelledDowntimes(false);
                        $DowntimeServiceConditions->setOrder(['DowntimeServices.scheduled_start_time' => 'asc']);
                        $DowntimeServiceConditions->setServiceUuid($service['uuid']);
                        /** @var DowntimehistoryHostsTableInterface $DowntimehistoryHostsTable */
                        $DowntimehistoryServicesTable = $this->DbBackend->getDowntimehistoryServicesTable();
                        $instantReportObjects['Hosts'][$hostId]['Services'][$serviceId]['downtimes'] = $DowntimehistoryServicesTable->getDowntimesForReporting(
                            $DowntimeServiceConditions
                        );
                        $serviceDowntimeFormatted = [];
                        /** @var  $hostDowntimeObject DowntimeHost */
                        foreach ($instantReportObjects['Hosts'][$hostId]['Services'][$serviceId]['downtimes'] as $serviceDowntimeObject) {
                            $serviceDowntimeFormatted[] = [
                                'DowntimeService' => [
                                    'id'                   => $serviceDowntimeObject->get('downtimehistory_id'),
                                    'author_name'          => $serviceDowntimeObject->get('author_name'),
                                    'scheduled_start_time' => $serviceDowntimeObject->get('scheduled_start_time')->i18nFormat(Time::UNIX_TIMESTAMP_FORMAT),
                                    'scheduled_end_time'   => $serviceDowntimeObject->get('scheduled_end_time')->i18nFormat(Time::UNIX_TIMESTAMP_FORMAT),
                                    'comment_data'         => $serviceDowntimeObject->get('comment_data'),
                                    'was_started'          => true,
                                    'was_cancelled'        => false
                                ]
                            ];
                        }
                        /** @var  $downtimes DowntimesMerger */
                        $downtimes = DowntimesMerger::mergeDowntimesWithSystemfailures(
                            'DowntimeService',
                            $serviceDowntimeFormatted,
                            $globalDowntimes
                        );
                        $downtimesAndSystemfailures = [];
                        foreach ($downtimes as $downtime) {
                            $DowntimeService = new Downtime($downtime['DowntimeService']);
                            $downtimesAndSystemfailures[] = [
                                'DowntimeService' => $DowntimeService->toArray()
                            ];
                        }

                        $downtimesFiltered = DaterangesCreator::mergeTimeOverlapping(
                            array_map(
                                function ($downtime) {
                                    return [
                                        'start_time' => $downtime['DowntimeService']['scheduledStartTime'],
                                        'end_time'   => $downtime['DowntimeService']['scheduledEndTime'],
                                    ];
                                },
                                $downtimesAndSystemfailures
                            )
                        );


                        $timeSlices = DaterangesCreator::setDowntimesInTimeslices(
                            $timeSlices,
                            $downtimesFiltered
                        );
                    }
                    $serviceUuid = $service['uuid'];

                    /** @var $StatehistoryServiceConditions StatehistoryServiceConditions */
                    $StatehistoryServiceConditions = new StatehistoryServiceConditions();
                    $StatehistoryServiceConditions->setOrder(['StatehistoryServices.state_time' => 'asc']);
                    if ($instantReport->get('reflection') === 2) { // type 2 hard state only
                        $StatehistoryServiceConditions->setHardStateTypeAndOkState(true); // 1 => Hard State !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                    }
                    $StatehistoryServiceConditions->setFrom($fromDate);
                    $StatehistoryServiceConditions->setTo($toDate);
                    $StatehistoryServiceConditions->setServiceUuid($serviceUuid);

                    /** @var StatehistoryServicesTable $StatehistoryServicesTable */
                    $StatehistoryServicesTable = $this->DbBackend->getStatehistoryServicesTable();

                    /** @var \Statusengine2Module\Model\Entity\StatehistoryService[] $statehistoriesService */
                    $statehistoriesService = $StatehistoryServicesTable->getStatehistoryIndex($StatehistoryServiceConditions);

                    if (empty($statehistoriesService)) {
                        $record = $StatehistoryServicesTable->getLastRecord($StatehistoryServiceConditions);
                        if (!empty($record)) {
                            $statehistoriesService[] = $record->set('state_time', $fromDate);
                        }
                    }

                    $ServicestatusTable = $this->DbBackend->getServicestatusTable();
                    $ServicestatusFields = new ServicestatusFields($this->DbBackend);
                    $ServicestatusFields
                        ->currentState()
                        ->lastHardState()
                        ->isHardstate()
                        ->lastStateChange();
                    $servicestatus = $ServicestatusTable->byUuid($serviceUuid, $ServicestatusFields);
                    if (!empty($servicestatus)) {
                        $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($servicestatus['Servicestatus']);
                        if ($Servicestatus->getLastStateChange() <= $fromDate) {
                            $stateHistoryServiceTmp = [
                                'StatehistoryService' => [
                                    'state_time'      => $fromDate,
                                    'state'           => $Servicestatus->currentState(),
                                    'last_state'      => $Servicestatus->currentState(),
                                    'last_hard_state' => $Servicestatus->getLastHardState(),
                                    'state_type'      => (int)$Servicestatus->isHardState()
                                ]
                            ];

                            $StateHistoryService = new StatehistoryService($stateHistoryServiceTmp['StatehistoryService']);
                            $statehistoriesService[] = $StateHistoryService;
                        }
                    }

                    foreach ($statehistoriesService as $statehistoryService) {
                        /** @var StatehistoryServicesTable|StatehistoryService $statehistoryService */
                        $StatehistoryService = new StatehistoryService($statehistoryService->toArray());
                        $allStatehistories[] = $StatehistoryService->toArray();
                    }

                    $reportData[$hostUuid]['Host']['Services'][$serviceUuid]['Service']['name'] = $service['name'];
                    $reportData[$hostUuid]['Host']['Services'][$serviceUuid]['Service']['id'] = $service['id'];
                    $reportData[$hostUuid]['Host']['Services'][$serviceUuid]['Service']['reportData'] = StatehistoryConverter::generateReportData(
                        $timeSlices,
                        $allStatehistories,
                        ($instantReport->get('reflection') === 2),
                        false
                    );
                    $reportData[$hostUuid]['Host']['Services'][$serviceUuid]['Service']['reportData']['percentage'] = StatehistoryConverter::getPercentageValues(
                        $reportData[$hostUuid]['Host']['Services'][$serviceUuid]['Service']['reportData'],
                        $totalTime,
                        false
                    );
                }
            }
        }

        $instantReportData = $reportData;
        unset($reportData);
        $reportData['hosts'] = $instantReportData;
        $reportSummaryData = [];
        if ($instantReport->get('summary') === 1) {
            $hostsData = [];
            $servicesData = [];
            $reportSummaryData = [
                'summary_hosts'    => null,
                'summary_services' => null

            ];

            foreach ($reportData['hosts'] as $reportHostData) {
                if (!empty($reportHostData['Host']['reportData'])) {
                    $hostsData[] = [
                        $reportHostData['Host']['reportData'][0],
                        $reportHostData['Host']['reportData'][1],
                        $reportHostData['Host']['reportData'][2]
                    ];
                }
                if (!empty($reportHostData['Host']['Services'])) {
                    foreach ($reportHostData['Host']['Services'] as $serviceData) {
                        if (!empty($serviceData['Service']['reportData'])) {
                            $servicesData[] = [
                                $serviceData['Service']['reportData'][0],
                                $serviceData['Service']['reportData'][1],
                                $serviceData['Service']['reportData'][2],
                                $serviceData['Service']['reportData'][3],
                            ];
                        }
                    }
                }
            }
            if (!empty($hostsData)) {
                $stateSummary = StatehistoryConverter::hostStateSummary($hostsData);
                $reportSummaryData['summary_hosts']['reportData'] = $stateSummary;
                $reportSummaryData['summary_hosts']['reportData']['percentage'] = StatehistoryConverter::getPercentageValues(
                    $stateSummary,
                    array_sum($stateSummary),
                    true
                );
            }
            if (!empty($servicesData)) {
                $stateSummary = StatehistoryConverter::serviceStateSummary($servicesData);
                $reportSummaryData['summary_services']['reportData'] = $stateSummary;
                $reportSummaryData['summary_services']['reportData']['percentage'] = StatehistoryConverter::getPercentageValues(
                    $stateSummary,
                    array_sum($stateSummary),
                    false
                );
            }
            $reportData['hosts'] = [];
        }

        $reportData['reportDetails'] = array_merge($reportDetails, $reportSummaryData);
        return $reportData;
    }


    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $InstantreportsTable InstantreportsTable */
        $InstantreportsTable = TableRegistry::getTableLocator()->get('Instantreports');

        if (!$InstantreportsTable->exists($id)) {
            throw new NotFoundException(__('Instant report not found'));
        }

        $instantreport = $InstantreportsTable->getInstantreportById($id);
        if (!$this->allowedByContainerId(Hash::extract($instantreport, 'Instantreport.container_id'))) {
            $this->render403();
            return;
        }
        $instantreportEntity = $InstantreportsTable->get($id);
        if ($InstantreportsTable->delete($instantreportEntity)) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->response = $this->response->withStatus(500);
        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
        return;
    }

    /**
     * @throws MissingDbBackendException
     */
    public function createPdfReport() {
        $User = new User($this->getUser());
        $UserTime = UserTime::fromUser($User);
        $offset = $UserTime->getUserTimeToServerOffset();


        $requestData = $this->request->getQuery('data', []);
        $instantreportForm = new InstantreportForm();

        $instantreportForm->execute($requestData);
        $instantreportId = $requestData['instantreport_id'];

        if (!empty($instantreportForm->getErrors())) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $instantreportForm->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        if ($this->isJsonRequest()) {
            //Only validate parameters
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $fromDate = strtotime($this->request->getQuery('data.from_date', date('d.m.Y')) . ' 00:00:00');
        $toDate = strtotime($this->request->getQuery('data.to_date', date('d.m.Y')) . ' 23:59:59');
        $instantreportId = $this->request->getQuery('data.instantreport_id', 0);


        /** @var $InstantreportsTable InstantreportsTable */
        $InstantreportsTable = TableRegistry::getTableLocator()->get('Instantreports');
        if (!$InstantreportsTable->existsById($instantreportId)) {
            throw new NotFoundException('Instant report not found!');
        }

        $instantReport = $this->createReport(
            $instantreportId,
            $fromDate,
            $toDate
        );

        if ($instantReport === null) {
            $this->set('error', [
                'no_data' => [
                    'empty' => __('! No data within specified time found (%s - %s) !',
                        date('d.m.Y', $fromDate),
                        date('d.m.Y', $toDate)
                    )
                ]
            ]);
            $this->viewBuilder()->setOption('serialize', ['error']);
        }

        $this->set('fromDate', $fromDate - $offset);
        $this->set('toDate', $toDate - $offset);


        $this->set('instantReport', $instantReport);
        $this->set('UserTime', $UserTime);

        $this->viewBuilder()->setOption('pdfConfig', [
                'download' => false,
                'filename' => sprintf('InstantReport_%s.pdf', $instantReport['reportDetails']['name']) . date('dmY_his') . '.pdf'
            ]
        );
    }

    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
        }
        $containers = Api::makeItJavaScriptAble($containers);

        $this->set('containers', $containers);
        $this->viewBuilder()->setOption('serialize', ['containers']);
    }

    public function hostAvailabilityPieChart() {
        //Only ship HTML template
        return;
    }

    public function serviceAvailabilityPieChart() {
        //Only ship HTML template
        return;
    }

    public function serviceAvailabilityBarChart() {
        //Only ship HTML template
        return;
    }

}
