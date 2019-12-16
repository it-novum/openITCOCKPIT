<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use App\Model\Table\ContainersTable;
use App\Model\Table\UsersTable;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use MapModule\Model\Entity\MapUpload;

/**
 * MapUploads Model
 *
 * @property UsersTable&BelongsTo $Users
 * @property ContainersTable&BelongsTo $Containers
 *
 * @method MapUpload get($primaryKey, $options = [])
 * @method MapUpload newEntity($data = null, array $options = [])
 * @method MapUpload[] newEntities(array $data, array $options = [])
 * @method MapUpload|false save(EntityInterface $entity, $options = [])
 * @method MapUpload saveOrFail(EntityInterface $entity, $options = [])
 * @method MapUpload patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method MapUpload[] patchEntities($entities, array $data, array $options = [])
 * @method MapUpload findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin TimestampBehavior
 */
class MapUploadsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('map_uploads');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'className'  => 'Users',
        ]);
        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'className'  => 'Containers',
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
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['container_id'], 'Containers'));

        return $rules;
    }
}
