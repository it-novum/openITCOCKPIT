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

namespace GrafanaModule\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use GrafanaModule\Model\Entity\GrafanaConfiguration;

/**
 * GrafanaConfigurations Model
 *
 * @method GrafanaConfiguration get($primaryKey, $options = [])
 * @method GrafanaConfiguration newEntity($data = null, array $options = [])
 * @method GrafanaConfiguration[] newEntities(array $data, array $options = [])
 * @method GrafanaConfiguration|false save(EntityInterface $entity, $options = [])
 * @method GrafanaConfiguration saveOrFail(EntityInterface $entity, $options = [])
 * @method GrafanaConfiguration patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method GrafanaConfiguration[] patchEntities($entities, array $data, array $options = [])
 * @method GrafanaConfiguration findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin TimestampBehavior
 */
class GrafanaConfigurationsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('grafana_configurations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('api_url')
            ->maxLength('api_url', 200)
            ->requirePresence('api_url', 'create')
            ->notEmptyString('api_url');

        $validator
            ->scalar('api_key')
            ->maxLength('api_key', 200)
            ->requirePresence('api_key', 'create')
            ->notEmptyString('api_key');

        $validator
            ->scalar('graphite_prefix')
            ->maxLength('graphite_prefix', 200)
            ->requirePresence('graphite_prefix', 'create')
            ->notEmptyString('graphite_prefix');

        $validator
            ->integer('use_https')
            ->requirePresence('use_https', 'create')
            ->notEmptyString('use_https');

        $validator
            ->integer('use_proxy')
            ->requirePresence('use_proxy', 'create')
            ->notEmptyString('use_proxy');

        $validator
            ->integer('ignore_ssl_certificate')
            ->requirePresence('ignore_ssl_certificate', 'create')
            ->notEmptyString('ignore_ssl_certificate');

        $validator
            ->scalar('dashboard_style')
            ->maxLength('dashboard_style', 200)
            ->requirePresence('dashboard_style', 'create')
            ->notEmptyString('dashboard_style');

        return $validator;
    }
}
