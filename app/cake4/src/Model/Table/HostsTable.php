<?php

namespace App\Model\Table;

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
 * @property \App\Model\Table\DeletedServicesTable|\Cake\ORM\Association\HasMany $DeletedServices
 * @property \App\Model\Table\EventcorrelationsTable|\Cake\ORM\Association\HasMany $Eventcorrelations
 * @property \App\Model\Table\GrafanaDashboardsTable|\Cake\ORM\Association\HasMany $GrafanaDashboards
 * @property \App\Model\Table\GrafanaUserdashboardMetricsTable|\Cake\ORM\Association\HasMany $GrafanaUserdashboardMetrics
 * @property \App\Model\Table\HostcommandargumentvaluesTable|\Cake\ORM\Association\HasMany $Hostcommandargumentvalues
 * @property \App\Model\Table\HostsToAutoreportsTable|\Cake\ORM\Association\HasMany $HostsToAutoreports
 * @property \App\Model\Table\HostsToContainersTable|\Cake\ORM\Association\HasMany $HostsToContainers
 * @property \App\Model\Table\HostsToHostdependenciesTable|\Cake\ORM\Association\HasMany $HostsToHostdependencies
 * @property \App\Model\Table\HostsToHostescalationsTable|\Cake\ORM\Association\HasMany $HostsToHostescalations
 * @property \App\Model\Table\HostsToHostgroupsTable|\Cake\ORM\Association\HasMany $HostsToHostgroups
 * @property \App\Model\Table\HostsToParenthostsTable|\Cake\ORM\Association\HasMany $HostsToParenthosts
 * @property \App\Model\Table\IdoitHostsTable|\Cake\ORM\Association\HasMany $IdoitHosts
 * @property \App\Model\Table\InstantreportsToHostsTable|\Cake\ORM\Association\HasMany $InstantreportsToHosts
 * @property \App\Model\Table\LastUsedMkagentsTable|\Cake\ORM\Association\HasMany $LastUsedMkagents
 * @property \App\Model\Table\MkservicedataTable|\Cake\ORM\Association\HasMany $Mkservicedata
 * @property \App\Model\Table\MksnmpTable|\Cake\ORM\Association\HasMany $Mksnmp
 * @property \App\Model\Table\NagiosHostContactgroupsTable|\Cake\ORM\Association\HasMany $NagiosHostContactgroups
 * @property \App\Model\Table\NagiosHostContactsTable|\Cake\ORM\Association\HasMany $NagiosHostContacts
 * @property \App\Model\Table\NagiosHostParenthostsTable|\Cake\ORM\Association\HasMany $NagiosHostParenthosts
 * @property \App\Model\Table\NagiosHostsTable|\Cake\ORM\Association\HasMany $NagiosHosts
 * @property \App\Model\Table\ServicesTable|\Cake\ORM\Association\HasMany $Services
 * @property \App\Model\Table\ServicesToAutoreportsTable|\Cake\ORM\Association\HasMany $ServicesToAutoreports
 * @property \App\Model\Table\WidgetsTable|\Cake\ORM\Association\HasMany $Widgets
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
        $this->belongsTo('Hosttemplates', [
            'foreignKey' => 'hosttemplate_id',
            'joinType'   => 'INNER'
        ]);
        $this->belongsTo('Commands', [
            'foreignKey' => 'command_id'
        ]);
        $this->belongsTo('EventhandlerCommands', [
            'foreignKey' => 'eventhandler_command_id'
        ]);
        $this->belongsTo('Timeperiods', [
            'foreignKey' => 'timeperiod_id'
        ]);
        $this->belongsTo('CheckPeriods', [
            'foreignKey' => 'check_period_id'
        ]);
        $this->belongsTo('NotifyPeriods', [
            'foreignKey' => 'notify_period_id'
        ]);
        $this->belongsTo('Satellites', [
            'foreignKey' => 'satellite_id'
        ]);
        $this->hasMany('ContactgroupsToHosts', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('ContactsToHosts', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('DeletedHosts', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('DeletedServices', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('Eventcorrelations', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('GrafanaDashboards', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('GrafanaUserdashboardMetrics', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('Hostcommandargumentvalues', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('HostsToAutoreports', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('HostsToContainers', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('HostsToHostdependencies', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('HostsToHostescalations', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('HostsToHostgroups', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('HostsToParenthosts', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('IdoitHosts', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('InstantreportsToHosts', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('LastUsedMkagents', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('Mkservicedata', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('Mksnmp', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('NagiosHostContactgroups', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('NagiosHostContacts', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('NagiosHostParenthosts', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('NagiosHosts', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('Services', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('ServicesToAutoreports', [
            'foreignKey' => 'host_id'
        ]);
        $this->hasMany('Widgets', [
            'foreignKey' => 'host_id'
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
}
