<?php

namespace MkModule\Model\Table;

use App\Lib\Interfaces\PluginManagerCoreAssociationsInterface;
use Cake\Datasource\RepositoryInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mkservicedata Model
 *
 * @property \MkModule\Model\Table\ServicesTable|\Cake\ORM\Association\BelongsTo $Services
 * @property \MkModule\Model\Table\HostsTable|\Cake\ORM\Association\BelongsTo $Hosts
 *
 * @method \MkModule\Model\Entity\Mkservicedata get($primaryKey, $options = [])
 * @method \MkModule\Model\Entity\Mkservicedata newEntity($data = null, array $options = [])
 * @method \MkModule\Model\Entity\Mkservicedata[] newEntities(array $data, array $options = [])
 * @method \MkModule\Model\Entity\Mkservicedata|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MkModule\Model\Entity\Mkservicedata|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MkModule\Model\Entity\Mkservicedata patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \MkModule\Model\Entity\Mkservicedata[] patchEntities($entities, array $data, array $options = [])
 * @method \MkModule\Model\Entity\Mkservicedata findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MkservicedataTable extends Table implements PluginManagerCoreAssociationsInterface {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) :void {
        parent::initialize($config);

        $this->setTable('mkservicedata');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Services', [
            'foreignKey' => 'service_id',
            'joinType'   => 'INNER'
        ]);

        $this->belongsTo('Hosts', [
            'foreignKey' => 'host_id',
            'joinType'   => 'INNER'
        ]);
    }

    /**
     * @param Table $coreTable
     */
    public function bindCoreAssociations(RepositoryInterface $coreTable) {
        switch ($coreTable->getAlias()) {
            case 'Services':
                $coreTable->hasOne('MkModule.Mkservicedata');
                break;
        }
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
            ->allowEmpty('id', 'create');

        $validator
            ->integer('is_process')
            ->requirePresence('is_process', 'create')
            ->notEmpty('is_process');

        $validator
            ->scalar('check_name')
            ->maxLength('check_name', 255)
            ->requirePresence('check_name', 'create')
            ->notEmpty('check_name');

        $validator
            ->scalar('check_item')
            ->maxLength('check_item', 255)
            ->requirePresence('check_item', 'create')
            ->notEmpty('check_item');

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
        $rules->add($rules->existsIn(['service_id'], 'Services'));
        $rules->add($rules->existsIn(['host_id'], 'Hosts'));

        return $rules;
    }
}
