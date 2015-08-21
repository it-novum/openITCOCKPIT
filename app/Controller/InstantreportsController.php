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
class InstantreportsController extends AppController {
	public $layout = 'Admin.default';
	public $uses = [
		'Instantreport',
		'Host',
		'Service',
		'Timeperiod',
	];

	public function index() {
//		$containers =  $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOST);
		$userContainerId = $this->MY_RIGHTS;
		//ContainerID => 1 for ROOT Container
		$userContainerIds = $this->Tree->resolveChildrenOfContainerIds($userContainerId);
		$timePeriods = $this->Timeperiod->timeperiodsByContainerId($userContainerIds, 'list');
		$hosts = $this->Host->hostsByContainerId($userContainerIds, 'all');
		$services = $this->Service->servicesByHostContainerIds($userContainerIds);
		$services = Hash::combine($services, ['%s|%s', '{n}.Host.uuid', '{n}.Service.uuid'], ['%s/%s' , '{n}.Host.name', '{n}.{n}.ServiceDescription'], '{n}.Host.name');
		
		if($this->request->is('post') || $this->request->is('put')){
			$this->Instantreport->set($this->request->data);
			if($this->Instantreport->validates()){
				$startDate = $this->request->data('Instantreport.start_date').' 00:00:00';
				$endDate = $this->request->data('Instantreport.end_date').' 23:59:59';
				$instantReportDetails = [
					'startDate'	=> $startDate,
					'endDate'	=> $endDate
				];
				$timeperiod = $this->Timeperiod->find('first', [
					'conditions' => [
						'Timeperiod.id' => $this->request->data('Instantreport.timeperiod_id')
					]
				]);
				$timeSlicesGlobal = Hash::insert(
					$this->Instantreport->createDateRanges(
					$this->request->data('Instantreport.start_date'),
					$this->request->data('Instantreport.end_date'),
					$timeperiod['Timerange']),
					'{n}.is_downtime', false
				);

				$startDateSqlFormat = date('Y-m-d H:i:s', strtotime($startDate));
				$endDateSqlFormat =  date('Y-m-d H:i:s', strtotime($endDate));

				$globalDowntimes = [];
				if($this->request->data('Instantreport.consider_downtimes')){
					$this->loadModel('Systemfailure');
					$globalDowntimes = $this->Systemfailure->find('all', [
						'recursive' => -1,
						'conditions' => [
							'OR' => [
									'"'.$startDateSqlFormat.'"
									BETWEEN Systemfailure.start_time
									AND Systemfailure.end_time',
									'"'.$endDateSqlFormat.'"
									BETWEEN Systemfailure.start_time
									AND Systemfailure.end_time',
									'Systemfailure.start_time BETWEEN "'.$startDateSqlFormat.'"
									AND "'.$endDateSqlFormat.'"'
							]
						],
					]);
					$globalDowntimes = ['Systemfailure' => Hash::extract($globalDowntimes, '{n}.Systemfailure')];
				}
				$this->loadModel(MONITORING_OBJECTS);
				$this->Objects->bindModel([
					'hasMany' => [
						'Statehistory' => [
							'className' => MONITORING_STATEHISTORY
						],
						'Downtime' => [
							'className' => MONITORING_DOWNTIME,
							'conditions' => [
								'Downtime.was_cancelled' => '0'
							]
						]
					],
				]);
				$totalTime = Hash::apply(Hash::map($timeSlicesGlobal, '{n}', ['Instantreport', 'calculateTotalTime']), '{n}','array_sum');
				$instantReportDetails['totalTime'] = $totalTime;
				if($this->request->data('Instantreport.Host')){
					$hostUuids = $this->request->data('Instantreport.Host');
					foreach($hostUuids as $hostUuid){
						$downtimes = [];
						$stateHistoryWithObject = $this->Objects->find('all', [
							'recursive' => -1,
							'contain' => [
								'Host' => [
									'fields' => [
										'id', 'name'
									]
								],
								'Statehistory' => [
									'fields' => [
										'object_id', 'state_time', 'state', 'state_type', 'last_state', 'last_hard_state'
									],
									'conditions' => [
										'Statehistory.state_time
										BETWEEN "'.$startDateSqlFormat.'"
										AND "'.$endDateSqlFormat.'"'
									],
									'order' => [
										'Statehistory.state_time'
									]
								],
								'Downtime' => [
									'fields' => [
										'downtimehistory_id', 'scheduled_start_time AS start_time', 'scheduled_end_time AS end_time'
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
											AND "'.$endDateSqlFormat.'"'
										]
									],
								]
							],
							'conditions' => [
								'Objects.name1' => $hostUuid,
							],
						]);
						if(!empty($stateHistoryWithObject)){
							if(!$this->request->data('Instantreport.consider_downtimes')){
								$timeSlices = $timeSlicesGlobal;
							}else{
								$downtimes = Hash::sort(
									Hash::filter(
										array_merge(
											$globalDowntimes['Systemfailure'],
											$stateHistoryWithObject[0]['Downtime']
										)
									),'{n}.start_time', 'ASC'
								);
								if(!empty($downtimes)){
									$downtimesFiltered = $this->Instantreport->mergeTimeOverlapping(
											array_map(
												function($downtimes){
														return [
															'start_time' => strtotime($downtimes['start_time']),
															'end_time' => strtotime($downtimes['end_time'])
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
								}else{
									$timeSlices = $timeSlicesGlobal;
								}
							}
							$instantReportData['Hosts'][$hostUuid] = $this->Instantreport->generateInstantreportData(
								$totalTime,
								$timeSlices,
								$stateHistoryWithObject,
								$this->request->data('Instantreport.check_hard_state'),
								true
							);
							$instantReportData['Hosts'][$hostUuid] = Hash::insert(
								$instantReportData['Hosts'][$hostUuid],
								'Host',
								[
									'name' => $stateHistoryWithObject[0]['Host']['name']
								]
							);
							unset($timeSlices, $stateHistoryWithObject);
						}else{
							$instantReportData['Hosts'][$hostUuid]['HostsNotMonitored'] = $this->Host->find('list',[
								'conditions' => [
									'Host.uuid' => $hostUuid
								]
							]);
						}
					}
				}elseif($this->request->data('Instantreport.Service')){
					$hostAndServiceUuids = Hash::combine(
							Hash::map(
								$this->request->data, 'Instantreport.Service.{n}', [$this, 'expandServices']
							),
						'{n}.1', '{n}.1', '{n}.0'
					);
					foreach($hostAndServiceUuids as $hostUuid => $serviceUuids){
						$downtimes = [];
						$stateHistoryWithObject = $this->Objects->find('all', [
							'recursive' => -1,
							'contain' => [
								'Host' => [
									'fields' => [
										'id', 'name'
									]
								],
								'Statehistory' => [
									'fields' => [
										'object_id', 'state_time', 'state', 'state_type', 'last_state', 'last_hard_state'
									],
									'conditions' => [
										'Statehistory.state_time
										BETWEEN "'.$startDateSqlFormat.'"
										AND "'.$endDateSqlFormat.'"'
									],
									'order' => [
										'Statehistory.state_time'
									]
								],
								'Downtime' => [
									'fields' => [
										'downtimehistory_id', 'scheduled_start_time AS start_time', 'scheduled_end_time AS end_time'
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
											AND "'.$endDateSqlFormat.'"'
										]
									],
								]
							],
							'conditions' => [
								'Objects.name1' => $hostUuid,
							],
						]);
						if(!empty($stateHistoryWithObject)){
							if(!$this->request->data('Instantreport.consider_downtimes')){
								$timeSlices = $timeSlicesGlobal;
							}else{
								$downtimes = Hash::sort(
									Hash::filter(
										array_merge(
											$globalDowntimes['Systemfailure'],
											$stateHistoryWithObject[0]['Downtime']
										)
									),'{n}.start_time', 'ASC'
								);
								if(!empty($downtimes)){
									$downtimesFiltered = $this->Instantreport->mergeTimeOverlapping(
											array_map(
												function($downtimes){
														return [
															'start_time' => strtotime($downtimes['start_time']),
															'end_time' => strtotime($downtimes['end_time'])
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
								}else{
									$timeSlices = $timeSlicesGlobal;
								}
							}
							$instantReportData['Hosts'][$hostUuid] = $this->Instantreport->generateInstantreportData(
								$totalTime,
								$timeSlices,
								$stateHistoryWithObject,
								$this->request->data('Instantreport.check_hard_state'),
								true
							);
							$instantReportData['Hosts'][$hostUuid] = Hash::insert(
								$instantReportData['Hosts'][$hostUuid],
								'Host',
								[
									'name' => $stateHistoryWithObject[0]['Host']['name']
								]
							);
							unset($timeSlices, $stateHistoryWithObject);
						}else{
							$instantReportData['Hosts'][$hostUuid]['HostsNotMonitored'] = $this->Host->find('list',[
								'conditions' => [
									'Host.uuid' => $hostUuid
								]
							]);
						}
						foreach($serviceUuids as $serviceUuid){
							$downtimes = [];
							$stateHistoryWithObject = $this->Objects->find('all', [
								'recursive' => -1,
								'contain' => [
									'Service' => [
										'Host' => [
											'fields' => [
												'id', 'name'
											]
										],
										'Servicetemplate' => [
											'fields' => [
												'id', 'name'
											]
										],
										'fields' => [
											'id', 'name'
										]
									],
									'Statehistory' => [
										'fields' => [
											'object_id', 'state_time', 'state', 'state_type', 'last_state', 'last_hard_state'
										],
										'conditions' => [
											'Statehistory.state_time
											BETWEEN "'.$startDateSqlFormat.'"
											AND "'.$endDateSqlFormat.'"'
										],
									],
									'Downtime' => [
										'fields' => [
											'downtimehistory_id', 'scheduled_start_time AS start_time', 'scheduled_end_time AS end_time', 'author_name', 'comment_data'
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
												AND "'.$endDateSqlFormat.'"'
											]
										],
									]
								],
								'conditions' => [
									'Objects.name2' => $serviceUuid
								]
							]);
							if(!empty($stateHistoryWithObject)){
								if(!$this->request->data('Instantreport.consider_downtimes')){
									$timeSlices = $timeSlicesGlobal;
								}else{
									$downtimes = Hash::sort(
										Hash::filter(
											array_merge(
												$globalDowntimes['Systemfailure'],
												$stateHistoryWithObject[0]['Downtime']
											)
										),'{n}.start_time', 'ASC'
									);
									if(!empty($downtimes)){
										$downtimesFiltered = $this->Instantreport->mergeTimeOverlapping(
												array_map(
													function($downtimes){
															return [
																'start_time' => strtotime($downtimes['start_time']),
																'end_time' => strtotime($downtimes['end_time'])
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
									}else{
										$timeSlices = $timeSlicesGlobal;
									}
								}
								$instantReportData['Hosts'][$hostUuid]['Services'][$serviceUuid] = $this->Instantreport->generateInstantreportData(
									$totalTime,
									$timeSlices,
									$stateHistoryWithObject,
									$this->request->data('Instantreport.check_hard_state'),
									false
								);

								$instantReportData['Hosts'][$hostUuid]['Services'][$serviceUuid]= Hash::insert(
									$instantReportData['Hosts'][$hostUuid]['Services'][$serviceUuid],
									'Service',
									[
										'name' => ($stateHistoryWithObject[0]['Service']['name'])?$stateHistoryWithObject[0]['Service']['name']:$stateHistoryWithObject[0]['Service']['Servicetemplate']['name']
									]
								);
								unset($timeSlices, $stateHistoryWithObject);
							}else{
								$instantReportData['Hosts'][$hostUuid]['Services']['ServicesNotMonitored'][$serviceUuid] = $this->Service->find('first',[
									'recursive' => -1,
									'contain' => [
										'Host' => [
											'fields' => 'Host.name'
										],
										'Servicetemplate' => [
											'fields' => 'Servicetemplate.name'
										],
									],
									'conditions' => [
										'Service.uuid' => $serviceUuid
									],
									'fields' => [
										'Service.name'
									]
								]);
							}

						}
					}
				}

				if($this->request->data('Instantreport.report_format') == 'pdf'){
					$this->Session->write('instantReportData', $instantReportData);
					$this->Session->write('instantReportDetails', $instantReportDetails);
					$this->redirect([
						'action' => 'createPdfReport',
						'ext'	=> 'pdf'
					]);

				}else{
					$this->set(compact(['instantReportData', 'instantReportDetails']));
					$this->render('/Elements/load_instant_report_data');
				}

			}
		}
		$this->set([
//			'container' => $container,
			'timeperiods' => $timePeriods,
			'hosts' => $hosts,
			'services' => $services,
			'userContainerId' => $userContainerId,
		]);
	}

	public function createPdfReport(){
		$this->set('instantReportData', $this->Session->read('instantReportData'));
		$this->set('instantReportDetails', $this->Session->read('instantReportDetails'));
		if($this->Session->check('instantReportData')){
			$this->Session->delete('instantReportData');
		}
		if($this->Session->check('instantReportDetails')){
			$this->Session->delete('instantReportDetails');
		}

		$binary_path = '/usr/bin/wkhtmltopdf';
		if(file_exists('/usr/local/bin/wkhtmltopdf')){
			$binary_path = '/usr/local/bin/wkhtmltopdf';
		}
		$this->pdfConfig = [
			'engine' =>'CakePdf.WkHtmlToPdf',
			'margin' => [
				'bottom'=>15,
				'left'=>0,
				'right'=>0,
				'top'=>15
			],
			'encoding'=>'UTF-8',
			'download' =>true,
			'binary' => $binary_path,
			'orientation' => 'portrait',
			'filename' => 'Instantreport.pdf',
			'no-pdf-compression' => '*',
			'image-dpi'	=> '900',
			'background' => true,
			'no-background' => false,
		];
	}

	public function expandServices($data){
		return explode('|', $data);
	}
}
