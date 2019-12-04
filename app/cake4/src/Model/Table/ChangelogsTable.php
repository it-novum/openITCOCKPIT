<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\CakePHP\Set;

/**
 * Changelogs Model
 *
 * @property \App\Model\Table\ObjectsTable|\Cake\ORM\Association\BelongsTo $Objects
 * @property \App\Model\Table\ObjecttypesTable|\Cake\ORM\Association\BelongsTo $Objecttypes
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ChangelogsToContainersTable|\Cake\ORM\Association\HasMany $ChangelogsToContainers
 *
 * @method \App\Model\Entity\Changelog get($primaryKey, $options = [])
 * @method \App\Model\Entity\Changelog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Changelog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Changelog|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Changelog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Changelog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Changelog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Changelog findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ChangelogsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('changelogs');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsToMany('Containers', [
            'joinTable' => 'changelogs_to_containers'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('model')
            ->maxLength('model', 255)
            ->requirePresence('model', 'create')
            ->allowEmptyString('model', null, false);

        $validator
            ->scalar('action')
            ->maxLength('action', 255)
            ->requirePresence('action', 'create')
            ->allowEmptyString('action', null, false);

        $validator
            ->scalar('data')
            ->requirePresence('data', 'create')
            ->allowEmptyString('data', null, false);

        $validator
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', null, false);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }


    /**
     * @param $changelog_data
     * @return bool
     * @deprecated
     */
    public function write($changelog_data) {
        return \CakeLog::write('log', serialize($changelog_data));
    }

    /**
     * @return array
     */
    public function getCompareRules() {
        $_objectDefaults = [
            'command'              => [
                'Command'                  => '{(command_type|name|description|command_line)}',
                'Command.commandarguments' => '{n}.{(id|name|human_name)}',
            ],
            'timeperiod'           => [
                'Timeperiod'                       => '{(name|description)}',
                'Timeperiod.timeperiod_timeranges' => '{n}.{(id|day|start|end)}',
            ],
            'contact'              => [
                'Contact'                 => '{(name|description|email|phone|notify_).*}',
                'HostTimeperiod'          => '{(id|name)}',
                'ServiceTimeperiod'       => '{(id|name)}',
                'HostCommands'            => '{n}.{(id|name)}',
                'ServiceCommands'         => '{n}.{(id|name)}',
                'Contact.customvariables' => '{n}.{(id|name|value)}',
            ],
            'contactgroup'         => [
                'Contactgroup'           => '{(description)}',
                'Contactgroup.container' => '{(name)}',
                'Contact'                => '{n}.{(id|name)}',
            ],
            'hostgroup'            => [
                'Hostgroup'           => '{(description|hostgroup_url)}',
                'Hostgroup.container' => '{(name)}',
                'Host'                => '{n}.{(id|name)}',
                'Hosttemplate'        => '{n}.{(id|name)}',
            ],
            'hosttemplate'         => [
                'Hosttemplate'                                   => '{(name|description|check_interval|retry_interval|max_check_attempts|notification_interval|notify_on_|flap_detection_notifications_enabled|notes|priority|tags|host_url|active_checks_enabled).*}',
                'CheckPeriod'                                    => '{(id|name)}',
                'NotifyPeriod'                                   => '{(id|name)}',
                'CheckCommand'                                   => '{(id|name)}',
                'Hosttemplate.customvariables'                   => '{n}.{(id|name|value)}',
                'Hosttemplate.hosttemplatecommandargumentvalues' => '{n}.{(id|value)}',
                'Contact'                                        => '{n}.{(id|name)}',
                'Contactgroup'                                   => ['prepareFields' => ['{n}.{(id)}', '{n}.Container.{(name)}'], 'fields' => '{n}.{(id|name)}'],
                'Hostgroup'                                      => ['prepareFields' => ['{n}.{(id)}', '{n}.Container.{(name)}'], 'fields' => '{n}.{(id|name)}'],
            ],
            'servicetemplate'      => [
                'Servicetemplate'                                           => '{(template_name|name|description|check_interval|retry_interval|max_check_attempts|notification_interval|notify_on_|flap_detection_enabled|notes|priority|tags|service_url|active_checks_enabled|process_performance_data|is_volatile|freshness_checks_enabled|freshness_threshold|flap_detection_on_).*}',
                'CheckPeriod'                                               => '{(id|name)}',
                'NotifyPeriod'                                              => '{(id|name)}',
                'CheckCommand'                                              => '{(id|name)}',
                'EventhandlerCommand'                                       => '{(id|name)}',
                'Servicetemplate.customvariables'                           => '{n}.{(id|name|value)}',
                'Servicetemplate.servicetemplatecommandargumentvalues'      => '{n}.{(id|value)}',
                'Servicetemplate.servicetemplateeventcommandargumentvalues' => '{n}.{(id|value)}',
                'Contact'                                                   => '{n}.{(id|name)}',
                'Contactgroup'                                              => ['prepareFields' => ['{n}.{(id)}', '{n}.Container.{(name)}'], 'fields' => '{n}.{(id|name)}'],
                'Servicegroup'                                              => ['prepareFields' => ['{n}.{(id)}', '{n}.Container.{(name)}'], 'fields' => '{n}.{(id|name)}'],
            ],
            'servicegroup'         => [
                'Servicegroup'    => '{(description|servicegroup_url)}',
                'Container'       => '{(name)}',
                'Service'         => '{n}.{(id|name)}',
                'Servicetemplate' => '{n}.{(id|name)}',
            ],
            'servicetemplategroup' => [
                'Servicetemplategroup' => '{(description)}',
                'Container'            => '{(name)}',
                'Servicetemplate'      => '{n}.{(id|template_name)}',
            ],
            'host'                 => [
                'Host'                           => '{(name|address|description|check_interval|retry_interval|max_check_attempts|notification_interval|notify_on_|flap_detection_notifications_enabled|notes|priority|tags|host_url|active_checks_enabled).*}',
                'Hosttemplate'                   => '{(id|name)}',
                'CheckPeriod'                    => '{(id|name)}',
                'NotifyPeriod'                   => '{(id|name)}',
                'CheckCommand'                   => '{(id|name)}',
                'Hostgroup'                      => '{n}.{(id|name)}',
                'Parenthost'                     => '{n}.{(id|name)}',
                'Host.customvariables'           => '{n}.{(id|name|value)}',
                'Host.hostcommandargumentvalues' => '{n}.{(id|value)}',
                'Contact'                        => '{n}.{(id|name)}',
                'Contactgroup'                   => '{n}.{(id|name)}',
            ],
            'service'              => [
                'Service'                                   => '{(name|description|check_interval|retry_interval|max_check_attempts|notification_interval|notify_on_|flap_detection_notifications_enabled|notes|priority|tags|service_url|active_checks_enabled|process_performance_data|is_volatile|freshness_checks_enabled|freshness_threshold|flap_detection_on_).*}',
                'Host'                                      => '{(id|name)}',
                'Servicetemplate'                           => '{(id|name)}',
                'CheckPeriod'                               => '{(id|name)}',
                'NotifyPeriod'                              => '{(id|name)}',
                'CheckCommand'                              => '{(id|name)}',
                'Servicegroup'                              => '{n}.{(id|name)}',
                'Service.customvariables'                   => '{n}.{(id|name|value)}',
                'Service.servicecommandargumentvalues'      => '{n}.{(id|value)}',
                'Service.serviceeventcommandargumentvalues' => '{n}.{(id|value)}',
                'Contact'                                   => '{n}.{(id|name)}',
                'Contactgroup'                              => '{n}.{(id|name)}',
            ],
            'map'                  => [
                'Map' => '{(name|description)}',
            ],
            'tenant'               => [
                'tenant'           => '{(description|firstname|lastname|street|zipcode|city)}',
                'tenant.container' => '{(name)}'
            ],
            'location'             => [
                'location'           => '{(description|latitude|longitude|timezone)}',
                'location.container' => '{(name)}'
            ],
        ];

        return $_objectDefaults;
    }

    /**
     *  use $user_id = 0 to specify cron task
     *
     * @param $action
     * @param $controller
     * @param $object_id
     * @param $objecttype_id
     * @param array $container_ids
     * @param $user_id
     * @param $name
     * @param $requestData
     * @param array $currentSavedData
     * @return array|bool|false
     */
    public function parseDataForChangelog($action, $controller, $object_id, $objecttype_id, $container_ids, $user_id, $name, $requestData, $currentSavedData = []) {
        $data_array_keys = ['action', 'controller', 'object_id', 'objecttype_id', 'container_id', 'user_id', 'name', 'data'];
        $changes = [];
        $compareRules = $this->getCompareRules();
        switch ($action) {
            case 'add':
            case 'copy':
                foreach ($compareRules[strtolower(Inflector::singularize($controller))] as $key => $fields) {
                    if (is_array($fields)) {
                        $fields = $fields['fields'];
                    }
                    if (!is_null($currentData = Set::classicExtract($requestData, $key . '.' . $fields))) {
                        $changes[] = [
                            $key => [
                                'current_data' => $currentData,
                            ],
                        ];
                    }
                }

                return [
                    'action'        => $action,
                    'model'         => ucwords(Inflector::singularize($controller)),
                    'object_id'     => $object_id,
                    'objecttype_id' => $objecttype_id,
                    'containers'    => [
                        '_ids' => $container_ids
                    ],
                    'user_id'       => $user_id,
                    'name'          => $name,
                    'data'          => serialize(Hash::filter($changes))
                ];
                break;
            case 'edit':
                foreach ($compareRules[strtolower(\Inflector::singularize($controller))] as $key => $fields) {
                    $tmp = [];
                    if (is_array($fields)) {
                        foreach ($fields['prepareFields'] as $field) {
                            $tmp = Hash::merge($tmp, Set::classicExtract($currentSavedData, $key . '.' . $field));
                        }
                        $currentSavedData[$key] = $tmp;
                        $fields = $fields['fields'];
                    }
                    $path = $key . '.' . $fields;
                    $diff1 = Set::classicExtract($requestData, $path);
                    $diff2 = Set::classicExtract($currentSavedData, $path);
                    $change_arr = $this->getDiffAsArray($diff1, $diff2, $key);
                    if (!empty($change_arr)) {
                        array_push($changes, $change_arr);
                    }
                }
                if (!empty($changes)) {

                    return [
                        'action'        => $action,
                        'model'         => ucwords(Inflector::singularize($controller)),
                        'object_id'     => $object_id,
                        'objecttype_id' => $objecttype_id,
                        'containers'    => [
                            '_ids' => $container_ids
                        ],
                        'user_id'       => $user_id,
                        'name'          => $name,
                        'data'          => serialize(Hash::filter($changes))
                    ];
                }
                break;
            case 'delete':
            case 'mass_delete':
                return [
                    'action'        => $action,
                    'model'         => ucwords(\Inflector::singularize($controller)),
                    'object_id'     => $object_id,
                    'objecttype_id' => $objecttype_id,
                    'containers'    => [
                        '_ids' => $container_ids
                    ],
                    'user_id'       => $user_id,
                    'name'          => $name,
                    'data'          => serialize([])
                ];
                break;
        }

        return false;
    }

    /**
     * @param $new_values
     * @param $old_values
     * @param $field_key
     * @return array
     */
    function getDiffAsArray($new_values, $old_values, $field_key) {
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
                    $diff[$field_key]['before'] = Set::classicExtract($old_values, '{(' . implode('$|', array_keys(Hash::diff($new_values, $old_values))) . '$)}');
                    $diff[$field_key]['after'] = Set::classicExtract($new_values, '{(' . implode('$|', array_keys(Hash::diff($new_values, $old_values))) . '$)}');
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
                    if (!$flag && !in_array($old_value, $diff, true)) $diff[$field_key]['before'][$old_value_key] = $old_value;
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
    public static function filterNullValues($var) {
        if ($var != null || $var === '0' || $var === '' || $var === 0) {
            return true;
        }

        return false;
    }
}
