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

class Instantreport extends AppModel {

    CONST EVALUATION_HOSTS = 1;
    CONST EVALUATION_HOSTS_SERVICES = 2;
    CONST EVALUATION_SERVICES = 3;

    CONST TYPE_HOSTGROUPS = 1;
    CONST TYPE_HOSTS = 2;
    CONST TYPE_SERVICEGROUPS = 3;
    CONST TYPE_SERVICES = 4;

    CONST FORMAT_PDF = 1;
    CONST FORMAT_HTML = 2;

    CONST STATE_SOFT_HARD = 1;
    CONST STATE_HARD_ONLY = 2;

    CONST SEND_DAILY = 1;
    CONST SEND_WEEKLY = 2;
    CONST SEND_MONTHLY = 3;
    CONST SEND_YEARLY = 4;

    public $name = 'Instantreport';
    public $actsAs = ['DateRange'];
    var $hasAndBelongsToMany = [
        'Hostgroup'    => [
            'className'             => 'Hostgroup',
            'joinTable'             => 'instantreports_to_hostgroups',
            'foreignKey'            => 'instantreport_id',
            'associationForeignKey' => 'hostgroup_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'Host'    => [
            'className'             => 'Host',
            'joinTable'             => 'instantreports_to_hosts',
            'foreignKey'            => 'instantreport_id',
            'associationForeignKey' => 'host_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'Servicegroup'    => [
            'className'             => 'Servicegroup',
            'joinTable'             => 'instantreports_to_servicegroups',
            'foreignKey'            => 'instantreport_id',
            'associationForeignKey' => 'servicegroup_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'Service'    => [
            'className'             => 'Service',
            'joinTable'             => 'instantreports_to_services',
            'foreignKey'            => 'instantreport_id',
            'associationForeignKey' => 'service_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'User'    => [
            'className'             => 'User',
            'joinTable'             => 'instantreports_to_users',
            'foreignKey'            => 'instantreport_id',
            'associationForeignKey' => 'user_id',
            'unique'                => true,
            'dependent'             => true,
        ],
    ];
    var $belongsTo = [
        'Container'    => [
            'className'  => 'Container',
            'foreignKey' => 'container_id',
        ],
        'Timeperiod'    => [
            'className'  => 'Timeperiod',
            'foreignKey' => 'timeperiod_id',
        ],
    ];
    public $validate = [
        'type' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'This field cannot be left blank',
            ],
        ],
        'container_id' => [
            'rule'     => 'notBlank',
            'message'  => 'This field cannot be left blank.',
            'required' => true,
        ],
        'report_format'    => [
            'checkIfEmail'      => [
                'rule'     => 'checkIfEmail',
                'message'  => 'You can only select PDF option with sending emails.',
            ]
        ],
        'start_date'    => [
            'notBlankDates'      => [
                'rule'     => 'notBlankDates',
                'message'  => 'This field cannot be left blank',
            ],
            'date'          => [
                'rule'     => ['date', 'dmy'],
                'message'  => 'Enter a valid date',
            ],
            'validateDates' => [
                'rule'     => ['validateDates', ['start_date', 'end_date']],
                'message'  => '"To" must not be earlier than "From"',
            ],
        ],
        'end_date'      => [
            'notBlankDates'      => [
                'rule'     => 'notBlankDates',
                'message'  => 'This field cannot be left blank',
            ],
            'date'          => [
                'rule'     => ['date', 'dmy'],
                'message'  => 'Enter a valid date',
            ],
            'validateDates' => [
                'rule'     => ['validateDates', ['start_date', 'end_date']],
                'message'  => '"To" must not be earlier than "From"',
            ],
        ],
        'Hostgroup'          => [
            'atLeastOne' => [
                'rule'     => ['atLeastOne'],
                'message'  => 'You must specify at least one option',
            ],
        ],
        'Servicegroup'       => [
            'atLeastOne' => [
                'rule'     => ['atLeastOne'],
                'message'  => 'You must specify at least one option',
            ],
        ],
        'Host'          => [
            'atLeastOne' => [
                'rule'     => ['atLeastOne'],
                'message'  => 'You must specify at least one option',
            ],
        ],
        'Service'       => [
            'atLeastOne' => [
                'rule'     => ['atLeastOne'],
                'message'  => 'You must specify at least one option',
            ],
        ],
        'User'          => [
            'atLeastOneUser' => [
                'rule'     => ['atLeastOneUser'],
                'message'  => 'You must specify at least one user',
            ],
        ],
        'timeperiod_id' => [
            'notBlank'   => [
                'rule'     => ['notZero', 'timeperiod_id'],
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'timeRanges' => [
                'rule'     => ['timeRanges', 'timeperiod_id'],
                'message'  => 'There are no time frames defined.Time evaluation report data is not available for the selected period.',
                'required' => true,
            ],
        ],
    ];

    public function checkIfEmail(){
        return $this->data['Instantreport']['send_email'] !== '1' || $this->data['Instantreport']['report_format'] == self::FORMAT_PDF;
    }

    public function beforeSave($options = []) {
        $this->data['Instantreport']['start_date'] = date('Y-m-d H:i:s', strtotime($this->data['Instantreport']['start_date']));
        $this->data['Instantreport']['end_date'] = date('Y-m-d H:i:s', strtotime($this->data['Instantreport']['end_date']));
        return true;
    }

    /*
    Custom validation rule for "Hosts", "Services", "Hostgroups", "Servicegroups" fields
    */
    public function atLeastOne() {
        switch($this->data['Instantreport']['type']){
            case self::TYPE_HOSTGROUPS:
                return isset($this->data['Instantreport']['Hostgroup']) && !empty($this->data['Instantreport']['Hostgroup']);
                break;

            case self::TYPE_SERVICEGROUPS:
                return isset($this->data['Instantreport']['Servicegroup']) && !empty($this->data['Instantreport']['Servicegroup']);
                break;

            case self::TYPE_HOSTS:
                return isset($this->data['Instantreport']['Host']) && !empty($this->data['Instantreport']['Host']);
                break;

            case self::TYPE_SERVICES:
                return isset($this->data['Instantreport']['Service']) && !empty($this->data['Instantreport']['Service']);
                break;
        }

        return true;
    }

    public function atLeastOneUser(){
        return $this->data['Instantreport']['send_email'] !== '1' || !empty($this->data['Instantreport']['User']);
    }

    public function notBlankDates(){
        return !isset($this->data['Instantreport']['send_email']) || $this->data['Instantreport']['send_email'] !== '1';
    }

    public function validateDates() {
        return (strtotime($this->data['Instantreport']['start_date']) <= strtotime($this->data['Instantreport']['end_date']));
    }

    public function notZero($fieldName)
    {
        return !($this->data[$this->name][key($fieldName)] == '0');
    }

    public function timeRanges($data)
    {
        $timeperiod = ClassRegistry::init('Timeperiod', false);
        $selectedTimeperiod = $timeperiod->findById($data['timeperiod_id']);

        return !(empty($selectedTimeperiod['Timerange']));
    }

//    public function validateType(){
//        $success = false;
//        switch($this->data['Instantreport']['evaluation']){
//            case self::EVALUATION_HOSTS:
//            case self::EVALUATION_HOSTS_SERVICES:
//                $success = in_array($this->data['Instantreport']['type'], [self::TYPE_HOSTS, self::TYPE_HOSTGROUPS]);
//                break;
//
//            case self::EVALUATION_SERVICES:
//                $success = true;
//                break;
//
//        }
//        return $success;
//    }

    /**
     * Generate date for host/service objects
     *
     * @param integer $totalTime              total time of all time ranges
     * @param array   $timeSlices             => Time ranges for evaluation period
     * @param array   $stateHistoryWithObject => Array with object data and state history array
     * @param boolean $checkHard_state        use hard and soft state or only hard state for evaluation
     * @param boolean $objectHost             , if object host => 1, else service => 0
     * @param         array                   downtimes array, use downtimes array if report setting
     *                                                  "consider_downtimes" => true
     *
     * @return array $instantreportData for host/service object
     */
    public function generateInstantreportData($totalTime, $timeSlices, $stateHistoryWithObject, $checkHardState, $objectHost = false)
    {
        $stateArray = array_fill(0, ($objectHost) ? 3 : 4, 0); // host states => 0,1,2; service statea => 0,1,2,3
        $stateOk = 0;
        $stateUnknown = ($objectHost) ? 2 : 3;//if the end of date in the future
        $evaluationData = $stateArray;

        $stateHistoryArray = Hash::extract($stateHistoryWithObject, '{n}.Statehistory.{n}');
        $outageState = ($objectHost) ? 1 : 2; // 1 for host down and 2 for service critical
        $setInitialState = false;
        $currentState = 0;
        foreach ($timeSlices as $timeSliceKey => $timeSlice) {
            $time = $timeSlice['start'];
            if ($time > strtotime('today 23:59:59')) { // ignore time_slice in the future
                $currentState = $stateUnknown;
            }
            $isDowntime = $timeSlice['is_downtime'];
            reset($stateHistoryArray);
            foreach ($stateHistoryArray as $key => $stateHistory) {
                $stateTimeTimestamp = strtotime($stateHistory['state_time']);
                if (!$setInitialState) {
                    $currentState = ($checkHardState) ? $stateHistory['last_hard_state'] : $stateHistory['last_state'];
                    $currentState = ($currentState == -1) ? 0 : $currentState;
                    $setInitialState = true;
                }
                if ($stateTimeTimestamp >= $timeSlice['end']) {
                    // if state time after time slice
                    break;
                }
                if ($stateTimeTimestamp <= $timeSlice['start']) {
                    $currentState = ($stateHistory['state'] == 0 || !$checkHardState || ($checkHardState && ($checkHardState && $stateHistory['state_type']))) ? $stateHistory['state'] : $currentState;
                    unset($stateHistoryArray[$key]);
                    continue;
                }
                if ($stateTimeTimestamp > $timeSlice['start']) {
                    //if outage in downtime add time for state "ok"
                    if (($currentState == $outageState) && $isDowntime) {
                        $evaluationData[0] += $stateTimeTimestamp - $time;
                    } else {
                        $evaluationData[$currentState] += $stateTimeTimestamp - $time;
                    }
                    $currentState = ($stateHistory['state'] == 0 || !$checkHardState || ($checkHardState && ($checkHardState && $stateHistory['state_type']))) ? $stateHistory['state'] : $currentState;
                    $time = $stateTimeTimestamp;
                    unset($stateHistoryArray[$key]);
                }
            }
            //if outage in downtime add time for state "ok"
            if ($currentState == $outageState && $isDowntime) {
                $evaluationData[$stateOk] += $timeSlice['end'] - $time;
            } else {
                $evaluationData[$currentState] += $timeSlice['end'] - $time;
            }
        }
        unset($timeSlices, $stateHistoryWithObject);

        return $evaluationData;
    }

    public static function calculateTotalTime($timeSlice) {
        return $timeSlice['end'] - $timeSlice['start'];
    }

    public function getTypes() {
        return [
            self::TYPE_HOSTGROUPS => __('Host groups'),
            self::TYPE_SERVICEGROUPS => __('Service groups'),
            self::TYPE_HOSTS => __('Hosts'),
            self::TYPE_SERVICES => __('Services')
        ];
    }

    public function getEvaluations(){
        return [
            self::EVALUATION_HOSTS => ['label' => __('Hosts '), 'icon' => 'desktop'],
            self::EVALUATION_HOSTS_SERVICES => ['label' => __('Hosts and Services '), 'icon' => 'gears'],
            self::EVALUATION_SERVICES => ['label' => __('Services '), 'icon' => 'gears']
        ];
    }

    public function getReportFormats(){
        return [
            self::FORMAT_PDF => __('PDF'),
            self::FORMAT_HTML => __('HTML')
        ];
    }

    public function getReflectionStates(){
        return [
            self::STATE_SOFT_HARD => __('soft and hard state'),
            self::STATE_HARD_ONLY => __('only hard state')
        ];
    }

    public function getSendIntervals(){
        return [
            self::SEND_DAILY => __('Daily'),
            self::SEND_WEEKLY => __('Weekly'),
            self::SEND_MONTHLY => __('Monthly'),
            self::SEND_YEARLY => __('Yearly')
        ];
    }
}
