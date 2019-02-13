<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Contacts Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $HostTimeperiods
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\HasMany $ContactsToContainers
 * @property \App\Model\Table\CommandsTable|\Cake\ORM\Association\HasMany $ContactsToHostcommands
 *
 * @method \App\Model\Entity\Contact get($primaryKey, $options = [])
 * @method \App\Model\Entity\Contact newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Contact[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Contact|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Contact|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Contact patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Contact[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Contact findOrCreate($search, callable $callback = null, $options = [])
 */
class ContactsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('contacts');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Containers', [
            'foreignKey' => 'contact_id',
            'joinTable'  => 'contacts_to_hostcommands'
        ]);
        $this->belongsToMany('HostCommands', [
            'className'        => 'Commands',
            'joinTable'        => 'contacts_to_hostcommands',
            'foreignKey'       => 'contact_id',
            'targetForeignKey' => 'command_id'
        ]);
        $this->belongsToMany('ServiceCommands', [
            'className'        => 'Commands',
            'joinTable'        => 'contacts_to_servicecommands',
            'foreignKey'       => 'contact_id',
            'targetForeignKey' => 'command_id',
        ]);
        $this->hasMany('Customvariables', [
            'conditions' => [
                'objecttype_id' => OBJECT_CONTACT
            ],
            'foreignKey' => 'object_id'
        ])->setDependent(true);


        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('HostTimeperiods', [
            'className'  => 'Timeperiods',
            'foreignKey' => 'host_timeperiod_id',
            'joinType'   => 'INNER'
        ]);
        $this->belongsTo('ServiceTimeperiods', [
            'className'  => 'Timeperiods',
            'foreignKey' => 'service_timeperiod_id',
            'joinType'   => 'INNER'
        ]);
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
            ->maxLength('name', 64)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->allowEmptyString('description', false);

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->allowEmptyString('email', false);

        $validator
            ->scalar('phone')
            ->maxLength('phone', 64)
            ->requirePresence('phone', 'create')
            ->allowEmptyString('phone', false);

        $validator
            ->integer('host_notifications_enabled')
            ->requirePresence('host_notifications_enabled', 'create')
            ->allowEmptyString('host_notifications_enabled', false);

        $validator
            ->integer('service_notifications_enabled')
            ->requirePresence('service_notifications_enabled', 'create')
            ->allowEmptyString('service_notifications_enabled', false);

        $validator
            ->integer('notify_service_recovery')
            ->requirePresence('notify_service_recovery', 'create')
            ->allowEmptyString('notify_service_recovery', false);

        $validator
            ->integer('notify_service_warning')
            ->requirePresence('notify_service_warning', 'create')
            ->allowEmptyString('notify_service_warning', false);

        $validator
            ->integer('notify_service_unknown')
            ->requirePresence('notify_service_unknown', 'create')
            ->allowEmptyString('notify_service_unknown', false);

        $validator
            ->integer('notify_service_critical')
            ->requirePresence('notify_service_critical', 'create')
            ->allowEmptyString('notify_service_critical', false);

        $validator
            ->integer('notify_service_flapping')
            ->requirePresence('notify_service_flapping', 'create')
            ->allowEmptyString('notify_service_flapping', false);

        $validator
            ->integer('notify_service_downtime')
            ->requirePresence('notify_service_downtime', 'create')
            ->allowEmptyString('notify_service_downtime', false);

        $validator
            ->integer('notify_host_recovery')
            ->requirePresence('notify_host_recovery', 'create')
            ->allowEmptyString('notify_host_recovery', false);

        $validator
            ->integer('notify_host_down')
            ->requirePresence('notify_host_down', 'create')
            ->allowEmptyString('notify_host_down', false);

        $validator
            ->integer('notify_host_unreachable')
            ->requirePresence('notify_host_unreachable', 'create')
            ->allowEmptyString('notify_host_unreachable', false);

        $validator
            ->integer('notify_host_flapping')
            ->requirePresence('notify_host_flapping', 'create')
            ->allowEmptyString('notify_host_flapping', false);

        $validator
            ->integer('notify_host_downtime')
            ->requirePresence('notify_host_downtime', 'create')
            ->allowEmptyString('notify_host_downtime', false);

        $validator
            ->integer('host_push_notifications_enabled')
            ->requirePresence('host_push_notifications_enabled', 'create')
            ->allowEmptyString('host_push_notifications_enabled', false);

        $validator
            ->integer('service_push_notifications_enabled')
            ->requirePresence('service_push_notifications_enabled', 'create')
            ->allowEmptyString('service_push_notifications_enabled', false);

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['uuid']));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['host_timeperiod_id'], 'HostTimeperiods'));
        $rules->add($rules->existsIn(['service_timeperiod_id'], 'ServiceTimeperiods'));

        return $rules;
    }
}
