<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Rotations Model
 *
 * @property \MapModule\Model\Table\MapsToRotationsTable&\Cake\ORM\Association\HasMany $MapsToRotations
 * @property \MapModule\Model\Table\RotationsToContainersTable&\Cake\ORM\Association\HasMany $RotationsToContainers
 *
 * @method \MapModule\Model\Entity\Rotation get($primaryKey, $options = [])
 * @method \MapModule\Model\Entity\Rotation newEntity($data = null, array $options = [])
 * @method \MapModule\Model\Entity\Rotation[] newEntities(array $data, array $options = [])
 * @method \MapModule\Model\Entity\Rotation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MapModule\Model\Entity\Rotation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MapModule\Model\Entity\Rotation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \MapModule\Model\Entity\Rotation[] patchEntities($entities, array $data, array $options = [])
 * @method \MapModule\Model\Entity\Rotation findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RotationsTable extends Table
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

        $this->setTable('rotations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('MapsToRotations', [
            'foreignKey' => 'rotation_id',
            'className' => 'MapModule.MapsToRotations',
        ]);
        $this->hasMany('RotationsToContainers', [
            'foreignKey' => 'rotation_id',
            'className' => 'MapModule.RotationsToContainers',
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
