<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\Database\Expression\Comparison;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\HostConditions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\HostFilter;

/**
 * Hosts Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\HosttemplatesTable|\Cake\ORM\Association\BelongsTo $Hosttemplates
 * @property \App\Model\Table\CommandsTable|\Cake\ORM\Association\BelongsTo $Commands
 * @property \App\Model\Table\EventhandlerCommandsTable|\Cake\ORM\Association\BelongsTo $EventhandlerCommands
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $Timeperiods
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $CheckPeriods
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $NotifyPeriods
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

    use PaginationAndScrollIndexTrait;
    use Cake2ResultTableTrait;

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

        $this->belongsToMany('HostsToContainersSharing', [
            'className'        => 'Containers',
            'joinTable'        => 'hosts_to_containers',
            'foreignKey'       => 'host_id',
            'targetForeignKey' => 'container_id'
        ]);

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
        ]);

        $this->belongsToMany('Contactgroups', [
            'className'        => 'Contactgroups',
            'foreignKey'       => 'host_id',
            'targetForeignKey' => 'contactgroup_id',
            'joinTable'        => 'contactgroups_to_hosts',
            'saveStrategy'     => 'replace'
        ])->setDependent(true);

        $this->belongsToMany('Contacts', [
            'className'        => 'Contacts',
            'foreignKey'       => 'host_id',
            'targetForeignKey' => 'contact_id',
            'joinTable'        => 'contacts_to_hosts',
            'saveStrategy'     => 'replace'
        ])->setDependent(true);

        $this->belongsToMany('Hostgroups', [
            'className'        => 'Hostgroups',
            'foreignKey'       => 'host_id',
            'targetForeignKey' => 'hostgroup_id',
            'joinTable'        => 'hosts_to_hostgroups',
            'saveStrategy'     => 'replace'
        ])->setDependent(true);


        $this->belongsToMany('Parenthosts', [
            'className'        => 'Hostgroups',
            'foreignKey'       => 'host_id',
            'targetForeignKey' => 'parenthost_id',
            'joinTable'        => 'hosts_to_parenthosts',
            'saveStrategy'     => 'replace'
        ])->setDependent(true);

        $this->belongsTo('Hosttemplates', [
            'foreignKey' => 'hosttemplate_id',
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
                'objecttype_id' => OBJECT_HOST
            ],
            'foreignKey'   => 'object_id',
            'saveStrategy' => 'replace'
        ])->setDependent(true);

        $this->hasMany('Hostcommandargumentvalues', [
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

        return $rules;
    }

    /**
     * @param int $id
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getHostById($id) {
        $query = $this->find()
            ->where([
                'Hosts.id' => $id
            ])
            ->contain('HostsToContainersSharing')
            ->first();
        return $query;
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

    /**
     * @param HostFilter $HostFilter
     * @param HostConditions $HostConditions
     * @param null|PaginateOMat $PaginateOMat
     * @return array
     */
    public function getHostsIndex(HostFilter $HostFilter, HostConditions $HostConditions, $PaginateOMat = null) {
        $MY_RIGHTS = $HostConditions->getContainerIds();

        $query = $this->find('all');
        $query->select([
            'Hosts.id',
            'Hosts.uuid',
            'Hosts.name',
            'Hosts.description',
            'Hosts.active_checks_enabled',
            'Hosts.address',
            'Hosts.satellite_id',
            'Hosts.container_id',
            'Hosts.tags',

            //'keywords'     => 'IF((Hosts.tags IS NULL OR Hosts.tags=""), Hosttemplates.tags, Hosts.tags)',
            //'not_keywords' => 'IF((Hosts.tags IS NULL OR Hosts.tags=""), Hosttemplates.tags, Hosts.tags)',

            'Hoststatus.current_state',
            'Hoststatus.last_check',
            'Hoststatus.next_check',
            'Hoststatus.last_hard_state_change',
            'Hoststatus.last_state_change',
            'Hoststatus.output',
            'Hoststatus.scheduled_downtime_depth',
            'Hoststatus.active_checks_enabled',
            'Hoststatus.state_type',
            'Hoststatus.problem_has_been_acknowledged',
            'Hoststatus.acknowledgement_type'
        ]);

        $query->join([
            'a' => [
                'table'      => 'nagios_objects',
                'type'       => 'INNER',
                'alias'      => 'HostObject',
                'conditions' => 'Hosts.uuid = HostObject.name1 AND HostObject.objecttype_id = 1'
            ],
            'b' => [
                'table'      => 'nagios_hoststatus',
                'type'       => 'LEFT OUTER',
                'alias'      => 'Hoststatus',
                'conditions' => 'Hoststatus.host_object_id = HostObject.object_id',
            ]
        ]);

        $query->innerJoinWith('HostsToContainersSharing', function (Query $q) use ($MY_RIGHTS) {
            if (!empty($MY_RIGHTS)) {
                return $q->where(['HostsToContainersSharing.id IN' => $MY_RIGHTS]);
            }
            return $q;
        });
        $query->contain([
            'HostsToContainersSharing',
            'Hosttemplates' => [
                'fields' => [
                    'Hosttemplates.id',
                    'Hosttemplates.uuid',
                    'Hosttemplates.name',
                    'Hosttemplates.description',
                    'Hosttemplates.active_checks_enabled',
                    'Hosttemplates.tags'
                ]
            ]

        ]);

        $where = $HostFilter->indexFilter();
        $where['Hosts.disabled'] = (int)$HostConditions->includeDisabled();
        if ($HostConditions->getHostIds()) {
            $where['Hosts.id'] = $HostConditions->getHostIds();
        }

        if (isset($where['Hosts.keywords rlike'])) {
            $where[] = new Comparison(
                'IF((Hosts.tags IS NULL OR Hosts.tags=""), Hosttemplates.tags, Hosts.tags)',
                $where['Hosts.keywords rlike'],
                'string',
                'RLIKE'
            );
            unset($where['Hosts.keywords rlike']);
        }

        if (isset($where['Hosts.not_keywords not rlike'])) {
            $where[] = new Comparison(
                'IF((Hosts.tags IS NULL OR Hosts.tags=""), Hosttemplates.tags, Hosts.tags)',
                $where['Hosts.not_keywords not rlike'],
                'string',
                'NOT RLIKE'
            );
            unset($where['Hosts.not_keywords not rlike']);
        }


        $query->where($where);

        $query->disableHydration();
        $query->group(['Hosts.id']);
        $query->order($HostFilter->getOrderForPaginator('Hoststatus.current_state', 'desc'));

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
     * @param HostFilter $HostFilter
     * @param HostConditions $HostConditions
     * @param null|PaginateOMat $PaginateOMat
     * @return array
     */
    public function getHostsNotMonitored(HostFilter $HostFilter, HostConditions $HostConditions, $PaginateOMat = null) {
        $MY_RIGHTS = $HostConditions->getContainerIds();

        $query = $this->find('all');
        $query->select([
            'Hosts.id',
            'Hosts.uuid',
            'Hosts.name',
            'Hosts.description',
            'Hosts.address',
            'Hosts.satellite_id',
            'Hosts.container_id'
        ]);

        $query->join([
            'a' => [
                'table'      => 'nagios_objects',
                'type'       => 'LEFT OUTER',
                'alias'      => 'HostObject',
                'conditions' => 'Hosts.uuid = HostObject.name1 AND HostObject.objecttype_id = 1'
            ]
        ]);

        $query->innerJoinWith('HostsToContainersSharing', function (Query $q) use ($MY_RIGHTS) {
            if (!empty($MY_RIGHTS)) {
                return $q->where(['HostsToContainersSharing.id IN' => $MY_RIGHTS]);
            }
            return $q;
        });
        $query->contain([
            'HostsToContainersSharing',
            'Hosttemplates' => [
                'fields' => [
                    'Hosttemplates.id',
                    'Hosttemplates.uuid',
                    'Hosttemplates.name',
                    'Hosttemplates.description',
                    'Hosttemplates.active_checks_enabled',
                ]
            ]

        ]);

        $where = $HostFilter->disabledFilter();
        $where['Hosts.disabled'] = (int)$HostConditions->includeDisabled();
        $where[] = 'HostObject.name1 IS NULL';
        $query->where($where);

        $query->disableHydration();
        $query->group(['Hosts.id']);
        $query->order($HostFilter->getOrderForPaginator('Hosts.name', 'asc'));

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
     * @param HostFilter $HostFilter
     * @param HostConditions $HostConditions
     * @param null|PaginateOMat $PaginateOMat
     * @return array
     */
    public function getHostsDisabled(HostFilter $HostFilter, HostConditions $HostConditions, $PaginateOMat = null) {
        $MY_RIGHTS = $HostConditions->getContainerIds();

        $query = $this->find('all');
        $query->select([
            'Hosts.id',
            'Hosts.uuid',
            'Hosts.name',
            'Hosts.description',
            'Hosts.address',
            'Hosts.satellite_id',
            'Hosts.container_id'
        ]);

        $where = $HostFilter->disabledFilter();
        $where['Hosts.disabled'] = (int)$HostConditions->includeDisabled();

        $query->where($where);
        $query->innerJoinWith('HostsToContainersSharing', function (Query $q) use ($MY_RIGHTS) {
            if (!empty($MY_RIGHTS)) {
                return $q->where(['HostsToContainersSharing.id IN' => $MY_RIGHTS]);
            }
            return $q;
        });
        $query->contain([
            'HostsToContainersSharing',
            'Hosttemplates' => [
                'fields' => [
                    'Hosttemplates.id',
                    'Hosttemplates.name'
                ]
            ]

        ]);
        $query->disableHydration();
        $query->group(['Hosts.id']);
        $query->order($HostFilter->getOrderForPaginator('Hosts.name', 'asc'));

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
     * @param array $containerIds
     * @param string $type
     * @param string $index
     * @param array $where
     * @return array
     */
    public function getHostsByContainerId($containerIds = [], $type = 'all', $index = 'id', $where = []) {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }
        $containerIds = array_unique($containerIds);

        $_where = [
            'Hosts.disabled' => 0
        ];

        $where = Hash::merge($_where, $where);

        $query = $this->find();
        $query->select([
            'Hosts.' . $index,
            'Hosts.name'
        ]);

        $query->where($where);
        $query->innerJoinWith('HostsToContainersSharing', function (Query $q) use ($containerIds) {
            if (!empty($containerIds)) {
                return $q->where(['HostsToContainersSharing.id IN' => $containerIds]);
            }
            return $q;
        });
        $query->disableHydration();
        $query->group(['Hosts.id']);
        $query->order([
            'Hosts.name' => 'asc'
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
            $list[$row[$index]] = $row['name'];
        }

        return $list;
    }

    /**
     * @param array $dataToParse
     * @return array
     */
    public function resolveDataForChangelog($dataToParse = []) {
        $extDataForChangelog = [
            'Contact'      => [],
            'Contactgroup' => [],
            'CheckPeriod'  => [],
            'NotifyPeriod' => [],
            'CheckCommand' => [],
            'Hostgroup'    => [],
            'Hosttemplate' => [],
            'Parenthost'   => []
        ];

        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');


        if (!empty($dataToParse['Host']['contacts']['_ids'])) {
            foreach ($ContactsTable->getContactsAsList($dataToParse['Host']['contacts']['_ids']) as $contactId => $contactName) {
                $extDataForChangelog['Contact'][] = [
                    'id'   => $contactId,
                    'name' => $contactName
                ];
            }
        }

        if (!empty($dataToParse['Host']['contactgroups']['_ids'])) {
            foreach ($ContactgroupsTable->getContactgroupsAsList($dataToParse['Host']['contactgroups']['_ids']) as $contactgroupId => $contactgroupName) {
                $extDataForChangelog['Contactgroup'][] = [
                    'id'   => $contactgroupId,
                    'name' => $contactgroupName
                ];
            }
        }

        if (!empty($dataToParse['Host']['check_period_id'])) {
            foreach ($TimeperiodsTable->getTimeperiodsAsList($dataToParse['Host']['check_period_id']) as $timeperiodId => $timeperiodName) {
                $extDataForChangelog['CheckPeriod'] = [
                    'id'   => $timeperiodId,
                    'name' => $timeperiodName
                ];
            }
        }

        if (!empty($dataToParse['Host']['notify_period_id'])) {
            foreach ($TimeperiodsTable->getTimeperiodsAsList($dataToParse['Host']['notify_period_id']) as $timeperiodId => $timeperiodName) {
                $extDataForChangelog['NotifyPeriod'] = [
                    'id'   => $timeperiodId,
                    'name' => $timeperiodName
                ];
            }
        }

        if (!empty($dataToParse['Host']['command_id'])) {
            foreach ($CommandsTable->getCommandByIdAsList($dataToParse['Host']['command_id']) as $commandId => $commandName) {
                $extDataForChangelog['CheckCommand'] = [
                    'id'   => $commandId,
                    'name' => $commandName
                ];
            }
        }

        if (!empty($dataToParse['Host']['hostgroups']['_ids'])) {
            foreach ($HostgroupsTable->getHostgroupsAsList($dataToParse['Host']['hostgroups']['_ids']) as $hostgroupId => $hostgroupName) {
                $extDataForChangelog['Hostgroup'] = [
                    'id'   => $hostgroupId,
                    'name' => $hostgroupName
                ];
            }
        }

        if (!empty($dataToParse['Host']['parenthosts']['_ids'])) {
            foreach ($this->getHostsAsList($dataToParse['Host']['parenthosts']['_ids']) as $parentHostId => $parentHostName) {
                $extDataForChangelog['Parenthost'] = [
                    'id'   => $parentHostId,
                    'name' => $parentHostName
                ];
            }
        }

        if (!empty($dataToParse['Host']['hosttemplate_id'])) {
            foreach ($HosttemplatesTable->getHosttemplatesAsList($dataToParse['Host']['hosttemplate_id']) as $hosttemplateId => $hosttemplateName) {
                $extDataForChangelog['Hosttemplate'] = [
                    'id'   => $hosttemplateId,
                    'name' => $hosttemplateName
                ];
            }
        }

        return $extDataForChangelog;
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getHostsAsList($ids = []) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $query = $this->find()
            ->select([
                'Hosts.id',
                'Hosts.name'
            ])
            ->disableHydration();
        if (!empty($ids)) {
            $query->where([
                'Hosts.id IN' => $ids
            ]);
        }

        return $this->formatListAsCake2($query->toArray());
    }


    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Hosts.id' => $id]);
    }
}
