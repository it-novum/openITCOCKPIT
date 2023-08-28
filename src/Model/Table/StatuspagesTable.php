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

use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\Association\BelongsToMany;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Filter\StatuspagesFilter;
use App\Lib\Traits\PaginationAndScrollIndexTrait;

/**
 * Statuspages Model
 *
 * @property \App\Model\Table\StatuspageItemsTable&\Cake\ORM\Association\HasMany $StatuspageItems
 * @property ContainersTable&HasMany $StatuspagesToContainers
 *
 * @method \App\Model\Entity\Statuspage newEmptyEntity()
 * @method \App\Model\Entity\Statuspage newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Statuspage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Statuspage get($primaryKey, $options = [])
 * @method \App\Model\Entity\Statuspage findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Statuspage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Statuspage[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Statuspage|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Statuspage saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StatuspagesTable extends Table
{
    use PaginationAndScrollIndexTrait;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('statuspages');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('StatuspageItems', [
            'foreignKey' => 'statuspage_id',
        ])->setDependent(true);

        $this->belongsToMany('Containers', [
            'className'        => 'Containers',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'container_id',
            'joinTable'        => 'statuspages_to_containers'
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
            ->requirePresence('containers', true, __('You have to choose at least one option.'))
            ->allowEmptyString('containers', null, false)
            ->multipleOptions('containers', [
                'min' => 1
            ], __('You have to choose at least one option.'));

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 1000)
            ->allowEmptyString('description');

        $validator
            ->boolean('public')
            ->notEmptyString('public');

        $validator
            ->boolean('show_comments')
            ->notEmptyString('show_comments');

        return $validator;
    }

    /**
     * @param StatuspagesFilter $StatuspagesFilter
     * @param $PaginateOMat
     * @param $MY_RIGHTS
     * @return array
     */
    public function getStatuspagesIndex(StatuspagesFilter $StatuspagesFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        $query = $this->find('all');
        $query->contain(['Containers']);
        $query->where($StatuspagesFilter->indexFilter());

        $query->innerJoinWith('Containers', function (Query $q) use ($MY_RIGHTS) {
            if (!empty($MY_RIGHTS)) {
                return $q->where(['Containers.id IN' => $MY_RIGHTS]);
            }
            return $q;
        });

        $query->distinct('Statuspages.id');

        $query->disableHydration();
        $query->order($StatuspagesFilter->getOrderForPaginator('Statuspages.name', 'asc'));

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
