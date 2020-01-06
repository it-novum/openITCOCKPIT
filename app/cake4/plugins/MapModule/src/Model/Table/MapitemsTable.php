<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\FileDebugger;
use MapModule\Model\Entity\Mapitem;

/**
 * Mapitems Model
 *
 * @property MapsTable&BelongsTo $Maps
 *
 * @method Mapitem get($primaryKey, $options = [])
 * @method Mapitem newEntity($data = null, array $options = [])
 * @method Mapitem[] newEntities(array $data, array $options = [])
 * @method Mapitem|false save(EntityInterface $entity, $options = [])
 * @method Mapitem saveOrFail(EntityInterface $entity, $options = [])
 * @method Mapitem patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Mapitem[] patchEntities($entities, array $data, array $options = [])
 * @method Mapitem findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin TimestampBehavior
 */
class MapitemsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('mapitems');
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
            ->integer('limit')
            ->allowEmptyString('limit');

        $validator
            ->scalar('iconset')
            ->maxLength('iconset', 128)
            ->requirePresence('iconset', 'create')
            ->notEmptyString('iconset');

        $validator
            ->scalar('type')
            ->maxLength('type', 20)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->integer('z_index')
            ->notEmptyString('z_index');

        $validator
            ->integer('show_label')
            ->notEmptyString('show_label');

        $validator
            ->integer('label_possition')
            ->notEmptyString('label_possition');

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
     * @param $objectId
     * @param $mapId
     * @return array
     */
    public function getMapitemsForMaps($objectId, $mapId) {
        $query = $this->find()
            ->select(['Mapitems.object_id'])
            ->where([
                'Mapitems.object_id' => $objectId,
                'Mapitems.map_id'    => $mapId,
                'Mapitems.type'      => 'map',
            ]);

        return $query->first()->toArray();
    }

    public function allVisibleMapItems($mapId, $MY_RIGHTS = []) {
        if (!is_array($MY_RIGHTS)) {
            $MY_RIGHTS = [$MY_RIGHTS];
        }

        $query = $this->find()
            ->join([
                [
                    'table'      => 'maps',
                    'type'       => 'INNER',
                    'alias'      => 'Maps',
                    'conditions' => 'Maps.id = Mapitems.map_id',
                ],
                [
                    'table'      => 'maps_to_containers',
                    'type'       => 'INNER',
                    'alias'      => 'MapsToContainers',
                    'conditions' => 'MapsToContainers.map_id = Maps.id',
                ],
            ])
            ->select([
                'Mapitems.map_id',
                'Mapitems.object_id',
            ])
            ->where([
                'Mapitems.type'       => 'map',
                'Mapitems.map_id != ' => $mapId
            ]);
        if (!empty($MY_RIGHTS)) {
            $query->where(['MapsToContainers.container_id IN' => $MY_RIGHTS]);
        }

        $result = $query->first();
        if(empty($result)) {
           return [];
        }
        return $result->toArray();
        /*
         * $query = [
                            'recursive'  => -1,
                            'joins'      => [
                                [
                                    'table'      => 'maps_to_containers',
                                    'type'       => 'INNER',
                                    'alias'      => 'MapsToContainers',
                                    'conditions' => 'MapsToContainers.map_id = Map.id',
                                ],
                            ],
                            'contain'    => [
                                'Map'
                            ],
                            'conditions' => [
                                'Mapitem.type' => 'map',
                                'NOT'          => [
                                    'Mapitem.map_id' => $map['Map']['id']
                                ]
                            ],
                            'fields'     => [
                                'Mapitem.map_id',
                                'Mapitem.object_id'
                            ]
                        ];
                        if (!$this->hasRootPrivileges) {
                            $query['conditions']['MapsToContainers.container_id'] = $this->MY_RIGHTS;
                        }
                        $allVisibleItems = $this->Mapitem->find('all', $query);
         */
    }
}
