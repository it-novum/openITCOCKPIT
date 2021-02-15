<?php

namespace App\Model\Table;

use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use App\Model\Entity\Agentconfig;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Agentconfigs Model
 *
 * @property \App\Model\Table\HostsTable|\Cake\ORM\Association\BelongsTo $Hosts
 *
 * @method \App\Model\Entity\Agentconfig get($primaryKey, $options = [])
 * @method \App\Model\Entity\Agentconfig newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Agentconfig[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Agentconfig|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agentconfig saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agentconfig patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Agentconfig[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Agentconfig findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AgentconfigsTable extends Table {

    use PaginationAndScrollIndexTrait;
    use CustomValidationTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('agentconfigs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Hosts', [
            'foreignKey' => 'host_id'
        ]);

        $this->hasOne('PushAgents');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('port')
            ->notEmptyString('port', null, false);

        $validator
            ->boolean('use_https')
            ->notEmptyString('use_https');

        $validator
            ->boolean('insecure')
            ->notEmptyString('insecure');

        $validator
            ->boolean('basic_auth')
            ->notEmptyString('basic_auth');

        $validator
            ->allowEmptyString('username', __('Password can not be blank if basic auth is enabled.'), function ($context) {
                return $this->notEmptyIfBasicAuth(null, $context);
            })
            ->add('username', 'custom', [
                'rule'    => [$this, 'notEmptyIfBasicAuth'],
                'message' => __('Password can not be blank if basic auth is enabled.')
            ]);

        $validator
            ->allowEmptyString('password', __('Password can not be blank if basic auth is enabled.'), function ($context) {
                return $this->notEmptyIfBasicAuth(null, $context);
            })
            ->add('password', 'custom', [
                'rule'    => [$this, 'notEmptyIfBasicAuth'],
                'message' => __('Password can not be blank if basic auth is enabled.')
            ]);

        $validator
            ->boolean('push_noticed');

        return $validator;
    }

    public function notEmptyIfBasicAuth($value, $context) {
        if (!isset($context['data']['basic_auth'])) {
            //Basic auth missing in request
            return false;
        }

        if ($context['data']['basic_auth'] === 0 || $context['data']['basic_auth'] === false) {
            //Basic auth disabled - ok
            return true;
        }

        if (!isset($context['data']['username']) || !isset($context['data']['password'])) {
            //Username or password is not in request
            return false;
        }

        if (!empty($context['data']['username']) && !empty($context['data']['password'])) {
            //User name and password is not empty
            return true;
        }

        return false;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->existsIn(['host_id'], 'Hosts'));

        return $rules;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Agentconfigs.id' => $id]);
    }

    /**
     * @param int $hostId
     * @return bool
     */
    public function existsByHostId($hostId) {
        return $this->exists(['Agentconfigs.host_id' => $hostId]);
    }

    /**
     * @param $hostId
     * @return Agentconfig|\Cake\Datasource\EntityInterface
     */
    public function getConfigByHostId($hostId) {
        return $this->find()
            ->where([
                'Agentconfigs.host_id' => $hostId
            ])
            ->firstOrFail();
    }
}
