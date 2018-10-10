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
        $data = array(
            (int) 0 => array(
                'Aco' => array(
                    'id' => '1',
                    'parent_id' => null,
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'controllers',
                    'lft' => '1',
                    'rght' => '2006'
                )
            ),
            (int) 1 => array(
                'Aco' => array(
                    'id' => '2',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Acknowledgements',
                    'lft' => '2',
                    'rght' => '23'
                )
            ),
            (int) 2 => array(
                'Aco' => array(
                    'id' => '3',
                    'parent_id' => '2',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'service',
                    'lft' => '3',
                    'rght' => '4'
                )
            ),
            (int) 3 => array(
                'Aco' => array(
                    'id' => '4',
                    'parent_id' => '2',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'host',
                    'lft' => '5',
                    'rght' => '6'
                )
            ),
            (int) 4 => array(
                'Aco' => array(
                    'id' => '5',
                    'parent_id' => '2',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '7',
                    'rght' => '8'
                )
            ),
            (int) 5 => array(
                'Aco' => array(
                    'id' => '6',
                    'parent_id' => '2',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '9',
                    'rght' => '10'
                )
            ),
            (int) 6 => array(
                'Aco' => array(
                    'id' => '7',
                    'parent_id' => '2',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '11',
                    'rght' => '12'
                )
            ),
            (int) 7 => array(
                'Aco' => array(
                    'id' => '8',
                    'parent_id' => '2',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '13',
                    'rght' => '14'
                )
            ),
            (int) 8 => array(
                'Aco' => array(
                    'id' => '9',
                    'parent_id' => '2',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '15',
                    'rght' => '16'
                )
            ),
            (int) 9 => array(
                'Aco' => array(
                    'id' => '10',
                    'parent_id' => '2',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '17',
                    'rght' => '18'
                )
            ),
            (int) 10 => array(
                'Aco' => array(
                    'id' => '11',
                    'parent_id' => '2',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '19',
                    'rght' => '20'
                )
            ),
            (int) 11 => array(
                'Aco' => array(
                    'id' => '12',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Administrators',
                    'lft' => '24',
                    'rght' => '47'
                )
            ),
            (int) 12 => array(
                'Aco' => array(
                    'id' => '13',
                    'parent_id' => '12',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '25',
                    'rght' => '26'
                )
            ),
            (int) 13 => array(
                'Aco' => array(
                    'id' => '14',
                    'parent_id' => '12',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'debug',
                    'lft' => '27',
                    'rght' => '28'
                )
            ),
            (int) 14 => array(
                'Aco' => array(
                    'id' => '15',
                    'parent_id' => '12',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '29',
                    'rght' => '30'
                )
            ),
            (int) 15 => array(
                'Aco' => array(
                    'id' => '16',
                    'parent_id' => '12',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '31',
                    'rght' => '32'
                )
            ),
            (int) 16 => array(
                'Aco' => array(
                    'id' => '17',
                    'parent_id' => '12',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '33',
                    'rght' => '34'
                )
            ),
            (int) 17 => array(
                'Aco' => array(
                    'id' => '18',
                    'parent_id' => '12',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '35',
                    'rght' => '36'
                )
            ),
            (int) 18 => array(
                'Aco' => array(
                    'id' => '19',
                    'parent_id' => '12',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '37',
                    'rght' => '38'
                )
            ),
            (int) 19 => array(
                'Aco' => array(
                    'id' => '20',
                    'parent_id' => '12',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '39',
                    'rght' => '40'
                )
            ),
            (int) 20 => array(
                'Aco' => array(
                    'id' => '21',
                    'parent_id' => '12',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '41',
                    'rght' => '42'
                )
            ),
            (int) 21 => array(
                'Aco' => array(
                    'id' => '22',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Automaps',
                    'lft' => '48',
                    'rght' => '77'
                )
            ),
            (int) 22 => array(
                'Aco' => array(
                    'id' => '23',
                    'parent_id' => '22',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '49',
                    'rght' => '50'
                )
            ),
            (int) 23 => array(
                'Aco' => array(
                    'id' => '24',
                    'parent_id' => '22',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '51',
                    'rght' => '52'
                )
            ),
            (int) 24 => array(
                'Aco' => array(
                    'id' => '25',
                    'parent_id' => '22',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '53',
                    'rght' => '54'
                )
            ),
            (int) 25 => array(
                'Aco' => array(
                    'id' => '26',
                    'parent_id' => '22',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '55',
                    'rght' => '56'
                )
            ),
            (int) 26 => array(
                'Aco' => array(
                    'id' => '27',
                    'parent_id' => '22',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadServiceDetails',
                    'lft' => '57',
                    'rght' => '58'
                )
            ),
            (int) 27 => array(
                'Aco' => array(
                    'id' => '28',
                    'parent_id' => '22',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '59',
                    'rght' => '60'
                )
            ),
            (int) 28 => array(
                'Aco' => array(
                    'id' => '29',
                    'parent_id' => '22',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '61',
                    'rght' => '62'
                )
            ),
            (int) 29 => array(
                'Aco' => array(
                    'id' => '30',
                    'parent_id' => '22',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '63',
                    'rght' => '64'
                )
            ),
            (int) 30 => array(
                'Aco' => array(
                    'id' => '31',
                    'parent_id' => '22',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '65',
                    'rght' => '66'
                )
            ),
            (int) 31 => array(
                'Aco' => array(
                    'id' => '32',
                    'parent_id' => '22',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '67',
                    'rght' => '68'
                )
            ),
            (int) 32 => array(
                'Aco' => array(
                    'id' => '33',
                    'parent_id' => '22',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '69',
                    'rght' => '70'
                )
            ),
            (int) 33 => array(
                'Aco' => array(
                    'id' => '34',
                    'parent_id' => '22',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '71',
                    'rght' => '72'
                )
            ),
            (int) 34 => array(
                'Aco' => array(
                    'id' => '35',
                    'parent_id' => '22',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '73',
                    'rght' => '74'
                )
            ),
            (int) 35 => array(
                'Aco' => array(
                    'id' => '36',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Browsers',
                    'lft' => '78',
                    'rght' => '99'
                )
            ),
            (int) 36 => array(
                'Aco' => array(
                    'id' => '37',
                    'parent_id' => '36',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '79',
                    'rght' => '80'
                )
            ),
            (int) 37 => array(
                'Aco' => array(
                    'id' => '38',
                    'parent_id' => '36',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'tenantBrowser',
                    'lft' => '81',
                    'rght' => '82'
                )
            ),
            (int) 38 => array(
                'Aco' => array(
                    'id' => '42',
                    'parent_id' => '36',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '83',
                    'rght' => '84'
                )
            ),
            (int) 39 => array(
                'Aco' => array(
                    'id' => '43',
                    'parent_id' => '36',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '85',
                    'rght' => '86'
                )
            ),
            (int) 40 => array(
                'Aco' => array(
                    'id' => '44',
                    'parent_id' => '36',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '87',
                    'rght' => '88'
                )
            ),
            (int) 41 => array(
                'Aco' => array(
                    'id' => '45',
                    'parent_id' => '36',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '89',
                    'rght' => '90'
                )
            ),
            (int) 42 => array(
                'Aco' => array(
                    'id' => '46',
                    'parent_id' => '36',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '91',
                    'rght' => '92'
                )
            ),
            (int) 43 => array(
                'Aco' => array(
                    'id' => '47',
                    'parent_id' => '36',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '93',
                    'rght' => '94'
                )
            ),
            (int) 44 => array(
                'Aco' => array(
                    'id' => '48',
                    'parent_id' => '36',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '95',
                    'rght' => '96'
                )
            ),
            (int) 45 => array(
                'Aco' => array(
                    'id' => '49',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Calendars',
                    'lft' => '100',
                    'rght' => '129'
                )
            ),
            (int) 46 => array(
                'Aco' => array(
                    'id' => '50',
                    'parent_id' => '49',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '101',
                    'rght' => '102'
                )
            ),
            (int) 47 => array(
                'Aco' => array(
                    'id' => '51',
                    'parent_id' => '49',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '103',
                    'rght' => '104'
                )
            ),
            (int) 48 => array(
                'Aco' => array(
                    'id' => '52',
                    'parent_id' => '49',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '105',
                    'rght' => '106'
                )
            ),
            (int) 49 => array(
                'Aco' => array(
                    'id' => '53',
                    'parent_id' => '49',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '107',
                    'rght' => '108'
                )
            ),
            (int) 50 => array(
                'Aco' => array(
                    'id' => '54',
                    'parent_id' => '49',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadHolidays',
                    'lft' => '109',
                    'rght' => '110'
                )
            ),
            (int) 51 => array(
                'Aco' => array(
                    'id' => '55',
                    'parent_id' => '49',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_delete',
                    'lft' => '111',
                    'rght' => '112'
                )
            ),
            (int) 52 => array(
                'Aco' => array(
                    'id' => '56',
                    'parent_id' => '49',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '113',
                    'rght' => '114'
                )
            ),
            (int) 53 => array(
                'Aco' => array(
                    'id' => '57',
                    'parent_id' => '49',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '115',
                    'rght' => '116'
                )
            ),
            (int) 54 => array(
                'Aco' => array(
                    'id' => '58',
                    'parent_id' => '49',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '117',
                    'rght' => '118'
                )
            ),
            (int) 55 => array(
                'Aco' => array(
                    'id' => '59',
                    'parent_id' => '49',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '119',
                    'rght' => '120'
                )
            ),
            (int) 56 => array(
                'Aco' => array(
                    'id' => '60',
                    'parent_id' => '49',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '121',
                    'rght' => '122'
                )
            ),
            (int) 57 => array(
                'Aco' => array(
                    'id' => '61',
                    'parent_id' => '49',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '123',
                    'rght' => '124'
                )
            ),
            (int) 58 => array(
                'Aco' => array(
                    'id' => '62',
                    'parent_id' => '49',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '125',
                    'rght' => '126'
                )
            ),
            (int) 59 => array(
                'Aco' => array(
                    'id' => '63',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Category',
                    'lft' => '130',
                    'rght' => '149'
                )
            ),
            (int) 60 => array(
                'Aco' => array(
                    'id' => '64',
                    'parent_id' => '63',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '131',
                    'rght' => '132'
                )
            ),
            (int) 61 => array(
                'Aco' => array(
                    'id' => '65',
                    'parent_id' => '63',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '133',
                    'rght' => '134'
                )
            ),
            (int) 62 => array(
                'Aco' => array(
                    'id' => '66',
                    'parent_id' => '63',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '135',
                    'rght' => '136'
                )
            ),
            (int) 63 => array(
                'Aco' => array(
                    'id' => '67',
                    'parent_id' => '63',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '137',
                    'rght' => '138'
                )
            ),
            (int) 64 => array(
                'Aco' => array(
                    'id' => '68',
                    'parent_id' => '63',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '139',
                    'rght' => '140'
                )
            ),
            (int) 65 => array(
                'Aco' => array(
                    'id' => '69',
                    'parent_id' => '63',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '141',
                    'rght' => '142'
                )
            ),
            (int) 66 => array(
                'Aco' => array(
                    'id' => '70',
                    'parent_id' => '63',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '143',
                    'rght' => '144'
                )
            ),
            (int) 67 => array(
                'Aco' => array(
                    'id' => '71',
                    'parent_id' => '63',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '145',
                    'rght' => '146'
                )
            ),
            (int) 68 => array(
                'Aco' => array(
                    'id' => '72',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Changelogs',
                    'lft' => '150',
                    'rght' => '169'
                )
            ),
            (int) 69 => array(
                'Aco' => array(
                    'id' => '73',
                    'parent_id' => '72',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '151',
                    'rght' => '152'
                )
            ),
            (int) 70 => array(
                'Aco' => array(
                    'id' => '74',
                    'parent_id' => '72',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '153',
                    'rght' => '154'
                )
            ),
            (int) 71 => array(
                'Aco' => array(
                    'id' => '75',
                    'parent_id' => '72',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '155',
                    'rght' => '156'
                )
            ),
            (int) 72 => array(
                'Aco' => array(
                    'id' => '76',
                    'parent_id' => '72',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '157',
                    'rght' => '158'
                )
            ),
            (int) 73 => array(
                'Aco' => array(
                    'id' => '77',
                    'parent_id' => '72',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '159',
                    'rght' => '160'
                )
            ),
            (int) 74 => array(
                'Aco' => array(
                    'id' => '78',
                    'parent_id' => '72',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '161',
                    'rght' => '162'
                )
            ),
            (int) 75 => array(
                'Aco' => array(
                    'id' => '79',
                    'parent_id' => '72',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '163',
                    'rght' => '164'
                )
            ),
            (int) 76 => array(
                'Aco' => array(
                    'id' => '80',
                    'parent_id' => '72',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '165',
                    'rght' => '166'
                )
            ),
            (int) 77 => array(
                'Aco' => array(
                    'id' => '81',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Commands',
                    'lft' => '170',
                    'rght' => '215'
                )
            ),
            (int) 78 => array(
                'Aco' => array(
                    'id' => '82',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '171',
                    'rght' => '172'
                )
            ),
            (int) 79 => array(
                'Aco' => array(
                    'id' => '83',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'hostchecks',
                    'lft' => '173',
                    'rght' => '174'
                )
            ),
            (int) 80 => array(
                'Aco' => array(
                    'id' => '84',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'notifications',
                    'lft' => '175',
                    'rght' => '176'
                )
            ),
            (int) 81 => array(
                'Aco' => array(
                    'id' => '85',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'handler',
                    'lft' => '177',
                    'rght' => '178'
                )
            ),
            (int) 82 => array(
                'Aco' => array(
                    'id' => '86',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '179',
                    'rght' => '180'
                )
            ),
            (int) 83 => array(
                'Aco' => array(
                    'id' => '87',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '181',
                    'rght' => '182'
                )
            ),
            (int) 84 => array(
                'Aco' => array(
                    'id' => '88',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '183',
                    'rght' => '184'
                )
            ),
            (int) 85 => array(
                'Aco' => array(
                    'id' => '89',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_delete',
                    'lft' => '185',
                    'rght' => '186'
                )
            ),
            (int) 86 => array(
                'Aco' => array(
                    'id' => '90',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'addCommandArg',
                    'lft' => '187',
                    'rght' => '188'
                )
            ),
            (int) 87 => array(
                'Aco' => array(
                    'id' => '91',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadMacros',
                    'lft' => '189',
                    'rght' => '190'
                )
            ),
            (int) 88 => array(
                'Aco' => array(
                    'id' => '92',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'terminal',
                    'lft' => '191',
                    'rght' => '192'
                )
            ),
            (int) 89 => array(
                'Aco' => array(
                    'id' => '93',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '193',
                    'rght' => '194'
                )
            ),
            (int) 90 => array(
                'Aco' => array(
                    'id' => '94',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '195',
                    'rght' => '196'
                )
            ),
            (int) 91 => array(
                'Aco' => array(
                    'id' => '95',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '197',
                    'rght' => '198'
                )
            ),
            (int) 92 => array(
                'Aco' => array(
                    'id' => '96',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '199',
                    'rght' => '200'
                )
            ),
            (int) 93 => array(
                'Aco' => array(
                    'id' => '97',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '201',
                    'rght' => '202'
                )
            ),
            (int) 94 => array(
                'Aco' => array(
                    'id' => '98',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '203',
                    'rght' => '204'
                )
            ),
            (int) 95 => array(
                'Aco' => array(
                    'id' => '99',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '205',
                    'rght' => '206'
                )
            ),
            (int) 96 => array(
                'Aco' => array(
                    'id' => '100',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Contactgroups',
                    'lft' => '216',
                    'rght' => '249'
                )
            ),
            (int) 97 => array(
                'Aco' => array(
                    'id' => '101',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '217',
                    'rght' => '218'
                )
            ),
            (int) 98 => array(
                'Aco' => array(
                    'id' => '102',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '219',
                    'rght' => '220'
                )
            ),
            (int) 99 => array(
                'Aco' => array(
                    'id' => '103',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '221',
                    'rght' => '222'
                )
            ),
            (int) 100 => array(
                'Aco' => array(
                    'id' => '104',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadContacts',
                    'lft' => '223',
                    'rght' => '224'
                )
            ),
            (int) 101 => array(
                'Aco' => array(
                    'id' => '105',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '225',
                    'rght' => '226'
                )
            ),
            (int) 102 => array(
                'Aco' => array(
                    'id' => '106',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_delete',
                    'lft' => '227',
                    'rght' => '228'
                )
            ),
            (int) 103 => array(
                'Aco' => array(
                    'id' => '107',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '229',
                    'rght' => '230'
                )
            ),
            (int) 104 => array(
                'Aco' => array(
                    'id' => '108',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '231',
                    'rght' => '232'
                )
            ),
            (int) 105 => array(
                'Aco' => array(
                    'id' => '109',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '233',
                    'rght' => '234'
                )
            ),
            (int) 106 => array(
                'Aco' => array(
                    'id' => '110',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '235',
                    'rght' => '236'
                )
            ),
            (int) 107 => array(
                'Aco' => array(
                    'id' => '111',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '237',
                    'rght' => '238'
                )
            ),
            (int) 108 => array(
                'Aco' => array(
                    'id' => '112',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '239',
                    'rght' => '240'
                )
            ),
            (int) 109 => array(
                'Aco' => array(
                    'id' => '113',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '241',
                    'rght' => '242'
                )
            ),
            (int) 110 => array(
                'Aco' => array(
                    'id' => '114',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Contacts',
                    'lft' => '250',
                    'rght' => '285'
                )
            ),
            (int) 111 => array(
                'Aco' => array(
                    'id' => '115',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '251',
                    'rght' => '252'
                )
            ),
            (int) 112 => array(
                'Aco' => array(
                    'id' => '116',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '253',
                    'rght' => '254'
                )
            ),
            (int) 113 => array(
                'Aco' => array(
                    'id' => '117',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '255',
                    'rght' => '256'
                )
            ),
            (int) 114 => array(
                'Aco' => array(
                    'id' => '118',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '257',
                    'rght' => '258'
                )
            ),
            (int) 115 => array(
                'Aco' => array(
                    'id' => '119',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_delete',
                    'lft' => '259',
                    'rght' => '260'
                )
            ),
            (int) 116 => array(
                'Aco' => array(
                    'id' => '120',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadTimeperiods',
                    'lft' => '261',
                    'rght' => '262'
                )
            ),
            (int) 117 => array(
                'Aco' => array(
                    'id' => '121',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '263',
                    'rght' => '264'
                )
            ),
            (int) 118 => array(
                'Aco' => array(
                    'id' => '122',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '265',
                    'rght' => '266'
                )
            ),
            (int) 119 => array(
                'Aco' => array(
                    'id' => '123',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '267',
                    'rght' => '268'
                )
            ),
            (int) 120 => array(
                'Aco' => array(
                    'id' => '124',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '269',
                    'rght' => '270'
                )
            ),
            (int) 121 => array(
                'Aco' => array(
                    'id' => '125',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '271',
                    'rght' => '272'
                )
            ),
            (int) 122 => array(
                'Aco' => array(
                    'id' => '126',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '273',
                    'rght' => '274'
                )
            ),
            (int) 123 => array(
                'Aco' => array(
                    'id' => '127',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '275',
                    'rght' => '276'
                )
            ),
            (int) 124 => array(
                'Aco' => array(
                    'id' => '128',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Containers',
                    'lft' => '286',
                    'rght' => '317'
                )
            ),
            (int) 125 => array(
                'Aco' => array(
                    'id' => '129',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '287',
                    'rght' => '288'
                )
            ),
            (int) 126 => array(
                'Aco' => array(
                    'id' => '130',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '289',
                    'rght' => '290'
                )
            ),
            (int) 127 => array(
                'Aco' => array(
                    'id' => '131',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'byTenant',
                    'lft' => '291',
                    'rght' => '292'
                )
            ),
            (int) 128 => array(
                'Aco' => array(
                    'id' => '132',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'byTenantForSelect',
                    'lft' => '293',
                    'rght' => '294'
                )
            ),
            (int) 129 => array(
                'Aco' => array(
                    'id' => '133',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '295',
                    'rght' => '296'
                )
            ),
            (int) 130 => array(
                'Aco' => array(
                    'id' => '134',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '297',
                    'rght' => '298'
                )
            ),
            (int) 131 => array(
                'Aco' => array(
                    'id' => '135',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '299',
                    'rght' => '300'
                )
            ),
            (int) 132 => array(
                'Aco' => array(
                    'id' => '136',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '301',
                    'rght' => '302'
                )
            ),
            (int) 133 => array(
                'Aco' => array(
                    'id' => '137',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '303',
                    'rght' => '304'
                )
            ),
            (int) 134 => array(
                'Aco' => array(
                    'id' => '138',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '305',
                    'rght' => '306'
                )
            ),
            (int) 135 => array(
                'Aco' => array(
                    'id' => '139',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '307',
                    'rght' => '308'
                )
            ),
            (int) 136 => array(
                'Aco' => array(
                    'id' => '140',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '309',
                    'rght' => '310'
                )
            ),
            (int) 137 => array(
                'Aco' => array(
                    'id' => '141',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Cronjobs',
                    'lft' => '318',
                    'rght' => '345'
                )
            ),
            (int) 138 => array(
                'Aco' => array(
                    'id' => '142',
                    'parent_id' => '141',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '319',
                    'rght' => '320'
                )
            ),
            (int) 139 => array(
                'Aco' => array(
                    'id' => '143',
                    'parent_id' => '141',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '321',
                    'rght' => '322'
                )
            ),
            (int) 140 => array(
                'Aco' => array(
                    'id' => '144',
                    'parent_id' => '141',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '323',
                    'rght' => '324'
                )
            ),
            (int) 141 => array(
                'Aco' => array(
                    'id' => '145',
                    'parent_id' => '141',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '325',
                    'rght' => '326'
                )
            ),
            (int) 142 => array(
                'Aco' => array(
                    'id' => '146',
                    'parent_id' => '141',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadTasksByPlugin',
                    'lft' => '327',
                    'rght' => '328'
                )
            ),
            (int) 143 => array(
                'Aco' => array(
                    'id' => '147',
                    'parent_id' => '141',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '329',
                    'rght' => '330'
                )
            ),
            (int) 144 => array(
                'Aco' => array(
                    'id' => '148',
                    'parent_id' => '141',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '331',
                    'rght' => '332'
                )
            ),
            (int) 145 => array(
                'Aco' => array(
                    'id' => '149',
                    'parent_id' => '141',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '333',
                    'rght' => '334'
                )
            ),
            (int) 146 => array(
                'Aco' => array(
                    'id' => '150',
                    'parent_id' => '141',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '335',
                    'rght' => '336'
                )
            ),
            (int) 147 => array(
                'Aco' => array(
                    'id' => '151',
                    'parent_id' => '141',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '337',
                    'rght' => '338'
                )
            ),
            (int) 148 => array(
                'Aco' => array(
                    'id' => '152',
                    'parent_id' => '141',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '339',
                    'rght' => '340'
                )
            ),
            (int) 149 => array(
                'Aco' => array(
                    'id' => '153',
                    'parent_id' => '141',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '341',
                    'rght' => '342'
                )
            ),
            (int) 150 => array(
                'Aco' => array(
                    'id' => '154',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Currentstatereports',
                    'lft' => '346',
                    'rght' => '367'
                )
            ),
            (int) 151 => array(
                'Aco' => array(
                    'id' => '155',
                    'parent_id' => '154',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '347',
                    'rght' => '348'
                )
            ),
            (int) 152 => array(
                'Aco' => array(
                    'id' => '156',
                    'parent_id' => '154',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'createPdfReport',
                    'lft' => '349',
                    'rght' => '350'
                )
            ),
            (int) 153 => array(
                'Aco' => array(
                    'id' => '157',
                    'parent_id' => '154',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '351',
                    'rght' => '352'
                )
            ),
            (int) 154 => array(
                'Aco' => array(
                    'id' => '158',
                    'parent_id' => '154',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '353',
                    'rght' => '354'
                )
            ),
            (int) 155 => array(
                'Aco' => array(
                    'id' => '159',
                    'parent_id' => '154',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '355',
                    'rght' => '356'
                )
            ),
            (int) 156 => array(
                'Aco' => array(
                    'id' => '160',
                    'parent_id' => '154',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '357',
                    'rght' => '358'
                )
            ),
            (int) 157 => array(
                'Aco' => array(
                    'id' => '161',
                    'parent_id' => '154',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '359',
                    'rght' => '360'
                )
            ),
            (int) 158 => array(
                'Aco' => array(
                    'id' => '162',
                    'parent_id' => '154',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '361',
                    'rght' => '362'
                )
            ),
            (int) 159 => array(
                'Aco' => array(
                    'id' => '163',
                    'parent_id' => '154',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '363',
                    'rght' => '364'
                )
            ),
            (int) 160 => array(
                'Aco' => array(
                    'id' => '164',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'DeletedHosts',
                    'lft' => '368',
                    'rght' => '387'
                )
            ),
            (int) 161 => array(
                'Aco' => array(
                    'id' => '165',
                    'parent_id' => '164',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '369',
                    'rght' => '370'
                )
            ),
            (int) 162 => array(
                'Aco' => array(
                    'id' => '166',
                    'parent_id' => '164',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '371',
                    'rght' => '372'
                )
            ),
            (int) 163 => array(
                'Aco' => array(
                    'id' => '167',
                    'parent_id' => '164',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '373',
                    'rght' => '374'
                )
            ),
            (int) 164 => array(
                'Aco' => array(
                    'id' => '168',
                    'parent_id' => '164',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '375',
                    'rght' => '376'
                )
            ),
            (int) 165 => array(
                'Aco' => array(
                    'id' => '169',
                    'parent_id' => '164',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '377',
                    'rght' => '378'
                )
            ),
            (int) 166 => array(
                'Aco' => array(
                    'id' => '170',
                    'parent_id' => '164',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '379',
                    'rght' => '380'
                )
            ),
            (int) 167 => array(
                'Aco' => array(
                    'id' => '171',
                    'parent_id' => '164',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '381',
                    'rght' => '382'
                )
            ),
            (int) 168 => array(
                'Aco' => array(
                    'id' => '172',
                    'parent_id' => '164',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '383',
                    'rght' => '384'
                )
            ),
            (int) 169 => array(
                'Aco' => array(
                    'id' => '185',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Documentations',
                    'lft' => '388',
                    'rght' => '411'
                )
            ),
            (int) 170 => array(
                'Aco' => array(
                    'id' => '186',
                    'parent_id' => '185',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '389',
                    'rght' => '390'
                )
            ),
            (int) 171 => array(
                'Aco' => array(
                    'id' => '187',
                    'parent_id' => '185',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '391',
                    'rght' => '392'
                )
            ),
            (int) 172 => array(
                'Aco' => array(
                    'id' => '188',
                    'parent_id' => '185',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'wiki',
                    'lft' => '393',
                    'rght' => '394'
                )
            ),
            (int) 173 => array(
                'Aco' => array(
                    'id' => '189',
                    'parent_id' => '185',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '395',
                    'rght' => '396'
                )
            ),
            (int) 174 => array(
                'Aco' => array(
                    'id' => '190',
                    'parent_id' => '185',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '397',
                    'rght' => '398'
                )
            ),
            (int) 175 => array(
                'Aco' => array(
                    'id' => '191',
                    'parent_id' => '185',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '399',
                    'rght' => '400'
                )
            ),
            (int) 176 => array(
                'Aco' => array(
                    'id' => '192',
                    'parent_id' => '185',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '401',
                    'rght' => '402'
                )
            ),
            (int) 177 => array(
                'Aco' => array(
                    'id' => '193',
                    'parent_id' => '185',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '403',
                    'rght' => '404'
                )
            ),
            (int) 178 => array(
                'Aco' => array(
                    'id' => '194',
                    'parent_id' => '185',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '405',
                    'rght' => '406'
                )
            ),
            (int) 179 => array(
                'Aco' => array(
                    'id' => '195',
                    'parent_id' => '185',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '407',
                    'rght' => '408'
                )
            ),
            (int) 180 => array(
                'Aco' => array(
                    'id' => '196',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Downtimereports',
                    'lft' => '412',
                    'rght' => '433'
                )
            ),
            (int) 181 => array(
                'Aco' => array(
                    'id' => '197',
                    'parent_id' => '196',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '413',
                    'rght' => '414'
                )
            ),
            (int) 182 => array(
                'Aco' => array(
                    'id' => '198',
                    'parent_id' => '196',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'createPdfReport',
                    'lft' => '415',
                    'rght' => '416'
                )
            ),
            (int) 183 => array(
                'Aco' => array(
                    'id' => '199',
                    'parent_id' => '196',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '417',
                    'rght' => '418'
                )
            ),
            (int) 184 => array(
                'Aco' => array(
                    'id' => '200',
                    'parent_id' => '196',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '419',
                    'rght' => '420'
                )
            ),
            (int) 185 => array(
                'Aco' => array(
                    'id' => '201',
                    'parent_id' => '196',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '421',
                    'rght' => '422'
                )
            ),
            (int) 186 => array(
                'Aco' => array(
                    'id' => '202',
                    'parent_id' => '196',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '423',
                    'rght' => '424'
                )
            ),
            (int) 187 => array(
                'Aco' => array(
                    'id' => '203',
                    'parent_id' => '196',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '425',
                    'rght' => '426'
                )
            ),
            (int) 188 => array(
                'Aco' => array(
                    'id' => '204',
                    'parent_id' => '196',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '427',
                    'rght' => '428'
                )
            ),
            (int) 189 => array(
                'Aco' => array(
                    'id' => '205',
                    'parent_id' => '196',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '429',
                    'rght' => '430'
                )
            ),
            (int) 190 => array(
                'Aco' => array(
                    'id' => '206',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Downtimes',
                    'lft' => '434',
                    'rght' => '459'
                )
            ),
            (int) 191 => array(
                'Aco' => array(
                    'id' => '207',
                    'parent_id' => '206',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'host',
                    'lft' => '435',
                    'rght' => '436'
                )
            ),
            (int) 192 => array(
                'Aco' => array(
                    'id' => '208',
                    'parent_id' => '206',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'service',
                    'lft' => '437',
                    'rght' => '438'
                )
            ),
            (int) 193 => array(
                'Aco' => array(
                    'id' => '209',
                    'parent_id' => '206',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '439',
                    'rght' => '440'
                )
            ),
            (int) 194 => array(
                'Aco' => array(
                    'id' => '210',
                    'parent_id' => '206',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'validateDowntimeInputFromBrowser',
                    'lft' => '441',
                    'rght' => '442'
                )
            ),
            (int) 195 => array(
                'Aco' => array(
                    'id' => '211',
                    'parent_id' => '206',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '443',
                    'rght' => '444'
                )
            ),
            (int) 196 => array(
                'Aco' => array(
                    'id' => '212',
                    'parent_id' => '206',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '445',
                    'rght' => '446'
                )
            ),
            (int) 197 => array(
                'Aco' => array(
                    'id' => '213',
                    'parent_id' => '206',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '447',
                    'rght' => '448'
                )
            ),
            (int) 198 => array(
                'Aco' => array(
                    'id' => '214',
                    'parent_id' => '206',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '449',
                    'rght' => '450'
                )
            ),
            (int) 199 => array(
                'Aco' => array(
                    'id' => '215',
                    'parent_id' => '206',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '451',
                    'rght' => '452'
                )
            ),
            (int) 200 => array(
                'Aco' => array(
                    'id' => '216',
                    'parent_id' => '206',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '453',
                    'rght' => '454'
                )
            ),
            (int) 201 => array(
                'Aco' => array(
                    'id' => '217',
                    'parent_id' => '206',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '455',
                    'rght' => '456'
                )
            ),
            (int) 202 => array(
                'Aco' => array(
                    'id' => '218',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Exports',
                    'lft' => '460',
                    'rght' => '487'
                )
            ),
            (int) 203 => array(
                'Aco' => array(
                    'id' => '219',
                    'parent_id' => '218',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '461',
                    'rght' => '462'
                )
            ),
            (int) 204 => array(
                'Aco' => array(
                    'id' => '220',
                    'parent_id' => '218',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '463',
                    'rght' => '464'
                )
            ),
            (int) 205 => array(
                'Aco' => array(
                    'id' => '221',
                    'parent_id' => '218',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '465',
                    'rght' => '466'
                )
            ),
            (int) 206 => array(
                'Aco' => array(
                    'id' => '222',
                    'parent_id' => '218',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '467',
                    'rght' => '468'
                )
            ),
            (int) 207 => array(
                'Aco' => array(
                    'id' => '223',
                    'parent_id' => '218',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '469',
                    'rght' => '470'
                )
            ),
            (int) 208 => array(
                'Aco' => array(
                    'id' => '224',
                    'parent_id' => '218',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '471',
                    'rght' => '472'
                )
            ),
            (int) 209 => array(
                'Aco' => array(
                    'id' => '225',
                    'parent_id' => '218',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '473',
                    'rght' => '474'
                )
            ),
            (int) 210 => array(
                'Aco' => array(
                    'id' => '226',
                    'parent_id' => '218',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '475',
                    'rght' => '476'
                )
            ),
            (int) 211 => array(
                'Aco' => array(
                    'id' => '227',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Forward',
                    'lft' => '488',
                    'rght' => '507'
                )
            ),
            (int) 212 => array(
                'Aco' => array(
                    'id' => '228',
                    'parent_id' => '227',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '489',
                    'rght' => '490'
                )
            ),
            (int) 213 => array(
                'Aco' => array(
                    'id' => '229',
                    'parent_id' => '227',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '491',
                    'rght' => '492'
                )
            ),
            (int) 214 => array(
                'Aco' => array(
                    'id' => '230',
                    'parent_id' => '227',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '493',
                    'rght' => '494'
                )
            ),
            (int) 215 => array(
                'Aco' => array(
                    'id' => '231',
                    'parent_id' => '227',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '495',
                    'rght' => '496'
                )
            ),
            (int) 216 => array(
                'Aco' => array(
                    'id' => '232',
                    'parent_id' => '227',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '497',
                    'rght' => '498'
                )
            ),
            (int) 217 => array(
                'Aco' => array(
                    'id' => '233',
                    'parent_id' => '227',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '499',
                    'rght' => '500'
                )
            ),
            (int) 218 => array(
                'Aco' => array(
                    'id' => '234',
                    'parent_id' => '227',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '501',
                    'rght' => '502'
                )
            ),
            (int) 219 => array(
                'Aco' => array(
                    'id' => '235',
                    'parent_id' => '227',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '503',
                    'rght' => '504'
                )
            ),
            (int) 220 => array(
                'Aco' => array(
                    'id' => '236',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'GraphCollections',
                    'lft' => '508',
                    'rght' => '537'
                )
            ),
            (int) 221 => array(
                'Aco' => array(
                    'id' => '237',
                    'parent_id' => '236',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '509',
                    'rght' => '510'
                )
            ),
            (int) 222 => array(
                'Aco' => array(
                    'id' => '238',
                    'parent_id' => '236',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '511',
                    'rght' => '512'
                )
            ),
            (int) 223 => array(
                'Aco' => array(
                    'id' => '239',
                    'parent_id' => '236',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'display',
                    'lft' => '513',
                    'rght' => '514'
                )
            ),
            (int) 224 => array(
                'Aco' => array(
                    'id' => '240',
                    'parent_id' => '236',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_delete',
                    'lft' => '515',
                    'rght' => '516'
                )
            ),
            (int) 225 => array(
                'Aco' => array(
                    'id' => '241',
                    'parent_id' => '236',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadCollectionGraphData',
                    'lft' => '517',
                    'rght' => '518'
                )
            ),
            (int) 226 => array(
                'Aco' => array(
                    'id' => '242',
                    'parent_id' => '236',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '519',
                    'rght' => '520'
                )
            ),
            (int) 227 => array(
                'Aco' => array(
                    'id' => '243',
                    'parent_id' => '236',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '521',
                    'rght' => '522'
                )
            ),
            (int) 228 => array(
                'Aco' => array(
                    'id' => '244',
                    'parent_id' => '236',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '523',
                    'rght' => '524'
                )
            ),
            (int) 229 => array(
                'Aco' => array(
                    'id' => '245',
                    'parent_id' => '236',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '525',
                    'rght' => '526'
                )
            ),
            (int) 230 => array(
                'Aco' => array(
                    'id' => '246',
                    'parent_id' => '236',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '527',
                    'rght' => '528'
                )
            ),
            (int) 231 => array(
                'Aco' => array(
                    'id' => '247',
                    'parent_id' => '236',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '529',
                    'rght' => '530'
                )
            ),
            (int) 232 => array(
                'Aco' => array(
                    'id' => '248',
                    'parent_id' => '236',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '531',
                    'rght' => '532'
                )
            ),
            (int) 233 => array(
                'Aco' => array(
                    'id' => '249',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Graphgenerators',
                    'lft' => '538',
                    'rght' => '575'
                )
            ),
            (int) 234 => array(
                'Aco' => array(
                    'id' => '250',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '539',
                    'rght' => '540'
                )
            ),
            (int) 235 => array(
                'Aco' => array(
                    'id' => '251',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'listing',
                    'lft' => '541',
                    'rght' => '542'
                )
            ),
            (int) 236 => array(
                'Aco' => array(
                    'id' => '252',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_delete',
                    'lft' => '543',
                    'rght' => '544'
                )
            ),
            (int) 237 => array(
                'Aco' => array(
                    'id' => '253',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'saveGraphTemplate',
                    'lft' => '545',
                    'rght' => '546'
                )
            ),
            (int) 238 => array(
                'Aco' => array(
                    'id' => '254',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadGraphTemplate',
                    'lft' => '547',
                    'rght' => '548'
                )
            ),
            (int) 239 => array(
                'Aco' => array(
                    'id' => '255',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadServicesByHostId',
                    'lft' => '549',
                    'rght' => '550'
                )
            ),
            (int) 240 => array(
                'Aco' => array(
                    'id' => '256',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadPerfDataStructures',
                    'lft' => '551',
                    'rght' => '552'
                )
            ),
            (int) 241 => array(
                'Aco' => array(
                    'id' => '257',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadServiceruleFromService',
                    'lft' => '553',
                    'rght' => '554'
                )
            ),
            (int) 242 => array(
                'Aco' => array(
                    'id' => '258',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'fetchGraphData',
                    'lft' => '555',
                    'rght' => '556'
                )
            ),
            (int) 243 => array(
                'Aco' => array(
                    'id' => '259',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '557',
                    'rght' => '558'
                )
            ),
            (int) 244 => array(
                'Aco' => array(
                    'id' => '260',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '559',
                    'rght' => '560'
                )
            ),
            (int) 245 => array(
                'Aco' => array(
                    'id' => '261',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '561',
                    'rght' => '562'
                )
            ),
            (int) 246 => array(
                'Aco' => array(
                    'id' => '262',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '563',
                    'rght' => '564'
                )
            ),
            (int) 247 => array(
                'Aco' => array(
                    'id' => '263',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '565',
                    'rght' => '566'
                )
            ),
            (int) 248 => array(
                'Aco' => array(
                    'id' => '264',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '567',
                    'rght' => '568'
                )
            ),
            (int) 249 => array(
                'Aco' => array(
                    'id' => '265',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '569',
                    'rght' => '570'
                )
            ),
            (int) 250 => array(
                'Aco' => array(
                    'id' => '266',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Hostchecks',
                    'lft' => '576',
                    'rght' => '595'
                )
            ),
            (int) 251 => array(
                'Aco' => array(
                    'id' => '267',
                    'parent_id' => '266',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '577',
                    'rght' => '578'
                )
            ),
            (int) 252 => array(
                'Aco' => array(
                    'id' => '268',
                    'parent_id' => '266',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '579',
                    'rght' => '580'
                )
            ),
            (int) 253 => array(
                'Aco' => array(
                    'id' => '269',
                    'parent_id' => '266',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '581',
                    'rght' => '582'
                )
            ),
            (int) 254 => array(
                'Aco' => array(
                    'id' => '270',
                    'parent_id' => '266',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '583',
                    'rght' => '584'
                )
            ),
            (int) 255 => array(
                'Aco' => array(
                    'id' => '271',
                    'parent_id' => '266',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '585',
                    'rght' => '586'
                )
            ),
            (int) 256 => array(
                'Aco' => array(
                    'id' => '272',
                    'parent_id' => '266',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '587',
                    'rght' => '588'
                )
            ),
            (int) 257 => array(
                'Aco' => array(
                    'id' => '273',
                    'parent_id' => '266',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '589',
                    'rght' => '590'
                )
            ),
            (int) 258 => array(
                'Aco' => array(
                    'id' => '274',
                    'parent_id' => '266',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '591',
                    'rght' => '592'
                )
            ),
            (int) 259 => array(
                'Aco' => array(
                    'id' => '275',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Hostdependencies',
                    'lft' => '596',
                    'rght' => '625'
                )
            ),
            (int) 260 => array(
                'Aco' => array(
                    'id' => '276',
                    'parent_id' => '275',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '597',
                    'rght' => '598'
                )
            ),
            (int) 261 => array(
                'Aco' => array(
                    'id' => '277',
                    'parent_id' => '275',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '599',
                    'rght' => '600'
                )
            ),
            (int) 262 => array(
                'Aco' => array(
                    'id' => '278',
                    'parent_id' => '275',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '601',
                    'rght' => '602'
                )
            ),
            (int) 263 => array(
                'Aco' => array(
                    'id' => '279',
                    'parent_id' => '275',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '603',
                    'rght' => '604'
                )
            ),
            (int) 264 => array(
                'Aco' => array(
                    'id' => '280',
                    'parent_id' => '275',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadElementsByContainerId',
                    'lft' => '605',
                    'rght' => '606'
                )
            ),
            (int) 265 => array(
                'Aco' => array(
                    'id' => '281',
                    'parent_id' => '275',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '607',
                    'rght' => '608'
                )
            ),
            (int) 266 => array(
                'Aco' => array(
                    'id' => '282',
                    'parent_id' => '275',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '609',
                    'rght' => '610'
                )
            ),
            (int) 267 => array(
                'Aco' => array(
                    'id' => '283',
                    'parent_id' => '275',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '611',
                    'rght' => '612'
                )
            ),
            (int) 268 => array(
                'Aco' => array(
                    'id' => '284',
                    'parent_id' => '275',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '613',
                    'rght' => '614'
                )
            ),
            (int) 269 => array(
                'Aco' => array(
                    'id' => '285',
                    'parent_id' => '275',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '615',
                    'rght' => '616'
                )
            ),
            (int) 270 => array(
                'Aco' => array(
                    'id' => '286',
                    'parent_id' => '275',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '617',
                    'rght' => '618'
                )
            ),
            (int) 271 => array(
                'Aco' => array(
                    'id' => '287',
                    'parent_id' => '275',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '619',
                    'rght' => '620'
                )
            ),
            (int) 272 => array(
                'Aco' => array(
                    'id' => '288',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Hostescalations',
                    'lft' => '626',
                    'rght' => '655'
                )
            ),
            (int) 273 => array(
                'Aco' => array(
                    'id' => '289',
                    'parent_id' => '288',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '627',
                    'rght' => '628'
                )
            ),
            (int) 274 => array(
                'Aco' => array(
                    'id' => '290',
                    'parent_id' => '288',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '629',
                    'rght' => '630'
                )
            ),
            (int) 275 => array(
                'Aco' => array(
                    'id' => '291',
                    'parent_id' => '288',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '631',
                    'rght' => '632'
                )
            ),
            (int) 276 => array(
                'Aco' => array(
                    'id' => '292',
                    'parent_id' => '288',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '633',
                    'rght' => '634'
                )
            ),
            (int) 277 => array(
                'Aco' => array(
                    'id' => '293',
                    'parent_id' => '288',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadElementsByContainerId',
                    'lft' => '635',
                    'rght' => '636'
                )
            ),
            (int) 278 => array(
                'Aco' => array(
                    'id' => '294',
                    'parent_id' => '288',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '637',
                    'rght' => '638'
                )
            ),
            (int) 279 => array(
                'Aco' => array(
                    'id' => '295',
                    'parent_id' => '288',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '639',
                    'rght' => '640'
                )
            ),
            (int) 280 => array(
                'Aco' => array(
                    'id' => '296',
                    'parent_id' => '288',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '641',
                    'rght' => '642'
                )
            ),
            (int) 281 => array(
                'Aco' => array(
                    'id' => '297',
                    'parent_id' => '288',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '643',
                    'rght' => '644'
                )
            ),
            (int) 282 => array(
                'Aco' => array(
                    'id' => '298',
                    'parent_id' => '288',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '645',
                    'rght' => '646'
                )
            ),
            (int) 283 => array(
                'Aco' => array(
                    'id' => '299',
                    'parent_id' => '288',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '647',
                    'rght' => '648'
                )
            ),
            (int) 284 => array(
                'Aco' => array(
                    'id' => '300',
                    'parent_id' => '288',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '649',
                    'rght' => '650'
                )
            ),
            (int) 285 => array(
                'Aco' => array(
                    'id' => '301',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Hostgroups',
                    'lft' => '656',
                    'rght' => '697'
                )
            ),
            (int) 286 => array(
                'Aco' => array(
                    'id' => '302',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '657',
                    'rght' => '658'
                )
            ),
            (int) 287 => array(
                'Aco' => array(
                    'id' => '303',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'extended',
                    'lft' => '659',
                    'rght' => '660'
                )
            ),
            (int) 288 => array(
                'Aco' => array(
                    'id' => '304',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '661',
                    'rght' => '662'
                )
            ),
            (int) 289 => array(
                'Aco' => array(
                    'id' => '305',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '663',
                    'rght' => '664'
                )
            ),
            (int) 290 => array(
                'Aco' => array(
                    'id' => '306',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadHosts',
                    'lft' => '665',
                    'rght' => '666'
                )
            ),
            (int) 291 => array(
                'Aco' => array(
                    'id' => '307',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '667',
                    'rght' => '668'
                )
            ),
            (int) 292 => array(
                'Aco' => array(
                    'id' => '308',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_add',
                    'lft' => '669',
                    'rght' => '670'
                )
            ),
            (int) 293 => array(
                'Aco' => array(
                    'id' => '309',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_delete',
                    'lft' => '671',
                    'rght' => '672'
                )
            ),
            (int) 294 => array(
                'Aco' => array(
                    'id' => '310',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'listToPdf',
                    'lft' => '673',
                    'rght' => '674'
                )
            ),
            (int) 295 => array(
                'Aco' => array(
                    'id' => '311',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '675',
                    'rght' => '676'
                )
            ),
            (int) 296 => array(
                'Aco' => array(
                    'id' => '312',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '677',
                    'rght' => '678'
                )
            ),
            (int) 297 => array(
                'Aco' => array(
                    'id' => '313',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '679',
                    'rght' => '680'
                )
            ),
            (int) 298 => array(
                'Aco' => array(
                    'id' => '314',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '681',
                    'rght' => '682'
                )
            ),
            (int) 299 => array(
                'Aco' => array(
                    'id' => '315',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '683',
                    'rght' => '684'
                )
            ),
            (int) 300 => array(
                'Aco' => array(
                    'id' => '316',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '685',
                    'rght' => '686'
                )
            ),
            (int) 301 => array(
                'Aco' => array(
                    'id' => '317',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '687',
                    'rght' => '688'
                )
            ),
            (int) 302 => array(
                'Aco' => array(
                    'id' => '318',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Hosts',
                    'lft' => '698',
                    'rght' => '783'
                )
            ),
            (int) 303 => array(
                'Aco' => array(
                    'id' => '319',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '699',
                    'rght' => '700'
                )
            ),
            (int) 304 => array(
                'Aco' => array(
                    'id' => '320',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'notMonitored',
                    'lft' => '701',
                    'rght' => '702'
                )
            ),
            (int) 305 => array(
                'Aco' => array(
                    'id' => '321',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '703',
                    'rght' => '704'
                )
            ),
            (int) 306 => array(
                'Aco' => array(
                    'id' => '322',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'sharing',
                    'lft' => '705',
                    'rght' => '706'
                )
            ),
            (int) 307 => array(
                'Aco' => array(
                    'id' => '323',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit_details',
                    'lft' => '707',
                    'rght' => '708'
                )
            ),
            (int) 308 => array(
                'Aco' => array(
                    'id' => '324',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '709',
                    'rght' => '710'
                )
            ),
            (int) 309 => array(
                'Aco' => array(
                    'id' => '325',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'disabled',
                    'lft' => '711',
                    'rght' => '712'
                )
            ),
            (int) 310 => array(
                'Aco' => array(
                    'id' => '326',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'deactivate',
                    'lft' => '713',
                    'rght' => '714'
                )
            ),
            (int) 311 => array(
                'Aco' => array(
                    'id' => '327',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_deactivate',
                    'lft' => '715',
                    'rght' => '716'
                )
            ),
            (int) 312 => array(
                'Aco' => array(
                    'id' => '328',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'enable',
                    'lft' => '717',
                    'rght' => '718'
                )
            ),
            (int) 313 => array(
                'Aco' => array(
                    'id' => '329',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '719',
                    'rght' => '720'
                )
            ),
            (int) 314 => array(
                'Aco' => array(
                    'id' => '330',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_delete',
                    'lft' => '721',
                    'rght' => '722'
                )
            ),
            (int) 315 => array(
                'Aco' => array(
                    'id' => '331',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'copy',
                    'lft' => '723',
                    'rght' => '724'
                )
            ),
            (int) 316 => array(
                'Aco' => array(
                    'id' => '332',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'browser',
                    'lft' => '725',
                    'rght' => '726'
                )
            ),
            (int) 317 => array(
                'Aco' => array(
                    'id' => '333',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'longOutputByUuid',
                    'lft' => '727',
                    'rght' => '728'
                )
            ),
            (int) 318 => array(
                'Aco' => array(
                    'id' => '334',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'gethostipbyname',
                    'lft' => '729',
                    'rght' => '730'
                )
            ),
            (int) 319 => array(
                'Aco' => array(
                    'id' => '335',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'gethostnamebyaddr',
                    'lft' => '731',
                    'rght' => '732'
                )
            ),
            (int) 320 => array(
                'Aco' => array(
                    'id' => '336',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadHosttemplate',
                    'lft' => '733',
                    'rght' => '734'
                )
            ),
            (int) 321 => array(
                'Aco' => array(
                    'id' => '337',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'addCustomMacro',
                    'lft' => '735',
                    'rght' => '736'
                )
            ),
            (int) 322 => array(
                'Aco' => array(
                    'id' => '338',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadTemplateMacros',
                    'lft' => '737',
                    'rght' => '738'
                )
            ),
            (int) 323 => array(
                'Aco' => array(
                    'id' => '339',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadParametersByCommandId',
                    'lft' => '739',
                    'rght' => '740'
                )
            ),
            (int) 324 => array(
                'Aco' => array(
                    'id' => '340',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadArguments',
                    'lft' => '741',
                    'rght' => '742'
                )
            ),
            (int) 325 => array(
                'Aco' => array(
                    'id' => '341',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadArgumentsAdd',
                    'lft' => '743',
                    'rght' => '744'
                )
            ),
            (int) 326 => array(
                'Aco' => array(
                    'id' => '342',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadHosttemplatesArguments',
                    'lft' => '745',
                    'rght' => '746'
                )
            ),
            (int) 327 => array(
                'Aco' => array(
                    'id' => '343',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getHostByAjax',
                    'lft' => '747',
                    'rght' => '748'
                )
            ),
            (int) 328 => array(
                'Aco' => array(
                    'id' => '344',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'listToPdf',
                    'lft' => '749',
                    'rght' => '750'
                )
            ),
            (int) 329 => array(
                'Aco' => array(
                    'id' => '345',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'ping',
                    'lft' => '751',
                    'rght' => '752'
                )
            ),
            (int) 330 => array(
                'Aco' => array(
                    'id' => '346',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'addParentHosts',
                    'lft' => '753',
                    'rght' => '754'
                )
            ),
            (int) 331 => array(
                'Aco' => array(
                    'id' => '347',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadElementsByContainerId',
                    'lft' => '755',
                    'rght' => '756'
                )
            ),
            (int) 332 => array(
                'Aco' => array(
                    'id' => '348',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkcommand',
                    'lft' => '757',
                    'rght' => '758'
                )
            ),
            (int) 333 => array(
                'Aco' => array(
                    'id' => '349',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '759',
                    'rght' => '760'
                )
            ),
            (int) 334 => array(
                'Aco' => array(
                    'id' => '350',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '761',
                    'rght' => '762'
                )
            ),
            (int) 335 => array(
                'Aco' => array(
                    'id' => '351',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '763',
                    'rght' => '764'
                )
            ),
            (int) 336 => array(
                'Aco' => array(
                    'id' => '352',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '765',
                    'rght' => '766'
                )
            ),
            (int) 337 => array(
                'Aco' => array(
                    'id' => '353',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '767',
                    'rght' => '768'
                )
            ),
            (int) 338 => array(
                'Aco' => array(
                    'id' => '354',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '769',
                    'rght' => '770'
                )
            ),
            (int) 339 => array(
                'Aco' => array(
                    'id' => '355',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '771',
                    'rght' => '772'
                )
            ),
            (int) 340 => array(
                'Aco' => array(
                    'id' => '356',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Hosttemplates',
                    'lft' => '784',
                    'rght' => '825'
                )
            ),
            (int) 341 => array(
                'Aco' => array(
                    'id' => '357',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '785',
                    'rght' => '786'
                )
            ),
            (int) 342 => array(
                'Aco' => array(
                    'id' => '358',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '787',
                    'rght' => '788'
                )
            ),
            (int) 343 => array(
                'Aco' => array(
                    'id' => '359',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '789',
                    'rght' => '790'
                )
            ),
            (int) 344 => array(
                'Aco' => array(
                    'id' => '360',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '791',
                    'rght' => '792'
                )
            ),
            (int) 345 => array(
                'Aco' => array(
                    'id' => '361',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'addCustomMacro',
                    'lft' => '793',
                    'rght' => '794'
                )
            ),
            (int) 346 => array(
                'Aco' => array(
                    'id' => '362',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadArguments',
                    'lft' => '795',
                    'rght' => '796'
                )
            ),
            (int) 347 => array(
                'Aco' => array(
                    'id' => '363',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadArgumentsAdd',
                    'lft' => '797',
                    'rght' => '798'
                )
            ),
            (int) 348 => array(
                'Aco' => array(
                    'id' => '364',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'usedBy',
                    'lft' => '799',
                    'rght' => '800'
                )
            ),
            (int) 349 => array(
                'Aco' => array(
                    'id' => '365',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadElementsByContainerId',
                    'lft' => '801',
                    'rght' => '802'
                )
            ),
            (int) 350 => array(
                'Aco' => array(
                    'id' => '366',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '803',
                    'rght' => '804'
                )
            ),
            (int) 351 => array(
                'Aco' => array(
                    'id' => '367',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '805',
                    'rght' => '806'
                )
            ),
            (int) 352 => array(
                'Aco' => array(
                    'id' => '368',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '807',
                    'rght' => '808'
                )
            ),
            (int) 353 => array(
                'Aco' => array(
                    'id' => '369',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '809',
                    'rght' => '810'
                )
            ),
            (int) 354 => array(
                'Aco' => array(
                    'id' => '370',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '811',
                    'rght' => '812'
                )
            ),
            (int) 355 => array(
                'Aco' => array(
                    'id' => '371',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '813',
                    'rght' => '814'
                )
            ),
            (int) 356 => array(
                'Aco' => array(
                    'id' => '372',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '815',
                    'rght' => '816'
                )
            ),
            (int) 357 => array(
                'Aco' => array(
                    'id' => '373',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Instantreports',
                    'lft' => '826',
                    'rght' => '849'
                )
            ),
            (int) 358 => array(
                'Aco' => array(
                    'id' => '374',
                    'parent_id' => '373',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '827',
                    'rght' => '828'
                )
            ),
            (int) 359 => array(
                'Aco' => array(
                    'id' => '375',
                    'parent_id' => '373',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'createPdfReport',
                    'lft' => '829',
                    'rght' => '830'
                )
            ),
            (int) 360 => array(
                'Aco' => array(
                    'id' => '376',
                    'parent_id' => '373',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'expandServices',
                    'lft' => '831',
                    'rght' => '832'
                )
            ),
            (int) 361 => array(
                'Aco' => array(
                    'id' => '377',
                    'parent_id' => '373',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '833',
                    'rght' => '834'
                )
            ),
            (int) 362 => array(
                'Aco' => array(
                    'id' => '378',
                    'parent_id' => '373',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '835',
                    'rght' => '836'
                )
            ),
            (int) 363 => array(
                'Aco' => array(
                    'id' => '379',
                    'parent_id' => '373',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '837',
                    'rght' => '838'
                )
            ),
            (int) 364 => array(
                'Aco' => array(
                    'id' => '380',
                    'parent_id' => '373',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '839',
                    'rght' => '840'
                )
            ),
            (int) 365 => array(
                'Aco' => array(
                    'id' => '381',
                    'parent_id' => '373',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '841',
                    'rght' => '842'
                )
            ),
            (int) 366 => array(
                'Aco' => array(
                    'id' => '382',
                    'parent_id' => '373',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '843',
                    'rght' => '844'
                )
            ),
            (int) 367 => array(
                'Aco' => array(
                    'id' => '383',
                    'parent_id' => '373',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '845',
                    'rght' => '846'
                )
            ),
            (int) 368 => array(
                'Aco' => array(
                    'id' => '384',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Locations',
                    'lft' => '850',
                    'rght' => '877'
                )
            ),
            (int) 369 => array(
                'Aco' => array(
                    'id' => '385',
                    'parent_id' => '384',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '851',
                    'rght' => '852'
                )
            ),
            (int) 370 => array(
                'Aco' => array(
                    'id' => '386',
                    'parent_id' => '384',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '853',
                    'rght' => '854'
                )
            ),
            (int) 371 => array(
                'Aco' => array(
                    'id' => '387',
                    'parent_id' => '384',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '855',
                    'rght' => '856'
                )
            ),
            (int) 372 => array(
                'Aco' => array(
                    'id' => '388',
                    'parent_id' => '384',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '857',
                    'rght' => '858'
                )
            ),
            (int) 373 => array(
                'Aco' => array(
                    'id' => '389',
                    'parent_id' => '384',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '859',
                    'rght' => '860'
                )
            ),
            (int) 374 => array(
                'Aco' => array(
                    'id' => '390',
                    'parent_id' => '384',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '861',
                    'rght' => '862'
                )
            ),
            (int) 375 => array(
                'Aco' => array(
                    'id' => '391',
                    'parent_id' => '384',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '863',
                    'rght' => '864'
                )
            ),
            (int) 376 => array(
                'Aco' => array(
                    'id' => '392',
                    'parent_id' => '384',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '865',
                    'rght' => '866'
                )
            ),
            (int) 377 => array(
                'Aco' => array(
                    'id' => '393',
                    'parent_id' => '384',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '867',
                    'rght' => '868'
                )
            ),
            (int) 378 => array(
                'Aco' => array(
                    'id' => '394',
                    'parent_id' => '384',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '869',
                    'rght' => '870'
                )
            ),
            (int) 379 => array(
                'Aco' => array(
                    'id' => '395',
                    'parent_id' => '384',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '871',
                    'rght' => '872'
                )
            ),
            (int) 380 => array(
                'Aco' => array(
                    'id' => '396',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Logentries',
                    'lft' => '878',
                    'rght' => '897'
                )
            ),
            (int) 381 => array(
                'Aco' => array(
                    'id' => '397',
                    'parent_id' => '396',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '879',
                    'rght' => '880'
                )
            ),
            (int) 382 => array(
                'Aco' => array(
                    'id' => '398',
                    'parent_id' => '396',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '881',
                    'rght' => '882'
                )
            ),
            (int) 383 => array(
                'Aco' => array(
                    'id' => '399',
                    'parent_id' => '396',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '883',
                    'rght' => '884'
                )
            ),
            (int) 384 => array(
                'Aco' => array(
                    'id' => '400',
                    'parent_id' => '396',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '885',
                    'rght' => '886'
                )
            ),
            (int) 385 => array(
                'Aco' => array(
                    'id' => '401',
                    'parent_id' => '396',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '887',
                    'rght' => '888'
                )
            ),
            (int) 386 => array(
                'Aco' => array(
                    'id' => '402',
                    'parent_id' => '396',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '889',
                    'rght' => '890'
                )
            ),
            (int) 387 => array(
                'Aco' => array(
                    'id' => '403',
                    'parent_id' => '396',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '891',
                    'rght' => '892'
                )
            ),
            (int) 388 => array(
                'Aco' => array(
                    'id' => '404',
                    'parent_id' => '396',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '893',
                    'rght' => '894'
                )
            ),
            (int) 389 => array(
                'Aco' => array(
                    'id' => '405',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Login',
                    'lft' => '898',
                    'rght' => '927'
                )
            ),
            (int) 390 => array(
                'Aco' => array(
                    'id' => '406',
                    'parent_id' => '405',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '899',
                    'rght' => '900'
                )
            ),
            (int) 391 => array(
                'Aco' => array(
                    'id' => '407',
                    'parent_id' => '405',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'login',
                    'lft' => '901',
                    'rght' => '902'
                )
            ),
            (int) 392 => array(
                'Aco' => array(
                    'id' => '408',
                    'parent_id' => '405',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'onetimetoken',
                    'lft' => '903',
                    'rght' => '904'
                )
            ),
            (int) 393 => array(
                'Aco' => array(
                    'id' => '409',
                    'parent_id' => '405',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'logout',
                    'lft' => '905',
                    'rght' => '906'
                )
            ),
            (int) 394 => array(
                'Aco' => array(
                    'id' => '410',
                    'parent_id' => '405',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'auth_required',
                    'lft' => '907',
                    'rght' => '908'
                )
            ),
            (int) 395 => array(
                'Aco' => array(
                    'id' => '411',
                    'parent_id' => '405',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'lock',
                    'lft' => '909',
                    'rght' => '910'
                )
            ),
            (int) 396 => array(
                'Aco' => array(
                    'id' => '412',
                    'parent_id' => '405',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '911',
                    'rght' => '912'
                )
            ),
            (int) 397 => array(
                'Aco' => array(
                    'id' => '413',
                    'parent_id' => '405',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '913',
                    'rght' => '914'
                )
            ),
            (int) 398 => array(
                'Aco' => array(
                    'id' => '414',
                    'parent_id' => '405',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '915',
                    'rght' => '916'
                )
            ),
            (int) 399 => array(
                'Aco' => array(
                    'id' => '415',
                    'parent_id' => '405',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '917',
                    'rght' => '918'
                )
            ),
            (int) 400 => array(
                'Aco' => array(
                    'id' => '416',
                    'parent_id' => '405',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '919',
                    'rght' => '920'
                )
            ),
            (int) 401 => array(
                'Aco' => array(
                    'id' => '417',
                    'parent_id' => '405',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '921',
                    'rght' => '922'
                )
            ),
            (int) 402 => array(
                'Aco' => array(
                    'id' => '418',
                    'parent_id' => '405',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '923',
                    'rght' => '924'
                )
            ),
            (int) 403 => array(
                'Aco' => array(
                    'id' => '419',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Macros',
                    'lft' => '928',
                    'rght' => '949'
                )
            ),
            (int) 404 => array(
                'Aco' => array(
                    'id' => '420',
                    'parent_id' => '419',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '929',
                    'rght' => '930'
                )
            ),
            (int) 405 => array(
                'Aco' => array(
                    'id' => '421',
                    'parent_id' => '419',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'addMacro',
                    'lft' => '931',
                    'rght' => '932'
                )
            ),
            (int) 406 => array(
                'Aco' => array(
                    'id' => '422',
                    'parent_id' => '419',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '933',
                    'rght' => '934'
                )
            ),
            (int) 407 => array(
                'Aco' => array(
                    'id' => '423',
                    'parent_id' => '419',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '935',
                    'rght' => '936'
                )
            ),
            (int) 408 => array(
                'Aco' => array(
                    'id' => '424',
                    'parent_id' => '419',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '937',
                    'rght' => '938'
                )
            ),
            (int) 409 => array(
                'Aco' => array(
                    'id' => '425',
                    'parent_id' => '419',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '939',
                    'rght' => '940'
                )
            ),
            (int) 410 => array(
                'Aco' => array(
                    'id' => '426',
                    'parent_id' => '419',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '941',
                    'rght' => '942'
                )
            ),
            (int) 411 => array(
                'Aco' => array(
                    'id' => '427',
                    'parent_id' => '419',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '943',
                    'rght' => '944'
                )
            ),
            (int) 412 => array(
                'Aco' => array(
                    'id' => '428',
                    'parent_id' => '419',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '945',
                    'rght' => '946'
                )
            ),
            (int) 413 => array(
                'Aco' => array(
                    'id' => '429',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Nagiostats',
                    'lft' => '950',
                    'rght' => '969'
                )
            ),
            (int) 414 => array(
                'Aco' => array(
                    'id' => '430',
                    'parent_id' => '429',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '951',
                    'rght' => '952'
                )
            ),
            (int) 415 => array(
                'Aco' => array(
                    'id' => '431',
                    'parent_id' => '429',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '953',
                    'rght' => '954'
                )
            ),
            (int) 416 => array(
                'Aco' => array(
                    'id' => '432',
                    'parent_id' => '429',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '955',
                    'rght' => '956'
                )
            ),
            (int) 417 => array(
                'Aco' => array(
                    'id' => '433',
                    'parent_id' => '429',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '957',
                    'rght' => '958'
                )
            ),
            (int) 418 => array(
                'Aco' => array(
                    'id' => '434',
                    'parent_id' => '429',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '959',
                    'rght' => '960'
                )
            ),
            (int) 419 => array(
                'Aco' => array(
                    'id' => '435',
                    'parent_id' => '429',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '961',
                    'rght' => '962'
                )
            ),
            (int) 420 => array(
                'Aco' => array(
                    'id' => '436',
                    'parent_id' => '429',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '963',
                    'rght' => '964'
                )
            ),
            (int) 421 => array(
                'Aco' => array(
                    'id' => '437',
                    'parent_id' => '429',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '965',
                    'rght' => '966'
                )
            ),
            (int) 422 => array(
                'Aco' => array(
                    'id' => '438',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Notifications',
                    'lft' => '970',
                    'rght' => '993'
                )
            ),
            (int) 423 => array(
                'Aco' => array(
                    'id' => '439',
                    'parent_id' => '438',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '971',
                    'rght' => '972'
                )
            ),
            (int) 424 => array(
                'Aco' => array(
                    'id' => '440',
                    'parent_id' => '438',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'hostNotification',
                    'lft' => '973',
                    'rght' => '974'
                )
            ),
            (int) 425 => array(
                'Aco' => array(
                    'id' => '441',
                    'parent_id' => '438',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceNotification',
                    'lft' => '975',
                    'rght' => '976'
                )
            ),
            (int) 426 => array(
                'Aco' => array(
                    'id' => '442',
                    'parent_id' => '438',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '977',
                    'rght' => '978'
                )
            ),
            (int) 427 => array(
                'Aco' => array(
                    'id' => '443',
                    'parent_id' => '438',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '979',
                    'rght' => '980'
                )
            ),
            (int) 428 => array(
                'Aco' => array(
                    'id' => '444',
                    'parent_id' => '438',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '981',
                    'rght' => '982'
                )
            ),
            (int) 429 => array(
                'Aco' => array(
                    'id' => '445',
                    'parent_id' => '438',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '983',
                    'rght' => '984'
                )
            ),
            (int) 430 => array(
                'Aco' => array(
                    'id' => '446',
                    'parent_id' => '438',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '985',
                    'rght' => '986'
                )
            ),
            (int) 431 => array(
                'Aco' => array(
                    'id' => '447',
                    'parent_id' => '438',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '987',
                    'rght' => '988'
                )
            ),
            (int) 432 => array(
                'Aco' => array(
                    'id' => '448',
                    'parent_id' => '438',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '989',
                    'rght' => '990'
                )
            ),
            (int) 433 => array(
                'Aco' => array(
                    'id' => '449',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Packetmanager',
                    'lft' => '994',
                    'rght' => '1015'
                )
            ),
            (int) 434 => array(
                'Aco' => array(
                    'id' => '450',
                    'parent_id' => '449',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '995',
                    'rght' => '996'
                )
            ),
            (int) 435 => array(
                'Aco' => array(
                    'id' => '451',
                    'parent_id' => '449',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getPackets',
                    'lft' => '997',
                    'rght' => '998'
                )
            ),
            (int) 436 => array(
                'Aco' => array(
                    'id' => '452',
                    'parent_id' => '449',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '999',
                    'rght' => '1000'
                )
            ),
            (int) 437 => array(
                'Aco' => array(
                    'id' => '453',
                    'parent_id' => '449',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1001',
                    'rght' => '1002'
                )
            ),
            (int) 438 => array(
                'Aco' => array(
                    'id' => '454',
                    'parent_id' => '449',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1003',
                    'rght' => '1004'
                )
            ),
            (int) 439 => array(
                'Aco' => array(
                    'id' => '455',
                    'parent_id' => '449',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1005',
                    'rght' => '1006'
                )
            ),
            (int) 440 => array(
                'Aco' => array(
                    'id' => '456',
                    'parent_id' => '449',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1007',
                    'rght' => '1008'
                )
            ),
            (int) 441 => array(
                'Aco' => array(
                    'id' => '457',
                    'parent_id' => '449',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1009',
                    'rght' => '1010'
                )
            ),
            (int) 442 => array(
                'Aco' => array(
                    'id' => '458',
                    'parent_id' => '449',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1011',
                    'rght' => '1012'
                )
            ),
            (int) 443 => array(
                'Aco' => array(
                    'id' => '459',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Profile',
                    'lft' => '1016',
                    'rght' => '1037'
                )
            ),
            (int) 444 => array(
                'Aco' => array(
                    'id' => '460',
                    'parent_id' => '459',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '1017',
                    'rght' => '1018'
                )
            ),
            (int) 445 => array(
                'Aco' => array(
                    'id' => '461',
                    'parent_id' => '459',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'deleteImage',
                    'lft' => '1019',
                    'rght' => '1020'
                )
            ),
            (int) 446 => array(
                'Aco' => array(
                    'id' => '462',
                    'parent_id' => '459',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1021',
                    'rght' => '1022'
                )
            ),
            (int) 447 => array(
                'Aco' => array(
                    'id' => '463',
                    'parent_id' => '459',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1023',
                    'rght' => '1024'
                )
            ),
            (int) 448 => array(
                'Aco' => array(
                    'id' => '464',
                    'parent_id' => '459',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1025',
                    'rght' => '1026'
                )
            ),
            (int) 449 => array(
                'Aco' => array(
                    'id' => '465',
                    'parent_id' => '459',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1027',
                    'rght' => '1028'
                )
            ),
            (int) 450 => array(
                'Aco' => array(
                    'id' => '466',
                    'parent_id' => '459',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1029',
                    'rght' => '1030'
                )
            ),
            (int) 451 => array(
                'Aco' => array(
                    'id' => '467',
                    'parent_id' => '459',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1031',
                    'rght' => '1032'
                )
            ),
            (int) 452 => array(
                'Aco' => array(
                    'id' => '468',
                    'parent_id' => '459',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1033',
                    'rght' => '1034'
                )
            ),
            (int) 453 => array(
                'Aco' => array(
                    'id' => '469',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Proxy',
                    'lft' => '1038',
                    'rght' => '1061'
                )
            ),
            (int) 454 => array(
                'Aco' => array(
                    'id' => '470',
                    'parent_id' => '469',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1039',
                    'rght' => '1040'
                )
            ),
            (int) 455 => array(
                'Aco' => array(
                    'id' => '471',
                    'parent_id' => '469',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '1041',
                    'rght' => '1042'
                )
            ),
            (int) 456 => array(
                'Aco' => array(
                    'id' => '472',
                    'parent_id' => '469',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getSettings',
                    'lft' => '1043',
                    'rght' => '1044'
                )
            ),
            (int) 457 => array(
                'Aco' => array(
                    'id' => '473',
                    'parent_id' => '469',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1045',
                    'rght' => '1046'
                )
            ),
            (int) 458 => array(
                'Aco' => array(
                    'id' => '474',
                    'parent_id' => '469',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1047',
                    'rght' => '1048'
                )
            ),
            (int) 459 => array(
                'Aco' => array(
                    'id' => '475',
                    'parent_id' => '469',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1049',
                    'rght' => '1050'
                )
            ),
            (int) 460 => array(
                'Aco' => array(
                    'id' => '476',
                    'parent_id' => '469',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1051',
                    'rght' => '1052'
                )
            ),
            (int) 461 => array(
                'Aco' => array(
                    'id' => '477',
                    'parent_id' => '469',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1053',
                    'rght' => '1054'
                )
            ),
            (int) 462 => array(
                'Aco' => array(
                    'id' => '478',
                    'parent_id' => '469',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1055',
                    'rght' => '1056'
                )
            ),
            (int) 463 => array(
                'Aco' => array(
                    'id' => '479',
                    'parent_id' => '469',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1057',
                    'rght' => '1058'
                )
            ),
            (int) 464 => array(
                'Aco' => array(
                    'id' => '480',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Qr',
                    'lft' => '1062',
                    'rght' => '1081'
                )
            ),
            (int) 465 => array(
                'Aco' => array(
                    'id' => '481',
                    'parent_id' => '480',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1063',
                    'rght' => '1064'
                )
            ),
            (int) 466 => array(
                'Aco' => array(
                    'id' => '482',
                    'parent_id' => '480',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1065',
                    'rght' => '1066'
                )
            ),
            (int) 467 => array(
                'Aco' => array(
                    'id' => '483',
                    'parent_id' => '480',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1067',
                    'rght' => '1068'
                )
            ),
            (int) 468 => array(
                'Aco' => array(
                    'id' => '484',
                    'parent_id' => '480',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1069',
                    'rght' => '1070'
                )
            ),
            (int) 469 => array(
                'Aco' => array(
                    'id' => '485',
                    'parent_id' => '480',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1071',
                    'rght' => '1072'
                )
            ),
            (int) 470 => array(
                'Aco' => array(
                    'id' => '486',
                    'parent_id' => '480',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1073',
                    'rght' => '1074'
                )
            ),
            (int) 471 => array(
                'Aco' => array(
                    'id' => '487',
                    'parent_id' => '480',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1075',
                    'rght' => '1076'
                )
            ),
            (int) 472 => array(
                'Aco' => array(
                    'id' => '488',
                    'parent_id' => '480',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1077',
                    'rght' => '1078'
                )
            ),
            (int) 473 => array(
                'Aco' => array(
                    'id' => '489',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Registers',
                    'lft' => '1082',
                    'rght' => '1103'
                )
            ),
            (int) 474 => array(
                'Aco' => array(
                    'id' => '490',
                    'parent_id' => '489',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1083',
                    'rght' => '1084'
                )
            ),
            (int) 475 => array(
                'Aco' => array(
                    'id' => '491',
                    'parent_id' => '489',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'check',
                    'lft' => '1085',
                    'rght' => '1086'
                )
            ),
            (int) 476 => array(
                'Aco' => array(
                    'id' => '492',
                    'parent_id' => '489',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1087',
                    'rght' => '1088'
                )
            ),
            (int) 477 => array(
                'Aco' => array(
                    'id' => '493',
                    'parent_id' => '489',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1089',
                    'rght' => '1090'
                )
            ),
            (int) 478 => array(
                'Aco' => array(
                    'id' => '494',
                    'parent_id' => '489',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1091',
                    'rght' => '1092'
                )
            ),
            (int) 479 => array(
                'Aco' => array(
                    'id' => '495',
                    'parent_id' => '489',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1093',
                    'rght' => '1094'
                )
            ),
            (int) 480 => array(
                'Aco' => array(
                    'id' => '496',
                    'parent_id' => '489',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1095',
                    'rght' => '1096'
                )
            ),
            (int) 481 => array(
                'Aco' => array(
                    'id' => '497',
                    'parent_id' => '489',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1097',
                    'rght' => '1098'
                )
            ),
            (int) 482 => array(
                'Aco' => array(
                    'id' => '498',
                    'parent_id' => '489',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1099',
                    'rght' => '1100'
                )
            ),
            (int) 483 => array(
                'Aco' => array(
                    'id' => '499',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Rrds',
                    'lft' => '1104',
                    'rght' => '1125'
                )
            ),
            (int) 484 => array(
                'Aco' => array(
                    'id' => '500',
                    'parent_id' => '499',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1105',
                    'rght' => '1106'
                )
            ),
            (int) 485 => array(
                'Aco' => array(
                    'id' => '501',
                    'parent_id' => '499',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'ajax',
                    'lft' => '1107',
                    'rght' => '1108'
                )
            ),
            (int) 486 => array(
                'Aco' => array(
                    'id' => '502',
                    'parent_id' => '499',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1109',
                    'rght' => '1110'
                )
            ),
            (int) 487 => array(
                'Aco' => array(
                    'id' => '503',
                    'parent_id' => '499',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1111',
                    'rght' => '1112'
                )
            ),
            (int) 488 => array(
                'Aco' => array(
                    'id' => '504',
                    'parent_id' => '499',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1113',
                    'rght' => '1114'
                )
            ),
            (int) 489 => array(
                'Aco' => array(
                    'id' => '505',
                    'parent_id' => '499',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1115',
                    'rght' => '1116'
                )
            ),
            (int) 490 => array(
                'Aco' => array(
                    'id' => '506',
                    'parent_id' => '499',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1117',
                    'rght' => '1118'
                )
            ),
            (int) 491 => array(
                'Aco' => array(
                    'id' => '507',
                    'parent_id' => '499',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1119',
                    'rght' => '1120'
                )
            ),
            (int) 492 => array(
                'Aco' => array(
                    'id' => '508',
                    'parent_id' => '499',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1121',
                    'rght' => '1122'
                )
            ),
            (int) 493 => array(
                'Aco' => array(
                    'id' => '509',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Search',
                    'lft' => '1126',
                    'rght' => '1149'
                )
            ),
            (int) 494 => array(
                'Aco' => array(
                    'id' => '510',
                    'parent_id' => '509',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1127',
                    'rght' => '1128'
                )
            ),
            (int) 495 => array(
                'Aco' => array(
                    'id' => '511',
                    'parent_id' => '509',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'hostMacro',
                    'lft' => '1129',
                    'rght' => '1130'
                )
            ),
            (int) 496 => array(
                'Aco' => array(
                    'id' => '512',
                    'parent_id' => '509',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceMacro',
                    'lft' => '1131',
                    'rght' => '1132'
                )
            ),
            (int) 497 => array(
                'Aco' => array(
                    'id' => '513',
                    'parent_id' => '509',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1133',
                    'rght' => '1134'
                )
            ),
            (int) 498 => array(
                'Aco' => array(
                    'id' => '514',
                    'parent_id' => '509',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1135',
                    'rght' => '1136'
                )
            ),
            (int) 499 => array(
                'Aco' => array(
                    'id' => '515',
                    'parent_id' => '509',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1137',
                    'rght' => '1138'
                )
            ),
            (int) 500 => array(
                'Aco' => array(
                    'id' => '516',
                    'parent_id' => '509',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1139',
                    'rght' => '1140'
                )
            ),
            (int) 501 => array(
                'Aco' => array(
                    'id' => '517',
                    'parent_id' => '509',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1141',
                    'rght' => '1142'
                )
            ),
            (int) 502 => array(
                'Aco' => array(
                    'id' => '518',
                    'parent_id' => '509',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1143',
                    'rght' => '1144'
                )
            ),
            (int) 503 => array(
                'Aco' => array(
                    'id' => '519',
                    'parent_id' => '509',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1145',
                    'rght' => '1146'
                )
            ),
            (int) 504 => array(
                'Aco' => array(
                    'id' => '520',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Servicechecks',
                    'lft' => '1150',
                    'rght' => '1169'
                )
            ),
            (int) 505 => array(
                'Aco' => array(
                    'id' => '521',
                    'parent_id' => '520',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1151',
                    'rght' => '1152'
                )
            ),
            (int) 506 => array(
                'Aco' => array(
                    'id' => '522',
                    'parent_id' => '520',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1153',
                    'rght' => '1154'
                )
            ),
            (int) 507 => array(
                'Aco' => array(
                    'id' => '523',
                    'parent_id' => '520',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1155',
                    'rght' => '1156'
                )
            ),
            (int) 508 => array(
                'Aco' => array(
                    'id' => '524',
                    'parent_id' => '520',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1157',
                    'rght' => '1158'
                )
            ),
            (int) 509 => array(
                'Aco' => array(
                    'id' => '525',
                    'parent_id' => '520',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1159',
                    'rght' => '1160'
                )
            ),
            (int) 510 => array(
                'Aco' => array(
                    'id' => '526',
                    'parent_id' => '520',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1161',
                    'rght' => '1162'
                )
            ),
            (int) 511 => array(
                'Aco' => array(
                    'id' => '527',
                    'parent_id' => '520',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1163',
                    'rght' => '1164'
                )
            ),
            (int) 512 => array(
                'Aco' => array(
                    'id' => '528',
                    'parent_id' => '520',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1165',
                    'rght' => '1166'
                )
            ),
            (int) 513 => array(
                'Aco' => array(
                    'id' => '529',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Servicedependencies',
                    'lft' => '1170',
                    'rght' => '1199'
                )
            ),
            (int) 514 => array(
                'Aco' => array(
                    'id' => '530',
                    'parent_id' => '529',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1171',
                    'rght' => '1172'
                )
            ),
            (int) 515 => array(
                'Aco' => array(
                    'id' => '531',
                    'parent_id' => '529',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '1173',
                    'rght' => '1174'
                )
            ),
            (int) 516 => array(
                'Aco' => array(
                    'id' => '532',
                    'parent_id' => '529',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '1175',
                    'rght' => '1176'
                )
            ),
            (int) 517 => array(
                'Aco' => array(
                    'id' => '533',
                    'parent_id' => '529',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '1177',
                    'rght' => '1178'
                )
            ),
            (int) 518 => array(
                'Aco' => array(
                    'id' => '534',
                    'parent_id' => '529',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadElementsByContainerId',
                    'lft' => '1179',
                    'rght' => '1180'
                )
            ),
            (int) 519 => array(
                'Aco' => array(
                    'id' => '535',
                    'parent_id' => '529',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1181',
                    'rght' => '1182'
                )
            ),
            (int) 520 => array(
                'Aco' => array(
                    'id' => '536',
                    'parent_id' => '529',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1183',
                    'rght' => '1184'
                )
            ),
            (int) 521 => array(
                'Aco' => array(
                    'id' => '537',
                    'parent_id' => '529',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1185',
                    'rght' => '1186'
                )
            ),
            (int) 522 => array(
                'Aco' => array(
                    'id' => '538',
                    'parent_id' => '529',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1187',
                    'rght' => '1188'
                )
            ),
            (int) 523 => array(
                'Aco' => array(
                    'id' => '539',
                    'parent_id' => '529',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1189',
                    'rght' => '1190'
                )
            ),
            (int) 524 => array(
                'Aco' => array(
                    'id' => '540',
                    'parent_id' => '529',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1191',
                    'rght' => '1192'
                )
            ),
            (int) 525 => array(
                'Aco' => array(
                    'id' => '541',
                    'parent_id' => '529',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1193',
                    'rght' => '1194'
                )
            ),
            (int) 526 => array(
                'Aco' => array(
                    'id' => '542',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Serviceescalations',
                    'lft' => '1200',
                    'rght' => '1229'
                )
            ),
            (int) 527 => array(
                'Aco' => array(
                    'id' => '543',
                    'parent_id' => '542',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1201',
                    'rght' => '1202'
                )
            ),
            (int) 528 => array(
                'Aco' => array(
                    'id' => '544',
                    'parent_id' => '542',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '1203',
                    'rght' => '1204'
                )
            ),
            (int) 529 => array(
                'Aco' => array(
                    'id' => '545',
                    'parent_id' => '542',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '1205',
                    'rght' => '1206'
                )
            ),
            (int) 530 => array(
                'Aco' => array(
                    'id' => '546',
                    'parent_id' => '542',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '1207',
                    'rght' => '1208'
                )
            ),
            (int) 531 => array(
                'Aco' => array(
                    'id' => '547',
                    'parent_id' => '542',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadElementsByContainerId',
                    'lft' => '1209',
                    'rght' => '1210'
                )
            ),
            (int) 532 => array(
                'Aco' => array(
                    'id' => '548',
                    'parent_id' => '542',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1211',
                    'rght' => '1212'
                )
            ),
            (int) 533 => array(
                'Aco' => array(
                    'id' => '549',
                    'parent_id' => '542',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1213',
                    'rght' => '1214'
                )
            ),
            (int) 534 => array(
                'Aco' => array(
                    'id' => '550',
                    'parent_id' => '542',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1215',
                    'rght' => '1216'
                )
            ),
            (int) 535 => array(
                'Aco' => array(
                    'id' => '551',
                    'parent_id' => '542',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1217',
                    'rght' => '1218'
                )
            ),
            (int) 536 => array(
                'Aco' => array(
                    'id' => '552',
                    'parent_id' => '542',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1219',
                    'rght' => '1220'
                )
            ),
            (int) 537 => array(
                'Aco' => array(
                    'id' => '553',
                    'parent_id' => '542',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1221',
                    'rght' => '1222'
                )
            ),
            (int) 538 => array(
                'Aco' => array(
                    'id' => '554',
                    'parent_id' => '542',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1223',
                    'rght' => '1224'
                )
            ),
            (int) 539 => array(
                'Aco' => array(
                    'id' => '555',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Servicegroups',
                    'lft' => '1230',
                    'rght' => '1265'
                )
            ),
            (int) 540 => array(
                'Aco' => array(
                    'id' => '556',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1231',
                    'rght' => '1232'
                )
            ),
            (int) 541 => array(
                'Aco' => array(
                    'id' => '557',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '1233',
                    'rght' => '1234'
                )
            ),
            (int) 542 => array(
                'Aco' => array(
                    'id' => '558',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '1235',
                    'rght' => '1236'
                )
            ),
            (int) 543 => array(
                'Aco' => array(
                    'id' => '559',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadServices',
                    'lft' => '1237',
                    'rght' => '1238'
                )
            ),
            (int) 544 => array(
                'Aco' => array(
                    'id' => '560',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '1239',
                    'rght' => '1240'
                )
            ),
            (int) 545 => array(
                'Aco' => array(
                    'id' => '561',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_delete',
                    'lft' => '1241',
                    'rght' => '1242'
                )
            ),
            (int) 546 => array(
                'Aco' => array(
                    'id' => '562',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_add',
                    'lft' => '1243',
                    'rght' => '1244'
                )
            ),
            (int) 547 => array(
                'Aco' => array(
                    'id' => '563',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'listToPdf',
                    'lft' => '1245',
                    'rght' => '1246'
                )
            ),
            (int) 548 => array(
                'Aco' => array(
                    'id' => '564',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1247',
                    'rght' => '1248'
                )
            ),
            (int) 549 => array(
                'Aco' => array(
                    'id' => '565',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1249',
                    'rght' => '1250'
                )
            ),
            (int) 550 => array(
                'Aco' => array(
                    'id' => '566',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1251',
                    'rght' => '1252'
                )
            ),
            (int) 551 => array(
                'Aco' => array(
                    'id' => '567',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1253',
                    'rght' => '1254'
                )
            ),
            (int) 552 => array(
                'Aco' => array(
                    'id' => '568',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1255',
                    'rght' => '1256'
                )
            ),
            (int) 553 => array(
                'Aco' => array(
                    'id' => '569',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1257',
                    'rght' => '1258'
                )
            ),
            (int) 554 => array(
                'Aco' => array(
                    'id' => '570',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1259',
                    'rght' => '1260'
                )
            ),
            (int) 555 => array(
                'Aco' => array(
                    'id' => '571',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Services',
                    'lft' => '1266',
                    'rght' => '1351'
                )
            ),
            (int) 556 => array(
                'Aco' => array(
                    'id' => '572',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1267',
                    'rght' => '1268'
                )
            ),
            (int) 557 => array(
                'Aco' => array(
                    'id' => '573',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'notMonitored',
                    'lft' => '1269',
                    'rght' => '1270'
                )
            ),
            (int) 558 => array(
                'Aco' => array(
                    'id' => '574',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'disabled',
                    'lft' => '1271',
                    'rght' => '1272'
                )
            ),
            (int) 559 => array(
                'Aco' => array(
                    'id' => '575',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '1273',
                    'rght' => '1274'
                )
            ),
            (int) 560 => array(
                'Aco' => array(
                    'id' => '576',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '1275',
                    'rght' => '1276'
                )
            ),
            (int) 561 => array(
                'Aco' => array(
                    'id' => '577',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '1277',
                    'rght' => '1278'
                )
            ),
            (int) 562 => array(
                'Aco' => array(
                    'id' => '578',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_delete',
                    'lft' => '1279',
                    'rght' => '1280'
                )
            ),
            (int) 563 => array(
                'Aco' => array(
                    'id' => '579',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'copy',
                    'lft' => '1281',
                    'rght' => '1282'
                )
            ),
            (int) 564 => array(
                'Aco' => array(
                    'id' => '580',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'deactivate',
                    'lft' => '1283',
                    'rght' => '1284'
                )
            ),
            (int) 565 => array(
                'Aco' => array(
                    'id' => '581',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_deactivate',
                    'lft' => '1285',
                    'rght' => '1286'
                )
            ),
            (int) 566 => array(
                'Aco' => array(
                    'id' => '582',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'enable',
                    'lft' => '1287',
                    'rght' => '1288'
                )
            ),
            (int) 567 => array(
                'Aco' => array(
                    'id' => '583',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadContactsAndContactgroups',
                    'lft' => '1289',
                    'rght' => '1290'
                )
            ),
            (int) 568 => array(
                'Aco' => array(
                    'id' => '584',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadParametersByCommandId',
                    'lft' => '1291',
                    'rght' => '1292'
                )
            ),
            (int) 569 => array(
                'Aco' => array(
                    'id' => '585',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadNagParametersByCommandId',
                    'lft' => '1293',
                    'rght' => '1294'
                )
            ),
            (int) 570 => array(
                'Aco' => array(
                    'id' => '586',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadArgumentsAdd',
                    'lft' => '1295',
                    'rght' => '1296'
                )
            ),
            (int) 571 => array(
                'Aco' => array(
                    'id' => '587',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadServicetemplatesArguments',
                    'lft' => '1297',
                    'rght' => '1298'
                )
            ),
            (int) 572 => array(
                'Aco' => array(
                    'id' => '588',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadTemplateData',
                    'lft' => '1299',
                    'rght' => '1300'
                )
            ),
            (int) 573 => array(
                'Aco' => array(
                    'id' => '589',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'addCustomMacro',
                    'lft' => '1301',
                    'rght' => '1302'
                )
            ),
            (int) 574 => array(
                'Aco' => array(
                    'id' => '590',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadServices',
                    'lft' => '1303',
                    'rght' => '1304'
                )
            ),
            (int) 575 => array(
                'Aco' => array(
                    'id' => '591',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadTemplateMacros',
                    'lft' => '1305',
                    'rght' => '1306'
                )
            ),
            (int) 576 => array(
                'Aco' => array(
                    'id' => '592',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'browser',
                    'lft' => '1307',
                    'rght' => '1308'
                )
            ),
            (int) 577 => array(
                'Aco' => array(
                    'id' => '593',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'servicesByHostId',
                    'lft' => '1309',
                    'rght' => '1310'
                )
            ),
            (int) 578 => array(
                'Aco' => array(
                    'id' => '594',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceList',
                    'lft' => '1311',
                    'rght' => '1312'
                )
            ),
            (int) 579 => array(
                'Aco' => array(
                    'id' => '595',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'grapherSwitch',
                    'lft' => '1313',
                    'rght' => '1314'
                )
            ),
            (int) 580 => array(
                'Aco' => array(
                    'id' => '596',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'grapher',
                    'lft' => '1315',
                    'rght' => '1316'
                )
            ),
            (int) 581 => array(
                'Aco' => array(
                    'id' => '597',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'grapherTemplate',
                    'lft' => '1317',
                    'rght' => '1318'
                )
            ),
            (int) 582 => array(
                'Aco' => array(
                    'id' => '598',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'grapherZoom',
                    'lft' => '1319',
                    'rght' => '1320'
                )
            ),
            (int) 583 => array(
                'Aco' => array(
                    'id' => '599',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'grapherZoomTemplate',
                    'lft' => '1321',
                    'rght' => '1322'
                )
            ),
            (int) 584 => array(
                'Aco' => array(
                    'id' => '600',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'createGrapherErrorPng',
                    'lft' => '1323',
                    'rght' => '1324'
                )
            ),
            (int) 585 => array(
                'Aco' => array(
                    'id' => '601',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'longOutputByUuid',
                    'lft' => '1325',
                    'rght' => '1326'
                )
            ),
            (int) 586 => array(
                'Aco' => array(
                    'id' => '602',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'listToPdf',
                    'lft' => '1327',
                    'rght' => '1328'
                )
            ),
            (int) 587 => array(
                'Aco' => array(
                    'id' => '603',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkcommand',
                    'lft' => '1329',
                    'rght' => '1330'
                )
            ),
            (int) 588 => array(
                'Aco' => array(
                    'id' => '604',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1331',
                    'rght' => '1332'
                )
            ),
            (int) 589 => array(
                'Aco' => array(
                    'id' => '605',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1333',
                    'rght' => '1334'
                )
            ),
            (int) 590 => array(
                'Aco' => array(
                    'id' => '606',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1335',
                    'rght' => '1336'
                )
            ),
            (int) 591 => array(
                'Aco' => array(
                    'id' => '607',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1337',
                    'rght' => '1338'
                )
            ),
            (int) 592 => array(
                'Aco' => array(
                    'id' => '608',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1339',
                    'rght' => '1340'
                )
            ),
            (int) 593 => array(
                'Aco' => array(
                    'id' => '609',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1341',
                    'rght' => '1342'
                )
            ),
            (int) 594 => array(
                'Aco' => array(
                    'id' => '610',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1343',
                    'rght' => '1344'
                )
            ),
            (int) 595 => array(
                'Aco' => array(
                    'id' => '611',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Servicetemplategroups',
                    'lft' => '1352',
                    'rght' => '1389'
                )
            ),
            (int) 596 => array(
                'Aco' => array(
                    'id' => '612',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1353',
                    'rght' => '1354'
                )
            ),
            (int) 597 => array(
                'Aco' => array(
                    'id' => '613',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '1355',
                    'rght' => '1356'
                )
            ),
            (int) 598 => array(
                'Aco' => array(
                    'id' => '614',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '1357',
                    'rght' => '1358'
                )
            ),
            (int) 599 => array(
                'Aco' => array(
                    'id' => '615',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allocateToHost',
                    'lft' => '1359',
                    'rght' => '1360'
                )
            ),
            (int) 600 => array(
                'Aco' => array(
                    'id' => '616',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allocateToHostgroup',
                    'lft' => '1361',
                    'rght' => '1362'
                )
            ),
            (int) 601 => array(
                'Aco' => array(
                    'id' => '617',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getHostsByHostgroupByAjax',
                    'lft' => '1363',
                    'rght' => '1364'
                )
            ),
            (int) 602 => array(
                'Aco' => array(
                    'id' => '618',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '1365',
                    'rght' => '1366'
                )
            ),
            (int) 603 => array(
                'Aco' => array(
                    'id' => '619',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadServicetemplatesByContainerId',
                    'lft' => '1367',
                    'rght' => '1368'
                )
            ),
            (int) 604 => array(
                'Aco' => array(
                    'id' => '620',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1369',
                    'rght' => '1370'
                )
            ),
            (int) 605 => array(
                'Aco' => array(
                    'id' => '621',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1371',
                    'rght' => '1372'
                )
            ),
            (int) 606 => array(
                'Aco' => array(
                    'id' => '622',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1373',
                    'rght' => '1374'
                )
            ),
            (int) 607 => array(
                'Aco' => array(
                    'id' => '623',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1375',
                    'rght' => '1376'
                )
            ),
            (int) 608 => array(
                'Aco' => array(
                    'id' => '624',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1377',
                    'rght' => '1378'
                )
            ),
            (int) 609 => array(
                'Aco' => array(
                    'id' => '625',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1379',
                    'rght' => '1380'
                )
            ),
            (int) 610 => array(
                'Aco' => array(
                    'id' => '626',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1381',
                    'rght' => '1382'
                )
            ),
            (int) 611 => array(
                'Aco' => array(
                    'id' => '627',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Servicetemplates',
                    'lft' => '1390',
                    'rght' => '1441'
                )
            ),
            (int) 612 => array(
                'Aco' => array(
                    'id' => '628',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1391',
                    'rght' => '1392'
                )
            ),
            (int) 613 => array(
                'Aco' => array(
                    'id' => '629',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '1393',
                    'rght' => '1394'
                )
            ),
            (int) 614 => array(
                'Aco' => array(
                    'id' => '630',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '1395',
                    'rght' => '1396'
                )
            ),
            (int) 615 => array(
                'Aco' => array(
                    'id' => '631',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '1397',
                    'rght' => '1398'
                )
            ),
            (int) 616 => array(
                'Aco' => array(
                    'id' => '632',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'usedBy',
                    'lft' => '1399',
                    'rght' => '1400'
                )
            ),
            (int) 617 => array(
                'Aco' => array(
                    'id' => '633',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadArguments',
                    'lft' => '1401',
                    'rght' => '1402'
                )
            ),
            (int) 618 => array(
                'Aco' => array(
                    'id' => '634',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadContactsAndContactgroups',
                    'lft' => '1403',
                    'rght' => '1404'
                )
            ),
            (int) 619 => array(
                'Aco' => array(
                    'id' => '635',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadArgumentsAdd',
                    'lft' => '1405',
                    'rght' => '1406'
                )
            ),
            (int) 620 => array(
                'Aco' => array(
                    'id' => '636',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadNagArgumentsAdd',
                    'lft' => '1407',
                    'rght' => '1408'
                )
            ),
            (int) 621 => array(
                'Aco' => array(
                    'id' => '637',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'addCustomMacro',
                    'lft' => '1409',
                    'rght' => '1410'
                )
            ),
            (int) 622 => array(
                'Aco' => array(
                    'id' => '638',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadParametersByCommandId',
                    'lft' => '1411',
                    'rght' => '1412'
                )
            ),
            (int) 623 => array(
                'Aco' => array(
                    'id' => '639',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadNagParametersByCommandId',
                    'lft' => '1413',
                    'rght' => '1414'
                )
            ),
            (int) 624 => array(
                'Aco' => array(
                    'id' => '640',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadElementsByContainerId',
                    'lft' => '1415',
                    'rght' => '1416'
                )
            ),
            (int) 625 => array(
                'Aco' => array(
                    'id' => '641',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1417',
                    'rght' => '1418'
                )
            ),
            (int) 626 => array(
                'Aco' => array(
                    'id' => '642',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1419',
                    'rght' => '1420'
                )
            ),
            (int) 627 => array(
                'Aco' => array(
                    'id' => '643',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1421',
                    'rght' => '1422'
                )
            ),
            (int) 628 => array(
                'Aco' => array(
                    'id' => '644',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1423',
                    'rght' => '1424'
                )
            ),
            (int) 629 => array(
                'Aco' => array(
                    'id' => '645',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1425',
                    'rght' => '1426'
                )
            ),
            (int) 630 => array(
                'Aco' => array(
                    'id' => '646',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1427',
                    'rght' => '1428'
                )
            ),
            (int) 631 => array(
                'Aco' => array(
                    'id' => '647',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1429',
                    'rght' => '1430'
                )
            ),
            (int) 632 => array(
                'Aco' => array(
                    'id' => '648',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Statehistories',
                    'lft' => '1442',
                    'rght' => '1463'
                )
            ),
            (int) 633 => array(
                'Aco' => array(
                    'id' => '649',
                    'parent_id' => '648',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'service',
                    'lft' => '1443',
                    'rght' => '1444'
                )
            ),
            (int) 634 => array(
                'Aco' => array(
                    'id' => '650',
                    'parent_id' => '648',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'host',
                    'lft' => '1445',
                    'rght' => '1446'
                )
            ),
            (int) 635 => array(
                'Aco' => array(
                    'id' => '651',
                    'parent_id' => '648',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1447',
                    'rght' => '1448'
                )
            ),
            (int) 636 => array(
                'Aco' => array(
                    'id' => '652',
                    'parent_id' => '648',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1449',
                    'rght' => '1450'
                )
            ),
            (int) 637 => array(
                'Aco' => array(
                    'id' => '653',
                    'parent_id' => '648',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1451',
                    'rght' => '1452'
                )
            ),
            (int) 638 => array(
                'Aco' => array(
                    'id' => '654',
                    'parent_id' => '648',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1453',
                    'rght' => '1454'
                )
            ),
            (int) 639 => array(
                'Aco' => array(
                    'id' => '655',
                    'parent_id' => '648',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1455',
                    'rght' => '1456'
                )
            ),
            (int) 640 => array(
                'Aco' => array(
                    'id' => '656',
                    'parent_id' => '648',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1457',
                    'rght' => '1458'
                )
            ),
            (int) 641 => array(
                'Aco' => array(
                    'id' => '657',
                    'parent_id' => '648',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1459',
                    'rght' => '1460'
                )
            ),
            (int) 642 => array(
                'Aco' => array(
                    'id' => '658',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Statusmaps',
                    'lft' => '1464',
                    'rght' => '1489'
                )
            ),
            (int) 643 => array(
                'Aco' => array(
                    'id' => '659',
                    'parent_id' => '658',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1465',
                    'rght' => '1466'
                )
            ),
            (int) 644 => array(
                'Aco' => array(
                    'id' => '660',
                    'parent_id' => '658',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getHostsAndConnections',
                    'lft' => '1467',
                    'rght' => '1468'
                )
            ),
            (int) 645 => array(
                'Aco' => array(
                    'id' => '661',
                    'parent_id' => '658',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'clickHostStatus',
                    'lft' => '1469',
                    'rght' => '1470'
                )
            ),
            (int) 646 => array(
                'Aco' => array(
                    'id' => '662',
                    'parent_id' => '658',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '1471',
                    'rght' => '1472'
                )
            ),
            (int) 647 => array(
                'Aco' => array(
                    'id' => '663',
                    'parent_id' => '658',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1473',
                    'rght' => '1474'
                )
            ),
            (int) 648 => array(
                'Aco' => array(
                    'id' => '664',
                    'parent_id' => '658',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1475',
                    'rght' => '1476'
                )
            ),
            (int) 649 => array(
                'Aco' => array(
                    'id' => '665',
                    'parent_id' => '658',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1477',
                    'rght' => '1478'
                )
            ),
            (int) 650 => array(
                'Aco' => array(
                    'id' => '666',
                    'parent_id' => '658',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1479',
                    'rght' => '1480'
                )
            ),
            (int) 651 => array(
                'Aco' => array(
                    'id' => '667',
                    'parent_id' => '658',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1481',
                    'rght' => '1482'
                )
            ),
            (int) 652 => array(
                'Aco' => array(
                    'id' => '668',
                    'parent_id' => '658',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1483',
                    'rght' => '1484'
                )
            ),
            (int) 653 => array(
                'Aco' => array(
                    'id' => '669',
                    'parent_id' => '658',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1485',
                    'rght' => '1486'
                )
            ),
            (int) 654 => array(
                'Aco' => array(
                    'id' => '670',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'System',
                    'lft' => '1490',
                    'rght' => '1509'
                )
            ),
            (int) 655 => array(
                'Aco' => array(
                    'id' => '671',
                    'parent_id' => '670',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'changelog',
                    'lft' => '1491',
                    'rght' => '1492'
                )
            ),
            (int) 656 => array(
                'Aco' => array(
                    'id' => '672',
                    'parent_id' => '670',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1493',
                    'rght' => '1494'
                )
            ),
            (int) 657 => array(
                'Aco' => array(
                    'id' => '673',
                    'parent_id' => '670',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1495',
                    'rght' => '1496'
                )
            ),
            (int) 658 => array(
                'Aco' => array(
                    'id' => '674',
                    'parent_id' => '670',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1497',
                    'rght' => '1498'
                )
            ),
            (int) 659 => array(
                'Aco' => array(
                    'id' => '675',
                    'parent_id' => '670',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1499',
                    'rght' => '1500'
                )
            ),
            (int) 660 => array(
                'Aco' => array(
                    'id' => '676',
                    'parent_id' => '670',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1501',
                    'rght' => '1502'
                )
            ),
            (int) 661 => array(
                'Aco' => array(
                    'id' => '677',
                    'parent_id' => '670',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1503',
                    'rght' => '1504'
                )
            ),
            (int) 662 => array(
                'Aco' => array(
                    'id' => '678',
                    'parent_id' => '670',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1505',
                    'rght' => '1506'
                )
            ),
            (int) 663 => array(
                'Aco' => array(
                    'id' => '679',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Systemdowntimes',
                    'lft' => '1510',
                    'rght' => '1537'
                )
            ),
            (int) 664 => array(
                'Aco' => array(
                    'id' => '680',
                    'parent_id' => '679',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1511',
                    'rght' => '1512'
                )
            ),
            (int) 665 => array(
                'Aco' => array(
                    'id' => '681',
                    'parent_id' => '679',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'addHostdowntime',
                    'lft' => '1513',
                    'rght' => '1514'
                )
            ),
            (int) 666 => array(
                'Aco' => array(
                    'id' => '682',
                    'parent_id' => '679',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'addHostgroupdowntime',
                    'lft' => '1515',
                    'rght' => '1516'
                )
            ),
            (int) 667 => array(
                'Aco' => array(
                    'id' => '683',
                    'parent_id' => '679',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'addServicedowntime',
                    'lft' => '1517',
                    'rght' => '1518'
                )
            ),
            (int) 668 => array(
                'Aco' => array(
                    'id' => '684',
                    'parent_id' => '679',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '1519',
                    'rght' => '1520'
                )
            ),
            (int) 669 => array(
                'Aco' => array(
                    'id' => '685',
                    'parent_id' => '679',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1521',
                    'rght' => '1522'
                )
            ),
            (int) 670 => array(
                'Aco' => array(
                    'id' => '686',
                    'parent_id' => '679',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1523',
                    'rght' => '1524'
                )
            ),
            (int) 671 => array(
                'Aco' => array(
                    'id' => '687',
                    'parent_id' => '679',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1525',
                    'rght' => '1526'
                )
            ),
            (int) 672 => array(
                'Aco' => array(
                    'id' => '688',
                    'parent_id' => '679',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1527',
                    'rght' => '1528'
                )
            ),
            (int) 673 => array(
                'Aco' => array(
                    'id' => '689',
                    'parent_id' => '679',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1529',
                    'rght' => '1530'
                )
            ),
            (int) 674 => array(
                'Aco' => array(
                    'id' => '690',
                    'parent_id' => '679',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1531',
                    'rght' => '1532'
                )
            ),
            (int) 675 => array(
                'Aco' => array(
                    'id' => '691',
                    'parent_id' => '679',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1533',
                    'rght' => '1534'
                )
            ),
            (int) 676 => array(
                'Aco' => array(
                    'id' => '692',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Systemfailures',
                    'lft' => '1538',
                    'rght' => '1561'
                )
            ),
            (int) 677 => array(
                'Aco' => array(
                    'id' => '693',
                    'parent_id' => '692',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1539',
                    'rght' => '1540'
                )
            ),
            (int) 678 => array(
                'Aco' => array(
                    'id' => '694',
                    'parent_id' => '692',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '1541',
                    'rght' => '1542'
                )
            ),
            (int) 679 => array(
                'Aco' => array(
                    'id' => '695',
                    'parent_id' => '692',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '1543',
                    'rght' => '1544'
                )
            ),
            (int) 680 => array(
                'Aco' => array(
                    'id' => '696',
                    'parent_id' => '692',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1545',
                    'rght' => '1546'
                )
            ),
            (int) 681 => array(
                'Aco' => array(
                    'id' => '697',
                    'parent_id' => '692',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1547',
                    'rght' => '1548'
                )
            ),
            (int) 682 => array(
                'Aco' => array(
                    'id' => '698',
                    'parent_id' => '692',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1549',
                    'rght' => '1550'
                )
            ),
            (int) 683 => array(
                'Aco' => array(
                    'id' => '699',
                    'parent_id' => '692',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1551',
                    'rght' => '1552'
                )
            ),
            (int) 684 => array(
                'Aco' => array(
                    'id' => '700',
                    'parent_id' => '692',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1553',
                    'rght' => '1554'
                )
            ),
            (int) 685 => array(
                'Aco' => array(
                    'id' => '701',
                    'parent_id' => '692',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1555',
                    'rght' => '1556'
                )
            ),
            (int) 686 => array(
                'Aco' => array(
                    'id' => '702',
                    'parent_id' => '692',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1557',
                    'rght' => '1558'
                )
            ),
            (int) 687 => array(
                'Aco' => array(
                    'id' => '703',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Systemsettings',
                    'lft' => '1562',
                    'rght' => '1581'
                )
            ),
            (int) 688 => array(
                'Aco' => array(
                    'id' => '704',
                    'parent_id' => '703',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1563',
                    'rght' => '1564'
                )
            ),
            (int) 689 => array(
                'Aco' => array(
                    'id' => '705',
                    'parent_id' => '703',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1565',
                    'rght' => '1566'
                )
            ),
            (int) 690 => array(
                'Aco' => array(
                    'id' => '706',
                    'parent_id' => '703',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1567',
                    'rght' => '1568'
                )
            ),
            (int) 691 => array(
                'Aco' => array(
                    'id' => '707',
                    'parent_id' => '703',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1569',
                    'rght' => '1570'
                )
            ),
            (int) 692 => array(
                'Aco' => array(
                    'id' => '708',
                    'parent_id' => '703',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1571',
                    'rght' => '1572'
                )
            ),
            (int) 693 => array(
                'Aco' => array(
                    'id' => '709',
                    'parent_id' => '703',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1573',
                    'rght' => '1574'
                )
            ),
            (int) 694 => array(
                'Aco' => array(
                    'id' => '710',
                    'parent_id' => '703',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1575',
                    'rght' => '1576'
                )
            ),
            (int) 695 => array(
                'Aco' => array(
                    'id' => '711',
                    'parent_id' => '703',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1577',
                    'rght' => '1578'
                )
            ),
            (int) 696 => array(
                'Aco' => array(
                    'id' => '712',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Tenants',
                    'lft' => '1582',
                    'rght' => '1611'
                )
            ),
            (int) 697 => array(
                'Aco' => array(
                    'id' => '713',
                    'parent_id' => '712',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1583',
                    'rght' => '1584'
                )
            ),
            (int) 698 => array(
                'Aco' => array(
                    'id' => '714',
                    'parent_id' => '712',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '1585',
                    'rght' => '1586'
                )
            ),
            (int) 699 => array(
                'Aco' => array(
                    'id' => '715',
                    'parent_id' => '712',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '1587',
                    'rght' => '1588'
                )
            ),
            (int) 700 => array(
                'Aco' => array(
                    'id' => '716',
                    'parent_id' => '712',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_delete',
                    'lft' => '1589',
                    'rght' => '1590'
                )
            ),
            (int) 701 => array(
                'Aco' => array(
                    'id' => '717',
                    'parent_id' => '712',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '1591',
                    'rght' => '1592'
                )
            ),
            (int) 702 => array(
                'Aco' => array(
                    'id' => '718',
                    'parent_id' => '712',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1593',
                    'rght' => '1594'
                )
            ),
            (int) 703 => array(
                'Aco' => array(
                    'id' => '719',
                    'parent_id' => '712',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1595',
                    'rght' => '1596'
                )
            ),
            (int) 704 => array(
                'Aco' => array(
                    'id' => '720',
                    'parent_id' => '712',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1597',
                    'rght' => '1598'
                )
            ),
            (int) 705 => array(
                'Aco' => array(
                    'id' => '721',
                    'parent_id' => '712',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1599',
                    'rght' => '1600'
                )
            ),
            (int) 706 => array(
                'Aco' => array(
                    'id' => '722',
                    'parent_id' => '712',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1601',
                    'rght' => '1602'
                )
            ),
            (int) 707 => array(
                'Aco' => array(
                    'id' => '723',
                    'parent_id' => '712',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1603',
                    'rght' => '1604'
                )
            ),
            (int) 708 => array(
                'Aco' => array(
                    'id' => '724',
                    'parent_id' => '712',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1605',
                    'rght' => '1606'
                )
            ),
            (int) 709 => array(
                'Aco' => array(
                    'id' => '725',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Timeperiods',
                    'lft' => '1612',
                    'rght' => '1647'
                )
            ),
            (int) 710 => array(
                'Aco' => array(
                    'id' => '726',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1613',
                    'rght' => '1614'
                )
            ),
            (int) 711 => array(
                'Aco' => array(
                    'id' => '727',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '1615',
                    'rght' => '1616'
                )
            ),
            (int) 712 => array(
                'Aco' => array(
                    'id' => '728',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '1617',
                    'rght' => '1618'
                )
            ),
            (int) 713 => array(
                'Aco' => array(
                    'id' => '729',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '1619',
                    'rght' => '1620'
                )
            ),
            (int) 714 => array(
                'Aco' => array(
                    'id' => '730',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_delete',
                    'lft' => '1621',
                    'rght' => '1622'
                )
            ),
            (int) 715 => array(
                'Aco' => array(
                    'id' => '731',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'browser',
                    'lft' => '1623',
                    'rght' => '1624'
                )
            ),
            (int) 716 => array(
                'Aco' => array(
                    'id' => '732',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'controller',
                    'lft' => '1625',
                    'rght' => '1626'
                )
            ),
            (int) 717 => array(
                'Aco' => array(
                    'id' => '733',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1627',
                    'rght' => '1628'
                )
            ),
            (int) 718 => array(
                'Aco' => array(
                    'id' => '734',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1629',
                    'rght' => '1630'
                )
            ),
            (int) 719 => array(
                'Aco' => array(
                    'id' => '735',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1631',
                    'rght' => '1632'
                )
            ),
            (int) 720 => array(
                'Aco' => array(
                    'id' => '736',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1633',
                    'rght' => '1634'
                )
            ),
            (int) 721 => array(
                'Aco' => array(
                    'id' => '737',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1635',
                    'rght' => '1636'
                )
            ),
            (int) 722 => array(
                'Aco' => array(
                    'id' => '738',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1637',
                    'rght' => '1638'
                )
            ),
            (int) 723 => array(
                'Aco' => array(
                    'id' => '739',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1639',
                    'rght' => '1640'
                )
            ),
            (int) 724 => array(
                'Aco' => array(
                    'id' => '740',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Usergroups',
                    'lft' => '1648',
                    'rght' => '1675'
                )
            ),
            (int) 725 => array(
                'Aco' => array(
                    'id' => '741',
                    'parent_id' => '740',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1649',
                    'rght' => '1650'
                )
            ),
            (int) 726 => array(
                'Aco' => array(
                    'id' => '742',
                    'parent_id' => '740',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '1651',
                    'rght' => '1652'
                )
            ),
            (int) 727 => array(
                'Aco' => array(
                    'id' => '743',
                    'parent_id' => '740',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '1653',
                    'rght' => '1654'
                )
            ),
            (int) 728 => array(
                'Aco' => array(
                    'id' => '744',
                    'parent_id' => '740',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '1655',
                    'rght' => '1656'
                )
            ),
            (int) 729 => array(
                'Aco' => array(
                    'id' => '745',
                    'parent_id' => '740',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1657',
                    'rght' => '1658'
                )
            ),
            (int) 730 => array(
                'Aco' => array(
                    'id' => '746',
                    'parent_id' => '740',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1659',
                    'rght' => '1660'
                )
            ),
            (int) 731 => array(
                'Aco' => array(
                    'id' => '747',
                    'parent_id' => '740',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1661',
                    'rght' => '1662'
                )
            ),
            (int) 732 => array(
                'Aco' => array(
                    'id' => '748',
                    'parent_id' => '740',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1663',
                    'rght' => '1664'
                )
            ),
            (int) 733 => array(
                'Aco' => array(
                    'id' => '749',
                    'parent_id' => '740',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1665',
                    'rght' => '1666'
                )
            ),
            (int) 734 => array(
                'Aco' => array(
                    'id' => '750',
                    'parent_id' => '740',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1667',
                    'rght' => '1668'
                )
            ),
            (int) 735 => array(
                'Aco' => array(
                    'id' => '751',
                    'parent_id' => '740',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1669',
                    'rght' => '1670'
                )
            ),
            (int) 736 => array(
                'Aco' => array(
                    'id' => '752',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Users',
                    'lft' => '1676',
                    'rght' => '1707'
                )
            ),
            (int) 737 => array(
                'Aco' => array(
                    'id' => '753',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1677',
                    'rght' => '1678'
                )
            ),
            (int) 738 => array(
                'Aco' => array(
                    'id' => '754',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'delete',
                    'lft' => '1679',
                    'rght' => '1680'
                )
            ),
            (int) 739 => array(
                'Aco' => array(
                    'id' => '755',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '1681',
                    'rght' => '1682'
                )
            ),
            (int) 740 => array(
                'Aco' => array(
                    'id' => '756',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'edit',
                    'lft' => '1683',
                    'rght' => '1684'
                )
            ),
            (int) 741 => array(
                'Aco' => array(
                    'id' => '757',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'addFromLdap',
                    'lft' => '1685',
                    'rght' => '1686'
                )
            ),
            (int) 742 => array(
                'Aco' => array(
                    'id' => '758',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'resetPassword',
                    'lft' => '1687',
                    'rght' => '1688'
                )
            ),
            (int) 743 => array(
                'Aco' => array(
                    'id' => '759',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1689',
                    'rght' => '1690'
                )
            ),
            (int) 744 => array(
                'Aco' => array(
                    'id' => '760',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1691',
                    'rght' => '1692'
                )
            ),
            (int) 745 => array(
                'Aco' => array(
                    'id' => '761',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1693',
                    'rght' => '1694'
                )
            ),
            (int) 746 => array(
                'Aco' => array(
                    'id' => '762',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1695',
                    'rght' => '1696'
                )
            ),
            (int) 747 => array(
                'Aco' => array(
                    'id' => '763',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1697',
                    'rght' => '1698'
                )
            ),
            (int) 748 => array(
                'Aco' => array(
                    'id' => '764',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1699',
                    'rght' => '1700'
                )
            ),
            (int) 749 => array(
                'Aco' => array(
                    'id' => '765',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1701',
                    'rght' => '1702'
                )
            ),
            (int) 750 => array(
                'Aco' => array(
                    'id' => '766',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'AclExtras',
                    'lft' => '1708',
                    'rght' => '1709'
                )
            ),
            (int) 751 => array(
                'Aco' => array(
                    'id' => '767',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Admin',
                    'lft' => '1710',
                    'rght' => '1711'
                )
            ),
            (int) 752 => array(
                'Aco' => array(
                    'id' => '809',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'BoostCake',
                    'lft' => '1712',
                    'rght' => '1737'
                )
            ),
            (int) 753 => array(
                'Aco' => array(
                    'id' => '810',
                    'parent_id' => '809',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'BoostCake',
                    'lft' => '1713',
                    'rght' => '1736'
                )
            ),
            (int) 754 => array(
                'Aco' => array(
                    'id' => '811',
                    'parent_id' => '810',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1714',
                    'rght' => '1715'
                )
            ),
            (int) 755 => array(
                'Aco' => array(
                    'id' => '812',
                    'parent_id' => '810',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'bootstrap2',
                    'lft' => '1716',
                    'rght' => '1717'
                )
            ),
            (int) 756 => array(
                'Aco' => array(
                    'id' => '813',
                    'parent_id' => '810',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'bootstrap3',
                    'lft' => '1718',
                    'rght' => '1719'
                )
            ),
            (int) 757 => array(
                'Aco' => array(
                    'id' => '814',
                    'parent_id' => '810',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1720',
                    'rght' => '1721'
                )
            ),
            (int) 758 => array(
                'Aco' => array(
                    'id' => '815',
                    'parent_id' => '810',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1722',
                    'rght' => '1723'
                )
            ),
            (int) 759 => array(
                'Aco' => array(
                    'id' => '816',
                    'parent_id' => '810',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1724',
                    'rght' => '1725'
                )
            ),
            (int) 760 => array(
                'Aco' => array(
                    'id' => '817',
                    'parent_id' => '810',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1726',
                    'rght' => '1727'
                )
            ),
            (int) 761 => array(
                'Aco' => array(
                    'id' => '818',
                    'parent_id' => '810',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1728',
                    'rght' => '1729'
                )
            ),
            (int) 762 => array(
                'Aco' => array(
                    'id' => '819',
                    'parent_id' => '810',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1730',
                    'rght' => '1731'
                )
            ),
            (int) 763 => array(
                'Aco' => array(
                    'id' => '820',
                    'parent_id' => '810',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1732',
                    'rght' => '1733'
                )
            ),
            (int) 764 => array(
                'Aco' => array(
                    'id' => '821',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'CakePdf',
                    'lft' => '1738',
                    'rght' => '1739'
                )
            ),
            (int) 765 => array(
                'Aco' => array(
                    'id' => '822',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'ChatModule',
                    'lft' => '1740',
                    'rght' => '1761'
                )
            ),
            (int) 766 => array(
                'Aco' => array(
                    'id' => '823',
                    'parent_id' => '822',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Chat',
                    'lft' => '1741',
                    'rght' => '1760'
                )
            ),
            (int) 767 => array(
                'Aco' => array(
                    'id' => '824',
                    'parent_id' => '823',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1742',
                    'rght' => '1743'
                )
            ),
            (int) 768 => array(
                'Aco' => array(
                    'id' => '825',
                    'parent_id' => '823',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1744',
                    'rght' => '1745'
                )
            ),
            (int) 769 => array(
                'Aco' => array(
                    'id' => '826',
                    'parent_id' => '823',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1746',
                    'rght' => '1747'
                )
            ),
            (int) 770 => array(
                'Aco' => array(
                    'id' => '827',
                    'parent_id' => '823',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1748',
                    'rght' => '1749'
                )
            ),
            (int) 771 => array(
                'Aco' => array(
                    'id' => '828',
                    'parent_id' => '823',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1750',
                    'rght' => '1751'
                )
            ),
            (int) 772 => array(
                'Aco' => array(
                    'id' => '829',
                    'parent_id' => '823',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1752',
                    'rght' => '1753'
                )
            ),
            (int) 773 => array(
                'Aco' => array(
                    'id' => '830',
                    'parent_id' => '823',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1754',
                    'rght' => '1755'
                )
            ),
            (int) 774 => array(
                'Aco' => array(
                    'id' => '831',
                    'parent_id' => '823',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1756',
                    'rght' => '1757'
                )
            ),
            (int) 775 => array(
                'Aco' => array(
                    'id' => '832',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'ClearCache',
                    'lft' => '1762',
                    'rght' => '1787'
                )
            ),
            (int) 776 => array(
                'Aco' => array(
                    'id' => '833',
                    'parent_id' => '832',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'ClearCache',
                    'lft' => '1763',
                    'rght' => '1786'
                )
            ),
            (int) 777 => array(
                'Aco' => array(
                    'id' => '834',
                    'parent_id' => '833',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'files',
                    'lft' => '1764',
                    'rght' => '1765'
                )
            ),
            (int) 778 => array(
                'Aco' => array(
                    'id' => '835',
                    'parent_id' => '833',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'engines',
                    'lft' => '1766',
                    'rght' => '1767'
                )
            ),
            (int) 779 => array(
                'Aco' => array(
                    'id' => '836',
                    'parent_id' => '833',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'groups',
                    'lft' => '1768',
                    'rght' => '1769'
                )
            ),
            (int) 780 => array(
                'Aco' => array(
                    'id' => '837',
                    'parent_id' => '833',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1770',
                    'rght' => '1771'
                )
            ),
            (int) 781 => array(
                'Aco' => array(
                    'id' => '838',
                    'parent_id' => '833',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1772',
                    'rght' => '1773'
                )
            ),
            (int) 782 => array(
                'Aco' => array(
                    'id' => '839',
                    'parent_id' => '833',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1774',
                    'rght' => '1775'
                )
            ),
            (int) 783 => array(
                'Aco' => array(
                    'id' => '840',
                    'parent_id' => '833',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1776',
                    'rght' => '1777'
                )
            ),
            (int) 784 => array(
                'Aco' => array(
                    'id' => '841',
                    'parent_id' => '833',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1778',
                    'rght' => '1779'
                )
            ),
            (int) 785 => array(
                'Aco' => array(
                    'id' => '842',
                    'parent_id' => '833',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1780',
                    'rght' => '1781'
                )
            ),
            (int) 786 => array(
                'Aco' => array(
                    'id' => '843',
                    'parent_id' => '833',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1782',
                    'rght' => '1783'
                )
            ),
            (int) 787 => array(
                'Aco' => array(
                    'id' => '844',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'DebugKit',
                    'lft' => '1788',
                    'rght' => '1811'
                )
            ),
            (int) 788 => array(
                'Aco' => array(
                    'id' => '845',
                    'parent_id' => '844',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'ToolbarAccess',
                    'lft' => '1789',
                    'rght' => '1810'
                )
            ),
            (int) 789 => array(
                'Aco' => array(
                    'id' => '846',
                    'parent_id' => '845',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'history_state',
                    'lft' => '1790',
                    'rght' => '1791'
                )
            ),
            (int) 790 => array(
                'Aco' => array(
                    'id' => '847',
                    'parent_id' => '845',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'sql_explain',
                    'lft' => '1792',
                    'rght' => '1793'
                )
            ),
            (int) 791 => array(
                'Aco' => array(
                    'id' => '848',
                    'parent_id' => '845',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1794',
                    'rght' => '1795'
                )
            ),
            (int) 792 => array(
                'Aco' => array(
                    'id' => '849',
                    'parent_id' => '845',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1796',
                    'rght' => '1797'
                )
            ),
            (int) 793 => array(
                'Aco' => array(
                    'id' => '850',
                    'parent_id' => '845',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1798',
                    'rght' => '1799'
                )
            ),
            (int) 794 => array(
                'Aco' => array(
                    'id' => '851',
                    'parent_id' => '845',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1800',
                    'rght' => '1801'
                )
            ),
            (int) 795 => array(
                'Aco' => array(
                    'id' => '852',
                    'parent_id' => '845',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1802',
                    'rght' => '1803'
                )
            ),
            (int) 796 => array(
                'Aco' => array(
                    'id' => '853',
                    'parent_id' => '845',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1804',
                    'rght' => '1805'
                )
            ),
            (int) 797 => array(
                'Aco' => array(
                    'id' => '854',
                    'parent_id' => '845',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1806',
                    'rght' => '1807'
                )
            ),
            (int) 798 => array(
                'Aco' => array(
                    'id' => '855',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'ExampleModule',
                    'lft' => '1812',
                    'rght' => '1813'
                )
            ),
            (int) 799 => array(
                'Aco' => array(
                    'id' => '856',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Frontend',
                    'lft' => '1814',
                    'rght' => '1835'
                )
            ),
            (int) 800 => array(
                'Aco' => array(
                    'id' => '857',
                    'parent_id' => '856',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'FrontendDependencies',
                    'lft' => '1815',
                    'rght' => '1834'
                )
            ),
            (int) 801 => array(
                'Aco' => array(
                    'id' => '858',
                    'parent_id' => '857',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1816',
                    'rght' => '1817'
                )
            ),
            (int) 802 => array(
                'Aco' => array(
                    'id' => '859',
                    'parent_id' => '857',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1818',
                    'rght' => '1819'
                )
            ),
            (int) 803 => array(
                'Aco' => array(
                    'id' => '860',
                    'parent_id' => '857',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1820',
                    'rght' => '1821'
                )
            ),
            (int) 804 => array(
                'Aco' => array(
                    'id' => '861',
                    'parent_id' => '857',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1822',
                    'rght' => '1823'
                )
            ),
            (int) 805 => array(
                'Aco' => array(
                    'id' => '862',
                    'parent_id' => '857',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1824',
                    'rght' => '1825'
                )
            ),
            (int) 806 => array(
                'Aco' => array(
                    'id' => '863',
                    'parent_id' => '857',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1826',
                    'rght' => '1827'
                )
            ),
            (int) 807 => array(
                'Aco' => array(
                    'id' => '864',
                    'parent_id' => '857',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1828',
                    'rght' => '1829'
                )
            ),
            (int) 808 => array(
                'Aco' => array(
                    'id' => '865',
                    'parent_id' => '857',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1830',
                    'rght' => '1831'
                )
            ),
            (int) 809 => array(
                'Aco' => array(
                    'id' => '866',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'ListFilter',
                    'lft' => '1836',
                    'rght' => '1837'
                )
            ),
            (int) 810 => array(
                'Aco' => array(
                    'id' => '867',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'NagiosModule',
                    'lft' => '1838',
                    'rght' => '1883'
                )
            ),
            (int) 811 => array(
                'Aco' => array(
                    'id' => '868',
                    'parent_id' => '867',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Cmd',
                    'lft' => '1839',
                    'rght' => '1862'
                )
            ),
            (int) 812 => array(
                'Aco' => array(
                    'id' => '869',
                    'parent_id' => '868',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1840',
                    'rght' => '1841'
                )
            ),
            (int) 813 => array(
                'Aco' => array(
                    'id' => '870',
                    'parent_id' => '868',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'submit',
                    'lft' => '1842',
                    'rght' => '1843'
                )
            ),
            (int) 814 => array(
                'Aco' => array(
                    'id' => '871',
                    'parent_id' => '868',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1844',
                    'rght' => '1845'
                )
            ),
            (int) 815 => array(
                'Aco' => array(
                    'id' => '872',
                    'parent_id' => '868',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1846',
                    'rght' => '1847'
                )
            ),
            (int) 816 => array(
                'Aco' => array(
                    'id' => '873',
                    'parent_id' => '868',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1848',
                    'rght' => '1849'
                )
            ),
            (int) 817 => array(
                'Aco' => array(
                    'id' => '874',
                    'parent_id' => '868',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1850',
                    'rght' => '1851'
                )
            ),
            (int) 818 => array(
                'Aco' => array(
                    'id' => '875',
                    'parent_id' => '868',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1852',
                    'rght' => '1853'
                )
            ),
            (int) 819 => array(
                'Aco' => array(
                    'id' => '876',
                    'parent_id' => '868',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1854',
                    'rght' => '1855'
                )
            ),
            (int) 820 => array(
                'Aco' => array(
                    'id' => '877',
                    'parent_id' => '868',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1856',
                    'rght' => '1857'
                )
            ),
            (int) 821 => array(
                'Aco' => array(
                    'id' => '878',
                    'parent_id' => '867',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Nagios',
                    'lft' => '1863',
                    'rght' => '1882'
                )
            ),
            (int) 822 => array(
                'Aco' => array(
                    'id' => '879',
                    'parent_id' => '878',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1864',
                    'rght' => '1865'
                )
            ),
            (int) 823 => array(
                'Aco' => array(
                    'id' => '880',
                    'parent_id' => '878',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1866',
                    'rght' => '1867'
                )
            ),
            (int) 824 => array(
                'Aco' => array(
                    'id' => '881',
                    'parent_id' => '878',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1868',
                    'rght' => '1869'
                )
            ),
            (int) 825 => array(
                'Aco' => array(
                    'id' => '882',
                    'parent_id' => '878',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1870',
                    'rght' => '1871'
                )
            ),
            (int) 826 => array(
                'Aco' => array(
                    'id' => '883',
                    'parent_id' => '878',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1872',
                    'rght' => '1873'
                )
            ),
            (int) 827 => array(
                'Aco' => array(
                    'id' => '884',
                    'parent_id' => '878',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1874',
                    'rght' => '1875'
                )
            ),
            (int) 828 => array(
                'Aco' => array(
                    'id' => '885',
                    'parent_id' => '878',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1876',
                    'rght' => '1877'
                )
            ),
            (int) 829 => array(
                'Aco' => array(
                    'id' => '886',
                    'parent_id' => '878',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1878',
                    'rght' => '1879'
                )
            ),
            (int) 830 => array(
                'Aco' => array(
                    'id' => '887',
                    'parent_id' => '12',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'testMail',
                    'lft' => '43',
                    'rght' => '44'
                )
            ),
            (int) 831 => array(
                'Aco' => array(
                    'id' => '888',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'addFromLdap',
                    'lft' => '277',
                    'rght' => '278'
                )
            ),
            (int) 832 => array(
                'Aco' => array(
                    'id' => '889',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Dashboards',
                    'lft' => '1884',
                    'rght' => '1955'
                )
            ),
            (int) 833 => array(
                'Aco' => array(
                    'id' => '890',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1885',
                    'rght' => '1886'
                )
            ),
            (int) 834 => array(
                'Aco' => array(
                    'id' => '891',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'next',
                    'lft' => '1887',
                    'rght' => '1888'
                )
            ),
            (int) 835 => array(
                'Aco' => array(
                    'id' => '892',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '1889',
                    'rght' => '1890'
                )
            ),
            (int) 836 => array(
                'Aco' => array(
                    'id' => '893',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'createTab',
                    'lft' => '1891',
                    'rght' => '1892'
                )
            ),
            (int) 837 => array(
                'Aco' => array(
                    'id' => '894',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'createTabFromSharing',
                    'lft' => '1893',
                    'rght' => '1894'
                )
            ),
            (int) 838 => array(
                'Aco' => array(
                    'id' => '895',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'updateSharedTab',
                    'lft' => '1895',
                    'rght' => '1896'
                )
            ),
            (int) 839 => array(
                'Aco' => array(
                    'id' => '896',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'disableUpdate',
                    'lft' => '1897',
                    'rght' => '1898'
                )
            ),
            (int) 840 => array(
                'Aco' => array(
                    'id' => '897',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'renameTab',
                    'lft' => '1899',
                    'rght' => '1900'
                )
            ),
            (int) 841 => array(
                'Aco' => array(
                    'id' => '898',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'deleteTab',
                    'lft' => '1901',
                    'rght' => '1902'
                )
            ),
            (int) 842 => array(
                'Aco' => array(
                    'id' => '899',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'restoreDefault',
                    'lft' => '1903',
                    'rght' => '1904'
                )
            ),
            (int) 843 => array(
                'Aco' => array(
                    'id' => '900',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'updateTitle',
                    'lft' => '1905',
                    'rght' => '1906'
                )
            ),
            (int) 844 => array(
                'Aco' => array(
                    'id' => '901',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'updateColor',
                    'lft' => '1907',
                    'rght' => '1908'
                )
            ),
            (int) 845 => array(
                'Aco' => array(
                    'id' => '902',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'updatePosition',
                    'lft' => '1909',
                    'rght' => '1910'
                )
            ),
            (int) 846 => array(
                'Aco' => array(
                    'id' => '903',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'deleteWidget',
                    'lft' => '1911',
                    'rght' => '1912'
                )
            ),
            (int) 847 => array(
                'Aco' => array(
                    'id' => '904',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'updateTabPosition',
                    'lft' => '1913',
                    'rght' => '1914'
                )
            ),
            (int) 848 => array(
                'Aco' => array(
                    'id' => '905',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'saveTabRotationInterval',
                    'lft' => '1915',
                    'rght' => '1916'
                )
            ),
            (int) 849 => array(
                'Aco' => array(
                    'id' => '906',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'startSharing',
                    'lft' => '1917',
                    'rght' => '1918'
                )
            ),
            (int) 850 => array(
                'Aco' => array(
                    'id' => '907',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'stopSharing',
                    'lft' => '1919',
                    'rght' => '1920'
                )
            ),
            (int) 851 => array(
                'Aco' => array(
                    'id' => '908',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'refresh',
                    'lft' => '1921',
                    'rght' => '1922'
                )
            ),
            (int) 852 => array(
                'Aco' => array(
                    'id' => '909',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'saveStatuslistSettings',
                    'lft' => '1923',
                    'rght' => '1924'
                )
            ),
            (int) 853 => array(
                'Aco' => array(
                    'id' => '910',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'saveTrafficLightService',
                    'lft' => '1925',
                    'rght' => '1926'
                )
            ),
            (int) 854 => array(
                'Aco' => array(
                    'id' => '911',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getTachoPerfdata',
                    'lft' => '1927',
                    'rght' => '1928'
                )
            ),
            (int) 855 => array(
                'Aco' => array(
                    'id' => '912',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'saveTachoConfig',
                    'lft' => '1929',
                    'rght' => '1930'
                )
            ),
            (int) 856 => array(
                'Aco' => array(
                    'id' => '913',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1931',
                    'rght' => '1932'
                )
            ),
            (int) 857 => array(
                'Aco' => array(
                    'id' => '914',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1933',
                    'rght' => '1934'
                )
            ),
            (int) 858 => array(
                'Aco' => array(
                    'id' => '915',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1935',
                    'rght' => '1936'
                )
            ),
            (int) 859 => array(
                'Aco' => array(
                    'id' => '916',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1937',
                    'rght' => '1938'
                )
            ),
            (int) 860 => array(
                'Aco' => array(
                    'id' => '917',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1939',
                    'rght' => '1940'
                )
            ),
            (int) 861 => array(
                'Aco' => array(
                    'id' => '918',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1941',
                    'rght' => '1942'
                )
            ),
            (int) 862 => array(
                'Aco' => array(
                    'id' => '919',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1943',
                    'rght' => '1944'
                )
            ),
            (int) 863 => array(
                'Aco' => array(
                    'id' => '920',
                    'parent_id' => '218',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'broadcast',
                    'lft' => '477',
                    'rght' => '478'
                )
            ),
            (int) 864 => array(
                'Aco' => array(
                    'id' => '921',
                    'parent_id' => '218',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'launchExport',
                    'lft' => '479',
                    'rght' => '480'
                )
            ),
            (int) 865 => array(
                'Aco' => array(
                    'id' => '922',
                    'parent_id' => '218',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'verifyConfig',
                    'lft' => '481',
                    'rght' => '482'
                )
            ),
            (int) 866 => array(
                'Aco' => array(
                    'id' => '923',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allocateToMatchingHostgroup',
                    'lft' => '1383',
                    'rght' => '1384'
                )
            ),
            (int) 867 => array(
                'Aco' => array(
                    'id' => '924',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '207',
                    'rght' => '208'
                )
            ),
            (int) 868 => array(
                'Aco' => array(
                    'id' => '925',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'usedBy',
                    'lft' => '209',
                    'rght' => '210'
                )
            ),
            (int) 869 => array(
                'Aco' => array(
                    'id' => '926',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '243',
                    'rght' => '244'
                )
            ),
            (int) 870 => array(
                'Aco' => array(
                    'id' => '927',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '279',
                    'rght' => '280'
                )
            ),
            (int) 871 => array(
                'Aco' => array(
                    'id' => '928',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'nest',
                    'lft' => '311',
                    'rght' => '312'
                )
            ),
            (int) 872 => array(
                'Aco' => array(
                    'id' => '929',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '313',
                    'rght' => '314'
                )
            ),
            (int) 873 => array(
                'Aco' => array(
                    'id' => '930',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'saveMapId',
                    'lft' => '1945',
                    'rght' => '1946'
                )
            ),
            (int) 874 => array(
                'Aco' => array(
                    'id' => '931',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'saveGraphId',
                    'lft' => '1947',
                    'rght' => '1948'
                )
            ),
            (int) 875 => array(
                'Aco' => array(
                    'id' => '932',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'saveNotice',
                    'lft' => '1949',
                    'rght' => '1950'
                )
            ),
            (int) 876 => array(
                'Aco' => array(
                    'id' => '933',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'saveMap',
                    'lft' => '1951',
                    'rght' => '1952'
                )
            ),
            (int) 877 => array(
                'Aco' => array(
                    'id' => '935',
                    'parent_id' => '236',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'add',
                    'lft' => '533',
                    'rght' => '534'
                )
            ),
            (int) 878 => array(
                'Aco' => array(
                    'id' => '936',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '571',
                    'rght' => '572'
                )
            ),
            (int) 879 => array(
                'Aco' => array(
                    'id' => '937',
                    'parent_id' => '275',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '621',
                    'rght' => '622'
                )
            ),
            (int) 880 => array(
                'Aco' => array(
                    'id' => '938',
                    'parent_id' => '288',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '651',
                    'rght' => '652'
                )
            ),
            (int) 881 => array(
                'Aco' => array(
                    'id' => '939',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '689',
                    'rght' => '690'
                )
            ),
            (int) 882 => array(
                'Aco' => array(
                    'id' => '940',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '773',
                    'rght' => '774'
                )
            ),
            (int) 883 => array(
                'Aco' => array(
                    'id' => '941',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allocateServiceTemplateGroup',
                    'lft' => '775',
                    'rght' => '776'
                )
            ),
            (int) 884 => array(
                'Aco' => array(
                    'id' => '942',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getServiceTemplatesfromGroup',
                    'lft' => '777',
                    'rght' => '778'
                )
            ),
            (int) 885 => array(
                'Aco' => array(
                    'id' => '943',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '817',
                    'rght' => '818'
                )
            ),
            (int) 886 => array(
                'Aco' => array(
                    'id' => '944',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_delete',
                    'lft' => '819',
                    'rght' => '820'
                )
            ),
            (int) 887 => array(
                'Aco' => array(
                    'id' => '945',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'copy',
                    'lft' => '821',
                    'rght' => '822'
                )
            ),
            (int) 888 => array(
                'Aco' => array(
                    'id' => '946',
                    'parent_id' => '384',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '873',
                    'rght' => '874'
                )
            ),
            (int) 889 => array(
                'Aco' => array(
                    'id' => '947',
                    'parent_id' => '529',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '1195',
                    'rght' => '1196'
                )
            ),
            (int) 890 => array(
                'Aco' => array(
                    'id' => '948',
                    'parent_id' => '542',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '1225',
                    'rght' => '1226'
                )
            ),
            (int) 891 => array(
                'Aco' => array(
                    'id' => '949',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '1261',
                    'rght' => '1262'
                )
            ),
            (int) 892 => array(
                'Aco' => array(
                    'id' => '950',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '1345',
                    'rght' => '1346'
                )
            ),
            (int) 893 => array(
                'Aco' => array(
                    'id' => '951',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getSelectedServices',
                    'lft' => '1347',
                    'rght' => '1348'
                )
            ),
            (int) 894 => array(
                'Aco' => array(
                    'id' => '955',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '1385',
                    'rght' => '1386'
                )
            ),
            (int) 895 => array(
                'Aco' => array(
                    'id' => '956',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '1431',
                    'rght' => '1432'
                )
            ),
            (int) 896 => array(
                'Aco' => array(
                    'id' => '957',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'mass_delete',
                    'lft' => '1433',
                    'rght' => '1434'
                )
            ),
            (int) 897 => array(
                'Aco' => array(
                    'id' => '958',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'copy',
                    'lft' => '1435',
                    'rght' => '1436'
                )
            ),
            (int) 898 => array(
                'Aco' => array(
                    'id' => '959',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'assignGroup',
                    'lft' => '1437',
                    'rght' => '1438'
                )
            ),
            (int) 899 => array(
                'Aco' => array(
                    'id' => '960',
                    'parent_id' => '712',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '1607',
                    'rght' => '1608'
                )
            ),
            (int) 900 => array(
                'Aco' => array(
                    'id' => '961',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '1641',
                    'rght' => '1642'
                )
            ),
            (int) 901 => array(
                'Aco' => array(
                    'id' => '962',
                    'parent_id' => '740',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '1671',
                    'rght' => '1672'
                )
            ),
            (int) 902 => array(
                'Aco' => array(
                    'id' => '963',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'view',
                    'lft' => '1703',
                    'rght' => '1704'
                )
            ),
            (int) 903 => array(
                'Aco' => array(
                    'id' => '964',
                    'parent_id' => '868',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'ack',
                    'lft' => '1858',
                    'rght' => '1859'
                )
            ),
            (int) 904 => array(
                'Aco' => array(
                    'id' => '965',
                    'parent_id' => '2',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '21',
                    'rght' => '22'
                )
            ),
            (int) 905 => array(
                'Aco' => array(
                    'id' => '966',
                    'parent_id' => '12',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '45',
                    'rght' => '46'
                )
            ),
            (int) 906 => array(
                'Aco' => array(
                    'id' => '967',
                    'parent_id' => '22',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '75',
                    'rght' => '76'
                )
            ),
            (int) 907 => array(
                'Aco' => array(
                    'id' => '968',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Backups',
                    'lft' => '1956',
                    'rght' => '1983'
                )
            ),
            (int) 908 => array(
                'Aco' => array(
                    'id' => '969',
                    'parent_id' => '968',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1957',
                    'rght' => '1958'
                )
            ),
            (int) 909 => array(
                'Aco' => array(
                    'id' => '970',
                    'parent_id' => '968',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'backup',
                    'lft' => '1959',
                    'rght' => '1960'
                )
            ),
            (int) 910 => array(
                'Aco' => array(
                    'id' => '971',
                    'parent_id' => '968',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'restore',
                    'lft' => '1961',
                    'rght' => '1962'
                )
            ),
            (int) 911 => array(
                'Aco' => array(
                    'id' => '972',
                    'parent_id' => '968',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1963',
                    'rght' => '1964'
                )
            ),
            (int) 912 => array(
                'Aco' => array(
                    'id' => '973',
                    'parent_id' => '968',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1965',
                    'rght' => '1966'
                )
            ),
            (int) 913 => array(
                'Aco' => array(
                    'id' => '974',
                    'parent_id' => '968',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1967',
                    'rght' => '1968'
                )
            ),
            (int) 914 => array(
                'Aco' => array(
                    'id' => '975',
                    'parent_id' => '968',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1969',
                    'rght' => '1970'
                )
            ),
            (int) 915 => array(
                'Aco' => array(
                    'id' => '976',
                    'parent_id' => '968',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1971',
                    'rght' => '1972'
                )
            ),
            (int) 916 => array(
                'Aco' => array(
                    'id' => '977',
                    'parent_id' => '968',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1973',
                    'rght' => '1974'
                )
            ),
            (int) 917 => array(
                'Aco' => array(
                    'id' => '978',
                    'parent_id' => '968',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '1975',
                    'rght' => '1976'
                )
            ),
            (int) 918 => array(
                'Aco' => array(
                    'id' => '979',
                    'parent_id' => '968',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1977',
                    'rght' => '1978'
                )
            ),
            (int) 919 => array(
                'Aco' => array(
                    'id' => '980',
                    'parent_id' => '36',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '97',
                    'rght' => '98'
                )
            ),
            (int) 920 => array(
                'Aco' => array(
                    'id' => '981',
                    'parent_id' => '49',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '127',
                    'rght' => '128'
                )
            ),
            (int) 921 => array(
                'Aco' => array(
                    'id' => '982',
                    'parent_id' => '63',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '147',
                    'rght' => '148'
                )
            ),
            (int) 922 => array(
                'Aco' => array(
                    'id' => '983',
                    'parent_id' => '72',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '167',
                    'rght' => '168'
                )
            ),
            (int) 923 => array(
                'Aco' => array(
                    'id' => '984',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '211',
                    'rght' => '212'
                )
            ),
            (int) 924 => array(
                'Aco' => array(
                    'id' => '985',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '245',
                    'rght' => '246'
                )
            ),
            (int) 925 => array(
                'Aco' => array(
                    'id' => '986',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '281',
                    'rght' => '282'
                )
            ),
            (int) 926 => array(
                'Aco' => array(
                    'id' => '987',
                    'parent_id' => '128',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '315',
                    'rght' => '316'
                )
            ),
            (int) 927 => array(
                'Aco' => array(
                    'id' => '988',
                    'parent_id' => '141',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '343',
                    'rght' => '344'
                )
            ),
            (int) 928 => array(
                'Aco' => array(
                    'id' => '989',
                    'parent_id' => '154',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '365',
                    'rght' => '366'
                )
            ),
            (int) 929 => array(
                'Aco' => array(
                    'id' => '990',
                    'parent_id' => '889',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1953',
                    'rght' => '1954'
                )
            ),
            (int) 930 => array(
                'Aco' => array(
                    'id' => '991',
                    'parent_id' => '164',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '385',
                    'rght' => '386'
                )
            ),
            (int) 931 => array(
                'Aco' => array(
                    'id' => '993',
                    'parent_id' => '185',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '409',
                    'rght' => '410'
                )
            ),
            (int) 932 => array(
                'Aco' => array(
                    'id' => '994',
                    'parent_id' => '196',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '431',
                    'rght' => '432'
                )
            ),
            (int) 933 => array(
                'Aco' => array(
                    'id' => '995',
                    'parent_id' => '206',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '457',
                    'rght' => '458'
                )
            ),
            (int) 934 => array(
                'Aco' => array(
                    'id' => '996',
                    'parent_id' => '218',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '483',
                    'rght' => '484'
                )
            ),
            (int) 935 => array(
                'Aco' => array(
                    'id' => '997',
                    'parent_id' => '227',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '505',
                    'rght' => '506'
                )
            ),
            (int) 936 => array(
                'Aco' => array(
                    'id' => '998',
                    'parent_id' => '236',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '535',
                    'rght' => '536'
                )
            ),
            (int) 937 => array(
                'Aco' => array(
                    'id' => '999',
                    'parent_id' => '249',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '573',
                    'rght' => '574'
                )
            ),
            (int) 938 => array(
                'Aco' => array(
                    'id' => '1000',
                    'parent_id' => '266',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '593',
                    'rght' => '594'
                )
            ),
            (int) 939 => array(
                'Aco' => array(
                    'id' => '1001',
                    'parent_id' => '275',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '623',
                    'rght' => '624'
                )
            ),
            (int) 940 => array(
                'Aco' => array(
                    'id' => '1002',
                    'parent_id' => '288',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '653',
                    'rght' => '654'
                )
            ),
            (int) 941 => array(
                'Aco' => array(
                    'id' => '1003',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadServicesByHostId',
                    'lft' => '691',
                    'rght' => '692'
                )
            ),
            (int) 942 => array(
                'Aco' => array(
                    'id' => '1004',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '693',
                    'rght' => '694'
                )
            ),
            (int) 943 => array(
                'Aco' => array(
                    'id' => '1005',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getSharingContainers',
                    'lft' => '779',
                    'rght' => '780'
                )
            ),
            (int) 944 => array(
                'Aco' => array(
                    'id' => '1006',
                    'parent_id' => '318',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '781',
                    'rght' => '782'
                )
            ),
            (int) 945 => array(
                'Aco' => array(
                    'id' => '1007',
                    'parent_id' => '356',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '823',
                    'rght' => '824'
                )
            ),
            (int) 946 => array(
                'Aco' => array(
                    'id' => '1008',
                    'parent_id' => '373',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '847',
                    'rght' => '848'
                )
            ),
            (int) 947 => array(
                'Aco' => array(
                    'id' => '1009',
                    'parent_id' => '384',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '875',
                    'rght' => '876'
                )
            ),
            (int) 948 => array(
                'Aco' => array(
                    'id' => '1010',
                    'parent_id' => '396',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '895',
                    'rght' => '896'
                )
            ),
            (int) 949 => array(
                'Aco' => array(
                    'id' => '1011',
                    'parent_id' => '405',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '925',
                    'rght' => '926'
                )
            ),
            (int) 950 => array(
                'Aco' => array(
                    'id' => '1012',
                    'parent_id' => '419',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '947',
                    'rght' => '948'
                )
            ),
            (int) 951 => array(
                'Aco' => array(
                    'id' => '1013',
                    'parent_id' => '429',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '967',
                    'rght' => '968'
                )
            ),
            (int) 952 => array(
                'Aco' => array(
                    'id' => '1014',
                    'parent_id' => '438',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '991',
                    'rght' => '992'
                )
            ),
            (int) 953 => array(
                'Aco' => array(
                    'id' => '1015',
                    'parent_id' => '449',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1013',
                    'rght' => '1014'
                )
            ),
            (int) 954 => array(
                'Aco' => array(
                    'id' => '1016',
                    'parent_id' => '459',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1035',
                    'rght' => '1036'
                )
            ),
            (int) 955 => array(
                'Aco' => array(
                    'id' => '1017',
                    'parent_id' => '469',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1059',
                    'rght' => '1060'
                )
            ),
            (int) 956 => array(
                'Aco' => array(
                    'id' => '1018',
                    'parent_id' => '480',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1079',
                    'rght' => '1080'
                )
            ),
            (int) 957 => array(
                'Aco' => array(
                    'id' => '1019',
                    'parent_id' => '489',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1101',
                    'rght' => '1102'
                )
            ),
            (int) 958 => array(
                'Aco' => array(
                    'id' => '1020',
                    'parent_id' => '499',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1123',
                    'rght' => '1124'
                )
            ),
            (int) 959 => array(
                'Aco' => array(
                    'id' => '1021',
                    'parent_id' => '509',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1147',
                    'rght' => '1148'
                )
            ),
            (int) 960 => array(
                'Aco' => array(
                    'id' => '1022',
                    'parent_id' => '520',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1167',
                    'rght' => '1168'
                )
            ),
            (int) 961 => array(
                'Aco' => array(
                    'id' => '1023',
                    'parent_id' => '529',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1197',
                    'rght' => '1198'
                )
            ),
            (int) 962 => array(
                'Aco' => array(
                    'id' => '1024',
                    'parent_id' => '542',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1227',
                    'rght' => '1228'
                )
            ),
            (int) 963 => array(
                'Aco' => array(
                    'id' => '1025',
                    'parent_id' => '555',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1263',
                    'rght' => '1264'
                )
            ),
            (int) 964 => array(
                'Aco' => array(
                    'id' => '1026',
                    'parent_id' => '571',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1349',
                    'rght' => '1350'
                )
            ),
            (int) 965 => array(
                'Aco' => array(
                    'id' => '1027',
                    'parent_id' => '611',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1387',
                    'rght' => '1388'
                )
            ),
            (int) 966 => array(
                'Aco' => array(
                    'id' => '1028',
                    'parent_id' => '627',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1439',
                    'rght' => '1440'
                )
            ),
            (int) 967 => array(
                'Aco' => array(
                    'id' => '1029',
                    'parent_id' => '648',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1461',
                    'rght' => '1462'
                )
            ),
            (int) 968 => array(
                'Aco' => array(
                    'id' => '1030',
                    'parent_id' => '658',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1487',
                    'rght' => '1488'
                )
            ),
            (int) 969 => array(
                'Aco' => array(
                    'id' => '1031',
                    'parent_id' => '670',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1507',
                    'rght' => '1508'
                )
            ),
            (int) 970 => array(
                'Aco' => array(
                    'id' => '1032',
                    'parent_id' => '679',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1535',
                    'rght' => '1536'
                )
            ),
            (int) 971 => array(
                'Aco' => array(
                    'id' => '1033',
                    'parent_id' => '692',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1559',
                    'rght' => '1560'
                )
            ),
            (int) 972 => array(
                'Aco' => array(
                    'id' => '1034',
                    'parent_id' => '703',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1579',
                    'rght' => '1580'
                )
            ),
            (int) 973 => array(
                'Aco' => array(
                    'id' => '1035',
                    'parent_id' => '712',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1609',
                    'rght' => '1610'
                )
            ),
            (int) 974 => array(
                'Aco' => array(
                    'id' => '1036',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1643',
                    'rght' => '1644'
                )
            ),
            (int) 975 => array(
                'Aco' => array(
                    'id' => '1037',
                    'parent_id' => '740',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1673',
                    'rght' => '1674'
                )
            ),
            (int) 976 => array(
                'Aco' => array(
                    'id' => '1038',
                    'parent_id' => '752',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1705',
                    'rght' => '1706'
                )
            ),
            (int) 977 => array(
                'Aco' => array(
                    'id' => '1039',
                    'parent_id' => '810',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1734',
                    'rght' => '1735'
                )
            ),
            (int) 978 => array(
                'Aco' => array(
                    'id' => '1040',
                    'parent_id' => '823',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1758',
                    'rght' => '1759'
                )
            ),
            (int) 979 => array(
                'Aco' => array(
                    'id' => '1041',
                    'parent_id' => '833',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1784',
                    'rght' => '1785'
                )
            ),
            (int) 980 => array(
                'Aco' => array(
                    'id' => '1042',
                    'parent_id' => '845',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1808',
                    'rght' => '1809'
                )
            ),
            (int) 981 => array(
                'Aco' => array(
                    'id' => '1043',
                    'parent_id' => '857',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1832',
                    'rght' => '1833'
                )
            ),
            (int) 982 => array(
                'Aco' => array(
                    'id' => '1044',
                    'parent_id' => '868',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1860',
                    'rght' => '1861'
                )
            ),
            (int) 983 => array(
                'Aco' => array(
                    'id' => '1045',
                    'parent_id' => '878',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '1880',
                    'rght' => '1881'
                )
            ),
            (int) 984 => array(
                'Aco' => array(
                    'id' => '1046',
                    'parent_id' => '81',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'copy',
                    'lft' => '213',
                    'rght' => '214'
                )
            ),
            (int) 985 => array(
                'Aco' => array(
                    'id' => '1047',
                    'parent_id' => '100',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'copy',
                    'lft' => '247',
                    'rght' => '248'
                )
            ),
            (int) 986 => array(
                'Aco' => array(
                    'id' => '1048',
                    'parent_id' => '114',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'copy',
                    'lft' => '283',
                    'rght' => '284'
                )
            ),
            (int) 987 => array(
                'Aco' => array(
                    'id' => '1050',
                    'parent_id' => '725',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'copy',
                    'lft' => '1645',
                    'rght' => '1646'
                )
            ),
            (int) 988 => array(
                'Aco' => array(
                    'id' => '1129',
                    'parent_id' => '1',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'Supports',
                    'lft' => '1984',
                    'rght' => '2005'
                )
            ),
            (int) 989 => array(
                'Aco' => array(
                    'id' => '1130',
                    'parent_id' => '1129',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'index',
                    'lft' => '1985',
                    'rght' => '1986'
                )
            ),
            (int) 990 => array(
                'Aco' => array(
                    'id' => '1131',
                    'parent_id' => '1129',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'issue',
                    'lft' => '1987',
                    'rght' => '1988'
                )
            ),
            (int) 991 => array(
                'Aco' => array(
                    'id' => '1132',
                    'parent_id' => '1129',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'isAuthorized',
                    'lft' => '1989',
                    'rght' => '1990'
                )
            ),
            (int) 992 => array(
                'Aco' => array(
                    'id' => '1133',
                    'parent_id' => '1129',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'flashBack',
                    'lft' => '1991',
                    'rght' => '1992'
                )
            ),
            (int) 993 => array(
                'Aco' => array(
                    'id' => '1134',
                    'parent_id' => '1129',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'setFlash',
                    'lft' => '1993',
                    'rght' => '1994'
                )
            ),
            (int) 994 => array(
                'Aco' => array(
                    'id' => '1135',
                    'parent_id' => '1129',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'serviceResponse',
                    'lft' => '1995',
                    'rght' => '1996'
                )
            ),
            (int) 995 => array(
                'Aco' => array(
                    'id' => '1136',
                    'parent_id' => '1129',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'getNamedParameter',
                    'lft' => '1997',
                    'rght' => '1998'
                )
            ),
            (int) 996 => array(
                'Aco' => array(
                    'id' => '1137',
                    'parent_id' => '1129',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'allowedByContainerId',
                    'lft' => '1999',
                    'rght' => '2000'
                )
            ),
            (int) 997 => array(
                'Aco' => array(
                    'id' => '1138',
                    'parent_id' => '1129',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'render403',
                    'lft' => '2001',
                    'rght' => '2002'
                )
            ),
            (int) 998 => array(
                'Aco' => array(
                    'id' => '1139',
                    'parent_id' => '1129',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkForUpdates',
                    'lft' => '2003',
                    'rght' => '2004'
                )
            ),
            (int) 999 => array(
                'Aco' => array(
                    'id' => '1140',
                    'parent_id' => '968',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'checkBackupFinished',
                    'lft' => '1979',
                    'rght' => '1980'
                )
            ),
            (int) 1000 => array(
                'Aco' => array(
                    'id' => '1141',
                    'parent_id' => '968',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'deleteBackupFile',
                    'lft' => '1981',
                    'rght' => '1982'
                )
            ),
            (int) 1001 => array(
                'Aco' => array(
                    'id' => '1142',
                    'parent_id' => '218',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'saveInstanceConfigSyncSelection',
                    'lft' => '485',
                    'rght' => '486'
                )
            ),
            (int) 1002 => array(
                'Aco' => array(
                    'id' => '1143',
                    'parent_id' => '301',
                    'model' => null,
                    'foreign_key' => null,
                    'alias' => 'loadHosttemplates',
                    'lft' => '695',
                    'rght' => '696'
                )
            )
        );

        return $data;
    }
}