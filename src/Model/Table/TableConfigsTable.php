<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TableConfigs Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\TableConfig newEmptyEntity()
 * @method \App\Model\Entity\TableConfig newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\TableConfig[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TableConfig get($primaryKey, $options = [])
 * @method \App\Model\Entity\TableConfig findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\TableConfig patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TableConfig[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\TableConfig|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TableConfig saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TableConfig[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TableConfig[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\TableConfig[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TableConfig[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class TableConfigsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('table_configs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType'   => 'INNER',
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
            ->integer('custom_last_change')
            ->notEmptyString('custom_last_change');

        $validator
            ->integer('custom_last_check')
            ->notEmptyString('custom_last_check');

        $validator
            ->integer('custom_host_output')
            ->notEmptyString('custom_host_output');

        $validator
            ->integer('custom_instance')
            ->notEmptyString('custom_instance');

        $validator
            ->integer('custom_service_summery')
            ->notEmptyString('custom_service_summary');
        $validator
            ->integer('custom_hoststatus')
            ->notEmptyString('custom_hoststatus');
        $validator
            ->integer('custom_acknowledgement')
            ->notEmptyString('custom_acknowledgement');
        $validator
            ->integer('custom_indowntime')
            ->notEmptyString('custom_indowntime');
        $validator
            ->integer('custom_shared')
            ->notEmptyString('custom_shared');
        $validator
            ->integer('custom_passive')
            ->notEmptyString('custom_passive');
        $validator
            ->integer('custom_priority')
            ->notEmptyString('custom_priority');
        $validator
            ->integer('custom_hostname')
            ->notEmptyString('custom_hostname');
        $validator
            ->integer('custom_ip_address')
            ->notEmptyString('custom_ip_address');
        $validator
            ->integer('custom_description')
            ->notEmptyString('custom_description');
        $validator
            ->integer('custom_container_name')
            ->notEmptyString('custom_container_name');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }

    /**
     *
     * @param int $id
     * @return bool
     */

    public function existByUserId($id){
        return $this->exists(['TableConfigs.user_id' => $id] );

    }
}
