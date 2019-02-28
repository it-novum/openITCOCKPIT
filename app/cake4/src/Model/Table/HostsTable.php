<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Hosts Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\HosttemplatesTable|\Cake\ORM\Association\BelongsTo $Hosttemplates
 * @property \App\Model\Table\CommandsTable|\Cake\ORM\Association\BelongsTo $Commands
 * @property \App\Model\Table\EventhandlerCommandsTable|\Cake\ORM\Association\BelongsTo $EventhandlerCommands
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $Timeperiods
 * @property \App\Model\Table\CheckPeriodsTable|\Cake\ORM\Association\BelongsTo $CheckPeriods
 * @property \App\Model\Table\NotifyPeriodsTable|\Cake\ORM\Association\BelongsTo $NotifyPeriods
 * @property \App\Model\Table\SatellitesTable|\Cake\ORM\Association\BelongsTo $Satellites
 * @property \App\Model\Table\ContactgroupsToHostsTable|\Cake\ORM\Association\HasMany $ContactgroupsToHosts
 * @property \App\Model\Table\ContactsToHostsTable|\Cake\ORM\Association\HasMany $ContactsToHosts
 * @property \App\Model\Table\DeletedHostsTable|\Cake\ORM\Association\HasMany $DeletedHosts
 * @property \App\Model\Table\ServicesTable|\Cake\ORM\Association\HasMany $Services
 *
 * @method \App\Model\Entity\Host get($primaryKey, $options = [])
 * @method \App\Model\Entity\Host newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Host[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Host|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Host|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Host patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Host[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Host findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HostsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('hosts');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
        ]);

        $this->belongsToMany('HostsToContainersSharing', [
            'className'        => 'Containers',
            'joinTable'        => 'hosts_to_containers',
            'foreignKey'       => 'host_id',
            'targetForeignKey' => 'container_id'
        ]);

        $this->belongsTo('Hosttemplates', [
            'foreignKey' => 'hosttemplate_id',
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
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        $validator
            ->scalar('address')
            ->maxLength('address', 128)
            ->requirePresence('address', 'create')
            ->allowEmptyString('address', false);

        $validator
            ->integer('check_interval')
            ->allowEmptyString('check_interval');

        $validator
            ->integer('retry_interval')
            ->allowEmptyString('retry_interval');

        $validator
            ->integer('max_check_attempts')
            ->allowEmptyString('max_check_attempts');

        $validator
            ->numeric('first_notification_delay')
            ->allowEmptyString('first_notification_delay');

        $validator
            ->numeric('notification_interval')
            ->allowEmptyString('notification_interval');

        $validator
            ->integer('notify_on_down')
            ->allowEmptyString('notify_on_down');

        $validator
            ->integer('notify_on_unreachable')
            ->allowEmptyString('notify_on_unreachable');

        $validator
            ->integer('notify_on_recovery')
            ->allowEmptyString('notify_on_recovery');

        $validator
            ->integer('notify_on_flapping')
            ->allowEmptyString('notify_on_flapping');

        $validator
            ->integer('notify_on_downtime')
            ->allowEmptyString('notify_on_downtime');

        $validator
            ->integer('flap_detection_enabled')
            ->allowEmptyString('flap_detection_enabled');

        $validator
            ->integer('flap_detection_on_up')
            ->allowEmptyString('flap_detection_on_up');

        $validator
            ->integer('flap_detection_on_down')
            ->allowEmptyString('flap_detection_on_down');

        $validator
            ->integer('flap_detection_on_unreachable')
            ->allowEmptyString('flap_detection_on_unreachable');

        $validator
            ->numeric('low_flap_threshold')
            ->allowEmptyString('low_flap_threshold');

        $validator
            ->numeric('high_flap_threshold')
            ->allowEmptyString('high_flap_threshold');

        $validator
            ->integer('process_performance_data')
            ->allowEmptyString('process_performance_data');

        $validator
            ->integer('freshness_checks_enabled')
            ->allowEmptyString('freshness_checks_enabled');

        $validator
            ->integer('freshness_threshold')
            ->allowEmptyString('freshness_threshold');

        $validator
            ->integer('passive_checks_enabled')
            ->allowEmptyString('passive_checks_enabled');

        $validator
            ->integer('event_handler_enabled')
            ->allowEmptyString('event_handler_enabled');

        $validator
            ->integer('active_checks_enabled')
            ->allowEmptyString('active_checks_enabled');

        $validator
            ->integer('retain_status_information')
            ->allowEmptyString('retain_status_information');

        $validator
            ->integer('retain_nonstatus_information')
            ->allowEmptyString('retain_nonstatus_information');

        $validator
            ->integer('notifications_enabled')
            ->allowEmptyString('notifications_enabled');

        $validator
            ->scalar('notes')
            ->maxLength('notes', 255)
            ->allowEmptyString('notes');

        $validator
            ->integer('priority')
            ->allowEmptyString('priority');

        $validator
            ->scalar('tags')
            ->maxLength('tags', 255)
            ->allowEmptyString('tags');

        $validator
            ->integer('own_contacts')
            ->requirePresence('own_contacts', 'create')
            ->allowEmptyString('own_contacts', false);

        $validator
            ->integer('own_contactgroups')
            ->requirePresence('own_contactgroups', 'create')
            ->allowEmptyString('own_contactgroups', false);

        $validator
            ->integer('own_customvariables')
            ->requirePresence('own_customvariables', 'create')
            ->allowEmptyString('own_customvariables', false);

        $validator
            ->scalar('host_url')
            ->maxLength('host_url', 255)
            ->allowEmptyString('host_url');

        $validator
            ->integer('host_type')
            ->requirePresence('host_type', 'create')
            ->allowEmptyString('host_type', false);

        $validator
            ->integer('disabled')
            ->allowEmptyString('disabled');

        $validator
            ->integer('usage_flag')
            ->requirePresence('usage_flag', 'create')
            ->allowEmptyString('usage_flag', false);

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
        $rules->add($rules->existsIn(['container_id'], 'Containers'));
        $rules->add($rules->existsIn(['hosttemplate_id'], 'Hosttemplates'));
        $rules->add($rules->existsIn(['command_id'], 'Commands'));
        $rules->add($rules->existsIn(['eventhandler_command_id'], 'EventhandlerCommands'));
        $rules->add($rules->existsIn(['timeperiod_id'], 'Timeperiods'));
        $rules->add($rules->existsIn(['check_period_id'], 'CheckPeriods'));
        $rules->add($rules->existsIn(['notify_period_id'], 'NotifyPeriods'));
        $rules->add($rules->existsIn(['satellite_id'], 'Satellites'));

        return $rules;
    }

    /**
     * @param int $hosttemplateId
     * @return array
     */
    public function getHostPrimaryContainerIdsByHosttemplateId($hosttemplateId) {
        $query = $this->find()
            ->select([
                'Hosts.id',
                'Hosts.container_id',
                'Hosts.hosttemplate_id'
            ])
            ->where([
                'Hosts.hosttemplate_id' => $hosttemplateId
            ])
            ->disableHydration()
            ->all();

        $query = $query->toArray();

        if (empty($query)) {
            return [];
        }

        $result = [];
        foreach ($query as $row) {
            $result[$row['id']] = (int)$row['container_id'];
        }

        return $result;
    }

    /**
     * @param int $hosttemplateId
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getHostsForHosttemplateUsedBy($hosttemplateId, $MY_RIGHTS = []) {
        $query = $this->find('all');
        $query->select([
            'Hosts.id',
            'Hosts.container_id',
            'Hosts.uuid',
            'Hosts.name',
            'Hosts.address',
        ]);

        $query->where([
            'Hosts.hosttemplate_id' => $hosttemplateId
        ]);
        $query->innerJoinWith('HostsToContainersSharing', function (Query $q) use ($MY_RIGHTS) {
            if (!empty($MY_RIGHTS)) {
                return $q->where(['HostsToContainersSharing.id IN' => $MY_RIGHTS]);
            }
            return $q;
        });
        $query->contain('HostsToContainersSharing');
        $query->disableHydration();
        $query->group(['Hosts.id']);
        $query->order([
            'Hosts.name' => 'asc'
        ]);

        $result = $query->toArray();
        if (empty($result)) {
            return [];
        }

        return $result;
    }
}
