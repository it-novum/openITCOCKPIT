<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsersToOrganizationalChartStructures Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\OrganizationalChartStructuresTable&\Cake\ORM\Association\BelongsTo $OrganizationalChartStructures
 *
 * @method \App\Model\Entity\UsersToOrganizationalChartStructure newEmptyEntity()
 * @method \App\Model\Entity\UsersToOrganizationalChartStructure newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\UsersToOrganizationalChartStructure[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsersToOrganizationalChartStructure get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsersToOrganizationalChartStructure findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\UsersToOrganizationalChartStructure patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsersToOrganizationalChartStructure[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsersToOrganizationalChartStructure|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsersToOrganizationalChartStructure saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsersToOrganizationalChartStructure[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UsersToOrganizationalChartStructure[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\UsersToOrganizationalChartStructure[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UsersToOrganizationalChartStructure[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class UsersToOrganizationalChartStructuresTable extends Table
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

        $this->setTable('users_to_organizational_chart_structures');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('OrganizationalChartStructures', [
            'foreignKey' => 'organizational_chart_structure_id',
            'joinType' => 'INNER',
        ]);
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
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->integer('organizational_chart_structure_id')
            ->notEmptyString('organizational_chart_structure_id');

        $validator
            ->integer('is_manager')
            ->notEmptyString('is_manager');

        $validator
            ->integer('user_role')
            ->notEmptyString('user_role');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);
        $rules->add($rules->existsIn('organizational_chart_structure_id', 'OrganizationalChartStructures'), ['errorField' => 'organizational_chart_structure_id']);

        return $rules;
    }
}
