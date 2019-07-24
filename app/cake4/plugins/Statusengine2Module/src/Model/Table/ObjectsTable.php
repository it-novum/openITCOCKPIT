<?php

namespace Statusengine2Module\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Objects Model
 *
 * Bake command: bin/cake bake model -p Statusengine2Module Objects
 *
 * @method \Statusengine2Module\Model\Entity\Object get($primaryKey, $options = [])
 * @method \Statusengine2Module\Model\Entity\Object newEntity($data = null, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Object[] newEntities(array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Object|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine2Module\Model\Entity\Object|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine2Module\Model\Entity\Object patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Object[] patchEntities($entities, array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Object findOrCreate($search, callable $callback = null, $options = [])
 */
class ObjectsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);
        $this->setTable('nagios_objects');
        $this->setDisplayField('object_id');
        $this->setPrimaryKey('object_id');

        //Cannot use 'Object' as class name as it is reserved
        $this->setEntityClass('Statusengine2Module.ObjectEntity');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
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
    public function buildRules(RulesChecker $rules) {
        return $rules;
    }

}
