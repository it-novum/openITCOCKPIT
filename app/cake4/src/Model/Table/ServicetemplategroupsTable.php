<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\ServicetemplategroupsConditions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ServicetemplategroupsFilter;

/**
 * Servicetemplategroups Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\ServicetemplatesTable|\Cake\ORM\Association\HasMany $servicetemplates
 *
 * @method \App\Model\Entity\Servicetemplategroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Servicetemplategroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Servicetemplategroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Servicetemplategroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Servicetemplategroup saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Servicetemplategroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Servicetemplategroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Servicetemplategroup findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServicetemplategroupsTable extends Table {

    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) :void {
        parent::initialize($config);

        $this->setTable('servicetemplategroups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
        ]);

        $this->belongsToMany('Servicetemplates', [
            'className'        => 'Servicetemplates',
            'foreignKey'       => 'servicetemplategroup_id',
            'targetForeignKey' => 'servicetemplate_id',
            'joinTable'        => 'servicetemplates_to_servicetemplategroups',
            'saveStrategy'     => 'replace'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) :Validator {
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
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description', null, true);

        $validator
            ->add('servicetemplates', 'custom', [
                'rule'    => [$this, 'atLeastOne'],
                'message' => __('You must select at least one service template.')
            ]);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) :RulesChecker {
        $rules->add($rules->isUnique(['uuid']));
        $rules->add($rules->existsIn(['container_id'], 'Containers'));

        return $rules;
    }

    /**
     * @param mixed $value
     * @param array $context
     * @return bool
     *
     * Custom validation rule for contacts and or contact groups
     */
    public function atLeastOne($value, $context) {
        return !empty($context['data']['servicetemplates']['_ids']);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Servicetemplategroups.id' => $id]);
    }

    /**
     * @param ServicetemplategroupsFilter $ServicetemplategroupsFilter
     * @param null|PaginateOMat $PaginateOMat
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getServicetemplategroupsIndex(ServicetemplategroupsFilter $ServicetemplategroupsFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        $query = $this->find('all')
            ->contain([
                'Containers'
            ])
            ->disableHydration();
        $where = $ServicetemplategroupsFilter->indexFilter();
        if (!empty($MY_RIGHTS)) {
            $where['Containers.parent_id IN'] = $MY_RIGHTS;
        }

        $query->where($where);
        $query->order($ServicetemplategroupsFilter->getOrderForPaginator('Containers.name', 'asc'));

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
     * @return array|\Cake\Datasource\EntityInterface
     */
    public function getServicetemplategroupForView($id) {
        $query = $this->find()
            ->contain([
                'containers',
                'servicetemplates' => function (Query $query) {
                    return $query->enableAutoFields(false)
                        ->select([
                            'Servicetemplates.id',
                        ]);
                }
            ])
            ->where([
                'Servicetemplategroups.id' => $id
            ])
            ->firstOrFail();

        return $query;
    }

    /**
     * @param array $dataToParse
     * @return array
     */
    public function resolveDataForChangelog($dataToParse = []) {
        $extDataForChangelog = [
            'Contact' => []
        ];

        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        foreach ($ServicetemplatesTable->getServicetemplatesAsList($dataToParse['Servicetemplategroup']['servicetemplates']['_ids']) as $servicetemplateId => $servicetemplateName) {
            $extDataForChangelog['Servicetemplate'][] = [
                'id'            => $servicetemplateId,
                'template_name' => $servicetemplateName
            ];
        }

        return $extDataForChangelog;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getServicetemplategroupForEdit($id) {
        $query = $this->find()
            ->where([
                'Servicetemplategroups.id' => $id
            ])
            ->contain([
                'Containers',
                'Servicetemplates' => function (Query $query) {
                    $query->enableAutoFields(false)
                        ->select([
                            'Servicetemplates.id'
                        ]);
                    return $query;
                }
            ])
            ->disableHydration()
            ->first();


        $servicetemplategroup = $query;
        $servicetemplategroup['servicetemplates'] = [
            '_ids' => Hash::extract($query, 'servicetemplates.{n}.id')
        ];

        return [
            'Servicetemplategroup' => $servicetemplategroup
        ];
    }

    /**
     * @param ServicetemplategroupsConditions $ServicetemplategroupsConditions
     * @param array|int $selected
     * @return array|null
     */
    public function getServicetemplategroupsForAngular(ServicetemplategroupsConditions $ServicetemplategroupsConditions, $selected = []) {
        if (!is_array($selected)) {
            $selected = [$selected];
        }
        $selected = array_filter($selected);


        $where = $ServicetemplategroupsConditions->getConditionsForFind();

        if (!empty($selected)) {
            $where['NOT'] = [
                'Servicetemplategroups.id IN' => $selected
            ];
        }

        $query = $this->find()
            ->contain([
                'containers'
            ])
            ->select([
                'Containers.name',
                'Servicetemplategroups.id'
            ])
            ->where(
                $where
            )
            ->order([
                'Containers.name' => 'asc'
            ])
            ->limit(ITN_AJAX_LIMIT)
            ->disableHydration()
            ->all();

        $groupsWithLimit = [];
        $result = $this->emptyArrayIfNull($query->toArray());
        foreach ($result as $row) {
            $groupsWithLimit[$row['id']] = $row['Containers']['name'];
        }

        $selectedGroups = [];
        if (!empty($selected)) {
            $query = $this->find()
                ->contain([
                    'containers'
                ])
                ->select([
                    'Containers.name',
                    'Servicetemplategroups.id'
                ])
                ->where([
                    'Servicetemplategroups.id IN' => $selected
                ])
                ->order([
                    'Containers.name' => 'asc'
                ])
                ->limit(ITN_AJAX_LIMIT)
                ->disableHydration()
                ->all();

            $selectedGroups = [];
            $result = $this->emptyArrayIfNull($query->toArray());
            foreach ($result as $row) {
                $selectedGroups[$row['id']] = $row['Containers']['name'];
            }
        }

        $groups = $groupsWithLimit + $selectedGroups;
        asort($groups, SORT_FLAG_CASE | SORT_NATURAL);

        return $groups;
    }


    /**
     * @param $servicetemplategroupId
     * @param array $containerIds
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getServicetemplatesforAllocation($servicetemplategroupId, $containerIds = []) {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $query = $this->find()
            ->where([
                'Servicetemplategroups.id' => $servicetemplategroupId
            ])
            ->contain([
                'Servicetemplates' => function (Query $query) use ($containerIds) {
                    $query->enableAutoFields(false)
                        ->select([
                            'Servicetemplates.id',
                            'Servicetemplates.name',
                            'Servicetemplates.description'
                        ])
                        ->order([
                            'Servicetemplates.name' => 'ASC'
                        ]);
                    if (!empty($containerIds)) {
                        $query->where([
                            'Servicetemplates.container_id IN' => $containerIds
                        ]);
                    }
                    return $query;
                }
            ])
            ->disableHydration()
            ->first();

        return $query;
    }

    /**
     * @param $id
     * @return array|\Cake\Datasource\EntityInterface
     */
    public function getServicetemplategroupNameById($id) {
        $result = $this->find()
            ->select([
                'Servicetemplategroups.id',
                'Containers.name'
            ])
            ->contain([
                'Containers'
            ])
            ->where([
                'Servicetemplategroups.id' => $id
            ])
            ->firstOrFail();

        $result = $result->toArray();
        return $result['container']['name'];
    }
}
