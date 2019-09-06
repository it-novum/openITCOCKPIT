<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Changelogs Model
 *
 * @property \App\Model\Table\ObjectsTable|\Cake\ORM\Association\BelongsTo $Objects
 * @property \App\Model\Table\ObjecttypesTable|\Cake\ORM\Association\BelongsTo $Objecttypes
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ChangelogsToContainersTable|\Cake\ORM\Association\HasMany $ChangelogsToContainers
 *
 * @method \App\Model\Entity\Changelog get($primaryKey, $options = [])
 * @method \App\Model\Entity\Changelog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Changelog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Changelog|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Changelog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Changelog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Changelog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Changelog findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ChangelogsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) :void {
        parent::initialize($config);

        $this->setTable('changelogs');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Objects', [
            'foreignKey' => 'object_id',
            'joinType'   => 'INNER'
        ]);
        $this->belongsTo('Objecttypes', [
            'foreignKey' => 'objecttype_id'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ChangelogsToContainers', [
            'foreignKey' => 'changelog_id'
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
            ->scalar('model')
            ->maxLength('model', 255)
            ->requirePresence('model', 'create')
            ->allowEmptyString('model', null, false);

        $validator
            ->scalar('action')
            ->maxLength('action', 255)
            ->requirePresence('action', 'create')
            ->allowEmptyString('action', null, false);

        $validator
            ->scalar('data')
            ->requirePresence('data', 'create')
            ->allowEmptyString('data', null, false);

        $validator
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', null, false);

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
        $rules->add($rules->existsIn(['object_id'], 'Objects'));
        $rules->add($rules->existsIn(['objecttype_id'], 'Objecttypes'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     *  Use $user_id = 0 to specify cron task
     *
     * @param $action
     * @param $controller
     * @param $object_id
     * @param $objecttype_id
     * @param $container_id
     * @param $user_id
     * @param $name
     * @param $requestData
     * @param array $currentSavedData
     * @return array|bool|false
     */
    public function parseDataForChangelog($action, $controller, $object_id, $objecttype_id, $container_id, $user_id, $name, $requestData, $currentSavedData = []) {
        /** @var \Changelog $Changelog */
        $Changelog = \ClassRegistry::init('Changelog');
        return $Changelog->parseDataForChangelog($action, $controller, $object_id, $objecttype_id, $container_id, $user_id, $name, $requestData, $currentSavedData);
    }

    /**
     * @param $changelog_data
     * @return bool
     */
    public function write($changelog_data) {
        return \CakeLog::write('log', serialize($changelog_data));
    }

}
