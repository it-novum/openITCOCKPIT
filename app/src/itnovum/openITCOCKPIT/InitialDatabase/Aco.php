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


class Aco extends Importer
{

	/**
	 * @property \Aco $Model
	 */

	/**
	 * @return bool
	 */
	public function import()
	{
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
	public function getData()
	{
		$data = array(
			0 =>
				array(
					'Aco' =>
						array(
							'id' => '1',
							'parent_id' => NULL,
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'controllers',
							'lft' => '1',
							'rght' => '1840',
						),
				),
			1 =>
				array(
					'Aco' =>
						array(
							'id' => '2',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Acknowledgements',
							'lft' => '2',
							'rght' => '21',
						),
				),
			2 =>
				array(
					'Aco' =>
						array(
							'id' => '3',
							'parent_id' => '2',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'service',
							'lft' => '3',
							'rght' => '4',
						),
				),
			3 =>
				array(
					'Aco' =>
						array(
							'id' => '4',
							'parent_id' => '2',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'host',
							'lft' => '5',
							'rght' => '6',
						),
				),
			4 =>
				array(
					'Aco' =>
						array(
							'id' => '5',
							'parent_id' => '2',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '7',
							'rght' => '8',
						),
				),
			5 =>
				array(
					'Aco' =>
						array(
							'id' => '6',
							'parent_id' => '2',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '9',
							'rght' => '10',
						),
				),
			6 =>
				array(
					'Aco' =>
						array(
							'id' => '7',
							'parent_id' => '2',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '11',
							'rght' => '12',
						),
				),
			7 =>
				array(
					'Aco' =>
						array(
							'id' => '8',
							'parent_id' => '2',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '13',
							'rght' => '14',
						),
				),
			8 =>
				array(
					'Aco' =>
						array(
							'id' => '9',
							'parent_id' => '2',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '15',
							'rght' => '16',
						),
				),
			9 =>
				array(
					'Aco' =>
						array(
							'id' => '10',
							'parent_id' => '2',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '17',
							'rght' => '18',
						),
				),
			10 =>
				array(
					'Aco' =>
						array(
							'id' => '11',
							'parent_id' => '2',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '19',
							'rght' => '20',
						),
				),
			11 =>
				array(
					'Aco' =>
						array(
							'id' => '12',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Administrators',
							'lft' => '22',
							'rght' => '43',
						),
				),
			12 =>
				array(
					'Aco' =>
						array(
							'id' => '13',
							'parent_id' => '12',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '23',
							'rght' => '24',
						),
				),
			13 =>
				array(
					'Aco' =>
						array(
							'id' => '14',
							'parent_id' => '12',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'debug',
							'lft' => '25',
							'rght' => '26',
						),
				),
			14 =>
				array(
					'Aco' =>
						array(
							'id' => '15',
							'parent_id' => '12',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '27',
							'rght' => '28',
						),
				),
			15 =>
				array(
					'Aco' =>
						array(
							'id' => '16',
							'parent_id' => '12',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '29',
							'rght' => '30',
						),
				),
			16 =>
				array(
					'Aco' =>
						array(
							'id' => '17',
							'parent_id' => '12',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '31',
							'rght' => '32',
						),
				),
			17 =>
				array(
					'Aco' =>
						array(
							'id' => '18',
							'parent_id' => '12',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '33',
							'rght' => '34',
						),
				),
			18 =>
				array(
					'Aco' =>
						array(
							'id' => '19',
							'parent_id' => '12',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '35',
							'rght' => '36',
						),
				),
			19 =>
				array(
					'Aco' =>
						array(
							'id' => '20',
							'parent_id' => '12',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '37',
							'rght' => '38',
						),
				),
			20 =>
				array(
					'Aco' =>
						array(
							'id' => '21',
							'parent_id' => '12',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '39',
							'rght' => '40',
						),
				),
			21 =>
				array(
					'Aco' =>
						array(
							'id' => '22',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Automaps',
							'lft' => '44',
							'rght' => '71',
						),
				),
			22 =>
				array(
					'Aco' =>
						array(
							'id' => '23',
							'parent_id' => '22',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '45',
							'rght' => '46',
						),
				),
			23 =>
				array(
					'Aco' =>
						array(
							'id' => '24',
							'parent_id' => '22',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '47',
							'rght' => '48',
						),
				),
			24 =>
				array(
					'Aco' =>
						array(
							'id' => '25',
							'parent_id' => '22',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '49',
							'rght' => '50',
						),
				),
			25 =>
				array(
					'Aco' =>
						array(
							'id' => '26',
							'parent_id' => '22',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '51',
							'rght' => '52',
						),
				),
			26 =>
				array(
					'Aco' =>
						array(
							'id' => '27',
							'parent_id' => '22',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadServiceDetails',
							'lft' => '53',
							'rght' => '54',
						),
				),
			27 =>
				array(
					'Aco' =>
						array(
							'id' => '28',
							'parent_id' => '22',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '55',
							'rght' => '56',
						),
				),
			28 =>
				array(
					'Aco' =>
						array(
							'id' => '29',
							'parent_id' => '22',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '57',
							'rght' => '58',
						),
				),
			29 =>
				array(
					'Aco' =>
						array(
							'id' => '30',
							'parent_id' => '22',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '59',
							'rght' => '60',
						),
				),
			30 =>
				array(
					'Aco' =>
						array(
							'id' => '31',
							'parent_id' => '22',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '61',
							'rght' => '62',
						),
				),
			31 =>
				array(
					'Aco' =>
						array(
							'id' => '32',
							'parent_id' => '22',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '63',
							'rght' => '64',
						),
				),
			32 =>
				array(
					'Aco' =>
						array(
							'id' => '33',
							'parent_id' => '22',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '65',
							'rght' => '66',
						),
				),
			33 =>
				array(
					'Aco' =>
						array(
							'id' => '34',
							'parent_id' => '22',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '67',
							'rght' => '68',
						),
				),
			34 =>
				array(
					'Aco' =>
						array(
							'id' => '35',
							'parent_id' => '22',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '69',
							'rght' => '70',
						),
				),
			35 =>
				array(
					'Aco' =>
						array(
							'id' => '36',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Browsers',
							'lft' => '72',
							'rght' => '91',
						),
				),
			36 =>
				array(
					'Aco' =>
						array(
							'id' => '37',
							'parent_id' => '36',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '73',
							'rght' => '74',
						),
				),
			37 =>
				array(
					'Aco' =>
						array(
							'id' => '38',
							'parent_id' => '36',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'tenantBrowser',
							'lft' => '75',
							'rght' => '76',
						),
				),
			38 =>
				array(
					'Aco' =>
						array(
							'id' => '42',
							'parent_id' => '36',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '77',
							'rght' => '78',
						),
				),
			39 =>
				array(
					'Aco' =>
						array(
							'id' => '43',
							'parent_id' => '36',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '79',
							'rght' => '80',
						),
				),
			40 =>
				array(
					'Aco' =>
						array(
							'id' => '44',
							'parent_id' => '36',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '81',
							'rght' => '82',
						),
				),
			41 =>
				array(
					'Aco' =>
						array(
							'id' => '45',
							'parent_id' => '36',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '83',
							'rght' => '84',
						),
				),
			42 =>
				array(
					'Aco' =>
						array(
							'id' => '46',
							'parent_id' => '36',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '85',
							'rght' => '86',
						),
				),
			43 =>
				array(
					'Aco' =>
						array(
							'id' => '47',
							'parent_id' => '36',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '87',
							'rght' => '88',
						),
				),
			44 =>
				array(
					'Aco' =>
						array(
							'id' => '48',
							'parent_id' => '36',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '89',
							'rght' => '90',
						),
				),
			45 =>
				array(
					'Aco' =>
						array(
							'id' => '49',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Calendars',
							'lft' => '92',
							'rght' => '119',
						),
				),
			46 =>
				array(
					'Aco' =>
						array(
							'id' => '50',
							'parent_id' => '49',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '93',
							'rght' => '94',
						),
				),
			47 =>
				array(
					'Aco' =>
						array(
							'id' => '51',
							'parent_id' => '49',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '95',
							'rght' => '96',
						),
				),
			48 =>
				array(
					'Aco' =>
						array(
							'id' => '52',
							'parent_id' => '49',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '97',
							'rght' => '98',
						),
				),
			49 =>
				array(
					'Aco' =>
						array(
							'id' => '53',
							'parent_id' => '49',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '99',
							'rght' => '100',
						),
				),
			50 =>
				array(
					'Aco' =>
						array(
							'id' => '54',
							'parent_id' => '49',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadHolidays',
							'lft' => '101',
							'rght' => '102',
						),
				),
			51 =>
				array(
					'Aco' =>
						array(
							'id' => '55',
							'parent_id' => '49',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_delete',
							'lft' => '103',
							'rght' => '104',
						),
				),
			52 =>
				array(
					'Aco' =>
						array(
							'id' => '56',
							'parent_id' => '49',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '105',
							'rght' => '106',
						),
				),
			53 =>
				array(
					'Aco' =>
						array(
							'id' => '57',
							'parent_id' => '49',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '107',
							'rght' => '108',
						),
				),
			54 =>
				array(
					'Aco' =>
						array(
							'id' => '58',
							'parent_id' => '49',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '109',
							'rght' => '110',
						),
				),
			55 =>
				array(
					'Aco' =>
						array(
							'id' => '59',
							'parent_id' => '49',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '111',
							'rght' => '112',
						),
				),
			56 =>
				array(
					'Aco' =>
						array(
							'id' => '60',
							'parent_id' => '49',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '113',
							'rght' => '114',
						),
				),
			57 =>
				array(
					'Aco' =>
						array(
							'id' => '61',
							'parent_id' => '49',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '115',
							'rght' => '116',
						),
				),
			58 =>
				array(
					'Aco' =>
						array(
							'id' => '62',
							'parent_id' => '49',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '117',
							'rght' => '118',
						),
				),
			59 =>
				array(
					'Aco' =>
						array(
							'id' => '63',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Category',
							'lft' => '120',
							'rght' => '137',
						),
				),
			60 =>
				array(
					'Aco' =>
						array(
							'id' => '64',
							'parent_id' => '63',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '121',
							'rght' => '122',
						),
				),
			61 =>
				array(
					'Aco' =>
						array(
							'id' => '65',
							'parent_id' => '63',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '123',
							'rght' => '124',
						),
				),
			62 =>
				array(
					'Aco' =>
						array(
							'id' => '66',
							'parent_id' => '63',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '125',
							'rght' => '126',
						),
				),
			63 =>
				array(
					'Aco' =>
						array(
							'id' => '67',
							'parent_id' => '63',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '127',
							'rght' => '128',
						),
				),
			64 =>
				array(
					'Aco' =>
						array(
							'id' => '68',
							'parent_id' => '63',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '129',
							'rght' => '130',
						),
				),
			65 =>
				array(
					'Aco' =>
						array(
							'id' => '69',
							'parent_id' => '63',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '131',
							'rght' => '132',
						),
				),
			66 =>
				array(
					'Aco' =>
						array(
							'id' => '70',
							'parent_id' => '63',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '133',
							'rght' => '134',
						),
				),
			67 =>
				array(
					'Aco' =>
						array(
							'id' => '71',
							'parent_id' => '63',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '135',
							'rght' => '136',
						),
				),
			68 =>
				array(
					'Aco' =>
						array(
							'id' => '72',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Changelogs',
							'lft' => '138',
							'rght' => '155',
						),
				),
			69 =>
				array(
					'Aco' =>
						array(
							'id' => '73',
							'parent_id' => '72',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '139',
							'rght' => '140',
						),
				),
			70 =>
				array(
					'Aco' =>
						array(
							'id' => '74',
							'parent_id' => '72',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '141',
							'rght' => '142',
						),
				),
			71 =>
				array(
					'Aco' =>
						array(
							'id' => '75',
							'parent_id' => '72',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '143',
							'rght' => '144',
						),
				),
			72 =>
				array(
					'Aco' =>
						array(
							'id' => '76',
							'parent_id' => '72',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '145',
							'rght' => '146',
						),
				),
			73 =>
				array(
					'Aco' =>
						array(
							'id' => '77',
							'parent_id' => '72',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '147',
							'rght' => '148',
						),
				),
			74 =>
				array(
					'Aco' =>
						array(
							'id' => '78',
							'parent_id' => '72',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '149',
							'rght' => '150',
						),
				),
			75 =>
				array(
					'Aco' =>
						array(
							'id' => '79',
							'parent_id' => '72',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '151',
							'rght' => '152',
						),
				),
			76 =>
				array(
					'Aco' =>
						array(
							'id' => '80',
							'parent_id' => '72',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '153',
							'rght' => '154',
						),
				),
			77 =>
				array(
					'Aco' =>
						array(
							'id' => '81',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Commands',
							'lft' => '156',
							'rght' => '197',
						),
				),
			78 =>
				array(
					'Aco' =>
						array(
							'id' => '82',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '157',
							'rght' => '158',
						),
				),
			79 =>
				array(
					'Aco' =>
						array(
							'id' => '83',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'hostchecks',
							'lft' => '159',
							'rght' => '160',
						),
				),
			80 =>
				array(
					'Aco' =>
						array(
							'id' => '84',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'notifications',
							'lft' => '161',
							'rght' => '162',
						),
				),
			81 =>
				array(
					'Aco' =>
						array(
							'id' => '85',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'handler',
							'lft' => '163',
							'rght' => '164',
						),
				),
			82 =>
				array(
					'Aco' =>
						array(
							'id' => '86',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '165',
							'rght' => '166',
						),
				),
			83 =>
				array(
					'Aco' =>
						array(
							'id' => '87',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '167',
							'rght' => '168',
						),
				),
			84 =>
				array(
					'Aco' =>
						array(
							'id' => '88',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '169',
							'rght' => '170',
						),
				),
			85 =>
				array(
					'Aco' =>
						array(
							'id' => '89',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_delete',
							'lft' => '171',
							'rght' => '172',
						),
				),
			86 =>
				array(
					'Aco' =>
						array(
							'id' => '90',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'addCommandArg',
							'lft' => '173',
							'rght' => '174',
						),
				),
			87 =>
				array(
					'Aco' =>
						array(
							'id' => '91',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadMacros',
							'lft' => '175',
							'rght' => '176',
						),
				),
			88 =>
				array(
					'Aco' =>
						array(
							'id' => '92',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'terminal',
							'lft' => '177',
							'rght' => '178',
						),
				),
			89 =>
				array(
					'Aco' =>
						array(
							'id' => '93',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '179',
							'rght' => '180',
						),
				),
			90 =>
				array(
					'Aco' =>
						array(
							'id' => '94',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '181',
							'rght' => '182',
						),
				),
			91 =>
				array(
					'Aco' =>
						array(
							'id' => '95',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '183',
							'rght' => '184',
						),
				),
			92 =>
				array(
					'Aco' =>
						array(
							'id' => '96',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '185',
							'rght' => '186',
						),
				),
			93 =>
				array(
					'Aco' =>
						array(
							'id' => '97',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '187',
							'rght' => '188',
						),
				),
			94 =>
				array(
					'Aco' =>
						array(
							'id' => '98',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '189',
							'rght' => '190',
						),
				),
			95 =>
				array(
					'Aco' =>
						array(
							'id' => '99',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '191',
							'rght' => '192',
						),
				),
			96 =>
				array(
					'Aco' =>
						array(
							'id' => '100',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Contactgroups',
							'lft' => '198',
							'rght' => '227',
						),
				),
			97 =>
				array(
					'Aco' =>
						array(
							'id' => '101',
							'parent_id' => '100',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '199',
							'rght' => '200',
						),
				),
			98 =>
				array(
					'Aco' =>
						array(
							'id' => '102',
							'parent_id' => '100',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '201',
							'rght' => '202',
						),
				),
			99 =>
				array(
					'Aco' =>
						array(
							'id' => '103',
							'parent_id' => '100',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '203',
							'rght' => '204',
						),
				),
			100 =>
				array(
					'Aco' =>
						array(
							'id' => '104',
							'parent_id' => '100',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadContacts',
							'lft' => '205',
							'rght' => '206',
						),
				),
			101 =>
				array(
					'Aco' =>
						array(
							'id' => '105',
							'parent_id' => '100',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '207',
							'rght' => '208',
						),
				),
			102 =>
				array(
					'Aco' =>
						array(
							'id' => '106',
							'parent_id' => '100',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_delete',
							'lft' => '209',
							'rght' => '210',
						),
				),
			103 =>
				array(
					'Aco' =>
						array(
							'id' => '107',
							'parent_id' => '100',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '211',
							'rght' => '212',
						),
				),
			104 =>
				array(
					'Aco' =>
						array(
							'id' => '108',
							'parent_id' => '100',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '213',
							'rght' => '214',
						),
				),
			105 =>
				array(
					'Aco' =>
						array(
							'id' => '109',
							'parent_id' => '100',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '215',
							'rght' => '216',
						),
				),
			106 =>
				array(
					'Aco' =>
						array(
							'id' => '110',
							'parent_id' => '100',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '217',
							'rght' => '218',
						),
				),
			107 =>
				array(
					'Aco' =>
						array(
							'id' => '111',
							'parent_id' => '100',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '219',
							'rght' => '220',
						),
				),
			108 =>
				array(
					'Aco' =>
						array(
							'id' => '112',
							'parent_id' => '100',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '221',
							'rght' => '222',
						),
				),
			109 =>
				array(
					'Aco' =>
						array(
							'id' => '113',
							'parent_id' => '100',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '223',
							'rght' => '224',
						),
				),
			110 =>
				array(
					'Aco' =>
						array(
							'id' => '114',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Contacts',
							'lft' => '228',
							'rght' => '259',
						),
				),
			111 =>
				array(
					'Aco' =>
						array(
							'id' => '115',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '229',
							'rght' => '230',
						),
				),
			112 =>
				array(
					'Aco' =>
						array(
							'id' => '116',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '231',
							'rght' => '232',
						),
				),
			113 =>
				array(
					'Aco' =>
						array(
							'id' => '117',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '233',
							'rght' => '234',
						),
				),
			114 =>
				array(
					'Aco' =>
						array(
							'id' => '118',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '235',
							'rght' => '236',
						),
				),
			115 =>
				array(
					'Aco' =>
						array(
							'id' => '119',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_delete',
							'lft' => '237',
							'rght' => '238',
						),
				),
			116 =>
				array(
					'Aco' =>
						array(
							'id' => '120',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadTimeperiods',
							'lft' => '239',
							'rght' => '240',
						),
				),
			117 =>
				array(
					'Aco' =>
						array(
							'id' => '121',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '241',
							'rght' => '242',
						),
				),
			118 =>
				array(
					'Aco' =>
						array(
							'id' => '122',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '243',
							'rght' => '244',
						),
				),
			119 =>
				array(
					'Aco' =>
						array(
							'id' => '123',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '245',
							'rght' => '246',
						),
				),
			120 =>
				array(
					'Aco' =>
						array(
							'id' => '124',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '247',
							'rght' => '248',
						),
				),
			121 =>
				array(
					'Aco' =>
						array(
							'id' => '125',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '249',
							'rght' => '250',
						),
				),
			122 =>
				array(
					'Aco' =>
						array(
							'id' => '126',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '251',
							'rght' => '252',
						),
				),
			123 =>
				array(
					'Aco' =>
						array(
							'id' => '127',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '253',
							'rght' => '254',
						),
				),
			124 =>
				array(
					'Aco' =>
						array(
							'id' => '128',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Containers',
							'lft' => '260',
							'rght' => '289',
						),
				),
			125 =>
				array(
					'Aco' =>
						array(
							'id' => '129',
							'parent_id' => '128',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '261',
							'rght' => '262',
						),
				),
			126 =>
				array(
					'Aco' =>
						array(
							'id' => '130',
							'parent_id' => '128',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '263',
							'rght' => '264',
						),
				),
			127 =>
				array(
					'Aco' =>
						array(
							'id' => '131',
							'parent_id' => '128',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'byTenant',
							'lft' => '265',
							'rght' => '266',
						),
				),
			128 =>
				array(
					'Aco' =>
						array(
							'id' => '132',
							'parent_id' => '128',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'byTenantForSelect',
							'lft' => '267',
							'rght' => '268',
						),
				),
			129 =>
				array(
					'Aco' =>
						array(
							'id' => '133',
							'parent_id' => '128',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '269',
							'rght' => '270',
						),
				),
			130 =>
				array(
					'Aco' =>
						array(
							'id' => '134',
							'parent_id' => '128',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '271',
							'rght' => '272',
						),
				),
			131 =>
				array(
					'Aco' =>
						array(
							'id' => '135',
							'parent_id' => '128',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '273',
							'rght' => '274',
						),
				),
			132 =>
				array(
					'Aco' =>
						array(
							'id' => '136',
							'parent_id' => '128',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '275',
							'rght' => '276',
						),
				),
			133 =>
				array(
					'Aco' =>
						array(
							'id' => '137',
							'parent_id' => '128',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '277',
							'rght' => '278',
						),
				),
			134 =>
				array(
					'Aco' =>
						array(
							'id' => '138',
							'parent_id' => '128',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '279',
							'rght' => '280',
						),
				),
			135 =>
				array(
					'Aco' =>
						array(
							'id' => '139',
							'parent_id' => '128',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '281',
							'rght' => '282',
						),
				),
			136 =>
				array(
					'Aco' =>
						array(
							'id' => '140',
							'parent_id' => '128',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '283',
							'rght' => '284',
						),
				),
			137 =>
				array(
					'Aco' =>
						array(
							'id' => '141',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Cronjobs',
							'lft' => '290',
							'rght' => '315',
						),
				),
			138 =>
				array(
					'Aco' =>
						array(
							'id' => '142',
							'parent_id' => '141',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '291',
							'rght' => '292',
						),
				),
			139 =>
				array(
					'Aco' =>
						array(
							'id' => '143',
							'parent_id' => '141',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '293',
							'rght' => '294',
						),
				),
			140 =>
				array(
					'Aco' =>
						array(
							'id' => '144',
							'parent_id' => '141',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '295',
							'rght' => '296',
						),
				),
			141 =>
				array(
					'Aco' =>
						array(
							'id' => '145',
							'parent_id' => '141',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '297',
							'rght' => '298',
						),
				),
			142 =>
				array(
					'Aco' =>
						array(
							'id' => '146',
							'parent_id' => '141',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadTasksByPlugin',
							'lft' => '299',
							'rght' => '300',
						),
				),
			143 =>
				array(
					'Aco' =>
						array(
							'id' => '147',
							'parent_id' => '141',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '301',
							'rght' => '302',
						),
				),
			144 =>
				array(
					'Aco' =>
						array(
							'id' => '148',
							'parent_id' => '141',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '303',
							'rght' => '304',
						),
				),
			145 =>
				array(
					'Aco' =>
						array(
							'id' => '149',
							'parent_id' => '141',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '305',
							'rght' => '306',
						),
				),
			146 =>
				array(
					'Aco' =>
						array(
							'id' => '150',
							'parent_id' => '141',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '307',
							'rght' => '308',
						),
				),
			147 =>
				array(
					'Aco' =>
						array(
							'id' => '151',
							'parent_id' => '141',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '309',
							'rght' => '310',
						),
				),
			148 =>
				array(
					'Aco' =>
						array(
							'id' => '152',
							'parent_id' => '141',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '311',
							'rght' => '312',
						),
				),
			149 =>
				array(
					'Aco' =>
						array(
							'id' => '153',
							'parent_id' => '141',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '313',
							'rght' => '314',
						),
				),
			150 =>
				array(
					'Aco' =>
						array(
							'id' => '154',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Currentstatereports',
							'lft' => '316',
							'rght' => '335',
						),
				),
			151 =>
				array(
					'Aco' =>
						array(
							'id' => '155',
							'parent_id' => '154',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '317',
							'rght' => '318',
						),
				),
			152 =>
				array(
					'Aco' =>
						array(
							'id' => '156',
							'parent_id' => '154',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'createPdfReport',
							'lft' => '319',
							'rght' => '320',
						),
				),
			153 =>
				array(
					'Aco' =>
						array(
							'id' => '157',
							'parent_id' => '154',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '321',
							'rght' => '322',
						),
				),
			154 =>
				array(
					'Aco' =>
						array(
							'id' => '158',
							'parent_id' => '154',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '323',
							'rght' => '324',
						),
				),
			155 =>
				array(
					'Aco' =>
						array(
							'id' => '159',
							'parent_id' => '154',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '325',
							'rght' => '326',
						),
				),
			156 =>
				array(
					'Aco' =>
						array(
							'id' => '160',
							'parent_id' => '154',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '327',
							'rght' => '328',
						),
				),
			157 =>
				array(
					'Aco' =>
						array(
							'id' => '161',
							'parent_id' => '154',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '329',
							'rght' => '330',
						),
				),
			158 =>
				array(
					'Aco' =>
						array(
							'id' => '162',
							'parent_id' => '154',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '331',
							'rght' => '332',
						),
				),
			159 =>
				array(
					'Aco' =>
						array(
							'id' => '163',
							'parent_id' => '154',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '333',
							'rght' => '334',
						),
				),
			160 =>
				array(
					'Aco' =>
						array(
							'id' => '164',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'DeletedHosts',
							'lft' => '336',
							'rght' => '353',
						),
				),
			161 =>
				array(
					'Aco' =>
						array(
							'id' => '165',
							'parent_id' => '164',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '337',
							'rght' => '338',
						),
				),
			162 =>
				array(
					'Aco' =>
						array(
							'id' => '166',
							'parent_id' => '164',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '339',
							'rght' => '340',
						),
				),
			163 =>
				array(
					'Aco' =>
						array(
							'id' => '167',
							'parent_id' => '164',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '341',
							'rght' => '342',
						),
				),
			164 =>
				array(
					'Aco' =>
						array(
							'id' => '168',
							'parent_id' => '164',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '343',
							'rght' => '344',
						),
				),
			165 =>
				array(
					'Aco' =>
						array(
							'id' => '169',
							'parent_id' => '164',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '345',
							'rght' => '346',
						),
				),
			166 =>
				array(
					'Aco' =>
						array(
							'id' => '170',
							'parent_id' => '164',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '347',
							'rght' => '348',
						),
				),
			167 =>
				array(
					'Aco' =>
						array(
							'id' => '171',
							'parent_id' => '164',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '349',
							'rght' => '350',
						),
				),
			168 =>
				array(
					'Aco' =>
						array(
							'id' => '172',
							'parent_id' => '164',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '351',
							'rght' => '352',
						),
				),
			169 =>
				array(
					'Aco' =>
						array(
							'id' => '173',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Devicegroups',
							'lft' => '354',
							'rght' => '379',
						),
				),
			170 =>
				array(
					'Aco' =>
						array(
							'id' => '174',
							'parent_id' => '173',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '355',
							'rght' => '356',
						),
				),
			171 =>
				array(
					'Aco' =>
						array(
							'id' => '175',
							'parent_id' => '173',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '357',
							'rght' => '358',
						),
				),
			172 =>
				array(
					'Aco' =>
						array(
							'id' => '176',
							'parent_id' => '173',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '359',
							'rght' => '360',
						),
				),
			173 =>
				array(
					'Aco' =>
						array(
							'id' => '177',
							'parent_id' => '173',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '361',
							'rght' => '362',
						),
				),
			174 =>
				array(
					'Aco' =>
						array(
							'id' => '178',
							'parent_id' => '173',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '363',
							'rght' => '364',
						),
				),
			175 =>
				array(
					'Aco' =>
						array(
							'id' => '179',
							'parent_id' => '173',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '365',
							'rght' => '366',
						),
				),
			176 =>
				array(
					'Aco' =>
						array(
							'id' => '180',
							'parent_id' => '173',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '367',
							'rght' => '368',
						),
				),
			177 =>
				array(
					'Aco' =>
						array(
							'id' => '181',
							'parent_id' => '173',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '369',
							'rght' => '370',
						),
				),
			178 =>
				array(
					'Aco' =>
						array(
							'id' => '182',
							'parent_id' => '173',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '371',
							'rght' => '372',
						),
				),
			179 =>
				array(
					'Aco' =>
						array(
							'id' => '183',
							'parent_id' => '173',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '373',
							'rght' => '374',
						),
				),
			180 =>
				array(
					'Aco' =>
						array(
							'id' => '184',
							'parent_id' => '173',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '375',
							'rght' => '376',
						),
				),
			181 =>
				array(
					'Aco' =>
						array(
							'id' => '185',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Documentations',
							'lft' => '380',
							'rght' => '401',
						),
				),
			182 =>
				array(
					'Aco' =>
						array(
							'id' => '186',
							'parent_id' => '185',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '381',
							'rght' => '382',
						),
				),
			183 =>
				array(
					'Aco' =>
						array(
							'id' => '187',
							'parent_id' => '185',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '383',
							'rght' => '384',
						),
				),
			184 =>
				array(
					'Aco' =>
						array(
							'id' => '188',
							'parent_id' => '185',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'wiki',
							'lft' => '385',
							'rght' => '386',
						),
				),
			185 =>
				array(
					'Aco' =>
						array(
							'id' => '189',
							'parent_id' => '185',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '387',
							'rght' => '388',
						),
				),
			186 =>
				array(
					'Aco' =>
						array(
							'id' => '190',
							'parent_id' => '185',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '389',
							'rght' => '390',
						),
				),
			187 =>
				array(
					'Aco' =>
						array(
							'id' => '191',
							'parent_id' => '185',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '391',
							'rght' => '392',
						),
				),
			188 =>
				array(
					'Aco' =>
						array(
							'id' => '192',
							'parent_id' => '185',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '393',
							'rght' => '394',
						),
				),
			189 =>
				array(
					'Aco' =>
						array(
							'id' => '193',
							'parent_id' => '185',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '395',
							'rght' => '396',
						),
				),
			190 =>
				array(
					'Aco' =>
						array(
							'id' => '194',
							'parent_id' => '185',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '397',
							'rght' => '398',
						),
				),
			191 =>
				array(
					'Aco' =>
						array(
							'id' => '195',
							'parent_id' => '185',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '399',
							'rght' => '400',
						),
				),
			192 =>
				array(
					'Aco' =>
						array(
							'id' => '196',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Downtimereports',
							'lft' => '402',
							'rght' => '421',
						),
				),
			193 =>
				array(
					'Aco' =>
						array(
							'id' => '197',
							'parent_id' => '196',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '403',
							'rght' => '404',
						),
				),
			194 =>
				array(
					'Aco' =>
						array(
							'id' => '198',
							'parent_id' => '196',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'createPdfReport',
							'lft' => '405',
							'rght' => '406',
						),
				),
			195 =>
				array(
					'Aco' =>
						array(
							'id' => '199',
							'parent_id' => '196',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '407',
							'rght' => '408',
						),
				),
			196 =>
				array(
					'Aco' =>
						array(
							'id' => '200',
							'parent_id' => '196',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '409',
							'rght' => '410',
						),
				),
			197 =>
				array(
					'Aco' =>
						array(
							'id' => '201',
							'parent_id' => '196',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '411',
							'rght' => '412',
						),
				),
			198 =>
				array(
					'Aco' =>
						array(
							'id' => '202',
							'parent_id' => '196',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '413',
							'rght' => '414',
						),
				),
			199 =>
				array(
					'Aco' =>
						array(
							'id' => '203',
							'parent_id' => '196',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '415',
							'rght' => '416',
						),
				),
			200 =>
				array(
					'Aco' =>
						array(
							'id' => '204',
							'parent_id' => '196',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '417',
							'rght' => '418',
						),
				),
			201 =>
				array(
					'Aco' =>
						array(
							'id' => '205',
							'parent_id' => '196',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '419',
							'rght' => '420',
						),
				),
			202 =>
				array(
					'Aco' =>
						array(
							'id' => '206',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Downtimes',
							'lft' => '422',
							'rght' => '445',
						),
				),
			203 =>
				array(
					'Aco' =>
						array(
							'id' => '207',
							'parent_id' => '206',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'host',
							'lft' => '423',
							'rght' => '424',
						),
				),
			204 =>
				array(
					'Aco' =>
						array(
							'id' => '208',
							'parent_id' => '206',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'service',
							'lft' => '425',
							'rght' => '426',
						),
				),
			205 =>
				array(
					'Aco' =>
						array(
							'id' => '209',
							'parent_id' => '206',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '427',
							'rght' => '428',
						),
				),
			206 =>
				array(
					'Aco' =>
						array(
							'id' => '210',
							'parent_id' => '206',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'validateDowntimeInputFromBrowser',
							'lft' => '429',
							'rght' => '430',
						),
				),
			207 =>
				array(
					'Aco' =>
						array(
							'id' => '211',
							'parent_id' => '206',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '431',
							'rght' => '432',
						),
				),
			208 =>
				array(
					'Aco' =>
						array(
							'id' => '212',
							'parent_id' => '206',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '433',
							'rght' => '434',
						),
				),
			209 =>
				array(
					'Aco' =>
						array(
							'id' => '213',
							'parent_id' => '206',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '435',
							'rght' => '436',
						),
				),
			210 =>
				array(
					'Aco' =>
						array(
							'id' => '214',
							'parent_id' => '206',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '437',
							'rght' => '438',
						),
				),
			211 =>
				array(
					'Aco' =>
						array(
							'id' => '215',
							'parent_id' => '206',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '439',
							'rght' => '440',
						),
				),
			212 =>
				array(
					'Aco' =>
						array(
							'id' => '216',
							'parent_id' => '206',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '441',
							'rght' => '442',
						),
				),
			213 =>
				array(
					'Aco' =>
						array(
							'id' => '217',
							'parent_id' => '206',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '443',
							'rght' => '444',
						),
				),
			214 =>
				array(
					'Aco' =>
						array(
							'id' => '218',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Exports',
							'lft' => '446',
							'rght' => '469',
						),
				),
			215 =>
				array(
					'Aco' =>
						array(
							'id' => '219',
							'parent_id' => '218',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '447',
							'rght' => '448',
						),
				),
			216 =>
				array(
					'Aco' =>
						array(
							'id' => '220',
							'parent_id' => '218',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '449',
							'rght' => '450',
						),
				),
			217 =>
				array(
					'Aco' =>
						array(
							'id' => '221',
							'parent_id' => '218',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '451',
							'rght' => '452',
						),
				),
			218 =>
				array(
					'Aco' =>
						array(
							'id' => '222',
							'parent_id' => '218',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '453',
							'rght' => '454',
						),
				),
			219 =>
				array(
					'Aco' =>
						array(
							'id' => '223',
							'parent_id' => '218',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '455',
							'rght' => '456',
						),
				),
			220 =>
				array(
					'Aco' =>
						array(
							'id' => '224',
							'parent_id' => '218',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '457',
							'rght' => '458',
						),
				),
			221 =>
				array(
					'Aco' =>
						array(
							'id' => '225',
							'parent_id' => '218',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '459',
							'rght' => '460',
						),
				),
			222 =>
				array(
					'Aco' =>
						array(
							'id' => '226',
							'parent_id' => '218',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '461',
							'rght' => '462',
						),
				),
			223 =>
				array(
					'Aco' =>
						array(
							'id' => '227',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Forward',
							'lft' => '470',
							'rght' => '487',
						),
				),
			224 =>
				array(
					'Aco' =>
						array(
							'id' => '228',
							'parent_id' => '227',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '471',
							'rght' => '472',
						),
				),
			225 =>
				array(
					'Aco' =>
						array(
							'id' => '229',
							'parent_id' => '227',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '473',
							'rght' => '474',
						),
				),
			226 =>
				array(
					'Aco' =>
						array(
							'id' => '230',
							'parent_id' => '227',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '475',
							'rght' => '476',
						),
				),
			227 =>
				array(
					'Aco' =>
						array(
							'id' => '231',
							'parent_id' => '227',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '477',
							'rght' => '478',
						),
				),
			228 =>
				array(
					'Aco' =>
						array(
							'id' => '232',
							'parent_id' => '227',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '479',
							'rght' => '480',
						),
				),
			229 =>
				array(
					'Aco' =>
						array(
							'id' => '233',
							'parent_id' => '227',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '481',
							'rght' => '482',
						),
				),
			230 =>
				array(
					'Aco' =>
						array(
							'id' => '234',
							'parent_id' => '227',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '483',
							'rght' => '484',
						),
				),
			231 =>
				array(
					'Aco' =>
						array(
							'id' => '235',
							'parent_id' => '227',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '485',
							'rght' => '486',
						),
				),
			232 =>
				array(
					'Aco' =>
						array(
							'id' => '236',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'GraphCollections',
							'lft' => '488',
							'rght' => '515',
						),
				),
			233 =>
				array(
					'Aco' =>
						array(
							'id' => '237',
							'parent_id' => '236',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '489',
							'rght' => '490',
						),
				),
			234 =>
				array(
					'Aco' =>
						array(
							'id' => '238',
							'parent_id' => '236',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '491',
							'rght' => '492',
						),
				),
			235 =>
				array(
					'Aco' =>
						array(
							'id' => '239',
							'parent_id' => '236',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'display',
							'lft' => '493',
							'rght' => '494',
						),
				),
			236 =>
				array(
					'Aco' =>
						array(
							'id' => '240',
							'parent_id' => '236',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_delete',
							'lft' => '495',
							'rght' => '496',
						),
				),
			237 =>
				array(
					'Aco' =>
						array(
							'id' => '241',
							'parent_id' => '236',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadCollectionGraphData',
							'lft' => '497',
							'rght' => '498',
						),
				),
			238 =>
				array(
					'Aco' =>
						array(
							'id' => '242',
							'parent_id' => '236',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '499',
							'rght' => '500',
						),
				),
			239 =>
				array(
					'Aco' =>
						array(
							'id' => '243',
							'parent_id' => '236',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '501',
							'rght' => '502',
						),
				),
			240 =>
				array(
					'Aco' =>
						array(
							'id' => '244',
							'parent_id' => '236',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '503',
							'rght' => '504',
						),
				),
			241 =>
				array(
					'Aco' =>
						array(
							'id' => '245',
							'parent_id' => '236',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '505',
							'rght' => '506',
						),
				),
			242 =>
				array(
					'Aco' =>
						array(
							'id' => '246',
							'parent_id' => '236',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '507',
							'rght' => '508',
						),
				),
			243 =>
				array(
					'Aco' =>
						array(
							'id' => '247',
							'parent_id' => '236',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '509',
							'rght' => '510',
						),
				),
			244 =>
				array(
					'Aco' =>
						array(
							'id' => '248',
							'parent_id' => '236',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '511',
							'rght' => '512',
						),
				),
			245 =>
				array(
					'Aco' =>
						array(
							'id' => '249',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Graphgenerators',
							'lft' => '516',
							'rght' => '551',
						),
				),
			246 =>
				array(
					'Aco' =>
						array(
							'id' => '250',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '517',
							'rght' => '518',
						),
				),
			247 =>
				array(
					'Aco' =>
						array(
							'id' => '251',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'listing',
							'lft' => '519',
							'rght' => '520',
						),
				),
			248 =>
				array(
					'Aco' =>
						array(
							'id' => '252',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_delete',
							'lft' => '521',
							'rght' => '522',
						),
				),
			249 =>
				array(
					'Aco' =>
						array(
							'id' => '253',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'saveGraphTemplate',
							'lft' => '523',
							'rght' => '524',
						),
				),
			250 =>
				array(
					'Aco' =>
						array(
							'id' => '254',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadGraphTemplate',
							'lft' => '525',
							'rght' => '526',
						),
				),
			251 =>
				array(
					'Aco' =>
						array(
							'id' => '255',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadServicesByHostId',
							'lft' => '527',
							'rght' => '528',
						),
				),
			252 =>
				array(
					'Aco' =>
						array(
							'id' => '256',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadPerfDataStructures',
							'lft' => '529',
							'rght' => '530',
						),
				),
			253 =>
				array(
					'Aco' =>
						array(
							'id' => '257',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadServiceruleFromService',
							'lft' => '531',
							'rght' => '532',
						),
				),
			254 =>
				array(
					'Aco' =>
						array(
							'id' => '258',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'fetchGraphData',
							'lft' => '533',
							'rght' => '534',
						),
				),
			255 =>
				array(
					'Aco' =>
						array(
							'id' => '259',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '535',
							'rght' => '536',
						),
				),
			256 =>
				array(
					'Aco' =>
						array(
							'id' => '260',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '537',
							'rght' => '538',
						),
				),
			257 =>
				array(
					'Aco' =>
						array(
							'id' => '261',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '539',
							'rght' => '540',
						),
				),
			258 =>
				array(
					'Aco' =>
						array(
							'id' => '262',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '541',
							'rght' => '542',
						),
				),
			259 =>
				array(
					'Aco' =>
						array(
							'id' => '263',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '543',
							'rght' => '544',
						),
				),
			260 =>
				array(
					'Aco' =>
						array(
							'id' => '264',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '545',
							'rght' => '546',
						),
				),
			261 =>
				array(
					'Aco' =>
						array(
							'id' => '265',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '547',
							'rght' => '548',
						),
				),
			262 =>
				array(
					'Aco' =>
						array(
							'id' => '266',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Hostchecks',
							'lft' => '552',
							'rght' => '569',
						),
				),
			263 =>
				array(
					'Aco' =>
						array(
							'id' => '267',
							'parent_id' => '266',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '553',
							'rght' => '554',
						),
				),
			264 =>
				array(
					'Aco' =>
						array(
							'id' => '268',
							'parent_id' => '266',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '555',
							'rght' => '556',
						),
				),
			265 =>
				array(
					'Aco' =>
						array(
							'id' => '269',
							'parent_id' => '266',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '557',
							'rght' => '558',
						),
				),
			266 =>
				array(
					'Aco' =>
						array(
							'id' => '270',
							'parent_id' => '266',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '559',
							'rght' => '560',
						),
				),
			267 =>
				array(
					'Aco' =>
						array(
							'id' => '271',
							'parent_id' => '266',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '561',
							'rght' => '562',
						),
				),
			268 =>
				array(
					'Aco' =>
						array(
							'id' => '272',
							'parent_id' => '266',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '563',
							'rght' => '564',
						),
				),
			269 =>
				array(
					'Aco' =>
						array(
							'id' => '273',
							'parent_id' => '266',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '565',
							'rght' => '566',
						),
				),
			270 =>
				array(
					'Aco' =>
						array(
							'id' => '274',
							'parent_id' => '266',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '567',
							'rght' => '568',
						),
				),
			271 =>
				array(
					'Aco' =>
						array(
							'id' => '275',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Hostdependencies',
							'lft' => '570',
							'rght' => '597',
						),
				),
			272 =>
				array(
					'Aco' =>
						array(
							'id' => '276',
							'parent_id' => '275',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '571',
							'rght' => '572',
						),
				),
			273 =>
				array(
					'Aco' =>
						array(
							'id' => '277',
							'parent_id' => '275',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '573',
							'rght' => '574',
						),
				),
			274 =>
				array(
					'Aco' =>
						array(
							'id' => '278',
							'parent_id' => '275',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '575',
							'rght' => '576',
						),
				),
			275 =>
				array(
					'Aco' =>
						array(
							'id' => '279',
							'parent_id' => '275',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '577',
							'rght' => '578',
						),
				),
			276 =>
				array(
					'Aco' =>
						array(
							'id' => '280',
							'parent_id' => '275',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadElementsByContainerId',
							'lft' => '579',
							'rght' => '580',
						),
				),
			277 =>
				array(
					'Aco' =>
						array(
							'id' => '281',
							'parent_id' => '275',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '581',
							'rght' => '582',
						),
				),
			278 =>
				array(
					'Aco' =>
						array(
							'id' => '282',
							'parent_id' => '275',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '583',
							'rght' => '584',
						),
				),
			279 =>
				array(
					'Aco' =>
						array(
							'id' => '283',
							'parent_id' => '275',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '585',
							'rght' => '586',
						),
				),
			280 =>
				array(
					'Aco' =>
						array(
							'id' => '284',
							'parent_id' => '275',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '587',
							'rght' => '588',
						),
				),
			281 =>
				array(
					'Aco' =>
						array(
							'id' => '285',
							'parent_id' => '275',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '589',
							'rght' => '590',
						),
				),
			282 =>
				array(
					'Aco' =>
						array(
							'id' => '286',
							'parent_id' => '275',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '591',
							'rght' => '592',
						),
				),
			283 =>
				array(
					'Aco' =>
						array(
							'id' => '287',
							'parent_id' => '275',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '593',
							'rght' => '594',
						),
				),
			284 =>
				array(
					'Aco' =>
						array(
							'id' => '288',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Hostescalations',
							'lft' => '598',
							'rght' => '625',
						),
				),
			285 =>
				array(
					'Aco' =>
						array(
							'id' => '289',
							'parent_id' => '288',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '599',
							'rght' => '600',
						),
				),
			286 =>
				array(
					'Aco' =>
						array(
							'id' => '290',
							'parent_id' => '288',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '601',
							'rght' => '602',
						),
				),
			287 =>
				array(
					'Aco' =>
						array(
							'id' => '291',
							'parent_id' => '288',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '603',
							'rght' => '604',
						),
				),
			288 =>
				array(
					'Aco' =>
						array(
							'id' => '292',
							'parent_id' => '288',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '605',
							'rght' => '606',
						),
				),
			289 =>
				array(
					'Aco' =>
						array(
							'id' => '293',
							'parent_id' => '288',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadElementsByContainerId',
							'lft' => '607',
							'rght' => '608',
						),
				),
			290 =>
				array(
					'Aco' =>
						array(
							'id' => '294',
							'parent_id' => '288',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '609',
							'rght' => '610',
						),
				),
			291 =>
				array(
					'Aco' =>
						array(
							'id' => '295',
							'parent_id' => '288',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '611',
							'rght' => '612',
						),
				),
			292 =>
				array(
					'Aco' =>
						array(
							'id' => '296',
							'parent_id' => '288',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '613',
							'rght' => '614',
						),
				),
			293 =>
				array(
					'Aco' =>
						array(
							'id' => '297',
							'parent_id' => '288',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '615',
							'rght' => '616',
						),
				),
			294 =>
				array(
					'Aco' =>
						array(
							'id' => '298',
							'parent_id' => '288',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '617',
							'rght' => '618',
						),
				),
			295 =>
				array(
					'Aco' =>
						array(
							'id' => '299',
							'parent_id' => '288',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '619',
							'rght' => '620',
						),
				),
			296 =>
				array(
					'Aco' =>
						array(
							'id' => '300',
							'parent_id' => '288',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '621',
							'rght' => '622',
						),
				),
			297 =>
				array(
					'Aco' =>
						array(
							'id' => '301',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Hostgroups',
							'lft' => '626',
							'rght' => '661',
						),
				),
			298 =>
				array(
					'Aco' =>
						array(
							'id' => '302',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '627',
							'rght' => '628',
						),
				),
			299 =>
				array(
					'Aco' =>
						array(
							'id' => '303',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'extended',
							'lft' => '629',
							'rght' => '630',
						),
				),
			300 =>
				array(
					'Aco' =>
						array(
							'id' => '304',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '631',
							'rght' => '632',
						),
				),
			301 =>
				array(
					'Aco' =>
						array(
							'id' => '305',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '633',
							'rght' => '634',
						),
				),
			302 =>
				array(
					'Aco' =>
						array(
							'id' => '306',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadHosts',
							'lft' => '635',
							'rght' => '636',
						),
				),
			303 =>
				array(
					'Aco' =>
						array(
							'id' => '307',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '637',
							'rght' => '638',
						),
				),
			304 =>
				array(
					'Aco' =>
						array(
							'id' => '308',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_add',
							'lft' => '639',
							'rght' => '640',
						),
				),
			305 =>
				array(
					'Aco' =>
						array(
							'id' => '309',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_delete',
							'lft' => '641',
							'rght' => '642',
						),
				),
			306 =>
				array(
					'Aco' =>
						array(
							'id' => '310',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'listToPdf',
							'lft' => '643',
							'rght' => '644',
						),
				),
			307 =>
				array(
					'Aco' =>
						array(
							'id' => '311',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '645',
							'rght' => '646',
						),
				),
			308 =>
				array(
					'Aco' =>
						array(
							'id' => '312',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '647',
							'rght' => '648',
						),
				),
			309 =>
				array(
					'Aco' =>
						array(
							'id' => '313',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '649',
							'rght' => '650',
						),
				),
			310 =>
				array(
					'Aco' =>
						array(
							'id' => '314',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '651',
							'rght' => '652',
						),
				),
			311 =>
				array(
					'Aco' =>
						array(
							'id' => '315',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '653',
							'rght' => '654',
						),
				),
			312 =>
				array(
					'Aco' =>
						array(
							'id' => '316',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '655',
							'rght' => '656',
						),
				),
			313 =>
				array(
					'Aco' =>
						array(
							'id' => '317',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '657',
							'rght' => '658',
						),
				),
			314 =>
				array(
					'Aco' =>
						array(
							'id' => '318',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Hosts',
							'lft' => '662',
							'rght' => '743',
						),
				),
			315 =>
				array(
					'Aco' =>
						array(
							'id' => '319',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '663',
							'rght' => '664',
						),
				),
			316 =>
				array(
					'Aco' =>
						array(
							'id' => '320',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'notMonitored',
							'lft' => '665',
							'rght' => '666',
						),
				),
			317 =>
				array(
					'Aco' =>
						array(
							'id' => '321',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '667',
							'rght' => '668',
						),
				),
			318 =>
				array(
					'Aco' =>
						array(
							'id' => '322',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'sharing',
							'lft' => '669',
							'rght' => '670',
						),
				),
			319 =>
				array(
					'Aco' =>
						array(
							'id' => '323',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit_details',
							'lft' => '671',
							'rght' => '672',
						),
				),
			320 =>
				array(
					'Aco' =>
						array(
							'id' => '324',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '673',
							'rght' => '674',
						),
				),
			321 =>
				array(
					'Aco' =>
						array(
							'id' => '325',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'disabled',
							'lft' => '675',
							'rght' => '676',
						),
				),
			322 =>
				array(
					'Aco' =>
						array(
							'id' => '326',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'deactivate',
							'lft' => '677',
							'rght' => '678',
						),
				),
			323 =>
				array(
					'Aco' =>
						array(
							'id' => '327',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_deactivate',
							'lft' => '679',
							'rght' => '680',
						),
				),
			324 =>
				array(
					'Aco' =>
						array(
							'id' => '328',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'enable',
							'lft' => '681',
							'rght' => '682',
						),
				),
			325 =>
				array(
					'Aco' =>
						array(
							'id' => '329',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '683',
							'rght' => '684',
						),
				),
			326 =>
				array(
					'Aco' =>
						array(
							'id' => '330',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_delete',
							'lft' => '685',
							'rght' => '686',
						),
				),
			327 =>
				array(
					'Aco' =>
						array(
							'id' => '331',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'copy',
							'lft' => '687',
							'rght' => '688',
						),
				),
			328 =>
				array(
					'Aco' =>
						array(
							'id' => '332',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'browser',
							'lft' => '689',
							'rght' => '690',
						),
				),
			329 =>
				array(
					'Aco' =>
						array(
							'id' => '333',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'longOutputByUuid',
							'lft' => '691',
							'rght' => '692',
						),
				),
			330 =>
				array(
					'Aco' =>
						array(
							'id' => '334',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'gethostbyname',
							'lft' => '693',
							'rght' => '694',
						),
				),
			331 =>
				array(
					'Aco' =>
						array(
							'id' => '335',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'gethostbyaddr',
							'lft' => '695',
							'rght' => '696',
						),
				),
			332 =>
				array(
					'Aco' =>
						array(
							'id' => '336',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadHosttemplate',
							'lft' => '697',
							'rght' => '698',
						),
				),
			333 =>
				array(
					'Aco' =>
						array(
							'id' => '337',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'addCustomMacro',
							'lft' => '699',
							'rght' => '700',
						),
				),
			334 =>
				array(
					'Aco' =>
						array(
							'id' => '338',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadTemplateMacros',
							'lft' => '701',
							'rght' => '702',
						),
				),
			335 =>
				array(
					'Aco' =>
						array(
							'id' => '339',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadParametersByCommandId',
							'lft' => '703',
							'rght' => '704',
						),
				),
			336 =>
				array(
					'Aco' =>
						array(
							'id' => '340',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadArguments',
							'lft' => '705',
							'rght' => '706',
						),
				),
			337 =>
				array(
					'Aco' =>
						array(
							'id' => '341',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadArgumentsAdd',
							'lft' => '707',
							'rght' => '708',
						),
				),
			338 =>
				array(
					'Aco' =>
						array(
							'id' => '342',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadHosttemplatesArguments',
							'lft' => '709',
							'rght' => '710',
						),
				),
			339 =>
				array(
					'Aco' =>
						array(
							'id' => '343',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getHostByAjax',
							'lft' => '711',
							'rght' => '712',
						),
				),
			340 =>
				array(
					'Aco' =>
						array(
							'id' => '344',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'listToPdf',
							'lft' => '713',
							'rght' => '714',
						),
				),
			341 =>
				array(
					'Aco' =>
						array(
							'id' => '345',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'ping',
							'lft' => '715',
							'rght' => '716',
						),
				),
			342 =>
				array(
					'Aco' =>
						array(
							'id' => '346',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'addParentHosts',
							'lft' => '717',
							'rght' => '718',
						),
				),
			343 =>
				array(
					'Aco' =>
						array(
							'id' => '347',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadElementsByContainerId',
							'lft' => '719',
							'rght' => '720',
						),
				),
			344 =>
				array(
					'Aco' =>
						array(
							'id' => '348',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'checkcommand',
							'lft' => '721',
							'rght' => '722',
						),
				),
			345 =>
				array(
					'Aco' =>
						array(
							'id' => '349',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '723',
							'rght' => '724',
						),
				),
			346 =>
				array(
					'Aco' =>
						array(
							'id' => '350',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '725',
							'rght' => '726',
						),
				),
			347 =>
				array(
					'Aco' =>
						array(
							'id' => '351',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '727',
							'rght' => '728',
						),
				),
			348 =>
				array(
					'Aco' =>
						array(
							'id' => '352',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '729',
							'rght' => '730',
						),
				),
			349 =>
				array(
					'Aco' =>
						array(
							'id' => '353',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '731',
							'rght' => '732',
						),
				),
			350 =>
				array(
					'Aco' =>
						array(
							'id' => '354',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '733',
							'rght' => '734',
						),
				),
			351 =>
				array(
					'Aco' =>
						array(
							'id' => '355',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '735',
							'rght' => '736',
						),
				),
			352 =>
				array(
					'Aco' =>
						array(
							'id' => '356',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Hosttemplates',
							'lft' => '744',
							'rght' => '783',
						),
				),
			353 =>
				array(
					'Aco' =>
						array(
							'id' => '357',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '745',
							'rght' => '746',
						),
				),
			354 =>
				array(
					'Aco' =>
						array(
							'id' => '358',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '747',
							'rght' => '748',
						),
				),
			355 =>
				array(
					'Aco' =>
						array(
							'id' => '359',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '749',
							'rght' => '750',
						),
				),
			356 =>
				array(
					'Aco' =>
						array(
							'id' => '360',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '751',
							'rght' => '752',
						),
				),
			357 =>
				array(
					'Aco' =>
						array(
							'id' => '361',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'addCustomMacro',
							'lft' => '753',
							'rght' => '754',
						),
				),
			358 =>
				array(
					'Aco' =>
						array(
							'id' => '362',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadArguments',
							'lft' => '755',
							'rght' => '756',
						),
				),
			359 =>
				array(
					'Aco' =>
						array(
							'id' => '363',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadArgumentsAdd',
							'lft' => '757',
							'rght' => '758',
						),
				),
			360 =>
				array(
					'Aco' =>
						array(
							'id' => '364',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'usedBy',
							'lft' => '759',
							'rght' => '760',
						),
				),
			361 =>
				array(
					'Aco' =>
						array(
							'id' => '365',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadElementsByContainerId',
							'lft' => '761',
							'rght' => '762',
						),
				),
			362 =>
				array(
					'Aco' =>
						array(
							'id' => '366',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '763',
							'rght' => '764',
						),
				),
			363 =>
				array(
					'Aco' =>
						array(
							'id' => '367',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '765',
							'rght' => '766',
						),
				),
			364 =>
				array(
					'Aco' =>
						array(
							'id' => '368',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '767',
							'rght' => '768',
						),
				),
			365 =>
				array(
					'Aco' =>
						array(
							'id' => '369',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '769',
							'rght' => '770',
						),
				),
			366 =>
				array(
					'Aco' =>
						array(
							'id' => '370',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '771',
							'rght' => '772',
						),
				),
			367 =>
				array(
					'Aco' =>
						array(
							'id' => '371',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '773',
							'rght' => '774',
						),
				),
			368 =>
				array(
					'Aco' =>
						array(
							'id' => '372',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '775',
							'rght' => '776',
						),
				),
			369 =>
				array(
					'Aco' =>
						array(
							'id' => '373',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Instantreports',
							'lft' => '784',
							'rght' => '805',
						),
				),
			370 =>
				array(
					'Aco' =>
						array(
							'id' => '374',
							'parent_id' => '373',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '785',
							'rght' => '786',
						),
				),
			371 =>
				array(
					'Aco' =>
						array(
							'id' => '375',
							'parent_id' => '373',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'createPdfReport',
							'lft' => '787',
							'rght' => '788',
						),
				),
			372 =>
				array(
					'Aco' =>
						array(
							'id' => '376',
							'parent_id' => '373',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'expandServices',
							'lft' => '789',
							'rght' => '790',
						),
				),
			373 =>
				array(
					'Aco' =>
						array(
							'id' => '377',
							'parent_id' => '373',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '791',
							'rght' => '792',
						),
				),
			374 =>
				array(
					'Aco' =>
						array(
							'id' => '378',
							'parent_id' => '373',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '793',
							'rght' => '794',
						),
				),
			375 =>
				array(
					'Aco' =>
						array(
							'id' => '379',
							'parent_id' => '373',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '795',
							'rght' => '796',
						),
				),
			376 =>
				array(
					'Aco' =>
						array(
							'id' => '380',
							'parent_id' => '373',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '797',
							'rght' => '798',
						),
				),
			377 =>
				array(
					'Aco' =>
						array(
							'id' => '381',
							'parent_id' => '373',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '799',
							'rght' => '800',
						),
				),
			378 =>
				array(
					'Aco' =>
						array(
							'id' => '382',
							'parent_id' => '373',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '801',
							'rght' => '802',
						),
				),
			379 =>
				array(
					'Aco' =>
						array(
							'id' => '383',
							'parent_id' => '373',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '803',
							'rght' => '804',
						),
				),
			380 =>
				array(
					'Aco' =>
						array(
							'id' => '384',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Locations',
							'lft' => '806',
							'rght' => '831',
						),
				),
			381 =>
				array(
					'Aco' =>
						array(
							'id' => '385',
							'parent_id' => '384',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '807',
							'rght' => '808',
						),
				),
			382 =>
				array(
					'Aco' =>
						array(
							'id' => '386',
							'parent_id' => '384',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '809',
							'rght' => '810',
						),
				),
			383 =>
				array(
					'Aco' =>
						array(
							'id' => '387',
							'parent_id' => '384',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '811',
							'rght' => '812',
						),
				),
			384 =>
				array(
					'Aco' =>
						array(
							'id' => '388',
							'parent_id' => '384',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '813',
							'rght' => '814',
						),
				),
			385 =>
				array(
					'Aco' =>
						array(
							'id' => '389',
							'parent_id' => '384',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '815',
							'rght' => '816',
						),
				),
			386 =>
				array(
					'Aco' =>
						array(
							'id' => '390',
							'parent_id' => '384',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '817',
							'rght' => '818',
						),
				),
			387 =>
				array(
					'Aco' =>
						array(
							'id' => '391',
							'parent_id' => '384',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '819',
							'rght' => '820',
						),
				),
			388 =>
				array(
					'Aco' =>
						array(
							'id' => '392',
							'parent_id' => '384',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '821',
							'rght' => '822',
						),
				),
			389 =>
				array(
					'Aco' =>
						array(
							'id' => '393',
							'parent_id' => '384',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '823',
							'rght' => '824',
						),
				),
			390 =>
				array(
					'Aco' =>
						array(
							'id' => '394',
							'parent_id' => '384',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '825',
							'rght' => '826',
						),
				),
			391 =>
				array(
					'Aco' =>
						array(
							'id' => '395',
							'parent_id' => '384',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '827',
							'rght' => '828',
						),
				),
			392 =>
				array(
					'Aco' =>
						array(
							'id' => '396',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Logentries',
							'lft' => '832',
							'rght' => '849',
						),
				),
			393 =>
				array(
					'Aco' =>
						array(
							'id' => '397',
							'parent_id' => '396',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '833',
							'rght' => '834',
						),
				),
			394 =>
				array(
					'Aco' =>
						array(
							'id' => '398',
							'parent_id' => '396',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '835',
							'rght' => '836',
						),
				),
			395 =>
				array(
					'Aco' =>
						array(
							'id' => '399',
							'parent_id' => '396',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '837',
							'rght' => '838',
						),
				),
			396 =>
				array(
					'Aco' =>
						array(
							'id' => '400',
							'parent_id' => '396',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '839',
							'rght' => '840',
						),
				),
			397 =>
				array(
					'Aco' =>
						array(
							'id' => '401',
							'parent_id' => '396',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '841',
							'rght' => '842',
						),
				),
			398 =>
				array(
					'Aco' =>
						array(
							'id' => '402',
							'parent_id' => '396',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '843',
							'rght' => '844',
						),
				),
			399 =>
				array(
					'Aco' =>
						array(
							'id' => '403',
							'parent_id' => '396',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '845',
							'rght' => '846',
						),
				),
			400 =>
				array(
					'Aco' =>
						array(
							'id' => '404',
							'parent_id' => '396',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '847',
							'rght' => '848',
						),
				),
			401 =>
				array(
					'Aco' =>
						array(
							'id' => '405',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Login',
							'lft' => '850',
							'rght' => '877',
						),
				),
			402 =>
				array(
					'Aco' =>
						array(
							'id' => '406',
							'parent_id' => '405',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '851',
							'rght' => '852',
						),
				),
			403 =>
				array(
					'Aco' =>
						array(
							'id' => '407',
							'parent_id' => '405',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'login',
							'lft' => '853',
							'rght' => '854',
						),
				),
			404 =>
				array(
					'Aco' =>
						array(
							'id' => '408',
							'parent_id' => '405',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'onetimetoken',
							'lft' => '855',
							'rght' => '856',
						),
				),
			405 =>
				array(
					'Aco' =>
						array(
							'id' => '409',
							'parent_id' => '405',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'logout',
							'lft' => '857',
							'rght' => '858',
						),
				),
			406 =>
				array(
					'Aco' =>
						array(
							'id' => '410',
							'parent_id' => '405',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'auth_required',
							'lft' => '859',
							'rght' => '860',
						),
				),
			407 =>
				array(
					'Aco' =>
						array(
							'id' => '411',
							'parent_id' => '405',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'lock',
							'lft' => '861',
							'rght' => '862',
						),
				),
			408 =>
				array(
					'Aco' =>
						array(
							'id' => '412',
							'parent_id' => '405',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '863',
							'rght' => '864',
						),
				),
			409 =>
				array(
					'Aco' =>
						array(
							'id' => '413',
							'parent_id' => '405',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '865',
							'rght' => '866',
						),
				),
			410 =>
				array(
					'Aco' =>
						array(
							'id' => '414',
							'parent_id' => '405',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '867',
							'rght' => '868',
						),
				),
			411 =>
				array(
					'Aco' =>
						array(
							'id' => '415',
							'parent_id' => '405',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '869',
							'rght' => '870',
						),
				),
			412 =>
				array(
					'Aco' =>
						array(
							'id' => '416',
							'parent_id' => '405',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '871',
							'rght' => '872',
						),
				),
			413 =>
				array(
					'Aco' =>
						array(
							'id' => '417',
							'parent_id' => '405',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '873',
							'rght' => '874',
						),
				),
			414 =>
				array(
					'Aco' =>
						array(
							'id' => '418',
							'parent_id' => '405',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '875',
							'rght' => '876',
						),
				),
			415 =>
				array(
					'Aco' =>
						array(
							'id' => '419',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Macros',
							'lft' => '878',
							'rght' => '897',
						),
				),
			416 =>
				array(
					'Aco' =>
						array(
							'id' => '420',
							'parent_id' => '419',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '879',
							'rght' => '880',
						),
				),
			417 =>
				array(
					'Aco' =>
						array(
							'id' => '421',
							'parent_id' => '419',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'addMacro',
							'lft' => '881',
							'rght' => '882',
						),
				),
			418 =>
				array(
					'Aco' =>
						array(
							'id' => '422',
							'parent_id' => '419',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '883',
							'rght' => '884',
						),
				),
			419 =>
				array(
					'Aco' =>
						array(
							'id' => '423',
							'parent_id' => '419',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '885',
							'rght' => '886',
						),
				),
			420 =>
				array(
					'Aco' =>
						array(
							'id' => '424',
							'parent_id' => '419',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '887',
							'rght' => '888',
						),
				),
			421 =>
				array(
					'Aco' =>
						array(
							'id' => '425',
							'parent_id' => '419',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '889',
							'rght' => '890',
						),
				),
			422 =>
				array(
					'Aco' =>
						array(
							'id' => '426',
							'parent_id' => '419',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '891',
							'rght' => '892',
						),
				),
			423 =>
				array(
					'Aco' =>
						array(
							'id' => '427',
							'parent_id' => '419',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '893',
							'rght' => '894',
						),
				),
			424 =>
				array(
					'Aco' =>
						array(
							'id' => '428',
							'parent_id' => '419',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '895',
							'rght' => '896',
						),
				),
			425 =>
				array(
					'Aco' =>
						array(
							'id' => '429',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Nagiostats',
							'lft' => '898',
							'rght' => '915',
						),
				),
			426 =>
				array(
					'Aco' =>
						array(
							'id' => '430',
							'parent_id' => '429',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '899',
							'rght' => '900',
						),
				),
			427 =>
				array(
					'Aco' =>
						array(
							'id' => '431',
							'parent_id' => '429',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '901',
							'rght' => '902',
						),
				),
			428 =>
				array(
					'Aco' =>
						array(
							'id' => '432',
							'parent_id' => '429',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '903',
							'rght' => '904',
						),
				),
			429 =>
				array(
					'Aco' =>
						array(
							'id' => '433',
							'parent_id' => '429',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '905',
							'rght' => '906',
						),
				),
			430 =>
				array(
					'Aco' =>
						array(
							'id' => '434',
							'parent_id' => '429',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '907',
							'rght' => '908',
						),
				),
			431 =>
				array(
					'Aco' =>
						array(
							'id' => '435',
							'parent_id' => '429',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '909',
							'rght' => '910',
						),
				),
			432 =>
				array(
					'Aco' =>
						array(
							'id' => '436',
							'parent_id' => '429',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '911',
							'rght' => '912',
						),
				),
			433 =>
				array(
					'Aco' =>
						array(
							'id' => '437',
							'parent_id' => '429',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '913',
							'rght' => '914',
						),
				),
			434 =>
				array(
					'Aco' =>
						array(
							'id' => '438',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Notifications',
							'lft' => '916',
							'rght' => '937',
						),
				),
			435 =>
				array(
					'Aco' =>
						array(
							'id' => '439',
							'parent_id' => '438',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '917',
							'rght' => '918',
						),
				),
			436 =>
				array(
					'Aco' =>
						array(
							'id' => '440',
							'parent_id' => '438',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'hostNotification',
							'lft' => '919',
							'rght' => '920',
						),
				),
			437 =>
				array(
					'Aco' =>
						array(
							'id' => '441',
							'parent_id' => '438',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceNotification',
							'lft' => '921',
							'rght' => '922',
						),
				),
			438 =>
				array(
					'Aco' =>
						array(
							'id' => '442',
							'parent_id' => '438',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '923',
							'rght' => '924',
						),
				),
			439 =>
				array(
					'Aco' =>
						array(
							'id' => '443',
							'parent_id' => '438',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '925',
							'rght' => '926',
						),
				),
			440 =>
				array(
					'Aco' =>
						array(
							'id' => '444',
							'parent_id' => '438',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '927',
							'rght' => '928',
						),
				),
			441 =>
				array(
					'Aco' =>
						array(
							'id' => '445',
							'parent_id' => '438',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '929',
							'rght' => '930',
						),
				),
			442 =>
				array(
					'Aco' =>
						array(
							'id' => '446',
							'parent_id' => '438',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '931',
							'rght' => '932',
						),
				),
			443 =>
				array(
					'Aco' =>
						array(
							'id' => '447',
							'parent_id' => '438',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '933',
							'rght' => '934',
						),
				),
			444 =>
				array(
					'Aco' =>
						array(
							'id' => '448',
							'parent_id' => '438',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '935',
							'rght' => '936',
						),
				),
			445 =>
				array(
					'Aco' =>
						array(
							'id' => '449',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Packetmanager',
							'lft' => '938',
							'rght' => '957',
						),
				),
			446 =>
				array(
					'Aco' =>
						array(
							'id' => '450',
							'parent_id' => '449',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '939',
							'rght' => '940',
						),
				),
			447 =>
				array(
					'Aco' =>
						array(
							'id' => '451',
							'parent_id' => '449',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getPackets',
							'lft' => '941',
							'rght' => '942',
						),
				),
			448 =>
				array(
					'Aco' =>
						array(
							'id' => '452',
							'parent_id' => '449',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '943',
							'rght' => '944',
						),
				),
			449 =>
				array(
					'Aco' =>
						array(
							'id' => '453',
							'parent_id' => '449',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '945',
							'rght' => '946',
						),
				),
			450 =>
				array(
					'Aco' =>
						array(
							'id' => '454',
							'parent_id' => '449',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '947',
							'rght' => '948',
						),
				),
			451 =>
				array(
					'Aco' =>
						array(
							'id' => '455',
							'parent_id' => '449',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '949',
							'rght' => '950',
						),
				),
			452 =>
				array(
					'Aco' =>
						array(
							'id' => '456',
							'parent_id' => '449',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '951',
							'rght' => '952',
						),
				),
			453 =>
				array(
					'Aco' =>
						array(
							'id' => '457',
							'parent_id' => '449',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '953',
							'rght' => '954',
						),
				),
			454 =>
				array(
					'Aco' =>
						array(
							'id' => '458',
							'parent_id' => '449',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '955',
							'rght' => '956',
						),
				),
			455 =>
				array(
					'Aco' =>
						array(
							'id' => '459',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Profile',
							'lft' => '958',
							'rght' => '977',
						),
				),
			456 =>
				array(
					'Aco' =>
						array(
							'id' => '460',
							'parent_id' => '459',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '959',
							'rght' => '960',
						),
				),
			457 =>
				array(
					'Aco' =>
						array(
							'id' => '461',
							'parent_id' => '459',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'deleteImage',
							'lft' => '961',
							'rght' => '962',
						),
				),
			458 =>
				array(
					'Aco' =>
						array(
							'id' => '462',
							'parent_id' => '459',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '963',
							'rght' => '964',
						),
				),
			459 =>
				array(
					'Aco' =>
						array(
							'id' => '463',
							'parent_id' => '459',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '965',
							'rght' => '966',
						),
				),
			460 =>
				array(
					'Aco' =>
						array(
							'id' => '464',
							'parent_id' => '459',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '967',
							'rght' => '968',
						),
				),
			461 =>
				array(
					'Aco' =>
						array(
							'id' => '465',
							'parent_id' => '459',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '969',
							'rght' => '970',
						),
				),
			462 =>
				array(
					'Aco' =>
						array(
							'id' => '466',
							'parent_id' => '459',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '971',
							'rght' => '972',
						),
				),
			463 =>
				array(
					'Aco' =>
						array(
							'id' => '467',
							'parent_id' => '459',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '973',
							'rght' => '974',
						),
				),
			464 =>
				array(
					'Aco' =>
						array(
							'id' => '468',
							'parent_id' => '459',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '975',
							'rght' => '976',
						),
				),
			465 =>
				array(
					'Aco' =>
						array(
							'id' => '469',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Proxy',
							'lft' => '978',
							'rght' => '999',
						),
				),
			466 =>
				array(
					'Aco' =>
						array(
							'id' => '470',
							'parent_id' => '469',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '979',
							'rght' => '980',
						),
				),
			467 =>
				array(
					'Aco' =>
						array(
							'id' => '471',
							'parent_id' => '469',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '981',
							'rght' => '982',
						),
				),
			468 =>
				array(
					'Aco' =>
						array(
							'id' => '472',
							'parent_id' => '469',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getSettings',
							'lft' => '983',
							'rght' => '984',
						),
				),
			469 =>
				array(
					'Aco' =>
						array(
							'id' => '473',
							'parent_id' => '469',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '985',
							'rght' => '986',
						),
				),
			470 =>
				array(
					'Aco' =>
						array(
							'id' => '474',
							'parent_id' => '469',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '987',
							'rght' => '988',
						),
				),
			471 =>
				array(
					'Aco' =>
						array(
							'id' => '475',
							'parent_id' => '469',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '989',
							'rght' => '990',
						),
				),
			472 =>
				array(
					'Aco' =>
						array(
							'id' => '476',
							'parent_id' => '469',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '991',
							'rght' => '992',
						),
				),
			473 =>
				array(
					'Aco' =>
						array(
							'id' => '477',
							'parent_id' => '469',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '993',
							'rght' => '994',
						),
				),
			474 =>
				array(
					'Aco' =>
						array(
							'id' => '478',
							'parent_id' => '469',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '995',
							'rght' => '996',
						),
				),
			475 =>
				array(
					'Aco' =>
						array(
							'id' => '479',
							'parent_id' => '469',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '997',
							'rght' => '998',
						),
				),
			476 =>
				array(
					'Aco' =>
						array(
							'id' => '480',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Qr',
							'lft' => '1000',
							'rght' => '1017',
						),
				),
			477 =>
				array(
					'Aco' =>
						array(
							'id' => '481',
							'parent_id' => '480',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1001',
							'rght' => '1002',
						),
				),
			478 =>
				array(
					'Aco' =>
						array(
							'id' => '482',
							'parent_id' => '480',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1003',
							'rght' => '1004',
						),
				),
			479 =>
				array(
					'Aco' =>
						array(
							'id' => '483',
							'parent_id' => '480',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1005',
							'rght' => '1006',
						),
				),
			480 =>
				array(
					'Aco' =>
						array(
							'id' => '484',
							'parent_id' => '480',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1007',
							'rght' => '1008',
						),
				),
			481 =>
				array(
					'Aco' =>
						array(
							'id' => '485',
							'parent_id' => '480',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1009',
							'rght' => '1010',
						),
				),
			482 =>
				array(
					'Aco' =>
						array(
							'id' => '486',
							'parent_id' => '480',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1011',
							'rght' => '1012',
						),
				),
			483 =>
				array(
					'Aco' =>
						array(
							'id' => '487',
							'parent_id' => '480',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1013',
							'rght' => '1014',
						),
				),
			484 =>
				array(
					'Aco' =>
						array(
							'id' => '488',
							'parent_id' => '480',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1015',
							'rght' => '1016',
						),
				),
			485 =>
				array(
					'Aco' =>
						array(
							'id' => '489',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Registers',
							'lft' => '1018',
							'rght' => '1037',
						),
				),
			486 =>
				array(
					'Aco' =>
						array(
							'id' => '490',
							'parent_id' => '489',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1019',
							'rght' => '1020',
						),
				),
			487 =>
				array(
					'Aco' =>
						array(
							'id' => '491',
							'parent_id' => '489',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'check',
							'lft' => '1021',
							'rght' => '1022',
						),
				),
			488 =>
				array(
					'Aco' =>
						array(
							'id' => '492',
							'parent_id' => '489',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1023',
							'rght' => '1024',
						),
				),
			489 =>
				array(
					'Aco' =>
						array(
							'id' => '493',
							'parent_id' => '489',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1025',
							'rght' => '1026',
						),
				),
			490 =>
				array(
					'Aco' =>
						array(
							'id' => '494',
							'parent_id' => '489',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1027',
							'rght' => '1028',
						),
				),
			491 =>
				array(
					'Aco' =>
						array(
							'id' => '495',
							'parent_id' => '489',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1029',
							'rght' => '1030',
						),
				),
			492 =>
				array(
					'Aco' =>
						array(
							'id' => '496',
							'parent_id' => '489',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1031',
							'rght' => '1032',
						),
				),
			493 =>
				array(
					'Aco' =>
						array(
							'id' => '497',
							'parent_id' => '489',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1033',
							'rght' => '1034',
						),
				),
			494 =>
				array(
					'Aco' =>
						array(
							'id' => '498',
							'parent_id' => '489',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1035',
							'rght' => '1036',
						),
				),
			495 =>
				array(
					'Aco' =>
						array(
							'id' => '499',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Rrds',
							'lft' => '1038',
							'rght' => '1057',
						),
				),
			496 =>
				array(
					'Aco' =>
						array(
							'id' => '500',
							'parent_id' => '499',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1039',
							'rght' => '1040',
						),
				),
			497 =>
				array(
					'Aco' =>
						array(
							'id' => '501',
							'parent_id' => '499',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'ajax',
							'lft' => '1041',
							'rght' => '1042',
						),
				),
			498 =>
				array(
					'Aco' =>
						array(
							'id' => '502',
							'parent_id' => '499',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1043',
							'rght' => '1044',
						),
				),
			499 =>
				array(
					'Aco' =>
						array(
							'id' => '503',
							'parent_id' => '499',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1045',
							'rght' => '1046',
						),
				),
			500 =>
				array(
					'Aco' =>
						array(
							'id' => '504',
							'parent_id' => '499',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1047',
							'rght' => '1048',
						),
				),
			501 =>
				array(
					'Aco' =>
						array(
							'id' => '505',
							'parent_id' => '499',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1049',
							'rght' => '1050',
						),
				),
			502 =>
				array(
					'Aco' =>
						array(
							'id' => '506',
							'parent_id' => '499',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1051',
							'rght' => '1052',
						),
				),
			503 =>
				array(
					'Aco' =>
						array(
							'id' => '507',
							'parent_id' => '499',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1053',
							'rght' => '1054',
						),
				),
			504 =>
				array(
					'Aco' =>
						array(
							'id' => '508',
							'parent_id' => '499',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1055',
							'rght' => '1056',
						),
				),
			505 =>
				array(
					'Aco' =>
						array(
							'id' => '509',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Search',
							'lft' => '1058',
							'rght' => '1079',
						),
				),
			506 =>
				array(
					'Aco' =>
						array(
							'id' => '510',
							'parent_id' => '509',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1059',
							'rght' => '1060',
						),
				),
			507 =>
				array(
					'Aco' =>
						array(
							'id' => '511',
							'parent_id' => '509',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'hostMacro',
							'lft' => '1061',
							'rght' => '1062',
						),
				),
			508 =>
				array(
					'Aco' =>
						array(
							'id' => '512',
							'parent_id' => '509',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceMacro',
							'lft' => '1063',
							'rght' => '1064',
						),
				),
			509 =>
				array(
					'Aco' =>
						array(
							'id' => '513',
							'parent_id' => '509',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1065',
							'rght' => '1066',
						),
				),
			510 =>
				array(
					'Aco' =>
						array(
							'id' => '514',
							'parent_id' => '509',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1067',
							'rght' => '1068',
						),
				),
			511 =>
				array(
					'Aco' =>
						array(
							'id' => '515',
							'parent_id' => '509',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1069',
							'rght' => '1070',
						),
				),
			512 =>
				array(
					'Aco' =>
						array(
							'id' => '516',
							'parent_id' => '509',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1071',
							'rght' => '1072',
						),
				),
			513 =>
				array(
					'Aco' =>
						array(
							'id' => '517',
							'parent_id' => '509',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1073',
							'rght' => '1074',
						),
				),
			514 =>
				array(
					'Aco' =>
						array(
							'id' => '518',
							'parent_id' => '509',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1075',
							'rght' => '1076',
						),
				),
			515 =>
				array(
					'Aco' =>
						array(
							'id' => '519',
							'parent_id' => '509',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1077',
							'rght' => '1078',
						),
				),
			516 =>
				array(
					'Aco' =>
						array(
							'id' => '520',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Servicechecks',
							'lft' => '1080',
							'rght' => '1097',
						),
				),
			517 =>
				array(
					'Aco' =>
						array(
							'id' => '521',
							'parent_id' => '520',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1081',
							'rght' => '1082',
						),
				),
			518 =>
				array(
					'Aco' =>
						array(
							'id' => '522',
							'parent_id' => '520',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1083',
							'rght' => '1084',
						),
				),
			519 =>
				array(
					'Aco' =>
						array(
							'id' => '523',
							'parent_id' => '520',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1085',
							'rght' => '1086',
						),
				),
			520 =>
				array(
					'Aco' =>
						array(
							'id' => '524',
							'parent_id' => '520',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1087',
							'rght' => '1088',
						),
				),
			521 =>
				array(
					'Aco' =>
						array(
							'id' => '525',
							'parent_id' => '520',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1089',
							'rght' => '1090',
						),
				),
			522 =>
				array(
					'Aco' =>
						array(
							'id' => '526',
							'parent_id' => '520',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1091',
							'rght' => '1092',
						),
				),
			523 =>
				array(
					'Aco' =>
						array(
							'id' => '527',
							'parent_id' => '520',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1093',
							'rght' => '1094',
						),
				),
			524 =>
				array(
					'Aco' =>
						array(
							'id' => '528',
							'parent_id' => '520',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1095',
							'rght' => '1096',
						),
				),
			525 =>
				array(
					'Aco' =>
						array(
							'id' => '529',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Servicedependencies',
							'lft' => '1098',
							'rght' => '1125',
						),
				),
			526 =>
				array(
					'Aco' =>
						array(
							'id' => '530',
							'parent_id' => '529',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1099',
							'rght' => '1100',
						),
				),
			527 =>
				array(
					'Aco' =>
						array(
							'id' => '531',
							'parent_id' => '529',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '1101',
							'rght' => '1102',
						),
				),
			528 =>
				array(
					'Aco' =>
						array(
							'id' => '532',
							'parent_id' => '529',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '1103',
							'rght' => '1104',
						),
				),
			529 =>
				array(
					'Aco' =>
						array(
							'id' => '533',
							'parent_id' => '529',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '1105',
							'rght' => '1106',
						),
				),
			530 =>
				array(
					'Aco' =>
						array(
							'id' => '534',
							'parent_id' => '529',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadElementsByContainerId',
							'lft' => '1107',
							'rght' => '1108',
						),
				),
			531 =>
				array(
					'Aco' =>
						array(
							'id' => '535',
							'parent_id' => '529',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1109',
							'rght' => '1110',
						),
				),
			532 =>
				array(
					'Aco' =>
						array(
							'id' => '536',
							'parent_id' => '529',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1111',
							'rght' => '1112',
						),
				),
			533 =>
				array(
					'Aco' =>
						array(
							'id' => '537',
							'parent_id' => '529',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1113',
							'rght' => '1114',
						),
				),
			534 =>
				array(
					'Aco' =>
						array(
							'id' => '538',
							'parent_id' => '529',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1115',
							'rght' => '1116',
						),
				),
			535 =>
				array(
					'Aco' =>
						array(
							'id' => '539',
							'parent_id' => '529',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1117',
							'rght' => '1118',
						),
				),
			536 =>
				array(
					'Aco' =>
						array(
							'id' => '540',
							'parent_id' => '529',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1119',
							'rght' => '1120',
						),
				),
			537 =>
				array(
					'Aco' =>
						array(
							'id' => '541',
							'parent_id' => '529',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1121',
							'rght' => '1122',
						),
				),
			538 =>
				array(
					'Aco' =>
						array(
							'id' => '542',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Serviceescalations',
							'lft' => '1126',
							'rght' => '1153',
						),
				),
			539 =>
				array(
					'Aco' =>
						array(
							'id' => '543',
							'parent_id' => '542',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1127',
							'rght' => '1128',
						),
				),
			540 =>
				array(
					'Aco' =>
						array(
							'id' => '544',
							'parent_id' => '542',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '1129',
							'rght' => '1130',
						),
				),
			541 =>
				array(
					'Aco' =>
						array(
							'id' => '545',
							'parent_id' => '542',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '1131',
							'rght' => '1132',
						),
				),
			542 =>
				array(
					'Aco' =>
						array(
							'id' => '546',
							'parent_id' => '542',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '1133',
							'rght' => '1134',
						),
				),
			543 =>
				array(
					'Aco' =>
						array(
							'id' => '547',
							'parent_id' => '542',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadElementsByContainerId',
							'lft' => '1135',
							'rght' => '1136',
						),
				),
			544 =>
				array(
					'Aco' =>
						array(
							'id' => '548',
							'parent_id' => '542',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1137',
							'rght' => '1138',
						),
				),
			545 =>
				array(
					'Aco' =>
						array(
							'id' => '549',
							'parent_id' => '542',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1139',
							'rght' => '1140',
						),
				),
			546 =>
				array(
					'Aco' =>
						array(
							'id' => '550',
							'parent_id' => '542',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1141',
							'rght' => '1142',
						),
				),
			547 =>
				array(
					'Aco' =>
						array(
							'id' => '551',
							'parent_id' => '542',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1143',
							'rght' => '1144',
						),
				),
			548 =>
				array(
					'Aco' =>
						array(
							'id' => '552',
							'parent_id' => '542',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1145',
							'rght' => '1146',
						),
				),
			549 =>
				array(
					'Aco' =>
						array(
							'id' => '553',
							'parent_id' => '542',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1147',
							'rght' => '1148',
						),
				),
			550 =>
				array(
					'Aco' =>
						array(
							'id' => '554',
							'parent_id' => '542',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1149',
							'rght' => '1150',
						),
				),
			551 =>
				array(
					'Aco' =>
						array(
							'id' => '555',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Servicegroups',
							'lft' => '1154',
							'rght' => '1187',
						),
				),
			552 =>
				array(
					'Aco' =>
						array(
							'id' => '556',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1155',
							'rght' => '1156',
						),
				),
			553 =>
				array(
					'Aco' =>
						array(
							'id' => '557',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '1157',
							'rght' => '1158',
						),
				),
			554 =>
				array(
					'Aco' =>
						array(
							'id' => '558',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '1159',
							'rght' => '1160',
						),
				),
			555 =>
				array(
					'Aco' =>
						array(
							'id' => '559',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadServices',
							'lft' => '1161',
							'rght' => '1162',
						),
				),
			556 =>
				array(
					'Aco' =>
						array(
							'id' => '560',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '1163',
							'rght' => '1164',
						),
				),
			557 =>
				array(
					'Aco' =>
						array(
							'id' => '561',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_delete',
							'lft' => '1165',
							'rght' => '1166',
						),
				),
			558 =>
				array(
					'Aco' =>
						array(
							'id' => '562',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_add',
							'lft' => '1167',
							'rght' => '1168',
						),
				),
			559 =>
				array(
					'Aco' =>
						array(
							'id' => '563',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'listToPdf',
							'lft' => '1169',
							'rght' => '1170',
						),
				),
			560 =>
				array(
					'Aco' =>
						array(
							'id' => '564',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1171',
							'rght' => '1172',
						),
				),
			561 =>
				array(
					'Aco' =>
						array(
							'id' => '565',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1173',
							'rght' => '1174',
						),
				),
			562 =>
				array(
					'Aco' =>
						array(
							'id' => '566',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1175',
							'rght' => '1176',
						),
				),
			563 =>
				array(
					'Aco' =>
						array(
							'id' => '567',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1177',
							'rght' => '1178',
						),
				),
			564 =>
				array(
					'Aco' =>
						array(
							'id' => '568',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1179',
							'rght' => '1180',
						),
				),
			565 =>
				array(
					'Aco' =>
						array(
							'id' => '569',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1181',
							'rght' => '1182',
						),
				),
			566 =>
				array(
					'Aco' =>
						array(
							'id' => '570',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1183',
							'rght' => '1184',
						),
				),
			567 =>
				array(
					'Aco' =>
						array(
							'id' => '571',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Services',
							'lft' => '1188',
							'rght' => '1277',
						),
				),
			568 =>
				array(
					'Aco' =>
						array(
							'id' => '572',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1189',
							'rght' => '1190',
						),
				),
			569 =>
				array(
					'Aco' =>
						array(
							'id' => '573',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'notMonitored',
							'lft' => '1191',
							'rght' => '1192',
						),
				),
			570 =>
				array(
					'Aco' =>
						array(
							'id' => '574',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'disabled',
							'lft' => '1193',
							'rght' => '1194',
						),
				),
			571 =>
				array(
					'Aco' =>
						array(
							'id' => '575',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '1195',
							'rght' => '1196',
						),
				),
			572 =>
				array(
					'Aco' =>
						array(
							'id' => '576',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '1197',
							'rght' => '1198',
						),
				),
			573 =>
				array(
					'Aco' =>
						array(
							'id' => '577',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '1199',
							'rght' => '1200',
						),
				),
			574 =>
				array(
					'Aco' =>
						array(
							'id' => '578',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_delete',
							'lft' => '1201',
							'rght' => '1202',
						),
				),
			575 =>
				array(
					'Aco' =>
						array(
							'id' => '579',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'copy',
							'lft' => '1203',
							'rght' => '1204',
						),
				),
			576 =>
				array(
					'Aco' =>
						array(
							'id' => '580',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'deactivate',
							'lft' => '1205',
							'rght' => '1206',
						),
				),
			577 =>
				array(
					'Aco' =>
						array(
							'id' => '581',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_deactivate',
							'lft' => '1207',
							'rght' => '1208',
						),
				),
			578 =>
				array(
					'Aco' =>
						array(
							'id' => '582',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'enable',
							'lft' => '1209',
							'rght' => '1210',
						),
				),
			579 =>
				array(
					'Aco' =>
						array(
							'id' => '583',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadContactsAndContactgroups',
							'lft' => '1211',
							'rght' => '1212',
						),
				),
			580 =>
				array(
					'Aco' =>
						array(
							'id' => '584',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadParametersByCommandId',
							'lft' => '1213',
							'rght' => '1214',
						),
				),
			581 =>
				array(
					'Aco' =>
						array(
							'id' => '585',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadNagParametersByCommandId',
							'lft' => '1215',
							'rght' => '1216',
						),
				),
			582 =>
				array(
					'Aco' =>
						array(
							'id' => '586',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadArgumentsAdd',
							'lft' => '1217',
							'rght' => '1218',
						),
				),
			583 =>
				array(
					'Aco' =>
						array(
							'id' => '587',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadServicetemplatesArguments',
							'lft' => '1219',
							'rght' => '1220',
						),
				),
			584 =>
				array(
					'Aco' =>
						array(
							'id' => '588',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadTemplateData',
							'lft' => '1221',
							'rght' => '1222',
						),
				),
			585 =>
				array(
					'Aco' =>
						array(
							'id' => '589',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'addCustomMacro',
							'lft' => '1223',
							'rght' => '1224',
						),
				),
			586 =>
				array(
					'Aco' =>
						array(
							'id' => '590',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadServices',
							'lft' => '1225',
							'rght' => '1226',
						),
				),
			587 =>
				array(
					'Aco' =>
						array(
							'id' => '591',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadTemplateMacros',
							'lft' => '1227',
							'rght' => '1228',
						),
				),
			588 =>
				array(
					'Aco' =>
						array(
							'id' => '592',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'browser',
							'lft' => '1229',
							'rght' => '1230',
						),
				),
			589 =>
				array(
					'Aco' =>
						array(
							'id' => '593',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'servicesByHostId',
							'lft' => '1231',
							'rght' => '1232',
						),
				),
			590 =>
				array(
					'Aco' =>
						array(
							'id' => '594',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceList',
							'lft' => '1233',
							'rght' => '1234',
						),
				),
			591 =>
				array(
					'Aco' =>
						array(
							'id' => '595',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'grapherSwitch',
							'lft' => '1235',
							'rght' => '1236',
						),
				),
			592 =>
				array(
					'Aco' =>
						array(
							'id' => '596',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'grapher',
							'lft' => '1237',
							'rght' => '1238',
						),
				),
			593 =>
				array(
					'Aco' =>
						array(
							'id' => '597',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'grapherTemplate',
							'lft' => '1239',
							'rght' => '1240',
						),
				),
			594 =>
				array(
					'Aco' =>
						array(
							'id' => '598',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'grapherZoom',
							'lft' => '1241',
							'rght' => '1242',
						),
				),
			595 =>
				array(
					'Aco' =>
						array(
							'id' => '599',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'grapherZoomTemplate',
							'lft' => '1243',
							'rght' => '1244',
						),
				),
			596 =>
				array(
					'Aco' =>
						array(
							'id' => '600',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'createGrapherErrorPng',
							'lft' => '1245',
							'rght' => '1246',
						),
				),
			597 =>
				array(
					'Aco' =>
						array(
							'id' => '601',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'longOutputByUuid',
							'lft' => '1247',
							'rght' => '1248',
						),
				),
			598 =>
				array(
					'Aco' =>
						array(
							'id' => '602',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'listToPdf',
							'lft' => '1249',
							'rght' => '1250',
						),
				),
			599 =>
				array(
					'Aco' =>
						array(
							'id' => '603',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'checkcommand',
							'lft' => '1251',
							'rght' => '1252',
						),
				),
			600 =>
				array(
					'Aco' =>
						array(
							'id' => '604',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1253',
							'rght' => '1254',
						),
				),
			601 =>
				array(
					'Aco' =>
						array(
							'id' => '605',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1255',
							'rght' => '1256',
						),
				),
			602 =>
				array(
					'Aco' =>
						array(
							'id' => '606',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1257',
							'rght' => '1258',
						),
				),
			603 =>
				array(
					'Aco' =>
						array(
							'id' => '607',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1259',
							'rght' => '1260',
						),
				),
			604 =>
				array(
					'Aco' =>
						array(
							'id' => '608',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1261',
							'rght' => '1262',
						),
				),
			605 =>
				array(
					'Aco' =>
						array(
							'id' => '609',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1263',
							'rght' => '1264',
						),
				),
			606 =>
				array(
					'Aco' =>
						array(
							'id' => '610',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1265',
							'rght' => '1266',
						),
				),
			607 =>
				array(
					'Aco' =>
						array(
							'id' => '611',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Servicetemplategroups',
							'lft' => '1278',
							'rght' => '1313',
						),
				),
			608 =>
				array(
					'Aco' =>
						array(
							'id' => '612',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1279',
							'rght' => '1280',
						),
				),
			609 =>
				array(
					'Aco' =>
						array(
							'id' => '613',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '1281',
							'rght' => '1282',
						),
				),
			610 =>
				array(
					'Aco' =>
						array(
							'id' => '614',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '1283',
							'rght' => '1284',
						),
				),
			611 =>
				array(
					'Aco' =>
						array(
							'id' => '615',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allocateToHost',
							'lft' => '1285',
							'rght' => '1286',
						),
				),
			612 =>
				array(
					'Aco' =>
						array(
							'id' => '616',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allocateToHostgroup',
							'lft' => '1287',
							'rght' => '1288',
						),
				),
			613 =>
				array(
					'Aco' =>
						array(
							'id' => '617',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getHostsByHostgroupByAjax',
							'lft' => '1289',
							'rght' => '1290',
						),
				),
			614 =>
				array(
					'Aco' =>
						array(
							'id' => '618',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '1291',
							'rght' => '1292',
						),
				),
			615 =>
				array(
					'Aco' =>
						array(
							'id' => '619',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadServicetemplatesByContainerId',
							'lft' => '1293',
							'rght' => '1294',
						),
				),
			616 =>
				array(
					'Aco' =>
						array(
							'id' => '620',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1295',
							'rght' => '1296',
						),
				),
			617 =>
				array(
					'Aco' =>
						array(
							'id' => '621',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1297',
							'rght' => '1298',
						),
				),
			618 =>
				array(
					'Aco' =>
						array(
							'id' => '622',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1299',
							'rght' => '1300',
						),
				),
			619 =>
				array(
					'Aco' =>
						array(
							'id' => '623',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1301',
							'rght' => '1302',
						),
				),
			620 =>
				array(
					'Aco' =>
						array(
							'id' => '624',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1303',
							'rght' => '1304',
						),
				),
			621 =>
				array(
					'Aco' =>
						array(
							'id' => '625',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1305',
							'rght' => '1306',
						),
				),
			622 =>
				array(
					'Aco' =>
						array(
							'id' => '626',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1307',
							'rght' => '1308',
						),
				),
			623 =>
				array(
					'Aco' =>
						array(
							'id' => '627',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Servicetemplates',
							'lft' => '1314',
							'rght' => '1363',
						),
				),
			624 =>
				array(
					'Aco' =>
						array(
							'id' => '628',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1315',
							'rght' => '1316',
						),
				),
			625 =>
				array(
					'Aco' =>
						array(
							'id' => '629',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '1317',
							'rght' => '1318',
						),
				),
			626 =>
				array(
					'Aco' =>
						array(
							'id' => '630',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '1319',
							'rght' => '1320',
						),
				),
			627 =>
				array(
					'Aco' =>
						array(
							'id' => '631',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '1321',
							'rght' => '1322',
						),
				),
			628 =>
				array(
					'Aco' =>
						array(
							'id' => '632',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'usedBy',
							'lft' => '1323',
							'rght' => '1324',
						),
				),
			629 =>
				array(
					'Aco' =>
						array(
							'id' => '633',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadArguments',
							'lft' => '1325',
							'rght' => '1326',
						),
				),
			630 =>
				array(
					'Aco' =>
						array(
							'id' => '634',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadContactsAndContactgroups',
							'lft' => '1327',
							'rght' => '1328',
						),
				),
			631 =>
				array(
					'Aco' =>
						array(
							'id' => '635',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadArgumentsAdd',
							'lft' => '1329',
							'rght' => '1330',
						),
				),
			632 =>
				array(
					'Aco' =>
						array(
							'id' => '636',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadNagArgumentsAdd',
							'lft' => '1331',
							'rght' => '1332',
						),
				),
			633 =>
				array(
					'Aco' =>
						array(
							'id' => '637',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'addCustomMacro',
							'lft' => '1333',
							'rght' => '1334',
						),
				),
			634 =>
				array(
					'Aco' =>
						array(
							'id' => '638',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadParametersByCommandId',
							'lft' => '1335',
							'rght' => '1336',
						),
				),
			635 =>
				array(
					'Aco' =>
						array(
							'id' => '639',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadNagParametersByCommandId',
							'lft' => '1337',
							'rght' => '1338',
						),
				),
			636 =>
				array(
					'Aco' =>
						array(
							'id' => '640',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadElementsByContainerId',
							'lft' => '1339',
							'rght' => '1340',
						),
				),
			637 =>
				array(
					'Aco' =>
						array(
							'id' => '641',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1341',
							'rght' => '1342',
						),
				),
			638 =>
				array(
					'Aco' =>
						array(
							'id' => '642',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1343',
							'rght' => '1344',
						),
				),
			639 =>
				array(
					'Aco' =>
						array(
							'id' => '643',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1345',
							'rght' => '1346',
						),
				),
			640 =>
				array(
					'Aco' =>
						array(
							'id' => '644',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1347',
							'rght' => '1348',
						),
				),
			641 =>
				array(
					'Aco' =>
						array(
							'id' => '645',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1349',
							'rght' => '1350',
						),
				),
			642 =>
				array(
					'Aco' =>
						array(
							'id' => '646',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1351',
							'rght' => '1352',
						),
				),
			643 =>
				array(
					'Aco' =>
						array(
							'id' => '647',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1353',
							'rght' => '1354',
						),
				),
			644 =>
				array(
					'Aco' =>
						array(
							'id' => '648',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Statehistories',
							'lft' => '1364',
							'rght' => '1383',
						),
				),
			645 =>
				array(
					'Aco' =>
						array(
							'id' => '649',
							'parent_id' => '648',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'service',
							'lft' => '1365',
							'rght' => '1366',
						),
				),
			646 =>
				array(
					'Aco' =>
						array(
							'id' => '650',
							'parent_id' => '648',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'host',
							'lft' => '1367',
							'rght' => '1368',
						),
				),
			647 =>
				array(
					'Aco' =>
						array(
							'id' => '651',
							'parent_id' => '648',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1369',
							'rght' => '1370',
						),
				),
			648 =>
				array(
					'Aco' =>
						array(
							'id' => '652',
							'parent_id' => '648',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1371',
							'rght' => '1372',
						),
				),
			649 =>
				array(
					'Aco' =>
						array(
							'id' => '653',
							'parent_id' => '648',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1373',
							'rght' => '1374',
						),
				),
			650 =>
				array(
					'Aco' =>
						array(
							'id' => '654',
							'parent_id' => '648',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1375',
							'rght' => '1376',
						),
				),
			651 =>
				array(
					'Aco' =>
						array(
							'id' => '655',
							'parent_id' => '648',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1377',
							'rght' => '1378',
						),
				),
			652 =>
				array(
					'Aco' =>
						array(
							'id' => '656',
							'parent_id' => '648',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1379',
							'rght' => '1380',
						),
				),
			653 =>
				array(
					'Aco' =>
						array(
							'id' => '657',
							'parent_id' => '648',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1381',
							'rght' => '1382',
						),
				),
			654 =>
				array(
					'Aco' =>
						array(
							'id' => '658',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Statusmaps',
							'lft' => '1384',
							'rght' => '1407',
						),
				),
			655 =>
				array(
					'Aco' =>
						array(
							'id' => '659',
							'parent_id' => '658',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1385',
							'rght' => '1386',
						),
				),
			656 =>
				array(
					'Aco' =>
						array(
							'id' => '660',
							'parent_id' => '658',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getHostsAndConnections',
							'lft' => '1387',
							'rght' => '1388',
						),
				),
			657 =>
				array(
					'Aco' =>
						array(
							'id' => '661',
							'parent_id' => '658',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'clickHostStatus',
							'lft' => '1389',
							'rght' => '1390',
						),
				),
			658 =>
				array(
					'Aco' =>
						array(
							'id' => '662',
							'parent_id' => '658',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '1391',
							'rght' => '1392',
						),
				),
			659 =>
				array(
					'Aco' =>
						array(
							'id' => '663',
							'parent_id' => '658',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1393',
							'rght' => '1394',
						),
				),
			660 =>
				array(
					'Aco' =>
						array(
							'id' => '664',
							'parent_id' => '658',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1395',
							'rght' => '1396',
						),
				),
			661 =>
				array(
					'Aco' =>
						array(
							'id' => '665',
							'parent_id' => '658',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1397',
							'rght' => '1398',
						),
				),
			662 =>
				array(
					'Aco' =>
						array(
							'id' => '666',
							'parent_id' => '658',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1399',
							'rght' => '1400',
						),
				),
			663 =>
				array(
					'Aco' =>
						array(
							'id' => '667',
							'parent_id' => '658',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1401',
							'rght' => '1402',
						),
				),
			664 =>
				array(
					'Aco' =>
						array(
							'id' => '668',
							'parent_id' => '658',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1403',
							'rght' => '1404',
						),
				),
			665 =>
				array(
					'Aco' =>
						array(
							'id' => '669',
							'parent_id' => '658',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1405',
							'rght' => '1406',
						),
				),
			666 =>
				array(
					'Aco' =>
						array(
							'id' => '670',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'System',
							'lft' => '1408',
							'rght' => '1425',
						),
				),
			667 =>
				array(
					'Aco' =>
						array(
							'id' => '671',
							'parent_id' => '670',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'changelog',
							'lft' => '1409',
							'rght' => '1410',
						),
				),
			668 =>
				array(
					'Aco' =>
						array(
							'id' => '672',
							'parent_id' => '670',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1411',
							'rght' => '1412',
						),
				),
			669 =>
				array(
					'Aco' =>
						array(
							'id' => '673',
							'parent_id' => '670',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1413',
							'rght' => '1414',
						),
				),
			670 =>
				array(
					'Aco' =>
						array(
							'id' => '674',
							'parent_id' => '670',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1415',
							'rght' => '1416',
						),
				),
			671 =>
				array(
					'Aco' =>
						array(
							'id' => '675',
							'parent_id' => '670',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1417',
							'rght' => '1418',
						),
				),
			672 =>
				array(
					'Aco' =>
						array(
							'id' => '676',
							'parent_id' => '670',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1419',
							'rght' => '1420',
						),
				),
			673 =>
				array(
					'Aco' =>
						array(
							'id' => '677',
							'parent_id' => '670',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1421',
							'rght' => '1422',
						),
				),
			674 =>
				array(
					'Aco' =>
						array(
							'id' => '678',
							'parent_id' => '670',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1423',
							'rght' => '1424',
						),
				),
			675 =>
				array(
					'Aco' =>
						array(
							'id' => '679',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Systemdowntimes',
							'lft' => '1426',
							'rght' => '1451',
						),
				),
			676 =>
				array(
					'Aco' =>
						array(
							'id' => '680',
							'parent_id' => '679',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1427',
							'rght' => '1428',
						),
				),
			677 =>
				array(
					'Aco' =>
						array(
							'id' => '681',
							'parent_id' => '679',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'addHostdowntime',
							'lft' => '1429',
							'rght' => '1430',
						),
				),
			678 =>
				array(
					'Aco' =>
						array(
							'id' => '682',
							'parent_id' => '679',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'addHostgroupdowntime',
							'lft' => '1431',
							'rght' => '1432',
						),
				),
			679 =>
				array(
					'Aco' =>
						array(
							'id' => '683',
							'parent_id' => '679',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'addServicedowntime',
							'lft' => '1433',
							'rght' => '1434',
						),
				),
			680 =>
				array(
					'Aco' =>
						array(
							'id' => '684',
							'parent_id' => '679',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '1435',
							'rght' => '1436',
						),
				),
			681 =>
				array(
					'Aco' =>
						array(
							'id' => '685',
							'parent_id' => '679',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1437',
							'rght' => '1438',
						),
				),
			682 =>
				array(
					'Aco' =>
						array(
							'id' => '686',
							'parent_id' => '679',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1439',
							'rght' => '1440',
						),
				),
			683 =>
				array(
					'Aco' =>
						array(
							'id' => '687',
							'parent_id' => '679',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1441',
							'rght' => '1442',
						),
				),
			684 =>
				array(
					'Aco' =>
						array(
							'id' => '688',
							'parent_id' => '679',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1443',
							'rght' => '1444',
						),
				),
			685 =>
				array(
					'Aco' =>
						array(
							'id' => '689',
							'parent_id' => '679',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1445',
							'rght' => '1446',
						),
				),
			686 =>
				array(
					'Aco' =>
						array(
							'id' => '690',
							'parent_id' => '679',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1447',
							'rght' => '1448',
						),
				),
			687 =>
				array(
					'Aco' =>
						array(
							'id' => '691',
							'parent_id' => '679',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1449',
							'rght' => '1450',
						),
				),
			688 =>
				array(
					'Aco' =>
						array(
							'id' => '692',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Systemfailures',
							'lft' => '1452',
							'rght' => '1473',
						),
				),
			689 =>
				array(
					'Aco' =>
						array(
							'id' => '693',
							'parent_id' => '692',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1453',
							'rght' => '1454',
						),
				),
			690 =>
				array(
					'Aco' =>
						array(
							'id' => '694',
							'parent_id' => '692',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '1455',
							'rght' => '1456',
						),
				),
			691 =>
				array(
					'Aco' =>
						array(
							'id' => '695',
							'parent_id' => '692',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '1457',
							'rght' => '1458',
						),
				),
			692 =>
				array(
					'Aco' =>
						array(
							'id' => '696',
							'parent_id' => '692',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1459',
							'rght' => '1460',
						),
				),
			693 =>
				array(
					'Aco' =>
						array(
							'id' => '697',
							'parent_id' => '692',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1461',
							'rght' => '1462',
						),
				),
			694 =>
				array(
					'Aco' =>
						array(
							'id' => '698',
							'parent_id' => '692',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1463',
							'rght' => '1464',
						),
				),
			695 =>
				array(
					'Aco' =>
						array(
							'id' => '699',
							'parent_id' => '692',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1465',
							'rght' => '1466',
						),
				),
			696 =>
				array(
					'Aco' =>
						array(
							'id' => '700',
							'parent_id' => '692',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1467',
							'rght' => '1468',
						),
				),
			697 =>
				array(
					'Aco' =>
						array(
							'id' => '701',
							'parent_id' => '692',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1469',
							'rght' => '1470',
						),
				),
			698 =>
				array(
					'Aco' =>
						array(
							'id' => '702',
							'parent_id' => '692',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1471',
							'rght' => '1472',
						),
				),
			699 =>
				array(
					'Aco' =>
						array(
							'id' => '703',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Systemsettings',
							'lft' => '1474',
							'rght' => '1491',
						),
				),
			700 =>
				array(
					'Aco' =>
						array(
							'id' => '704',
							'parent_id' => '703',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1475',
							'rght' => '1476',
						),
				),
			701 =>
				array(
					'Aco' =>
						array(
							'id' => '705',
							'parent_id' => '703',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1477',
							'rght' => '1478',
						),
				),
			702 =>
				array(
					'Aco' =>
						array(
							'id' => '706',
							'parent_id' => '703',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1479',
							'rght' => '1480',
						),
				),
			703 =>
				array(
					'Aco' =>
						array(
							'id' => '707',
							'parent_id' => '703',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1481',
							'rght' => '1482',
						),
				),
			704 =>
				array(
					'Aco' =>
						array(
							'id' => '708',
							'parent_id' => '703',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1483',
							'rght' => '1484',
						),
				),
			705 =>
				array(
					'Aco' =>
						array(
							'id' => '709',
							'parent_id' => '703',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1485',
							'rght' => '1486',
						),
				),
			706 =>
				array(
					'Aco' =>
						array(
							'id' => '710',
							'parent_id' => '703',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1487',
							'rght' => '1488',
						),
				),
			707 =>
				array(
					'Aco' =>
						array(
							'id' => '711',
							'parent_id' => '703',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1489',
							'rght' => '1490',
						),
				),
			708 =>
				array(
					'Aco' =>
						array(
							'id' => '712',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Tenants',
							'lft' => '1492',
							'rght' => '1519',
						),
				),
			709 =>
				array(
					'Aco' =>
						array(
							'id' => '713',
							'parent_id' => '712',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1493',
							'rght' => '1494',
						),
				),
			710 =>
				array(
					'Aco' =>
						array(
							'id' => '714',
							'parent_id' => '712',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '1495',
							'rght' => '1496',
						),
				),
			711 =>
				array(
					'Aco' =>
						array(
							'id' => '715',
							'parent_id' => '712',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '1497',
							'rght' => '1498',
						),
				),
			712 =>
				array(
					'Aco' =>
						array(
							'id' => '716',
							'parent_id' => '712',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_delete',
							'lft' => '1499',
							'rght' => '1500',
						),
				),
			713 =>
				array(
					'Aco' =>
						array(
							'id' => '717',
							'parent_id' => '712',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '1501',
							'rght' => '1502',
						),
				),
			714 =>
				array(
					'Aco' =>
						array(
							'id' => '718',
							'parent_id' => '712',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1503',
							'rght' => '1504',
						),
				),
			715 =>
				array(
					'Aco' =>
						array(
							'id' => '719',
							'parent_id' => '712',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1505',
							'rght' => '1506',
						),
				),
			716 =>
				array(
					'Aco' =>
						array(
							'id' => '720',
							'parent_id' => '712',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1507',
							'rght' => '1508',
						),
				),
			717 =>
				array(
					'Aco' =>
						array(
							'id' => '721',
							'parent_id' => '712',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1509',
							'rght' => '1510',
						),
				),
			718 =>
				array(
					'Aco' =>
						array(
							'id' => '722',
							'parent_id' => '712',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1511',
							'rght' => '1512',
						),
				),
			719 =>
				array(
					'Aco' =>
						array(
							'id' => '723',
							'parent_id' => '712',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1513',
							'rght' => '1514',
						),
				),
			720 =>
				array(
					'Aco' =>
						array(
							'id' => '724',
							'parent_id' => '712',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1515',
							'rght' => '1516',
						),
				),
			721 =>
				array(
					'Aco' =>
						array(
							'id' => '725',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Timeperiods',
							'lft' => '1520',
							'rght' => '1551',
						),
				),
			722 =>
				array(
					'Aco' =>
						array(
							'id' => '726',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1521',
							'rght' => '1522',
						),
				),
			723 =>
				array(
					'Aco' =>
						array(
							'id' => '727',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '1523',
							'rght' => '1524',
						),
				),
			724 =>
				array(
					'Aco' =>
						array(
							'id' => '728',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '1525',
							'rght' => '1526',
						),
				),
			725 =>
				array(
					'Aco' =>
						array(
							'id' => '729',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '1527',
							'rght' => '1528',
						),
				),
			726 =>
				array(
					'Aco' =>
						array(
							'id' => '730',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_delete',
							'lft' => '1529',
							'rght' => '1530',
						),
				),
			727 =>
				array(
					'Aco' =>
						array(
							'id' => '731',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'browser',
							'lft' => '1531',
							'rght' => '1532',
						),
				),
			728 =>
				array(
					'Aco' =>
						array(
							'id' => '732',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'controller',
							'lft' => '1533',
							'rght' => '1534',
						),
				),
			729 =>
				array(
					'Aco' =>
						array(
							'id' => '733',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1535',
							'rght' => '1536',
						),
				),
			730 =>
				array(
					'Aco' =>
						array(
							'id' => '734',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1537',
							'rght' => '1538',
						),
				),
			731 =>
				array(
					'Aco' =>
						array(
							'id' => '735',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1539',
							'rght' => '1540',
						),
				),
			732 =>
				array(
					'Aco' =>
						array(
							'id' => '736',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1541',
							'rght' => '1542',
						),
				),
			733 =>
				array(
					'Aco' =>
						array(
							'id' => '737',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1543',
							'rght' => '1544',
						),
				),
			734 =>
				array(
					'Aco' =>
						array(
							'id' => '738',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1545',
							'rght' => '1546',
						),
				),
			735 =>
				array(
					'Aco' =>
						array(
							'id' => '739',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1547',
							'rght' => '1548',
						),
				),
			736 =>
				array(
					'Aco' =>
						array(
							'id' => '740',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Usergroups',
							'lft' => '1552',
							'rght' => '1577',
						),
				),
			737 =>
				array(
					'Aco' =>
						array(
							'id' => '741',
							'parent_id' => '740',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1553',
							'rght' => '1554',
						),
				),
			738 =>
				array(
					'Aco' =>
						array(
							'id' => '742',
							'parent_id' => '740',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '1555',
							'rght' => '1556',
						),
				),
			739 =>
				array(
					'Aco' =>
						array(
							'id' => '743',
							'parent_id' => '740',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '1557',
							'rght' => '1558',
						),
				),
			740 =>
				array(
					'Aco' =>
						array(
							'id' => '744',
							'parent_id' => '740',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '1559',
							'rght' => '1560',
						),
				),
			741 =>
				array(
					'Aco' =>
						array(
							'id' => '745',
							'parent_id' => '740',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1561',
							'rght' => '1562',
						),
				),
			742 =>
				array(
					'Aco' =>
						array(
							'id' => '746',
							'parent_id' => '740',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1563',
							'rght' => '1564',
						),
				),
			743 =>
				array(
					'Aco' =>
						array(
							'id' => '747',
							'parent_id' => '740',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1565',
							'rght' => '1566',
						),
				),
			744 =>
				array(
					'Aco' =>
						array(
							'id' => '748',
							'parent_id' => '740',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1567',
							'rght' => '1568',
						),
				),
			745 =>
				array(
					'Aco' =>
						array(
							'id' => '749',
							'parent_id' => '740',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1569',
							'rght' => '1570',
						),
				),
			746 =>
				array(
					'Aco' =>
						array(
							'id' => '750',
							'parent_id' => '740',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1571',
							'rght' => '1572',
						),
				),
			747 =>
				array(
					'Aco' =>
						array(
							'id' => '751',
							'parent_id' => '740',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1573',
							'rght' => '1574',
						),
				),
			748 =>
				array(
					'Aco' =>
						array(
							'id' => '752',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Users',
							'lft' => '1578',
							'rght' => '1607',
						),
				),
			749 =>
				array(
					'Aco' =>
						array(
							'id' => '753',
							'parent_id' => '752',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1579',
							'rght' => '1580',
						),
				),
			750 =>
				array(
					'Aco' =>
						array(
							'id' => '754',
							'parent_id' => '752',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'delete',
							'lft' => '1581',
							'rght' => '1582',
						),
				),
			751 =>
				array(
					'Aco' =>
						array(
							'id' => '755',
							'parent_id' => '752',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '1583',
							'rght' => '1584',
						),
				),
			752 =>
				array(
					'Aco' =>
						array(
							'id' => '756',
							'parent_id' => '752',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'edit',
							'lft' => '1585',
							'rght' => '1586',
						),
				),
			753 =>
				array(
					'Aco' =>
						array(
							'id' => '757',
							'parent_id' => '752',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'addFromLdap',
							'lft' => '1587',
							'rght' => '1588',
						),
				),
			754 =>
				array(
					'Aco' =>
						array(
							'id' => '758',
							'parent_id' => '752',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'resetPassword',
							'lft' => '1589',
							'rght' => '1590',
						),
				),
			755 =>
				array(
					'Aco' =>
						array(
							'id' => '759',
							'parent_id' => '752',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1591',
							'rght' => '1592',
						),
				),
			756 =>
				array(
					'Aco' =>
						array(
							'id' => '760',
							'parent_id' => '752',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1593',
							'rght' => '1594',
						),
				),
			757 =>
				array(
					'Aco' =>
						array(
							'id' => '761',
							'parent_id' => '752',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1595',
							'rght' => '1596',
						),
				),
			758 =>
				array(
					'Aco' =>
						array(
							'id' => '762',
							'parent_id' => '752',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1597',
							'rght' => '1598',
						),
				),
			759 =>
				array(
					'Aco' =>
						array(
							'id' => '763',
							'parent_id' => '752',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1599',
							'rght' => '1600',
						),
				),
			760 =>
				array(
					'Aco' =>
						array(
							'id' => '764',
							'parent_id' => '752',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1601',
							'rght' => '1602',
						),
				),
			761 =>
				array(
					'Aco' =>
						array(
							'id' => '765',
							'parent_id' => '752',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1603',
							'rght' => '1604',
						),
				),
			762 =>
				array(
					'Aco' =>
						array(
							'id' => '766',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'AclExtras',
							'lft' => '1608',
							'rght' => '1609',
						),
				),
			763 =>
				array(
					'Aco' =>
						array(
							'id' => '767',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Admin',
							'lft' => '1610',
							'rght' => '1611',
						),
				),
			764 =>
				array(
					'Aco' =>
						array(
							'id' => '809',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'BoostCake',
							'lft' => '1612',
							'rght' => '1635',
						),
				),
			765 =>
				array(
					'Aco' =>
						array(
							'id' => '810',
							'parent_id' => '809',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'BoostCake',
							'lft' => '1613',
							'rght' => '1634',
						),
				),
			766 =>
				array(
					'Aco' =>
						array(
							'id' => '811',
							'parent_id' => '810',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1614',
							'rght' => '1615',
						),
				),
			767 =>
				array(
					'Aco' =>
						array(
							'id' => '812',
							'parent_id' => '810',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'bootstrap2',
							'lft' => '1616',
							'rght' => '1617',
						),
				),
			768 =>
				array(
					'Aco' =>
						array(
							'id' => '813',
							'parent_id' => '810',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'bootstrap3',
							'lft' => '1618',
							'rght' => '1619',
						),
				),
			769 =>
				array(
					'Aco' =>
						array(
							'id' => '814',
							'parent_id' => '810',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1620',
							'rght' => '1621',
						),
				),
			770 =>
				array(
					'Aco' =>
						array(
							'id' => '815',
							'parent_id' => '810',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1622',
							'rght' => '1623',
						),
				),
			771 =>
				array(
					'Aco' =>
						array(
							'id' => '816',
							'parent_id' => '810',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1624',
							'rght' => '1625',
						),
				),
			772 =>
				array(
					'Aco' =>
						array(
							'id' => '817',
							'parent_id' => '810',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1626',
							'rght' => '1627',
						),
				),
			773 =>
				array(
					'Aco' =>
						array(
							'id' => '818',
							'parent_id' => '810',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1628',
							'rght' => '1629',
						),
				),
			774 =>
				array(
					'Aco' =>
						array(
							'id' => '819',
							'parent_id' => '810',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1630',
							'rght' => '1631',
						),
				),
			775 =>
				array(
					'Aco' =>
						array(
							'id' => '820',
							'parent_id' => '810',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1632',
							'rght' => '1633',
						),
				),
			776 =>
				array(
					'Aco' =>
						array(
							'id' => '821',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'CakePdf',
							'lft' => '1636',
							'rght' => '1637',
						),
				),
			777 =>
				array(
					'Aco' =>
						array(
							'id' => '822',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'ChatModule',
							'lft' => '1638',
							'rght' => '1657',
						),
				),
			778 =>
				array(
					'Aco' =>
						array(
							'id' => '823',
							'parent_id' => '822',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Chat',
							'lft' => '1639',
							'rght' => '1656',
						),
				),
			779 =>
				array(
					'Aco' =>
						array(
							'id' => '824',
							'parent_id' => '823',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1640',
							'rght' => '1641',
						),
				),
			780 =>
				array(
					'Aco' =>
						array(
							'id' => '825',
							'parent_id' => '823',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1642',
							'rght' => '1643',
						),
				),
			781 =>
				array(
					'Aco' =>
						array(
							'id' => '826',
							'parent_id' => '823',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1644',
							'rght' => '1645',
						),
				),
			782 =>
				array(
					'Aco' =>
						array(
							'id' => '827',
							'parent_id' => '823',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1646',
							'rght' => '1647',
						),
				),
			783 =>
				array(
					'Aco' =>
						array(
							'id' => '828',
							'parent_id' => '823',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1648',
							'rght' => '1649',
						),
				),
			784 =>
				array(
					'Aco' =>
						array(
							'id' => '829',
							'parent_id' => '823',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1650',
							'rght' => '1651',
						),
				),
			785 =>
				array(
					'Aco' =>
						array(
							'id' => '830',
							'parent_id' => '823',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1652',
							'rght' => '1653',
						),
				),
			786 =>
				array(
					'Aco' =>
						array(
							'id' => '831',
							'parent_id' => '823',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1654',
							'rght' => '1655',
						),
				),
			787 =>
				array(
					'Aco' =>
						array(
							'id' => '832',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'ClearCache',
							'lft' => '1658',
							'rght' => '1681',
						),
				),
			788 =>
				array(
					'Aco' =>
						array(
							'id' => '833',
							'parent_id' => '832',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'ClearCache',
							'lft' => '1659',
							'rght' => '1680',
						),
				),
			789 =>
				array(
					'Aco' =>
						array(
							'id' => '834',
							'parent_id' => '833',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'files',
							'lft' => '1660',
							'rght' => '1661',
						),
				),
			790 =>
				array(
					'Aco' =>
						array(
							'id' => '835',
							'parent_id' => '833',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'engines',
							'lft' => '1662',
							'rght' => '1663',
						),
				),
			791 =>
				array(
					'Aco' =>
						array(
							'id' => '836',
							'parent_id' => '833',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'groups',
							'lft' => '1664',
							'rght' => '1665',
						),
				),
			792 =>
				array(
					'Aco' =>
						array(
							'id' => '837',
							'parent_id' => '833',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1666',
							'rght' => '1667',
						),
				),
			793 =>
				array(
					'Aco' =>
						array(
							'id' => '838',
							'parent_id' => '833',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1668',
							'rght' => '1669',
						),
				),
			794 =>
				array(
					'Aco' =>
						array(
							'id' => '839',
							'parent_id' => '833',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1670',
							'rght' => '1671',
						),
				),
			795 =>
				array(
					'Aco' =>
						array(
							'id' => '840',
							'parent_id' => '833',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1672',
							'rght' => '1673',
						),
				),
			796 =>
				array(
					'Aco' =>
						array(
							'id' => '841',
							'parent_id' => '833',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1674',
							'rght' => '1675',
						),
				),
			797 =>
				array(
					'Aco' =>
						array(
							'id' => '842',
							'parent_id' => '833',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1676',
							'rght' => '1677',
						),
				),
			798 =>
				array(
					'Aco' =>
						array(
							'id' => '843',
							'parent_id' => '833',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1678',
							'rght' => '1679',
						),
				),
			799 =>
				array(
					'Aco' =>
						array(
							'id' => '844',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'DebugKit',
							'lft' => '1682',
							'rght' => '1703',
						),
				),
			800 =>
				array(
					'Aco' =>
						array(
							'id' => '845',
							'parent_id' => '844',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'ToolbarAccess',
							'lft' => '1683',
							'rght' => '1702',
						),
				),
			801 =>
				array(
					'Aco' =>
						array(
							'id' => '846',
							'parent_id' => '845',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'history_state',
							'lft' => '1684',
							'rght' => '1685',
						),
				),
			802 =>
				array(
					'Aco' =>
						array(
							'id' => '847',
							'parent_id' => '845',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'sql_explain',
							'lft' => '1686',
							'rght' => '1687',
						),
				),
			803 =>
				array(
					'Aco' =>
						array(
							'id' => '848',
							'parent_id' => '845',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1688',
							'rght' => '1689',
						),
				),
			804 =>
				array(
					'Aco' =>
						array(
							'id' => '849',
							'parent_id' => '845',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1690',
							'rght' => '1691',
						),
				),
			805 =>
				array(
					'Aco' =>
						array(
							'id' => '850',
							'parent_id' => '845',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1692',
							'rght' => '1693',
						),
				),
			806 =>
				array(
					'Aco' =>
						array(
							'id' => '851',
							'parent_id' => '845',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1694',
							'rght' => '1695',
						),
				),
			807 =>
				array(
					'Aco' =>
						array(
							'id' => '852',
							'parent_id' => '845',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1696',
							'rght' => '1697',
						),
				),
			808 =>
				array(
					'Aco' =>
						array(
							'id' => '853',
							'parent_id' => '845',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1698',
							'rght' => '1699',
						),
				),
			809 =>
				array(
					'Aco' =>
						array(
							'id' => '854',
							'parent_id' => '845',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1700',
							'rght' => '1701',
						),
				),
			810 =>
				array(
					'Aco' =>
						array(
							'id' => '855',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'ExampleModule',
							'lft' => '1704',
							'rght' => '1705',
						),
				),
			811 =>
				array(
					'Aco' =>
						array(
							'id' => '856',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Frontend',
							'lft' => '1706',
							'rght' => '1725',
						),
				),
			812 =>
				array(
					'Aco' =>
						array(
							'id' => '857',
							'parent_id' => '856',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'FrontendDependencies',
							'lft' => '1707',
							'rght' => '1724',
						),
				),
			813 =>
				array(
					'Aco' =>
						array(
							'id' => '858',
							'parent_id' => '857',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1708',
							'rght' => '1709',
						),
				),
			814 =>
				array(
					'Aco' =>
						array(
							'id' => '859',
							'parent_id' => '857',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1710',
							'rght' => '1711',
						),
				),
			815 =>
				array(
					'Aco' =>
						array(
							'id' => '860',
							'parent_id' => '857',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1712',
							'rght' => '1713',
						),
				),
			816 =>
				array(
					'Aco' =>
						array(
							'id' => '861',
							'parent_id' => '857',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1714',
							'rght' => '1715',
						),
				),
			817 =>
				array(
					'Aco' =>
						array(
							'id' => '862',
							'parent_id' => '857',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1716',
							'rght' => '1717',
						),
				),
			818 =>
				array(
					'Aco' =>
						array(
							'id' => '863',
							'parent_id' => '857',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1718',
							'rght' => '1719',
						),
				),
			819 =>
				array(
					'Aco' =>
						array(
							'id' => '864',
							'parent_id' => '857',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1720',
							'rght' => '1721',
						),
				),
			820 =>
				array(
					'Aco' =>
						array(
							'id' => '865',
							'parent_id' => '857',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1722',
							'rght' => '1723',
						),
				),
			821 =>
				array(
					'Aco' =>
						array(
							'id' => '866',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'ListFilter',
							'lft' => '1726',
							'rght' => '1727',
						),
				),
			822 =>
				array(
					'Aco' =>
						array(
							'id' => '867',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'NagiosModule',
							'lft' => '1728',
							'rght' => '1769',
						),
				),
			823 =>
				array(
					'Aco' =>
						array(
							'id' => '868',
							'parent_id' => '867',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Cmd',
							'lft' => '1729',
							'rght' => '1750',
						),
				),
			824 =>
				array(
					'Aco' =>
						array(
							'id' => '869',
							'parent_id' => '868',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1730',
							'rght' => '1731',
						),
				),
			825 =>
				array(
					'Aco' =>
						array(
							'id' => '870',
							'parent_id' => '868',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'submit',
							'lft' => '1732',
							'rght' => '1733',
						),
				),
			826 =>
				array(
					'Aco' =>
						array(
							'id' => '871',
							'parent_id' => '868',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1734',
							'rght' => '1735',
						),
				),
			827 =>
				array(
					'Aco' =>
						array(
							'id' => '872',
							'parent_id' => '868',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1736',
							'rght' => '1737',
						),
				),
			828 =>
				array(
					'Aco' =>
						array(
							'id' => '873',
							'parent_id' => '868',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1738',
							'rght' => '1739',
						),
				),
			829 =>
				array(
					'Aco' =>
						array(
							'id' => '874',
							'parent_id' => '868',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1740',
							'rght' => '1741',
						),
				),
			830 =>
				array(
					'Aco' =>
						array(
							'id' => '875',
							'parent_id' => '868',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1742',
							'rght' => '1743',
						),
				),
			831 =>
				array(
					'Aco' =>
						array(
							'id' => '876',
							'parent_id' => '868',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1744',
							'rght' => '1745',
						),
				),
			832 =>
				array(
					'Aco' =>
						array(
							'id' => '877',
							'parent_id' => '868',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1746',
							'rght' => '1747',
						),
				),
			833 =>
				array(
					'Aco' =>
						array(
							'id' => '878',
							'parent_id' => '867',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Nagios',
							'lft' => '1751',
							'rght' => '1768',
						),
				),
			834 =>
				array(
					'Aco' =>
						array(
							'id' => '879',
							'parent_id' => '878',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1752',
							'rght' => '1753',
						),
				),
			835 =>
				array(
					'Aco' =>
						array(
							'id' => '880',
							'parent_id' => '878',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1754',
							'rght' => '1755',
						),
				),
			836 =>
				array(
					'Aco' =>
						array(
							'id' => '881',
							'parent_id' => '878',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1756',
							'rght' => '1757',
						),
				),
			837 =>
				array(
					'Aco' =>
						array(
							'id' => '882',
							'parent_id' => '878',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1758',
							'rght' => '1759',
						),
				),
			838 =>
				array(
					'Aco' =>
						array(
							'id' => '883',
							'parent_id' => '878',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1760',
							'rght' => '1761',
						),
				),
			839 =>
				array(
					'Aco' =>
						array(
							'id' => '884',
							'parent_id' => '878',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1762',
							'rght' => '1763',
						),
				),
			840 =>
				array(
					'Aco' =>
						array(
							'id' => '885',
							'parent_id' => '878',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1764',
							'rght' => '1765',
						),
				),
			841 =>
				array(
					'Aco' =>
						array(
							'id' => '886',
							'parent_id' => '878',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1766',
							'rght' => '1767',
						),
				),
			842 =>
				array(
					'Aco' =>
						array(
							'id' => '887',
							'parent_id' => '12',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'testMail',
							'lft' => '41',
							'rght' => '42',
						),
				),
			843 =>
				array(
					'Aco' =>
						array(
							'id' => '888',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'addFromLdap',
							'lft' => '255',
							'rght' => '256',
						),
				),
			844 =>
				array(
					'Aco' =>
						array(
							'id' => '889',
							'parent_id' => '1',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'Dashboards',
							'lft' => '1770',
							'rght' => '1839',
						),
				),
			845 =>
				array(
					'Aco' =>
						array(
							'id' => '890',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'index',
							'lft' => '1771',
							'rght' => '1772',
						),
				),
			846 =>
				array(
					'Aco' =>
						array(
							'id' => '891',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'next',
							'lft' => '1773',
							'rght' => '1774',
						),
				),
			847 =>
				array(
					'Aco' =>
						array(
							'id' => '892',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '1775',
							'rght' => '1776',
						),
				),
			848 =>
				array(
					'Aco' =>
						array(
							'id' => '893',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'createTab',
							'lft' => '1777',
							'rght' => '1778',
						),
				),
			849 =>
				array(
					'Aco' =>
						array(
							'id' => '894',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'createTabFromSharing',
							'lft' => '1779',
							'rght' => '1780',
						),
				),
			850 =>
				array(
					'Aco' =>
						array(
							'id' => '895',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'updateSharedTab',
							'lft' => '1781',
							'rght' => '1782',
						),
				),
			851 =>
				array(
					'Aco' =>
						array(
							'id' => '896',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'disableUpdate',
							'lft' => '1783',
							'rght' => '1784',
						),
				),
			852 =>
				array(
					'Aco' =>
						array(
							'id' => '897',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'renameTab',
							'lft' => '1785',
							'rght' => '1786',
						),
				),
			853 =>
				array(
					'Aco' =>
						array(
							'id' => '898',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'deleteTab',
							'lft' => '1787',
							'rght' => '1788',
						),
				),
			854 =>
				array(
					'Aco' =>
						array(
							'id' => '899',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'restoreDefault',
							'lft' => '1789',
							'rght' => '1790',
						),
				),
			855 =>
				array(
					'Aco' =>
						array(
							'id' => '900',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'updateTitle',
							'lft' => '1791',
							'rght' => '1792',
						),
				),
			856 =>
				array(
					'Aco' =>
						array(
							'id' => '901',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'updateColor',
							'lft' => '1793',
							'rght' => '1794',
						),
				),
			857 =>
				array(
					'Aco' =>
						array(
							'id' => '902',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'updatePosition',
							'lft' => '1795',
							'rght' => '1796',
						),
				),
			858 =>
				array(
					'Aco' =>
						array(
							'id' => '903',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'deleteWidget',
							'lft' => '1797',
							'rght' => '1798',
						),
				),
			859 =>
				array(
					'Aco' =>
						array(
							'id' => '904',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'updateTabPosition',
							'lft' => '1799',
							'rght' => '1800',
						),
				),
			860 =>
				array(
					'Aco' =>
						array(
							'id' => '905',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'saveTabRotationInterval',
							'lft' => '1801',
							'rght' => '1802',
						),
				),
			861 =>
				array(
					'Aco' =>
						array(
							'id' => '906',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'startSharing',
							'lft' => '1803',
							'rght' => '1804',
						),
				),
			862 =>
				array(
					'Aco' =>
						array(
							'id' => '907',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'stopSharing',
							'lft' => '1805',
							'rght' => '1806',
						),
				),
			863 =>
				array(
					'Aco' =>
						array(
							'id' => '908',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'refresh',
							'lft' => '1807',
							'rght' => '1808',
						),
				),
			864 =>
				array(
					'Aco' =>
						array(
							'id' => '909',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'saveStatuslistSettings',
							'lft' => '1809',
							'rght' => '1810',
						),
				),
			865 =>
				array(
					'Aco' =>
						array(
							'id' => '910',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'saveTrafficLightService',
							'lft' => '1811',
							'rght' => '1812',
						),
				),
			866 =>
				array(
					'Aco' =>
						array(
							'id' => '911',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getTachoPerfdata',
							'lft' => '1813',
							'rght' => '1814',
						),
				),
			867 =>
				array(
					'Aco' =>
						array(
							'id' => '912',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'saveTachoConfig',
							'lft' => '1815',
							'rght' => '1816',
						),
				),
			868 =>
				array(
					'Aco' =>
						array(
							'id' => '913',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'isAuthorized',
							'lft' => '1817',
							'rght' => '1818',
						),
				),
			869 =>
				array(
					'Aco' =>
						array(
							'id' => '914',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'flashBack',
							'lft' => '1819',
							'rght' => '1820',
						),
				),
			870 =>
				array(
					'Aco' =>
						array(
							'id' => '915',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'setFlash',
							'lft' => '1821',
							'rght' => '1822',
						),
				),
			871 =>
				array(
					'Aco' =>
						array(
							'id' => '916',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'serviceResponse',
							'lft' => '1823',
							'rght' => '1824',
						),
				),
			872 =>
				array(
					'Aco' =>
						array(
							'id' => '917',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getNamedParameter',
							'lft' => '1825',
							'rght' => '1826',
						),
				),
			873 =>
				array(
					'Aco' =>
						array(
							'id' => '918',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allowedByContainerId',
							'lft' => '1827',
							'rght' => '1828',
						),
				),
			874 =>
				array(
					'Aco' =>
						array(
							'id' => '919',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'render403',
							'lft' => '1829',
							'rght' => '1830',
						),
				),
			875 =>
				array(
					'Aco' =>
						array(
							'id' => '920',
							'parent_id' => '218',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'broadcast',
							'lft' => '463',
							'rght' => '464',
						),
				),
			876 =>
				array(
					'Aco' =>
						array(
							'id' => '921',
							'parent_id' => '218',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'launchExport',
							'lft' => '465',
							'rght' => '466',
						),
				),
			877 =>
				array(
					'Aco' =>
						array(
							'id' => '922',
							'parent_id' => '218',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'verifyConfig',
							'lft' => '467',
							'rght' => '468',
						),
				),
			878 =>
				array(
					'Aco' =>
						array(
							'id' => '923',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allocateToMatchingHostgroup',
							'lft' => '1309',
							'rght' => '1310',
						),
				),
			879 =>
				array(
					'Aco' =>
						array(
							'id' => '924',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '193',
							'rght' => '194',
						),
				),
			880 =>
				array(
					'Aco' =>
						array(
							'id' => '925',
							'parent_id' => '81',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'usedBy',
							'lft' => '195',
							'rght' => '196',
						),
				),
			881 =>
				array(
					'Aco' =>
						array(
							'id' => '926',
							'parent_id' => '100',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '225',
							'rght' => '226',
						),
				),
			882 =>
				array(
					'Aco' =>
						array(
							'id' => '927',
							'parent_id' => '114',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '257',
							'rght' => '258',
						),
				),
			883 =>
				array(
					'Aco' =>
						array(
							'id' => '928',
							'parent_id' => '128',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'nest',
							'lft' => '285',
							'rght' => '286',
						),
				),
			884 =>
				array(
					'Aco' =>
						array(
							'id' => '929',
							'parent_id' => '128',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '287',
							'rght' => '288',
						),
				),
			885 =>
				array(
					'Aco' =>
						array(
							'id' => '930',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'saveMapId',
							'lft' => '1831',
							'rght' => '1832',
						),
				),
			886 =>
				array(
					'Aco' =>
						array(
							'id' => '931',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'saveGraphId',
							'lft' => '1833',
							'rght' => '1834',
						),
				),
			887 =>
				array(
					'Aco' =>
						array(
							'id' => '932',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'saveNotice',
							'lft' => '1835',
							'rght' => '1836',
						),
				),
			888 =>
				array(
					'Aco' =>
						array(
							'id' => '933',
							'parent_id' => '889',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'saveMap',
							'lft' => '1837',
							'rght' => '1838',
						),
				),
			889 =>
				array(
					'Aco' =>
						array(
							'id' => '934',
							'parent_id' => '173',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '377',
							'rght' => '378',
						),
				),
			890 =>
				array(
					'Aco' =>
						array(
							'id' => '935',
							'parent_id' => '236',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'add',
							'lft' => '513',
							'rght' => '514',
						),
				),
			891 =>
				array(
					'Aco' =>
						array(
							'id' => '936',
							'parent_id' => '249',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '549',
							'rght' => '550',
						),
				),
			892 =>
				array(
					'Aco' =>
						array(
							'id' => '937',
							'parent_id' => '275',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '595',
							'rght' => '596',
						),
				),
			893 =>
				array(
					'Aco' =>
						array(
							'id' => '938',
							'parent_id' => '288',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '623',
							'rght' => '624',
						),
				),
			894 =>
				array(
					'Aco' =>
						array(
							'id' => '939',
							'parent_id' => '301',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '659',
							'rght' => '660',
						),
				),
			895 =>
				array(
					'Aco' =>
						array(
							'id' => '940',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '737',
							'rght' => '738',
						),
				),
			896 =>
				array(
					'Aco' =>
						array(
							'id' => '941',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'allocateServiceTemplateGroup',
							'lft' => '739',
							'rght' => '740',
						),
				),
			897 =>
				array(
					'Aco' =>
						array(
							'id' => '942',
							'parent_id' => '318',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getServiceTemplatesfromGroup',
							'lft' => '741',
							'rght' => '742',
						),
				),
			898 =>
				array(
					'Aco' =>
						array(
							'id' => '943',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '777',
							'rght' => '778',
						),
				),
			899 =>
				array(
					'Aco' =>
						array(
							'id' => '944',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_delete',
							'lft' => '779',
							'rght' => '780',
						),
				),
			900 =>
				array(
					'Aco' =>
						array(
							'id' => '945',
							'parent_id' => '356',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'copy',
							'lft' => '781',
							'rght' => '782',
						),
				),
			901 =>
				array(
					'Aco' =>
						array(
							'id' => '946',
							'parent_id' => '384',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '829',
							'rght' => '830',
						),
				),
			902 =>
				array(
					'Aco' =>
						array(
							'id' => '947',
							'parent_id' => '529',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '1123',
							'rght' => '1124',
						),
				),
			903 =>
				array(
					'Aco' =>
						array(
							'id' => '948',
							'parent_id' => '542',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '1151',
							'rght' => '1152',
						),
				),
			904 =>
				array(
					'Aco' =>
						array(
							'id' => '949',
							'parent_id' => '555',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '1185',
							'rght' => '1186',
						),
				),
			905 =>
				array(
					'Aco' =>
						array(
							'id' => '950',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '1267',
							'rght' => '1268',
						),
				),
			906 =>
				array(
					'Aco' =>
						array(
							'id' => '951',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'getSelectedServices',
							'lft' => '1269',
							'rght' => '1270',
						),
				),
			907 =>
				array(
					'Aco' =>
						array(
							'id' => '952',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'showCheckMKLogfile',
							'lft' => '1271',
							'rght' => '1272',
						),
				),
			908 =>
				array(
					'Aco' =>
						array(
							'id' => '953',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'loadCheckMkLogfile',
							'lft' => '1273',
							'rght' => '1274',
						),
				),
			909 =>
				array(
					'Aco' =>
						array(
							'id' => '954',
							'parent_id' => '571',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'modifyCheckMkLogfile',
							'lft' => '1275',
							'rght' => '1276',
						),
				),
			910 =>
				array(
					'Aco' =>
						array(
							'id' => '955',
							'parent_id' => '611',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '1311',
							'rght' => '1312',
						),
				),
			911 =>
				array(
					'Aco' =>
						array(
							'id' => '956',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '1355',
							'rght' => '1356',
						),
				),
			912 =>
				array(
					'Aco' =>
						array(
							'id' => '957',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'mass_delete',
							'lft' => '1357',
							'rght' => '1358',
						),
				),
			913 =>
				array(
					'Aco' =>
						array(
							'id' => '958',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'copy',
							'lft' => '1359',
							'rght' => '1360',
						),
				),
			914 =>
				array(
					'Aco' =>
						array(
							'id' => '959',
							'parent_id' => '627',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'assignGroup',
							'lft' => '1361',
							'rght' => '1362',
						),
				),
			915 =>
				array(
					'Aco' =>
						array(
							'id' => '960',
							'parent_id' => '712',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '1517',
							'rght' => '1518',
						),
				),
			916 =>
				array(
					'Aco' =>
						array(
							'id' => '961',
							'parent_id' => '725',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '1549',
							'rght' => '1550',
						),
				),
			917 =>
				array(
					'Aco' =>
						array(
							'id' => '962',
							'parent_id' => '740',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '1575',
							'rght' => '1576',
						),
				),
			918 =>
				array(
					'Aco' =>
						array(
							'id' => '963',
							'parent_id' => '752',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'view',
							'lft' => '1605',
							'rght' => '1606',
						),
				),
			919 =>
				array(
					'Aco' =>
						array(
							'id' => '964',
							'parent_id' => '868',
							'model' => NULL,
							'foreign_key' => NULL,
							'alias' => 'ack',
							'lft' => '1748',
							'rght' => '1749',
						),
				),
		);
		return $data;
	}

}