<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use MapModule\Model\Entity\Mapgadget;

/**
 * Mapgadgets Model
 *
 * @property MapsTable&BelongsTo $Maps
 *
 * @method Mapgadget get($primaryKey, $options = [])
 * @method Mapgadget newEntity($data = null, array $options = [])
 * @method Mapgadget[] newEntities(array $data, array $options = [])
 * @method Mapgadget|false save(EntityInterface $entity, $options = [])
 * @method Mapgadget saveOrFail(EntityInterface $entity, $options = [])
 * @method Mapgadget patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Mapgadget[] patchEntities($entities, array $data, array $options = [])
 * @method Mapgadget findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin TimestampBehavior
 */
class MapgadgetsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('mapgadgets');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Maps', [
            'foreignKey' => 'map_id',
            'joinType'   => 'INNER',
            'className'  => 'MapModule.Maps',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator {
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
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->existsIn(['map_id'], 'Maps'));

        return $rules;
    }

    /**
     * @param $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Mapitems.id' => $id]);
    }
}
