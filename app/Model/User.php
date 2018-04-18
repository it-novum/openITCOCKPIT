<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

class User extends AppModel
{

    //public $actsAs = [
    //	'Acl' => [
    //		'type' => 'requester',
    //	]
    //];

    public $hasMany = [
        'ContainerUserMembership' => [
            'className'  => 'ContainerUserMembership',
            'foreignKey' => 'user_id',
            'dependent'  => true,
        ],
        'Apikey' => [
            'className'  => 'Apikey',
            'foreignKey' => 'user_id',
            'dependent'  => true,
        ],
    ];

    public $belongsTo = ['Usergroup'];

    /**
     * Password validation regex.
     */
    const PASSWORD_REGEX = '/^(?=.*\d).{6,}$/i';

    /**
     * @var string
     */
    public $displayField = 'full_name';

    /**
     * @param mixed      $id
     * @param string     $table
     * @param DataSource $ds
     */
    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->virtualFields['full_name'] = sprintf('CONCAT(%s.firstname, " ", %s.lastname)', $this->alias, $this->alias);
    }


    public $validate = [
        'status'               => [
            'notBlank' => [
                'rule'    => 'notBlank',
                'message' => 'users.illegal_status',
            ],
        ],
        'Container'            => [
            'multiple' => [
                'rule'     => ['multiple', ['min' => 1]],
                'message'  => 'Please select at least one container',
                'required' => true,
            ],
        ],
        'email'                => [
            'validEmailRule' => [
                'rule'     => ['email'],
                'message'  => 'Invalid email address',
                'required' => true,
            ],
            'isUnique'       => [
                'rule'    => 'isUnique',
                'message' => 'This email address has already been taken.',
            ],
        ],
        'firstname'            => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'lastname'             => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'current_password'     => [
            'rule'    => 'checkCurrentPassword',
            'message' => 'user_model.incorrect_password',
        ],
        'new_password'         => [
            'rule'    => ['custom', self::PASSWORD_REGEX],
            'message' => 'user_model.password_requirement_notice',
        ],
        'confirm_new_password' => [
            'rule'    => ['custom', self::PASSWORD_REGEX],
            'message' => 'user_model.password_requirement_notice',
        ],
        'usergroup_id'         => [
            'notZero' => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
    ];


    public function bindNode($user)
    {
        return [
            'model'       => 'Usergroup',
            'foreign_key' => $user['User']['usergroup_id'],
        ];
    }

    //public function parentNode(){
    //	if(!$this->id && empty($this->data)){
    //		return null;
    //	}
    //
    //	if(isset($this->data['User']['usergroup_id'])){
    //		$usergroupId = $this->data['User']['usergroup_id'];
    //	}else{
    //		$usergroupId = $this->field('usergroup_id');
    //	}
    //	if(!$usergroupId){
    //		return null;
    //	}
    //	return [
    //		'Usergroup' => [
    //			'id' => $usergroupId
    //		]
    //	];
    //}

    /**
     * checks if given password matches hash in database
     *
     * @param  array $data
     *
     * @return bool
     */
    public function checkCurrentPassword($data)
    {
        $this->id = AuthComponent::user('id');
        $password = $this->field('password');
        if (empty($data['current_password'])) {
            return false;
        }

        return (AuthComponent::password($data['current_password']) == $password);
    }

    /**
     * Used for changing the password of a user. Will validate the input fields.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return bool
     */
    public function changePassword($userId, array $data)
    {
        if (!$this->checkCurrentPassword($data['User'])) {
            $this->invalidate('current_password', 'The current password is not correct.');
        }
        unset($data['User']['current_password']);
        $this->id = null;
        $this->set($data);
        if ($data['User']['new_password'] !== $data['User']['new_password_repeat']) {
            $this->invalidate('new_password_repeat', 'Please make sure the this confirmation is identical to your new password');
        }

        if ($this->validates()) {
            $this->updateField($userId, 'password', AuthComponent::password($data['User']['new_password']));

            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns a map of the available admin user statuses
     * @return void
     */
    public static function getStates()
    {
        return Status::getMap(Status::ACTIVE, Status::SUSPENDED, Status::DELETED);
    }

    /**
     * Returns a map of the available admin user statuses
     * @return void
     */
    public static function getRoles()
    {
        return Types::getMap(
            Types::ROLE_ADMIN,
            Types::ROLE_EMPLOYEE
        );
    }

    /**
     * called before validating
     * @return bool
     */
    public function beforeValidate($options = [])
    {
        if (!empty($this->id) && empty($this->data['User']['new_password'])) {
            unset($this->data['User']['new_password'], $this->data['User']['confirm_new_password']);
        }

        return true;
    }

    /**
     * called before saving
     * @return true
     */
    public function beforeSave($options = [])
    {
        if (!empty($this->data['User']['new_password'])) {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['new_password']);
        }

        return true;
    }

    /**
     * checks if user with given id is soft deleted
     *
     * @param  int $id
     *
     * @return bool
     */
    public function softDeleted($id = null)
    {
        if (empty($id)) {
            return false;
        }
        $user = $this->find('first', [
            'conditions' => [
                'User.id'     => $id,
                'User.status' => Status::DELETED,
            ],
        ]);
        if (!empty($user)) {
            return true;
        } else {
            return false;
        }
    }

    public function usersByContainerId($container_ids, $type = 'all')
    {
        if (!is_array($container_ids)) {
            $container_ids = [$container_ids];
        }

        $container_ids = array_unique($container_ids);

        switch ($type) {
            case 'all':
                return $this->find('all', [
                    'contain'    => [],
                    'joins'      => [
                        [
                            'table'      => 'users_to_containers',
                            'alias'      => 'UserToContainer',
                            'type'       => 'INNER',
                            'conditions' => [
                                'UserToContainer.user_id = User.id',
                            ],
                        ],
                    ],
                    'fields'     => [
                        'User.id',
                        'User.firstname',
                        'User.lastname',
                        'UserToContainer.container_id',
                    ],
                    'conditions' => [
                        'UserToContainer.container_id' => $container_ids,
                    ],
                    'group'      => [
                        'User.id',
                    ],
                    'order'      => [
                        'User.lastname'  => 'ASC',
                        'User.firstname' => 'ASC',
                    ],
                ]);
                break;

            case 'list':
                $results = $this->find('all', [
                    'contain'    => [],
                    'joins'      => [
                        [
                            'table'      => 'users_to_containers',
                            'alias'      => 'UserToContainer',
                            'type'       => 'INNER',
                            'conditions' => [
                                'UserToContainer.user_id = User.id',
                            ],
                        ],
                    ],
                    'fields'     => [
                        'User.id',
                        'User.firstname',
                        'User.lastname',
                        'UserToContainer.container_id',
                    ],
                    'conditions' => [
                        'UserToContainer.container_id' => $container_ids,
                    ],
                    'group'      => [
                        'User.id',
                    ],
                    'order'      => [
                        'User.lastname'  => 'ASC',
                        'User.firstname' => 'ASC',
                    ],
                ]);

                $return = [];
                foreach ($results as $result) {
                    $return[$result['User']['id']] = $result['User']['lastname'].', '.$result['User']['firstname'];
                }

                return $return;
                break;
        }
    }

    //A user can have >= 1 tenants, due to multiple containers
    public function getTenantIds($id = null, $index = 'id')
    {
        if ($id === null) {
            $id = $this->id;
        }
        $Container = ClassRegistry::init('Container');
        $user = $this->findById($id);
        $tenants = [];
        foreach ($user['ContainerUserMembership'] as $_container) {
            foreach ($Container->getPath($_container['container_id']) as $subContainer) {
                if ($subContainer['Container']['containertype_id'] == CT_TENANT) {
                    $tenants[$subContainer['Container'][$index]] = $subContainer['Container']['name'];
                }
            }
        }

        return $tenants;
    }

    public function __delete($user, $userId)
    {
        if (is_numeric($user)) {
            $userId = $user;
            $user = $this->findById($userId);
        } else {
            $userId = $user['User']['id'];
        }

        if ($this->delete($user['User']['id'])) {
            return true;
        }

        return false;
    }
}
