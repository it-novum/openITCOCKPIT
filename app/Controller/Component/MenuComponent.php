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

class MenuComponent extends Component
{
    public function compileMenu()
    {
        Configure::load('menu');
        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });
        foreach ($modulePlugins as $pluginName) {
            Configure::load($pluginName.'.'.'menu');
        }
        $menuOrder = [];
        $menu = Configure::read('menu');

        foreach ($menu as $key => $menuItem) {
            if (isset($menuItem['order'])) {
                $menuOrder[$key] = $menuItem['order'];
            } else {
                $menuOrder[$key] = 9999;
            }
        }
        asort($menuOrder);

        $finalMenu = [];
        foreach ($menuOrder as $key => $dev_null) {
            if (isset($menu[$key]['parent'])) {
                if (array_key_exists($menu[$key]['parent'], $finalMenu)) {
                    //merge 
                    $finalMenu[$menu[$key]['parent']]['children'] = Hash::merge($finalMenu[$menu[$key]['parent']]['children'], $menu[$key]['children']);
                } else {
                    if (array_key_exists($menu[$key]['parent'], $menuOrder)) {
                        //create the new key
                        $finalMenu[$menu[$key]['parent']]['children'] = $menu[$key]['children'];
                    } else {
                        //create the menu as there were no parent set
                        $finalMenu[$key] = $menu[$key];
                    }
                }
            } else {
                if (array_key_exists($key, $finalMenu)) {
                    //merge
                    $finalMenu[$key] = Hash::merge($finalMenu[$key], $menu[$key]);
                } else {
                    //create
                    $finalMenu[$key] = $menu[$key];
                }
            }
        }
        unset($menu);

        return $finalMenu;
    }

    public function filterMenuByAcl($menu, $permissions)
    {
        $_menu = [];
        foreach ($menu as $parentKey => $parentNode) {
            $_parentNode = [];
            //Dashboard is always allowed
            if ($parentNode['url']['controller'] === 'dashboard' && $parentNode['url']['action'] === 'index' && $parentNode['url']['plugin'] === 'admin') {
                $_menu[$parentKey] = $parentNode;
                continue;
            }

            if (isset($parentNode['children']) && !empty($parentNode['children'])) {
                if ($this->checkPermissions($parentNode['url']['plugin'], $parentNode['url']['controller'], $parentNode['url']['action'], $permissions)) {
                    $_parentNode = $parentNode;
                    unset($_parentNode['children']);
                }
                $_childNodes = [];
                if (!empty($parentNode['children']) && !empty($_parentNode)) {
                    foreach ($parentNode['children'] as $childKey => $childNode) {
                        if (!isset($childNode['url']['plugin'])) {
                            $childNode['url']['plugin'] = '';
                        }

                        if ($this->checkPermissions($childNode['url']['plugin'], $childNode['url']['controller'], $childNode['url']['action'], $permissions)) {
                            $_childNodes[$childKey] = $childNode;
                        } else {
                            //Check if we have any fallback actions like by DowntimesController
                            if (isset($childNode['fallback_actions'])) {
                                if (!is_array($childNode['fallback_actions'])) {
                                    $childNode['fallback_actions'] = [$childNode['fallback_actions']];
                                }
                                foreach ($childNode['fallback_actions'] as $fallbackAction) {
                                    if ($this->checkPermissions($childNode['url']['plugin'], $childNode['url']['controller'], $fallbackAction, $permissions)) {
                                        $childNode['url']['action'] = $fallbackAction;
                                        $_childNodes[$childKey] = $childNode;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                if ($this->checkPermissions($parentNode['url']['plugin'], $parentNode['url']['controller'], $parentNode['url']['action'], $permissions)) {
                    $_menu[$parentKey] = $parentNode;
                }
            }

            if (!empty($_childNodes) && !empty($_parentNode)) {
                $_parentNode['children'] = $_childNodes;
                $_menu[$parentKey] = $_parentNode;
            }

        }

        return $_menu;
    }

    public function lower($string)
    {
        //return strtolower(Inflector::classify($string));
        return strtolower(str_replace('_', '', $string));
    }

    public function checkPermissions($plugin = '', $controller = '', $action = '', $permissions)
    {
        $controller = $this->lower($controller);
        $action = $this->lower($action);
        if ($plugin === '') {
            return isset($permissions[$controller][$action]);
        } else {
            $plugin = $this->lower($plugin);

            return isset($permissions[$plugin][$controller][$action]);
        }
    }
}

