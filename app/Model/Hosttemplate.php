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

use itnovum\openITCOCKPIT\Filter\HosttemplateFilter;

class Hosttemplate extends AppModel {
    var $hasAndBelongsToMany = [
        'Contactgroup' => [
            'className'             => 'Contactgroup',
            'joinTable'             => 'contactgroups_to_hosttemplates',
            'foreignKey'            => 'hosttemplate_id',
            'associationForeignKey' => 'contactgroup_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'Contact'      => [
            'className'             => 'Contact',
            'joinTable'             => 'contacts_to_hosttemplates',
            'foreignKey'            => 'hosttemplate_id',
            'associationForeignKey' => 'contact_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'Hostgroup'    => [
            'className'             => 'Hostgroup',
            'joinTable'             => 'hosttemplates_to_hostgroups',
            'foreignKey'            => 'hosttemplate_id',
            'associationForeignKey' => 'hostgroup_id',
            'unique'                => true,
            'dependent'             => true,
        ],
    ];

    var $belongsTo = [
        'Container'    => [
            'className'  => 'Container',
            'foreignKey' => 'container_id',
        ],
        'CheckPeriod'  => [
            'className'  => 'Timeperiod',
            'foreignKey' => 'check_period_id',
        ],
        'NotifyPeriod' => [
            'className'  => 'Timeperiod',
            'foreignKey' => 'notify_period_id',
        ],
        'CheckCommand' => [
            'className'  => 'Command',
            'foreignKey' => 'command_id',
        ],
    ];

    var $hasMany = [
        'Customvariable' => [
            'className'  => 'Customvariable',
            'foreignKey' => 'object_id',
            'conditions' => [
                'objecttype_id' => OBJECT_HOSTTEMPLATE,
            ],
            'dependent'  => true,
        ],
        'Hosttemplatecommandargumentvalue',
        'Host'
    ];

    var $validate = [
        'name'                          => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'priority'                      => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'range'    => [
                'rule'    => ['range', 0, 6],
                'message' => 'This value must be between 1 and 5',
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'This value needs to be numeric',
            ],
        ],
        'notify_period_id'              => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'container_id'                  => [
            'rule'     => 'notBlank',
            'message'  => 'This field cannot be left blank.',
            'required' => true,
        ],
        'max_check_attempts'            => [
            'allowEmpty' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'    => [
                'rule'    => 'numeric',
                'message' => 'This field need to be numeric.',
            ],
            'comparison' => [
                'rule'    => ['comparison', '>=', 1],
                'message' => 'This value need to be at least 1',
            ],
        ],
        'notification_interval'         => [
            'allowEmpty' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'    => [
                'rule'    => 'numeric',
                'message' => 'This field need to be numeric.',
            ],
            'comparison' => [
                'rule'    => ['comparison', '>=', 0],
                'message' => 'This value need to be at least 0',
            ],
        ],
        'check_interval'                => [
            'allowEmpty' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'    => [
                'rule'    => 'numeric',
                'message' => 'This field need to be numeric.',
            ],
            'comparison' => [
                'rule'    => ['comparison', '>=', 1],
                'message' => 'This value need to be at least 1',
            ],
        ],
        'retry_interval'                => [
            'allowEmpty' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'    => [
                'rule'    => 'numeric',
                'message' => 'This field need to be numeric.',
            ],
            'comparison' => [
                'rule'    => ['comparison', '>=', 1],
                'message' => 'This value need to be at least 1',
            ],
        ],
        'check_period_id'               => [
            'allowEmpty' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'    => [
                'rule'    => 'numeric',
                'message' => 'This field need to be numeric.',
            ],
        ],
        'command_id'                    => [
            'allowEmpty' => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'    => [
                'rule'    => 'numeric',
                'message' => 'This field need to be numeric.',
            ],
        ],
        'notify_on_recovery'            => [
            'check_options' => [
                'rule'       => ['checkNotificationOptions', 'host'],
                'message'    => 'You have to choose at least one option.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'notify_on_down'                => [
            'check_options' => [
                'rule'       => ['checkNotificationOptions', 'host'],
                'message'    => 'You have to choose at least one option.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'notify_on_unreachable'         => [
            'check_options' => [
                'rule'       => ['checkNotificationOptions', 'host'],
                'message'    => 'You have to choose at least one option.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'notify_on_flapping'            => [
            'check_options' => [
                'rule'       => ['checkNotificationOptions', 'host'],
                'message'    => 'You have to choose at least one option.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'notify_on_downtime'            => [
            'check_options' => [
                'rule'       => ['checkNotificationOptions', 'host'],
                'message'    => 'You have to choose at least one option.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'flap_detection_enabled'        => [
            'boolean' => [
                'rule'       => ['boolean'],
                'message'    => 'Incorrect datatype',
                'required'   => false,
                'allowEmpty' => true,
            ],
        ],
        'flap_detection_on_up'          => [
            'check_options' => [
                'rule'       => ['checkFlapDetectionOptions', 'host'],
                'message'    => 'You have to choose at least one option.',
                'required'   => false,
                'allowEmpty' => true,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'flap_detection_on_down'        => [
            'check_options' => [
                'rule'       => ['checkFlapDetectionOptions', 'host'],
                'message'    => 'You have to choose at least one option.',
                'required'   => false,
                'allowEmpty' => true,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'flap_detection_on_unreachable' => [
            'check_options' => [
                'rule'       => ['checkFlapDetectionOptions', 'host'],
                'message'    => 'You have to choose at least one option.',
                'required'   => false,
                'allowEmpty' => true,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'Contact'                       => [
            'atLeastOne' => [
                'rule'     => ['atLeastOne'],
                'message'  => 'You must specify at least one contact or contact group.',
                'required' => true,
            ],
        ],
        'Contactgroup'                  => [
            'atLeastOne' => [
                'rule'     => ['atLeastOne'],
                'message'  => 'You must specify at least one contact or contact group',
                'required' => true,
            ],
        ],
        'host_url'                      => [
            'rule'       => 'url',
            'allowEmpty' => true,
            'required'   => false,
            'message'    => 'Not a valid URL format',
        ],
    ];

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        App::uses('UUID', 'Lib');
        $this->notification_options = [
            'host' => [
                'notify_on_recovery',
                'notify_on_down',
                'notify_on_unreachable',
                'notify_on_flapping',
                'notify_on_downtime',
            ],
        ];

        $this->falpdetection_options = [
            'host' => [
                'flap_detection_on_up',
                'flap_detection_on_down',
                'flap_detection_on_unreachable',
            ],
        ];
    }

    function checkNotificationOptions($data, $notification_type) {
        foreach ($this->data as $request) {
            foreach ($request as $request_key => $request_value) {
                if (in_array($request_key, $this->notification_options[$notification_type], true) && $request_value == 1) {
                    return true;
                }
            }
        }

        return false;
    }

    function checkFlapDetectionOptions($data, $flapdetection_type) {
        if (isset($this->data['Hosttemplate']['flap_detection_enabled']) && (boolean)$this->data['Hosttemplate']['flap_detection_enabled'] === true) {
            foreach ($this->data as $request) {
                foreach ($request as $request_key => $request_value) {
                    if (in_array($request_key, $this->falpdetection_options[$flapdetection_type]) && $request_value == 1) {
                        return true;
                    }
                }
            }

            return false;
        }

        return true;
    }

    public function createUUID() {
        return UUID::v4();
    }

    /*
    Custom validation rule for contact and/or contactgroup fields
    */
    public function atLeastOne($data) {
        return !empty($this->data[$this->name]['Contact']) || !empty($this->data[$this->name]['Contactgroup']);
    }

    public function hosttemplatesByContainerId($container_ids = [], $type = 'all', $hosttemplate_type = GENERIC_HOST, $ignoreType = false) {
        $conditions = [
            'Hosttemplate.container_id' => $container_ids,
        ];
        if (!$ignoreType) {
            $conditions['Hosttemplate.hosttemplatetype_id'] = $hosttemplate_type;
        }

        return $this->find($type, [
            'conditions' => [
                $conditions
            ],
            'order'      => [
                'Hosttemplate.name' => 'ASC',
            ],
        ]);
    }


    public function __allowDelete($hosttemplateId) {
        $Host = ClassRegistry::init('Host');
        $hosts = $Host->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Host.hosttemplate_id' => $hosttemplateId,
            ],
            'fields'     => [
                'Host.id',
            ],
        ]);

        //check if the host is used somwhere
        if (CakePlugin::loaded('EventcorrelationModule')) {
            $notInUse = true;
            $result = [];
            $Service = ClassRegistry::init('Service');
            $this->Eventcorrelation = ClassRegistry::init('Eventcorrelation');
            foreach ($hosts as $host) {
                $serviceIds = Hash::extract($Service->find('all', [
                    'recursive'  => -1,
                    'conditions' => [
                        'host_id' => $host['Host']['id'],
                    ],
                    'fields'     => [
                        'Service.id',
                    ],
                ]), '{n}.Service.id');
                $evcCount = $this->Eventcorrelation->find('count', [
                    'conditions' => [
                        'OR' => [
                            'host_id'    => $host['Host']['id'],
                            'service_id' => $serviceIds,
                        ],

                    ],
                ]);
                $result[] = $evcCount;
            }
            foreach ($result as $value) {
                if ($value > 0) {
                    $notInUse = false;
                }
            }

            return $notInUse;
        }

        return true;
    }

    public function getHosttemplatesForAngular($containerIds = [], HosttemplateFilter $HosttemplateFilter, $selected = []) {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $query = [
            'recursive'  => -1,
            'conditions' => $HosttemplateFilter->ajaxFilter(),
            'order'      => [
                'Hosttemplate.name' => 'ASC',
            ],
            'limit'      => self::ITN_AJAX_LIMIT
        ];

        $query['conditions']['Hosttemplate.container_id'] = $containerIds;

        $hosttemplatesWithLimit = $this->find('list', $query);

        $selectedHosttemplates = [];
        if (!empty($selected)) {
            $query = [
                'recursive'  => -1,
                'conditions' => [
                    'Hosttemplate.id'           => $selected,
                    'Hosttemplate.container_id' => $containerIds
                ],
                'order'      => [
                    'Hosttemplate.name' => 'ASC',
                ],
            ];
            $selectedHosttemplates = $this->find('list', $query);
        }

        $hosttemplates = $hosttemplatesWithLimit + $selectedHosttemplates;
        asort($hosttemplates, SORT_FLAG_CASE | SORT_NATURAL);
        return $hosttemplates;
    }

    /**
     * @param int $hosttemplateId
     * @return array
     */
    public function getQueryForBrowser($hosttemplateId) {
        return [
            'recursive'  => -1,
            'contain'    => [
                'CheckPeriod',
                'NotifyPeriod',
                'CheckCommand',
                'Contact'                          => [
                    'fields' => [
                        'id',
                        'name'
                    ],
                    'Container'
                ],
                'Contactgroup'                     => [
                    'fields'    => [
                        'id'
                    ],
                    'Container' => [
                        'fields' => [
                            'name',
                            'parent_id'
                        ],
                    ],
                ],
                'Customvariable'                   => [
                    'fields' => [
                        'id',
                        'name',
                        'value',
                        'objecttype_id',
                    ],
                ],
                'Hosttemplatecommandargumentvalue' => [
                    'fields'          => [
                        'id',
                        'commandargument_id',
                        'value',
                    ],
                    'Commandargument' => [
                        'fields' => [
                            'id',
                            'human_name',
                            'name'
                        ],
                    ],
                ],
            ],
            'conditions' => [
                'Hosttemplate.id' => $hosttemplateId
            ]
        ];
    }
}
