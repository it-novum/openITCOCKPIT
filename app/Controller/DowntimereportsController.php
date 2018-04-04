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

/**
 * @property Downtimereport $Downtimereport
 * @property Host $Host
 * @property Service $Service
 * @property Timeperiod $Timeperiod
 */
class DowntimereportsController extends AppController {
    public $layout = 'Admin.default';
    public $uses = [
        'Downtimereport',
        'Host',
        'Service',
        'Timeperiod',
        MONITORING_STATEHISTORY_HOST,
        MONITORING_STATEHISTORY_SERVICE,
        MONITORING_DOWNTIME_HOST,
        MONITORING_DOWNTIME_SERVICE
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
                    'endDate' => $endDate,
                ];

                $startTimeStamp = strtotime($startDate);
                $endTimeStamp = strtotime($endDate);

                $downtimeReportDetails = [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ];
                $timeperiod = $this->Timeperiod->find('first', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data('Downtimereport.timeperiod_id'),
                    ],
                ]);
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
                        $timeperiod['Timerange']
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
                            'recursive' => -1,
                            'conditions' => [
                                'Host.uuid' => $hostUuid,
                            ],
                            'fields' => [
                                'Host.id',
                                'Host.uuid',
                                'Host.name',
                                'Host.description',
                                'Host.address'
                            ],
                            'condition' => [
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
                                        'id' => $host['Host']['id'],
                                        'name' => $host['Host']['name'],
                                        'description' => $host['Host']['description'],
                                        'address' => $host['Host']['address'],
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

                            }else {
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
                                'recursive' => -1,
                                'contain' => [
                                    'Servicetemplate' => [
                                        'fields' => [
                                            'Servicetemplate.id',
                                            'Servicetemplate.name'
                                        ]
                                    ],
                                    'Host' => [
                                        'fields' => [
                                            'Host.uuid',
                                            'Host.name'
                                        ]
                                    ]
                                ],
                                'fields' => [
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
                                            'host' => $service['Host']['name'],
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
                                        'id' => $service['Service']['id'],
                                        'name' => ($service['Service']['name']) ? $service['Service']['name'] : $service['Servicetemplate']['name'],
                                        'Servicetemplate' => [
                                            'id' => $service['Servicetemplate']['id'],
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
                            'ext' => 'pdf',
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
                            'endDate' => CakeTime::format(
                                $downtimeReportDetails['endDate'], '%Y, %m, %d', false, $this->Auth->user('timezone')
                            ),
                        ]);
                        $this->Frontend->setJson('hostDowntimes', array_map(
                                function ($filteredHostsDowntimes) {
                                    return [
                                        'author_name' => $filteredHostsDowntimes['author_name'],
                                        'comment_data' => $filteredHostsDowntimes['comment_data'],
                                        'host' => $filteredHostsDowntimes['data']['host'],
                                        'scheduled_start_time' => CakeTime::format(
                                            $filteredHostsDowntimes['scheduled_start_time'], '%Y %m %d %H:%M', false, $this->Auth->user('timezone')
                                        ),
                                        'scheduled_end_time' => CakeTime::format(
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
                                        'author_name' => $filteredServicesDowntimes['author_name'],
                                        'comment_data' => $filteredServicesDowntimes['comment_data'],
                                        'host' => $filteredServicesDowntimes['data']['host'],
                                        'service' => $filteredServicesDowntimes['data']['service'],
                                        'scheduled_start_time' => CakeTime::format(
                                            $filteredServicesDowntimes['scheduled_start_time'], '%Y %m %d %H:%M', false, $this->Auth->user('timezone')
                                        ),
                                        'scheduled_end_time' => CakeTime::format(
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
            'engine' => 'CakePdf.WkHtmlToPdf',
            'margin' => [
                'bottom' => 15,
                'left' => 0,
                'right' => 0,
                'top' => 15,
            ],
            'encoding' => 'UTF-8',
            'download' => true,
            'binary' => $binary_path,
            'orientation' => 'portrait',
            'filename' => 'Downtimereport.pdf',
            'no-pdf-compression' => '*',
            'image-dpi' => '900',
            'background' => true,
            'no-background' => false,
        ];
    }
}
