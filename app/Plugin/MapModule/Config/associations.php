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

$config = [
    'associations' => [
        //'callbacks' => ['beforeFind', 'beforeDelete'],
        'Container'    => [ // In wich model do we need the association
            'callbacks'           => ['beforeDelete'], // in wich AppModel callback we want to set our binding
            'hasAndBelongsToMany' => [ // Type of the association (this is cake default syntax for Model::bindModel())
                'Map' => [
                    'className'             => 'MapModule.Map',
                    'foreignKey'            => 'container_id',
                    'joinTable'             => 'maps_to_containers',
                    'associationForeignKey' => 'map_id',
                    'dependent'             => true,
                    'unique'                => true,
                    'conditions'            => '',
                    'fields'                => '',
                    'order'                 => '',
                    'limit'                 => '',
                    'offset'                => '',
                    'finderQuery'           => '',
                    'with'                  => 'MapModule.MapsToContainers',
                ],
            ],
        ],
        'Host'         => [ // In wich model do we need the association
            'callbacks' => ['beforeDelete'], // in wich AppModel callback we want to set our binding
            'hasMany'   => [ // Type of the association (this is cake default syntax for Model::bindModel())
                'Mapline'        => [
                    'className'    => 'MapModule.Mapline',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapline.type' => 'host'],
                    'fields'       => '',
                    'order'        => '',
                    'limit'        => '',
                    'offset'       => '',
                    'exclusive'    => '',
                    'finderQuery'  => '',
                    'counterQuery' => '',
                ],
                'Mapgadget'      => [
                    'className'    => 'MapModule.Mapgadget',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapgadget.type' => 'host'],
                    'fields'       => '',
                    'order'        => '',
                    'limit'        => '',
                    'offset'       => '',
                    'exclusive'    => '',
                    'finderQuery'  => '',
                    'counterQuery' => '',
                ],
                'Mapitem'        => [
                    'className'    => 'MapModule.Mapitem',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapitem.type' => 'host'],
                    'fields'       => '',
                    'order'        => '',
                    'limit'        => '',
                    'offset'       => '',
                    'exclusive'    => '',
                    'finderQuery'  => '',
                    'counterQuery' => '',
                ],
                'Mapsummaryitem' => [
                    'className'    => 'MapModule.Mapsummaryitem',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapsummaryitem.type' => 'host'],
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
        'Service'      => [ // In wich model do we need the association
            'callbacks' => ['beforeDelete'], // in wich AppModel callback we want to set our binding
            'hasMany'   => [ // Type of the association (this is cake default syntax for Model::bindModel())
                'Mapline'        => [
                    'className'    => 'MapModule.Mapline',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapline.type' => 'service'],
                    'fields'       => '',
                    'order'        => '',
                    'limit'        => '',
                    'offset'       => '',
                    'exclusive'    => '',
                    'finderQuery'  => '',
                    'counterQuery' => '',
                ],
                'Mapgadget'      => [
                    'className'    => 'MapModule.Mapgadget',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapgadget.type' => 'service'],
                    'fields'       => '',
                    'order'        => '',
                    'limit'        => '',
                    'offset'       => '',
                    'exclusive'    => '',
                    'finderQuery'  => '',
                    'counterQuery' => '',
                ],
                'Mapitem'        => [
                    'className'    => 'MapModule.Mapitem',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapitem.type' => 'service'],
                    'fields'       => '',
                    'order'        => '',
                    'limit'        => '',
                    'offset'       => '',
                    'exclusive'    => '',
                    'finderQuery'  => '',
                    'counterQuery' => '',
                ],
                'Mapsummaryitem' => [
                    'className'    => 'MapModule.Mapsummaryitem',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapsummaryitem.type' => 'service'],
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
        'Servicegroup' => [ // In wich model do we need the association
            'callbacks' => ['beforeDelete'], // in wich AppModel callback we want to set our binding
            'hasMany'   => [ // Type of the association (this is cake default syntax for Model::bindModel())
                'Mapline'        => [
                    'className'    => 'MapModule.Mapline',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapline.type' => 'servicegroup'],
                    'fields'       => '',
                    'order'        => '',
                    'limit'        => '',
                    'offset'       => '',
                    'exclusive'    => '',
                    'finderQuery'  => '',
                    'counterQuery' => '',
                ],
                'Mapgadget'      => [
                    'className'    => 'MapModule.Mapgadget',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapgadget.type' => 'servicegroup'],
                    'fields'       => '',
                    'order'        => '',
                    'limit'        => '',
                    'offset'       => '',
                    'exclusive'    => '',
                    'finderQuery'  => '',
                    'counterQuery' => '',
                ],
                'Mapitem'        => [
                    'className'    => 'MapModule.Mapitem',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapitem.type' => 'servicegroup'],
                    'fields'       => '',
                    'order'        => '',
                    'limit'        => '',
                    'offset'       => '',
                    'exclusive'    => '',
                    'finderQuery'  => '',
                    'counterQuery' => '',
                ],
                'Mapsummaryitem' => [
                    'className'    => 'MapModule.Mapsummaryitem',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapsummaryitem.type' => 'servicegroup'],
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
        'Hostgroup'    => [ // In wich model do we need the association
            'callbacks' => ['beforeDelete'], // in wich AppModel callback we want to set our binding
            'hasMany'   => [ // Type of the association (this is cake default syntax for Model::bindModel())
                'Mapline'        => [
                    'className'    => 'MapModule.Mapline',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapline.type' => 'hostgroup'],
                    'fields'       => '',
                    'order'        => '',
                    'limit'        => '',
                    'offset'       => '',
                    'exclusive'    => '',
                    'finderQuery'  => '',
                    'counterQuery' => '',
                ],
                'Mapgadget'      => [
                    'className'    => 'MapModule.Mapgadget',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapgadget.type' => 'hostgroup'],
                    'fields'       => '',
                    'order'        => '',
                    'limit'        => '',
                    'offset'       => '',
                    'exclusive'    => '',
                    'finderQuery'  => '',
                    'counterQuery' => '',
                ],
                'Mapitem'        => [
                    'className'    => 'MapModule.Mapitem',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapitem.type' => 'hostgroup'],
                    'fields'       => '',
                    'order'        => '',
                    'limit'        => '',
                    'offset'       => '',
                    'exclusive'    => '',
                    'finderQuery'  => '',
                    'counterQuery' => '',
                ],
                'Mapsummaryitem' => [
                    'className'    => 'MapModule.Mapsummaryitem',
                    'foreignKey'   => 'object_id',
                    'dependent'    => true,
                    'conditions'   => ['Mapsummaryitem.type' => 'hostgroup'],
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
