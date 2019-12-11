<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\MapFilter;

/**
 * Maps Model
 *
 * @property \MapModule\Model\Table\MapgadgetsTable&\Cake\ORM\Association\HasMany $Mapgadgets
 * @property \MapModule\Model\Table\MapiconsTable&\Cake\ORM\Association\HasMany $Mapicons
 * @property \MapModule\Model\Table\MapitemsTable&\Cake\ORM\Association\HasMany $Mapitems
 * @property \MapModule\Model\Table\MaplinesTable&\Cake\ORM\Association\HasMany $Maplines
 * @property \MapModule\Model\Table\MapsToContainersTable&\Cake\ORM\Association\HasMany $MapsToContainers
 * @property \MapModule\Model\Table\MapsToRotationsTable&\Cake\ORM\Association\HasMany $MapsToRotations
 * @property \MapModule\Model\Table\MapsummaryitemsTable&\Cake\ORM\Association\HasMany $Mapsummaryitems
 * @property \MapModule\Model\Table\MaptextsTable&\Cake\ORM\Association\HasMany $Maptexts
 *
 * @method \MapModule\Model\Entity\Map get($primaryKey, $options = [])
 * @method \MapModule\Model\Entity\Map newEntity($data = null, array $options = [])
 * @method \MapModule\Model\Entity\Map[] newEntities(array $data, array $options = [])
 * @method \MapModule\Model\Entity\Map|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MapModule\Model\Entity\Map saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \MapModule\Model\Entity\Map patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \MapModule\Model\Entity\Map[] patchEntities($entities, array $data, array $options = [])
 * @method \MapModule\Model\Entity\Map findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
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
            'className' => 'Containers',
            'joinTable' => 'maps_to_containers',
            'dependent' => true,
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
        $this->hasMany('MapsToContainers', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.MapsToContainers',
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
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
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
        //debug($MapFilter->getOrderForPaginator('Maps.name', 'asc'));
        //die();
        $query = $this->find('all')
            ->where($MapFilter->indexFilter())
            ->distinct('Maps.id')
            ->contain(['Containers', 'MapsToContainers'])
            ->innerJoinWith('Containers', function ($q) use ($MY_RIGHTS) {
                if (!empty($MY_RIGHTS)) {
                    return $q->where(['Containers.id IN' => $MY_RIGHTS]);
                }
                return $q;
            })
            ->innerJoinWith('MapsToContainers', function ($q) use ($MY_RIGHTS) {
                if (!empty($MY_RIGHTS)) {
                    return $q->where(['MapsToContainers.map_id' => 'Maps.id']);
                }
                return $q;
            })
            ->enableAutoFields()
            ->disableHydration()
            ->order($MapFilter->getOrderForPaginator('Maps.name', 'asc'));


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
