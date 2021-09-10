<?php

namespace App\Model\Table;

use AutoreportModule\Model\Table\AutoreportsTable;
use Cake\Cache\Cache;
use Cake\Core\Plugin;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use GrafanaModule\Model\Table\GrafanaUserdashboardsTable;
use itnovum\openITCOCKPIT\Core\ContainerNestedSet;
use MapModule\Model\Table\MapsTable;

/**
 * Containers Model
 *
 * @property \App\Model\Table\ContainertypesTable|\Cake\ORM\Association\BelongsTo $Containertypes
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $ParentContainers
 * @property \App\Model\Table\AutomapsTable|\Cake\ORM\Association\HasMany $Automaps
 * @property \App\Model\Table\AutoreportsTable|\Cake\ORM\Association\HasMany $Autoreports
 * @property \App\Model\Table\CalendarsTable|\Cake\ORM\Association\HasMany $Calendars
 * @property \App\Model\Table\ChangelogsToContainersTable|\Cake\ORM\Association\HasMany $ChangelogsToContainers
 * @property \App\Model\Table\ContactgroupsTable|\Cake\ORM\Association\HasMany $Contactgroups
 * @property \App\Model\Table\ContactsToContainersTable|\Cake\ORM\Association\HasMany $ContactsToContainers
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\HasMany $ChildContainers
 * @property \App\Model\Table\GrafanaUserdashboardsTable|\Cake\ORM\Association\HasMany $GrafanaUserdashboards
 * @property \App\Model\Table\HostdependenciesTable|\Cake\ORM\Association\HasMany $Hostdependencies
 * @property \App\Model\Table\HostescalationsTable|\Cake\ORM\Association\HasMany $Hostescalations
 * @property \App\Model\Table\HostgroupsTable|\Cake\ORM\Association\HasMany $Hostgroups
 * @property \App\Model\Table\HostsTable|\Cake\ORM\Association\HasMany $Hosts
 * @property \App\Model\Table\HostsToContainersTable|\Cake\ORM\Association\HasMany $HostsToContainers
 * @property \App\Model\Table\HosttemplatesTable|\Cake\ORM\Association\HasMany $Hosttemplates
 * @property \App\Model\Table\IdoitObjectsTable|\Cake\ORM\Association\HasMany $IdoitObjects
 * @property \App\Model\Table\IdoitObjecttypesTable|\Cake\ORM\Association\HasMany $IdoitObjecttypes
 * @property \App\Model\Table\InstantreportsTable|\Cake\ORM\Association\HasMany $Instantreports
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\HasMany $Locations
 * @property \App\Model\Table\MapUploadsTable|\Cake\ORM\Association\HasMany $MapUploads
 * @property \App\Model\Table\MapsToContainersTable|\Cake\ORM\Association\HasMany $MapsToContainers
 * @property \App\Model\Table\MkagentsTable|\Cake\ORM\Association\HasMany $Mkagents
 * @property \App\Model\Table\NmapConfigurationsTable|\Cake\ORM\Association\HasMany $NmapConfigurations
 * @property \App\Model\Table\RotationsToContainersTable|\Cake\ORM\Association\HasMany $RotationsToContainers
 * @property \App\Model\Table\SatellitesTable|\Cake\ORM\Association\HasMany $Satellites
 * @property \App\Model\Table\ServicedependenciesTable|\Cake\ORM\Association\HasMany $Servicedependencies
 * @property \App\Model\Table\ServiceescalationsTable|\Cake\ORM\Association\HasMany $Serviceescalations
 * @property \App\Model\Table\ServicegroupsTable|\Cake\ORM\Association\HasMany $Servicegroups
 * @property \App\Model\Table\ServicetemplategroupsTable|\Cake\ORM\Association\HasMany $Servicetemplategroups
 * @property \App\Model\Table\ServicetemplatesTable|\Cake\ORM\Association\HasMany $Servicetemplates
 * @property \App\Model\Table\TenantsTable|\Cake\ORM\Association\HasMany $Tenants
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\HasMany $Timeperiods
 * @property \App\Model\Table\UsersToContainersTable|\Cake\ORM\Association\HasMany $UsersToContainers
 *
 * @method \App\Model\Entity\Container get($primaryKey, $options = [])
 * @method \App\Model\Entity\Container newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Container[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Container|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Container|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Container patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Container[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Container findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class ContainersTable extends Table {

    /**
     * @var null|array
     */
    private $containerCache = null;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('containers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree');

        $this->hasMany('Contactgroups', [
            'foreignKey'       => 'container_id',
            'cascadeCallbacks' => true
        ])->setDependent(true);

        $this->hasMany('Hostgroups', [
            'foreignKey'       => 'container_id',
            'cascadeCallbacks' => true
        ])->setDependent(true);

        $this->hasMany('Servicegroups', [
            'foreignKey'       => 'container_id',
            'cascadeCallbacks' => true
        ])->setDependent(true);

        $this->hasMany('Servicetemplategroups', [
            'foreignKey'       => 'container_id',
            'cascadeCallbacks' => true
        ])->setDependent(true);

        $this->hasMany('Locations', [
            'foreignKey'       => 'container_id',
            'cascadeCallbacks' => true
        ])->setDependent(true);

        $this->hasMany('MapsToContainers', [
            'foreignKey'       => 'container_id',
            'cascadeCallbacks' => true
        ]);

        $this->hasMany('Tenants', [
            'foreignKey'       => 'container_id',
            'cascadeCallbacks' => true
        ])->setDependent(true);

        //$this->belongsTo('ParentContainers', [
        //    'className' => 'Containers',
        //    'foreignKey' => 'parent_id'
        //]);

        /*
        $this->hasMany('Automaps', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Autoreports', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Calendars', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('ChangelogsToContainers', [
            'foreignKey' => 'container_id'
        ]);

        $this->hasMany('ContactsToContainers', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('ChildContainers', [
            'className' => 'Containers',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('GrafanaUserdashboards', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Hostdependencies', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Hostescalations', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Hostgroups', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Hosts', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('HostsToContainers', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Hosttemplates', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('IdoitObjects', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('IdoitObjecttypes', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Instantreports', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Locations', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('MapUploads', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Mkagents', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('NmapConfigurations', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('RotationsToContainers', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Satellites', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Servicedependencies', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Serviceescalations', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Servicetemplategroups', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Servicetemplates', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Timeperiods', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('UsersToContainers', [
            'foreignKey' => 'container_id'
        ]);
        */
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name', __('This field cannot be left blank.'), false)
            ->add('name', 'custom', [
                'rule'    => function ($value, $context) {
                    if (isset($context['data']['containertype_id']) && $context['data']['containertype_id'] == CT_TENANT) {
                        if (isset($context['data']['id'])) {
                            //In post data is an ID given
                            //May be an update of a Tenant container
                            $count = $this->find()
                                ->where(function (QueryExpression $exp) use ($context) {
                                    return $exp
                                        ->eq('Containers.name', $context['data']['name'])
                                        ->eq('Containers.containertype_id', CT_TENANT)
                                        ->notEq('Containers.id', $context['data']['id']);
                                })
                                ->count();
                        } else {
                            //No ID given in POST data. Check if a tenant with this name already exists
                            $count = $this->find()
                                ->where([
                                    'Containers.name'             => $context['data']['name'],
                                    'Containers.containertype_id' => CT_TENANT
                                ])
                                ->count();
                        }


                        return $count === 0;
                    }

                    return true;
                },
                'message' => __('This name already exists.')
            ]);

        $validator
            ->scalar('parent_id')
            ->numeric('parent_id')
            ->greaterThan('parent_id', 0)
            ->allowEmptyString('parent_id', __('This field cannot be left blank.'), false);

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
        //$rules->add($rules->existsIn(['parent_id'], 'ParentContainers'));

        return $rules;
    }


    /**
     * @param int|array $ids
     * @param array $options
     * @param array $valide_types
     * @return array
     *
     * ### Options
     * - `delimiter`   The delimiter for the path (default /)
     * - `order`       Order of the returned array asc|desc (default asc)
     */
    private function path($ids, $options = [], $valide_types = [CT_GLOBAL, CT_TENANT, CT_LOCATION, CT_NODE]) {
        $_options = [
            'delimiter'    => '/',
            'valide_types' => $valide_types,
            'order'        => 'asc',
        ];
        $options = Hash::merge($_options, $options);

        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $node = $this->find()
            ->where(['id IN ' => $ids])
            ->disableHydration()
            ->all()
            ->toArray();

        $paths = [];
        foreach ($node as $container) {
            $containerTypeId = (int)$container['containertype_id'];
            if (in_array($containerTypeId, $options['valide_types'], true)) {
                $paths[$container['id']] = '/' . $this->treePath($container['id'], $options['delimiter']);
            }
        }

        if ($options['order'] === 'asc') {
            asort($paths);
        }

        if ($options['order'] === 'desc') {
            arsort($paths);
        }

        return $paths;
    }

    /**
     * Returns tha path to a single node in the tree
     *
     * @param integer $id of the container
     * @param string $delimiter (default /)
     *
     * @return string with the path to the container
     */
    public function treePath($id = null, $delimiter = '/') {
        try {
            $containerNames = [];
            $tree = $this->find('path', ['for' => $id])
                ->disableHydration()
                ->toArray();

            foreach ($tree as $node) {
                $containerNames[] = $node['name'];
            }

            return implode($delimiter, $containerNames);

        } catch (RecordNotFoundException $e) {
            return '';
        }
    }

    /**
     *
     * @param int $id of the container
     * @param array $MY_RIGHTS_LEVEL
     *
     * @return array
     */
    public function getTreePathForBrowser($id, $MY_RIGHTS_LEVEL = []) {
        try {
            $result = [];
            $tree = $this->find('path', ['for' => $id])
                ->disableHydration()
                ->toArray();

            foreach ($tree as $node) {
                if (isset($MY_RIGHTS_LEVEL[$node['id']])) {
                    $result[] = [
                        'id'   => $node['id'],
                        'name' => $node['name']
                    ];
                } else {
                    //User has no permission to this container
                    $result[] = [
                        'id'   => null,
                        'name' => $node['name']
                    ];
                }
            }

            return $result;
        } catch (RecordNotFoundException $e) {
            return [];
        }
    }


    /**
     * @param int|array $id
     * @param int $ObjectsByConstancName
     * @param array $options
     * @param bool $hasRootPrivileges
     * @param array $exclude Array of container tyoes which gets excluded from result
     * @return array
     *
     * Returns:
     * [
     *     1 => '/root',
     *     2 => '/root/tenant'
     * ]
     *
     * ### Options
     * - `delimiter`   The delimiter for the path (default /)
     * - `order`       Order of the returned array asc|desc (default asc)
     *
     * @throws \Exception
     */
    public function easyPath($id, $ObjectsByConstancName, $options = [], $hasRootPrivileges = false, $exclude = []) {
        if ($this->containerCache === null) {
            $query = $this->find('all')
                ->disableHydration()
                ->toArray();

            $this->containerCache = $query;
        }

        if ($hasRootPrivileges == false) {
            if (is_array($id)) {
                // User has no root privileges so we need to delete the root container
                $id = $this->removeRootContainer($id);
            } else {
                if ($id == ROOT_CONTAINER) {
                    throw new ForbiddenException(__('You need root privileges'));
                }
            }
        }

        if (empty($ObjectsByConstancName)) {
            return [];
        }

        //Container implementation in PHP but fast
        $ContainerNestedSet = ContainerNestedSet::fromCake4($this->containerCache, $hasRootPrivileges);
        return $ContainerNestedSet->easyPath($id, $ObjectsByConstancName, $exclude);

        //Plain ORM but bad performance
        //$Constants = new Constants();
        //return $this->path($id, $options, $Constants->containerProperties($ObjectsByConstancName, $exclude));
    }

    /**
     * @param int|array $containerIds
     * @param bool $resolveRoot
     * @param array $includeContainerTypes
     * @return array
     */
    public function resolveChildrenOfContainerIds($containerIds, $resolveRoot = false, $includeContainerTypes = []) {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $containerIds = array_unique($containerIds);
        $result = [ROOT_CONTAINER];
        foreach ($containerIds as $containerId) {
            $containerId = (int)$containerId;
            if ($containerId === ROOT_CONTAINER && $resolveRoot === false) {
                continue;
            }

            $cacheKey = 'TreeComponentResolveChildrenOfContainerIds:' . $containerId . ':false';
            if ($resolveRoot) {
                $cacheKey = 'TreeComponentResolveChildrenOfContainerIds:' . $containerId . ':true';
            }

            $tmpResult = Cache::remember($cacheKey, function () use ($containerId) {
                try {
                    $query = $this->find('children', [
                        'for' => $containerId
                    ])->disableHydration()->select(['id', 'containertype_id'])->all();
                    return $query->toArray();
                } catch (RecordNotFoundException $e) {
                    return [];
                }
            }, 'migration');

            if (!empty($includeContainerTypes)) {
                $tmpResult = Hash::extract($tmpResult, '{n}[containertype_id=/^(' . implode('|', $includeContainerTypes) . ')$/].id');
            } else {
                $tmpResult = Hash::extract($tmpResult, '{n}.id');
            }
            $result = array_merge($result, $tmpResult);
            $result[] = $containerId;
        }

        return array_unique($result);
    }

    /**
     * Remove the ROOT_CONTAINER from a given array with container ids as value
     *
     * @param array $containerIds
     *
     * @return array
     */
    public function removeRootContainer($containerIds) {
        $result = [];
        foreach ($containerIds as $containerId) {
            $containerId = (int)$containerId;
            if ($containerId !== ROOT_CONTAINER) {
                $result[] = $containerId;
            }
        }

        return $result;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getPathById($id) {
        try {
            $path = $this->find('path', ['for' => $id])
                ->disableHydration()
                ->all()
                ->toArray();
            return $path;
        } catch (RecordNotFoundException $e) {
            return [];
        }
    }

    public function getPathByIdAndCacheResult($id, $cacheKey) {
        $cacheKey = sprintf('%s:%s', $cacheKey, $id);
        $path = Cache::remember($cacheKey, function () use ($id) {
            try {
                $path = $this->find('path', ['for' => $id])
                    ->disableHydration()
                    ->all()
                    ->toArray();
                return $path;
            } catch (RecordNotFoundException $e) {
                return [];
            }
        }, 'migration');
        return $path;
    }

    /**
     * @param int $id
     * @param string $delimiter
     * @return string
     */
    public function getPathByIdAsString($id, $delimiter = '/') {
        $path = $this->find('path', ['for' => $id])
            ->disableHydration()
            ->all()
            ->toArray();
        $nodes = [];
        foreach ($path as $node) {
            $nodes[] = $node['name'];
        }

        return $delimiter . implode($delimiter, $nodes);
    }

    public function getAllContainerByParentId($parentContainerId) {
        if (!is_array($parentContainerId)) {
            $parentContainerId = [$parentContainerId];
        }

        $containers = $this->find()
            ->where(['Containers.parent_id IN' => $parentContainerId])
            ->disableHydration()
            ->all()
            ->toArray();

        if ($containers === null) {
            return [];
        }

        return $containers;
    }

    /**
     * @param int $id
     * @return bool|mixed
     * @link https://book.cakephp.org/3.0/en/orm/behaviors/tree.html#deleting-nodes
     */
    public function deleteContainerById($id) {
        $container = $this->get($id);
        return $this->delete($container);
    }

    /**
     * @param $id
     * @param bool $threaded
     * @return array
     */
    public function getChildren($id, $threaded = false) {
        try {
            $query = $this->find('children', [
                'for' => $id
            ]);

            if ($threaded) {
                $query->find('threaded');
            }

            return $query->disableHydration()
                ->all()
                ->toArray();

        } catch (RecordNotFoundException $e) {
            return [];
        }
    }

    /**
     * @param $browserAsNest
     * @param $MY_RIGHTS
     * @param $containerTypes
     * @return array
     */
    public function getFirstContainers($browserAsNest, $MY_RIGHTS, $containerTypes) {
        $containers = [];
        foreach ($browserAsNest as $container) {
            if (in_array($container['id'], $MY_RIGHTS) && in_array($container['containertype_id'], $containerTypes, true)) {
                $containers[] = $container;
                continue;
            }

            foreach ($container['children'] as $childContainer) {
                $results = $this->getFirstContainers([$childContainer], $MY_RIGHTS, $containerTypes);
                foreach ($results as $result) {
                    $containers[] = $result;
                }
            }
        }

        return $containers;
    }

    /**
     * !!! ONLY USE THIS FOR DISPLAY PURPOSE !!!
     *
     * @param int $hostPrimaryContainerId
     * @param array $hostSharingContainerIds
     * @param array $MY_RIGHTS
     * @return null|array
     */
    public function getFakePrimaryContainerForHostEditDisplay($hostPrimaryContainerId, $hostSharingContainerIdsParam, $MY_RIGHTS) {
        $hostSharingContainerIds = [];
        foreach ($hostSharingContainerIdsParam as $hostSharingContainerId) {
            $hostSharingContainerId = (int)$hostSharingContainerId;
            $hostSharingContainerIds[$hostSharingContainerId] = $hostSharingContainerId;
        }


        $containerIdUserHasPermissionsOn = null;
        foreach ($MY_RIGHTS as $MY_RIGHT_CONTAINER_ID) {
            if (isset($hostSharingContainerIds[$MY_RIGHT_CONTAINER_ID])) {
                //Get the first container id that the user has permissions for
                $containerIdUserHasPermissionsOn = $hostSharingContainerIds[$MY_RIGHT_CONTAINER_ID];
                break;
            }
        }

        //get the name of the container
        if ($containerIdUserHasPermissionsOn !== null) {
            $path = '/' . $this->treePath($containerIdUserHasPermissionsOn);
            // $path contains a sharing container.
            // to let the select box display a shard container as read only, we set the primaryContainerId
            // as for the $path
            // THIS IS ONLY USED FOR DISPLAY PURPOSE
            return [
                $hostPrimaryContainerId => $path
            ];
        }

        return null;
    }

    /**
     * !!! ONLY USE THIS FOR DISPLAY PURPOSE !!!
     * @param $hostSharingContainerIdsParam
     * @param $MY_RIGHTS
     * @param $MY_RIGHTS_LEVEL
     * @return array|null
     */
    public function getFakePrimaryContainerForHostBrowserDisplay($hostSharingContainerIdsParam, $MY_RIGHTS, $MY_RIGHTS_LEVEL) {
        $hostSharingContainerIds = [];
        foreach ($hostSharingContainerIdsParam as $hostSharingContainerId) {
            $hostSharingContainerId = (int)$hostSharingContainerId;
            $hostSharingContainerIds[$hostSharingContainerId] = $hostSharingContainerId;
        }


        $containerIdUserHasPermissionsOn = null;
        foreach ($MY_RIGHTS as $MY_RIGHT_CONTAINER_ID) {
            if (isset($hostSharingContainerIds[$MY_RIGHT_CONTAINER_ID])) {
                //Get the first container id that the user has permissions for
                $containerIdUserHasPermissionsOn = $hostSharingContainerIds[$MY_RIGHT_CONTAINER_ID];
                break;
            }
        }

        //get the name of the container
        if ($containerIdUserHasPermissionsOn !== null) {
            return $this->getTreePathForBrowser($containerIdUserHasPermissionsOn, $MY_RIGHTS_LEVEL);
        }

        return null;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Containers.id' => $id]);
    }


    /**
     * @param $id
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getContainerById($id, $MY_RIGHTS = []) {
        $query = $this->find()
            ->select([
                'Containers.id',
                'Containers.parent_id',
                'Containers.name',
                'Containers.containertype_id',
                'Containers.lft',
                'Containers.rght'
            ])
            ->where([
                'Containers.id' => $id
            ]);

        if (!empty($MY_RIGHTS)) {
            $query->andWhere([
                'Containers.id IN' => $MY_RIGHTS
            ]);
        }
        $result = $query->first();
        if (empty($result)) {
            return [];
        }
        return $result->toArray();
    }


    /**
     * @param int $containerId
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getContainerWithAllChildren($containerId, $MY_RIGHTS = []) {
        $containersMap = [
            'nodes' => [],
            'edges' => []
        ];

        $parentContainer = $this->getContainerById($containerId);

        $query = $this->find('children', ['for' => $containerId]);

        $query->select([
            'Containers.id',
            'Containers.parent_id',
            'Containers.name',
            'Containers.containertype_id',
            'Containers.lft',
            'Containers.rght'
        ])
            ->where([
                'Containers.containertype_id IN ' => [CT_GLOBAL, CT_TENANT, CT_LOCATION, CT_NODE]
            ])
            ->disableHydration();
        $containers = $query->toArray();
        $containers[] = $parentContainer;
        $containers = Hash::sort($containers, '{n}.id', 'asc');


        /** Monitoring Objects */
        /** @var HosttemplatesTable $HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var HostgroupsTable $HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        /** @var ServicetemplatesTable $ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
        /** @var ServicetemplategroupsTable $ServicetemplategroupsTable */
        $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var ServicegroupsTable $ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');
        /** @var TimeperiodsTable $TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var ContactsTable $ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        /** @var ContactgroupsTable $ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        /** @var HostdependenciesTable $HostdependenciesTable */
        $HostdependenciesTable = TableRegistry::getTableLocator()->get('Hostdependencies');
        /** @var $HostescalationsTable HostescalationsTable */
        $HostescalationsTable = TableRegistry::getTableLocator()->get('Hostescalations');
        /** @var ServicedependenciesTable $ServicedependenciesTable */
        $ServicedependenciesTable = TableRegistry::getTableLocator()->get('Servicedependencies');
        /** @var $ServiceescalationsTable ServiceescalationsTable */
        $ServiceescalationsTable = TableRegistry::getTableLocator()->get('Serviceescalations');

        /** Reports Objects */

        /** @var $InstantreportsTable InstantreportsTable */
        $InstantreportsTable = TableRegistry::getTableLocator()->get('Instantreports');
        if (Plugin::isLoaded('AutoreportModule')) {
            /** @var $AutoreportsTable AutoreportsTable */
            $AutoreportsTable = TableRegistry::getTableLocator()->get('AutoreportModule.Autoreports');
        }

        /** Satellites Objects */

        if (Plugin::isLoaded('DistributeModule')) {
            /** @var \DistributeModule\Model\Table\SatellitesTable $SatellitesTable */
            $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');
        }

        /**
         * 'CT_GLOBAL'               => 1,
         * 'CT_TENANT'               => 2,
         * 'CT_LOCATION'             => 3,
         * 'CT_NODE'                 => 5,
         * 'CT_CONTACTGROUP'         => 6,
         * 'CT_HOSTGROUP'            => 7,
         * 'CT_SERVICEGROUP'         => 8,
         * 'CT_SERVICETEMPLATEGROUP' => 9,
         */


        if (Plugin::isLoaded('GrafanaModule')) {
            /** @var $GrafanaUserdashboardsTable GrafanaUserdashboardsTable */
            $GrafanaUserdashboardsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaUserdashboards');
        }

        if (Plugin::isLoaded('MapModule')) {
            /** @var $MapsTable MapsTable */
            $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');
        }

        foreach ($containers as $index => $container) {
            switch ($container['containertype_id']) {
                case CT_GLOBAL:
                case CT_TENANT:
                case CT_LOCATION:
                case CT_NODE:
                    $containers[$index]['childsElements']['hosts'] = $HostsTable->getHostsByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS, ['Hosts.disabled IN' => [0, 1]]);
                    $containers[$index]['childsElements']['hosttemplates'] = $HosttemplatesTable->getHosttemplatesByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    $containers[$index]['childsElements']['hostgroups'] = $HostgroupsTable->getHostgroupsByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    $containers[$index]['childsElements']['servicetemplates'] = $ServicetemplatesTable->getServicetemplatesByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    $containers[$index]['childsElements']['servicetemplategroups'] = $ServicetemplategroupsTable->getServicetemplategroupsByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    $containers[$index]['childsElements']['servicegroups'] = $ServicegroupsTable->getServicegroupsByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    $containers[$index]['childsElements']['timeperiods'] = $TimeperiodsTable->getTimeperiodsByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    $containers[$index]['childsElements']['contacts'] = $ContactsTable->getContactsByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    $containers[$index]['childsElements']['contactgroups'] = $ContactgroupsTable->getContactgroupsByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);

                    // label Type_#Id
                    $containers[$index]['childsElements']['hostdependencies'] = $HostdependenciesTable->getHostdependenciesByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    $containers[$index]['childsElements']['hostescalations'] = $HostescalationsTable->getHostescalationsByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    $containers[$index]['childsElements']['servicedependencies'] = $ServicedependenciesTable->getServicedependenciesByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    $containers[$index]['childsElements']['serviceescalations'] = $ServiceescalationsTable->getServiceescalationsByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);

                    // Load Reports
                    $containers[$index]['childsElements']['instantreports'] = $InstantreportsTable->getInstantreportsByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    if (isset($AutoreportsTable)) {
                        $containers[$index]['childsElements']['autoreports'] = $AutoreportsTable->getAutoreportsByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    }

                    // Load Satellites
                    if (isset($SatellitesTable)) {
                        $containers[$index]['childsElements']['satellites'] = $SatellitesTable->getSatellitesByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    }

                    // Load Maps
                    if (isset($MapsTable)) {
                        $containers[$index]['childsElements']['maps'] = $MapsTable->getMapsByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    }

                    // Load Grafana User dashboards
                    if (isset($GrafanaUserdashboardsTable)) {
                        $containers[$index]['childsElements']['grafana_userdashboards'] = $GrafanaUserdashboardsTable->getGrafanaUserDashboardsByContainerIdExact($container['id'], 'list', 'id', $MY_RIGHTS);
                    }

                    break;
            }
        }
        return $containers;
    }

    /**
     * @param array $rootContainer
     * @param array $subContainers
     * @return array
     */
    public function getContainerMap(array $rootContainer, array $subContainers = []) {
        $possibleClusterTypes = [
            1  => 'root',
            2  => 'tenant',
            3  => 'location',
            4  => 'devicegroup',
            5  => 'node',
            6  => 'contactgroups',
            7  => 'hostgroups',
            8  => 'servicegroups',
            9  => 'servicetemplategroups',
            10 => 'hostdependencies',
            11 => 'hostescalations',
            12 => 'servicedependencies',
            13 => 'serviceescalations',
            14 => 'instantreports',
            15 => 'autoreports',
            16 => 'maps',
            17 => 'hosttemplates'
        ];

        $nodes[] = [
            'id'    => $rootContainer['id'],
            'label' => $rootContainer['name'],
            'group' => $possibleClusterTypes[$rootContainer['containertype_id']]
        ];

        $nodes = [];
        $edges = [];
        $cluster = [];

        foreach ($subContainers as $subContainer) {
            //$subContainers -> all containers by id

            $nodes[] = [
                'id'    => $subContainer['id'],
                'label' => $subContainer['name'],
                'group' => $possibleClusterTypes[$subContainer['containertype_id']]
            ];

            if ($rootContainer['id'] !== $subContainer['id']) {
                $edges[] = [
                    'from'   => $rootContainer['id'],
                    'to'     => $subContainer['id'],
                    'color'  => [
                        'inherit' => 'to',
                    ],
                    'arrows' => 'to'
                ];
            }


            $childMap = $this->getNodeAndEdgesForChilds($subContainer['id'], $subContainer['childsElements']);
            if (!empty($childMap['nodes']) && !empty($childMap['edges'])) {
                foreach ($childMap['nodes'] as $node) {
                    $nodes[] = $node;
                }
                foreach ($childMap['edges'] as $edge) {
                    $edges[] = $edge;
                }
                $cluster = array_merge($cluster, $childMap['cluster']);
            }
        }
        $containerMap = [
            'nodes'   => $nodes,
            'edges'   => $edges,
            'cluster' => $cluster
        ];

        return $containerMap;
    }

    /**
     * @param int $containerId
     * @param array $childsArray
     * @return array
     */
    private function getNodeAndEdgesForChilds(int $containerId, array $childsArray) {
        $nodes = [];
        $edges = [];
        $cluster = [];

        $possibleClusterTypesWithLabel = [
            'root'                   => '/root',
            'tenant'                 => __('Tenant'),
            'location'               => __('Location'),
            'devicegroup'            => __('Device group'),
            'node'                   => __('Node'),
            'hosts'                  => __('Hosts'),
            'hosttemplates'          => __('Host templates'),
            'servicetemplates'       => __('Service templates'),
            'contacts'               => __('Contacts'),
            'contactgroups'          => __('Contact groups'),
            'hostgroups'             => __('Host groups'),
            'servicegroups'          => __('Service groups'),
            'servicetemplategroups'  => __('Service template groups'),
            'hostdependencies'       => __('Host dependencies'),
            'hostescalations'        => __('Host escalations'),
            'servicedependencies'    => __('Service dependencies'),
            'serviceescalations'     => __('Service escalations'),
            'instantreports'         => __('Instant reports'),
            'autoreports'            => __('Autoreports'),
            'maps'                   => __('Maps'),
            'satellites'             => __('Satellites'),
            'timeperiods'            => __('Time periods'),
            'grafana_userdashboards' => __('Grafana user dashboards'),
        ];


        foreach ($childsArray as $childObjectName => $childs) {

            $sizeof = sizeof($childs);
            if ($sizeof > 0) {
                //Create cluster node
                //This contains all childs (example: Servicetemplates and attatch all Servicetemplates to THIS node!)
                $nodes[] = [
                    'id'            => $containerId . '_' . $childObjectName, //1_tenant
                    'label'         => $possibleClusterTypesWithLabel[$childObjectName], // tenant
                    'group'         => $childObjectName,
                    'createCluster' => $containerId . '_' . $childObjectName //1_tenant
                ];
                $cluster[] = [
                    'name'  => $containerId . '_' . $childObjectName,
                    'label' => $possibleClusterTypesWithLabel[$childObjectName],
                    'size'  => $sizeof
                ];
                $edges[] = [
                    'from'   => $containerId,
                    'to'     => $containerId . '_' . $childObjectName,
                    'color'  => [
                        'inherit' => 'to',
                    ],
                    'arrows' => 'to'
                ];
            }

            //Create all child elements of clustered group
            // Example: Attatch all servicetemplates to servicetemplate cluster
            foreach ($childs as $id => $childName) {
                $nodes[] = [
                    'id'    => $childObjectName . '_' . $childName . '_' . $id . '_' . $containerId,  //tenant_TenantName_tenantId_containerId
                    'label' => $childName,
                    'group' => $childObjectName,
                    'cid'   => $containerId . '_' . $childObjectName //1_tenant
                ];
                $edges[] = [
                    'from'   => $containerId . '_' . $childObjectName,
                    'to'     => $childObjectName . '_' . $childName . '_' . $id . '_' . $containerId,
                    'color'  => [
                        'inherit' => 'to',
                    ],
                    'arrows' => 'to'
                ];

                if ($sizeof === 1) {
                    // A cluster of one element is always expande in VisJS
                    // We add a hidden fake element to make the cluster collapse
                    $nodes[] = [
                        'id'     => 'fake_' . $childObjectName . '_' . $childName . '_' . $id . '_' . $containerId,  //fake_tenant_TenantName_tenantId_containerId
                        'cid'    => $containerId . '_' . $childObjectName, //1_tenant,
                        'hidden' => true
                    ];
                    $edges[] = [
                        'from' => $containerId . '_' . $childObjectName,
                        'to'   => 'fake_' . $childObjectName . '_' . $childName . '_' . $id . '_' . $containerId
                    ];
                }
            }
        }
        return [
            'nodes'   => $nodes,
            'edges'   => $edges,
            'cluster' => $cluster
        ];
    }

    /**
     * checks if the given container contains subcontainers
     * return false if it has subcontainers - so it cant be deleted
     * return true if its empty and can be safely deleted
     * @param int $id
     * @return bool
     */
    public function allowDelete($id): bool {
        if (!$this->existsById($id)) {
            throw new NotFoundException(__('Invalid container'));
        }

        $subContainers = $this->getContainerWithAllChildren($id);
        // check content of subcontainers
        /*
         This checks if there are content in containers like servicetemplates, services or hosts e.g.
        this also causes that a Servicetemplategroup for example cant be deleted anymore due to the fact that its
        content has to be at least one servicetemplate. So you cant save an empty servicetemplategroup which is the
        only allowed form of a servicetemplategroup to be deleted with the following function.
        This checking is may too strict
        */
        /*
        foreach ($subContainers as $key => $container) {
            if (!$this->isEmptyContainer($id, $container['containertype_id'])) {
                return false;
            }
        }
        */
        foreach ($subContainers as $key => $subcontainer) {
            //check child elements
            foreach ($subcontainer['childsElements'] as $childsElement) {
                if (!empty($childsElement)) {
                    return false;
                }
            }

            //remove the base container itself from the array
            if ($subcontainer['id'] == $id) {
                unset($subContainers[$key]);
            }

            //if $subContainers still not empty then there are child containers which stops the container deletion
            if (!empty($subContainers)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param null $containerId
     * @param null $containertype
     * @return bool
     * @deprecated
     */
    public function isEmptyContainer($containerId = null, $containertype = null): bool {
        if (!empty($containertype)) {
            switch ($containertype) {
                case CT_TENANT:
                    /** @var TenantsTable $TenantsTable */
                    $TenantsTable = TableRegistry::getTableLocator()->get('Tenants');
                    $tenant = $TenantsTable->getTenantByContainerId($containerId);
                    break;
                case CT_LOCATION:
                    /** @var LocationsTable $LocationsTable */
                    $LocationsTable = TableRegistry::getTableLocator()->get('Locations');
                    $location = $LocationsTable->getLocationByContainerId($containerId);
                    break;
                case CT_NODE:

                    break;
                case CT_CONTACTGROUP:
                    /** @var $ContactgroupsTable ContactgroupsTable */
                    $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
                    $ContactgroupsTable->getContactgroupByContainerId($containerId);

                    break;
                case CT_HOSTGROUP:
                    /** @var HostgroupsTable $HostgroupsTable */
                    $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
                    $hostgroup = $HostgroupsTable->getHostgroupByContainerId($containerId);
                    if (!empty($hostgroup['hosts']) || !empty($hostgroup['hosttemplates'])) {
                        return false;
                    }
                    return true;
                    break;
                case CT_SERVICEGROUP:
                    /** @var ServicegroupsTable $ServicegroupsTable */
                    $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');
                    $servicegroup = $ServicegroupsTable->getServicegroupByContainerId($containerId);
                    if (!empty($servicegroup['services']) || !empty($servicegroup['servicetemplates'])) {
                        return false;
                    }
                    return true;

                    break;
                case CT_SERVICETEMPLATEGROUP:
                    /** @var ServicetemplategroupsTable $ServicetemplategroupsTable */
                    $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');
                    $servicetemplategroup = $ServicetemplategroupsTable->getServicetemplategroupByContainerId($containerId);
                    if (!empty($servicetemplategroup['servicetemplates'])) {
                        return false;
                    }
                    return true;
                    break;

            }
        }
        return false;
    }
}
