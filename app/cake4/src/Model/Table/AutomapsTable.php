<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Automaps Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 *
 * @method \App\Model\Entity\Automap get($primaryKey, $options = [])
 * @method \App\Model\Entity\Automap newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Automap[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Automap|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Automap saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Automap patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Automap[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Automap findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AutomapsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('automaps');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        $validator
            ->scalar('host_regex')
            ->maxLength('host_regex', 255)
            ->allowEmptyString('host_regex');

        $validator
            ->scalar('service_regex')
            ->maxLength('service_regex', 255)
            ->allowEmptyString('service_regex');

        $validator
            ->boolean('show_ok')
            ->notEmptyString('show_ok');

        $validator
            ->boolean('show_warning')
            ->notEmptyString('show_warning');

        $validator
            ->boolean('show_critical')
            ->notEmptyString('show_critical');

        $validator
            ->boolean('show_unknown')
            ->notEmptyString('show_unknown');

        $validator
            ->boolean('show_acknowledged')
            ->notEmptyString('show_acknowledged');

        $validator
            ->boolean('show_downtime')
            ->notEmptyString('show_downtime');

        $validator
            ->boolean('show_label')
            ->notEmptyString('show_label');

        $validator
            ->boolean('group_by_host')
            ->notEmptyString('group_by_host');

        $validator
            ->scalar('font_size')
            ->maxLength('font_size', 255)
            ->allowEmptyString('font_size');

        $validator
            ->boolean('recursive')
            ->notEmptyString('recursive');

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
        $rules->add($rules->existsIn(['container_id'], 'Containers'));

        return $rules;
    }
}
