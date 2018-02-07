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
 * @property Downtimereport $Downtimereport
 * @property Host $Host
 * @property Service $Service
 * @property Timeperiod $Timeperiod
 */
class DowntimereportsController extends AppController {
    public $layout = 'Admin.default';
    public $uses = [
        MONITORING_OBJECTS,
        MONITORING_DOWNTIME,
        'Downtimereport',
        'Host',
        'Service',
        'Timeperiod',
    ];

    public function index() {
        $userContainerIds = $this->Tree->resolveChildrenOfContainerIds(
            $this->MY_RIGHTS,
            $this->hasRootPrivileges);
        $timeperiods = $this->Timeperiod->timeperiodsByContainerId($userContainerIds, 'list');
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
                $startDateSqlFormat = date('Y-m-d H:i:s', strtotime($startDate));
                $endDateSqlFormat = date('Y-m-d H:i:s', strtotime($endDate));

                $downtimeReportDetails = [
                    'startDate' => $startDate,
                    'endDate'   => $endDate,
                ];
                $timeperiod = $this->Timeperiod->find('first', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data('Downtimereport.timeperiod_id'),
                    ],
                ]);
                $downtimes['Hosts'] = $this->Downtime->find('all', [
                        'recursive'  => -1,
                        'conditions' => [
                            'Downtime.downtime_type' => 2,
                            'OR'                     => [
                                '"' . $startDateSqlFormat . '"
								BETWEEN Downtime.scheduled_start_time
								AND Downtime.scheduled_end_time',
                                '"' . $endDateSqlFormat . '"
								BETWEEN Downtime.scheduled_start_time
								AND Downtime.scheduled_end_time',
                                'Downtime.scheduled_start_time BETWEEN "' . $startDateSqlFormat . '"
								AND "' . $endDateSqlFormat . '"',
                            ],
                            'Downtime.was_cancelled' => 0,
                            'Objects.object_id = Downtime.object_id',
                        ],
                        'fields'     => [
                            'Downtime.downtimehistory_id',
                            'Downtime.scheduled_start_time',
                            'Downtime.scheduled_end_time',
                            'Downtime.author_name',
                            'Downtime.comment_data',
                        ],
                        'contain'    => [
                            'Objects' => [
                                'fields'     => [
                                    'Objects.name1',
                                ],
                                'conditions' => [
                                    'AND' => [
                                        'Objects.name1'     => $hostsUuids,
                                        'Objects.is_active' => 1,
                                    ],

                                ],
                            ],
                        ],
                    ]
                );
                if ($this->request->data('Downtimereport.evaluationMethod') == 'DowntimereportService') {
                    $downtimes['Services'] = $this->Downtime->find('all', [
                        'recursive'  => -1,
                        'conditions' => [
                            'Downtime.downtime_type' => 1,
                            'OR'                     => [
                                '"' . $startDateSqlFormat . '"
								BETWEEN Downtime.scheduled_start_time
								AND Downtime.scheduled_end_time',
                                '"' . $endDateSqlFormat . '"
								BETWEEN Downtime.scheduled_start_time
								AND Downtime.scheduled_end_time',
                                'Downtime.scheduled_start_time BETWEEN "' . $startDateSqlFormat . '"
								AND "' . $endDateSqlFormat . '"',
                            ],
                            'Downtime.was_cancelled' => 0,
                            'Objects.object_id = Downtime.object_id',
                        ],
                        'fields'     => [
                            'Downtime.downtimehistory_id',
                            'Downtime.scheduled_start_time',
                            'Downtime.scheduled_end_time',
                            'Downtime.author_name',
                            'Downtime.comment_data',
                        ],
                        'contain'    => [
                            'Objects' => [
                                'fields'     => [
                                    'Objects.name1',
                                    'Objects.name2',
                                ],
                                'conditions' => [
                                    'AND' => [
                                        'Objects.name1'     => $hostsUuids,
                                        'Objects.is_active' => 1,
                                    ],
                                ],
                            ],
                        ],
                    ]);
                }
                if (!empty($downtimes['Hosts']) || !empty($downtimes['Services'])) {
                    $timeSlices = $this->Downtimereport->createDateRanges(
                        $this->request->data('Downtimereport.start_date'),
                        $this->request->data('Downtimereport.end_date'),
                        $timeperiod['Timerange']
                    );
                    unset($timeperiod);
                    $totalTime = Hash::apply(Hash::map($timeSlices, '{n}', ['Downtimereport', 'calculateTotalTime']), '{n}', 'array_sum');
                    $downtimeReportDetails['totalTime'] = $totalTime;
                    $this->Objects->bindModel([
                        'hasMany' => [
                            'Statehistory' => [
                                'className'  => MONITORING_STATEHISTORY,
                                'foreignKey' => 'object_id',
                            ],

                        ],
                    ]);
                    $hostUuids = array_unique(Hash::merge(Hash::extract($downtimes['Hosts'], '{n}.Objects.name1'), Hash::extract($downtimes['Services'], '{n}.Objects.name1')));
                    $downtimeHosts = Hash::combine(
                        $downtimes['Hosts'],
                        '{n}.Downtime.downtimehistory_id', '{n}.Downtime', '{n}.Objects.name1'
                    );
                    foreach ($hostUuids as $hostUuid) {
                        $stateHistoryWithObject = $this->Objects->find('all', [
                            'recursive'  => -1,
                            'contain'    => [
                                'Host'         => [
                                    'fields' => [
                                        'Host.id', 'Host.name', 'Host.description', 'Host.address',
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
                            ],
                            'conditions' => [
                                'Objects.name1' => $hostUuid,
                            ],
                        ]);
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
                                    'id'          => $stateHistoryWithObject[0]['Host']['id'],
                                    'name'        => $stateHistoryWithObject[0]['Host']['name'],
                                    'description' => $stateHistoryWithObject[0]['Host']['description'],
                                    'address'     => $stateHistoryWithObject[0]['Host']['address'],
                                ]
                            );
                            //add host name to downtime array
                            if (array_key_exists($hostUuid, $downtimeHosts)) {
                                $downtimeHosts = Hash::insert($downtimeHosts,
                                    $hostUuid . '.{n}.data',
                                    [
                                        'host' => $stateHistoryWithObject[0]['Host']['name'],
                                    ]
                                );
                            }
                            unset($stateHistoryWithObject);
                        } else {
                            $downtimeReportData['Hosts'][$hostUuid]['HostsNotMonitored'] = $this->Host->find('list', [
                                'conditions' => [
                                    'Host.uuid' => $hostUuid,
                                ],
                            ]);
                        }
                    }
                    $downtimeServices = Hash::combine(
                        $downtimes['Services'],
                        '{n}.Downtime.downtimehistory_id', '{n}.Downtime', '{n}.Objects.name2'
                    );
                    $serviceUuids = array_unique(Hash::extract($downtimes['Services'], '{n}.Objects.name2'));
                    unset($downtimes);
                    foreach ($serviceUuids as $serviceUuid) {
                        $stateHistoryWithObject = $this->Objects->find('all', [
                            'recursive'  => -1,
                            'contain'    => [
                                'Service'      => [
                                    'Host'            => [
                                        'fields' => [
                                            'Host.id', 'Host.uuid', 'Host.name',
                                        ],
                                    ],
                                    'Servicetemplate' => [
                                        'fields' => [
                                            'Servicetemplate.id', 'Servicetemplate.name',
                                        ],
                                    ],
                                    'fields'          => [
                                        'Service.id', 'Service.uuid', 'Service.name',
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
                            ],
                            'conditions' => [
                                'Objects.name2' => $serviceUuid,
                            ],
                        ]);
                        if (!empty($stateHistoryWithObject)) {
                            if (array_key_exists($stateHistoryWithObject[0]['Service']['uuid'], $downtimeServices)) {
                                $downtimeServices = Hash::insert($downtimeServices,
                                    $stateHistoryWithObject[0]['Service']['uuid'] . '.{n}.data',
                                    [
                                        'host'    => $stateHistoryWithObject[0]['Service']['Host']['name'],
                                        'service' => ($stateHistoryWithObject[0]['Service']['name']) ? $stateHistoryWithObject[0]['Service']['name'] : $stateHistoryWithObject[0]['Service']['Servicetemplate']['name'],
                                    ]
                                );
                            }
                            $downtimeReportData['Hosts'][$stateHistoryWithObject[0]['Service']['Host']['uuid']]['Services'][$serviceUuid] = $this->Downtimereport->generateDowntimereportData(
                                $timeSlices,
                                $stateHistoryWithObject,
                                $this->request->data('Downtimereport.check_hard_state'),
                                false
                            );
                            $downtimeReportData['Hosts'][$stateHistoryWithObject[0]['Service']['Host']['uuid']]['Services'][$serviceUuid] = Hash::insert(
                                $downtimeReportData['Hosts'][$stateHistoryWithObject[0]['Service']['Host']['uuid']]['Services'][$serviceUuid],
                                'Service',
                                [
                                    'id'              => $stateHistoryWithObject[0]['Service']['id'],
                                    'name'            => ($stateHistoryWithObject[0]['Service']['name']) ? $stateHistoryWithObject[0]['Service']['name'] : $stateHistoryWithObject[0]['Service']['Servicetemplate']['name'],
                                    'Servicetemplate' => [
                                        'id'   => $stateHistoryWithObject[0]['Service']['Servicetemplate']['id'],
                                        'name' => $stateHistoryWithObject[0]['Service']['Servicetemplate']['name'],
                                    ],
                                ]
                            );
                            unset($stateHistoryWithObject);
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

    public function createPdfReport() {
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
