<?php

namespace Statusengine2Module\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Hoststatus Model
 *
 * @property \Statusengine2Module\Model\Table\ObjectsTable|\Cake\ORM\Association\BelongsTo $Object
 *
 * @method \Statusengine2Module\Model\Entity\Hoststatus get($primaryKey, $options = [])
 * @method \Statusengine2Module\Model\Entity\Hoststatus newEntity($data = null, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Hoststatus[] newEntities(array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Hoststatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine2Module\Model\Entity\Hoststatus|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine2Module\Model\Entity\Hoststatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Hoststatus[] patchEntities($entities, array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Hoststatus findOrCreate($search, callable $callback = null, $options = [])
 */
class HoststatusTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('nagios_hoststatus');
        $this->setDisplayField('hoststatus_id');
        $this->setPrimaryKey('hoststatus_id');

        $this->belongsTo('Objects', [
            'foreignKey' => 'host_object_id',
            'joinType'   => 'INNER',
            'className'  => 'Statusengine2Module.HostObjects'
        ]);
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
