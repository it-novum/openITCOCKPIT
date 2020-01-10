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
use GrafanaModule\Model\Entity\GrafanaDashboard;

/**
 * GrafanaDashboards Model
 *
 * @property ConfigurationsTable&BelongsTo $Configurations
 * @property HostsTable&BelongsTo $Hosts
 *
 * @method GrafanaDashboard get($primaryKey, $options = [])
 * @method GrafanaDashboard newEntity($data = null, array $options = [])
 * @method GrafanaDashboard[] newEntities(array $data, array $options = [])
 * @method GrafanaDashboard|false save(EntityInterface $entity, $options = [])
 * @method GrafanaDashboard saveOrFail(EntityInterface $entity, $options = [])
 * @method GrafanaDashboard patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method GrafanaDashboard[] patchEntities($entities, array $data, array $options = [])
 * @method GrafanaDashboard findOrCreate($search, callable $callback = null, $options = [])
 */
class GrafanaDashboardsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('grafana_dashboards');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Configurations', [
            'foreignKey' => 'configuration_id',
            'joinType'   => 'INNER',
            'className'  => 'GrafanaModule.Configurations',
        ]);
        $this->belongsTo('Hosts', [
            'foreignKey' => 'host_id',
            'joinType'   => 'INNER',
            'className'  => 'GrafanaModule.Hosts',
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
            ->scalar('host_uuid')
            ->maxLength('host_uuid', 200)
            ->requirePresence('host_uuid', 'create')
            ->notEmptyString('host_uuid');

        $validator
            ->scalar('grafana_uid')
            ->maxLength('grafana_uid', 255)
            ->notEmptyString('grafana_uid');

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
        $rules->add($rules->existsIn(['host_id'], 'Hosts'));

        return $rules;
    }

    /**
     * @param string $uuid
     * @return bool
     */
    public function existsForUuid($uuid) {
        return $this->exists(['GrafanaDashboards.host_uuid' => $uuid]);
    }

    /**
     * @param array $MY_RIGHTS
     * @return array|\Cake\Datasource\ResultSetInterface
     */
    public function getGrafanaDashboards($MY_RIGHTS = []){
        $query = $this->find()
            ->select([
                'id',
                'host_id',
                'host_uuid',
                'Host.name'
            ])
            ->join([
                [
                    'table'      => 'hosts',
                    'alias'      => 'Host',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'Host.id = GrafanaDashboards.host_id',
                    ],
                ],
                [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'INNER',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ]
            ])
            ->group([
                'Host.id'
            ])
            ->order([
                'Host.name' => 'ASC'
            ]);

        if(!empty($MY_RIGHTS)){
            $query->where([
                'HostsToContainers.container_id IN' => $MY_RIGHTS
            ]);
        }

        $query->disableHydration();
        $result = $query->all();
        $result = $result->toArray();

        return $result;
    }
}
