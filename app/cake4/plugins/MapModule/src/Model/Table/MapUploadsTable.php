<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MapUploads Model
 *
 * @property \MapModule\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \MapModule\Model\Table\ContainersTable&\Cake\ORM\Association\BelongsTo $Containers
 *
 * @method \MapModule\Model\Entity\MapUpload get($primaryKey, $options = [])
 * @method \MapModule\Model\Entity\MapUpload newEntity($data = null, array $options = [])
 * @method \MapModule\Model\Entity\MapUpload[] newEntities(array $data, array $options = [])
 * @method \MapModule\Model\Entity\MapUpload|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MapModule\Model\Entity\MapUpload saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MapModule\Model\Entity\MapUpload patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \MapModule\Model\Entity\MapUpload[] patchEntities($entities, array $data, array $options = [])
 * @method \MapModule\Model\Entity\MapUpload findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MapUploadsTable extends Table
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

        $this->setTable('map_uploads');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'className' => 'MapModule.Users',
        ]);
        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'className' => 'MapModule.Containers',
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
            ->integer('upload_type')
            ->allowEmptyString('upload_type');

        $validator
            ->scalar('upload_name')
            ->maxLength('upload_name', 255)
            ->requirePresence('upload_name', 'create')
            ->notEmptyString('upload_name');

        $validator
            ->scalar('saved_name')
            ->maxLength('saved_name', 255)
            ->requirePresence('saved_name', 'create')
            ->notEmptyString('saved_name');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['container_id'], 'Containers'));

        return $rules;
    }
}
