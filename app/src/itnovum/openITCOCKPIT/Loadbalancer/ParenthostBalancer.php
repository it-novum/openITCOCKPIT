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

namespace itnovum\openITCOCKPIT\Loadbalancer;


class ParenthostBalancer {

    /**
     * @var \Model
     */
    private $Parenthost;

    public function __construct(\Model $Parenthost) {
        $this->Parenthost = $Parenthost;
    }

    public function getParentWithChilds() {
        $parentHostWithChildId = $this->Parenthost->find('all', [
            'recursive' => -1,
            'joins'     => [
                [
                    'table'      => 'hosts_to_parenthosts',
                    'alias'      => 'HostToParenthost',
                    'type'       => 'INNER',
                    'conditions' => [
                        'HostToParenthost.parenthost_id = Parenthost.id',
                    ],
                ],
                [
                    'table'      => 'hosts',
                    'alias'      => 'Host',
                    'type'       => 'INNER',
                    'conditions' => [
                        'HostToParenthost.host_id = Host.id',
                    ],
                ]
            ],
            'fields'    => [
                'DISTINCT Parenthost.id',
                'Host.id'
            ],
        ]);

        $parentChildGroups = [];
        foreach ($parentHostWithChildId as $parentWithChildHost) {
            //$parentChildGroups[(int)$parentWithChildHost['Parenthost']['id']][] = (int)$parentWithChildHost['Host']['id'];

            $childHostId = (int)$parentWithChildHost['Host']['id'];
            $parentHostId = (int)$parentWithChildHost['Parenthost']['id'];
            if (!isset($parentChildGroups[$childHostId])) {
                $parentChildGroups[$childHostId] = [];
            }
            $parentChildGroups[$childHostId][] = $parentHostId;
        }


        return $parentChildGroups;
    }


    public function getRelationGroups() {
        $childsWithParent = $this->getParentWithChilds();
        $parentsWithAllChildsGroupd = [];

        //Group all childs to the parent host
        foreach ($childsWithParent as $childId => $parentIds) {
            foreach ($parentIds as $parentId) {
                if (!isset($parentsWithAllChildsGroupd[$parentId])) {
                    $parentsWithAllChildsGroupd[$parentId] = [
                        $parentId
                    ];
                }

                $parentsWithAllChildsGroupd[$parentId][] = $childId;
            }
        }

        //Group all childs into the same group
        return $this->mergeIntersectingParentWithChildGroups($parentsWithAllChildsGroupd);
    }

    private function mergeIntersectingParentWithChildGroups($parentsWithAllChildsGroupd) {

        $groupsToIntersect = array_values($parentsWithAllChildsGroupd);

        for ($i = 0; $i < sizeof($groupsToIntersect); $i++) {
            $doMinusMinus = false;
            for ($k = $i + 1; $k < sizeof($groupsToIntersect); $k++) {
                if (isset($groupsToIntersect[$i]) && isset($groupsToIntersect[$k])) {
                    if (array_intersect($groupsToIntersect[$i], $groupsToIntersect[$k])) {
                        $groupsToIntersect[$i] = array_merge($groupsToIntersect[$i], $groupsToIntersect[$k]);
                        unset($groupsToIntersect[$k]);
                        if (!$doMinusMinus) {
                            $i--;
                            $doMinusMinus = true;
                        }

                    }
                }
            }

            if ($doMinusMinus) {
                //Reset array structure
                $groupsToIntersect = array_values($groupsToIntersect);
            }
        }


        return array_map('array_unique', $groupsToIntersect);
    }
    
}
