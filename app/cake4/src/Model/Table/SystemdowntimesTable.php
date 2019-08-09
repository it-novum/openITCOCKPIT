<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Systemdowntimes Model
 *
 * @property \App\Model\Table\ObjecttypesTable|\Cake\ORM\Association\BelongsTo $Objecttypes
 * @property \App\Model\Table\ObjectsTable|\Cake\ORM\Association\BelongsTo $Objects
 * @property \App\Model\Table\DowntimetypesTable|\Cake\ORM\Association\BelongsTo $Downtimetypes
 *
 * @method \App\Model\Entity\Systemdowntime get($primaryKey, $options = [])
 * @method \App\Model\Entity\Systemdowntime newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Systemdowntime[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Systemdowntime|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Systemdowntime saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Systemdowntime patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Systemdowntime[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Systemdowntime findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SystemdowntimesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('systemdowntimes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Objecttypes', [
            'foreignKey' => 'objecttype_id'
        ]);
        $this->belongsTo('Objects', [
            'foreignKey' => 'object_id'
        ]);
        $this->belongsTo('Downtimetypes', [
            'foreignKey' => 'downtimetype_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('weekdays')
            ->maxLength('weekdays', 255)
            ->allowEmptyString('weekdays');

        $validator
            ->scalar('day_of_month')
            ->maxLength('day_of_month', 255)
            ->allowEmptyString('day_of_month');

        $validator
            ->scalar('from_time')
            ->maxLength('from_time', 255)
            ->requirePresence('from_time', 'create')
            ->notEmptyString('from_time');

        $validator
            ->scalar('to_time')
            ->maxLength('to_time', 255)
            ->allowEmptyString('to_time');

        $validator
            ->integer('duration')
            ->notEmptyString('duration');

        $validator
            ->scalar('comment')
            ->maxLength('comment', 255)
            ->allowEmptyString('comment');

        $validator
            ->scalar('author')
            ->maxLength('author', 255)
            ->allowEmptyString('author');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['objecttype_id'], 'Objecttypes'));
        $rules->add($rules->existsIn(['object_id'], 'Objects'));
        $rules->add($rules->existsIn(['downtimetype_id'], 'Downtimetypes'));

        return $rules;
    }
}
