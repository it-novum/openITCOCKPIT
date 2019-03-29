<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
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
    public function initialize(array $config) {
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
    public function validationDefault(Validator $validator) {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        $validator
            ->integer('is_active')
            ->requirePresence('is_active', 'create')
            ->allowEmptyString('is_active', false);

        $validator
            ->integer('number_users')
            ->requirePresence('number_users', 'create')
            ->allowEmptyString('number_users', false);

        $validator
            ->integer('max_users')
            ->requirePresence('max_users', 'create')
            ->allowEmptyString('max_users', false);

        $validator
            ->integer('number_hosts')
            ->requirePresence('number_hosts', 'create')
            ->allowEmptyString('number_hosts', false);

        $validator
            ->integer('number_services')
            ->requirePresence('number_services', 'create')
            ->allowEmptyString('number_services', false);

        $validator
            ->scalar('firstname')
            ->maxLength('firstname', 255)
            ->allowEmptyString('firstname');

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 255)
            ->allowEmptyString('lastname');

        $validator
            ->scalar('street')
            ->maxLength('street', 255)
            ->allowEmptyString('street');

        $validator
            ->integer('zipcode')
            ->allowEmptyString('zipcode');

        $validator
            ->scalar('city')
            ->maxLength('city', 255)
            ->allowEmptyString('city');

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
    public function buildRules(RulesChecker $rules) {
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
}
