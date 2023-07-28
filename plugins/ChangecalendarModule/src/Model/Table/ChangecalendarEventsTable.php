<?php
declare(strict_types=1);

namespace ChangecalendarModule\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ChangecalendarEvents Model
 *
 * @property \ChangecalendarModule\Model\Table\ChangecalendarsTable&\Cake\ORM\Association\BelongsTo $Changecalendars
 * @property \ChangecalendarModule\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \ChangecalendarModule\Model\Entity\ChangecalendarEvent newEmptyEntity()
 * @method \ChangecalendarModule\Model\Entity\ChangecalendarEvent newEntity(array $data, array $options = [])
 * @method \ChangecalendarModule\Model\Entity\ChangecalendarEvent[] newEntities(array $data, array $options = [])
 * @method \ChangecalendarModule\Model\Entity\ChangecalendarEvent get($primaryKey, $options = [])
 * @method \ChangecalendarModule\Model\Entity\ChangecalendarEvent findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \ChangecalendarModule\Model\Entity\ChangecalendarEvent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \ChangecalendarModule\Model\Entity\ChangecalendarEvent[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \ChangecalendarModule\Model\Entity\ChangecalendarEvent|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ChangecalendarModule\Model\Entity\ChangecalendarEvent saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ChangecalendarModule\Model\Entity\ChangecalendarEvent[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \ChangecalendarModule\Model\Entity\ChangecalendarEvent[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \ChangecalendarModule\Model\Entity\ChangecalendarEvent[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \ChangecalendarModule\Model\Entity\ChangecalendarEvent[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ChangecalendarEventsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('changecalendar_events');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Changecalendars', [
            'foreignKey' => 'changecalendar_id',
            'joinType' => 'INNER',
            'className' => 'ChangecalendarModule.Changecalendars',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => 'ChangecalendarModule.Users',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        $validator
            ->dateTime('start')
            ->requirePresence('start', 'create')
            ->notEmptyDateTime('start');

        $validator
            ->dateTime('end')
            ->requirePresence('end', 'create')
            ->notEmptyDateTime('end');

        $validator
            ->scalar('uid')
            ->maxLength('uid', 255)
            ->allowEmptyString('uid');

        $validator
            ->allowEmptyString('context');

        $validator
            ->integer('changecalendar_id')
            ->notEmptyString('changecalendar_id');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('changecalendar_id', 'Changecalendars'), ['errorField' => 'changecalendar_id']);
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
