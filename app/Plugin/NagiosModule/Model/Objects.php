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

class Objects extends NagiosModuleAppModel
{
    //public $useDbConfig = 'nagios';
    public $useTable = 'objects';
    public $tablePrefix = 'nagios_';
    public $primaryKey = 'object_id';
    /*
        public $belongsTo = [
            'Uuidhost' => [
                'className' => 'Uuidhost',
                'foreignKey' => 'name1',
            ],
            'Host' => [
                'className' => 'Host',
                'foreignKey' => 'name1',
                'conditions' => array('Objects.name1 = Host.uuid')
            ],
        ];
    
    */

    public $belongsTo = [
        'Host' => [
            'className'  => 'Host',
            'foreignKey' => false,
            'conditions' => [
                'AND' => [
                    'Objects.name1 = Host.uuid',
                    'Objects.objecttype_id' => 1,
                ],

            ],
            'type'       => 'INNER',
        ],

        'Service' => [
            'className'  => 'Service',
            'foreignKey' => false,
            //'conditions' => array('Objects.name1 = Host.uuid')
            'conditions' => [
                'Objects.name2 = Service.uuid',
                'Objects.objecttype_id' => 2,
            ],
//			'type' => 'INNER'
        ],

    ];

}
