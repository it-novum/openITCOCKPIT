<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DynamicTableConfigs Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\DynamicTableConfig newEmptyEntity()
 * @method \App\Model\Entity\DynamicTableConfig newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\DynamicTableConfig[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DynamicTableConfig get($primaryKey, $options = [])
 * @method \App\Model\Entity\DynamicTableConfig findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\DynamicTableConfig patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DynamicTableConfig[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DynamicTableConfig|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DynamicTableConfig saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DynamicTableConfig[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DynamicTableConfig[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\DynamicTableConfig[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DynamicTableConfig[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DynamicTableConfigsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('dynamic_table_configs');
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
            ->scalar('json_data')
            ->maxLength('json_data', 2000)
            ->allowEmptyString('json_data');

        $validator
            ->scalar('table_name')
            ->maxLength('table_name', 255)
            ->allowEmptyString('table_name');

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
     * return table id
     *
     * @param int $id
     * return bool
     */
    public function existById($id) {
        return $this->exists(['DynamicTableConfigs.id' => $id]);
    }

    /**
     *
     * @param int $id
     * @return bool
     */

    public function existByUserId($id){
        return $this->exists(['DynamicTableConfigs.user_id' => $id] );

    }

    /**
     *
     * @param int $id
     * @param string $name
     * @return bool
     */
    public function existEntiy ($id, $name){
        return $this->exists(['DynamicTableConfigs.user_id' => $id, 'DynamicTableConfigs.table_name' => $name ]);
    }
}
