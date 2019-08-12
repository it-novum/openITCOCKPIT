<?php

namespace App\Model\Table;

use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\SystemdowntimesConditions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;

/**
 * Systemdowntimes Model
 *
 *
 * @method \App\Model\Entity\Systemdowntime get($primaryKey, $options = [])
 * @method \App\Model\Entity\Systemdowntime newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Systemdowntime[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Systemdowntime|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Systemdowntime saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Systemdowntime patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Systemdowntime[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Systemdowntime findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SystemdowntimesTable extends Table {

    use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('systemdowntimes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Hosts', [
            'foreignKey' => 'object_id',
            'joinType'   => 'INNER',
            'className'  => 'Hosts',
        ]);

        $this->belongsTo('Services', [
            'foreignKey' => 'object_id',
            'joinType'   => 'INNER',
            'className'  => 'Services',
        ]);

        $this->belongsTo('Hostgroups', [
            'foreignKey' => 'object_id',
            'joinType'   => 'INNER',
            'className'  => 'Hostgroups',
        ]);

        $this->belongsTo('Containers', [
            'foreignKey' => 'object_id',
            'joinType'   => 'INNER',
            'className'  => 'Containers',
        ]);
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
            ->scalar('weekdays')
            ->maxLength('weekdays', 255)
            ->allowEmptyString('weekdays');

        $validator
            ->scalar('day_of_month')
            ->maxLength('day_of_month', 255)
            ->allowEmptyString('day_of_month');

        $validator
            ->scalar('from_time')
            ->maxLength('from_time', 255)
            ->requirePresence('from_time', 'create')
            ->notEmptyString('from_time');

        $validator
            ->scalar('to_time')
            ->maxLength('to_time', 255)
            ->allowEmptyString('to_time');

        $validator
            ->integer('duration')
            ->notEmptyString('duration');

        $validator
            ->scalar('comment')
            ->maxLength('comment', 255)
            ->allowEmptyString('comment');

        $validator
            ->scalar('author')
            ->maxLength('author', 255)
            ->allowEmptyString('author');

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
        return $rules;
    }


    /**
     * @param SystemdowntimesConditions $SystemdowntimesConditions
     * @param PaginateOMat|null $PaginateOMat
     * @return array
     */
    public function getRecurringHostDowntimes(SystemdowntimesConditions $SystemdowntimesConditions, $PaginateOMat = null) {
        $MY_RIGHTS = $SystemdowntimesConditions->getContainerIds();

        $query = $this->find()
            ->select([
                'Systemdowntimes.id',
                'Systemdowntimes.objecttype_id',
                'Systemdowntimes.object_id',
                'Systemdowntimes.downtimetype_id',
                'Systemdowntimes.weekdays',
                'Systemdowntimes.day_of_month',
                'Systemdowntimes.from_time',
                'Systemdowntimes.to_time',
                'Systemdowntimes.duration',
                'Systemdowntimes.comment',
                'Systemdowntimes.author',
            ])
            ->contain([
                'Hosts' => function (Query $query) use ($MY_RIGHTS) {
                    $query->innerJoinWith('HostsToContainersSharing', function (Query $q) use ($MY_RIGHTS) {
                        if (!empty($MY_RIGHTS)) {
                            return $q->where(['HostsToContainersSharing.id IN' => $MY_RIGHTS]);
                        }
                        return $q;
                    });
                    $query->contain('HostsToContainersSharing');
                    return $query;
                }
            ])
            ->andWhere([
                'Systemdowntimes.objecttype_id' => OBJECT_HOST
            ]);

        if ($SystemdowntimesConditions->hasConditions()) {
            $query->andWhere($SystemdowntimesConditions->getConditions());
        }

        $query->group([
            'Systemdowntimes.id'
        ])
            ->order($SystemdowntimesConditions->getOrder())
            ->disableHydration();

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->emptyArrayIfNull($query->toArray());
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
     * @param SystemdowntimesConditions $SystemdowntimesConditions
     * @param PaginateOMat|null $PaginateOMat
     * @return array
     */
    public function getRecurringServiceDowntimes(SystemdowntimesConditions $SystemdowntimesConditions, $PaginateOMat = null) {
        $MY_RIGHTS = $SystemdowntimesConditions->getContainerIds();

        $query = $this->find();
        $query->select([
            'Systemdowntimes.id',
            'Systemdowntimes.objecttype_id',
            'Systemdowntimes.object_id',
            'Systemdowntimes.downtimetype_id',
            'Systemdowntimes.weekdays',
            'Systemdowntimes.day_of_month',
            'Systemdowntimes.from_time',
            'Systemdowntimes.to_time',
            'Systemdowntimes.duration',
            'Systemdowntimes.comment',
            'Systemdowntimes.author',

            'servicename' => $query->newExpr('IF(Services.name IS NULL, Servicetemplates.name, Services.name)')
        ])
            ->contain([
                'Services' => function (Query $query) use ($MY_RIGHTS) {
                    $query->contain([
                        'Hosts' => function (Query $query) use ($MY_RIGHTS) {
                            $query->innerJoinWith('HostsToContainersSharing', function (Query $q) use ($MY_RIGHTS) {
                                if (!empty($MY_RIGHTS)) {
                                    return $q->where(['HostsToContainersSharing.id IN' => $MY_RIGHTS]);
                                }
                                return $q;
                            });
                            $query->contain('HostsToContainersSharing');
                            return $query;
                        },
                        'Servicetemplates'
                    ]);
                    return $query;
                },


            ])
            ->andWhere([
                'Systemdowntimes.objecttype_id' => OBJECT_SERVICE
            ]);

        $where = $SystemdowntimesConditions->getConditions();
        $having = null;
        if (isset($where['servicename LIKE'])) {
            $having = [
                'servicename LIKE' => $where['servicename LIKE']
            ];
            unset($where['servicename LIKE']);
        }

        if (!empty($where)) {
            $query->andWhere($where);
        }

        if (!empty($having)) {
            $query->having($having);
        }

        $query->group([
            'Systemdowntimes.id'
        ])
            ->order($SystemdowntimesConditions->getOrder())
            ->disableHydration();

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->emptyArrayIfNull($query->toArray());
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
     * @param SystemdowntimesConditions $SystemdowntimesConditions
     * @param PaginateOMat|null $PaginateOMat
     * @return array
     */
    public function getRecurringHostgroupDowntimes(SystemdowntimesConditions $SystemdowntimesConditions, $PaginateOMat = null) {
        $MY_RIGHTS = $SystemdowntimesConditions->getContainerIds();

        $query = $this->find();
        $query->select([
            'Systemdowntimes.id',
            'Systemdowntimes.objecttype_id',
            'Systemdowntimes.object_id',
            'Systemdowntimes.downtimetype_id',
            'Systemdowntimes.weekdays',
            'Systemdowntimes.day_of_month',
            'Systemdowntimes.from_time',
            'Systemdowntimes.to_time',
            'Systemdowntimes.duration',
            'Systemdowntimes.comment',
            'Systemdowntimes.author',
        ])
            ->contain([
                'Hostgroups' => function (Query $query) {
                    $query
                        ->disableAutoFields()
                        ->select([
                            'Hostgroups.id',
                            'Hostgroups.uuid',
                            'Hostgroups.description',
                            'Hostgroups.container_id'
                        ])
                        ->contain([
                            'Containers' => function (Query $query) {
                                $query
                                    ->disableAutoFields()
                                    ->select([
                                        'Containers.id',
                                        'Containers.containertype_id',
                                        'Containers.name'
                                    ]);
                                return $query;
                            }
                        ]);
                    return $query;
                }
            ])
            ->andWhere([
                'Systemdowntimes.objecttype_id' => OBJECT_HOSTGROUP
            ]);

        if (!empty($MY_RIGHTS)) {
            $query->andWhere([
                'Hostgroups.container_id IN' => $MY_RIGHTS
            ]);
        }

        if ($SystemdowntimesConditions->hasConditions()) {
            $query->andWhere($SystemdowntimesConditions->getConditions());
        }

        $query->group([
            'Systemdowntimes.id'
        ])
            ->order($SystemdowntimesConditions->getOrder())
            ->disableHydration();

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->emptyArrayIfNull($query->toArray());
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
     * @param SystemdowntimesConditions $SystemdowntimesConditions
     * @param PaginateOMat|null $PaginateOMat
     * @return array
     */
    public function getRecurringNodeDowntimes(SystemdowntimesConditions $SystemdowntimesConditions, $PaginateOMat = null) {
        $MY_RIGHTS = $SystemdowntimesConditions->getContainerIds();

        $query = $this->find();
        $query->select([
            'Systemdowntimes.id',
            'Systemdowntimes.objecttype_id',
            'Systemdowntimes.object_id',
            'Systemdowntimes.downtimetype_id',
            'Systemdowntimes.weekdays',
            'Systemdowntimes.day_of_month',
            'Systemdowntimes.from_time',
            'Systemdowntimes.to_time',
            'Systemdowntimes.duration',
            'Systemdowntimes.comment',
            'Systemdowntimes.author',
        ])
            ->contain([
                'Containers' => function (Query $query) {
                    $query
                        ->disableAutoFields()
                        ->select([
                            'Containers.id',
                            'Containers.containertype_id',
                            'Containers.name'
                        ]);
                    return $query;
                }
            ])
            ->andWhere([
                'Systemdowntimes.objecttype_id' => OBJECT_NODE
            ]);

        if (!empty($MY_RIGHTS)) {
            $query->andWhere([
                'Containers.id IN' => $MY_RIGHTS
            ]);
        }

        if ($SystemdowntimesConditions->hasConditions()) {
            $query->andWhere($SystemdowntimesConditions->getConditions());
        }

        $query->group([
            'Systemdowntimes.id'
        ])
            ->order($SystemdowntimesConditions->getOrder())
            ->disableHydration();

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->emptyArrayIfNull($query->toArray());
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scrollCake4($query, $PaginateOMat->getHandler());
            } else {
                $result = $this->paginateCake4($query, $PaginateOMat->getHandler());
            }
        }

        return $result;
    }
}
