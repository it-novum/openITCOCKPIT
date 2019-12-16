<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\FileDebugger;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ContactsFilter;

/**
 * Contacts Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\BelongsTo $HostTimeperiods
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\HasMany $ContactsToContainers
 * @property \App\Model\Table\CommandsTable|\Cake\ORM\Association\HasMany $ContactsToHostcommands
 *
 * @method \App\Model\Entity\Contact get($primaryKey, $options = [])
 * @method \App\Model\Entity\Contact newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Contact[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Contact|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Contact|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Contact patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Contact[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Contact findOrCreate($search, callable $callback = null, $options = [])
 */
class ContactsTable extends Table {

    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;
    use CustomValidationTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) :void {
        parent::initialize($config);

        $this->setTable('contacts');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Containers', [
            'className'        => 'Containers',
            'foreignKey'       => 'contact_id',
            'targetForeignKey' => 'container_id',
            'joinTable'        => 'contacts_to_containers'
        ]);

        $this->belongsToMany('HostCommands', [
            'className'        => 'Commands',
            'joinTable'        => 'contacts_to_hostcommands',
            'foreignKey'       => 'contact_id',
            'targetForeignKey' => 'command_id'
        ]);

        $this->belongsToMany('ServiceCommands', [
            'className'        => 'Commands',
            'joinTable'        => 'contacts_to_servicecommands',
            'foreignKey'       => 'contact_id',
            'targetForeignKey' => 'command_id',
        ]);

        $this->hasMany('Customvariables', [
            'conditions'   => [
                'objecttype_id' => OBJECT_CONTACT
            ],
            'foreignKey'   => 'object_id',
            'saveStrategy' => 'replace'
        ])->setDependent(true);


        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);

        $this->belongsTo('HostTimeperiods', [
            'className'  => 'Timeperiods',
            'foreignKey' => 'host_timeperiod_id',
            'joinType'   => 'INNER'
        ]);

        $this->belongsTo('ServiceTimeperiods', [
            'className'  => 'Timeperiods',
            'foreignKey' => 'service_timeperiod_id',
            'joinType'   => 'INNER'
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
            ->scalar('name')
            ->maxLength('name', 64)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', null, false);

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->allowEmptyString('description', null, true);

        $validator
            ->allowEmptyString('email', __('You must at least specify either the email address or the phone number.'), function ($context) {
                return !empty($context['data']['email']) || !empty($context['data']['phone']);
            })
            ->email('email', false, __('Invalid email address'));

        $validator
            ->maxLength('phone', 64)
            ->allowEmptyString('phone', __('You must at least specify either the email address or the phone number.'), function ($context) {
                return !empty($context['data']['email']) || !empty($context['data']['phone']);
            })
            ->regex('phone', '/[\d\s-\+]+/');

        $validator
            ->integer('host_timeperiod_id')
            ->allowEmptyString('host_timeperiod_id', null, false)
            ->requirePresence('host_timeperiod_id')
            ->greaterThan('host_timeperiod_id', 0);

        $validator
            ->integer('service_timeperiod_id')
            ->allowEmptyString('service_timeperiod_id', null, false)
            ->requirePresence('service_timeperiod_id')
            ->greaterThan('service_timeperiod_id', 0);

        $validator
            ->allowEmptyString('host_commands', null, false)
            ->multipleOptions('host_commands', [
                'min' => 1
            ], __('You have to choose at least one command.'));

        $validator
            ->allowEmptyString('service_commands', null, false)
            ->multipleOptions('service_commands', [
                'min' => 1
            ], __('You have to choose at least one command.'));

        $validator
            ->requirePresence('notify_host_recovery', true, __('You have to choose at least one option.'))
            ->add('notify_host_recovery', 'custom', [
                'rule'    => [$this, 'checkHostNotificationOptions'],
                'message' => 'You have to choose at least one option.',
            ]);

        $validator
            ->requirePresence('notify_service_recovery', true, __('You have to choose at least one option.'))
            ->add('notify_service_recovery', 'custom', [
                'rule'    => [$this, 'checkHostNotificationOptions'],
                'message' => 'You have to choose at least one option.',
            ]);

        $validator
            ->requirePresence('containers', true, __('You have to choose at least one option.'))
            ->allowEmptyString('containers', null, false)
            ->multipleOptions('containers', [
                'min' => 1
            ], __('You have to choose at least one option.'));

        $validator
            ->allowEmptyString('customvariables', null, true)
            ->add('customvariables', 'custom', [
                'rule'    => [$this, 'checkMacroNames'], //\App\Lib\Traits\CustomValidationTrait
                'message' => _('Macro name needs to be unique')
            ]);

        $booleanFields = [
            'host_notifications_enabled',
            'service_notifications_enabled',
            'notify_service_warning',
            'notify_service_unknown',
            'notify_service_critical',
            'notify_service_flapping',
            'notify_service_downtime',
            'notify_host_down',
            'notify_host_unreachable',
            'notify_host_flapping',
            'notify_host_downtime',
            'host_push_notifications_enabled',
            'service_push_notifications_enabled'
        ];

        foreach ($booleanFields as $booleanField) {
            $validator
                ->integer($booleanField)
                ->lessThanOrEqual($booleanField, 1)
                ->greaterThanOrEqual($booleanField, 0);
        }

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * @param mixed $value
     * @param array $context
     * @return bool
     *
     * Custom validation rule for email and/or phone fields.
     */
    public function atLeastOne($value, $context) {
        return !empty($context['data']['email']) || !empty($context['data']['phone']);
    }

    /**
     * @param mixed $value
     * @param array $context
     * @return bool
     */
    public function checkHostNotificationOptions($value, $context) {
        $notificationOptions = [
            'notify_host_recovery',
            'notify_host_down',
            'notify_host_unreachable',
            'notify_host_flapping',
            'notify_host_downtime'
        ];

        foreach ($notificationOptions as $notificationOption) {
            if (isset($context['data'][$notificationOption]) && $context['data'][$notificationOption] == 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $value
     * @param array $context
     * @return bool
     */
    public function checkServiceNotificationOptions($value, $context) {
        $notificationOptions = [
            'notify_service_recovery',
            'notify_service_warning',
            'notify_service_unknown',
            'notify_service_critical',
            'notify_service_flapping',
            'notify_service_downtime'
        ];

        foreach ($notificationOptions as $notificationOption) {
            if (isset($context['data'][$notificationOption]) && $context['data'][$notificationOption] == 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function allowDelete($id) {
        $tableNames = [
            'ContactsToContactgroups',
            'ContactsToHosttemplates',
            'ContactsToHosts',
            'ContactsToServicetemplates',
            'ContactsToServices',
            'ContactsToHostescalations',
            'ContactsToServiceescalations',
        ];

        foreach ($tableNames as $tableName) {
            $LinkingTable = TableRegistry::getTableLocator()->get($tableName);
            $count = $LinkingTable->find()
                ->where(['contact_id' => $id])
                ->count();

            if ($count > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param ContactsFilter $ContactsFilter
     * @param null|PaginateOMat $PaginateOMat
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getContactsIndex(ContactsFilter $ContactsFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        $query = $this->find('all');
        $query->contain(['Containers']);
        $query->where($ContactsFilter->indexFilter());

        $query->innerJoinWith('Containers', function (Query $q) use ($MY_RIGHTS) {
            if (!empty($MY_RIGHTS)) {
                return $q->where(['Containers.id IN' => $MY_RIGHTS]);
            }
            return $q;
        });

        $query->distinct('Contacts.id');

        $query->disableHydration();
        $query->order($ContactsFilter->getOrderForPaginator('Contacts.name', 'asc'));


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
    public function getContactById($id, $contain = ['Containers']) {
        $query = $this->find()
            ->where([
                'Contacts.id' => $id
            ])
            ->contain($contain)
            ->disableHydration()
            ->first();

        return $this->formatFirstResultAsCake2($query, true);
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getContactsForCopy($ids = []) {
        $query = $this->find()
            ->select([
                'Contacts.id',
                'Contacts.name',
                'Contacts.description',
                'Contacts.email',
                'Contacts.phone'
            ])
            ->where(['Contacts.id IN' => $ids])
            ->order(['Contacts.id' => 'asc'])
            ->disableHydration()
            ->all();

        return $this->formatResultAsCake2($query->toArray(), false);
    }


    /**
     * @param int $id
     * @return array
     */
    public function getContactForEdit($id) {
        $query = $this->find()
            ->where([
                'Contacts.id' => $id
            ])
            ->contain([
                'Containers',
                'HostCommands',
                'ServiceCommands',
                'Customvariables'
            ])
            ->disableHydration()
            ->first();


        $contact = $query;
        $contact['containers'] = [
            '_ids' => Hash::extract($query, 'containers.{n}.id')
        ];
        $contact['host_commands'] = [
            '_ids' => Hash::extract($query, 'host_commands.{n}.id')
        ];
        $contact['service_commands'] = [
            '_ids' => Hash::extract($query, 'service_commands.{n}.id')
        ];

        return [
            'Contact' => $contact
        ];
    }

    /**
     * @return array
     */
    public function getContactsForCrateDBSync() {
        $query = $this->find()
            ->select([
                'Contacts.id',
                'Contacts.name',
                'Contacts.uuid'
            ])
            ->disableHydration()
            ->all();

        return $this->formatResultAsCake2($query->toArray(), false);
    }


    /**
     * @param null $uuid
     * @return array|\Cake\ORM\Query
     */
    public function getContactsForExport($uuid = null) {
        $query = $this->find()
            ->contain([
                'Containers',
                'HostCommands',
                'ServiceCommands',
                'HostTimeperiods',
                'ServiceTimeperiods',
                'Customvariables'
            ]);
        if (!empty($uuid)) {
            if (!is_array($uuid)) {
                $uuid = [$uuid];
            }
            $query->where([
                'Contacts.uuid IN' => $uuid
            ]);
        }
        $query->all();
        return $query;
    }

    /**
     * @param string $name
     * @param array $contain
     * @return array
     */
    public function getContactByName($name, $contain = ['Containers']) {
        $query = $this->find()
            ->where([
                'Contacts.name' => $name
            ])
            ->contain($contain)
            ->disableHydration()
            ->first();

        return $this->formatFirstResultAsCake2($query, true);
    }

    /**
     * @param int $timeperiodId
     * @return bool
     */
    public function isTimeperiodUsedByContacts($timeperiodId) {
        $count = $this->find()
            ->where([
                'OR' => [
                    'Contacts.host_timeperiod_id'    => $timeperiodId,
                    'Contacts.service_timeperiod_id' => $timeperiodId
                ]
            ])->count();

        if ($count > 0) {
            return true;
        }

        return false;
    }

    public function contactsByContainerId($container_ids = [], $type = 'all') {
        if (!is_array($container_ids)) {
            $container_ids = [$container_ids];
        }

        $container_ids = array_unique($container_ids);

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $tenantContainerIds = [];
        foreach ($container_ids as $container_id) {
            if ($container_id != ROOT_CONTAINER) {
                $path = $ContainersTable->getPathByIdAndCacheResult($container_id, 'ContactContactsByContainerId');
                // Get container id of the tenant container
                // Tenant contacts are available for all users of a tenant (oITC V2 legacy)
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

        $query->distinct('Contacts.id');
        $query->disableHydration();

        if ($type === 'all') {
            return $this->formatResultAsCake2($query->toArray());
        }

        return $this->formatListAsCake2($query->toArray());
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getContactsAsList($ids = []) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $query = $this->find()
            ->select([
                'Contacts.id',
                'Contacts.name'
            ])
            ->disableHydration();
        if (!empty($ids)) {
            $query->where([
                'Contacts.id IN' => $ids
            ]);
        }

        return $this->formatListAsCake2($query->toArray());
    }

    /**
     * @param $userId
     * @return bool
     */
    public function hasUserAPushContact($userId) {
        $query = $this->find()
            ->select(['Contacts.id'])
            ->where([
                [
                    'AND' => [
                        'Contacts.user_id' => $userId,
                        'OR'               => [
                            'Contacts.host_push_notifications_enabled'    => 1,
                            'Contacts.service_push_notifications_enabled' => 1
                        ]
                    ]
                ]
            ])
            ->disableHydration()
            ->first();


        return $query !== null;
    }

    public function getAllInfoContacts() {
        $query = $this->find()
            ->select([
                'Contacts.id',
                'Contacts.name',
                'Contacts.uuid'
            ])
            ->where([
                'Contacts.name' => 'info'
            ])
            ->disableHydration()
            ->all();

        if ($query === null) {
            return [];
        }

        return $query->toArray();
    }


    /**
     * @param array $dataToParse
     * @return array
     */
    public function resolveDataForChangelog($dataToParse = []) {
        $extDataForChangelog = [
            'HostTimeperiod'    => [],
            'ServiceTimeperiod' => [],
            'HostCommands'      => [],
            'ServiceCommands'   => []
        ];

        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');

        foreach ($dataToParse['Contact']['host_commands']['_ids'] as $id) {
            $command = $CommandsTable->getCommandById($id);
            if (!empty($command)) {
                $extDataForChangelog['HostCommands'][] = [
                    'id'   => $command['Command']['id'],
                    'name' => $command['Command']['name']
                ];
            }
        }

        foreach ($dataToParse['Contact']['service_commands']['_ids'] as $id) {
            $command = $CommandsTable->getCommandById($id);
            if (!empty($command)) {
                $extDataForChangelog['ServiceCommands'][] = [
                    'id'   => $command['Command']['id'],
                    'name' => $command['Command']['name']
                ];
            }
        }

        $timeperiod = $TimeperiodsTable->getTimeperiodById($dataToParse['Contact']['host_timeperiod_id']);
        if (!empty($timeperiod)) {
            $extDataForChangelog['HostTimeperiod'] = [
                'id'   => $timeperiod['Timeperiod']['id'],
                'name' => $timeperiod['Timeperiod']['name']
            ];
        }

        $timeperiod = $TimeperiodsTable->getTimeperiodById($dataToParse['Contact']['service_timeperiod_id']);
        if (!empty($timeperiod)) {
            $extDataForChangelog['ServiceTimeperiod'] = [
                'id'   => $timeperiod['Timeperiod']['id'],
                'name' => $timeperiod['Timeperiod']['name']
            ];
        }

        return $extDataForChangelog;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Contacts.id' => $id]);
    }

    /**
     * @param array $contactIds
     * @param array $containerId
     * @return array
     */
    public function removeContactsWhichAreNotInContainer($contactIds, $containerId) {
        if (!is_array($contactIds)) {
            $contactIds = [$contactIds];
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);

        $tenantContainerIds = [];
        foreach ($containerIds as $containerId) {
            if ($containerId != ROOT_CONTAINER) {
                $path = $ContainersTable->getPathByIdAndCacheResult($containerId, 'ContactRemoveContactsWhichAreNotInContainer');
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
                'Contacts.id'
            ])
            ->contain([
                'Containers' => function (Query $q) {
                    return $q->disableAutoFields()
                        ->select([
                            'Containers.id'
                        ]);
                }
            ])
            ->where([
                'Contacts.id IN ' => $contactIds
            ])
            ->disableHydration()
            ->all();

        if ($query === null) {
            return [];
        }

        $contactIds = [];
        foreach ($query->toArray() as $record) {
            $containersFromContact = Hash::extract($record['containers'], '{n}.id');
            if (!empty(array_intersect($containerIds, $containersFromContact))) {
                $contactIds[] = $record['id'];
            }
        }
        return $contactIds;
    }

    /**
     * @param int $commandId
     * @param array $MY_RIGHTS
     * @param bool $enableHydration
     * @return array
     */
    public function getContactsByCommandId($commandId, $MY_RIGHTS = [], $enableHydration = true) {

        /** @var ContactsToHostcommandsTable $ContactsToHostcommandsTable */
        $ContactsToHostcommandsTable = TableRegistry::getTableLocator()->get('ContactsToHostcommands');
        /** @var ContactsToServicecommandsTable $ContactsToServicecommandsTable */
        $ContactsToServicecommandsTable = TableRegistry::getTableLocator()->get('ContactsToServicecommands');

        $query = $ContactsToHostcommandsTable->find()
            ->select([
                'contact_id'
            ])
            ->where([
                'command_id' => $commandId
            ])
            ->group([
                'contact_id'
            ])
            ->disableHydration()
            ->all();

        $result = $query->toArray();
        if(empty($result)){
            $result = [];
        }

        $contactIds = Hash::extract($result, '{n}.contact_id');

        $query = $ContactsToServicecommandsTable->find()
            ->select([
                'contact_id'
            ])
            ->where([
                'command_id' => $commandId
            ])
            ->group([
                'contact_id'
            ])
            ->disableHydration()
            ->all();

        $result = $query->toArray();
        if(empty($result)){
            $result = [];
        }

        $contactIds = array_unique(array_merge($contactIds, Hash::extract($result, '{n}.contact_id')));

        if(empty($contactIds)){
            return [];
        }

        $query = $this->find('all');
        $query->contain([
            'Containers'
        ]);

        $query->innerJoinWith('Containers', function (Query $q) use ($MY_RIGHTS) {
            if (!empty($MY_RIGHTS)) {
                return $q->where(['Containers.id IN' => $MY_RIGHTS]);
            }
            return $q;
        });

        $query->where([
            'Contacts.id IN' => $contactIds
        ]);
        $query->distinct('Contacts.id');
        $query->enableHydration($enableHydration);
        $query->order(['Contacts.name' => 'asc']);

        $result = $query->all();


        return $this->emptyArrayIfNull($result->toArray());
    }

}
