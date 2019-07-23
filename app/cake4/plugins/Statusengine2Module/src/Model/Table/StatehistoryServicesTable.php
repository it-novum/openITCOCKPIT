<?php

namespace Statusengine2Module\Model\Table;

use App\Lib\Interfaces\StatehistoryServiceTableInterface;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\StatehistoryServiceConditions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use Statusengine2Module\Model\Entity\StatehistoryService;

/**
 * StatehistoryService Model
 *
 * @property \Statusengine2Module\Model\Table\StatehistoryServicesTable|\Cake\ORM\Association\BelongsTo $Statehistories
 * @property \Statusengine2Module\Model\Table\ObjectsTable|\Cake\ORM\Association\BelongsTo $Objects
 *
 * @method \Statusengine2Module\Model\Entity\StatehistoryService get($primaryKey, $options = [])
 * @method \Statusengine2Module\Model\Entity\StatehistoryService newEntity($data = null, array $options = [])
 * @method \Statusengine2Module\Model\Entity\StatehistoryService[] newEntities(array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\StatehistoryService|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine2Module\Model\Entity\StatehistoryService saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine2Module\Model\Entity\StatehistoryService patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\StatehistoryService[] patchEntities($entities, array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\StatehistoryService findOrCreate($search, callable $callback = null, $options = [])
 */
class StatehistoryServicesTable extends Table implements StatehistoryServiceTableInterface {

    use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('nagios_statehistory');
        $this->setDisplayField('statehistory_id');
        $this->setPrimaryKey(['statehistory_id', 'state_time']);

        $this->belongsTo('Objects', [
            'foreignKey' => 'object_id',
            'joinType'   => 'INNER',
            'className'  => 'Statusengine2Module.Objects',
            'conditions' => [
                'Objects.objecttype_id' => 2
            ]
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        //Readonly table
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
        //Readonly table
        return $rules;
    }

    /**
     * @param StatehistoryServiceConditions $StatehistoryServiceConditions
     * @param PaginateOMat|null $PaginateOMat
     * @return array
     */
    public function getStatehistoryIndex(StatehistoryServiceConditions $StatehistoryServiceConditions, $PaginateOMat = null) {
        $query = $this->find()->first();
        $query = $this->find()
            ->contain([
                'Objects'
            ])
            ->where([
                'Objects.name2'                  => $StatehistoryServiceConditions->getServiceUuid(),
                'StatehistoryServices.state_time >' => date('Y-m-d H:i:s', $StatehistoryServiceConditions->getFrom()),
                'StatehistoryServices.state_time <' => date('Y-m-d H:i:s', $StatehistoryServiceConditions->getTo())
            ])
            ->order($StatehistoryServiceConditions->getOrder());

        if ($StatehistoryServiceConditions->hasConditions()) {
            $query->andWhere($StatehistoryServiceConditions->getConditions());
        }

        if (!empty($StatehistoryServiceConditions->getStates())) {
            $query->andWhere([
                'StatehistoryServices.state IN' => $StatehistoryServiceConditions->getStates()
            ]);
        }


        if (!empty($StatehistoryServiceConditions->getStateTypes())) {
            $query->andWhere([
                'StatehistoryServices.state_type IN' => $StatehistoryServiceConditions->getStateTypes()
            ]);
        }

        if ($StatehistoryServiceConditions->hardStateTypeAndUpState()) {
            $query->andWhere([
                'OR' => [
                    'StatehistoryServices.state_type' => 1,
                    'StatehistoryServices.state'      => 0
                ]
            ]);
        }


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
     * @param StatehistoryServiceConditions $StatehistoryServiceConditions
     * @param bool $enableHydration
     * @return array
     */


    /**
     * @param StatehistoryServiceConditions $StatehistoryServiceConditions
     * @param bool $enableHydration
     * @return array|StatehistoryService|null
     */
    public function getLastRecord(StatehistoryServiceConditions $StatehistoryServiceConditions, $enableHydration = true) {
        $query = $this->find()
            ->contain([
                'Objects'
            ])
            ->where([
                'Objects.name2' => $StatehistoryServiceConditions->getServiceUuid(),
                'StatehistoryServices.state_time <=' => date('Y-m-d H:i:s', $StatehistoryServiceConditions->getFrom())
            ])
            ->order([
                'StatehistoryServices.state_time' => 'DESC'
            ])
            ->enableHydration($enableHydration)
            ->first();

        return $query;
    }
}
