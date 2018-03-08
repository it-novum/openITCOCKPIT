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

use itnovum\openITCOCKPIT\Filter\ServicetemplateFilter;

class Servicetemplate extends AppModel {
    public $hasAndBelongsToMany = [
        'Contactgroup'         => [
            'className'             => 'Contactgroup',
            'joinTable'             => 'contactgroups_to_servicetemplates',
            'foreignKey'            => 'servicetemplate_id',
            'associationForeignKey' => 'contactgroup_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'Contact'              => [
            'className'             => 'Contact',
            'joinTable'             => 'contacts_to_servicetemplates',
            'foreignKey'            => 'servicetemplate_id',
            'associationForeignKey' => 'contact_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'Servicetemplategroup' => [
            'joinTable'             => 'servicetemplates_to_servicetemplategroups',
            'foreignKey'            => 'servicetemplate_id',
            'associationForeignKey' => 'servicetemplategroup_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'Servicegroup'         => [
            'className'             => 'Servicegroup',
            'joinTable'             => 'servicetemplates_to_servicegroups',
            'foreignKey'            => 'servicetemplate_id',
            'associationForeignKey' => 'servicegroup_id',
            'unique'                => true,
            'dependent'             => true,
        ],
    ];


    public $belongsTo = [
        'Container'           => [
            'className'  => 'Container',
            'foreignKey' => 'container_id',
        ],
        'CheckPeriod'         => [
            'className'  => 'Timeperiod',
            'foreignKey' => 'check_period_id',
        ],
        'NotifyPeriod'        => [
            'className'  => 'Timeperiod',
            'foreignKey' => 'notify_period_id',
        ],
        'CheckCommand'        => [
            'className'  => 'Command',
            'foreignKey' => 'command_id',
        ],
        'EventhandlerCommand' => [
            'className'  => 'Command',
            'foreignKey' => 'eventhandler_command_id',
        ],
    ];

    public $hasMany = [
        'Customvariable'                           => [
            'className'  => 'Customvariable',
            'foreignKey' => 'object_id',
            'conditions' => [
                'objecttype_id' => OBJECT_SERVICETEMPLATE,
            ],
            'dependent'  => true,
        ],
        'Servicetemplatecommandargumentvalue'      => [
            'dependent' => true,
        ],
        'Servicetemplateeventcommandargumentvalue' => [
            'dependent' => true,
        ],
        'Service'                                  => [
            'className'  => 'Service',
            'foreignKey' => 'servicetemplate_id',
            'dependent'  => true,
        ],

    ];

    public $validate = [
        'container_id'               => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'template_name'              => [
            'allowEmpty' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank',
                'required' => true,
            ],
        ],
        'name'                       => [
            'allowEmpty' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank',
                'required' => true,
            ],
        ],
        'command_id'                 => [
            'allowEmpty' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'notZero'    => [
                'rule'    => ['comparison', '>', 0],
                'message' => 'This field cannot be left blank.',
            ],

        ],
        'check_period_id'            => [
            'allowEmpty' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'notZero'    => [
                'rule'    => ['comparison', '>', 0],
                'message' => 'This field cannot be left blank.',
            ],

        ],
        'max_check_attempts'         => [
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
        'check_interval'             => [
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
        'retry_interval'             => [
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
        'notify_on_recovery'         => [
            'check_options' => [
                'rule'       => ['checkNotificationOptions', 'service'],
                'message'    => 'You have to choose at least one option.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'notify_on_warning'          => [
            'check_options' => [
                'rule'       => ['checkNotificationOptions', 'service'],
                'message'    => 'You have to choose at least one option.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'notify_on_unknown'          => [
            'check_options' => [
                'rule'       => ['checkNotificationOptions', 'service'],
                'message'    => 'You have to choose at least one option.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'notify_on_critical'         => [
            'check_options' => [
                'rule'       => ['checkNotificationOptions', 'service'],
                'message'    => 'You have to choose at least one option.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'notify_on_flapping'         => [
            'check_options' => [
                'rule'       => ['checkNotificationOptions', 'service'],
                'message'    => 'You have to choose at least one option.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'notify_on_downtime'         => [
            'check_options' => [
                'rule'       => ['checkNotificationOptions', 'service'],
                'message'    => 'You have to choose at least one option.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'notify_period_id'           => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'notification_interval'      => [
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
        'flap_detection_on_ok'       => [
            'check_options' => [
                'rule'       => ['checkFlapDetectionOptions', 'service'],
                'message'    => 'You have to choose at least one option.',
                'required'   => false,
                'allowEmpty' => true,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'flap_detection_on_warning'  => [
            'check_options' => [
                'rule'       => ['checkFlapDetectionOptions', 'service'],
                'message'    => 'You have to choose at least one option.',
                'required'   => false,
                'allowEmpty' => true,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'flap_detection_on_unknown'  => [
            'check_options' => [
                'rule'       => ['checkFlapDetectionOptions', 'service'],
                'message'    => 'You have to choose at least one option.',
                'required'   => false,
                'allowEmpty' => true,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
        'flap_detection_on_critical' => [
            'check_options' => [
                'rule'       => ['checkFlapDetectionOptions', 'service'],
                'message'    => 'You have to choose at least one option.',
                'required'   => false,
                'allowEmpty' => true,
            ],
            'boolean'       => [
                'rule'    => ['boolean'],
                'message' => 'Incorrect datatype',
            ],
        ],
    ];

    function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        App::uses('UUID', 'Lib');
        $this->notification_options = [
            'service' => [
                'notify_on_recovery',
                'notify_on_warning',
                'notify_on_unknown',
                'notify_on_critical',
                'notify_on_flapping',
                'notify_on_downtime',
            ],
        ];

        $this->flapdetection_options = [
            'service' => [
                'flap_detection_on_ok',
                'flap_detection_on_warning',
                'flap_detection_on_unknown',
                'flap_detection_on_critical',
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
        if (isset($this->data['Servicetemplate']['flap_detection_enabled']) && (boolean)$this->data['Servicetemplate']['flap_detection_enabled'] === true) {
            foreach ($this->data as $request) {
                foreach ($request as $request_key => $request_value) {
                    if (in_array($request_key, $this->flapdetection_options[$flapdetection_type]) && $request_value == 1) {
                        return true;
                    }
                }
            }

            return false;
        }

        return true;
    }

    public function servicetemplatesByContainerId($container_ids = [], $type = 'all', $servicetemplate_type = GENERIC_SERVICE, $ignoreType = false) {
        $conditions = [
            'Servicetemplate.container_id' => $container_ids,
        ];
        if (!$ignoreType) {
            $conditions['Servicetemplate.servicetemplatetype_id'] = $servicetemplate_type;
        }

        return $this->find($type, [
            'recursive'  => -1,
            'conditions' => $conditions,
            'order'      => [
                'Servicetemplate.template_name' => 'ASC',
            ],
            'fields'     => [
                'Servicetemplate.id',
                'Servicetemplate.template_name'
            ]
        ]);
    }

    public function createUUID() {
        return UUID::v4();
    }

    public function __allowDelete($servicetemplateId) {
        $Service = ClassRegistry::init('Service');
        $services = $Service->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Service.servicetemplate_id' => $servicetemplateId,
            ],
            'fields'     => [
                'Service.id',
            ],
        ]);

        //check if the host is used somwhere
        if (CakePlugin::loaded('EventcorrelationModule')) {
            $notInUse = true;
            $result = [];
            $this->Eventcorrelation = ClassRegistry::init('Eventcorrelation');
            foreach ($services as $service) {
                $evcCount = $this->Eventcorrelation->find('count', [
                    'conditions' => [
                        'service_id' => $service['Service']['id'],
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

    /**
     * @param ServicetemplateFilter $ServicetemplateFilter
     * @param array $selected
     * @return array|null
     */
    public function getServicetemplatesForAngular($containerIds = [], ServicetemplateFilter $ServicetemplateFilter, $selected = []) {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $query = [
            'recursive'  => -1,
            'conditions' => $ServicetemplateFilter->ajaxFilter(),
            'order'      => [
                'Servicetemplate.name' => 'ASC',
            ],
            'limit'      => self::ITN_AJAX_LIMIT
        ];

        $query['conditions']['Servicetemplate.container_id'] = $containerIds;

        $servicetemplatesWithLimit = $this->find('list', $query);

        $selectedServicetemplates = [];
        if (!empty($selected)) {
            $query = [
                'recursive'  => -1,
                'conditions' => [
                    'Servicetemplate.id'           => $selected,
                    'Servicetemplate.container_id' => $containerIds
                ],
                'order'      => [
                    'Servicetemplate.name' => 'ASC',
                ],
            ];
            $selectedServicetemplates = $this->find('list', $query);
        }

        $servicetemplates = $servicetemplatesWithLimit + $selectedServicetemplates;
        asort($servicetemplates, SORT_FLAG_CASE | SORT_NATURAL);
        return $servicetemplates;
    }

    /**
     * @param int $servicetemplateId
     * @return array
     */
    public function getQueryForBrowser($servicetemplateId) {
        return [
            'recursive'  => -1,
            'contain'    => [
                'Contact'                                  => [
                    'fields' => [
                        'id',
                        'name',
                    ],
                    'Container'
                ],
                'Contactgroup'                             => [
                    'fields'    => [
                        'id',
                    ],
                    'Container' => [
                        'fields' => [
                            'name',
                            'parent_id'
                        ],
                    ],
                ],

                'CheckCommand',
                'CheckPeriod',
                'NotifyPeriod',
                'Customvariable'                           => [
                    'fields' => [
                        'id',
                        'name',
                        'value',
                        'objecttype_id',
                    ],
                ],
                'Servicetemplatecommandargumentvalue'      => [
                    'fields'          => [
                        'commandargument_id',
                        'value',
                    ],
                    'Commandargument' => [
                        'fields' => [
                            'human_name',
                            'command_id',
                            'name'
                        ],
                    ],
                ],
                'Servicetemplateeventcommandargumentvalue' => [
                    'fields'          => [
                        'commandargument_id',
                        'value',
                    ],
                    'Commandargument' => [
                        'fields' => [
                            'human_name',
                            'command_id',
                            'name'
                        ],
                    ],
                ],
            ],
            'conditions' => [
                'Servicetemplate.id' => $servicetemplateId
            ]
        ];
    }
}
