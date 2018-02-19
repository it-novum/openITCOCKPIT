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

use itnovum\openITCOCKPIT\Core\SystemdowntimesConditions;

class Systemdowntime extends AppModel {


    var $validate = [
        'downtimetype' => [
            'rule'    => ['checkDowntimeSettings'],
            'message' => 'An error occurred',
        ],
        'object_id'    => [
            'multiple' => [
                'rule'     => ['multiple', ['min' => 1]],
                'message'  => 'Please select at least 1 object',
                'required' => true,
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'Please select at least 1 object',
                'required' => true,
            ],
        ],
        'from_date'    => [
            'notBlank'   => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'date'       => [
                'rule'    => ['date', 'dmy'],
                'message' => 'Please enter a valid date',
            ],
            'comparison' => [
                'rule'     => ['dateComparison'],
                'message'  => 'The "from" date must occur before the "to" date 123',
                'required' => true
            ],
        ],
        'from_time'    => [
            'notBlank'   => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'time'       => [
                'rule'    => 'time',
                'message' => 'Please enter a valid time',
            ],
            'comparison' => [
                'rule'     => ['timeComparison'],
                'message'  => 'The "from" time must occur before the "to" time',
                'required' => true
            ],
        ],
        'to_date'      => [
            'notBlank'      => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'date'          => [
                'rule'    => ['date', 'dmy'],
                'message' => 'Please enter a valid date',
            ],
            'checkPastDate' => [
                'rule'     => ['checkPastDate'],
                'message'  => 'The "to" date should be in the future and not the past',
                'required' => true
            ],
            'comparison'    => [
                'rule'     => ['dateComparison'],
                'message'  => 'The "from" date must occur before the "to" date',
                'required' => true
            ],
        ],
        'to_time'      => [
            'notBlank'      => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'time'          => [
                'rule'    => 'time',
                'message' => 'Please enter a valid time',
            ],
            'checkPastTime' => [
                'rule'     => ['checkPastTime'],
                'message'  => 'The "to" time should be in the future and not the past',
                'required' => true
            ],
            'comparison'    => [
                'rule'     => ['timeComparison'],
                'message'  => 'The "from" time must occur before the "to" time',
                'required' => true
            ],
        ],
        'comment'      => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'weekdays'     => [
            'atLeastOne' => [
                'rule'     => ['atLeastOne'],
                'message'  => 'You must specify at least one week day or day of month',
                'required' => true,
            ],
        ],
        'day_of_month' => [
            'atLeastOne' => [
                'rule'     => ['atLeastOne'],
                'message'  => 'You must specify at least one week day or day of month',
                'required' => true,
            ],
        ],
        'duration'     => [
            'notBlank'         => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'validateDuration' => [
                'rule'     => ['validateDuration'],
                'message'  => 'The duration should be numeric value',
                'required' => true
            ]
        ]
    ];

    public function checkDowntimeSettings() {
        if (isset($this->data['Systemdowntime']['downtimetype'])) {
            if ($this->data['Systemdowntime']['is_recurring'] == 1) {
                return $this->validateRecurring($this->data);
            }
            return true;
        }
        return false;
    }

    public function validateRecurring($request) {
        //Validate days from selectbox
        if ($request['Systemdowntime']['weekdays'] != '') {
            $valideDays = [1, 2, 3, 4, 5, 6, 7];
            $_expldoe = explode(',', $request['Systemdowntime']['weekdays']);
            foreach ($_expldoe as $day) {
                if ($day !== '' && $day !== null && !in_array($day, $valideDays)) {
                    return false;
                }
            }
        }

        if ($request['Systemdowntime']['day_of_month'] != '') {
            $valideDays = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31];
            $_expldoe = explode(',', $request['Systemdowntime']['day_of_month']);
            foreach ($_expldoe as $day) {
                if ($day !== '' && $day !== null && !in_array($day, $valideDays)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function validateDuration() {
        if ($this->data['Systemdowntime']['is_recurring'] == 0) {
            return true;
        }
        if (empty($this->data['Systemdowntime']['duration'])) {
            return false;
        }
        if (!empty($this->data['Systemdowntime']['duration']) &&
            is_numeric($this->data['Systemdowntime']['duration']) && $this->data['Systemdowntime']['duration'] > 0) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getValidationRulesForRecurringDowntimes(){
        $validate = Hash::merge(
            $this->validate,
            [
                'from_date' => [
                    'notBlank' => [
                        'required'   => false,
                        'allowEmpty' => true,
                    ],
                ],
                'to_date'   => [
                    'notBlank' => [
                        'required'   => false,
                        'allowEmpty' => true,
                    ]
                ],
                'from_time' => [
                    'notBlank' => [
                        'required'   => true,
                        'allowEmpty' => false,
                    ],
                ],
                'to_time'   => [
                    'notBlank' => [
                        'required'   => false,
                        'allowEmpty' => true,
                    ],
                ]
            ]
        );
        return $validate;
    }


    /**
     * Custom Validation Rule: Ensures a selected date is in the past.
     *
     * @param array $check Contains the value passed from the view to be validated
     * @return boolean False if in the future, True otherwise
     */
    public function checkPastDate() {
        if ($this->data[$this->name]['is_recurring']) {
            return true;
        }
        //return CakeTime::fromString($this->data[$this->alias]['to_date'] . ' ' . $this->data[$this->alias]['to_time']) > time();
        return CakeTime::fromString($this->data[$this->alias]['to_date']) >= CakeTime::fromString(date("d.m.Y", time()));
    }

    public function checkPastTime() {
        if (CakeTime::fromString($this->data[$this->alias]['to_date']) > CakeTime::fromString(date("d.m.Y", time()))) {
            return true;
        }
        return CakeTime::fromString($this->data[$this->alias]['to_date'] . ' ' . $this->data[$this->alias]['to_time']) > time();
    }

    function dateComparison() {
        if ($this->data[$this->name]['is_recurring']) {
            return true;
        }
        return Validation::comparison(CakeTime::fromString($this->data[$this->alias]['from_date']), '<=', CakeTime::fromString($this->data[$this->alias]['to_date']));
    }

    function timeComparison() {
        if ($this->data[$this->name]['is_recurring']) {
            return Validation::time($this->data[$this->alias]['from_time']);
        }
        if (Validation::comparison($this->data[$this->alias]['from_date'], '==', $this->data[$this->alias]['to_date'])) {
            return Validation::comparison($this->data[$this->alias]['from_time'], '<', $this->data[$this->alias]['to_time']);
        } else {
            return true;
        }
        return false;
    }

    /*
    Custom validation rule for recurring downtimes
    */
    public function atLeastOne() {
        if (!$this->data[$this->name]['is_recurring']) {
            return true;
        }
        return !empty($this->data[$this->name]['weekdays']) || !empty($this->data[$this->name]['day_of_month']);
    }

    /**
     * @return array
     */
    public function getRecurringHostDowntimesQuery(SystemdowntimesConditions $Conditions, $filterConditions = []) {
        $this->bindModel([
            'belongsTo' => [
                'Host' => [
                    'className'  => 'Host',
                    'foreignKey' => 'object_id',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Systemdowntime.objecttype_id' => OBJECT_HOST
                    ]
                ],
            ]
        ]);
        $query = [
            'recursive'  => -1,
            'contain'    => [
                'Host' => [
                    'Container' => [
                        'conditions' => [
                            'Container.id' => $Conditions->getContainerIds()
                        ]
                    ]
                ],
                'Host.id',
                'Host.uuid',
                'Host.name'
            ],
            'conditions' => [],
            'order'      => $Conditions->getOrder()
        ];

        $query['conditions'] = Hash::merge($query['conditions'], $filterConditions);

        return $query;
    }

    /**
     * @return array
     */
    public function getRecurringServiceDowntimesQuery(SystemdowntimesConditions $Conditions, $filterConditions = []) {
        $this->virtualFields['servicename'] = 'IF((Service.name IS NULL OR Service.name=""), Servicetemplate.name, Service.name)';
        $query = [
            'recursive'  => -1,
            'fields'     => [
                'Systemdowntime.*',
                'Service.id',
                'Service.host_id',
                'Service.name',
                'Servicetemplate.name',
                'Host.id',
                'Host.name'
            ],
            'joins'      => [
                [
                    'table'      => 'services',
                    'type'       => 'INNER',
                    'alias'      => 'Service',
                    'conditions' => [
                        'Service.id = Systemdowntime.object_id',
                        'Systemdowntime.objecttype_id' => OBJECT_SERVICE
                    ]
                ], [
                    'table'      => 'servicetemplates',
                    'type'       => 'INNER',
                    'alias'      => 'Servicetemplate',
                    'conditions' => 'Servicetemplate.id = Service.servicetemplate_id',
                ], [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Service.host_id = Host.id',
                ], [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'INNER',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ],
            ],
            'conditions' => [
                'HostsToContainers.container_id' => $Conditions->getContainerIds()
            ],
            'order'      => $Conditions->getOrder()
        ];

        $query['conditions'] = Hash::merge($query['conditions'], $filterConditions);

        return $query;
    }

    /**
     * @return array
     */
    public function getRecurringHostgroupDowntimesQuery(SystemdowntimesConditions $Conditions, $filterConditions = []) {
        $query = [
            'recursive'  => -1,
            'fields'     => [
                'Systemdowntime.*',
                'Hostgroup.id',
                'Hostgroup.container_id',
                'Container.name'
            ],
            'joins'      => [
                [
                    'table'      => 'hostgroups',
                    'type'       => 'INNER',
                    'alias'      => 'Hostgroup',
                    'conditions' => [
                        'Hostgroup.id = Systemdowntime.object_id',
                        'Systemdowntime.objecttype_id' => OBJECT_HOSTGROUP
                    ]
                ], [
                    'table'      => 'containers',
                    'type'       => 'INNER',
                    'alias'      => 'Container',
                    'conditions' => 'Container.id = Hostgroup.container_id'
                ]
            ],
            'conditions' => [
                'Container.id' => $Conditions->getContainerIds()
            ],
            'order'      => $Conditions->getOrder()
        ];

        $query['conditions'] = Hash::merge($query['conditions'], $filterConditions);

        return $query;
    }

    public function getRecurringNodeDowntimesQuery(SystemdowntimesConditions $Conditions, $filterConditions = []) {
        $query = [
            'recursive'  => -1,
            'fields'     => [
                'Systemdowntime.*',
                'Container.id',
                'Container.name'
            ],
            'joins'      => [
                [
                    'table'      => 'containers',
                    'type'       => 'INNER',
                    'alias'      => 'Container',
                    'conditions' => [
                        'Container.id = Systemdowntime.object_id',
                        'Systemdowntime.objecttype_id' => OBJECT_NODE
                    ]
                ]
            ],
            'conditions' => [
                'Container.id' => $Conditions->getContainerIds()
            ],
            'order'      => $Conditions->getOrder()
        ];

        $query['conditions'] = Hash::merge($query['conditions'], $filterConditions);

        return $query;
    }
}
