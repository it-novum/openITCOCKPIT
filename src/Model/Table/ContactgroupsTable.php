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
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ContactgroupsFilter;

/**
 * Contactgroups Model
 *
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $Containers
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsToMany $Contacts
 *
 * @method \App\Model\Entity\Contactgroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Contactgroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Contactgroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Contactgroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Contactgroup|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Contactgroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Contactgroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Contactgroup findOrCreate($search, callable $callback = null, $options = [])
 */
class ContactgroupsTable extends Table {

    use PaginationAndScrollIndexTrait;
    use Cake2ResultTableTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) :void {
        parent::initialize($config);

        $this->setTable('contactgroups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER'
        ])->setDependent(true);

        $this->belongsToMany('Contacts', [
            'className'        => 'Contacts',
            'foreignKey'       => 'contactgroup_id',
            'targetForeignKey' => 'contact_id',
            'joinTable'        => 'contacts_to_contactgroups',
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
            ->requirePresence('contacts', true, __('You have to choose at least one contact.'))
            ->allowEmptyString('contacts', null, false)
            ->multipleOptions('contacts', [
                'min' => 1
            ], __('You have to choose at least one contact.'));

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
     * @param ContactgroupsFilter $ContactgroupsFilter
     * @param null|PaginateOMat $PaginateOMat
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getContactgroupsIndex(ContactgroupsFilter $ContactgroupsFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        $query = $this->find('all');
        $query->contain(['Containers']);
        $query->where($ContactgroupsFilter->indexFilter());

        $query->innerJoinWith('Containers', function (Query $q) use ($MY_RIGHTS) {
            if (!empty($MY_RIGHTS)) {
                return $q->where(['Containers.parent_id IN' => $MY_RIGHTS]);
            }
            return $q;
        });

        $query->disableHydration();
        $query->order($ContactgroupsFilter->getOrderForPaginator('Containers.name', 'asc'));


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
     * @param int $id
     * @param array $contain
     * @return array
     */
    public function getContactgroupById($id, $contain = ['Containers', 'Contacts']) {
        $query = $this->find()
            ->where([
                'Contactgroups.id' => $id
            ])
            ->contain($contain)
            ->disableHydration()
            ->first();

        $result = $this->formatFirstResultAsCake2($query, true);
        unset($result['Container'], $result['Contactstocontactgroup']);
        return $result;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getContactgroupForEdit($id) {
        $query = $this->find()
            ->where([
                'Contactgroups.id' => $id
            ])
            ->contain([
                'Containers',
                'Contacts',
            ])
            ->disableHydration()
            ->first();


        $contact = $query;
        $contact['contacts'] = [
            '_ids' => Hash::extract($query, 'contacts.{n}.id')
        ];

        return [
            'Contactgroup' => $contact
        ];
    }

    /**
     * @param array $containerIds
     * @param string $type
     * @param string $index
     * @return array
     * @deprecated Use self::getContactgroupsByContainerId()
     */
    public function contactgroupsByContainerId($containerIds = [], $type = 'all', $index = 'id') {
        return $this->getContactgroupsByContainerId($containerIds, $type, $index);
    }

    /**
     * @param array $containerIds
     * @param string $type
     * @param string $index
     * @return array
     */
    public function getContactgroupsByContainerId($containerIds = [], $type = 'all', $index = 'id') {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        //Lookup for the tenant container of $container_id
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');


        $tenantContainerIds = [];

        foreach ($containerIds as $containerId) {
            if ($containerId != ROOT_CONTAINER) {

                // Get container id of the tenant container
                // $container_id is may be a location, devicegroup or whatever, so we need to container id of the tenant container to load contactgroups and contacts
                $path = $ContainersTable->getPathByIdAndCacheResult($containerId, 'ContactGroupContactsByContainerId');

                // Tenant contact groups are available for all users of a tenant (oITC V2 legacy)
                if (isset($path[1])) {
                    $tenantContainerIds[] = $path[1]['id'];
                }
            } else {
                $tenantContainerIds[] = ROOT_CONTAINER;
            }
        }
        $tenantContainerIds = array_unique($tenantContainerIds);
        $containerIds = array_unique(array_merge($tenantContainerIds, $containerIds));


        if (empty($containerIds)) {
            return [];
        }

        $query = $this->find()
            ->contain(['Containers'])
            ->where([
                'Containers.parent_id IN'     => $containerIds,
                'Containers.containertype_id' => CT_CONTACTGROUP
            ])
            ->disableHydration()
            ->all();

        $records = $query->toArray();
        if (empty($records) || is_null($records)) {
            return [];
        }

        if ($type === 'all') {
            return $records;
        }

        $list = [];
        foreach ($records as $record) {
            $list[$record[$index]] = $record['container']['name'];
        }
        return $list;
    }

    /**
     * @param array $dataToParse
     * @return array
     */
    public function resolveDataForChangelog($dataToParse = []) {
        $extDataForChangelog = [
            'Contact' => []
        ];

        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');

        foreach ($ContactsTable->getContactsAsList($dataToParse['Contactgroup']['contacts']['_ids']) as $contactId => $contactName) {
            $extDataForChangelog['Contact'][] = [
                'id'   => $contactId,
                'name' => $contactName
            ];
        }

        return $extDataForChangelog;
    }

    /**
     * @return array
     */
    public function getAllContactsAsList() {
        $query = $this->find()
            ->select([
                'Containers.id',
                'Containers.name',
                'Contactgroups.id'
            ])
            ->contain(['Containers'])
            ->where([
                'Containers.containertype_id' => CT_CONTACTGROUP
            ])
            ->disableHydration()
            ->all();

        $records = $query->toArray();
        if (empty($records)) {
            return [];
        }
        $list = [];
        foreach ($records as $record) {
            $list[$record['id']] = $record['container']['name'];
        }
        return $list;
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getContactgroupsAsList($ids = []) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $query = $this->find()
            ->select([
                'Containers.id',
                'Containers.name',
                'Contactgroups.id'
            ])
            ->contain(['Containers'])
            ->where([
                'Contactgroups.id IN'         => $ids,
                'Containers.containertype_id' => CT_CONTACTGROUP
            ])
            ->disableHydration()
            ->all();

        $records = $query->toArray();
        if (empty($records)) {
            return [];
        }
        $list = [];
        foreach ($records as $record) {
            $list[$record['id']] = $record['container']['name'];
        }
        return $list;
    }

    /**
     * @param null $uuid
     * @return array|\Cake\ORM\Query
     */
    public function getContactgroupsForExport($uuid = null) {
        $query = $this->find()
            ->contain([
                'Containers',
                'Contacts'
            ]);
        if (!empty($uuid)) {
            if (!is_array($uuid)) {
                $uuid = [$uuid];
            }
            $query->where([
                'Contactgroups.uuid IN' => $uuid
            ]);
        }
        $query->all();
        return $query;
    }

    /**
     * @param array $containerIds
     * @return array
     */
    public function getContactgroupsByContainerIdsForContainerDelete($containerIds) {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $query = $this->find()
            ->select([
                'Containers.id',
                'Containers.name',
                'Contactgroups.id'
            ])
            ->contain(['Containers'])
            ->where([
                'Contactgroups.container_id IN' => $containerIds,
            ])
            ->disableHydration()
            ->all();

        $result = $query->toArray();
        if (empty($result)) {
            return [];
        }

        return $result;
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getContactgroupsForCopy($ids = []) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $query = $this->find()
            ->select([
                'Containers.id',
                'Containers.name',
                'Contactgroups.id',
                'Contactgroups.description',
            ])
            ->contain(['Containers'])
            ->where([
                'Contactgroups.id IN' => $ids,
            ])
            ->disableHydration()
            ->all();

        return $this->formatResultAsCake2($query->toArray(), false);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function allowDelete($id) {
        $tableNames = [
            'ContactgroupsToHosttemplates',
            'ContactgroupsToHosts',
            'ContactgroupsToServicetemplates',
            'ContactgroupsToServices',
            'ContactgroupsToHostescalations',
            'ContactgroupsToServiceescalations',
        ];

        foreach ($tableNames as $tableName) {
            $LinkingTable = TableRegistry::getTableLocator()->get($tableName);
            $count = $LinkingTable->find()
                ->where(['contactgroup_id' => $id])
                ->count();

            if ($count > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Contactgroups.id' => $id]);
    }

    /**
     * @param array $contactgroupsId
     * @param array $containerId
     * @return array
     */
    public function removeContactgroupsWhichAreNotInContainer($contactgroupsId, $containerId) {
        if (!is_array($contactgroupsId)) {
            $contactgroupsId = [$contactgroupsId];
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);

        $tenantContainerIds = [];
        foreach ($containerIds as $containerId) {
            if ($containerId != ROOT_CONTAINER) {
                $path = $ContainersTable->getPathByIdAndCacheResult($containerId, 'ContactgroupsRemoveContactgroupsWhichAreNotInContainer');
                // Get container id of the tenant container
                // Tenant contacts are available for all users of a tenant (oITC V2 legacy)
                if (isset($path[1])) {
                    $tenantContainerIds[] = $path[1]['id'];
                }
            } else {
                $tenantContainerIds[] = ROOT_CONTAINER;
            }
        }
        $containerIds = array_unique(array_merge($containerIds, $tenantContainerIds));

        $query = $this->find()
            ->select([
                'Contactgroups.id',
            ])
            ->contain([
                'Containers' => function (Query $q) {
                    return $q->disableAutoFields()
                        ->select([
                            'Containers.parent_id'
                        ]);
                }
            ])
            ->where([
                'Contactgroups.id IN ' => $contactgroupsId
            ])
            ->disableHydration()
            ->all();

        if ($query === null) {
            return [];
        }

        $contactgroupIds = [];
        foreach ($query->toArray() as $record) {
            if (in_array($record['container']['parent_id'], $containerIds, true)) {
                $contactgroupIds[] = $record['id'];
            }
        }
        return $contactgroupIds;
    }

    /**
     * @param int $contactId
     * @param array $MY_RIGHTS
     * @param bool $enableHydration
     * @return array
     */
    public function getContactgroupsByContactId($contactId, $MY_RIGHTS = [], $enableHydration = true) {

        /** @var ContactsToContactgroupsTable $ContactsToContactgroupsTable */
        $ContactsToContactgroupsTable = TableRegistry::getTableLocator()->get('ContactsToContactgroups');

        $query = $ContactsToContactgroupsTable->find()
            ->select([
                'contactgroup_id'
            ])
            ->where([
                'contact_id' => $contactId
            ])
            ->group([
                'contactgroup_id'
            ])
            ->disableHydration()
            ->all();

        $result = $query->toArray();
        if (empty($result)) {
            return [];
        }

        $contactgroupIds = Hash::extract($result, '{n}.contactgroup_id');

        $query = $this->find('all');
        $query->contain(['Containers']);
        $query->where([
            'Contactgroups.id IN' => $contactgroupIds
        ]);

        $query->innerJoinWith('Containers', function (Query $q) use ($MY_RIGHTS) {
            if (!empty($MY_RIGHTS)) {
                return $q->where(['Containers.parent_id IN' => $MY_RIGHTS]);
            }
            return $q;
        });

        $query->enableHydration($enableHydration);
        $query->order([
            'Containers.name' => 'asc'
        ]);

        $result = $query->all();

        return $this->emptyArrayIfNull($result->toArray());
    }
}
