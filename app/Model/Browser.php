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
 * Class Browser
 * @deprecated
 */
class Browser extends AppModel {
    var $useTable = false;

    /**
     * @param $browserAsNest
     * @param $MY_RIGHTS
     * @param $containerTypes
     * @return array
     * @deprecated
     */
    public function getFirstContainers($browserAsNest, $MY_RIGHTS, $containerTypes) {
        $containers = [];
        foreach ($browserAsNest as $container) {
            if (in_array($container['id'], $MY_RIGHTS) && in_array($container['containertype_id'], $containerTypes)) {
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
}
