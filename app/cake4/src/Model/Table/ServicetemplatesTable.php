<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Servicetemplates Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $CheckPeriods
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $NotifyPeriods
 * @property \App\Model\Table\CommandsTable|\Cake\ORM\Association\BelongsTo $Commands
 * @property \App\Model\Table\EventhandlerCommandsTable|\Cake\ORM\Association\BelongsTo $EventhandlerCommands
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $Timeperiods
 * @property \App\Model\Table\ContactgroupsToServicetemplatesTable|\Cake\ORM\Association\HasMany $ContactgroupsToServicetemplates
 * @property \App\Model\Table\ContactsToServicetemplatesTable|\Cake\ORM\Association\HasMany $ContactsToServicetemplates
 * @property \App\Model\Table\DeletedServicesTable|\Cake\ORM\Association\HasMany $DeletedServices
 * @property \App\Model\Table\ServicetemplatecommandargumentvaluesTable|\Cake\ORM\Association\HasMany $Servicetemplatecommandargumentvalues
 * @property \App\Model\Table\ServicetemplateeventcommandargumentvaluesTable|\Cake\ORM\Association\HasMany $Servicetemplateeventcommandargumentvalues
 * @property \App\Model\Table\ServicetemplatesToServicegroupsTable|\Cake\ORM\Association\HasMany $ServicetemplatesToServicegroups
 * @property \App\Model\Table\ServicetemplatesToServicetemplategroupsTable|\Cake\ORM\Association\HasMany $ServicetemplatesToServicetemplategroups
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
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->notEmpty('uuid')
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('template_name')
            ->maxLength('template_name', 255)
            ->requirePresence('template_name', 'create')
            ->notEmpty('template_name');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->scalar('check_command_args')
            ->maxLength('check_command_args', 1000)
            ->requirePresence('check_command_args', 'create')
            ->notEmpty('check_command_args');

        $validator
            ->scalar('checkcommand_info')
            ->maxLength('checkcommand_info', 255)
            ->requirePresence('checkcommand_info', 'create')
            ->notEmpty('checkcommand_info');

        $validator
            ->integer('check_interval')
            ->requirePresence('check_interval', 'create')
            ->notEmpty('check_interval');

        $validator
            ->integer('retry_interval')
            ->requirePresence('retry_interval', 'create')
            ->notEmpty('retry_interval');

        $validator
            ->integer('max_check_attempts')
            ->requirePresence('max_check_attempts', 'create')
            ->notEmpty('max_check_attempts');

        $validator
            ->numeric('first_notification_delay')
            ->requirePresence('first_notification_delay', 'create')
            ->notEmpty('first_notification_delay');

        $validator
            ->numeric('notification_interval')
            ->requirePresence('notification_interval', 'create')
            ->notEmpty('notification_interval');

        $validator
            ->integer('notify_on_warning')
            ->requirePresence('notify_on_warning', 'create')
            ->notEmpty('notify_on_warning');

        $validator
            ->integer('notify_on_unknown')
            ->requirePresence('notify_on_unknown', 'create')
            ->notEmpty('notify_on_unknown');

        $validator
            ->integer('notify_on_critical')
            ->requirePresence('notify_on_critical', 'create')
            ->notEmpty('notify_on_critical');

        $validator
            ->integer('notify_on_recovery')
            ->requirePresence('notify_on_recovery', 'create')
            ->notEmpty('notify_on_recovery');

        $validator
            ->integer('notify_on_flapping')
            ->requirePresence('notify_on_flapping', 'create')
            ->notEmpty('notify_on_flapping');

        $validator
            ->integer('notify_on_downtime')
            ->requirePresence('notify_on_downtime', 'create')
            ->notEmpty('notify_on_downtime');

        $validator
            ->integer('flap_detection_enabled')
            ->requirePresence('flap_detection_enabled', 'create')
            ->notEmpty('flap_detection_enabled');

        $validator
            ->integer('flap_detection_on_ok')
            ->requirePresence('flap_detection_on_ok', 'create')
            ->notEmpty('flap_detection_on_ok');

        $validator
            ->integer('flap_detection_on_warning')
            ->requirePresence('flap_detection_on_warning', 'create')
            ->notEmpty('flap_detection_on_warning');

        $validator
            ->integer('flap_detection_on_unknown')
            ->requirePresence('flap_detection_on_unknown', 'create')
            ->notEmpty('flap_detection_on_unknown');

        $validator
            ->boolean('flap_detection_on_critical')
            ->requirePresence('flap_detection_on_critical', 'create')
            ->notEmpty('flap_detection_on_critical');

        $validator
            ->numeric('low_flap_threshold')
            ->requirePresence('low_flap_threshold', 'create')
            ->notEmpty('low_flap_threshold');

        $validator
            ->numeric('high_flap_threshold')
            ->requirePresence('high_flap_threshold', 'create')
            ->notEmpty('high_flap_threshold');

        $validator
            ->integer('process_performance_data')
            ->requirePresence('process_performance_data', 'create')
            ->notEmpty('process_performance_data');

        $validator
            ->integer('freshness_checks_enabled')
            ->requirePresence('freshness_checks_enabled', 'create')
            ->notEmpty('freshness_checks_enabled');

        $validator
            ->integer('freshness_threshold')
            ->allowEmpty('freshness_threshold');

        $validator
            ->integer('passive_checks_enabled')
            ->requirePresence('passive_checks_enabled', 'create')
            ->notEmpty('passive_checks_enabled');

        $validator
            ->integer('event_handler_enabled')
            ->requirePresence('event_handler_enabled', 'create')
            ->notEmpty('event_handler_enabled');

        $validator
            ->integer('active_checks_enabled')
            ->requirePresence('active_checks_enabled', 'create')
            ->notEmpty('active_checks_enabled');

        $validator
            ->integer('retain_status_information')
            ->requirePresence('retain_status_information', 'create')
            ->notEmpty('retain_status_information');

        $validator
            ->integer('retain_nonstatus_information')
            ->requirePresence('retain_nonstatus_information', 'create')
            ->notEmpty('retain_nonstatus_information');

        $validator
            ->integer('notifications_enabled')
            ->requirePresence('notifications_enabled', 'create')
            ->notEmpty('notifications_enabled');

        $validator
            ->scalar('notes')
            ->maxLength('notes', 255)
            ->requirePresence('notes', 'create')
            ->notEmpty('notes');

        $validator
            ->integer('priority')
            ->allowEmpty('priority');

        $validator
            ->scalar('tags')
            ->maxLength('tags', 1500)
            ->allowEmpty('tags');

        $validator
            ->scalar('service_url')
            ->maxLength('service_url', 255)
            ->allowEmpty('service_url');

        $validator
            ->boolean('is_volatile')
            ->requirePresence('is_volatile', 'create')
            ->notEmpty('is_volatile');

        $validator
            ->boolean('check_freshness')
            ->requirePresence('check_freshness', 'create')
            ->notEmpty('check_freshness');

        return $validator;
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
