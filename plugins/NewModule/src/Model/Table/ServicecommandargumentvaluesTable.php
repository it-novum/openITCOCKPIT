<?php

namespace NewModule\Model\Table;

use App\Lib\Interfaces\PluginManagerCoreAssociationsInterface;
use Cake\Datasource\RepositoryInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Servicecommandargumentvalues Model
 *
 * @property \NewModule\Model\Table\CommandargumentsTable|\Cake\ORM\Association\BelongsTo $Commandarguments
 * @property \NewModule\Model\Table\ServicesTable|\Cake\ORM\Association\BelongsTo $Services
 *
 * @method \NewModule\Model\Entity\Servicecommandargumentvalue get($primaryKey, $options = [])
 * @method \NewModule\Model\Entity\Servicecommandargumentvalue newEntity($data = null, array $options = [])
 * @method \NewModule\Model\Entity\Servicecommandargumentvalue[] newEntities(array $data, array $options = [])
 * @method \NewModule\Model\Entity\Servicecommandargumentvalue|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \NewModule\Model\Entity\Servicecommandargumentvalue|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \NewModule\Model\Entity\Servicecommandargumentvalue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \NewModule\Model\Entity\Servicecommandargumentvalue[] patchEntities($entities, array $data, array $options = [])
 * @method \NewModule\Model\Entity\Servicecommandargumentvalue findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServicecommandargumentvaluesTable extends Table implements PluginManagerCoreAssociationsInterface {

    public function bindCoreAssociations(RepositoryInterface $coreModel) {
        switch ($coreModel->getAlias()) {
            case 'Services':
                $coreModel->hasMany('NewModule.Servicecommandargumentvalues');
                break;
        }
        // TODO: Implement bindCoreAssociations() method.
    }

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) :void {
        parent::initialize($config);

        $this->setTable('servicecommandargumentvalues');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Commandarguments', [
            'foreignKey' => 'commandargument_id',
            'joinType'   => 'INNER',
            'className'  => 'NewModule.Commandarguments'
        ]);
        $this->belongsTo('Services', [
            'foreignKey' => 'service_id',
            'joinType'   => 'INNER',
            'className'  => 'NewModule.Services'
        ]);
    }


    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) :Validator {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('value')
            ->maxLength('value', 1000)
            ->requirePresence('value', 'create')
            ->allowEmptyString('value', null, false);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) :RulesChecker {
        $rules->add($rules->existsIn(['commandargument_id'], 'Commandarguments'));
        $rules->add($rules->existsIn(['service_id'], 'Services'));

        return $rules;
    }
}
