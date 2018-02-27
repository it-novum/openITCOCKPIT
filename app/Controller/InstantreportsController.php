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
use itnovum\openITCOCKPIT\Core\DowntimeHostConditions;
use itnovum\openITCOCKPIT\Core\DowntimeServiceConditions;
use itnovum\openITCOCKPIT\Core\StatehistoryHostConditions;
use itnovum\openITCOCKPIT\Core\StatehistoryServiceConditions;
use itnovum\openITCOCKPIT\Core\ValueObjects\StateTypes;
use itnovum\openITCOCKPIT\Filter\InstantreportFilter;


/**
 * @property Instantreport $Instantreport
 * @property Host $Host
 * @property Service $Service
 * @property Timeperiod $Timeperiod
 * @property StatehistoryHost $StatehistoryHost
 * @property DowntimeHost $DowntimeHost
 * @property StatehistoryService $StatehistoryService
 */
class InstantreportsController extends AppController {

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
        MONITORING_STATEHISTORY_HOST,
        MONITORING_STATEHISTORY_SERVICE,
        MONITORING_DOWNTIME_HOST,
        MONITORING_DOWNTIME_SERVICE
    ];

    public function index() {
        $this->layout = 'angularjs';
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        $InstantreportFilter = new InstantreportFilter($this->request);

        $options = [
            'recursive'  => -1,
            'conditions' => [
                'Instantreport.container_id' => $this->MY_RIGHTS
            ],
            'contain'    => [
                'Timeperiod.name',
                'User.firstname',
                'User.lastname'
            ],
            'order'      => $InstantreportFilter->getOrderForPaginator('Instantreport.name', 'asc'),
            'conditions' => $InstantreportFilter->indexFilter(),
            'limit'      => $this->Paginator->settings['limit']
        ];

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            unset($options['limit']);
            $instantreports = $this->Instantreport->find('all', $options);
        } else {
            $this->Paginator->settings = $options;
            $this->Paginator->settings['page'] = $InstantreportFilter->getPage();
            $instantreports = $this->Paginator->paginate();
        }

        $evaluations = $this->Instantreport->getEvaluations();
        $types = $this->Instantreport->getTypes();
        $sendIntervals = $this->Instantreport->getSendIntervals();

        array_walk($instantreports, function (&$value, &$key) use ($evaluations, $types, $sendIntervals) {
            $value['Instantreport']['evaluation'] = $evaluations[$value['Instantreport']['evaluation']];
            $value['Instantreport']['type'] = $types[$value['Instantreport']['type']];
            $value['Instantreport']['send_interval'] = $sendIntervals[$value['Instantreport']['send_interval']];
        });

        $this->set([
            'instantreports' => $instantreports
        ]);
        $this->set('_serialize', ['instantreports', 'paging']);
    }

    public function add() {
        $this->layout = 'angularjs';
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['User'] = $this->request->data('Instantreport.User');
            if ($this->request->data('Instantreport.send_email') == 0) {
                $this->request->data['Instantreport']['send_interval'] = 0;
                $this->request->data['User'] = [];
            }
            $this->request->data['Hostgroup'] = $this->request->data('Instantreport.Hostgroup');
            $this->request->data['Host'] = $this->request->data('Instantreport.Host');
            $this->request->data['Servicegroup'] = $this->request->data('Instantreport.Servicegroup');
            $this->request->data['Service'] = $this->request->data('Instantreport.Service');
            if ($this->Instantreport->saveAll($this->request->data)) {
                if ($this->request->ext == 'json') {
                    if ($this->isAngularJsRequest()) {
                        $this->setFlash(__('<a href="/instantreports/edit/%s">Instantreport</a> successfully saved', $this->Instantreport->id));
                    }
                    $this->serializeId();
                    return;
                }
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();
                    return;
                }
                $this->setFlash(__('Could not save data'), false);
            }
        }
    }

    public function edit($id = null) {
        $this->layout = 'angularjs';
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }
        if (!$this->Instantreport->exists($id)) {
            throw new NotFoundException(__('Invalid Instant report'));
        }


        $instantreport = $this->Instantreport->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'User.id',
                'Host.id',
                'Service.id',
                'Hostgroup.id',
                'Servicegroup.id'
            ],
            'conditions' => [
                'Instantreport.id' => $id
            ]
        ]);

        if (!$this->allowedByContainerId(Hash::extract($instantreport, 'Instantreport.container_id'))) {
            $this->render403();
            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Instantreport']['id'] = $id;
            $this->request->data['User'] = $this->request->data('Instantreport.User');
            if ($this->request->data('Instantreport.send_email') == 0) {
                $this->request->data['Instantreport']['send_interval'] = 0;
                $this->request->data['User'] = [];
            }
            $this->request->data['Hostgroup'] = $this->request->data('Instantreport.Hostgroup');
            $this->request->data['Host'] = $this->request->data('Instantreport.Host');
            $this->request->data['Servicegroup'] = $this->request->data('Instantreport.Servicegroup');
            $this->request->data['Service'] = $this->request->data('Instantreport.Service');
            if ($this->Instantreport->saveAll($this->request->data)) {
                if ($this->request->ext == 'json') {
                    if ($this->isAngularJsRequest()) {
                        $this->setFlash(__('<a href="/instantreports/edit/%s">Instantreport</a> successfully saved', $this->Instantreport->id));
                    }
                    $this->serializeId();
                    return;
                }
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();
                    return;
                }
            }
        }
        $this->set('instantreport', $instantreport);
        $this->set('_serialize', ['instantreport']);
    }

    public function generate($id = null) {
        $instantReport = $this->Instantreport->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Instantreport.id' => $id,
            ]
        ]);

        if (empty($instantReport)) {
            throw new NotFoundException(__('Invalid Instant report'));
        }
        if (!empty($this->cronFromDate)) {
            $this->generateReport($instantReport, date('d.m.Y', $this->cronFromDate), date('d.m.Y', $this->cronToDate), Instantreport::FORMAT_PDF);
            return;
        } else {
            $options = [
                'recursive'  => -1,
                'conditions' => [
                    'Instantreport.container_id' => $this->MY_RIGHTS,
                ],
                'order'      => [
                    'Instantreport.name' => 'asc'
                ]
            ];

            $allInstantReports = $this->Instantreport->find('all', $options);

            $reportFormats = $this->Instantreport->getReportFormats();
            $this->Instantreport->setValidationRules('generate');
            $this->Instantreport->set($this->request->data);

            if ($this->request->is('post') || $this->request->is('put')) {
                if ($this->Instantreport->validates()) {
                    $instantReport = $this->Instantreport->find('first', [
                        'recursive'  => -1,
                        'conditions' => [
                            'Instantreport.id' => $this->request->data['Instantreport']['id'],
                        ]
                    ]);
                    if (empty($instantReport)) {
                        throw new NotFoundException(__('Invalid Instant report'));
                    }

                    if (!$this->allowedByContainerId(Hash::extract($instantReport, 'Instantreport.container_id'))) {
                        $this->render403();
                        return;
                    }


                    $this->generateReport($instantReport, $this->request->data['Instantreport']['start_date'], $this->request->data['Instantreport']['end_date'], $this->request->data['Instantreport']['report_format']);
                }
            }
        }
        $this->set([
            'id'                => $id,
            'allInstantReports' => $allInstantReports,
            'reportFormats'     => $reportFormats
        ]);

    }

    private function generateReport($instantReport, $baseStartDate, $baseEndDate, $reportFormat) {
        $startDate = $baseStartDate . ' 00:00:00';
        $endDate = $baseEndDate . ' 23:59:59';

        $instantReportDetails = [
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ];
        $instantReportDetails['onlyHosts'] = ($instantReport['Instantreport']['evaluation'] == 1);
        $instantReportDetails['onlyServices'] = ($instantReport['Instantreport']['evaluation'] == 3);
        $instantReportDetails['summary'] = $instantReport['Instantreport']['summary'];
        $instantReportDetails['name'] = $instantReport['Instantreport']['name'];
        $instantReportData = [];
        $allHostsServices = $this->getAllHostsServices($instantReport);
        if (!empty($allHostsServices['Hosts']) || !empty($allHostsServices['Services'])) {
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

            //Default time slices (no downtimes in report)
            if ($instantReport['Instantreport']['downtimes'] !== '1') {
                $timeSlices = $timeSlicesGlobal;
            }


            $startDateSqlFormat = date('Y-m-d H:i:s', strtotime($startDate));
            $endDateSqlFormat = date('Y-m-d H:i:s', strtotime($endDate));

            $globalDowntimes = [];

            if ($instantReport['Instantreport']['downtimes'] === '1') {
                $this->loadModel('Systemfailure');
                $globalDowntimes = $this->Systemfailure->find('all', [
                    'recursive'  => -1,
                    'conditions' => [
                        'OR' => [
                            '"' . $startDateSqlFormat . '"
                                    BETWEEN Systemfailure.start_time
                                    AND Systemfailure.end_time',
                            '"' . $endDateSqlFormat . '"
                                    BETWEEN Systemfailure.start_time
                                    AND Systemfailure.end_time',
                            'Systemfailure.start_time BETWEEN "' . $startDateSqlFormat . '"
                                    AND "' . $endDateSqlFormat . '"',
                        ],
                    ],
                ]);
                $globalDowntimes = ['Systemfailure' => Hash::extract($globalDowntimes, '{n}.Systemfailure')];
            }

            $totalTime = Hash::apply(Hash::map($timeSlicesGlobal, '{n}', ['Instantreport', 'calculateTotalTime']), '{n}', 'array_sum');
            $instantReportDetails['totalTime'] = $totalTime;

            foreach ($allHostsServices['Hosts'] as $hostUuid => $name) {
                //Process conditions
                $Conditions = new StatehistoryHostConditions();
                $Conditions->setOrder(['StatehistoryHost.state_time' => 'asc']);

                if ($instantReport['Instantreport']['reflection'] == Instantreport::STATE_HARD_ONLY) {
                    $StateTypes = new StateTypes();
                    $StateTypes->setStateType(1, true);
                    $Conditions->setStateTypes($StateTypes);
                }
                $Conditions->setFrom(strtotime($startDate));
                $Conditions->setTo(strtotime($endDate));
                $Conditions->setHostUuid($hostUuid);
                $Conditions->setUseLimit(false);

                //Query state history records for hosts
                $query = $this->StatehistoryHost->getQuery($Conditions);
                $statehistories = $this->StatehistoryHost->find('all', $query);
                $all_statehistories[$hostUuid] = [];
                foreach ($statehistories as $statehistory) {
                    $StatehistoryHost = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryHost($statehistory['StatehistoryHost']);
                    $all_statehistories[$hostUuid]['Statehistory'][] = $StatehistoryHost->toArray();
                }

                if (empty($all_statehistories[$hostUuid]['Statehistory'])) {
                    //Host has no state history record for selected time range
                    //Get last available state history record for this host
                    $query = $this->StatehistoryHost->getLastRecord($Conditions);
                    $record = $this->StatehistoryHost->find('first', $query);
                    if (!empty($record)) {
                        $record['StatehistoryHost']['state_time'] = $startDateSqlFormat;
                        $StatehistoryHost = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryHost($record['StatehistoryHost']);
                        $all_statehistories[$hostUuid]['Statehistory'][] = $StatehistoryHost->toArray();
                    }
                }


                if ($instantReport['Instantreport']['downtimes'] == '1') {
                    //Query downtime records for hosts
                    $DowntimeHostConditions = new DowntimeHostConditions();
                    $DowntimeHostConditions->setOrder(['DowntimeHost.scheduled_start_time' => 'asc']);
                    $DowntimeHostConditions->setFrom(strtotime($startDate));
                    $DowntimeHostConditions->setTo(strtotime($endDate));
                    $DowntimeHostConditions->setHostUuid($hostUuid);


                    $query = $this->DowntimeHost->getQueryForReporting($DowntimeHostConditions);
                    $downtimes = $this->DowntimeHost->find('all', $query);

                    //Merge monitoring downtimes with openITCOCKPIT system failures
                    $downtimes = $this->Instantreport->mergeDowntimesWithSystemfailures(
                        'DowntimeHost',
                        $downtimes,
                        $globalDowntimes['Systemfailure']
                    );


                    $downtimesAndSystemfailures = [];
                    foreach ($downtimes as $downtime) {
                        $DowntimeHost = new \itnovum\openITCOCKPIT\Core\Views\Downtime($downtime['DowntimeHost']);
                        $downtimesAndSystemfailures[] = [
                            'DowntimeHost' => $DowntimeHost->toArray()
                        ];
                    }

                    $timeSlices = $timeSlicesGlobal; //Default time slice if no downtime will be found
                    if (!empty($downtimesAndSystemfailures)) {
                        $downtimesFiltered = $this->Instantreport->mergeTimeOverlapping(
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
                        $timeSlices = $this->Instantreport->setDowntimesInTimeslices(
                            $timeSlicesGlobal,
                            $downtimesFiltered
                        );
                        unset($downtimesFiltered);
                    }
                }
                $stateHistoryWithObject[$hostUuid] = $all_statehistories[$hostUuid];

                if (!empty($stateHistoryWithObject)) {
                    $instantReportData['Hosts'][$hostUuid] = $this->Instantreport->generateInstantreportData(
                        $timeSlices,
                        $stateHistoryWithObject,
                        $instantReport['Instantreport']['reflection'] == Instantreport::STATE_HARD_ONLY,
                        true
                    );
                    $instantReportData['Hosts'][$hostUuid]['Host']['name'] = $name;

                    unset($stateHistoryWithObject);
                } else {
                    $instantReportData['Hosts'][$hostUuid]['HostsNotMonitored'] = $this->Host->find('list', [
                        'conditions' => [
                            'Host.uuid' => $hostUuid,
                        ],
                    ]);
                }
            }
            foreach ($allHostsServices['Services'] as $hostUuid => $services) {
                foreach ($services as $serviceUuid => $name) {
                    //Process conditions
                    $Conditions = new StatehistoryServiceConditions();
                    $Conditions->setOrder(['StatehistoryService.state_time' => 'asc']);

                    if ($instantReport['Instantreport']['reflection'] == Instantreport::STATE_HARD_ONLY) {
                        $StateTypes = new StateTypes();
                        $StateTypes->setStateType(1, true);
                        $Conditions->setStateTypes($StateTypes);
                    }
                    $Conditions->setFrom(strtotime($startDate));
                    $Conditions->setTo(strtotime($endDate));
                    $Conditions->setServiceUuid($serviceUuid);
                    $Conditions->setUseLimit(false);

                    //Query state history records for services
                    $query = $this->StatehistoryService->getQuery($Conditions);
                    $statehistories = $this->StatehistoryService->find('all', $query);

                    $all_statehistories[$serviceUuid] = [];
                    foreach ($statehistories as $statehistory) {
                        $StatehistoryService = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryService($statehistory['StatehistoryService']);
                        $all_statehistories[$serviceUuid]['Statehistory'][] = $StatehistoryService->toArray();
                    }

                    if (empty($all_statehistories[$serviceUuid]['Statehistory'])) {
                        //Service has no state history record for selected time range
                        //Get last available state history record for this service
                        $query = $this->StatehistoryService->getLastRecord($Conditions);
                        $record = $this->StatehistoryService->find('first', $query);
                        if (!empty($record)) {
                            $record['StatehistoryService']['state_time'] = $startDateSqlFormat;
                            $StatehistoryService = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryService($record['StatehistoryService']);
                            $all_statehistories[$serviceUuid]['Statehistory'][] = $StatehistoryService->toArray();
                        }
                    }


                    if ($instantReport['Instantreport']['downtimes'] == '1') {
                        //Query downtime records for hosts
                        $DowntimeServiceConditions = new DowntimeServiceConditions();
                        $DowntimeServiceConditions->setOrder(['DowntimeService.scheduled_start_time' => 'asc']);
                        $DowntimeServiceConditions->setFrom(strtotime($startDate));
                        $DowntimeServiceConditions->setTo(strtotime($endDate));
                        $DowntimeServiceConditions->setServiceUuid($serviceUuid);


                        $query = $this->DowntimeService->getQueryForReporting($DowntimeServiceConditions);
                        $downtimes = $this->DowntimeService->find('all', $query);

                        //Merge monitoring downtimes with openITCOCKPIT system failures
                        $downtimes = $this->Instantreport->mergeDowntimesWithSystemfailures(
                            'DowntimeService',
                            $downtimes,
                            $globalDowntimes['Systemfailure']
                        );


                        $downtimesAndSystemfailures = [];
                        foreach ($downtimes as $downtime) {
                            $DowntimeService = new \itnovum\openITCOCKPIT\Core\Views\Downtime($downtime['DowntimeService']);
                            $downtimesAndSystemfailures[] = [
                                'DowntimeService' => $DowntimeService->toArray()
                            ];
                        }

                        $timeSlices = $timeSlicesGlobal;
                        if (!empty($downtimesAndSystemfailures)) {
                            $downtimesFiltered = $this->Instantreport->mergeTimeOverlapping(
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
                            $timeSlices = $this->Instantreport->setDowntimesInTimeslices(
                                $timeSlicesGlobal,
                                $downtimesFiltered
                            );
                            unset($downtimesFiltered);
                        }
                    }
                    $stateHistoryWithObject[$serviceUuid] = $all_statehistories[$serviceUuid];

                    if (!empty($stateHistoryWithObject)) {
                        $instantReportData['Hosts'][$hostUuid]['Services'][$serviceUuid] = $this->Instantreport->generateInstantreportData(
                            $timeSlices,
                            $stateHistoryWithObject,
                            $instantReport['Instantreport']['reflection'] == Instantreport::STATE_HARD_ONLY,
                            false
                        );
                        $instantReportData['Hosts'][$hostUuid]['Services'][$serviceUuid]['Service']['name'] = $name;

                        unset($stateHistoryWithObject);
                    } else {
                        $instantReportService = $this->Service->find('first', [
                            'recursive'  => -1,
                            'contain'    => [
                                'Host'            => [
                                    'fields' => [
                                        'Host.uuid',
                                        'Host.name'
                                    ]
                                ],
                                'Servicetemplate' => [
                                    'fields' => 'Servicetemplate.name',
                                ],
                            ],
                            'conditions' => [
                                'Service.uuid' => $serviceUuid,
                            ],
                            'fields'     => [
                                'Service.name',
                            ],
                        ]);
                        $instantReportData['Hosts'][$instantReportService['Host']['uuid']]['Services']['ServicesNotMonitored'][$serviceUuid] = $instantReportService;
                    }
                }
            }
        }

        if ($reportFormat == Instantreport::FORMAT_PDF) {
            if (empty($this->cronFromDate)) {
                $this->Session->write('instantReportData', $instantReportData);
                $this->Session->write('instantReportDetails', $instantReportDetails);
                $this->redirect([
                    'action' => 'createPdfReport',
                    'ext'    => 'pdf',
                ]);
            } else {
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

    private function getAllHostsServices($instantReport) {
        /**
         *  $containArray = [
         *      1 => [], // Type Only Hosts
         *      2 => [], // Type Hosts and Services
         *      3 => []  // Type Only Services
         *  ];
         */

        $objectsForInstantReport = [
            'Hosts'    => [],
            'Services' => []
        ];
        switch ($instantReport['Instantreport']['type']) {
            case Instantreport::TYPE_HOSTGROUPS:      //-> 1
                $containArray = [
                    Instantreport::EVALUATION_HOSTS          => [
                        'Host' => [
                            'fields' => [
                                'Host.uuid',
                                'Host.name'
                            ],
                            'conditions' => [
                                'Host.disabled' => 0
                            ]
                        ]
                    ],
                    Instantreport::EVALUATION_HOSTS_SERVICES => [
                        'Host' => [
                            'fields'  => [
                                'Host.uuid',
                                'Host.name'
                            ],
                            'Service' => [
                                'fields'          => [
                                    'Service.uuid',
                                    'Service.name'
                                ],
                                'Servicetemplate' => [
                                    'fields' => [
                                        'Servicetemplate.name'
                                    ]
                                ],
                                'conditions' => [
                                    'Service.disabled' => 0
                                ]
                            ],
                            'conditions' => [
                                'Host.disabled' => 0
                            ]
                        ]
                    ],
                    Instantreport::EVALUATION_SERVICES       => [
                        'Host' => [
                            'fields'  => [
                                'Host.uuid',
                                'Host.name'
                            ],
                            'conditions' => [
                                'Host.disabled' => 0
                            ],
                            'Service' => [
                                'fields'          => [
                                    'Service.uuid',
                                    'Service.name'
                                ],
                                'conditions' => [
                                    'Service.disabled' => 0
                                ],
                                'Servicetemplate' => [
                                    'fields' => [
                                        'Servicetemplate.name'
                                    ]
                                ]
                            ]
                        ]
                    ],
                ];
                $instantReportHostgroups = $this->Instantreport->find('first', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Hostgroup' =>
                            $containArray[$instantReport['Instantreport']['evaluation']]
                    ],
                    'conditions' => [
                        'Instantreport.id' => $instantReport['Instantreport']['id']
                    ]
                ]);
                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS) {
                    $objectsForInstantReport['Hosts'] = array_unique(
                        Hash::combine($instantReportHostgroups['Hostgroup'], '{n}.Host.{n}.uuid', '{n}.Host.{n}.name')
                    );
                }
                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS_SERVICES ||
                    $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_SERVICES) {
                    foreach ($instantReportHostgroups['Hostgroup'] as $hostgroup) {
                        foreach ($hostgroup['Host'] as $host) {
                            $objectsForInstantReport['Hosts'][$host['uuid']] = $host['name'];
                            foreach ($host['Service'] as $service) {
                                $serviceName = $service['name'];
                                if ($serviceName === null || $serviceName === '') {
                                    $serviceName = $service['Servicetemplate']['name'];
                                }
                                $objectsForInstantReport['Services'][$host['uuid']][$service['uuid']] = $serviceName;
                            }
                        }
                    }
                }
                return $objectsForInstantReport;
            case Instantreport::TYPE_HOSTS:           //-> 2
                $containArray = [
                    Instantreport::EVALUATION_HOSTS          => [
                        'Host' => [
                            'fields' => [
                                'Host.name',
                                'Host.uuid'
                            ],
                            'conditions' => [
                                'Host.disabled' => 0
                            ]
                        ]
                    ],
                    Instantreport::EVALUATION_HOSTS_SERVICES => [
                        'Host' => [
                            'fields'  => [
                                'Host.name',
                                'Host.uuid'
                            ],
                            'conditions' => [
                                'Host.disabled' => 0
                            ],
                            'Service' => [
                                'fields'          => [
                                    'Service.uuid',
                                    'Service.name'
                                ],
                                'Servicetemplate' => [
                                    'fields' => [
                                        'Servicetemplate.name'
                                    ]
                                ],
                                'conditions' => [
                                    'Service.disabled' => 0
                                ]
                            ]
                        ]
                    ]
                ];
                $containArray[Instantreport::EVALUATION_SERVICES] = $containArray[Instantreport::EVALUATION_HOSTS_SERVICES];
                $instantReportHosts = $this->Instantreport->find('first', [
                    'recursive'  => -1,
                    'contain'    => $containArray[$instantReport['Instantreport']['evaluation']],
                    'conditions' => [
                        'Instantreport.id' => $instantReport['Instantreport']['id']
                    ]
                ]);

                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS) {
                    $objectsForInstantReport['Hosts'] = array_unique(
                        Hash::combine($instantReportHosts['Host'], '{n}.uuid', '{n}.name')
                    );
                }
                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS_SERVICES ||
                    $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_SERVICES) {

                    foreach ($instantReportHosts['Host'] as $host) {
                        $objectsForInstantReport['Hosts'][$host['uuid']] = $host['name'];
                        foreach ($host['Service'] as $service) {
                            $serviceName = $service['name'];
                            if ($serviceName === null || $serviceName === '') {
                                $serviceName = $service['Servicetemplate']['name'];
                            }
                            $objectsForInstantReport['Services'][$host['uuid']][$service['uuid']] = $serviceName;
                        }
                    }
                }
                return $objectsForInstantReport;
            case Instantreport::TYPE_SERVICEGROUPS:   //-> 3

                $instantReportServicegroups = $this->Instantreport->find('first', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Servicegroup' => [
                            'Service' => [
                                'fields'          => [
                                    'Service.uuid',
                                    'Service.name'
                                ],
                                'conditions' => [
                                    'Service.disabled' => 0
                                ],
                                'Servicetemplate' => [
                                    'fields' => [
                                        'Servicetemplate.name'
                                    ]
                                ],
                                'Host'            => [
                                    'fields' => [
                                        'Host.name',
                                        'Host.uuid'
                                    ],
                                    'conditions' => [
                                        'Host.disabled' => 0
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'conditions' => [
                        'Instantreport.id' => $instantReport['Instantreport']['id']
                    ]
                ]);
                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS) {
                    $objectsForInstantReport['Hosts'] = array_unique(
                        Hash::combine($instantReportServicegroups['Servicegroup'], '{n}.Service.{n}.Host.uuid', '{n}.Service.{n}.Host.name')
                    );
                }

                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS_SERVICES ||
                    $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_SERVICES) {
                    foreach ($instantReportServicegroups['Servicegroup'] as $servicegroup) {
                        foreach ($servicegroup['Service'] as $service) {
                            $serviceName = $service['name'];
                            if ($serviceName === null || $serviceName === '') {
                                $serviceName = $service['Servicetemplate']['name'];
                            }
                            $objectsForInstantReport['Hosts'][$service['Host']['uuid']] = $service['Host']['name'];
                            $objectsForInstantReport['Services'][$service['Host']['uuid']][$service['uuid']] = $serviceName;
                        }
                    }
                }
                return $objectsForInstantReport;
            case Instantreport::TYPE_SERVICES:        //-> 4
                $instantReportServices = $this->Instantreport->find('first', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Service' => [
                            'Servicetemplate' => [
                                'fields' => [
                                    'Servicetemplate.name'
                                ]
                            ],
                            'Host'            => [
                                'fields' => [
                                    'Host.uuid',
                                    'Host.name'
                                ],
                                'conditions' => [
                                    'Host.disabled' => 0
                                ]
                            ],
                            'fields'          => [
                                'Service.name',
                                'Service.uuid'
                            ],
                            'conditions' => [
                                'Service.disabled' => 0
                            ]
                        ]
                    ],
                    'conditions' => [
                        'Instantreport.id' => $instantReport['Instantreport']['id']
                    ]
                ]);


                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS) {
                    $objectsForInstantReport['Hosts'] = array_unique(
                        Hash::combine($instantReportServices['Service'], '{n}.Host.uuid', '{n}.Host.name')
                    );
                }

                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS_SERVICES ||
                    $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_SERVICES) {

                    foreach ($instantReportServices['Service'] as $service) {
                        $serviceName = $service['name'];
                        if ($serviceName === null || $serviceName === '') {
                            $serviceName = $service['Servicetemplate']['name'];
                        }
                        $objectsForInstantReport['Hosts'][$service['Host']['uuid']] = $service['Host']['name'];
                        $objectsForInstantReport['Services'][$service['Host']['uuid']][$service['uuid']] = $serviceName;
                    }
                }
                return $objectsForInstantReport;
        }
    }

    public function delete($id = null) {
        if (!$this->Instantreport->exists($id)) {
            throw new NotFoundException(__('Invalid Instant report'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $instantreport = $this->Instantreport->find('first', [
            'recursive'  => -1,
            'contain'    => [
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
            $this->set('success', true);
            $this->set('message', __('Instant report successfully deleted'));
            $this->set('_serialize', ['success']);
            return;
        }

        $this->response->statusCode(400);
        $this->set('success', false);
        $this->set('id', $id);
        $this->set('message', __('Issue while deleting instant report'));
        $this->set('_serialize', ['success', 'id', 'message']);
    }

    public function createPdfReport() {
        $instantReportDetails = $this->Session->read('instantReportDetails');
        $reportName = '';
        if (isset($instantReportDetails['name'])) {
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
                'bottom' => 5,
                'left'   => 0,
                'right'  => 0,
                'top'    => 5,
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

    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
        }
        $containers = $this->Container->makeItJavaScriptAble($containers);


        $this->set('containers', $containers);
        $this->set('_serialize', ['containers']);
    }
}
