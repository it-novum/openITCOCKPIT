<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Servicegroups Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\ServicegroupsToServicedependenciesTable|\Cake\ORM\Association\HasMany $ServicegroupsToServicedependencies
 * @property \App\Model\Table\ServicegroupsToServiceescalationsTable|\Cake\ORM\Association\HasMany $ServicegroupsToServiceescalations
 * @property \App\Model\Table\ServicesToServicegroupsTable|\Cake\ORM\Association\HasMany $ServicesToServicegroups
 * @property \App\Model\Table\ServicetemplatesTable|\Cake\ORM\Association\HasMany $Servicetemplates
 *
 * @method \App\Model\Entity\Servicegroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Servicegroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Servicegroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Servicegroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Servicegroup saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Servicegroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Servicegroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Servicegroup findOrCreate($search, callable $callback = null, $options = [])
 */
class ServicegroupsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('servicegroups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
        ]);

        $this->hasMany('ServicegroupsToServicedependencies', [
            'foreignKey' => 'servicegroup_id'
        ]);
        $this->hasMany('ServicegroupsToServiceescalations', [
            'foreignKey' => 'servicegroup_id'
        ]);
        $this->hasMany('ServicesToServicegroups', [
            'foreignKey' => 'servicegroup_id'
        ]);
        $this->hasMany('ServicetemplatesToServicegroups', [
            'foreignKey' => 'servicegroup_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->allowEmptyString('uuid', false)
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->allowEmptyString('description', false);

        $validator
            ->scalar('servicegroup_url')
            ->maxLength('servicegroup_url', 255)
            ->allowEmptyString('servicegroup_url')
            ->url('servicegroup_url', __('Not a valid URL.'));

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['uuid']));
        $rules->add($rules->existsIn(['container_id'], 'Containers'));

        return $rules;
    }
}
