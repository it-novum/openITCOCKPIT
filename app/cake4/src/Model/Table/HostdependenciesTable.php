<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Filter\HostdependenciesFilter;

/**
 * Hostdependencies Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\HostdependenciesTable|\Cake\ORM\Association\HasMany $Hosts
 * @property \App\Model\Table\HostdependenciesTable|\Cake\ORM\Association\HasMany $Hostgroups
 *
 * @method \App\Model\Entity\Hostdependency get($primaryKey, $options = [])
 * @method \App\Model\Entity\Hostdependency newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Hostdependency[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Hostdependency|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Hostdependency|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Hostdependency patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Hostdependency[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Hostdependency findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HostdependenciesTable extends Table {

    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;
    use CustomValidationTrait;


    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);
        $this->addBehavior('Timestamp');

        $this->setTable('hostdependencies');
        $this->setPrimaryKey('id');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
        ]);
        $this->belongsTo('Timeperiods', [
            'className'  => 'Timeperiods',
            'foreignKey' => 'timeperiod_id',
            'joinType'   => 'LEFT'
        ]);

        $this->belongsToMany('Hosts', [
            'className'    => 'Hosts',
            'through'      => 'HostdependenciesHostMemberships',
            'saveStrategy' => 'replace'
        ]);
        $this->belongsToMany('HostsDependent', [
            'className'        => 'Hosts',
            'through'          => 'HostdependenciesHostMemberships',
            'targetForeignKey' => 'host_id',
            'saveStrategy'     => 'replace'
        ]);
        $this->belongsToMany('Hostgroups', [
            'through'      => 'HostdependenciesHostgroupMemberships',
            'saveStrategy' => 'replace'
        ]);
        $this->belongsToMany('HostgroupsDependent', [
            'className'        => 'Hostgroups',
            'through'          => 'HostdependenciesHostgroupMemberships',
            'targetForeignKey' => 'hostgroup_id',
            'saveStrategy'     => 'replace'
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
            ->integer('container_id')
            ->greaterThan('container_id', 0)
            ->requirePresence('container_id')
            ->allowEmptyString('container_id', false);

        $validator
            ->requirePresence('hosts', true, __('You have to choose at least one host.'))
            ->allowEmptyString('hosts', false)
            ->multipleOptions('hosts', [
                'min' => 1
            ], __('You have to choose at least one host.'));

        $validator
            ->requirePresence('hosts_dependent', true, __('You have to choose at least one dependent host.'))
            ->allowEmptyString('hosts_dependent', false)
            ->multipleOptions('hosts_dependent', [
                'min' => 1
            ], __('You have to choose at least one dependent host.'));

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
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Hostdependencies.id' => $id]);
    }

    /**
     * @param HostdependenciesFilter $HostdependenciesFilter
     * @param null $PaginateOMat
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getHostdependenciesIndex(HostdependenciesFilter $HostdependenciesFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        $query = $this->find('all')
            ->contain([
                'Timeperiods'    => function (Query $q) {
                    return $q->enableAutoFields(false)
                        ->select([
                            'Timeperiods.id',
                            'Timeperiods.name'
                        ]);
                },
                'Hosts'          => function (Query $q) {
                    return $q->enableAutoFields(false)
                        ->where([
                            'HostdependenciesHostMemberships.dependent' => 0
                        ])
                        ->select([
                            'Hosts.id',
                            'Hosts.name',
                            'Hosts.disabled'
                        ]);
                },
                'HostsDependent' => function (Query $q) {
                    return $q->enableAutoFields(false)
                        ->where([
                            'HostdependenciesHostMemberships.dependent' => 1
                        ])
                        ->select([
                            'HostsDependent.id',
                            'HostsDependent.name',
                            'HostsDependent.disabled'
                        ]);
                },

                'Hostgroups'          => [
                    'Containers' => function (Query $q) {
                        return $q->enableAutoFields(false)
                            ->where([
                                'HostdependenciesHostgroupMemberships.dependent' => 0
                            ])
                            ->select([
                                'Hostgroups.id',
                                'Containers.name'
                            ]);
                    },
                ],
                'HostgroupsDependent' => [
                    'Containers' => function (Query $q) {
                        return $q->enableAutoFields(false)
                            ->where([
                                'HostdependenciesHostgroupMemberships.dependent' => 1
                            ])
                            ->select([
                                'HostgroupsDependent.id',
                                'Containers.name'
                            ]);
                    },
                ]
            ])
            ->group('Hostdependencies.id')
            ->disableHydration();
        $indexFilter = $HostdependenciesFilter->indexFilter();
        $containFilter = [
            'Hosts.name'               => '',
            'HostsDependent.name'      => '',
            'Hostgroups.name'          => '',
            'HostgroupsDependent.name' => ''
        ];
        if (!empty($indexFilter['Hosts.name LIKE'])) {
            $containFilter['Hosts.name'] = [
                'Hosts.name LIKE' => $indexFilter['Hosts.name LIKE']
            ];
            $query->matching('Hosts', function ($q) use ($containFilter) {
                return $q->where($containFilter['Hosts.name']);
            });
            unset($indexFilter['Hosts.name LIKE']);
        }
        if (!empty($indexFilter['HostsDependent.name LIKE'])) {
            $containFilter['HostsDependent.name'] = [
                'HostsDependent.name LIKE' => $indexFilter['HostsDependent.name LIKE']
            ];
            $query->matching('HostsDependent', function ($q) use ($containFilter) {
                return $q->where($containFilter['HostsDependent.name']);
            });
            unset($indexFilter['HostsDependent.name LIKE']);

        }
        if (!empty($indexFilter['Hostgroups.name LIKE'])) {
            $containFilter['Hostgroups.name'] = [
                'Containers.name LIKE' => $indexFilter['Hostgroups.name LIKE']
            ];
            $query->matching('Hostgroups.Containers', function ($q) use ($containFilter) {
                return $q->where($containFilter['Hostgroups.name']);
            });
            unset($indexFilter['Hostgroups.name LIKE']);
        }
        if (!empty($indexFilter['HostgroupsDependent.name LIKE'])) {
            $containFilter['HostgroupsDependent.name'] = [
                'Containers.name LIKE' => $indexFilter['HostgroupsDependent.name LIKE']
            ];
            $query->matching('HostgroupsDependent.Containers', function ($q) use ($containFilter) {
                return $q->where($containFilter['HostgroupsDependent.name']);
            });
            unset($indexFilter['HostgroupsDependent.name LIKE']);
        }
        if (!empty($MY_RIGHTS)) {
            $indexFilter['Hostdependencies.container_id IN'] = $MY_RIGHTS;
        }
        $query->where($indexFilter);
        $query->order($HostdependenciesFilter->getOrderForPaginator('Hostdependencies.id', 'asc'));

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $query->toArray();
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scrollCake4($query, $PaginateOMat->getHandler(), false);
            } else {
                $result = $this->paginate($query, $PaginateOMat->getHandler(), false);
            }
        }

        return $result;
    }

    /**
     * @param array|int $hosts
     * @param array|int $dependent_hosts
     * @return array
     */
    public function parseHostMembershipData($hosts = [], $dependent_hosts = []) {
        $hostmembershipData = [];
        foreach ($hosts as $host) {
            $hostmembershipData[] = [
                'id'        => $host,
                '_joinData' => [
                    'dependent' => 0
                ]
            ];
        }
        foreach ($dependent_hosts as $dependent_host) {
            $hostmembershipData[] = [
                'id'        => $dependent_host,
                '_joinData' => [
                    'dependent' => 1
                ]
            ];
        }
        return $hostmembershipData;
    }

    /**
     * @param array $hostgroups
     * @param array $dependent_hostgroups
     * @return array
     */
    public function parseHostgroupMembershipData($hostgroups = [], $dependent_hostgroups = []) {
        $hostgroupmembershipData = [];
        foreach ($hostgroups as $hostgroup) {
            $hostgroupmembershipData[] = [
                'id'        => $hostgroup,
                '_joinData' => [
                    'dependent' => 0
                ]
            ];
        }
        foreach ($dependent_hostgroups as $dependent_hostgroup) {
            $hostgroupmembershipData[] = [
                'id'        => $dependent_hostgroup,
                '_joinData' => [
                    'dependent' => 1
                ]
            ];
        }
        return $hostgroupmembershipData;
    }

    /**
     * @param null|string $uuid
     * @return array
     */
    public function getHostdependenciesForExport($uuid = null) {
        $query = $this->find()
            ->contain([
                'hosts'       =>
                    function (Query $q) {
                        return $q->enableAutoFields(false)->select(['id', 'uuid', 'check_interval', 'command_id']);
                    },
                'hostgroups'  =>
                    function (Query $q) {
                        return $q->enableAutoFields(false)->select(['id', 'uuid']);
                    },
                'Timeperiods' =>
                    function (Query $q) {
                        return $q->enableAutoFields(false)->select(['id', 'uuid']);
                    },
            ])
            ->select([
                'id',
                'uuid',
                'inherits_parent',
                'execution_fail_on_up',
                'execution_fail_on_down',
                'execution_fail_on_unreachable',
                'execution_fail_on_pending',
                'execution_none',
                'notification_fail_on_up',
                'notification_fail_on_down',
                'notification_fail_on_unreachable',
                'notification_fail_on_pending',
                'notification_none'
            ]);
        if ($uuid !== null) {
            $query->where([
                'Hostdependencies.uuid' => $uuid
            ]);
        }

        $query->all();

        return $query;

        //  return $this->emptyArrayIfNull($query->toArray());
    }
}
