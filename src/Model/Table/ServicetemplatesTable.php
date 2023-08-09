<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use App\Lib\Traits\PluginManagerTableTrait;
use App\Model\Entity\Changelog;
use App\Model\Entity\Servicetemplate;
use Cake\Core\Plugin;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ServicetemplateFilter;

/**
 * Servicetemplates Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $CheckPeriods
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $NotifyPeriods
 * @property \App\Model\Table\CommandsTable|\Cake\ORM\Association\BelongsTo $Commands
 * @property \App\Model\Table\CommandsTable|\Cake\ORM\Association\BelongsTo $EventhandlerCommands
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $Timeperiods
 * @property \App\Model\Table\ContactgroupsToServicetemplatesTable|\Cake\ORM\Association\HasMany $ContactgroupsToServicetemplates
 * @property \App\Model\Table\ContactsToServicetemplatesTable|\Cake\ORM\Association\HasMany $ContactsToServicetemplates
 * @property \App\Model\Table\DeletedServicesTable|\Cake\ORM\Association\HasMany $DeletedServices
 * @property \App\Model\Table\ServicetemplatecommandargumentvaluesTable|\Cake\ORM\Association\HasMany $Servicetemplatecommandargumentvalues
 * @property \App\Model\Table\ServicetemplateeventcommandargumentvaluesTable|\Cake\ORM\Association\HasMany $Servicetemplateeventcommandargumentvalues
 *
 * @method \App\Model\Entity\Servicetemplate get($primaryKey, $options = [])
 * @method \App\Model\Entity\Servicetemplate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Servicetemplate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Servicetemplate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Servicetemplate|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Servicetemplate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Servicetemplate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Servicetemplate findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServicetemplatesTable extends Table {


    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;
    use CustomValidationTrait;
    use PluginManagerTableTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('servicetemplates');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Contactgroups', [
            'className'        => 'Contactgroups',
            'foreignKey'       => 'servicetemplate_id',
            'targetForeignKey' => 'contactgroup_id',
            'joinTable'        => 'contactgroups_to_servicetemplates',
            'saveStrategy'     => 'replace'
        ]);

        $this->belongsToMany('Contacts', [
            'className'        => 'Contacts',
            'foreignKey'       => 'servicetemplate_id',
            'targetForeignKey' => 'contact_id',
            'joinTable'        => 'contacts_to_servicetemplates',
            'saveStrategy'     => 'replace'
        ]);

        $this->belongsToMany('Servicegroups', [
            'className'        => 'Servicegroups',
            'foreignKey'       => 'servicetemplate_id',
            'targetForeignKey' => 'servicegroup_id',
            'joinTable'        => 'servicetemplates_to_servicegroups',
            'saveStrategy'     => 'replace'
        ]);

        $this->belongsToMany('Servicetemplategroups', [
            'className'        => 'Servicetemplategroups',
            'foreignKey'       => 'servicetemplate_id',
            'targetForeignKey' => 'servicetemplategroup_id',
            'joinTable'        => 'servicetemplates_to_servicetemplategroups',
            'saveStrategy'     => 'replace'
        ]);

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
        ]);

        $this->belongsTo('CheckPeriod', [
            'className'  => 'Timeperiods',
            'foreignKey' => 'check_period_id',
            'joinType'   => 'INNER'
        ]);

        $this->belongsTo('NotifyPeriod', [
            'className'  => 'Timeperiods',
            'foreignKey' => 'notify_period_id',
            'joinType'   => 'INNER'
        ]);

        $this->belongsTo('CheckCommand', [
            'className'  => 'Commands',
            'foreignKey' => 'command_id',
            'joinType'   => 'INNER'
        ]);

        $this->hasOne('Agentchecks', [
            'foreignKey' => 'servicetemplate_id'
        ])->setDependent(true);

        $this->hasMany('Customvariables', [
            'conditions'   => [
                'objecttype_id' => OBJECT_SERVICETEMPLATE
            ],
            'foreignKey'   => 'object_id',
            'saveStrategy' => 'replace'
        ])->setDependent(true);

        $this->hasMany('Servicetemplatecommandargumentvalues', [
            'saveStrategy' => 'replace'
        ])->setDependent(true);

        $this->hasMany('Servicetemplateeventcommandargumentvalues', [
            'foreignKey'   => 'servicetemplate_id',
            'saveStrategy' => 'replace'
        ])->setDependent(true);

        $this->hasMany('Services');
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
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->allowEmptyString('uuid', null, false)
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', null, false);

        $validator
            ->scalar('template_name')
            ->maxLength('template_name', 255)
            ->requirePresence('template_name', 'create')
            ->allowEmptyString('template_name', null, false);


        $validator
            ->allowEmptyString('description', null, true);

        $validator
            ->integer('priority')
            ->requirePresence('priority', 'create')
            ->range('priority', [1, 5], __('This value must be between 1 and 5'));

        $validator
            ->integer('container_id')
            ->requirePresence('container_id', 'create')
            ->allowEmptyString('container_id', null, false)
            ->greaterThanOrEqual('container_id', 1);

        $validator
            ->integer('max_check_attempts')
            ->requirePresence('max_check_attempts', 'create')
            ->greaterThanOrEqual('max_check_attempts', 1, __('This value need to be at least 1'))
            ->allowEmptyString('max_check_attempts', null, false);

        $validator
            ->numeric('notification_interval')
            ->requirePresence('notification_interval', 'create')
            ->greaterThanOrEqual('notification_interval', 0, __('This value need to be at least 0'))
            ->allowEmptyString('notification_interval', null, false);

        $validator
            ->integer('check_interval')
            ->requirePresence('check_interval', 'create')
            //->greaterThanOrEqual('check_interval', 1, __('This value need to be at least 1'), function ($context) {
            //    if (array_key_exists('active_checks_enabled', $context['data']) && $context['data']['active_checks_enabled'] == 0) {
            //        return false;
            //    }
            //    return true;
            //})
            ->allowEmptyString('check_interval', null, false);

        $validator
            ->integer('retry_interval')
            ->requirePresence('retry_interval', 'create')
            //->greaterThanOrEqual('retry_interval', 1, __('This value need to be at least 1'), function ($context) {
            //    if (array_key_exists('active_checks_enabled', $context['data']) && $context['data']['active_checks_enabled'] == 0) {
            //        return false;
            //    }
            //    return true;
            //})
            ->allowEmptyString('retry_interval', null, false);

        $validator
            ->integer('check_period_id')
            ->requirePresence('check_period_id', 'create')
            ->greaterThan('check_period_id', 0, __('Please select a check period'))
            ->allowEmptyString('check_period_id', null, false);

        $validator
            ->integer('command_id')
            ->requirePresence('command_id', 'create')
            ->greaterThan('command_id', 0, __('Please select a check command'))
            ->allowEmptyString('command_id', null, __('Please select a check period'));

        $validator
            ->integer('eventhandler_command_id')
            ->requirePresence('eventhandler_command_id', false)
            ->allowEmptyString('eventhandler_command_id', null, true);

        $validator
            ->integer('notify_period_id')
            ->requirePresence('notify_period_id', 'create')
            ->greaterThan('notify_period_id', 0, __('Please select a notify period'))
            ->allowEmptyString('notify_period_id', null, false);

        $validator
            ->boolean('notify_on_recovery')
            ->requirePresence('notify_on_recovery', 'create')
            ->allowEmptyString('notify_on_recovery', null, false)
            ->add('notify_on_recovery', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsServicetemplate'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_warning')
            ->requirePresence('notify_on_warning', 'create')
            ->allowEmptyString('notify_on_warning', null, false)
            ->add('notify_on_warning', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsServicetemplate'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_critical')
            ->requirePresence('notify_on_critical', 'create')
            ->allowEmptyString('notify_on_critical', null, false)
            ->add('notify_on_critical', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsServicetemplate'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_unknown')
            ->requirePresence('notify_on_unknown', 'create')
            ->allowEmptyString('notify_on_unknown', null, false)
            ->add('notify_on_unknown', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsServicetemplate'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_flapping')
            ->requirePresence('notify_on_flapping', 'create')
            ->allowEmptyString('notify_on_flapping', null, false)
            ->add('notify_on_flapping', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsServicetemplate'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_downtime')
            ->requirePresence('notify_on_downtime', 'create')
            ->allowEmptyString('notify_on_downtime', null, false)
            ->add('notify_on_downtime', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsServicetemplate'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('flap_detection_enabled')
            ->requirePresence('flap_detection_enabled', 'create')
            ->allowEmptyString('flap_detection_enabled', null, false);

        $validator
            ->boolean('flap_detection_on_ok')
            ->requirePresence('flap_detection_on_ok', 'create')
            ->allowEmptyString('flap_detection_on_ok', null, false)
            ->add('flap_detection_on_ok', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsServicetemplate'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->boolean('flap_detection_on_warning')
            ->requirePresence('flap_detection_on_warning', 'create')
            ->allowEmptyString('flap_detection_on_warning', null, false)
            ->add('flap_detection_on_warning', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsServicetemplate'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->boolean('flap_detection_on_critical')
            ->requirePresence('flap_detection_on_critical', 'create')
            ->allowEmptyString('flap_detection_on_critical', null, false)
            ->add('flap_detection_on_critical', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsServicetemplate'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->boolean('flap_detection_on_unknown')
            ->requirePresence('flap_detection_on_unknown', 'create')
            ->allowEmptyString('flap_detection_on_unknown', null, false)
            ->add('flap_detection_on_unknown', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsServicetemplate'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->numeric('low_flap_threshold')
            ->requirePresence('low_flap_threshold', 'create')
            ->allowEmptyString('low_flap_threshold', null, false);

        $validator
            ->numeric('high_flap_threshold')
            ->requirePresence('high_flap_threshold', 'create')
            ->allowEmptyString('high_flap_threshold', null, false);

        $validator
            ->boolean('process_performance_data')
            ->requirePresence('process_performance_data', false)
            ->allowEmptyString('process_performance_data', null, true);

        $validator
            ->boolean('passive_checks_enabled')
            ->requirePresence('passive_checks_enabled', 'create')
            ->allowEmptyString('passive_checks_enabled', null, false);

        $validator
            ->boolean('event_handler_enabled')
            ->requirePresence('event_handler_enabled', 'create')
            ->allowEmptyString('event_handler_enabled', null, false);

        $validator
            ->boolean('active_checks_enabled')
            ->requirePresence('active_checks_enabled', 'create')
            ->allowEmptyString('active_checks_enabled', null, false);

        $validator
            ->scalar('notes')
            ->requirePresence('notes', false)
            ->allowEmptyString('notes', null, true)
            ->maxLength('notes', 255);

        $validator
            ->scalar('tags')
            ->requirePresence('tags', false)
            ->allowEmptyString('tags', null, true)
            ->maxLength('tags', 255);

        $validator
            ->scalar('service_url')
            ->requirePresence('service_url', false)
            ->allowEmptyString('service_url', null, true)
            ->maxLength('service_url', 255);

        $validator
            ->allowEmptyString('customvariables', null, true)
            ->add('customvariables', 'custom', [
                'rule'    => [$this, 'checkMacroNames'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('Macro name needs to be unique')
            ]);

        $validator
            ->boolean('is_volatile')
            ->requirePresence('is_volatile', false)
            ->allowEmptyString('is_volatile', null, true);

        $validator
            ->boolean('freshness_checks_enabled')
            ->requirePresence('freshness_checks_enabled', false)
            ->allowEmptyString('freshness_checks_enabled', null, true);

        $validator
            ->integer('freshness_threshold')
            ->allowEmptyString('freshness_threshold');

        return $validator;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Servicetemplates.id' => $id]);
    }

    /**
     * @param string $uuid
     * @return bool
     */
    public function existsByUuid(string $uuid) {
        return $this->exists(['Servicetemplates.uuid' => $uuid]);
    }

    /**
     * @param ServicetemplateFilter $ServicetemplateFilter
     * @param null|PaginateOMat $PaginateOMat
     * @param array $MY_RIGHTS
     * @param null $servicetemplatetypeId
     * @return array
     */
    public function getServicetemplatesIndex(ServicetemplateFilter $ServicetemplateFilter, $PaginateOMat = null, $MY_RIGHTS = [], $servicetemplatetypeId = null) {
        if ($servicetemplatetypeId === null) {
            //$servicetemplatetypeId = GENERIC_SERVICE;
        }


        $query = $this->find('all')->disableHydration();
        $where = $ServicetemplateFilter->indexFilter();
        //$where['Servicetemplates.servicetemplatetype_id'] = $servicetemplatetypeId;
        if (!empty($MY_RIGHTS)) {
            $where['Servicetemplates.container_id IN'] = $MY_RIGHTS;
        }

        $query->where($where);
        $query->order($ServicetemplateFilter->getOrderForPaginator('Servicetemplates.name', 'asc'));

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->formatResultAsCake2($query->toArray(), false);
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scroll($query, $PaginateOMat->getHandler(), false);
            } else {
                $result = $this->paginate($query, $PaginateOMat->getHandler(), false);
            }
        }

        return $result;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->isUnique(['uuid']));
        return $rules;
    }

    /**
     * @param array $dataToParse
     * @return array
     */
    public function resolveDataForChangelog($dataToParse = []) {
        $extDataForChangelog = [
            'Contact'      => [],
            'Contactgroup' => [],
            'Servicegroup' => [],
            'CheckPeriod',
            'NotifyPeriod',
            'CheckCommand',
            'EventhandlerCommand'
        ];

        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        /** @var $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');

        if (!empty($dataToParse['Servicetemplate']['contacts']['_ids'])) {
            foreach ($ContactsTable->getContactsAsList($dataToParse['Servicetemplate']['contacts']['_ids']) as $contactId => $contactName) {
                $extDataForChangelog['Contact'][] = [
                    'id'   => $contactId,
                    'name' => $contactName
                ];
            }
        }

        if (!empty($dataToParse['Servicetemplate']['contactgroups']['_ids'])) {
            foreach ($ContactgroupsTable->getContactgroupsAsList($dataToParse['Servicetemplate']['contactgroups']['_ids']) as $contactgroupId => $contactgroupName) {
                $extDataForChangelog['Contactgroup'][] = [
                    'id'   => $contactgroupId,
                    'name' => $contactgroupName
                ];
            }
        }

        if (!empty($dataToParse['Servicetemplate']['servicegroups']['_ids'])) {
            foreach ($ServicegroupsTable->getServicegroupsAsList($dataToParse['Servicetemplate']['servicegroups']['_ids']) as $servicegroupId => $servicegroupName) {
                $extDataForChangelog['Servicegroup'][] = [
                    'id'   => $servicegroupId,
                    'name' => $servicegroupName
                ];
            }
        }

        if (!empty($dataToParse['Servicetemplate']['check_period_id'])) {
            foreach ($TimeperiodsTable->getTimeperiodsAsList($dataToParse['Servicetemplate']['check_period_id']) as $timeperiodId => $timeperiodName) {
                $extDataForChangelog['CheckPeriod'] = [
                    'id'   => $timeperiodId,
                    'name' => $timeperiodName
                ];
            }
        }

        if (!empty($dataToParse['Servicetemplate']['notify_period_id'])) {
            foreach ($TimeperiodsTable->getTimeperiodsAsList($dataToParse['Servicetemplate']['notify_period_id']) as $timeperiodId => $timeperiodName) {
                $extDataForChangelog['NotifyPeriod'] = [
                    'id'   => $timeperiodId,
                    'name' => $timeperiodName
                ];
            }
        }

        if (!empty($dataToParse['Servicetemplate']['command_id'])) {
            foreach ($CommandsTable->getCommandByIdAsList($dataToParse['Servicetemplate']['command_id']) as $commandId => $commandName) {
                $extDataForChangelog['CheckCommand'] = [
                    'id'   => $commandId,
                    'name' => $commandName
                ];
            }
        }

        if (!empty($dataToParse['Servicetemplate']['eventhandler_command_id'])) {
            foreach ($CommandsTable->getCommandByIdAsList($dataToParse['Servicetemplate']['eventhandler_command_id']) as $commandId => $commandName) {
                $extDataForChangelog['EventhandlerCommand'] = [
                    'id'   => $commandId,
                    'name' => $commandName
                ];
            }
        }

        return $extDataForChangelog;
    }

    /**
     * @param int $id
     * @param array $contain
     * @return array
     */
    public function getServicetemplateById($id, $contain = ['Containers']) {
        $query = $this->find()
            ->where([
                'Servicetemplates.id' => $id
            ])
            ->contain($contain)
            ->disableHydration()
            ->first();

        return $this->formatFirstResultAsCake2($query, true);
    }

    /**
     * @param $uuid
     * @param array $contain
     * @param bool $formatAsCake2
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getServicetemplateByUuid($uuid, $contain = ['Containers'], $formatAsCake2 = true) {
        $query = $this->find()
            ->where([
                'Servicetemplates.uuid' => $uuid
            ])
            ->contain($contain)
            ->disableHydration()
            ->first();

        if (!$formatAsCake2) {
            return $query;
        }
        return $this->formatFirstResultAsCake2($query, true);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getServicetemplateForEdit($id) {
        $query = $this->find()
            ->where([
                'Servicetemplates.id' => $id
            ])
            ->contain([
                'Contactgroups',
                'Contacts',
                'Servicegroups',
                'Customvariables',
                'Servicetemplatecommandargumentvalues'      => [
                    'Commandarguments'
                ],
                'Servicetemplateeventcommandargumentvalues' => [
                    'Commandarguments'
                ],
                'CheckCommand'                              => [
                    'Commandarguments'
                ]
            ])
            ->disableHydration()
            ->first();

        $servicetemplate = $query;
        $servicetemplate['servicegroups'] = [
            '_ids' => Hash::extract($query, 'servicegroups.{n}.id')
        ];
        $servicetemplate['contacts'] = [
            '_ids' => Hash::extract($query, 'contacts.{n}.id')
        ];
        $servicetemplate['contactgroups'] = [
            '_ids' => Hash::extract($query, 'contactgroups.{n}.id')
        ];

        // Merge new command arguments that are missing in the service template to service template command arguments
        // and remove old command arguments that don't exists in the command anymore.
        $filteredCommandArgs = [];
        foreach ($servicetemplate['check_command']['commandarguments'] as $commandargument) {
            $valueExists = false;
            foreach ($servicetemplate['servicetemplatecommandargumentvalues'] as $servicetemplatecommandargumentvalue) {
                if ($commandargument['id'] === $servicetemplatecommandargumentvalue['commandargument']['id']) {
                    $filteredCommandArgs[] = $servicetemplatecommandargumentvalue;
                    $valueExists = true;
                }
            }
            if (!$valueExists) {
                $filteredCommandArgs[] = [
                    'commandargument_id' => $commandargument['id'],
                    'servicetemplate_id' => $servicetemplate['id'],
                    'value'              => '',
                    'commandargument'    => [
                        'name'       => $commandargument['name'],
                        'human_name' => $commandargument['human_name'],
                        'command_id' => $commandargument['command_id'],
                    ]
                ];
            }
        }

        $servicetemplate['servicetemplatecommandargumentvalues'] = $filteredCommandArgs;
        $servicetemplate['servicetemplatecommandargumentvalues'] = Hash::sort(
            $servicetemplate['servicetemplatecommandargumentvalues'],
            '{n}.commandargument.name',
            'asc',
            'natural'
        );


        return [
            'Servicetemplate' => $servicetemplate
        ];
    }

    /**
     * @param $ids
     * @param array $MY_RIGHTS
     * @param array $excludedUuids
     * @return array
     */
    public function getServicetemplatesFoWizardDeploy($ids, $MY_RIGHTS = [], $excludedUuids = []) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        if (!is_array($excludedUuids)) {
            $excludedUuids = [$excludedUuids];
        }

        $query = $this->find()
            ->where([
                'Servicetemplates.id IN' => $ids
            ]);
        if (!empty($excludedUuids)) {
            $query->whereNotInList('Servicetemplates.uuid', $excludedUuids);
        }

        $query->contain([
            'Servicetemplatecommandargumentvalues' => [
                'Commandarguments'
            ],
            'CheckCommand'                         => [
                'Commandarguments'
            ]
        ]);
        if (!empty($MY_RIGHTS)) {
            $query->where([
                'Servicetemplates.container_id IN' => $MY_RIGHTS
            ]);
        }
        $query->disableHydration()
            ->all();

        $servicetemplates = [];
        foreach ($query as $servicetemplate) {
            // Merge new command arguments that are missing in the service template to service template command arguments
            // and remove old command arguments that don't exists in the command anymore.
            $filteredCommandArgs = [];
            foreach ($servicetemplate['check_command']['commandarguments'] as $commandargument) {
                $valueExists = false;
                foreach ($servicetemplate['servicetemplatecommandargumentvalues'] as $servicetemplatecommandargumentvalue) {
                    if ($commandargument['id'] === $servicetemplatecommandargumentvalue['commandargument']['id']) {
                        $filteredCommandArgs[] = $servicetemplatecommandargumentvalue;
                        $valueExists = true;
                    }
                }
                if (!$valueExists) {
                    $filteredCommandArgs[] = [
                        'commandargument_id' => $commandargument['id'],
                        'servicetemplate_id' => $servicetemplate['id'],
                        'value'              => '',
                        'commandargument'    => [
                            'name'       => $commandargument['name'],
                            'human_name' => $commandargument['human_name'],
                            'command_id' => $commandargument['command_id'],
                        ]
                    ];
                }
            }
            $servicetemplate['servicetemplatecommandargumentvalues'] = $filteredCommandArgs;
            $servicetemplate['servicetemplatecommandargumentvalues'] = Hash::sort(
                $servicetemplate['servicetemplatecommandargumentvalues'],
                '{n}.commandargument.name',
                'asc',
                'natural'
            );

            $servicetemplates[] = $servicetemplate;
        }

        return $servicetemplates;
    }

    /**
     * @param array $MY_RIGHTS
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getServicetemplateFoWizardDeployInterfaces($MY_RIGHTS = []) {
        $query = $this->find()
            ->where([
                'Servicetemplates.uuid' => 'de5e3045-3011-45d8-8ac6-bc5fbb3d396d'
            ])
            ->contain([
                'Servicetemplatecommandargumentvalues' => [
                    'Commandarguments'
                ],
                'CheckCommand'                         => [
                    'Commandarguments'
                ]
            ]);
        if (!empty($MY_RIGHTS)) {
            $query->where([
                'Servicetemplates.container_id IN' => $MY_RIGHTS
            ]);
        }
        $query->disableHydration();
        if (!is_null($query)) {
            return $query->first();
        }
        return [];
    }

    /**
     * @param int $id
     * @return array
     */
    public function getServicetemplateForDiff($id) {
        return $this->getServicetemplateForEdit($id);
    }

    /**
     * @param int $id
     * @return int
     */
    public function getContainerIdById($id) {
        $query = $this->find()
            ->select([
                'Servicetemplates.id',
                'Servicetemplates.container_id'
            ])
            ->where([
                'Servicetemplates.id' => $id
            ])
            ->firstOrFail();

        return (int)$query->get('container_id');
    }

    /**
     * @param array $containerIds
     * @param ServicetemplateFilter $ServicetemplateFilter
     * @param array $selected
     * @return array|\Cake\ORM\Query
     */
    public function getServicetemplatesForAngular($containerIds, ServicetemplateFilter $ServicetemplateFilter, $selected = []) {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }
        if (!is_array($selected)) {
            $selected = [$selected];
        }

        $where = $ServicetemplateFilter->ajaxFilter();
        $where['Servicetemplates.container_id IN'] = $containerIds;
        $query = $this->find('list', [
            'keyField'   => 'id',
            'valueField' => 'template_name'
        ])
            ->where($where)
            ->order([
                'Servicetemplates.template_name' => 'asc'
            ])
            ->limit(ITN_AJAX_LIMIT)
            ->disableHydration();

        $servicetemplatesWithLimit = $query->toArray();
        if (empty($servicetemplatesWithLimit)) {
            $servicetemplatesWithLimit = [];
        }

        $selectedServicetemplates = [];
        if (!empty($selected)) {
            $query = $this->find('list', [
                'keyField'   => 'id',
                'valueField' => 'template_name'
            ])
                ->where([
                    'Servicetemplates.id IN'           => $selected,
                    'Servicetemplates.container_id IN' => $containerIds
                ])
                ->order([
                    'Servicetemplates.template_name' => 'asc'
                ]);

            $selectedServicetemplates = $query->toArray();
            if (empty($selectedServicetemplates)) {
                $selectedServicetemplates = [];
            }
        }

        $servicetemplates = $servicetemplatesWithLimit + $selectedServicetemplates;

        asort($servicetemplates, SORT_FLAG_CASE | SORT_NATURAL);
        return $servicetemplates;
    }

    /**
     * @param array $ids
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getServicetemplatesForCopy($ids = [], array $MY_RIGHTS = []) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $query = $this->find()
            ->select([
                'Servicetemplates.id',
                'Servicetemplates.name',
                'Servicetemplates.template_name',
                'Servicetemplates.description',
                'Servicetemplates.command_id',
                'Servicetemplates.active_checks_enabled'
            ])
            ->contain([
                'Servicetemplatecommandargumentvalues'      => [
                    'Commandarguments'
                ],
                'Servicetemplateeventcommandargumentvalues' => [
                    'Commandarguments'
                ]
            ])
            ->where(['Servicetemplates.id IN' => $ids])
            ->order(['Servicetemplates.id' => 'asc']);

        if (!empty($MY_RIGHTS)) {
            $query->andWhere(['Servicetemplates.container_id IN' => $MY_RIGHTS]);
        }

        $query
            ->disableHydration()
            ->all();

        $query = $query->toArray();

        if ($query === null) {
            return [];
        }

        return $query;
    }

    /**
     * @param array $containerIds
     * @param string $type
     * @param int|array $servicetemplateTypes
     * @param bool $ignoreType
     * @return array
     */
    public function getServicetemplatesByContainerId($containerIds = [], $type = 'all', $servicetemplateTypes = GENERIC_SERVICE, $ignoreType = false) {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        if (!is_array($servicetemplateTypes)) {
            $servicetemplateTypes = [$servicetemplateTypes];
        }

        //Lookup for the tenant container of $container_id
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $tenantContainerIds = [];

        foreach ($containerIds as $containerId) {
            if ($containerId != ROOT_CONTAINER) {

                // Get container id of the tenant container
                // $container_id is may be a location, devicegroup or whatever, so we need to container id of the tenant container to load service templates
                $path = $ContainersTable->getPathByIdAndCacheResult($containerId, 'ServicetemplatesByContainerId');

                // Tenant service templates are available for all users of a tenant (oITC V2 legacy)
                if (isset($path[1])) {
                    $tenantContainerIds[] = $path[1]['id'];
                }
            } else {
                $tenantContainerIds[] = ROOT_CONTAINER;
            }
        }
        $tenantContainerIds = array_unique($tenantContainerIds);
        $containerIds = array_unique(array_merge($tenantContainerIds, $containerIds));


        if (empty($containerIds)) {
            return [];
        }

        $where = [
            'Servicetemplates.container_id IN' => $containerIds,
        ];
        if (!$ignoreType) {
            $where['Servicetemplates.servicetemplatetype_id IN'] = $servicetemplateTypes;
        }


        $query = $this->find()
            ->contain(['Containers'])
            ->where($where)
            ->disableHydration()
            ->all();

        $records = $query->toArray();
        if (empty($records) || is_null($records)) {
            return [];
        }

        if ($type === 'all') {
            return $records;
        }

        $list = [];
        foreach ($records as $record) {
            $list[$record['id']] = $record['template_name'];
        }
        return $list;
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getServicetemplatesAsList($ids = []) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $query = $this->find()
            ->select([
                'Servicetemplates.id',
                'Servicetemplates.template_name'
            ])
            ->disableHydration();
        if (!empty($ids)) {
            $query->where([
                'Servicetemplates.id IN' => $ids
            ]);
        }

        return $this->formatListAsCake2($query->toArray(), 'id', 'template_name');
    }

    /**
     * @param int $id
     * @return array|\Cake\Datasource\EntityInterface
     */
    public function getContactsAndContactgroupsById($id) {
        $query = $this->find()
            ->select([
                'Servicetemplates.id'
            ])
            ->where([
                'Servicetemplates.id' => $id
            ])
            ->contain([
                'Contactgroups',
                'Contacts'
            ])
            ->disableHydration()
            ->firstOrFail();

        $servicetemplate = $query;
        $servicetemplate['contacts'] = [
            '_ids' => Hash::extract($query, 'contacts.{n}.id')
        ];
        $servicetemplate['contactgroups'] = [
            '_ids' => Hash::extract($query, 'contactgroups.{n}.id')
        ];

        return $servicetemplate;
    }

    /**
     * @param null $uuid
     * @return array|\Cake\ORM\Query
     */
    public function getServicetemplatesForExport($uuid = null) {
        $query = $this->find()
            ->contain([
                'Contactgroups',
                'Contacts',
                'Servicegroups',
                'Customvariables',
                'CheckPeriod',
                'NotifyPeriod',
                'CheckCommand',
                'Servicetemplatecommandargumentvalues'      => [
                    'Commandarguments'
                ],
                'Servicetemplateeventcommandargumentvalues' => [
                    'Commandarguments'
                ]
            ]);
        if (!empty($uuid)) {
            if (!is_array($uuid)) {
                $uuid = [$uuid];
            }
            $query->where([
                'Servicetemplates.uuid IN' => $uuid
            ]);
        }
        $query->all();
        return $query;
    }

    /**
     * @param $id
     * @return array|\Cake\Datasource\EntityInterface
     */
    public function getServicetemplateForServiceExport($id) {
        return $this->find()
            ->contain([
                'Servicegroups',
                'Servicetemplatecommandargumentvalues' => [
                    'Commandarguments'
                ]
            ])
            ->where([
                'Servicetemplates.id' => $id
            ])->firstOrFail();
    }

    /**
     * @param int $commandId
     * @return bool
     */
    public function isCommandUsedByServicetemplate($commandId) {
        $count = $this->find()
            ->where([
                'Servicetemplates.command_id' => $commandId,
            ])->count();

        if ($count > 0) {
            return true;
        }

        $count = $this->find()
            ->where([
                'Servicetemplates.eventhandler_command_id' => $commandId,
            ])->count();

        if ($count > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param int $commandId
     * @param array $MY_RIGHTS
     * @param bool $enableHydration
     * @return array
     */
    public function getServicetemplatesByCommandId($commandId, $MY_RIGHTS = [], $enableHydration = true) {
        $query = $this->find()
            ->select([
                'Servicetemplates.id',
                'Servicetemplates.name',
                'Servicetemplates.template_name',
                'Servicetemplates.uuid'
            ]);

        if (!empty($MY_RIGHTS)) {
            $query->where([
                'Servicetemplates.container_id IN' => $MY_RIGHTS
            ]);
        }

        $query->andWhere([
            'OR' => [
                ['Servicetemplates.command_id' => $commandId],
                ['Servicetemplates.eventhandler_command_id' => $commandId]
            ]
        ])
            ->order(['Servicetemplates.name' => 'asc'])
            ->enableHydration($enableHydration)
            ->all();

        return $this->emptyArrayIfNull($query->toArray());
    }

    /**
     * @param int $contactId
     * @param array $MY_RIGHTS
     * @param bool $enableHydration
     * @return array
     */
    public function getServicetemplatesByContactId($contactId, $MY_RIGHTS = [], $enableHydration = true) {

        /** @var ContactsToServicetemplatesTable $ContactsToServicetemplatesTable */
        $ContactsToServicetemplatesTable = TableRegistry::getTableLocator()->get('ContactsToServicetemplates');

        $query = $ContactsToServicetemplatesTable->find()
            ->select([
                'servicetemplate_id'
            ])
            ->where([
                'contact_id' => $contactId
            ])
            ->group([
                'servicetemplate_id'
            ])
            ->disableHydration()
            ->all();

        $result = $query->toArray();
        if (empty($result)) {
            return [];
        }

        $servicetemplateIds = Hash::extract($result, '{n}.servicetemplate_id');

        $query = $this->find('all');
        $where = [
            'Servicetemplates.id IN' => $servicetemplateIds
        ];
        if (!empty($MY_RIGHTS)) {
            $where['Servicetemplates.container_id IN'] = $MY_RIGHTS;
        }
        $query->where($where);
        $query->enableHydration($enableHydration);
        $query->order([
            'Servicetemplates.name' => 'asc'
        ]);

        $result = $query->all();

        return $this->emptyArrayIfNull($result->toArray());
    }

    /**
     * @param $servicetemplateName
     * @param null $servicetemplateTypeId
     * @return array|\Cake\Datasource\EntityInterface
     */
    public function getServicetemplateByName($servicetemplateName, $servicetemplateTypeId = null) {
        if ($servicetemplateTypeId === null) {
            $servicetemplateTypeId = GENERIC_SERVICE;
        }

        return $this->find()
            ->where([
                'Servicetemplates.template_name'          => $servicetemplateName,
                'Servicetemplates.servicetemplatetype_id' => $servicetemplateTypeId
            ])
            ->firstOrFail();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getServicetemplateForServiceBrowser($id) {
        $query = $this->find()
            ->where([
                'Servicetemplates.id' => $id
            ])
            ->contain([
                'Contactgroups'                             => [
                    'Containers'
                ],
                'Contacts'                                  => [
                    'Containers'
                ],
                'Servicegroups',
                'Customvariables',
                'Servicetemplatecommandargumentvalues'      => [
                    'Commandarguments'
                ],
                'Servicetemplateeventcommandargumentvalues' => [
                    'Commandarguments'
                ],
                'CheckCommand'                              => [
                    'Commandarguments'
                ]
            ])
            ->disableHydration()
            ->first();

        $servicetemplate = $query;

        // Merge new command arguments that are missing in the service template to service template command arguments
        // and remove old command arguments that don't exists in the command anymore.
        $filteredCommandArgs = [];
        foreach ($servicetemplate['check_command']['commandarguments'] as $commandargument) {
            $valueExists = false;
            foreach ($servicetemplate['servicetemplatecommandargumentvalues'] as $servicetemplatecommandargumentvalue) {
                if ($commandargument['id'] === $servicetemplatecommandargumentvalue['commandargument']['id']) {
                    $filteredCommandArgs[] = $servicetemplatecommandargumentvalue;
                    $valueExists = true;
                }
            }
            if (!$valueExists) {
                $filteredCommandArgs[] = [
                    'commandargument_id' => $commandargument['id'],
                    'servicetemplate_id' => $servicetemplate['id'],
                    'value'              => '',
                    'commandargument'    => [
                        'name'       => $commandargument['name'],
                        'human_name' => $commandargument['human_name'],
                        'command_id' => $commandargument['command_id'],
                    ]
                ];
            }
        }

        $servicetemplate['servicetemplatecommandargumentvalues'] = $filteredCommandArgs;
        $servicetemplate['servicetemplatecommandargumentvalues'] = Hash::sort(
            $servicetemplate['servicetemplatecommandargumentvalues'],
            '{n}.commandargument.name',
            'asc',
            'natural'
        );

        return $servicetemplate;
    }

    /**
     * @param int $timeperiodId
     * @return bool
     */
    public function isTimeperiodUsedByServicetemplate($timeperiodId) {
        $count = $this->find()
            ->where([
                'OR' => [
                    'Servicetemplates.check_period_id'  => $timeperiodId,
                    'Servicetemplates.notify_period_id' => $timeperiodId
                ]
            ])->count();

        if ($count > 0) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getServicetemplateTypes() {
        $types = $this->getServicetemplateTypesWithStyles();
        return array_combine(array_keys($types), Hash::extract($types, '{n}.title'));
    }

    /**
     * @return array
     */
    public function getServicetemplateTypesWithStyles() {
        $types[GENERIC_SERVICE] = [
            'title' => __('Generic templates'),
            'color' => 'text-generic',
            'class' => 'border-generic',
            'icon'  => 'fa fa-cog'
        ];

        if (Plugin::isLoaded('EventcorrelationModule')) {
            $types[EVK_SERVICE] = [
                'title' => __('EVC templates'),
                'color' => 'text-evc',
                'class' => 'border-evc',
                'icon'  => 'fa fa-sitemap fa-rotate-90'
            ];
        }

        if (Plugin::isLoaded('CheckmkModule')) {
            $types[MK_SERVICE] = [
                'title' => __('Checkmk templates'),
                'color' => 'text-mk',
                'class' => 'border-mk',
                'icon'  => 'fas fa-search-plus'
            ];
        }

        if (Plugin::isLoaded('PrometheusModule')) {
            $types[PROMETHEUS_SERVICE] = [
                'title' => __('Prometheus templates'),
                'color' => 'text-prometheus',
                'class' => 'border-prometheus',
                'icon'  => 'fas fa-burn'
            ];
        }

        $types[OITC_AGENT_SERVICE] = [
            'title' => __('Agent templates'),
            'color' => 'text-agent',
            'class' => 'border-agent',
            'icon'  => 'fa fa-user-secret'
        ];

        if (Plugin::isLoaded('ImportModule')) {
            $types[EXTERNAL_SERVICE] = [
                'title' => __('External templates'),
                'color' => 'text-external',
                'class' => 'border-external',
                'icon'  => 'fa-solid fa-tower-observation'
            ];
        }

        return $types;
    }

    /**
     * @param int $timeperiodId
     * @param array $MY_RIGHTS
     * @param bool $enableHydration
     * @return array
     */
    public function getServicetemplatesByTimeperiodId($timeperiodId, $MY_RIGHTS = [], $enableHydration = true) {
        $query = $this->find('all');
        $query->where([
            'OR' => [
                'Servicetemplates.check_period_id'  => $timeperiodId,
                'Servicetemplates.notify_period_id' => $timeperiodId
            ]
        ]);
        $query->select([
            'Servicetemplates.id',
            'Servicetemplates.name'
        ]);

        if (!empty($MY_RIGHTS)) {
            $query->where(['Servicetemplates.container_id IN' => $MY_RIGHTS]);
        }

        $query->enableHydration($enableHydration);
        $query->order([
            'Servicetemplates.name' => 'asc'
        ]);

        $result = $query->all();

        return $this->emptyArrayIfNull($result->toArray());
    }

    /**
     * @param $id
     * @return array
     * @deprecated
     * @todo delete this method with oITC 4.3 !!
     */
    public function getServicetemplateForNewAgentService($id) {
        $query = $this->find()
            ->where([
                'Servicetemplates.id' => $id
            ])
            ->contain([
                'Servicetemplatecommandargumentvalues'      => [
                    'Commandarguments'
                ],
                'Servicetemplateeventcommandargumentvalues' => [
                    'Commandarguments'
                ],
                'CheckCommand'                              => [
                    'Commandarguments'
                ],
                'Contacts',
                'Contactgroups',
                'Servicegroups',
                'Customvariables'
            ])
            ->disableHydration()
            ->first();

        $servicetemplate = $query;
        unset($servicetemplate['created'], $servicetemplate['modified']);

        $servicetemplate['servicegroups'] = [
            '_ids' => Hash::extract($query, 'servicegroups.{n}.id')
        ];
        $servicetemplate['contacts'] = [
            '_ids' => Hash::extract($query, 'contacts.{n}.id')
        ];
        $servicetemplate['contactgroups'] = [
            '_ids' => Hash::extract($query, 'contactgroups.{n}.id')
        ];

        // Merge new command arguments that are missing in the service template to service template command arguments
        // and remove old command arguments that don't exists in the command anymore.
        $filteredCommandArgs = [];
        foreach ($servicetemplate['check_command']['commandarguments'] as $commandargument) {
            $valueExists = false;
            foreach ($servicetemplate['servicetemplatecommandargumentvalues'] as $servicetemplatecommandargumentvalue) {
                if ($commandargument['id'] === $servicetemplatecommandargumentvalue['commandargument']['id']) {
                    $filteredCommandArgs[] = $servicetemplatecommandargumentvalue;
                    $valueExists = true;
                }
            }
            if (!$valueExists) {
                $filteredCommandArgs[] = [
                    'commandargument_id' => $commandargument['id'],
                    'servicetemplate_id' => $servicetemplate['id'],
                    'value'              => '',
                    'commandargument'    => [
                        'name'       => $commandargument['name'],
                        'human_name' => $commandargument['human_name'],
                        'command_id' => $commandargument['command_id'],
                    ]
                ];
            }
        }

        $servicetemplate['serviceeventcommandargumentvalues'] = $servicetemplate['servicetemplateeventcommandargumentvalues'];
        unset($servicetemplate['servicetemplateeventcommandargumentvalues']);
        $servicetemplate['servicecommandargumentvalues'] = $filteredCommandArgs;

        foreach ($servicetemplate['servicecommandargumentvalues'] as $i => $servicecommandargumentvalues) {
            unset($servicetemplate['servicecommandargumentvalues'][$i]['id']);

            if (isset($servicetemplate['servicecommandargumentvalues'][$i]['servicetemplate_id'])) {
                unset($servicetemplate['servicecommandargumentvalues'][$i]['servicetemplate_id']);
            }
        }

        return $servicetemplate;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function allowDelete($id) {
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $count = $ServicesTable->find()
            ->where([
                'Services.servicetemplate_id' => $id,
                'Services.usage_flag >'       => 0
            ])
            ->count();

        if ($count > 0) {
            //Service template is used by modules
            return false;
        }

        return true;
    }

    /**
     * @param Servicetemplate $Servicetemplate
     * @param User $User
     * @return bool
     */
    public function __delete(Servicetemplate $Servicetemplate, User $User) {

        /** @var  ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $services = $ServicesTable->find()
            ->contain([
                'ServiceescalationsServiceMemberships',
                'ServicedependenciesServiceMemberships'
            ])
            ->where([
                'Services.servicetemplate_id' => $Servicetemplate->get('id')
            ])
            ->all();

        //Delete all services used by this service template
        foreach ($services as $service) {
            $ServicesTable->__delete($service, $User);
        }

        if (!$this->delete($Servicetemplate)) {
            return false;
        }

        /** @var  ChangelogsTable $ChangelogsTable */
        $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

        $changelog_data = $ChangelogsTable->parseDataForChangelog(
            'delete',
            'servicetemplates',
            $Servicetemplate->get('id'),
            OBJECT_SERVICETEMPLATE,
            $Servicetemplate->get('container_id'),
            $User->getId(),
            $Servicetemplate->get('name'),
            [
                'Servicetemplate' => $Servicetemplate->toArray()
            ]
        );
        if ($changelog_data) {
            /** @var Changelog $changelogEntry */
            $changelogEntry = $ChangelogsTable->newEntity($changelog_data);
            $ChangelogsTable->save($changelogEntry);
        }

        //Delete Documentation record if exists
        /** @var DocumentationsTable $DocumentationsTable */
        $DocumentationsTable = TableRegistry::getTableLocator()->get('Documentations');
        if ($DocumentationsTable->existsByUuid($Servicetemplate->get('uuid'))) {
            $DocumentationsTable->delete($DocumentationsTable->getDocumentationByUuid($Servicetemplate->get('uuid')));
        }

        return true;
    }

    /**
     * @param int $containerId
     * @param string $type
     * @param array $MY_RIGHTS
     * @param array $where
     * @return array
     */
    public function getServicetemplatesByContainerIdExact($containerId, $type = 'all', $index = 'id', $MY_RIGHTS = [], $where = []) {
        $_where = [
            'Servicetemplates.container_id' => $containerId
        ];

        $where = Hash::merge($_where, $where);

        $query = $this->find();
        $query->select([
            'Servicetemplates.' . $index,
            'Servicetemplates.name'
        ]);
        $query->where($where);

        if (!empty($MY_RIGHTS)) {
            $query->andWhere([
                'Servicetemplates.container_id IN' => $MY_RIGHTS
            ]);
        }

        $query->disableHydration();
        $query->order([
            'Servicetemplates.name' => 'asc'
        ]);

        $result = $query->toArray();
        if (empty($result)) {
            return [];
        }

        if ($type === 'all') {
            return $result;
        }

        $list = [];
        foreach ($result as $row) {
            $list[$row[$index]] = $row['name'];
        }

        return $list;
    }

    /**
     * @param int $servicetemplateIds
     * @param array $MY_RIGHTS
     * @param bool $enableHydration
     * @return array
     */
    public function getServicetemplatesByIds($servicetemplateIds = [], $MY_RIGHTS = [], $enableHydration = true) {
        if (!is_array($servicetemplateIds)) {
            $servicetemplateIds = [$servicetemplateIds];
        }
        $query = $this->find()
            ->select([
                'Servicetemplates.id',
                'Servicetemplates.name',
                'Servicetemplates.description',
                'Servicetemplates.uuid'
            ])
            ->contain([
                'Servicetemplatecommandargumentvalues' => [
                    'Commandarguments'
                ]
            ]);

        if (!empty($MY_RIGHTS)) {
            $query->where([
                'Servicetemplates.container_id IN' => $MY_RIGHTS
            ]);
        }

        $query->andWhere([
            'Servicetemplates.id IN ' => $servicetemplateIds
        ])
            ->order(['Servicetemplates.name' => 'asc'])
            ->enableHydration($enableHydration)
            ->all();

        return $this->emptyArrayIfNull($query->toArray());
    }

}
