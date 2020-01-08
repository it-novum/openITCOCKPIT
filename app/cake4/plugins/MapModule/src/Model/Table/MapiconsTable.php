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

namespace MapModule\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use MapModule\Model\Entity\Mapicon;

/**
 * Mapicons Model
 *
 * @property MapsTable&BelongsTo $Maps
 *
 * @method Mapicon get($primaryKey, $options = [])
 * @method Mapicon newEntity($data = null, array $options = [])
 * @method Mapicon[] newEntities(array $data, array $options = [])
 * @method Mapicon|false save(EntityInterface $entity, $options = [])
 * @method Mapicon saveOrFail(EntityInterface $entity, $options = [])
 * @method Mapicon patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Mapicon[] patchEntities($entities, array $data, array $options = [])
 * @method Mapicon findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin TimestampBehavior
 */
class MapiconsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('mapicons');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Maps', [
            'foreignKey' => 'map_id',
            'joinType'   => 'INNER',
            'className'  => 'MapModule.Maps',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('x')
            ->notEmptyString('x');

        $validator
            ->integer('y')
            ->notEmptyString('y');

        $validator
            ->scalar('icon')
            ->maxLength('icon', 128)
            ->requirePresence('icon', 'create')
            ->notEmptyString('icon');

        $validator
            ->integer('z_index')
            ->notEmptyString('z_index');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->existsIn(['map_id'], 'Maps'));

        return $rules;
    }
}
