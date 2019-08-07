<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\TimeperiodsFilter;

/**
 * Timeperiods Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\TimeperiodTimerangesTable|\Cake\ORM\Association\HasMany $TimeperiodTimeranges
 *
 * @method \App\Model\Entity\Timeperiod get($primaryKey, $options = [])
 * @method \App\Model\Entity\Timeperiod newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Timeperiod[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Timeperiod|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Timeperiod|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Timeperiod patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Timeperiod[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Timeperiod findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TimeperiodsTable extends Table {

    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;


    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('timeperiods');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
        ]);

        $this->hasMany('TimeperiodTimeranges', [
            'foreignKey'   => 'timeperiod_id',
            'saveStrategy' => 'replace'
        ])->setDependent(true);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->allowEmptyString('uuid', null, false)
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->integer('container_id')
            ->greaterThan('container_id', 0)
            ->requirePresence('container_id')
            ->allowEmptyString('container_id', null, false);

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name')
            ->allowEmptyString('name', null, false);

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        return $validator;
    }


    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['uuid']));
        $rules->add($rules->existsIn(['container_id'], 'Containers'));
        /** @var $entity Entity */
        $rules->add(function ($entity, $options) {
            if (empty($entity->timeperiod_timeranges)) {
                return true;
            }
            if (!empty($entity->timeperiod_timeranges)) {
                $data = [];
                foreach ($entity->timeperiod_timeranges as $timeperiodTimerangEentity) {
                    $data[] = [
                        'day'   => $timeperiodTimerangEentity->day,
                        'start' => $timeperiodTimerangEentity->start,
                        'end'   => $timeperiodTimerangEentity->end
                    ];
                }
                $errors = $this->checkTimerangeOvelapping($data);
                if (!empty($errors)) {
                    $entity->setInvalidField('validate_timeranges', false);
                    $entity->setErrors([
                        'validate_timeranges' => $errors
                    ]);
                    return false;
                }
                return true;
            }
        }, 'validate_timeranges');

        return $rules;
    }

    /**
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getAllTimeperiodsAsCake2($MY_RIGHTS) {
        $query = $this->find()
            ->where([
                'Timeperiods.container_id IN' => $MY_RIGHTS
            ])
            ->order(['Timeperiods.name' => 'asc'])
            ->disableHydration()
            ->all();
        return $this->formatResultAsCake2($query->toArray(), false);
    }

    /**
     * @return array
     */
    public function getAllTimeperiodsUuidsAsList() {
        $query = $this->find('list', [
            'keyField'   => 'id',
            'valueField' => 'uuid'
        ])
            ->disableHydration();
        return $query->toArray();
    }

    /**
     * @param TimeperiodsFilter $TimeperiodsFilter
     * @param null|PaginateOMat $PaginateOMat
     * @return array
     */
    public function getTimeperiodsIndex(TimeperiodsFilter $TimeperiodsFilter, $PaginateOMat = null) {
        $query = $this->find('all')->disableHydration();
        $query->where($TimeperiodsFilter->indexFilter());
        $query->order($TimeperiodsFilter->getOrderForPaginator('Timeperiods.name', 'asc'));

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

    /**
     * @param $id
     * @return array
     */
    public function getTimeperiodById($id) {
        $query = $this->find()
            ->where([
                'Timeperiods.id' => $id
            ])
            ->first();
        return $this->formatFirstResultAsCake2($query->toArray(), false);
    }

    /**
     * @return array
     */
    public function getTimeperiodWithTimeranges() {
        $query = $this->find()
            ->contain('TimeperiodTimeranges')
            ->disableHydration();
        return $this->formatResultAsCake2($query->toArray(), false);
    }

    /**
     * @param $id
     * @return array
     */
    public function getTimeperiodWithTimerangesById($id) {
        $query = $this->find()
            ->contain('TimeperiodTimeranges')
            ->where([
                'Timeperiods.id' => $id
            ])
            ->first();
        return $this->formatFirstResultAsCake2($query->toArray(), false);
    }

    /**
     * @param $uuid
     * @return array
     */
    public function getTimeperiodWithTimerangesByUuid($uuid) {
        $query = $this->find()
            ->contain('TimeperiodTimeranges')
            ->where([
                'Timeperiods.uuid' => $uuid
            ])
            ->first();
        return $this->formatFirstResultAsCake2($query->toArray(), false);
    }

    /**
     * @param int|array $containerIds
     * @return array
     */
    public function getTimeperiodByContainerIdsAsList($containerIds = []) {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $timeperiods = $this->find()
            ->select([
                'Timeperiods.id',
                'Timeperiods.name'
            ])
            ->where(['Timeperiods.container_id IN' => $containerIds])
            ->all();

        return $this->formatListAsCake2($timeperiods->toArray());
    }

    /**
     * @param array $calendarIds
     * @return array
     */
    public function getTimeperiodByCalendarIdsAsList($calendarIds = []) {
        if (!is_array($calendarIds)) {
            $calendarIds = [$calendarIds];
        }

        $timeperiods = $this->find()
            ->select([
                'Timeperiods.id',
                'Timeperiods.name'
            ])
            ->where(['Timeperiods.calendar_id IN' => $calendarIds])
            ->all();

        return $this->formatListAsCake2($timeperiods->toArray());
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getTimeperiodsAsList($ids = []) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $query = $this->find()
            ->select([
                'Timeperiods.id',
                'Timeperiods.name'
            ])
            ->disableHydration();
        if (!empty($ids)) {
            $query->where([
                'Timeperiods.id IN' => $ids
            ]);
        }

        return $this->formatListAsCake2($query->toArray());
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getTimeperiodsForCopy($ids = []) {
        $query = $this->find()
            ->select([
                'Timeperiods.id',
                'Timeperiods.name',
                'Timeperiods.description'
            ])
            ->where(['Timeperiods.id IN' => $ids])
            ->order(['Timeperiods.id' => 'asc'])
            ->disableHydration()
            ->all();

        return $this->formatResultAsCake2($query->toArray(), false);
    }

    public function checkTimerangeOvelapping($timeranges) {
        $error_arr = [];
        foreach ($timeranges as $key => $row) {
            $day[$key] = $row['day'];
            $start[$key] = $row['start'];
        }
        array_multisort($day, SORT_ASC, $start, SORT_ASC, $timeranges);
        $check_timerange_array = [];
        foreach ($timeranges as $key => $timerange) {
            $check_timerange_array[$timerange['day']][] = ['start' => $timerange['start'], 'end' => $timerange['end']];
        }
        $error_arr = [];
        foreach ($check_timerange_array as $day => $timerange_data) {
            if (sizeof($timerange_data) > 1) {
                $intern_counter = 0;
                $tmp_start = $check_timerange_array[$day][$intern_counter]['start'];
                $tmp_end = $check_timerange_array[$day][$intern_counter]['end'];
                for ($input_key = 0; $input_key < sizeof($timerange_data); $input_key++) {
                    $intern_counter++;
                    if (isset($timerange_data[$intern_counter])) {
                        if ($tmp_start <= $timerange_data[$intern_counter]['start'] &&
                            $tmp_end > $timerange_data[$intern_counter]['start']
                        ) {
                            if ($tmp_end <= $timerange_data[$intern_counter]['end']) {
                                $tmp_end = $timerange_data[$intern_counter]['end'];
                            } else {
                                $input_key--;
                            }
                            $error_arr[$intern_counter]['Timeperiod'][$day] = 'state-error';
                            $timeranges[$intern_counter] = 'error';
                        } else {
                            $tmp_start = $timerange_data[$intern_counter]['start'];
                            $tmp_end = $timerange_data[$intern_counter]['end'];
                            $input_key++;

                        }
                    }
                }
            }
        }
        return $error_arr;
    }

    /**
     * @param $id
     * @return string|null
     */
    public function getTimeperiodUuidById($id) {
        $timeperiod = $this->find('all')
            ->select(['Timeperiods.uuid'])
            ->where(['Timeperiods.id' => $id])
            ->first();

        if (is_null($timeperiod)) {
            return null;
        }

        return $timeperiod->uuid;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Timeperiods.id' => $id]);
    }

    public function timeperiodsByContainerId($container_ids = [], $type = 'all') {
        if (!is_array($container_ids)) {
            $container_ids = [$container_ids];
        }

        $container_ids = array_unique($container_ids);

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $tenantContainerIds = [];
        foreach ($container_ids as $container_id) {
            if ($container_id != ROOT_CONTAINER) {
                $path = $ContainersTable->getPathByIdAndCacheResult($container_id, 'TimeperiodTimeperiodsByContainerId');
                // Get container id of the tenant container
                // Tenant timeperiods are available for all users of a tenant (oITC V2 legacy)
                if (isset($path[1])) {
                    $tenantContainerIds[] = $path[1]['id'];
                }
            } else {
                $tenantContainerIds[] = ROOT_CONTAINER;
            }
        }
        $tenantContainerIds = array_unique($tenantContainerIds);

        $containerIds = array_unique(array_merge($tenantContainerIds, $container_ids));
        if (empty($containerIds)) {
            return [];
        }

        $query = $this->find('all');
        $query->contain(['Containers']);
        $query->innerJoinWith('Containers', function (Query $q) use ($containerIds) {
            return $q->where(['Containers.id IN' => $containerIds]);
        });

        $query->distinct('Timeperiods.id');
        $query->disableHydration();

        if ($type === 'all') {
            return $this->formatResultAsCake2($query->toArray());
        }

        return $this->formatListAsCake2($query->toArray());
    }

    /**
     * @param int $timeperiodId
     * @param int $containerId
     * @param int $fallbackTimeperiodId
     * @return int
     */
    public function checkTimeperiodIdForContainerPermissions($timeperiodId, $containerId, $fallbackTimeperiodId) {
        $tenantContainerIds = [];

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);

        foreach ($containerIds as $containerId) {
            if ($containerId != ROOT_CONTAINER) {
                $path = $ContainersTable->getPathByIdAndCacheResult($containerId, 'TimeperiodCheckTimeperiodIdForContainerPermissions');
                // Get container id of the tenant container
                // Tenant timeperiods are available for all users of a tenant (oITC V2 legacy)
                if (isset($path[1])) {
                    $tenantContainerIds[] = $path[1]['id'];
                }
            } else {
                $tenantContainerIds[] = ROOT_CONTAINER;
            }
        }

        $containerIds = array_unique(array_merge($tenantContainerIds, $containerIds));

        $timeperiod = $this->find()
            ->select([
                'Timeperiods.id',
                'Timeperiods.container_id'
            ])
            ->where([
                'Timeperiods.id ' => $timeperiodId
            ])
            ->disableHydration()
            ->first();

        if ($timeperiod === null) {
            return $fallbackTimeperiodId;
        }

        if (in_array($timeperiod['container_id'], $containerIds, true)) {
            return $timeperiod['id'];
        }

        return $fallbackTimeperiodId;
    }

}
