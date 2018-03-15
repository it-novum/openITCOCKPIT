<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\Core\Views;


class ContainerPermissions {

    /**
     * @var array
     */
    private $MY_RIGHTS_LEVEL = [];

    /**
     * @var array
     */
    private $Containers = [];

    /**
     * ContainerPermissions constructor.
     * @param array $MY_RIGHTS_LEVEL
     * @param array $ContainersToCheck
     */
    public function __construct($MY_RIGHTS_LEVEL, $ContainersToCheck = []) {
        foreach($MY_RIGHTS_LEVEL as $containerId => $rightLevel){
            $rightLevel = (int)$rightLevel;
            if($rightLevel === WRITE_RIGHT){
                $this->MY_RIGHTS_LEVEL[$containerId] = $rightLevel;
            }
        }
        $this->Containers = $ContainersToCheck;
    }

    /**
     * @return bool
     */
    public function hasPermission() {
        return !empty(array_intersect($this->Containers, array_keys($this->MY_RIGHTS_LEVEL)));
    }

}
