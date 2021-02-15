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

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PushAgents Model
 *
 * @property \App\Model\Table\AgentconfigsTable&\Cake\ORM\Association\BelongsTo $Agentconfigs
 *
 * @method \App\Model\Entity\PushAgent newEmptyEntity()
 * @method \App\Model\Entity\PushAgent newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\PushAgent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PushAgent get($primaryKey, $options = [])
 * @method \App\Model\Entity\PushAgent findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\PushAgent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PushAgent[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PushAgent|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PushAgent saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PushAgent[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\PushAgent[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\PushAgent[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\PushAgent[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PushAgentsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('push_agents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Agentconfigs', [
            'foreignKey' => 'agentconfig_id',
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
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->notEmptyString('uuid')
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('hostname')
            ->maxLength('hostname', 255)
            ->allowEmptyString('hostname');

        $validator
            ->scalar('ipaddress')
            ->maxLength('ipaddress', 255)
            ->allowEmptyString('ipaddress');

        $validator
            ->scalar('remote_address')
            ->maxLength('remote_address', 255)
            ->allowEmptyString('remote_address');

        $validator
            ->scalar('http_x_forwarded_for')
            ->maxLength('http_x_forwarded_for', 255)
            ->allowEmptyString('http_x_forwarded_for');

        $validator
            ->scalar('checkresults')
            ->maxLength('checkresults', 16777215)
            ->requirePresence('checkresults', 'create')
            ->notEmptyString('checkresults');

        $validator
            ->dateTime('last_update')
            ->notEmptyDateTime('last_update');

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
        $rules->add($rules->isUnique(['uuid']), ['errorField' => 'uuid']);
        $rules->add($rules->existsIn(['agentconfig_id'], 'Agentconfigs'), ['errorField' => 'agentconfig_id']);

        return $rules;
    }
}
