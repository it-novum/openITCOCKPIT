<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
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

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
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
            'conditions' => [
                'objecttype_id' => OBJECT_CONTACT
            ],
            'foreignKey' => 'object_id'
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
    public function validationDefault(Validator $validator) {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->allowEmptyString('uuid', false)
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('name')
            ->maxLength('name', 64)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->allowEmptyString('description', true);

        $validator
            ->allowEmptyString('email', function ($context) {
                return !empty($context['data']['email']) || !empty($context['data']['phone']);
            }, __('You must at least specify either the email address or the phone number.'))
            ->email('email');

        $validator
            ->allowEmptyString('phone', true)
            ->maxLength('phone', 64)
            ->allowEmptyString('email', function ($context) {
                return !empty($context['data']['email']) || !empty($context['data']['phone']);
            }, __('You must at least specify either the email address or the phone number.'))
            ->regex('phone', '/[\d\s-\+]+/');

        $validator
            ->integer('host_timeperiod_id')
            ->allowEmptyString('host_timeperiod_id', false)
            ->greaterThan('host_timeperiod_id', 0);

        $validator
            ->integer('service_timeperiod_id')
            ->allowEmptyString('service_timeperiod_id', false)
            ->greaterThan('service_timeperiod_id', 0);

        $validator
            ->allowEmptyString('HostCommands', false)
            ->multipleOptions('HostCommands', [
                'min' => 1
            ], __('You have to choose at least one command.'));

        $validator
            ->allowEmptyString('ServiceCommands', false)
            ->multipleOptions('ServiceCommands', [
                'min' => 1
            ], __('You have to choose at least one command.'));

        $validator->add('notify_host_recovery', 'custom', [
            'rule'    => [$this, 'checkHostNotificationOptions'],
            'message' => 'You have to choose at least one option.',
        ]);

        $validator->add('notify_service_recovery', 'custom', [
            'rule'    => [$this, 'checkHostNotificationOptions'],
            'message' => 'You have to choose at least one option.',
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
    public function buildRules(RulesChecker $rules) {
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
            '__ContactsToContactgroups',
            '__ContactsToHosttemplates',
            '__ContactsToHosts',
            '__ContactsToServicetemplates',
            '__ContactsToServices',
            '__ContactsToHostescalations',
            '__ContactsToServiceescalations',
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

        $query->innerJoinWith('Containers', function ($q) use ($MY_RIGHTS) {
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
     * @return array
     */
    public function getContactById($id) {
        $query = $this->find()
            ->where([
                'Contacts.id' => $id
            ])
            ->contain(['Containers'])
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
     * @param \CakeRequest $Request
     * @return array
     */
    public function getExtDataForChangelog(\CakeRequest $Request) {
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

        foreach ($Request->data('Contact.host_commands._ids') as $id) {
            $command = $CommandsTable->getCommandById($id);
            if (!empty($command)) {
                $extDataForChangelog['HostCommands'][] = [
                    'id'   => $command['Command']['id'],
                    'name' => $command['Command']['name']
                ];
            }
        }

        foreach ($Request->data('Contact.service_commands._ids') as $id) {
            $command = $CommandsTable->getCommandById($id);
            if (!empty($command)) {
                $extDataForChangelog['ServiceCommands'][] = [
                    'id'   => $command['Command']['id'],
                    'name' => $command['Command']['name']
                ];
            }
        }

        $timeperiod = $TimeperiodsTable->getTimeperiodById($Request->data('Contact.host_timeperiod_id'));
        if (!empty($timeperiod)) {
            $extDataForChangelog['HostTimeperiod'] = [
                'id'   => $timeperiod['Timeperiod']['id'],
                'name' => $timeperiod['Timeperiod']['name']
            ];
        }

        $timeperiod = $TimeperiodsTable->getTimeperiodById($Request->data('Contact.service_timeperiod_id'));
        if (!empty($timeperiod)) {
            $extDataForChangelog['ServiceTimeperiod'] = [
                'id'   => $timeperiod['Timeperiod']['id'],
                'name' => $timeperiod['Timeperiod']['name']
            ];
        }

        return $extDataForChangelog;
    }

}
