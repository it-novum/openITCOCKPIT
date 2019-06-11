<?php

namespace App\Model\Table;

use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ServicegroupFilter;

/**
 * Servicegroups Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\ServicegroupsToServicedependenciesTable|\Cake\ORM\Association\HasMany $ServicegroupsToServicedependencies
 * @property \App\Model\Table\ServicegroupsToServiceescalationsTable|\Cake\ORM\Association\HasMany $ServicegroupsToServiceescalations
 * @property \App\Model\Table\ServicesToServicegroupsTable|\Cake\ORM\Association\HasMany $ServicesToServicegroups
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
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('servicegroups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
        ]);

        $this->hasMany('ServicegroupsToServicedependencies', [
            'foreignKey' => 'servicegroup_id'
        ]);
        $this->hasMany('ServicegroupsToServiceescalations', [
            'foreignKey' => 'servicegroup_id'
        ]);
        $this->hasMany('ServicesToServicegroups', [
            'foreignKey' => 'servicegroup_id'
        ]);
        $this->hasMany('ServicetemplatesToServicegroups', [
            'foreignKey' => 'servicegroup_id'
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
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->allowEmptyString('description', false);

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
    public function buildRules(RulesChecker $rules) {
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
            foreach ($ServicesTable->getServicesAsList($dataToParse['Servicegroup']['services']['_ids']) as $serviceId => $serviceName) {
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
}
