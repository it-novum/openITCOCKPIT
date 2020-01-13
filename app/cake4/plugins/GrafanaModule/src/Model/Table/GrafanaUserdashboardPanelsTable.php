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
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use GrafanaModule\Model\Entity\GrafanaUserdashboardPanel;

/**
 * GrafanaUserdashboardPanels Model
 *
 * @property GrafanaUserdashboardsTable&BelongsTo $GrafanaUserdashboardsTable
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

        $this->belongsTo('GrafanaUserdashboards', [
            'foreignKey' => 'userdashboard_id',
            'joinType'   => 'INNER',
            'className'  => 'GrafanaModule.GrafanaUserdashboards',
        ]);

        $this->hasMany('GrafanaUserdashboardMetrics', [
            'foreignKey'       => 'panel_id',
            'className'        => 'GrafanaModule.GrafanaUserdashboardMetrics',
            'dependent'        => true,
            'cascadeCallbacks' => true
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
            ->integer('userdashboard_id')
            ->requirePresence('userdashboard_id', 'create')
            ->notEmptyString('userdashboard_id')
            ->greaterThan('userdashboard_id', 0);


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
        $rules->add($rules->existsIn(['userdashboard_id'], 'GrafanaUserdashboards'));

        return $rules;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['GrafanaUserdashboardPanels.id' => $id]);
    }


    /**
     * @param $dashboardId
     * @return int
     */
    public function getNextRow($dashboardId) {
        try {
            $result = $this->find()
                ->where([
                    'GrafanaUserdashboardPanels.userdashboard_id' => $dashboardId
                ])
                ->order([
                    'GrafanaUserdashboardPanels.row' => 'DESC'
                ])
                ->firstOrFail();
        } catch (RecordNotFoundException $e) {
            return 0;
        }

        return $result->get('row') + 1;
    }

}
