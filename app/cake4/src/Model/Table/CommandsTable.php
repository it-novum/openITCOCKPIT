<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\CommandsFilter;

/**
 * Commands Model
 *
 * @property \App\Model\Table\CommandargumentsTable|\Cake\ORM\Association\HasMany $Commandarguments
 *
 * @method \App\Model\Entity\Command get($primaryKey, $options = [])
 * @method \App\Model\Entity\Command newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Command[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Command|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Command|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Command patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Command[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Command findOrCreate($search, callable $callback = null, $options = [])
 */
class CommandsTable extends Table {

    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;

    /**
     * @var array
     */
    private $commandTypes = [];

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('commands');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Commandarguments', [
            'foreignKey' => 'command_id'
        ]);

        $this->commandTypes = [
            CHECK_COMMAND        => __('Service check command'),
            HOSTCHECK_COMMAND    => __('Host check command'),
            NOTIFICATION_COMMAND => __('Notification command'),
            EVENTHANDLER_COMMAND => __('Eventhandler command'),
        ];
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name');

        $validator
            ->scalar('command_line')
            ->allowEmptyString('command_line');

        $validator
            ->integer('command_type')
            ->requirePresence('command_type', 'create')
            ->allowEmptyString('command_type', false);

        $validator
            ->scalar('human_args')
            ->allowEmptyString('human_args');

        $validator
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->allowEmptyString('uuid', false)
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('description')
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

        return $rules;
    }

    /**
     * @param CommandsFilter $CommandsFilter
     * @param null $PaginateOMat
     * @return array
     */
    public function getCommandsIndex(CommandsFilter $CommandsFilter, $PaginateOMat = null) {
        $query = $this->find('all')->disableHydration();
        $query->where($CommandsFilter->indexFilter());
        $query->order($CommandsFilter->getOrderForPaginator('Commands.name', 'asc'));

        $result = [];
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

        foreach ($result as $index => $row) {
            $result[$index]['Command']['type'] = $this->commandTypes[$row['Command']['command_type']];
        }
        return $result;
    }
}
