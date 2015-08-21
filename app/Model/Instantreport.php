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

class Instantreport extends AppModel{

	public $name = 'Instantreport';
	public $useTable = false;
	public $actsAs = ['DateRange'];
	public $validate = [
		'start_date' => [
			'notEmpty' => [
				'rule' => 'notEmpty',
				'required'	=> true,
				'message' => 'This field cannot be left blank'
			],
			'date' => [
				'rule' => ['date', 'dmy'],
				'required'	=> true,
				'message' => 'Enter a valid date',
			],
			'validateDates' => [
				'rule' => ['validateDates', ['start_date', 'end_date']],
				'required'	=> true,
				'message' => '"To" must not be earlier than "From"'
			]
		],
		'end_date' => [
			'notEmpty' => [
				'rule' => 'notEmpty',
				'required'	=> true,
				'message' => 'This field cannot be left blank'
			],
			'date' => [
				'rule' => ['date', 'dmy'],
				'required'	=> true,
				'message' => 'Enter a valid date',
			],
			'validateDates' => [
				'rule' => ['validateDates', ['start_date', 'end_date']],
				'required'	=> true,
				'message' => '"To" must not be earlier than "From"'
			]
		],
		'Host' => [
			'atLeastOne' => [
				'rule' => ['atLeastOne'],
				'message' => 'You must specify at least one host or service.',
				'required' => true
			]
		],
		'Service' => [
			'atLeastOne' => [
				'rule' => ['atLeastOne'],
				'message' => 'You must specify at least one host or service.',
				'required' => true
			]
		],
		'timeperiod_id' => [
			'notEmpty' => [
				'rule'		=> ['notZero', 'timeperiod_id'],
				'message'	=> 'This field cannot be left blank.',
				'required'	=> true
			],
			'timeRanges' => [
				'rule'		=> ['timeRanges', 'timeperiod_id'],
				'message'	=> 'There are no time frames defined.Time evaluation report data is not available for the selected period.',
				'required'	=> true
			]
		],
	];

	/*
	Custom validation rule for "Hosts" and/or "Services" fields
	*/
	public function atLeastOne($data) {
		return ($this->data['Instantreport']['Host'] || $this->data['Instantreport']['Service']);
	}

	public function validateDates(){
		return (strtotime($this->data['Instantreport']['start_date']) <= strtotime($this->data['Instantreport']['end_date']));
	}

	public function notZero($fieldName){
		return !($this->data[$this->name][key($fieldName)]=='0');
	}

	public function timeRanges($data){
		$timeperiod = ClassRegistry::init('Timeperiod', false);
		$selectedTimeperiod = $timeperiod->findById($data['timeperiod_id']);
		return !(empty($selectedTimeperiod['Timerange']));
	}

	/**
	* Generate date for host/service objects
	* @param integer $totalTime total time of all time ranges
	* @param array $timeSlices => Time ranges for evaluation period
	* @param array $stateHistoryWithObject => Array with object data and state history array
	* @param boolean $checkHard_state use hard and soft state or only hard state for evaluation
	* @param boolean $objectHost, if object host => 1, else service => 0
	* @param array downtimes array, use downtimes array if report setting "consider_downtimes" => true
	* @return array $instantreportData for host/service object
	*/
	public function generateInstantreportData($totalTime, $timeSlices, $stateHistoryWithObject, $checkHardState, $objectHost = false){
		$stateArray = array_fill(0, ($objectHost)?3:4, 0); // host states => 0,1,2; service statea => 0,1,2,3
		$stateOk = 0;
		$stateUnknown = ($objectHost)?2:3;//if the end of date in the future
		$evaluationData = $stateArray;

		$stateHistoryArray = Hash::extract($stateHistoryWithObject, '{n}.Statehistory.{n}');
		$outageState = ($objectHost)?1:2; // 1 for host down and 2 for service critical
		$setInitialState = false;
		$currentState = 0;
		foreach($timeSlices as $timeSliceKey => $timeSlice){
			$time = $timeSlice['start'];
			if($time > strtotime('today 23:59:59')){ // ignore time_slice in the future
				$currentState = $stateUnknown;
			}
			$isDowntime = $timeSlice['is_downtime'];
			reset($stateHistoryArray);
			foreach($stateHistoryArray as $key => $stateHistory){
				$stateTimeTimestamp = strtotime($stateHistory['state_time']);
				if(!$setInitialState){
					$currentState = ($checkHardState)?$stateHistory['last_hard_state']:$stateHistory['last_state'];
					$currentState = ($currentState == -1)?0:$currentState;
					$setInitialState = true;
				}
				if($stateTimeTimestamp >= $timeSlice['end']) {
					// if state time after time slice
					break;
				}
				if($stateTimeTimestamp <= $timeSlice['start']) {
					$currentState = ($stateHistory['state'] == 0 || !$checkHardState || ($checkHardState && ($checkHardState && $stateHistory['state_type'])))?$stateHistory['state']:$currentState;
					unset($stateHistoryArray[$key]);
					continue;
				}
				if($stateTimeTimestamp > $timeSlice['start']) {
					//if outage in downtime add time for state "ok"
					if(($currentState == $outageState) && $isDowntime){
						$evaluationData[0] += $stateTimeTimestamp - $time;
					}else{
						$evaluationData[$currentState] += $stateTimeTimestamp - $time;
					}
					$currentState = ($stateHistory['state'] == 0 || !$checkHardState || ($checkHardState && ($checkHardState && $stateHistory['state_type'])))?$stateHistory['state']:$currentState;
					$time = $stateTimeTimestamp;
					unset($stateHistoryArray[$key]);
				}
			}
			//if outage in downtime add time for state "ok"
			if($currentState == $outageState && $isDowntime){
				$evaluationData[$stateOk] += $timeSlice['end'] - $time;
			}else{
				$evaluationData[$currentState] += $timeSlice['end'] - $time;
			}
		}
		unset($timeSlices, $stateHistoryWithObject);
		return $evaluationData;
	}

		public static function calculateTotalTime($timeSlice){
			return $timeSlice['end'] - $timeSlice['start'];
	}
}
