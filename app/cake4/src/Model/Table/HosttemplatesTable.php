<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Filter\HosttemplateFilter;

/**
 * Hosttemplates Model
 *
 * @property \App\Model\Table\CommandsTable|\Cake\ORM\Association\BelongsTo $Commands
 * @property \App\Model\Table\CommandsTable|\Cake\ORM\Association\BelongsTo $EventhandlerCommands
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $CheckPeriods
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $NotifyPeriods
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\ContactgroupsTable|\Cake\ORM\Association\HasMany $Contactgroups
 * @property \App\Model\Table\ContactsTable|\Cake\ORM\Association\HasMany $Contacts
 * @property \App\Model\Table\HostsTable|\Cake\ORM\Association\HasMany $Hosts
 * @property \App\Model\Table\HosttemplatecommandargumentvaluesTable|\Cake\ORM\Association\HasMany $Hosttemplatecommandargumentvalues
 *
 * @method \App\Model\Entity\Hosttemplate get($primaryKey, $options = [])
 * @method \App\Model\Entity\Hosttemplate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Hosttemplate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Hosttemplate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Hosttemplate|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Hosttemplate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Hosttemplate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Hosttemplate findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HosttemplatesTable extends Table {

    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('hosttemplates');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Contactgroups', [
            'className'        => 'Contactgroups',
            'foreignKey'       => 'hosttemplate_id',
            'targetForeignKey' => 'contactgroup_id',
            'joinTable'        => 'contactgroups_to_hosttemplates',
            'saveStrategy'     => 'replace'
        ]);

        $this->belongsToMany('Contacts', [
            'className'        => 'Contacts',
            'foreignKey'       => 'hosttemplate_id',
            'targetForeignKey' => 'contact_id',
            'joinTable'        => 'contacts_to_hosttemplates',
            'saveStrategy'     => 'replace'
        ]);

        /*
        $this->belongsToMany('Hostgroups', [
            'className'        => 'Hostgroups',
            'foreignKey'       => 'hosttemplate_id',
            'targetForeignKey' => 'hostgroup_id',
            'joinTable'        => 'hosttemplates_to_hostgroups',
            'saveStrategy'     => 'replace'
        ]);*/

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
                'objecttype_id' => OBJECT_HOSTTEMPLATE
            ],
            'foreignKey'   => 'object_id',
            'saveStrategy' => 'replace'
        ])->setDependent(true);

        $this->hasMany('Hosttemplatecommandargumentvalue', [
            'saveStrategy' => 'replace'
        ])->setDependent(true);

        $this->hasMany('Host', [
            'saveStrategy' => 'replace'
        ])->setDependent(true);

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
            ->requirePresence('description', 'create')
            ->allowEmptyString('description', false);

        $validator
            ->scalar('check_command_args')
            ->maxLength('check_command_args', 1000)
            ->requirePresence('check_command_args', 'create')
            ->allowEmptyString('check_command_args', false);

        $validator
            ->integer('check_interval')
            ->requirePresence('check_interval', 'create')
            ->allowEmptyString('check_interval', false);

        $validator
            ->integer('retry_interval')
            ->requirePresence('retry_interval', 'create')
            ->allowEmptyString('retry_interval', false);

        $validator
            ->integer('max_check_attempts')
            ->requirePresence('max_check_attempts', 'create')
            ->allowEmptyString('max_check_attempts', false);

        $validator
            ->numeric('first_notification_delay')
            ->requirePresence('first_notification_delay', 'create')
            ->allowEmptyString('first_notification_delay', false);

        $validator
            ->numeric('notification_interval')
            ->requirePresence('notification_interval', 'create')
            ->allowEmptyString('notification_interval', false);

        $validator
            ->integer('notify_on_down')
            ->requirePresence('notify_on_down', 'create')
            ->allowEmptyString('notify_on_down', false);

        $validator
            ->integer('notify_on_unreachable')
            ->requirePresence('notify_on_unreachable', 'create')
            ->allowEmptyString('notify_on_unreachable', false);

        $validator
            ->integer('notify_on_recovery')
            ->requirePresence('notify_on_recovery', 'create')
            ->allowEmptyString('notify_on_recovery', false);

        $validator
            ->integer('notify_on_flapping')
            ->requirePresence('notify_on_flapping', 'create')
            ->allowEmptyString('notify_on_flapping', false);

        $validator
            ->integer('notify_on_downtime')
            ->requirePresence('notify_on_downtime', 'create')
            ->allowEmptyString('notify_on_downtime', false);

        $validator
            ->integer('flap_detection_enabled')
            ->requirePresence('flap_detection_enabled', 'create')
            ->allowEmptyString('flap_detection_enabled', false);

        $validator
            ->integer('flap_detection_on_up')
            ->requirePresence('flap_detection_on_up', 'create')
            ->allowEmptyString('flap_detection_on_up', false);

        $validator
            ->integer('flap_detection_on_down')
            ->requirePresence('flap_detection_on_down', 'create')
            ->allowEmptyString('flap_detection_on_down', false);

        $validator
            ->integer('flap_detection_on_unreachable')
            ->requirePresence('flap_detection_on_unreachable', 'create')
            ->allowEmptyString('flap_detection_on_unreachable', false);

        $validator
            ->numeric('low_flap_threshold')
            ->requirePresence('low_flap_threshold', 'create')
            ->allowEmptyString('low_flap_threshold', false);

        $validator
            ->numeric('high_flap_threshold')
            ->requirePresence('high_flap_threshold', 'create')
            ->allowEmptyString('high_flap_threshold', false);

        $validator
            ->integer('process_performance_data')
            ->requirePresence('process_performance_data', 'create')
            ->allowEmptyString('process_performance_data', false);

        $validator
            ->integer('freshness_checks_enabled')
            ->requirePresence('freshness_checks_enabled', 'create')
            ->allowEmptyString('freshness_checks_enabled', false);

        $validator
            ->integer('freshness_threshold')
            ->allowEmptyString('freshness_threshold');

        $validator
            ->integer('passive_checks_enabled')
            ->requirePresence('passive_checks_enabled', 'create')
            ->allowEmptyString('passive_checks_enabled', false);

        $validator
            ->integer('event_handler_enabled')
            ->requirePresence('event_handler_enabled', 'create')
            ->allowEmptyString('event_handler_enabled', false);

        $validator
            ->integer('active_checks_enabled')
            ->requirePresence('active_checks_enabled', 'create')
            ->allowEmptyString('active_checks_enabled', false);

        $validator
            ->integer('retain_status_information')
            ->requirePresence('retain_status_information', 'create')
            ->allowEmptyString('retain_status_information', false);

        $validator
            ->integer('retain_nonstatus_information')
            ->requirePresence('retain_nonstatus_information', 'create')
            ->allowEmptyString('retain_nonstatus_information', false);

        $validator
            ->integer('notifications_enabled')
            ->requirePresence('notifications_enabled', 'create')
            ->allowEmptyString('notifications_enabled', false);

        $validator
            ->scalar('notes')
            ->maxLength('notes', 255)
            ->requirePresence('notes', 'create')
            ->allowEmptyString('notes', false);

        $validator
            ->integer('priority')
            ->requirePresence('priority', 'create')
            ->allowEmptyString('priority', false);

        $validator
            ->scalar('tags')
            ->maxLength('tags', 255)
            ->requirePresence('tags', 'create')
            ->allowEmptyString('tags', false);

        $validator
            ->scalar('host_url')
            ->maxLength('host_url', 255)
            ->allowEmptyString('host_url');

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

    /**
     * @param HosttemplateFilter $CommandsFilter
     * @param null $PaginateOMat
     * @return array
     */
    public function getHosttemplatesIndex(HosttemplateFilter $HosttemplateFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        $query = $this->find('all')->disableHydration();
        $where = $HosttemplateFilter->indexFilter();
        $where['Hosttemplate.hosttemplatetype_id'] = GENERIC_HOSTTEMPLATE;
        if (!empty($MY_RIGHTS)) {
            $where['Hosttemplates.container_id IN'] = $MY_RIGHTS;
        }

        $query->where();
        $query->order($HosttemplateFilter->getOrderForPaginator('Hosttemplates.name', 'asc'));

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
}
