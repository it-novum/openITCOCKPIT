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

namespace Statusengine2Module\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NagiosContactnotificationmethods Model
 *
 * @property \Statusengine2Module\Model\Table\ContactnotificationmethodsTable&\Cake\ORM\Association\BelongsTo $Contactnotificationmethods
 * @property \Statusengine2Module\Model\Table\InstancesTable&\Cake\ORM\Association\BelongsTo $Instances
 * @property \Statusengine2Module\Model\Table\ContactnotificationsTable&\Cake\ORM\Association\BelongsTo $Contactnotifications
 * @property \Statusengine2Module\Model\Table\CommandObjectsTable&\Cake\ORM\Association\BelongsTo $CommandObjects
 *
 * @method \Statusengine2Module\Model\Entity\Contactnotificationmethod get($primaryKey, $options = [])
 * @method \Statusengine2Module\Model\Entity\Contactnotificationmethod newEntity($data = null, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Contactnotificationmethod[] newEntities(array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Contactnotificationmethod|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine2Module\Model\Entity\Contactnotificationmethod saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine2Module\Model\Entity\Contactnotificationmethod patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Contactnotificationmethod[] patchEntities($entities, array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Contactnotificationmethod findOrCreate($search, callable $callback = null, $options = [])
 */
class ContactnotificationmethodsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('nagios_contactnotificationmethods');
        $this->setDisplayField('contactnotificationmethod_id');
        $this->setPrimaryKey(['contactnotificationmethod_id']);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator {
        //Readonly table

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
        return $rules;
    }
}
