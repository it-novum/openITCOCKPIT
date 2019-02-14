<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Entity;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
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
            'foreignKey' => 'timeperiod_id'
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
            ->allowEmptyString('id', 'create');

        $validator
            ->integer('container_id')
            ->greaterThan('container_id', 0)
            ->requirePresence('container_id', 'create')
            ->allowEmptyString('container_id', false);

        $validator
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->allowEmptyString('uuid', false);

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false)
            ->add('name', 'unique', [
                'rule'     => 'validateUnique',
                'provider' => 'table',
                'message'  => __('This timeperiod name has already been taken.')
            ]);
        /*
                $validator
                    ->add('validate_timeranges', 'custom', [
                        'rule'    => function ($value, $context) {
                            if (empty($context['data']['timeperiod_timeranges'])) {
                                return true;
                            }
                            $error_arr = [];
                            foreach ($context['data']['timeperiod_timeranges'] as $key => $row) {
                                $day[$key] = $row['day'];
                                $start[$key] = $row['start'];
                            }
                            array_multisort($day, SORT_ASC, $start, SORT_ASC, $context['data']['timeperiod_timeranges']);
                            $check_timerange_array = [];
                            foreach ($context['data']['timeperiod_timeranges'] as $key => $timerange) {
                                $check_timerange_array[$timerange['day']][] = [
                                    'start' => $timerange['start'],
                                    'end'   => $timerange['end']
                                ];
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
                                                $error_arr[$day][] = $intern_counter;

                                                //	$this->invalidate('Timeperiod.'.$day.'.'.$intern_counter, 'state-error');
                                                //$this->invalidate('Timerange.' . $day . '.' . $intern_counter . '.start', 'state-error');
                                                //$this->setErrors('Timerange.' . $day . '.' . $intern_counter . '.start', ['state-error']);

                                            } else {
                                                $tmp_start = $timerange_data[$intern_counter]['start'];
                                                $tmp_end = $timerange_data[$intern_counter]['end'];
                                                $input_key++;

                                            }
                                        }
                                    }
                                }
                            }
                            if (sizeof($error_arr) > 0) {
                                return false;
                            }
                            return true;
                        },
                        'message' => __('Do not enter overlapping time frames')
                    ]);
        */

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
        $rules->add($rules->isUnique(['name']));
        $rules->add($rules->isUnique(['uuid']));
        $rules->add($rules->existsIn(['container_id'], 'Containers'));
        $rules->add(function ($entity, $options) {

         /*
            $entity->setInvalidField('validate_timeranges', false);
            $entity->setErrors([
                'validate_timeranges' => [
                    1 => 'kaputt 1',
                    3 => 'kaputt 3'
                ]
            ]);

         */
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
                $entity->setInvalidField('validate_timeranges', false);

                if(!empty($errors)){
                    $entity->setInvalidField('validate_timeranges', false);

                    $entity->setErrors([
                        'validate_timeranges' => $errors
                    ]);
                    return false;
                }
            }

            /** @var $entity Entity */

            /**
            $entity->setInvalidField('validate_timeranges', false);
            $entity->setErrors([
                'validate_timeranges' => [
                    1 => 'kaputt 1',
                    3 => 'kaputt 3'
                ]
            ]);
            //   debug($options);
             */
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

    public function getTimeperiodById($id) {
        $query = $this->find()
            ->where([
                'Timeperiods.id' => $id
            ])
            ->first();
        return $this->formatFirstResultAsCake2($query->toArray(), false);
    }

    /**
     * @param int|array $containerIds
     * @return array
     */
    public function getCommandByContainerIdsAsList($containerIds = []) {
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

                            //	$this->invalidate('Timeperiod.'.$day.'.'.$intern_counter, 'state-error');
                            //$this->invalidate('Timerange.' . $day . '.' . $intern_counter . '.start', 'state-error');

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
}
