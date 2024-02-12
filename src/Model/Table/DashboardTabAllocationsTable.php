<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DashboardTabAllocations Model
 *
 * @property \App\Model\Table\DashboardTabsTable&\Cake\ORM\Association\BelongsTo $DashboardTabs
 * @property \App\Model\Table\ContainersTable&\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\UsergroupsToDashboardTabAllocationsTable&\Cake\ORM\Association\HasMany $UsergroupsToDashboardTabAllocations
 * @property \App\Model\Table\UsersToDashboardTabAllocationsTable&\Cake\ORM\Association\HasMany $UsersToDashboardTabAllocations
 *
 * @method \App\Model\Entity\DashboardTabAllocation newEmptyEntity()
 * @method \App\Model\Entity\DashboardTabAllocation newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\DashboardTabAllocation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DashboardTabAllocation get($primaryKey, $options = [])
 * @method \App\Model\Entity\DashboardTabAllocation findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\DashboardTabAllocation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DashboardTabAllocation[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DashboardTabAllocation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DashboardTabAllocation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DashboardTabAllocation[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DashboardTabAllocation[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\DashboardTabAllocation[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DashboardTabAllocation[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DashboardTabAllocationsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('dashboard_tab_allocations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('DashboardTabs', [
            'foreignKey' => 'dashboard_tab_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('UsergroupsToDashboardTabAllocations', [
            'foreignKey' => 'dashboard_tab_allocation_id',
        ]);
        $this->hasMany('UsersToDashboardTabAllocations', [
            'foreignKey' => 'dashboard_tab_allocation_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('dashboard_tab_id')
            ->notEmptyString('dashboard_tab_id');

        $validator
            ->integer('container_id')
            ->notEmptyString('container_id');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->integer('pinned')
            ->notEmptyString('pinned');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('dashboard_tab_id', 'DashboardTabs'), ['errorField' => 'dashboard_tab_id']);
        $rules->add($rules->existsIn('container_id', 'Containers'), ['errorField' => 'container_id']);
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
