<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Security;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Filter\UsersFilter;

/**
 * Users Model
 *
 * @property \App\Model\Table\UsergroupsTable|\Cake\ORM\Association\BelongsTo $Usergroups
 * @property \App\Model\Table\ApikeysTable|\Cake\ORM\Association\HasMany $Apikeys
 * @property \App\Model\Table\ChangelogsTable|\Cake\ORM\Association\HasMany $Changelogs
 * @property \App\Model\Table\ContactsTable|\Cake\ORM\Association\HasMany $Contacts
 * @property \App\Model\Table\DashboardTabsTable|\Cake\ORM\Association\HasMany $DashboardTabs
 * @property \App\Model\Table\InstantreportsToUsersTable|\Cake\ORM\Association\HasMany $InstantreportsToUsers
 * @property \App\Model\Table\MapUploadsTable|\Cake\ORM\Association\HasMany $MapUploads
 * @property \App\Model\Table\SystemfailuresTable|\Cake\ORM\Association\HasMany $Systemfailures
 * @property \App\Model\Table\UsersToAutoreportsTable|\Cake\ORM\Association\HasMany $UsersToAutoreports
 * @property \App\Model\Table\UsersToContainersTable|\Cake\ORM\Association\HasMany $UsersToContainers
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
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
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
            'through' => 'ContainersUsersMemberships',
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
            ->requirePresence('containers', 'create', __('You have to choose at least one option.'))
            ->allowEmptyString('containers', false)
            ->multipleOptions('containers', [
                'min' => 1
            ], __('You have to choose at least one option.'));

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->allowEmptyString('status', false);

        $validator
            ->integer('usergroup_id')
            ->requirePresence('usergroup_id', 'create')
            ->allowEmptyString('usergroup_id', false);

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->allowEmptyString('email', false);

        $validator
            ->scalar('firstname')
            ->maxLength('firstname', 100)
            ->requirePresence('firstname', 'create')
            ->allowEmptyString('firstname', false);

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 100)
            ->requirePresence('lastname', 'create')
            ->allowEmptyString('lastname', false);

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
            ->allowEmptyString('samaccountname');

        $validator
            ->scalar('ldap_dn')
            ->maxLength('ldap_dn', 512)
            ->allowEmptyString('ldap_dn');

        $validator
            ->boolean('showstatsinmenu')
            ->requirePresence('showstatsinmenu', 'create')
            ->allowEmptyString('showstatsinmenu', false);

        $validator
            ->integer('dashboard_tab_rotation')
            ->requirePresence('dashboard_tab_rotation', 'create')
            ->allowEmptyString('dashboard_tab_rotation', false);

        $validator
            ->integer('paginatorlength')
            ->requirePresence('paginatorlength', 'create')
            ->allowEmptyString('paginatorlength', false);

        $validator
            ->boolean('recursive_browser')
            ->requirePresence('recursive_browser', 'create')
            ->allowEmptyString('recursive_browser', false);

        $validator
            ->scalar('password')
            ->maxLength('password', 45)
            ->requirePresence('password', 'create')
            ->allowEmptyString('password', false)
            ->regex('password', self::PASSWORD_REGEX, 'The password must consist of 6 alphanumeric characters and must contain at least one digit.');

        $validator->add('confirm_password',
            'compareWith', [
                'rule'    => ['compareWith', 'password'],
                'message' => 'Passwords not equal'
            ]);

      /*  $validator->add('current_password',
            'checkCurrentPassword', [
                'rule'    => 'checkCurrentPassword',
                'message' => 'The provided Password is different from your Password'
            ]);
*/
        return $validator;
    }


  /*  public function checkCurrentPassword($check, array $context){
        debug($check);
        debug($context);
        $pass = Security::hash($context['User']['current_password'], null, true);
        return $pass == Security::hash($context['User']['current_password'], null, true);
    }
*/
    /**
     * Password validation regex.
     */
    const PASSWORD_REGEX = '/^(?=.*\d).{6,}$/i';

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['usergroup_id'], 'Usergroups'));

        return $rules;
    }

    /**
     * @param $event
     * @param $entity
     * @param $options
     * @return bool
     */
    public function beforeSave($event, $entity, $options) {
        if (!empty($entity->password)) {
            //$entity->password = Security::hash($entity->password, null, true);
            $this->getPasswordHash($entity->password);
            return false;
        }
        return true;
    }

    /**
     * @param $str
     * @return string
     */
    public function getPasswordHash($str){
        return Security::hash($str, null, true);
    }



    /**
     * @param $rights
     * @param null $PaginateOMat
     * @return array
     */
    public function getUsers($rights, UsersFilter $usersFilter, $PaginateOMat = null) {
        $query = $this->find()
            ->disableHydration()
            ->contain(['Containers', 'Usergroups'])
            ->matching('Containers')
            ->order(['full_name' => 'asc'])
            ->where([
                'ContainersUsersMemberships.container_id IN' => $rights,
                $usersFilter->indexFilter()
            ])
            ->select(function (Query $query) {
                return [
                    'Users.id',
                    'Users.email',
                    'Users.company',
                    'Users.phone',
                    'Users.status',
                    'Users.samaccountname',
                    'Usergroups.id',
                    'Usergroups.name',
                    'ContainersUsersMemberships.container_id',
                    'full_name' => $query->func()->concat([
                        'Users.firstname' => 'literal',
                        ' ',
                        'Users.lastname'  => 'literal'
                    ])
                ];
            })
            ->group([
                'Users.id'
            ]);
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
     * @param $userId
     * @param $rights
     * @return array
     */
    public function getUser($userId, $rights) {
        $query = $this->find()
            ->disableHydration()
            ->contain(['Containers'])
            ->matching('Containers')
            ->where([
                'Users.id'                                   => $userId,
                'ContainersUsersMemberships.container_id IN' => $rights
            ])
            ->select(function (Query $query) {
                return [
                    'Users.id',
                    'Users.email',
                    'Users.company',
                    'Users.status',
                    'Users.samaccountname',
                    'Users.usergroup_id',
                    //'Users.password',
                    'Users.firstname',
                    'Users.lastname',
                    'Users.position',
                    'Users.phone',
                    'Users.timezone',
                    'Users.dateformat',
                    'Users.showstatsinmenu',
                    'Users.dashboard_tab_rotation',
                    'Users.paginatorlength',
                    'Users.recursive_browser',
                    'ContainersUsersMemberships.container_id',
                    'full_name' => $query->func()->concat([
                        'Users.firstname' => 'literal',
                        ' ',
                        'Users.lastname'  => 'literal'
                    ])
                ];
            })
            ->group([
                'Users.id'
            ]);
        if (!is_null($query)) {
            return $query->first();
        }
        return [];

    }


    /**
     * @param null $userId
     * @return array|int
     */
    public function getUserStatus($userId = null) {
        if (!is_null($userId)) {
            //return state for the given user ID
            return $this->get($userId)->status;

        } else {
            //no user ID so return all possible states
            return [];
        }
    }

    /**
     * Saving additional data to through table
     * table key is the main table which is associated with this model, not the 'through' table
     * ie:
     * users is associated with containers through ContainersUsersMemberships
     * in save method: $this->request->data['containers'] = containerPermissionsForSave($myKeyValueData)
     * @param array $containerPermissions
     * @return array
     */
    public function containerPermissionsForSave($containerPermissions = []) {
        //ContainersUsersMemberships
        return array_map(function ($containerId, $permissionLevel) {
            return [
                'id'        => $containerId,
                '_joinData' => [
                    'permission_level' => $permissionLevel
                ]
            ];
        },
            array_keys($containerPermissions),
            $containerPermissions
        );
    }

    /**
     * @param $containers
     * @return array
     */
    public function containerPermissionsForAngular($containers) {
        if (empty($containers)) {
            return [];
        }
        $ret = [];
        foreach ($containers as $container) {
            $ret['ContainersUsersMemberships'][$container['id']] = $container['_joinData']['permission_level'];
            $ret['containers']['_ids'][] = $container['id'];
        }
        return $ret;
    }

    /**
     * @param null $userId
     * @param $rights
     * @return array
     */
    public function getUserWithContainerPermission($userId = null, $rights) {
        $user = $this->getUser($userId, $rights);
        $containerPermissions = [];
        if (!empty($user['containers'])) {
            $containerPermissions = $this->containerPermissionsForAngular($user['containers']);
            $user = array_merge($user, $containerPermissions);
        }
        return $user;
    }

    public function getUserByEmail($email = null) {
        $query = $this->find()
            ->disableHydration()
            ->where([
                'Users.email'  => $email,
                'Users.status' => 1,
            ])
            ->select([
                'Users.id',
                'Users.email',
                'Users.company',
                'Users.status',
                'Users.samaccountname',
                'Users.usergroup_id',
                //'Users.password',
                'Users.firstname',
                'Users.lastname',
                'Users.position',
                'Users.phone',
                'Users.timezone',
                'Users.dateformat',
                'Users.showstatsinmenu',
                'Users.dashboard_tab_rotation',
                'Users.paginatorlength',
                'Users.recursive_browser',
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
        $char = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
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
        //Format: https://secure.php.net/manual/en/function.strftime.php
        return [
            1 => '%B %e, %Y %H:%M:%S',
            2 => '%m-%d-%Y  %H:%M:%S',
            3 => '%m-%d-%Y  %H:%M',
            4 => '%m-%d-%Y  %l:%M:%S %p',
            5 => '%H:%M:%S  %m-%d-%Y',

            6  => '%e %B %Y, %H:%M:%S',
            7  => '%d.%m.%Y - %H:%M:%S',
            9  => '%d.%m.%Y - %l:%M:%S %p',
            10 => '%H:%M:%S - %d.%m.%Y', //Default date format
            11 => '%H:%M - %d.%m.%Y',

            12 => '%Y-%m-%d %H:%M',
            13 => '%Y-%m-%d %H:%M:%S'
        ];
    }
}
