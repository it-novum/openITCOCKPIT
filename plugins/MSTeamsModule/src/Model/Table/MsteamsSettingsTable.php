<?php
// Copyright (C) <2024>  <it-novum GmbH>
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

namespace MSTeamsModule\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MsteamsSettings Model
 *
 * @method \MSTeamsModule\Model\Entity\MsteamsSetting newEmptyEntity()
 * @method \MSTeamsModule\Model\Entity\MsteamsSetting newEntity(array $data, array $options = [])
 * @method \MSTeamsModule\Model\Entity\MsteamsSetting[] newEntities(array $data, array $options = [])
 * @method \MSTeamsModule\Model\Entity\MsteamsSetting get($primaryKey, $options = [])
 * @method \MSTeamsModule\Model\Entity\MsteamsSetting findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \MSTeamsModule\Model\Entity\MsteamsSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \MSTeamsModule\Model\Entity\MsteamsSetting[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \MSTeamsModule\Model\Entity\MsteamsSetting|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MSTeamsModule\Model\Entity\MsteamsSetting saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MSTeamsModule\Model\Entity\MsteamsSetting[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \MSTeamsModule\Model\Entity\MsteamsSetting[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \MSTeamsModule\Model\Entity\MsteamsSetting[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \MSTeamsModule\Model\Entity\MsteamsSetting[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MsteamsSettingsTable extends Table
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

        $this->setTable('msteams_settings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('webhook_url')
            ->maxLength('webhook_url', 255)
            ->notEmptyString('webhook_url');

        $validator
            ->boolean('two_way')
            ->notEmptyString('two_way');

        $validator
            ->boolean('use_proxy')
            ->notEmptyString('use_proxy');

        return $validator;
    }

    /**
     * @return array
     */
    public function getTeamsSettings() {
        $default = [
            'webhook_url' => '',
            'two_way'     => true,
            'use_proxy'   => false
        ];

        $result = $this->find()
            ->where([
                'id' => 1
            ])
            ->disableHydration()
            ->first();

        if (empty($result)) {
            return $default;
        }

        return $result;
    }

    /**
     * @return \Cake\Datasource\EntityInterface
     */
    public function getTeamsSettingsEntity() {
        $result = $this->find()
            ->where([
                'id' => 1
            ])
            ->first();

        if (empty($result)) {
            $entity = $this->newEmptyEntity();
            $entity->set('id', 1);
            $entity->setAccess('id', false);
            return $entity;
        }

        $result->setAccess('id', false);
        return $result;
    }
}
