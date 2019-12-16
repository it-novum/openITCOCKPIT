<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mapgadgets Model
 *
 * @property \MapModule\Model\Table\MapsTable&\Cake\ORM\Association\BelongsTo $Maps
 * @property \MapModule\Model\Table\ObjectsTable&\Cake\ORM\Association\BelongsTo $Objects
 *
 * @method \MapModule\Model\Entity\Mapgadget get($primaryKey, $options = [])
 * @method \MapModule\Model\Entity\Mapgadget newEntity($data = null, array $options = [])
 * @method \MapModule\Model\Entity\Mapgadget[] newEntities(array $data, array $options = [])
 * @method \MapModule\Model\Entity\Mapgadget|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MapModule\Model\Entity\Mapgadget saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MapModule\Model\Entity\Mapgadget patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \MapModule\Model\Entity\Mapgadget[] patchEntities($entities, array $data, array $options = [])
 * @method \MapModule\Model\Entity\Mapgadget findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MapgadgetsTable extends Table
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

        $this->setTable('mapgadgets');
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
            ->integer('size_x')
            ->notEmptyString('size_x');

        $validator
            ->integer('size_y')
            ->notEmptyString('size_y');

        $validator
            ->integer('limit')
            ->allowEmptyString('limit');

        $validator
            ->scalar('gadget')
            ->maxLength('gadget', 128)
            ->allowEmptyString('gadget');

        $validator
            ->scalar('type')
            ->maxLength('type', 20)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->integer('transparent_background')
            ->notEmptyString('transparent_background');

        $validator
            ->integer('show_label')
            ->notEmptyString('show_label');

        $validator
            ->integer('font_size')
            ->notEmptyString('font_size');

        $validator
            ->integer('z_index')
            ->notEmptyString('z_index');

        $validator
            ->scalar('metric')
            ->maxLength('metric', 256)
            ->allowEmptyString('metric');

        $validator
            ->scalar('output_type')
            ->maxLength('output_type', 256)
            ->allowEmptyString('output_type');

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
