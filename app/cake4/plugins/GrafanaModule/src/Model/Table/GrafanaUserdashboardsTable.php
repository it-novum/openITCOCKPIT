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
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use GrafanaModule\Model\Entity\GrafanaUserdashboard;

/**
 * GrafanaUserdashboards Model
 *
 * @property ContainersTable&BelongsTo $Containers
 * @property ConfigurationsTable&BelongsTo $Configurations
 *
 * @method GrafanaUserdashboard get($primaryKey, $options = [])
 * @method GrafanaUserdashboard newEntity($data = null, array $options = [])
 * @method GrafanaUserdashboard[] newEntities(array $data, array $options = [])
 * @method GrafanaUserdashboard|false save(EntityInterface $entity, $options = [])
 * @method GrafanaUserdashboard saveOrFail(EntityInterface $entity, $options = [])
 * @method GrafanaUserdashboard patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method GrafanaUserdashboard[] patchEntities($entities, array $data, array $options = [])
 * @method GrafanaUserdashboard findOrCreate($search, callable $callback = null, $options = [])
 */
class GrafanaUserdashboardsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('grafana_userdashboards');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER',
            'className'  => 'GrafanaModule.Containers',
        ]);
        $this->belongsTo('Configurations', [
            'foreignKey' => 'configuration_id',
            'joinType'   => 'INNER',
            'className'  => 'GrafanaModule.Configurations',
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
            ->notEmptyString('name');

        $validator
            ->scalar('grafana_uid')
            ->maxLength('grafana_uid', 255)
            ->notEmptyString('grafana_uid');

        $validator
            ->scalar('grafana_url')
            ->maxLength('grafana_url', 255)
            ->notEmptyString('grafana_url');

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
        $rules->add($rules->existsIn(['container_id'], 'Containers'));
        $rules->add($rules->existsIn(['configuration_id'], 'Configurations'));

        return $rules;
    }
}
