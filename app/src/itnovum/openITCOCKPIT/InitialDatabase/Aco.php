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

namespace itnovum\openITCOCKPIT\InitialDatabase;


class Aco extends Importer {

    /**
     * @property \Aco $Model
     */

    /**
     * @return bool
     */
    public function import() {
        if ($this->isTableEmpty()) {
            $data = $this->getData();
            foreach ($data as $record) {
                $this->Model->create();
                $this->Model->saveAll($record);
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getData() {
        $data = [
            (int)0    => [
                'Aco' => [
                    'id'          => '1',
                    'parent_id'   => null,
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'controllers',
                    'lft'         => '1',
                    'rght'        => '2006'
                ]
            ],
            (int)1    => [
                'Aco' => [
                    'id'          => '2',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Acknowledgements',
                    'lft'         => '2',
                    'rght'        => '23'
                ]
            ],
            (int)2    => [
                'Aco' => [
                    'id'          => '3',
                    'parent_id'   => '2',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'service',
                    'lft'         => '3',
                    'rght'        => '4'
                ]
            ],
            (int)3    => [
                'Aco' => [
                    'id'          => '4',
                    'parent_id'   => '2',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'host',
                    'lft'         => '5',
                    'rght'        => '6'
                ]
            ],
            (int)4    => [
                'Aco' => [
                    'id'          => '5',
                    'parent_id'   => '2',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '7',
                    'rght'        => '8'
                ]
            ],
            (int)5    => [
                'Aco' => [
                    'id'          => '6',
                    'parent_id'   => '2',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '9',
                    'rght'        => '10'
                ]
            ],
            (int)6    => [
                'Aco' => [
                    'id'          => '7',
                    'parent_id'   => '2',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '11',
                    'rght'        => '12'
                ]
            ],
            (int)7    => [
                'Aco' => [
                    'id'          => '8',
                    'parent_id'   => '2',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '13',
                    'rght'        => '14'
                ]
            ],
            (int)8    => [
                'Aco' => [
                    'id'          => '9',
                    'parent_id'   => '2',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '15',
                    'rght'        => '16'
                ]
            ],
            (int)9    => [
                'Aco' => [
                    'id'          => '10',
                    'parent_id'   => '2',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '17',
                    'rght'        => '18'
                ]
            ],
            (int)10   => [
                'Aco' => [
                    'id'          => '11',
                    'parent_id'   => '2',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '19',
                    'rght'        => '20'
                ]
            ],
            (int)11   => [
                'Aco' => [
                    'id'          => '12',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Administrators',
                    'lft'         => '24',
                    'rght'        => '47'
                ]
            ],
            (int)12   => [
                'Aco' => [
                    'id'          => '13',
                    'parent_id'   => '12',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '25',
                    'rght'        => '26'
                ]
            ],
            (int)13   => [
                'Aco' => [
                    'id'          => '14',
                    'parent_id'   => '12',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'debug',
                    'lft'         => '27',
                    'rght'        => '28'
                ]
            ],
            (int)14   => [
                'Aco' => [
                    'id'          => '15',
                    'parent_id'   => '12',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '29',
                    'rght'        => '30'
                ]
            ],
            (int)15   => [
                'Aco' => [
                    'id'          => '16',
                    'parent_id'   => '12',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '31',
                    'rght'        => '32'
                ]
            ],
            (int)16   => [
                'Aco' => [
                    'id'          => '17',
                    'parent_id'   => '12',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '33',
                    'rght'        => '34'
                ]
            ],
            (int)17   => [
                'Aco' => [
                    'id'          => '18',
                    'parent_id'   => '12',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '35',
                    'rght'        => '36'
                ]
            ],
            (int)18   => [
                'Aco' => [
                    'id'          => '19',
                    'parent_id'   => '12',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '37',
                    'rght'        => '38'
                ]
            ],
            (int)19   => [
                'Aco' => [
                    'id'          => '20',
                    'parent_id'   => '12',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '39',
                    'rght'        => '40'
                ]
            ],
            (int)20   => [
                'Aco' => [
                    'id'          => '21',
                    'parent_id'   => '12',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '41',
                    'rght'        => '42'
                ]
            ],
            (int)21   => [
                'Aco' => [
                    'id'          => '22',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Automaps',
                    'lft'         => '48',
                    'rght'        => '77'
                ]
            ],
            (int)22   => [
                'Aco' => [
                    'id'          => '23',
                    'parent_id'   => '22',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '49',
                    'rght'        => '50'
                ]
            ],
            (int)23   => [
                'Aco' => [
                    'id'          => '24',
                    'parent_id'   => '22',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '51',
                    'rght'        => '52'
                ]
            ],
            (int)24   => [
                'Aco' => [
                    'id'          => '25',
                    'parent_id'   => '22',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '53',
                    'rght'        => '54'
                ]
            ],
            (int)25   => [
                'Aco' => [
                    'id'          => '26',
                    'parent_id'   => '22',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '55',
                    'rght'        => '56'
                ]
            ],
            (int)26   => [
                'Aco' => [
                    'id'          => '27',
                    'parent_id'   => '22',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadServiceDetails',
                    'lft'         => '57',
                    'rght'        => '58'
                ]
            ],
            (int)27   => [
                'Aco' => [
                    'id'          => '28',
                    'parent_id'   => '22',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '59',
                    'rght'        => '60'
                ]
            ],
            (int)28   => [
                'Aco' => [
                    'id'          => '29',
                    'parent_id'   => '22',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '61',
                    'rght'        => '62'
                ]
            ],
            (int)29   => [
                'Aco' => [
                    'id'          => '30',
                    'parent_id'   => '22',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '63',
                    'rght'        => '64'
                ]
            ],
            (int)30   => [
                'Aco' => [
                    'id'          => '31',
                    'parent_id'   => '22',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '65',
                    'rght'        => '66'
                ]
            ],
            (int)31   => [
                'Aco' => [
                    'id'          => '32',
                    'parent_id'   => '22',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '67',
                    'rght'        => '68'
                ]
            ],
            (int)32   => [
                'Aco' => [
                    'id'          => '33',
                    'parent_id'   => '22',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '69',
                    'rght'        => '70'
                ]
            ],
            (int)33   => [
                'Aco' => [
                    'id'          => '34',
                    'parent_id'   => '22',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '71',
                    'rght'        => '72'
                ]
            ],
            (int)34   => [
                'Aco' => [
                    'id'          => '35',
                    'parent_id'   => '22',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '73',
                    'rght'        => '74'
                ]
            ],
            (int)35   => [
                'Aco' => [
                    'id'          => '36',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Browsers',
                    'lft'         => '78',
                    'rght'        => '99'
                ]
            ],
            (int)36   => [
                'Aco' => [
                    'id'          => '37',
                    'parent_id'   => '36',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '79',
                    'rght'        => '80'
                ]
            ],
            (int)37   => [
                'Aco' => [
                    'id'          => '38',
                    'parent_id'   => '36',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'tenantBrowser',
                    'lft'         => '81',
                    'rght'        => '82'
                ]
            ],
            (int)38   => [
                'Aco' => [
                    'id'          => '42',
                    'parent_id'   => '36',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '83',
                    'rght'        => '84'
                ]
            ],
            (int)39   => [
                'Aco' => [
                    'id'          => '43',
                    'parent_id'   => '36',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '85',
                    'rght'        => '86'
                ]
            ],
            (int)40   => [
                'Aco' => [
                    'id'          => '44',
                    'parent_id'   => '36',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '87',
                    'rght'        => '88'
                ]
            ],
            (int)41   => [
                'Aco' => [
                    'id'          => '45',
                    'parent_id'   => '36',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '89',
                    'rght'        => '90'
                ]
            ],
            (int)42   => [
                'Aco' => [
                    'id'          => '46',
                    'parent_id'   => '36',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '91',
                    'rght'        => '92'
                ]
            ],
            (int)43   => [
                'Aco' => [
                    'id'          => '47',
                    'parent_id'   => '36',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '93',
                    'rght'        => '94'
                ]
            ],
            (int)44   => [
                'Aco' => [
                    'id'          => '48',
                    'parent_id'   => '36',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '95',
                    'rght'        => '96'
                ]
            ],
            (int)45   => [
                'Aco' => [
                    'id'          => '49',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Calendars',
                    'lft'         => '100',
                    'rght'        => '129'
                ]
            ],
            (int)46   => [
                'Aco' => [
                    'id'          => '50',
                    'parent_id'   => '49',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '101',
                    'rght'        => '102'
                ]
            ],
            (int)47   => [
                'Aco' => [
                    'id'          => '51',
                    'parent_id'   => '49',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '103',
                    'rght'        => '104'
                ]
            ],
            (int)48   => [
                'Aco' => [
                    'id'          => '52',
                    'parent_id'   => '49',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '105',
                    'rght'        => '106'
                ]
            ],
            (int)49   => [
                'Aco' => [
                    'id'          => '53',
                    'parent_id'   => '49',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '107',
                    'rght'        => '108'
                ]
            ],
            (int)50   => [
                'Aco' => [
                    'id'          => '54',
                    'parent_id'   => '49',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadHolidays',
                    'lft'         => '109',
                    'rght'        => '110'
                ]
            ],
            (int)51   => [
                'Aco' => [
                    'id'          => '55',
                    'parent_id'   => '49',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_delete',
                    'lft'         => '111',
                    'rght'        => '112'
                ]
            ],
            (int)52   => [
                'Aco' => [
                    'id'          => '56',
                    'parent_id'   => '49',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '113',
                    'rght'        => '114'
                ]
            ],
            (int)53   => [
                'Aco' => [
                    'id'          => '57',
                    'parent_id'   => '49',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '115',
                    'rght'        => '116'
                ]
            ],
            (int)54   => [
                'Aco' => [
                    'id'          => '58',
                    'parent_id'   => '49',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '117',
                    'rght'        => '118'
                ]
            ],
            (int)55   => [
                'Aco' => [
                    'id'          => '59',
                    'parent_id'   => '49',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '119',
                    'rght'        => '120'
                ]
            ],
            (int)56   => [
                'Aco' => [
                    'id'          => '60',
                    'parent_id'   => '49',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '121',
                    'rght'        => '122'
                ]
            ],
            (int)57   => [
                'Aco' => [
                    'id'          => '61',
                    'parent_id'   => '49',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '123',
                    'rght'        => '124'
                ]
            ],
            (int)58   => [
                'Aco' => [
                    'id'          => '62',
                    'parent_id'   => '49',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '125',
                    'rght'        => '126'
                ]
            ],
            (int)59   => [
                'Aco' => [
                    'id'          => '63',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Category',
                    'lft'         => '130',
                    'rght'        => '149'
                ]
            ],
            (int)60   => [
                'Aco' => [
                    'id'          => '64',
                    'parent_id'   => '63',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '131',
                    'rght'        => '132'
                ]
            ],
            (int)61   => [
                'Aco' => [
                    'id'          => '65',
                    'parent_id'   => '63',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '133',
                    'rght'        => '134'
                ]
            ],
            (int)62   => [
                'Aco' => [
                    'id'          => '66',
                    'parent_id'   => '63',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '135',
                    'rght'        => '136'
                ]
            ],
            (int)63   => [
                'Aco' => [
                    'id'          => '67',
                    'parent_id'   => '63',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '137',
                    'rght'        => '138'
                ]
            ],
            (int)64   => [
                'Aco' => [
                    'id'          => '68',
                    'parent_id'   => '63',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '139',
                    'rght'        => '140'
                ]
            ],
            (int)65   => [
                'Aco' => [
                    'id'          => '69',
                    'parent_id'   => '63',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '141',
                    'rght'        => '142'
                ]
            ],
            (int)66   => [
                'Aco' => [
                    'id'          => '70',
                    'parent_id'   => '63',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '143',
                    'rght'        => '144'
                ]
            ],
            (int)67   => [
                'Aco' => [
                    'id'          => '71',
                    'parent_id'   => '63',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '145',
                    'rght'        => '146'
                ]
            ],
            (int)68   => [
                'Aco' => [
                    'id'          => '72',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Changelogs',
                    'lft'         => '150',
                    'rght'        => '169'
                ]
            ],
            (int)69   => [
                'Aco' => [
                    'id'          => '73',
                    'parent_id'   => '72',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '151',
                    'rght'        => '152'
                ]
            ],
            (int)70   => [
                'Aco' => [
                    'id'          => '74',
                    'parent_id'   => '72',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '153',
                    'rght'        => '154'
                ]
            ],
            (int)71   => [
                'Aco' => [
                    'id'          => '75',
                    'parent_id'   => '72',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '155',
                    'rght'        => '156'
                ]
            ],
            (int)72   => [
                'Aco' => [
                    'id'          => '76',
                    'parent_id'   => '72',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '157',
                    'rght'        => '158'
                ]
            ],
            (int)73   => [
                'Aco' => [
                    'id'          => '77',
                    'parent_id'   => '72',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '159',
                    'rght'        => '160'
                ]
            ],
            (int)74   => [
                'Aco' => [
                    'id'          => '78',
                    'parent_id'   => '72',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '161',
                    'rght'        => '162'
                ]
            ],
            (int)75   => [
                'Aco' => [
                    'id'          => '79',
                    'parent_id'   => '72',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '163',
                    'rght'        => '164'
                ]
            ],
            (int)76   => [
                'Aco' => [
                    'id'          => '80',
                    'parent_id'   => '72',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '165',
                    'rght'        => '166'
                ]
            ],
            (int)77   => [
                'Aco' => [
                    'id'          => '81',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Commands',
                    'lft'         => '170',
                    'rght'        => '215'
                ]
            ],
            (int)78   => [
                'Aco' => [
                    'id'          => '82',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '171',
                    'rght'        => '172'
                ]
            ],
            (int)79   => [
                'Aco' => [
                    'id'          => '83',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'hostchecks',
                    'lft'         => '173',
                    'rght'        => '174'
                ]
            ],
            (int)80   => [
                'Aco' => [
                    'id'          => '84',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'notifications',
                    'lft'         => '175',
                    'rght'        => '176'
                ]
            ],
            (int)81   => [
                'Aco' => [
                    'id'          => '85',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'handler',
                    'lft'         => '177',
                    'rght'        => '178'
                ]
            ],
            (int)82   => [
                'Aco' => [
                    'id'          => '86',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '179',
                    'rght'        => '180'
                ]
            ],
            (int)83   => [
                'Aco' => [
                    'id'          => '87',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '181',
                    'rght'        => '182'
                ]
            ],
            (int)84   => [
                'Aco' => [
                    'id'          => '88',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '183',
                    'rght'        => '184'
                ]
            ],
            (int)85   => [
                'Aco' => [
                    'id'          => '89',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_delete',
                    'lft'         => '185',
                    'rght'        => '186'
                ]
            ],
            (int)86   => [
                'Aco' => [
                    'id'          => '90',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'addCommandArg',
                    'lft'         => '187',
                    'rght'        => '188'
                ]
            ],
            (int)87   => [
                'Aco' => [
                    'id'          => '91',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadMacros',
                    'lft'         => '189',
                    'rght'        => '190'
                ]
            ],
            (int)88   => [
                'Aco' => [
                    'id'          => '92',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'terminal',
                    'lft'         => '191',
                    'rght'        => '192'
                ]
            ],
            (int)89   => [
                'Aco' => [
                    'id'          => '93',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '193',
                    'rght'        => '194'
                ]
            ],
            (int)90   => [
                'Aco' => [
                    'id'          => '94',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '195',
                    'rght'        => '196'
                ]
            ],
            (int)91   => [
                'Aco' => [
                    'id'          => '95',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '197',
                    'rght'        => '198'
                ]
            ],
            (int)92   => [
                'Aco' => [
                    'id'          => '96',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '199',
                    'rght'        => '200'
                ]
            ],
            (int)93   => [
                'Aco' => [
                    'id'          => '97',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '201',
                    'rght'        => '202'
                ]
            ],
            (int)94   => [
                'Aco' => [
                    'id'          => '98',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '203',
                    'rght'        => '204'
                ]
            ],
            (int)95   => [
                'Aco' => [
                    'id'          => '99',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '205',
                    'rght'        => '206'
                ]
            ],
            (int)96   => [
                'Aco' => [
                    'id'          => '100',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Contactgroups',
                    'lft'         => '216',
                    'rght'        => '249'
                ]
            ],
            (int)97   => [
                'Aco' => [
                    'id'          => '101',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '217',
                    'rght'        => '218'
                ]
            ],
            (int)98   => [
                'Aco' => [
                    'id'          => '102',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '219',
                    'rght'        => '220'
                ]
            ],
            (int)99   => [
                'Aco' => [
                    'id'          => '103',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '221',
                    'rght'        => '222'
                ]
            ],
            (int)100  => [
                'Aco' => [
                    'id'          => '104',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadContacts',
                    'lft'         => '223',
                    'rght'        => '224'
                ]
            ],
            (int)101  => [
                'Aco' => [
                    'id'          => '105',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '225',
                    'rght'        => '226'
                ]
            ],
            (int)102  => [
                'Aco' => [
                    'id'          => '106',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_delete',
                    'lft'         => '227',
                    'rght'        => '228'
                ]
            ],
            (int)103  => [
                'Aco' => [
                    'id'          => '107',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '229',
                    'rght'        => '230'
                ]
            ],
            (int)104  => [
                'Aco' => [
                    'id'          => '108',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '231',
                    'rght'        => '232'
                ]
            ],
            (int)105  => [
                'Aco' => [
                    'id'          => '109',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '233',
                    'rght'        => '234'
                ]
            ],
            (int)106  => [
                'Aco' => [
                    'id'          => '110',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '235',
                    'rght'        => '236'
                ]
            ],
            (int)107  => [
                'Aco' => [
                    'id'          => '111',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '237',
                    'rght'        => '238'
                ]
            ],
            (int)108  => [
                'Aco' => [
                    'id'          => '112',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '239',
                    'rght'        => '240'
                ]
            ],
            (int)109  => [
                'Aco' => [
                    'id'          => '113',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '241',
                    'rght'        => '242'
                ]
            ],
            (int)110  => [
                'Aco' => [
                    'id'          => '114',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Contacts',
                    'lft'         => '250',
                    'rght'        => '285'
                ]
            ],
            (int)111  => [
                'Aco' => [
                    'id'          => '115',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '251',
                    'rght'        => '252'
                ]
            ],
            (int)112  => [
                'Aco' => [
                    'id'          => '116',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '253',
                    'rght'        => '254'
                ]
            ],
            (int)113  => [
                'Aco' => [
                    'id'          => '117',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '255',
                    'rght'        => '256'
                ]
            ],
            (int)114  => [
                'Aco' => [
                    'id'          => '118',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '257',
                    'rght'        => '258'
                ]
            ],
            (int)115  => [
                'Aco' => [
                    'id'          => '119',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_delete',
                    'lft'         => '259',
                    'rght'        => '260'
                ]
            ],
            (int)116  => [
                'Aco' => [
                    'id'          => '120',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadTimeperiods',
                    'lft'         => '261',
                    'rght'        => '262'
                ]
            ],
            (int)117  => [
                'Aco' => [
                    'id'          => '121',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '263',
                    'rght'        => '264'
                ]
            ],
            (int)118  => [
                'Aco' => [
                    'id'          => '122',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '265',
                    'rght'        => '266'
                ]
            ],
            (int)119  => [
                'Aco' => [
                    'id'          => '123',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '267',
                    'rght'        => '268'
                ]
            ],
            (int)120  => [
                'Aco' => [
                    'id'          => '124',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '269',
                    'rght'        => '270'
                ]
            ],
            (int)121  => [
                'Aco' => [
                    'id'          => '125',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '271',
                    'rght'        => '272'
                ]
            ],
            (int)122  => [
                'Aco' => [
                    'id'          => '126',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '273',
                    'rght'        => '274'
                ]
            ],
            (int)123  => [
                'Aco' => [
                    'id'          => '127',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '275',
                    'rght'        => '276'
                ]
            ],
            (int)124  => [
                'Aco' => [
                    'id'          => '128',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Containers',
                    'lft'         => '286',
                    'rght'        => '317'
                ]
            ],
            (int)125  => [
                'Aco' => [
                    'id'          => '129',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '287',
                    'rght'        => '288'
                ]
            ],
            (int)126  => [
                'Aco' => [
                    'id'          => '130',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '289',
                    'rght'        => '290'
                ]
            ],
            (int)127  => [
                'Aco' => [
                    'id'          => '131',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'byTenant',
                    'lft'         => '291',
                    'rght'        => '292'
                ]
            ],
            (int)128  => [
                'Aco' => [
                    'id'          => '132',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'byTenantForSelect',
                    'lft'         => '293',
                    'rght'        => '294'
                ]
            ],
            (int)129  => [
                'Aco' => [
                    'id'          => '133',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '295',
                    'rght'        => '296'
                ]
            ],
            (int)130  => [
                'Aco' => [
                    'id'          => '134',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '297',
                    'rght'        => '298'
                ]
            ],
            (int)131  => [
                'Aco' => [
                    'id'          => '135',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '299',
                    'rght'        => '300'
                ]
            ],
            (int)132  => [
                'Aco' => [
                    'id'          => '136',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '301',
                    'rght'        => '302'
                ]
            ],
            (int)133  => [
                'Aco' => [
                    'id'          => '137',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '303',
                    'rght'        => '304'
                ]
            ],
            (int)134  => [
                'Aco' => [
                    'id'          => '138',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '305',
                    'rght'        => '306'
                ]
            ],
            (int)135  => [
                'Aco' => [
                    'id'          => '139',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '307',
                    'rght'        => '308'
                ]
            ],
            (int)136  => [
                'Aco' => [
                    'id'          => '140',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '309',
                    'rght'        => '310'
                ]
            ],
            (int)137  => [
                'Aco' => [
                    'id'          => '141',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Cronjobs',
                    'lft'         => '318',
                    'rght'        => '345'
                ]
            ],
            (int)138  => [
                'Aco' => [
                    'id'          => '142',
                    'parent_id'   => '141',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '319',
                    'rght'        => '320'
                ]
            ],
            (int)139  => [
                'Aco' => [
                    'id'          => '143',
                    'parent_id'   => '141',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '321',
                    'rght'        => '322'
                ]
            ],
            (int)140  => [
                'Aco' => [
                    'id'          => '144',
                    'parent_id'   => '141',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '323',
                    'rght'        => '324'
                ]
            ],
            (int)141  => [
                'Aco' => [
                    'id'          => '145',
                    'parent_id'   => '141',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '325',
                    'rght'        => '326'
                ]
            ],
            (int)142  => [
                'Aco' => [
                    'id'          => '146',
                    'parent_id'   => '141',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getTasks',
                    'lft'         => '327',
                    'rght'        => '328'
                ]
            ],
            (int)143  => [
                'Aco' => [
                    'id'          => '147',
                    'parent_id'   => '141',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '329',
                    'rght'        => '330'
                ]
            ],
            (int)144  => [
                'Aco' => [
                    'id'          => '148',
                    'parent_id'   => '141',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '331',
                    'rght'        => '332'
                ]
            ],
            (int)145  => [
                'Aco' => [
                    'id'          => '149',
                    'parent_id'   => '141',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '333',
                    'rght'        => '334'
                ]
            ],
            (int)146  => [
                'Aco' => [
                    'id'          => '150',
                    'parent_id'   => '141',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '335',
                    'rght'        => '336'
                ]
            ],
            (int)147  => [
                'Aco' => [
                    'id'          => '151',
                    'parent_id'   => '141',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '337',
                    'rght'        => '338'
                ]
            ],
            (int)148  => [
                'Aco' => [
                    'id'          => '152',
                    'parent_id'   => '141',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '339',
                    'rght'        => '340'
                ]
            ],
            (int)149  => [
                'Aco' => [
                    'id'          => '153',
                    'parent_id'   => '141',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '341',
                    'rght'        => '342'
                ]
            ],
            (int)150  => [
                'Aco' => [
                    'id'          => '154',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Currentstatereports',
                    'lft'         => '346',
                    'rght'        => '367'
                ]
            ],
            (int)151  => [
                'Aco' => [
                    'id'          => '155',
                    'parent_id'   => '154',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '347',
                    'rght'        => '348'
                ]
            ],
            (int)152  => [
                'Aco' => [
                    'id'          => '156',
                    'parent_id'   => '154',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'createPdfReport',
                    'lft'         => '349',
                    'rght'        => '350'
                ]
            ],
            (int)153  => [
                'Aco' => [
                    'id'          => '157',
                    'parent_id'   => '154',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '351',
                    'rght'        => '352'
                ]
            ],
            (int)154  => [
                'Aco' => [
                    'id'          => '158',
                    'parent_id'   => '154',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '353',
                    'rght'        => '354'
                ]
            ],
            (int)155  => [
                'Aco' => [
                    'id'          => '159',
                    'parent_id'   => '154',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '355',
                    'rght'        => '356'
                ]
            ],
            (int)156  => [
                'Aco' => [
                    'id'          => '160',
                    'parent_id'   => '154',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '357',
                    'rght'        => '358'
                ]
            ],
            (int)157  => [
                'Aco' => [
                    'id'          => '161',
                    'parent_id'   => '154',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '359',
                    'rght'        => '360'
                ]
            ],
            (int)158  => [
                'Aco' => [
                    'id'          => '162',
                    'parent_id'   => '154',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '361',
                    'rght'        => '362'
                ]
            ],
            (int)159  => [
                'Aco' => [
                    'id'          => '163',
                    'parent_id'   => '154',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '363',
                    'rght'        => '364'
                ]
            ],
            (int)160  => [
                'Aco' => [
                    'id'          => '164',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'DeletedHosts',
                    'lft'         => '368',
                    'rght'        => '387'
                ]
            ],
            (int)161  => [
                'Aco' => [
                    'id'          => '165',
                    'parent_id'   => '164',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '369',
                    'rght'        => '370'
                ]
            ],
            (int)162  => [
                'Aco' => [
                    'id'          => '166',
                    'parent_id'   => '164',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '371',
                    'rght'        => '372'
                ]
            ],
            (int)163  => [
                'Aco' => [
                    'id'          => '167',
                    'parent_id'   => '164',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '373',
                    'rght'        => '374'
                ]
            ],
            (int)164  => [
                'Aco' => [
                    'id'          => '168',
                    'parent_id'   => '164',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '375',
                    'rght'        => '376'
                ]
            ],
            (int)165  => [
                'Aco' => [
                    'id'          => '169',
                    'parent_id'   => '164',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '377',
                    'rght'        => '378'
                ]
            ],
            (int)166  => [
                'Aco' => [
                    'id'          => '170',
                    'parent_id'   => '164',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '379',
                    'rght'        => '380'
                ]
            ],
            (int)167  => [
                'Aco' => [
                    'id'          => '171',
                    'parent_id'   => '164',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '381',
                    'rght'        => '382'
                ]
            ],
            (int)168  => [
                'Aco' => [
                    'id'          => '172',
                    'parent_id'   => '164',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '383',
                    'rght'        => '384'
                ]
            ],
            (int)169  => [
                'Aco' => [
                    'id'          => '185',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Documentations',
                    'lft'         => '388',
                    'rght'        => '411'
                ]
            ],
            (int)170  => [
                'Aco' => [
                    'id'          => '186',
                    'parent_id'   => '185',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '389',
                    'rght'        => '390'
                ]
            ],
            (int)171  => [
                'Aco' => [
                    'id'          => '187',
                    'parent_id'   => '185',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '391',
                    'rght'        => '392'
                ]
            ],
            (int)172  => [
                'Aco' => [
                    'id'          => '188',
                    'parent_id'   => '185',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'wiki',
                    'lft'         => '393',
                    'rght'        => '394'
                ]
            ],
            (int)173  => [
                'Aco' => [
                    'id'          => '189',
                    'parent_id'   => '185',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '395',
                    'rght'        => '396'
                ]
            ],
            (int)174  => [
                'Aco' => [
                    'id'          => '190',
                    'parent_id'   => '185',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '397',
                    'rght'        => '398'
                ]
            ],
            (int)175  => [
                'Aco' => [
                    'id'          => '191',
                    'parent_id'   => '185',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '399',
                    'rght'        => '400'
                ]
            ],
            (int)176  => [
                'Aco' => [
                    'id'          => '192',
                    'parent_id'   => '185',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '401',
                    'rght'        => '402'
                ]
            ],
            (int)177  => [
                'Aco' => [
                    'id'          => '193',
                    'parent_id'   => '185',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '403',
                    'rght'        => '404'
                ]
            ],
            (int)178  => [
                'Aco' => [
                    'id'          => '194',
                    'parent_id'   => '185',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '405',
                    'rght'        => '406'
                ]
            ],
            (int)179  => [
                'Aco' => [
                    'id'          => '195',
                    'parent_id'   => '185',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '407',
                    'rght'        => '408'
                ]
            ],
            (int)180  => [
                'Aco' => [
                    'id'          => '196',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Downtimereports',
                    'lft'         => '412',
                    'rght'        => '433'
                ]
            ],
            (int)181  => [
                'Aco' => [
                    'id'          => '197',
                    'parent_id'   => '196',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '413',
                    'rght'        => '414'
                ]
            ],
            (int)182  => [
                'Aco' => [
                    'id'          => '198',
                    'parent_id'   => '196',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'createPdfReport',
                    'lft'         => '415',
                    'rght'        => '416'
                ]
            ],
            (int)183  => [
                'Aco' => [
                    'id'          => '199',
                    'parent_id'   => '196',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '417',
                    'rght'        => '418'
                ]
            ],
            (int)184  => [
                'Aco' => [
                    'id'          => '200',
                    'parent_id'   => '196',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '419',
                    'rght'        => '420'
                ]
            ],
            (int)185  => [
                'Aco' => [
                    'id'          => '201',
                    'parent_id'   => '196',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '421',
                    'rght'        => '422'
                ]
            ],
            (int)186  => [
                'Aco' => [
                    'id'          => '202',
                    'parent_id'   => '196',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '423',
                    'rght'        => '424'
                ]
            ],
            (int)187  => [
                'Aco' => [
                    'id'          => '203',
                    'parent_id'   => '196',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '425',
                    'rght'        => '426'
                ]
            ],
            (int)188  => [
                'Aco' => [
                    'id'          => '204',
                    'parent_id'   => '196',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '427',
                    'rght'        => '428'
                ]
            ],
            (int)189  => [
                'Aco' => [
                    'id'          => '205',
                    'parent_id'   => '196',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '429',
                    'rght'        => '430'
                ]
            ],
            (int)190  => [
                'Aco' => [
                    'id'          => '206',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Downtimes',
                    'lft'         => '434',
                    'rght'        => '459'
                ]
            ],
            (int)191  => [
                'Aco' => [
                    'id'          => '207',
                    'parent_id'   => '206',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'host',
                    'lft'         => '435',
                    'rght'        => '436'
                ]
            ],
            (int)192  => [
                'Aco' => [
                    'id'          => '208',
                    'parent_id'   => '206',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'service',
                    'lft'         => '437',
                    'rght'        => '438'
                ]
            ],
            (int)193  => [
                'Aco' => [
                    'id'          => '209',
                    'parent_id'   => '206',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '439',
                    'rght'        => '440'
                ]
            ],
            (int)194  => [
                'Aco' => [
                    'id'          => '210',
                    'parent_id'   => '206',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'validateDowntimeInputFromBrowser',
                    'lft'         => '441',
                    'rght'        => '442'
                ]
            ],
            (int)195  => [
                'Aco' => [
                    'id'          => '211',
                    'parent_id'   => '206',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '443',
                    'rght'        => '444'
                ]
            ],
            (int)196  => [
                'Aco' => [
                    'id'          => '212',
                    'parent_id'   => '206',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '445',
                    'rght'        => '446'
                ]
            ],
            (int)197  => [
                'Aco' => [
                    'id'          => '213',
                    'parent_id'   => '206',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '447',
                    'rght'        => '448'
                ]
            ],
            (int)198  => [
                'Aco' => [
                    'id'          => '214',
                    'parent_id'   => '206',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '449',
                    'rght'        => '450'
                ]
            ],
            (int)199  => [
                'Aco' => [
                    'id'          => '215',
                    'parent_id'   => '206',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '451',
                    'rght'        => '452'
                ]
            ],
            (int)200  => [
                'Aco' => [
                    'id'          => '216',
                    'parent_id'   => '206',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '453',
                    'rght'        => '454'
                ]
            ],
            (int)201  => [
                'Aco' => [
                    'id'          => '217',
                    'parent_id'   => '206',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '455',
                    'rght'        => '456'
                ]
            ],
            (int)202  => [
                'Aco' => [
                    'id'          => '218',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Exports',
                    'lft'         => '460',
                    'rght'        => '487'
                ]
            ],
            (int)203  => [
                'Aco' => [
                    'id'          => '219',
                    'parent_id'   => '218',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '461',
                    'rght'        => '462'
                ]
            ],
            (int)204  => [
                'Aco' => [
                    'id'          => '220',
                    'parent_id'   => '218',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '463',
                    'rght'        => '464'
                ]
            ],
            (int)205  => [
                'Aco' => [
                    'id'          => '221',
                    'parent_id'   => '218',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '465',
                    'rght'        => '466'
                ]
            ],
            (int)206  => [
                'Aco' => [
                    'id'          => '222',
                    'parent_id'   => '218',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '467',
                    'rght'        => '468'
                ]
            ],
            (int)207  => [
                'Aco' => [
                    'id'          => '223',
                    'parent_id'   => '218',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '469',
                    'rght'        => '470'
                ]
            ],
            (int)208  => [
                'Aco' => [
                    'id'          => '224',
                    'parent_id'   => '218',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '471',
                    'rght'        => '472'
                ]
            ],
            (int)209  => [
                'Aco' => [
                    'id'          => '225',
                    'parent_id'   => '218',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '473',
                    'rght'        => '474'
                ]
            ],
            (int)210  => [
                'Aco' => [
                    'id'          => '226',
                    'parent_id'   => '218',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '475',
                    'rght'        => '476'
                ]
            ],
            (int)211  => [
                'Aco' => [
                    'id'          => '227',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Forward',
                    'lft'         => '488',
                    'rght'        => '507'
                ]
            ],
            (int)212  => [
                'Aco' => [
                    'id'          => '228',
                    'parent_id'   => '227',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '489',
                    'rght'        => '490'
                ]
            ],
            (int)213  => [
                'Aco' => [
                    'id'          => '229',
                    'parent_id'   => '227',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '491',
                    'rght'        => '492'
                ]
            ],
            (int)214  => [
                'Aco' => [
                    'id'          => '230',
                    'parent_id'   => '227',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '493',
                    'rght'        => '494'
                ]
            ],
            (int)215  => [
                'Aco' => [
                    'id'          => '231',
                    'parent_id'   => '227',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '495',
                    'rght'        => '496'
                ]
            ],
            (int)216  => [
                'Aco' => [
                    'id'          => '232',
                    'parent_id'   => '227',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '497',
                    'rght'        => '498'
                ]
            ],
            (int)217  => [
                'Aco' => [
                    'id'          => '233',
                    'parent_id'   => '227',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '499',
                    'rght'        => '500'
                ]
            ],
            (int)218  => [
                'Aco' => [
                    'id'          => '234',
                    'parent_id'   => '227',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '501',
                    'rght'        => '502'
                ]
            ],
            (int)219  => [
                'Aco' => [
                    'id'          => '235',
                    'parent_id'   => '227',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '503',
                    'rght'        => '504'
                ]
            ],
            (int)220  => [
                'Aco' => [
                    'id'          => '236',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'GraphCollections',
                    'lft'         => '508',
                    'rght'        => '537'
                ]
            ],
            (int)221  => [
                'Aco' => [
                    'id'          => '237',
                    'parent_id'   => '236',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '509',
                    'rght'        => '510'
                ]
            ],
            (int)222  => [
                'Aco' => [
                    'id'          => '238',
                    'parent_id'   => '236',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '511',
                    'rght'        => '512'
                ]
            ],
            (int)223  => [
                'Aco' => [
                    'id'          => '239',
                    'parent_id'   => '236',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'display',
                    'lft'         => '513',
                    'rght'        => '514'
                ]
            ],
            (int)224  => [
                'Aco' => [
                    'id'          => '240',
                    'parent_id'   => '236',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_delete',
                    'lft'         => '515',
                    'rght'        => '516'
                ]
            ],
            (int)225  => [
                'Aco' => [
                    'id'          => '241',
                    'parent_id'   => '236',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadCollectionGraphData',
                    'lft'         => '517',
                    'rght'        => '518'
                ]
            ],
            (int)226  => [
                'Aco' => [
                    'id'          => '242',
                    'parent_id'   => '236',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '519',
                    'rght'        => '520'
                ]
            ],
            (int)227  => [
                'Aco' => [
                    'id'          => '243',
                    'parent_id'   => '236',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '521',
                    'rght'        => '522'
                ]
            ],
            (int)228  => [
                'Aco' => [
                    'id'          => '244',
                    'parent_id'   => '236',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '523',
                    'rght'        => '524'
                ]
            ],
            (int)229  => [
                'Aco' => [
                    'id'          => '245',
                    'parent_id'   => '236',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '525',
                    'rght'        => '526'
                ]
            ],
            (int)230  => [
                'Aco' => [
                    'id'          => '246',
                    'parent_id'   => '236',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '527',
                    'rght'        => '528'
                ]
            ],
            (int)231  => [
                'Aco' => [
                    'id'          => '247',
                    'parent_id'   => '236',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '529',
                    'rght'        => '530'
                ]
            ],
            (int)232  => [
                'Aco' => [
                    'id'          => '248',
                    'parent_id'   => '236',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '531',
                    'rght'        => '532'
                ]
            ],
            (int)233  => [
                'Aco' => [
                    'id'          => '249',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Graphgenerators',
                    'lft'         => '538',
                    'rght'        => '575'
                ]
            ],
            (int)234  => [
                'Aco' => [
                    'id'          => '250',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '539',
                    'rght'        => '540'
                ]
            ],
            (int)235  => [
                'Aco' => [
                    'id'          => '251',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'listing',
                    'lft'         => '541',
                    'rght'        => '542'
                ]
            ],
            (int)236  => [
                'Aco' => [
                    'id'          => '252',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_delete',
                    'lft'         => '543',
                    'rght'        => '544'
                ]
            ],
            (int)237  => [
                'Aco' => [
                    'id'          => '253',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'saveGraphTemplate',
                    'lft'         => '545',
                    'rght'        => '546'
                ]
            ],
            (int)238  => [
                'Aco' => [
                    'id'          => '254',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadGraphTemplate',
                    'lft'         => '547',
                    'rght'        => '548'
                ]
            ],
            (int)239  => [
                'Aco' => [
                    'id'          => '255',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadServicesByHostId',
                    'lft'         => '549',
                    'rght'        => '550'
                ]
            ],
            (int)240  => [
                'Aco' => [
                    'id'          => '256',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadPerfDataStructures',
                    'lft'         => '551',
                    'rght'        => '552'
                ]
            ],
            (int)241  => [
                'Aco' => [
                    'id'          => '257',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadServiceruleFromService',
                    'lft'         => '553',
                    'rght'        => '554'
                ]
            ],
            (int)242  => [
                'Aco' => [
                    'id'          => '258',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'fetchGraphData',
                    'lft'         => '555',
                    'rght'        => '556'
                ]
            ],
            (int)243  => [
                'Aco' => [
                    'id'          => '259',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '557',
                    'rght'        => '558'
                ]
            ],
            (int)244  => [
                'Aco' => [
                    'id'          => '260',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '559',
                    'rght'        => '560'
                ]
            ],
            (int)245  => [
                'Aco' => [
                    'id'          => '261',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '561',
                    'rght'        => '562'
                ]
            ],
            (int)246  => [
                'Aco' => [
                    'id'          => '262',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '563',
                    'rght'        => '564'
                ]
            ],
            (int)247  => [
                'Aco' => [
                    'id'          => '263',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '565',
                    'rght'        => '566'
                ]
            ],
            (int)248  => [
                'Aco' => [
                    'id'          => '264',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '567',
                    'rght'        => '568'
                ]
            ],
            (int)249  => [
                'Aco' => [
                    'id'          => '265',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '569',
                    'rght'        => '570'
                ]
            ],
            (int)250  => [
                'Aco' => [
                    'id'          => '266',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Hostchecks',
                    'lft'         => '576',
                    'rght'        => '595'
                ]
            ],
            (int)251  => [
                'Aco' => [
                    'id'          => '267',
                    'parent_id'   => '266',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '577',
                    'rght'        => '578'
                ]
            ],
            (int)252  => [
                'Aco' => [
                    'id'          => '268',
                    'parent_id'   => '266',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '579',
                    'rght'        => '580'
                ]
            ],
            (int)253  => [
                'Aco' => [
                    'id'          => '269',
                    'parent_id'   => '266',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '581',
                    'rght'        => '582'
                ]
            ],
            (int)254  => [
                'Aco' => [
                    'id'          => '270',
                    'parent_id'   => '266',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '583',
                    'rght'        => '584'
                ]
            ],
            (int)255  => [
                'Aco' => [
                    'id'          => '271',
                    'parent_id'   => '266',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '585',
                    'rght'        => '586'
                ]
            ],
            (int)256  => [
                'Aco' => [
                    'id'          => '272',
                    'parent_id'   => '266',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '587',
                    'rght'        => '588'
                ]
            ],
            (int)257  => [
                'Aco' => [
                    'id'          => '273',
                    'parent_id'   => '266',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '589',
                    'rght'        => '590'
                ]
            ],
            (int)258  => [
                'Aco' => [
                    'id'          => '274',
                    'parent_id'   => '266',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '591',
                    'rght'        => '592'
                ]
            ],
            (int)259  => [
                'Aco' => [
                    'id'          => '275',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Hostdependencies',
                    'lft'         => '596',
                    'rght'        => '625'
                ]
            ],
            (int)260  => [
                'Aco' => [
                    'id'          => '276',
                    'parent_id'   => '275',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '597',
                    'rght'        => '598'
                ]
            ],
            (int)261  => [
                'Aco' => [
                    'id'          => '277',
                    'parent_id'   => '275',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '599',
                    'rght'        => '600'
                ]
            ],
            (int)262  => [
                'Aco' => [
                    'id'          => '278',
                    'parent_id'   => '275',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '601',
                    'rght'        => '602'
                ]
            ],
            (int)263  => [
                'Aco' => [
                    'id'          => '279',
                    'parent_id'   => '275',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '603',
                    'rght'        => '604'
                ]
            ],
            (int)264  => [
                'Aco' => [
                    'id'          => '280',
                    'parent_id'   => '275',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadElementsByContainerId',
                    'lft'         => '605',
                    'rght'        => '606'
                ]
            ],
            (int)265  => [
                'Aco' => [
                    'id'          => '281',
                    'parent_id'   => '275',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '607',
                    'rght'        => '608'
                ]
            ],
            (int)266  => [
                'Aco' => [
                    'id'          => '282',
                    'parent_id'   => '275',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '609',
                    'rght'        => '610'
                ]
            ],
            (int)267  => [
                'Aco' => [
                    'id'          => '283',
                    'parent_id'   => '275',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '611',
                    'rght'        => '612'
                ]
            ],
            (int)268  => [
                'Aco' => [
                    'id'          => '284',
                    'parent_id'   => '275',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '613',
                    'rght'        => '614'
                ]
            ],
            (int)269  => [
                'Aco' => [
                    'id'          => '285',
                    'parent_id'   => '275',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '615',
                    'rght'        => '616'
                ]
            ],
            (int)270  => [
                'Aco' => [
                    'id'          => '286',
                    'parent_id'   => '275',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '617',
                    'rght'        => '618'
                ]
            ],
            (int)271  => [
                'Aco' => [
                    'id'          => '287',
                    'parent_id'   => '275',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '619',
                    'rght'        => '620'
                ]
            ],
            (int)272  => [
                'Aco' => [
                    'id'          => '288',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Hostescalations',
                    'lft'         => '626',
                    'rght'        => '655'
                ]
            ],
            (int)273  => [
                'Aco' => [
                    'id'          => '289',
                    'parent_id'   => '288',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '627',
                    'rght'        => '628'
                ]
            ],
            (int)274  => [
                'Aco' => [
                    'id'          => '290',
                    'parent_id'   => '288',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '629',
                    'rght'        => '630'
                ]
            ],
            (int)275  => [
                'Aco' => [
                    'id'          => '291',
                    'parent_id'   => '288',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '631',
                    'rght'        => '632'
                ]
            ],
            (int)276  => [
                'Aco' => [
                    'id'          => '292',
                    'parent_id'   => '288',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '633',
                    'rght'        => '634'
                ]
            ],
            (int)277  => [
                'Aco' => [
                    'id'          => '293',
                    'parent_id'   => '288',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadElementsByContainerId',
                    'lft'         => '635',
                    'rght'        => '636'
                ]
            ],
            (int)278  => [
                'Aco' => [
                    'id'          => '294',
                    'parent_id'   => '288',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '637',
                    'rght'        => '638'
                ]
            ],
            (int)279  => [
                'Aco' => [
                    'id'          => '295',
                    'parent_id'   => '288',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '639',
                    'rght'        => '640'
                ]
            ],
            (int)280  => [
                'Aco' => [
                    'id'          => '296',
                    'parent_id'   => '288',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '641',
                    'rght'        => '642'
                ]
            ],
            (int)281  => [
                'Aco' => [
                    'id'          => '297',
                    'parent_id'   => '288',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '643',
                    'rght'        => '644'
                ]
            ],
            (int)282  => [
                'Aco' => [
                    'id'          => '298',
                    'parent_id'   => '288',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '645',
                    'rght'        => '646'
                ]
            ],
            (int)283  => [
                'Aco' => [
                    'id'          => '299',
                    'parent_id'   => '288',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '647',
                    'rght'        => '648'
                ]
            ],
            (int)284  => [
                'Aco' => [
                    'id'          => '300',
                    'parent_id'   => '288',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '649',
                    'rght'        => '650'
                ]
            ],
            (int)285  => [
                'Aco' => [
                    'id'          => '301',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Hostgroups',
                    'lft'         => '656',
                    'rght'        => '697'
                ]
            ],
            (int)286  => [
                'Aco' => [
                    'id'          => '302',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '657',
                    'rght'        => '658'
                ]
            ],
            (int)287  => [
                'Aco' => [
                    'id'          => '303',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'extended',
                    'lft'         => '659',
                    'rght'        => '660'
                ]
            ],
            (int)288  => [
                'Aco' => [
                    'id'          => '304',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '661',
                    'rght'        => '662'
                ]
            ],
            (int)289  => [
                'Aco' => [
                    'id'          => '305',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '663',
                    'rght'        => '664'
                ]
            ],
            (int)290  => [
                'Aco' => [
                    'id'          => '306',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadHosts',
                    'lft'         => '665',
                    'rght'        => '666'
                ]
            ],
            (int)291  => [
                'Aco' => [
                    'id'          => '307',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '667',
                    'rght'        => '668'
                ]
            ],
            (int)292  => [
                'Aco' => [
                    'id'          => '308',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_add',
                    'lft'         => '669',
                    'rght'        => '670'
                ]
            ],
            (int)293  => [
                'Aco' => [
                    'id'          => '309',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_delete',
                    'lft'         => '671',
                    'rght'        => '672'
                ]
            ],
            (int)294  => [
                'Aco' => [
                    'id'          => '310',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'listToPdf',
                    'lft'         => '673',
                    'rght'        => '674'
                ]
            ],
            (int)295  => [
                'Aco' => [
                    'id'          => '311',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '675',
                    'rght'        => '676'
                ]
            ],
            (int)296  => [
                'Aco' => [
                    'id'          => '312',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '677',
                    'rght'        => '678'
                ]
            ],
            (int)297  => [
                'Aco' => [
                    'id'          => '313',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '679',
                    'rght'        => '680'
                ]
            ],
            (int)298  => [
                'Aco' => [
                    'id'          => '314',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '681',
                    'rght'        => '682'
                ]
            ],
            (int)299  => [
                'Aco' => [
                    'id'          => '315',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '683',
                    'rght'        => '684'
                ]
            ],
            (int)300  => [
                'Aco' => [
                    'id'          => '316',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '685',
                    'rght'        => '686'
                ]
            ],
            (int)301  => [
                'Aco' => [
                    'id'          => '317',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '687',
                    'rght'        => '688'
                ]
            ],
            (int)302  => [
                'Aco' => [
                    'id'          => '318',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Hosts',
                    'lft'         => '698',
                    'rght'        => '783'
                ]
            ],
            (int)303  => [
                'Aco' => [
                    'id'          => '319',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '699',
                    'rght'        => '700'
                ]
            ],
            (int)304  => [
                'Aco' => [
                    'id'          => '320',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'notMonitored',
                    'lft'         => '701',
                    'rght'        => '702'
                ]
            ],
            (int)305  => [
                'Aco' => [
                    'id'          => '321',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '703',
                    'rght'        => '704'
                ]
            ],
            (int)306  => [
                'Aco' => [
                    'id'          => '322',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'sharing',
                    'lft'         => '705',
                    'rght'        => '706'
                ]
            ],
            (int)307  => [
                'Aco' => [
                    'id'          => '323',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit_details',
                    'lft'         => '707',
                    'rght'        => '708'
                ]
            ],
            (int)308  => [
                'Aco' => [
                    'id'          => '324',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '709',
                    'rght'        => '710'
                ]
            ],
            (int)309  => [
                'Aco' => [
                    'id'          => '325',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'disabled',
                    'lft'         => '711',
                    'rght'        => '712'
                ]
            ],
            (int)310  => [
                'Aco' => [
                    'id'          => '326',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'deactivate',
                    'lft'         => '713',
                    'rght'        => '714'
                ]
            ],
            (int)311  => [
                'Aco' => [
                    'id'          => '327',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_deactivate',
                    'lft'         => '715',
                    'rght'        => '716'
                ]
            ],
            (int)312  => [
                'Aco' => [
                    'id'          => '328',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'enable',
                    'lft'         => '717',
                    'rght'        => '718'
                ]
            ],
            (int)313  => [
                'Aco' => [
                    'id'          => '329',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '719',
                    'rght'        => '720'
                ]
            ],
            (int)314  => [
                'Aco' => [
                    'id'          => '330',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_delete',
                    'lft'         => '721',
                    'rght'        => '722'
                ]
            ],
            (int)315  => [
                'Aco' => [
                    'id'          => '331',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'copy',
                    'lft'         => '723',
                    'rght'        => '724'
                ]
            ],
            (int)316  => [
                'Aco' => [
                    'id'          => '332',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'browser',
                    'lft'         => '725',
                    'rght'        => '726'
                ]
            ],
            (int)317  => [
                'Aco' => [
                    'id'          => '333',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'longOutputByUuid',
                    'lft'         => '727',
                    'rght'        => '728'
                ]
            ],
            (int)318  => [
                'Aco' => [
                    'id'          => '334',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'gethostbyname',
                    'lft'         => '729',
                    'rght'        => '730'
                ]
            ],
            (int)319  => [
                'Aco' => [
                    'id'          => '335',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'gethostbyaddr',
                    'lft'         => '731',
                    'rght'        => '732'
                ]
            ],
            (int)320  => [
                'Aco' => [
                    'id'          => '336',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadHosttemplate',
                    'lft'         => '733',
                    'rght'        => '734'
                ]
            ],
            (int)321  => [
                'Aco' => [
                    'id'          => '337',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'addCustomMacro',
                    'lft'         => '735',
                    'rght'        => '736'
                ]
            ],
            (int)322  => [
                'Aco' => [
                    'id'          => '338',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadTemplateMacros',
                    'lft'         => '737',
                    'rght'        => '738'
                ]
            ],
            (int)323  => [
                'Aco' => [
                    'id'          => '339',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadParametersByCommandId',
                    'lft'         => '739',
                    'rght'        => '740'
                ]
            ],
            (int)324  => [
                'Aco' => [
                    'id'          => '340',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadArguments',
                    'lft'         => '741',
                    'rght'        => '742'
                ]
            ],
            (int)325  => [
                'Aco' => [
                    'id'          => '341',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadArgumentsAdd',
                    'lft'         => '743',
                    'rght'        => '744'
                ]
            ],
            (int)326  => [
                'Aco' => [
                    'id'          => '342',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadHosttemplatesArguments',
                    'lft'         => '745',
                    'rght'        => '746'
                ]
            ],
            (int)327  => [
                'Aco' => [
                    'id'          => '343',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getHostByAjax',
                    'lft'         => '747',
                    'rght'        => '748'
                ]
            ],
            (int)328  => [
                'Aco' => [
                    'id'          => '344',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'listToPdf',
                    'lft'         => '749',
                    'rght'        => '750'
                ]
            ],
            (int)329  => [
                'Aco' => [
                    'id'          => '345',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'ping',
                    'lft'         => '751',
                    'rght'        => '752'
                ]
            ],
            (int)330  => [
                'Aco' => [
                    'id'          => '346',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'addParentHosts',
                    'lft'         => '753',
                    'rght'        => '754'
                ]
            ],
            (int)331  => [
                'Aco' => [
                    'id'          => '347',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadElementsByContainerId',
                    'lft'         => '755',
                    'rght'        => '756'
                ]
            ],
            (int)332  => [
                'Aco' => [
                    'id'          => '348',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkcommand',
                    'lft'         => '757',
                    'rght'        => '758'
                ]
            ],
            (int)333  => [
                'Aco' => [
                    'id'          => '349',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '759',
                    'rght'        => '760'
                ]
            ],
            (int)334  => [
                'Aco' => [
                    'id'          => '350',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '761',
                    'rght'        => '762'
                ]
            ],
            (int)335  => [
                'Aco' => [
                    'id'          => '351',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '763',
                    'rght'        => '764'
                ]
            ],
            (int)336  => [
                'Aco' => [
                    'id'          => '352',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '765',
                    'rght'        => '766'
                ]
            ],
            (int)337  => [
                'Aco' => [
                    'id'          => '353',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '767',
                    'rght'        => '768'
                ]
            ],
            (int)338  => [
                'Aco' => [
                    'id'          => '354',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '769',
                    'rght'        => '770'
                ]
            ],
            (int)339  => [
                'Aco' => [
                    'id'          => '355',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '771',
                    'rght'        => '772'
                ]
            ],
            (int)340  => [
                'Aco' => [
                    'id'          => '356',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Hosttemplates',
                    'lft'         => '784',
                    'rght'        => '825'
                ]
            ],
            (int)341  => [
                'Aco' => [
                    'id'          => '357',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '785',
                    'rght'        => '786'
                ]
            ],
            (int)342  => [
                'Aco' => [
                    'id'          => '358',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '787',
                    'rght'        => '788'
                ]
            ],
            (int)343  => [
                'Aco' => [
                    'id'          => '359',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '789',
                    'rght'        => '790'
                ]
            ],
            (int)344  => [
                'Aco' => [
                    'id'          => '360',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '791',
                    'rght'        => '792'
                ]
            ],
            (int)345  => [
                'Aco' => [
                    'id'          => '361',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'addCustomMacro',
                    'lft'         => '793',
                    'rght'        => '794'
                ]
            ],
            (int)346  => [
                'Aco' => [
                    'id'          => '362',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadArguments',
                    'lft'         => '795',
                    'rght'        => '796'
                ]
            ],
            (int)347  => [
                'Aco' => [
                    'id'          => '363',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadArgumentsAdd',
                    'lft'         => '797',
                    'rght'        => '798'
                ]
            ],
            (int)348  => [
                'Aco' => [
                    'id'          => '364',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'usedBy',
                    'lft'         => '799',
                    'rght'        => '800'
                ]
            ],
            (int)349  => [
                'Aco' => [
                    'id'          => '365',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadElementsByContainerId',
                    'lft'         => '801',
                    'rght'        => '802'
                ]
            ],
            (int)350  => [
                'Aco' => [
                    'id'          => '366',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '803',
                    'rght'        => '804'
                ]
            ],
            (int)351  => [
                'Aco' => [
                    'id'          => '367',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '805',
                    'rght'        => '806'
                ]
            ],
            (int)352  => [
                'Aco' => [
                    'id'          => '368',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '807',
                    'rght'        => '808'
                ]
            ],
            (int)353  => [
                'Aco' => [
                    'id'          => '369',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '809',
                    'rght'        => '810'
                ]
            ],
            (int)354  => [
                'Aco' => [
                    'id'          => '370',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '811',
                    'rght'        => '812'
                ]
            ],
            (int)355  => [
                'Aco' => [
                    'id'          => '371',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '813',
                    'rght'        => '814'
                ]
            ],
            (int)356  => [
                'Aco' => [
                    'id'          => '372',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '815',
                    'rght'        => '816'
                ]
            ],
            (int)357  => [
                'Aco' => [
                    'id'          => '373',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Instantreports',
                    'lft'         => '826',
                    'rght'        => '849'
                ]
            ],
            (int)358  => [
                'Aco' => [
                    'id'          => '374',
                    'parent_id'   => '373',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '827',
                    'rght'        => '828'
                ]
            ],
            (int)359  => [
                'Aco' => [
                    'id'          => '375',
                    'parent_id'   => '373',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'createPdfReport',
                    'lft'         => '829',
                    'rght'        => '830'
                ]
            ],
            (int)360  => [
                'Aco' => [
                    'id'          => '376',
                    'parent_id'   => '373',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'expandServices',
                    'lft'         => '831',
                    'rght'        => '832'
                ]
            ],
            (int)361  => [
                'Aco' => [
                    'id'          => '377',
                    'parent_id'   => '373',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '833',
                    'rght'        => '834'
                ]
            ],
            (int)362  => [
                'Aco' => [
                    'id'          => '378',
                    'parent_id'   => '373',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '835',
                    'rght'        => '836'
                ]
            ],
            (int)363  => [
                'Aco' => [
                    'id'          => '379',
                    'parent_id'   => '373',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '837',
                    'rght'        => '838'
                ]
            ],
            (int)364  => [
                'Aco' => [
                    'id'          => '380',
                    'parent_id'   => '373',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '839',
                    'rght'        => '840'
                ]
            ],
            (int)365  => [
                'Aco' => [
                    'id'          => '381',
                    'parent_id'   => '373',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '841',
                    'rght'        => '842'
                ]
            ],
            (int)366  => [
                'Aco' => [
                    'id'          => '382',
                    'parent_id'   => '373',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '843',
                    'rght'        => '844'
                ]
            ],
            (int)367  => [
                'Aco' => [
                    'id'          => '383',
                    'parent_id'   => '373',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '845',
                    'rght'        => '846'
                ]
            ],
            (int)368  => [
                'Aco' => [
                    'id'          => '384',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Locations',
                    'lft'         => '850',
                    'rght'        => '877'
                ]
            ],
            (int)369  => [
                'Aco' => [
                    'id'          => '385',
                    'parent_id'   => '384',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '851',
                    'rght'        => '852'
                ]
            ],
            (int)370  => [
                'Aco' => [
                    'id'          => '386',
                    'parent_id'   => '384',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '853',
                    'rght'        => '854'
                ]
            ],
            (int)371  => [
                'Aco' => [
                    'id'          => '387',
                    'parent_id'   => '384',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '855',
                    'rght'        => '856'
                ]
            ],
            (int)372  => [
                'Aco' => [
                    'id'          => '388',
                    'parent_id'   => '384',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '857',
                    'rght'        => '858'
                ]
            ],
            (int)373  => [
                'Aco' => [
                    'id'          => '389',
                    'parent_id'   => '384',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '859',
                    'rght'        => '860'
                ]
            ],
            (int)374  => [
                'Aco' => [
                    'id'          => '390',
                    'parent_id'   => '384',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '861',
                    'rght'        => '862'
                ]
            ],
            (int)375  => [
                'Aco' => [
                    'id'          => '391',
                    'parent_id'   => '384',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '863',
                    'rght'        => '864'
                ]
            ],
            (int)376  => [
                'Aco' => [
                    'id'          => '392',
                    'parent_id'   => '384',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '865',
                    'rght'        => '866'
                ]
            ],
            (int)377  => [
                'Aco' => [
                    'id'          => '393',
                    'parent_id'   => '384',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '867',
                    'rght'        => '868'
                ]
            ],
            (int)378  => [
                'Aco' => [
                    'id'          => '394',
                    'parent_id'   => '384',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '869',
                    'rght'        => '870'
                ]
            ],
            (int)379  => [
                'Aco' => [
                    'id'          => '395',
                    'parent_id'   => '384',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '871',
                    'rght'        => '872'
                ]
            ],
            (int)380  => [
                'Aco' => [
                    'id'          => '396',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Logentries',
                    'lft'         => '878',
                    'rght'        => '897'
                ]
            ],
            (int)381  => [
                'Aco' => [
                    'id'          => '397',
                    'parent_id'   => '396',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '879',
                    'rght'        => '880'
                ]
            ],
            (int)382  => [
                'Aco' => [
                    'id'          => '398',
                    'parent_id'   => '396',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '881',
                    'rght'        => '882'
                ]
            ],
            (int)383  => [
                'Aco' => [
                    'id'          => '399',
                    'parent_id'   => '396',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '883',
                    'rght'        => '884'
                ]
            ],
            (int)384  => [
                'Aco' => [
                    'id'          => '400',
                    'parent_id'   => '396',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '885',
                    'rght'        => '886'
                ]
            ],
            (int)385  => [
                'Aco' => [
                    'id'          => '401',
                    'parent_id'   => '396',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '887',
                    'rght'        => '888'
                ]
            ],
            (int)386  => [
                'Aco' => [
                    'id'          => '402',
                    'parent_id'   => '396',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '889',
                    'rght'        => '890'
                ]
            ],
            (int)387  => [
                'Aco' => [
                    'id'          => '403',
                    'parent_id'   => '396',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '891',
                    'rght'        => '892'
                ]
            ],
            (int)388  => [
                'Aco' => [
                    'id'          => '404',
                    'parent_id'   => '396',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '893',
                    'rght'        => '894'
                ]
            ],
            (int)389  => [
                'Aco' => [
                    'id'          => '405',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Login',
                    'lft'         => '898',
                    'rght'        => '927'
                ]
            ],
            (int)390  => [
                'Aco' => [
                    'id'          => '406',
                    'parent_id'   => '405',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '899',
                    'rght'        => '900'
                ]
            ],
            (int)391  => [
                'Aco' => [
                    'id'          => '407',
                    'parent_id'   => '405',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'login',
                    'lft'         => '901',
                    'rght'        => '902'
                ]
            ],
            (int)392  => [
                'Aco' => [
                    'id'          => '408',
                    'parent_id'   => '405',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'onetimetoken',
                    'lft'         => '903',
                    'rght'        => '904'
                ]
            ],
            (int)393  => [
                'Aco' => [
                    'id'          => '409',
                    'parent_id'   => '405',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'logout',
                    'lft'         => '905',
                    'rght'        => '906'
                ]
            ],
            (int)394  => [
                'Aco' => [
                    'id'          => '410',
                    'parent_id'   => '405',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'auth_required',
                    'lft'         => '907',
                    'rght'        => '908'
                ]
            ],
            (int)395  => [
                'Aco' => [
                    'id'          => '411',
                    'parent_id'   => '405',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'lock',
                    'lft'         => '909',
                    'rght'        => '910'
                ]
            ],
            (int)396  => [
                'Aco' => [
                    'id'          => '412',
                    'parent_id'   => '405',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '911',
                    'rght'        => '912'
                ]
            ],
            (int)397  => [
                'Aco' => [
                    'id'          => '413',
                    'parent_id'   => '405',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '913',
                    'rght'        => '914'
                ]
            ],
            (int)398  => [
                'Aco' => [
                    'id'          => '414',
                    'parent_id'   => '405',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '915',
                    'rght'        => '916'
                ]
            ],
            (int)399  => [
                'Aco' => [
                    'id'          => '415',
                    'parent_id'   => '405',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '917',
                    'rght'        => '918'
                ]
            ],
            (int)400  => [
                'Aco' => [
                    'id'          => '416',
                    'parent_id'   => '405',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '919',
                    'rght'        => '920'
                ]
            ],
            (int)401  => [
                'Aco' => [
                    'id'          => '417',
                    'parent_id'   => '405',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '921',
                    'rght'        => '922'
                ]
            ],
            (int)402  => [
                'Aco' => [
                    'id'          => '418',
                    'parent_id'   => '405',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '923',
                    'rght'        => '924'
                ]
            ],
            (int)403  => [
                'Aco' => [
                    'id'          => '419',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Macros',
                    'lft'         => '928',
                    'rght'        => '949'
                ]
            ],
            (int)404  => [
                'Aco' => [
                    'id'          => '420',
                    'parent_id'   => '419',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '929',
                    'rght'        => '930'
                ]
            ],
            (int)405  => [
                'Aco' => [
                    'id'          => '421',
                    'parent_id'   => '419',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'addMacro',
                    'lft'         => '931',
                    'rght'        => '932'
                ]
            ],
            (int)406  => [
                'Aco' => [
                    'id'          => '422',
                    'parent_id'   => '419',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '933',
                    'rght'        => '934'
                ]
            ],
            (int)407  => [
                'Aco' => [
                    'id'          => '423',
                    'parent_id'   => '419',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '935',
                    'rght'        => '936'
                ]
            ],
            (int)408  => [
                'Aco' => [
                    'id'          => '424',
                    'parent_id'   => '419',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '937',
                    'rght'        => '938'
                ]
            ],
            (int)409  => [
                'Aco' => [
                    'id'          => '425',
                    'parent_id'   => '419',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '939',
                    'rght'        => '940'
                ]
            ],
            (int)410  => [
                'Aco' => [
                    'id'          => '426',
                    'parent_id'   => '419',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '941',
                    'rght'        => '942'
                ]
            ],
            (int)411  => [
                'Aco' => [
                    'id'          => '427',
                    'parent_id'   => '419',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '943',
                    'rght'        => '944'
                ]
            ],
            (int)412  => [
                'Aco' => [
                    'id'          => '428',
                    'parent_id'   => '419',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '945',
                    'rght'        => '946'
                ]
            ],
            (int)413  => [
                'Aco' => [
                    'id'          => '429',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Nagiostats',
                    'lft'         => '950',
                    'rght'        => '969'
                ]
            ],
            (int)414  => [
                'Aco' => [
                    'id'          => '430',
                    'parent_id'   => '429',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '951',
                    'rght'        => '952'
                ]
            ],
            (int)415  => [
                'Aco' => [
                    'id'          => '431',
                    'parent_id'   => '429',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '953',
                    'rght'        => '954'
                ]
            ],
            (int)416  => [
                'Aco' => [
                    'id'          => '432',
                    'parent_id'   => '429',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '955',
                    'rght'        => '956'
                ]
            ],
            (int)417  => [
                'Aco' => [
                    'id'          => '433',
                    'parent_id'   => '429',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '957',
                    'rght'        => '958'
                ]
            ],
            (int)418  => [
                'Aco' => [
                    'id'          => '434',
                    'parent_id'   => '429',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '959',
                    'rght'        => '960'
                ]
            ],
            (int)419  => [
                'Aco' => [
                    'id'          => '435',
                    'parent_id'   => '429',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '961',
                    'rght'        => '962'
                ]
            ],
            (int)420  => [
                'Aco' => [
                    'id'          => '436',
                    'parent_id'   => '429',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '963',
                    'rght'        => '964'
                ]
            ],
            (int)421  => [
                'Aco' => [
                    'id'          => '437',
                    'parent_id'   => '429',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '965',
                    'rght'        => '966'
                ]
            ],
            (int)422  => [
                'Aco' => [
                    'id'          => '438',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Notifications',
                    'lft'         => '970',
                    'rght'        => '993'
                ]
            ],
            (int)423  => [
                'Aco' => [
                    'id'          => '439',
                    'parent_id'   => '438',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '971',
                    'rght'        => '972'
                ]
            ],
            (int)424  => [
                'Aco' => [
                    'id'          => '440',
                    'parent_id'   => '438',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'hostNotification',
                    'lft'         => '973',
                    'rght'        => '974'
                ]
            ],
            (int)425  => [
                'Aco' => [
                    'id'          => '441',
                    'parent_id'   => '438',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceNotification',
                    'lft'         => '975',
                    'rght'        => '976'
                ]
            ],
            (int)426  => [
                'Aco' => [
                    'id'          => '442',
                    'parent_id'   => '438',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '977',
                    'rght'        => '978'
                ]
            ],
            (int)427  => [
                'Aco' => [
                    'id'          => '443',
                    'parent_id'   => '438',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '979',
                    'rght'        => '980'
                ]
            ],
            (int)428  => [
                'Aco' => [
                    'id'          => '444',
                    'parent_id'   => '438',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '981',
                    'rght'        => '982'
                ]
            ],
            (int)429  => [
                'Aco' => [
                    'id'          => '445',
                    'parent_id'   => '438',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '983',
                    'rght'        => '984'
                ]
            ],
            (int)430  => [
                'Aco' => [
                    'id'          => '446',
                    'parent_id'   => '438',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '985',
                    'rght'        => '986'
                ]
            ],
            (int)431  => [
                'Aco' => [
                    'id'          => '447',
                    'parent_id'   => '438',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '987',
                    'rght'        => '988'
                ]
            ],
            (int)432  => [
                'Aco' => [
                    'id'          => '448',
                    'parent_id'   => '438',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '989',
                    'rght'        => '990'
                ]
            ],
            (int)433  => [
                'Aco' => [
                    'id'          => '449',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Packetmanager',
                    'lft'         => '994',
                    'rght'        => '1015'
                ]
            ],
            (int)434  => [
                'Aco' => [
                    'id'          => '450',
                    'parent_id'   => '449',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '995',
                    'rght'        => '996'
                ]
            ],
            (int)435  => [
                'Aco' => [
                    'id'          => '451',
                    'parent_id'   => '449',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getPackets',
                    'lft'         => '997',
                    'rght'        => '998'
                ]
            ],
            (int)436  => [
                'Aco' => [
                    'id'          => '452',
                    'parent_id'   => '449',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '999',
                    'rght'        => '1000'
                ]
            ],
            (int)437  => [
                'Aco' => [
                    'id'          => '453',
                    'parent_id'   => '449',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1001',
                    'rght'        => '1002'
                ]
            ],
            (int)438  => [
                'Aco' => [
                    'id'          => '454',
                    'parent_id'   => '449',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1003',
                    'rght'        => '1004'
                ]
            ],
            (int)439  => [
                'Aco' => [
                    'id'          => '455',
                    'parent_id'   => '449',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1005',
                    'rght'        => '1006'
                ]
            ],
            (int)440  => [
                'Aco' => [
                    'id'          => '456',
                    'parent_id'   => '449',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1007',
                    'rght'        => '1008'
                ]
            ],
            (int)441  => [
                'Aco' => [
                    'id'          => '457',
                    'parent_id'   => '449',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1009',
                    'rght'        => '1010'
                ]
            ],
            (int)442  => [
                'Aco' => [
                    'id'          => '458',
                    'parent_id'   => '449',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1011',
                    'rght'        => '1012'
                ]
            ],
            (int)443  => [
                'Aco' => [
                    'id'          => '459',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Profile',
                    'lft'         => '1016',
                    'rght'        => '1037'
                ]
            ],
            (int)444  => [
                'Aco' => [
                    'id'          => '460',
                    'parent_id'   => '459',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '1017',
                    'rght'        => '1018'
                ]
            ],
            (int)445  => [
                'Aco' => [
                    'id'          => '461',
                    'parent_id'   => '459',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'deleteImage',
                    'lft'         => '1019',
                    'rght'        => '1020'
                ]
            ],
            (int)446  => [
                'Aco' => [
                    'id'          => '462',
                    'parent_id'   => '459',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1021',
                    'rght'        => '1022'
                ]
            ],
            (int)447  => [
                'Aco' => [
                    'id'          => '463',
                    'parent_id'   => '459',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1023',
                    'rght'        => '1024'
                ]
            ],
            (int)448  => [
                'Aco' => [
                    'id'          => '464',
                    'parent_id'   => '459',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1025',
                    'rght'        => '1026'
                ]
            ],
            (int)449  => [
                'Aco' => [
                    'id'          => '465',
                    'parent_id'   => '459',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1027',
                    'rght'        => '1028'
                ]
            ],
            (int)450  => [
                'Aco' => [
                    'id'          => '466',
                    'parent_id'   => '459',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1029',
                    'rght'        => '1030'
                ]
            ],
            (int)451  => [
                'Aco' => [
                    'id'          => '467',
                    'parent_id'   => '459',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1031',
                    'rght'        => '1032'
                ]
            ],
            (int)452  => [
                'Aco' => [
                    'id'          => '468',
                    'parent_id'   => '459',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1033',
                    'rght'        => '1034'
                ]
            ],
            (int)453  => [
                'Aco' => [
                    'id'          => '469',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Proxy',
                    'lft'         => '1038',
                    'rght'        => '1061'
                ]
            ],
            (int)454  => [
                'Aco' => [
                    'id'          => '470',
                    'parent_id'   => '469',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1039',
                    'rght'        => '1040'
                ]
            ],
            (int)455  => [
                'Aco' => [
                    'id'          => '471',
                    'parent_id'   => '469',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '1041',
                    'rght'        => '1042'
                ]
            ],
            (int)456  => [
                'Aco' => [
                    'id'          => '472',
                    'parent_id'   => '469',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getSettings',
                    'lft'         => '1043',
                    'rght'        => '1044'
                ]
            ],
            (int)457  => [
                'Aco' => [
                    'id'          => '473',
                    'parent_id'   => '469',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1045',
                    'rght'        => '1046'
                ]
            ],
            (int)458  => [
                'Aco' => [
                    'id'          => '474',
                    'parent_id'   => '469',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1047',
                    'rght'        => '1048'
                ]
            ],
            (int)459  => [
                'Aco' => [
                    'id'          => '475',
                    'parent_id'   => '469',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1049',
                    'rght'        => '1050'
                ]
            ],
            (int)460  => [
                'Aco' => [
                    'id'          => '476',
                    'parent_id'   => '469',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1051',
                    'rght'        => '1052'
                ]
            ],
            (int)461  => [
                'Aco' => [
                    'id'          => '477',
                    'parent_id'   => '469',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1053',
                    'rght'        => '1054'
                ]
            ],
            (int)462  => [
                'Aco' => [
                    'id'          => '478',
                    'parent_id'   => '469',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1055',
                    'rght'        => '1056'
                ]
            ],
            (int)463  => [
                'Aco' => [
                    'id'          => '479',
                    'parent_id'   => '469',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1057',
                    'rght'        => '1058'
                ]
            ],
            (int)464  => [
                'Aco' => [
                    'id'          => '480',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Qr',
                    'lft'         => '1062',
                    'rght'        => '1081'
                ]
            ],
            (int)465  => [
                'Aco' => [
                    'id'          => '481',
                    'parent_id'   => '480',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1063',
                    'rght'        => '1064'
                ]
            ],
            (int)466  => [
                'Aco' => [
                    'id'          => '482',
                    'parent_id'   => '480',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1065',
                    'rght'        => '1066'
                ]
            ],
            (int)467  => [
                'Aco' => [
                    'id'          => '483',
                    'parent_id'   => '480',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1067',
                    'rght'        => '1068'
                ]
            ],
            (int)468  => [
                'Aco' => [
                    'id'          => '484',
                    'parent_id'   => '480',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1069',
                    'rght'        => '1070'
                ]
            ],
            (int)469  => [
                'Aco' => [
                    'id'          => '485',
                    'parent_id'   => '480',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1071',
                    'rght'        => '1072'
                ]
            ],
            (int)470  => [
                'Aco' => [
                    'id'          => '486',
                    'parent_id'   => '480',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1073',
                    'rght'        => '1074'
                ]
            ],
            (int)471  => [
                'Aco' => [
                    'id'          => '487',
                    'parent_id'   => '480',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1075',
                    'rght'        => '1076'
                ]
            ],
            (int)472  => [
                'Aco' => [
                    'id'          => '488',
                    'parent_id'   => '480',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1077',
                    'rght'        => '1078'
                ]
            ],
            (int)473  => [
                'Aco' => [
                    'id'          => '489',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Registers',
                    'lft'         => '1082',
                    'rght'        => '1103'
                ]
            ],
            (int)474  => [
                'Aco' => [
                    'id'          => '490',
                    'parent_id'   => '489',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1083',
                    'rght'        => '1084'
                ]
            ],
            (int)475  => [
                'Aco' => [
                    'id'          => '491',
                    'parent_id'   => '489',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'check',
                    'lft'         => '1085',
                    'rght'        => '1086'
                ]
            ],
            (int)476  => [
                'Aco' => [
                    'id'          => '492',
                    'parent_id'   => '489',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1087',
                    'rght'        => '1088'
                ]
            ],
            (int)477  => [
                'Aco' => [
                    'id'          => '493',
                    'parent_id'   => '489',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1089',
                    'rght'        => '1090'
                ]
            ],
            (int)478  => [
                'Aco' => [
                    'id'          => '494',
                    'parent_id'   => '489',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1091',
                    'rght'        => '1092'
                ]
            ],
            (int)479  => [
                'Aco' => [
                    'id'          => '495',
                    'parent_id'   => '489',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1093',
                    'rght'        => '1094'
                ]
            ],
            (int)480  => [
                'Aco' => [
                    'id'          => '496',
                    'parent_id'   => '489',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1095',
                    'rght'        => '1096'
                ]
            ],
            (int)481  => [
                'Aco' => [
                    'id'          => '497',
                    'parent_id'   => '489',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1097',
                    'rght'        => '1098'
                ]
            ],
            (int)482  => [
                'Aco' => [
                    'id'          => '498',
                    'parent_id'   => '489',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1099',
                    'rght'        => '1100'
                ]
            ],
            (int)483  => [
                'Aco' => [
                    'id'          => '499',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Rrds',
                    'lft'         => '1104',
                    'rght'        => '1125'
                ]
            ],
            (int)484  => [
                'Aco' => [
                    'id'          => '500',
                    'parent_id'   => '499',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1105',
                    'rght'        => '1106'
                ]
            ],
            (int)485  => [
                'Aco' => [
                    'id'          => '501',
                    'parent_id'   => '499',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'ajax',
                    'lft'         => '1107',
                    'rght'        => '1108'
                ]
            ],
            (int)486  => [
                'Aco' => [
                    'id'          => '502',
                    'parent_id'   => '499',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1109',
                    'rght'        => '1110'
                ]
            ],
            (int)487  => [
                'Aco' => [
                    'id'          => '503',
                    'parent_id'   => '499',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1111',
                    'rght'        => '1112'
                ]
            ],
            (int)488  => [
                'Aco' => [
                    'id'          => '504',
                    'parent_id'   => '499',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1113',
                    'rght'        => '1114'
                ]
            ],
            (int)489  => [
                'Aco' => [
                    'id'          => '505',
                    'parent_id'   => '499',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1115',
                    'rght'        => '1116'
                ]
            ],
            (int)490  => [
                'Aco' => [
                    'id'          => '506',
                    'parent_id'   => '499',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1117',
                    'rght'        => '1118'
                ]
            ],
            (int)491  => [
                'Aco' => [
                    'id'          => '507',
                    'parent_id'   => '499',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1119',
                    'rght'        => '1120'
                ]
            ],
            (int)492  => [
                'Aco' => [
                    'id'          => '508',
                    'parent_id'   => '499',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1121',
                    'rght'        => '1122'
                ]
            ],
            (int)493  => [
                'Aco' => [
                    'id'          => '509',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Search',
                    'lft'         => '1126',
                    'rght'        => '1149'
                ]
            ],
            (int)494  => [
                'Aco' => [
                    'id'          => '510',
                    'parent_id'   => '509',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1127',
                    'rght'        => '1128'
                ]
            ],
            (int)495  => [
                'Aco' => [
                    'id'          => '511',
                    'parent_id'   => '509',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'hostMacro',
                    'lft'         => '1129',
                    'rght'        => '1130'
                ]
            ],
            (int)496  => [
                'Aco' => [
                    'id'          => '512',
                    'parent_id'   => '509',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceMacro',
                    'lft'         => '1131',
                    'rght'        => '1132'
                ]
            ],
            (int)497  => [
                'Aco' => [
                    'id'          => '513',
                    'parent_id'   => '509',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1133',
                    'rght'        => '1134'
                ]
            ],
            (int)498  => [
                'Aco' => [
                    'id'          => '514',
                    'parent_id'   => '509',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1135',
                    'rght'        => '1136'
                ]
            ],
            (int)499  => [
                'Aco' => [
                    'id'          => '515',
                    'parent_id'   => '509',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1137',
                    'rght'        => '1138'
                ]
            ],
            (int)500  => [
                'Aco' => [
                    'id'          => '516',
                    'parent_id'   => '509',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1139',
                    'rght'        => '1140'
                ]
            ],
            (int)501  => [
                'Aco' => [
                    'id'          => '517',
                    'parent_id'   => '509',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1141',
                    'rght'        => '1142'
                ]
            ],
            (int)502  => [
                'Aco' => [
                    'id'          => '518',
                    'parent_id'   => '509',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1143',
                    'rght'        => '1144'
                ]
            ],
            (int)503  => [
                'Aco' => [
                    'id'          => '519',
                    'parent_id'   => '509',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1145',
                    'rght'        => '1146'
                ]
            ],
            (int)504  => [
                'Aco' => [
                    'id'          => '520',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Servicechecks',
                    'lft'         => '1150',
                    'rght'        => '1169'
                ]
            ],
            (int)505  => [
                'Aco' => [
                    'id'          => '521',
                    'parent_id'   => '520',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1151',
                    'rght'        => '1152'
                ]
            ],
            (int)506  => [
                'Aco' => [
                    'id'          => '522',
                    'parent_id'   => '520',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1153',
                    'rght'        => '1154'
                ]
            ],
            (int)507  => [
                'Aco' => [
                    'id'          => '523',
                    'parent_id'   => '520',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1155',
                    'rght'        => '1156'
                ]
            ],
            (int)508  => [
                'Aco' => [
                    'id'          => '524',
                    'parent_id'   => '520',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1157',
                    'rght'        => '1158'
                ]
            ],
            (int)509  => [
                'Aco' => [
                    'id'          => '525',
                    'parent_id'   => '520',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1159',
                    'rght'        => '1160'
                ]
            ],
            (int)510  => [
                'Aco' => [
                    'id'          => '526',
                    'parent_id'   => '520',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1161',
                    'rght'        => '1162'
                ]
            ],
            (int)511  => [
                'Aco' => [
                    'id'          => '527',
                    'parent_id'   => '520',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1163',
                    'rght'        => '1164'
                ]
            ],
            (int)512  => [
                'Aco' => [
                    'id'          => '528',
                    'parent_id'   => '520',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1165',
                    'rght'        => '1166'
                ]
            ],
            (int)513  => [
                'Aco' => [
                    'id'          => '529',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Servicedependencies',
                    'lft'         => '1170',
                    'rght'        => '1199'
                ]
            ],
            (int)514  => [
                'Aco' => [
                    'id'          => '530',
                    'parent_id'   => '529',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1171',
                    'rght'        => '1172'
                ]
            ],
            (int)515  => [
                'Aco' => [
                    'id'          => '531',
                    'parent_id'   => '529',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '1173',
                    'rght'        => '1174'
                ]
            ],
            (int)516  => [
                'Aco' => [
                    'id'          => '532',
                    'parent_id'   => '529',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '1175',
                    'rght'        => '1176'
                ]
            ],
            (int)517  => [
                'Aco' => [
                    'id'          => '533',
                    'parent_id'   => '529',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '1177',
                    'rght'        => '1178'
                ]
            ],
            (int)518  => [
                'Aco' => [
                    'id'          => '534',
                    'parent_id'   => '529',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadElementsByContainerId',
                    'lft'         => '1179',
                    'rght'        => '1180'
                ]
            ],
            (int)519  => [
                'Aco' => [
                    'id'          => '535',
                    'parent_id'   => '529',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1181',
                    'rght'        => '1182'
                ]
            ],
            (int)520  => [
                'Aco' => [
                    'id'          => '536',
                    'parent_id'   => '529',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1183',
                    'rght'        => '1184'
                ]
            ],
            (int)521  => [
                'Aco' => [
                    'id'          => '537',
                    'parent_id'   => '529',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1185',
                    'rght'        => '1186'
                ]
            ],
            (int)522  => [
                'Aco' => [
                    'id'          => '538',
                    'parent_id'   => '529',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1187',
                    'rght'        => '1188'
                ]
            ],
            (int)523  => [
                'Aco' => [
                    'id'          => '539',
                    'parent_id'   => '529',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1189',
                    'rght'        => '1190'
                ]
            ],
            (int)524  => [
                'Aco' => [
                    'id'          => '540',
                    'parent_id'   => '529',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1191',
                    'rght'        => '1192'
                ]
            ],
            (int)525  => [
                'Aco' => [
                    'id'          => '541',
                    'parent_id'   => '529',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1193',
                    'rght'        => '1194'
                ]
            ],
            (int)526  => [
                'Aco' => [
                    'id'          => '542',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Serviceescalations',
                    'lft'         => '1200',
                    'rght'        => '1229'
                ]
            ],
            (int)527  => [
                'Aco' => [
                    'id'          => '543',
                    'parent_id'   => '542',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1201',
                    'rght'        => '1202'
                ]
            ],
            (int)528  => [
                'Aco' => [
                    'id'          => '544',
                    'parent_id'   => '542',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '1203',
                    'rght'        => '1204'
                ]
            ],
            (int)529  => [
                'Aco' => [
                    'id'          => '545',
                    'parent_id'   => '542',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '1205',
                    'rght'        => '1206'
                ]
            ],
            (int)530  => [
                'Aco' => [
                    'id'          => '546',
                    'parent_id'   => '542',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '1207',
                    'rght'        => '1208'
                ]
            ],
            (int)531  => [
                'Aco' => [
                    'id'          => '547',
                    'parent_id'   => '542',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadElementsByContainerId',
                    'lft'         => '1209',
                    'rght'        => '1210'
                ]
            ],
            (int)532  => [
                'Aco' => [
                    'id'          => '548',
                    'parent_id'   => '542',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1211',
                    'rght'        => '1212'
                ]
            ],
            (int)533  => [
                'Aco' => [
                    'id'          => '549',
                    'parent_id'   => '542',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1213',
                    'rght'        => '1214'
                ]
            ],
            (int)534  => [
                'Aco' => [
                    'id'          => '550',
                    'parent_id'   => '542',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1215',
                    'rght'        => '1216'
                ]
            ],
            (int)535  => [
                'Aco' => [
                    'id'          => '551',
                    'parent_id'   => '542',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1217',
                    'rght'        => '1218'
                ]
            ],
            (int)536  => [
                'Aco' => [
                    'id'          => '552',
                    'parent_id'   => '542',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1219',
                    'rght'        => '1220'
                ]
            ],
            (int)537  => [
                'Aco' => [
                    'id'          => '553',
                    'parent_id'   => '542',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1221',
                    'rght'        => '1222'
                ]
            ],
            (int)538  => [
                'Aco' => [
                    'id'          => '554',
                    'parent_id'   => '542',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1223',
                    'rght'        => '1224'
                ]
            ],
            (int)539  => [
                'Aco' => [
                    'id'          => '555',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Servicegroups',
                    'lft'         => '1230',
                    'rght'        => '1265'
                ]
            ],
            (int)540  => [
                'Aco' => [
                    'id'          => '556',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1231',
                    'rght'        => '1232'
                ]
            ],
            (int)541  => [
                'Aco' => [
                    'id'          => '557',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '1233',
                    'rght'        => '1234'
                ]
            ],
            (int)542  => [
                'Aco' => [
                    'id'          => '558',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '1235',
                    'rght'        => '1236'
                ]
            ],
            (int)543  => [
                'Aco' => [
                    'id'          => '559',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadServices',
                    'lft'         => '1237',
                    'rght'        => '1238'
                ]
            ],
            (int)544  => [
                'Aco' => [
                    'id'          => '560',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '1239',
                    'rght'        => '1240'
                ]
            ],
            (int)545  => [
                'Aco' => [
                    'id'          => '561',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_delete',
                    'lft'         => '1241',
                    'rght'        => '1242'
                ]
            ],
            (int)546  => [
                'Aco' => [
                    'id'          => '562',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_add',
                    'lft'         => '1243',
                    'rght'        => '1244'
                ]
            ],
            (int)547  => [
                'Aco' => [
                    'id'          => '563',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'listToPdf',
                    'lft'         => '1245',
                    'rght'        => '1246'
                ]
            ],
            (int)548  => [
                'Aco' => [
                    'id'          => '564',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1247',
                    'rght'        => '1248'
                ]
            ],
            (int)549  => [
                'Aco' => [
                    'id'          => '565',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1249',
                    'rght'        => '1250'
                ]
            ],
            (int)550  => [
                'Aco' => [
                    'id'          => '566',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1251',
                    'rght'        => '1252'
                ]
            ],
            (int)551  => [
                'Aco' => [
                    'id'          => '567',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1253',
                    'rght'        => '1254'
                ]
            ],
            (int)552  => [
                'Aco' => [
                    'id'          => '568',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1255',
                    'rght'        => '1256'
                ]
            ],
            (int)553  => [
                'Aco' => [
                    'id'          => '569',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1257',
                    'rght'        => '1258'
                ]
            ],
            (int)554  => [
                'Aco' => [
                    'id'          => '570',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1259',
                    'rght'        => '1260'
                ]
            ],
            (int)555  => [
                'Aco' => [
                    'id'          => '571',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Services',
                    'lft'         => '1266',
                    'rght'        => '1351'
                ]
            ],
            (int)556  => [
                'Aco' => [
                    'id'          => '572',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1267',
                    'rght'        => '1268'
                ]
            ],
            (int)557  => [
                'Aco' => [
                    'id'          => '573',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'notMonitored',
                    'lft'         => '1269',
                    'rght'        => '1270'
                ]
            ],
            (int)558  => [
                'Aco' => [
                    'id'          => '574',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'disabled',
                    'lft'         => '1271',
                    'rght'        => '1272'
                ]
            ],
            (int)559  => [
                'Aco' => [
                    'id'          => '575',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '1273',
                    'rght'        => '1274'
                ]
            ],
            (int)560  => [
                'Aco' => [
                    'id'          => '576',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '1275',
                    'rght'        => '1276'
                ]
            ],
            (int)561  => [
                'Aco' => [
                    'id'          => '577',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '1277',
                    'rght'        => '1278'
                ]
            ],
            (int)562  => [
                'Aco' => [
                    'id'          => '578',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_delete',
                    'lft'         => '1279',
                    'rght'        => '1280'
                ]
            ],
            (int)563  => [
                'Aco' => [
                    'id'          => '579',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'copy',
                    'lft'         => '1281',
                    'rght'        => '1282'
                ]
            ],
            (int)564  => [
                'Aco' => [
                    'id'          => '580',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'deactivate',
                    'lft'         => '1283',
                    'rght'        => '1284'
                ]
            ],
            (int)565  => [
                'Aco' => [
                    'id'          => '581',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_deactivate',
                    'lft'         => '1285',
                    'rght'        => '1286'
                ]
            ],
            (int)566  => [
                'Aco' => [
                    'id'          => '582',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'enable',
                    'lft'         => '1287',
                    'rght'        => '1288'
                ]
            ],
            (int)567  => [
                'Aco' => [
                    'id'          => '583',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadContactsAndContactgroups',
                    'lft'         => '1289',
                    'rght'        => '1290'
                ]
            ],
            (int)568  => [
                'Aco' => [
                    'id'          => '584',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadParametersByCommandId',
                    'lft'         => '1291',
                    'rght'        => '1292'
                ]
            ],
            (int)569  => [
                'Aco' => [
                    'id'          => '585',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadNagParametersByCommandId',
                    'lft'         => '1293',
                    'rght'        => '1294'
                ]
            ],
            (int)570  => [
                'Aco' => [
                    'id'          => '586',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadArgumentsAdd',
                    'lft'         => '1295',
                    'rght'        => '1296'
                ]
            ],
            (int)571  => [
                'Aco' => [
                    'id'          => '587',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadServicetemplatesArguments',
                    'lft'         => '1297',
                    'rght'        => '1298'
                ]
            ],
            (int)572  => [
                'Aco' => [
                    'id'          => '588',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadTemplateData',
                    'lft'         => '1299',
                    'rght'        => '1300'
                ]
            ],
            (int)573  => [
                'Aco' => [
                    'id'          => '589',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'addCustomMacro',
                    'lft'         => '1301',
                    'rght'        => '1302'
                ]
            ],
            (int)574  => [
                'Aco' => [
                    'id'          => '590',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadServices',
                    'lft'         => '1303',
                    'rght'        => '1304'
                ]
            ],
            (int)575  => [
                'Aco' => [
                    'id'          => '591',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadTemplateMacros',
                    'lft'         => '1305',
                    'rght'        => '1306'
                ]
            ],
            (int)576  => [
                'Aco' => [
                    'id'          => '592',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'browser',
                    'lft'         => '1307',
                    'rght'        => '1308'
                ]
            ],
            (int)577  => [
                'Aco' => [
                    'id'          => '593',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'servicesByHostId',
                    'lft'         => '1309',
                    'rght'        => '1310'
                ]
            ],
            (int)578  => [
                'Aco' => [
                    'id'          => '594',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceList',
                    'lft'         => '1311',
                    'rght'        => '1312'
                ]
            ],
            (int)579  => [
                'Aco' => [
                    'id'          => '595',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'grapherSwitch',
                    'lft'         => '1313',
                    'rght'        => '1314'
                ]
            ],
            (int)580  => [
                'Aco' => [
                    'id'          => '596',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'grapher',
                    'lft'         => '1315',
                    'rght'        => '1316'
                ]
            ],
            (int)581  => [
                'Aco' => [
                    'id'          => '597',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'grapherTemplate',
                    'lft'         => '1317',
                    'rght'        => '1318'
                ]
            ],
            (int)582  => [
                'Aco' => [
                    'id'          => '598',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'grapherZoom',
                    'lft'         => '1319',
                    'rght'        => '1320'
                ]
            ],
            (int)583  => [
                'Aco' => [
                    'id'          => '599',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'grapherZoomTemplate',
                    'lft'         => '1321',
                    'rght'        => '1322'
                ]
            ],
            (int)584  => [
                'Aco' => [
                    'id'          => '600',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'createGrapherErrorPng',
                    'lft'         => '1323',
                    'rght'        => '1324'
                ]
            ],
            (int)585  => [
                'Aco' => [
                    'id'          => '601',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'longOutputByUuid',
                    'lft'         => '1325',
                    'rght'        => '1326'
                ]
            ],
            (int)586  => [
                'Aco' => [
                    'id'          => '602',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'listToPdf',
                    'lft'         => '1327',
                    'rght'        => '1328'
                ]
            ],
            (int)587  => [
                'Aco' => [
                    'id'          => '603',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkcommand',
                    'lft'         => '1329',
                    'rght'        => '1330'
                ]
            ],
            (int)588  => [
                'Aco' => [
                    'id'          => '604',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1331',
                    'rght'        => '1332'
                ]
            ],
            (int)589  => [
                'Aco' => [
                    'id'          => '605',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1333',
                    'rght'        => '1334'
                ]
            ],
            (int)590  => [
                'Aco' => [
                    'id'          => '606',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1335',
                    'rght'        => '1336'
                ]
            ],
            (int)591  => [
                'Aco' => [
                    'id'          => '607',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1337',
                    'rght'        => '1338'
                ]
            ],
            (int)592  => [
                'Aco' => [
                    'id'          => '608',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1339',
                    'rght'        => '1340'
                ]
            ],
            (int)593  => [
                'Aco' => [
                    'id'          => '609',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1341',
                    'rght'        => '1342'
                ]
            ],
            (int)594  => [
                'Aco' => [
                    'id'          => '610',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1343',
                    'rght'        => '1344'
                ]
            ],
            (int)595  => [
                'Aco' => [
                    'id'          => '611',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Servicetemplategroups',
                    'lft'         => '1352',
                    'rght'        => '1389'
                ]
            ],
            (int)596  => [
                'Aco' => [
                    'id'          => '612',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1353',
                    'rght'        => '1354'
                ]
            ],
            (int)597  => [
                'Aco' => [
                    'id'          => '613',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '1355',
                    'rght'        => '1356'
                ]
            ],
            (int)598  => [
                'Aco' => [
                    'id'          => '614',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '1357',
                    'rght'        => '1358'
                ]
            ],
            (int)599  => [
                'Aco' => [
                    'id'          => '615',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allocateToHost',
                    'lft'         => '1359',
                    'rght'        => '1360'
                ]
            ],
            (int)600  => [
                'Aco' => [
                    'id'          => '616',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allocateToHostgroup',
                    'lft'         => '1361',
                    'rght'        => '1362'
                ]
            ],
            (int)601  => [
                'Aco' => [
                    'id'          => '617',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getHostsByHostgroupByAjax',
                    'lft'         => '1363',
                    'rght'        => '1364'
                ]
            ],
            (int)602  => [
                'Aco' => [
                    'id'          => '618',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '1365',
                    'rght'        => '1366'
                ]
            ],
            (int)603  => [
                'Aco' => [
                    'id'          => '619',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadServicetemplatesByContainerId',
                    'lft'         => '1367',
                    'rght'        => '1368'
                ]
            ],
            (int)604  => [
                'Aco' => [
                    'id'          => '620',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1369',
                    'rght'        => '1370'
                ]
            ],
            (int)605  => [
                'Aco' => [
                    'id'          => '621',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1371',
                    'rght'        => '1372'
                ]
            ],
            (int)606  => [
                'Aco' => [
                    'id'          => '622',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1373',
                    'rght'        => '1374'
                ]
            ],
            (int)607  => [
                'Aco' => [
                    'id'          => '623',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1375',
                    'rght'        => '1376'
                ]
            ],
            (int)608  => [
                'Aco' => [
                    'id'          => '624',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1377',
                    'rght'        => '1378'
                ]
            ],
            (int)609  => [
                'Aco' => [
                    'id'          => '625',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1379',
                    'rght'        => '1380'
                ]
            ],
            (int)610  => [
                'Aco' => [
                    'id'          => '626',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1381',
                    'rght'        => '1382'
                ]
            ],
            (int)611  => [
                'Aco' => [
                    'id'          => '627',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Servicetemplates',
                    'lft'         => '1390',
                    'rght'        => '1441'
                ]
            ],
            (int)612  => [
                'Aco' => [
                    'id'          => '628',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1391',
                    'rght'        => '1392'
                ]
            ],
            (int)613  => [
                'Aco' => [
                    'id'          => '629',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '1393',
                    'rght'        => '1394'
                ]
            ],
            (int)614  => [
                'Aco' => [
                    'id'          => '630',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '1395',
                    'rght'        => '1396'
                ]
            ],
            (int)615  => [
                'Aco' => [
                    'id'          => '631',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '1397',
                    'rght'        => '1398'
                ]
            ],
            (int)616  => [
                'Aco' => [
                    'id'          => '632',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'usedBy',
                    'lft'         => '1399',
                    'rght'        => '1400'
                ]
            ],
            (int)617  => [
                'Aco' => [
                    'id'          => '633',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadArguments',
                    'lft'         => '1401',
                    'rght'        => '1402'
                ]
            ],
            (int)618  => [
                'Aco' => [
                    'id'          => '634',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadContactsAndContactgroups',
                    'lft'         => '1403',
                    'rght'        => '1404'
                ]
            ],
            (int)619  => [
                'Aco' => [
                    'id'          => '635',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadArgumentsAdd',
                    'lft'         => '1405',
                    'rght'        => '1406'
                ]
            ],
            (int)620  => [
                'Aco' => [
                    'id'          => '636',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadNagArgumentsAdd',
                    'lft'         => '1407',
                    'rght'        => '1408'
                ]
            ],
            (int)621  => [
                'Aco' => [
                    'id'          => '637',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'addCustomMacro',
                    'lft'         => '1409',
                    'rght'        => '1410'
                ]
            ],
            (int)622  => [
                'Aco' => [
                    'id'          => '638',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadParametersByCommandId',
                    'lft'         => '1411',
                    'rght'        => '1412'
                ]
            ],
            (int)623  => [
                'Aco' => [
                    'id'          => '639',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadNagParametersByCommandId',
                    'lft'         => '1413',
                    'rght'        => '1414'
                ]
            ],
            (int)624  => [
                'Aco' => [
                    'id'          => '640',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadElementsByContainerId',
                    'lft'         => '1415',
                    'rght'        => '1416'
                ]
            ],
            (int)625  => [
                'Aco' => [
                    'id'          => '641',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1417',
                    'rght'        => '1418'
                ]
            ],
            (int)626  => [
                'Aco' => [
                    'id'          => '642',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1419',
                    'rght'        => '1420'
                ]
            ],
            (int)627  => [
                'Aco' => [
                    'id'          => '643',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1421',
                    'rght'        => '1422'
                ]
            ],
            (int)628  => [
                'Aco' => [
                    'id'          => '644',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1423',
                    'rght'        => '1424'
                ]
            ],
            (int)629  => [
                'Aco' => [
                    'id'          => '645',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1425',
                    'rght'        => '1426'
                ]
            ],
            (int)630  => [
                'Aco' => [
                    'id'          => '646',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1427',
                    'rght'        => '1428'
                ]
            ],
            (int)631  => [
                'Aco' => [
                    'id'          => '647',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1429',
                    'rght'        => '1430'
                ]
            ],
            (int)632  => [
                'Aco' => [
                    'id'          => '648',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Statehistories',
                    'lft'         => '1442',
                    'rght'        => '1463'
                ]
            ],
            (int)633  => [
                'Aco' => [
                    'id'          => '649',
                    'parent_id'   => '648',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'service',
                    'lft'         => '1443',
                    'rght'        => '1444'
                ]
            ],
            (int)634  => [
                'Aco' => [
                    'id'          => '650',
                    'parent_id'   => '648',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'host',
                    'lft'         => '1445',
                    'rght'        => '1446'
                ]
            ],
            (int)635  => [
                'Aco' => [
                    'id'          => '651',
                    'parent_id'   => '648',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1447',
                    'rght'        => '1448'
                ]
            ],
            (int)636  => [
                'Aco' => [
                    'id'          => '652',
                    'parent_id'   => '648',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1449',
                    'rght'        => '1450'
                ]
            ],
            (int)637  => [
                'Aco' => [
                    'id'          => '653',
                    'parent_id'   => '648',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1451',
                    'rght'        => '1452'
                ]
            ],
            (int)638  => [
                'Aco' => [
                    'id'          => '654',
                    'parent_id'   => '648',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1453',
                    'rght'        => '1454'
                ]
            ],
            (int)639  => [
                'Aco' => [
                    'id'          => '655',
                    'parent_id'   => '648',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1455',
                    'rght'        => '1456'
                ]
            ],
            (int)640  => [
                'Aco' => [
                    'id'          => '656',
                    'parent_id'   => '648',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1457',
                    'rght'        => '1458'
                ]
            ],
            (int)641  => [
                'Aco' => [
                    'id'          => '657',
                    'parent_id'   => '648',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1459',
                    'rght'        => '1460'
                ]
            ],
            (int)642  => [
                'Aco' => [
                    'id'          => '658',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Statusmaps',
                    'lft'         => '1464',
                    'rght'        => '1489'
                ]
            ],
            (int)643  => [
                'Aco' => [
                    'id'          => '659',
                    'parent_id'   => '658',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1465',
                    'rght'        => '1466'
                ]
            ],
            (int)644  => [
                'Aco' => [
                    'id'          => '660',
                    'parent_id'   => '658',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getHostsAndConnections',
                    'lft'         => '1467',
                    'rght'        => '1468'
                ]
            ],
            (int)645  => [
                'Aco' => [
                    'id'          => '661',
                    'parent_id'   => '658',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'clickHostStatus',
                    'lft'         => '1469',
                    'rght'        => '1470'
                ]
            ],
            (int)646  => [
                'Aco' => [
                    'id'          => '662',
                    'parent_id'   => '658',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '1471',
                    'rght'        => '1472'
                ]
            ],
            (int)647  => [
                'Aco' => [
                    'id'          => '663',
                    'parent_id'   => '658',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1473',
                    'rght'        => '1474'
                ]
            ],
            (int)648  => [
                'Aco' => [
                    'id'          => '664',
                    'parent_id'   => '658',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1475',
                    'rght'        => '1476'
                ]
            ],
            (int)649  => [
                'Aco' => [
                    'id'          => '665',
                    'parent_id'   => '658',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1477',
                    'rght'        => '1478'
                ]
            ],
            (int)650  => [
                'Aco' => [
                    'id'          => '666',
                    'parent_id'   => '658',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1479',
                    'rght'        => '1480'
                ]
            ],
            (int)651  => [
                'Aco' => [
                    'id'          => '667',
                    'parent_id'   => '658',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1481',
                    'rght'        => '1482'
                ]
            ],
            (int)652  => [
                'Aco' => [
                    'id'          => '668',
                    'parent_id'   => '658',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1483',
                    'rght'        => '1484'
                ]
            ],
            (int)653  => [
                'Aco' => [
                    'id'          => '669',
                    'parent_id'   => '658',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1485',
                    'rght'        => '1486'
                ]
            ],
            (int)654  => [
                'Aco' => [
                    'id'          => '670',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'System',
                    'lft'         => '1490',
                    'rght'        => '1509'
                ]
            ],
            (int)655  => [
                'Aco' => [
                    'id'          => '671',
                    'parent_id'   => '670',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'changelog',
                    'lft'         => '1491',
                    'rght'        => '1492'
                ]
            ],
            (int)656  => [
                'Aco' => [
                    'id'          => '672',
                    'parent_id'   => '670',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1493',
                    'rght'        => '1494'
                ]
            ],
            (int)657  => [
                'Aco' => [
                    'id'          => '673',
                    'parent_id'   => '670',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1495',
                    'rght'        => '1496'
                ]
            ],
            (int)658  => [
                'Aco' => [
                    'id'          => '674',
                    'parent_id'   => '670',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1497',
                    'rght'        => '1498'
                ]
            ],
            (int)659  => [
                'Aco' => [
                    'id'          => '675',
                    'parent_id'   => '670',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1499',
                    'rght'        => '1500'
                ]
            ],
            (int)660  => [
                'Aco' => [
                    'id'          => '676',
                    'parent_id'   => '670',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1501',
                    'rght'        => '1502'
                ]
            ],
            (int)661  => [
                'Aco' => [
                    'id'          => '677',
                    'parent_id'   => '670',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1503',
                    'rght'        => '1504'
                ]
            ],
            (int)662  => [
                'Aco' => [
                    'id'          => '678',
                    'parent_id'   => '670',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1505',
                    'rght'        => '1506'
                ]
            ],
            (int)663  => [
                'Aco' => [
                    'id'          => '679',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Systemdowntimes',
                    'lft'         => '1510',
                    'rght'        => '1537'
                ]
            ],
            (int)664  => [
                'Aco' => [
                    'id'          => '680',
                    'parent_id'   => '679',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1511',
                    'rght'        => '1512'
                ]
            ],
            (int)665  => [
                'Aco' => [
                    'id'          => '681',
                    'parent_id'   => '679',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'addHostdowntime',
                    'lft'         => '1513',
                    'rght'        => '1514'
                ]
            ],
            (int)666  => [
                'Aco' => [
                    'id'          => '682',
                    'parent_id'   => '679',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'addHostgroupdowntime',
                    'lft'         => '1515',
                    'rght'        => '1516'
                ]
            ],
            (int)667  => [
                'Aco' => [
                    'id'          => '683',
                    'parent_id'   => '679',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'addServicedowntime',
                    'lft'         => '1517',
                    'rght'        => '1518'
                ]
            ],
            (int)668  => [
                'Aco' => [
                    'id'          => '684',
                    'parent_id'   => '679',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '1519',
                    'rght'        => '1520'
                ]
            ],
            (int)669  => [
                'Aco' => [
                    'id'          => '685',
                    'parent_id'   => '679',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1521',
                    'rght'        => '1522'
                ]
            ],
            (int)670  => [
                'Aco' => [
                    'id'          => '686',
                    'parent_id'   => '679',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1523',
                    'rght'        => '1524'
                ]
            ],
            (int)671  => [
                'Aco' => [
                    'id'          => '687',
                    'parent_id'   => '679',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1525',
                    'rght'        => '1526'
                ]
            ],
            (int)672  => [
                'Aco' => [
                    'id'          => '688',
                    'parent_id'   => '679',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1527',
                    'rght'        => '1528'
                ]
            ],
            (int)673  => [
                'Aco' => [
                    'id'          => '689',
                    'parent_id'   => '679',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1529',
                    'rght'        => '1530'
                ]
            ],
            (int)674  => [
                'Aco' => [
                    'id'          => '690',
                    'parent_id'   => '679',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1531',
                    'rght'        => '1532'
                ]
            ],
            (int)675  => [
                'Aco' => [
                    'id'          => '691',
                    'parent_id'   => '679',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1533',
                    'rght'        => '1534'
                ]
            ],
            (int)676  => [
                'Aco' => [
                    'id'          => '692',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Systemfailures',
                    'lft'         => '1538',
                    'rght'        => '1561'
                ]
            ],
            (int)677  => [
                'Aco' => [
                    'id'          => '693',
                    'parent_id'   => '692',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1539',
                    'rght'        => '1540'
                ]
            ],
            (int)678  => [
                'Aco' => [
                    'id'          => '694',
                    'parent_id'   => '692',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '1541',
                    'rght'        => '1542'
                ]
            ],
            (int)679  => [
                'Aco' => [
                    'id'          => '695',
                    'parent_id'   => '692',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '1543',
                    'rght'        => '1544'
                ]
            ],
            (int)680  => [
                'Aco' => [
                    'id'          => '696',
                    'parent_id'   => '692',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1545',
                    'rght'        => '1546'
                ]
            ],
            (int)681  => [
                'Aco' => [
                    'id'          => '697',
                    'parent_id'   => '692',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1547',
                    'rght'        => '1548'
                ]
            ],
            (int)682  => [
                'Aco' => [
                    'id'          => '698',
                    'parent_id'   => '692',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1549',
                    'rght'        => '1550'
                ]
            ],
            (int)683  => [
                'Aco' => [
                    'id'          => '699',
                    'parent_id'   => '692',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1551',
                    'rght'        => '1552'
                ]
            ],
            (int)684  => [
                'Aco' => [
                    'id'          => '700',
                    'parent_id'   => '692',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1553',
                    'rght'        => '1554'
                ]
            ],
            (int)685  => [
                'Aco' => [
                    'id'          => '701',
                    'parent_id'   => '692',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1555',
                    'rght'        => '1556'
                ]
            ],
            (int)686  => [
                'Aco' => [
                    'id'          => '702',
                    'parent_id'   => '692',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1557',
                    'rght'        => '1558'
                ]
            ],
            (int)687  => [
                'Aco' => [
                    'id'          => '703',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Systemsettings',
                    'lft'         => '1562',
                    'rght'        => '1581'
                ]
            ],
            (int)688  => [
                'Aco' => [
                    'id'          => '704',
                    'parent_id'   => '703',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1563',
                    'rght'        => '1564'
                ]
            ],
            (int)689  => [
                'Aco' => [
                    'id'          => '705',
                    'parent_id'   => '703',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1565',
                    'rght'        => '1566'
                ]
            ],
            (int)690  => [
                'Aco' => [
                    'id'          => '706',
                    'parent_id'   => '703',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1567',
                    'rght'        => '1568'
                ]
            ],
            (int)691  => [
                'Aco' => [
                    'id'          => '707',
                    'parent_id'   => '703',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1569',
                    'rght'        => '1570'
                ]
            ],
            (int)692  => [
                'Aco' => [
                    'id'          => '708',
                    'parent_id'   => '703',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1571',
                    'rght'        => '1572'
                ]
            ],
            (int)693  => [
                'Aco' => [
                    'id'          => '709',
                    'parent_id'   => '703',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1573',
                    'rght'        => '1574'
                ]
            ],
            (int)694  => [
                'Aco' => [
                    'id'          => '710',
                    'parent_id'   => '703',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1575',
                    'rght'        => '1576'
                ]
            ],
            (int)695  => [
                'Aco' => [
                    'id'          => '711',
                    'parent_id'   => '703',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1577',
                    'rght'        => '1578'
                ]
            ],
            (int)696  => [
                'Aco' => [
                    'id'          => '712',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Tenants',
                    'lft'         => '1582',
                    'rght'        => '1611'
                ]
            ],
            (int)697  => [
                'Aco' => [
                    'id'          => '713',
                    'parent_id'   => '712',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1583',
                    'rght'        => '1584'
                ]
            ],
            (int)698  => [
                'Aco' => [
                    'id'          => '714',
                    'parent_id'   => '712',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '1585',
                    'rght'        => '1586'
                ]
            ],
            (int)699  => [
                'Aco' => [
                    'id'          => '715',
                    'parent_id'   => '712',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '1587',
                    'rght'        => '1588'
                ]
            ],
            (int)700  => [
                'Aco' => [
                    'id'          => '716',
                    'parent_id'   => '712',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_delete',
                    'lft'         => '1589',
                    'rght'        => '1590'
                ]
            ],
            (int)701  => [
                'Aco' => [
                    'id'          => '717',
                    'parent_id'   => '712',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '1591',
                    'rght'        => '1592'
                ]
            ],
            (int)702  => [
                'Aco' => [
                    'id'          => '718',
                    'parent_id'   => '712',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1593',
                    'rght'        => '1594'
                ]
            ],
            (int)703  => [
                'Aco' => [
                    'id'          => '719',
                    'parent_id'   => '712',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1595',
                    'rght'        => '1596'
                ]
            ],
            (int)704  => [
                'Aco' => [
                    'id'          => '720',
                    'parent_id'   => '712',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1597',
                    'rght'        => '1598'
                ]
            ],
            (int)705  => [
                'Aco' => [
                    'id'          => '721',
                    'parent_id'   => '712',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1599',
                    'rght'        => '1600'
                ]
            ],
            (int)706  => [
                'Aco' => [
                    'id'          => '722',
                    'parent_id'   => '712',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1601',
                    'rght'        => '1602'
                ]
            ],
            (int)707  => [
                'Aco' => [
                    'id'          => '723',
                    'parent_id'   => '712',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1603',
                    'rght'        => '1604'
                ]
            ],
            (int)708  => [
                'Aco' => [
                    'id'          => '724',
                    'parent_id'   => '712',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1605',
                    'rght'        => '1606'
                ]
            ],
            (int)709  => [
                'Aco' => [
                    'id'          => '725',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Timeperiods',
                    'lft'         => '1612',
                    'rght'        => '1647'
                ]
            ],
            (int)710  => [
                'Aco' => [
                    'id'          => '726',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1613',
                    'rght'        => '1614'
                ]
            ],
            (int)711  => [
                'Aco' => [
                    'id'          => '727',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '1615',
                    'rght'        => '1616'
                ]
            ],
            (int)712  => [
                'Aco' => [
                    'id'          => '728',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '1617',
                    'rght'        => '1618'
                ]
            ],
            (int)713  => [
                'Aco' => [
                    'id'          => '729',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '1619',
                    'rght'        => '1620'
                ]
            ],
            (int)714  => [
                'Aco' => [
                    'id'          => '730',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_delete',
                    'lft'         => '1621',
                    'rght'        => '1622'
                ]
            ],
            (int)715  => [
                'Aco' => [
                    'id'          => '731',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'browser',
                    'lft'         => '1623',
                    'rght'        => '1624'
                ]
            ],
            (int)716  => [
                'Aco' => [
                    'id'          => '732',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'controller',
                    'lft'         => '1625',
                    'rght'        => '1626'
                ]
            ],
            (int)717  => [
                'Aco' => [
                    'id'          => '733',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1627',
                    'rght'        => '1628'
                ]
            ],
            (int)718  => [
                'Aco' => [
                    'id'          => '734',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1629',
                    'rght'        => '1630'
                ]
            ],
            (int)719  => [
                'Aco' => [
                    'id'          => '735',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1631',
                    'rght'        => '1632'
                ]
            ],
            (int)720  => [
                'Aco' => [
                    'id'          => '736',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1633',
                    'rght'        => '1634'
                ]
            ],
            (int)721  => [
                'Aco' => [
                    'id'          => '737',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1635',
                    'rght'        => '1636'
                ]
            ],
            (int)722  => [
                'Aco' => [
                    'id'          => '738',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1637',
                    'rght'        => '1638'
                ]
            ],
            (int)723  => [
                'Aco' => [
                    'id'          => '739',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1639',
                    'rght'        => '1640'
                ]
            ],
            (int)724  => [
                'Aco' => [
                    'id'          => '740',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Usergroups',
                    'lft'         => '1648',
                    'rght'        => '1675'
                ]
            ],
            (int)725  => [
                'Aco' => [
                    'id'          => '741',
                    'parent_id'   => '740',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1649',
                    'rght'        => '1650'
                ]
            ],
            (int)726  => [
                'Aco' => [
                    'id'          => '742',
                    'parent_id'   => '740',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '1651',
                    'rght'        => '1652'
                ]
            ],
            (int)727  => [
                'Aco' => [
                    'id'          => '743',
                    'parent_id'   => '740',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '1653',
                    'rght'        => '1654'
                ]
            ],
            (int)728  => [
                'Aco' => [
                    'id'          => '744',
                    'parent_id'   => '740',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '1655',
                    'rght'        => '1656'
                ]
            ],
            (int)729  => [
                'Aco' => [
                    'id'          => '745',
                    'parent_id'   => '740',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1657',
                    'rght'        => '1658'
                ]
            ],
            (int)730  => [
                'Aco' => [
                    'id'          => '746',
                    'parent_id'   => '740',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1659',
                    'rght'        => '1660'
                ]
            ],
            (int)731  => [
                'Aco' => [
                    'id'          => '747',
                    'parent_id'   => '740',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1661',
                    'rght'        => '1662'
                ]
            ],
            (int)732  => [
                'Aco' => [
                    'id'          => '748',
                    'parent_id'   => '740',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1663',
                    'rght'        => '1664'
                ]
            ],
            (int)733  => [
                'Aco' => [
                    'id'          => '749',
                    'parent_id'   => '740',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1665',
                    'rght'        => '1666'
                ]
            ],
            (int)734  => [
                'Aco' => [
                    'id'          => '750',
                    'parent_id'   => '740',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1667',
                    'rght'        => '1668'
                ]
            ],
            (int)735  => [
                'Aco' => [
                    'id'          => '751',
                    'parent_id'   => '740',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1669',
                    'rght'        => '1670'
                ]
            ],
            (int)736  => [
                'Aco' => [
                    'id'          => '752',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Users',
                    'lft'         => '1676',
                    'rght'        => '1707'
                ]
            ],
            (int)737  => [
                'Aco' => [
                    'id'          => '753',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1677',
                    'rght'        => '1678'
                ]
            ],
            (int)738  => [
                'Aco' => [
                    'id'          => '754',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'delete',
                    'lft'         => '1679',
                    'rght'        => '1680'
                ]
            ],
            (int)739  => [
                'Aco' => [
                    'id'          => '755',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '1681',
                    'rght'        => '1682'
                ]
            ],
            (int)740  => [
                'Aco' => [
                    'id'          => '756',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'edit',
                    'lft'         => '1683',
                    'rght'        => '1684'
                ]
            ],
            (int)741  => [
                'Aco' => [
                    'id'          => '757',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'addFromLdap',
                    'lft'         => '1685',
                    'rght'        => '1686'
                ]
            ],
            (int)742  => [
                'Aco' => [
                    'id'          => '758',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'resetPassword',
                    'lft'         => '1687',
                    'rght'        => '1688'
                ]
            ],
            (int)743  => [
                'Aco' => [
                    'id'          => '759',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1689',
                    'rght'        => '1690'
                ]
            ],
            (int)744  => [
                'Aco' => [
                    'id'          => '760',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1691',
                    'rght'        => '1692'
                ]
            ],
            (int)745  => [
                'Aco' => [
                    'id'          => '761',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1693',
                    'rght'        => '1694'
                ]
            ],
            (int)746  => [
                'Aco' => [
                    'id'          => '762',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1695',
                    'rght'        => '1696'
                ]
            ],
            (int)747  => [
                'Aco' => [
                    'id'          => '763',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1697',
                    'rght'        => '1698'
                ]
            ],
            (int)748  => [
                'Aco' => [
                    'id'          => '764',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1699',
                    'rght'        => '1700'
                ]
            ],
            (int)749  => [
                'Aco' => [
                    'id'          => '765',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1701',
                    'rght'        => '1702'
                ]
            ],
            (int)750  => [
                'Aco' => [
                    'id'          => '766',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'AclExtras',
                    'lft'         => '1708',
                    'rght'        => '1709'
                ]
            ],
            (int)751  => [
                'Aco' => [
                    'id'          => '767',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Admin',
                    'lft'         => '1710',
                    'rght'        => '1711'
                ]
            ],
            (int)752  => [
                'Aco' => [
                    'id'          => '809',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'BoostCake',
                    'lft'         => '1712',
                    'rght'        => '1737'
                ]
            ],
            (int)753  => [
                'Aco' => [
                    'id'          => '810',
                    'parent_id'   => '809',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'BoostCake',
                    'lft'         => '1713',
                    'rght'        => '1736'
                ]
            ],
            (int)754  => [
                'Aco' => [
                    'id'          => '811',
                    'parent_id'   => '810',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1714',
                    'rght'        => '1715'
                ]
            ],
            (int)755  => [
                'Aco' => [
                    'id'          => '812',
                    'parent_id'   => '810',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'bootstrap2',
                    'lft'         => '1716',
                    'rght'        => '1717'
                ]
            ],
            (int)756  => [
                'Aco' => [
                    'id'          => '813',
                    'parent_id'   => '810',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'bootstrap3',
                    'lft'         => '1718',
                    'rght'        => '1719'
                ]
            ],
            (int)757  => [
                'Aco' => [
                    'id'          => '814',
                    'parent_id'   => '810',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1720',
                    'rght'        => '1721'
                ]
            ],
            (int)758  => [
                'Aco' => [
                    'id'          => '815',
                    'parent_id'   => '810',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1722',
                    'rght'        => '1723'
                ]
            ],
            (int)759  => [
                'Aco' => [
                    'id'          => '816',
                    'parent_id'   => '810',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1724',
                    'rght'        => '1725'
                ]
            ],
            (int)760  => [
                'Aco' => [
                    'id'          => '817',
                    'parent_id'   => '810',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1726',
                    'rght'        => '1727'
                ]
            ],
            (int)761  => [
                'Aco' => [
                    'id'          => '818',
                    'parent_id'   => '810',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1728',
                    'rght'        => '1729'
                ]
            ],
            (int)762  => [
                'Aco' => [
                    'id'          => '819',
                    'parent_id'   => '810',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1730',
                    'rght'        => '1731'
                ]
            ],
            (int)763  => [
                'Aco' => [
                    'id'          => '820',
                    'parent_id'   => '810',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1732',
                    'rght'        => '1733'
                ]
            ],
            (int)764  => [
                'Aco' => [
                    'id'          => '821',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'CakePdf',
                    'lft'         => '1738',
                    'rght'        => '1739'
                ]
            ],
            (int)765  => [
                'Aco' => [
                    'id'          => '822',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'ChatModule',
                    'lft'         => '1740',
                    'rght'        => '1761'
                ]
            ],
            (int)766  => [
                'Aco' => [
                    'id'          => '823',
                    'parent_id'   => '822',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Chat',
                    'lft'         => '1741',
                    'rght'        => '1760'
                ]
            ],
            (int)767  => [
                'Aco' => [
                    'id'          => '824',
                    'parent_id'   => '823',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1742',
                    'rght'        => '1743'
                ]
            ],
            (int)768  => [
                'Aco' => [
                    'id'          => '825',
                    'parent_id'   => '823',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1744',
                    'rght'        => '1745'
                ]
            ],
            (int)769  => [
                'Aco' => [
                    'id'          => '826',
                    'parent_id'   => '823',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1746',
                    'rght'        => '1747'
                ]
            ],
            (int)770  => [
                'Aco' => [
                    'id'          => '827',
                    'parent_id'   => '823',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1748',
                    'rght'        => '1749'
                ]
            ],
            (int)771  => [
                'Aco' => [
                    'id'          => '828',
                    'parent_id'   => '823',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1750',
                    'rght'        => '1751'
                ]
            ],
            (int)772  => [
                'Aco' => [
                    'id'          => '829',
                    'parent_id'   => '823',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1752',
                    'rght'        => '1753'
                ]
            ],
            (int)773  => [
                'Aco' => [
                    'id'          => '830',
                    'parent_id'   => '823',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1754',
                    'rght'        => '1755'
                ]
            ],
            (int)774  => [
                'Aco' => [
                    'id'          => '831',
                    'parent_id'   => '823',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1756',
                    'rght'        => '1757'
                ]
            ],
            (int)775  => [
                'Aco' => [
                    'id'          => '832',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'ClearCache',
                    'lft'         => '1762',
                    'rght'        => '1787'
                ]
            ],
            (int)776  => [
                'Aco' => [
                    'id'          => '833',
                    'parent_id'   => '832',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'ClearCache',
                    'lft'         => '1763',
                    'rght'        => '1786'
                ]
            ],
            (int)777  => [
                'Aco' => [
                    'id'          => '834',
                    'parent_id'   => '833',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'files',
                    'lft'         => '1764',
                    'rght'        => '1765'
                ]
            ],
            (int)778  => [
                'Aco' => [
                    'id'          => '835',
                    'parent_id'   => '833',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'engines',
                    'lft'         => '1766',
                    'rght'        => '1767'
                ]
            ],
            (int)779  => [
                'Aco' => [
                    'id'          => '836',
                    'parent_id'   => '833',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'groups',
                    'lft'         => '1768',
                    'rght'        => '1769'
                ]
            ],
            (int)780  => [
                'Aco' => [
                    'id'          => '837',
                    'parent_id'   => '833',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1770',
                    'rght'        => '1771'
                ]
            ],
            (int)781  => [
                'Aco' => [
                    'id'          => '838',
                    'parent_id'   => '833',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1772',
                    'rght'        => '1773'
                ]
            ],
            (int)782  => [
                'Aco' => [
                    'id'          => '839',
                    'parent_id'   => '833',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1774',
                    'rght'        => '1775'
                ]
            ],
            (int)783  => [
                'Aco' => [
                    'id'          => '840',
                    'parent_id'   => '833',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1776',
                    'rght'        => '1777'
                ]
            ],
            (int)784  => [
                'Aco' => [
                    'id'          => '841',
                    'parent_id'   => '833',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1778',
                    'rght'        => '1779'
                ]
            ],
            (int)785  => [
                'Aco' => [
                    'id'          => '842',
                    'parent_id'   => '833',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1780',
                    'rght'        => '1781'
                ]
            ],
            (int)786  => [
                'Aco' => [
                    'id'          => '843',
                    'parent_id'   => '833',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1782',
                    'rght'        => '1783'
                ]
            ],
            (int)787  => [
                'Aco' => [
                    'id'          => '844',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'DebugKit',
                    'lft'         => '1788',
                    'rght'        => '1811'
                ]
            ],
            (int)788  => [
                'Aco' => [
                    'id'          => '845',
                    'parent_id'   => '844',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'ToolbarAccess',
                    'lft'         => '1789',
                    'rght'        => '1810'
                ]
            ],
            (int)789  => [
                'Aco' => [
                    'id'          => '846',
                    'parent_id'   => '845',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'history_state',
                    'lft'         => '1790',
                    'rght'        => '1791'
                ]
            ],
            (int)790  => [
                'Aco' => [
                    'id'          => '847',
                    'parent_id'   => '845',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'sql_explain',
                    'lft'         => '1792',
                    'rght'        => '1793'
                ]
            ],
            (int)791  => [
                'Aco' => [
                    'id'          => '848',
                    'parent_id'   => '845',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1794',
                    'rght'        => '1795'
                ]
            ],
            (int)792  => [
                'Aco' => [
                    'id'          => '849',
                    'parent_id'   => '845',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1796',
                    'rght'        => '1797'
                ]
            ],
            (int)793  => [
                'Aco' => [
                    'id'          => '850',
                    'parent_id'   => '845',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1798',
                    'rght'        => '1799'
                ]
            ],
            (int)794  => [
                'Aco' => [
                    'id'          => '851',
                    'parent_id'   => '845',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1800',
                    'rght'        => '1801'
                ]
            ],
            (int)795  => [
                'Aco' => [
                    'id'          => '852',
                    'parent_id'   => '845',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1802',
                    'rght'        => '1803'
                ]
            ],
            (int)796  => [
                'Aco' => [
                    'id'          => '853',
                    'parent_id'   => '845',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1804',
                    'rght'        => '1805'
                ]
            ],
            (int)797  => [
                'Aco' => [
                    'id'          => '854',
                    'parent_id'   => '845',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1806',
                    'rght'        => '1807'
                ]
            ],
            (int)798  => [
                'Aco' => [
                    'id'          => '855',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'ExampleModule',
                    'lft'         => '1812',
                    'rght'        => '1813'
                ]
            ],
            (int)799  => [
                'Aco' => [
                    'id'          => '856',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Frontend',
                    'lft'         => '1814',
                    'rght'        => '1835'
                ]
            ],
            (int)800  => [
                'Aco' => [
                    'id'          => '857',
                    'parent_id'   => '856',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'FrontendDependencies',
                    'lft'         => '1815',
                    'rght'        => '1834'
                ]
            ],
            (int)801  => [
                'Aco' => [
                    'id'          => '858',
                    'parent_id'   => '857',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1816',
                    'rght'        => '1817'
                ]
            ],
            (int)802  => [
                'Aco' => [
                    'id'          => '859',
                    'parent_id'   => '857',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1818',
                    'rght'        => '1819'
                ]
            ],
            (int)803  => [
                'Aco' => [
                    'id'          => '860',
                    'parent_id'   => '857',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1820',
                    'rght'        => '1821'
                ]
            ],
            (int)804  => [
                'Aco' => [
                    'id'          => '861',
                    'parent_id'   => '857',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1822',
                    'rght'        => '1823'
                ]
            ],
            (int)805  => [
                'Aco' => [
                    'id'          => '862',
                    'parent_id'   => '857',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1824',
                    'rght'        => '1825'
                ]
            ],
            (int)806  => [
                'Aco' => [
                    'id'          => '863',
                    'parent_id'   => '857',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1826',
                    'rght'        => '1827'
                ]
            ],
            (int)807  => [
                'Aco' => [
                    'id'          => '864',
                    'parent_id'   => '857',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1828',
                    'rght'        => '1829'
                ]
            ],
            (int)808  => [
                'Aco' => [
                    'id'          => '865',
                    'parent_id'   => '857',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1830',
                    'rght'        => '1831'
                ]
            ],
            (int)809  => [
                'Aco' => [
                    'id'          => '866',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'ListFilter',
                    'lft'         => '1836',
                    'rght'        => '1837'
                ]
            ],
            (int)810  => [
                'Aco' => [
                    'id'          => '867',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'NagiosModule',
                    'lft'         => '1838',
                    'rght'        => '1883'
                ]
            ],
            (int)811  => [
                'Aco' => [
                    'id'          => '868',
                    'parent_id'   => '867',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Cmd',
                    'lft'         => '1839',
                    'rght'        => '1862'
                ]
            ],
            (int)812  => [
                'Aco' => [
                    'id'          => '869',
                    'parent_id'   => '868',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1840',
                    'rght'        => '1841'
                ]
            ],
            (int)813  => [
                'Aco' => [
                    'id'          => '870',
                    'parent_id'   => '868',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'submit',
                    'lft'         => '1842',
                    'rght'        => '1843'
                ]
            ],
            (int)814  => [
                'Aco' => [
                    'id'          => '871',
                    'parent_id'   => '868',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1844',
                    'rght'        => '1845'
                ]
            ],
            (int)815  => [
                'Aco' => [
                    'id'          => '872',
                    'parent_id'   => '868',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1846',
                    'rght'        => '1847'
                ]
            ],
            (int)816  => [
                'Aco' => [
                    'id'          => '873',
                    'parent_id'   => '868',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1848',
                    'rght'        => '1849'
                ]
            ],
            (int)817  => [
                'Aco' => [
                    'id'          => '874',
                    'parent_id'   => '868',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1850',
                    'rght'        => '1851'
                ]
            ],
            (int)818  => [
                'Aco' => [
                    'id'          => '875',
                    'parent_id'   => '868',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1852',
                    'rght'        => '1853'
                ]
            ],
            (int)819  => [
                'Aco' => [
                    'id'          => '876',
                    'parent_id'   => '868',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1854',
                    'rght'        => '1855'
                ]
            ],
            (int)820  => [
                'Aco' => [
                    'id'          => '877',
                    'parent_id'   => '868',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1856',
                    'rght'        => '1857'
                ]
            ],
            (int)821  => [
                'Aco' => [
                    'id'          => '878',
                    'parent_id'   => '867',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Nagios',
                    'lft'         => '1863',
                    'rght'        => '1882'
                ]
            ],
            (int)822  => [
                'Aco' => [
                    'id'          => '879',
                    'parent_id'   => '878',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1864',
                    'rght'        => '1865'
                ]
            ],
            (int)823  => [
                'Aco' => [
                    'id'          => '880',
                    'parent_id'   => '878',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1866',
                    'rght'        => '1867'
                ]
            ],
            (int)824  => [
                'Aco' => [
                    'id'          => '881',
                    'parent_id'   => '878',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1868',
                    'rght'        => '1869'
                ]
            ],
            (int)825  => [
                'Aco' => [
                    'id'          => '882',
                    'parent_id'   => '878',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1870',
                    'rght'        => '1871'
                ]
            ],
            (int)826  => [
                'Aco' => [
                    'id'          => '883',
                    'parent_id'   => '878',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1872',
                    'rght'        => '1873'
                ]
            ],
            (int)827  => [
                'Aco' => [
                    'id'          => '884',
                    'parent_id'   => '878',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1874',
                    'rght'        => '1875'
                ]
            ],
            (int)828  => [
                'Aco' => [
                    'id'          => '885',
                    'parent_id'   => '878',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1876',
                    'rght'        => '1877'
                ]
            ],
            (int)829  => [
                'Aco' => [
                    'id'          => '886',
                    'parent_id'   => '878',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1878',
                    'rght'        => '1879'
                ]
            ],
            (int)830  => [
                'Aco' => [
                    'id'          => '887',
                    'parent_id'   => '12',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'testMail',
                    'lft'         => '43',
                    'rght'        => '44'
                ]
            ],
            (int)831  => [
                'Aco' => [
                    'id'          => '888',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'addFromLdap',
                    'lft'         => '277',
                    'rght'        => '278'
                ]
            ],
            (int)832  => [
                'Aco' => [
                    'id'          => '889',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Dashboards',
                    'lft'         => '1884',
                    'rght'        => '1955'
                ]
            ],
            (int)833  => [
                'Aco' => [
                    'id'          => '890',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1885',
                    'rght'        => '1886'
                ]
            ],
            (int)834  => [
                'Aco' => [
                    'id'          => '891',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'next',
                    'lft'         => '1887',
                    'rght'        => '1888'
                ]
            ],
            (int)835  => [
                'Aco' => [
                    'id'          => '892',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '1889',
                    'rght'        => '1890'
                ]
            ],
            (int)836  => [
                'Aco' => [
                    'id'          => '893',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'createTab',
                    'lft'         => '1891',
                    'rght'        => '1892'
                ]
            ],
            (int)837  => [
                'Aco' => [
                    'id'          => '894',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'createTabFromSharing',
                    'lft'         => '1893',
                    'rght'        => '1894'
                ]
            ],
            (int)838  => [
                'Aco' => [
                    'id'          => '895',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'updateSharedTab',
                    'lft'         => '1895',
                    'rght'        => '1896'
                ]
            ],
            (int)839  => [
                'Aco' => [
                    'id'          => '896',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'disableUpdate',
                    'lft'         => '1897',
                    'rght'        => '1898'
                ]
            ],
            (int)840  => [
                'Aco' => [
                    'id'          => '897',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'renameTab',
                    'lft'         => '1899',
                    'rght'        => '1900'
                ]
            ],
            (int)841  => [
                'Aco' => [
                    'id'          => '898',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'deleteTab',
                    'lft'         => '1901',
                    'rght'        => '1902'
                ]
            ],
            (int)842  => [
                'Aco' => [
                    'id'          => '899',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'restoreDefault',
                    'lft'         => '1903',
                    'rght'        => '1904'
                ]
            ],
            (int)843  => [
                'Aco' => [
                    'id'          => '900',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'updateTitle',
                    'lft'         => '1905',
                    'rght'        => '1906'
                ]
            ],
            (int)844  => [
                'Aco' => [
                    'id'          => '901',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'updateColor',
                    'lft'         => '1907',
                    'rght'        => '1908'
                ]
            ],
            (int)845  => [
                'Aco' => [
                    'id'          => '902',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'updatePosition',
                    'lft'         => '1909',
                    'rght'        => '1910'
                ]
            ],
            (int)846  => [
                'Aco' => [
                    'id'          => '903',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'deleteWidget',
                    'lft'         => '1911',
                    'rght'        => '1912'
                ]
            ],
            (int)847  => [
                'Aco' => [
                    'id'          => '904',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'updateTabPosition',
                    'lft'         => '1913',
                    'rght'        => '1914'
                ]
            ],
            (int)848  => [
                'Aco' => [
                    'id'          => '905',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'saveTabRotationInterval',
                    'lft'         => '1915',
                    'rght'        => '1916'
                ]
            ],
            (int)849  => [
                'Aco' => [
                    'id'          => '906',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'startSharing',
                    'lft'         => '1917',
                    'rght'        => '1918'
                ]
            ],
            (int)850  => [
                'Aco' => [
                    'id'          => '907',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'stopSharing',
                    'lft'         => '1919',
                    'rght'        => '1920'
                ]
            ],
            (int)851  => [
                'Aco' => [
                    'id'          => '908',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'refresh',
                    'lft'         => '1921',
                    'rght'        => '1922'
                ]
            ],
            (int)852  => [
                'Aco' => [
                    'id'          => '909',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'saveStatuslistSettings',
                    'lft'         => '1923',
                    'rght'        => '1924'
                ]
            ],
            (int)853  => [
                'Aco' => [
                    'id'          => '910',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'saveTrafficLightService',
                    'lft'         => '1925',
                    'rght'        => '1926'
                ]
            ],
            (int)854  => [
                'Aco' => [
                    'id'          => '911',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getTachoPerfdata',
                    'lft'         => '1927',
                    'rght'        => '1928'
                ]
            ],
            (int)855  => [
                'Aco' => [
                    'id'          => '912',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'saveTachoConfig',
                    'lft'         => '1929',
                    'rght'        => '1930'
                ]
            ],
            (int)856  => [
                'Aco' => [
                    'id'          => '913',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1931',
                    'rght'        => '1932'
                ]
            ],
            (int)857  => [
                'Aco' => [
                    'id'          => '914',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1933',
                    'rght'        => '1934'
                ]
            ],
            (int)858  => [
                'Aco' => [
                    'id'          => '915',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1935',
                    'rght'        => '1936'
                ]
            ],
            (int)859  => [
                'Aco' => [
                    'id'          => '916',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1937',
                    'rght'        => '1938'
                ]
            ],
            (int)860  => [
                'Aco' => [
                    'id'          => '917',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1939',
                    'rght'        => '1940'
                ]
            ],
            (int)861  => [
                'Aco' => [
                    'id'          => '918',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1941',
                    'rght'        => '1942'
                ]
            ],
            (int)862  => [
                'Aco' => [
                    'id'          => '919',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1943',
                    'rght'        => '1944'
                ]
            ],
            (int)863  => [
                'Aco' => [
                    'id'          => '920',
                    'parent_id'   => '218',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'broadcast',
                    'lft'         => '477',
                    'rght'        => '478'
                ]
            ],
            (int)864  => [
                'Aco' => [
                    'id'          => '921',
                    'parent_id'   => '218',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'launchExport',
                    'lft'         => '479',
                    'rght'        => '480'
                ]
            ],
            (int)865  => [
                'Aco' => [
                    'id'          => '922',
                    'parent_id'   => '218',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'verifyConfig',
                    'lft'         => '481',
                    'rght'        => '482'
                ]
            ],
            (int)866  => [
                'Aco' => [
                    'id'          => '923',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allocateToMatchingHostgroup',
                    'lft'         => '1383',
                    'rght'        => '1384'
                ]
            ],
            (int)867  => [
                'Aco' => [
                    'id'          => '924',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '207',
                    'rght'        => '208'
                ]
            ],
            (int)868  => [
                'Aco' => [
                    'id'          => '925',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'usedBy',
                    'lft'         => '209',
                    'rght'        => '210'
                ]
            ],
            (int)869  => [
                'Aco' => [
                    'id'          => '926',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '243',
                    'rght'        => '244'
                ]
            ],
            (int)870  => [
                'Aco' => [
                    'id'          => '927',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '279',
                    'rght'        => '280'
                ]
            ],
            (int)871  => [
                'Aco' => [
                    'id'          => '928',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'nest',
                    'lft'         => '311',
                    'rght'        => '312'
                ]
            ],
            (int)872  => [
                'Aco' => [
                    'id'          => '929',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '313',
                    'rght'        => '314'
                ]
            ],
            (int)873  => [
                'Aco' => [
                    'id'          => '930',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'saveMapId',
                    'lft'         => '1945',
                    'rght'        => '1946'
                ]
            ],
            (int)874  => [
                'Aco' => [
                    'id'          => '931',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'saveGraphId',
                    'lft'         => '1947',
                    'rght'        => '1948'
                ]
            ],
            (int)875  => [
                'Aco' => [
                    'id'          => '932',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'saveNotice',
                    'lft'         => '1949',
                    'rght'        => '1950'
                ]
            ],
            (int)876  => [
                'Aco' => [
                    'id'          => '933',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'saveMap',
                    'lft'         => '1951',
                    'rght'        => '1952'
                ]
            ],
            (int)877  => [
                'Aco' => [
                    'id'          => '935',
                    'parent_id'   => '236',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'add',
                    'lft'         => '533',
                    'rght'        => '534'
                ]
            ],
            (int)878  => [
                'Aco' => [
                    'id'          => '936',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '571',
                    'rght'        => '572'
                ]
            ],
            (int)879  => [
                'Aco' => [
                    'id'          => '937',
                    'parent_id'   => '275',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '621',
                    'rght'        => '622'
                ]
            ],
            (int)880  => [
                'Aco' => [
                    'id'          => '938',
                    'parent_id'   => '288',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '651',
                    'rght'        => '652'
                ]
            ],
            (int)881  => [
                'Aco' => [
                    'id'          => '939',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '689',
                    'rght'        => '690'
                ]
            ],
            (int)882  => [
                'Aco' => [
                    'id'          => '940',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '773',
                    'rght'        => '774'
                ]
            ],
            (int)883  => [
                'Aco' => [
                    'id'          => '941',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allocateServiceTemplateGroup',
                    'lft'         => '775',
                    'rght'        => '776'
                ]
            ],
            (int)884  => [
                'Aco' => [
                    'id'          => '942',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getServiceTemplatesfromGroup',
                    'lft'         => '777',
                    'rght'        => '778'
                ]
            ],
            (int)885  => [
                'Aco' => [
                    'id'          => '943',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '817',
                    'rght'        => '818'
                ]
            ],
            (int)886  => [
                'Aco' => [
                    'id'          => '944',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_delete',
                    'lft'         => '819',
                    'rght'        => '820'
                ]
            ],
            (int)887  => [
                'Aco' => [
                    'id'          => '945',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'copy',
                    'lft'         => '821',
                    'rght'        => '822'
                ]
            ],
            (int)888  => [
                'Aco' => [
                    'id'          => '946',
                    'parent_id'   => '384',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '873',
                    'rght'        => '874'
                ]
            ],
            (int)889  => [
                'Aco' => [
                    'id'          => '947',
                    'parent_id'   => '529',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '1195',
                    'rght'        => '1196'
                ]
            ],
            (int)890  => [
                'Aco' => [
                    'id'          => '948',
                    'parent_id'   => '542',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '1225',
                    'rght'        => '1226'
                ]
            ],
            (int)891  => [
                'Aco' => [
                    'id'          => '949',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '1261',
                    'rght'        => '1262'
                ]
            ],
            (int)892  => [
                'Aco' => [
                    'id'          => '950',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '1345',
                    'rght'        => '1346'
                ]
            ],
            (int)893  => [
                'Aco' => [
                    'id'          => '951',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getSelectedServices',
                    'lft'         => '1347',
                    'rght'        => '1348'
                ]
            ],
            (int)894  => [
                'Aco' => [
                    'id'          => '955',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '1385',
                    'rght'        => '1386'
                ]
            ],
            (int)895  => [
                'Aco' => [
                    'id'          => '956',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '1431',
                    'rght'        => '1432'
                ]
            ],
            (int)896  => [
                'Aco' => [
                    'id'          => '957',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'mass_delete',
                    'lft'         => '1433',
                    'rght'        => '1434'
                ]
            ],
            (int)897  => [
                'Aco' => [
                    'id'          => '958',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'copy',
                    'lft'         => '1435',
                    'rght'        => '1436'
                ]
            ],
            (int)898  => [
                'Aco' => [
                    'id'          => '959',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'assignGroup',
                    'lft'         => '1437',
                    'rght'        => '1438'
                ]
            ],
            (int)899  => [
                'Aco' => [
                    'id'          => '960',
                    'parent_id'   => '712',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '1607',
                    'rght'        => '1608'
                ]
            ],
            (int)900  => [
                'Aco' => [
                    'id'          => '961',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '1641',
                    'rght'        => '1642'
                ]
            ],
            (int)901  => [
                'Aco' => [
                    'id'          => '962',
                    'parent_id'   => '740',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '1671',
                    'rght'        => '1672'
                ]
            ],
            (int)902  => [
                'Aco' => [
                    'id'          => '963',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'view',
                    'lft'         => '1703',
                    'rght'        => '1704'
                ]
            ],
            (int)903  => [
                'Aco' => [
                    'id'          => '964',
                    'parent_id'   => '868',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'ack',
                    'lft'         => '1858',
                    'rght'        => '1859'
                ]
            ],
            (int)904  => [
                'Aco' => [
                    'id'          => '965',
                    'parent_id'   => '2',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '21',
                    'rght'        => '22'
                ]
            ],
            (int)905  => [
                'Aco' => [
                    'id'          => '966',
                    'parent_id'   => '12',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '45',
                    'rght'        => '46'
                ]
            ],
            (int)906  => [
                'Aco' => [
                    'id'          => '967',
                    'parent_id'   => '22',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '75',
                    'rght'        => '76'
                ]
            ],
            (int)907  => [
                'Aco' => [
                    'id'          => '968',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Backups',
                    'lft'         => '1956',
                    'rght'        => '1983'
                ]
            ],
            (int)908  => [
                'Aco' => [
                    'id'          => '969',
                    'parent_id'   => '968',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1957',
                    'rght'        => '1958'
                ]
            ],
            (int)909  => [
                'Aco' => [
                    'id'          => '970',
                    'parent_id'   => '968',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'backup',
                    'lft'         => '1959',
                    'rght'        => '1960'
                ]
            ],
            (int)910  => [
                'Aco' => [
                    'id'          => '971',
                    'parent_id'   => '968',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'restore',
                    'lft'         => '1961',
                    'rght'        => '1962'
                ]
            ],
            (int)911  => [
                'Aco' => [
                    'id'          => '972',
                    'parent_id'   => '968',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1963',
                    'rght'        => '1964'
                ]
            ],
            (int)912  => [
                'Aco' => [
                    'id'          => '973',
                    'parent_id'   => '968',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1965',
                    'rght'        => '1966'
                ]
            ],
            (int)913  => [
                'Aco' => [
                    'id'          => '974',
                    'parent_id'   => '968',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1967',
                    'rght'        => '1968'
                ]
            ],
            (int)914  => [
                'Aco' => [
                    'id'          => '975',
                    'parent_id'   => '968',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1969',
                    'rght'        => '1970'
                ]
            ],
            (int)915  => [
                'Aco' => [
                    'id'          => '976',
                    'parent_id'   => '968',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1971',
                    'rght'        => '1972'
                ]
            ],
            (int)916  => [
                'Aco' => [
                    'id'          => '977',
                    'parent_id'   => '968',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1973',
                    'rght'        => '1974'
                ]
            ],
            (int)917  => [
                'Aco' => [
                    'id'          => '978',
                    'parent_id'   => '968',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '1975',
                    'rght'        => '1976'
                ]
            ],
            (int)918  => [
                'Aco' => [
                    'id'          => '979',
                    'parent_id'   => '968',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1977',
                    'rght'        => '1978'
                ]
            ],
            (int)919  => [
                'Aco' => [
                    'id'          => '980',
                    'parent_id'   => '36',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '97',
                    'rght'        => '98'
                ]
            ],
            (int)920  => [
                'Aco' => [
                    'id'          => '981',
                    'parent_id'   => '49',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '127',
                    'rght'        => '128'
                ]
            ],
            (int)921  => [
                'Aco' => [
                    'id'          => '982',
                    'parent_id'   => '63',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '147',
                    'rght'        => '148'
                ]
            ],
            (int)922  => [
                'Aco' => [
                    'id'          => '983',
                    'parent_id'   => '72',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '167',
                    'rght'        => '168'
                ]
            ],
            (int)923  => [
                'Aco' => [
                    'id'          => '984',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '211',
                    'rght'        => '212'
                ]
            ],
            (int)924  => [
                'Aco' => [
                    'id'          => '985',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '245',
                    'rght'        => '246'
                ]
            ],
            (int)925  => [
                'Aco' => [
                    'id'          => '986',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '281',
                    'rght'        => '282'
                ]
            ],
            (int)926  => [
                'Aco' => [
                    'id'          => '987',
                    'parent_id'   => '128',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '315',
                    'rght'        => '316'
                ]
            ],
            (int)927  => [
                'Aco' => [
                    'id'          => '988',
                    'parent_id'   => '141',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '343',
                    'rght'        => '344'
                ]
            ],
            (int)928  => [
                'Aco' => [
                    'id'          => '989',
                    'parent_id'   => '154',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '365',
                    'rght'        => '366'
                ]
            ],
            (int)929  => [
                'Aco' => [
                    'id'          => '990',
                    'parent_id'   => '889',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1953',
                    'rght'        => '1954'
                ]
            ],
            (int)930  => [
                'Aco' => [
                    'id'          => '991',
                    'parent_id'   => '164',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '385',
                    'rght'        => '386'
                ]
            ],
            (int)931  => [
                'Aco' => [
                    'id'          => '993',
                    'parent_id'   => '185',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '409',
                    'rght'        => '410'
                ]
            ],
            (int)932  => [
                'Aco' => [
                    'id'          => '994',
                    'parent_id'   => '196',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '431',
                    'rght'        => '432'
                ]
            ],
            (int)933  => [
                'Aco' => [
                    'id'          => '995',
                    'parent_id'   => '206',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '457',
                    'rght'        => '458'
                ]
            ],
            (int)934  => [
                'Aco' => [
                    'id'          => '996',
                    'parent_id'   => '218',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '483',
                    'rght'        => '484'
                ]
            ],
            (int)935  => [
                'Aco' => [
                    'id'          => '997',
                    'parent_id'   => '227',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '505',
                    'rght'        => '506'
                ]
            ],
            (int)936  => [
                'Aco' => [
                    'id'          => '998',
                    'parent_id'   => '236',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '535',
                    'rght'        => '536'
                ]
            ],
            (int)937  => [
                'Aco' => [
                    'id'          => '999',
                    'parent_id'   => '249',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '573',
                    'rght'        => '574'
                ]
            ],
            (int)938  => [
                'Aco' => [
                    'id'          => '1000',
                    'parent_id'   => '266',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '593',
                    'rght'        => '594'
                ]
            ],
            (int)939  => [
                'Aco' => [
                    'id'          => '1001',
                    'parent_id'   => '275',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '623',
                    'rght'        => '624'
                ]
            ],
            (int)940  => [
                'Aco' => [
                    'id'          => '1002',
                    'parent_id'   => '288',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '653',
                    'rght'        => '654'
                ]
            ],
            (int)941  => [
                'Aco' => [
                    'id'          => '1003',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadServicesByHostId',
                    'lft'         => '691',
                    'rght'        => '692'
                ]
            ],
            (int)942  => [
                'Aco' => [
                    'id'          => '1004',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '693',
                    'rght'        => '694'
                ]
            ],
            (int)943  => [
                'Aco' => [
                    'id'          => '1005',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getSharingContainers',
                    'lft'         => '779',
                    'rght'        => '780'
                ]
            ],
            (int)944  => [
                'Aco' => [
                    'id'          => '1006',
                    'parent_id'   => '318',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '781',
                    'rght'        => '782'
                ]
            ],
            (int)945  => [
                'Aco' => [
                    'id'          => '1007',
                    'parent_id'   => '356',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '823',
                    'rght'        => '824'
                ]
            ],
            (int)946  => [
                'Aco' => [
                    'id'          => '1008',
                    'parent_id'   => '373',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '847',
                    'rght'        => '848'
                ]
            ],
            (int)947  => [
                'Aco' => [
                    'id'          => '1009',
                    'parent_id'   => '384',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '875',
                    'rght'        => '876'
                ]
            ],
            (int)948  => [
                'Aco' => [
                    'id'          => '1010',
                    'parent_id'   => '396',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '895',
                    'rght'        => '896'
                ]
            ],
            (int)949  => [
                'Aco' => [
                    'id'          => '1011',
                    'parent_id'   => '405',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '925',
                    'rght'        => '926'
                ]
            ],
            (int)950  => [
                'Aco' => [
                    'id'          => '1012',
                    'parent_id'   => '419',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '947',
                    'rght'        => '948'
                ]
            ],
            (int)951  => [
                'Aco' => [
                    'id'          => '1013',
                    'parent_id'   => '429',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '967',
                    'rght'        => '968'
                ]
            ],
            (int)952  => [
                'Aco' => [
                    'id'          => '1014',
                    'parent_id'   => '438',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '991',
                    'rght'        => '992'
                ]
            ],
            (int)953  => [
                'Aco' => [
                    'id'          => '1015',
                    'parent_id'   => '449',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1013',
                    'rght'        => '1014'
                ]
            ],
            (int)954  => [
                'Aco' => [
                    'id'          => '1016',
                    'parent_id'   => '459',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1035',
                    'rght'        => '1036'
                ]
            ],
            (int)955  => [
                'Aco' => [
                    'id'          => '1017',
                    'parent_id'   => '469',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1059',
                    'rght'        => '1060'
                ]
            ],
            (int)956  => [
                'Aco' => [
                    'id'          => '1018',
                    'parent_id'   => '480',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1079',
                    'rght'        => '1080'
                ]
            ],
            (int)957  => [
                'Aco' => [
                    'id'          => '1019',
                    'parent_id'   => '489',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1101',
                    'rght'        => '1102'
                ]
            ],
            (int)958  => [
                'Aco' => [
                    'id'          => '1020',
                    'parent_id'   => '499',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1123',
                    'rght'        => '1124'
                ]
            ],
            (int)959  => [
                'Aco' => [
                    'id'          => '1021',
                    'parent_id'   => '509',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1147',
                    'rght'        => '1148'
                ]
            ],
            (int)960  => [
                'Aco' => [
                    'id'          => '1022',
                    'parent_id'   => '520',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1167',
                    'rght'        => '1168'
                ]
            ],
            (int)961  => [
                'Aco' => [
                    'id'          => '1023',
                    'parent_id'   => '529',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1197',
                    'rght'        => '1198'
                ]
            ],
            (int)962  => [
                'Aco' => [
                    'id'          => '1024',
                    'parent_id'   => '542',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1227',
                    'rght'        => '1228'
                ]
            ],
            (int)963  => [
                'Aco' => [
                    'id'          => '1025',
                    'parent_id'   => '555',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1263',
                    'rght'        => '1264'
                ]
            ],
            (int)964  => [
                'Aco' => [
                    'id'          => '1026',
                    'parent_id'   => '571',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1349',
                    'rght'        => '1350'
                ]
            ],
            (int)965  => [
                'Aco' => [
                    'id'          => '1027',
                    'parent_id'   => '611',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1387',
                    'rght'        => '1388'
                ]
            ],
            (int)966  => [
                'Aco' => [
                    'id'          => '1028',
                    'parent_id'   => '627',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1439',
                    'rght'        => '1440'
                ]
            ],
            (int)967  => [
                'Aco' => [
                    'id'          => '1029',
                    'parent_id'   => '648',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1461',
                    'rght'        => '1462'
                ]
            ],
            (int)968  => [
                'Aco' => [
                    'id'          => '1030',
                    'parent_id'   => '658',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1487',
                    'rght'        => '1488'
                ]
            ],
            (int)969  => [
                'Aco' => [
                    'id'          => '1031',
                    'parent_id'   => '670',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1507',
                    'rght'        => '1508'
                ]
            ],
            (int)970  => [
                'Aco' => [
                    'id'          => '1032',
                    'parent_id'   => '679',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1535',
                    'rght'        => '1536'
                ]
            ],
            (int)971  => [
                'Aco' => [
                    'id'          => '1033',
                    'parent_id'   => '692',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1559',
                    'rght'        => '1560'
                ]
            ],
            (int)972  => [
                'Aco' => [
                    'id'          => '1034',
                    'parent_id'   => '703',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1579',
                    'rght'        => '1580'
                ]
            ],
            (int)973  => [
                'Aco' => [
                    'id'          => '1035',
                    'parent_id'   => '712',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1609',
                    'rght'        => '1610'
                ]
            ],
            (int)974  => [
                'Aco' => [
                    'id'          => '1036',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1643',
                    'rght'        => '1644'
                ]
            ],
            (int)975  => [
                'Aco' => [
                    'id'          => '1037',
                    'parent_id'   => '740',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1673',
                    'rght'        => '1674'
                ]
            ],
            (int)976  => [
                'Aco' => [
                    'id'          => '1038',
                    'parent_id'   => '752',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1705',
                    'rght'        => '1706'
                ]
            ],
            (int)977  => [
                'Aco' => [
                    'id'          => '1039',
                    'parent_id'   => '810',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1734',
                    'rght'        => '1735'
                ]
            ],
            (int)978  => [
                'Aco' => [
                    'id'          => '1040',
                    'parent_id'   => '823',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1758',
                    'rght'        => '1759'
                ]
            ],
            (int)979  => [
                'Aco' => [
                    'id'          => '1041',
                    'parent_id'   => '833',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1784',
                    'rght'        => '1785'
                ]
            ],
            (int)980  => [
                'Aco' => [
                    'id'          => '1042',
                    'parent_id'   => '845',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1808',
                    'rght'        => '1809'
                ]
            ],
            (int)981  => [
                'Aco' => [
                    'id'          => '1043',
                    'parent_id'   => '857',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1832',
                    'rght'        => '1833'
                ]
            ],
            (int)982  => [
                'Aco' => [
                    'id'          => '1044',
                    'parent_id'   => '868',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1860',
                    'rght'        => '1861'
                ]
            ],
            (int)983  => [
                'Aco' => [
                    'id'          => '1045',
                    'parent_id'   => '878',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '1880',
                    'rght'        => '1881'
                ]
            ],
            (int)984  => [
                'Aco' => [
                    'id'          => '1046',
                    'parent_id'   => '81',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'copy',
                    'lft'         => '213',
                    'rght'        => '214'
                ]
            ],
            (int)985  => [
                'Aco' => [
                    'id'          => '1047',
                    'parent_id'   => '100',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'copy',
                    'lft'         => '247',
                    'rght'        => '248'
                ]
            ],
            (int)986  => [
                'Aco' => [
                    'id'          => '1048',
                    'parent_id'   => '114',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'copy',
                    'lft'         => '283',
                    'rght'        => '284'
                ]
            ],
            (int)987  => [
                'Aco' => [
                    'id'          => '1050',
                    'parent_id'   => '725',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'copy',
                    'lft'         => '1645',
                    'rght'        => '1646'
                ]
            ],
            (int)988  => [
                'Aco' => [
                    'id'          => '1129',
                    'parent_id'   => '1',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'Supports',
                    'lft'         => '1984',
                    'rght'        => '2005'
                ]
            ],
            (int)989  => [
                'Aco' => [
                    'id'          => '1130',
                    'parent_id'   => '1129',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'index',
                    'lft'         => '1985',
                    'rght'        => '1986'
                ]
            ],
            (int)990  => [
                'Aco' => [
                    'id'          => '1131',
                    'parent_id'   => '1129',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'issue',
                    'lft'         => '1987',
                    'rght'        => '1988'
                ]
            ],
            (int)991  => [
                'Aco' => [
                    'id'          => '1132',
                    'parent_id'   => '1129',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'isAuthorized',
                    'lft'         => '1989',
                    'rght'        => '1990'
                ]
            ],
            (int)992  => [
                'Aco' => [
                    'id'          => '1133',
                    'parent_id'   => '1129',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'flashBack',
                    'lft'         => '1991',
                    'rght'        => '1992'
                ]
            ],
            (int)993  => [
                'Aco' => [
                    'id'          => '1134',
                    'parent_id'   => '1129',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'setFlash',
                    'lft'         => '1993',
                    'rght'        => '1994'
                ]
            ],
            (int)994  => [
                'Aco' => [
                    'id'          => '1135',
                    'parent_id'   => '1129',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'serviceResponse',
                    'lft'         => '1995',
                    'rght'        => '1996'
                ]
            ],
            (int)995  => [
                'Aco' => [
                    'id'          => '1136',
                    'parent_id'   => '1129',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'getNamedParameter',
                    'lft'         => '1997',
                    'rght'        => '1998'
                ]
            ],
            (int)996  => [
                'Aco' => [
                    'id'          => '1137',
                    'parent_id'   => '1129',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'allowedByContainerId',
                    'lft'         => '1999',
                    'rght'        => '2000'
                ]
            ],
            (int)997  => [
                'Aco' => [
                    'id'          => '1138',
                    'parent_id'   => '1129',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'render403',
                    'lft'         => '2001',
                    'rght'        => '2002'
                ]
            ],
            (int)998  => [
                'Aco' => [
                    'id'          => '1139',
                    'parent_id'   => '1129',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkForUpdates',
                    'lft'         => '2003',
                    'rght'        => '2004'
                ]
            ],
            (int)999  => [
                'Aco' => [
                    'id'          => '1140',
                    'parent_id'   => '968',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'checkBackupFinished',
                    'lft'         => '1979',
                    'rght'        => '1980'
                ]
            ],
            (int)1000 => [
                'Aco' => [
                    'id'          => '1141',
                    'parent_id'   => '968',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'deleteBackupFile',
                    'lft'         => '1981',
                    'rght'        => '1982'
                ]
            ],
            (int)1001 => [
                'Aco' => [
                    'id'          => '1142',
                    'parent_id'   => '218',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'saveInstanceConfigSyncSelection',
                    'lft'         => '485',
                    'rght'        => '486'
                ]
            ],
            (int)1002 => [
                'Aco' => [
                    'id'          => '1143',
                    'parent_id'   => '301',
                    'model'       => null,
                    'foreign_key' => null,
                    'alias'       => 'loadHosttemplates',
                    'lft'         => '695',
                    'rght'        => '696'
                ]
            ]
        ];

        return $data;
    }
}