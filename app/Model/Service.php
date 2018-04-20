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

use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\ValueObjects\LastDeletedId;

class Service extends AppModel {

    public $hasAndBelongsToMany = [
        'Contactgroup' => [
            'className'             => 'Contactgroup',
            'joinTable'             => 'contactgroups_to_services',
            'foreignKey'            => 'service_id',
            'associationForeignKey' => 'contactgroup_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'Contact'      => [
            'className'             => 'Contact',
            'joinTable'             => 'contacts_to_services',
            'foreignKey'            => 'service_id',
            'associationForeignKey' => 'contact_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'Servicegroup' => [
            'className'             => 'Servicegroup',
            'joinTable'             => 'services_to_servicegroups',
            'foreignKey'            => 'service_id',
            'associationForeignKey' => 'servicegroup_id',
        ],
    ];

    // public $hasOne = [
    // 	'DashboardWidget' => [
    // 		'className' => 'DashboardWidget',
    // 		'foreignKey' => 'service_id',
    // 	],
    // ];

    public $hasMany = [
        'Servicecommandargumentvalue'        => [
            'dependent' => true,
        ],
        'Serviceeventcommandargumentvalue'   => [
            'dependent' => true,
        ],
        'ServiceEscalationServiceMembership' => [
            'className'  => 'ServiceescalationServiceMembership',
            'foreignKey' => 'service_id',
            'dependent'  => true,
        ],
        'ServicedependencyServiceMembership' => [
            'className'  => 'ServicedependencyServiceMembership',
            'foreignKey' => 'service_id',
            'dependent'  => true,
        ],
        'Customvariable'                     => [
            'className'  => 'Customvariable',
            'foreignKey' => 'object_id',
            'conditions' => [
                'objecttype_id' => OBJECT_SERVICE,
            ],
            'dependent'  => true,
        ],
        'Widget'                             => [
            'className'  => 'Widget',
            'foreignKey' => 'service_id',
        ],
    ];

    public $belongsTo = [
        // 'DashboardWidget',
        'Host',
        'Servicetemplate',
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
    public $validate = [
        'host_id'                    => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'This field cannot be left blank.',
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'servicetemplate_id'         => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'This field cannot be left blank.',
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'max_check_attempts'         => [
            'numeric'    => [
                'rule'       => 'numeric',
                'message'    => 'This field need to be numeric.',
                'required'   => false,
                'allowEmpty' => true,
            ],
            'comparison' => [
                'rule'       => ['comparison', '>=', 1],
                'message'    => 'This value need to be at least 1',
                'required'   => false,
                'allowEmpty' => true,
            ],
        ],
        'check_interval'             => [
            'numeric'    => [
                'rule'       => 'numeric',
                'message'    => 'This field need to be numeric.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'comparison' => [
                'rule'       => ['comparison', '>=', 1],
                'message'    => 'This value need to be at least 1',
                'allowEmpty' => true,
                'required'   => false,
            ],
        ],
        'retry_interval'             => [
            'numeric'    => [
                'rule'       => 'numeric',
                'message'    => 'This field need to be numeric.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'comparison' => [
                'rule'       => ['comparison', '>=', 1],
                'message'    => 'This value need to be at least 1',
                'allowEmpty' => true,
                'required'   => false,
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
        'notification_interval'      => [
            'numeric'    => [
                'rule'       => 'numeric',
                'message'    => 'This field need to be numeric.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'comparison' => [
                'rule'       => ['comparison', '>=', 0],
                'message'    => 'This value need to be at least 0',
                'allowEmpty' => true,
                'required'   => false,
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

    /**
     * @var null|LastDeletedId
     */
    private $LastDeletedId = null;

    function __construct($id = false, $table = null, $ds = null, $useDynamicAssociations = true) {
        parent::__construct($id, $table, $ds, $useDynamicAssociations);
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

        /*
                if (is_object($this->Behaviors->DynamicAssociations)) {


                    $dynamicAssociations = $this->Behaviors->DynamicAssociations->dynamicAssociationsIgnoreCallback($this->alias);

                    //This is not working, but i dont know why!!
                    //$this->bindModel() not works on delete, so we use the --force method
                    if (!empty($dynamicAssociations) && is_array($dynamicAssociations)) {
                        $this->bindModel($dynamicAssociations, false);
                    }


                }
        */

    }

    /*
    Custom validation rule for contact and/or contactgroup fields
    */
    public function atLeastOne($data) {
        return !empty($this->data[$this->name]['Contact']) || !empty($this->data[$this->name]['Contactgroup']);
    }

    function checkNotificationOptions($data, $notification_type) {
        foreach ($this->data as $request) {
            foreach ($request as $request_key => $request_value) {
                if (in_array($request_key, $this->notification_options[$notification_type]) && $request_value == 1) {
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

    public function getDiffAsArray($service_values = [], $servicetemplate_values = []) {
        $service_values = ($service_values === null) ? [] : $service_values;
        $servicetemplate_values = ($servicetemplate_values === null) ? [] : $servicetemplate_values;

        return Hash::diff($service_values, $servicetemplate_values);
    }

    public function prepareForCompare($prepare_array = [], $prepare = false) {
        $keysForArraySort = ['Contact', 'Contactgroup', 'Servicegroup']; //sort array for array diff
        //if prepare_for_compare => false, nothing to do $prepare_array[0] => 'Template.{n}, $prepare_array[1] => true/false'
        if (!$prepare && is_array($prepare_array)) {
            $currentKey = key($prepare_array);
            if (!in_array($currentKey, $keysForArraySort, true)) {
                return $prepare_array;
            }
            if (is_array($prepare_array[$currentKey][$currentKey])) {
                sort($prepare_array[$currentKey][$currentKey]);
            }
            return $prepare_array;
        }
        $new_array = [];
        if (is_array($prepare_array)) {
            foreach ($prepare_array as $key => $data) {
                if (is_array($data)) {
                    sort($data);
                }
                $new_array[$key][$key] = $data;
            }
        }
        return $new_array;
    }

    public function prepareForSave($diff_array = [], $request_data = [], $save_mode = 'add') {
        $tmp_keys = [];
        //Check differences for notification settings
        if (!empty(Set::classicExtract($diff_array, 'Service.{(notify_on_).*}'))) {
            //Overwrite all notification settings if at least one option has been changed
            $diff_array = Hash::merge($diff_array, ['Service' => Set::classicExtract($request_data, 'Service.{(notify_on_).*}')]);
        }
        //Check differences for flap detection settings
        if (!empty(Set::classicExtract($diff_array, 'Service.{(flap_detection_on_).*}'))) {
            //Overwrite all flap detection settings if at least one option has been changed

            $diff_array = Hash::merge($diff_array, ['Service' => Set::classicExtract($request_data, 'Service.{(flap_detection_on_).*}')]);
        }

        //Set default for contact/contactgroup settings
        $diff_array = Hash::merge($diff_array, ['Service' => ['own_contacts' => '0', 'own_contactgroups' => '0', 'own_customvariables' => '0']]);
        if ($save_mode === 'edit' && isset($diff_array['Service'])) {
            $tmp_keys = array_diff_key($request_data['Service'], $diff_array['Service']);
        }

        if (!isset($request_data['Service']['Contactgroup']) ||
            (isset($request_data['Service']['Contactgroup']) && $request_data['Service']['Contactgroup'] == '')
        ) {
            $contactGroupData = [];
        } else {
            $contactGroupData = $request_data['Service']['Contactgroup'];
        }

        if (!isset($request_data['Service']['Contact']) ||
            (isset($request_data['Service']['Contact']) && $request_data['Service']['Contact'] == '')
        ) {
            $contactData = [];
        } else {
            $contactData = $request_data['Service']['Contact'];
        }

        //Stupid nagios 4 inheritance
        //See https://github.com/naemon/naemon-core/pull/92
        $breakInherit = false;
        if (isset($diff_array['Contact']) && !isset($diff_array['Contactgroup']['Contactgroup'])) {
            $diff_array['Contact']['Contact'] = $contactData;
            $diff_array['Contactgroup']['Contactgroup'] = $contactGroupData;
            $diff_array = Hash::merge($diff_array, ['Service' => ['own_contacts' => '1']]);
            $diff_array = Hash::merge($diff_array, ['Service' => ['own_contactgroups' => '1']]);
            $breakInherit = true;
        }

        //Stupid nagios 4 inheritance
        //See https://github.com/naemon/naemon-core/pull/92
        if (!isset($diff_array['Contact']['Contact']) && isset($diff_array['Contactgroup'])) {
            $diff_array['Contact']['Contact'] = $contactData;
            $diff_array['Contactgroup']['Contactgroup'] = $contactGroupData;
            $diff_array = Hash::merge($diff_array, ['Service' => ['own_contacts' => '1']]);
            $diff_array = Hash::merge($diff_array, ['Service' => ['own_contactgroups' => '1']]);
            $breakInherit = true;
        }

        //Stupid nagios 4 inheritance
        //See https://github.com/naemon/naemon-core/pull/92
        if (!isset($diff_array['Contact']['Contact']) && !isset($diff_array['Contactgroup']['Contactgroup'])) {
            $diff_array['Contact']['Contact'] = [];
            $diff_array['Contactgroup']['Contactgroup'] = [];
            $diff_array = Hash::merge($diff_array, ['Service' => ['own_contacts' => '0']]);
            $diff_array = Hash::merge($diff_array, ['Service' => ['own_contactgroups' => '0']]);
            $breakInherit = true;
        }
        //Check differences for contacts and contactgroups
        if (!$breakInherit) {
            foreach (Set::classicExtract($diff_array, '{(Contact|Contactgroup)}.{(Contact|Contactgroup)}.{n}') as $key => $value) {
                //overwrite default setting for: own_contact/own_contactgroups => 1 if contact/contactgroup array exists
                $diff_array = Hash::merge($diff_array, ['Service' => ['own_' . strtolower(Inflector::pluralize($key)) => '1']]);
                if ($diff_array[$key][$key] === null) {
                    //Remove empty contacts or contactgroups from array
                    $diff_array = Hash::remove($diff_array, $key);
                }
            }
        }
        if ($save_mode === 'edit') {
            $diff_array = Hash::merge($diff_array, ['Service' => array_fill_keys(array_keys($tmp_keys), null)]);
        }

        if ((!is_array($contactGroupData) || !$diff_array['Service']['own_contactgroups'])) {
        }
        {
            $contactGroup = $contactGroupData;
        }
        if (!is_array($contactData) || !$diff_array['Service']['own_contacts']) {
            $contact = [];
        } else {
            $contact = $contactData;
        }
        if (empty($diff_array['Servicegroup']['Servicegroup'])) {
            $diff_array['Servicegroup']['Servicegroup'] = [];
        }

        if (isset($request_data['Service'])) {
            $diff_array = Hash::merge($diff_array, [
                'Service'      => [
                    'servicetemplate_id' => $request_data['Service']['servicetemplate_id'],
                    'host_id'            => $request_data['Service']['host_id'],
                    /* Set Contact/Contactgroup for custom validation rule*/
                    'Contact'            => [
                        'Contact' => [
                            $contact,
                        ],
                    ],
                    'Contactgroup'       => [
                        $contactGroup,
                    ],
                ],
                'Contact'      => [
                    'Contact' => [
                        $contact,
                    ],
                ],
                'Contactgroup' => [
                    'Contactgroup' => [
                        $contactGroup,
                    ],
                ],
                'Servicegroup' => [
                    'Servicegroup' => $request_data['Servicegroup']['Servicegroup'],
                ],
            ]);
        }

        if ($save_mode === 'add') {
            $diff_array = Hash::merge($diff_array,
                [
                    'Service' => [
                        'uuid' => UUID::v4(),
                    ],
                ]);
        } else if ($save_mode === 'edit') {
            $diff_array = Hash::merge($diff_array,
                [
                    'Service' => [
                        'id' => $request_data['Service']['id'],
                    ],
                ]);
        }

        if (!isset($request_data['Servicecommandargumentvalue'])) {
            $diff_array = Hash::remove($diff_array, 'Servicecommandargumentvalue');
        }

        if (!isset($request_data['Serviceeventcommandargumentvalue'])) {
            $diff_array = Hash::remove($diff_array, 'Serviceeventcommandargumentvalue');
        }

        if (isset($diff_array['Service'])) {
            // Nagios 4 inheritance - See https://github.com/naemon/naemon-core/pull/92
            if (isset($diff_array['Service']['Contact']) && isset($diff_array['Service']['Contactgroup']) &&
                $diff_array['Service']['Contact'] == '' && $diff_array['Service']['Contactgroup'] == ''
            ) {
                $diff_array['Contact']['Contact'] = [];
                $diff_array['Contactgroup']['Contactgroup'] = [];
                $diff_array = Hash::merge($diff_array, ['Service' => ['own_contacts' => '0']]);
                $diff_array = Hash::merge($diff_array, ['Service' => ['own_contactgroups' => '0']]);
            }
        }

        return $diff_array;
    }

    public function prepareForView($id = null) {
        if (!$this->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }
        $service = $this->find('all', [
            'conditions' => [
                'Service.id' => $id,
            ],
            'contain'    => [
                'Servicetemplate'                  => [
                    'Contact'                                  => [
                        'fields' => [
                            'id', 'name',
                        ],
                    ],
                    'Contactgroup'                             => [
                        'fields'    => ['id'],
                        'Container' => [
                            'fields' => [
                                'name',
                            ],
                        ],
                    ],
                    'Servicegroup'                             => [
                        'fields'    => [
                            'id'
                        ],
                        'Container' => [
                            'fields' => [
                                'name',
                            ],
                        ],
                    ],
                    'CheckCommand',
                    'CheckPeriod',
                    'NotifyPeriod',
                    'Customvariable'                           => [
                        'fields' => [
                            'id', 'name', 'value', 'objecttype_id',
                        ],
                    ],
                    'Servicetemplatecommandargumentvalue'      => [
                        'fields'          => [
                            'commandargument_id', 'value',
                        ],
                        'Commandargument' => [
                            'fields' => ['human_name', 'command_id'],
                        ],
                    ],
                    'Servicetemplateeventcommandargumentvalue' => [
                        'fields'          => [
                            'commandargument_id', 'value',
                        ],
                        'Commandargument' => [
                            'fields' => ['human_name', 'command_id'],
                        ],
                    ],
                ],
                'Host'                             => [
                    'fields'       => [
                        'id', 'name',
                    ],
                    'Contact'      => [
                        'fields' => [
                            'id', 'name',
                        ],
                    ],
                    'Contactgroup' => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                        'fields'    => [
                            'Contactgroup.id',
                        ],
                    ],
                    'Hosttemplate' => [
                        'Contact'      => [
                            'fields' => [
                                'id', 'name',
                            ],
                        ],
                        'Contactgroup' => [
                            'Container' => [
                                'fields' => [
                                    'Container.name',
                                ],
                            ],
                            'fields'    => [
                                'Contactgroup.id',
                            ],
                        ],
                    ],
                ],
                'Contact'                          => [
                    'fields' => [
                        'id',
                        'name',
                    ],
                ],
                'Contactgroup'                     => [
                    'fields'    => [
                        'id'
                    ],
                    'Container' => [
                        'fields' => [
                            'name',
                        ],
                    ],
                ],
                'Servicegroup'                     => [
                    'fields'    => [
                        'id'
                    ],
                    'Container' => [
                        'fields' => [
                            'name',
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
                'Servicecommandargumentvalue'      => [
                    'fields'          => [
                        'id',
                        'commandargument_id',
                        'value',
                    ],
                    'Commandargument' => [
                        'fields' => [
                            'id',
                            'human_name',
                        ],
                    ],
                ],
                'Serviceeventcommandargumentvalue' => [
                    'fields'          => [
                        'id',
                        'commandargument_id',
                        'value',
                    ],
                    'Commandargument' => [
                        'fields' => [
                            'id',
                            'human_name',
                        ],
                    ],
                ],
                'CheckCommand',
                'CheckPeriod',
                'NotifyPeriod',
            ],
            'recursive'  => -1,
        ]);
        $service = $service[0];
        if (!isset($service['Service']['servicetemplate_id']) || $service['Service']['servicetemplate_id'] == 0) {
            return $service;
        }

        $servicecommandargumentvalue = [];
        if (!empty($service['Servicecommandargumentvalue'])) {
            $servicecommandargumentvalue = $service['Servicecommandargumentvalue'];
        } else {
            if ($service['Service']['command_id'] === $service['Servicetemplate']['command_id'] || $service['Service']['command_id'] === null) {
                $servicecommandargumentvalue = $service['Servicetemplate']['Servicetemplatecommandargumentvalue'];
            }
        }

        $serviceeventcommandargumentvalue = [];
        if (!empty($service['Serviceeventcommandargumentvalue'])) {
            $serviceeventcommandargumentvalue = $service['Serviceeventcommandargumentvalue'];
        } else {
            if ($service['Service']['eventhandler_command_id'] === $service['Servicetemplate']['eventhandler_command_id'] || $service['Service']['eventhandler_command_id'] === null) {
                $serviceeventcommandargumentvalue = $service['Servicetemplate']['Servicetemplateeventcommandargumentvalue'];
            }
        }

        $servicegroups = [];
        if (!empty($service['Servicegroup'])) {
            $servicegroups = Hash::combine($service['Servicegroup'], '{n}.id', '{n}.id');
        } else if (empty($service['Servicegroup']) && !(empty($service['Servicetemplate']['Servicegroup']))) {
            $servicegroups = Hash::combine($service['Servicetemplate']['Servicegroup'], '{n}.id', '{n}.id');
        }

        $service = [
            'Service'                          => Hash::merge(Hash::filter($service['Service'], ['Service', 'filterNullValues']), Set::classicExtract($service['Servicetemplate'], '{(' . implode('|', array_keys(Hash::diff($service['Service'], Hash::filter($service['Service'], ['Service', 'filterNullValues'])))) . ')}')),
            'Contact'                          => Hash::extract((($service['Service']['own_contacts']) ? $service['Contact'] : $service['Servicetemplate']['Contact']), '{n}.id'),
            'Contactgroup'                     => Hash::extract((($service['Service']['own_contactgroups']) ? $service['Contactgroup'] : $service['Servicetemplate']['Contactgroup']), '{n}.id'),
            'Customvariable'                   => ($service['Service']['own_customvariables']) ? $service['Customvariable'] : $service['Servicetemplate']['Customvariable'],
            'Servicecommandargumentvalue'      => $servicecommandargumentvalue,
            'Serviceeventcommandargumentvalue' => $serviceeventcommandargumentvalue,
            'Servicetemplate'                  => $service['Servicetemplate'],
            'Servicegroup'                     => $servicegroups,
            'Host'                             => $service['Host'],
            'CheckPeriod'                      => (empty(Hash::filter($service['CheckPeriod']))) ? $service['Servicetemplate']['CheckPeriod'] : $service['CheckPeriod'],
            'NotifyPeriod'                     => (empty(Hash::filter($service['NotifyPeriod']))) ? $service['Servicetemplate']['NotifyPeriod'] : $service['NotifyPeriod'],
            'CheckCommand'                     => (empty(Hash::filter($service['CheckCommand']))) ? $service['Servicetemplate']['CheckCommand'] : $service['CheckCommand'],
        ];

        return $service;
    }


    /**
     * Callback function for filtering.
     *
     * @param array $var Array to filter.
     *
     * @return boolean
     */
    public static function filterNullValues($var) {
        if ($var != null || $var === '0' || $var === '') {
            return true;
        }

        return false;
    }

    public function getAjaxServices($containerIds = [], $conditions = [], $servicesIncluding = []) {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }
        $containerIds = array_unique($containerIds);

        $this->virtualFields['ServiceDescription'] = 'IF((Service.name IS NULL OR Service.name = ""), Servicetemplate.name, Service.name)';

        $_conditions = [
            //'Host.container_id' => $containerIds,

            'Host.disabled'    => 0,
            'Service.disabled' => 0,
        ];
        $conditions = Hash::merge($_conditions, $conditions);
        $services = $this->find('all', [
            'recursive'  => -1,
            'contain'    => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.name'
                    ],
                ],
                'Host'            => [
                    'fields' => [
                        'Host.name',
                    ],
                ],
            ],
            'fields'     => [
                'Service.id',
                'Service.ServiceDescription'
            ],
            'order'      => [
                'Host.name ASC', 'Service.name ASC', 'Servicetemplate.name ASC',
            ],
            'conditions' => $conditions,
            'limit'      => self::ITN_AJAX_LIMIT
        ]);

        $formattedServices = [];
        if (!empty($servicesIncluding) && isset($servicesIncluding[0]) && is_array($servicesIncluding[0])) {
            foreach ($servicesIncluding as $serviceIncluding) {
                $formattedServices[] = $serviceIncluding['id'];
            }
        } else {
            $formattedServices = $servicesIncluding;
        }

        $servicesIds = [];
        foreach ($services as $service) {
            $servicesIds[] = $service['Service']['id'];
        }

        if (!empty($formattedServices) && !empty(array_diff($formattedServices, $servicesIds))) {
            $selectedCondition = ['Service.id' => array_diff($formattedServices, $servicesIds)];
            $selectedServices = $this->find('all', [
                'recursive'  => -1,
                'contain'    => [
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.name'
                        ],
                    ],
                    'Host'            => [
                        'fields' => [
                            'Host.name',
                        ],
                    ],
                ],
                'fields'     => [
                    'Service.id',
                    'Service.ServiceDescription'
                ],
                'order'      => [
                    'Host.name ASC', 'Service.name ASC', 'Servicetemplate.name ASC',
                ],
                'conditions' => Hash::merge($selectedCondition, $_conditions),
            ]);

            if (!empty($selectedServices)) {
                $services = array_merge($services, $selectedServices);
            }
        }

        $returnValue = [];
        foreach ($services as $serviceArray) {
            $returnValue[$serviceArray['Host']['id']][$serviceArray['Host']['name']][$serviceArray['Service']['id']] = $serviceArray['Host']['name'] . '/' . $serviceArray['Service']['ServiceDescription'];
        }
        return $returnValue;
    }

    public function servicesByHostContainerIds($containerIds = [], $type = 'all', $conditions = [], $limit = null) {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $containerIds = array_unique($containerIds);

        $_conditions = [
            'Host.container_id' => $containerIds,
            'Host.disabled'     => 0,
            'Service.disabled'  => 0,
        ];
        $conditions = Hash::merge($_conditions, $conditions);

        $query = [
            'recursive'  => -1,
            'contain'    => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.name'
                    ],
                ],
                'Host'            => [
                    'fields'    => [
                        'Host.name',
                        'Host.uuid',
                        'Host.address',
                        'Host.description'
                    ],
                    'Container' => [
                        'conditions' => [
                            'Container.id' => $containerIds,
                        ]

                    ]
                ],
            ],
            'fields'     => [
                'Service.id',
                'IF((Service.name IS NULL OR Service.name = ""), Servicetemplate.name, Service.name) AS ServiceDescription',
                'IF((Service.description IS NULL OR Service.description = ""), Servicetemplate.description, Service.description) AS ServiceDescr',
                'Service.uuid',
                'Service.service_type',
                'Service.disabled'
            ],
            'order'      => [
                'Host.name ASC', 'Service.name ASC', 'Servicetemplate.name ASC',
            ],
            'conditions' => $conditions,
        ];
        if ($limit) {
            $query['limit'] = $limit;
        }

        $result = $this->find('all', $query);

        if ($type == 'list') {
            $_return = [];
            foreach ($result as $service) {
                $_return[$service['Service']['id']] = $service[0]['ServiceDescription'];
            }

            return $_return;
        }

        // type all or everything else
        return $result;
    }

    public function diffWithTemplate($service, $servicetemplate) {
        $diff_array = [];
        //Service-/Servicetemplate fields
        $fields = [
            'name',
            'description',
            'notes',
            'notify_period_id',
            'notification_interval',
            'notify_on_recovery',
            'notify_on_warning',
            'notify_on_unknown',
            'notify_on_critical',
            'notify_on_flapping',
            'notify_on_downtime',
            'command_id',
            'check_period_id',
            'max_check_attempts',
            'check_interval',
            'retry_interval',
            'tags',
            'flap_detection_enabled',
            'flap_detection_on_ok',
            'flap_detection_on_warning',
            'flap_detection_on_unknown',
            'flap_detection_on_critical',
            'freshness_checks_enabled',
            'freshness_threshold',
            'eventhandler_command_id',
            'priority',
            'service_url',
            'active_checks_enabled',
            'process_performance_data',
        ];

        $compare_array = [
            'Service'         => [
                ['Service.{(' . implode('|', array_values(Hash::merge($fields, ['uuid', 'disabled', 'service_type']))) . ')}', false],
                ['{^Contact$}.{^Contact$}.{n}', false],
                ['{^Contactgroup$}.{^Contactgroup$}.{n}', false],
                ['{^Servicegroup$}.{^Servicegroup$}.{n}', false],
                ['Servicecommandargumentvalue.{n}.{(commandargument_id|value)}', false],
                ['Serviceeventcommandargumentvalue.{n}.{(commandargument_id|value)}', false],
            ],
            'Servicetemplate' => [
                ['Servicetemplate.{(' . implode('|', array_values($fields)) . ')}', false],
                ['{^Contact$}.{n}.id', true],
                ['{^Contactgroup$}.{n}.id', true],
                ['{^Servicegroup$}.{n}.id', true],
                ['Servicetemplatecommandargumentvalue.{n}.{(commandargument_id|value)}', false],
                ['Servicetemplateeventcommandargumentvalue.{n}.{(commandargument_id|value)}', false],
            ],
        ];
        $diff_array = [];

        foreach ($compare_array['Service'] as $key => $data) {
            $possible_key = preg_replace('/(\{.*\})|(\.)/', '', $data[0]);
            if ($data[0] == 'Servicecommandargumentvalue.{n}.{(commandargument_id|value)}') {
                if (!empty($service['Servicecommandargumentvalue'])) {
                    if (!empty(Hash::diff(Set::classicExtract($service, $data[0]), Set::classicExtract($servicetemplate, $compare_array['Servicetemplate'][$key][0])))) {
                        $diff_data = Set::classicExtract($service, $data[0]);
                        $diff_array['Servicecommandargumentvalue'] = $diff_data;
                    }
                    //	debug(Hash::diff(Set::classicExtract($service, $data[0]), Set::classicExtract($servicetemplate,$compare_array['Servicetemplate'][$key][0])));
                    $diff_data = $this->getDiffAsArray($this->prepareForCompare(Set::classicExtract($service, $data[0]), $data[1]),
                        $this->prepareForCompare(Set::classicExtract($servicetemplate, $compare_array['Servicetemplate'][$key][0]),
                            $compare_array['Servicetemplate'][$key][1]));
                }
            } else if ($data[0] == 'Serviceeventcommandargumentvalue.{n}.{(commandargument_id|value)}') {
                if (!empty($service['Serviceeventcommandargumentvalue'])) {
                    if (!empty(Hash::diff(Set::classicExtract($service, $data[0]), Set::classicExtract($servicetemplate, $compare_array['Servicetemplate'][$key][0])))) {
                        $diff_data = Set::classicExtract($service, $data[0]);
                        $diff_array['Serviceeventcommandargumentvalue'] = $diff_data;
                    }
                    //	debug(Hash::diff(Set::classicExtract($service, $data[0]), Set::classicExtract($servicetemplate,$compare_array['Servicetemplate'][$key][0])));
                    $diff_data = $this->getDiffAsArray($this->prepareForCompare(Set::classicExtract($service, $data[0]), $data[1]),
                        $this->prepareForCompare(Set::classicExtract($servicetemplate, $compare_array['Servicetemplate'][$key][0]),
                            $compare_array['Servicetemplate'][$key][1]));
                }
            } else {
                //$Key for DiffArray with preg_replace ==>  from 'Customvariable.{n}.{(name|value)}'' to 'Customvariable'
                $diff_data = $this->getDiffAsArray($this->prepareForCompare(Set::classicExtract($service, $data[0]), $data[1]),
                    $this->prepareForCompare(Set::classicExtract($servicetemplate, $compare_array['Servicetemplate'][$key][0]),
                        $compare_array['Servicetemplate'][$key][1]));
                if (!empty($diff_data)) {
                    $diff_array = Hash::merge($diff_array, (!empty($possible_key)) ? [$possible_key => $diff_data] : $diff_data);
                }
            }
        }

        return $diff_array;
    }

    public function dataForChangelogCopy($service, $servicetemplate) {
        $servicecommandargumentvalue = [];
        if (!empty($service['Servicecommandargumentvalue'])) {
            $servicecommandargumentvalue = $service['Servicecommandargumentvalue'];
        } else {
            if ($service['Service']['command_id'] === $servicetemplate['Servicetemplate']['command_id'] || $service['Service']['command_id'] === null) {
                $servicecommandargumentvalue = $servicetemplate['Servicetemplatecommandargumentvalue'];
            }
        }
        $serviceeventcommandargumentvalue = [];
        if (!empty($service['Serviceeventcommandargumentvalue'])) {
            $serviceeventcommandargumentvalue = $service['Serviceeventcommandargumentvalue'];
        } else {
            if ($service['Service']['eventhandler_command_id'] === $servicetemplate['Servicetemplate']['eventhandler_command_id'] || $service['Service']['eventhandler_command_id'] === null) {
                $serviceeventcommandargumentvalue = $servicetemplate['Servicetemplateeventcommandargumentvalue'];
            }
        }
        $service = [
            'Service'                          => Hash::merge(Hash::filter($service['Service'], ['Service', 'filterNullValues']), $servicetemplate['Servicetemplate']),
            'Contact'                          => (!empty($service['Contact'])) ? $service['Contact'] : $servicetemplate['Contact'],
            'Contactgroup'                     => (!empty($service['Contactgroup'])) ? $service['Contactgroup'] : $servicetemplate['Contactgroup'],
            'Customvariable'                   => ($service['Service']['own_customvariables']) ? $service['Customvariable'] : $servicetemplate['Customvariable'],
            'Servicecommandargumentvalue'      => $servicecommandargumentvalue,
            'Serviceeventcommandargumentvalue' => $serviceeventcommandargumentvalue,
            'Servicetemplate'                  => $servicetemplate['Servicetemplate'],
            'Servicegroup'                     => (!empty($service['Servicegroup'])) ? $service['Servicegroup'] : $servicetemplate['Servicegroup'],
            'Host'                             => $service['Host'],
            'CheckPeriod'                      => (empty(Hash::filter($service['CheckPeriod']))) ? $servicetemplate['CheckPeriod'] : $service['CheckPeriod'],
            'NotifyPeriod'                     => (empty(Hash::filter($service['NotifyPeriod']))) ? $servicetemplate['NotifyPeriod'] : $service['NotifyPeriod'],
            'CheckCommand'                     => (empty(Hash::filter($service['CheckCommand']))) ? $servicetemplate['CheckCommand'] : $service['CheckCommand'],
        ];
        return $service;
    }

    public function serviceTypes($task) {
        switch ($task) {
            case 'copy':
                return [GENERIC_SERVICE];
                break;

            default:
                return [GENERIC_SERVICE];
                break;
        }
    }

    public function hostHasServiceByServicetemplateId($host_id, $servicetemplate_id) {
        $this->unbindModel([
            'hasAndBelongsToMany' => ['Contactgroup', 'Contact', 'Servicegroup'],
            'hasMany'             => ['Servicecommandargumentvalue', 'Serviceeventcommandargumentvalue', 'ServiceEscalationServiceMembership', 'ServicedependencyServiceMembership', 'Customvariable'],
            'belongsTo'           => ['Host', 'Servicetemplate', 'CheckPeriod', 'NotifyPeriod', 'CheckCommand'],
        ]);
        $result = $this->find('first', [
            'conditions' => [
                'Service.host_id'            => $host_id,
                'Service.servicetemplate_id' => $servicetemplate_id,
            ],
            'fields'     => ['Service.id'],
        ]);

        return !empty($result);
    }


    public function __delete($id, $userId) {
        if (is_numeric($id)) {
            $service = $this->findById($id);
        } else {
            $service = $id;
            $id = $service['Service']['id'];
        }

        $Changelog = ClassRegistry::init('Changelog');

        if ($this->delete($id)) {
            //Delete was successfully - delete Graphgenerator configurations
            $GraphgenTmplConf = ClassRegistry::init('GraphgenTmplConf');
            $graphgenTmplConfs = $GraphgenTmplConf->find('all', [
                'conditions' => [
                    'GraphgenTmplConf.service_id' => $id,
                ],
            ]);
            foreach ($graphgenTmplConfs as $graphgenTmplConf) {
                $GraphgenTmplConf->delete($graphgenTmplConf['GraphgenTmplConf']['id']);
            }

            $changelog_data = $Changelog->parseDataForChangelog(
                'delete',
                'services',
                $id,
                OBJECT_SERVICE,
                $service['Host']['container_id'],
                $userId,
                $service['Host']['name'] . '/' . (($service['Service']['name'] !== null) ? $service['Service']['name'] : $service['Servicetemplate']['name']),
                $service
            );
            if ($changelog_data) {
                CakeLog::write('log', serialize($changelog_data));
            }

            $Documentation = ClassRegistry::init('Documentation');
            //Delete the Documentation of the Host
            $documentation = $Documentation->findByUuid($service['Service']['uuid']);
            if (isset($documentation['Documentation']['id'])) {
                $Documentation->delete($documentation['Documentation']['id']);
                unset($documentation);
            }

            //Add service to deleted objects table
            $_serviceName = $service['Service']['name'];
            if ($service['Service']['name'] == null || $service['Service']['name'] == '') {
                $_serviceName = $service['Servicetemplate']['name'];
            }
            //Add host to deleted objects table
            $DeletedService = ClassRegistry::init('DeletedService');
            $DeletedService->create();
            $data = [
                'DeletedService' => [
                    'uuid'               => $service['Service']['uuid'],
                    'host_uuid'          => $service['Host']['uuid'],
                    'servicetemplate_id' => $service['Service']['servicetemplate_id'],
                    'host_id'            => $service['Service']['host_id'],
                    'name'               => $_serviceName,
                    'description'        => $service['Service']['description'],
                    'deleted_perfdata'   => 0,
                ],
            ];
            $DeletedService->save($data);

            /*
             * Check if the service was part of an servicegroup, serviceescalation or servicedependency
             * If yes, cake delete the records by it self, but may be we have an empty serviceescalation or servicegroup now.
             * Nagios don't relay like this so we need to check this and delete the serviceescalation/servicegroup or service dependency if empty
             */
            $this->_clenupServiceEscalationDependencyAndGroup($service);

            return true;
        }

        return false;
    }

    /*
     * Check if the service was part of an servicegroup, serviceescalation or servicedependency
     * If yes, cake delete the records by it self, but may be we have an empty serviceescalation or servicegroup now.
     * Nagios don't relay like this so we need to check this and delete the serviceescalation/servicegroup or service dependency if empty
     */
    public function _clenupServiceEscalationDependencyAndGroup($service) {
        if (!empty($service['ServiceEscalationServiceMembership'])) {
            $Serviceescalation = ClassRegistry::init('Serviceescalation');
            foreach ($service['ServiceEscalationServiceMembership'] as $_serviceescalation) {
                $serviceescalation = $Serviceescalation->findById($_serviceescalation['serviceescalation_id']);
                if (empty($serviceescalation['ServiceescalationServiceMembership']) && empty($serviceescalation['ServiceescalationServicegroupMembership'])) {
                    //This eslacation is empty now, so we can delete it
                    $Serviceescalation->delete($serviceescalation['Serviceescalation']['id']);
                }
            }
        }

        if (!empty($service['ServicedependencyServiceMembership'])) {
            $Servicedependency = ClassRegistry::init('Servicedependency');
            foreach ($service['ServicedependencyServiceMembership'] as $_servicedependency) {
                $servicedependency = $Servicedependency->findById($_servicedependency['servicedependency_id']);
                if (empty($servicedependency['ServicedependencyServiceMembership']) && empty($servicedependency['ServicedependencyServicegroupMembership'])) {
                    $Servicedependency->delete($servicedependency['Servicedependency']['id']);
                } else {
                    //Not the whole dependency is empty, but may be its broken
                    $services = Hash::extract($servicedependency['ServicedependencyServiceMembership'], '{n}[dependent=0]');
                    $dependentServices = Hash::extract($servicedependency['ServicedependencyServiceMembership'], '{n}[dependent=1]');
                    if (empty($services) || empty($dependentServices)) {
                        //Data is not valid, delete!
                        $Servicedependency->delete($servicedependency['Servicedependency']['id']);
                    }
                }
            }
        }
    }

    /**
     * @param $service
     * @param $moduleConstants
     * @return array
     */
    public function isUsedByModules($service, $moduleConstants) {
        $usedBy = [];
        foreach ($moduleConstants as $moduleName => $value) {
            if ($service['Service']['usage_flag'] & $value) {
                $usedBy[$moduleName] = $value;
            }
        }
        return $usedBy;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function humanizeModuleConstantName($name) {
        return str_replace('_MODULE', '', $name);
    }

    /**
     * @param int $serviceId
     * @param int $moduleValue
     * @return bool
     */
    public function checkUsageFlag($serviceId, $moduleValue) {
        $result = $this->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Service.id' => $serviceId,
                //'Service.usage_flag &'.$moduleValue
            ],
            'fields'     => [
                'Service.usage_flag'
            ]
        ]);

        if (!empty($result)) {
            $result = $result['Service']['usage_flag'];
            $this->currentUsageFlag = $result;
            if ($result & $moduleValue) {
                return true;
            }
            return false;
        }
    }

    public function virtualFieldsForIndexAndServiceList() {
        $this->virtualFields['servicename'] = 'IF((Service.name IS NULL OR Service.name=""), Servicetemplate.name, Service.name)';
        $this->virtualFields['keywords'] = 'IF((Service.tags IS NULL OR Service.tags=""), Servicetemplate.tags, Service.tags)';
    }

    public function virtualFieldsForNotMonitored() {
        $this->virtualFields['servicename'] = 'IF((Service.name IS NULL OR Service.name=""), Servicetemplate.name, Service.name)';
    }

    public function virtualFieldsForDisabled() {
        $this->virtualFieldsForNotMonitored();
    }

    /**
     * @param ServiceConditions $ServiceConditions
     * @param array $conditions
     * @return array
     */
    public function getServiceIndexQuery(ServiceConditions $ServiceConditions, $conditions = []) {
        $query = [
            'recursive'  => -1,
            'conditions' => $conditions,
            'contain'    => ['Servicetemplate'],
            'fields'     => [
                //'DISTINCT (Service.id) as banane', //Fix pagination
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.description',
                'Service.active_checks_enabled',
                'Service.tags',

                'Servicestatus.current_state',
                'Servicestatus.last_check',
                'Servicestatus.next_check',
                'Servicestatus.last_hard_state_change',
                'Servicestatus.last_state_change',
                'Servicestatus.output',
                'Servicestatus.scheduled_downtime_depth',
                'Servicestatus.active_checks_enabled',
                'Servicestatus.state_type',
                'Servicestatus.problem_has_been_acknowledged',
                'Servicestatus.acknowledgement_type',
                'Servicestatus.is_flapping',

                'Servicetemplate.id',
                'Servicetemplate.uuid',
                'Servicetemplate.name',
                'Servicetemplate.description',
                'Servicetemplate.active_checks_enabled',
                'Servicetemplate.tags',

                'Host.name',
                'Host.id',
                'Host.uuid',
                'Host.description',
                'Host.address',
                'Host.satellite_id',

                'Hoststatus.current_state',
                'Hoststatus.is_flapping',
                'Hoststatus.last_hard_state_change',

                'HostsToContainers.container_id',
            ],
            'order'      => $ServiceConditions->getOrder(),
            'joins'      => [
                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Service.host_id = Host.id',
                ], [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'HostObject',
                    'conditions' => 'Host.uuid = HostObject.name1 AND HostObject.objecttype_id = 1',
                ], [
                    'table'      => 'nagios_hoststatus',
                    'type'       => 'INNER',
                    'alias'      => 'Hoststatus',
                    'conditions' => 'Hoststatus.host_object_id = HostObject.object_id',
                ], [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'ServiceObject',
                    'conditions' => 'ServiceObject.name1 = Host.uuid AND Service.uuid = ServiceObject.name2 AND ServiceObject.objecttype_id = 2',
                ], [
                    'table'      => 'nagios_servicestatus',
                    'type'       => 'LEFT OUTER',
                    'alias'      => 'Servicestatus',
                    'conditions' => 'Servicestatus.service_object_id = ServiceObject.object_id',
                ], [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ],
            ],
            'group'      => [
                'Service.id',
            ],
        ];

        $query['conditions']['Service.disabled'] = (int)$ServiceConditions->includeDisabled();
        $query['conditions']['HostsToContainers.container_id'] = $ServiceConditions->getContainerIds();

        if ($ServiceConditions->getHostId()) {
            $query['conditions']['Service.host_id'] = $ServiceConditions->getHostId();
        }

        return $query;

    }

    /**
     * @param ServiceConditions $ServiceConditions
     * @param array $conditions
     * @return array
     */
    public function getServiceNotMonitoredQuery(ServiceConditions $ServiceConditions, $conditions = []) {
        $query = [
            'recursive'  => -1,
            'conditions' => $conditions,
            'contain'    => ['Servicetemplate'],
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.description',
                'Service.active_checks_enabled',

                'Servicetemplate.id',
                'Servicetemplate.uuid',
                'Servicetemplate.name',
                'Servicetemplate.description',
                'Servicetemplate.active_checks_enabled',

                'ServiceObject.object_id',

                'Host.name',
                'Host.id',
                'Host.uuid',
                'Host.description',
                'Host.address',
            ],
            'order'      => $ServiceConditions->getOrder(),
            'joins'      => [
                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Service.host_id = Host.id',
                ], [
                    'table'      => 'nagios_objects',
                    'type'       => 'LEFT OUTER',
                    'alias'      => 'ServiceObject',
                    'conditions' => 'ServiceObject.name1 = Host.uuid AND Service.uuid = ServiceObject.name2 AND ServiceObject.objecttype_id = 2',
                ]
            ]
        ];

        //$query['order'] = Hash::merge(['Service.id' => 'asc'], $ServiceConditions->getOrder());
        $query['conditions'][] = [
            "EXISTS (SELECT * FROM hosts_to_containers AS HostsToContainers WHERE HostsToContainers.container_id )" =>
                $ServiceConditions->getContainerIds()
        ];

        $query['conditions']['Service.disabled'] = (int)$ServiceConditions->includeDisabled();
        //$query['conditions']['HostsToContainers.container_id'] = $ServiceConditions->getContainerIds();

        if ($ServiceConditions->getHostId()) {
            $query['conditions']['Service.host_id'] = $ServiceConditions->getHostId();
        }

        $query['conditions'][] = 'ServiceObject.name2 IS NULL';

        return $query;

    }

    /**
     * @param ServiceConditions $ServiceConditions
     * @param array $conditions
     * @return array
     */
    public function getServiceDisabledQuery(ServiceConditions $ServiceConditions, $conditions = []) {
        $query = [
            'recursive'  => -1,
            'conditions' => $conditions,
            'contain'    => ['Servicetemplate'],
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.name',

                'Servicetemplate.id',
                'Servicetemplate.uuid',
                'Servicetemplate.name',

                'Host.name',
                'Host.id',
                'Host.uuid',
                'Host.description',
                'Host.address',
            ],
            'order'      => $ServiceConditions->getOrder(),
            'joins'      => [
                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Service.host_id = Host.id',
                ]
            ]
        ];

        //$query['order'] = Hash::merge(['Service.id' => 'asc'], $ServiceConditions->getOrder());
        $query['conditions'][] = [
            "EXISTS (SELECT * FROM hosts_to_containers AS HostsToContainers WHERE HostsToContainers.container_id )" =>
                $ServiceConditions->getContainerIds()
        ];

        $query['conditions']['Service.disabled'] = 1;

        if ($ServiceConditions->getHostId()) {
            $query['conditions']['Service.host_id'] = $ServiceConditions->getHostId();
        }

        return $query;

    }

    /**
     * @param ServiceConditions $ServiceConditions
     * @param array $conditions
     * @return array
     */
    public function getServiceDeletedQuery(ServiceConditions $ServiceConditions, $conditions = []) {
        $query = [
            'recursive'  => -1,
            'conditions' => $conditions,
            'fields'     => [
                'DeletedService.*'
            ],
            'order'      => $ServiceConditions->getOrder(),
        ];

        if ($ServiceConditions->getHostId()) {
            $query['conditions']['DeletedService.host_id'] = $ServiceConditions->getHostId();
        }

        return $query;

    }

    /**
     * @param ServiceConditions $ServiceConditions
     * @param array $selected
     * @return array|null
     */
    public function getServicesForAngular(ServiceConditions $ServiceConditions, $selected = []) {
        $query = [
            'recursive' => -1,
            'joins'     => [
                [
                    'table'      => 'servicetemplates',
                    'alias'      => 'Servicetemplate',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Servicetemplate.id = Service.servicetemplate_id',
                    ],
                ],
                [
                    'table'      => 'hosts',
                    'alias'      => 'Host',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Host.id = Service.host_id',
                    ],
                ],
                [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'INNER',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ],
            ],

            'order'  => [
                'Host.name'                                                    => 'ASC',
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name)' => 'ASC'
            ],
            'fields' => [
                'Service.id',
                'Service.name',
                'Host.id',
                'Host.name',
                'Servicetemplate.name'

            ],
            'group'  => [
                'Service.id'
            ],
            'limit'  => self::ITN_AJAX_LIMIT
        ];

        if (!empty($ServiceConditions->getConditions())) {
            $query['conditions']['OR'] = $ServiceConditions->getConditions();
        }

        if (is_array($selected)) {
            $selected = array_filter($selected);
        }
        if (!empty($selected)) {
            $query['conditions']['NOT'] = ['Service.id' => $selected];
        }

        if ($ServiceConditions->hasNotConditions()) {
            if (!empty($query['conditions']['NOT'])) {
                $query['conditions']['NOT'] = array_merge($query['conditions']['NOT'], $ServiceConditions->getNotConditions());
            } else {
                $query['conditions']['NOT'] = $ServiceConditions->getNotConditions();
            }

        }

        $servicesWithLimit = $this->find('all', $query);
        $servicesWithLimit = Hash::combine($servicesWithLimit, '{n}.Service.id', '{n}');

        $selectedServices = [];

        if (is_array($selected)) {
            $selected = array_filter($selected);
        }

        if (!empty($selected)) {
            $query = [
                'recursive'  => -1,
                'joins'      => [
                    [
                        'table'      => 'servicetemplates',
                        'alias'      => 'Servicetemplate',
                        'type'       => 'INNER',
                        'conditions' => [
                            'Servicetemplate.id = Service.servicetemplate_id',
                        ],
                    ],
                    [
                        'table'      => 'hosts',
                        'alias'      => 'Host',
                        'type'       => 'INNER',
                        'conditions' => [
                            'Host.id = Service.host_id',
                        ],
                    ],
                    [
                        'table'      => 'hosts_to_containers',
                        'alias'      => 'HostsToContainers',
                        'type'       => 'INNER',
                        'conditions' => [
                            'HostsToContainers.host_id = Host.id',
                        ],
                    ],
                ],
                'conditions' => [
                    'Service.id' => $selected
                ],
                'order'      => [
                    'Host.name'                                                    => 'ASC',
                    'IF(Service.name IS NULL, Servicetemplate.name, Service.name)' => 'ASC'
                ],
                'fields'     => [
                    'Service.id',
                    'Service.name',
                    'Host.id',
                    'Host.name',
                    'Servicetemplate.name'

                ],
                'group'      => [
                    'Service.id'
                ]
            ];
            $query['conditions']['HostsToContainers.container_id'] = $ServiceConditions->getContainerIds();
            $selectedServices = $this->find('all', $query);
            $selectedServices = Hash::combine($selectedServices, '{n}.Service.id', '{n}');
        }
        return $servicesWithLimit + $selectedServices;
    }

    /**
     * @param int $serviceId
     * @return array
     */
    public function getQueryForBrowser($serviceId) {
        return [
            'recursive'  => -1,
            'conditions' => [
                'Service.id' => $serviceId,
            ],
            'contain'    => [
                'Contact'                          => [
                    'fields' => [
                        'id',
                        'name',
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
                'Servicecommandargumentvalue'      => [
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
                'Serviceeventcommandargumentvalue' => [
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
                'CheckCommand',
                'CheckPeriod',
                'NotifyPeriod',
            ]
        ];
    }

    /**
     * Updates multiple model records based on a set of conditions.
     *
     * @param array $fields Set of fields and values, indexed by fields.
     *    Fields are treated as SQL snippets, to insert literal values manually escape your data.
     * @param mixed $conditions Conditions to match, true for all records
     * @return bool True on success, false on failure
     * @link https://book.cakephp.org/2.0/en/models/saving-your-data.html#model-updateall-array-fields-mixed-conditions
     */
    public function updateAll($fields, $conditions = true) {
        $success = parent::updateAll($fields, $conditions);

        //Also update disable field in CrateDB for Services
        //CakePHP does not fire the afterSave callback, if updateAll got called.
        if ($success && $this->DbBackend->isCrateDb()) {
            if (is_array($fields) && sizeof($fields) === 1 && is_array($conditions) && sizeof($conditions) === 1) {
                if(isset($fields['Service.disabled']) && isset($conditions['Service.host_id'])){
                    $CrateServiceModel = ClassRegistry::init('CrateModule.CrateService');
                    $CrateServiceModel->updateAll([
                        'disabled' => (bool)$fields['Service.disabled']
                    ], [
                        'host_id' => $conditions['Service.host_id']
                    ]);
                }
            }
        }
        return $success;
    }

    /**
     * @param bool $created
     * @param array $options
     * @return bool|void
     */
    public function afterSave($created, $options = []) {
        if ($this->DbBackend->isCrateDb() && isset($this->data['Service']['id'])) {
            //Save data also to CrateDB
            $CrateService = new \itnovum\openITCOCKPIT\Crate\CrateService($this->data['Service']['id']);
            $service = $this->find('first', $CrateService->getFindQuery());
            $CrateService->setDataFromFindResult($service);

            $CrateServiceModel = ClassRegistry::init('CrateModule.CrateService');
            $CrateServiceModel->save($CrateService->getDataForSave());
        }

        parent::afterSave($created, $options);
    }

    public function beforeDelete($cascade = true) {
        $this->LastDeletedId = new LastDeletedId($this->id);
        return parent::beforeDelete($cascade);
    }

    public function afterDelete() {
        if ($this->LastDeletedId !== null) {
            if ($this->DbBackend->isCrateDb() && $this->LastDeletedId->hasId()) {
                $CrateServiceModel = ClassRegistry::init('CrateModule.CrateService');
                $CrateServiceModel->delete($this->LastDeletedId->getId());
                $this->LastDeletedId = null;
            }
        }

        parent::afterDelete();
    }
}
