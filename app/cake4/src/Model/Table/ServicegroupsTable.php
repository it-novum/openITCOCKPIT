<?php

namespace App\Model\Table;

use App\Lib\Traits\PaginationAndScrollIndexTrait;
use App\Model\Entity\Servicegroup;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\ServicegroupConditions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ServicegroupFilter;

/**
 * Servicegroups Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\ServicesTable|\Cake\ORM\Association\HasMany $Services
 * @property \App\Model\Table\ServicetemplatesTable|\Cake\ORM\Association\HasMany $Servicetemplates
 *
 * @method \App\Model\Entity\Servicegroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Servicegroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Servicegroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Servicegroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Servicegroup saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Servicegroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Servicegroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Servicegroup findOrCreate($search, callable $callback = null, $options = [])
 */
class ServicegroupsTable extends Table {

    use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('servicegroups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
        ]);

        $this->belongsToMany('Services', [
            'className'        => 'Services',
            'foreignKey'       => 'servicegroup_id',
            'targetForeignKey' => 'service_id',
            'joinTable'        => 'services_to_servicegroups',
            'saveStrategy'     => 'replace'
        ]);

        $this->belongsToMany('Servicetemplates', [
            'className'        => 'Servicetemplates',
            'foreignKey'       => 'servicegroup_id',
            'targetForeignKey' => 'servicetemplate_id',
            'joinTable'        => 'servicetemplates_to_servicegroups',
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
            ->requirePresence('description', 'create')
            ->allowEmptyString('description', null, true);

        $validator
            ->scalar('servicegroup_url')
            ->maxLength('servicegroup_url', 255)
            ->allowEmptyString('servicegroup_url')
            ->url('servicegroup_url', __('Not a valid URL.'));

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
     * @deprecated Use self::getServicegroupsByContainerId()
     */
    public function servicegroupsByContainerId($containerIds = [], $type = 'all', $index = 'container_id') {
        return $this->getServicegroupsByContainerId($containerIds, $type, $index);
    }


    /**
     * @param array|int $containerIds
     * @param string $type
     * @param string $index
     * @return array|null
     */
    public function getServicegroupsByContainerId($containerIds = [], $type = 'all', $index = 'id') {
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
                $path = $ContainersTable->getPathByIdAndCacheResult($container_id, 'ServicegroupServicegroupsByContainerId');

                // Tenant service groups are available for all users of a tenant (oITC V2 legacy)
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
                        'Containers.containertype_id' => CT_SERVICEGROUP,
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
                        'Containers.containertype_id' => CT_SERVICEGROUP,
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
                foreach ($query as $servicegroup) {
                    if ($index === 'id') {
                        $return[$servicegroup['id']] = $servicegroup['container']['name'];
                    } else {
                        $return[$servicegroup['container_id']] = $servicegroup['container']['name'];
                    }
                }

                return $return;
        }
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getServicegroupsAsList($ids = [], $MY_RIGHTS = []) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $query = $this->find()
            ->select([
                'Servicegroups.id',
                'Containers.name'
            ])
            ->contain(['Containers'])
            ->disableHydration();

        $where = [];
        if (!empty($ids)) {
            $where = [
                'Servicegroups.id IN'         => $ids,
                'Containers.containertype_id' => CT_SERVICEGROUP
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
     * @return array
     */
    public function getServicegroupsForExport() {
        $query = $this->find()
            ->select([
                'Servicegroups.id',
                'Servicegroups.uuid',
                'Servicegroups.description'
            ])
            ->all();

        return $this->emptyArrayIfNull($query->toArray());
    }

    /**
     * @param ServicegroupFilter $ServicegroupFilter
     * @param null|PaginateOMat $PaginateOMat
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getServicegroupsIndex(ServicegroupFilter $ServicegroupFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        $query = $this->find('all');
        $query->contain(['Containers']);
        $query->where($ServicegroupFilter->indexFilter());

        $query->innerJoinWith('Containers', function (Query $q) use ($MY_RIGHTS) {
            if (!empty($MY_RIGHTS)) {
                return $q->where(['Containers.parent_id IN' => $MY_RIGHTS]);
            }
            return $q;
        });


        $query->disableHydration();
        $query->order($ServicegroupFilter->getOrderForPaginator('Containers.name', 'asc'));


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
     * @param ServicegroupFilter $ServicegroupFilter
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getServicegroupsForPdf(ServicegroupFilter $ServicegroupFilter, $MY_RIGHTS = []) {

        $query = $this->find()
            ->contain([
                'services',
                'Containers'
            ]);

        $where = $ServicegroupFilter->indexFilter();
        if (!empty($MY_RIGHTS)) {
            $where['Containers.parent_id IN'] = $MY_RIGHTS;
        }
        $query->where($where)
            ->order(
                $ServicegroupFilter->getOrderForPaginator('Containers.name', 'asc')
            )->disableHydration();

        $data = $query->all();
        return $this->emptyArrayIfNull($data->toArray());
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Servicegroups.id' => $id]);
    }

    /**
     * @param array $dataToParse
     * @return array
     */
    public function resolveDataForChangelog($dataToParse = []) {
        $extDataForChangelog = [
            'Service'         => [],
            'Servicetemplate' => [],
        ];

        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        if (!empty($dataToParse['Servicegroup']['services']['_ids'])) {
            foreach ($ServicesTable->getServicesAsList($dataToParse['Servicegroup']['services']['_ids'], true) as $serviceId => $serviceName) {
                $extDataForChangelog['Service'][] = [
                    'id'   => $serviceId,
                    'name' => $serviceName
                ];
            }
        }

        if (!empty($dataToParse['Servicegroup']['servicetemplates']['_ids'])) {
            foreach ($ServicetemplatesTable->getServicetemplatesAsList($dataToParse['Servicegroup']['servicetemplates']['_ids']) as $servicetemplateId => $servicetemplateName) {
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
     * @return array
     */
    public function getServicegroupForEdit($id) {
        $query = $this->find()
            ->where([
                'Servicegroups.id' => $id
            ])
            ->contain([
                'Services',
                'Servicetemplates',
                'Containers'
            ])
            ->disableHydration()
            ->first();

        $hostgroup = $query;
        $hostgroup['services'] = [
            '_ids' => Hash::extract($query, 'services.{n}.id')
        ];
        $hostgroup['servicetemplates'] = [
            '_ids' => Hash::extract($query, 'servicetemplates.{n}.id')
        ];

        return [
            'Servicegroup' => $hostgroup
        ];
    }

    /**
     * @param int $id
     * @return \App\Model\Entity\Servicegroup
     */
    public function getServicegroupById($id) {
        return $this->get($id, [
            'contain' => [
                'Containers'
            ]
        ]);
    }

    /**
     * @param $id
     * @return array
     */
    public function getServicegroupByIdForMapeditor($id) {
        $query = $this->find()
            ->contain([
                'Containers' => function (Query $q) {
                    return $q->select([
                        'Containers.id',
                        'Containers.name'
                    ]);
                },
                'Services'   => function (Query $q) {
                    return $q->contain([
                        'Servicetemplates' => function (Query $q) {
                            return $q->select([
                                'Servicetemplates.id',
                                'Servicetemplates.name'
                            ]);
                        },
                        'Hosts'            => function (Query $q) {
                            return $q->contain([
                                'HostsToContainersSharing'
                            ])->select([
                                'Hosts.id',
                                'Hosts.uuid'
                            ])->where([
                                'Hosts.disabled' => 0
                            ]);
                        }
                    ])->select([
                        'Services.id',
                        'Services.uuid',
                        'Services.name'
                    ])->where([
                        'Services.disabled' => 0
                    ]);
                }
            ])
            ->where([
                'Servicegroups.id' => $id
            ])
            ->select([
                'Servicegroups.id',
                'Servicegroups.description'
            ]);

        $result = $query->all();
        if (empty($result)) {
            return [];
        }
        return $result->toArray();
    }

    /**
     * @param $id
     * @param array $MY_RIGHTS
     * @return array|Servicegroup
     */
    public function getServicegroupsByServicegroupForMaps($id, $MY_RIGHTS = []) {
        $where = [
            'Servicegroups.id' => $id
        ];
        if (!empty($MY_RIGHTS)) {
            $where['Containers.parent_id IN'] = $MY_RIGHTS;
        }

        $servicegroup = $this->find()
            ->select([
                'Servicegroups.id',
                'Servicegroups.description',
                'Containers.name',
            ])
            ->contain([
                'Containers',
                'Services' => function (Query $q) {
                    return $q->enableAutoFields(false)
                        ->select([
                            'Services.id',
                            'Services.uuid',
                            'Services.name'
                        ])
                        ->contain([
                            'Hosts'            => function (Query $q) {
                                return $q->enableAutoFields(false)
                                    ->select([
                                        'Hosts.id',
                                        'Hosts.uuid'
                                    ])
                                    ->contain(['HostsToContainersSharing'])
                                    ->where(['Hosts.disabled' => 0]);
                            },
                            'Servicetemplates' => function (Query $q) {
                                return $q->enableAutoFields(false)
                                    ->select([
                                        'Servicetemplates.name'
                                    ]);
                            },
                        ])
                        ->where(['Services.disabled' => 0]);
                }
            ])
            ->where($where)
            ->disableHydration()
            ->firstOrFail();

        return $servicegroup;
    }

    /**
     * @param ServicegroupConditions $ServicegroupConditions
     * @param array $selected
     * @return array
     */
    public function getServicegroupsForAngular(ServicegroupConditions $ServicegroupConditions, $selected = []) {

        $query = $this->find()
            ->contain([
                'Containers'
            ])
            ->where([
                'Containers.containertype_id' => CT_SERVICEGROUP,
            ]);

        if (!empty($ServicegroupConditions->getContainerIds())) {
            $query->andWhere([
                'Containers.parent_id IN' => $ServicegroupConditions->getContainerIds()
            ]);
        }

        if (!empty($ServicegroupConditions->getConditions())) {
            $query->andWhere(
                $ServicegroupConditions->getConditions()
            );
        }

        $query
            ->order([
                'Containers.name' => 'ASC'
            ])
            ->group([
                'Containers.id'
            ])
            ->disableHydration()
            ->limit(ITN_AJAX_LIMIT)
            ->all();

        $result = $query->toArray();

        $resultAslist = [];
        foreach ($result as $record) {
            $resultAslist[$record['id']] = $record['container']['name'];
        }

        if (!empty($selected)) {
            $query = $this->find()
                ->contain([
                    'Containers'
                ])
                ->where([
                    'Servicegroups.id IN'         => $selected,
                    'Containers.containertype_id' => CT_SERVICEGROUP,
                ]);

            if (!empty($ServicegroupConditions->getContainerIds())) {
                $query->andWhere([
                    'Containers.parent_id IN' => $ServicegroupConditions->getContainerIds()
                ]);
            }

            $query
                ->order([
                    'Containers.name' => 'ASC'
                ])
                ->group([
                    'Containers.id'
                ])
                ->disableHydration()
                ->limit(ITN_AJAX_LIMIT)
                ->all();


            foreach ($query->toArray() as $selectedServicegroup) {
                $resultAslist[$selectedServicegroup['id']] = $selectedServicegroup['container']['name'];
            }

        }

        return $resultAslist;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getServiceIdsByServicegroupId($id) {
        $servicegroup = $this->find()
            ->contain([
                // Get all services that are in this service group through the service template AND
                // which does NOT have any own service groups
                'servicetemplates' => function (Query $query) {
                    $query->disableAutoFields()
                        ->select([
                            'id',
                        ])
                        ->contain([
                            'services' => function (Query $query) {
                                $query->disableAutoFields()
                                    ->select([
                                        'Services.id',
                                        'Services.uuid',
                                        'Services.servicetemplate_id',
                                        'Servicegroups.id'
                                    ])
                                    ->leftJoinWith('Servicegroups')
                                    ->whereNull('Servicegroups.id');
                                return $query;
                            }
                        ]);
                    return $query;
                },

                // Get all services from this service group
                'services'         => function (Query $query) {
                    $query->disableAutoFields()
                        ->select([
                            'Services.id',
                            'Services.uuid',
                        ]);
                    return $query;
                }
            ])
            ->where([
                'Servicegroups.id' => $id
            ])
            ->disableHydration()
            ->first();


        $serviceIds = array_unique(array_merge(
            Hash::extract($servicegroup, 'services.{n}.id'),
            Hash::extract($servicegroup, 'servicetemplates.{n}.services.{n}.id')
        ));

        return $serviceIds;
    }

    /**
     * @param int $serviceId
     * @param int|array $servicegroupIds
     * @return bool
     */
    public function isServiceInServicegroup($serviceId, $servicegroupIds) {
        if (!is_array($servicegroupIds)) {
            $servicegroupIds = [$servicegroupIds];
        }
        $servicegroupIds = array_unique($servicegroupIds);

        /** @var ServicesToServicegroupsTable $ServicesToServicegroupsTable */
        $ServicesToServicegroupsTable = TableRegistry::getTableLocator()->get('ServicesToServicegroups');

        $count = $ServicesToServicegroupsTable->find()
            ->where([
                'service_id'         => $serviceId,
                'servicegroup_id IN' => $servicegroupIds
            ])
            ->count();

        if ($count > 0) {
            return true;
        }

        // Through servicetemplate maybe?
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        $service = $ServicesTable->get($serviceId, [
            'contain' => [
                'Servicegroups'
            ]
        ]);

        if (empty($service->get('servicegroups'))) {
            /** @var ServicetemplatesToServicegroupsTable $ServicetemplatesToServicegroupsTable */
            $ServicetemplatesToServicegroupsTable = TableRegistry::getTableLocator()->get('ServicetemplatesToServicegroups');
            $count = $ServicetemplatesToServicegroupsTable->find()
                ->where([
                    'servicetemplate_id' => $service->get('servicetemplate_id'),
                    'servicegroup_id IN' => $servicegroupIds
                ])
                ->count();

            return $count > 0;
        }

        return false;
    }
}
