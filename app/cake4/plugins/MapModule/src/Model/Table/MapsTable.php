<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use App\Model\Table\ContainersTable;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\MapFilter;
use MapModule\Model\Entity\Map;

/**
 * Maps Model
 *
 * @property MapgadgetsTable&HasMany $Mapgadgets
 * @property MapiconsTable&HasMany $Mapicons
 * @property MapitemsTable&HasMany $Mapitems
 * @property MaplinesTable&HasMany $Maplines
 * @property ContainersTable&HasMany $MapsToContainers
 * @property RotationsTable&HasMany $MapsToRotations
 * @property MapsummaryitemsTable&HasMany $Mapsummaryitems
 * @property MaptextsTable&HasMany $Maptexts
 *
 * @method Map get($primaryKey, $options = [])
 * @method Map newEntity($data = null, array $options = [])
 * @method Map[] newEntities(array $data, array $options = [])
 * @method Map|false save(EntityInterface $entity, $options = [])
 * @method Map saveOrFail(EntityInterface $entity, $options = [])
 * @method Map patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Map[] patchEntities($entities, array $data, array $options = [])
 * @method Map findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin TimestampBehavior
 */
class MapsTable extends Table {

    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;
    use CustomValidationTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('maps');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Containers', [
            'className'        => 'Containers',
            'foreignKey'       => 'map_id',
            'targetForeignKey' => 'container_id',
            'joinTable'        => 'maps_to_containers',
            //'saveStrategy'     => 'replace'
        ]);

        $this->hasMany('Mapgadgets', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.Mapgadgets',
        ]);
        $this->hasMany('Mapicons', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.Mapicons',
        ]);
        $this->hasMany('Mapitems', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.Mapitems',
        ]);
        $this->hasMany('Maplines', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.Maplines',
        ]);
        $this->hasMany('MapsToRotations', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.MapsToRotations',
        ]);
        $this->hasMany('Mapsummaryitems', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.Mapsummaryitems',
        ]);
        $this->hasMany('Maptexts', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.Maptexts',
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
            ->requirePresence('containers', true, __('You have to choose at least one option.'))
            ->allowEmptyString('containers', null, false)
            ->multipleOptions('containers', [
                'min' => 1
            ], __('You have to choose at least one option.'));

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('background')
            ->maxLength('background', 128)
            ->allowEmptyString('background');

        $validator
            ->integer('refresh_interval')
            ->notEmptyString('refresh_interval');

        return $validator;
    }

    /**
     * @param MapFilter $MapFilter
     * @param null|PaginateOMat $PaginateOMat
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getMapsIndex(MapFilter $MapFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        if (!is_array($MY_RIGHTS)) {
            $MY_RIGHTS = [$MY_RIGHTS];
        }
        $query = $this->find('all')
            ->where($MapFilter->indexFilter())
            ->distinct('Maps.id')
            ->contain(['Containers'])
            ->innerJoinWith('Containers', function (Query $query) use ($MY_RIGHTS) {
                if (!empty($MY_RIGHTS)) {
                    return $query->where(['Containers.id IN' => $MY_RIGHTS]);
                }
                return $query;
            })
            ->order($MapFilter->getOrderForPaginator('Maps.name', 'asc'));

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $query->toArray();
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scrollCake4($query, $PaginateOMat->getHandler());
            } else {
                $result = $this->paginateCake4($query, $PaginateOMat->getHandler());
            }
        }

        return $result;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Maps.id' => $id]);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getMapForEdit($id) {
        $query = $this->find()
            ->where([
                'Maps.id' => $id
            ])
            ->contain([
                'Containers'
            ])
            ->disableHydration()
            ->first();


        $contact = $query;
        $contact['containers'] = [
            '_ids' => Hash::extract($query, 'containers.{n}.id')
        ];

        return [
            'Map' => $contact
        ];
    }

    /**
     * @param array $ids
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getMapsForCopy($ids = [], $MY_RIGHTS = []) {
        if (!is_array($MY_RIGHTS)) {
            $MY_RIGHTS = [$MY_RIGHTS];
        }

        $query = $this->find()
            ->select([
                'Maps.id',
                'Maps.name',
                'Maps.title',
                'Maps.refresh_interval'
            ])
            ->where(['Maps.id IN' => $ids])
            ->order(['Maps.id' => 'asc'])
            ->contain(['Containers'])
            ->innerJoinWith('Containers', function (Query $query) use ($MY_RIGHTS) {
                if (!empty($MY_RIGHTS)) {
                    return $query->where(['Containers.id IN' => $MY_RIGHTS]);
                }
                return $query;
            })
            ->group(['Maps.id'])
            ->disableHydration()
            ->all();

        return $query->toArray();
    }
}
