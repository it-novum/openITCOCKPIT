<?php

namespace App\Model\Table;

use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use App\Model\Entity\Service;
use App\Model\Entity\Servicedependency;
use App\Model\Entity\Serviceescalation;
use Cake\Database\Expression\Comparison;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\FileDebugger;
use itnovum\openITCOCKPIT\Core\KeyValueStore;
use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Database\PaginateOMat;

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
    public function initialize(array $config): void {
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

        $this->hasMany('ServiceescalationsServiceMemberships', [
            'foreignKey' => 'service_id'
        ]);

        $this->hasMany('ServicedependenciesServiceMemberships', [
            'foreignKey' => 'service_id'
        ]);

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
    public function validationDefault(Validator $validator): Validator {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->allowEmptyString('uuid', null, false)
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->requirePresence('host_id', 'create')
            ->integer('host_id')
            ->allowEmptyString('host_id', null, false);

        $validator
            ->requirePresence('servicetemplate_id', 'create')
            ->integer('servicetemplate_id')
            ->allowEmptyString('servicetemplate_id', null, false);

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', false)
            ->allowEmptyString('name', null, true);


        $validator
            ->integer('priority')
            ->requirePresence('priority', false)
            ->range('priority', [1, 5], __('This value must be between 1 and 5'))
            ->allowEmptyString('priority', null, true);

        $validator
            ->integer('max_check_attempts')
            ->requirePresence('max_check_attempts', false)
            ->greaterThanOrEqual('max_check_attempts', 1, __('This value need to be at least 1'))
            ->allowEmptyString('max_check_attempts', null, true);

        $validator
            ->numeric('notification_interval')
            ->requirePresence('notification_interval', false)
            ->greaterThanOrEqual('notification_interval', 0, __('This value need to be at least 0'))
            ->allowEmptyString('notification_interval', null, true);

        $validator
            ->numeric('check_interval')
            ->requirePresence('check_interval', false)
            ->greaterThanOrEqual('check_interval', 1, __('This value need to be at least 1'))
            ->allowEmptyString('check_interval', null, true);

        $validator
            ->numeric('retry_interval')
            ->requirePresence('retry_interval', false)
            ->greaterThanOrEqual('retry_interval', 1, __('This value need to be at least 1'))
            ->allowEmptyString('retry_interval', null, true);

        $validator
            ->integer('check_period_id')
            ->requirePresence('check_period_id', false)
            ->greaterThan('check_period_id', 0, __('Please select a check period'))
            ->allowEmptyString('check_period_id', null, true);

        $validator
            ->integer('command_id')
            ->requirePresence('command_id', false)
            ->greaterThan('command_id', 0, __('Please select a check command'))
            ->allowEmptyString('command_id', null, true);

        $validator
            ->integer('eventhandler_command_id')
            ->requirePresence('eventhandler_command_id', false)
            ->allowEmptyString('eventhandler_command_id', null, true);

        $validator
            ->integer('notify_period_id')
            ->requirePresence('notify_period_id', false)
            ->greaterThan('notify_period_id', 0, __('Please select a notify period'))
            ->allowEmptyString('notify_period_id', null, true);

        $validator
            ->boolean('notify_on_recovery')
            ->requirePresence('notify_on_recovery', false)
            ->allowEmptyString('notify_on_recovery', null, true)
            ->add('notify_on_recovery', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_warning')
            ->requirePresence('notify_on_warning', false)
            ->allowEmptyString('notify_on_warning', null, true)
            ->add('notify_on_warning', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_critical')
            ->requirePresence('notify_on_critical', false)
            ->allowEmptyString('notify_on_critical', null, true)
            ->add('notify_on_critical', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_unknown')
            ->requirePresence('notify_on_unknown', false)
            ->allowEmptyString('notify_on_unknown', null, true)
            ->add('notify_on_unknown', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_flapping')
            ->requirePresence('notify_on_flapping', false)
            ->allowEmptyString('notify_on_flapping', null, true)
            ->add('notify_on_flapping', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('notify_on_downtime')
            ->requirePresence('notify_on_downtime', false)
            ->allowEmptyString('notify_on_downtime', null, true)
            ->add('notify_on_downtime', 'custom', [
                'rule'    => [$this, 'checkNotificationOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one notification option.')
            ]);

        $validator
            ->boolean('flap_detection_enabled')
            ->requirePresence('flap_detection_enabled', 'create')
            ->allowEmptyString('flap_detection_enabled', null, true);

        $validator
            ->allowEmptyString('flap_detection_on_ok', __('You must specify at least one flap detection option.'), function ($context) {
                return $this->checkFlapDetectionOptionsService(null, $context);
            })
            ->add('flap_detection_on_ok', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->allowEmptyString('flap_detection_on_warning', __('You must specify at least one flap detection option.'), function ($context) {
                return $this->checkFlapDetectionOptionsService(null, $context);
            })
            ->add('flap_detection_on_warning', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->allowEmptyString('flap_detection_on_critical', __('You must specify at least one flap detection option.'), function ($context) {
                return $this->checkFlapDetectionOptionsService(null, $context);
            })
            ->add('flap_detection_on_critical', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->allowEmptyString('flap_detection_on_unknown', __('You must specify at least one flap detection option.'), function ($context) {
                return $this->checkFlapDetectionOptionsService(null, $context);
            })
            ->add('flap_detection_on_unknown', 'custom', [
                'rule'    => [$this, 'checkFlapDetectionOptionsService'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('You must specify at least one flap detection option.')
            ]);

        $validator
            ->numeric('low_flap_threshold')
            ->requirePresence('low_flap_threshold', false)
            ->allowEmptyString('low_flap_threshold', null, true);

        $validator
            ->numeric('high_flap_threshold')
            ->requirePresence('high_flap_threshold', false)
            ->allowEmptyString('high_flap_threshold', null, true);

        $validator
            ->boolean('process_performance_data')
            ->requirePresence('process_performance_data', false)
            ->allowEmptyString('process_performance_data', null, true);

        $validator
            ->boolean('passive_checks_enabled')
            ->requirePresence('passive_checks_enabled', false)
            ->allowEmptyString('passive_checks_enabled', null, true);

        $validator
            ->boolean('event_handler_enabled')
            ->requirePresence('event_handler_enabled', false)
            ->allowEmptyString('event_handler_enabled', null, true);

        $validator
            ->boolean('active_checks_enabled')
            ->requirePresence('active_checks_enabled', false)
            ->allowEmptyString('active_checks_enabled', null, true);

        $validator
            ->scalar('notes')
            ->requirePresence('notes', false)
            ->allowEmptyString('notes', null, true)
            ->maxLength('notes', 255);

        $validator
            ->scalar('tags')
            ->requirePresence('tags', false)
            ->allowEmptyString('tags', null, true)
            ->maxLength('tags', 255);

        $validator
            ->scalar('service_url')
            ->requirePresence('service_url', false)
            ->allowEmptyString('service_url', null, true)
            ->maxLength('service_url', 255);

        $validator
            ->allowEmptyString('customvariables', null, true)
            ->add('customvariables', 'custom', [
                'rule'    => [$this, 'checkMacroNames'], //\App\Lib\Traits\CustomValidationTrait
                'message' => _('Macro name needs to be unique')
            ]);

        $validator
            ->boolean('is_volatile')
            ->requirePresence('is_volatile', false)
            ->allowEmptyString('is_volatile', null, true);

        $validator
            ->boolean('freshness_checks_enabled')
            ->requirePresence('freshness_checks_enabled', false)
            ->allowEmptyString('freshness_checks_enabled', null, true);

        $validator
            ->integer('freshness_threshold')
            ->greaterThan('check_period_id', 0, __('This field cannot be 0'))
            ->allowEmptyString('freshness_threshold', null, true);

        return $validator;

    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker {
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

        $having = null;
        if (isset($where['servicename LIKE'])) {
            $having = [
                'servicename LIKE' => $where['servicename LIKE']
            ];
            unset($where['servicename LIKE']);
        }

        $query = $this->find();
        $query
            ->innerJoinWith('Hosts')
            ->innerJoinWith('Hosts.HostsToContainersSharing', function (Query $q) use ($ServiceConditions) {
                return $q->where([
                    'HostsToContainersSharing.id IN ' => $ServiceConditions->getContainerIds()
                ]);
            })
            ->innerJoinWith('Servicetemplates')
            ->select([
                'servicename' => $query->newExpr('CONCAT(Hosts.name, "/", IF(Services.name IS NULL, Servicetemplates.name, Services.name))'),
                'Services.id',
                'Hosts.name'
            ])
            ->where(
                $where
            );
        if (!empty($having)) {
            $query->having($having);
        }
        $query->order([
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

        $having = null;
        if (isset($where['servicename LIKE'])) {
            $having = [
                'servicename LIKE' => $where['servicename LIKE']
            ];
            unset($where['servicename LIKE']);
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
            ->limit(ITN_AJAX_LIMIT);

        if (!empty($having)) {
            $query->having($having);
        }

        $query
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
                    'Services.id',
                    'Services.name',
                    'servicename' => $query->newExpr('CONCAT(Hosts.name, "/", IF(Services.name IS NULL, Servicetemplates.name, Services.name))'),
                    'Hosts.id',
                    'Hosts.name',
                    'Servicetemplates.name'
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
                'Service'         => [
                    'id'   => $serviceData['id'],
                    'name' => $serviceData['name']
                ],
                'Host'            => [
                    'id'   => $serviceData['_matchingData']['Hosts']['id'],
                    'name' => $serviceData['_matchingData']['Hosts']['name']
                ],
                'Servicetemplate' => [
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

    /**
     * @param int $id
     * @param bool $enableHydration
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getServiceById($id, $enableHydration = true) {
        $query = $this->find()
            ->where([
                'Services.id' => $id
            ])
            ->contain([
                'Hosts' => [
                    'HostsToContainersSharing'
                ],
                'Servicetemplates'

            ])
            ->enableHydration($enableHydration)
            ->first();
        return $query;
    }

    /**
     * @param $id
     * @return array|Service|null
     */
    public function getServiceByIdForPermissionsCheck($id) {
        $query = $this->find()
            ->select([
                'Services.id',
                'Services.name',
                'Services.uuid',
                'Services.servicetemplate_id',
            ])
            ->where([
                'Services.id' => $id
            ])
            ->contain([
                'Hosts' => function (Query $query) {
                    $query->select([
                        'Hosts.id',
                        'Hosts.uuid',
                        'Hosts.container_id'
                    ])
                        ->contain([
                            'HostsToContainersSharing'
                        ]);
                    return $query;
                }
            ])
            ->first();

        return $query;
    }

    /**
     * @param int $id
     * @return array|\Cake\Datasource\EntityInterface
     * @throws RecordNotFoundException
     */
    public function getServiceByIdForExternalCommand($id) {
        return $this->find()
            ->select([
                'Services.id',
                'Services.uuid',
            ])
            ->where([
                'Services.id' => $id
            ])
            ->contain([
                'Hosts' => function (Query $query) {
                    $query->select([
                        'Hosts.id',
                        'Hosts.uuid',
                        'Hosts.satellite_id'
                    ]);
                    return $query;
                }
            ])
            ->firstOrFail();
    }

    /**
     * @param $id
     * @return array|Service|null
     */
    public function getServiceByIdWithHostAndServicetemplate($id) {
        $query = $this->find()
            ->select([
                'Services.id',
                'Services.name',
                'Services.uuid',
                'Services.servicetemplate_id',
                'Services.service_url',
            ])
            ->where([
                'Services.id' => $id
            ])
            ->contain([
                'Hosts'            => function (Query $query) {
                    $query->select([
                        'Hosts.id',
                        'Hosts.uuid',
                        'Hosts.container_id',
                        'Hosts.name',
                        'Hosts.address'
                    ])
                        ->contain([
                            'HostsToContainersSharing'
                        ]);
                    return $query;
                },
                'Servicetemplates' => function (Query $query) {
                    $query->select([
                        'Servicetemplates.id',
                        'Servicetemplates.uuid',
                        'Servicetemplates.name',
                        'Servicetemplates.service_url'
                    ]);
                    return $query;
                }
            ])
            ->first();

        return $query;
    }

    /**
     * @param $hostId
     * @return array
     */
    public function getServicesForExportByHostId($hostId) {
        $where = [
            'Services.host_id'  => $hostId,
            'Services.disabled' => 0
        ];

        $query = $this->find()
            ->where($where)
            ->contain([
                //'Servicetemplates'                  =>
                //    function (Query $q) {
                //        return $q->enableAutoFields(false)->select(['id', 'uuid', 'check_interval', 'command_id']);
                //    },
                'Contactgroups'                     =>
                    function (Query $q) {
                        return $q->enableAutoFields(false)->select(['id', 'uuid']);
                    },
                'Contacts'                          =>
                    function (Query $q) {
                        return $q->enableAutoFields(false)->select(['id', 'uuid']);
                    },
                'Servicegroups'                     =>
                    function (Query $q) {
                        return $q->enableAutoFields(false)->select(['id', 'uuid']);
                    },
                'Customvariables',
                'Servicecommandargumentvalues'      => [
                    'Commandarguments'
                ],
                'Serviceeventcommandargumentvalues' => [
                    'Commandarguments'
                ]
            ])
            ->all();

        return $this->emptyArrayIfNull($query);
    }

    /**
     * @param array $ids
     * @param array $containerIds
     * @return array
     */
    public function getServicesForCopy($ids, $containerIds = []) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $query = $this->find()
            ->select([
                'Services.id',
                'Services.name',
                'Services.description',
                'Services.command_id',
                'Services.active_checks_enabled'
            ])
            ->contain([
                'Servicetemplates' => function (Query $q) {
                    return $q->disableAutoFields()
                        ->select([
                            'Servicetemplates.id',
                            'Servicetemplates.name',
                            'Servicetemplates.description',
                            'Servicetemplates.command_id',
                            'Servicetemplates.active_checks_enabled'
                        ])
                        ->contain([
                            'Servicetemplatecommandargumentvalues' => [
                                'Commandarguments'
                            ]
                        ]);
                },
                'Hosts'            => function (Query $q) {
                    return $q->disableAutoFields()
                        ->select([
                            'Hosts.id',
                            'Hosts.name'
                        ]);
                },

                'Servicecommandargumentvalues' => [
                    'Commandarguments'
                ]
            ]);

        $where = [
            'Services.id IN'               => $ids,
            'Services.service_type NOT IN' => [MK_SERVICE]
        ];

        if (!empty($containerIds)) {
            $where['Servicetemplates.container_id IN'] = $containerIds;
        }

        $query
            ->where($where)
            ->order(['Services.id' => 'asc'])
            ->disableHydration()
            ->all();
        $query = $query->toArray();
        if ($query === null) {
            return [];
        }

        $result = [];

        $serviceFields = [
            'id',
            'name',
            'description',
            'command_id',
            'active_checks_enabled'
        ];

        foreach ($query as $service) {
            foreach ($serviceFields as $serviceField) {
                if ($service[$serviceField] === null || $service[$serviceField] === '') {
                    $service[$serviceField] = $service['servicetemplate'][$serviceField];
                }

                //Duplicate the name for front end to display the original service name
                $service['_name'] = $service['name'];
            }

            //Compare service command arguments
            $servicecommandargumentvalues = $service['servicecommandargumentvalues'];
            if (empty($_servicecommandargumentvalues)) {
                $servicecommandargumentvalues = $service['servicetemplate']['servicetemplatecommandargumentvalues'];
            }

            if (!empty($servicecommandargumentvalues)) {
                //Remove ids for front end
                foreach ($servicecommandargumentvalues as $index => $servicecommandargumentvalue) {
                    unset($servicecommandargumentvalues[$index]['id']);

                    if (isset($servicecommandargumentvalues[$index]['service_id'])) {
                        unset($servicecommandargumentvalues[$index]['service_id']);
                    }

                    if (isset($servicecommandargumentvalues[$index]['servicetemplate_id'])) {
                        unset($servicecommandargumentvalues[$index]['servicetemplate_id']);
                    }
                }
            }

            $service['servicecommandargumentvalues'] = $servicecommandargumentvalues;

            $result[] = $service;
        }

        return $result;

    }

    /**
     * @param Service $service
     * @param User $User
     * @return bool
     */
    public function __delete(Service $service, User $User) {
        $servicename = $service->get('name');

        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        if ($servicename === null || $servicename === '') {
            $servicetemplate = $ServicetemplatesTable->get($service->get('servicetemplate_id'));
            $servicename = $servicetemplate->get('name');
        }

        $servicedependencies = $service->get('servicedependencies_service_memberships');
        $servicedependenciesToDelete = [];

        if (!empty($servicedependencies)) {
            /** @var $ServicedependenciesTable ServicedependenciesTable */
            $ServicedependenciesTable = TableRegistry::getTableLocator()->get('Servicedependencies');
            /** @var  $servicedependency Servicedependency */
            foreach ($servicedependencies as $servicedependency) {
                $servicedependencyId = $servicedependency->get('servicedependency_id');
                $servicedependencyIsBroken = $ServicedependenciesTable->isServicedependencyBroken(
                    $servicedependencyId,
                    $service->get('id')
                );
                if ($servicedependencyIsBroken === true) {
                    $servicedependenciesToDelete[] = $servicedependency;
                }
            }
        }

        $serviceescalations = $service->get('serviceescalations_service_memberships');
        $serviceescalationsToDelete = [];
        if (!empty($serviceescalations)) {
            /** @var $ServiceescalationsTable ServiceescalationsTable */
            $ServiceescalationsTable = TableRegistry::getTableLocator()->get('Serviceescalations');
            /** @var $serviceescalation Serviceescalation */
            foreach ($serviceescalations as $serviceescalation) {
                $serviceescalationId = $serviceescalation->get('serviceescalation_id');
                $serviceescalationIsBroken = $ServiceescalationsTable->isServiceescalationBroken(
                    $serviceescalationId,
                    $service->get('id')
                );
                if ($serviceescalationIsBroken === true) {
                    $serviceescalationsToDelete[] = $serviceescalation;
                }
            }
        }

        if (!$this->delete($service)) {
            return false;
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $DocumentationsTable DocumentationsTable */
        $DocumentationsTable = TableRegistry::getTableLocator()->get('Documentations');
        /** @var $DeletedServicesTable DeletedServicesTable */
        $DeletedServicesTable = TableRegistry::getTableLocator()->get('DeletedServices');
        /** @var $ChangelogsTable ChangelogsTable */
        $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

        $host = $HostsTable->get($service->get('host_id'));

        $changelog_data = $ChangelogsTable->parseDataForChangelog(
            'delete',
            'services',
            $service->get('id'),
            OBJECT_SERVICE,
            $host->get('container_id'),
            $User->getId(),
            $host->get('name') . '/' . $servicename,
            [
                'Service' => $service->toArray()
            ]
        );

        if ($changelog_data) {
            $ChangelogsTable->write($changelog_data);
        }

        if ($DocumentationsTable->existsByUuid($service->get('uuid'))) {
            $DocumentationsTable->delete($DocumentationsTable->getDocumentationByUuid($service->get('uuid')));
        }

        $this->_clenupServiceEscalationAndDependency($servicedependenciesToDelete, $serviceescalationsToDelete);

        //Save service to DeletedServicesTable
        $data = $DeletedServicesTable->newEntity([
            'uuid'               => $service->get('uuid'),
            'host_uuid'          => $host->get('uuid'),
            'servicetemplate_id' => $service->get('servicetemplate_id'),
            'host_id'            => $service->get('host_id'),
            'name'               => $servicename,
            'description'        => $service->get('description'),
            'deleted_perfdata'   => 0
        ]);
        $DeletedServicesTable->save($data);

        // @todo implement this in cake4
        //Service::_clenupServiceEscalationDependencyAndGroup($service);

        return true;
    }

    /**
     * @param string $uuid
     * @param bool $enableHydration
     * @return array|\Cake\Datasource\EntityInterface
     */
    public function getServiceByUuid($uuid, $enableHydration = true) {
        $query = $this->find()
            ->where([
                'Services.uuid' => $uuid
            ])
            ->contain([
                'Hosts' => [
                    'HostsToContainersSharing'
                ],
                'Servicetemplates'

            ])
            ->enableHydration($enableHydration)
            ->firstOrFail();
        return $query;
    }

    public function getServiceUuidsOfHostByHostId($hostId) {
        $query = $this->find('list', [
            'keyField'   => 'id',
            'valueField' => 'uuid'
        ])
            ->where([
                'Services.host_id' => $hostId
            ])
            ->disableHydration();
        return $query->toArray();
    }

    /**
     * @param ServiceConditions $ServiceConditions
     * @param null|PaginateOMat $PaginateOMat
     * @return array
     */
    public function getServicesForDisabled(ServiceConditions $ServiceConditions, $PaginateOMat = null) {
        $where = $ServiceConditions->getConditions();
        $where['Services.disabled'] = 1;

        $having = null;
        if (isset($where['servicename LIKE'])) {
            $having = [
                'servicename LIKE' => $where['servicename LIKE']
            ];
            unset($where['servicename LIKE']);
        }


        if ($ServiceConditions->getHostId()) {
            $where['Services.host_id'] = $ServiceConditions->getHostId();
        }

        $query = $this->find();
        $query
            ->select([
                'Services.id',
                'Services.uuid',
                'Services.name',
                'Services.host_id',
                'Services.disabled',
                'servicename' => $query->newExpr('IF((Services.name IS NULL OR Services.name=""), Servicetemplates.name, Services.name)'),

                'Servicetemplates.id',
                'Servicetemplates.uuid',
                'Servicetemplates.name',

                'Hosts.name',
                'Hosts.id',
                'Hosts.uuid',
                'Hosts.description',
                'Hosts.address',
                'Hosts.disabled',
            ])
            ->innerJoinWith('Hosts')
            ->innerJoinWith('Hosts.HostsToContainersSharing', function (Query $q) use ($ServiceConditions) {
                if (!empty($ServiceConditions->getContainerIds())) {
                    $q->where([
                        'HostsToContainersSharing.id IN ' => $ServiceConditions->getContainerIds()
                    ]);
                }
                return $q;
            })->innerJoinWith('Servicetemplates');

        if (!empty($where)) {
            $query->where($where);
        }

        $query->disableHydration();
        $query->group(['Services.id']);

        if (!empty($having)) {
            $query->having($having);
        }

        $query->order($ServiceConditions->getOrder());

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->emptyArrayIfNull($query->toArray());
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scrollCake4($query, $PaginateOMat->getHandler());
            } else {
                $result = $this->paginateCake4($query, $PaginateOMat->getHandler());
            }
        }

        return $result;
    }

    /**
     * @param ServiceConditions $ServiceConditions
     * @param null|PaginateOMat $PaginateOMat
     * @return array
     */
    public function getServiceNotMonitored(ServiceConditions $ServiceConditions, $PaginateOMat = null) {
        $where = $ServiceConditions->getConditions();
        $where['Services.disabled'] = 0;

        $having = null;
        if (isset($where['servicename LIKE'])) {
            $having = [
                'servicename LIKE' => $where['servicename LIKE']
            ];
            unset($where['servicename LIKE']);
        }


        if ($ServiceConditions->getHostId()) {
            $where['Services.host_id'] = $ServiceConditions->getHostId();
        }

        $query = $this->find();
        $query
            ->select([
                'Services.id',
                'Services.uuid',
                'Services.name',
                'Services.host_id',
                'Services.disabled',
                'Services.active_checks_enabled',
                'servicename' => $query->newExpr('IF((Services.name IS NULL OR Services.name=""), Servicetemplates.name, Services.name)'),

                'Servicetemplates.id',
                'Servicetemplates.uuid',
                'Servicetemplates.name',
                'Servicetemplates.active_checks_enabled',

                'Objects.object_id',

                'Hosts.name',
                'Hosts.id',
                'Hosts.uuid',
                'Hosts.description',
                'Hosts.address',
                'Hosts.disabled',
            ])
            ->innerJoinWith('Hosts')
            ->innerJoinWith('Hosts.HostsToContainersSharing', function (Query $q) use ($ServiceConditions) {
                if (!empty($ServiceConditions->getContainerIds())) {
                    $q->where([
                        'HostsToContainersSharing.id IN ' => $ServiceConditions->getContainerIds()
                    ]);
                }
                return $q;
            })
            ->innerJoinWith('Servicetemplates')
            ->leftJoin(['Objects' => 'nagios_objects'], [
                'Objects.name2 = Services.uuid',
                'Objects.objecttype_id' => 2
            ])
            ->whereNull('Objects.object_id');

        if (!empty($where)) {
            $query->where($where);
        }

        $query->disableHydration();
        $query->group(['Services.id']);

        if (!empty($having)) {
            $query->having($having);
        }

        $query->order($ServiceConditions->getOrder());

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->emptyArrayIfNull($query->toArray());
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scrollCake4($query, $PaginateOMat->getHandler());
            } else {
                $result = $this->paginateCake4($query, $PaginateOMat->getHandler());
            }
        }

        return $result;
    }

    /**
     * @param ServiceConditions $ServiceConditions
     * @param null|PaginateOMat $PaginateOMat
     * @return array
     */
    public function getServiceIndex(ServiceConditions $ServiceConditions, $PaginateOMat = null) {
        $where = $ServiceConditions->getConditions();

        $where['Services.disabled'] = 0;
        if ($ServiceConditions->getServiceIds()) {
            $serviceIds = $ServiceConditions->getServiceIds();
            if (!is_array($serviceIds)) {
                $serviceIds = [$serviceIds];
            }

            $where['Services.id IN'] = $serviceIds;
        }

        $having = null;
        if (isset($where['servicename LIKE'])) {
            $having = [
                'servicename LIKE' => $where['servicename LIKE']
            ];
            unset($where['servicename LIKE']);
        }


        if ($ServiceConditions->getHostId()) {
            $where['Services.host_id'] = $ServiceConditions->getHostId();
        }

        $query = $this->find();
        $query
            ->select([
                'Services.id',
                'Services.uuid',
                'Services.name',
                'Services.host_id',
                'Services.description',
                'Services.disabled',
                'Services.active_checks_enabled',
                'Services.tags',
                'servicename' => $query->newExpr('IF((Services.name IS NULL OR Services.name=""), Servicetemplates.name, Services.name)'),

                'Servicetemplates.id',
                'Servicetemplates.uuid',
                'Servicetemplates.name',
                'Servicetemplates.description',
                'Servicetemplates.active_checks_enabled',
                'Servicetemplates.tags',

                'Objects.object_id',

                'Servicestatus.current_state',
                'Servicestatus.last_check',
                'Servicestatus.next_check',
                'Servicestatus.last_hard_state_change',
                'Servicestatus.last_state_change',
                'Servicestatus.output',
                'Servicestatus.scheduled_downtime_depth',
                'Servicestatus.active_checks_enabled',
                'Servicestatus.state_type',
                'Servicestatus.problem_has_been_acknowledged',
                'Servicestatus.acknowledgement_type',
                'Servicestatus.is_flapping',
                'Servicestatus.perfdata',

                'Hosts.name',
                'Hosts.id',
                'Hosts.uuid',
                'Hosts.description',
                'Hosts.address',
                'Hosts.disabled',
            ])
            ->innerJoinWith('Hosts')
            ->innerJoinWith('Hosts.HostsToContainersSharing', function (Query $q) use ($ServiceConditions) {
                if (!empty($ServiceConditions->getContainerIds())) {
                    $q->where([
                        'HostsToContainersSharing.id IN ' => $ServiceConditions->getContainerIds()
                    ]);
                }
                return $q;
            })
            ->innerJoinWith('Servicetemplates')
            ->innerJoin(['Objects' => 'nagios_objects'], [
                'Objects.name2 = Services.uuid',
                'Objects.objecttype_id' => 2
            ])
            ->innerJoin(['Servicestatus' => 'nagios_servicestatus'], [
                'Servicestatus.service_object_id = Objects.object_id',
            ]);

        if (isset($where['keywords rlike'])) {
            $query->where(new Comparison(
                'IF((Services.tags IS NULL OR Services.tags=""), Servicetemplates.tags, Services.tags)',
                $where['keywords rlike'],
                'string',
                'rlike'
            ));
            unset($where['keywords rlike']);
        }

        if (isset($where['not_keywords not rlike'])) {
            $query->andWhere(new Comparison(
                'IF((Services.tags IS NULL OR Services.tags=""), Servicetemplates.tags, Services.tags)',
                $where['not_keywords not rlike'],
                'string',
                'not rlike'
            ));
            unset($where['not_keywords not rlike']);
        }

        if (!empty($where)) {
            $query->andWhere($where);
        }


        $query->disableHydration();
        $query->group(['Services.id']);

        if (!empty($having)) {
            $query->having($having);
        }

        $query->order($ServiceConditions->getOrder());

        //FileDebugger::dieQuery($query);


        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->emptyArrayIfNull($query->toArray());
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scrollCake4($query, $PaginateOMat->getHandler());
            } else {
                $result = $this->paginateCake4($query, $PaginateOMat->getHandler());
            }
        }

        return $result;
    }

    /**
     * @param array $ids
     * @param bool $prefixWithHostname
     * @return array
     */
    public function getServicesAsList($ids = [], $prefixWithHostname = false) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $query = $this->find();
        $query
            ->select([
                'Services.id',
                'servicename' => $query->newExpr('IF((Services.name IS NULL OR Services.name=""), Servicetemplates.name, Services.name)'),
                'Hosts.name'
            ])
            ->innerJoinWith('Servicetemplates')
            ->innerJoinWith('Hosts')
            ->disableHydration();
        if (!empty($ids)) {
            $query->where([
                'Services.id IN' => $ids
            ]);
        }

        $records = $query->toArray();

        if (empty($records) || is_null($records)) {
            return [];
        }

        $result = [];
        foreach ($records as $row) {
            if ($prefixWithHostname === true) {
                $result[$row['id']] = $row['_matchingData']['Hosts']['name'] . '/' . $row['servicename'];
            } else {
                $result[$row['id']] = $row['servicename'];
            }
        }

        return $result;
    }

    /**
     * Check if the service was part of an serviceescalation or servicedependency
     * If yes, cake delete the records by it self, but may be we have an empty serviceescalation or servicegroup now.
     * Nagios don't relay like this so we need to check this and delete the service escalation or service dependency if empty
     *
     * @param array $servicedependenciesMembershipToDelete
     * @param array $serviceescalationsMembershipToDelete
     */
    public function _clenupServiceEscalationAndDependency($servicedependenciesMembershipToDelete = [], $serviceescalationsMembershipToDelete = []) {
        if (!empty($servicedependenciesMembershipToDelete)) {
            /** @var $ServicedependenciesTable ServicedependenciesTable */
            $ServicedependenciesTable = TableRegistry::getTableLocator()->get('Servicedependencies');
            foreach ($servicedependenciesMembershipToDelete as $servicedependencyMembership) {
                if ($ServicedependenciesTable->existsById($servicedependencyMembership->get('servicedependency_id'))) {
                    $servicedependency = $ServicedependenciesTable->get($servicedependencyMembership->get('servicedependency_id'));
                    $ServicedependenciesTable->delete($servicedependency);
                }
            }
        }

        if (!empty($serviceescalationsMembershipToDelete)) {
            /* @var $ServiceescalationsTable ServiceescalationsTable */
            $ServiceescalationsTable = TableRegistry::getTableLocator()->get('Serviceescalations');
            foreach ($serviceescalationsMembershipToDelete as $serviceescalationMembership) {
                if ($ServiceescalationsTable->existsById($serviceescalationMembership->get('serviceescalation_id'))) {
                    $serviceescalation = $ServiceescalationsTable->get($serviceescalationMembership->get('serviceescalation_id'));
                    $ServiceescalationsTable->delete($serviceescalation);
                }
            }
        }
    }

    /**
     * @param int $commandId
     * @return bool
     */
    public function isCommandUsedByService($commandId) {
        $count = $this->find()
            ->where([
                'Services.command_id' => $commandId,
            ])->count();

        if ($count > 0) {
            return true;
        }

        $count = $this->find()
            ->where([
                'Services.eventhandler_command_id' => $commandId,
            ])->count();

        if ($count > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param int $commandId
     * @param array $MY_RIGHTS
     * @param bool $enableHydration
     * @return array
     */
    public function getServicesByCommandId($commandId, $MY_RIGHTS = [], $enableHydration = true) {
        $query = $this->find();
        $query->select([
            'Services.id',
            'Services.name',
            'Services.uuid',
            'Servicetemplates.id',
            'Servicetemplates.name',
            'Servicetemplates.uuid',
            'Hosts.id',
            'Hosts.name',
            'Hosts.uuid',
            'servicename' => $query->newExpr('IF((Services.name IS NULL OR Services.name=""), Servicetemplates.name, Services.name)'),
        ])
            ->innerJoinWith('Hosts')
            ->innerJoinWith('Hosts.HostsToContainersSharing', function (Query $q) use ($MY_RIGHTS) {
                if (!empty($MY_RIGHTS)) {
                    $q->where([
                        'HostsToContainersSharing.id IN ' => $MY_RIGHTS
                    ]);
                }
                return $q;
            })
            ->innerJoinWith('Servicetemplates')
            ->where([
                'OR' => [
                    ['Services.command_id' => $commandId],
                    ['Services.eventhandler_command_id' => $commandId]
                ]
            ])
            ->enableHydration($enableHydration)
            ->order([
                'Hosts.name'  => 'asc',
                'servicename' => 'asc'
            ])
            ->group(['Services.id'])
            ->all();

        return $this->emptyArrayIfNull($query->toArray());
    }

    /**
     * @param int $contactId
     * @param array $MY_RIGHTS
     * @param bool $enableHydration
     * @return array
     */
    public function getServicesByContactId($contactId, $MY_RIGHTS = [], $enableHydration = true) {

        /** @var ContactsToServicesTable $ContactsToServicesTable */
        $ContactsToServicesTable = TableRegistry::getTableLocator()->get('ContactsToServices');

        $query = $ContactsToServicesTable->find()
            ->select([
                'service_id'
            ])
            ->where([
                'contact_id' => $contactId
            ])
            ->group([
                'service_id'
            ])
            ->disableHydration()
            ->all();

        $result = $query->toArray();
        if (empty($result)) {
            return [];
        }

        $serviceIds = Hash::extract($result, '{n}.service_id');

        $query = $this->find('all');
        $query->where([
            'Services.id IN' => $serviceIds
        ]);
        $query->select([
            'Services.id',
            'Services.uuid',
            'Services.name',
            'Services.host_id',

            'servicename' => $query->newExpr('IF((Services.name IS NULL OR Services.name=""), Servicetemplates.name, Services.name)'),

            'Servicetemplates.id',
            'Servicetemplates.uuid',
            'Servicetemplates.name',

            'Hosts.name',
            'Hosts.id',
            'Hosts.uuid',
            'Hosts.description',
            'Hosts.address',
            'Hosts.disabled',
        ]);
        $query
            ->innerJoinWith('Hosts')
            ->innerJoinWith('Hosts.HostsToContainersSharing', function (Query $q) use ($MY_RIGHTS) {
                if (!empty($MY_RIGHTS)) {
                    $q->where([
                        'HostsToContainersSharing.id IN ' => $MY_RIGHTS
                    ]);
                }
                return $q;
            })
            ->contain([
                'Servicetemplates'
            ]);


        $query->enableHydration($enableHydration);
        $query->order([
            'servicename' => 'asc'
        ]);
        $query->group([
            'Services.id'
        ]);

        $result = $query->all();

        return $this->emptyArrayIfNull($result->toArray());
    }

    /**
     * @param ServiceConditions $ServiceConditions
     * @param ServicestatusConditions $ServicestatusConditions
     * @param null $PaginateOMat
     * @return array
     */
    public function getServiceForCurrentReport(ServiceConditions $ServiceConditions, ServicestatusConditions $ServicestatusConditions, $PaginateOMat = null) {
        $where = $ServiceConditions->getConditions();

        $where['Services.disabled'] = 0;
        if ($ServiceConditions->getServiceIds()) {
            $serviceIds = $ServiceConditions->getServiceIds();
            if (!is_array($serviceIds)) {
                $serviceIds = [$serviceIds];
            }

            $where['Services.id IN'] = $serviceIds;
        }

        $having = null;
        if (isset($where['servicename LIKE'])) {
            $having = [
                'servicename LIKE' => $where['servicename LIKE']
            ];
            unset($where['servicename LIKE']);
        }


        if ($ServiceConditions->getHostId()) {
            $where['Services.host_id'] = $ServiceConditions->getHostId();
        }

        $query = $this->find();
        $query
            ->select([
                'Services.id',
                'Services.uuid',
                'Services.name',
                'Services.host_id',
                'Services.description',
                'Services.disabled',
                'Services.active_checks_enabled',
                'servicename' => $query->newExpr('IF((Services.name IS NULL OR Services.name=""), Servicetemplates.name, Services.name)'),

                'Servicetemplates.name',
                'Servicetemplates.active_checks_enabled',

                'Objects.object_id',

                'Servicestatus.current_state',
                'Servicestatus.last_check',
                'Servicestatus.next_check',
                'Servicestatus.last_hard_state_change',
                'Servicestatus.last_state_change',
                'Servicestatus.output',
                'Servicestatus.perfdata',
                'Servicestatus.scheduled_downtime_depth',
                'Servicestatus.active_checks_enabled',
                'Servicestatus.state_type',
                'Servicestatus.problem_has_been_acknowledged',
                'Servicestatus.acknowledgement_type',
                'Servicestatus.is_flapping',
                'Servicestatus.current_check_attempt',
                'Servicestatus.max_check_attempts',

                'Hosts.name',
                'Hosts.id',
                'Hosts.uuid',
                'Hosts.description',
                'Hosts.address',
                'Hosts.disabled',
            ])
            ->innerJoinWith('Hosts')
            ->innerJoinWith('Hosts.HostsToContainersSharing', function (Query $q) use ($ServiceConditions) {
                if (!empty($ServiceConditions->getContainerIds())) {
                    $q->where([
                        'HostsToContainersSharing.id IN ' => $ServiceConditions->getContainerIds()
                    ]);
                }
                return $q;
            })
            ->innerJoinWith('Servicetemplates')
            ->innerJoin(['Objects' => 'nagios_objects'], [
                'Objects.name2 = Services.uuid',
                'Objects.objecttype_id' => 2
            ])
            ->innerJoin(['Servicestatus' => 'nagios_servicestatus'], [
                'Servicestatus.service_object_id = Objects.object_id',
            ]);

        if (isset($where['keywords rlike'])) {
            $query->where(new Comparison(
                'IF((Services.tags IS NULL OR Services.tags=""), Servicetemplates.tags, Services.tags)',
                $where['keywords rlike'],
                'string',
                'rlike'
            ));
            unset($where['keywords rlike']);
        }

        if (isset($where['not_keywords not rlike'])) {
            $query->andWhere(new Comparison(
                'IF((Services.tags IS NULL OR Services.tags=""), Servicetemplates.tags, Services.tags)',
                $where['not_keywords not rlike'],
                'string',
                'not rlike'
            ));
            unset($where['not_keywords not rlike']);
        }

        if ($ServicestatusConditions->hasConditions()) {
            $whereServicestatusConditions = $ServicestatusConditions->getConditions();
            if (isset($whereServicestatusConditions['Servicestatus.current_state'])) {
                $query->andWhere([
                    'Servicestatus.current_state IN ' => array_values($whereServicestatusConditions['Servicestatus.current_state'])
                ]);
            }
            if (isset($whereServicestatusConditions['Servicestatus.scheduled_downtime_depth'])) {
                $query->andWhere([
                    'Servicestatus.scheduled_downtime_depth' => 0
                ]);
            }
            if (isset($whereServicestatusConditions['Servicestatus.scheduled_downtime_depth > '])) {
                $query->andWhere([
                    'Servicestatus.scheduled_downtime_depth > ' => 0
                ]);
            }
            if (isset($whereServicestatusConditions['Servicestatus.problem_has_been_acknowledged'])) {
                $query->andWhere([
                    'Servicestatus.problem_has_been_acknowledged' => $whereServicestatusConditions['Servicestatus.problem_has_been_acknowledged']
                ]);
            }
            if (isset($whereServicestatusConditions['Servicestatus.active_checks_enabled'])) {
                $query->andWhere([
                    'Servicestatus.active_checks_enabled' => $whereServicestatusConditions['Servicestatus.active_checks_enabled']
                ]);
            }
        }

        if (!empty($where)) {
            $query->andWhere($where);
        }

        $query->disableHydration();
        $query->group(['Services.id']);

        if (!empty($having)) {
            $query->having($having);
        }

        $query->order($ServiceConditions->getOrder());

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->emptyArrayIfNull($query->toArray());
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scrollCake4($query, $PaginateOMat->getHandler());
            } else {
                $result = $this->paginateCake4($query, $PaginateOMat->getHandler());
            }
        }

        return $result;
    }

    public function getAllOitcAgentServicesByHostIdForExport($hostId) {
        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        $query = $this->find()
            ->select([
                'Services.id',
                'Services.name',
                'Services.servicetemplate_id',
                'Services.uuid',

                'Servicetemplates.id',
                'Servicetemplates.name',
                'Servicetemplates.uuid',

                'Agentchecks.id',
                'Agentchecks.name',
                'Agentchecks.servicetemplate_id',
                'Agentchecks.plugin_name'
            ])
            ->innerJoin(
                ['Servicetemplates' => 'servicetemplates'],
                ['Services.servicetemplate_id = Servicetemplates.id']
            )
            ->innerJoin(
                ['Agentchecks' => 'agentchecks'],
                ['Servicetemplates.id = Agentchecks.servicetemplate_id']
            )
            ->contain([
                'Servicecommandargumentvalues' => [
                    'Commandarguments'
                ]
            ])
            ->where([
                'Services.host_id'      => $hostId,
                'Services.service_type' => OITC_AGENT_SERVICE
            ])
            ->disableHydration()
            ->all();

        $services = $this->emptyArrayIfNull($query->toArray());

        $ServicetemplateArgsCache = new KeyValueStore();

        foreach ($services as $index => $service) {
            if (!empty($servicecommandargumentvalues)) {
                //Arguments from service
                $servicecommandargumentvalues = $service['servicecommandargumentvalues'];
                $servicecommandargumentvalues = Hash::sort($servicecommandargumentvalues, '{n}.commandargument.name', 'asc', 'natural');
                $servicecommandargumentvalues = Hash::extract($servicecommandargumentvalues, '{n}.value');
            } else {
                //Use arguments from service template
                if (!$ServicetemplateArgsCache->has($service['servicetemplate_id'])) {
                    $servicetemplate = $ServicetemplatesTable->getServicetemplateForEdit($service['servicetemplate_id']);

                    $servicecommandargumentvalues = $servicetemplate['Servicetemplate']['servicetemplatecommandargumentvalues'];
                    $servicecommandargumentvalues = Hash::sort($servicecommandargumentvalues, '{n}.commandargument.name', 'asc', 'natural');

                    $servicecommandargumentvalues = Hash::extract($servicecommandargumentvalues, '{n}.value');
                    $ServicetemplateArgsCache->set($service['servicetemplate_id'], $servicecommandargumentvalues);
                }

                $servicecommandargumentvalues = $ServicetemplateArgsCache->get($service['servicetemplate_id']);
            }

            $services[$index]['args_for_config'] = $servicecommandargumentvalues;
        }

        return $services;
    }

    /**
     * @param bool $includeDisabled
     * @return int|null
     */
    public function getServicesCountForStats($includeDisabled = true) {
        $query = $this->find();
        if ($includeDisabled === false) {
            $query->where([
                'Services.disabled' => 0
            ]);
        }

        return $query->count();
    }

    public function getServiceByIdForDowntimeCreation($id) {
        $query = $this->find()
            ->select([
                'Services.id',
                'Services.uuid'
            ])
            ->where([
                'Services.id' => $id,
            ])
            ->contain([
                'Hosts' => function (Query $query) {
                    $query->disableAutoFields()
                        ->select([
                            'Hosts.id',
                            'Hosts.name',
                            'Hosts.uuid'
                        ]);
                    return $query;
                }
            ])
            ->first();
        return $query;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getServiceForBrowser($id) {
        $query = $this->find()
            ->where([
                'Services.id' => $id
            ])
            ->contain([
                'Contactgroups'                     => [
                    'Containers'
                ],
                'Contacts'                          => [
                    'Containers'
                ],
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

        return $service;
    }

    /**
     * @param $id
     * @return array|Service|null
     */
    public function getServiceByIdForTimeline($id) {
        $query = $this->find()
            ->select([
                'Services.id',
                'Services.name',
                'Services.uuid',
                'Services.servicetemplate_id',
                'Services.check_period_id',
                'Services.notify_period_id',
            ])
            ->where([
                'Services.id' => $id
            ])
            ->contain([
                'Hosts'            => function (Query $query) {
                    $query->select([
                        'Hosts.id',
                        'Hosts.uuid',
                        'Hosts.container_id'
                    ])
                        ->contain([
                            'HostsToContainersSharing'
                        ]);
                    return $query;
                },
                'Servicetemplates' => function (Query $query) {
                    $query->select([
                        'Servicetemplates.id',
                        'Servicetemplates.check_period_id',
                        'Servicetemplates.notify_period_id'
                    ]);
                    return $query;
                }
            ])
            ->first();

        return $query;
    }


    /**
     * @param ServiceConditions $ServiceConditions
     * @param null|PaginateOMat $PaginateOMat
     * @param string $type (all or count, list is NOT supported!)
     * @return int|array
     */
    public function getServicesByRegularExpression(ServiceConditions $ServiceConditions, $PaginateOMat = null, $type = 'all') {
        $MY_RIGHTS = $ServiceConditions->getContainerIds();
        $query = $this->find('all');
        $query->select([
            'Services.id',
            'Services.uuid',
            'Services.name',
            'Services.host_id',
            'Services.description',
            'Services.disabled',
            'Services.active_checks_enabled',
            'Services.tags',
            'servicename' => $query->newExpr('IF((Services.name IS NULL OR Services.name=""), Servicetemplates.name, Services.name)'),

            'Servicetemplates.id',
            'Servicetemplates.uuid',
            'Servicetemplates.name',

            'Hosts.name',
            'Hosts.id',
            'Hosts.uuid',
            'Hosts.description',
            'Hosts.address',
            'Hosts.disabled',
        ]);
        $query->where([
            'Hosts.disabled'    => 0,
            'Hosts.name REGEXP' => $ServiceConditions->getHostnameRegex(),
            'Services.disabled' => 0
        ]);
        $query->having([
            'servicename REGEXP' => $ServiceConditions->getServicenameRegex()
        ]);
        $query
            ->innerJoinWith('Hosts')
            ->innerJoinWith('Hosts.HostsToContainersSharing', function (Query $q) use ($MY_RIGHTS) {
                if (!empty($MY_RIGHTS)) {
                    $q->where([
                        'HostsToContainersSharing.id IN ' => $MY_RIGHTS
                    ]);
                }
                return $q;
            })
            ->contain([
                'Servicetemplates'
            ]);

        if ($type === 'all') {
            $query->order([
                'servicename' => 'asc'
            ]);
        }
        $query->group([
            'Services.id'
        ]);

        if ($type === 'count') {
            return $query->count();
        }

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->emptyArrayIfNull($query->toArray());
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scrollCake4($query, $PaginateOMat->getHandler());
            } else {
                $result = $this->paginateCake4($query, $PaginateOMat->getHandler());
            }
        }

        return $result;
    }

    /**
     * @param array $MY_RIGHTS
     * @param array $conditions
     * @return int
     */
    public function getServicestatusCountBySelectedStatus($MY_RIGHTS, $conditions) {
        $query = $this->find();
        $query
            ->select([
                'count' => $query->newExpr('COUNT(DISTINCT Servicestatus.service_object_id)')
            ])
            ->where([
                'Services.disabled'       => 0,
                'ServiceObject.is_active' => 1,
            ])
            ->join([
                'a' => [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'ServiceObject',
                    'conditions' => 'ServiceObject.name2 = Services.uuid',
                ],
                'b' => [
                    'table'      => 'nagios_servicestatus',
                    'type'       => 'INNER',
                    'alias'      => 'Servicestatus',
                    'conditions' => 'Servicestatus.service_object_id = ServiceObject.object_id',
                ],
            ])
            ->innerJoinWith('Servicetemplates')
            ->innerJoinWith('Hosts')
            ->innerJoinWith('Hosts.HostsToContainersSharing', function (Query $q) use ($MY_RIGHTS) {
                return $q->where([
                    'HostsToContainersSharing.id IN ' => $MY_RIGHTS
                ]);
            })
            ->group([
                'Servicestatus.current_state',
            ])
            ->disableHydration();

        $where = [];
        if (!empty($conditions['Host']['name'])) {
            $where['Hosts.name LIKE'] = sprintf('%%%s%%', $conditions['Host']['name']);
        }
        if (!empty($conditions['Service']['name'])) {
            $query->andWhere(
                $query->newExpr('IF(Services.name IS NULL, Servicetemplates.name, Services.name) LIKE :servicename')
            );
            $query->bind(':servicename',  sprintf('%%%s%%', $conditions['Service']['name']));
        }


        $where['Servicestatus.current_state'] = $conditions['Servicestatus']['current_state'];

        if ($where['Servicestatus.current_state'] > 0) {
            if ($conditions['Servicestatus']['problem_has_been_acknowledged'] === false) {
                $where['Servicestatus.problem_has_been_acknowledged'] = false;
            }
            if ($conditions['Servicestatus']['scheduled_downtime_depth'] === false) {
                $where['Servicestatus.scheduled_downtime_depth'] = false;
            }
        }

        $query->andWhere($where);
        //FileDebugger::dieQuery($query);
        $result = $query->first();

        if ($result === null) {
            return 0;
        }

        return $result['count'];
    }

    /**
     * @param int $timeperiodId
     * @return bool
     */
    public function isTimeperiodUsedByService($timeperiodId) {
        $count = $this->find()
            ->where([
                'OR' => [
                    'Services.check_period_id'  => $timeperiodId,
                    'Services.notify_period_id' => $timeperiodId
                ]
            ])->count();

        if ($count > 0) {
            return true;
        }

        return false;
    }
}
