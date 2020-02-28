<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
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
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('commands');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Commandarguments', [
            'foreignKey'   => 'command_id',
            'saveStrategy' => 'replace'
        ])->setDependent(true);

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
    public function validationDefault(Validator $validator): Validator {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name', null, false)
            ->add('name', 'unique', [
                'rule'     => 'validateUnique',
                'provider' => 'table',
                'message'  => __('This command name has already been taken.')
            ]);

        $validator
            ->scalar('command_line')
            ->allowEmptyString('command_line', __('This field cannot be left blank.'), false);

        $validator
            ->integer('command_type')
            ->requirePresence('command_type', 'create')
            ->allowEmptyString('command_type', null, false);

        $validator
            ->scalar('human_args')
            ->allowEmptyString('human_args');

        $validator
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->allowEmptyString('uuid', null, false)
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
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->isUnique(['uuid']));

        return $rules;
    }

    /**
     * @param CommandsFilter $CommandsFilter
     * @param PaginateOMat|null $PaginateOMat
     * @return array
     */
    public function getCommandsIndex(CommandsFilter $CommandsFilter, $PaginateOMat = null) {
        $query = $this->find('all')->disableHydration();
        $query->where($CommandsFilter->indexFilter());
        $query->order($CommandsFilter->getOrderForPaginator('Commands.name', 'asc'));

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


    /**
     * @param $id
     * @return array
     */
    public function getCommandById($id) {
        if ($id === null) {
            return [];
        }
        $command = $this->find('all')
            ->contain('Commandarguments')
            ->where(['Commands.id' => $id])
            ->first();

        return $this->formatFirstResultAsCake2($command->toArray());
    }

    /**
     * @param int|array $ids
     * @return array
     */
    public function getCommandByIds($ids = []) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $command = $this->find('all')
            ->contain('Commandarguments')
            ->where(['Commands.id IN' => $ids])
            ->all();

        return $this->formatResultAsCake2($command->toArray());
    }

    /**
     * @param int|array $ids
     * @return array
     */
    public function getCommandByIdAsList($ids = []) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $command = $this->find()
            ->select([
                'Commands.id',
                'Commands.name'
            ])
            ->where(['Commands.id IN' => $ids])
            ->all();

        return $this->formatListAsCake2($command->toArray());
    }

    /**
     * @param int|array
     * @return array
     */
    public function getCommandByTypeAsList($commandTypeId) {
        if (!is_array($commandTypeId)) {
            $commandTypeId = [$commandTypeId];
        }

        $command = $this->find()
            ->select([
                'Commands.id',
                'Commands.name'
            ])
            ->where(['Commands.command_type IN' => $commandTypeId])
            ->all();

        return $this->formatListAsCake2($command->toArray());
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getCommandsForCopy($ids = []) {
        $query = $this->find()
            ->select([
                'Commands.id',
                'Commands.name',
                'Commands.command_line',
                'Commands.description'
            ])
            ->where(['Commands.id IN' => $ids])
            ->order(['Commands.id' => 'asc'])
            ->disableHydration()
            ->all();

        return $this->formatResultAsCake2($query->toArray(), false);
    }

    /**
     * @param int $typeId
     * @return array
     */
    public function getCommandsByTypeId($typeId) {
        $query = $this->find()
            ->select([
                'Commands.id',
                'Commands.name',
                'Commands.command_line',
                'Commands.uuid',
                'Commands.command_type',
                'Commands.description'
            ])
            ->where(['Commands.command_type' => $typeId])
            ->disableHydration()
            ->all();

        return $this->formatResultAsCake2($query->toArray(), false);
    }


    /**
     * @param string $uuid
     * @return int|null
     */
    public function getCommandIdByCommandUuid($uuid) {
        $command = $this->find('all')
            ->select(['Commands.id'])
            ->where(['Commands.uuid' => $uuid])
            ->first();

        if (is_null($command)) {
            return null;
        }

        return $command->id;
    }

    /**
     * @param int $id
     * @return string|null
     */
    public function getCommandUuidByCommandId($id) {
        $command = $this->find('all')
            ->select(['Commands.uuid'])
            ->where(['Commands.id' => $id])
            ->first();

        if (is_null($command)) {
            return null;
        }

        return $command->uuid;
    }

    /**
     * @param string $uuid
     * @param bool $contain
     * @return array
     */
    public function getCommandByUuid($uuid, $contain = false, $formatResultAsCake2 = true) {
        $command = $this->find('all')
            ->where(['Commands.uuid' => $uuid])
            ->disableHydration();

        if ($contain) {
            $command->contain('Commandarguments');
        }
        $command->first();

        if ($formatResultAsCake2) {
            return $this->formatFirstResultAsCake2($command->toArray());
        }
        return $command->toArray();
    }

    /**
     * @param bool $contain
     * @return array
     */
    public function getAllCommands($contain = false) {
        $command = $this->find('all')
            ->disableHydration()
            ->all();

        if ($contain) {
            $command->contain('Commandarguments');
        }

        return $this->formatResultAsCake2($command->toArray(), $contain);
    }

    /**
     * @return array
     */
    public function getAllCommandsUuidsAsList() {
        $query = $this->find('list', [
            'keyField'   => 'id',
            'valueField' => 'uuid'
        ])
            ->disableHydration();
        return $query->toArray();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Commands.id' => $id]);
    }

    /**
     * @param string $uuid
     * @return bool
     */
    public function existsByUuid(string $uuid) {
        return $this->exists(['Commands.uuid' => $uuid]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function allowDelete($id) {

        //Check if the command is used by contacts
        $tableNames = [
            'ContactsToHostcommands',
            'ContactsToServicecommands',
        ];

        foreach ($tableNames as $tableName) {
            $LinkingTable = TableRegistry::getTableLocator()->get($tableName);
            $count = $LinkingTable->find()
                ->where(['command_id' => $id])
                ->count();

            if ($count > 0) {
                return false;
            }
        }

        //Check if the command is used by host or service templates
        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        if ($HosttemplatesTable->isCommandUsedByHosttemplate($id)) {
            return false;
        }

        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
        if ($ServicetemplatesTable->isCommandUsedByServicetemplate($id)) {
            return false;
        }

        //Checking host and services
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        if ($HostsTable->isCommandUsedByHost($id)) {
            return false;
        }

        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        if ($ServicesTable->isCommandUsedByService($id)) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getCommandTypes() {
        return $this->commandTypes;
    }

    public function getSourceCommandForCopy($sourceCommandId){
        $sourceCommand = $this->get($sourceCommandId, [
            'contain' => [
                'Commandarguments'
            ]
        ])->toArray();

        //Remove all source ids so the new copied command will not use the original command arguments...
        $commandarguments = [];
        foreach($sourceCommand['commandarguments'] as $commandargument){
            unset($commandargument['id'], $commandargument['command_id']);
            $commandarguments[] = $commandargument;
        }
        $sourceCommand['commandarguments'] = $commandarguments;
        return $sourceCommand;
    }
}
