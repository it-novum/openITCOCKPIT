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
use itnovum\openITCOCKPIT\Filter\InstantreportFilter;


/**
 * @property Instantreport $Instantreport
 * @property Host $Host
 * @property Service $Service
 * @property Timeperiod $Timeperiod
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
    ];

    public function index() {
        $this->layout = 'angularjs';
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        $InstantreportFilter = new InstantreportFilter($this->request);

        $options = [
            'recursive' => -1,
            'conditions' => [
                'Instantreport.container_id' => $this->MY_RIGHTS
            ],
            'contain' => [
                'Timeperiod.name',
                'User.firstname',
                'User.lastname'
            ],
            'order' => $InstantreportFilter->getOrderForPaginator('Instantreport.name', 'asc'),
            'conditions' => $InstantreportFilter->indexFilter(),
            'limit' => $this->Paginator->settings['limit']
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
            'recursive' => -1,
            'contain' => [
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
            'recursive' => -1,
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
                'recursive' => -1,
                'conditions' => [
                    'Instantreport.container_id' => $this->MY_RIGHTS,
                ],
                'order' => [
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
                        'recursive' => -1,
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
            'id' => $id,
            'allInstantReports' => $allInstantReports,
            'reportFormats' => $reportFormats
        ]);

    }

    private function generateReport($instantReport, $baseStartDate, $baseEndDate, $reportFormat) {
        $startDate = $baseStartDate . ' 00:00:00';
        $endDate = $baseEndDate . ' 23:59:59';
        $instantReportDetails = [
            'startDate' => $startDate,
            'endDate' => $endDate,
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
            $startDateSqlFormat = date('Y-m-d H:i:s', strtotime($startDate));
            $endDateSqlFormat = date('Y-m-d H:i:s', strtotime($endDate));

            $globalDowntimes = [];

            if ($instantReport['Instantreport']['downtimes'] === '1') {
                $this->loadModel('Systemfailure');
                $globalDowntimes = $this->Systemfailure->find('all', [
                    'recursive' => -1,
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

            $this->loadModel(MONITORING_OBJECTS);
            $this->loadModel(MONITORING_STATEHISTORY);
            $this->Objects->bindModel([
                'hasMany' => [
                    'Statehistory' => [
                        'className' => MONITORING_STATEHISTORY,
                    ],
                    'Downtime' => [
                        'className' => MONITORING_DOWNTIME,
                        'conditions' => [
                            'Downtime.was_cancelled' => '0',
                        ],
                    ],
                ],
            ]);
            $totalTime = Hash::apply(Hash::map($timeSlicesGlobal, '{n}', ['Instantreport', 'calculateTotalTime']), '{n}', 'array_sum');
            $instantReportDetails['totalTime'] = $totalTime;

            foreach ($allHostsServices['Hosts'] as $hostUuid) {
                $downtimes = [];
                $stateHistoryWithObject = $this->Objects->find('all', [
                    'recursive' => -1,
                    'contain' => [
                        'Host' => [
                            'fields' => [
                                'id',
                                'name',
                                'uuid'
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
                        'Objects.name1' => $hostUuid
                    ],
                ]);
                if (!empty($stateHistoryWithObject)) {
                    if (empty($stateHistoryWithObject[0]['Statehistory'])) {
                        $stateHistoryWithPrev = $this->Statehistory->find('first', [
                            'recursive' => -1,
                            'fields' => ['Statehistory.object_id', 'Statehistory.state_time', 'Statehistory.state', 'Statehistory.state_type', 'Statehistory.last_state', 'Statehistory.last_hard_state'],
                            'conditions' => [
                                'AND' => [
                                    'Statehistory.object_id' => $stateHistoryWithObject[0]['Objects']['object_id'],
                                    'Statehistory.state_time <= "' . $startDateSqlFormat . '"'
                                ],
                            ],
                            'order' => ['Statehistory.state_time' => 'DESC'],

                        ]);
                    }
                    if (!empty($stateHistoryWithPrev)) {
                        $stateHistoryWithObject[0]['Statehistory'][0] = $stateHistoryWithPrev['Statehistory'];
                        $stateHistoryWithObject[0]['Statehistory'][0]['state_time'] = $startDateSqlFormat;
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

                    $instantReportData['Hosts'][$hostUuid] = $this->Instantreport->generateInstantreportData(
                        $totalTime,
                        $timeSlices,
                        $stateHistoryWithObject,
                        $instantReport['Instantreport']['reflection'] == Instantreport::STATE_HARD_ONLY,
                        true
                    );
                    $instantReportData['Hosts'][$hostUuid] = Hash::insert(
                        $instantReportData['Hosts'][$hostUuid],
                        'Host',
                        [
                            'name' => $stateHistoryWithObject[0]['Host']['name'],
                        ]
                    );
                    unset($timeSlices, $stateHistoryWithObject);
                } else {
                    $instantReportData['Hosts'][$hostUuid]['HostsNotMonitored'] = $this->Host->find('list', [
                        'conditions' => [
                            'Host.uuid' => $hostUuid,
                        ],
                    ]);
                }

            }
            foreach ($allHostsServices['Services'] as $serviceUuid) {
                $downtimes = [];
                $stateHistoryWithObject = $this->Objects->find('all', [
                    'recursive' => -1,
                    'contain' => [
                        'Service' => [
                            'Host' => [
                                'fields' => [
                                    'id',
                                    'name',
                                    'uuid'
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
                    if (empty($stateHistoryWithObject[0]['Statehistory'])) {
                        $stateHistoryWithPrev = $this->Statehistory->find('first', [
                            'recursive' => -1,
                            'fields' => ['Statehistory.object_id', 'Statehistory.state_time', 'Statehistory.state', 'Statehistory.state_type', 'Statehistory.last_state', 'Statehistory.last_hard_state'],
                            'conditions' => [
                                'AND' => [
                                    'Statehistory.object_id' => $stateHistoryWithObject[0]['Objects']['object_id'],
                                    'Statehistory.state_time <= "' . $startDateSqlFormat . '"'
                                ],
                            ],
                            'order' => ['Statehistory.state_time' => 'DESC'],

                        ]);
                    }
                    if (!empty($stateHistoryWithPrev)) {
                        $stateHistoryWithObject[0]['Statehistory'][0] = $stateHistoryWithPrev['Statehistory'];
                        $stateHistoryWithObject[0]['Statehistory'][0]['state_time'] = $startDateSqlFormat;
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
                    $hostUuid = $stateHistoryWithObject[0]['Service']['Host']['uuid'];
                    if (empty($instantReportData['Hosts'][$hostUuid]['Host']['name'])) {
                        $instantReportData['Hosts'][$hostUuid]['Host']['name'] = $stateHistoryWithObject[0]['Service']['Host']['name'];
                    }
                    $instantReportData['Hosts'][$hostUuid]['Services'][$serviceUuid] = $this->Instantreport->generateInstantreportData(
                        $totalTime,
                        $timeSlices,
                        $stateHistoryWithObject,
                        $instantReport['Instantreport']['reflection'] == Instantreport::STATE_HARD_ONLY,
                        false
                    );

                    $instantReportData['Hosts'][$hostUuid]['Services'][$serviceUuid] = Hash::insert(
                        $instantReportData['Hosts'][$hostUuid]['Services'][$serviceUuid],
                        'Service',
                        [
                            'name' => ($stateHistoryWithObject[0]['Service']['name']) ? $stateHistoryWithObject[0]['Service']['name'] : $stateHistoryWithObject[0]['Service']['Servicetemplate']['name'],
                        ]
                    );
                    unset($timeSlices, $stateHistoryWithObject);
                } else {
                    $instantReportService = $this->Service->find('first', [
                        'recursive' => -1,
                        'contain' => [
                            'Host' => [
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
                        'fields' => [
                            'Service.name',
                        ],
                    ]);
                    $instantReportData['Hosts'][$instantReportService['Host']['uuid']]['Services']['ServicesNotMonitored'][$serviceUuid] = $instantReportService;
                }

            }
        }
        if ($reportFormat == Instantreport::FORMAT_PDF) {
            if (empty($this->cronFromDate)) {
                $this->Session->write('instantReportData', $instantReportData);
                $this->Session->write('instantReportDetails', $instantReportDetails);
                $this->redirect([
                    'action' => 'createPdfReport',
                    'ext' => 'pdf',
                ]);
            } else {
                $binary_path = '/usr/bin/wkhtmltopdf';
                if (file_exists('/usr/local/bin/wkhtmltopdf')) {
                    $binary_path = '/usr/local/bin/wkhtmltopdf';
                }
                $CakePdf = new CakePdf([
                    'engine' => 'CakePdf.WkHtmlToPdf',
                    'margin' => [
                        'bottom' => 15,
                        'left' => 0,
                        'right' => 0,
                        'top' => 15,
                    ],
                    'encoding' => 'UTF-8',
                    'download' => false,
                    'binary' => $binary_path,
                    'orientation' => 'portrait',
                    'filename' => sprintf('InstantReport_%s.pdf', $instantReport['Instantreport']['name']),
                    'no-pdf-compression' => '*',
                    'image-dpi' => '900',
                    'background' => true,
                    'no-background' => false,
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
            'Hosts' => [],
            'Services' => []
        ];
        switch ($instantReport['Instantreport']['type']) {
            case Instantreport::TYPE_HOSTGROUPS:      //-> 1
                $containArray = [
                    1 => [
                        'Host.uuid'
                    ],
                    2 => [
                        'Host.uuid' => [
                            'Service.uuid'
                        ]
                    ],
                    3 => [
                        'Host.uuid' => [
                            'Service.uuid'
                        ]
                    ],
                ];
                $instantReportHostgroups = $this->Instantreport->find('first', [
                    'recursive' => -1,
                    'contain' => [
                        'Hostgroup' =>
                            $containArray[$instantReport['Instantreport']['evaluation']]
                    ],
                    'conditions' => [
                        'Instantreport.id' => $instantReport['Instantreport']['id']
                    ]
                ]);
                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS ||
                    $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS_SERVICES) {
                    $objectsForInstantReport['Hosts'] = array_unique(
                        Hash::extract($instantReportHostgroups['Hostgroup'], '{n}.Host.{n}.uuid')
                    );
                }
                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS_SERVICES ||
                    $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_SERVICES) {
                    $objectsForInstantReport['Services'] = array_unique(
                        Hash::extract($instantReportHostgroups['Hostgroup'], '{n}.Host.{n}.Service.{n}.uuid')
                    );
                }
                return $objectsForInstantReport;
            case Instantreport::TYPE_HOSTS:           //-> 2
                $containArray = [
                    1 => [],
                    2 => [
                        'Service.uuid'
                    ],
                    3 => [
                        'Service.uuid'
                    ]
                ];
                $instantReportHosts = $this->Instantreport->find('first', [
                    'recursive' => -1,
                    'contain' => [
                        'Host.uuid' =>
                            $containArray[$instantReport['Instantreport']['evaluation']]
                    ],
                    'conditions' => [
                        'Instantreport.id' => $instantReport['Instantreport']['id']
                    ]
                ]);
                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS ||
                    $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS_SERVICES) {
                    $objectsForInstantReport['Hosts'] = array_unique(
                        Hash::extract($instantReportHosts['Host'], '{n}.uuid')
                    );
                }
                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS_SERVICES ||
                    $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_SERVICES) {
                    $objectsForInstantReport['Services'] = array_unique(
                        Hash::extract($instantReportHosts['Host'], '{n}.Service.{n}.uuid')
                    );
                }
                return $objectsForInstantReport;
            case Instantreport::TYPE_SERVICEGROUPS:   //-> 3
                $containArray = [
                    1 => [
                        'Service.uuid' => [
                            'Host.uuid'
                        ]
                    ],
                    2 => [
                        'Service.uuid' => [
                            'Host.uuid'
                        ]
                    ],
                    3 => [
                        'Service.uuid'
                    ]
                ];
                $instantReportServicegroups = $this->Instantreport->find('first', [
                    'recursive' => -1,
                    'contain' => [
                        'Servicegroup' =>
                            $containArray[$instantReport['Instantreport']['evaluation']]
                    ],
                    'conditions' => [
                        'Instantreport.id' => $instantReport['Instantreport']['id']
                    ]
                ]);
                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS ||
                    $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS_SERVICES) {
                    $objectsForInstantReport['Hosts'] = array_unique(
                        Hash::extract($instantReportServicegroups['Servicegroup'], '{n}.Service.{n}.Host.uuid')
                    );
                }
                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS_SERVICES ||
                    $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_SERVICES) {
                    $objectsForInstantReport['Services'] = array_unique(
                        Hash::extract($instantReportServicegroups['Servicegroup'], '{n}.Service.{n}.uuid')
                    );
                }
                return $objectsForInstantReport;
            case Instantreport::TYPE_SERVICES:        //-> 4
                $containArray = [
                    1 => [
                        'Host.uuid'
                    ],
                    2 => [
                        'Host.uuid'
                    ],
                    3 => []
                ];
                $instantReportServices = $this->Instantreport->find('first', [
                    'recursive' => -1,
                    'contain' => [
                        'Service.uuid' =>
                            $containArray[$instantReport['Instantreport']['evaluation']]
                    ],
                    'conditions' => [
                        'Instantreport.id' => $instantReport['Instantreport']['id']
                    ]
                ]);
                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS ||
                    $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS_SERVICES) {
                    $objectsForInstantReport['Hosts'] = array_unique(
                        Hash::extract($instantReportServices['Service'], '{n}.Host.uuid')
                    );
                }
                if ($instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_HOSTS_SERVICES ||
                    $instantReport['Instantreport']['evaluation'] == Instantreport::EVALUATION_SERVICES) {
                    $objectsForInstantReport['Services'] = array_unique(
                        Hash::extract($instantReportServices['Service'], '{n}.uuid')
                    );
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
            'engine' => 'CakePdf.WkHtmlToPdf',
            'margin' => [
                'bottom' => 5,
                'left' => 0,
                'right' => 0,
                'top' => 5,
            ],
            'encoding' => 'UTF-8',
            'download' => true,
            'binary' => $binary_path,
            'orientation' => 'portrait',
            'filename' => sprintf('Instantreport_%s.pdf', $reportName),
            'no-pdf-compression' => '*',
            'image-dpi' => '900',
            'background' => true,
            'no-background' => false,
        ];
    }

    public function expandServices($data) {
        return explode('|', $data);
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
