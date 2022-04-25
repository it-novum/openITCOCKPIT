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
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use GrafanaModule\Model\Entity\GrafanaConfigurationHostgroupMembership;

/**
 * GrafanaConfigurations Model
 *
 * @method GrafanaConfigurationHostgroupMembership get($primaryKey, $options = [])
 * @method GrafanaConfigurationHostgroupMembership newEntity($data = null, array $options = [])
 * @method GrafanaConfigurationHostgroupMembership[] newEntities(array $data, array $options = [])
 * @method GrafanaConfigurationHostgroupMembership|false save(EntityInterface $entity, $options = [])
 * @method GrafanaConfigurationHostgroupMembership saveOrFail(EntityInterface $entity, $options = [])
 * @method GrafanaConfigurationHostgroupMembership patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method GrafanaConfigurationHostgroupMembership[] patchEntities($entities, array $data, array $options = [])
 * @method GrafanaConfigurationHostgroupMembership findOrCreate($search, callable $callback = null, $options = [])
 *
 */
class GrafanaConfigurationHostgroupMembershipTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('hostgroups_to_grafanaconfigurations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('GrafanaConfigurations', [
            'foreignKey' => 'configuration_id',
            'joinType'   => 'INNER',
            'className'  => 'GrafanaModule.GrafanaConfigurations'
        ]);

        $this->belongsTo('Hostgroups', [
            'foreignKey' => 'hostgroup_id',
            'joinType'   => 'INNER',
            'className'  => 'Hostgroups'
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
            ->integer('configuration_id')
            ->notEmptyString('configuration_id');

        $validator
            ->integer('hostgroup_id')
            ->notEmptyString('hostgroup_id');

        $validator
            ->boolean('excluded')
            ->notEmptyString('excluded');

        return $validator;
    }
}
