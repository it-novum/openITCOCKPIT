<?php

namespace App\Model\Table;

use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\Database\Exception;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\CakePHP\Set;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ChangelogsFilter;

/**
 * Changelogs Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\HasMany $containers
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

    use PaginationAndScrollIndexTrait;

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

        // ITC-485 Export Finished has no user
        //$rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }

    /**
     * @param ChangelogsFilter $ChangelogsFilter
     * @param PaginateOMat|null $PaginateOMat
     * @param array $MY_RIGHTS
     * @param bool $includeUser
     * @param bool $enableHydration
     * @return array
     */
    public function getChangelogIndex(ChangelogsFilter $ChangelogsFilter, $PaginateOMat = null, $MY_RIGHTS = [], $includeUser = false, $enableHydration = true) {
        $contain = ['Containers'];
        $select = [
            'id',
            'model',
            'action',
            'object_id',
            'objecttype_id',
            'data',
            'name',
            'created'
        ];
        if ($includeUser === true) {
            $select[] = 'user_id';
            $select[] = 'Users.id';
            $select[] = 'Users.firstname';
            $select[] = 'Users.lastname';
            $select[] = 'Users.email';
            $contain[] = 'Users';
        }

        $query = $this->find()
            ->select($select)
            ->innerJoinWith('Containers', function (Query $q) use ($MY_RIGHTS) {
                if (!empty($MY_RIGHTS)) {
                    return $q->where(['Containers.id IN' => $MY_RIGHTS]);
                }
                return $q;
            })
            ->contain($contain)
            ->enableHydration($enableHydration);

        $where = $ChangelogsFilter->indexFilter();
        if (!empty($MY_RIGHTS)) {
            $where['Containers.id IN'] = $MY_RIGHTS;
            $select[] = 'Containers.id';
        }

        $where['Changelogs.created >='] = date('Y-m-d H:i:s', $ChangelogsFilter->getFrom());
        $where['Changelogs.created <='] = date('Y-m-d H:i:s', $ChangelogsFilter->getTo());
        $query->group(['Changelogs.id']);
        $query->where($where);
        $query->order(
            array_merge(
                $ChangelogsFilter->getOrderForPaginator('Changelogs.id', 'desc'),
                ['Changelogs.id' => 'desc']
            )

        );

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->emptyArrayIfNull($query->toArray());
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scrollCake4($query, $PaginateOMat->getHandler());
            } else {
                $result = $this->paginateCake4($query, $PaginateOMat->getHandler());
            }
        }

        return $result;
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
                'Hosttemplate'                                   => '{(name|description|check_interval|retry_interval|max_check_attempts|notification_interval|notify_on_|flap_detection_notifications_enabled|notes|priority|tags|host_url|active_checks_enabled|freshness_checks_enabled|freshness_threshold|notifications_enabled$).*}',
                'CheckPeriod'                                    => '{(id|name)}',
                'NotifyPeriod'                                   => '{(id|name)}',
                'CheckCommand'                                   => '{(id|name)}',
                'Hosttemplate.customvariables'                   => '{n}.{(id|name|value)}',
                'Hosttemplate.hosttemplatecommandargumentvalues' => '{n}.{(id|value)}',
                'Contact'                                        => '{n}.{(id|name)}',
                'Contactgroup'                                   => '{n}.{(id|name)}',
                'Hostgroup'                                      => '{n}.{(id|name)}'
            ],
            'servicetemplate'      => [
                'Servicetemplate'                                           => '{(template_name|name|description|check_interval|retry_interval|max_check_attempts|notification_interval|notify_on_|flap_detection_enabled|flap_detection_notifications_enabled|notes|priority|tags|service_url|active_checks_enabled|process_performance_data|is_volatile|freshness_checks_enabled|freshness_threshold|flap_detection_on_|notifications_enabled$).*}',
                'CheckPeriod'                                               => '{(id|name)}',
                'NotifyPeriod'                                              => '{(id|name)}',
                'CheckCommand'                                              => '{(id|name)}',
                'EventhandlerCommand'                                       => '{(id|name)}',
                'Servicetemplate.customvariables'                           => '{n}.{(id|name|value)}',
                'Servicetemplate.servicetemplatecommandargumentvalues'      => '{n}.{(id|value)}',
                'Servicetemplate.servicetemplateeventcommandargumentvalues' => '{n}.{(id|value)}',
                'Contact'                                                   => '{n}.{(id|name)}',
                'Contactgroup'                                              => '{n}.{(id|name)}',
                'Servicegroup'                                              => '{n}.{(id|name)}',
            ],
            'servicegroup'         => [
                'Servicegroup'           => '{(description|servicegroup_url)}',
                'Servicegroup.container' => '{(name)}',
                'Service'                => '{n}.{(id|name)}',
                'Servicetemplate'        => '{n}.{(id|name)}',
            ],
            'servicetemplategroup' => [
                'Servicetemplategroup'           => '{(description)}',
                'Servicetemplategroup.container' => '{(name)}',
                'Servicetemplate'                => '{n}.{(id|template_name)}',
            ],
            'host'                 => [
                'Host'                           => '{(name|address|description|check_interval|retry_interval|max_check_attempts|notification_interval|notify_on_|flap_detection_notifications_enabled|notes|priority|tags|host_url|active_checks_enabled|freshness_checks_enabled|freshness_threshold|notifications_enabled$).*}',
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
                'Service'                                   => '{(name|description|check_interval|retry_interval|max_check_attempts|notification_interval|notify_on_|flap_detection_notifications_enabled|notes|priority|tags|service_url|active_checks_enabled|process_performance_data|is_volatile|freshness_checks_enabled|freshness_threshold|flap_detection_on_|notifications_enabled$).*}',
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
            'container'            => [
                'container' => '{(name)}'
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
        if (!is_array($container_ids)) {
            $container_ids = [$container_ids];
        }
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
                foreach ($compareRules[strtolower(Inflector::singularize($controller))] as $key => $fields) {
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
            case 'deactivate':
            case 'activate':
            case 'export':
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
    public function getDiffAsArray($new_values, $old_values, $field_key) {
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

    /**
     * @param string $modelName
     * @param int $objectId
     * @return bool
     */
    public function recordExists(string $modelName, $objectId) {
        $tableName = Inflector::pluralize($modelName);

        /** @var Table $Table */
        $Table = TableRegistry::getTableLocator()->get($tableName);

        try {
            return $Table->exists(['id' => $objectId]);
        } catch (Exception $e) {
            Log::error(sprintf('Changelog: Table %s not found!', $tableName));
            Log::error($e->getMessage());
        }
        return false;
    }

    /**
     * @param string $action
     * @return string
     */
    public function getIconByAction(string $action) {
        switch ($action) {
            case 'add':
                return 'fa fa-plus';
            case 'delete':
                return 'fa fa-trash ';
            case 'activate':
                return 'fa fa-asterisk';
            case 'deactivate':
                return 'fa fa-plug';
            case 'copy':
                return 'fa fa-files-o';
            case 'export':
                return 'fa fa-retweet';
            default:
                return 'fas fa-edit';
        }
    }

    /**
     * @param string $action
     * @return string
     */
    public function getColorByAction(string $action) {
        switch ($action) {
            case 'add':
                return 'bg-up';

            case 'delete':
                return 'bg-down';

            case 'activate':
                return 'bg-up-soft';

            case 'deactivate':
                return 'bg-critical-soft';

            case 'edit':
                return 'bg-warning';

            case 'export':
                return 'bg-info';

            default:
                return 'bg-primary';
        }
    }

    public function replaceFieldValues($dataUnserialized) {
        $newDataUnserialized = [];

        /** @var CommandsTable $CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        $commandTypes = $CommandsTable->getCommandTypes();

        $days = [
            1 => __('Monday'),
            2 => __('Tuesday'),
            3 => __('Wednesday'),
            4 => __('Thursday'),
            5 => __('Friday'),
            6 => __('Saturday'),
            7 => __('Sunday')
        ];

        foreach ($dataUnserialized as $index => $record) {
            foreach ($record as $tableName => $changes) {

                switch ($tableName) {
                    case 'Command':
                        foreach ($changes as $changeState => $stateData) {
                            //Replace command_type id with type name
                            if (isset($stateData['command_type'])) {
                                $dataUnserialized[$index][$tableName][$changeState]['command_type'] = $commandTypes[$stateData['command_type']];
                            }
                        }
                        break;

                    case 'Timeperiod.timeperiod_timeranges':
                        foreach ($changes as $changeState => $stateData) {
                            foreach ($stateData as $dayIndex => $day) {
                                //Replace day id with day name
                                if (isset($day['day'])) {
                                    $dataUnserialized[$index][$tableName][$changeState][$dayIndex]['day'] = $days[$day['day']];
                                }
                            }

                        }
                        break;
                }
            }
        }

        return $dataUnserialized;
    }

    /**
     * @param array $dataUnserialized
     * @return array
     */
    public function replaceTableNames($dataUnserialized) {
        $tablesToReplace = [
            'Command.commandarguments' => __('Command arguments'),

            'Timeperiod.timeperiod_timeranges' => __('Time ranges'),

            'Contact.customvariables' => __('Custom variables'),
            'Contactgroup.container'  => __('Container'),

            'Hostgroup.container' => __('Container'),

            'Hosttemplate.customvariables'                   => __('Custom variables'),
            'Hosttemplate.hosttemplatecommandargumentvalues' => __('Command arguments'),

            'Servicetemplate.customvariables'                           => __('Custom variables'),
            'Servicetemplate.servicetemplatecommandargumentvalues'      => __('Command arguments'),
            'Servicetemplate.servicetemplateeventcommandargumentvalues' => __('Event handler command arguments'),

            'Servicegroup.container' => __('Container'),

            'Servicetemplategroup.container' => __('Container'),

            'Host.customvariables'           => __('Custom variables'),
            'Host.hostcommandargumentvalues' => __('Command arguments'),


            'Service.customvariables'                   => __('Custom variables'),
            'Service.servicecommandargumentvalues'      => __('Command arguments'),
            'Service.serviceeventcommandargumentvalues' => __('Event handler command arguments'),

            'tenant.container' => __('Container'),

            'location.container' => __('Container'),
        ];

        $newDataUnserialized = [];
        foreach ($dataUnserialized as $index => $record) {
            foreach ($record as $tableName => $changes) {
                //Replace table name with better name for humans?
                if (isset($tablesToReplace[$tableName])) {
                    $newTableName = $tablesToReplace[$tableName];
                    $newDataUnserialized[$newTableName] = $changes;
                } else {
                    //Keep table name
                    $newDataUnserialized[$tableName] = $changes;
                }

            }
        }
        return $newDataUnserialized;

    }

    /**
     * @param array $dataUnserialized
     * @param string $action
     * @return array
     */
    public function formatDataForView(array $dataUnserialized, string $action): array {
        foreach ($dataUnserialized as $index => $record) {
            foreach ($record as $tableName => $changes) {
                if ($action !== 'edit') {
                    $dataUnserialized[$index][$tableName] = [
                        'data'    => $changes['current_data'] ?? [],
                        'isArray' => Hash::dimensions($changes) === 3
                    ];
                } else {
                    //Back box Unicorn 🦄 Merge-O-Mat and Diff-O-Mat

                    $diffs = [];
                    $isArray = Hash::dimensions($changes) === 3;

                    if (empty($changes['before']) && !empty($changes['after'])) {
                        //All changes are new/added (fields where empty before)
                        foreach ($changes['after'] as $fieldName => $fieldValue) {
                            $diffs[$fieldName] = [
                                'old' => '',
                                'new' => is_array($fieldValue) ? Hash::remove($fieldValue, 'id') : $fieldValue,
                            ];
                        }
                    }

                    if (!empty($changes['before']) && empty($changes['after'])) {
                        //All data got removed from fields (fields where filled before)
                        foreach ($changes['before'] as $fieldName => $fieldValue) {
                            $diffs[$fieldName] = [
                                'old' => is_array($fieldValue) ? Hash::remove($fieldValue, 'id') : $fieldValue,
                                'new' => null
                            ];
                        }
                    }

                    if (!empty($changes['before']) && !empty($changes['after'])) {
                        //Data got modified (e.g. rename or so)

                        if (!$isArray) {
                            foreach (Hash::diff($changes['after'], $changes['before']) as $fieldName => $fieldValue) {
                                if ($fieldName === 'id') {
                                    continue;
                                }
                                $diffs[$fieldName] = [
                                    'old' => $changes['before'][$fieldName] ?? '',
                                    'new' => $fieldValue
                                ];
                            }
                        } else {
                            $idsBeforeSave = Hash::extract($changes['before'], '{n}.id');
                            $idsAfterSave = Hash::extract($changes['after'], '{n}.id');

                            foreach ($idsBeforeSave as $id) {
                                if (!in_array($id, $idsAfterSave, true)) {
                                    //Object got deleted
                                    $diffs[] = [
                                        'old' => Hash::remove(Hash::extract($changes['before'], '{n}[id=' . $id . ']')[0], 'id'),
                                        'new' => null
                                    ];
                                } else {
                                    //Object got edited
                                    $diffs[] = [
                                        'old' => Hash::remove(Hash::extract($changes['before'], '{n}[id=' . $id . ']')[0], 'id'),
                                        'new' => Hash::remove(Hash::extract($changes['after'], '{n}[id=' . $id . ']')[0], 'id'),
                                    ];
                                }
                            }

                            foreach ($changes['after'] as $after) {
                                if (!isset($after['id'])) {
                                    //New created object
                                    $diffs[] = [
                                        'old' => null,
                                        'new' => $after
                                    ];
                                }
                            }
                        }

                    }

                    $dataUnserialized[$index][$tableName] = [
                        'data'    => $diffs,
                        'isArray' => $isArray
                    ];

                }
            }
        }
        return $dataUnserialized;
    }
}


