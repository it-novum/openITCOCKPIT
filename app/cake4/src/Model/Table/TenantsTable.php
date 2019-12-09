<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\Core\Plugin;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use EventcorrelationModule\Model\Table\EventcorrelationsTable;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\TenantFilter;

/**
 * Tenants Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 *
 * @method \App\Model\Entity\Tenant get($primaryKey, $options = [])
 * @method \App\Model\Entity\Tenant newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Tenant[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Tenant|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Tenant|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Tenant patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Tenant[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Tenant findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TenantsTable extends Table {

    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('tenants');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
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
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        return $validator;
    }

    /**
     * @param TenantFilter $TenantFilter
     * @param null|PaginateOMat $PaginateOMat
     * @return array
     */
    public function getTenantsIndex(TenantFilter $TenantFilter, $PaginateOMat = null) {
        $query = $this->find('all')
            ->contain(['Containers'])
            ->disableHydration();
        $query->where($TenantFilter->indexFilter());
        $query->order($TenantFilter->getOrderForPaginator('Containers.name', 'asc'));

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
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->existsIn(['container_id'], 'Containers'));

        return $rules;
    }

    /**
     * @param $id
     * @return array|\Cake\Datasource\EntityInterface
     */
    public function getTenantById($id) {
        $query = $this->find()
            ->where([
                'Tenants.id' => $id
            ])
            ->contain([
                'Containers'
            ])
            ->disableHydration()
            ->firstOrFail();

        return $query;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Tenants.id' => $id]);
    }

    /**
     * @param $containerId
     * @return bool
     */
    public function allowDelete($containerId) {
        /** @var ContainersTable $ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicesTable $ServiceTable */
        $ServiceTable = TableRegistry::getTableLocator()->get('Services');

        $children = $ContainersTable->getChildren($containerId);

        $newContainerIds = Hash::extract($children, '{n}.id');
        //append the containerID itself
        $newContainerIds[] = $containerId;
        //get the hosts of these containers
        $hostIds = Hash::extract($HostsTable->getHostsByContainerIdForDelete($newContainerIds), '{n}.id');
        $serviceIds = Hash::extract($ServiceTable->getServicesByHostIdForDelete($hostIds), '{n}.id');

        //check if the host is used somwhere
        if (Plugin::loaded('EventcorrelationModule') && !empty($hostIds)) {
            /** @var EventcorrelationsTable $EventcorrelationTable */
            $EventcorrelationTable = TableRegistry::getTableLocator()->get('EventcorrelationModule.Eventcorrelations');
            $query = $EventcorrelationTable->find()
                ->where([
                    'OR' => [
                        'Eventcorrelations.host_id IN'    => $hostIds,
                        'Eventcorrelations.service_id IN' => $serviceIds,
                    ]
                ])
                ->count();

            if (!empty($query) && $query > 0) {
                return false;
            }
        }

        return true;
    }
}
