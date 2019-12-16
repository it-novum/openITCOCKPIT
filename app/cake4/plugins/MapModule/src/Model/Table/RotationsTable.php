<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use App\Model\Table\ContainersTable;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use MapModule\Model\Entity\Rotation;

/**
 * Rotations Model
 *
 * @property RotationsTable&HasMany $MapsToRotations
 * @property ContainersTable&HasMany $RotationsToContainers
 *
 * @method Rotation get($primaryKey, $options = [])
 * @method Rotation newEntity($data = null, array $options = [])
 * @method Rotation[] newEntities(array $data, array $options = [])
 * @method Rotation|false save(EntityInterface $entity, $options = [])
 * @method Rotation saveOrFail(EntityInterface $entity, $options = [])
 * @method Rotation patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Rotation[] patchEntities($entities, array $data, array $options = [])
 * @method Rotation findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin TimestampBehavior
 */
class RotationsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('rotations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('MapsToRotations', [
            'foreignKey' => 'rotation_id',
            'className'  => 'MapModule.MapsToRotations',
        ]);
        $this->hasMany('RotationsToContainers', [
            'foreignKey' => 'rotation_id',
            'className'  => 'MapModule.RotationsToContainers',
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('interval')
            ->notEmptyString('interval');

        return $validator;
    }
}
