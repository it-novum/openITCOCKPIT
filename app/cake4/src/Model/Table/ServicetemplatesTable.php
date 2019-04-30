<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
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

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
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
            'foreignKey' => 'servicetemplate_id',
            'saveStrategy' => 'replace'
        ]);

        /*
        $this->hasMany('Service', [
            'saveStrategy' => 'replace'
        ]);
        */
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->allowEmptyString('uuid', false)
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('template_name')
            ->maxLength('template_name', 255)
            ->requirePresence('template_name', 'create')
            ->allowEmptyString('template_name', false);


        $validator
            ->allowEmptyString('description', true);

        $validator
            ->integer('priority')
            ->requirePresence('priority', 'create')
            ->range('priority', [1, 5], __('This value must be between 1 and 5'));

        $validator
            ->integer('container_id')
            ->requirePresence('container_id', 'create')
            ->allowEmptyString('container_id', false)
            ->greaterThanOrEqual('container_id', 1);

        $validator
            ->integer('max_check_attempts')
            ->requirePresence('max_check_attempts', 'create')
            ->greaterThanOrEqual('max_check_attempts', 1, __('This value need to be at least 1'))
            ->allowEmptyString('max_check_attempts', false);

        $validator
            ->numeric('notification_interval')
            ->requirePresence('notification_interval', 'create')
            ->greaterThanOrEqual('notification_interval', 0, __('This value need to be at least 0'))
            ->allowEmptyString('notification_interval', false);

        $validator
            ->integer('check_interval')
            ->requirePresence('check_interval', 'create')
            ->greaterThanOrEqual('check_interval', 1, __('This value need to be at least 1'))
            ->allowEmptyString('check_interval', false);

        $validator
            ->integer('retry_interval')
            ->requirePresence('retry_interval', 'create')
            ->greaterThanOrEqual('retry_interval', 1, __('This value need to be at least 1'))
            ->allowEmptyString('retry_interval', false);

        $validator
            ->integer('check_period_id')
            ->requirePresence('check_period_id', 'create')
            ->greaterThan('check_period_id', 0, __('Please select a check period'))
            ->allowEmptyString('check_period_id', false);

        $validator
            ->integer('command_id')
            ->requirePresence('command_id', 'create')
            ->greaterThan('command_id', 0, __('Please select a check command'))
            ->allowEmptyString('command_id', false);

        $validator
            ->integer('eventhandler_command_id')
            ->requirePresence('eventhandler_command_id', false)
            ->allowEmptyString('eventhandler_command_id', true);

        $validator
            ->integer('notify_period_id')
            ->requirePresence('notify_period_id', 'create')
            ->greaterThan('notify_period_id', 0, __('Please select a notify period'))
            ->allowEmptyString('notify_period_id', false);

        $validator
            ->boolean('notify_on_recovery')
            ->requirePresence('notify_on_recovery', 'create')
            ->allowEmptyString('notify_on_recovery', false)
            ->add('notify_on_recovery', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_warning')
            ->requirePresence('notify_on_warning', 'create')
            ->allowEmptyString('notify_on_warning', false)
            ->add('notify_on_warning', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_critical')
            ->requirePresence('notify_on_critical', 'create')
            ->allowEmptyString('notify_on_critical', false)
            ->add('notify_on_critical', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_unknown')
            ->requirePresence('notify_on_unknown', 'create')
            ->allowEmptyString('notify_on_unknown', false)
            ->add('notify_on_unknown', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_flapping')
            ->requirePresence('notify_on_flapping', 'create')
            ->allowEmptyString('notify_on_flapping', false)
            ->add('notify_on_flapping', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_downtime')
            ->requirePresence('notify_on_downtime', 'create')
            ->allowEmptyString('notify_on_downtime', false)
            ->add('notify_on_downtime', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('flap_detection_enabled')
            ->requirePresence('flap_detection_enabled', 'create')
            ->allowEmptyString('flap_detection_enabled', false);

        $validator
            ->boolean('flap_detection_on_ok')
            ->requirePresence('flap_detection_on_ok', 'create')
            ->allowEmptyString('flap_detection_on_ok', false)
            ->add('flap_detection_on_ok', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->boolean('flap_detection_on_warning')
            ->requirePresence('flap_detection_on_warning', 'create')
            ->allowEmptyString('flap_detection_on_warning', false)
            ->add('flap_detection_on_warning', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->boolean('flap_detection_on_critical')
            ->requirePresence('flap_detection_on_critical', 'create')
            ->allowEmptyString('flap_detection_on_critical', false)
            ->add('flap_detection_on_critical', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->boolean('flap_detection_on_unknown')
            ->requirePresence('flap_detection_on_unknown', 'create')
            ->allowEmptyString('flap_detection_on_unknown', false)
            ->add('flap_detection_on_unknown', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->numeric('low_flap_threshold')
            ->requirePresence('low_flap_threshold', 'create')
            ->allowEmptyString('low_flap_threshold', false);

        $validator
            ->numeric('high_flap_threshold')
            ->requirePresence('high_flap_threshold', 'create')
            ->allowEmptyString('high_flap_threshold', false);

        $validator
            ->boolean('process_performance_data')
            ->requirePresence('process_performance_data', false)
            ->allowEmptyString('process_performance_data', true);

        $validator
            ->boolean('passive_checks_enabled')
            ->requirePresence('passive_checks_enabled', 'create')
            ->allowEmptyString('passive_checks_enabled', false);

        $validator
            ->boolean('event_handler_enabled')
            ->requirePresence('event_handler_enabled', 'create')
            ->allowEmptyString('event_handler_enabled', false);

        $validator
            ->boolean('active_checks_enabled')
            ->requirePresence('active_checks_enabled', 'create')
            ->allowEmptyString('active_checks_enabled', false);

        $validator
            ->scalar('notes')
            ->requirePresence('notes', false)
            ->allowEmptyString('notes', true)
            ->maxLength('notes', 255);

        $validator
            ->scalar('tags')
            ->requirePresence('tags', false)
            ->allowEmptyString('tags', true)
            ->maxLength('tags', 255);

        $validator
            ->scalar('service_url')
            ->requirePresence('service_url', false)
            ->allowEmptyString('service_url', true)
            ->maxLength('service_url', 255);

        $validator
            ->allowEmptyString('customvariables', true)
            ->add('customvariables', 'custom', [
                'rule'    => [$this, 'checkMacroNames'], //\App\Lib\Traits\CustomValidationTrait
                'message' => _('Macro name needs to be unique')
            ]);

        $validator
            ->boolean('is_volatile')
            ->requirePresence('is_volatile', false)
            ->allowEmptyString('is_volatile', true);

        $validator
            ->boolean('freshness_checks_enabled')
            ->requirePresence('freshness_checks_enabled', false)
            ->allowEmptyString('freshness_checks_enabled', true);

        $validator
            ->integer('freshness_threshold')
            ->greaterThan('check_period_id', 0, __('This field cannot be 0'))
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
     * @param ServicetemplateFilter $ServicetemplateFilter
     * @param null|PaginateOMat $PaginateOMat
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getServicetemplatesIndex(ServicetemplateFilter $ServicetemplateFilter, $PaginateOMat = null, $MY_RIGHTS = []) {

        $query = $this->find('all')->disableHydration();
        $where = $ServicetemplateFilter->indexFilter();
        $where['Servicetemplates.servicetemplatetype_id'] = GENERIC_SERVICE;
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
    public function buildRules(RulesChecker $rules) {
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
                'Servicetemplatecommandargumentvalues' => [
                    'Commandarguments'
                ],
                'Servicetemplateeventcommandargumentvalues' => [
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

        return [
            'Servicetemplate' => $servicetemplate
        ];
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

}
