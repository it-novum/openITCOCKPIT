<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

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
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('hostgroups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
        ]);
        $this->belongsToMany('Hosts', [
            'className'        => 'Hosts',
            'foreignKey'       => 'hostgroup_id',
            'targetForeignKey' => 'host_id',
            'joinTable'        => 'hosts_to_hostgroups',
            'saveStrategy'     => 'replace'
        ]);
        $this->belongsToMany('Hosttemplates', [
            'className'        => 'Hosttemplates',
            'foreignKey'       => 'hosttemplate_id',
            'targetForeignKey' => 'host_id',
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
            ->scalar('hostgroup_url')
            ->maxLength('hostgroup_url', 255)
            ->allowEmptyString('hostgroup_url');

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

                foreach ($path as $containers) {
                    if ($containers['containertype_id'] == CT_HOSTGROUP) {
                        $tenantContainerIds[] = $containers['parent_id'];
                    }
                }
                $tenantContainerIds[] = $path[1]['id'];
            } else {
                $tenantContainerIds[] = ROOT_CONTAINER;
            }
        }
        $tenantContainerIds = array_unique($tenantContainerIds);

        $hostgroupsAsList = [];

        foreach ($tenantContainerIds as $tenantContainerId) {
            $children = $ContainersTable->find('children', ['for' => $tenantContainerId])->disableHydration()->all()->toArray();
            foreach ($children as $child) {
                if ($child['containertype_id'] == CT_HOSTGROUP) {
                    // containerId of hostgroup => hostgroup name
                    $hostgroupsAsList[$child['id']] = $child['name'];
                }
            }
        }
        if (empty($hostgroupsAsList)) {
            return [];
        }
        switch ($type) {
            case 'all':
                $query = $this->find()
                    ->contain([
                        'Containers'
                    ])
                    ->where([
                        'Containers.id IN' => array_keys($hostgroupsAsList)
                    ])
                    ->order([
                        'Containers.name' => 'ASC'
                    ])
                    ->disableHydration()
                    ->all();

                return $query->toArray();


            default:
                if ($index == 'id') {
                    $query = $this->find()
                        ->contain([
                            'Containers'
                        ])
                        ->where([
                            'Containers.id IN' => array_keys($hostgroupsAsList)
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
                        $return[$hostgroup['id']] = $hostgroup['container']['name'];
                    }

                    return $return;
                }
                asort($hostgroupsAsList);

                return $hostgroupsAsList;
        }
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getHostgroupsAsList($ids = []) {
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
        if (!empty($ids)) {
            $query->where([
                'Hostgroups.id IN'            => $ids,
                'Containers.containertype_id' => CT_HOSTGROUP
            ]);
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
}
