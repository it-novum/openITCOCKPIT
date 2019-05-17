<?php

namespace App\Model\Table;

use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\ServiceConditions;

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

    use CustomValidationTrait;
    use PaginationAndScrollIndexTrait;


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
        /*
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
        */

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
            ->requirePresence('host_id', 'create')
            ->integer('host_id')
            ->allowEmptyString('host_id', false);

        $validator
            ->requirePresence('servicetemplate_id', 'create')
            ->integer('servicetemplate_id')
            ->allowEmptyString('servicetemplate_id', false);

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', false)
            ->allowEmptyString('name', true);


        $validator
            ->integer('priority')
            ->requirePresence('priority', false)
            ->range('priority', [1, 5], __('This value must be between 1 and 5'))
            ->allowEmptyString('priority', true);

        $validator
            ->integer('max_check_attempts')
            ->requirePresence('max_check_attempts', false)
            ->greaterThanOrEqual('max_check_attempts', 1, __('This value need to be at least 1'))
            ->allowEmptyString('max_check_attempts', true);

        $validator
            ->numeric('notification_interval')
            ->requirePresence('notification_interval', false)
            ->greaterThanOrEqual('notification_interval', 0, __('This value need to be at least 0'))
            ->allowEmptyString('notification_interval', true);

        $validator
            ->integer('check_interval')
            ->requirePresence('check_interval', false)
            ->greaterThanOrEqual('check_interval', 1, __('This value need to be at least 1'))
            ->allowEmptyString('check_interval', true);

        $validator
            ->integer('retry_interval')
            ->requirePresence('retry_interval', false)
            ->greaterThanOrEqual('retry_interval', 1, __('This value need to be at least 1'))
            ->allowEmptyString('retry_interval', true);

        $validator
            ->integer('check_period_id')
            ->requirePresence('check_period_id', false)
            ->greaterThan('check_period_id', 0, __('Please select a check period'))
            ->allowEmptyString('check_period_id', true);

        $validator
            ->integer('command_id')
            ->requirePresence('command_id', false)
            ->greaterThan('command_id', 0, __('Please select a check command'))
            ->allowEmptyString('command_id', true);

        $validator
            ->integer('eventhandler_command_id')
            ->requirePresence('eventhandler_command_id', false)
            ->allowEmptyString('eventhandler_command_id', true);

        $validator
            ->integer('notify_period_id')
            ->requirePresence('notify_period_id', false)
            ->greaterThan('notify_period_id', 0, __('Please select a notify period'))
            ->allowEmptyString('notify_period_id', true);

        $validator
            ->boolean('notify_on_recovery')
            ->requirePresence('notify_on_recovery', false)
            ->allowEmptyString('notify_on_recovery', true)
            ->add('notify_on_recovery', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_warning')
            ->requirePresence('notify_on_warning', false)
            ->allowEmptyString('notify_on_warning', true)
            ->add('notify_on_warning', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_critical')
            ->requirePresence('notify_on_critical', false)
            ->allowEmptyString('notify_on_critical', true)
            ->add('notify_on_critical', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_unknown')
            ->requirePresence('notify_on_unknown', false)
            ->allowEmptyString('notify_on_unknown', true)
            ->add('notify_on_unknown', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_flapping')
            ->requirePresence('notify_on_flapping', false)
            ->allowEmptyString('notify_on_flapping', true)
            ->add('notify_on_flapping', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_downtime')
            ->requirePresence('notify_on_downtime', false)
            ->allowEmptyString('notify_on_downtime', true)
            ->add('notify_on_downtime', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('flap_detection_enabled')
            ->requirePresence('flap_detection_enabled', 'create')
            ->allowEmptyString('flap_detection_enabled', true);

        $validator
            ->allowEmptyString('flap_detection_on_ok', function ($context) {
                return $this->checkFlapDetectionOptionsService(null, $context);
            }, __('You must specify at least one flap detection option.'))
            ->add('flap_detection_on_ok', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->allowEmptyString('flap_detection_on_warning', function ($context) {
                return $this->checkFlapDetectionOptionsService(null, $context);
            }, __('You must specify at least one flap detection option.'))
            ->add('flap_detection_on_warning', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->allowEmptyString('flap_detection_on_critical', function ($context) {
                return $this->checkFlapDetectionOptionsService(null, $context);
            }, __('You must specify at least one flap detection option.'))
            ->add('flap_detection_on_critical', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->allowEmptyString('flap_detection_on_unknown', function ($context) {
                return $this->checkFlapDetectionOptionsService(null, $context);
            }, __('You must specify at least one flap detection option.'))
            ->add('flap_detection_on_unknown', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->numeric('low_flap_threshold')
            ->requirePresence('low_flap_threshold', false)
            ->allowEmptyString('low_flap_threshold', true);

        $validator
            ->numeric('high_flap_threshold')
            ->requirePresence('high_flap_threshold', false)
            ->allowEmptyString('high_flap_threshold', true);

        $validator
            ->boolean('process_performance_data')
            ->requirePresence('process_performance_data', false)
            ->allowEmptyString('process_performance_data', true);

        $validator
            ->boolean('passive_checks_enabled')
            ->requirePresence('passive_checks_enabled', false)
            ->allowEmptyString('passive_checks_enabled', true);

        $validator
            ->boolean('event_handler_enabled')
            ->requirePresence('event_handler_enabled', false)
            ->allowEmptyString('event_handler_enabled', true);

        $validator
            ->boolean('active_checks_enabled')
            ->requirePresence('active_checks_enabled', false)
            ->allowEmptyString('active_checks_enabled', true);

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
            ->allowEmptyString('freshness_threshold', true);

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
        $rules->add($rules->existsIn(['command_id'], 'CheckCommand'));
        $rules->add($rules->existsIn(['eventhandler_command_id'], 'CheckCommand'));
        $rules->add($rules->existsIn(['notify_period_id'], 'NotifyPeriod'));
        $rules->add($rules->existsIn(['check_period_id'], 'CheckPeriod'));

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

    /**
     * @param array $containerIds
     * @param string $type
     * @param string $index
     * @param array $where
     * @return array
     */
    public function getServicesByContainerId($containerIds = [], $type = 'all', $index = 'id', $where = []) {

        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }
        $containerIds = array_unique($containerIds);

        $_where = [
            'Hosts.disabled' => 0
        ];

        $where = Hash::merge($_where, $where);

        $query = $this->find();
        $query
            ->innerJoinWith('Hosts')
            ->innerJoinWith('Hosts.HostsToContainersSharing')
            ->innerJoinWith('Servicetemplates')
            ->select([
                'Services.' . $index,
                'servicename' => $query->newExpr('CONCAT(Hosts.name, "/", IF(Services.name IS NULL, Servicetemplates.name, Services.name))'),
                'Services.name',
                'Hosts.name'
            ]);

        $query->disableHydration();
        $query->group(['Services.id']);
        $query->order([
            'servicename' => 'asc'
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
            $list[$row[$index]] = $row['servicename'];
        }

        return $list;
    }

    /**
     * @param ServiceConditions $ServiceConditions
     * @param array|int $selected
     * @return array|null
     */
    public function getServicesForAngularCake4(ServiceConditions $ServiceConditions, $selected = []) {
        if (!is_array($selected)) {
            $selected = [$selected];
        }
        $selected = array_filter($selected);


        $where = $ServiceConditions->getConditions();

        if (!empty($selected)) {
            $where['NOT'] = [
                'Services.id IN' => $selected
            ];
        }

        $query = $this->find();
        $query
            ->innerJoinWith('Hosts')
            ->innerJoinWith('Hosts.HostsToContainersSharing')
            ->innerJoinWith('Servicetemplates')
            ->select([
                'servicename' => $query->newExpr('CONCAT(Hosts.name, "/", IF(Services.name IS NULL, Servicetemplates.name, Services.name))'),
                'Services.id',
                'Hosts.name'
            ])
            ->where(
                $where
            )
            ->order([
                'servicename' => 'asc'
            ])
            ->limit(ITN_AJAX_LIMIT)
            ->disableHydration()
            ->all();

        $servicesWithLimit = [];
        $selectedServices = [];
        $results = $this->emptyArrayIfNull($query->toArray());
        foreach ($results as $result) {
            $servicesWithLimit[$result['id']] = $result;
        }
        if (!empty($selected)) {
            $query = $this->find();
            $query
                ->innerJoinWith('Hosts')
                ->innerJoinWith('Hosts.HostsToContainersSharing')
                ->innerJoinWith('Servicetemplates')
                ->select([
                    'servicename' => $query->newExpr('CONCAT(Hosts.name, "/", IF(Services.name IS NULL, Servicetemplates.name, Services.name))'),
                    'Services.id',
                    'Hosts.name'
                ])
                ->where([
                    'Services.id IN' => $selected
                ])
                ->order([
                    'servicename' => 'asc'
                ])
                ->limit(ITN_AJAX_LIMIT)
                ->disableHydration()
                ->all();

            $results = $this->emptyArrayIfNull($query->toArray());
            foreach ($results as $result) {
                $selectedServices[$result['id']] = $result;
            }

        }
        $services = $servicesWithLimit + $selectedServices;
        $serviceIds = array_keys($services);

        array_multisort(
            array_column($services, 'servicename'), SORT_ASC, SORT_NATURAL, $services, $serviceIds
        );
        $services = array_combine($serviceIds, $services);
        return $services;
    }


    /**
     * @param ServiceConditions $ServiceConditions
     * @param array|int $selected
     * @return array|null
     */
    public function getServicesForAngular(ServiceConditions $ServiceConditions, $selected = []) {
        if (!is_array($selected)) {
            $selected = [$selected];
        }
        $selected = array_filter($selected);


        $where = $ServiceConditions->getConditions();

        if (!empty($selected)) {
            $where['NOT'] = [
                'Services.id IN' => $selected
            ];
        }

        $query = $this->find();
        $query
            ->innerJoinWith('Hosts')
            ->innerJoinWith('Hosts.HostsToContainersSharing')
            ->innerJoinWith('Servicetemplates')
            ->select([
                'Services.id',
                'Services.name',
                'servicename' => $query->newExpr('CONCAT(Hosts.name, "/", IF(Services.name IS NULL, Servicetemplates.name, Services.name))'),
                'Hosts.id',
                'Hosts.name',
                'Servicetemplates.name'
            ])
            ->where(
                $where
            )
            ->order([
                'servicename' => 'asc'
            ])
            ->limit(ITN_AJAX_LIMIT)
            ->disableHydration()
            ->all();

        $servicesWithLimit = [];
        $selectedServices = [];
        $results = $this->emptyArrayIfNull($query->toArray());
        foreach ($results as $result) {
            $servicesWithLimit[$result['id']] = $result;
        }
        if (!empty($selected)) {
            $query = $this->find();
            $query
                ->innerJoinWith('Hosts')
                ->innerJoinWith('Hosts.HostsToContainersSharing')
                ->innerJoinWith('Servicetemplates')
                ->select([
                    'servicename' => $query->newExpr('CONCAT(Hosts.name, "/", IF(Services.name IS NULL, Servicetemplates.name, Services.name))'),
                    'Services.id',
                    'Hosts.name'
                ])
                ->where([
                    'Services.id IN' => $selected
                ])
                ->order([
                    'servicename' => 'asc'
                ])
                ->limit(ITN_AJAX_LIMIT)
                ->disableHydration()
                ->all();

            $results = $this->emptyArrayIfNull($query->toArray());
            foreach ($results as $result) {
                $selectedServices[$result['id']] = $result;
            }

        }
        $services = $servicesWithLimit + $selectedServices;
        $serviceIds = array_keys($services);

        array_multisort(
            array_column($services, 'servicename'), SORT_ASC, SORT_NATURAL, $services, $serviceIds
        );
        $services = array_combine($serviceIds, $services);
        $serviceFormated = [];
        foreach ($services as $serviceId => $serviceData) {
            $serviceFormated[$serviceId] = [
                'Service' => [
                    'id'   => $serviceData['id'],
                    'name' => $serviceData['name']
                ],
                'Host'    => [
                    'id' => $serviceData['_matchingData']['Hosts']['id'],
                    'name' => $serviceData['_matchingData']['Hosts']['name']
                ],
                'Servicetemplate'    => [
                    'name' => $serviceData['_matchingData']['Servicetemplates']['name']
                ]
            ];
        }
        return $serviceFormated;
    }


    /**
     * @param array $dataToParse
     * @return array
     */
    public function resolveDataForChangelog($dataToParse = []) {
        $extDataForChangelog = [
            'Contact'         => [],
            'Contactgroup'    => [],
            'CheckPeriod'     => [],
            'NotifyPeriod'    => [],
            'CheckCommand'    => [],
            'Servicegroup'    => [],
            'Servicetemplate' => []
        ];


        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');
        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');


        if (!empty($dataToParse['Service']['contacts']['_ids'])) {
            foreach ($ContactsTable->getContactsAsList($dataToParse['Service']['contacts']['_ids']) as $contactId => $contactName) {
                $extDataForChangelog['Contact'][] = [
                    'id'   => $contactId,
                    'name' => $contactName
                ];
            }
        }

        if (!empty($dataToParse['Service']['contactgroups']['_ids'])) {
            foreach ($ContactgroupsTable->getContactgroupsAsList($dataToParse['Service']['contactgroups']['_ids']) as $contactgroupId => $contactgroupName) {
                $extDataForChangelog['Contactgroup'][] = [
                    'id'   => $contactgroupId,
                    'name' => $contactgroupName
                ];
            }
        }

        if (!empty($dataToParse['Service']['check_period_id'])) {
            foreach ($TimeperiodsTable->getTimeperiodsAsList($dataToParse['Service']['check_period_id']) as $timeperiodId => $timeperiodName) {
                $extDataForChangelog['CheckPeriod'] = [
                    'id'   => $timeperiodId,
                    'name' => $timeperiodName
                ];
            }
        }

        if (!empty($dataToParse['Service']['notify_period_id'])) {
            foreach ($TimeperiodsTable->getTimeperiodsAsList($dataToParse['Service']['notify_period_id']) as $timeperiodId => $timeperiodName) {
                $extDataForChangelog['NotifyPeriod'] = [
                    'id'   => $timeperiodId,
                    'name' => $timeperiodName
                ];
            }
        }

        if (!empty($dataToParse['Service']['command_id'])) {
            foreach ($CommandsTable->getCommandByIdAsList($dataToParse['Service']['command_id']) as $commandId => $commandName) {
                $extDataForChangelog['CheckCommand'] = [
                    'id'   => $commandId,
                    'name' => $commandName
                ];
            }
        }

        if (!empty($dataToParse['Service']['servicegroups']['_ids'])) {
            foreach ($ServicegroupsTable->getServicegroupsAsList($dataToParse['Service']['servicegroups']['_ids']) as $servicegroupId => $servicegroupName) {
                $extDataForChangelog['Servicegroup'][] = [
                    'id'   => $servicegroupId,
                    'name' => $servicegroupName
                ];
            }
        }

        if (!empty($dataToParse['Service']['servicetemplate_id'])) {
            foreach ($ServicetemplatesTable->getServicetemplatesAsList($dataToParse['Service']['servicetemplate_id']) as $servicetemplateId => $servicetemplateName) {
                $extDataForChangelog['Servicetemplate'][] = [
                    'id'   => $servicetemplateId,
                    'name' => $servicetemplateName
                ];
            }
        }

        return $extDataForChangelog;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Services.id' => $id]);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getServiceForEdit($id) {
        $query = $this->find()
            ->where([
                'Services.id' => $id
            ])
            ->contain([
                'Contactgroups',
                'Contacts',
                'Servicegroups',
                'Customvariables',
                'Servicecommandargumentvalues'      => [
                    'Commandarguments'
                ],
                'Serviceeventcommandargumentvalues' => [
                    'Commandarguments'
                ]
            ])
            ->disableHydration()
            ->first();

        $service = $query;
        $service['servicegroups'] = [
            '_ids' => Hash::extract($query, 'servicegroups.{n}.id')
        ];
        $service['contacts'] = [
            '_ids' => Hash::extract($query, 'contacts.{n}.id')
        ];
        $service['contactgroups'] = [
            '_ids' => Hash::extract($query, 'contactgroups.{n}.id')
        ];

        return [
            'Service' => $service
        ];
    }


}
