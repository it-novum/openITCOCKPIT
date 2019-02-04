<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TimeperiodTimeranges Model
 *
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $Timeperiods
 *
 * @method \App\Model\Entity\TimeperiodTimerange get($primaryKey, $options = [])
 * @method \App\Model\Entity\TimeperiodTimerange newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TimeperiodTimerange[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TimeperiodTimerange|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TimeperiodTimerange|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TimeperiodTimerange patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TimeperiodTimerange[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TimeperiodTimerange findOrCreate($search, callable $callback = null, $options = [])
 */
class TimeperiodTimerangesTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('timeperiod_timeranges');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Timeperiods', [
            'foreignKey' => 'timeperiod_id',
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
            ->allowEmptyString('id', 'create');

        $validator
            ->integer('day')
            ->requirePresence('day', 'create')
            ->allowEmptyString('day', false);

        $validator
            ->scalar('start')
            ->maxLength('start', 5)
            ->requirePresence('start', 'create')
            ->allowEmptyString('start', false);

        $validator
            ->scalar('end')
            ->maxLength('end', 5)
            ->requirePresence('end', 'create')
            ->allowEmptyString('end', false);

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
        $rules->add($rules->existsIn(['timeperiod_id'], 'Timeperiods'));

        return $rules;
    }
}
