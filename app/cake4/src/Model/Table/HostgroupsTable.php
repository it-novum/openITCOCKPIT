<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\HostgroupConditions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\HostgroupFilter;

/**
 * Hostgroups Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\HostsTable|\Cake\ORM\Association\BelongsToMany $Hosts
 * @property \App\Model\Table\HosttemplatesTable|\Cake\ORM\Association\BelongsToMany $Hosttemplates
 *
 * @method \App\Model\Entity\Hostgroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Hostgroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Hostgroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Hostgroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Hostgroup|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Hostgroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Hostgroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Hostgroup findOrCreate($search, callable $callback = null, $options = [])
 */
class HostgroupsTable extends Table {

    use PaginationAndScrollIndexTrait;
    use Cake2ResultTableTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('hostgroups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
        ])->setDependent(true);

        $this->belongsToMany('Hosts', [
            'className'        => 'Hosts',
            'foreignKey'       => 'hostgroup_id',
            'targetForeignKey' => 'host_id',
            'joinTable'        => 'hosts_to_hostgroups',
            'saveStrategy'     => 'replace'
        ]);
        $this->belongsToMany('Hosttemplates', [
            'className'        => 'Hosttemplates',
            'foreignKey'       => 'hostgroup_id',
            'targetForeignKey' => 'hosttemplate_id',
            'joinTable'        => 'hosttemplates_to_hostgroups',
            'saveStrategy'     => 'replace'
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
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', false)
            ->allowEmptyString('description', null, true);

        $validator
            ->scalar('hostgroup_url')
            ->maxLength('hostgroup_url', 255)
            ->allowEmptyString('hostgroup_url', null, true)
            ->url('hostgroup_url', __('Not a valid URL.'));

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
        $rules->add($rules->existsIn(['container_id'], 'Containers'));

        return $rules;
    }

    /**
     * @param array $containerIds
     * @param string $type
     * @param string $index
     * @return array|\Cake\ORM\Query
     * @deprecated Use self::getHostgroupsByContainerId()
     */
    public function hostgroupsByContainerId($containerIds = [], $type = 'all', $index = 'container_id') {
        return $this->getHostgroupsByContainerId($containerIds, $type, $index);
    }

    /**
     * @param array $containerIds
     * @param string $type
     * @param string $index
     * @return array
     */
    public function getHostgroupsByContainerId($containerIds = [], $type = 'all', $index = 'container_id') {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $tenantContainerIds = [];

        foreach ($containerIds as $container_id) {
            if ($container_id != ROOT_CONTAINER) {
                // Get contaier id of the tenant container
                // $container_id is may be a location, devicegroup or whatever, so we need to container id of the tenant container to load contactgroups and contacts
                $path = $ContainersTable->getPathByIdAndCacheResult($container_id, 'HostgroupHostgroupsByContainerId');

                // Tenant host groups are available for all users of a tenant (oITC V2 legacy)
                $tenantContainerIds[] = $path[1]['id'];
            } else {
                $tenantContainerIds[] = ROOT_CONTAINER;
            }
        }
        $tenantContainerIds = array_unique($tenantContainerIds);
        $containerIds = array_unique(array_merge($tenantContainerIds, $containerIds));


        switch ($type) {
            case 'all':
                $query = $this->find()
                    ->contain([
                        'Containers'
                    ])
                    ->where([
                        'Containers.parent_id IN'     => $containerIds,
                        'Containers.containertype_id' => CT_HOSTGROUP
                    ])
                    ->order([
                        'Containers.name' => 'ASC'
                    ])
                    ->disableHydration()
                    ->all();

                return $this->emptyArrayIfNull($query->toArray());


            default:
                $query = $this->find()
                    ->contain([
                        'Containers'
                    ])
                    ->where([
                        'Containers.parent_id IN'     => $containerIds,
                        'Containers.containertype_id' => CT_HOSTGROUP
                    ])
                    ->order([
                        'Containers.name' => 'ASC'
                    ])
                    ->disableHydration()
                    ->all();

                $query = $query->toArray();
                if (empty($query)) {
                    $query = [];
                }


                $return = [];
                foreach ($query as $hostgroup) {
                    if ($index === 'id') {
                        $return[$hostgroup['id']] = $hostgroup['container']['name'];
                    } else {
                        $return[$hostgroup['container_id']] = $hostgroup['container']['name'];
                    }
                }

                return $return;
        }
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getHostgroupsAsList($ids = [], $MY_RIGHTS = []) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $query = $this->find()
            ->select([
                'Hostgroups.id',
                'Containers.name'
            ])
            ->contain(['Containers'])
            ->disableHydration();

        $where = [];
        if (!empty($ids)) {
            $where = [
                'Hostgroups.id IN'            => $ids,
                'Containers.containertype_id' => CT_HOSTGROUP
            ];
        }

        if (!empty($MY_RIGHTS)) {
            $where['Containers.parent_id IN'] = $MY_RIGHTS;
        }

        if (!empty($where)) {
            $query->where($where);
        }

        $result = $query->toArray();
        if (empty($result)) {
            return [];
        }

        $list = [];
        foreach ($result as $row) {
            $list[$row['id']] = $row['container']['name'];
        }

        return $list;
    }

    /**
     * @param HostgroupFilter $HostgroupFilter
     * @param null|PaginateOMat $PaginateOMat
     * @return array
     * @package array $MY_RIGHTS
     */
    public function getHostgroupsIndex(HostgroupFilter $HostgroupFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        $query = $this->find()
            ->contain([
                'containers'
            ]);

        $where = $HostgroupFilter->indexFilter();
        if (!empty($MY_RIGHTS)) {
            $where['Containers.parent_id IN'] = $MY_RIGHTS;
        }
        $query->where($where);

        $query->order($HostgroupFilter->getOrderForPaginator('Containers.name', 'asc'));

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
     * @param array $dataToParse
     * @return array
     */
    public function resolveDataForChangelog($dataToParse = []) {
        $extDataForChangelog = [
            'Host'         => [],
            'Hosttemplate' => [],
        ];

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');

        if (!empty($dataToParse['Hostgroup']['hosts']['_ids'])) {
            foreach ($HostsTable->getHostsAsList($dataToParse['Hostgroup']['hosts']['_ids']) as $hostId => $hostName) {
                $extDataForChangelog['Host'][] = [
                    'id'   => $hostId,
                    'name' => $hostName
                ];
            }
        }

        if (!empty($dataToParse['Hostgroup']['hosttemplates']['_ids'])) {
            foreach ($HosttemplatesTable->getHosttemplatesAsList($dataToParse['Hostgroup']['hosttemplates']['_ids']) as $hosttemplateId => $hosttemplateName) {
                $extDataForChangelog['Hosttemplate'][] = [
                    'id'   => $hosttemplateId,
                    'name' => $hosttemplateName
                ];
            }
        }

        return $extDataForChangelog;
    }

    public function getHostgroupForEdit($id) {
        $query = $this->find()
            ->where([
                'Hostgroups.id' => $id
            ])
            ->contain([
                'Hosts',
                'Hosttemplates',
                'Containers'
            ])
            ->disableHydration()
            ->first();

        $hostgroup = $query;
        $hostgroup['hosts'] = [
            '_ids' => Hash::extract($query, 'hosts.{n}.id')
        ];
        $hostgroup['hosttemplates'] = [
            '_ids' => Hash::extract($query, 'hosttemplates.{n}.id')
        ];

        return [
            'Hostgroup' => $hostgroup
        ];
    }

    /**
     * @param int $id
     * @return \App\Model\Entity\Hostgroup
     */
    public function getHostgroupById($id) {
        return $this->get($id, [
            'contain' => [
                'Containers'
            ]
        ]);
    }

    /**
     * @param HostgroupConditions $HostgroupConditions
     * @param array|int $selected
     * @return array|null
     */
    public function getHostgroupsForAngular(HostgroupConditions $HostgroupConditions, $selected = []) {
        if (!is_array($selected)) {
            $selected = [$selected];
        }
        $selected = array_filter($selected);


        $where = $HostgroupConditions->getConditionsForFind();

        if (!empty($selected)) {
            $where['NOT'] = [
                'Hostgroups.id IN' => $selected
            ];
        }

        $query = $this->find()
            ->contain([
                'containers'
            ])
            ->select([
                'Containers.name',
                'Hostgroups.id'
            ])
            ->where(
                $where
            )
            ->order([
                'Containers.name' => 'asc'
            ])
            ->limit(ITN_AJAX_LIMIT)
            ->disableHydration()
            ->all();

        $hostgroupsWithLimit = [];
        $result = $this->emptyArrayIfNull($query->toArray());
        foreach ($result as $row) {
            $hostgroupsWithLimit[$row['id']] = $row['Containers']['name'];
        }

        $selectedHostgroups = [];
        if (!empty($selected)) {
            $query = $this->find()
                ->contain([
                    'containers'
                ])
                ->select([
                    'Containers.name',
                    'Hostgroups.id'
                ])
                ->where([
                    'Hostgroups.id IN' => $selected
                ])
                ->order([
                    'Containers.name' => 'asc'
                ])
                ->limit(ITN_AJAX_LIMIT)
                ->disableHydration()
                ->all();

            $selectedHostgroups = [];
            $result = $this->emptyArrayIfNull($query->toArray());
            foreach ($result as $row) {
                $selectedHostgroups[$row['id']] = $row['Containers']['name'];
            }
        }

        $hostgroups = $hostgroupsWithLimit + $selectedHostgroups;
        asort($hostgroups, SORT_FLAG_CASE | SORT_NATURAL);

        return $hostgroups;
    }

    /**
     * @param HostgroupConditions $HostgroupConditions
     * @param array|int $selected
     * @return array|null
     */
    public function getHostgroupsByContainerIdNew(HostgroupConditions $HostgroupConditions) {
        $where = $HostgroupConditions->getConditionsForFind();

        $query = $this->find()
            ->contain([
                'containers'
            ])
            ->select([
                'Containers.name',
                'Hostgroups.id'
            ])
            ->where(
                $where
            )
            ->order([
                'Containers.name' => 'asc'
            ])
            ->disableHydration()
            ->all();

        $hostgroups = [];
        $result = $this->emptyArrayIfNull($query->toArray());
        foreach ($result as $row) {
            $hostgroups[$row['id']] = $row['Containers']['name'];
        }

        return $hostgroups;
    }

    /**
     * @param HostgroupFilter $HostgroupFilter
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getHostgroupsForPdf(HostgroupFilter $HostgroupFilter, $MY_RIGHTS = []) {

        $query = $this->find()
            ->contain([
                'hosts',
                'Containers'
            ]);

        $where = $HostgroupFilter->indexFilter();
        if (!empty($MY_RIGHTS)) {
            $where['Containers.parent_id IN'] = $MY_RIGHTS;
        }
        $query->where($where)
            ->order(
                $HostgroupFilter->getOrderForPaginator('Containers.name', 'asc')
            )->disableHydration();

        $data = $query->all();
        return $this->emptyArrayIfNull($data->toArray());
    }

    /**
     * @return array
     */
    public function getHostgroupsForExport() {
        $query = $this->find()
            ->select([
                'Hostgroups.id',
                'Hostgroups.uuid',
                'Hostgroups.description'
            ])
            ->all();

        return $this->emptyArrayIfNull($query->toArray());
    }

    /**
     * @param int $id
     * @return string
     */
    public function getHostgroupUuidById($id) {
        $query = $this->find()
            ->select([
                'Hostgroups.uuid',
            ])
            ->where([
                'Hostgroups.id' => $id
            ]);

        $hostgroup = $query->firstOrFail();

        return $hostgroup->get('uuid');
    }

    /**
     * @param string $uuid
     * @return array
     */
    public function getHostsByHostgroupUuidForExternalcommands($uuid) {
        $query = $this->find()
            ->select([
                'Hostgroups.id'
            ])
            ->where([
                'Hostgroups.uuid' => $uuid,
            ])
            ->contain([
                'hosts' =>
                    function (Query $q) {
                        return $q->enableAutoFields(false)
                            ->select([
                                'Hosts.id',
                                'Hosts.uuid',
                                'Hosts.satellite_id',
                                'Hosts.active_checks_enabled'
                            ])
                            ->where([
                                'Hosts.disabled' => 0
                            ])
                            ->contain([
                                'hosttemplates' =>
                                    function (Query $q) {
                                        return $q->enableAutoFields(false)
                                            ->select([
                                                'Hosttemplates.active_checks_enabled'
                                            ]);
                                    }
                            ]);
                    },
            ])->disableHydration();
        try {
            $result = $query->firstOrFail();
            return $result;
        } catch (RecordNotFoundException $e) {
            return [];
        }
    }

    /**
     * @param string $uuid
     * @return array
     */
    public function getHostsByHostgroupUuidForExternalcommandsIncludeingHosttemplateHosts($uuid) {
        $query = $this->find()
            ->select([
                'Hostgroups.id'
            ])
            ->where([
                'Hostgroups.uuid' => $uuid,
            ])
            ->contain([
                'hosts'         =>
                    function (Query $q) {
                        return $q->enableAutoFields(false)
                            ->select([
                                'Hosts.id',
                                'Hosts.uuid',
                                'Hosts.satellite_id',
                                'Hosts.active_checks_enabled'
                            ])
                            ->where([
                                'Hosts.disabled' => 0
                            ])
                            ->contain([
                                'hosttemplates' =>
                                    function (Query $q) {
                                        return $q->enableAutoFields(false)
                                            ->select([
                                                'Hosttemplates.id',
                                                'Hosttemplates.active_checks_enabled'
                                            ]);
                                    }
                            ]);
                    },
                'hosttemplates' =>
                    function (Query $q) {
                        return $q->enableAutoFields(false)
                            ->select([
                                'Hosttemplates.id',
                            ]);
                    },
            ])->disableHydration();
        try {
            $tmpResult = $query->firstOrFail();
            /** @var $HostsTable HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

            $hosts = [];
            foreach ($tmpResult['hosts'] as $host) {
                $hosts[$host['id']] = $host;
            }

            foreach ($tmpResult['hosttemplates'] as $hosttemplate) {
                $hostsFromHosttemplate = $HostsTable->find()
                    ->select([
                        'Hosts.id',
                        'Hosts.uuid',
                        'Hosts.satellite_id',
                        'Hosts.active_checks_enabled'
                    ])
                    ->where([
                        'Hosts.hosttemplate_id' => $hosttemplate['id'],
                        'Hosts.disabled'        => 0
                    ])
                    ->contain([
                        'hosttemplates' =>
                            function (Query $q) {
                                return $q->enableAutoFields(false)
                                    ->select([
                                        'Hosttemplates.id',
                                        'Hosttemplates.active_checks_enabled'
                                    ]);
                            }
                    ])
                    ->disableHydration()
                    ->all();

                //Merge Hosts from host templates
                $hostsFromHosttemplate = $this->emptyArrayIfNull($hostsFromHosttemplate->toArray());
                foreach ($hostsFromHosttemplate as $hostFromHosttemplate) {
                    $hosts[$hostFromHosttemplate['id']] = $hostFromHosttemplate;
                }
            }

            $tmpResult['hosts'] = $hosts;

            return $tmpResult;
        } catch (RecordNotFoundException $e) {
            return [];
        }
    }

    /**
     * @param int $id
     * @param array $MY_RIGHTS
     * @return array
     * @throws RecordNotFoundException
     */
    public function getHostsByHostgroupForMaps($id, $MY_RIGHTS = []) {
        $where = [
            'Hostgroups.id' => $id
        ];
        if (!empty($MY_RIGHTS)) {
            $where['Containers.parent_id IN'] = $MY_RIGHTS;
        }

        $hostgroup = $this->find()
            ->select([
                'Hostgroups.id',
                'Hostgroups.description',
                'Containers.name'
            ])
            ->contain([
                'containers',
                'hosts' => function (Query $q) {
                    return $q->enableAutoFields(false)
                        ->select([
                            'Hosts.id',
                            'Hosts.uuid',
                            'Hosts.name',
                            'Hosts.description'
                        ])
                        ->contain(['HostsToContainersSharing']);
                }
            ])
            ->where($where)
            ->disableHydration()
            ->firstOrFail();

        return $hostgroup;
    }


    /**
     * @param int $id
     * @return array
     */
    public function getHostIdsByHostgroupId($id) {
        $hostgroup = $this->find()
            ->contain([
                // Get all hosts that are in this host group through the host template AND
                // which does NOT have any own host groups
                'hosttemplates' => function (Query $query) {
                    $query->disableAutoFields()
                        ->select([
                            'id',
                        ])
                        ->contain([
                            'hosts' => function (Query $query) {
                                $query->disableAutoFields()
                                    ->select([
                                        'Hosts.id',
                                        'Hosts.uuid',
                                        'Hosts.hosttemplate_id',
                                        'Hostgroups.id'
                                    ])
                                    ->leftJoinWith('Hostgroups')
                                    ->whereNull('Hostgroups.id');
                                return $query;
                            }
                        ]);
                    return $query;
                },

                // Get all hosts from this host group
                'hosts'         => function (Query $query) {
                    $query->disableAutoFields()
                        ->select([
                            'Hosts.id',
                            'Hosts.uuid',
                        ]);
                    return $query;
                }
            ])
            ->where([
                'Hostgroups.id' => $id
            ])
            ->disableHydration()
            ->first();


        $hostIds = array_unique(array_merge(
            Hash::extract($hostgroup, 'hosts.{n}.id'),
            Hash::extract($hostgroup, 'hosttemplates.{n}.hosts.{n}.id')
        ));

        return $hostIds;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Hostgroups.id' => $id]);
    }

    /**
     * @param $name
     * @param array $MY_RIGHTS
     * @return array|\Cake\Datasource\EntityInterface
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function getHostgroupByName($name, $MY_RIGHTS = []) {
        if (!is_array($MY_RIGHTS)) {
            $MY_RIGHTS = [$MY_RIGHTS];
        }

        $query = $this->find()
            ->contain([
                'Containers'
            ])
            ->where([
                'Containers.name' => $name
            ]);

        if (!empty($MY_RIGHTS)) {
            $query->andWhere([
                'Containers.parent_id IN' => $MY_RIGHTS
            ]);
        }

        $result = $query->firstOrFail();

        return $result;
    }

    /**
     * @param int $hostId
     * @param int|array $hostgroupIds
     * @return bool
     */
    public function isHostInHostgroup($hostId, $hostgroupIds) {
        if (!is_array($hostgroupIds)) {
            $hostgroupIds = [$hostgroupIds];
        }
        $hostgroupIds = array_unique($hostgroupIds);

        /** @var HostsToHostgroupsTable $HostsToHostgroupsTable */
        $HostsToHostgroupsTable = TableRegistry::getTableLocator()->get('HostsToHostgroups');

        $count = $HostsToHostgroupsTable->find()
            ->where([
                'host_id'         => $hostId,
                'hostgroup_id IN' => $hostgroupIds
            ])
            ->count();

        if ($count > 0) {
            return true;
        }

        // Through hosttemplate maybe?
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        $host = $HostsTable->get($hostId, [
            'contain' => [
                'Hostgroups'
            ]
        ]);

        if (empty($host->get('hostgroups'))) {
            /** @var HosttemplatesToHostgroupsTable $HosttemplatesToHostgroupsTable */
            $HosttemplatesToHostgroupsTable = TableRegistry::getTableLocator()->get('HosttemplatesToHostgroups');
            $count = $HosttemplatesToHostgroupsTable->find()
                ->where([
                    'hosttemplate_id' => $host->get('hosttemplate_id'),
                    'hostgroup_id IN' => $hostgroupIds
                ])
                ->count();

            return $count > 0;
        }

        return false;
    }
}
