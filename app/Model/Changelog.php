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

class Changelog extends AppModel
{
    public $belongsTo = ['User'];
    public $hasAndBelongsToMany = [
        'Container' => [
            'className'             => 'Container',
            'joinTable'             => 'changelogs_to_containers',
            'foreignKey'            => 'changelog_id',
            'associationForeignKey' => 'container_id',
        ],
    ];

    public function getCompareRules()
    {
        $_objectDefaults = [
            'command'         => [
                'Command'         => '{(command_type|name|description|command_line)}',
                'Commandargument' => '{n}.{(id|name|human_name)}',
            ],
            /*'devicegroup' => [
                'Devicegroup' => '{(description)}',
                'Container' => '{(name)}',
            ],*/
            'timeperiod'      => [
                'Timeperiod' => '{(name|description)}',
                'Timerange'  => '{n}.{(id|day|start|end)}',
            ],
            'contact'         => [
                'Contact'           => '{(name|description|email|phone|notify_).*}',
                'HostTimeperiod'    => '{(id|name)}',
                'ServiceTimeperiod' => '{(id|name)}',
                'HostCommands'      => '{n}.{(id|name)}',
                'ServiceCommands'   => '{n}.{(id|name)}',
            ],
            'contactgroup'    => [
                'Contactgroup' => '{(description)}',
                'Container'    => '{(name)}',
                'Contact'      => '{n}.{(id|name)}',
            ],
            'hostgroup'       => [
                'Hostgroup' => '{(description|hostgroup_url)}',
                'Container' => '{(name)}',
                'Host'      => '{n}.{(id|name)}',
            ],
            'hosttemplate'    => [
                'Hosttemplate'                     => '{(name|description|check_interval|retry_interval|max_check_attempts|notification_interval|notify_on_|flap_detection_notifications_enabled|notes|priority|tags|host_url|active_checks_enabled).*}',
                'CheckPeriod'                      => '{(id|name)}',
                'NotifyPeriod'                     => '{(id|name)}',
                'CheckCommand'                     => '{(id|name)}',
                'Customvariable'                   => '{n}.{(id|name|value)}',
                'Hosttemplatecommandargumentvalue' => '{n}.{(id|value)}',
                //			'Hosttemplatecommandargumentvalue' => ['prepareFields' => ['{n}.{(id|name|value)}', 'Commandargument.{(human_name)}'], 'fields' => '{n}.{(id|name|human_name)}'],
                'Contact'                          => '{n}.{(id|name)}',
                'Contactgroup'                     => ['prepareFields' => ['{n}.{(id)}', '{n}.Container.{(name)}'], 'fields' => '{n}.{(id|name)}'],
            ],
            'servicetemplate' => [
                'Servicetemplate'                          => '{(name|description|check_interval|retry_interval|max_check_attempts|notification_interval|notify_on_|flap_detection_enabled|notes|priority|tags|service_url|active_checks_enabled|process_performance_data|is_volatile|freshness_checks_enabled|freshness_threshold|flap_detection_on_).*}',
                'CheckPeriod'                              => '{(id|name)}',
                'NotifyPeriod'                             => '{(id|name)}',
                'CheckCommand'                             => '{(id|name)}',
                'EventhandlerCommand'                      => '{(id|name)}',
                'Servicetemplateeventcommandargumentvalue' => '{n}.{(id|value)}',
                'Customvariable'                           => '{n}.{(id|name|value)}',
                'Servicetemplatecommandargumentvalue'      => '{n}.{(id|value)}',
                'Contact'                                  => '{n}.{(id|name)}',
                'Contactgroup'                             => ['prepareFields' => ['{n}.{(id)}', '{n}.Container.{(name)}'], 'fields' => '{n}.{(id|name)}'],
            ],
            'servicegroup'    => [
                'Servicegroup' => '{(description|servicegroup_url)}',
                'Container'    => '{(name)}',
                'Service'      => '{n}.{(id|name)}',
            ],
            'host'            => [
                'Host'                     => '{(name|description|check_interval|retry_interval|max_check_attempts|notification_interval|notify_on_|flap_detection_notifications_enabled|notes|priority|tags|host_url|active_checks_enabled).*}',
                'Hosttemplate'             => '{(id|name)}',
                'CheckPeriod'              => '{(id|name)}',
                'NotifyPeriod'             => '{(id|name)}',
                'CheckCommand'             => '{(id|name)}',
                'Hostgroup'                => '{n}.{(id|name)}',
                'Parenthost'               => '{n}.{(id|name)}',
                'Customvariable'           => '{n}.{(id|name|value)}',
                'Hostcommandargumentvalue' => '{n}.{(id|value)}',
                'Contact'                  => '{n}.{(id|name)}',
                'Contactgroup'             => '{n}.{(id|name)}',
            ],
            'service'         => [
                'Service'                     => '{(name|description|check_interval|retry_interval|max_check_attempts|notification_interval|notify_on_|flap_detection_notifications_enabled|notes|priority|tags|service_url|active_checks_enabled|process_performance_data|is_volatile|freshness_checks_enabled|freshness_threshold|flap_detection_on_).*}',
                'Host'                        => '{(id|name)}',
                'Servicetemplate'             => '{(id|name)}',
                'CheckPeriod'                 => '{(id|name)}',
                'NotifyPeriod'                => '{(id|name)}',
                'CheckCommand'                => '{(id|name)}',
                'Servicegroup'                => '{n}.{(id|name)}',
                'Customvariable'              => '{n}.{(id|name|value)}',
                'Servicecommandargumentvalue' => '{n}.{(id|value)}',
                'Contact'                     => '{n}.{(id|name)}',
                'Contactgroup'                => '{n}.{(id|name)}',
            ],
            'map'             => [
                'Map' => '{(name|description)}',
            ],
        ];

        return $_objectDefaults;
    }

    public function parseDataForChangelog($action, $controller, $object_id, $objecttype_id, $container_id, $user_id, $name, $requestData, $currentSavedData = [])
    {
        $data_array_keys = ['action', 'controller', 'object_id', 'objecttype_id', 'container_id', 'user_id', 'name', 'data'];
        $changes = [];
        $compareRules = $this->getCompareRules();
        switch ($action) {
            case 'add':
                foreach ($compareRules[strtolower(Inflector::singularize($controller))] as $key => $fields) {
                    if (is_array($fields)) {
                        $fields = $fields['fields'];
                    }
                    if (!is_null(Set::classicExtract($requestData, $key.'.'.$fields))) {
                        $changes[] = [
                            $key => [
                                'current_data' => Set::classicExtract($requestData, $key.'.'.$fields),
                            ],
                        ];
                    }
                }

                return array_combine($data_array_keys, [
                    $action,
                    $controller,
                    $object_id,
                    $objecttype_id,
                    $container_id,
                    $user_id,
                    $name,
                    Hash::filter($changes),
                ]);
                break;
            case 'edit':
                $tmp = [];
                foreach ($compareRules[strtolower(Inflector::singularize($controller))] as $key => $fields) {
                    if (is_array($fields)) {
                        foreach ($fields['prepareFields'] as $field) {
                            $tmp = Hash::merge($tmp, Set::classicExtract($currentSavedData, $key.'.'.$field));
                        }
                        $currentSavedData[$key] = $tmp;
                        $fields = $fields['fields'];
                    }
                    $path = $key.'.'.$fields;
                    $diff1 = Set::classicExtract($requestData, $path);
                    $diff2 = Set::classicExtract($currentSavedData, $path);
                    $change_arr = $this->getDiffAsArray($diff1, $diff2, $key);
                    if (!empty($change_arr)) {
                        array_push($changes, $change_arr);
                    }
                }
                if (!empty($changes)) {
                    return array_combine($data_array_keys, [
                        $action,
                        $controller,
                        $object_id,
                        $objecttype_id,
                        $container_id,
                        $user_id,
                        $name,
                        $changes,
                    ]);
                }
                break;
            case 'delete':
            case 'mass_delete':
                return array_combine($data_array_keys, [
                    'delete',
                    $controller,
                    $object_id,
                    $objecttype_id,
                    $container_id,
                    $user_id,
                    $name,
                    [],
                ]);
                break;
        }

        return false;
    }

    function getDiffAsArray($new_values, $old_values, $field_key)
    {
        $new_values = ($new_values === null) ? [] : $new_values;
        $old_values = ($old_values === null || empty(Hash::filter($old_values, [$this, 'filterNullValues']))) ? [] : $old_values;

        // compare the value of 2 array
        // get differences that in new_values but not in old_values
        // get difference that in old_values but not in new_values
        // return the unique difference between value of 2 array
        $diff = [
            $field_key => [
                'before'       => [],
                'after'        => [],
                'current_data' => $old_values,
            ],
        ];
        switch (Hash::dimensions($new_values)) {
            case 0:
            case 1:
                $diff_keys = Hash::diff($new_values, $old_values);
                if (is_array($diff_keys) && !empty($diff_keys)) {
                    $diff[$field_key]['before'] = Set::classicExtract($old_values, '{('.implode('$|', array_keys(Hash::diff($new_values, $old_values))).'$)}');
                    $diff[$field_key]['after'] = Set::classicExtract($new_values, '{('.implode('$|', array_keys(Hash::diff($new_values, $old_values))).'$)}');
                }
                break;
            case 2:
                // get differences that in new_values but not in old_values
                foreach ($new_values as $new_value_key => $new_value) {
                    $flag = 0;
                    foreach ($old_values as $old_value) {
                        $flag |= ($new_value == $old_value);
                        if ($flag) break;
                    }
                    if (!$flag) $diff[$field_key]['after'][$new_value_key] = $new_value;
                }

                // get difference that in $old_values but not in $new_values
                foreach ($old_values as $old_value_key => $old_value) {
                    $flag = 0;
                    foreach ($new_values as $new_value) {
                        $flag |= ($new_value == $old_value);
                        if ($flag) break;
                    }
                    if (!$flag && !in_array($old_value, $diff)) $diff[$field_key]['before'][$old_value_key] = $old_value;
                }
                break;
        }
        if (empty($diff[$field_key]['before']) && empty($diff[$field_key]['after'])) {
            unset($diff[$field_key]);

            return [];
        }
        $diff[$field_key]['before'] = (is_null($diff[$field_key]['before'])) ? [] : $diff[$field_key]['before'];
        $diff[$field_key]['after'] = (is_null($diff[$field_key]['after'])) ? [] : $diff[$field_key]['after'];
        //Remove all "null" entries from array
        $diff[$field_key]['before'] = Hash::filter($diff[$field_key]['before'], [$this, 'filterNullValues']);
        $diff[$field_key]['current_data'] = Hash::filter($diff[$field_key]['current_data'], [$this, 'filterNullValues']);
        $diff[$field_key]['after'] = Hash::filter($diff[$field_key]['after'], [$this, 'filterNullValues']);

        return $diff;
    }

    /**
     * Callback function for filtering.
     *
     * @param array $var Array to filter.
     *
     * @return boolean
     */
    public static function filterNullValues($var)
    {
        if ($var != null || $var === '0' || $var === '') {
            return true;
        }

        return false;
    }
}
