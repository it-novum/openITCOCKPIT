<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Commands Model
 *
 * @property \App\Model\Table\CommandargumentsTable|\Cake\ORM\Association\HasMany $Commandarguments
 * @property \App\Model\Table\ContactsToHostcommandsTable|\Cake\ORM\Association\HasMany $ContactsToHostcommands
 * @property \App\Model\Table\ContactsToServicecommandsTable|\Cake\ORM\Association\HasMany $ContactsToServicecommands
 * @property \App\Model\Table\HostsTable|\Cake\ORM\Association\HasMany $Hosts
 * @property \App\Model\Table\HosttemplatesTable|\Cake\ORM\Association\HasMany $Hosttemplates
 * @property \App\Model\Table\NagiosCommandsTable|\Cake\ORM\Association\HasMany $NagiosCommands
 * @property \App\Model\Table\ServicesTable|\Cake\ORM\Association\HasMany $Services
 * @property \App\Model\Table\ServicetemplatesTable|\Cake\ORM\Association\HasMany $Servicetemplates
 *
 * @method \App\Model\Entity\Command get($primaryKey, $options = [])
 * @method \App\Model\Entity\Command newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Command[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Command|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Command|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Command patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Command[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Command findOrCreate($search, callable $callback = null, $options = [])
 */
class CommandsTable extends Table {

    use Cake2ResultTableTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('commands');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Commandarguments', [
            'foreignKey' => 'command_id'
        ]);
        $this->hasMany('ContactsToHostcommands', [
            'foreignKey' => 'command_id'
        ]);
        $this->hasMany('ContactsToServicecommands', [
            'foreignKey' => 'command_id'
        ]);
        $this->hasMany('Hosts', [
            'foreignKey' => 'command_id'
        ]);
        $this->hasMany('Hosttemplates', [
            'foreignKey' => 'command_id'
        ]);
        $this->hasMany('NagiosCommands', [
            'foreignKey' => 'command_id'
        ]);
        $this->hasMany('Services', [
            'foreignKey' => 'command_id'
        ]);
        $this->hasMany('Servicetemplates', [
            'foreignKey' => 'command_id'
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name');

        $validator
            ->scalar('command_line')
            ->allowEmptyString('command_line');

        $validator
            ->integer('command_type')
            ->requirePresence('command_type', 'create')
            ->allowEmptyString('command_type', false);

        $validator
            ->scalar('human_args')
            ->allowEmptyString('human_args');

        $validator
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->allowEmptyString('uuid', false)
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

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

        return $rules;
    }

    /**
     * @return array
     */
    public function test() {
        $query = $this->find()->contain([
            'Commandarguments'
        ])->disableHydration();

        if (is_null($query)) {
            return [];
        }

        return $this->formatResultAsCake2($query->toArray());
    }
}
