<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ConfigurationQueue Model
 *
 * @method \App\Model\Entity\ConfigurationQueue get($primaryKey, $options = [])
 * @method \App\Model\Entity\ConfigurationQueue newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ConfigurationQueue[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ConfigurationQueue|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigurationQueue saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigurationQueue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigurationQueue[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigurationQueue findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ConfigurationQueueTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('configuration_queue');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('task')
            ->maxLength('task', 255)
            ->requirePresence('task', 'create')
            ->notEmptyString('task');

        $validator
            ->scalar('data')
            ->maxLength('data', 255)
            ->requirePresence('data', 'create')
            ->notEmptyString('data');

        $validator
            ->scalar('json_data')
            ->maxLength('json_data', 2000)
            ->allowEmptyString('json_data');

        return $validator;
    }

    /**
     * @return array|null
     */
    public function getConfigFilesToGenerate() {
        $query = $this->find()
            ->where([
                'ConfigurationQueue.task' => 'ConfigGenerator'
            ])
            ->disableHydration()
            ->all();

        return $query->toArray();
    }
}
