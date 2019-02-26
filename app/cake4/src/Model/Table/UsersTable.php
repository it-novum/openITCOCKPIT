<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
            ->integer('status')
            ->requirePresence('status', 'create')
            ->allowEmptyString('status', false);

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->allowEmptyString('email', false);

        $validator
            ->scalar('password')
            ->maxLength('password', 45)
            ->requirePresence('password', 'create')
            ->allowEmptyString('password', false);

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
            ->integer('recursive_browser')
            ->requirePresence('recursive_browser', 'create')
            ->allowEmptyString('recursive_browser', false);

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['usergroup_id'], 'Usergroups'));

        return $rules;
    }

    public function getUsers($rights, $PaginateOMat = null) {
        //$this->loadModel('Container');
        /*$options = [
            'recursive'  => -1,
            'order'      => [
                'User.full_name' => 'asc',
            ],
            'joins'      => [
                [
                    'table'      => 'users_to_containers',
                    'type'       => 'LEFT',
                    'alias'      => 'UsersToContainer',
                    'conditions' => 'UsersToContainer.user_id = User.id',
                ],
                [
                    'table'      => 'usergroups',
                    'type'       => 'LEFT',
                    'alias'      => 'Usergroup',
                    'conditions' => 'Usergroup.id = User.usergroup_id',
                ],
            ],
            'conditions' => [
                'UsersToContainer.container_id' => $rights,
            ],
            'fields'     => [
                'User.id',
                'User.email',
                'User.company',
                'User.phone',
                'User.status',
                'User.full_name',
                'User.samaccountname',
                'Usergroup.id',
                'Usergroup.name',
                'UsersToContainer.container_id',
            ],
            'group'      => [
                'User.id',
            ],
        ];*/
//debug($rights);
        /*debug($this->find('all')->disableHydration()->toArray());
        die();*/

        $query = $this->find()
            ->disableHydration()
            ->contain('Containers')
            ->matching('Containers')
            /* ->select([
                 'Users.id',
                 'Users.email',
                 'Users.company',
                 'Users.phone',
                 'Users.status',
                 'Users.samaccountname',
                 //  'Usergroups.id',
                 //  'Usergroups.name',
                 'ContainersUsersMemberships.container_id',
             ])*/
            ->order(['full_name' => 'asc'])
            ->where([
                'ContainersUsersMemberships.container_id IN' => $rights
            ])
            ->select(function (Query $query) {
                return [
                    'Users.id',
                    'Users.email',
                    'Users.company',
                    'Users.phone',
                    'Users.status',
                    'Users.samaccountname',
                    //  'Usergroups.id',
                    //  'Usergroups.name',
                    'ContainersUsersMemberships.container_id',
                    'full_name' => $query->func()->concat([
                        'Users.firstname' => 'literal',
                        ' ',
                        'Users.lastname'  => 'literal'
                    ])
                ];
            })
            /*  ->join([
                      [
                          'table'      => 'users_to_containers',
                          'type'       => 'LEFT',
                          'alias'      => 'UsersToContainer',
                          'conditions' => 'UsersToContainer.user_id = User.id',
                      ],
                  [
                      'table'      => 'usergroups',
                      'type'       => 'LEFT',
                      'alias'      => 'Usergroup',
                      'conditions' => 'Usergroup.id = User.usergroup_id',
                  ],
              ])*/
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
}
