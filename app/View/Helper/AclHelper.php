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

class AclHelper extends AppHelper
{

    public function hasPermission($action = null, $controller = null, $plugin = null)
    {
        //return false;
        if ($action === null) {
            $action = $this->params['index'];
        }
        if ($controller === null) {
            $controller = $this->params['controller'];
        }
        if ($plugin === null) {
            $plugin = Inflector::classify($this->params['plugin']);
        }

        $controller = strtolower($controller);
        $action = strtolower($action);
        $plugin = strtolower($plugin);
        if ($plugin === null || $plugin === '') {
            return isset($this->_View->viewVars['aclPermissions'][$controller][$action]);
        }

        return isset($this->_View->viewVars['aclPermissions'][$plugin][$controller][$action]);
    }

    public function isWritableContainer($containerIds)
    {
        if (isset($this->_View->viewVars['hasRootPrivileges']) && $this->_View->viewVars['hasRootPrivileges'] === true) {
            return true;
        }
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }
        foreach ($containerIds as $containerId) {
            if (isset($this->_View->viewVars['MY_RIGHTS_LEVEL'][$containerId])) {
                if ($this->_View->viewVars['MY_RIGHTS_LEVEL'][$containerId] == WRITE_RIGHT) {
                    return true;
                }
            }
        }

        return false;
    }

}
