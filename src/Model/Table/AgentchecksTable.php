<?php

namespace App\Model\Table;

use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Filter\AgentchecksFilter;

/**
 * Agentchecks Model
 *
 * @property \App\Model\Table\ServicetemplatesTable|\Cake\ORM\Association\BelongsTo $Servicetemplates
 *
 * @method \App\Model\Entity\Agentcheck get($primaryKey, $options = [])
 * @method \App\Model\Entity\Agentcheck newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Agentcheck[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Agentcheck|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agentcheck saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agentcheck patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Agentcheck[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Agentcheck findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @deprecated
 */
class AgentchecksTable extends Table {

    use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('agentchecks');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Servicetemplates', [
            'foreignKey' => 'servicetemplate_id'
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
            ->scalar('plugin_name')
            ->maxLength('plugin_name', 255)
            ->requirePresence('plugin_name', 'create')
            ->notEmptyString('plugin_name');

        $validator
            ->scalar('servicetemplate_id')
            ->requirePresence('servicetemplate_id', 'create')
            ->notEmptyString('servicetemplate_id')
            ->integer('servicetemplate_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->existsIn(['servicetemplate_id'], 'Servicetemplates'));

        return $rules;
    }

    public function getAgentchecksIndex(AgentchecksFilter $AgentchecksFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        $query = $this->find('all');
        $where = $AgentchecksFilter->indexFilter();
        if (!empty($MY_RIGHTS)) {
            $where['Servicetemplates.container_id IN'] = $MY_RIGHTS;
        }

        $query->contain([
            'Servicetemplates'
        ]);
        $query->where($where);
        $query->order($AgentchecksFilter->getOrderForPaginator('Agentchecks.name', 'asc'));

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
     * @param $id
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getAgentcheckById($id) {
        return $this->query()
            ->contain([
                'Servicetemplates'
            ])
            ->where([
                'Agentchecks.id' => $id
            ])
            ->first();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Agentchecks.id' => $id]);
    }

    /**
     * @param $name
     * @param $servicetemplateId
     * @return bool
     */
    public function existsByNameAndServicetemplateId($name, $servicetemplateId) {
        return $this->exists(['Agentchecks.name' => $name, 'Agentchecks.servicetemplate_id' => $servicetemplateId]);
    }


    /**
     * @return array
     */
    public function getAgentchecksForMapping() {
        $query = $this->find()
            ->contain([
                'Servicetemplates' => function (Query $q) {
                    $q->contain([
                        'Servicetemplatecommandargumentvalues' => [
                            'Commandarguments'
                        ]
                    ]);
                    return $q;
                }
            ])
            ->disableHydration()
            ->all();
        $agentchecksTmp = $query->toArray();
        $agentchecks = [];
        foreach($agentchecksTmp as $agentcheck){
            $agentchecks[$agentcheck['name']] = $agentcheck;
        }

        debug($agentchecks);

        return $agentchecks;
    }

    /**
     * @return array
     * @deprecated
     * @todo delete with oITC 4.3
     */
    public function getAgentchecksForMappingOld() {
        $query = $this->find()
            ->disableHydration()
            ->all();
        $agentchecks = $query->toArray();
        if ($agentchecks === null) {
            return [];
        }

        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        foreach ($agentchecks as $index => $agentcheck) {
            $servicetemplate = $ServicetemplatesTable->getServicetemplateForNewAgentService(
                $agentcheck['servicetemplate_id']
            );

            $service = $servicetemplate;

            $fieldsToRename = [
                'id'                                   => 'servicetemplate_id',
                'servicetemplatecommandargumentvalues' => 'servicecommandargumentvalues',
            ];
            foreach ($fieldsToRename as $srcField => $dscField) {
                $service[$dscField] = $servicetemplate[$srcField];
                unset($service[$srcField]);
                unset($service['uuid'], $service['template_name']);
            }

            $agentchecks[$index]['service'] = $service;
        }

        return $agentchecks;
    }

}
