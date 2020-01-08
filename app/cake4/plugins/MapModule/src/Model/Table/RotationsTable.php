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

use App\Model\Table\ContainersTable;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use MapModule\Model\Entity\Rotation;

/**
 * Rotations Model
 *
 * @property RotationsTable&HasMany $MapsToRotations
 * @property ContainersTable&HasMany $RotationsToContainers
 *
 * @method Rotation get($primaryKey, $options = [])
 * @method Rotation newEntity($data = null, array $options = [])
 * @method Rotation[] newEntities(array $data, array $options = [])
 * @method Rotation|false save(EntityInterface $entity, $options = [])
 * @method Rotation saveOrFail(EntityInterface $entity, $options = [])
 * @method Rotation patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Rotation[] patchEntities($entities, array $data, array $options = [])
 * @method Rotation findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin TimestampBehavior
 */
class RotationsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('rotations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('MapsToRotations', [
            'foreignKey' => 'rotation_id',
            'className'  => 'MapModule.MapsToRotations',
        ]);
        $this->hasMany('RotationsToContainers', [
            'foreignKey' => 'rotation_id',
            'className'  => 'MapModule.RotationsToContainers',
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('interval')
            ->notEmptyString('interval');

        return $validator;
    }
}
