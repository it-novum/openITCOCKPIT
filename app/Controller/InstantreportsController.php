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


/**
 * @property Instantreport $Instantreport
 * @property Host          $Host
 * @property Service       $Service
 * @property Timeperiod    $Timeperiod
 */
class InstantreportsController extends AppController
{

    public $cronFromDate = '';
    public $cronToDate = '';
    public $cronPdfName = '';
    public $layout = 'Admin.default';
    public $components = [
        'Paginator',
        'RequestHandler',
    ];
    public $uses = [
        'Instantreport',
        'User',
        'Hostgroup',
        'Servicegroup',
        'Host',
        'Service',
        'Timeperiod',
    ];

    public function index(){
        $options = [
            'recursive' => -1,
            'order' => [
                'Instantreport.id' => 'desc',
            ],
            'conditions' => [
                'Instantreport.container_id' => $this->MY_RIGHTS,
                'Instantreport.send_email' => '0',
            ],
            'contain'    => [
                'Timeperiod.name'
            ],
        ];

        if ($this->isApiRequest()) {
            $allInstantReports = $this->Instantreport->find('all', $options);
        } else {
            $this->Paginator->settings = Hash::merge($this->Paginator->settings, $options);
            $allInstantReports = $this->Paginator->paginate();
        }

        if(empty($allInstantReports)){
            $allInstantReports = $this->Instantreport->find('all', [
                'recursive' => -1,
                'order' => [
                    'Instantreport.id' => 'desc',
                ],
                'conditions' => [
                    'Instantreport.container_id' => $this->MY_RIGHTS,
                    'Instantreport.send_email' => '1',
                ],
                'contain'    => [
                    'Timeperiod.name',
                    'User.firstname',
                    'User.lastname',
                ],
            ]);
            if(empty($allInstantReports)) {
                $this->redirect(['action' => 'add']);
            }else{
                $this->redirect(['action' => 'sendEmailsList']);
            }
        }

        $evaluations = $this->Instantreport->getEvaluations();
        $types = $this->Instantreport->getTypes();

        $this->set([
            'allInstantReports' => $allInstantReports,
            'evaluations' => $evaluations,
            'types' => $types
        ]);
    }

    public function sendEmailsList(){
        $options = [
            'recursive' => -1,
            'order' => [
                'Instantreport.id' => 'desc',
            ],
            'conditions' => [
                'Instantreport.container_id' => $this->MY_RIGHTS,
                'Instantreport.send_email' => '1',
            ],
            'contain'    => [
                'Timeperiod.name',
                'User.firstname',
                'User.lastname',
            ],
        ];

        if ($this->isApiRequest()) {
            $allInstantReports = $this->Instantreport->find('all', $options);
        } else {
            $this->Paginator->settings = Hash::merge($this->Paginator->settings, $options);
            $allInstantReports = $this->Paginator->paginate();
        }

        if(empty($allInstantReports)){
            $this->redirect(['action' => 'index']);
        }

        $evaluations = $this->Instantreport->getEvaluations();
        $types = $this->Instantreport->getTypes();
        $sendIntervals = $this->Instantreport->getSendIntervals();

        $this->set([
            'allInstantReports' => $allInstantReports,
            'evaluations' => $evaluations,
            'types' => $types,
            'sendIntervals' => $sendIntervals
        ]);

    }

    public function add() {
        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_INSTANTREPORT, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_INSTANTREPORT, [], $this->hasRootPrivileges);
        }
        //ContainerID => 1 for ROOT Container
        $userContainerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $timePeriods = $this->Timeperiod->timeperiodsByContainerId($userContainerIds, 'list');
        $hostgroups = $this->Hostgroup->hostgroupsByContainerId($userContainerIds, 'all');
        $servicegroups = $this->Servicegroup->servicegroupsByContainerId($userContainerIds, 'all');
        $usersToSend = $this->User->usersByContainerId($userContainerIds, 'all');
        $types = $this->Instantreport->getTypes();
        $evaluations = $this->Instantreport->getEvaluations();
        $reportFormats = $this->Instantreport->getReportFormats();
        $reflectionStates = $this->Instantreport->getReflectionStates();
        $sendIntervals = $this->Instantreport->getSendIntervals();

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->request->data['Instantreport']['send_email'] === '1' && isset($this->request->data['Instantreport']['User'])) {
                $this->request->data['User'] = $this->request->data['Instantreport']['User'];
            }
            if ($this->request->data['Instantreport']['send_email'] === '1' && isset($this->request->data['Instantreport']['User'])) {
                $this->request->data['User'] = $this->request->data['Instantreport']['User'];
            }
            $this->request->data['Service'] = $this->request->data['Host'] = $this->request->data['Servicegroup'] = $this->request->data['Hostgroup'] = [];
            if (isset($this->request->data['Instantreport']['Hostgroup']) && $this->request->data['Instantreport']['type'] == Instantreport::TYPE_HOSTGROUPS) {
                $this->request->data['Hostgroup'] = $this->request->data['Instantreport']['Hostgroup'];
            }
            if (isset($this->request->data['Instantreport']['Servicegroup']) && $this->request->data['Instantreport']['type'] == Instantreport::TYPE_SERVICEGROUPS) {
                $this->request->data['Servicegroup'] = $this->request->data['Instantreport']['Servicegroup'];
            }
            if (isset($this->request->data['Instantreport']['Host']) && $this->request->data['Instantreport']['type'] == Instantreport::TYPE_HOSTS) {
                $this->request->data['Host'] = $this->request->data['Instantreport']['Host'];
            }
            if (isset($this->request->data['Instantreport']['Service']) && $this->request->data['Instantreport']['type'] == Instantreport::TYPE_SERVICES) {
                $this->request->data['Service'] = $this->request->data['Instantreport']['Service'];
            }
            $this->Instantreport->set($this->request->data);
            if ($this->Instantreport->validates()) {
                $instantReportData = $this->Instantreport->data;
                $this->Instantreport->saveAll();
                if(isset($this->request->data['save_submit'])){
                    $this->setFlash(__('<a href="/instantreports/edit/%s">Instant Report</a> saved successfully', $this->Instantreport->id));
                    if($instantReportData['Instantreport']['send_email'] === '1'){
                        $this->redirect(['action' => 'sendEmailsList']);
                    }
                    $this->redirect(['action' => 'index']);
                }
            }
        }

        $hosts = $this->Host->hostsByContainerId($userContainerIds, 'all');
        $services = $this->Service->servicesByHostContainerIds($userContainerIds);

        $this->set([
            'evaluations' => $evaluations,
            'types' => $types,
            'reportFormats' => $reportFormats,
            'reflectionStates' => $reflectionStates,
            'sendIntervals' => $sendIntervals,
			'containers' => $containers,
            'timeperiods' => $timePeriods,
            'hostgroups' => $hostgroups,
            'servicegroups' => $servicegroups,
            'hosts' => $hosts,
            'services' => $services,
            'usersToSend' => $usersToSend
        ]);
    }

    public function edit($id = null){

        if (!$this->Instantreport->exists($id)) {
            throw new NotFoundException(__('Invalid Instant report'));
        }

        $instantReport = $this->Instantreport->findById($id);

        if(empty($instantReport)){
            throw new NotFoundException(__('Invalid Instant report'));
        }

        if (!$this->allowedByContainerId(Hash::extract($instantReport, 'Instantreport.container_id'))) {
            $this->render403();
            return;
        }

        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_INSTANTREPORT, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_INSTANTREPORT, [], $this->hasRootPrivileges);
        }
        //ContainerID => 1 for ROOT Container
        $userContainerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $timePeriods = $this->Timeperiod->timeperiodsByContainerId($userContainerIds, 'list');
        $hostgroups = $this->Hostgroup->hostgroupsByContainerId($userContainerIds, 'all');
        $servicegroups = $this->Servicegroup->servicegroupsByContainerId($userContainerIds, 'all');
        $usersToSend = $this->User->usersByContainerId($userContainerIds, 'all');
        $types = $this->Instantreport->getTypes();
        $evaluations = $this->Instantreport->getEvaluations();
        $reportFormats = $this->Instantreport->getReportFormats();
        $reflectionStates = $this->Instantreport->getReflectionStates();
        $sendIntervals = $this->Instantreport->getSendIntervals();

        $this->request->data = Hash::merge($instantReport, $this->request->data);
        unset($this->request->data['Container']);

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->request->data['Instantreport']['send_email'] === '1' && isset($this->request->data['Instantreport']['User'])) {
                $this->request->data['User'] = $this->request->data['Instantreport']['User'];
            }else{
                $this->request->data['User'] = [];
            }
            $this->request->data['Service'] = $this->request->data['Host'] = $this->request->data['Servicegroup'] = $this->request->data['Hostgroup'] = [];
            if (isset($this->request->data['Instantreport']['Hostgroup']) && $this->request->data['Instantreport']['type'] == Instantreport::TYPE_HOSTGROUPS) {
                $this->request->data['Hostgroup'] = $this->request->data['Instantreport']['Hostgroup'];
            }
            if (isset($this->request->data['Instantreport']['Servicegroup']) && $this->request->data['Instantreport']['type'] == Instantreport::TYPE_SERVICEGROUPS) {
                $this->request->data['Servicegroup'] = $this->request->data['Instantreport']['Servicegroup'];
            }
            if (isset($this->request->data['Instantreport']['Host']) && $this->request->data['Instantreport']['type'] == Instantreport::TYPE_HOSTS) {
                $this->request->data['Host'] = $this->request->data['Instantreport']['Host'];
            }
            if (isset($this->request->data['Instantreport']['Service']) && $this->request->data['Instantreport']['type'] == Instantreport::TYPE_SERVICES) {
                $this->request->data['Service'] = $this->request->data['Instantreport']['Service'];
            }
            $this->Instantreport->set($this->request->data);

            if ($this->Instantreport->validates()) {
                $instantReportData = $this->Instantreport->data;
                $this->Instantreport->saveAll();
                if(isset($this->request->data['save_submit']) || $instantReportData['Instantreport']['send_email'] === '1'){
                    $this->setFlash(__('<a href="/instantreports/edit/%s">Instant Report</a> modified successfully', $instantReportData['Instantreport']['id']));
                    if($instantReportData['Instantreport']['send_email'] === '1'){
                        $this->redirect(['action' => 'sendEmailsList']);
                    }
                    $this->redirect(['action' => 'index']);
                }
            }

        }

        $hosts = $this->Host->hostsByContainerId($userContainerIds, 'all');
        $services = $this->Service->servicesByHostContainerIds($userContainerIds);

        $this->set([
            'evaluations' => $evaluations,
            'types' => $types,
            'reportFormats' => $reportFormats,
            'reflectionStates' => $reflectionStates,
            'sendIntervals' => $sendIntervals,
            'containers' => $containers,
            'timeperiods' => $timePeriods,
            'hostgroups' => $hostgroups,
            'servicegroups' => $servicegroups,
            'hosts' => $hosts,
            'services' => $services,
            'usersToSend' => $usersToSend
        ]);
    }

    public function generate($id = null){
        $instantReport = $this->Instantreport->find('first', [
            'recursive' => -1,
            'conditions' => [
                'Instantreport.id' => $id,
            ],
            'contain' => [
                'Hostgroup.id',
                'Host.id',
                'Servicegroup.id',
                'Service.id'
            ],
        ]);

        if (empty($instantReport)) {
            throw new NotFoundException(__('Invalid Instant report'));
        }

        if(!empty($this->cronFromDate)) {
            $this->generateReport($instantReport, date('d.m.Y', $this->cronFromDate), date('d.m.Y', $this->cronToDate), Instantreport::FORMAT_PDF);
        }else{

            $options = [
                'recursive' => -1,
                'order' => [
                    'Instantreport.id' => 'desc',
                ],
                'conditions' => [
                    'Instantreport.container_id' => $this->MY_RIGHTS,
                ]
            ];

            $allInstantReports = $this->Instantreport->find('all', $options);

            if(empty($allInstantReports)){
                $this->redirect(['action' => 'add']);
            }

            $reportFormats = $this->Instantreport->getReportFormats();

            if ($this->request->is('post') || $this->request->is('put')) {
                $instantReport = $this->Instantreport->find('first', [
                    'recursive' => -1,
                    'conditions' => [
                        'Instantreport.id' => $this->request->data['Instantreport']['id'],
                    ],
                    'contain' => [
                        'Hostgroup.id',
                        'Host.id',
                        'Servicegroup.id',
                        'Service.id'
                    ],
                ]);

                if (empty($instantReport)) {
                    throw new NotFoundException(__('Invalid Instant report'));
                }

                if (!$this->allowedByContainerId(Hash::extract($instantReport, 'Instantreport.container_id'))) {
                    $this->render403();
                    return;
                }

                try{
                    if(!$this->checkDate($this->request->data['Instantreport']['start_date'])){
                        throw new Exception('From date has invalid format');
                    }

                    if(!$this->checkDate($this->request->data['Instantreport']['end_date'])){
                        throw new Exception('To date has invalid format');
                    }

                    if(strtotime($this->request->data['Instantreport']['end_date']) <= strtotime($this->request->data['Instantreport']['start_date'])){
                        throw new Exception('To date must be later than from date');
                    }

                    if(!array_key_exists($this->request->data['Instantreport']['report_format'], $reportFormats)){
                        throw new Exception('Invalid report format');
                    }

                    $this->generateReport($instantReport, $this->request->data['Instantreport']['start_date'], $this->request->data['Instantreport']['end_date'], $this->request->data['Instantreport']['report_format']);
                }catch (Exception $exx){
                    $this->setFlash(__($exx->getMessage()), false);
                }

            }

            $this->set([
                'id' => $id,
                'allInstantReports' => $allInstantReports,
                'reportFormats' => $reportFormats,
                'reportFormats' => $reportFormats
            ]);

        }

    }

    private function checkDate($date){ // d.m.Y
        $dateParts = explode('.', $date);
        if(count($dateParts) == 3 && is_numeric($dateParts[0]) && is_numeric($dateParts[1]) && is_numeric($dateParts[2])){
            return checkdate($dateParts[1], $dateParts[0], $dateParts[2]);
        }
        return false;
    }

    private function generateReport($instantReport, $baseStartDate, $baseEndDate, $reportFormat){
        $startDate = $baseStartDate .' 00:00:00';
        $endDate = $baseEndDate .' 23:59:59';
        $instantReportDetails = [
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ];
        $timeperiod = $this->Timeperiod->find('first', [
            'conditions' => [
                'Timeperiod.id' => $instantReport['Instantreport']['timeperiod_id'],
            ],
        ]);
        $timeSlicesGlobal = Hash::insert(
            $this->Instantreport->createDateRanges(
                $baseStartDate,
                $baseEndDate,
                $timeperiod['Timerange']),
            '{n}.is_downtime', false
        );

        $startDateSqlFormat = date('Y-m-d H:i:s', strtotime($startDate));
        $endDateSqlFormat = date('Y-m-d H:i:s', strtotime($endDate));

        $globalDowntimes = [];

        if ($instantReport['Instantreport']['downtimes'] === '1') {
            $this->loadModel('Systemfailure');
            $globalDowntimes = $this->Systemfailure->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'OR' => [
                        '"'.$startDateSqlFormat.'"
									BETWEEN Systemfailure.start_time
									AND Systemfailure.end_time',
                        '"'.$endDateSqlFormat.'"
									BETWEEN Systemfailure.start_time
									AND Systemfailure.end_time',
                        'Systemfailure.start_time BETWEEN "'.$startDateSqlFormat.'"
									AND "'.$endDateSqlFormat.'"',
                    ],
                ],
            ]);
            $globalDowntimes = ['Systemfailure' => Hash::extract($globalDowntimes, '{n}.Systemfailure')];
        }

        $this->loadModel(MONITORING_OBJECTS);
        $this->loadModel(MONITORING_STATEHISTORY);
        $this->Objects->bindModel([
            'hasMany' => [
                'Statehistory' => [
                    'className' => MONITORING_STATEHISTORY,
                ],
                'Downtime'     => [
                    'className'  => MONITORING_DOWNTIME,
                    'conditions' => [
                        'Downtime.was_cancelled' => '0',
                    ],
                ],
            ],
        ]);
        $totalTime = Hash::apply(Hash::map($timeSlicesGlobal, '{n}', ['Instantreport', 'calculateTotalTime']), '{n}', 'array_sum');
        $instantReportDetails['totalTime'] = $totalTime;

        $allHostsServices = $this->getAllHostsServices($instantReport);

        $instantReportDetails['onlyHosts'] = $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS;
        $instantReportDetails['onlyServices'] = $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_SERVICES;
        $instantReportDetails['summary'] = $instantReport['Instantreport']['summary'] === '1';
        $instantReportDetails['name'] = $instantReport['Instantreport']['name'];
        $instantReportData = [];

        if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS) {
            foreach ($allHostsServices as $hostId => $hostArr) {
                $downtimes = [];
                $stateHistoryWithObject = $this->Objects->find('all', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Host'         => [
                            'fields' => [
                                'id', 'name',
                            ],
                        ],
                        'Statehistory' => [
                            'fields'     => [
                                'object_id', 'state_time', 'state', 'state_type', 'last_state', 'last_hard_state',
                            ],
                            'conditions' => [
                                'Statehistory.state_time
                                        BETWEEN "' . $startDateSqlFormat . '"
                                        AND "' . $endDateSqlFormat . '"',
                            ],
                            'order'      => [
                                'Statehistory.state_time',
                            ],
                        ],
                        'Downtime'     => [
                            'fields'     => [
                                'downtimehistory_id', 'scheduled_start_time AS start_time', 'scheduled_end_time AS end_time',
                            ],
                            'conditions' => [
                                'OR' => [
                                    '"'.$startDateSqlFormat.'"
											BETWEEN Downtime.scheduled_start_time
											AND Downtime.scheduled_end_time',
                                    '"'.$endDateSqlFormat.'"
											BETWEEN Downtime.scheduled_start_time
											AND Downtime.scheduled_end_time',
                                    'Downtime.scheduled_start_time BETWEEN "'.$startDateSqlFormat.'"
											AND "'.$endDateSqlFormat.'"',
                                ],
                            ],
                        ],
                    ],
                    'conditions' => [
                        'Objects.name1' => $hostArr['uuid'],
                    ],
                ]);

                if (!empty($stateHistoryWithObject)) {
                    if(empty($stateHistoryWithObject[0]['Statehistory'])){
                        $stateHistoryWithPrev = $this->Statehistory->find('first', [
                            'recursive' => -1,
                            'fields' => ['Statehistory.object_id', 'Statehistory.state_time', 'Statehistory.state', 'Statehistory.state_type', 'Statehistory.last_state', 'Statehistory.last_hard_state'],
                            'conditions' => [
                                'AND' => [
                                    'Statehistory.object_id' => $stateHistoryWithObject[0]['Objects']['object_id'],
                                    'Statehistory.state_time <= "'.$startDateSqlFormat.'"'
                                ],
                            ],
                            'order' => ['Statehistory.state_time' => 'DESC'],

                        ]);
                    }
                    if(!empty($stateHistoryWithPrev)){
                        $stateHistoryWithObject[0]['Statehistory'][0] = $stateHistoryWithPrev['Statehistory'];
                    }
//                    debug($stateHistoryWithObject);exit;
                    if ($instantReport['Instantreport']['downtimes'] !== '1') {
                        $timeSlices = $timeSlicesGlobal;
                    } else {
                        $downtimes = Hash::sort(
                            Hash::filter(
                                array_merge(
                                    $globalDowntimes['Systemfailure'],
                                    $stateHistoryWithObject[0]['Downtime']
                                )
                            ), '{n}.start_time', 'ASC'
                        );
                        if (!empty($downtimes)) {
                            $downtimesFiltered = $this->Instantreport->mergeTimeOverlapping(
                                array_map(
                                    function ($downtimes) {
                                        return [
                                            'start_time' => strtotime($downtimes['start_time']),
                                            'end_time'   => strtotime($downtimes['end_time']),
                                        ];
                                    },
                                    $downtimes
                                )
                            );
                            $timeSlices = $this->Instantreport->setDowntimesInTimeslices(
                                $timeSlicesGlobal,
                                $downtimesFiltered
                            );
                            unset($downtimesFiltered);
                        } else {
                            $timeSlices = $timeSlicesGlobal;
                        }
                    }
                    $instantReportData['Hosts'][$hostArr['uuid']] = $this->Instantreport->generateInstantreportData(
                        $totalTime,
                        $timeSlices,
                        $stateHistoryWithObject,
                        $instantReport['Instantreport']['reflection'] == Instantreport::STATE_HARD_ONLY,
                        true
                    );
                    $instantReportData['Hosts'][$hostArr['uuid']] = Hash::insert(
                        $instantReportData['Hosts'][$hostArr['uuid']],
                        'Host',
                        [
                            'name' => $stateHistoryWithObject[0]['Host']['name'],
                        ]
                    );
                    unset($timeSlices, $stateHistoryWithObject);
                } else {
                    $instantReportData['Hosts'][$hostArr['uuid']]['HostsNotMonitored'] = $this->Host->find('list', [
                        'conditions' => [
                            'Host.uuid' => $hostArr['uuid'],
                        ],
                    ]);
                }
            }
        } elseif ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS_SERVICES ||
            $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_SERVICES) {

            foreach ($allHostsServices as $hostId => $hostArr) {
                $downtimes = [];

                $stateHistoryWithObject = $this->Objects->find('all', [
                    'recursive' => -1,
                    'contain' => [
                        'Host' => [
                            'fields' => [
                                'id', 'name',
                            ],
                        ],
                        'Statehistory' => [
                            'fields' => [
                                'object_id', 'state_time', 'state', 'state_type', 'last_state', 'last_hard_state',
                            ],
                            'conditions' => [
                                'Statehistory.state_time
                                        BETWEEN "' . $startDateSqlFormat . '"
                                        AND "' . $endDateSqlFormat . '"',
                            ],
                            'order' => [
                                'Statehistory.state_time',
                            ],
                        ],
                        'Downtime' => [
                            'fields' => [
                                'downtimehistory_id', 'scheduled_start_time AS start_time', 'scheduled_end_time AS end_time',
                            ],
                            'conditions' => [
                                'OR' => [
                                    '"' . $startDateSqlFormat . '"
                                            BETWEEN Downtime.scheduled_start_time
                                            AND Downtime.scheduled_end_time',
                                    '"' . $endDateSqlFormat . '"
                                            BETWEEN Downtime.scheduled_start_time
                                            AND Downtime.scheduled_end_time',
                                    'Downtime.scheduled_start_time BETWEEN "' . $startDateSqlFormat . '"
                                            AND "' . $endDateSqlFormat . '"',
                                ],
                            ],
                        ],
                    ],
                    'conditions' => [
                        'Objects.name1' => $hostArr['uuid'],
                    ],
                ]);
                if (!empty($stateHistoryWithObject)) {
                    if(empty($stateHistoryWithObject[0]['Statehistory'])){
                        $stateHistoryWithPrev = $this->Statehistory->find('first', [
                            'recursive' => -1,
                            'fields' => ['Statehistory.object_id', 'Statehistory.state_time', 'Statehistory.state', 'Statehistory.state_type', 'Statehistory.last_state', 'Statehistory.last_hard_state'],
                            'conditions' => [
                                'AND' => [
                                    'Statehistory.object_id' => $stateHistoryWithObject[0]['Objects']['object_id'],
                                    'Statehistory.state_time <= "'.$startDateSqlFormat.'"'
                                ],
                            ],
                            'order' => ['Statehistory.state_time' => 'DESC'],

                        ]);
                    }
                    if(!empty($stateHistoryWithPrev)){
                        $stateHistoryWithObject[0]['Statehistory'][0] = $stateHistoryWithPrev['Statehistory'];
                    }
                    if ($instantReport['Instantreport']['downtimes'] !== '1') {
                        $timeSlices = $timeSlicesGlobal;
                    } else {
                        $downtimes = Hash::sort(
                            Hash::filter(
                                array_merge(
                                    $globalDowntimes['Systemfailure'],
                                    $stateHistoryWithObject[0]['Downtime']
                                )
                            ), '{n}.start_time', 'ASC'
                        );
                        if (!empty($downtimes)) {
                            $downtimesFiltered = $this->Instantreport->mergeTimeOverlapping(
                                array_map(
                                    function ($downtimes) {
                                        return [
                                            'start_time' => strtotime($downtimes['start_time']),
                                            'end_time' => strtotime($downtimes['end_time']),
                                        ];
                                    },
                                    $downtimes
                                )
                            );
                            $timeSlices = $this->Instantreport->setDowntimesInTimeslices(
                                $timeSlicesGlobal,
                                $downtimesFiltered
                            );
                            unset($downtimesFiltered);
                        } else {
                            $timeSlices = $timeSlicesGlobal;
                        }
                    }
                    $instantReportData['Hosts'][$hostArr['uuid']] = $this->Instantreport->generateInstantreportData(
                        $totalTime,
                        $timeSlices,
                        $stateHistoryWithObject,
                        $instantReport['Instantreport']['reflection'] == Instantreport::STATE_HARD_ONLY,
                        true
                    );
                    $instantReportData['Hosts'][$hostArr['uuid']] = Hash::insert(
                        $instantReportData['Hosts'][$hostArr['uuid']],
                        'Host',
                        [
                            'name' => $stateHistoryWithObject[0]['Host']['name'],
                        ]
                    );
                    unset($timeSlices, $stateHistoryWithObject);
                } else {
                    $instantReportData['Hosts'][$hostArr['uuid']]['HostsNotMonitored'] = $this->Host->find('list', [
                        'conditions' => [
                            'Host.uuid' => $hostArr['uuid'],
                        ],
                    ]);
                }

                if (isset($hostArr['Service'])){
                    foreach ($hostArr['Service'] as $serviceId => $serviceUuid) {
                        $downtimes = [];
                        $stateHistoryWithObject = $this->Objects->find('all', [
                            'recursive' => -1,
                            'contain' => [
                                'Service' => [
                                    'Host' => [
                                        'fields' => [
                                            'id', 'name',
                                        ],
                                    ],
                                    'Servicetemplate' => [
                                        'fields' => [
                                            'id', 'name',
                                        ],
                                    ],
                                    'fields' => [
                                        'id', 'name',
                                    ],
                                ],
                                'Statehistory' => [
                                    'fields' => [
                                        'object_id', 'state_time', 'state', 'state_type', 'last_state', 'last_hard_state',
                                    ],
                                    'conditions' => [
                                        'Statehistory.state_time
                                        BETWEEN "' . $startDateSqlFormat . '"
                                        AND "' . $endDateSqlFormat . '"',
                                    ],
                                ],
                                'Downtime' => [
                                    'fields' => [
                                        'downtimehistory_id', 'scheduled_start_time AS start_time', 'scheduled_end_time AS end_time', 'author_name', 'comment_data',
                                    ],
                                    'conditions' => [
                                        'OR' => [
                                            '"' . $startDateSqlFormat . '"
                                                    BETWEEN Downtime.scheduled_start_time
                                                    AND Downtime.scheduled_end_time',
                                            '"' . $endDateSqlFormat . '"
                                                    BETWEEN Downtime.scheduled_start_time
                                                    AND Downtime.scheduled_end_time',
                                            'Downtime.scheduled_start_time BETWEEN "' . $startDateSqlFormat . '"
                                                    AND "' . $endDateSqlFormat . '"',
                                        ],
                                    ],
                                ],
                            ],
                            'conditions' => [
                                'Objects.name2' => $serviceUuid,
                            ],
                        ]);

                        if (!empty($stateHistoryWithObject)) {
                            if(empty($stateHistoryWithObject[0]['Statehistory'])){
                                $stateHistoryWithPrev = $this->Statehistory->find('first', [
                                    'recursive' => -1,
                                    'fields' => ['Statehistory.object_id', 'Statehistory.state_time', 'Statehistory.state', 'Statehistory.state_type', 'Statehistory.last_state', 'Statehistory.last_hard_state'],
                                    'conditions' => [
                                        'AND' => [
                                            'Statehistory.object_id' => $stateHistoryWithObject[0]['Objects']['object_id'],
                                            'Statehistory.state_time <= "'.$startDateSqlFormat.'"'
                                        ],
                                    ],
                                    'order' => ['Statehistory.state_time' => 'DESC'],

                                ]);
                            }
                            if(!empty($stateHistoryWithPrev)){
                                $stateHistoryWithObject[0]['Statehistory'][0] = $stateHistoryWithPrev['Statehistory'];
                            }
                            if ($instantReport['Instantreport']['downtimes'] !== '1') {
                                $timeSlices = $timeSlicesGlobal;
                            } else {
                                $downtimes = Hash::sort(
                                    Hash::filter(
                                        array_merge(
                                            $globalDowntimes['Systemfailure'],
                                            $stateHistoryWithObject[0]['Downtime']
                                        )
                                    ), '{n}.start_time', 'ASC'
                                );
                                if (!empty($downtimes)) {
                                    $downtimesFiltered = $this->Instantreport->mergeTimeOverlapping(
                                        array_map(
                                            function ($downtimes) {
                                                return [
                                                    'start_time' => strtotime($downtimes['start_time']),
                                                    'end_time' => strtotime($downtimes['end_time']),
                                                ];
                                            },
                                            $downtimes
                                        )
                                    );
                                    $timeSlices = $this->Instantreport->setDowntimesInTimeslices(
                                        $timeSlicesGlobal,
                                        $downtimesFiltered
                                    );
                                    unset($downtimesFiltered);
                                } else {
                                    $timeSlices = $timeSlicesGlobal;
                                }
                            }
                            $instantReportData['Hosts'][$hostArr['uuid']]['Services'][$serviceUuid] = $this->Instantreport->generateInstantreportData(
                                $totalTime,
                                $timeSlices,
                                $stateHistoryWithObject,
                                $instantReport['Instantreport']['reflection'] == Instantreport::STATE_HARD_ONLY,
                                false
                            );

                            $instantReportData['Hosts'][$hostArr['uuid']]['Services'][$serviceUuid] = Hash::insert(
                                $instantReportData['Hosts'][$hostArr['uuid']]['Services'][$serviceUuid],
                                'Service',
                                [
                                    'name' => ($stateHistoryWithObject[0]['Service']['name']) ? $stateHistoryWithObject[0]['Service']['name'] : $stateHistoryWithObject[0]['Service']['Servicetemplate']['name'],
                                ]
                            );
                            unset($timeSlices, $stateHistoryWithObject);
                        } else {
                            $instantReportData['Hosts'][$hostArr['uuid']]['Services']['ServicesNotMonitored'][$serviceUuid] = $this->Service->find('first', [
                                'recursive' => -1,
                                'contain' => [
                                    'Host' => [
                                        'fields' => 'Host.name',
                                    ],
                                    'Servicetemplate' => [
                                        'fields' => 'Servicetemplate.name',
                                    ],
                                ],
                                'conditions' => [
                                    'Service.uuid' => $serviceUuid,
                                ],
                                'fields' => [
                                    'Service.name',
                                ],
                            ]);
                        }

                    }
                }
            }
        }

        if ($reportFormat == Instantreport::FORMAT_PDF) {
            if(empty($this->cronFromDate)) {
                $this->Session->write('instantReportData', $instantReportData);
                $this->Session->write('instantReportDetails', $instantReportDetails);
                $this->redirect([
                    'action' => 'createPdfReport',
                    'ext' => 'pdf',
                ]);
            }else{

                $binary_path = '/usr/bin/wkhtmltopdf';
                if (file_exists('/usr/local/bin/wkhtmltopdf')) {
                    $binary_path = '/usr/local/bin/wkhtmltopdf';
                }
                $CakePdf = new CakePdf([
                    'engine'             => 'CakePdf.WkHtmlToPdf',
                    'margin'             => [
                        'bottom' => 15,
                        'left'   => 0,
                        'right'  => 0,
                        'top'    => 15,
                    ],
                    'encoding'           => 'UTF-8',
                    'download'           => false,
                    'binary'             => $binary_path,
                    'orientation'        => 'portrait',
                    'filename'           => sprintf('InstantReport_%s.pdf', $instantReport['Instantreport']['name']),
                    'no-pdf-compression' => '*',
                    'image-dpi'          => '900',
                    'background'         => true,
                    'no-background'      => false,
                ]);

                $CakePdf->_engineClass->binary = $binary_path;
                $CakePdf->viewVars(['instantReportData' => $instantReportData, 'instantReportDetails' => $instantReportDetails]);
                $CakePdf->template('create_pdf_report');
                $pdf = $CakePdf->write($this->cronPdfName);

            }
        } else {

            $this->set(compact(['instantReportData', 'instantReportDetails']));
            $this->render('/Elements/load_instant_report_data');
        }
    }

    private function getAllHostsServices($instantReport){
        $fields = $joins = $conditions = [];
        switch($instantReport['Instantreport']['type'].'-'.$instantReport['Instantreport']['evaluation']){
            case Instantreport::TYPE_HOSTGROUPS.'-'.Instantreport::EVALUATION_HOSTS:
                $fields = [
                    'Host.id AS host_id',
                    'Host.uuid AS host_uuid'
                ];
                $instantReportKey = 'Hostgroup';
                $inCondition = 'Host2Hostgroup.hostgroup_id';
                $joins[] = [
                    'table' => 'hosts_to_hostgroups',
                    'alias' => 'Host2Hostgroup',
                    'type' => 'INNER',
                    'conditions' => 'Host.id = Host2Hostgroup.host_id'
                ];
                break;

            case Instantreport::TYPE_HOSTS.'-'.Instantreport::EVALUATION_HOSTS:
                $fields = [
                    'Host.id AS host_id',
                    'Host.uuid AS host_uuid'
                ];
                $instantReportKey = 'Host';
                $inCondition = 'Host.id';
                break;

            case Instantreport::TYPE_SERVICEGROUPS.'-'.Instantreport::EVALUATION_HOSTS:
                $fields = [
                    'Host.id AS host_id',
                    'Host.uuid AS host_uuid'
                ];
                $instantReportKey = 'Servicegroup';
                $inCondition = 'Service2Servicegroup.servicegroup_id';
                $joins[] = [
                    'table' => 'services',
                    'alias' => 'Service',
                    'type' => 'INNER',
                    'conditions' => 'Host.id = Service.host_id'
                ];
                $joins[] = [
                    'table' => 'services_to_servicegroups',
                    'alias' => 'Service2Servicegroup',
                    'type' => 'INNER',
                    'conditions' => 'Service.id = Service2Servicegroup.service_id'
                ];
                break;

            case Instantreport::TYPE_SERVICES.'-'.Instantreport::EVALUATION_HOSTS:
                $fields = [
                    'Host.id AS host_id',
                    'Host.uuid AS host_uuid'
                ];
                $instantReportKey = 'Service';
                $inCondition = 'Service.id';
                $joins[] = [
                    'table' => 'services',
                    'alias' => 'Service',
                    'type' => 'INNER',
                    'conditions' => 'Host.id = Service.host_id'
                ];
                break;

            case Instantreport::TYPE_HOSTGROUPS.'-'.Instantreport::EVALUATION_HOSTS_SERVICES:
                $fields = [
                    'Host.id AS host_id',
                    'Host.uuid AS host_uuid',
                    'Service.id AS service_id',
                    'Service.uuid AS service_uuid',
                    'Service.host_id AS service_host_id',
                ];
                $instantReportKey = 'Hostgroup';
                $inCondition = 'Host2Hostgroup.hostgroup_id';
                $joins[] = [
                    'table' => 'hosts_to_hostgroups',
                    'alias' => 'Host2Hostgroup',
                    'type' => 'INNER',
                    'conditions' => 'Host.id = Host2Hostgroup.host_id'
                ];
                $joins[] = [
                    'table' => 'services',
                    'alias' => 'Service',
                    'type' => 'INNER',
                    'conditions' => 'Host.id = Service.host_id'
                ];
                break;

            case Instantreport::TYPE_HOSTS.'-'.Instantreport::EVALUATION_HOSTS_SERVICES:
                $fields = [
                    'Host.id AS host_id',
                    'Host.uuid AS host_uuid',
                    'Service.id AS service_id',
                    'Service.uuid AS service_uuid',
                    'Service.host_id AS service_host_id',
                ];
                $instantReportKey = 'Host';
                $inCondition = 'Host.id';
                $joins[] = [
                    'table' => 'services',
                    'alias' => 'Service',
                    'type' => 'LEFT',
                    'conditions' => 'Host.id = Service.host_id'
                ];
                break;

            case Instantreport::TYPE_SERVICEGROUPS.'-'.Instantreport::EVALUATION_HOSTS_SERVICES:
                $fields = [
                    'Host.id AS host_id',
                    'Host.uuid AS host_uuid',
                    'Service.id AS service_id',
                    'Service.uuid AS service_uuid',
                    'Service.host_id AS service_host_id',
                ];
                $instantReportKey = 'Servicegroup';
                $inCondition = 'Service2Servicegroup.servicegroup_id';
                $joins[] = [
                    'table' => 'services',
                    'alias' => 'Service',
                    'type' => 'INNER',
                    'conditions' => 'Host.id = Service.host_id'
                ];
                $joins[] = [
                    'table' => 'services_to_servicegroups',
                    'alias' => 'Service2Servicegroup',
                    'type' => 'INNER',
                    'conditions' => 'Service.id = Service2Servicegroup.service_id'
                ];
                break;

            case Instantreport::TYPE_SERVICES.'-'.Instantreport::EVALUATION_HOSTS_SERVICES:
                $fields = [
                    'Host.id AS host_id',
                    'Host.uuid AS host_uuid',
                    'Service.id AS service_id',
                    'Service.uuid AS service_uuid',
                    'Service.host_id AS service_host_id',
                ];
                $instantReportKey = 'Service';
                $inCondition = 'Service.id';
                $joins[] = [
                    'table' => 'services',
                    'alias' => 'Service',
                    'type' => 'INNER',
                    'conditions' => 'Host.id = Service.host_id'
                ];
                break;

            case Instantreport::TYPE_HOSTGROUPS.'-'.Instantreport::EVALUATION_SERVICES:
                $fields = [
                    'Host.id AS host_id',
                    'Host.uuid AS host_uuid',
                    'Service.id AS service_id',
                    'Service.uuid AS service_uuid',
                    'Service.host_id AS service_host_id',
                ];
                $instantReportKey = 'Hostgroup';
                $inCondition = 'Host2Hostgroup.hostgroup_id';
                $joins[] = [
                    'table' => 'services',
                    'alias' => 'Service',
                    'type' => 'INNER',
                    'conditions' => 'Host.id = Service.host_id'
                ];
                $joins[] = [
                    'table' => 'hosts_to_hostgroups',
                    'alias' => 'Host2Hostgroup',
                    'type' => 'INNER',
                    'conditions' => 'Host.id = Host2Hostgroup.host_id'
                ];
                break;

            case Instantreport::TYPE_HOSTS.'-'.Instantreport::EVALUATION_SERVICES:
                $fields = [
                    'Host.id AS host_id',
                    'Host.uuid AS host_uuid',
                    'Service.id AS service_id',
                    'Service.uuid AS service_uuid',
                    'Service.host_id AS service_host_id',
                ];
                $instantReportKey = 'Host';
                $inCondition = 'Host.id';
                $joins[] = [
                    'table' => 'services',
                    'alias' => 'Service',
                    'type' => 'INNER',
                    'conditions' => 'Host.id = Service.host_id'
                ];
                break;

            case Instantreport::TYPE_SERVICEGROUPS.'-'.Instantreport::EVALUATION_SERVICES:
                $fields = [
                    'Host.id AS host_id',
                    'Host.uuid AS host_uuid',
                    'Service.id AS service_id',
                    'Service.uuid AS service_uuid',
                    'Service.host_id AS service_host_id',
                ];
                $instantReportKey = 'Servicegroup';
                $inCondition = 'Service2Servicegroup.servicegroup_id';
                $joins[] = [
                    'table' => 'services',
                    'alias' => 'Service',
                    'type' => 'INNER',
                    'conditions' => 'Host.id = Service.host_id'
                ];
                $joins[] = [
                    'table' => 'services_to_servicegroups',
                    'alias' => 'Service2Servicegroup',
                    'type' => 'INNER',
                    'conditions' => 'Service.id = Service2Servicegroup.service_id'
                ];
                break;

            case Instantreport::TYPE_SERVICES.'-'.Instantreport::EVALUATION_SERVICES:
                $fields = [
                    'Host.id AS host_id',
                    'Host.uuid AS host_uuid',
                    'Service.id AS service_id',
                    'Service.uuid AS service_uuid',
                    'Service.host_id AS service_host_id',
                ];
                $instantReportKey = 'Service';
                $inCondition = 'Service.id';
                $joins[] = [
                    'table' => 'services',
                    'alias' => 'Service',
                    'type' => 'INNER',
                    'conditions' => 'Host.id = Service.host_id'
                ];
                break;
        }

        $connectionModelIds = [];
        foreach($instantReport[$instantReportKey] as $connectionModel){
            if(isset($connectionModel['id'])){
                $connectionModelIds[] = $connectionModel['id'];
            }elseif(!empty($connectionModel) && !is_array($connectionModel)){
                $connectionModelIds[] = $connectionModel;
            }
        }
        $conditions[$inCondition] = $connectionModelIds; // generating WHERE $inCondition IN ($connectionModelIds)

        $options = [
            'recursive' => -1,
            'fields' => $fields,
            'joins' => $joins,
            'conditions' => $conditions
        ];

        $returnResult = [];
        $myHostsServices = $this->Host->find('all', $options);
        foreach ($myHostsServices as $myHostService){
            if(isset($myHostService['Host']['host_id']) && isset($myHostService['Host']['host_uuid'])){
                $returnResult[$myHostService['Host']['host_id']]['uuid'] = $myHostService['Host']['host_uuid'];
            }
            if(isset($myHostService['Service']['service_id']) && isset($myHostService['Service']['service_uuid']) && isset($myHostService['Service']['service_host_id'])){
                if(!isset($returnResult[$myHostService['Service']['service_host_id']]['Service'])){
                    $returnResult[$myHostService['Service']['service_host_id']]['Service'] = [];
                }
                $returnResult[$myHostService['Service']['service_host_id']]['Service'][$myHostService['Service']['service_id']] = $myHostService['Service']['service_uuid'];
            }
        }

        return $returnResult;
    }

    public function delete($id = null) {
        if (!$this->Instantreport->exists($id)) {
            throw new NotFoundException(__('Invalid Instant report'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $instantreport = $this->Instantreport->find('first', [
            'recursive' => -1,
            'contain' => [
                'Container'
            ],
            'conditions' => [
                'Instantreport.id' => $id,
            ]
        ]);

        if (!$this->allowedByContainerId(Hash::extract($instantreport, 'Container.id'))) {
            $this->render403();
            return;
        }

        $this->Instantreport->id = $id;

        if ($this->Instantreport->delete()) {
            $this->setFlash(__('Instant Report deleted'));
        }else {
            $this->setFlash(__('Could not delete Instant Report'), false);
        }

        if($instantreport['Instantreport']['send_email'] === '1'){
            $this->redirect(['action' => 'sendEmailsList']);
        }else{
            $this->redirect(['action' => 'index']);
        }

    }

    public function createPdfReport(){
        $instantReportDetails = $this->Session->read('instantReportDetails');
        $reportName = '';
        if(isset($instantReportDetails['name'])){
            $reportName = $instantReportDetails['name'];
        }
        $this->set('instantReportData', $this->Session->read('instantReportData'));
        $this->set('instantReportDetails', $instantReportDetails);
        if ($this->Session->check('instantReportData')) {
            $this->Session->delete('instantReportData');
        }
        if ($this->Session->check('instantReportDetails')) {
            $this->Session->delete('instantReportDetails');
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
            'filename'           => sprintf('Instantreport_%s.pdf', $reportName),
            'no-pdf-compression' => '*',
            'image-dpi'          => '900',
            'background'         => true,
            'no-background'      => false,
        ];
    }

    public function expandServices($data)
    {
        return explode('|', $data);
    }

}
