<?php

namespace App\Model\Table;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Apikeys Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Apikey get($primaryKey, $options = [])
 * @method \App\Model\Entity\Apikey newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Apikey[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Apikey|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Apikey|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Apikey patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Apikey[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Apikey findOrCreate($search, callable $callback = null, $options = [])
 */
class ApikeysTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('apikeys');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType'   => 'INNER'
        ]);
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
            ->scalar('apikey')
            ->maxLength('apikey', 255)
            ->requirePresence('apikey', 'create')
            ->allowEmptyString('apikey', null, false);

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->allowEmptyString('description', null, false);

        $validator
            ->integer('user_id')
            //->requirePresence('user_id', 'create')
            ->greaterThan('user_id', 0);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * @param int $userId
     * @return array|\Cake\Datasource\ResultSetInterface
     */
    public function getAllapiKeysByUserId($userId) {
        $query = $this->find()
            ->where([
                'Apikeys.user_id' => $userId
            ])
            ->all();

        if ($query === null) {
            return [];
        }
        return $query;
    }

    /**
     * @param int $id
     * @param int $userId
     * @return array|\Cake\Datasource\EntityInterface
     */
    public function getApikeyByIdAndUserId($id, $userId) {
        try {
            $query = $this->find()
                ->where([
                    'Apikeys.id'      => $id,
                    'Apikeys.user_id' => $userId
                ])
                ->firstOrFail();
        } catch (RecordNotFoundException $e) {
            return [];
        }

        return $query;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Apikeys.id' => $id]);
    }

    /**
     * @param int $len
     * @return string
     */
    public function generateApiKey($len = 80) {
        $bytes = openssl_random_pseudo_bytes($len, $cstrong);
        $apikey = bin2hex($bytes);
        return $apikey;
    }
}
