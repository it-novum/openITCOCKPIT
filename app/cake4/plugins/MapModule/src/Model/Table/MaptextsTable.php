<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use MapModule\Model\Entity\Maptext;

/**
 * Maptexts Model
 *
 * @property MapsTable&BelongsTo $Maps
 *
 * @method Maptext get($primaryKey, $options = [])
 * @method Maptext newEntity($data = null, array $options = [])
 * @method Maptext[] newEntities(array $data, array $options = [])
 * @method Maptext|false save(EntityInterface $entity, $options = [])
 * @method Maptext saveOrFail(EntityInterface $entity, $options = [])
 * @method Maptext patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Maptext[] patchEntities($entities, array $data, array $options = [])
 * @method Maptext findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin TimestampBehavior
 */
class MaptextsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('maptexts');
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
            ->scalar('text')
            ->maxLength('text', 256)
            ->notEmptyString('text');

        $validator
            ->integer('font_size')
            ->notEmptyString('font_size');

        $validator
            ->integer('z_index')
            ->notEmptyString('z_index');

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
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Maptexts.id' => $id]);
    }
}
