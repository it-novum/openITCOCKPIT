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

App::uses('ValidationCollection', 'Lib');

use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\HostConditions;
use itnovum\openITCOCKPIT\Core\ValueObjects\LastDeletedId;
use itnovum\openITCOCKPIT\Filter\HostFilter;

/**
 * @property ParentHost $ParentHost
 * @property DbBackend $DbBackend
 */
class Host extends AppModel {

    public $hasAndBelongsToMany = [
        'Container'    => [
            'className'             => 'Container',
            'joinTable'             => 'hosts_to_containers',
            'foreignKey'            => 'host_id',
            'associationForeignKey' => 'container_id',
        ],
        'Contactgroup' => [
            'className'             => 'Contactgroup',
            'joinTable'             => 'contactgroups_to_hosts',
            'foreignKey'            => 'host_id',
            'associationForeignKey' => 'contactgroup_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'Contact'      => [
            'className'             => 'Contact',
            'joinTable'             => 'contacts_to_hosts',
            'foreignKey'            => 'host_id',
            'associationForeignKey' => 'contact_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'Parenthost'   => [
            'className'             => 'Host',
            'joinTable'             => 'hosts_to_parenthosts',
            'foreignKey'            => 'host_id',
            'associationForeignKey' => 'parenthost_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'Hostgroup'    => [
            'className'             => 'Hostgroup',
            'joinTable'             => 'hosts_to_hostgroups',
            'foreignKey'            => 'host_id',
            'associationForeignKey' => 'hostgroup_id',
            'unique'                => true,
            'dependent'             => true,
        ],
    ];

    public $hasMany = [
        'Hostcommandargumentvalue',
        'HostescalationHostMembership' => [
            'className'  => 'HostescalationHostMembership',
            'foreignKey' => 'host_id',
            'dependent'  => true,
        ],
        'HostdependencyHostMembership' => [
            'className'  => 'HostdependencyHostMembership',
            'foreignKey' => 'host_id',
            'dependent'  => true,
        ],
        'Service'                      => [
            'className'  => 'Service',
            'foreignKey' => 'host_id',
            'dependent'  => true,
        ],
        'Customvariable'               => [
            'className'  => 'Customvariable',
            'foreignKey' => 'object_id',
            'conditions' => [
                'objecttype_id' => OBJECT_HOST,
            ],
            'dependent'  => true,
        ],
    ];

    public $belongsTo = [
        'Hosttemplate',
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

    public $validate = [
        'name'               => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            /*'isUnique' => [
                'rule' => 'isUnique',
                'message' => 'This host name has already been taken.'
            ],*/
        ],
        'container_id'       => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'This field needs to be numeric.',
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'Invalid container.',
                'required' => true,
            ],
        ],
        'hosttemplate_id'    => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'This field needs to be numeric.',
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'Invalid host template.',
                'required' => true,
            ],
        ],
        'address'            => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        /*
        'Contact' => [
            'atLeastOne' => [
                'rule' => ['atLeastOne'],
                'message' => 'You must specify at least one contact or contact group.',
                'required' => true
            ]
        ],
        'Contactgroup' => [
            'atLeastOne' => [
                'rule' => ['atLeastOne'],
                'message' => 'You must specify at least one contact or contact group',
                'required' => true
            ]
        ],
        */
        'command_id'         => [
            'numeric' => [
                'rule'       => 'numeric',
                'message'    => 'This field needs to be numeric.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'notZero' => [
                'rule'       => ['comparison', '>', 0],
                'message'    => 'This field cannot be left blank.',
                'allowEmpty' => true,
                'required'   => false,
            ],
        ],
        'max_check_attempts' => [
            'notBlank'    => [
                'rule'       => 'notBlank',
                'message'    => 'This field cannot be left blank.',
                'allowEmpty' => true,
                'required'   => false,
            ],
            'positiveInt' => [
                'rule'    => ['positiveInt', 'max_check_attempts'],
                'message' => 'This value need to be at least 1.',
            ],

        ],
    ];

    /**
     * @var LastDeletedId|null
     */
    private $LastDeletedId = null;

    /**
     * @param HostConditions $HostConditions
     * @param array $selected
     * @return array|null
     */
    public function getHostsForAngular(HostConditions $HostConditions, $selected = []) {
        $query = [
            'recursive'  => -1,
            'joins'      => [
                [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'INNER',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ],
            ],
            'conditions' => $HostConditions->getConditionsForFind(),
            'order'      => [
                'Host.name' => 'ASC',
            ],
            'group'      => [
                'Host.id'
            ],
            'limit'      => self::ITN_AJAX_LIMIT
        ];
        $hostsWithLimit = $this->find('list', $query);

        $selectedHosts = [];
        if (!empty($selected)) {
            $query = [
                'recursive'  => -1,
                'joins'      => [
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
                    'Host.id' => $selected
                ],
                'order'      => [
                    'Host.name' => 'ASC',
                ],
            ];
            if ($HostConditions->hasContainer()) {
                $query['conditions']['HostsToContainers.container_id'] = $HostConditions->getContainerIds();
            }
            if ($HostConditions->includeDisabled() === false) {
                $query['conditions']['Host.disabled'] = 0;
            }
            $selectedHosts = $this->find('list', $query);
        }

        $hosts = $hostsWithLimit + $selectedHosts;
        asort($hosts, SORT_FLAG_CASE | SORT_NATURAL);
        return $hosts;
    }

    /**
     * Returns an array with hosts, the user is allowd to see by container_id
     *
     * @param array $containerIds Container IDs of container ids the user is allowd to see
     * @param string $type cake's find types
     * @param array $conditions Additional conditions for selecting hosts
     * @param string $index of associative array in result
     * @param integer $limit or hosts. null if no limit needed.
     *
     * @return array
     */
    public function hostsByContainerId($containerIds = [], $type = 'all', $conditions = [], $index = 'id', $limit = null) {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }
        $containerIds = array_unique($containerIds);

        $_conditions = [
            'HostsToContainers.container_id' => $containerIds,
            'Host.disabled'                  => 0,
        ];

        $conditions = Hash::merge($_conditions, $conditions);

        $selectArray = [
            'recursive'  => -1,
            'joins'      => [
                [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ],
            ],
            'conditions' => $conditions,
            'order'      => [
                'Host.name' => 'ASC',
            ],
            'fields'     => [
                'Host.' . $index,
                'Host.name',
            ],
        ];
        if (!is_null($limit)) {
            $selectArray['limit'] = $limit;
        }

        $hosts = $this->find($type, $selectArray);

        if (in_array($type, ['list', 'first'])) {
            return $hosts;
        }

        $result = [];
        foreach ($hosts as $host) {
            $result[$host['Host'][$index]] = $host['Host']['name'];
        }

        return $result;
    }

    /**
     * same as $this->hostsByContainerId but remove the host with id given in $id
     *
     * @param array $container_ids
     * @param    string $type cake's find types
     * @param    int $id of a host you want to remove from result
     *
     * @return array
     */
    public function hostsByContainerIdExcludeHostId($container_ids = [], $type = 'all', $id) {
        return $this->find($type, [
            'recursive'  => -1,
            'joins'      => [
                [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ],
            ],
            'conditions' => [
                'HostsToContainers.container_id' => $container_ids,
                'Host.disabled'                  => 0,
                'NOT'                            => ['Host.id' => $id],
            ],
            'order'      => [
                'Host.name' => 'ASC',
            ],
        ]);
    }

    public function getDiffAsArray($host_values = [], $hosttemplate_values = []) {
        $host_values = ($host_values === null) ? [] : $host_values;
        $hosttemplate_values = ($hosttemplate_values === null) ? [] : $hosttemplate_values;

        return Hash::diff($host_values, $hosttemplate_values);
    }

    public function prepareForCompare($prepare_array = [], $prepare = false) {
        $keysForArraySort = ['Contact', 'Contactgroup', 'Hostgroup']; //sort array for array diff
        //if prepare_for_compare => false, nothing to do $prepare_array[0] => 'Template.{n}, $prepare_array[1] => true/false'

        if (!$prepare) {
            if (!is_array($prepare_array)) return [];
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

    public function prepareForSave($diff_array = [], $requestData = [], $save_mode = 'add') {
        //Check differences for notification settings
        if (!empty(Set::classicExtract($diff_array, 'Host.{(notify_on_).*}'))) {
            //Overwrite all notification settings if at least one option has been changed
            $diff_array = Hash::merge($diff_array, ['Host' => Set::classicExtract($requestData, 'Host.{(notify_on_).*}')]);
        }
        //Check differences for flap detection settings
        if (!empty(Set::classicExtract($diff_array, 'Host.{(flap_detection_on_).*}'))) {
            //Overwrite all flap detection settings if at least one option has been changed

            $diff_array = Hash::merge($diff_array, ['Host' => Set::classicExtract($requestData, 'Host.{(flap_detection_on_).*}')]);
        }
        //Set default for contact/contactgroup settings
        $diff_array = Hash::merge($diff_array, ['Host' => ['own_contacts' => '0', 'own_contactgroups' => '0', 'own_customvariables' => '0']]);
        if ($save_mode === 'edit') {
            $tmp_keys = array_diff_key($requestData['Host'], $diff_array['Host']);
        }

        //Because of nagios 4 inheritance
        //See https://github.com/naemon/naemon-core/pull/92
        $breakInherit = false;
        if (isset($diff_array['Contact']) && empty($diff_array['Contactgroup']['Contactgroup'])) {
            if (empty($requestData['Contact']['Contact'])) {
                $diff_array['Contact']['Contact'] = [];
            } else {
                $diff_array['Contact']['Contact'] = $requestData['Contact']['Contact'];
            }
            if (empty($requestData['Contactgroup']['Contactgroup'])) {
                $diff_array['Contactgroup']['Contactgroup'] = [];
            } else {
                $diff_array['Contactgroup']['Contactgroup'] = $requestData['Contactgroup']['Contactgroup'];
            }
            $diff_array = Hash::merge($diff_array, ['Host' => ['own_contacts' => '1']]);
            $diff_array = Hash::merge($diff_array, ['Host' => ['own_contactgroups' => '1']]);
            $breakInherit = true;
        }

        //Because of nagios 4 inheritance
        //See https://github.com/naemon/naemon-core/pull/92
        if (!empty($diff_array['Contact']['Contact']) || !empty($diff_array['Contactgroup']['Contactgroup'])) {
            $diff_array['Contact']['Contact'] = empty($requestData['Contact']['Contact']) ? [] : $requestData['Contact']['Contact'];
            $diff_array['Contactgroup']['Contactgroup'] = empty($requestData['Contactgroup']['Contactgroup']) ? [] : $requestData['Contactgroup']['Contactgroup'];
            $diff_array = Hash::merge($diff_array, ['Host' => ['own_contacts' => '1']]);
            $diff_array = Hash::merge($diff_array, ['Host' => ['own_contactgroups' => '1']]);
            $breakInherit = true;
        }

        //Because of nagios 4 inheritance
        //See https://github.com/naemon/naemon-core/pull/92
        if (empty($diff_array['Contact']['Contact']) && empty($diff_array['Contactgroup']['Contactgroup'])) {
            $diff_array['Contact']['Contact'] = [];
            $diff_array['Contactgroup']['Contactgroup'] = [];
            $diff_array = Hash::merge($diff_array, ['Host' => ['own_contacts' => '0']]);
            $diff_array = Hash::merge($diff_array, ['Host' => ['own_contactgroups' => '0']]);
            $breakInherit = true;
        }

        //Because of nagios 4 inheritance
        //See https://github.com/naemon/naemon-core/pull/92
        /*
        if(!isset($request_data['Contact']['Contact'])){
            $request_data['Contact']['Contact'] = [];
        }

        if(!isset($request_data['Contactgroup']['Contactgroup'])){
            $request_data['Contactgroup']['Contactgroup'] = [];
        }


        if(isset($diff_array['Contact']['Contact']) || ((isset($diff_array['Contact']['Contact']) && $diff_array['Contact']['Contact'] == null)) && !isset($diff_array['Contactgroup']['Contactgroup'])){
            $diff_array['Contact']['Contact'] = ($request_data['Contact']['Contact'] == '')?[]:$request_data['Contact']['Contact'];
            $diff_array['Contactgroup']['Contactgroup'] = ($request_data['Contactgroup']['Contactgroup'] == '')?[]:$request_data['Contactgroup']['Contactgroup'];
            $diff_array = Hash::merge($diff_array, ['Host' => ['own_contacts' => '1']]);
            $diff_array = Hash::merge($diff_array, ['Host' => ['own_contactgroups' => '1']]);
            $breakInherit = true;
        }

        //Because of nagios 4 inheritance
        if(isset($diff_array['Contactgroup']['Contactgroup']) || ((isset($diff_array['Contactgroup']['Contactgroup']) && $diff_array['Contactgroup']['Contactgroup'] == null)) && !isset($diff_array['Contact']['Contact'])){
        //if(!isset($diff_array['Contact']['Contact']) && (isset($diff_array['Contactgroup']['Contactgroup']) || $diff_array['Contactgroup']['Contactgroup'] == null)){
            $diff_array['Contact']['Contact'] = ($request_data['Contact']['Contact'] == '')?[]:$request_data['Contact']['Contact'];
            $diff_array['Contactgroup']['Contactgroup'] = ($request_data['Contactgroup']['Contactgroup'] == '')?[]:$request_data['Contactgroup']['Contactgroup'];
            $diff_array = Hash::merge($diff_array, ['Host' => ['own_contacts' => '1']]);
            $diff_array = Hash::merge($diff_array, ['Host' => ['own_contactgroups' => '1']]);
            $breakInherit = true;
        }
        //debug($breakInherit);
        //debug($request_data);debug($diff_array);die('test');
        */
        if (!$breakInherit) {
            //Check differences for contacts and contactgroups
            foreach (Set::classicExtract($diff_array, '{(Contact|Contactgroup)}.{(Contact|Contactgroup)}.{n}') as $key => $value) {
                //overwrite default setting for: own_contact/own_contactgroups => 1 if contact/contactgroup array exists
                $diff_array = Hash::merge($diff_array, ['Host' => ['own_' . strtolower(Inflector::pluralize($key)) => '1']]);
                if ($diff_array[$key][$key] === null) {
                    //Remove empty contacts or contactgroups from array
                    $diff_array[$key][$key] = [];
                    //$diff_array = Hash::remove($diff_array, $key);
                }
            }
        }
        if ($save_mode === 'edit') {
            $diff_array = Hash::merge($diff_array, ['Host' => array_fill_keys(array_keys($tmp_keys), null)]);
        }

        $hostTemplateId = 0;
        if (isset($requestData['Host']['hosttemplate_id'])) {
            $hostTemplateId = $requestData['Host']['hosttemplate_id'];
        }

        $containerId = 0;
        if (isset($requestData['Host']['container_id'])) {
            $containerId = $requestData['Host']['container_id'];
        }

        if (isset($requestData['Host']['shared_container'])) {
            //may its serialized
            $sharedContainer = $requestData['Host']['shared_container'];

            if (empty($sharedContainer)) {
                $sharedContainer = [];
            }
            if (is_string($sharedContainer) && strlen($sharedContainer) > 0 && $result = unserialize($sharedContainer)) {
                $sharedContainer = $result;
            }

            $containerIds = array_merge([$containerId], $sharedContainer);
        } else {
            $containerIds = $containerId;
        }

        if (empty($requestData['Host']['Contactgroup'])) {
            $requestData['Host']['Contactgroup'] = [];
        }
        if (empty($requestData['Host']['Contact'])) {
            $requestData['Host']['Contact'] = [];
        }

        if (empty($requestData['Host']['Hostgroup'])) {
            $requestData['Host']['Hostgroup'] = [];
        }


        $diff_array = Hash::merge($diff_array, [
            'Host'       => [
                'hosttemplate_id' => $hostTemplateId,
                'container_id'    => $containerId,
                /* Set Contact/Contactgroup for custom validation rule*/
                'Contact'         => $requestData['Host']['Contact'],
                'Contactgroup'    => $requestData['Host']['Contactgroup'],
                'Hostgroup'       => $requestData['Host']['Hostgroup'],
                'Parenthost'      => $requestData['Parenthost']['Parenthost'],
            ],
            'Container'  => [
                'Container' => $containerIds,
            ],
            'Parenthost' => [
                'Parenthost' => $requestData['Parenthost']['Parenthost'],
            ],
        ]);
        if (empty($diff_array['Hostcommandargumentvalue'])) {
            $diff_array = Hash::merge($diff_array, [
                    'Hostcommandargumentvalue' => [],
                ]
            );
        }
        if ($save_mode === 'add') {
            $diff_array = Hash::merge($diff_array, [
                'Host' => [
                    'uuid' => UUID::v4(),
                ],
            ]);
        } else if ($save_mode === 'edit') {
            $diff_array = Hash::merge($diff_array, [
                'Host' => [
                    'id' => $requestData['Host']['id'],
                ],
            ]);
        }
        if (empty($requestData['Hostcommandargumentvalue'])) {
            $diff_array = Hash::remove($diff_array, 'Hostcommandargumentvalue');
        }

        //Because of nagios 4 inheritance
        //See https://github.com/naemon/naemon-core/pull/92
        //if(empty($diff_array['Host']['Contact']) && empty($diff_array['Host']['Contactgroup'])){
        //	$diff_array['Contact']['Contact'] = [];
        //	$diff_array['Contactgroup']['Contactgroup'] = [];
        //	$diff_array = Hash::merge($diff_array, ['Host' => ['own_contacts' => '0']]);
        //	$diff_array = Hash::merge($diff_array, ['Host' => ['own_contactgroups' => '0']]);
        //}
        return $diff_array;
    }

    /*
    Custom validation rule for contact and/or contactgroup fields
    */
    public function atLeastOne($data) {
        return !empty($this->data[$this->name]['Contact']) || !empty($this->data[$this->name]['Contactgroup']);
    }

    public function positiveInt($data) {
        return intval($data['max_check_attempts']) == $data['max_check_attempts'] && $data['max_check_attempts'] > 0;
    }

    public function prepareForView($id = null) {
        if (!$this->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }
        $host = $this->find('all', [
            'conditions' => [
                'Host.id' => $id,
            ],
            'contain'    => [
                'Container',
                'CheckPeriod',
                'NotifyPeriod',
                'CheckCommand',
                'Hosttemplate'             => [
                    'Contact'                          => [
                        'fields' => [
                            'id', 'name',
                        ],
                    ],
                    'Contactgroup'                     => [
                        'fields'    => ['id'],
                        'Container' => [
                            'fields' => [
                                'name',
                            ],
                        ],
                    ],
                    'CheckCommand',
                    'CheckPeriod',
                    'NotifyPeriod',
                    'Customvariable'                   => [
                        'fields' => [
                            'id', 'name', 'value', 'objecttype_id',
                        ],
                    ],
                    'Hosttemplatecommandargumentvalue' => [
                        'fields'          => [
                            'commandargument_id', 'value',
                        ],
                        'Commandargument' => [
                            'fields' => ['human_name'],
                        ],
                    ],
                    'Hostgroup'                        => [
                        'fields'    => ['id'],
                        'Container' => [
                            'fields' => [
                                'name',
                            ],
                        ],
                    ],
                ],
                'Contact'                  => [
                    'fields' => [
                        'id', 'name',
                    ],
                ],
                'Contactgroup'             => [
                    'fields'    => ['id'],
                    'Container' => [
                        'fields' => [
                            'name',
                        ],
                    ],
                ],
                'Customvariable'           => [
                    'fields' => [
                        'id', 'name', 'value', 'objecttype_id',
                    ],
                ],
                'Hostcommandargumentvalue' => [
                    'fields'          => [
                        'id', 'commandargument_id', 'value',
                    ],
                    'Commandargument' => [
                        'fields' => [
                            'id', 'human_name',
                        ],
                    ],
                ],
                'Parenthost'               => [
                    'fields' => [
                        'id', 'name',
                    ],
                ],
                'Hostgroup'                => [
                    'fields'    => [
                        'id',
                    ],
                    'Container' => [
                        'fields' => ['name'],
                    ],
                ],
            ],
            'recursive'  => -1,
        ]);
        $host = $host[0];
        if (empty($host['Host']['hosttemplate_id']) || $host['Host']['hosttemplate_id'] == 0) {
            return $host;
        }
        $hostcommandargumentvalue = [];
        if (!empty($host['Hostcommandargumentvalue'])) {
            $hostcommandargumentvalue = $host['Hostcommandargumentvalue'];
        } else {
            if ($host['Host']['command_id'] === $host['Hosttemplate']['command_id'] || $host['Host']['command_id'] === null) {
                $hostcommandargumentvalue = $host['Hosttemplate']['Hosttemplatecommandargumentvalue'];
            }
        }

        $hostgroups = [];
        if (!empty($host['Hostgroup'])) {
            $hostgroups = Hash::combine($host['Hostgroup'], '{n}.id', '{n}.id');
        } else if (empty($host['Hostgroup']) && !(empty($host['Hosttemplate']['Hostgroup']))) {
            $hostgroups = Hash::combine($host['Hosttemplate']['Hostgroup'], '{n}.id', '{n}.id');
        }

        $host = [
            'Host'                     => Hash::merge(Hash::filter($host['Host'], ['Host', 'filterNullValues']), Set::classicExtract($host['Hosttemplate'], '{(' . implode('|', array_keys(Hash::diff($host['Host'], Hash::filter($host['Host'], ['Host', 'filterNullValues'])))) . ')}')),
            'Contact'                  => Hash::extract((($host['Host']['own_contacts']) ? $host['Contact'] : $host['Hosttemplate']['Contact']), '{n}.id'),
            'Container'                => Hash::extract($host['Container'], '{n}.id'),
            'Contactgroup'             => Hash::extract((($host['Host']['own_contactgroups']) ? $host['Contactgroup'] : $host['Hosttemplate']['Contactgroup']), '{n}.id'),
            'Parenthost'               => Hash::extract($host['Parenthost'], '{n}.id'),
            'Customvariable'           => ($host['Host']['own_customvariables']) ? $host['Customvariable'] : $host['Hosttemplate']['Customvariable'],
            'Hostcommandargumentvalue' => $hostcommandargumentvalue,
            'Hosttemplate'             => $host['Hosttemplate'],
            'Hostgroup'                => $hostgroups,
            'CheckCommand'             => (!is_null($host['Host']['command_id'])) ? $host['CheckCommand'] : $host['Hosttemplate']['CheckCommand'],
            'CheckPeriod'              => (!is_null($host['Host']['check_period_id'])) ? $host['CheckPeriod'] : $host['Hosttemplate']['CheckPeriod'],
            'NotifyPeriod'             => (!is_null($host['Host']['notify_period_id'])) ? $host['NotifyPeriod'] : $host['Hosttemplate']['NotifyPeriod'],
        ];

        return $host;
    }

    public function dataForChangelogCopy($host, $hosttemplate) {
        $hostcommandargumentvalue = [];
        if (!empty($host['Hostcommandargumentvalue'])) {
            $hostcommandargumentvalue = $host['Hostcommandargumentvalue'];
        } else {
            if ($host['Host']['command_id'] === $hosttemplate['Hosttemplate']['command_id'] || $host['Host']['command_id'] === null) {
                $hostcommandargumentvalue = $hosttemplate['Hosttemplatecommandargumentvalue'];
            }
        }

        $host = [
            'Host'                     => Hash::merge(Hash::filter($host['Host'], ['Host', 'filterNullValues']), $hosttemplate['Hosttemplate']),
            'Contact'                  => (!empty($host['Contact'])) ? $host['Contact'] : $hosttemplate['Contact'],
            'Contactgroup'             => (!empty($host['Contactgroup'])) ? $host['Contactgroup'] : $hosttemplate['Contactgroup'],
            'Customvariable'           => ($host['Host']['own_customvariables']) ? $host['Customvariable'] : $hosttemplate['Customvariable'],
            'Hostcommandargumentvalue' => $hostcommandargumentvalue,
            'Hosttemplate'             => $hosttemplate['Hosttemplate'],
            'Hostgroup'                => (!empty($host['Hostgroup'])) ? $host['Hostgroup'] : $hosttemplate['Hostgroup'],
            'Parenthost'               => (!empty($host['Parenthost'])) ? $host['Parenthost'] : [],
            'CheckPeriod'              => (empty($host['CheckPeriod'])) ? $hosttemplate['CheckPeriod'] : $host['CheckPeriod'],
            'NotifyPeriod'             => (empty($host['NotifyPeriod'])) ? $hosttemplate['NotifyPeriod'] : $host['NotifyPeriod'],
            'CheckCommand'             => (empty($host['CheckCommand'])) ? $hosttemplate['CheckCommand'] : $host['CheckCommand'],
        ];
        return $host;
    }

    /**
     * Callback function for filtering.
     *
     * @param array $var Array to filter.
     *
     * @return boolean
     */
    public static function filterNullValues($var) {
        if ($var != null || $var === '0' || $var === '' || $var === []) {
            return true;
        }

        return false;
    }

    public function hostHasServiceByServicetemplateId($host_id, $servicetemplateId = null) {
        if ($this->exists($host_id)) {
            $host = $this->find('first', [
                'recursive'  => -1,
                'conditions' => ['Host.id' => $host_id],
                'contain'    => [
                    'Service' => [
                        'Servicetemplate' => [
                            'fields' => ['id', 'name', 'uuid'],
                        ],
                    ],
                ],
            ]);

            foreach ($host['Service'] as $service) {
                if (isset($service['Servicetemplate']['id'])) {
                    if ($service['Servicetemplate']['id'] == $servicetemplateId) {
                        return true;
                    }
                }
            }
        }

        return false;
    }


    public $additionalValidationRules = [];
    public $additionalData = [];

    public function beforeValidate($options = []) {
        $params = Router::getParams();
        if (empty($params['action'])) {
            return parent::beforeValidate($options);
        }
        $action = $params['action'];

        if ($action == 'addParentHosts') {
            $this->validate = [
                'id'         => ValidationCollection::getIdRule(),
                'Parenthost' => [
                    'multiple' => [
                        'rule'     => ['multiple', ['min' => 1]],
                        'message'  => 'You need to select at least one parent host.',
                        'required' => true,
                    ],
                ],
            ];
        }

        return parent::beforeValidate($options);
    }

    /**
     * @param int[] $containerIds May be empty if the option `hasRootPrivileges` is true.
     * @param string $type
     * @param array $options
     *
     * @return int[]
     */
    public function servicesByContainerIds($containerIds, $type = 'all', $options = []) {
        $_options = [
            'prefixHostname'    => true,
            'delimiter'         => '/',
            'forOptiongroup'    => false,
            'hasRootPrivileges' => false,
        ];
        $options = Hash::merge($_options, $options);

        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $Service = ClassRegistry::init('Service');
        $hosts = $this->hostsByContainerId($containerIds, 'list');

        switch ($type) {
            case 'all':
                $return = [];
                foreach ($hosts as $hostId => $hostName) {
                    $services = $Service->find('all', [
                        'recursive'  => -1,
                        'conditions' => [
                            'Service.host_id'  => $hostId,
                            'Service.disabled' => 0,
                        ],
                        'joins'      => [
                            [
                                'table'      => 'servicetemplates',
                                'type'       => 'INNER',
                                'alias'      => 'Servicetemplate',
                                'conditions' => 'Servicetemplate.id = Service.servicetemplate_id',
                            ],
                        ],
                        'fields'     => [
                            'Service.*',
                            'Servicetemplate.id',
                            'Servicetemplate.name',
                        ],
                    ]);
                    foreach ($services as $service) {
                        $serviceName = $service['Service']['name'];
                        if ($serviceName === null || $serviceName === '') {
                            $serviceName = $service['Servicetemplate']['name'];
                        }
                        $service['Service']['hostname'] = $hostName;
                        $service['Service']['name'] = $serviceName;
                        $return[] = $service['Service'];
                    }
                }

                return $return;
                break;

            case 'list':
                $return = [];
                foreach ($hosts as $hostId => $hostName) {
                    $services = $Service->find('all', [
                        'recursive'  => -1,
                        'conditions' => [
                            'Service.host_id'  => $hostId,
                            'Service.disabled' => 0,
                        ],
                        'joins'      => [
                            [
                                'table'      => 'servicetemplates',
                                'type'       => 'INNER',
                                'alias'      => 'Servicetemplate',
                                'conditions' => 'Servicetemplate.id = Service.servicetemplate_id',
                            ],
                        ],
                        'fields'     => [
                            'Service.id',
                            'Service.uuid',
                            'Service.servicetemplate_id',
                            'Service.host_id',
                            'Service.name',
                            'Service.disabled',
                            'Servicetemplate.id',
                            'Servicetemplate.name',
                        ],
                    ]);
                    foreach ($services as $service) {
                        $serviceName = $service['Service']['name'];
                        if ($serviceName === null || $serviceName === '') {
                            $serviceName = $service['Servicetemplate']['name'];
                        }

                        $serviceId = $service['Service']['id'];

                        if ($options['forOptiongroup'] === false) {
                            if ($options['prefixHostname']) {
                                $return[$serviceId] = $hostName . $options['delimiter'] . $serviceName;
                            } else {
                                $return[$serviceId] = $serviceName;
                            }
                        } else {
                            if ($options['prefixHostname']) {
                                $return[$hostId][$hostName][$serviceId] = $hostName . $options['delimiter'] . $serviceName;
                            } else {
                                $return[$hostId][$hostName][$serviceId] = $serviceName;
                            }
                        }
                    }
                }

                return $return;
                break;
        }

        return [];
    }

    /**
     * deletes a Host
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  $host array the Host to delete
     * @param  $userId int the Id of the User
     *
     * @return boolean
     */
    public function __delete($host, $userId) {
        if (empty($host)) {
            return false;
        }

        $id = $host['Host']['id'];
        $this->id = $id;
        $Changelog = ClassRegistry::init('Changelog');

        //Load the Service Model to delete Graphgenerator configurations
        $Service = ClassRegistry::init('Service');
        $serviceIds = array_keys($Service->find('list', [
            'recursive'  => -1,
            'contain'    => [],
            'conditions' => [
                'Service.host_id' => $id,
            ],
        ]));

        $GraphgenTmplConf = ClassRegistry::init('GraphgenTmplConf');
        $graphgenTmplConfs = $GraphgenTmplConf->find('all', [
            'conditions' => [
                'GraphgenTmplConf.service_id' => $serviceIds,
            ],
        ]);


        if ($this->delete()) {
            //Delete was successfully - delete Graphgenerator configurations
            foreach ($graphgenTmplConfs as $graphgenTmplConf) {
                $GraphgenTmplConf->delete($graphgenTmplConf['GraphgenTmplConf']['id']);
            }

            $changelog_data = $Changelog->parseDataForChangelog(
                'delete',
                'hosts',
                $id,
                OBJECT_HOST,
                $host['Host']['container_id'],
                $userId,
                $host['Host']['name'],
                $host
            );
            if ($changelog_data) {
                CakeLog::write('log', serialize($changelog_data));
            }


            //Add host to deleted objects table
            $DeletedHost = ClassRegistry::init('DeletedHost');
            $DeletedService = ClassRegistry::init('DeletedService');
            $DeletedHost->create();
            $data = [
                'DeletedHost' => [
                    'host_id'          => $host['Host']['id'],
                    'uuid'             => $host['Host']['uuid'],
                    'hosttemplate_id'  => $host['Host']['hosttemplate_id'],
                    'name'             => $host['Host']['name'],
                    'description'      => $host['Host']['description'],
                    'deleted_perfdata' => 0,
                ],
            ];
            if ($DeletedHost->save($data)) {
                // The host is history now, so we can delete all deleted services of this host, we dont need this data anymore
                $DeletedService->deleteAll([
                    'DeletedService.host_id' => $id,
                ]);
            }


            /*
             * Check if the host was part of an hostgroup, hostescalation or hostdependency
             * If yes, cake delete the records by it self, but may be we have an empty hostescalation or hostgroup now.
             * Nagios don't relay like this so we need to check this and delete the hostescalation/hostgroup or host dependency if empty
             */
            $this->_cleanupHostEscalationDependency($host);

            $Documentation = ClassRegistry::init('Documentation');
            //Delete the Documentation of the Host
            $documentation = $Documentation->findByUuid($host['Host']['uuid']);
            if (isset($documentation['Documentation']['id'])) {
                $Documentation->delete($documentation['Documentation']['id']);
                unset($documentation);
            }

            //Delete Idoit imported Hosts
            if (CakePlugin::loaded('IdoitModule')) {
                $this->IdoitMapping = ClassRegistry::init('IdoitMapping');
                $this->IdoitMapping->deleteAll([
                    'IdoitMapping.oitc_object_id' => $id,
                    'IdoitMapping.type'           => 1, // Must be IdoitMapping::TYPE_HOST
                ]);
            }

            return true;

        }

        return false;
    }

    /**
     * @param $host
     * @param $moduleConstants
     * @return array
     */
    public function isUsedByModules($host, $moduleConstants) {
        $usedBy = [
            'host'    => [],
            'service' => []
        ];
        foreach ($moduleConstants as $moduleName => $value) {
            if ($host['Host']['usage_flag'] & $value) {
                $usedBy['host'][$moduleName] = $value;
            }
            foreach ($host['Service'] as $service) {
                if ($service['usage_flag'] & $value) {
                    $usedBy['service'][$moduleName] = $value;
                }
            }
        }
        return $usedBy;
    }

    public function humanizeModuleConstantName($name) {
        return preg_replace('/_MODULE/', '', $name);
    }

    public function __deleteBySatellite($satelliteId, $userId) { // performance optimization
        $hostsInSatellite = $this->find('all', [
            'recursive'  => -1,
            'contain'    => [],
            'conditions' => [
                'Host.satellite_id' => $satelliteId,
            ],
        ]);

        $hostIds = [];
        //remove from hostIds id that are not allowed to delete
        $Service = ClassRegistry::init('Service');
        foreach ($hostsInSatellite as $hostKey => $hostArr) {
            $serviceIds = Hash::extract($Service->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'host_id' => $hostArr['Host']['id'],
                ],
                'fields'     => [
                    'Service.id',
                ],
            ]), '{n}.Service.id');

            //check if the host is used somwhere
            if (CakePlugin::loaded('EventcorrelationModule')) {
                $this->Eventcorrelation = ClassRegistry::init('Eventcorrelation');
                $evcCount = $this->Eventcorrelation->find('count', [
                    'conditions' => [
                        'OR' => [
                            'Eventcorrelation.host_id'    => $hostArr['Host']['id'],
                            'Eventcorrelation.service_id' => $serviceIds,
                        ],
                    ],
                ]);
                if ($evcCount == 0) {
                    $hostIds[] = $hostArr['Host']['id'];
                } else {
                    unset($hostsInSatellite[$hostKey]);
                }
            }
        }

        $Changelog = ClassRegistry::init('Changelog');
        //Load the Service Model to delete Graphgenerator configurations
        $serviceIds = array_keys($Service->find('list', [
            'recursive'  => -1,
            'contain'    => [],
            'conditions' => [
                'Service.host_id' => $hostIds,
            ],
        ]));

        $datasource = $this->getDataSource();
        $DeletedHost = ClassRegistry::init('DeletedHost');
        $DeletedService = ClassRegistry::init('DeletedService');
        $GraphgenTmplConf = ClassRegistry::init('GraphgenTmplConf');
        $Documentation = ClassRegistry::init('Documentation');
        try {
            $datasource->begin();
            $GraphgenTmplConf->deleteAll(['GraphgenTmplConf.service_id' => $serviceIds, true]);

            foreach ($hostsInSatellite as $hostArr) {
                $changelog_data = $Changelog->parseDataForChangelog(
                    'delete',
                    'hosts',
                    $hostArr['Host']['container_id'],
                    OBJECT_HOST,
                    $hostArr['Host']['container_id'],
                    $userId,
                    $hostArr['Host']['name'],
                    $hostArr
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                $DeletedHost->create();
                $data = [
                    'DeletedHost' => [
                        'host_id'          => $hostArr['Host']['id'],
                        'uuid'             => $hostArr['Host']['uuid'],
                        'hosttemplate_id'  => $hostArr['Host']['hosttemplate_id'],
                        'name'             => $hostArr['Host']['name'],
                        'description'      => $hostArr['Host']['description'],
                        'deleted_perfdata' => 0,
                    ],
                ];
                if (!$DeletedHost->save($data)) {
                    throw new Exception(__('Cannot modify Host deletion data.'));
                }
                $this->_cleanupHostEscalationDependency($hostArr);
                $documentation = $Documentation->findByUuid($hostArr['Host']['uuid']);
                if (isset($documentation['Documentation']['id'])) {
                    $Documentation->delete($documentation['Documentation']['id']);
                }
            }
            $DeletedService->deleteAll(['DeletedService.host_id' => $serviceIds, true]);
            $this->deleteAll(['Host.id' => $hostIds], true);
            $datasource->commit();

            return ['success' => true, 'message' => ''];
        } catch (Exception $exc) {
            $datasource->rollback();

            return ['success' => false, 'message' => $exc->getMessage()];
        }
    }

    /**
     * Check if the host is part of a hostescalation and if it would be empty after the host would be deleted,
     * This prevents nagios from getting problems because of empty hostescalations.
     *
     * @param array $host
     */
    public function _cleanupHostEscalationDependency($host) {
        if (!empty($host['HostescalationHostMembership'])) {
            $Hostescalation = ClassRegistry::init('Hostescalation');
            foreach ($host['HostescalationHostMembership'] as $_hostescalation) {
                $hostescalation = $Hostescalation->findById($_hostescalation['hostescalation_id']);
                if (empty($hostescalation['HostescalationHostMembership']) && empty($hostescalation['HostescalationHostgroupMembership'])) {
                    //This eslacation is empty now, so we can delete it
                    $Hostescalation->delete($hostescalation['Hostescalation']['id']);
                }
            }
        }

        if (!empty($host['HostdependencyHostMembership'])) {
            $Hostdependency = ClassRegistry::init('Hostdependency');
            foreach ($host['HostdependencyHostMembership'] as $_hostdependency) {
                $hostdependency = $Hostdependency->findById($_hostdependency['hostdependency_id']);
                if (empty($hostdependency['HostdependencyHostMembership']) && empty($hostdependency['HostdependencyHostgroupMembership'])) {
                    $Hostdependency->delete($hostdependency['Hostdependency']['id']);
                } else {
                    //Not the whole dependency is empty, but may be its broken
                    $hosts = Hash::extract($hostdependency['HostdependencyHostMembership'], '{n}[dependent=0]');
                    $dependentHosts = Hash::extract($hostdependency['HostdependencyHostMembership'], '{n}[dependent=1]');
                    if (empty($hosts) || empty($dependentHosts)) {
                        //Data is not valid, delete!
                        $Hostdependency->delete($hostdependency['Hostdependency']['id']);
                    }
                }
            }
        }
    }

    /**
     * check if the given Host is in use by the given module
     * @param $hostId
     * @param $moduleValue
     * @return bool
     */
    public function checkUsageFlag($hostId, $moduleValue) {
        $result = $this->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Host.id' => $hostId,
                //'Host.usage_flag & '.$moduleValue
            ],
            'fields'     => [
                'Host.usage_flag'
            ]
        ]);

        if (!empty($result)) {
            $result = $result['Host']['usage_flag'];
            $this->currentUsageFlag = $result;
            if ($result & $moduleValue) {
                return true;
            }
            return false;
        }
    }

    /**
     * @param HostConditions $HostConditions
     * @param array $conditions
     * @return array
     */
    public function getHostIndexQuery(HostConditions $HostConditions, $conditions = []) {
        $query = [
            'recursive'  => -1,
            'contain'    => [
                'Hosttemplate' => [
                    'fields' => [
                        'Hoststatus.is_flapping',
                        'Hosttemplate.id',
                        'Hosttemplate.uuid',
                        'Hosttemplate.name',
                        'Hosttemplate.description',
                        'Hosttemplate.active_checks_enabled',
                        'Hosttemplate.tags',
                    ]
                ],
                'Container'
            ],
            'conditions' => $conditions,
            'fields'     => [
                //'DISTINCT (Host.id) as banane', //Fix pagination
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.description',
                'Host.active_checks_enabled',
                'Host.address',
                'Host.satellite_id',
                'Host.container_id',
                'Host.tags',

                'Hoststatus.current_state',
                'Hoststatus.last_check',
                'Hoststatus.next_check',
                'Hoststatus.last_hard_state_change',
                'Hoststatus.last_state_change',
                'Hoststatus.output',
                'Hoststatus.scheduled_downtime_depth',
                'Hoststatus.active_checks_enabled',
                'Hoststatus.state_type',
                'Hoststatus.problem_has_been_acknowledged',
                'Hoststatus.acknowledgement_type',


                'Hoststatus.current_state',
            ],
            'order'      => $HostConditions->getOrder(),
            'joins'      => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'HostObject',
                    'conditions' => 'Host.uuid = HostObject.name1 AND HostObject.objecttype_id = 1',
                ], [
                    'table'      => 'nagios_hoststatus',
                    'type'       => 'LEFT OUTER',
                    'alias'      => 'Hoststatus',
                    'conditions' => 'Hoststatus.host_object_id = HostObject.object_id',
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
                'Host.id',
            ],
        ];

        $query['conditions']['Host.disabled'] = (int)$HostConditions->includeDisabled();
        $query['conditions']['HostsToContainers.container_id'] = $HostConditions->getContainerIds();

        return $query;
    }

    public function virtualFieldsForIndex() {
        $this->virtualFields['keywords'] = 'IF((Host.tags IS NULL OR Host.tags=""), Hosttemplate.tags, Host.tags)';
    }

    /**
     * @param HostConditions $HostConditions
     * @param array $conditions
     * @return array
     */
    public function getHostNotMonitoredQuery(HostConditions $HostConditions, $conditions = []) {
        $query = [
            'recursive'  => -1,
            'contain'    => [
                'Hosttemplate' => [
                    'fields' => [
                        'Hosttemplate.id',
                        'Hosttemplate.uuid',
                        'Hosttemplate.name',
                        'Hosttemplate.description',
                        'Hosttemplate.active_checks_enabled',
                    ]
                ],
                'Container'
            ],
            'conditions' => $conditions,
            'fields'     => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.description',
                'Host.active_checks_enabled',
                'Host.address',
                'Host.satellite_id',
                'Host.container_id',

            ],
            'order'      => $HostConditions->getOrder(),
            'joins'      => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'LEFT OUTER',
                    'alias'      => 'HostObject',
                    'conditions' => 'Host.uuid = HostObject.name1 AND HostObject.objecttype_id = 1',
                ],
                [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ],
            ],
            'group'      => [
                'Host.id',
            ],
        ];

        $query['conditions']['Host.disabled'] = (int)$HostConditions->includeDisabled();
        $query['conditions']['HostsToContainers.container_id'] = $HostConditions->getContainerIds();
        $query['conditions'][] = 'HostObject.name1 IS NULL';

        return $query;
    }

    /**
     * @param HostConditions $HostConditions
     * @param array $conditions
     * @return array
     */
    public function getHostDisabledQuery(HostConditions $HostConditions, $conditions = []) {
        $query = [
            'recursive'  => -1,
            'contain'    => [
                'Hosttemplate' => [
                    'fields' => [
                        'Hosttemplate.id',
                        'Hosttemplate.name',
                    ]
                ],
                'Container'
            ],
            'conditions' => $conditions,
            'fields'     => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.description',
                'Host.address',
                'Host.satellite_id',
                'Host.container_id',

            ],
            'order'      => $HostConditions->getOrder(),
            'joins'      => [
                [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ]
            ],
            'group'      => [
                'Host.id',
            ],
        ];

        $query['conditions']['Host.disabled'] = (int)$HostConditions->includeDisabled();
        $query['conditions']['HostsToContainers.container_id'] = $HostConditions->getContainerIds();

        return $query;
    }

    /**
     * @param array $MY_RIGHTS
     * @param bool $includeOkState
     * @return array
     */
    public function getHoststatusCount($MY_RIGHTS, $includeOkState = false){
        $hoststatusCount = [
            '1' => 0,
            '2' => 0,
        ];
        if($includeOkState === true){
            $hoststatusCount['0'] = 0;
        }

        $query = [
            'conditions' => [
                'Host.disabled'                  => 0,
                'HostObject.is_active'           => 1,
                'HostsToContainers.container_id' => $MY_RIGHTS
            ],
            'contain'    => [],
            'fields'     => [
                'Hoststatus.current_state',
                'COUNT(DISTINCT Hoststatus.host_object_id) AS count',
            ],
            'group'      => [
                'Hoststatus.current_state',
            ],
            'joins'      => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'HostObject',
                    'conditions' => 'Host.uuid = HostObject.name1 AND HostObject.objecttype_id = 1',
                ],

                [
                    'table'      => 'nagios_hoststatus',
                    'type'       => 'INNER',
                    'alias'      => 'Hoststatus',
                    'conditions' => 'Hoststatus.host_object_id = HostObject.object_id',
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
        ];

        if($includeOkState === false){
            $query['conditions']['Hoststatus.current_state >'] = 0;
        }

        $hoststatusCountResult = $this->find('all', $query);
        foreach ($hoststatusCountResult as $hoststatus) {
            $hoststatusCount[$hoststatus['Hoststatus']['current_state']] = (int)$hoststatus[0]['count'];
        }
        return $hoststatusCount;
    }

    /**
     * @param array $MY_RIGHTS
     * @param bool $includeOkState
     * @return array
     */
    public function getServicestatusCount($MY_RIGHTS, $includeOkState = false){
        $servicestatusCount = [
            '1' => 0,
            '2' => 0,
            '3' => 0,
        ];
        if($includeOkState === true){
            $servicestatusCount['0'] = 0;
        }

        $query = [
            'conditions' => [
                'Service.disabled'               => 0,
                'ServiceObject.is_active'        => 1,
                'HostsToContainers.container_id' => $MY_RIGHTS,

            ],
            'contain'    => [],
            'fields'     => [
                'Servicestatus.current_state',
                'COUNT(DISTINCT Servicestatus.service_object_id) AS count',
            ],
            'group'      => [
                'Servicestatus.current_state',
            ],
            'joins'      => [
                [
                    'table'      => 'hosts_to_containers',
                    'type'       => 'INNER',
                    'alias'      => 'HostsToContainers',
                    'conditions' => 'HostsToContainers.host_id = Host.id',
                ],
                [
                    'table'      => 'services',
                    'type'       => 'INNER',
                    'alias'      => 'Service',
                    'conditions' => 'Service.host_id = Host.id',
                ],
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'ServiceObject',
                    'conditions' => 'ServiceObject.name2 = Service.uuid',
                ],
                [
                    'table'      => 'nagios_servicestatus',
                    'type'       => 'INNER',
                    'alias'      => 'Servicestatus',
                    'conditions' => 'Servicestatus.service_object_id = ServiceObject.object_id',
                ],
            ],
        ];

        if($includeOkState === false){
            $query['conditions']['Servicestatus.current_state >'] = 0;
        }

        $servicestatusCountResult = $this->find('all', $query);
        foreach ($servicestatusCountResult as $servicestatus) {
            $servicestatusCount[$servicestatus['Servicestatus']['current_state']] = (int)$servicestatus[0]['count'];
        }
        return $servicestatusCount;
    }

    /**
     * @param int $hostId
     * @return array
     */
    public function getQueryForBrowser($hostId) {
        return [
            'recursive'  => -1,
            'contain'    => [
                'Parenthost'               => [
                    'fields' => [
                        'id',
                        'uuid',
                        'name'
                    ]
                ],
                'Hosttemplate',
                'CheckPeriod',
                'NotifyPeriod',
                'CheckCommand',
                'Contact'                  => [
                    'fields' => [
                        'id',
                        'name',
                    ],
                    'Container'
                ],
                'Contactgroup'             => [
                    'fields'    => ['id'],
                    'Container' => [
                        'fields' => [
                            'name',
                            'parent_id'
                        ],
                    ],
                ],
                'Customvariable'           => [
                    'fields' => [
                        'id', 'name', 'value', 'objecttype_id',
                    ],
                ],
                'Hostcommandargumentvalue' => [
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
                'Host.id' => $hostId
            ]
        ];
    }

    /**
     * @param int $hostId
     * @return array
     */
    public function getQueryForServiceBrowser($hostId) {
        return [
            'recursive'  => -1,
            'fields'     => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.address',
                'Host.container_id',
                'Host.satellite_id'
            ],
            'contain'    => [
                'Container',
                'Contact'      => [
                    'fields' => [
                        'id',
                        'name'
                    ],
                ],
                'Contactgroup' => [
                    'Container' => [
                        'fields' => [
                            'Container.name',
                            'Container.parent_id'
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
                        'Container'
                    ],
                    'Contactgroup' => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                                'Container.parent_id'
                            ],
                        ],
                        'fields'    => [
                            'Contactgroup.id',
                        ],
                    ],
                ],
            ],
            'conditions' => [
                'Host.id' => $hostId
            ]
        ];
    }

    /**
     * @param bool $created
     * @param array $options
     * @return bool|void
     */
    public function afterSave($created, $options = []) {
        if ($this->DbBackend->isCrateDb() && isset($this->data['Host']['id'])) {
            //Save data also to CrateDB
            $CrateHost = new \itnovum\openITCOCKPIT\Crate\CrateHost($this->data['Host']['id']);
            $host = $this->find('first', $CrateHost->getFindQuery());
            $CrateHost->setDataFromFindResult($host);

            $CrateHostModel = ClassRegistry::init('CrateModule.CrateHost');
            $CrateHostModel->save($CrateHost->getDataForSave());
        }

        parent::afterSave($created, $options);
    }

    public function beforeDelete($cascade = true){
        $this->LastDeletedId = new LastDeletedId($this->id);
        return parent::beforeDelete($cascade);
    }

    public function afterDelete(){
        if($this->LastDeletedId !== null) {
            if ($this->DbBackend->isCrateDb() && $this->LastDeletedId->hasId()) {
                $CrateHostModel = ClassRegistry::init('CrateModule.CrateHost');
                $CrateHostModel->delete($this->LastDeletedId->getId());
                $this->LastDeletedId = null;
            }
        }

        parent::afterDelete();
    }
}
