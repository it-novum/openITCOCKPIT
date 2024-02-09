<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use App\Model\Entity\DashboardTab;
use App\Model\Entity\User;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\DashboardTabsFilter;

/**
 * DashboardTabs Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\WidgetsTable&\Cake\ORM\Association\HasMany $Widgets
 *
 * @method \App\Model\Entity\DashboardTab get($primaryKey, $options = [])
 * @method \App\Model\Entity\DashboardTab newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DashboardTab[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DashboardTab|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DashboardTab saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DashboardTab patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DashboardTab[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DashboardTab findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DashboardTabsTable extends Table {

    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('dashboard_tabs');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');


        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType'   => 'INNER',
        ]);

        $this->hasMany('Widgets', [
            'foreignKey' => 'dashboard_tab_id',
            'dependent'  => true
        ]);
        $this->belongsToMany('Usergroups', [
            'className'        => 'Usergroups',
            'joinTable'        => 'usergroups_to_dashboard_tabs',
            'foreignKey'       => 'dashboard_tab_id',
            'targetForeignKey' => 'usergroup_id',
            'saveStrategy'     => 'replace',
            'dependent'        => true
        ]);
        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'LEFT'
        ]);
        $this->belongsToMany('AllocatedUsers', [
            'className'        => 'Users',
            'joinTable'        => 'users_to_dashboard_tabs',
            'foreignKey'       => 'dashboard_tab_id',
            'targetForeignKey' => 'user_id',
            'saveStrategy'     => 'replace',
            'dependent'        => true,
            'joinType'         => 'LEFT'
        ]);

    }

    /**
     * @param DashboardTabsFilter $DashboardTabsFilter
     * @param PaginateOMat|null $PaginateOMat
     * @return array
     */
    public function getDashboardTabsIndex(DashboardTabsFilter $DashboardTabsFilter, ?PaginateOMat $PaginateOMat = null) {
        $query = $this->find();
        $query
            ->select([
                'id',
                'name',
                'flags',
                'Users.firstname',
                'Users.lastname',
                'full_name' => $query->func()->concat([
                    'Users.firstname' => 'literal',
                    ' ',
                    'Users.lastname'  => 'literal'
                ])

            ])
            ->contain([
                'Usergroups'     => function (Query $query) {
                    return $query->select([
                        'Usergroups.id',
                        'Usergroups.name'
                    ]);
                },
                'AllocatedUsers' => function (Query $query) {
                    return $query->select([
                        'AllocatedUsers.id',
                        'full_name' => $query->func()->concat([
                            'AllocatedUsers.firstname' => 'literal',
                            ' ',
                            'AllocatedUsers.lastname'  => 'literal'
                        ])
                    ]);
                },
            ])
            ->contain('Users')
            ->whereNull('source_tab_id')
            ->disableHydration();
        $where = $DashboardTabsFilter->indexFilter();
        if (isset($where['full_name LIKE'])) {
            $having = [];
            $having['full_name LIKE'] = $where['full_name LIKE'];
            unset($where['full_name LIKE']);
            $query->having($having);
        }

        $query->order(
            array_merge(
                $DashboardTabsFilter->getOrderForPaginator('DashboardTabs.name', 'asc'),
                ['DashboardTabs.name' => 'asc']
            )
        )->where($where);

        if ($PaginateOMat === null) {
            $result = $query->toArray();
        } else if ($PaginateOMat->useScroll()) {
            $result = $this->scrollCake4($query, $PaginateOMat->getHandler());
        } else {
            $result = $this->paginateCake4($query, $PaginateOMat->getHandler());
        }
        return $result;
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
            ->integer('position')
            ->requirePresence('position', 'create')
            ->notEmptyString('position');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->boolean('shared')
            ->notEmptyString('shared');

        $validator
            ->integer('check_for_updates')
            ->allowEmptyString('check_for_updates');

        $validator
            ->integer('last_update')
            ->allowEmptyString('last_update');

        $validator
            ->boolean('locked')
            ->notEmptyString('locked');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['DashboardTabs.id' => $id]);
    }

    /**
     * @param int $userId
     * @param array $options
     * @return \Cake\Datasource\EntityInterface
     */
    public function createNewTab($userId, $options = []) {
        $_options = [
            'name'              => __('Default'),
            'shared'            => 0,
            'source_tab_id'     => null,
            'check_for_updates' => 0,
            'position'          => $this->getNextPosition($userId),
        ];
        $options = Hash::merge($_options, $options);

        $entity = $this->newEmptyEntity();
        $entity->set('user_id', $userId);
        foreach ($options as $key => $value) {
            $entity->set($key, $value);
        }

        $this->save($entity);
        return $entity;
    }

    /**
     * @param $userId
     * @return int
     */
    public function getNextPosition($userId): int {
        try {
            $result = $this->find()
                ->where([
                    'DashboardTabs.user_id' => $userId
                ])
                ->order([
                    'DashboardTabs.position' => 'DESC'
                ])
                ->firstOrFail();

            return ($result->get('position') + 1);
        } catch (RecordNotFoundException $e) {
            return 1;
        }
    }

    /**
     * @param User $UserEntity
     * @return bool
     */
    public function hasUserATab(User $UserEntity) {
        try {
            $result = $this->find()
                ->where([
                    'DashboardTabs.user_id' => $UserEntity->id,
                ])
                ->first();
            if (!empty($result)) {
                return true;
            }

            // Check for allocated Dashboards!
            /** @var UsersTable $UsersTable */
            $UsersTable = TableRegistry::getTableLocator()->get('Users');

            // User has an allocated dashboard?
            $result = $UsersTable->getAllocatedTabsByUserId($UserEntity->id);
            if (!empty($result)) {
                return true;
            }


            // Usergroup has an allocated dashboard?
            // todo does this work with LDAP?
            /** @var UsergroupsTable $UsergroupsTable */
            $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');
            $result = $UsergroupsTable->getAllocatedTabsByUsergroupId($UserEntity->usergroup_id);

            if (!empty ($result)) {
                return true;
            }
        } catch (RecordNotFoundException $e) {
            return false;
        }
    }

    /**
     * @param User $UserEntity
     * @return array|null
     */
    public function getAllTabsByUserId(User $UserEntity) {
        $forJs = [];

        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');

        /** @var int[] $allocatedTabIds */
        $allocatedTabIds = [];

        // Add Usergroup Allocations.
        $allocatedTabIds += $UsergroupsTable->getAllocatedTabsByUsergroupId($UserEntity->usergroup_id);

        // Add User Allocations.
        $allocatedTabIds += $UsersTable->getAllocatedTabsByUserId($UserEntity->id);

        // Make unique, just for sanity.
        $allocatedTabIds = array_unique($allocatedTabIds);

        // Traverse the allocations and copy / update the allocated tabs.
        foreach ($allocatedTabIds as $allocatedTabId) {
            try {
                $Entity = $this->get($allocatedTabId);
                if ($Entity->user_id === $UserEntity->id) {
                    continue;
                }
                $UsersTable->get($Entity->user_id);
            } catch (RecordNotFoundException $exception) {
                // If the author is gone, just for sake of stability, remove the entire tab.
                // This should not happen without fiddling the database.
                // But... better safe than sorry.
                if (isset($Entity)) {
                    $this->delete($Entity);
                }
                continue;
            }

            // Find Copy
            $copy = $this->findAllocatedTab($UserEntity->id, $allocatedTabId);

            if (empty($copy)) {
                // Create new copy
                $this->copyAllocatedTab($allocatedTabId, $UserEntity->id);

                // Find Copy (again, duh...)
                $copy = $this->findAllocatedTab($UserEntity->id, $allocatedTabId);

            } else if ($copy['modified']->getTimestamp() <= $Entity->modified->getTimestamp()) {
                // or maybe update the existing copy if needed.
                $this->updateAllocatedTab($allocatedTabId, $copy['id']);
            }
        }

        $result = $this->find()
            ->where([
                'DashboardTabs.user_id' => $UserEntity->id
            ])
            ->order([
                'DashboardTabs.position' => 'ASC',
            ])
            ->disableHydration()
            ->all();

        foreach ($result as $row) {
            $forJs[] = [
                'id'                => (int)$row['id'],
                'position'          => (int)$row['position'],
                'name'              => $row['name'],
                'shared'            => (bool)$row['shared'],
                'source_tab_id'     => (int)$row['source_tab_id'],
                'check_for_updates' => (bool)$row['check_for_updates'],
                'last_update'       => (int)$row['last_update'],
                'locked'            => (bool)$row['locked'],
                'modified'          => $row['modified'],
                'flags'             => (int)$row['flags'],
                'isPinned'          => ($row['flags'] & DashboardTab::FLAG_ALLOCATED) && ($row['flags'] & DashboardTab::FLAG_PINNED),
                'isReadonly'        => (bool)($row['flags'] & DashboardTab::FLAG_ALLOCATED),
                'source'            => (bool)($row['flags'] & DashboardTab::FLAG_ALLOCATED) ? 'ALLOCATED' : ''
            ];
        }


        return $forJs;
    }

    /**
     * I will return the entire copy of the given $allocatedTabId for the given $userId.
     * @param int $userId
     * @param int $allocatedTabId
     * @return array
     */
    public function findAllocatedTab(int $userId, int $allocatedTabId): array {
        return $this
            ->find()
            ->where([
                'user_id'       => $userId,
                'source_tab_id' => $allocatedTabId,
                'flags & '      => DashboardTab::FLAG_ALLOCATED
            ])
            ->disableHydration()
            ->first() ?? [];
    }

    /**
     * @return array|null
     */
    public function getSharedTabs() {
        $query = $this->find()
            ->select([
                'DashboardTabs.id',
                'DashboardTabs.position',
                'DashboardTabs.name',
                'DashboardTabs.shared',
                'DashboardTabs.source_tab_id',
                'DashboardTabs.check_for_updates',
                'DashboardTabs.last_update',
                'DashboardTabs.locked',
                'Users.firstname',
                'Users.lastname',
            ])
            ->join([
                [
                    'table'      => 'users',
                    'alias'      => 'Users',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Users.id = DashboardTabs.user_id',
                    ],
                ]
            ])
            ->where([
                'DashboardTabs.shared' => 1
            ])
            ->disableHydration()
            ->all();

        if ($query->isEmpty()) {
            return [];
        }

        $forJs = [];
        foreach ($query->toArray() as $row) {
            $forJs[] = [
                'id'                => (int)$row['id'],
                'position'          => (int)$row['position'],
                'name'              => sprintf(
                    '%s, %s/%s',
                    $row['Users']['firstname'],
                    $row['Users']['lastname'],
                    $row['name']
                ),
                'shared'            => (bool)$row['shared'],
                'source_tab_id'     => (int)$row['source_tab_id'],
                'check_for_updates' => (bool)$row['check_for_updates'],
                'last_update'       => (int)$row['last_update'],
                'locked'            => (bool)$row['locked']
            ];
        }

        return $forJs;
    }

    /**
     * @param $userId
     * @param $tabId
     * @return array|null
     */
    public function getWidgetsForTabByUserIdAndTabId($userId, $tabId) {
        $query = $this->find()
            ->contain('Widgets', function (Query $query) {
                $query->order([
                    'Widgets.col' => 'ASC'
                ]);
                return $query;
            })
            ->contain('AllocatedUsers')
            ->contain('Usergroups')
            ->where([
                'DashboardTabs.id'      => $tabId,
                'DashboardTabs.user_id' => $userId
            ])
            ->disableHydration()
            ->first();

        if ($query === null) {
            return [];
        }

        $result = $this->formatFirstResultAsCake2($query);


        $result['Usergroup'] = [
            '_ids' => Hash::extract($result, 'Usergroup.{n}.id')
        ];
        $result['allocated_users'] = [
            '_ids' => Hash::extract($result, 'DashboardTab.allocated_users.{n}.id')
        ];

        unset($result['DashboardTab']['allocated_users']);

        return $result;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getTabByIdAsCake2($id) {
        $result = $this->find()
            ->where([
                'DashboardTabs.id' => $id
            ])
            ->disableHydration()
            ->first();
        return $this->formatFirstResultAsCake2($result);
    }


    /**
     * @param int $id
     * @param int $userId
     * @return \App\Model\Entity\DashboardTab
     * @throws RecordNotFoundException
     */
    public function copySharedTab($id, $userId) {
        $sourceTab = $this->find()
            ->where([
                'DashboardTabs.id'     => $id,
                'DashboardTabs.shared' => 1
            ])
            ->contain([
                'Widgets'
            ])
            ->firstOrFail();

        $widgets = [];
        foreach ($sourceTab->get('widgets') as $widget) {
            $widgets[] = [
                'type_id'    => $widget->get('type_id'),
                'host_id'    => $widget->get('host_id'),
                'service_id' => $widget->get('service_id'),
                'row'        => $widget->get('row'),
                'col'        => $widget->get('col'),
                'width'      => $widget->get('width'),
                'height'     => $widget->get('height'),
                'title'      => $widget->get('title'),
                'color'      => $widget->get('color'),
                'directive'  => $widget->get('directive'),
                'icon'       => $widget->get('icon'),
                'json_data'  => $widget->get('json_data')
            ];
        }

        $newTab = $this->newEntity([
            'name'              => $sourceTab->get('name'),
            'locked'            => $sourceTab->get('locked'),
            'user_id'           => $userId,
            'position'          => $this->getNextPosition($userId),
            'shared'            => 0,
            'source_tab_id'     => $id,
            'check_for_updates' => 1,
            'last_update'       => time(),
            'widgets'           => $widgets
        ]);

        $this->save($newTab);
        return $newTab;
    }


    /**
     * @param int $tabId
     * @param int $userId
     * @throws RecordNotFoundException
     */
    public function copyAllocatedTab(int $tabId, int $userId): void {
        $sourceTab = $this->find()
            ->where([
                'DashboardTabs.id' => $tabId,
            ])
            ->contain([
                'Widgets'
            ])
            ->firstOrFail();

        $widgets = [];
        foreach ($sourceTab->get('widgets') as $widget) {
            $widgets[] = [
                'type_id'    => $widget->get('type_id'),
                'host_id'    => $widget->get('host_id'),
                'service_id' => $widget->get('service_id'),
                'row'        => $widget->get('row'),
                'col'        => $widget->get('col'),
                'width'      => $widget->get('width'),
                'height'     => $widget->get('height'),
                'title'      => $widget->get('title'),
                'color'      => $widget->get('color'),
                'directive'  => $widget->get('directive'),
                'icon'       => $widget->get('icon'),
                'json_data'  => $widget->get('json_data'),
            ];
        }

        $nextPosition = $this->getNextPosition($userId);
        // If tab is pinned, force negative position.
        if ($sourceTab->get('flags') & DashboardTab::FLAG_PINNED) {
            $nextPosition = -1;
        }
        $newTab = $this->newEntity([
            'name'              => $sourceTab->get('name'),
            'locked'            => true,
            'user_id'           => $userId,
            'position'          => $nextPosition,
            'shared'            => 0,
            'flags'             => $sourceTab->get('flags') + DashboardTab::FLAG_ALLOCATED,
            'source_tab_id'     => $tabId,
            'check_for_updates' => 0,
            'last_update'       => time(),
            'widgets'           => $widgets
        ]);

        $this->save($newTab);
    }


    /**
     * @param int $originalTabId
     * @param int $copyTabId
     * @return \App\Model\Entity\DashboardTab
     * @throws RecordNotFoundException
     */
    public function updateAllocatedTab(int $originalTabId, int $copyTabId) {
        $sourceTab = $this->find()
            ->where([
                'DashboardTabs.id' => $originalTabId,
            ])
            ->contain([
                'Widgets'
            ])
            ->firstOrFail();

        $widgets = [];
        foreach ($sourceTab->get('widgets') as $widget) {
            $widgets[] = [
                'type_id'    => $widget->get('type_id'),
                'host_id'    => $widget->get('host_id'),
                'service_id' => $widget->get('service_id'),
                'row'        => $widget->get('row'),
                'col'        => $widget->get('col'),
                'width'      => $widget->get('width'),
                'height'     => $widget->get('height'),
                'title'      => $widget->get('title'),
                'color'      => $widget->get('color'),
                'directive'  => $widget->get('directive'),
                'icon'       => $widget->get('icon'),
                'json_data'  => $widget->get('json_data')
            ];
        }

        /** @var WidgetsTable $WidgetsTable */
        $WidgetsTable = TableRegistry::getTableLocator()->get('widgets');
        $WidgetsTable->deleteAll(['dashboard_tab_id' => $copyTabId]);

        $Entity = $this->get($copyTabId);

        $nextPosition = $this->getNextPosition($Entity->user_id);
        if ($sourceTab->get('flags') & DashboardTab::FLAG_PINNED) {
            $nextPosition = -1;
        }
        $patch = [
            'name'              => $sourceTab->get('name'),
            'locked'            => $sourceTab->get('locked'),
            'shared'            => 0,
            'source_tab_id'     => $originalTabId,
            'position'          => $nextPosition,
            'check_for_updates' => 0,
            'last_update'       => time(),
            'widgets'           => $widgets,
            'flags'             => $sourceTab->get('flags') | DashboardTab::FLAG_ALLOCATED
        ];

        $Entity = $this->patchEntity($Entity, $patch);

        $this->save($Entity);
        return $Entity;
    }

    public function cleanup(int $dashboardTabId, array $newUserIds, array $newUsergroupIds): void {
        // Get normalized array of userIds where the $dashboardTabId will remain.
        $cleanUserIds = $this->fetchUserIdsToClean(
            $dashboardTabId,
            $newUserIds,
            $newUsergroupIds
        );
        // Early return
        if (empty ($cleanUserIds)) {
            return;
        }

        foreach ($this->findAllocations($dashboardTabId, $cleanUserIds) as $CopyEntity) {
            $copyPatch = [
                'flags'         => $CopyEntity->flags & DashboardTab::FLAG_BLANK,
                'source_tab_id' => null
            ];
            $CopyEntity = $this->patchEntity($CopyEntity, $copyPatch);
            $this->save($CopyEntity);
        }
    }

    /**
     * I will find all allocated instances of the given $dashboardTabId.
     * If you pass the $userIds, I will filter for those.
     *
     * @param int $dashboardTabId
     * @param array $userIds
     *
     * @return \Cake\Datasource\ResultSetInterface
     */
    public function findAllocations(int $dashboardTabId, array $userIds = []) {
        $query = $this->find()->where([
            'source_tab_id' => $dashboardTabId,
            'flags & '      => DashboardTab::FLAG_ALLOCATED
        ]);
        if (!empty($userIds)) {
            $query->where(['user_id IN' => $userIds]);
        }

        return $query->all();
    }

    /**
     * I will return a set of UserIds whose copies of given $dashboardTabId will remain after cleanup.
     * This also regards the UserGroups.
     * @param int $dashboardTabId
     * @param array $newUserIds
     * @param array $newUsergroupIds
     * @return array
     */
    public function fetchUserIdsToClean(int $dashboardTabId, array $newUserIds, array $newUsergroupIds): array {

        // Fetch current setup.
        $Obj = $this->find()
            ->where(['id' => $dashboardTabId])
            ->contain('AllocatedUsers')
            ->contain('Usergroups')
            ->toArray();

        $currentUserIds = Hash::extract($Obj[0], 'allocated_users.{n}.id');
        $currentGroupIds = Hash::extract($Obj[0], 'usergroups.{n}.id');


        $cleanUserIds = [];

        // Traverse users and see if any one is mentioned in the new users.
        foreach ($currentUserIds as $userId) {
            if (!in_array($userId, $newUserIds)) {
                $cleanUserIds[] = $userId;
            }
        }


        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        // Traverse usergroups and do the same.
        foreach ($currentGroupIds as $usergroupId) {
            if (in_array($usergroupId, $newUsergroupIds)) {
                continue;
            }

            $userIds = $UsersTable->getUserIdsByUsergroupId($usergroupId);

            foreach ($userIds as $userId) {
                // Check if the user is added explicitly.
                // Check if the assignment already is found
                if (in_array($userId, $newUserIds)) {
                    continue;
                }
                $cleanUserIds[] = $userId;
            }
        }
        return array_unique($cleanUserIds);
    }

    public function getDashboardTabForAllocate($id) {
        $query = $this->find();
        $query->select([
            'id',
            'name',
            'flags',
            'container_id',
            'user_id',
        ])->contain([
            'Usergroups'     => function (Query $query) {
                return $query->select([
                    'Usergroups.id'
                ]);
            },
            'AllocatedUsers' => function (Query $query) {
                return $query->select([
                    'AllocatedUsers.id'
                ]);
            },
            'Users'
        ])->where(['DashboardTabs.id' => $id])
            ->disableHydration();

        $dashboard = $query->firstOrFail();

        $dashboard['allocated_users'] = [
            '_ids' => Hash::extract($dashboard['allocated_users'], '{n}.id')
        ];
        $dashboard['usergroups'] = [
            '_ids' => Hash::extract($dashboard['usergroups'], '{n}.id')
        ];

        return $dashboard;
    }
}
