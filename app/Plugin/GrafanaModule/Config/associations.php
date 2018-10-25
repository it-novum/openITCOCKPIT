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

$config = [];
$config = [
    'associations' => [
        //'callbacks' => ['beforeFind', 'beforeDelete'],
        'Host'      => [ // In wich model do we need the association
            'callbacks' => ['beforeDelete'], // in wich AppModel callback we want to set our binding
            'hasOne'    => [
                'GrafanaDashboard' => [
                    'className'    => 'GrafanaModule.GrafanaDashboard',
                    'foreignKey'   => 'host_id',
                    'dependent'    => true,
                    'conditions'   => '',
                    'fields'       => '',
                    'order'        => '',
                    'limit'        => '',
                    'offset'       => '',
                    'exclusive'    => '',
                    'finderQuery'  => '',
                    'counterQuery' => '',
                ],
            ],
        ],
        'Hostgroup' => [ // In wich model do we need the association
            'callbacks' => ['beforeDelete'],
            'hasOne'    => [ // Type of the association (this is cake default syntax for Model::bindModel())
                'GrafanaConfigurationHostgroupMembership' => [
                    'className'    => 'GrafanaModule.GrafanaConfigurationHostgroupMembership',
                    'foreignKey'   => 'hostgroup_id',
                    'dependent'    => true,
                    'conditions'   => '',
                    'fields'       => '',
                    'order'        => '',
                    'limit'        => '',
                    'offset'       => '',
                    'exclusive'    => '',
                    'finderQuery'  => '',
                    'counterQuery' => '',
                ],
            ],
        ],
    ],
];
