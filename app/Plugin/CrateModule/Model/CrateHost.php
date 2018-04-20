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


use itnovum\openITCOCKPIT\Core\HostConditions;

class CrateHost extends CrateModuleAppModel {

    public $useDbConfig = 'Crate';
    public $useTable = 'hosts';
    public $tablePrefix = 'openitcockpit_';

    /**
     * @param HostConditions $HostConditions
     * @param array $conditions
     * @return array
     */
    public function getHostNotMonitoredQuery(HostConditions $HostConditions, $conditions = []){
        $query = [
            'joins' => [
                [
                    'table' => 'statusengine_hoststatus',
                    'type' => 'LEFT',
                    'alias' => 'Hoststatus',
                    'conditions' => 'Host.uuid = Hoststatus.hostname',
                ]
            ],
            'conditions' => $conditions,
            'array_difference' => [
                'Host.container_ids' =>
                    $HostConditions->getContainerIds(),
            ],
            'order' => $HostConditions->getOrder()
        ];
        $query['conditions']['Host.disabled'] = (bool)$HostConditions->includeDisabled();
        $query['conditions'][] = 'Hoststatus.hostname IS NULL';
        return $query;
    }


}
