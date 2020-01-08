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

namespace MkModule\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mkagents Model
 *
 * @property \MkModule\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 *
 * @method \MkModule\Model\Entity\Mkagent get($primaryKey, $options = [])
 * @method \MkModule\Model\Entity\Mkagent newEntity($data = null, array $options = [])
 * @method \MkModule\Model\Entity\Mkagent[] newEntities(array $data, array $options = [])
 * @method \MkModule\Model\Entity\Mkagent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MkModule\Model\Entity\Mkagent|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MkModule\Model\Entity\Mkagent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \MkModule\Model\Entity\Mkagent[] patchEntities($entities, array $data, array $options = [])
 * @method \MkModule\Model\Entity\Mkagent findOrCreate($search, callable $callback = null, $options = [])
 */
class MkagentsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) :void {
        parent::initialize($config);

        $this->setTable('mkagents');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER',
            'className'  => 'MkModule.Containers'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) :Validator {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->scalar('command_line')
            ->requirePresence('command_line', 'create')
            ->notEmpty('command_line');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) :RulesChecker {
        $rules->add($rules->existsIn(['container_id'], 'Containers'));

        return $rules;
    }
}
