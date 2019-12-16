<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mapitems Model
 *
 * @property \MapModule\Model\Table\MapsTable&\Cake\ORM\Association\BelongsTo $Maps
 * @property \MapModule\Model\Table\ObjectsTable&\Cake\ORM\Association\BelongsTo $Objects
 *
 * @method \MapModule\Model\Entity\Mapitem get($primaryKey, $options = [])
 * @method \MapModule\Model\Entity\Mapitem newEntity($data = null, array $options = [])
 * @method \MapModule\Model\Entity\Mapitem[] newEntities(array $data, array $options = [])
 * @method \MapModule\Model\Entity\Mapitem|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MapModule\Model\Entity\Mapitem saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MapModule\Model\Entity\Mapitem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \MapModule\Model\Entity\Mapitem[] patchEntities($entities, array $data, array $options = [])
 * @method \MapModule\Model\Entity\Mapitem findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MapitemsTable extends Table
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

        $this->setTable('mapitems');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Maps', [
            'foreignKey' => 'map_id',
            'joinType' => 'INNER',
            'className' => 'MapModule.Maps',
        ]);
        $this->belongsTo('Objects', [
            'foreignKey' => 'object_id',
            'joinType' => 'INNER',
            'className' => 'MapModule.Objects',
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
            ->integer('limit')
            ->allowEmptyString('limit');

        $validator
            ->scalar('iconset')
            ->maxLength('iconset', 128)
            ->requirePresence('iconset', 'create')
            ->notEmptyString('iconset');

        $validator
            ->scalar('type')
            ->maxLength('type', 20)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->integer('z_index')
            ->notEmptyString('z_index');

        $validator
            ->integer('show_label')
            ->notEmptyString('show_label');

        $validator
            ->integer('label_possition')
            ->notEmptyString('label_possition');

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
        $rules->add($rules->existsIn(['object_id'], 'Objects'));

        return $rules;
    }
}
