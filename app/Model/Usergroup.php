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

/**
 * Class Usergroup
 * @deprecated
 */
class Usergroup extends AppModel {
    public $name = 'Usergroup';
    public $actsAs = [
        'Acl' => [
            'type' => 'requester',
        ],
    ];

    public $hasMany = 'User';

    public $validate = [
        'name' => [
            'notBlank' => [
                'rule'       => 'notBlank',
                'message'    => 'This field cannot be left blank.',
                'required'   => true,
                'allowEmpty' => false,
            ],
            'isUnique' => [
                'rule'       => 'isUnique',
                'message'    => 'This user role name has already been taken.',
                'required'   => true,
                'allowEmpty' => false,
            ],
        ],
    ];

    public function parentNode() {
        return 'Usergroups';
    }

    //Return a array of aco ids that needs to enabled for every user! (for ajax requests etc..)

    /**
     * @param $acosAsNest
     * @return array
     * @deprecated
     */
    public function getAlwaysAllowedAcos($acosAsNest) {
        Configure::load('acl_dependencies');

        //Load Plugin configuration files
        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
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

    //Return an array of aco ids + dependenc aco ids
    public function getAcoDependencies($acosAsNest) {
        Configure::load('acl_dependencies');

        //Load Plugin configuration files
        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
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

    //Return a array of aco ids that needs to enabled for specific usergroup!
    public function getUsergroupAcos($acosAsNest, $userGroupName) {
        Configure::load('acl_dependencies');

        //Load Plugin configuration files
        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
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

    //Return an array with all aco ids that depend to an other aco, to remove them from the interface
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
