<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Configure;

/**
 * Usergroups Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\Usergroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Usergroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Usergroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Usergroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Usergroup|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Usergroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Usergroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Usergroup findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsergroupsTable extends Table {
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

        $this->setTable('usergroups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Users', [
            'foreignKey' => 'usergroup_id'
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', null, false);

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        return $validator;
    }

    /**
     * @return array
     */
    public function getUsergroupsList() {
        $query = $this->find('list');
        return $query->toArray();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Usergroups.id' => $id]);
    }

    /**
     * @param null $PaginateOMat
     * @return array
     */
    public function getUsergroups($PaginateOMat = null) {
        $query = $this->find()
            ->disableHydration()
            ->order([
                'name' => 'asc'
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
        $result = $query->toArray();
        return $result;
    }


    /**
     * @param $id
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getUsergroupById($id) {
        $query = $this->find('all')
            ->disableHydration()
            ->where([
                'id' => $id
            ]);
        if (is_null($query)) {
            return [];
        }
        return $query->first();
    }

    /**
     * @param $acosAsNest
     * @return array
     */
    public function getAlwaysAllowedAcos($acosAsNest) {
        Configure::load('acl_dependencies');

        //Load Plugin configuration files
        $modulePlugins = array_filter(\CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });
        foreach ($modulePlugins as $moduleName) {
            $pluginAclConfigFile = OLD_APP . 'Plugin' . DS . $moduleName . DS . 'Config' . DS . 'acl_dependencies.php';
            if (file_exists($pluginAclConfigFile)) {
                Configure::load($moduleName . '.acl_dependencies');
            }
        }
        //all acl_dependencies
        $config = Configure::read('acl_dependencies');

        $appControllerAcoNames = $config['AppController'];
        $alwaysAllowedAcos = $config['always_allowed'];
        unset($config);

        $result = [];

        foreach ($acosAsNest as $usergroupAcos) {
            foreach ($usergroupAcos['children'] as $controllerAcos) {
                $controllerName = $controllerAcos['Aco']['alias'];
                if (!strpos($controllerName, 'Module')) {
                    //Core ACLs
                    foreach ($controllerAcos['children'] as $actionAco) {
                        $actionName = $actionAco['Aco']['alias'];
                        $acoId = $actionAco['Aco']['id'];

                        $permitRight = false;
                        if (!isset($result[$acoId])) {
                            if (in_array($actionName, $appControllerAcoNames)) {
                                $permitRight = true;
                            }
                            if (isset($alwaysAllowedAcos[$controllerName]) && in_array($actionName, $alwaysAllowedAcos[$controllerName])) {
                                $permitRight = true;
                            }

                            if ($permitRight === true) {
                                $result[$acoId] = $controllerName . DS . $actionName;
                            }
                        }
                    }
                } else {
                    //Plugin ACLs
                    $pluginName = $controllerAcos['Aco']['alias'];
                    $pluginAcos = $controllerAcos;
                    foreach ($pluginAcos['children'] as $controllerAcos) {
                        $controllerName = $controllerAcos['Aco']['alias'];
                        foreach ($controllerAcos['children'] as $actionAco) {
                            $actionName = $actionAco['Aco']['alias'];
                            $acoId = $actionAco['Aco']['id'];

                            $permitRight = false;
                            if (!isset($result[$acoId])) {
                                if (in_array($actionName, $appControllerAcoNames)) {
                                    $permitRight = true;
                                }
                                if (isset($alwaysAllowedAcos[$controllerName]) && in_array($actionName, $alwaysAllowedAcos[$controllerName])) {
                                    $permitRight = true;
                                }

                                if ($permitRight === true) {
                                    $result[$acoId] = $pluginName . DS . $controllerName . DS . $actionName;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }


    /**
     * Return an array of aco ids + dependenc aco ids
     * @param $acosAsNest
     * @return array
     */
    public function getAcoDependencies($acosAsNest) {
        Configure::load('acl_dependencies');

        //Load Plugin configuration files
        $modulePlugins = array_filter(\CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });

        foreach ($modulePlugins as $moduleName) {
            $pluginAclConfigFile = OLD_APP . 'Plugin' . DS . $moduleName . DS . 'Config' . DS . 'acl_dependencies.php';
            if (file_exists($pluginAclConfigFile)) {
                Configure::load($moduleName . '.acl_dependencies');
            }
        }

        $acoDependencies = Configure::read('acl_dependencies.dependencies');
        $appControllerAcoNames = Configure::read('acl_dependencies.AppController');
        $result = [];
        foreach ($acosAsNest as $usergroupAcos) {
            foreach ($usergroupAcos['children'] as $controllerAcos) {
                $controllerName = $controllerAcos['Aco']['alias'];
                if (!strpos($controllerName, 'Module')) {
                    //Core ACL
                    //Has some of the controller actions dependencies?
                    if (isset($acoDependencies[$controllerName])) {
                        $acos = [];
                        foreach ($controllerAcos['children'] as $actionAco) {
                            $acos[$actionAco['Aco']['alias']] = $actionAco['Aco']['id'];
                        }
                        if (!empty($acos)) {
                            //Match found acos to dependencies
                            foreach ($acoDependencies[$controllerName] as $action => $dependenActions) {
                                if (isset($acos[$action])) {
                                    foreach ($dependenActions as $dependendAction) {
                                        if (isset($acos[$dependendAction])) {
                                            $result[$acos[$action]][$acos[$dependendAction]] = $controllerName . DS . $action . DS . $dependendAction;
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $pluginName = $controllerAcos['Aco']['alias'];
                    $pluginAcos = $controllerAcos;
                    foreach ($pluginAcos['children'] as $controllerAcos) {
                        $controllerName = $controllerAcos['Aco']['alias'];
                        //Has some of the controller actions dependencies?
                        if (isset($acoDependencies[$controllerName])) {
                            $acos = [];
                            foreach ($controllerAcos['children'] as $actionAco) {
                                $acos[$actionAco['Aco']['alias']] = $actionAco['Aco']['id'];
                            }
                            if (!empty($acos)) {
                                //Match found acos to dependencies
                                foreach ($acoDependencies[$controllerName] as $action => $dependenActions) {
                                    if (isset($acos[$action])) {
                                        foreach ($dependenActions as $dependendAction) {
                                            if (isset($acos[$dependendAction])) {
                                                $result[$acos[$action]][$acos[$dependendAction]] = $pluginName . DS . $controllerName . DS . $action . DS . $dependendAction;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }



    /**
     * Return a array of aco ids that needs to enabled for specific usergroup!
     * @param $acosAsNest
     * @param $userGroupName
     * @return array
     */
    public function getUsergroupAcos($acosAsNest, $userGroupName) {
        Configure::load('acl_dependencies');

        //Load Plugin configuration files
        $modulePlugins = array_filter(\CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });
        foreach ($modulePlugins as $moduleName) {
            $pluginAclConfigFile = OLD_APP . 'Plugin' . DS . $moduleName . DS . 'Config' . DS . 'acl_dependencies.php';
            if (file_exists($pluginAclConfigFile)) {
                Configure::load($moduleName . '.acl_dependencies');
            }
        }

        $config = Configure::read('acl_dependencies');
        $appControllerAcoNames = $config['AppController'];
        if (!isset($config['roles_rights'][$userGroupName]))
            return [];
        $thisUsergroupAcos = $config['roles_rights'][$userGroupName];

        unset($config);

        $result = [];

        foreach ($acosAsNest as $usergroupAcos) {
            foreach ($usergroupAcos['children'] as $controllerAcos) {
                $controllerName = $controllerAcos['Aco']['alias'];
                if (!strpos($controllerName, 'Module')) {
                    //Core ACLs
                    foreach ($controllerAcos['children'] as $actionAco) {
                        $actionName = $actionAco['Aco']['alias'];
                        $acoId = $actionAco['Aco']['id'];

                        if (isset($result[$acoId])) continue;

                        if (in_array('*', $thisUsergroupAcos)) {
                            $result[$acoId] = $controllerName . DS . $actionName;
                            continue;
                        }

                        if (isset($thisUsergroupAcos[$controllerName]) && in_array($actionName, $thisUsergroupAcos[$controllerName])) {
                            $result[$acoId] = $controllerName . DS . $actionName;
                        }
                    }
                } else {
                    //Plugin ACLs
                    $pluginName = $controllerAcos['Aco']['alias'];
                    $pluginAcos = $controllerAcos;
                    foreach ($pluginAcos['children'] as $controllerAcos) {
                        $controllerName = $controllerAcos['Aco']['alias'];
                        foreach ($controllerAcos['children'] as $actionAco) {
                            $actionName = $actionAco['Aco']['alias'];
                            $acoId = $actionAco['Aco']['id'];

                            if (isset($result[$acoId])) continue;

                            if (in_array('*', $thisUsergroupAcos)) {
                                $result[$acoId] = $controllerName . DS . $actionName;
                                continue;
                            }

                            if (isset($thisUsergroupAcos[$controllerName]) && in_array($actionName, $thisUsergroupAcos[$controllerName])) {
                                $result[$acoId] = $pluginName . DS . $controllerName . DS . $actionName;
                            }

                        }
                    }
                }
            }
        }
        return $result;
    }


    /**
     * Return an array with all aco ids that depend to an other aco, to remove them from the interface
     * @param $acoDependencies
     * @return array
     */
    public function getAcoDependencyIds($acoDependencies) {
        $result = [];
        foreach ($acoDependencies as $dependency) {
            foreach (array_keys($dependency) as $acoId) {
                $result[$acoId] = $acoId;
            }
        }

        return $result;
    }

}
