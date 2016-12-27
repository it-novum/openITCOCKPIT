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


class Contact extends AppModel
{
    public $hasAndBelongsToMany = [
        'Container'       => [
            'className'             => 'Container',
            'joinTable'             => 'contacts_to_containers',
            'foreignKey'            => 'contact_id',
            'associationForeignKey' => 'container_id',
            //		'conditions' => ['Container.containertype_id' => [CT_TENANT, CT_GLOBAL]]
        ],
        'HostCommands'    => [
            'className'             => 'Command',
            'joinTable'             => 'contacts_to_hostcommands',
            'foreignKey'            => 'contact_id',
            'associationForeignKey' => 'command_id',
        ],
        'ServiceCommands' => [
            'className'             => 'Command',
            'joinTable'             => 'contacts_to_servicecommands',
            'foreignKey'            => 'contact_id',
            'associationForeignKey' => 'command_id',
        ],

        'Contactgroup' => [
            'className'             => 'Contactgroup',
            'joinTable'             => 'contacts_to_containers',
            'foreignKey'            => 'contact_id',
            'associationForeignKey' => 'container_id',
            //		'conditions' => ['Contactgroup.containertype_id' => CT_CONTACTGROUP]
        ],
    ];

    public $belongsTo = [
        'HostTimeperiod'    => [
            'dependent'  => true,
            'foreignKey' => 'host_timeperiod_id',
            'className'  => 'Timeperiod',
        ],
        'ServiceTimeperiod' => [
            'dependent'  => true,
            'foreignKey' => 'service_timeperiod_id',
            'className'  => 'Timeperiod',
        ],
    ];

    var $validate = [
        'Container' => [
            'rule'    => ['multiple', ['min' => 1]],
            'message' => 'Please select one or more containers',
        ],
        'name'      => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'isUnique' => [
                'rule'    => 'isUnique',
                'message' => 'This contact name has already been taken.',
            ],
        ],
        'email'     => [
            'atLeastOne'     => [
                'rule'     => ['atLeastOne'],
                'message'  => 'You must at least specify either the email address or the phone number.',
                'required' => true,
            ],
            'validEmailRule' => [
                'rule'       => ['email'],
                'message'    => 'Invalid email address',
                'allowEmpty' => true,
            ],
        ],

        'phone' => [
            'atLeastOne' => [
                'rule'     => ['atLeastOne'],
                'message'  => 'You must at least specify either the email address or the phone number.',
                'required' => true,
            ],
            'phone'      => [
                'allowEmpty' => true,
                'message'    => 'Invalid phone number.',
                'rule'       => ['phone', '/[\d\s-\+]+/'],
            ],
        ],

        'host_timeperiod_id' => [
            'notBlank' => [
                'allowEmpty' => false,
                'rule'       => 'notBlank',
                'message'    => 'This field cannot be left blank.',
                'required'   => true,
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'Invalid timeperiod.',
                'required' => true,
            ],
        ],

        'HostCommands'          => [
            'notBlank' => [
                'allowEmpty' => false,
                'rule'       => ['multiple', [
                    'min' => 1,
                ]],
                'message'    => 'You have to choose at least one command.',
                'required'   => true,
            ],
        ],
        'service_timeperiod_id' => [
            'notBlank' => [
                'allowEmpty' => false,
                'rule'       => 'notBlank',
                'message'    => 'This field cannot be left blank.',
                'required'   => true,
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'Invalid timeperiod.',
                'required' => true,
            ],
        ],

        'ServiceCommands'         => [
            'notBlank' => [
                //'allowEmpty' => false,
                'rule'     => ['multiple', [
                    'min' => 1,
                ]],
                'message'  => 'You have to choose at least one command.',
                'required' => true,
            ],
        ],
        'notify_host_recovery'    => [
            'check_options' => [
                'rule'       => ['checkNotificationOptions', 'host'],
                'message'    => 'You have to choose at least one option.',
                'allowEmpty' => true,
                'required'   => false,
            ],
        ],
        'notify_service_recovery' => [
            'check_options' => [
                'rule'       => ['checkNotificationOptions', 'service'],
                'message'    => 'You have to choose at least one option.',
                'allowEmpty' => true,
                'required'   => false,
            ],
        ],
    ];

    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->notification_options = [
            'host'    => [
                'notify_host_recovery',
                'notify_host_down',
                'notify_host_unreachable',
                'notify_host_flapping',
                'notify_host_downtime',
            ],
            'service' => [
                'notify_service_recovery',
                'notify_service_warning',
                'notify_service_unknown',
                'notify_service_critical',
                'notify_service_flapping',
                'notify_service_downtime',
            ],
        ];
        App::uses('UUID', 'Lib');
    }


    function checkNotificationOptions($data, $notification_type)
    {
        foreach ($this->data as $request) {
            foreach ($request as $request_key => $request_value) {
                if (in_array($request_key, $this->notification_options[$notification_type]) && $request_value == 1) {
                    return true;
                }
            }
        }

        return false;
    }

    public function filterZero($var)
    {
        if ($var == 0) {
            return false;
        }

        return true;
    }

    public function beforeValidate($options = [])
    {
        foreach ($this->hasAndBelongsToMany as $k => $v) {
            if (isset($this->data[$k][$k])) {
                $this->data[$this->alias][$k] = $this->data[$k][$k];
            }
        }
    }

    public function beforeSave($options = [])
    {
        foreach (array_keys($this->hasAndBelongsToMany) as $model) {
            if (isset($this->data[$this->name][$model])) {
                $this->data[$model][$model] = $this->data[$this->name][$model];
                unset($this->data[$this->name][$model]);
            }
        }

        return true;
    }


    public function contactsByContainerId($container_ids = [], $type = 'all')
    {
        if (!is_array($container_ids)) {
            $container_ids = [$container_ids];
        }

        $container_ids = array_unique($container_ids);

        //Lookup for the tenant container of $container_id
        $this->Container = ClassRegistry::init('Container');

        $tenantContainerIds = [];

        foreach ($container_ids as $container_id) {
            if ($container_id != ROOT_CONTAINER) {

                // Get container id of the tenant container
                // $container_id is may be a location, devicegroup or whatever, so we need to container id of the tenant container to load contactgroups and contacts
                $path = Cache::remember('ContactContactsByContainerId:'.$container_id, function () use ($container_id) {
                    return $this->Container->getPath($container_id);
                }, 'migration');
                $tenantContainerIds[] = $path[1]['Container']['id'];
            } else {
                $tenantContainerIds[] = ROOT_CONTAINER;
            }
        }
        $tenantContainerIds = array_unique($tenantContainerIds);

        return $this->find($type, [
            'joins'      => [
                ['table'      => 'contacts_to_containers',
                 'alias'      => 'ContactsToContainers',
                 'type'       => 'LEFT',
                 'conditions' => [
                     'ContactsToContainers.contact_id = Contact.id',
                 ],
                ],
            ],
            'conditions' => [
                'ContactsToContainers.container_id' => $tenantContainerIds,
            ],
            'order'      => [
                'Contact.name' => 'ASC',
            ],
        ]);
    }

    /*
     * Custom validation rule for email and/or phone fields.
    */
    public function atLeastOne($data)
    {
        $result = !empty($this->data[$this->name]['email']) || !empty($this->data[$this->name]['phone']);

        return $result;
    }

}
