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
use GrafanaModule\Model\Entity\GrafanaUserdashboardPanel;

/**
 * GrafanaUserdashboardPanels Model
 *
 * @property UserdashboardsTable&BelongsTo $Userdashboards
 *
 * @method GrafanaUserdashboardPanel get($primaryKey, $options = [])
 * @method GrafanaUserdashboardPanel newEntity($data = null, array $options = [])
 * @method GrafanaUserdashboardPanel[] newEntities(array $data, array $options = [])
 * @method GrafanaUserdashboardPanel|false save(EntityInterface $entity, $options = [])
 * @method GrafanaUserdashboardPanel saveOrFail(EntityInterface $entity, $options = [])
 * @method GrafanaUserdashboardPanel patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method GrafanaUserdashboardPanel[] patchEntities($entities, array $data, array $options = [])
 * @method GrafanaUserdashboardPanel findOrCreate($search, callable $callback = null, $options = [])
 */
class GrafanaUserdashboardPanelsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('grafana_userdashboard_panels');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Userdashboards', [
            'foreignKey' => 'userdashboard_id',
            'joinType'   => 'INNER',
            'className'  => 'GrafanaModule.Userdashboards',
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
            ->integer('row')
            ->requirePresence('row', 'create')
            ->notEmptyString('row');

        $validator
            ->scalar('unit')
            ->maxLength('unit', 255)
            ->notEmptyString('unit');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->notEmptyString('title');

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
        $rules->add($rules->existsIn(['userdashboard_id'], 'Userdashboards'));

        return $rules;
    }
}
