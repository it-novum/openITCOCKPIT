<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OrganizationalChartStructures Model
 *
 * @property \App\Model\Table\OrganizationalChartStructuresTable&\Cake\ORM\Association\BelongsTo $ParentOrganizationalChartStructures
 * @property \App\Model\Table\OrganizationalChartsTable&\Cake\ORM\Association\BelongsTo $OrganizationalCharts
 * @property \App\Model\Table\ContainersTable&\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\OrganizationalChartStructuresTable&\Cake\ORM\Association\HasMany $ChildOrganizationalChartStructures
 * @property \App\Model\Table\UsersToOrganizationalChartStructuresTable&\Cake\ORM\Association\HasMany $UsersToOrganizationalChartStructures
 *
 * @method \App\Model\Entity\OrganizationalChartStructure newEmptyEntity()
 * @method \App\Model\Entity\OrganizationalChartStructure newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrganizationalChartStructure[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrganizationalChartStructure get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrganizationalChartStructure findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrganizationalChartStructure patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrganizationalChartStructure[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrganizationalChartStructure|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrganizationalChartStructure saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrganizationalChartStructure[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrganizationalChartStructure[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrganizationalChartStructure[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrganizationalChartStructure[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class OrganizationalChartStructuresTable extends Table
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

        $this->setTable('organizational_chart_structures');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree');

        $this->belongsTo('ParentOrganizationalChartStructures', [
            'className' => 'OrganizationalChartStructures',
            'foreignKey' => 'parent_id',
        ]);
        $this->belongsTo('OrganizationalCharts', [
            'foreignKey' => 'organizational_chart_id',
        ]);
        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
        ]);
        $this->hasMany('ChildOrganizationalChartStructures', [
            'className' => 'OrganizationalChartStructures',
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('UsersToOrganizationalChartStructures', [
            'foreignKey' => 'organizational_chart_structure_id',
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
            ->integer('parent_id')
            ->allowEmptyString('parent_id');

        $validator
            ->integer('organizational_chart_id')
            ->allowEmptyString('organizational_chart_id');

        $validator
            ->integer('container_id')
            ->allowEmptyString('container_id');

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
        $rules->add($rules->existsIn('parent_id', 'ParentOrganizationalChartStructures'), ['errorField' => 'parent_id']);
        $rules->add($rules->existsIn('organizational_chart_id', 'OrganizationalCharts'), ['errorField' => 'organizational_chart_id']);
        $rules->add($rules->existsIn('container_id', 'Containers'), ['errorField' => 'container_id']);

        return $rules;
    }
}
