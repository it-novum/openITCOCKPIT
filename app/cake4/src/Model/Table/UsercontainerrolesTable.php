<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\UsercontainerrolesFilter;


/**
 * Usercontainerroles Model
 *
 * @property \App\Model\Table\UsercontainerrolesToContainersTable|\Cake\ORM\Association\HasMany $UsercontainerrolesToContainers
 * @property \App\Model\Table\UsersToUsercontainerrolesTable|\Cake\ORM\Association\HasMany $UsersToUsercontainerroles
 *
 * @method \App\Model\Entity\Usercontainerrole get($primaryKey, $options = [])
 * @method \App\Model\Entity\Usercontainerrole newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Usercontainerrole[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Usercontainerrole|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Usercontainerrole saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Usercontainerrole patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Usercontainerrole[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Usercontainerrole findOrCreate($search, callable $callback = null, $options = [])
 */
class UsercontainerrolesTable extends Table {
    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) :void {
        parent::initialize($config);

        $this->setTable('usercontainerroles');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('UsersToUsercontainerroles', [
            'foreignKey' => 'usercontainerrole_id'
        ]);

        $this->belongsToMany('Containers', [
            'through'          => 'ContainersUsercontainerrolesMemberships',
            'className'        => 'Containers',
            'foreignKey'       => 'usercontainerrole_id',
            'targetForeignKey' => 'container_id',
            'joinTable'        => 'usercontainerroles_to_containers'
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
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', null, false);

        $validator
            ->requirePresence('containers', true, __('You have to choose at least one container.'))
            ->allowEmptyString('containers', null, false)
            ->multipleOptions('containers', [
                'min' => 1
            ], __('You have to choose at least one container.'));

        return $validator;
    }

    /**
     * @param $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Usercontainerroles.id' => $id]);
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
        //ContainersUsercontainerrolesMemberships

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


    /**
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getUsercontainerrolesAsList($MY_RIGHTS = []) {
        if (!is_array($MY_RIGHTS)) {
            $MY_RIGHTS = [$MY_RIGHTS];
        }

        $query = $this->find()
            ->select([
                'Usercontainerroles.id',
                'Usercontainerroles.name'
            ])
            ->contain('Containers')
            ->matching('Containers')
            ->where([
                'ContainersUsercontainerrolesMemberships.container_id IN' => $MY_RIGHTS
            ])
            ->group([
                'Usercontainerroles.id'
            ])
            ->order([
                'Usercontainerroles.name' => 'asc'
            ])
            ->disableHydration();

        $result = [];
        foreach ($query->toArray() as $record) {
            $result[$record['id']] = $record['name'];
        }

        return $result;
    }

    /**
     * @param UsercontainerrolesFilter $UsercontainerrolesFilter
     * @param PaginateOMat|null $PaginateOMat
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getUsercontainerRolesIndex(UsercontainerrolesFilter $UsercontainerrolesFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        if (!is_array($MY_RIGHTS)) {
            $MY_RIGHTS = [$MY_RIGHTS];
        }

        $query = $this->find()
            ->disableHydration()
            ->contain([
                'Containers'
            ])
            ->matching('Containers')
            ->where([
                'ContainersUsercontainerrolesMemberships.container_id IN' => $MY_RIGHTS,
                $UsercontainerrolesFilter->indexFilter()
            ])
            ->order($UsercontainerrolesFilter->getOrderForPaginator('Usercontainerroles.name', 'asc'))
            ->group([
                'Usercontainerroles.id'
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
    public function getUserContainerRoleForEdit($id) {
        $query = $this->find()
            ->where([
                'Usercontainerroles.id' => $id
            ])
            ->contain([
                'Containers',
            ])
            ->disableHydration()
            ->first();


        $usercontainerrole = $query;

        $usercontainerrole['containers'] = [
            '_ids' => Hash::extract($query, 'containers.{n}.id')
        ];


        //Build up data struct for radio inputs
        $usercontainerrole['ContainersUsercontainerrolesMemberships'] = [];
        foreach ($query['containers'] as $container) {
            //Cast permission_level to string for AngularJS...
            $usercontainerrole['ContainersUsercontainerrolesMemberships'][$container['id']] = (string)$container['_joinData']['permission_level'];
        }

        return [
            'Usercontainerrole' => $usercontainerrole
        ];
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getContainerPermissionsByUserContainerRoleIds($ids = []) {
        $query = $this->find()
            ->contain('Containers')
            ->where([
                'Usercontainerroles.id IN' => $ids
            ])
            ->disableHydration()
            ->all();

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $result = [];
        foreach ($query->toArray() as $record) {
            foreach ($record['containers'] as $index => $container) {
                $record['containers'][$index]['path'] = $ContainersTable->getPathByIdAsString($container['id']);
            }
            $result[$record['id']] = $record;
        }

        return $result;
    }
}
