<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
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
            'foreignKey' => 'servicetemplate_id'
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
            ->greaterThan('eventhandler_command_id', 0, __('Please select a event handler'))
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
                'rule'    => [$this, 'checkNotificationOptionsHost'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_down')
            ->requirePresence('notify_on_down', 'create')
            ->allowEmptyString('notify_on_down', false)
            ->add('notify_on_down', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsHost'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_unreachable')
            ->requirePresence('notify_on_unreachable', 'create')
            ->allowEmptyString('notify_on_unreachable', false)
            ->add('notify_on_unreachable', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsHost'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_flapping')
            ->requirePresence('notify_on_flapping', 'create')
            ->allowEmptyString('notify_on_flapping', false)
            ->add('notify_on_flapping', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsHost'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_downtime')
            ->requirePresence('notify_on_downtime', 'create')
            ->allowEmptyString('notify_on_downtime', false)
            ->add('notify_on_downtime', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsHost'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('flap_detection_enabled')
            ->requirePresence('flap_detection_enabled', 'create')
            ->allowEmptyString('flap_detection_enabled', false);

        $validator
            ->boolean('flap_detection_on_up')
            ->requirePresence('flap_detection_on_up', 'create')
            ->allowEmptyString('flap_detection_on_up', false)
            ->add('flap_detection_on_up', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsHost'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->boolean('flap_detection_on_down')
            ->requirePresence('flap_detection_on_down', 'create')
            ->allowEmptyString('flap_detection_on_down', false)
            ->add('flap_detection_on_down', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsHost'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->boolean('flap_detection_on_unreachable')
            ->requirePresence('flap_detection_on_unreachable', 'create')
            ->allowEmptyString('flap_detection_on_unreachable', false)
            ->add('flap_detection_on_unreachable', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsHost'], //\App\Lib\Traits\CustomValidationTrait
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
            ->scalar('host_url')
            ->requirePresence('host_url', false)
            ->allowEmptyString('host_url', true)
            ->maxLength('host_url', 255);

        $validator
            ->add('contacts', 'custom', [
                'rule'    => [$this, 'atLeastOne'],
                'message' => __('You must specify at least one contact or contact group.')
            ]);

        $validator
            ->add('contactgroups', 'custom', [
                'rule'    => [$this, 'atLeastOne'],
                'message' => __('You must specify at least one contact or contact group.')
            ]);

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
            ->allowEmptyString('freshness_threshold');

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
}
