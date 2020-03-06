<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use App\Model\Entity\User;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\FileDebugger;
use itnovum\openITCOCKPIT\Core\UUID;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\UsersFilter;

/**
 * Users Model
 *
 * @property \App\Model\Table\UsergroupsTable|\Cake\ORM\Association\BelongsTo $Usergroups
 * @property \App\Model\Table\ApikeysTable|\Cake\ORM\Association\HasMany $Apikeys
 * @property \App\Model\Table\ChangelogsTable|\Cake\ORM\Association\HasMany $Changelogs
 * @property \App\Model\Table\ContactsTable|\Cake\ORM\Association\HasMany $Contacts
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table {
    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;

    /**
     * Password validation regex.
     */
    const PASSWORD_REGEX = '/^(?=.*\d).{6,}$/i';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Usergroups', [
            'foreignKey' => 'usergroup_id',
            'joinType'   => 'INNER'
        ]);
        $this->hasMany('Apikeys', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Contacts', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsToMany('Containers', [
            'through'          => 'ContainersUsersMemberships',
            'className'        => 'Containers',
            'foreignKey'       => 'user_id',
            'targetForeignKey' => 'container_id',
            'joinTable'        => 'users_to_containers'
        ]);

        $this->belongsToMany('Usercontainerroles', [
            'className'        => 'Usercontainerroles',
            'joinTable'        => 'users_to_usercontainerroles',
            'foreignKey'       => 'user_id',
            'targetForeignKey' => 'usercontainerrole_id',
            'saveStrategy'     => 'replace'
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
            ->allowEmptyString('containers', __('You need to select at least one container or container role.'), function ($context) {
                return $this->validateHasContainerOrContainerUserRolePermissions(null, $context);
            })
            ->add('containers', 'custom', [
                'rule'    => [$this, 'validateHasContainerOrContainerUserRolePermissions'],
                'message' => __('You need to select at least one container or container role.')
            ]);

        $validator
            ->allowEmptyString('usercontainerroles', __('You need to select at least one container or container role.'), function ($context) {
                return $this->validateHasContainerOrContainerUserRolePermissions(null, $context);
            })
            ->add('usercontainerroles', 'custom', [
                'rule'    => [$this, 'validateHasContainerOrContainerUserRolePermissions'],
                'message' => __('You need to select at least one container or container role.')
            ]);

        $validator
            ->integer('usergroup_id')
            ->requirePresence('usergroup_id', 'create')
            ->greaterThan('usergroup_id', 0, __('You have to select a user role.'))
            ->allowEmptyString('usergroup_id', null, false);

        $validator
            ->boolean('is_active')
            ->requirePresence('is_active', 'create')
            ->allowEmptyString('is_active', null, false);

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->allowEmptyString('email', null, false);

        $validator
            ->scalar('firstname')
            ->maxLength('firstname', 100)
            ->requirePresence('firstname', 'create')
            ->allowEmptyString('firstname', null, false);

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 100)
            ->requirePresence('lastname', 'create')
            ->allowEmptyString('lastname', null, false);

        $validator
            ->scalar('position')
            ->maxLength('position', 100)
            ->allowEmptyString('position');

        $validator
            ->scalar('company')
            ->maxLength('company', 100)
            ->allowEmptyString('company');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 100)
            ->allowEmptyString('phone');

        $validator
            ->scalar('timezone')
            ->maxLength('timezone', 100)
            ->allowEmptyString('timezone');

        $validator
            ->scalar('dateformat')
            ->maxLength('dateformat', 100)
            ->allowEmptyString('dateformat');

        $validator
            ->scalar('image')
            ->maxLength('image', 100)
            ->allowEmptyFile('image');

        $validator
            ->scalar('onetimetoken')
            ->maxLength('onetimetoken', 100)
            ->allowEmptyString('onetimetoken');

        $validator
            ->scalar('samaccountname')
            ->maxLength('samaccountname', 128)
            ->allowEmptyString('samaccountname', __('You have to select a user'), function ($context) {
                if (isset($context['data']['is_ldap']) && $context['data']['is_ldap'] === true) {
                    //User create an LDAP user - samaccountname is required
                    return false;
                }

                //User create a non LDAP user
                return true;
            });

        $validator
            ->scalar('ldap_dn')
            ->maxLength('ldap_dn', 512)
            ->allowEmptyString('ldap_dn', __('DN could not left be blank.'), function ($context) {
                if (isset($context['data']['is_ldap']) && $context['data']['is_ldap'] === true) {
                    //User create an LDAP user - samaccountname is required
                    return false;
                }

                //User create a non LDAP user
                return true;
            });
        $validator
            ->boolean('showstatsinmenu')
            ->requirePresence('showstatsinmenu', 'create')
            ->allowEmptyString('showstatsinmenu', null, false);

        $validator
            ->integer('dashboard_tab_rotation')
            ->requirePresence('dashboard_tab_rotation', 'create')
            ->allowEmptyString('dashboard_tab_rotation', null, false);

        $validator
            ->integer('paginatorlength')
            ->requirePresence('paginatorlength', 'create')
            ->allowEmptyString('paginatorlength', null, false)
            ->greaterThan('paginatorlength', 0, __('Minimum amount is 1'))
            ->lessThanOrEqual('paginatorlength', 1000, __('Maximum amount is 1000'));

        $validator
            ->boolean('recursive_browser')
            ->requirePresence('recursive_browser', 'create')
            ->allowEmptyString('recursive_browser', null, false);

        $validator
            ->scalar('password')
            ->requirePresence('password', 'create')
            ->allowEmptyString('password', null, false)
            ->regex('password', self::PASSWORD_REGEX, 'The password must consist of 6 alphanumeric characters and must contain at least one digit.');

        $validator->add('confirm_password',
            'compareWith', [
                'rule'    => ['compareWith', 'password'],
                'message' => 'Passwords not equal'
            ]);

        return $validator;
    }

    /**
     * @param null $value
     * @param array $context
     * @return bool
     */
    public function validateHasContainerOrContainerUserRolePermissions($value, $context) {
        if (isset($context['data']['containers']) && is_array($context['data']['containers'])) {
            if (!empty($context['data']['containers']) && sizeof($context['data']['containers']) > 0) {

                //User has own containers
                return true;
            }
        }

        //Has the user an user container role assignment?
        if (isset($context['data']['usercontainerroles']['_ids']) && is_array($context['data']['usercontainerroles']['_ids'])) {
            if (!empty($context['data']['usercontainerroles']['_ids']) && sizeof($context['data']['usercontainerroles']['_ids']) > 0) {

                //User has a user container role assignment
                return true;
            }
        }

        //No own containers, no user container role
        return false;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['usergroup_id'], 'Usergroups'));

        return $rules;
    }

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param \ArrayObject $options
     * @return bool
     */
    public function beforeSave(Event $event, EntityInterface $entity, \ArrayObject $options) {
        if ($entity->isDirty('password')) {
            $Hasher = $this->getDefaultPasswordHasher();
            $entity->password = $Hasher->hash($entity->password);
        }
        return true;
    }

    /**
     * @return DefaultPasswordHasher
     */
    public function getDefaultPasswordHasher(){
        return new DefaultPasswordHasher();
    }

    /**
     * @param array $rights
     * @param UsersFilter $UsersFilter
     * @param PaginateOMat|null $PaginateOMat
     * @return array
     */
    public function getUsersIndex(UsersFilter $UsersFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        //Get all user ids where container assigned are made directly at the user
        $query = $this->find()
            ->select([
                'Users.id'
            ])
            ->matching('Containers')
            ->group([
                'Users.id'
            ])
            ->disableHydration();

        if (!empty($MY_RIGHTS)) {
            $query->where([
                'ContainersUsersMemberships.container_id IN' => $MY_RIGHTS
            ]);
        }

        $userIds = Hash::extract($query->toArray(), '{n}.id');

        //Get all user ids where container assigned are made through an user container role
        $query = $this->find()
            ->select([
                'Users.id'
            ])
            ->matching('Usercontainerroles.Containers')
            ->group([
                'Users.id'
            ])
            ->disableHydration();

        if (!empty($MY_RIGHTS)) {
            $query->where([
                'Containers.id IN' => $MY_RIGHTS
            ]);
        }

        $userIdsThroughContainerRoles = Hash::extract($query->toArray(), '{n}.id');

        $userIds = array_unique(array_merge($userIds, $userIdsThroughContainerRoles));

        $where = $UsersFilter->indexFilter();
        $having = [];
        if (isset($where['full_name LIKE'])) {
            $having['full_name LIKE'] = $where['full_name LIKE'];
            unset($where['full_name LIKE']);
        }

        $query = $this->find();
        $query->select([
            'Users.id',
            'Users.email',
            'Users.company',
            'Users.phone',
            'Users.is_active',
            'Users.samaccountname',
            'Usergroups.id',
            'Usergroups.name',
            'full_name' => $query->func()->concat([
                'Users.firstname' => 'literal',
                ' ',
                'Users.lastname'  => 'literal'
            ])
        ])
            ->contain([
                'Usergroups',
                'Containers',
                'Usercontainerroles' => [
                    'Containers'
                ]
            ]);

        if (!empty($userIds)) {
            $query->where([
                'Users.id IN' => $userIds
            ]);
        }

        if (!empty($where)) {
            $query->andWhere($where);
        }
        if (!empty($having)) {
            $query->having($having);
        }

        $query->order($UsersFilter->getOrderForPaginator('full_name', 'asc'));
        $query->group([
            'Users.id'
        ]);

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $query->toArray();
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
     * @param int $id
     * @return array
     */
    public function getUserForEdit($id) {
        $query = $this->find()
            ->select([
                'Users.id',
                'Users.usergroup_id',
                'Users.email',
                'Users.firstname',
                'Users.lastname',
                'Users.position',
                'Users.company',
                'Users.phone',
                'Users.timezone',
                'Users.i18n',
                'Users.dateformat',
                'Users.samaccountname',
                'Users.ldap_dn',
                'Users.showstatsinmenu',
                'Users.is_active',
                'Users.dashboard_tab_rotation',
                'Users.paginatorlength',
                'Users.recursive_browser',
                'Users.image'
            ])
            ->where([
                'Users.id' => $id
            ])
            ->contain([
                'Usergroups',
                'Containers',
                'Usercontainerroles' => [
                    'Containers'
                ]
            ])
            ->disableHydration()
            ->first();


        $user = $query;

        $intCasts = [
            'showstatsinmenu',
            'is_active',
            'dashboard_tab_rotation',
            'paginatorlength',
            'recursive_browser'
        ];
        foreach ($intCasts as $intCast) {
            $user[$intCast] = (int)$user[$intCast];
        }

        $user['containers'] = [
            '_ids' => Hash::extract($query, 'containers.{n}.id')
        ];
        $user['usercontainerroles'] = [
            '_ids' => Hash::extract($query, 'usercontainerroles.{n}.id')
        ];

        $user['usercontainerroles_containerids'] = [
            '_ids' => Hash::extract($query, 'usercontainerroles.{n}.containers.{n}.id')
        ];

        //Build up data struct for radio inputs (only of user containers - NOT for container roles)
        $user['ContainersUsersMemberships'] = [];
        foreach ($query['containers'] as $container) {
            //Cast permission_level to string for AngularJS...
            $user['ContainersUsersMemberships'][$container['id']] = (string)$container['_joinData']['permission_level'];
        }

        if (empty($user['ContainersUsersMemberships'])) {
            //Make this an empty object {} in the JSON, not an empty array []
            $user['ContainersUsersMemberships'] = new \stdClass();
        }

        return [
            'User' => $user
        ];
    }


    /**
     * @param array $containerPermissions
     * @return array
     */
    public function containerPermissionsForSave($containerPermissions = []) {
        //ContainersUsersMemberships

        $dataForSave = [];
        foreach ($containerPermissions as $containerId => $permissionLevel) {
            $containerId = (int)$containerId;
            $permissionLevel = (int)$permissionLevel;
            if ($permissionLevel !== READ_RIGHT && $permissionLevel !== WRITE_RIGHT) {
                $permissionLevel = READ_RIGHT;
            }
            if ($containerId === ROOT_CONTAINER) {
                // ROOT_CONTAINER is always read/write
                $permissionLevel = WRITE_RIGHT;
            }

            $dataForSave[] = [
                'id'        => $containerId,
                '_joinData' => [
                    'permission_level' => $permissionLevel
                ]
            ];
        }

        return $dataForSave;
    }

    public function getUserByEmail($email = null) {
        $query = $this->find();
        $query->disableHydration()
            ->where([
                'Users.email' => $email,
            ])
            ->select([
                'Users.id',
                'Users.email',
                'Users.password',
                'Users.company',
                'Users.samaccountname',
                'Users.ldap_dn',
                'Users.usergroup_id',
                'Users.is_active',
                'Users.firstname',
                'Users.lastname',
                'Users.position',
                'Users.phone',
                'Users.timezone',
                'Users.i18n',
                'Users.dateformat',
                'Users.showstatsinmenu',
                'Users.dashboard_tab_rotation',
                'Users.paginatorlength',
                'Users.recursive_browser',
                'Users.image',
                'Users.onetimetoken',
                'full_name' => $query->func()->concat([
                    'Users.firstname' => 'literal',
                    ' ',
                    'Users.lastname'  => 'literal'
                ])
            ]);
        if (!is_null($query)) {
            return $query->first();
        }
        return [];
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Users.id' => $id]);
    }

    /**
     * @return string
     */
    public function generatePassword() {
        $char = [
            0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
            'a', 'b', 'c', 'd', 'e', 'f',
            'g', 'h', 'i', 'j', 'k', 'l',
            'm', 'n', 'o', 'p', 'q', 'r',
            's', 't', 'u', 'v', 'w', 'x',
            'y', 'z', 'A', 'B', 'C', 'D',
            'E', 'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'N', 'O', 'P',
            'Q', 'R', 'S', 'T', 'U', 'V',
            'W', 'X', 'Y', 'Z'
        ];
        $size = (sizeof($char) - 1);
        $token = '';
        for ($i = 0; $i < 7; $i++) {
            $token .= $char[rand(0, $size)];
        }
        $token = $token . rand(0, 9);

        return $token;
    }

    /**
     * @return array
     */
    public function getDateformats() {
        //Format: https://www.php.net/manual/de/function.date.php
        return [
            1 => 'F j, Y H:i:s',        //'%B %e, %Y %H:%M:%S',
            2 => 'm-d-Y H:i:s',         //'%m-%d-%Y  %H:%M:%S',
            3 => 'm-d-Y H:i',           //'%m-%d-%Y  %H:%M',
            4 => 'm-d-Y h:i:s A',       //'%m-%d-%Y  %l:%M:%S %p',
            5 => 'H:i:s m-d-Y',         //'%H:%M:%S  %m-%d-%Y',

            6  => 'j F Y, H:i:s',       // '%e %B %Y, %H:%M:%S',
            7  => 'd.m.Y - H:i:s',      // '%d.%m.%Y - %H:%M:%S',
            9  => 'd.m.Y - h:i:s A',    // '%d.%m.%Y - %l:%M:%S %p',
            10 => 'H:i:s - d.m.Y',      // '%H:%M:%S - %d.%m.%Y', //Default date format
            11 => 'H:i - d.m.Y',        // '%H:%M - %d.%m.%Y',

            12 => 'Y-m-d H:i',          // '%Y-%m-%d %H:%M',
            13 => 'Y-m-d H:i:s',        // '%Y-%m-%d %H:%M:%S'
        ];
    }

    /**
     * @param array $containerIds
     * @param string $type
     * @return array
     */
    public function getUsersByContainerIds($containerIds = [], $type = 'all') {
        return $this->usersByContainerId($containerIds, $type);
    }

    /**
     * @param array $containerIds
     * @param string $type
     * @return array
     */
    public function usersByContainerId($containerIds = [], $type = 'all') {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        //Get all user ids where container assigned are made directly at the user
        $query = $this->find()
            ->select([
                'Users.id'
            ])
            ->matching('Containers')
            ->group([
                'Users.id'
            ])
            ->disableHydration();

        if (!empty($containerIds)) {
            $query->where([
                'ContainersUsersMemberships.container_id IN' => $containerIds
            ]);
        }

        $userIds = Hash::extract($query->toArray(), '{n}.id');

        //Get all user ids where container assigned are made through an user container role
        $query = $this->find()
            ->select([
                'Users.id'
            ])
            ->matching('Usercontainerroles.Containers')
            ->group([
                'Users.id'
            ])
            ->disableHydration();

        if (!empty($containerIds)) {
            $query->where([
                'Containers.id IN' => $containerIds
            ]);
        }

        $userIdsThroughContainerRoles = Hash::extract($query->toArray(), '{n}.id');

        $userIds = array_unique(array_merge($userIds, $userIdsThroughContainerRoles));
        if (empty($userIds)) {
            return [];
        }

        $query = $this->find();
        $query->select([
            'Users.id',
            'Users.email',
            'Users.password',
            'Users.company',
            'Users.samaccountname',
            'Users.ldap_dn',
            'Users.usergroup_id',
            'Users.is_active',
            'Users.firstname',
            'Users.lastname',
            'Users.position',
            'Users.phone',
            'Users.timezone',
            'Users.i18n',
            'Users.dateformat',
            'Users.showstatsinmenu',
            'Users.dashboard_tab_rotation',
            'Users.paginatorlength',
            'Users.recursive_browser',
            'Users.image',
            'Users.onetimetoken',
            'full_name' => $query->func()->concat([
                'Users.firstname' => 'literal',
                ' ',
                'Users.lastname'  => 'literal'
            ])
        ])
            ->where([
                'Users.id IN' => $userIds
            ])
            ->order([
                'full_name' => 'asc'
            ])
            ->group([
                'Users.id'
            ])
            ->disableHydration()
            ->all();

        if ($type === 'list') {
            $return = [];
            foreach ($query->toArray() as $user) {
                $return[$user['id']] = $user['lastname'] . ', ' . $user['firstname'];
            }
            return $return;
        }

        return $query->toArray();
    }

    /**
     * May deprecated functions after fully moving to cakephp 4
     * get the first user
     * @return array
     */
    public function getFirstUser() {
        $query = $this->find('all')->disableHydration();
        $result = $query->first();
        return $this->formatFirstResultAsCake2($result);
    }

    /**
     * @param int $id
     * @return array|EntityInterface
     */
    public function getActiveUsersByIdForCake2Login($id) {
        $query = $this->find();
        $query->select([
            'Users.id',
            'Users.email',
            'Users.password',
            'Users.company',
            'Users.samaccountname',
            'Users.ldap_dn',
            'Users.usergroup_id',
            'Users.is_active',
            'Users.firstname',
            'Users.lastname',
            'Users.position',
            'Users.phone',
            'Users.timezone',
            'Users.i18n',
            'Users.dateformat',
            'Users.showstatsinmenu',
            'Users.dashboard_tab_rotation',
            'Users.paginatorlength',
            'Users.recursive_browser',
            'Users.image',
            'Users.onetimetoken',
            'full_name' => $query->func()->concat([
                'Users.firstname' => 'literal',
                ' ',
                'Users.lastname'  => 'literal'
            ]),

            'Usergroups.id',
            'Usergroups.name',
            'Usergroups.description',
            'Usergroups.created',
            'Usergroups.modified',
        ])
            ->contain([
                'Usergroups'
            ])
            ->disableHydration()
            ->where([
                'Users.id'        => $id,
                'Users.is_active' => 1
            ]);
        $rawResult = $query->firstOrFail();
        $result = [
            'User' => $rawResult
        ];
        unset($result['User']['usergroup']);

        $result['User']['Usergroup'] = $rawResult['usergroup'];
        $result['User']['Usergroup']['created'] = date('Y-m-d H:i:s', $result['User']['Usergroup']['created']->timestamp);
        $result['User']['Usergroup']['modified'] = date('Y-m-d H:i:s', $result['User']['Usergroup']['modified']->timestamp);

        return $result;
    }

    /**
     * May deprecated functions after fully moving to cakephp 4
     * @param $email
     * @return array
     */
    public function getActiveUsersByEmail($email) {
        $query = $this->find();
        $query->select([
            'Users.id',
            'Users.email',
            'Users.password',
            'Users.company',
            'Users.samaccountname',
            'Users.ldap_dn',
            'Users.usergroup_id',
            'Users.is_active',
            'Users.firstname',
            'Users.lastname',
            'Users.position',
            'Users.phone',
            'Users.timezone',
            'Users.i18n',
            'Users.dateformat',
            'Users.showstatsinmenu',
            'Users.dashboard_tab_rotation',
            'Users.paginatorlength',
            'Users.recursive_browser',
            'Users.image',
            'Users.onetimetoken',
            'full_name' => $query->func()->concat([
                'Users.firstname' => 'literal',
                ' ',
                'Users.lastname'  => 'literal'
            ])
        ])
            ->disableHydration()
            ->where([
                'Users.email'     => $email,
                'Users.is_active' => 1
            ]);
        $result = $query->first();
        return $this->formatFirstResultAsCake2($result);
    }

    /**
     * @param string $samaccountname
     * @return array|EntityInterface|null
     */
    public function getUserBySamAccountName(string $samaccountname) {
        $query = $this->find();
        return $query
            ->where([
                'Users.samaccountname' => $samaccountname,
                'Users.is_active'      => 1
            ])
            ->first();
    }

    /**
     * @param string v
     * @return array|EntityInterface|null
     */
    public function getUserByEmailForLogin(string $email) {
        $query = $this->find();
        return $query
            ->where([
                'Users.email'     => $email,
                'Users.is_active' => 1
            ])
            ->first();
    }

    /**
     * @param string v
     * @return array|EntityInterface|null
     */
    public function getUserByApikeyForLogin(string $apikey) {
        $query = $this->find()
            ->join([
                [
                    'table'      => 'apikeys',
                    'alias'      => 'Apikeys',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Apikeys.user_id = Users.id'
                    ]
                ]
            ])
            ->enableAutoFields()
            ->where([
                'Users.is_active' => 1,
                'Apikeys.apikey' => $apikey
            ]);
        return $query->first();
    }

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $likeEmail
     * @return array|EntityInterface|null
     */
    public function getUserForFhgLogin(string $firstname, string $lastname, string $likeEmail) {
        $query = $this->find();
        return $query
            ->where([
                'Users.firstname'  => $firstname,
                'Users.lastname'   => $lastname,
                'Users.is_active'  => 1,
                'Users.email LIKE' => sprintf('%%%s%%', $likeEmail)
            ])
            ->first();
    }

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $likeEmail
     * @return array|EntityInterface|null
     */
    public function getUserForFhgLoginInsecure(string $firstname, string $lastname) {
        $query = $this->find();
        return $query
            ->where([
                'Users.firstname' => $firstname,
                'Users.lastname'  => $lastname,
                'Users.is_active' => 1
            ])
            ->first();
    }

    /**
     * May deprecated functions after fully moving to cakephp 4
     * @param $id
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getUserById($id) {
        $query = $this->find('all')
            ->disableHydration()
            ->contain([
                'Containers',
                'Usercontainerroles' => [
                    'Containers'
                ]
            ])
            ->where([
                'Users.id' => $id
            ]);
        if (is_null($query)) {
            return [];
        }
        return $query->first();
    }

    /**
     * This method is used to fetch all users that needs to be
     * deleted if a Container/Node gets deleted.
     *
     * @param $containerIds
     * @return array
     */
    public function getUsersToDeleteByContainerIds($containerIds) {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        //Get all user ids where container assigned are made directly at the user
        $query = $this->find()
            ->select([
                'Users.id'
            ])
            ->matching('Containers')
            ->group([
                'Users.id'
            ])
            ->disableHydration();

        if (!empty($containerIds)) {
            $query->where([
                'ContainersUsersMemberships.container_id IN' => $containerIds
            ]);
        }

        $userIds = Hash::extract($query->toArray(), '{n}.id');

        //Get all user ids where container assigned are made through an user container role
        $query = $this->find()
            ->select([
                'Users.id'
            ])
            ->matching('Usercontainerroles.Containers')
            ->group([
                'Users.id'
            ])
            ->disableHydration();

        if (!empty($containerIds)) {
            $query->where([
                'Containers.id IN' => $containerIds
            ]);
        }

        $userIdsThroughContainerRoles = Hash::extract($query->toArray(), '{n}.id');

        $userIds = array_unique(array_merge($userIds, $userIdsThroughContainerRoles));
        if (empty($userIds)) {
            return [];
        }

        $query = $this->find()
            ->where([
                'Users.id IN' => $userIds
            ])
            ->group([
                'Users.id'
            ])
            ->contain([
                'Containers',
                'Usercontainerroles' => [
                    'Containers'
                ]
            ])
            ->all();

        $users = $query->toArray();
        if ($users === null) {
            return [];
        }

        $userToDelete = [];
        foreach ($users as $user) {
            /** @var User $user */
            $containerWithWritePermissionByUserContainerRoles = Hash::extract(
                $user['usercontainerroles'],
                '{n}.containers.{n}._joinData.container_id'
            );

            $container = Hash::extract(
                $user['containers'],
                '{n}.id'
            );

            $containers = array_unique(array_merge($container, $containerWithWritePermissionByUserContainerRoles));

            foreach ($containerIds as $containerId) {
                foreach ($containers as $index => $containerId) {
                    //Remove the container, which should get deleted, from the user assigned containers.
                    if ((int)$containerId === (int)$containerId) {
                        unset($containers[$index]);
                    }
                }
            }

            if (empty($containers)) {
                //User has no containers anymore - delete this user
                $userToDelete[] = $user;
            }
        }

        return $userToDelete;
    }

    /**
     * @return false|string
     */
    public function uploadProfilePicture() {
        $path = WWW_ROOT . 'img' . DS . 'userimages' . DS;

        if ($_FILES['Picture']['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        if (!is_dir($path)) {
            return false;
        }

        $tmpImage = UUID::v4();
        if (move_uploaded_file($_FILES['Picture']['tmp_name'], $path . $tmpImage)) {
            $newImage = UUID::v4() . '.png';

            //Try to create new user image from uploaded image

            $tmpImageFull = $path . $tmpImage;
            $newImageFull = $path . $newImage;

            $imgsize = getimagesize($tmpImageFull);
            $width = $imgsize[0];
            $height = $imgsize[1];
            $imgtype = $imgsize[2];

            switch ($imgtype) {
                /**
                 * 1 => GIF
                 * 2 => JPG
                 * 3 => PNG
                 * 4 => SWF
                 * 5 => PSD
                 * 6 => BMP
                 * 7 => TIFF(intel byte order)
                 * 8 => TIFF(motorola byte order)
                 * 9 => JPC
                 * 10 => JP2
                 * 11 => JPX
                 * 12 => JB2
                 * 13 => SWC
                 * 14 => IFF
                 * 15 => WBMP
                 * 16 => XBM
                 */
                case 1:
                    $srcImg = imagecreatefromgif($tmpImageFull);
                    break;
                case 2:
                    $srcImg = imagecreatefromjpeg($tmpImageFull);
                    break;
                case 3:
                    $srcImg = imagecreatefrompng($tmpImageFull);
                    break;
                default:
                    //Filetype not supported!
                    return false;
                    break;
            }

            $newWidth = 120;
            $newHeight = 120;
            //Thanks to http://php.net/manual/de/function.imagecopyresized.php#50019 :)
            if ($width > $height && $newWidth < $newHeight) {
                $newHeight = $height / ($width / $newWidth);
            } else if ($width < $height && $newHeight < $width) {
                $newWidth = $width / ($height / $newHeight);
            } else {
                $newHeight = $height;
                $newWidth = $width;
            }
            $destImg = imagecreatetruecolor($newWidth, $newHeight);
            $transparent = imagecolorallocatealpha($destImg, 0, 0, 0, 127);
            imagefill($destImg, 0, 0, $transparent);
            imagecopyresized($destImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagealphablending($destImg, false);
            imagesavealpha($destImg, true);
            imagepng($destImg, $newImageFull);
            imagedestroy($destImg);

            //Delete source image
            unlink($tmpImageFull);
            return $newImage;
        }
        return false;
    }
}
