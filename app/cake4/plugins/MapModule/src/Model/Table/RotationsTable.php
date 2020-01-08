<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use App\Model\Table\ContainersTable;
use Cake\Database\Query;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
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

    use PaginationAndScrollIndexTrait;
    use Cake2ResultTableTrait;

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

    /**
     * @param $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Rotations.id' => $id]);
    }

    /**
     * @param array $indexFilter
     * @param array $orderForPaginator
     * @param int|null $limit
     * @param PaginateOMat|null $PaginateOMat
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getAll(array $indexFilter, array $orderForPaginator, int $limit = null, PaginateOMat $PaginateOMat = null, $MY_RIGHTS = []) {
        if (!is_array($MY_RIGHTS)) {
            $MY_RIGHTS = [$MY_RIGHTS];
        }

        $query = $this->find()
            ->contain([
                'MapsToRotations' => function (Query $q) {
                    return $q->select([
                        'MapsToRotations.map_id',
                        'MapsToRotations.rotation_id'
                    ]);
                },
                /*'RotationsToContainers' => function (Query $q) {
                    return $q->select([
                        'RotationsToContainers.container_id',
                        'RotationsToContainers.rotation_id'
                    ]);
                }*/
            ])
            ->join([
                [
                    'table'      => 'rotations_to_containers',
                    'type'       => 'INNER',
                    'alias'      => 'RotationsToContainers',
                    'conditions' => 'RotationsToContainers.rotation_id = Rotations.id',
                ],
            ])
            ->where($indexFilter)
            ->select([
                'RotationsToContainers.container_id',
                'RotationsToContainers.rotation_id',
            ]);

        if (!empty($MY_RIGHTS)) {
            $query->where([
                'RotationsToContainers.container_id IN' => $this->MY_RIGHTS
            ]);
        }
        if ($limit !== null) {
            $query->limit($limit);
        }
        $query->order($orderForPaginator)
            ->group(['Rotations.id'])
            ->enableAutoFields(true)
            ->all();
        return $query->toArray();
        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->formatResultAsCake2($query->toArray(), false);
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scroll($query, $PaginateOMat->getHandler(), false);
            } else {
                $result = $this->paginate($query, $PaginateOMat->getHandler(), false);
            }
        }

        return $result;
    }
}
