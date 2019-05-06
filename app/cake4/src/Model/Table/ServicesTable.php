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
 * @property \App\Model\Table\CommandsTable|\Cake\ORM\Association\BelongsTo $Commands
 * @property \App\Model\Table\EventhandlerCommandsTable|\Cake\ORM\Association\BelongsTo $EventhandlerCommands
 * @property \App\Model\Table\NotifyPeriodsTable|\Cake\ORM\Association\BelongsTo $NotifyPeriods
 * @property \App\Model\Table\CheckPeriodsTable|\Cake\ORM\Association\BelongsTo $CheckPeriods
 * @property \App\Model\Table\ContactgroupsToServicesTable|\Cake\ORM\Association\HasMany $ContactgroupsToServices
 * @property \App\Model\Table\ContactsToServicesTable|\Cake\ORM\Association\HasMany $ContactsToServices
 * @property \App\Model\Table\EventcorrelationsTable|\Cake\ORM\Association\HasMany $Eventcorrelations
 * @property \App\Model\Table\GrafanaUserdashboardMetricsTable|\Cake\ORM\Association\HasMany $GrafanaUserdashboardMetrics
 * @property \App\Model\Table\GraphgenTmplConfsTable|\Cake\ORM\Association\HasMany $GraphgenTmplConfs
 * @property \App\Model\Table\InstantreportsToServicesTable|\Cake\ORM\Association\HasMany $InstantreportsToServices
 * @property \App\Model\Table\MkservicedataTable|\Cake\ORM\Association\HasMany $Mkservicedata
 * @property \App\Model\Table\NagiosServiceContactgroupsTable|\Cake\ORM\Association\HasMany $NagiosServiceContactgroups
 * @property \App\Model\Table\NagiosServiceContactsTable|\Cake\ORM\Association\HasMany $NagiosServiceContacts
 * @property \App\Model\Table\NagiosServiceParentservicesTable|\Cake\ORM\Association\HasMany $NagiosServiceParentservices
 * @property \App\Model\Table\NagiosServicesTable|\Cake\ORM\Association\HasMany $NagiosServices
 * @property \App\Model\Table\ServicecommandargumentvaluesTable|\Cake\ORM\Association\HasMany $Servicecommandargumentvalues
 * @property \App\Model\Table\ServiceeventcommandargumentvaluesTable|\Cake\ORM\Association\HasMany $Serviceeventcommandargumentvalues
 * @property \App\Model\Table\ServicesToAutoreportsTable|\Cake\ORM\Association\HasMany $ServicesToAutoreports
 * @property \App\Model\Table\ServicesToServicedependenciesTable|\Cake\ORM\Association\HasMany $ServicesToServicedependencies
 * @property \App\Model\Table\ServicesToServiceescalationsTable|\Cake\ORM\Association\HasMany $ServicesToServiceescalations
 * @property \App\Model\Table\ServicesToServicegroupsTable|\Cake\ORM\Association\HasMany $ServicesToServicegroups
 * @property \App\Model\Table\WidgetsTable|\Cake\ORM\Association\HasMany $Widgets
 *
 * @method \App\Model\Entity\Service get($primaryKey, $options = [])
 * @method \App\Model\Entity\Service newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Service[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Service|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Service|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
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

        $this->belongsTo('Hosts', [
            'foreignKey' => 'host_id',
            'joinType'   => 'INNER'
        ]);

        $this->belongsTo('Servicetemplates', [
            'foreignKey' => 'servicetemplate_id',
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
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->notEmpty('uuid')
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('name')
            ->maxLength('name', 1500)
            ->allowEmpty('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmpty('description');

        $validator
            ->scalar('check_command_args')
            ->maxLength('check_command_args', 1000)
            ->requirePresence('check_command_args', 'create')
            ->notEmpty('check_command_args');

        $validator
            ->numeric('check_interval')
            ->allowEmpty('check_interval');

        $validator
            ->numeric('retry_interval')
            ->allowEmpty('retry_interval');

        $validator
            ->integer('max_check_attempts')
            ->allowEmpty('max_check_attempts');

        $validator
            ->numeric('first_notification_delay')
            ->allowEmpty('first_notification_delay');

        $validator
            ->numeric('notification_interval')
            ->allowEmpty('notification_interval');

        $validator
            ->integer('notify_on_warning')
            ->allowEmpty('notify_on_warning');

        $validator
            ->integer('notify_on_unknown')
            ->allowEmpty('notify_on_unknown');

        $validator
            ->integer('notify_on_critical')
            ->allowEmpty('notify_on_critical');

        $validator
            ->integer('notify_on_recovery')
            ->allowEmpty('notify_on_recovery');

        $validator
            ->integer('notify_on_flapping')
            ->allowEmpty('notify_on_flapping');

        $validator
            ->integer('notify_on_downtime')
            ->allowEmpty('notify_on_downtime');

        $validator
            ->integer('is_volatile')
            ->allowEmpty('is_volatile');

        $validator
            ->integer('flap_detection_enabled')
            ->allowEmpty('flap_detection_enabled');

        $validator
            ->integer('flap_detection_on_ok')
            ->allowEmpty('flap_detection_on_ok');

        $validator
            ->integer('flap_detection_on_warning')
            ->allowEmpty('flap_detection_on_warning');

        $validator
            ->integer('flap_detection_on_unknown')
            ->allowEmpty('flap_detection_on_unknown');

        $validator
            ->integer('flap_detection_on_critical')
            ->allowEmpty('flap_detection_on_critical');

        $validator
            ->numeric('low_flap_threshold')
            ->allowEmpty('low_flap_threshold');

        $validator
            ->numeric('high_flap_threshold')
            ->allowEmpty('high_flap_threshold');

        $validator
            ->integer('process_performance_data')
            ->allowEmpty('process_performance_data');

        $validator
            ->integer('freshness_checks_enabled')
            ->allowEmpty('freshness_checks_enabled');

        $validator
            ->integer('freshness_threshold')
            ->allowEmpty('freshness_threshold');

        $validator
            ->integer('passive_checks_enabled')
            ->allowEmpty('passive_checks_enabled');

        $validator
            ->integer('event_handler_enabled')
            ->allowEmpty('event_handler_enabled');

        $validator
            ->integer('active_checks_enabled')
            ->allowEmpty('active_checks_enabled');

        $validator
            ->integer('notifications_enabled')
            ->allowEmpty('notifications_enabled');

        $validator
            ->scalar('notes')
            ->maxLength('notes', 255)
            ->allowEmpty('notes');

        $validator
            ->integer('priority')
            ->allowEmpty('priority');

        $validator
            ->scalar('tags')
            ->maxLength('tags', 255)
            ->allowEmpty('tags');

        $validator
            ->integer('own_contacts')
            ->allowEmpty('own_contacts');

        $validator
            ->integer('own_contactgroups')
            ->allowEmpty('own_contactgroups');

        $validator
            ->integer('own_customvariables')
            ->allowEmpty('own_customvariables');

        $validator
            ->scalar('service_url')
            ->maxLength('service_url', 255)
            ->allowEmpty('service_url');

        $validator
            ->integer('service_type')
            ->requirePresence('service_type', 'create')
            ->notEmpty('service_type');

        $validator
            ->integer('disabled')
            ->allowEmpty('disabled');

        $validator
            ->integer('usage_flag')
            ->requirePresence('usage_flag', 'create')
            ->notEmpty('usage_flag');

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
