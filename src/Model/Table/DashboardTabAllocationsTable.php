<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

declare(strict_types=1);

namespace App\Model\Table;

use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\DashboardTabAllocationsFilter;
use itnovum\openITCOCKPIT\Filter\GenericFilter;

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
class DashboardTabAllocationsTable extends Table {

    use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('dashboard_tab_allocations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('DashboardTabs', [
            'foreignKey' => 'dashboard_tab_id',
            'joinType'   => 'INNER',
        ]);
        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType'   => 'INNER',
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
    public function validationDefault(Validator $validator): Validator {
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
            ->boolean('pinned')
            ->notEmptyString('pinned');

        $validator
            ->notEmptyArray('users_to_dashboard_tab_allocations', __('You must at least specify at least one user or user role'), function ($context) {
                return !empty($context['data']['usergroups_to_dashboard_tab_allocations']['_ids']) || !empty($context['data']['users_to_dashboard_tab_allocations']['_ids']);
            });

        $validator
            ->notEmptyArray('usergroups_to_dashboard_tab_allocations', __('You must at least specify at least one user or user role'), function ($context) {
                return !empty($context['data']['usergroups_to_dashboard_tab_allocations']['_ids']) || !empty($context['data']['users_to_dashboard_tab_allocations']['_ids']);
            });

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
        $rules->add($rules->existsIn('dashboard_tab_id', 'DashboardTabs'), ['errorField' => 'dashboard_tab_id']);
        $rules->add($rules->existsIn('container_id', 'Containers'), ['errorField' => 'container_id']);
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }

    /**
     * @param DashboardTabAllocationsFilter $DashboardTabAllocationsFilter
     * @param PaginateOMat|null $PaginateOMat
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getDashboardTabAllocationsIndex(DashboardTabAllocationsFilter $DashboardTabAllocationsFilter, ?PaginateOMat $PaginateOMat, array $MY_RIGHTS = []) {
        $query = $this->find();

        $where = $DashboardTabAllocationsFilter->indexFilter();
        if (!empty($MY_RIGHTS)) {
            $where['DashboardTabAllocations.container_id IN'] = $MY_RIGHTS;
        }
        $query->where($where);

        $query->order($DashboardTabAllocationsFilter->getOrderForPaginator('DashboardTabAllocations.name', 'asc'));

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
}
