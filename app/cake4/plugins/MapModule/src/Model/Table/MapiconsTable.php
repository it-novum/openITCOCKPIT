<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mapicons Model
 *
 * @property \MapModule\Model\Table\MapsTable&\Cake\ORM\Association\BelongsTo $Maps
 *
 * @method \MapModule\Model\Entity\Mapicon get($primaryKey, $options = [])
 * @method \MapModule\Model\Entity\Mapicon newEntity($data = null, array $options = [])
 * @method \MapModule\Model\Entity\Mapicon[] newEntities(array $data, array $options = [])
 * @method \MapModule\Model\Entity\Mapicon|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MapModule\Model\Entity\Mapicon saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MapModule\Model\Entity\Mapicon patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \MapModule\Model\Entity\Mapicon[] patchEntities($entities, array $data, array $options = [])
 * @method \MapModule\Model\Entity\Mapicon findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MapiconsTable extends Table
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

        $this->setTable('mapicons');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Maps', [
            'foreignKey' => 'map_id',
            'joinType' => 'INNER',
            'className' => 'MapModule.Maps',
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('x')
            ->notEmptyString('x');

        $validator
            ->integer('y')
            ->notEmptyString('y');

        $validator
            ->scalar('icon')
            ->maxLength('icon', 128)
            ->requirePresence('icon', 'create')
            ->notEmptyString('icon');

        $validator
            ->integer('z_index')
            ->notEmptyString('z_index');

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
        $rules->add($rules->existsIn(['map_id'], 'Maps'));

        return $rules;
    }
}
