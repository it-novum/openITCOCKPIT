<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Hostgroups Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\HostsTable|\Cake\ORM\Association\BelongsToMany $Hosts
 * @property \App\Model\Table\HosttemplatesTable|\Cake\ORM\Association\BelongsToMany $Hosttemplates
 *
 * @method \App\Model\Entity\Hostgroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Hostgroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Hostgroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Hostgroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Hostgroup|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Hostgroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Hostgroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Hostgroup findOrCreate($search, callable $callback = null, $options = [])
 */
class HostgroupsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('hostgroups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
        ]);
        $this->belongsToMany('Hosts', [
            'className'        => 'Hosts',
            'foreignKey'       => 'hostgroup_id',
            'targetForeignKey' => 'host_id',
            'joinTable'        => 'hosts_to_hostgroups',
            'saveStrategy'     => 'replace'
        ]);
        $this->belongsToMany('Hosttemplates', [
            'className'        => 'Hosttemplates',
            'foreignKey'       => 'hosttemplate_id',
            'targetForeignKey' => 'host_id',
            'joinTable'        => 'hosttemplates_to_hostgroups',
            'saveStrategy'     => 'replace'
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
            ->scalar('hostgroup_url')
            ->maxLength('hostgroup_url', 255)
            ->allowEmptyString('hostgroup_url');

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
