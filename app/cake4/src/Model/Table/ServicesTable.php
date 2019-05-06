<?php

namespace App\Model\Table;

use App\Lib\Traits\PluginManagerTableTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Services Model
 *
 * @property \App\Model\Table\ServicetemplatesTable|\Cake\ORM\Association\BelongsTo $Servicetemplates
 * @property \App\Model\Table\HostsTable|\Cake\ORM\Association\BelongsTo $Hosts
 * @property |\Cake\ORM\Association\BelongsTo $Commands
 * @property |\Cake\ORM\Association\BelongsTo $EventhandlerCommands
 * @property |\Cake\ORM\Association\BelongsTo $NotifyPeriods
 * @property |\Cake\ORM\Association\BelongsTo $CheckPeriods
 * @property |\Cake\ORM\Association\HasMany $ContactgroupsToServices
 * @property |\Cake\ORM\Association\HasMany $ContactsToServices
 * @property |\Cake\ORM\Association\HasMany $Eventcorrelations
 * @property |\Cake\ORM\Association\HasMany $GrafanaUserdashboardMetrics
 * @property |\Cake\ORM\Association\HasMany $GraphgenTmplConfs
 * @property |\Cake\ORM\Association\HasMany $InstantreportsToServices
 * @property \MkModule\Model\Table\MkservicedataTable|\Cake\ORM\Association\HasMany $Mkservicedata
 * @property |\Cake\ORM\Association\HasMany $NagiosServiceContactgroups
 * @property |\Cake\ORM\Association\HasMany $NagiosServiceContacts
 * @property |\Cake\ORM\Association\HasMany $NagiosServiceParentservices
 * @property |\Cake\ORM\Association\HasMany $NagiosServices
 * @property \NewModule\Model\Table\ServicecommandargumentvaluesTable|\Cake\ORM\Association\HasMany $Servicecommandargumentvalues
 * @property |\Cake\ORM\Association\HasMany $Serviceeventcommandargumentvalues
 * @property |\Cake\ORM\Association\HasMany $ServicesToAutoreports
 * @property |\Cake\ORM\Association\HasMany $ServicesToServicedependencies
 * @property |\Cake\ORM\Association\HasMany $ServicesToServiceescalations
 * @property |\Cake\ORM\Association\HasMany $ServicesToServicegroups
 * @property |\Cake\ORM\Association\HasMany $Widgets
 *
 * @method \App\Model\Entity\Service get($primaryKey, $options = [])
 * @method \App\Model\Entity\Service newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Service[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Service|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Service saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Service patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Service[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Service findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServicesTable extends Table {

    use PluginManagerTableTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('services');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Servicetemplates', [
            'foreignKey' => 'servicetemplate_id',
            'joinType'   => 'INNER'
        ]);
        $this->belongsTo('Hosts', [
            'foreignKey' => 'host_id',
            'joinType'   => 'INNER'
        ]);

        $this->belongsToMany('Contactgroups', [
            'className'        => 'Contactgroups',
            'foreignKey'       => 'service_id',
            'targetForeignKey' => 'contactgroup_id',
            'joinTable'        => 'contactgroups_to_services',
            'saveStrategy'     => 'replace'
        ]);

        $this->belongsToMany('Contacts', [
            'className'        => 'Contacts',
            'foreignKey'       => 'service_id',
            'targetForeignKey' => 'contact_id',
            'joinTable'        => 'contacts_to_services',
            'saveStrategy'     => 'replace'
        ]);

        $this->belongsToMany('Servicegroups', [
            'className'        => 'Servicegroups',
            'foreignKey'       => 'service_id',
            'targetForeignKey' => 'servicegroup_id',
            'joinTable'        => 'services_to_servicegroups',
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
                'objecttype_id' => OBJECT_SERVICE
            ],
            'foreignKey'   => 'object_id',
            'saveStrategy' => 'replace'
        ])->setDependent(true);

        $this->hasMany('Servicecommandargumentvalues', [
            'saveStrategy' => 'replace'
        ])->setDependent(true);

        $this->hasMany('Serviceeventcommandargumentvalues', [
            'foreignKey'   => 'service_id',
            'saveStrategy' => 'replace'
        ]);

        /*
        $this->hasMany('ServicesToServicedependencies', [
            'foreignKey' => 'service_id'
        ]);
        $this->hasMany('ServicesToServiceescalations', [
            'foreignKey' => 'service_id'
        ]);
        */

        $this->hasMany('Widgets', [
            'foreignKey' => 'service_id'
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
            ->maxLength('name', 1500)
            ->allowEmptyString('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        $validator
            ->scalar('check_command_args')
            ->maxLength('check_command_args', 1000)
            ->requirePresence('check_command_args', 'create')
            ->allowEmptyString('check_command_args', false);

        $validator
            ->numeric('check_interval')
            ->allowEmptyString('check_interval');

        $validator
            ->numeric('retry_interval')
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
            ->integer('notify_on_warning')
            ->allowEmptyString('notify_on_warning');

        $validator
            ->integer('notify_on_unknown')
            ->allowEmptyString('notify_on_unknown');

        $validator
            ->integer('notify_on_critical')
            ->allowEmptyString('notify_on_critical');

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
            ->integer('is_volatile')
            ->allowEmptyString('is_volatile');

        $validator
            ->integer('flap_detection_enabled')
            ->allowEmptyString('flap_detection_enabled');

        $validator
            ->integer('flap_detection_on_ok')
            ->allowEmptyString('flap_detection_on_ok');

        $validator
            ->integer('flap_detection_on_warning')
            ->allowEmptyString('flap_detection_on_warning');

        $validator
            ->integer('flap_detection_on_unknown')
            ->allowEmptyString('flap_detection_on_unknown');

        $validator
            ->integer('flap_detection_on_critical')
            ->allowEmptyString('flap_detection_on_critical');

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
            ->allowEmptyString('own_contacts');

        $validator
            ->integer('own_contactgroups')
            ->allowEmptyString('own_contactgroups');

        $validator
            ->integer('own_customvariables')
            ->allowEmptyString('own_customvariables');

        $validator
            ->scalar('service_url')
            ->maxLength('service_url', 255)
            ->allowEmptyString('service_url');

        $validator
            ->integer('service_type')
            ->allowEmptyString('service_type', false);

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
        $rules->add($rules->existsIn(['servicetemplate_id'], 'Servicetemplates'));
        $rules->add($rules->existsIn(['host_id'], 'Hosts'));
        $rules->add($rules->existsIn(['command_id'], 'Commands'));
        $rules->add($rules->existsIn(['eventhandler_command_id'], 'EventhandlerCommands'));
        $rules->add($rules->existsIn(['notify_period_id'], 'NotifyPeriods'));
        $rules->add($rules->existsIn(['check_period_id'], 'CheckPeriods'));

        return $rules;
    }

    /**
     * @param int $servicetemplateId
     * @return array
     */
    public function getHostPrimaryContainerIdsByServicetemplateId($servicetemplateId) {
        $query = $this->find()
            ->select([
                'Services.id',
                'Hosts.id',
                'Hosts.container_id',
            ])
            ->contain([
                'Hosts'
            ])
            ->where([
                'Services.servicetemplate_id' => $servicetemplateId
            ])
            ->disableHydration()
            ->all();

        $query = $query->toArray();

        if (empty($query)) {
            return [];
        }

        $result = [];
        foreach ($query as $row) {
            $result[$row['id']] = (int)$row['host']['container_id'];
        }

        return $result;
    }

    /**
     * @param int $servicetemplateId
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getServicesWithHostForServicetemplateUsedBy($servicetemplateId, $MY_RIGHTS = [], $includeDisabled = false) {
        $where = [
            'Services.servicetemplate_id' => $servicetemplateId
        ];
        if ($includeDisabled === false) {
            $where['Services.disabled'] = 0;
        }

        $query = $this->find('all');
        $query->select([
            'Services.id',
            'Services.name',
            'Services.disabled',
        ])
            ->contain([
                'Hosts'            => function (Query $query) use ($MY_RIGHTS) {
                    $query->enableAutoFields(false)
                        ->select([
                            'Hosts.name',
                            'Hosts.id',
                            'Hosts.uuid',
                            'Hosts.address'
                        ])
                        ->innerJoinWith('HostsToContainersSharing', function (Query $q) use ($MY_RIGHTS) {
                            if (!empty($MY_RIGHTS)) {
                                return $q->where(['HostsToContainersSharing.id IN' => $MY_RIGHTS]);
                            }
                            return $q;
                        });

                    return $query;
                },
                'Servicetemplates' => function (Query $query) {
                    $query->enableAutoFields(false)
                        ->select([
                            'Servicetemplates.id',
                            'Servicetemplates.name',
                        ]);
                    return $query;
                }
            ])
            ->where($where)
            ->group([
                'Services.id'
            ])
            ->disableHydration();

        $result = $query->toArray();
        if (empty($result)) {
            return [];
        }

        return $result;
    }
}
