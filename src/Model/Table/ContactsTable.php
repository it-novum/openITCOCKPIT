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
    public function initialize(array $config): void {
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
    public function validationDefault(Validator $validator): Validator {
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
            ->regex('phone', '/[\d\s\-\+]+/');

        $validator
            ->integer('host_timeperiod_id')
            ->allowEmptyString('host_timeperiod_id', null, false)
            ->requirePresence('host_timeperiod_id', 'create')
            ->greaterThan('host_timeperiod_id', 0);

        $validator
            ->integer('service_timeperiod_id')
            ->allowEmptyString('service_timeperiod_id', null, false)
            ->requirePresence('service_timeperiod_id', 'create')
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
            ->requirePresence('notify_host_recovery', 'create', __('You have to choose at least one option.'))
            ->add('notify_host_recovery', 'custom', [
                'rule'    => [$this, 'checkHostNotificationOptions'],
                'message' => 'You have to choose at least one option.',
            ]);

        $validator
            ->requirePresence('notify_service_recovery', 'create', __('You have to choose at least one option.'))
            ->add('notify_service_recovery', 'custom', [
                'rule'    => [$this, 'checkHostNotificationOptions'],
                'message' => 'You have to choose at least one option.',
            ]);

        $validator
            ->requirePresence('containers', 'create', __('You have to choose at least one option.'))
            ->allowEmptyString('containers', null, false)
            ->multipleOptions('containers', [
                'min' => 1
            ], __('You have to choose at least one option.'));

        $validator
            ->allowEmptyString('customvariables', null, true)
            ->add('customvariables', 'custom', [
                'rule'    => [$this, 'checkMacroNames'], //\App\Lib\Traits\CustomValidationTrait
                'message' => __('Macro name needs to be unique')
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
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->isUnique(['uuid']));
        //$rules->add($rules->existsIn(['user_id'], 'Users'));
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
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getContactsForCopy($ids = [], array $MY_RIGHTS = []) {
        $query = $this->find()
            ->select([
                'Contacts.id',
                'Contacts.name',
                'Contacts.description',
                'Contacts.email',
                'Contacts.phone'
            ])
            ->contain('Containers')
            ->where(['Contacts.id IN' => $ids])
            ->order(['Contacts.id' => 'asc']);

        if (!empty($MY_RIGHTS)) {
            $query->innerJoinWith('Containers', function (Query $q) use ($MY_RIGHTS) {
                if (!empty($MY_RIGHTS)) {
                    return $q->where(['Containers.id IN' => $MY_RIGHTS]);
                }
                return $q;
            });
        }

        $query
            ->distinct('Contacts.id')
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
        if (empty($result)) {
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
        if (empty($result)) {
            $result = [];
        }

        $contactIds = array_unique(array_merge($contactIds, Hash::extract($result, '{n}.contact_id')));

        if (empty($contactIds)) {
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

    /**
     * @param $timeperiodId
     * @param array $MY_RIGHTS
     * @param bool $enableHydration
     * @return array
     */
    public function getContactsByTimeperiodId($timeperiodId, $MY_RIGHTS = [], $enableHydration = true) {
        $query = $this->find()
            ->select([
                'Contacts.id',
                'Contacts.name'
            ])
            ->where([
                'OR' => [
                    'Contacts.host_timeperiod_id'    => $timeperiodId,
                    'Contacts.service_timeperiod_id' => $timeperiodId
                ]
            ])
            ->group([
                'Contacts.id'
            ]);

        $query->contain([
            'Containers'
        ]);

        $query->innerJoinWith('Containers', function (Query $q) use ($MY_RIGHTS) {
            if (!empty($MY_RIGHTS)) {
                return $q->where(['Containers.id IN' => $MY_RIGHTS]);
            }
            return $q;
        });


        $query->enableHydration($enableHydration);
        $query->order([
            'Contacts.name' => 'asc',
            'Contacts.id'
        ]);

        $result = $query->all();

        return $this->emptyArrayIfNull($result->toArray());
    }

    /**
     * @param int $containerId
     * @param string $type
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getContactsByContainerIdExact($containerId, $type = 'all', $index = 'id', $MY_RIGHTS = []) {
        $query = $this->find()
            ->select([
                'Contacts.id',
                'Contacts.name'
            ])
            ->innerJoinWith('Containers', function (Query $q) use ($containerId, $MY_RIGHTS) {
                $q->disableAutoFields()
                    ->select([
                        'Containers.id'
                    ])
                    ->where([
                        'Containers.id' => $containerId
                    ]);
                if (!empty($MY_RIGHTS)) {
                    $q->andWhere([
                        'Containers.id IN' => $MY_RIGHTS
                    ]);
                }
                return $q;
            })
            ->disableHydration();

        $result = $query->toArray();

        if (empty($result)) {
            return [];
        }

        if ($type === 'all') {
            return $result;
        }

        $list = [];
        foreach ($result as $row) {
            $list[$row[$index]] = $row['name'];
        }

        return $list;
    }

    // Containers check for contact

    /**
     * ContactsToContactgroups
     * @param int $contactId
     * @param array $containerIds
     * @return array
     */
    public function getContactgroupContainerIdsForContact(int $contactId, array $containerIds) {
        if (empty($containerIds)) {
            return [];
        }

        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $query = $this->find()
            ->select([
                'Containers.parent_id'
            ])
            ->innerJoin(
                ['ContactsToContactgroups' => 'contacts_to_contactgroups'],
                ['ContactsToContactgroups.contact_id = Contacts.id']
            )
            ->innerJoin(
                ['Contactgroups' => 'contactgroups'],
                ['Contactgroups.id = ContactsToContactgroups.contactgroup_id']
            )
            ->innerJoin(
                ['Containers' => 'containers'],
                ['Containers.id = Contactgroups.container_id']
            )
            ->where([
                'Contacts.id'             => $contactId,
                'Containers.parent_id IN' => $containerIds
            ])
            ->distinct()
            ->disableAutoFields()
            ->disableHydration()
            ->toArray();
        return Hash::extract($query, '{n}.Containers.parent_id');
    }

    /**
     * ContactsToHosttemplates
     * @param int $contactId
     * @param array $containerIds
     * @return array
     */
    public function getHosttemplateContainerIdsForContact(int $contactId, array $containerIds) {
        if (empty($containerIds)) {
            return [];
        }

        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $query = $this->find()
            ->select([
                'Hosttemplates.container_id'
            ])
            ->innerJoin(
                ['ContactsToHosttemplates' => 'contacts_to_hosttemplates'],
                ['ContactsToHosttemplates.contact_id = Contacts.id']
            )
            ->innerJoin(
                ['Hosttemplates' => 'hosttemplates'],
                ['Hosttemplates.id = ContactsToHosttemplates.hosttemplate_id']
            )
            ->where([
                'Contacts.id'                   => $contactId,
                'Hosttemplates.container_id IN' => $containerIds
            ])
            ->distinct()
            ->disableAutoFields()
            ->disableHydration()
            ->toArray();
        return Hash::extract($query, '{n}.Hosttemplates.container_id');
    }


    /**
     * ContactsToServicetemplates
     * @param int $contactId
     * @param array $containerIds
     * @return array
     */
    public function getServicetemplateContainerIdsForContact(int $contactId, array $containerIds) {
        if (empty($containerIds)) {
            return [];
        }

        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $query = $this->find()
            ->select([
                'Servicetemplates.container_id'
            ])
            ->innerJoin(
                ['ContactsToServicetemplates' => 'contacts_to_servicetemplates'],
                ['ContactsToServicetemplates.contact_id = Contacts.id']
            )
            ->innerJoin(
                ['Servicetemplates' => 'servicetemplates'],
                ['Servicetemplates.id = ContactsToServicetemplates.servicetemplate_id']
            )
            ->where([
                'Contacts.id'                      => $contactId,
                'Servicetemplates.container_id IN' => $containerIds
            ])
            ->distinct()
            ->disableAutoFields()
            ->disableHydration()
            ->toArray();
        return Hash::extract($query, '{n}.Servicetemplates.container_id');
    }

    /**
     * ContactsToHosts
     * @param int $contactId
     * @param array $containerIds
     * @return array
     */
    public function getHostContainerIdsForContact(int $contactId, array $containerIds) {
        if (empty($containerIds)) {
            return [];
        }

        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $query = $this->find()
            ->select([
                'Hosts.container_id'
            ])
            ->innerJoin(
                ['ContactsToHosts' => 'contacts_to_hosts'],
                ['ContactsToHosts.contact_id = Contacts.id']
            )
            ->innerJoin(
                ['Hosts' => 'hosts'],
                ['Hosts.id = ContactsToHosts.host_id']
            )
            ->where([
                'Contacts.id'           => $contactId,
                'Hosts.container_id IN' => $containerIds
            ])
            ->distinct()
            ->disableAutoFields()
            ->disableHydration()
            ->toArray();
        return Hash::extract($query, '{n}.Hosts.container_id');
    }

    /**
     * ContactsToHostescalations,
     * @param int $contactId
     * @param array $containerIds
     * @return array
     */
    public function getHostescalationContainerIdsForContact(int $contactId, array $containerIds) {
        if (empty($containerIds)) {
            return [];
        }

        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $query = $this->find()
            ->select([
                'Hostescalations.container_id'
            ])
            ->innerJoin(
                ['ContactsToHostescalations' => 'contacts_to_hostescalations'],
                ['ContactsToHostescalations.contact_id = Contacts.id']
            )
            ->innerJoin(
                ['Hostescalations' => 'hostescalations'],
                ['Hostescalations.id = ContactsToHostescalations.hostescalation_id']
            )
            ->where([
                'Contacts.id'                     => $contactId,
                'Hostescalations.container_id IN' => $containerIds
            ])
            ->distinct()
            ->disableAutoFields()
            ->disableHydration()
            ->toArray();
        return Hash::extract($query, '{n}.Hostescalations.container_id');
    }

    /**
     * ContactsToServiceescalations,
     * @param int $contactId
     * @param array $containerIds
     * @return array
     */
    public function getServiceescalationContainerIdsForContact(int $contactId, array $containerIds) {
        if (empty($containerIds)) {
            return [];
        }

        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $query = $this->find()
            ->select([
                'Serviceescalations.container_id'
            ])
            ->innerJoin(
                ['ContactsToServiceescalations' => 'contacts_to_serviceescalations'],
                ['ContactsToServiceescalations.contact_id = Contacts.id']
            )
            ->innerJoin(
                ['Serviceescalations' => 'serviceescalations'],
                ['Serviceescalations.id = ContactsToServiceescalations.serviceescalation_id']
            )
            ->where([
                'Contacts.id'                        => $contactId,
                'Serviceescalations.container_id IN' => $containerIds
            ])
            ->distinct()
            ->disableAutoFields()
            ->disableHydration()
            ->toArray();
        return Hash::extract($query, '{n}.Serviceescalations.container_id');
    }

    /**
     * @param $contactId
     * @param array $containerIds
     * @return array
     */
    public function getRequiredContainerIdsFoContact($contactId, array $containerIds) {
        $requiredIds = [];
        if (empty($containerIds)) {
            return $requiredIds;
        }
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }
        $ids = array_unique(
            array_merge_recursive(
                $this->getContactgroupContainerIdsForContact($contactId, $containerIds),
                $this->getHosttemplateContainerIdsForContact($contactId, $containerIds),
                $this->getServicetemplateContainerIdsForContact($contactId, $containerIds),
                $this->getHostContainerIdsForContact($contactId, $containerIds),
                $this->getHostescalationContainerIdsForContact($contactId, $containerIds),
                $this->getServiceescalationContainerIdsForContact($contactId, $containerIds)
            )
        );

        foreach ($ids as $requiredId) {
            $requiredIds[] = (int)$requiredId;
        }
        return $requiredIds;
    }

    /**
     * @param int $containerId
     * @return array
     */
    public function getOrphanedContactsByContainerId(int $containerId) {
        $query = $this->find()
            ->innerJoinWith('Containers')
            ->contain([
                'Containers' => function (Query $query) use ($containerId) {
                    return $query->select([
                        'Containers.id',
                    ])->whereNotInList('Containers.id', [$containerId]);
                }
            ])
            ->where(['Containers.id' => $containerId]);

        $result = $query->all();
        $contacts = $result->toArray();

        // Check each contact, if it as more than one container.
        // If the contact has more than 1 container, we can keep this contact because is not orphaned
        $orphanedContacts = [];
        foreach ($contacts as $contact) {
            if (empty($contact->containers)) {
                $orphanedContacts[] = $contact;
            }
        }

        return $orphanedContacts;
    }

}
