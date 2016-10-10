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


class Aro extends Importer
{

	/**
	 * @property \Aro $Model
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
					'Aro' =>
						array(
							'id' => '1',
							'parent_id' => NULL,
							'model' => 'Usergroup',
							'foreign_key' => '1',
							'alias' => NULL,
							'lft' => '1',
							'rght' => '2',
						),
					'Aco' =>
						array(
							0 =>
								array(
									'id' => '3',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'service',
									'lft' => '3',
									'rght' => '4',
									'Permission' =>
										array(
											'id' => '2067',
											'aro_id' => '1',
											'aco_id' => '3',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							1 =>
								array(
									'id' => '4',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'host',
									'lft' => '5',
									'rght' => '6',
									'Permission' =>
										array(
											'id' => '2068',
											'aro_id' => '1',
											'aco_id' => '4',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							2 =>
								array(
									'id' => '13',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '23',
									'rght' => '24',
									'Permission' =>
										array(
											'id' => '2069',
											'aro_id' => '1',
											'aco_id' => '13',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							3 =>
								array(
									'id' => '14',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'debug',
									'lft' => '25',
									'rght' => '26',
									'Permission' =>
										array(
											'id' => '2070',
											'aro_id' => '1',
											'aco_id' => '14',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							4 =>
								array(
									'id' => '887',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'testMail',
									'lft' => '41',
									'rght' => '42',
									'Permission' =>
										array(
											'id' => '2071',
											'aro_id' => '1',
											'aco_id' => '887',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							5 =>
								array(
									'id' => '23',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '45',
									'rght' => '46',
									'Permission' =>
										array(
											'id' => '2072',
											'aro_id' => '1',
											'aco_id' => '23',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							6 =>
								array(
									'id' => '24',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '47',
									'rght' => '48',
									'Permission' =>
										array(
											'id' => '2073',
											'aro_id' => '1',
											'aco_id' => '24',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							7 =>
								array(
									'id' => '25',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '49',
									'rght' => '50',
									'Permission' =>
										array(
											'id' => '2074',
											'aro_id' => '1',
											'aco_id' => '25',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							8 =>
								array(
									'id' => '26',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '51',
									'rght' => '52',
									'Permission' =>
										array(
											'id' => '2075',
											'aro_id' => '1',
											'aco_id' => '26',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							9 =>
								array(
									'id' => '27',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadServiceDetails',
									'lft' => '53',
									'rght' => '54',
									'Permission' =>
										array(
											'id' => '2076',
											'aro_id' => '1',
											'aco_id' => '27',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							10 =>
								array(
									'id' => '28',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '55',
									'rght' => '56',
									'Permission' =>
										array(
											'id' => '2077',
											'aro_id' => '1',
											'aco_id' => '28',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							11 =>
								array(
									'id' => '37',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '73',
									'rght' => '74',
									'Permission' =>
										array(
											'id' => '2078',
											'aro_id' => '1',
											'aco_id' => '37',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							12 =>
								array(
									'id' => '38',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'tenantBrowser',
									'lft' => '75',
									'rght' => '76',
									'Permission' =>
										array(
											'id' => '2079',
											'aro_id' => '1',
											'aco_id' => '38',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							13 =>
								array(
									'id' => '50',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '93',
									'rght' => '94',
									'Permission' =>
										array(
											'id' => '2080',
											'aro_id' => '1',
											'aco_id' => '50',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							14 =>
								array(
									'id' => '51',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '95',
									'rght' => '96',
									'Permission' =>
										array(
											'id' => '2081',
											'aro_id' => '1',
											'aco_id' => '51',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							15 =>
								array(
									'id' => '54',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadHolidays',
									'lft' => '101',
									'rght' => '102',
									'Permission' =>
										array(
											'id' => '2082',
											'aro_id' => '1',
											'aco_id' => '54',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							16 =>
								array(
									'id' => '52',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '97',
									'rght' => '98',
									'Permission' =>
										array(
											'id' => '2083',
											'aro_id' => '1',
											'aco_id' => '52',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							17 =>
								array(
									'id' => '53',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '99',
									'rght' => '100',
									'Permission' =>
										array(
											'id' => '2084',
											'aro_id' => '1',
											'aco_id' => '53',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							18 =>
								array(
									'id' => '55',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_delete',
									'lft' => '103',
									'rght' => '104',
									'Permission' =>
										array(
											'id' => '2085',
											'aro_id' => '1',
											'aco_id' => '55',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							19 =>
								array(
									'id' => '64',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '121',
									'rght' => '122',
									'Permission' =>
										array(
											'id' => '2086',
											'aro_id' => '1',
											'aco_id' => '64',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							20 =>
								array(
									'id' => '73',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '139',
									'rght' => '140',
									'Permission' =>
										array(
											'id' => '2087',
											'aro_id' => '1',
											'aco_id' => '73',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							21 =>
								array(
									'id' => '82',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '157',
									'rght' => '158',
									'Permission' =>
										array(
											'id' => '2088',
											'aro_id' => '1',
											'aco_id' => '82',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							22 =>
								array(
									'id' => '924',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '193',
									'rght' => '194',
									'Permission' =>
										array(
											'id' => '2089',
											'aro_id' => '1',
											'aco_id' => '924',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							23 =>
								array(
									'id' => '83',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'hostchecks',
									'lft' => '159',
									'rght' => '160',
									'Permission' =>
										array(
											'id' => '2090',
											'aro_id' => '1',
											'aco_id' => '83',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							24 =>
								array(
									'id' => '84',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'notifications',
									'lft' => '161',
									'rght' => '162',
									'Permission' =>
										array(
											'id' => '2091',
											'aro_id' => '1',
											'aco_id' => '84',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							25 =>
								array(
									'id' => '85',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'handler',
									'lft' => '163',
									'rght' => '164',
									'Permission' =>
										array(
											'id' => '2092',
											'aro_id' => '1',
											'aco_id' => '85',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							26 =>
								array(
									'id' => '86',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '165',
									'rght' => '166',
									'Permission' =>
										array(
											'id' => '2093',
											'aro_id' => '1',
											'aco_id' => '86',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							27 =>
								array(
									'id' => '90',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'addCommandArg',
									'lft' => '173',
									'rght' => '174',
									'Permission' =>
										array(
											'id' => '2094',
											'aro_id' => '1',
											'aco_id' => '90',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							28 =>
								array(
									'id' => '91',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadMacros',
									'lft' => '175',
									'rght' => '176',
									'Permission' =>
										array(
											'id' => '2095',
											'aro_id' => '1',
											'aco_id' => '91',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							29 =>
								array(
									'id' => '87',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '167',
									'rght' => '168',
									'Permission' =>
										array(
											'id' => '2096',
											'aro_id' => '1',
											'aco_id' => '87',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							30 =>
								array(
									'id' => '88',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '169',
									'rght' => '170',
									'Permission' =>
										array(
											'id' => '2097',
											'aro_id' => '1',
											'aco_id' => '88',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							31 =>
								array(
									'id' => '89',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_delete',
									'lft' => '171',
									'rght' => '172',
									'Permission' =>
										array(
											'id' => '2098',
											'aro_id' => '1',
											'aco_id' => '89',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							32 =>
								array(
									'id' => '92',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'terminal',
									'lft' => '177',
									'rght' => '178',
									'Permission' =>
										array(
											'id' => '2099',
											'aro_id' => '1',
											'aco_id' => '92',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							33 =>
								array(
									'id' => '101',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '199',
									'rght' => '200',
									'Permission' =>
										array(
											'id' => '2100',
											'aro_id' => '1',
											'aco_id' => '101',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							34 =>
								array(
									'id' => '926',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '225',
									'rght' => '226',
									'Permission' =>
										array(
											'id' => '2101',
											'aro_id' => '1',
											'aco_id' => '926',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							35 =>
								array(
									'id' => '102',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '201',
									'rght' => '202',
									'Permission' =>
										array(
											'id' => '2102',
											'aro_id' => '1',
											'aco_id' => '102',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							36 =>
								array(
									'id' => '104',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadContacts',
									'lft' => '205',
									'rght' => '206',
									'Permission' =>
										array(
											'id' => '2103',
											'aro_id' => '1',
											'aco_id' => '104',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							37 =>
								array(
									'id' => '103',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '203',
									'rght' => '204',
									'Permission' =>
										array(
											'id' => '2104',
											'aro_id' => '1',
											'aco_id' => '103',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							38 =>
								array(
									'id' => '105',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '207',
									'rght' => '208',
									'Permission' =>
										array(
											'id' => '2105',
											'aro_id' => '1',
											'aco_id' => '105',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							39 =>
								array(
									'id' => '106',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_delete',
									'lft' => '209',
									'rght' => '210',
									'Permission' =>
										array(
											'id' => '2106',
											'aro_id' => '1',
											'aco_id' => '106',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							40 =>
								array(
									'id' => '115',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '229',
									'rght' => '230',
									'Permission' =>
										array(
											'id' => '2107',
											'aro_id' => '1',
											'aco_id' => '115',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							41 =>
								array(
									'id' => '927',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '257',
									'rght' => '258',
									'Permission' =>
										array(
											'id' => '2108',
											'aro_id' => '1',
											'aco_id' => '927',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							42 =>
								array(
									'id' => '116',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '231',
									'rght' => '232',
									'Permission' =>
										array(
											'id' => '2109',
											'aro_id' => '1',
											'aco_id' => '116',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							43 =>
								array(
									'id' => '120',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadTimeperiods',
									'lft' => '239',
									'rght' => '240',
									'Permission' =>
										array(
											'id' => '2110',
											'aro_id' => '1',
											'aco_id' => '120',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							44 =>
								array(
									'id' => '117',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '233',
									'rght' => '234',
									'Permission' =>
										array(
											'id' => '2111',
											'aro_id' => '1',
											'aco_id' => '117',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							45 =>
								array(
									'id' => '118',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '235',
									'rght' => '236',
									'Permission' =>
										array(
											'id' => '2112',
											'aro_id' => '1',
											'aco_id' => '118',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							46 =>
								array(
									'id' => '119',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_delete',
									'lft' => '237',
									'rght' => '238',
									'Permission' =>
										array(
											'id' => '2113',
											'aro_id' => '1',
											'aco_id' => '119',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							47 =>
								array(
									'id' => '888',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'addFromLdap',
									'lft' => '255',
									'rght' => '256',
									'Permission' =>
										array(
											'id' => '2114',
											'aro_id' => '1',
											'aco_id' => '888',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							48 =>
								array(
									'id' => '129',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '261',
									'rght' => '262',
									'Permission' =>
										array(
											'id' => '2115',
											'aro_id' => '1',
											'aco_id' => '129',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							49 =>
								array(
									'id' => '929',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '287',
									'rght' => '288',
									'Permission' =>
										array(
											'id' => '2116',
											'aro_id' => '1',
											'aco_id' => '929',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							50 =>
								array(
									'id' => '928',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'nest',
									'lft' => '285',
									'rght' => '286',
									'Permission' =>
										array(
											'id' => '2117',
											'aro_id' => '1',
											'aco_id' => '928',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							51 =>
								array(
									'id' => '130',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '263',
									'rght' => '264',
									'Permission' =>
										array(
											'id' => '2118',
											'aro_id' => '1',
											'aco_id' => '130',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							52 =>
								array(
									'id' => '133',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '269',
									'rght' => '270',
									'Permission' =>
										array(
											'id' => '2119',
											'aro_id' => '1',
											'aco_id' => '133',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							53 =>
								array(
									'id' => '142',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '291',
									'rght' => '292',
									'Permission' =>
										array(
											'id' => '2120',
											'aro_id' => '1',
											'aco_id' => '142',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							54 =>
								array(
									'id' => '143',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '293',
									'rght' => '294',
									'Permission' =>
										array(
											'id' => '2121',
											'aro_id' => '1',
											'aco_id' => '143',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							55 =>
								array(
									'id' => '146',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadTasksByPlugin',
									'lft' => '299',
									'rght' => '300',
									'Permission' =>
										array(
											'id' => '2122',
											'aro_id' => '1',
											'aco_id' => '146',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							56 =>
								array(
									'id' => '144',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '295',
									'rght' => '296',
									'Permission' =>
										array(
											'id' => '2123',
											'aro_id' => '1',
											'aco_id' => '144',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							57 =>
								array(
									'id' => '145',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '297',
									'rght' => '298',
									'Permission' =>
										array(
											'id' => '2124',
											'aro_id' => '1',
											'aco_id' => '145',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							58 =>
								array(
									'id' => '155',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '317',
									'rght' => '318',
									'Permission' =>
										array(
											'id' => '2125',
											'aro_id' => '1',
											'aco_id' => '155',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							59 =>
								array(
									'id' => '156',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'createPdfReport',
									'lft' => '319',
									'rght' => '320',
									'Permission' =>
										array(
											'id' => '2126',
											'aro_id' => '1',
											'aco_id' => '156',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							60 =>
								array(
									'id' => '165',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '337',
									'rght' => '338',
									'Permission' =>
										array(
											'id' => '2127',
											'aro_id' => '1',
											'aco_id' => '165',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							61 =>
								array(
									'id' => '174',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '355',
									'rght' => '356',
									'Permission' =>
										array(
											'id' => '2128',
											'aro_id' => '1',
											'aco_id' => '174',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							62 =>
								array(
									'id' => '934',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '377',
									'rght' => '378',
									'Permission' =>
										array(
											'id' => '2129',
											'aro_id' => '1',
											'aco_id' => '934',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							63 =>
								array(
									'id' => '175',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '357',
									'rght' => '358',
									'Permission' =>
										array(
											'id' => '2130',
											'aro_id' => '1',
											'aco_id' => '175',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							64 =>
								array(
									'id' => '176',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '359',
									'rght' => '360',
									'Permission' =>
										array(
											'id' => '2131',
											'aro_id' => '1',
											'aco_id' => '176',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							65 =>
								array(
									'id' => '177',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '361',
									'rght' => '362',
									'Permission' =>
										array(
											'id' => '2132',
											'aro_id' => '1',
											'aco_id' => '177',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							66 =>
								array(
									'id' => '186',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '381',
									'rght' => '382',
									'Permission' =>
										array(
											'id' => '2133',
											'aro_id' => '1',
											'aco_id' => '186',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							67 =>
								array(
									'id' => '187',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '383',
									'rght' => '384',
									'Permission' =>
										array(
											'id' => '2134',
											'aro_id' => '1',
											'aco_id' => '187',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							68 =>
								array(
									'id' => '188',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'wiki',
									'lft' => '385',
									'rght' => '386',
									'Permission' =>
										array(
											'id' => '2135',
											'aro_id' => '1',
											'aco_id' => '188',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							69 =>
								array(
									'id' => '197',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '403',
									'rght' => '404',
									'Permission' =>
										array(
											'id' => '2136',
											'aro_id' => '1',
											'aco_id' => '197',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							70 =>
								array(
									'id' => '198',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'createPdfReport',
									'lft' => '405',
									'rght' => '406',
									'Permission' =>
										array(
											'id' => '2137',
											'aro_id' => '1',
											'aco_id' => '198',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							71 =>
								array(
									'id' => '207',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'host',
									'lft' => '423',
									'rght' => '424',
									'Permission' =>
										array(
											'id' => '2138',
											'aro_id' => '1',
											'aco_id' => '207',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							72 =>
								array(
									'id' => '209',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '427',
									'rght' => '428',
									'Permission' =>
										array(
											'id' => '2139',
											'aro_id' => '1',
											'aco_id' => '209',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							73 =>
								array(
									'id' => '208',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'service',
									'lft' => '425',
									'rght' => '426',
									'Permission' =>
										array(
											'id' => '2140',
											'aro_id' => '1',
											'aco_id' => '208',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							74 =>
								array(
									'id' => '219',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '447',
									'rght' => '448',
									'Permission' =>
										array(
											'id' => '2141',
											'aro_id' => '1',
											'aco_id' => '219',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							75 =>
								array(
									'id' => '920',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'broadcast',
									'lft' => '463',
									'rght' => '464',
									'Permission' =>
										array(
											'id' => '2142',
											'aro_id' => '1',
											'aco_id' => '920',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							76 =>
								array(
									'id' => '921',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'launchExport',
									'lft' => '465',
									'rght' => '466',
									'Permission' =>
										array(
											'id' => '2143',
											'aro_id' => '1',
											'aco_id' => '921',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							77 =>
								array(
									'id' => '922',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'verifyConfig',
									'lft' => '467',
									'rght' => '468',
									'Permission' =>
										array(
											'id' => '2144',
											'aro_id' => '1',
											'aco_id' => '922',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							78 =>
								array(
									'id' => '237',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '489',
									'rght' => '490',
									'Permission' =>
										array(
											'id' => '2145',
											'aro_id' => '1',
											'aco_id' => '237',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							79 =>
								array(
									'id' => '238',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '491',
									'rght' => '492',
									'Permission' =>
										array(
											'id' => '2146',
											'aro_id' => '1',
											'aco_id' => '238',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							80 =>
								array(
									'id' => '239',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'display',
									'lft' => '493',
									'rght' => '494',
									'Permission' =>
										array(
											'id' => '2147',
											'aro_id' => '1',
											'aco_id' => '239',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							81 =>
								array(
									'id' => '240',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_delete',
									'lft' => '495',
									'rght' => '496',
									'Permission' =>
										array(
											'id' => '2148',
											'aro_id' => '1',
											'aco_id' => '240',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							82 =>
								array(
									'id' => '250',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '517',
									'rght' => '518',
									'Permission' =>
										array(
											'id' => '2149',
											'aro_id' => '1',
											'aco_id' => '250',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							83 =>
								array(
									'id' => '251',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'listing',
									'lft' => '519',
									'rght' => '520',
									'Permission' =>
										array(
											'id' => '2150',
											'aro_id' => '1',
											'aco_id' => '251',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							84 =>
								array(
									'id' => '253',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'saveGraphTemplate',
									'lft' => '523',
									'rght' => '524',
									'Permission' =>
										array(
											'id' => '2151',
											'aro_id' => '1',
											'aco_id' => '253',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							85 =>
								array(
									'id' => '254',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadGraphTemplate',
									'lft' => '525',
									'rght' => '526',
									'Permission' =>
										array(
											'id' => '2152',
											'aro_id' => '1',
											'aco_id' => '254',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							86 =>
								array(
									'id' => '252',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_delete',
									'lft' => '521',
									'rght' => '522',
									'Permission' =>
										array(
											'id' => '2153',
											'aro_id' => '1',
											'aco_id' => '252',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							87 =>
								array(
									'id' => '267',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '553',
									'rght' => '554',
									'Permission' =>
										array(
											'id' => '2154',
											'aro_id' => '1',
											'aco_id' => '267',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							88 =>
								array(
									'id' => '276',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '571',
									'rght' => '572',
									'Permission' =>
										array(
											'id' => '2155',
											'aro_id' => '1',
											'aco_id' => '276',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							89 =>
								array(
									'id' => '937',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '595',
									'rght' => '596',
									'Permission' =>
										array(
											'id' => '2156',
											'aro_id' => '1',
											'aco_id' => '937',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							90 =>
								array(
									'id' => '277',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '573',
									'rght' => '574',
									'Permission' =>
										array(
											'id' => '2157',
											'aro_id' => '1',
											'aco_id' => '277',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							91 =>
								array(
									'id' => '280',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadElementsByContainerId',
									'lft' => '579',
									'rght' => '580',
									'Permission' =>
										array(
											'id' => '2158',
											'aro_id' => '1',
											'aco_id' => '280',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							92 =>
								array(
									'id' => '278',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '575',
									'rght' => '576',
									'Permission' =>
										array(
											'id' => '2159',
											'aro_id' => '1',
											'aco_id' => '278',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							93 =>
								array(
									'id' => '279',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '577',
									'rght' => '578',
									'Permission' =>
										array(
											'id' => '2160',
											'aro_id' => '1',
											'aco_id' => '279',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							94 =>
								array(
									'id' => '289',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '599',
									'rght' => '600',
									'Permission' =>
										array(
											'id' => '2161',
											'aro_id' => '1',
											'aco_id' => '289',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							95 =>
								array(
									'id' => '938',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '623',
									'rght' => '624',
									'Permission' =>
										array(
											'id' => '2162',
											'aro_id' => '1',
											'aco_id' => '938',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							96 =>
								array(
									'id' => '290',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '601',
									'rght' => '602',
									'Permission' =>
										array(
											'id' => '2163',
											'aro_id' => '1',
											'aco_id' => '290',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							97 =>
								array(
									'id' => '293',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadElementsByContainerId',
									'lft' => '607',
									'rght' => '608',
									'Permission' =>
										array(
											'id' => '2164',
											'aro_id' => '1',
											'aco_id' => '293',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							98 =>
								array(
									'id' => '291',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '603',
									'rght' => '604',
									'Permission' =>
										array(
											'id' => '2165',
											'aro_id' => '1',
											'aco_id' => '291',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							99 =>
								array(
									'id' => '292',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '605',
									'rght' => '606',
									'Permission' =>
										array(
											'id' => '2166',
											'aro_id' => '1',
											'aco_id' => '292',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							100 =>
								array(
									'id' => '302',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '627',
									'rght' => '628',
									'Permission' =>
										array(
											'id' => '2167',
											'aro_id' => '1',
											'aco_id' => '302',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							101 =>
								array(
									'id' => '310',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'listToPdf',
									'lft' => '643',
									'rght' => '644',
									'Permission' =>
										array(
											'id' => '2168',
											'aro_id' => '1',
											'aco_id' => '310',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							102 =>
								array(
									'id' => '939',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '659',
									'rght' => '660',
									'Permission' =>
										array(
											'id' => '2169',
											'aro_id' => '1',
											'aco_id' => '939',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							103 =>
								array(
									'id' => '303',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'extended',
									'lft' => '629',
									'rght' => '630',
									'Permission' =>
										array(
											'id' => '2170',
											'aro_id' => '1',
											'aco_id' => '303',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							104 =>
								array(
									'id' => '304',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '631',
									'rght' => '632',
									'Permission' =>
										array(
											'id' => '2171',
											'aro_id' => '1',
											'aco_id' => '304',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							105 =>
								array(
									'id' => '306',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadHosts',
									'lft' => '635',
									'rght' => '636',
									'Permission' =>
										array(
											'id' => '2172',
											'aro_id' => '1',
											'aco_id' => '306',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							106 =>
								array(
									'id' => '305',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '633',
									'rght' => '634',
									'Permission' =>
										array(
											'id' => '2173',
											'aro_id' => '1',
											'aco_id' => '305',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							107 =>
								array(
									'id' => '308',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_add',
									'lft' => '639',
									'rght' => '640',
									'Permission' =>
										array(
											'id' => '2174',
											'aro_id' => '1',
											'aco_id' => '308',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							108 =>
								array(
									'id' => '307',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '637',
									'rght' => '638',
									'Permission' =>
										array(
											'id' => '2175',
											'aro_id' => '1',
											'aco_id' => '307',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							109 =>
								array(
									'id' => '309',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_delete',
									'lft' => '641',
									'rght' => '642',
									'Permission' =>
										array(
											'id' => '2176',
											'aro_id' => '1',
											'aco_id' => '309',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							110 =>
								array(
									'id' => '319',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '663',
									'rght' => '664',
									'Permission' =>
										array(
											'id' => '2177',
											'aro_id' => '1',
											'aco_id' => '319',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							111 =>
								array(
									'id' => '343',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getHostByAjax',
									'lft' => '711',
									'rght' => '712',
									'Permission' =>
										array(
											'id' => '2178',
											'aro_id' => '1',
											'aco_id' => '343',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							112 =>
								array(
									'id' => '344',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'listToPdf',
									'lft' => '713',
									'rght' => '714',
									'Permission' =>
										array(
											'id' => '2179',
											'aro_id' => '1',
											'aco_id' => '344',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							113 =>
								array(
									'id' => '320',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'notMonitored',
									'lft' => '665',
									'rght' => '666',
									'Permission' =>
										array(
											'id' => '2180',
											'aro_id' => '1',
											'aco_id' => '320',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							114 =>
								array(
									'id' => '321',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '667',
									'rght' => '668',
									'Permission' =>
										array(
											'id' => '2181',
											'aro_id' => '1',
											'aco_id' => '321',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							115 =>
								array(
									'id' => '334',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'gethostbyname',
									'lft' => '693',
									'rght' => '694',
									'Permission' =>
										array(
											'id' => '2182',
											'aro_id' => '1',
											'aco_id' => '334',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							116 =>
								array(
									'id' => '335',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'gethostbyaddr',
									'lft' => '695',
									'rght' => '696',
									'Permission' =>
										array(
											'id' => '2183',
											'aro_id' => '1',
											'aco_id' => '335',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							117 =>
								array(
									'id' => '336',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadHosttemplate',
									'lft' => '697',
									'rght' => '698',
									'Permission' =>
										array(
											'id' => '2184',
											'aro_id' => '1',
											'aco_id' => '336',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							118 =>
								array(
									'id' => '337',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'addCustomMacro',
									'lft' => '699',
									'rght' => '700',
									'Permission' =>
										array(
											'id' => '2185',
											'aro_id' => '1',
											'aco_id' => '337',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							119 =>
								array(
									'id' => '338',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadTemplateMacros',
									'lft' => '701',
									'rght' => '702',
									'Permission' =>
										array(
											'id' => '2186',
											'aro_id' => '1',
											'aco_id' => '338',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							120 =>
								array(
									'id' => '339',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadParametersByCommandId',
									'lft' => '703',
									'rght' => '704',
									'Permission' =>
										array(
											'id' => '2187',
											'aro_id' => '1',
											'aco_id' => '339',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							121 =>
								array(
									'id' => '340',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadArguments',
									'lft' => '705',
									'rght' => '706',
									'Permission' =>
										array(
											'id' => '2188',
											'aro_id' => '1',
											'aco_id' => '340',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							122 =>
								array(
									'id' => '341',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadArgumentsAdd',
									'lft' => '707',
									'rght' => '708',
									'Permission' =>
										array(
											'id' => '2189',
											'aro_id' => '1',
											'aco_id' => '341',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							123 =>
								array(
									'id' => '342',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadHosttemplatesArguments',
									'lft' => '709',
									'rght' => '710',
									'Permission' =>
										array(
											'id' => '2190',
											'aro_id' => '1',
											'aco_id' => '342',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							124 =>
								array(
									'id' => '346',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'addParentHosts',
									'lft' => '717',
									'rght' => '718',
									'Permission' =>
										array(
											'id' => '2191',
											'aro_id' => '1',
											'aco_id' => '346',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							125 =>
								array(
									'id' => '347',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadElementsByContainerId',
									'lft' => '719',
									'rght' => '720',
									'Permission' =>
										array(
											'id' => '2192',
											'aro_id' => '1',
											'aco_id' => '347',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							126 =>
								array(
									'id' => '322',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'sharing',
									'lft' => '669',
									'rght' => '670',
									'Permission' =>
										array(
											'id' => '2193',
											'aro_id' => '1',
											'aco_id' => '322',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							127 =>
								array(
									'id' => '323',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit_details',
									'lft' => '671',
									'rght' => '672',
									'Permission' =>
										array(
											'id' => '2194',
											'aro_id' => '1',
											'aco_id' => '323',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							128 =>
								array(
									'id' => '324',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '673',
									'rght' => '674',
									'Permission' =>
										array(
											'id' => '2195',
											'aro_id' => '1',
											'aco_id' => '324',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							129 =>
								array(
									'id' => '325',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'disabled',
									'lft' => '675',
									'rght' => '676',
									'Permission' =>
										array(
											'id' => '2196',
											'aro_id' => '1',
											'aco_id' => '325',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							130 =>
								array(
									'id' => '326',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'deactivate',
									'lft' => '677',
									'rght' => '678',
									'Permission' =>
										array(
											'id' => '2197',
											'aro_id' => '1',
											'aco_id' => '326',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							131 =>
								array(
									'id' => '327',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_deactivate',
									'lft' => '679',
									'rght' => '680',
									'Permission' =>
										array(
											'id' => '2198',
											'aro_id' => '1',
											'aco_id' => '327',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							132 =>
								array(
									'id' => '328',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'enable',
									'lft' => '681',
									'rght' => '682',
									'Permission' =>
										array(
											'id' => '2199',
											'aro_id' => '1',
											'aco_id' => '328',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							133 =>
								array(
									'id' => '329',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '683',
									'rght' => '684',
									'Permission' =>
										array(
											'id' => '2200',
											'aro_id' => '1',
											'aco_id' => '329',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							134 =>
								array(
									'id' => '330',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_delete',
									'lft' => '685',
									'rght' => '686',
									'Permission' =>
										array(
											'id' => '2201',
											'aro_id' => '1',
											'aco_id' => '330',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							135 =>
								array(
									'id' => '331',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'copy',
									'lft' => '687',
									'rght' => '688',
									'Permission' =>
										array(
											'id' => '2202',
											'aro_id' => '1',
											'aco_id' => '331',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							136 =>
								array(
									'id' => '332',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'browser',
									'lft' => '689',
									'rght' => '690',
									'Permission' =>
										array(
											'id' => '2203',
											'aro_id' => '1',
											'aco_id' => '332',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							137 =>
								array(
									'id' => '333',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'longOutputByUuid',
									'lft' => '691',
									'rght' => '692',
									'Permission' =>
										array(
											'id' => '2204',
											'aro_id' => '1',
											'aco_id' => '333',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							138 =>
								array(
									'id' => '345',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'ping',
									'lft' => '715',
									'rght' => '716',
									'Permission' =>
										array(
											'id' => '2205',
											'aro_id' => '1',
											'aco_id' => '345',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							139 =>
								array(
									'id' => '348',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'checkcommand',
									'lft' => '721',
									'rght' => '722',
									'Permission' =>
										array(
											'id' => '2206',
											'aro_id' => '1',
											'aco_id' => '348',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							140 =>
								array(
									'id' => '357',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '745',
									'rght' => '746',
									'Permission' =>
										array(
											'id' => '2207',
											'aro_id' => '1',
											'aco_id' => '357',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							141 =>
								array(
									'id' => '943',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '777',
									'rght' => '778',
									'Permission' =>
										array(
											'id' => '2208',
											'aro_id' => '1',
											'aco_id' => '943',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							142 =>
								array(
									'id' => '358',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '747',
									'rght' => '748',
									'Permission' =>
										array(
											'id' => '2209',
											'aro_id' => '1',
											'aco_id' => '358',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							143 =>
								array(
									'id' => '361',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'addCustomMacro',
									'lft' => '753',
									'rght' => '754',
									'Permission' =>
										array(
											'id' => '2210',
											'aro_id' => '1',
											'aco_id' => '361',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							144 =>
								array(
									'id' => '362',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadArguments',
									'lft' => '755',
									'rght' => '756',
									'Permission' =>
										array(
											'id' => '2211',
											'aro_id' => '1',
											'aco_id' => '362',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							145 =>
								array(
									'id' => '363',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadArgumentsAdd',
									'lft' => '757',
									'rght' => '758',
									'Permission' =>
										array(
											'id' => '2212',
											'aro_id' => '1',
											'aco_id' => '363',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							146 =>
								array(
									'id' => '365',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadElementsByContainerId',
									'lft' => '761',
									'rght' => '762',
									'Permission' =>
										array(
											'id' => '2213',
											'aro_id' => '1',
											'aco_id' => '365',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							147 =>
								array(
									'id' => '359',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '749',
									'rght' => '750',
									'Permission' =>
										array(
											'id' => '2214',
											'aro_id' => '1',
											'aco_id' => '359',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							148 =>
								array(
									'id' => '360',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '751',
									'rght' => '752',
									'Permission' =>
										array(
											'id' => '2215',
											'aro_id' => '1',
											'aco_id' => '360',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							149 =>
								array(
									'id' => '364',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'usedBy',
									'lft' => '759',
									'rght' => '760',
									'Permission' =>
										array(
											'id' => '2216',
											'aro_id' => '1',
											'aco_id' => '364',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							150 =>
								array(
									'id' => '374',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '785',
									'rght' => '786',
									'Permission' =>
										array(
											'id' => '2217',
											'aro_id' => '1',
											'aco_id' => '374',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							151 =>
								array(
									'id' => '375',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'createPdfReport',
									'lft' => '787',
									'rght' => '788',
									'Permission' =>
										array(
											'id' => '2218',
											'aro_id' => '1',
											'aco_id' => '375',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							152 =>
								array(
									'id' => '376',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'expandServices',
									'lft' => '789',
									'rght' => '790',
									'Permission' =>
										array(
											'id' => '2219',
											'aro_id' => '1',
											'aco_id' => '376',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							153 =>
								array(
									'id' => '385',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '807',
									'rght' => '808',
									'Permission' =>
										array(
											'id' => '2220',
											'aro_id' => '1',
											'aco_id' => '385',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							154 =>
								array(
									'id' => '946',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '829',
									'rght' => '830',
									'Permission' =>
										array(
											'id' => '2221',
											'aro_id' => '1',
											'aco_id' => '946',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							155 =>
								array(
									'id' => '386',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '809',
									'rght' => '810',
									'Permission' =>
										array(
											'id' => '2222',
											'aro_id' => '1',
											'aco_id' => '386',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							156 =>
								array(
									'id' => '387',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '811',
									'rght' => '812',
									'Permission' =>
										array(
											'id' => '2223',
											'aro_id' => '1',
											'aco_id' => '387',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							157 =>
								array(
									'id' => '388',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '813',
									'rght' => '814',
									'Permission' =>
										array(
											'id' => '2224',
											'aro_id' => '1',
											'aco_id' => '388',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							158 =>
								array(
									'id' => '397',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '833',
									'rght' => '834',
									'Permission' =>
										array(
											'id' => '2225',
											'aro_id' => '1',
											'aco_id' => '397',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							159 =>
								array(
									'id' => '406',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '851',
									'rght' => '852',
									'Permission' =>
										array(
											'id' => '2226',
											'aro_id' => '1',
											'aco_id' => '406',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							160 =>
								array(
									'id' => '407',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'login',
									'lft' => '853',
									'rght' => '854',
									'Permission' =>
										array(
											'id' => '2227',
											'aro_id' => '1',
											'aco_id' => '407',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							161 =>
								array(
									'id' => '408',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'onetimetoken',
									'lft' => '855',
									'rght' => '856',
									'Permission' =>
										array(
											'id' => '2228',
											'aro_id' => '1',
											'aco_id' => '408',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							162 =>
								array(
									'id' => '409',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'logout',
									'lft' => '857',
									'rght' => '858',
									'Permission' =>
										array(
											'id' => '2229',
											'aro_id' => '1',
											'aco_id' => '409',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							163 =>
								array(
									'id' => '410',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'auth_required',
									'lft' => '859',
									'rght' => '860',
									'Permission' =>
										array(
											'id' => '2230',
											'aro_id' => '1',
											'aco_id' => '410',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							164 =>
								array(
									'id' => '411',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'lock',
									'lft' => '861',
									'rght' => '862',
									'Permission' =>
										array(
											'id' => '2231',
											'aro_id' => '1',
											'aco_id' => '411',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							165 =>
								array(
									'id' => '420',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '879',
									'rght' => '880',
									'Permission' =>
										array(
											'id' => '2232',
											'aro_id' => '1',
											'aco_id' => '420',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							166 =>
								array(
									'id' => '421',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'addMacro',
									'lft' => '881',
									'rght' => '882',
									'Permission' =>
										array(
											'id' => '2233',
											'aro_id' => '1',
											'aco_id' => '421',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							167 =>
								array(
									'id' => '430',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '899',
									'rght' => '900',
									'Permission' =>
										array(
											'id' => '2234',
											'aro_id' => '1',
											'aco_id' => '430',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							168 =>
								array(
									'id' => '439',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '917',
									'rght' => '918',
									'Permission' =>
										array(
											'id' => '2235',
											'aro_id' => '1',
											'aco_id' => '439',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							169 =>
								array(
									'id' => '440',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'hostNotification',
									'lft' => '919',
									'rght' => '920',
									'Permission' =>
										array(
											'id' => '2236',
											'aro_id' => '1',
											'aco_id' => '440',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							170 =>
								array(
									'id' => '441',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceNotification',
									'lft' => '921',
									'rght' => '922',
									'Permission' =>
										array(
											'id' => '2237',
											'aro_id' => '1',
											'aco_id' => '441',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							171 =>
								array(
									'id' => '450',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '939',
									'rght' => '940',
									'Permission' =>
										array(
											'id' => '2238',
											'aro_id' => '1',
											'aco_id' => '450',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							172 =>
								array(
									'id' => '470',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '979',
									'rght' => '980',
									'Permission' =>
										array(
											'id' => '2239',
											'aro_id' => '1',
											'aco_id' => '470',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							173 =>
								array(
									'id' => '471',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '981',
									'rght' => '982',
									'Permission' =>
										array(
											'id' => '2240',
											'aro_id' => '1',
											'aco_id' => '471',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							174 =>
								array(
									'id' => '481',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1001',
									'rght' => '1002',
									'Permission' =>
										array(
											'id' => '2241',
											'aro_id' => '1',
											'aco_id' => '481',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							175 =>
								array(
									'id' => '490',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1019',
									'rght' => '1020',
									'Permission' =>
										array(
											'id' => '2242',
											'aro_id' => '1',
											'aco_id' => '490',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							176 =>
								array(
									'id' => '491',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'check',
									'lft' => '1021',
									'rght' => '1022',
									'Permission' =>
										array(
											'id' => '2243',
											'aro_id' => '1',
											'aco_id' => '491',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							177 =>
								array(
									'id' => '521',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1081',
									'rght' => '1082',
									'Permission' =>
										array(
											'id' => '2244',
											'aro_id' => '1',
											'aco_id' => '521',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							178 =>
								array(
									'id' => '530',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1099',
									'rght' => '1100',
									'Permission' =>
										array(
											'id' => '2245',
											'aro_id' => '1',
											'aco_id' => '530',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							179 =>
								array(
									'id' => '947',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1123',
									'rght' => '1124',
									'Permission' =>
										array(
											'id' => '2246',
											'aro_id' => '1',
											'aco_id' => '947',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							180 =>
								array(
									'id' => '531',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '1101',
									'rght' => '1102',
									'Permission' =>
										array(
											'id' => '2247',
											'aro_id' => '1',
											'aco_id' => '531',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							181 =>
								array(
									'id' => '532',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '1103',
									'rght' => '1104',
									'Permission' =>
										array(
											'id' => '2248',
											'aro_id' => '1',
											'aco_id' => '532',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							182 =>
								array(
									'id' => '534',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadElementsByContainerId',
									'lft' => '1107',
									'rght' => '1108',
									'Permission' =>
										array(
											'id' => '2249',
											'aro_id' => '1',
											'aco_id' => '534',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							183 =>
								array(
									'id' => '533',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '1105',
									'rght' => '1106',
									'Permission' =>
										array(
											'id' => '2250',
											'aro_id' => '1',
											'aco_id' => '533',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							184 =>
								array(
									'id' => '543',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1127',
									'rght' => '1128',
									'Permission' =>
										array(
											'id' => '2251',
											'aro_id' => '1',
											'aco_id' => '543',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							185 =>
								array(
									'id' => '948',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1151',
									'rght' => '1152',
									'Permission' =>
										array(
											'id' => '2252',
											'aro_id' => '1',
											'aco_id' => '948',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							186 =>
								array(
									'id' => '544',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '1129',
									'rght' => '1130',
									'Permission' =>
										array(
											'id' => '2253',
											'aro_id' => '1',
											'aco_id' => '544',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							187 =>
								array(
									'id' => '547',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadElementsByContainerId',
									'lft' => '1135',
									'rght' => '1136',
									'Permission' =>
										array(
											'id' => '2254',
											'aro_id' => '1',
											'aco_id' => '547',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							188 =>
								array(
									'id' => '545',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '1131',
									'rght' => '1132',
									'Permission' =>
										array(
											'id' => '2255',
											'aro_id' => '1',
											'aco_id' => '545',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							189 =>
								array(
									'id' => '546',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '1133',
									'rght' => '1134',
									'Permission' =>
										array(
											'id' => '2256',
											'aro_id' => '1',
											'aco_id' => '546',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							190 =>
								array(
									'id' => '556',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1155',
									'rght' => '1156',
									'Permission' =>
										array(
											'id' => '2257',
											'aro_id' => '1',
											'aco_id' => '556',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							191 =>
								array(
									'id' => '563',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'listToPdf',
									'lft' => '1169',
									'rght' => '1170',
									'Permission' =>
										array(
											'id' => '2258',
											'aro_id' => '1',
											'aco_id' => '563',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							192 =>
								array(
									'id' => '949',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1185',
									'rght' => '1186',
									'Permission' =>
										array(
											'id' => '2259',
											'aro_id' => '1',
											'aco_id' => '949',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							193 =>
								array(
									'id' => '557',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '1157',
									'rght' => '1158',
									'Permission' =>
										array(
											'id' => '2260',
											'aro_id' => '1',
											'aco_id' => '557',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							194 =>
								array(
									'id' => '559',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadServices',
									'lft' => '1161',
									'rght' => '1162',
									'Permission' =>
										array(
											'id' => '2261',
											'aro_id' => '1',
											'aco_id' => '559',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							195 =>
								array(
									'id' => '558',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '1159',
									'rght' => '1160',
									'Permission' =>
										array(
											'id' => '2262',
											'aro_id' => '1',
											'aco_id' => '558',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							196 =>
								array(
									'id' => '562',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_add',
									'lft' => '1167',
									'rght' => '1168',
									'Permission' =>
										array(
											'id' => '2263',
											'aro_id' => '1',
											'aco_id' => '562',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							197 =>
								array(
									'id' => '560',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '1163',
									'rght' => '1164',
									'Permission' =>
										array(
											'id' => '2264',
											'aro_id' => '1',
											'aco_id' => '560',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							198 =>
								array(
									'id' => '561',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_delete',
									'lft' => '1165',
									'rght' => '1166',
									'Permission' =>
										array(
											'id' => '2265',
											'aro_id' => '1',
											'aco_id' => '561',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							199 =>
								array(
									'id' => '572',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1189',
									'rght' => '1190',
									'Permission' =>
										array(
											'id' => '2266',
											'aro_id' => '1',
											'aco_id' => '572',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							200 =>
								array(
									'id' => '602',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'listToPdf',
									'lft' => '1249',
									'rght' => '1250',
									'Permission' =>
										array(
											'id' => '2267',
											'aro_id' => '1',
											'aco_id' => '602',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							201 =>
								array(
									'id' => '590',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadServices',
									'lft' => '1225',
									'rght' => '1226',
									'Permission' =>
										array(
											'id' => '2268',
											'aro_id' => '1',
											'aco_id' => '590',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							202 =>
								array(
									'id' => '950',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1267',
									'rght' => '1268',
									'Permission' =>
										array(
											'id' => '2269',
											'aro_id' => '1',
											'aco_id' => '950',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							203 =>
								array(
									'id' => '573',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'notMonitored',
									'lft' => '1191',
									'rght' => '1192',
									'Permission' =>
										array(
											'id' => '2270',
											'aro_id' => '1',
											'aco_id' => '573',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							204 =>
								array(
									'id' => '574',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'disabled',
									'lft' => '1193',
									'rght' => '1194',
									'Permission' =>
										array(
											'id' => '2271',
											'aro_id' => '1',
											'aco_id' => '574',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							205 =>
								array(
									'id' => '575',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '1195',
									'rght' => '1196',
									'Permission' =>
										array(
											'id' => '2272',
											'aro_id' => '1',
											'aco_id' => '575',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							206 =>
								array(
									'id' => '583',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadContactsAndContactgroups',
									'lft' => '1211',
									'rght' => '1212',
									'Permission' =>
										array(
											'id' => '2273',
											'aro_id' => '1',
											'aco_id' => '583',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							207 =>
								array(
									'id' => '584',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadParametersByCommandId',
									'lft' => '1213',
									'rght' => '1214',
									'Permission' =>
										array(
											'id' => '2274',
											'aro_id' => '1',
											'aco_id' => '584',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							208 =>
								array(
									'id' => '585',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadNagParametersByCommandId',
									'lft' => '1215',
									'rght' => '1216',
									'Permission' =>
										array(
											'id' => '2275',
											'aro_id' => '1',
											'aco_id' => '585',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							209 =>
								array(
									'id' => '586',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadArgumentsAdd',
									'lft' => '1217',
									'rght' => '1218',
									'Permission' =>
										array(
											'id' => '2276',
											'aro_id' => '1',
											'aco_id' => '586',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							210 =>
								array(
									'id' => '587',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadServicetemplatesArguments',
									'lft' => '1219',
									'rght' => '1220',
									'Permission' =>
										array(
											'id' => '2277',
											'aro_id' => '1',
											'aco_id' => '587',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							211 =>
								array(
									'id' => '588',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadTemplateData',
									'lft' => '1221',
									'rght' => '1222',
									'Permission' =>
										array(
											'id' => '2278',
											'aro_id' => '1',
											'aco_id' => '588',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							212 =>
								array(
									'id' => '589',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'addCustomMacro',
									'lft' => '1223',
									'rght' => '1224',
									'Permission' =>
										array(
											'id' => '2279',
											'aro_id' => '1',
											'aco_id' => '589',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							213 =>
								array(
									'id' => '591',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadTemplateMacros',
									'lft' => '1227',
									'rght' => '1228',
									'Permission' =>
										array(
											'id' => '2280',
											'aro_id' => '1',
											'aco_id' => '591',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							214 =>
								array(
									'id' => '576',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '1197',
									'rght' => '1198',
									'Permission' =>
										array(
											'id' => '2281',
											'aro_id' => '1',
											'aco_id' => '576',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							215 =>
								array(
									'id' => '577',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '1199',
									'rght' => '1200',
									'Permission' =>
										array(
											'id' => '2282',
											'aro_id' => '1',
											'aco_id' => '577',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							216 =>
								array(
									'id' => '578',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_delete',
									'lft' => '1201',
									'rght' => '1202',
									'Permission' =>
										array(
											'id' => '2283',
											'aro_id' => '1',
											'aco_id' => '578',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							217 =>
								array(
									'id' => '579',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'copy',
									'lft' => '1203',
									'rght' => '1204',
									'Permission' =>
										array(
											'id' => '2284',
											'aro_id' => '1',
											'aco_id' => '579',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							218 =>
								array(
									'id' => '580',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'deactivate',
									'lft' => '1205',
									'rght' => '1206',
									'Permission' =>
										array(
											'id' => '2285',
											'aro_id' => '1',
											'aco_id' => '580',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							219 =>
								array(
									'id' => '581',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_deactivate',
									'lft' => '1207',
									'rght' => '1208',
									'Permission' =>
										array(
											'id' => '2286',
											'aro_id' => '1',
											'aco_id' => '581',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							220 =>
								array(
									'id' => '582',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'enable',
									'lft' => '1209',
									'rght' => '1210',
									'Permission' =>
										array(
											'id' => '2287',
											'aro_id' => '1',
											'aco_id' => '582',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							221 =>
								array(
									'id' => '592',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'browser',
									'lft' => '1229',
									'rght' => '1230',
									'Permission' =>
										array(
											'id' => '2288',
											'aro_id' => '1',
											'aco_id' => '592',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							222 =>
								array(
									'id' => '593',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'servicesByHostId',
									'lft' => '1231',
									'rght' => '1232',
									'Permission' =>
										array(
											'id' => '2289',
											'aro_id' => '1',
											'aco_id' => '593',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							223 =>
								array(
									'id' => '601',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'longOutputByUuid',
									'lft' => '1247',
									'rght' => '1248',
									'Permission' =>
										array(
											'id' => '2290',
											'aro_id' => '1',
											'aco_id' => '601',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							224 =>
								array(
									'id' => '594',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceList',
									'lft' => '1233',
									'rght' => '1234',
									'Permission' =>
										array(
											'id' => '2291',
											'aro_id' => '1',
											'aco_id' => '594',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							225 =>
								array(
									'id' => '603',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'checkcommand',
									'lft' => '1251',
									'rght' => '1252',
									'Permission' =>
										array(
											'id' => '2292',
											'aro_id' => '1',
											'aco_id' => '603',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							226 =>
								array(
									'id' => '612',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1279',
									'rght' => '1280',
									'Permission' =>
										array(
											'id' => '2293',
											'aro_id' => '1',
											'aco_id' => '612',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							227 =>
								array(
									'id' => '617',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getHostsByHostgroupByAjax',
									'lft' => '1289',
									'rght' => '1290',
									'Permission' =>
										array(
											'id' => '2294',
											'aro_id' => '1',
											'aco_id' => '617',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							228 =>
								array(
									'id' => '619',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadServicetemplatesByContainerId',
									'lft' => '1293',
									'rght' => '1294',
									'Permission' =>
										array(
											'id' => '2295',
											'aro_id' => '1',
											'aco_id' => '619',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							229 =>
								array(
									'id' => '955',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1311',
									'rght' => '1312',
									'Permission' =>
										array(
											'id' => '2296',
											'aro_id' => '1',
											'aco_id' => '955',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							230 =>
								array(
									'id' => '613',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '1281',
									'rght' => '1282',
									'Permission' =>
										array(
											'id' => '2297',
											'aro_id' => '1',
											'aco_id' => '613',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							231 =>
								array(
									'id' => '614',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '1283',
									'rght' => '1284',
									'Permission' =>
										array(
											'id' => '2298',
											'aro_id' => '1',
											'aco_id' => '614',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							232 =>
								array(
									'id' => '615',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allocateToHost',
									'lft' => '1285',
									'rght' => '1286',
									'Permission' =>
										array(
											'id' => '2299',
											'aro_id' => '1',
											'aco_id' => '615',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							233 =>
								array(
									'id' => '616',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allocateToHostgroup',
									'lft' => '1287',
									'rght' => '1288',
									'Permission' =>
										array(
											'id' => '2300',
											'aro_id' => '1',
											'aco_id' => '616',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							234 =>
								array(
									'id' => '618',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '1291',
									'rght' => '1292',
									'Permission' =>
										array(
											'id' => '2301',
											'aro_id' => '1',
											'aco_id' => '618',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							235 =>
								array(
									'id' => '923',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allocateToMatchingHostgroup',
									'lft' => '1309',
									'rght' => '1310',
									'Permission' =>
										array(
											'id' => '2302',
											'aro_id' => '1',
											'aco_id' => '923',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							236 =>
								array(
									'id' => '628',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1315',
									'rght' => '1316',
									'Permission' =>
										array(
											'id' => '2303',
											'aro_id' => '1',
											'aco_id' => '628',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							237 =>
								array(
									'id' => '956',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1355',
									'rght' => '1356',
									'Permission' =>
										array(
											'id' => '2304',
											'aro_id' => '1',
											'aco_id' => '956',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							238 =>
								array(
									'id' => '629',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '1317',
									'rght' => '1318',
									'Permission' =>
										array(
											'id' => '2305',
											'aro_id' => '1',
											'aco_id' => '629',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							239 =>
								array(
									'id' => '633',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadArguments',
									'lft' => '1325',
									'rght' => '1326',
									'Permission' =>
										array(
											'id' => '2306',
											'aro_id' => '1',
											'aco_id' => '633',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							240 =>
								array(
									'id' => '634',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadContactsAndContactgroups',
									'lft' => '1327',
									'rght' => '1328',
									'Permission' =>
										array(
											'id' => '2307',
											'aro_id' => '1',
											'aco_id' => '634',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							241 =>
								array(
									'id' => '635',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadArgumentsAdd',
									'lft' => '1329',
									'rght' => '1330',
									'Permission' =>
										array(
											'id' => '2308',
											'aro_id' => '1',
											'aco_id' => '635',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							242 =>
								array(
									'id' => '636',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadNagArgumentsAdd',
									'lft' => '1331',
									'rght' => '1332',
									'Permission' =>
										array(
											'id' => '2309',
											'aro_id' => '1',
											'aco_id' => '636',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							243 =>
								array(
									'id' => '637',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'addCustomMacro',
									'lft' => '1333',
									'rght' => '1334',
									'Permission' =>
										array(
											'id' => '2310',
											'aro_id' => '1',
											'aco_id' => '637',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							244 =>
								array(
									'id' => '638',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadParametersByCommandId',
									'lft' => '1335',
									'rght' => '1336',
									'Permission' =>
										array(
											'id' => '2311',
											'aro_id' => '1',
											'aco_id' => '638',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							245 =>
								array(
									'id' => '639',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadNagParametersByCommandId',
									'lft' => '1337',
									'rght' => '1338',
									'Permission' =>
										array(
											'id' => '2312',
											'aro_id' => '1',
											'aco_id' => '639',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							246 =>
								array(
									'id' => '640',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadElementsByContainerId',
									'lft' => '1339',
									'rght' => '1340',
									'Permission' =>
										array(
											'id' => '2313',
											'aro_id' => '1',
											'aco_id' => '640',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							247 =>
								array(
									'id' => '630',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '1319',
									'rght' => '1320',
									'Permission' =>
										array(
											'id' => '2314',
											'aro_id' => '1',
											'aco_id' => '630',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							248 =>
								array(
									'id' => '631',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '1321',
									'rght' => '1322',
									'Permission' =>
										array(
											'id' => '2315',
											'aro_id' => '1',
											'aco_id' => '631',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							249 =>
								array(
									'id' => '632',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'usedBy',
									'lft' => '1323',
									'rght' => '1324',
									'Permission' =>
										array(
											'id' => '2316',
											'aro_id' => '1',
											'aco_id' => '632',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							250 =>
								array(
									'id' => '649',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'service',
									'lft' => '1365',
									'rght' => '1366',
									'Permission' =>
										array(
											'id' => '2317',
											'aro_id' => '1',
											'aco_id' => '649',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							251 =>
								array(
									'id' => '650',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'host',
									'lft' => '1367',
									'rght' => '1368',
									'Permission' =>
										array(
											'id' => '2318',
											'aro_id' => '1',
											'aco_id' => '650',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							252 =>
								array(
									'id' => '659',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1385',
									'rght' => '1386',
									'Permission' =>
										array(
											'id' => '2319',
											'aro_id' => '1',
											'aco_id' => '659',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							253 =>
								array(
									'id' => '662',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1391',
									'rght' => '1392',
									'Permission' =>
										array(
											'id' => '2320',
											'aro_id' => '1',
											'aco_id' => '662',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							254 =>
								array(
									'id' => '671',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'changelog',
									'lft' => '1409',
									'rght' => '1410',
									'Permission' =>
										array(
											'id' => '2321',
											'aro_id' => '1',
											'aco_id' => '671',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							255 =>
								array(
									'id' => '680',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1427',
									'rght' => '1428',
									'Permission' =>
										array(
											'id' => '2322',
											'aro_id' => '1',
											'aco_id' => '680',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							256 =>
								array(
									'id' => '681',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'addHostdowntime',
									'lft' => '1429',
									'rght' => '1430',
									'Permission' =>
										array(
											'id' => '2323',
											'aro_id' => '1',
											'aco_id' => '681',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							257 =>
								array(
									'id' => '682',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'addHostgroupdowntime',
									'lft' => '1431',
									'rght' => '1432',
									'Permission' =>
										array(
											'id' => '2324',
											'aro_id' => '1',
											'aco_id' => '682',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							258 =>
								array(
									'id' => '683',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'addServicedowntime',
									'lft' => '1433',
									'rght' => '1434',
									'Permission' =>
										array(
											'id' => '2325',
											'aro_id' => '1',
											'aco_id' => '683',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							259 =>
								array(
									'id' => '684',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '1435',
									'rght' => '1436',
									'Permission' =>
										array(
											'id' => '2326',
											'aro_id' => '1',
											'aco_id' => '684',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							260 =>
								array(
									'id' => '693',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1453',
									'rght' => '1454',
									'Permission' =>
										array(
											'id' => '2327',
											'aro_id' => '1',
											'aco_id' => '693',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							261 =>
								array(
									'id' => '694',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '1455',
									'rght' => '1456',
									'Permission' =>
										array(
											'id' => '2328',
											'aro_id' => '1',
											'aco_id' => '694',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							262 =>
								array(
									'id' => '695',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '1457',
									'rght' => '1458',
									'Permission' =>
										array(
											'id' => '2329',
											'aro_id' => '1',
											'aco_id' => '695',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							263 =>
								array(
									'id' => '704',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1475',
									'rght' => '1476',
									'Permission' =>
										array(
											'id' => '2330',
											'aro_id' => '1',
											'aco_id' => '704',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							264 =>
								array(
									'id' => '713',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1493',
									'rght' => '1494',
									'Permission' =>
										array(
											'id' => '2331',
											'aro_id' => '1',
											'aco_id' => '713',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							265 =>
								array(
									'id' => '960',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1517',
									'rght' => '1518',
									'Permission' =>
										array(
											'id' => '2332',
											'aro_id' => '1',
											'aco_id' => '960',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							266 =>
								array(
									'id' => '714',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '1495',
									'rght' => '1496',
									'Permission' =>
										array(
											'id' => '2333',
											'aro_id' => '1',
											'aco_id' => '714',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							267 =>
								array(
									'id' => '715',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '1497',
									'rght' => '1498',
									'Permission' =>
										array(
											'id' => '2334',
											'aro_id' => '1',
											'aco_id' => '715',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							268 =>
								array(
									'id' => '716',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_delete',
									'lft' => '1499',
									'rght' => '1500',
									'Permission' =>
										array(
											'id' => '2335',
											'aro_id' => '1',
											'aco_id' => '716',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							269 =>
								array(
									'id' => '717',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '1501',
									'rght' => '1502',
									'Permission' =>
										array(
											'id' => '2336',
											'aro_id' => '1',
											'aco_id' => '717',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							270 =>
								array(
									'id' => '726',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1521',
									'rght' => '1522',
									'Permission' =>
										array(
											'id' => '2337',
											'aro_id' => '1',
											'aco_id' => '726',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							271 =>
								array(
									'id' => '961',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1549',
									'rght' => '1550',
									'Permission' =>
										array(
											'id' => '2338',
											'aro_id' => '1',
											'aco_id' => '961',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							272 =>
								array(
									'id' => '727',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '1523',
									'rght' => '1524',
									'Permission' =>
										array(
											'id' => '2339',
											'aro_id' => '1',
											'aco_id' => '727',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							273 =>
								array(
									'id' => '728',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '1525',
									'rght' => '1526',
									'Permission' =>
										array(
											'id' => '2340',
											'aro_id' => '1',
											'aco_id' => '728',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							274 =>
								array(
									'id' => '729',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '1527',
									'rght' => '1528',
									'Permission' =>
										array(
											'id' => '2341',
											'aro_id' => '1',
											'aco_id' => '729',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							275 =>
								array(
									'id' => '730',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'mass_delete',
									'lft' => '1529',
									'rght' => '1530',
									'Permission' =>
										array(
											'id' => '2342',
											'aro_id' => '1',
											'aco_id' => '730',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							276 =>
								array(
									'id' => '731',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'browser',
									'lft' => '1531',
									'rght' => '1532',
									'Permission' =>
										array(
											'id' => '2343',
											'aro_id' => '1',
											'aco_id' => '731',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							277 =>
								array(
									'id' => '732',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'controller',
									'lft' => '1533',
									'rght' => '1534',
									'Permission' =>
										array(
											'id' => '2344',
											'aro_id' => '1',
											'aco_id' => '732',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							278 =>
								array(
									'id' => '741',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1553',
									'rght' => '1554',
									'Permission' =>
										array(
											'id' => '2345',
											'aro_id' => '1',
											'aco_id' => '741',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							279 =>
								array(
									'id' => '962',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1575',
									'rght' => '1576',
									'Permission' =>
										array(
											'id' => '2346',
											'aro_id' => '1',
											'aco_id' => '962',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							280 =>
								array(
									'id' => '742',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '1555',
									'rght' => '1556',
									'Permission' =>
										array(
											'id' => '2347',
											'aro_id' => '1',
											'aco_id' => '742',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							281 =>
								array(
									'id' => '743',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '1557',
									'rght' => '1558',
									'Permission' =>
										array(
											'id' => '2348',
											'aro_id' => '1',
											'aco_id' => '743',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							282 =>
								array(
									'id' => '744',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '1559',
									'rght' => '1560',
									'Permission' =>
										array(
											'id' => '2349',
											'aro_id' => '1',
											'aco_id' => '744',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							283 =>
								array(
									'id' => '753',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1579',
									'rght' => '1580',
									'Permission' =>
										array(
											'id' => '2350',
											'aro_id' => '1',
											'aco_id' => '753',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							284 =>
								array(
									'id' => '963',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1605',
									'rght' => '1606',
									'Permission' =>
										array(
											'id' => '2351',
											'aro_id' => '1',
											'aco_id' => '963',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							285 =>
								array(
									'id' => '754',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'delete',
									'lft' => '1581',
									'rght' => '1582',
									'Permission' =>
										array(
											'id' => '2352',
											'aro_id' => '1',
											'aco_id' => '754',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							286 =>
								array(
									'id' => '755',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '1583',
									'rght' => '1584',
									'Permission' =>
										array(
											'id' => '2353',
											'aro_id' => '1',
											'aco_id' => '755',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							287 =>
								array(
									'id' => '757',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'addFromLdap',
									'lft' => '1587',
									'rght' => '1588',
									'Permission' =>
										array(
											'id' => '2354',
											'aro_id' => '1',
											'aco_id' => '757',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							288 =>
								array(
									'id' => '756',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '1585',
									'rght' => '1586',
									'Permission' =>
										array(
											'id' => '2355',
											'aro_id' => '1',
											'aco_id' => '756',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							289 =>
								array(
									'id' => '758',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'resetPassword',
									'lft' => '1589',
									'rght' => '1590',
									'Permission' =>
										array(
											'id' => '2356',
											'aro_id' => '1',
											'aco_id' => '758',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							290 =>
								array(
									'id' => '810',
									'parent_id' => '809',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'BoostCake',
									'lft' => '1613',
									'rght' => '1634',
									'Permission' =>
										array(
											'id' => '2357',
											'aro_id' => '1',
											'aco_id' => '810',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							291 =>
								array(
									'id' => '824',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1640',
									'rght' => '1641',
									'Permission' =>
										array(
											'id' => '2358',
											'aro_id' => '1',
											'aco_id' => '824',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							292 =>
								array(
									'id' => '833',
									'parent_id' => '832',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'ClearCache',
									'lft' => '1659',
									'rght' => '1680',
									'Permission' =>
										array(
											'id' => '2359',
											'aro_id' => '1',
											'aco_id' => '833',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							293 =>
								array(
									'id' => '845',
									'parent_id' => '844',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'ToolbarAccess',
									'lft' => '1683',
									'rght' => '1702',
									'Permission' =>
										array(
											'id' => '2360',
											'aro_id' => '1',
											'aco_id' => '845',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							294 =>
								array(
									'id' => '857',
									'parent_id' => '856',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'FrontendDependencies',
									'lft' => '1707',
									'rght' => '1724',
									'Permission' =>
										array(
											'id' => '2361',
											'aro_id' => '1',
											'aco_id' => '857',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							295 =>
								array(
									'id' => '869',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1730',
									'rght' => '1731',
									'Permission' =>
										array(
											'id' => '2362',
											'aro_id' => '1',
											'aco_id' => '869',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							296 =>
								array(
									'id' => '870',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'submit',
									'lft' => '1732',
									'rght' => '1733',
									'Permission' =>
										array(
											'id' => '2363',
											'aro_id' => '1',
											'aco_id' => '870',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							297 =>
								array(
									'id' => '964',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'ack',
									'lft' => '1748',
									'rght' => '1749',
									'Permission' =>
										array(
											'id' => '2364',
											'aro_id' => '1',
											'aco_id' => '964',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							298 =>
								array(
									'id' => '879',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1752',
									'rght' => '1753',
									'Permission' =>
										array(
											'id' => '2365',
											'aro_id' => '1',
											'aco_id' => '879',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							299 =>
								array(
									'id' => '5',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '7',
									'rght' => '8',
									'Permission' =>
										array(
											'id' => '2366',
											'aro_id' => '1',
											'aco_id' => '5',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							300 =>
								array(
									'id' => '6',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '9',
									'rght' => '10',
									'Permission' =>
										array(
											'id' => '2367',
											'aro_id' => '1',
											'aco_id' => '6',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							301 =>
								array(
									'id' => '7',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '11',
									'rght' => '12',
									'Permission' =>
										array(
											'id' => '2368',
											'aro_id' => '1',
											'aco_id' => '7',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							302 =>
								array(
									'id' => '8',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '13',
									'rght' => '14',
									'Permission' =>
										array(
											'id' => '2369',
											'aro_id' => '1',
											'aco_id' => '8',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							303 =>
								array(
									'id' => '9',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '15',
									'rght' => '16',
									'Permission' =>
										array(
											'id' => '2370',
											'aro_id' => '1',
											'aco_id' => '9',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							304 =>
								array(
									'id' => '10',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '17',
									'rght' => '18',
									'Permission' =>
										array(
											'id' => '2371',
											'aro_id' => '1',
											'aco_id' => '10',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							305 =>
								array(
									'id' => '11',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '19',
									'rght' => '20',
									'Permission' =>
										array(
											'id' => '2372',
											'aro_id' => '1',
											'aco_id' => '11',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							306 =>
								array(
									'id' => '15',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '27',
									'rght' => '28',
									'Permission' =>
										array(
											'id' => '2373',
											'aro_id' => '1',
											'aco_id' => '15',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							307 =>
								array(
									'id' => '16',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '29',
									'rght' => '30',
									'Permission' =>
										array(
											'id' => '2374',
											'aro_id' => '1',
											'aco_id' => '16',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							308 =>
								array(
									'id' => '17',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '31',
									'rght' => '32',
									'Permission' =>
										array(
											'id' => '2375',
											'aro_id' => '1',
											'aco_id' => '17',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							309 =>
								array(
									'id' => '18',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '33',
									'rght' => '34',
									'Permission' =>
										array(
											'id' => '2376',
											'aro_id' => '1',
											'aco_id' => '18',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							310 =>
								array(
									'id' => '19',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '35',
									'rght' => '36',
									'Permission' =>
										array(
											'id' => '2377',
											'aro_id' => '1',
											'aco_id' => '19',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							311 =>
								array(
									'id' => '20',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '37',
									'rght' => '38',
									'Permission' =>
										array(
											'id' => '2378',
											'aro_id' => '1',
											'aco_id' => '20',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							312 =>
								array(
									'id' => '21',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '39',
									'rght' => '40',
									'Permission' =>
										array(
											'id' => '2379',
											'aro_id' => '1',
											'aco_id' => '21',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							313 =>
								array(
									'id' => '29',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '57',
									'rght' => '58',
									'Permission' =>
										array(
											'id' => '2380',
											'aro_id' => '1',
											'aco_id' => '29',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							314 =>
								array(
									'id' => '30',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '59',
									'rght' => '60',
									'Permission' =>
										array(
											'id' => '2381',
											'aro_id' => '1',
											'aco_id' => '30',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							315 =>
								array(
									'id' => '31',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '61',
									'rght' => '62',
									'Permission' =>
										array(
											'id' => '2382',
											'aro_id' => '1',
											'aco_id' => '31',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							316 =>
								array(
									'id' => '32',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '63',
									'rght' => '64',
									'Permission' =>
										array(
											'id' => '2383',
											'aro_id' => '1',
											'aco_id' => '32',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							317 =>
								array(
									'id' => '33',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '65',
									'rght' => '66',
									'Permission' =>
										array(
											'id' => '2384',
											'aro_id' => '1',
											'aco_id' => '33',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							318 =>
								array(
									'id' => '34',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '67',
									'rght' => '68',
									'Permission' =>
										array(
											'id' => '2385',
											'aro_id' => '1',
											'aco_id' => '34',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							319 =>
								array(
									'id' => '35',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '69',
									'rght' => '70',
									'Permission' =>
										array(
											'id' => '2386',
											'aro_id' => '1',
											'aco_id' => '35',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							320 =>
								array(
									'id' => '42',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '77',
									'rght' => '78',
									'Permission' =>
										array(
											'id' => '2387',
											'aro_id' => '1',
											'aco_id' => '42',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							321 =>
								array(
									'id' => '43',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '79',
									'rght' => '80',
									'Permission' =>
										array(
											'id' => '2388',
											'aro_id' => '1',
											'aco_id' => '43',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							322 =>
								array(
									'id' => '44',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '81',
									'rght' => '82',
									'Permission' =>
										array(
											'id' => '2389',
											'aro_id' => '1',
											'aco_id' => '44',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							323 =>
								array(
									'id' => '45',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '83',
									'rght' => '84',
									'Permission' =>
										array(
											'id' => '2390',
											'aro_id' => '1',
											'aco_id' => '45',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							324 =>
								array(
									'id' => '46',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '85',
									'rght' => '86',
									'Permission' =>
										array(
											'id' => '2391',
											'aro_id' => '1',
											'aco_id' => '46',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							325 =>
								array(
									'id' => '47',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '87',
									'rght' => '88',
									'Permission' =>
										array(
											'id' => '2392',
											'aro_id' => '1',
											'aco_id' => '47',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							326 =>
								array(
									'id' => '48',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '89',
									'rght' => '90',
									'Permission' =>
										array(
											'id' => '2393',
											'aro_id' => '1',
											'aco_id' => '48',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							327 =>
								array(
									'id' => '56',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '105',
									'rght' => '106',
									'Permission' =>
										array(
											'id' => '2394',
											'aro_id' => '1',
											'aco_id' => '56',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							328 =>
								array(
									'id' => '57',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '107',
									'rght' => '108',
									'Permission' =>
										array(
											'id' => '2395',
											'aro_id' => '1',
											'aco_id' => '57',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							329 =>
								array(
									'id' => '58',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '109',
									'rght' => '110',
									'Permission' =>
										array(
											'id' => '2396',
											'aro_id' => '1',
											'aco_id' => '58',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							330 =>
								array(
									'id' => '59',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '111',
									'rght' => '112',
									'Permission' =>
										array(
											'id' => '2397',
											'aro_id' => '1',
											'aco_id' => '59',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							331 =>
								array(
									'id' => '60',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '113',
									'rght' => '114',
									'Permission' =>
										array(
											'id' => '2398',
											'aro_id' => '1',
											'aco_id' => '60',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							332 =>
								array(
									'id' => '61',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '115',
									'rght' => '116',
									'Permission' =>
										array(
											'id' => '2399',
											'aro_id' => '1',
											'aco_id' => '61',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							333 =>
								array(
									'id' => '62',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '117',
									'rght' => '118',
									'Permission' =>
										array(
											'id' => '2400',
											'aro_id' => '1',
											'aco_id' => '62',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							334 =>
								array(
									'id' => '65',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '123',
									'rght' => '124',
									'Permission' =>
										array(
											'id' => '2401',
											'aro_id' => '1',
											'aco_id' => '65',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							335 =>
								array(
									'id' => '66',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '125',
									'rght' => '126',
									'Permission' =>
										array(
											'id' => '2402',
											'aro_id' => '1',
											'aco_id' => '66',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							336 =>
								array(
									'id' => '67',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '127',
									'rght' => '128',
									'Permission' =>
										array(
											'id' => '2403',
											'aro_id' => '1',
											'aco_id' => '67',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							337 =>
								array(
									'id' => '68',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '129',
									'rght' => '130',
									'Permission' =>
										array(
											'id' => '2404',
											'aro_id' => '1',
											'aco_id' => '68',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							338 =>
								array(
									'id' => '69',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '131',
									'rght' => '132',
									'Permission' =>
										array(
											'id' => '2405',
											'aro_id' => '1',
											'aco_id' => '69',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							339 =>
								array(
									'id' => '70',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '133',
									'rght' => '134',
									'Permission' =>
										array(
											'id' => '2406',
											'aro_id' => '1',
											'aco_id' => '70',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							340 =>
								array(
									'id' => '71',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '135',
									'rght' => '136',
									'Permission' =>
										array(
											'id' => '2407',
											'aro_id' => '1',
											'aco_id' => '71',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							341 =>
								array(
									'id' => '74',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '141',
									'rght' => '142',
									'Permission' =>
										array(
											'id' => '2408',
											'aro_id' => '1',
											'aco_id' => '74',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							342 =>
								array(
									'id' => '75',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '143',
									'rght' => '144',
									'Permission' =>
										array(
											'id' => '2409',
											'aro_id' => '1',
											'aco_id' => '75',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							343 =>
								array(
									'id' => '76',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '145',
									'rght' => '146',
									'Permission' =>
										array(
											'id' => '2410',
											'aro_id' => '1',
											'aco_id' => '76',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							344 =>
								array(
									'id' => '77',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '147',
									'rght' => '148',
									'Permission' =>
										array(
											'id' => '2411',
											'aro_id' => '1',
											'aco_id' => '77',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							345 =>
								array(
									'id' => '78',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '149',
									'rght' => '150',
									'Permission' =>
										array(
											'id' => '2412',
											'aro_id' => '1',
											'aco_id' => '78',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							346 =>
								array(
									'id' => '79',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '151',
									'rght' => '152',
									'Permission' =>
										array(
											'id' => '2413',
											'aro_id' => '1',
											'aco_id' => '79',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							347 =>
								array(
									'id' => '80',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '153',
									'rght' => '154',
									'Permission' =>
										array(
											'id' => '2414',
											'aro_id' => '1',
											'aco_id' => '80',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							348 =>
								array(
									'id' => '93',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '179',
									'rght' => '180',
									'Permission' =>
										array(
											'id' => '2415',
											'aro_id' => '1',
											'aco_id' => '93',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							349 =>
								array(
									'id' => '94',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '181',
									'rght' => '182',
									'Permission' =>
										array(
											'id' => '2416',
											'aro_id' => '1',
											'aco_id' => '94',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							350 =>
								array(
									'id' => '95',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '183',
									'rght' => '184',
									'Permission' =>
										array(
											'id' => '2417',
											'aro_id' => '1',
											'aco_id' => '95',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							351 =>
								array(
									'id' => '96',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '185',
									'rght' => '186',
									'Permission' =>
										array(
											'id' => '2418',
											'aro_id' => '1',
											'aco_id' => '96',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							352 =>
								array(
									'id' => '97',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '187',
									'rght' => '188',
									'Permission' =>
										array(
											'id' => '2419',
											'aro_id' => '1',
											'aco_id' => '97',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							353 =>
								array(
									'id' => '98',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '189',
									'rght' => '190',
									'Permission' =>
										array(
											'id' => '2420',
											'aro_id' => '1',
											'aco_id' => '98',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							354 =>
								array(
									'id' => '99',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '191',
									'rght' => '192',
									'Permission' =>
										array(
											'id' => '2421',
											'aro_id' => '1',
											'aco_id' => '99',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							355 =>
								array(
									'id' => '107',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '211',
									'rght' => '212',
									'Permission' =>
										array(
											'id' => '2422',
											'aro_id' => '1',
											'aco_id' => '107',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							356 =>
								array(
									'id' => '108',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '213',
									'rght' => '214',
									'Permission' =>
										array(
											'id' => '2423',
											'aro_id' => '1',
											'aco_id' => '108',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							357 =>
								array(
									'id' => '109',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '215',
									'rght' => '216',
									'Permission' =>
										array(
											'id' => '2424',
											'aro_id' => '1',
											'aco_id' => '109',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							358 =>
								array(
									'id' => '110',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '217',
									'rght' => '218',
									'Permission' =>
										array(
											'id' => '2425',
											'aro_id' => '1',
											'aco_id' => '110',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							359 =>
								array(
									'id' => '111',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '219',
									'rght' => '220',
									'Permission' =>
										array(
											'id' => '2426',
											'aro_id' => '1',
											'aco_id' => '111',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							360 =>
								array(
									'id' => '112',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '221',
									'rght' => '222',
									'Permission' =>
										array(
											'id' => '2427',
											'aro_id' => '1',
											'aco_id' => '112',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							361 =>
								array(
									'id' => '113',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '223',
									'rght' => '224',
									'Permission' =>
										array(
											'id' => '2428',
											'aro_id' => '1',
											'aco_id' => '113',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							362 =>
								array(
									'id' => '121',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '241',
									'rght' => '242',
									'Permission' =>
										array(
											'id' => '2429',
											'aro_id' => '1',
											'aco_id' => '121',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							363 =>
								array(
									'id' => '122',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '243',
									'rght' => '244',
									'Permission' =>
										array(
											'id' => '2430',
											'aro_id' => '1',
											'aco_id' => '122',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							364 =>
								array(
									'id' => '123',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '245',
									'rght' => '246',
									'Permission' =>
										array(
											'id' => '2431',
											'aro_id' => '1',
											'aco_id' => '123',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							365 =>
								array(
									'id' => '124',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '247',
									'rght' => '248',
									'Permission' =>
										array(
											'id' => '2432',
											'aro_id' => '1',
											'aco_id' => '124',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							366 =>
								array(
									'id' => '125',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '249',
									'rght' => '250',
									'Permission' =>
										array(
											'id' => '2433',
											'aro_id' => '1',
											'aco_id' => '125',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							367 =>
								array(
									'id' => '126',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '251',
									'rght' => '252',
									'Permission' =>
										array(
											'id' => '2434',
											'aro_id' => '1',
											'aco_id' => '126',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							368 =>
								array(
									'id' => '127',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '253',
									'rght' => '254',
									'Permission' =>
										array(
											'id' => '2435',
											'aro_id' => '1',
											'aco_id' => '127',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							369 =>
								array(
									'id' => '131',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'byTenant',
									'lft' => '265',
									'rght' => '266',
									'Permission' =>
										array(
											'id' => '2436',
											'aro_id' => '1',
											'aco_id' => '131',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							370 =>
								array(
									'id' => '132',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'byTenantForSelect',
									'lft' => '267',
									'rght' => '268',
									'Permission' =>
										array(
											'id' => '2437',
											'aro_id' => '1',
											'aco_id' => '132',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							371 =>
								array(
									'id' => '134',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '271',
									'rght' => '272',
									'Permission' =>
										array(
											'id' => '2438',
											'aro_id' => '1',
											'aco_id' => '134',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							372 =>
								array(
									'id' => '135',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '273',
									'rght' => '274',
									'Permission' =>
										array(
											'id' => '2439',
											'aro_id' => '1',
											'aco_id' => '135',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							373 =>
								array(
									'id' => '136',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '275',
									'rght' => '276',
									'Permission' =>
										array(
											'id' => '2440',
											'aro_id' => '1',
											'aco_id' => '136',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							374 =>
								array(
									'id' => '137',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '277',
									'rght' => '278',
									'Permission' =>
										array(
											'id' => '2441',
											'aro_id' => '1',
											'aco_id' => '137',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							375 =>
								array(
									'id' => '138',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '279',
									'rght' => '280',
									'Permission' =>
										array(
											'id' => '2442',
											'aro_id' => '1',
											'aco_id' => '138',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							376 =>
								array(
									'id' => '139',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '281',
									'rght' => '282',
									'Permission' =>
										array(
											'id' => '2443',
											'aro_id' => '1',
											'aco_id' => '139',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							377 =>
								array(
									'id' => '140',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '283',
									'rght' => '284',
									'Permission' =>
										array(
											'id' => '2444',
											'aro_id' => '1',
											'aco_id' => '140',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							378 =>
								array(
									'id' => '147',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '301',
									'rght' => '302',
									'Permission' =>
										array(
											'id' => '2445',
											'aro_id' => '1',
											'aco_id' => '147',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							379 =>
								array(
									'id' => '148',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '303',
									'rght' => '304',
									'Permission' =>
										array(
											'id' => '2446',
											'aro_id' => '1',
											'aco_id' => '148',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							380 =>
								array(
									'id' => '149',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '305',
									'rght' => '306',
									'Permission' =>
										array(
											'id' => '2447',
											'aro_id' => '1',
											'aco_id' => '149',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							381 =>
								array(
									'id' => '150',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '307',
									'rght' => '308',
									'Permission' =>
										array(
											'id' => '2448',
											'aro_id' => '1',
											'aco_id' => '150',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							382 =>
								array(
									'id' => '151',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '309',
									'rght' => '310',
									'Permission' =>
										array(
											'id' => '2449',
											'aro_id' => '1',
											'aco_id' => '151',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							383 =>
								array(
									'id' => '152',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '311',
									'rght' => '312',
									'Permission' =>
										array(
											'id' => '2450',
											'aro_id' => '1',
											'aco_id' => '152',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							384 =>
								array(
									'id' => '153',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '313',
									'rght' => '314',
									'Permission' =>
										array(
											'id' => '2451',
											'aro_id' => '1',
											'aco_id' => '153',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							385 =>
								array(
									'id' => '157',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '321',
									'rght' => '322',
									'Permission' =>
										array(
											'id' => '2452',
											'aro_id' => '1',
											'aco_id' => '157',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							386 =>
								array(
									'id' => '158',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '323',
									'rght' => '324',
									'Permission' =>
										array(
											'id' => '2453',
											'aro_id' => '1',
											'aco_id' => '158',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							387 =>
								array(
									'id' => '159',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '325',
									'rght' => '326',
									'Permission' =>
										array(
											'id' => '2454',
											'aro_id' => '1',
											'aco_id' => '159',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							388 =>
								array(
									'id' => '160',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '327',
									'rght' => '328',
									'Permission' =>
										array(
											'id' => '2455',
											'aro_id' => '1',
											'aco_id' => '160',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							389 =>
								array(
									'id' => '161',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '329',
									'rght' => '330',
									'Permission' =>
										array(
											'id' => '2456',
											'aro_id' => '1',
											'aco_id' => '161',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							390 =>
								array(
									'id' => '162',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '331',
									'rght' => '332',
									'Permission' =>
										array(
											'id' => '2457',
											'aro_id' => '1',
											'aco_id' => '162',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							391 =>
								array(
									'id' => '163',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '333',
									'rght' => '334',
									'Permission' =>
										array(
											'id' => '2458',
											'aro_id' => '1',
											'aco_id' => '163',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							392 =>
								array(
									'id' => '166',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '339',
									'rght' => '340',
									'Permission' =>
										array(
											'id' => '2459',
											'aro_id' => '1',
											'aco_id' => '166',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							393 =>
								array(
									'id' => '167',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '341',
									'rght' => '342',
									'Permission' =>
										array(
											'id' => '2460',
											'aro_id' => '1',
											'aco_id' => '167',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							394 =>
								array(
									'id' => '168',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '343',
									'rght' => '344',
									'Permission' =>
										array(
											'id' => '2461',
											'aro_id' => '1',
											'aco_id' => '168',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							395 =>
								array(
									'id' => '169',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '345',
									'rght' => '346',
									'Permission' =>
										array(
											'id' => '2462',
											'aro_id' => '1',
											'aco_id' => '169',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							396 =>
								array(
									'id' => '170',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '347',
									'rght' => '348',
									'Permission' =>
										array(
											'id' => '2463',
											'aro_id' => '1',
											'aco_id' => '170',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							397 =>
								array(
									'id' => '171',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '349',
									'rght' => '350',
									'Permission' =>
										array(
											'id' => '2464',
											'aro_id' => '1',
											'aco_id' => '171',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							398 =>
								array(
									'id' => '172',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '351',
									'rght' => '352',
									'Permission' =>
										array(
											'id' => '2465',
											'aro_id' => '1',
											'aco_id' => '172',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							399 =>
								array(
									'id' => '178',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '363',
									'rght' => '364',
									'Permission' =>
										array(
											'id' => '2466',
											'aro_id' => '1',
											'aco_id' => '178',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							400 =>
								array(
									'id' => '179',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '365',
									'rght' => '366',
									'Permission' =>
										array(
											'id' => '2467',
											'aro_id' => '1',
											'aco_id' => '179',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							401 =>
								array(
									'id' => '180',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '367',
									'rght' => '368',
									'Permission' =>
										array(
											'id' => '2468',
											'aro_id' => '1',
											'aco_id' => '180',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							402 =>
								array(
									'id' => '181',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '369',
									'rght' => '370',
									'Permission' =>
										array(
											'id' => '2469',
											'aro_id' => '1',
											'aco_id' => '181',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							403 =>
								array(
									'id' => '182',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '371',
									'rght' => '372',
									'Permission' =>
										array(
											'id' => '2470',
											'aro_id' => '1',
											'aco_id' => '182',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							404 =>
								array(
									'id' => '183',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '373',
									'rght' => '374',
									'Permission' =>
										array(
											'id' => '2471',
											'aro_id' => '1',
											'aco_id' => '183',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							405 =>
								array(
									'id' => '184',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '375',
									'rght' => '376',
									'Permission' =>
										array(
											'id' => '2472',
											'aro_id' => '1',
											'aco_id' => '184',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							406 =>
								array(
									'id' => '189',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '387',
									'rght' => '388',
									'Permission' =>
										array(
											'id' => '2473',
											'aro_id' => '1',
											'aco_id' => '189',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							407 =>
								array(
									'id' => '190',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '389',
									'rght' => '390',
									'Permission' =>
										array(
											'id' => '2474',
											'aro_id' => '1',
											'aco_id' => '190',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							408 =>
								array(
									'id' => '191',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '391',
									'rght' => '392',
									'Permission' =>
										array(
											'id' => '2475',
											'aro_id' => '1',
											'aco_id' => '191',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							409 =>
								array(
									'id' => '192',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '393',
									'rght' => '394',
									'Permission' =>
										array(
											'id' => '2476',
											'aro_id' => '1',
											'aco_id' => '192',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							410 =>
								array(
									'id' => '193',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '395',
									'rght' => '396',
									'Permission' =>
										array(
											'id' => '2477',
											'aro_id' => '1',
											'aco_id' => '193',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							411 =>
								array(
									'id' => '194',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '397',
									'rght' => '398',
									'Permission' =>
										array(
											'id' => '2478',
											'aro_id' => '1',
											'aco_id' => '194',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							412 =>
								array(
									'id' => '195',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '399',
									'rght' => '400',
									'Permission' =>
										array(
											'id' => '2479',
											'aro_id' => '1',
											'aco_id' => '195',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							413 =>
								array(
									'id' => '199',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '407',
									'rght' => '408',
									'Permission' =>
										array(
											'id' => '2480',
											'aro_id' => '1',
											'aco_id' => '199',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							414 =>
								array(
									'id' => '200',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '409',
									'rght' => '410',
									'Permission' =>
										array(
											'id' => '2481',
											'aro_id' => '1',
											'aco_id' => '200',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							415 =>
								array(
									'id' => '201',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '411',
									'rght' => '412',
									'Permission' =>
										array(
											'id' => '2482',
											'aro_id' => '1',
											'aco_id' => '201',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							416 =>
								array(
									'id' => '202',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '413',
									'rght' => '414',
									'Permission' =>
										array(
											'id' => '2483',
											'aro_id' => '1',
											'aco_id' => '202',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							417 =>
								array(
									'id' => '203',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '415',
									'rght' => '416',
									'Permission' =>
										array(
											'id' => '2484',
											'aro_id' => '1',
											'aco_id' => '203',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							418 =>
								array(
									'id' => '204',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '417',
									'rght' => '418',
									'Permission' =>
										array(
											'id' => '2485',
											'aro_id' => '1',
											'aco_id' => '204',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							419 =>
								array(
									'id' => '205',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '419',
									'rght' => '420',
									'Permission' =>
										array(
											'id' => '2486',
											'aro_id' => '1',
											'aco_id' => '205',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							420 =>
								array(
									'id' => '210',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'validateDowntimeInputFromBrowser',
									'lft' => '429',
									'rght' => '430',
									'Permission' =>
										array(
											'id' => '2487',
											'aro_id' => '1',
											'aco_id' => '210',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							421 =>
								array(
									'id' => '211',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '431',
									'rght' => '432',
									'Permission' =>
										array(
											'id' => '2488',
											'aro_id' => '1',
											'aco_id' => '211',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							422 =>
								array(
									'id' => '212',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '433',
									'rght' => '434',
									'Permission' =>
										array(
											'id' => '2489',
											'aro_id' => '1',
											'aco_id' => '212',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							423 =>
								array(
									'id' => '213',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '435',
									'rght' => '436',
									'Permission' =>
										array(
											'id' => '2490',
											'aro_id' => '1',
											'aco_id' => '213',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							424 =>
								array(
									'id' => '214',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '437',
									'rght' => '438',
									'Permission' =>
										array(
											'id' => '2491',
											'aro_id' => '1',
											'aco_id' => '214',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							425 =>
								array(
									'id' => '215',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '439',
									'rght' => '440',
									'Permission' =>
										array(
											'id' => '2492',
											'aro_id' => '1',
											'aco_id' => '215',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							426 =>
								array(
									'id' => '216',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '441',
									'rght' => '442',
									'Permission' =>
										array(
											'id' => '2493',
											'aro_id' => '1',
											'aco_id' => '216',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							427 =>
								array(
									'id' => '217',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '443',
									'rght' => '444',
									'Permission' =>
										array(
											'id' => '2494',
											'aro_id' => '1',
											'aco_id' => '217',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							428 =>
								array(
									'id' => '220',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '449',
									'rght' => '450',
									'Permission' =>
										array(
											'id' => '2495',
											'aro_id' => '1',
											'aco_id' => '220',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							429 =>
								array(
									'id' => '221',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '451',
									'rght' => '452',
									'Permission' =>
										array(
											'id' => '2496',
											'aro_id' => '1',
											'aco_id' => '221',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							430 =>
								array(
									'id' => '222',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '453',
									'rght' => '454',
									'Permission' =>
										array(
											'id' => '2497',
											'aro_id' => '1',
											'aco_id' => '222',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							431 =>
								array(
									'id' => '223',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '455',
									'rght' => '456',
									'Permission' =>
										array(
											'id' => '2498',
											'aro_id' => '1',
											'aco_id' => '223',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							432 =>
								array(
									'id' => '224',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '457',
									'rght' => '458',
									'Permission' =>
										array(
											'id' => '2499',
											'aro_id' => '1',
											'aco_id' => '224',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							433 =>
								array(
									'id' => '225',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '459',
									'rght' => '460',
									'Permission' =>
										array(
											'id' => '2500',
											'aro_id' => '1',
											'aco_id' => '225',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							434 =>
								array(
									'id' => '226',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '461',
									'rght' => '462',
									'Permission' =>
										array(
											'id' => '2501',
											'aro_id' => '1',
											'aco_id' => '226',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							435 =>
								array(
									'id' => '228',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '471',
									'rght' => '472',
									'Permission' =>
										array(
											'id' => '2502',
											'aro_id' => '1',
											'aco_id' => '228',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							436 =>
								array(
									'id' => '229',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '473',
									'rght' => '474',
									'Permission' =>
										array(
											'id' => '2503',
											'aro_id' => '1',
											'aco_id' => '229',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							437 =>
								array(
									'id' => '230',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '475',
									'rght' => '476',
									'Permission' =>
										array(
											'id' => '2504',
											'aro_id' => '1',
											'aco_id' => '230',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							438 =>
								array(
									'id' => '231',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '477',
									'rght' => '478',
									'Permission' =>
										array(
											'id' => '2505',
											'aro_id' => '1',
											'aco_id' => '231',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							439 =>
								array(
									'id' => '232',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '479',
									'rght' => '480',
									'Permission' =>
										array(
											'id' => '2506',
											'aro_id' => '1',
											'aco_id' => '232',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							440 =>
								array(
									'id' => '233',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '481',
									'rght' => '482',
									'Permission' =>
										array(
											'id' => '2507',
											'aro_id' => '1',
											'aco_id' => '233',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							441 =>
								array(
									'id' => '234',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '483',
									'rght' => '484',
									'Permission' =>
										array(
											'id' => '2508',
											'aro_id' => '1',
											'aco_id' => '234',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							442 =>
								array(
									'id' => '235',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '485',
									'rght' => '486',
									'Permission' =>
										array(
											'id' => '2509',
											'aro_id' => '1',
											'aco_id' => '235',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							443 =>
								array(
									'id' => '241',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadCollectionGraphData',
									'lft' => '497',
									'rght' => '498',
									'Permission' =>
										array(
											'id' => '2510',
											'aro_id' => '1',
											'aco_id' => '241',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							444 =>
								array(
									'id' => '242',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '499',
									'rght' => '500',
									'Permission' =>
										array(
											'id' => '2511',
											'aro_id' => '1',
											'aco_id' => '242',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							445 =>
								array(
									'id' => '243',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '501',
									'rght' => '502',
									'Permission' =>
										array(
											'id' => '2512',
											'aro_id' => '1',
											'aco_id' => '243',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							446 =>
								array(
									'id' => '244',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '503',
									'rght' => '504',
									'Permission' =>
										array(
											'id' => '2513',
											'aro_id' => '1',
											'aco_id' => '244',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							447 =>
								array(
									'id' => '245',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '505',
									'rght' => '506',
									'Permission' =>
										array(
											'id' => '2514',
											'aro_id' => '1',
											'aco_id' => '245',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							448 =>
								array(
									'id' => '246',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '507',
									'rght' => '508',
									'Permission' =>
										array(
											'id' => '2515',
											'aro_id' => '1',
											'aco_id' => '246',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							449 =>
								array(
									'id' => '247',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '509',
									'rght' => '510',
									'Permission' =>
										array(
											'id' => '2516',
											'aro_id' => '1',
											'aco_id' => '247',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							450 =>
								array(
									'id' => '248',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '511',
									'rght' => '512',
									'Permission' =>
										array(
											'id' => '2517',
											'aro_id' => '1',
											'aco_id' => '248',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							451 =>
								array(
									'id' => '255',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadServicesByHostId',
									'lft' => '527',
									'rght' => '528',
									'Permission' =>
										array(
											'id' => '2518',
											'aro_id' => '1',
											'aco_id' => '255',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							452 =>
								array(
									'id' => '256',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadPerfDataStructures',
									'lft' => '529',
									'rght' => '530',
									'Permission' =>
										array(
											'id' => '2519',
											'aro_id' => '1',
											'aco_id' => '256',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							453 =>
								array(
									'id' => '257',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadServiceruleFromService',
									'lft' => '531',
									'rght' => '532',
									'Permission' =>
										array(
											'id' => '2520',
											'aro_id' => '1',
											'aco_id' => '257',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							454 =>
								array(
									'id' => '258',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'fetchGraphData',
									'lft' => '533',
									'rght' => '534',
									'Permission' =>
										array(
											'id' => '2521',
											'aro_id' => '1',
											'aco_id' => '258',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							455 =>
								array(
									'id' => '259',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '535',
									'rght' => '536',
									'Permission' =>
										array(
											'id' => '2522',
											'aro_id' => '1',
											'aco_id' => '259',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							456 =>
								array(
									'id' => '260',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '537',
									'rght' => '538',
									'Permission' =>
										array(
											'id' => '2523',
											'aro_id' => '1',
											'aco_id' => '260',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							457 =>
								array(
									'id' => '261',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '539',
									'rght' => '540',
									'Permission' =>
										array(
											'id' => '2524',
											'aro_id' => '1',
											'aco_id' => '261',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							458 =>
								array(
									'id' => '262',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '541',
									'rght' => '542',
									'Permission' =>
										array(
											'id' => '2525',
											'aro_id' => '1',
											'aco_id' => '262',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							459 =>
								array(
									'id' => '263',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '543',
									'rght' => '544',
									'Permission' =>
										array(
											'id' => '2526',
											'aro_id' => '1',
											'aco_id' => '263',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							460 =>
								array(
									'id' => '264',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '545',
									'rght' => '546',
									'Permission' =>
										array(
											'id' => '2527',
											'aro_id' => '1',
											'aco_id' => '264',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							461 =>
								array(
									'id' => '265',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '547',
									'rght' => '548',
									'Permission' =>
										array(
											'id' => '2528',
											'aro_id' => '1',
											'aco_id' => '265',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							462 =>
								array(
									'id' => '268',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '555',
									'rght' => '556',
									'Permission' =>
										array(
											'id' => '2529',
											'aro_id' => '1',
											'aco_id' => '268',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							463 =>
								array(
									'id' => '269',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '557',
									'rght' => '558',
									'Permission' =>
										array(
											'id' => '2530',
											'aro_id' => '1',
											'aco_id' => '269',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							464 =>
								array(
									'id' => '270',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '559',
									'rght' => '560',
									'Permission' =>
										array(
											'id' => '2531',
											'aro_id' => '1',
											'aco_id' => '270',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							465 =>
								array(
									'id' => '271',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '561',
									'rght' => '562',
									'Permission' =>
										array(
											'id' => '2532',
											'aro_id' => '1',
											'aco_id' => '271',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							466 =>
								array(
									'id' => '272',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '563',
									'rght' => '564',
									'Permission' =>
										array(
											'id' => '2533',
											'aro_id' => '1',
											'aco_id' => '272',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							467 =>
								array(
									'id' => '273',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '565',
									'rght' => '566',
									'Permission' =>
										array(
											'id' => '2534',
											'aro_id' => '1',
											'aco_id' => '273',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							468 =>
								array(
									'id' => '274',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '567',
									'rght' => '568',
									'Permission' =>
										array(
											'id' => '2535',
											'aro_id' => '1',
											'aco_id' => '274',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							469 =>
								array(
									'id' => '281',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '581',
									'rght' => '582',
									'Permission' =>
										array(
											'id' => '2536',
											'aro_id' => '1',
											'aco_id' => '281',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							470 =>
								array(
									'id' => '282',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '583',
									'rght' => '584',
									'Permission' =>
										array(
											'id' => '2537',
											'aro_id' => '1',
											'aco_id' => '282',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							471 =>
								array(
									'id' => '283',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '585',
									'rght' => '586',
									'Permission' =>
										array(
											'id' => '2538',
											'aro_id' => '1',
											'aco_id' => '283',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							472 =>
								array(
									'id' => '284',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '587',
									'rght' => '588',
									'Permission' =>
										array(
											'id' => '2539',
											'aro_id' => '1',
											'aco_id' => '284',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							473 =>
								array(
									'id' => '285',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '589',
									'rght' => '590',
									'Permission' =>
										array(
											'id' => '2540',
											'aro_id' => '1',
											'aco_id' => '285',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							474 =>
								array(
									'id' => '286',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '591',
									'rght' => '592',
									'Permission' =>
										array(
											'id' => '2541',
											'aro_id' => '1',
											'aco_id' => '286',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							475 =>
								array(
									'id' => '287',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '593',
									'rght' => '594',
									'Permission' =>
										array(
											'id' => '2542',
											'aro_id' => '1',
											'aco_id' => '287',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							476 =>
								array(
									'id' => '294',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '609',
									'rght' => '610',
									'Permission' =>
										array(
											'id' => '2543',
											'aro_id' => '1',
											'aco_id' => '294',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							477 =>
								array(
									'id' => '295',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '611',
									'rght' => '612',
									'Permission' =>
										array(
											'id' => '2544',
											'aro_id' => '1',
											'aco_id' => '295',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							478 =>
								array(
									'id' => '296',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '613',
									'rght' => '614',
									'Permission' =>
										array(
											'id' => '2545',
											'aro_id' => '1',
											'aco_id' => '296',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							479 =>
								array(
									'id' => '297',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '615',
									'rght' => '616',
									'Permission' =>
										array(
											'id' => '2546',
											'aro_id' => '1',
											'aco_id' => '297',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							480 =>
								array(
									'id' => '298',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '617',
									'rght' => '618',
									'Permission' =>
										array(
											'id' => '2547',
											'aro_id' => '1',
											'aco_id' => '298',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							481 =>
								array(
									'id' => '299',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '619',
									'rght' => '620',
									'Permission' =>
										array(
											'id' => '2548',
											'aro_id' => '1',
											'aco_id' => '299',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							482 =>
								array(
									'id' => '300',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '621',
									'rght' => '622',
									'Permission' =>
										array(
											'id' => '2549',
											'aro_id' => '1',
											'aco_id' => '300',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							483 =>
								array(
									'id' => '311',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '645',
									'rght' => '646',
									'Permission' =>
										array(
											'id' => '2550',
											'aro_id' => '1',
											'aco_id' => '311',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							484 =>
								array(
									'id' => '312',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '647',
									'rght' => '648',
									'Permission' =>
										array(
											'id' => '2551',
											'aro_id' => '1',
											'aco_id' => '312',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							485 =>
								array(
									'id' => '313',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '649',
									'rght' => '650',
									'Permission' =>
										array(
											'id' => '2552',
											'aro_id' => '1',
											'aco_id' => '313',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							486 =>
								array(
									'id' => '314',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '651',
									'rght' => '652',
									'Permission' =>
										array(
											'id' => '2553',
											'aro_id' => '1',
											'aco_id' => '314',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							487 =>
								array(
									'id' => '315',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '653',
									'rght' => '654',
									'Permission' =>
										array(
											'id' => '2554',
											'aro_id' => '1',
											'aco_id' => '315',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							488 =>
								array(
									'id' => '316',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '655',
									'rght' => '656',
									'Permission' =>
										array(
											'id' => '2555',
											'aro_id' => '1',
											'aco_id' => '316',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							489 =>
								array(
									'id' => '317',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '657',
									'rght' => '658',
									'Permission' =>
										array(
											'id' => '2556',
											'aro_id' => '1',
											'aco_id' => '317',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							490 =>
								array(
									'id' => '349',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '723',
									'rght' => '724',
									'Permission' =>
										array(
											'id' => '2557',
											'aro_id' => '1',
											'aco_id' => '349',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							491 =>
								array(
									'id' => '350',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '725',
									'rght' => '726',
									'Permission' =>
										array(
											'id' => '2558',
											'aro_id' => '1',
											'aco_id' => '350',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							492 =>
								array(
									'id' => '351',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '727',
									'rght' => '728',
									'Permission' =>
										array(
											'id' => '2559',
											'aro_id' => '1',
											'aco_id' => '351',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							493 =>
								array(
									'id' => '352',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '729',
									'rght' => '730',
									'Permission' =>
										array(
											'id' => '2560',
											'aro_id' => '1',
											'aco_id' => '352',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							494 =>
								array(
									'id' => '353',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '731',
									'rght' => '732',
									'Permission' =>
										array(
											'id' => '2561',
											'aro_id' => '1',
											'aco_id' => '353',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							495 =>
								array(
									'id' => '354',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '733',
									'rght' => '734',
									'Permission' =>
										array(
											'id' => '2562',
											'aro_id' => '1',
											'aco_id' => '354',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							496 =>
								array(
									'id' => '355',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '735',
									'rght' => '736',
									'Permission' =>
										array(
											'id' => '2563',
											'aro_id' => '1',
											'aco_id' => '355',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							497 =>
								array(
									'id' => '940',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '737',
									'rght' => '738',
									'Permission' =>
										array(
											'id' => '2564',
											'aro_id' => '1',
											'aco_id' => '940',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							498 =>
								array(
									'id' => '366',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '763',
									'rght' => '764',
									'Permission' =>
										array(
											'id' => '2565',
											'aro_id' => '1',
											'aco_id' => '366',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							499 =>
								array(
									'id' => '367',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '765',
									'rght' => '766',
									'Permission' =>
										array(
											'id' => '2566',
											'aro_id' => '1',
											'aco_id' => '367',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							500 =>
								array(
									'id' => '368',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '767',
									'rght' => '768',
									'Permission' =>
										array(
											'id' => '2567',
											'aro_id' => '1',
											'aco_id' => '368',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							501 =>
								array(
									'id' => '369',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '769',
									'rght' => '770',
									'Permission' =>
										array(
											'id' => '2568',
											'aro_id' => '1',
											'aco_id' => '369',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							502 =>
								array(
									'id' => '370',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '771',
									'rght' => '772',
									'Permission' =>
										array(
											'id' => '2569',
											'aro_id' => '1',
											'aco_id' => '370',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							503 =>
								array(
									'id' => '371',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '773',
									'rght' => '774',
									'Permission' =>
										array(
											'id' => '2570',
											'aro_id' => '1',
											'aco_id' => '371',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							504 =>
								array(
									'id' => '372',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '775',
									'rght' => '776',
									'Permission' =>
										array(
											'id' => '2571',
											'aro_id' => '1',
											'aco_id' => '372',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							505 =>
								array(
									'id' => '377',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '791',
									'rght' => '792',
									'Permission' =>
										array(
											'id' => '2572',
											'aro_id' => '1',
											'aco_id' => '377',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							506 =>
								array(
									'id' => '378',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '793',
									'rght' => '794',
									'Permission' =>
										array(
											'id' => '2573',
											'aro_id' => '1',
											'aco_id' => '378',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							507 =>
								array(
									'id' => '379',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '795',
									'rght' => '796',
									'Permission' =>
										array(
											'id' => '2574',
											'aro_id' => '1',
											'aco_id' => '379',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							508 =>
								array(
									'id' => '380',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '797',
									'rght' => '798',
									'Permission' =>
										array(
											'id' => '2575',
											'aro_id' => '1',
											'aco_id' => '380',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							509 =>
								array(
									'id' => '381',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '799',
									'rght' => '800',
									'Permission' =>
										array(
											'id' => '2576',
											'aro_id' => '1',
											'aco_id' => '381',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							510 =>
								array(
									'id' => '382',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '801',
									'rght' => '802',
									'Permission' =>
										array(
											'id' => '2577',
											'aro_id' => '1',
											'aco_id' => '382',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							511 =>
								array(
									'id' => '383',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '803',
									'rght' => '804',
									'Permission' =>
										array(
											'id' => '2578',
											'aro_id' => '1',
											'aco_id' => '383',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							512 =>
								array(
									'id' => '389',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '815',
									'rght' => '816',
									'Permission' =>
										array(
											'id' => '2579',
											'aro_id' => '1',
											'aco_id' => '389',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							513 =>
								array(
									'id' => '390',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '817',
									'rght' => '818',
									'Permission' =>
										array(
											'id' => '2580',
											'aro_id' => '1',
											'aco_id' => '390',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							514 =>
								array(
									'id' => '391',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '819',
									'rght' => '820',
									'Permission' =>
										array(
											'id' => '2581',
											'aro_id' => '1',
											'aco_id' => '391',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							515 =>
								array(
									'id' => '392',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '821',
									'rght' => '822',
									'Permission' =>
										array(
											'id' => '2582',
											'aro_id' => '1',
											'aco_id' => '392',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							516 =>
								array(
									'id' => '393',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '823',
									'rght' => '824',
									'Permission' =>
										array(
											'id' => '2583',
											'aro_id' => '1',
											'aco_id' => '393',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							517 =>
								array(
									'id' => '394',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '825',
									'rght' => '826',
									'Permission' =>
										array(
											'id' => '2584',
											'aro_id' => '1',
											'aco_id' => '394',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							518 =>
								array(
									'id' => '395',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '827',
									'rght' => '828',
									'Permission' =>
										array(
											'id' => '2585',
											'aro_id' => '1',
											'aco_id' => '395',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							519 =>
								array(
									'id' => '398',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '835',
									'rght' => '836',
									'Permission' =>
										array(
											'id' => '2586',
											'aro_id' => '1',
											'aco_id' => '398',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							520 =>
								array(
									'id' => '399',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '837',
									'rght' => '838',
									'Permission' =>
										array(
											'id' => '2587',
											'aro_id' => '1',
											'aco_id' => '399',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							521 =>
								array(
									'id' => '400',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '839',
									'rght' => '840',
									'Permission' =>
										array(
											'id' => '2588',
											'aro_id' => '1',
											'aco_id' => '400',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							522 =>
								array(
									'id' => '401',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '841',
									'rght' => '842',
									'Permission' =>
										array(
											'id' => '2589',
											'aro_id' => '1',
											'aco_id' => '401',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							523 =>
								array(
									'id' => '402',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '843',
									'rght' => '844',
									'Permission' =>
										array(
											'id' => '2590',
											'aro_id' => '1',
											'aco_id' => '402',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							524 =>
								array(
									'id' => '403',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '845',
									'rght' => '846',
									'Permission' =>
										array(
											'id' => '2591',
											'aro_id' => '1',
											'aco_id' => '403',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							525 =>
								array(
									'id' => '404',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '847',
									'rght' => '848',
									'Permission' =>
										array(
											'id' => '2592',
											'aro_id' => '1',
											'aco_id' => '404',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							526 =>
								array(
									'id' => '412',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '863',
									'rght' => '864',
									'Permission' =>
										array(
											'id' => '2593',
											'aro_id' => '1',
											'aco_id' => '412',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							527 =>
								array(
									'id' => '413',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '865',
									'rght' => '866',
									'Permission' =>
										array(
											'id' => '2594',
											'aro_id' => '1',
											'aco_id' => '413',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							528 =>
								array(
									'id' => '414',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '867',
									'rght' => '868',
									'Permission' =>
										array(
											'id' => '2595',
											'aro_id' => '1',
											'aco_id' => '414',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							529 =>
								array(
									'id' => '415',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '869',
									'rght' => '870',
									'Permission' =>
										array(
											'id' => '2596',
											'aro_id' => '1',
											'aco_id' => '415',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							530 =>
								array(
									'id' => '416',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '871',
									'rght' => '872',
									'Permission' =>
										array(
											'id' => '2597',
											'aro_id' => '1',
											'aco_id' => '416',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							531 =>
								array(
									'id' => '417',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '873',
									'rght' => '874',
									'Permission' =>
										array(
											'id' => '2598',
											'aro_id' => '1',
											'aco_id' => '417',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							532 =>
								array(
									'id' => '418',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '875',
									'rght' => '876',
									'Permission' =>
										array(
											'id' => '2599',
											'aro_id' => '1',
											'aco_id' => '418',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							533 =>
								array(
									'id' => '422',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '883',
									'rght' => '884',
									'Permission' =>
										array(
											'id' => '2600',
											'aro_id' => '1',
											'aco_id' => '422',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							534 =>
								array(
									'id' => '423',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '885',
									'rght' => '886',
									'Permission' =>
										array(
											'id' => '2601',
											'aro_id' => '1',
											'aco_id' => '423',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							535 =>
								array(
									'id' => '424',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '887',
									'rght' => '888',
									'Permission' =>
										array(
											'id' => '2602',
											'aro_id' => '1',
											'aco_id' => '424',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							536 =>
								array(
									'id' => '425',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '889',
									'rght' => '890',
									'Permission' =>
										array(
											'id' => '2603',
											'aro_id' => '1',
											'aco_id' => '425',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							537 =>
								array(
									'id' => '426',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '891',
									'rght' => '892',
									'Permission' =>
										array(
											'id' => '2604',
											'aro_id' => '1',
											'aco_id' => '426',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							538 =>
								array(
									'id' => '427',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '893',
									'rght' => '894',
									'Permission' =>
										array(
											'id' => '2605',
											'aro_id' => '1',
											'aco_id' => '427',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							539 =>
								array(
									'id' => '428',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '895',
									'rght' => '896',
									'Permission' =>
										array(
											'id' => '2606',
											'aro_id' => '1',
											'aco_id' => '428',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							540 =>
								array(
									'id' => '431',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '901',
									'rght' => '902',
									'Permission' =>
										array(
											'id' => '2607',
											'aro_id' => '1',
											'aco_id' => '431',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							541 =>
								array(
									'id' => '432',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '903',
									'rght' => '904',
									'Permission' =>
										array(
											'id' => '2608',
											'aro_id' => '1',
											'aco_id' => '432',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							542 =>
								array(
									'id' => '433',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '905',
									'rght' => '906',
									'Permission' =>
										array(
											'id' => '2609',
											'aro_id' => '1',
											'aco_id' => '433',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							543 =>
								array(
									'id' => '434',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '907',
									'rght' => '908',
									'Permission' =>
										array(
											'id' => '2610',
											'aro_id' => '1',
											'aco_id' => '434',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							544 =>
								array(
									'id' => '435',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '909',
									'rght' => '910',
									'Permission' =>
										array(
											'id' => '2611',
											'aro_id' => '1',
											'aco_id' => '435',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							545 =>
								array(
									'id' => '436',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '911',
									'rght' => '912',
									'Permission' =>
										array(
											'id' => '2612',
											'aro_id' => '1',
											'aco_id' => '436',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							546 =>
								array(
									'id' => '437',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '913',
									'rght' => '914',
									'Permission' =>
										array(
											'id' => '2613',
											'aro_id' => '1',
											'aco_id' => '437',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							547 =>
								array(
									'id' => '442',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '923',
									'rght' => '924',
									'Permission' =>
										array(
											'id' => '2614',
											'aro_id' => '1',
											'aco_id' => '442',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							548 =>
								array(
									'id' => '443',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '925',
									'rght' => '926',
									'Permission' =>
										array(
											'id' => '2615',
											'aro_id' => '1',
											'aco_id' => '443',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							549 =>
								array(
									'id' => '444',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '927',
									'rght' => '928',
									'Permission' =>
										array(
											'id' => '2616',
											'aro_id' => '1',
											'aco_id' => '444',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							550 =>
								array(
									'id' => '445',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '929',
									'rght' => '930',
									'Permission' =>
										array(
											'id' => '2617',
											'aro_id' => '1',
											'aco_id' => '445',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							551 =>
								array(
									'id' => '446',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '931',
									'rght' => '932',
									'Permission' =>
										array(
											'id' => '2618',
											'aro_id' => '1',
											'aco_id' => '446',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							552 =>
								array(
									'id' => '447',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '933',
									'rght' => '934',
									'Permission' =>
										array(
											'id' => '2619',
											'aro_id' => '1',
											'aco_id' => '447',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							553 =>
								array(
									'id' => '448',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '935',
									'rght' => '936',
									'Permission' =>
										array(
											'id' => '2620',
											'aro_id' => '1',
											'aco_id' => '448',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							554 =>
								array(
									'id' => '451',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getPackets',
									'lft' => '941',
									'rght' => '942',
									'Permission' =>
										array(
											'id' => '2621',
											'aro_id' => '1',
											'aco_id' => '451',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							555 =>
								array(
									'id' => '452',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '943',
									'rght' => '944',
									'Permission' =>
										array(
											'id' => '2622',
											'aro_id' => '1',
											'aco_id' => '452',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							556 =>
								array(
									'id' => '453',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '945',
									'rght' => '946',
									'Permission' =>
										array(
											'id' => '2623',
											'aro_id' => '1',
											'aco_id' => '453',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							557 =>
								array(
									'id' => '454',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '947',
									'rght' => '948',
									'Permission' =>
										array(
											'id' => '2624',
											'aro_id' => '1',
											'aco_id' => '454',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							558 =>
								array(
									'id' => '455',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '949',
									'rght' => '950',
									'Permission' =>
										array(
											'id' => '2625',
											'aro_id' => '1',
											'aco_id' => '455',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							559 =>
								array(
									'id' => '456',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '951',
									'rght' => '952',
									'Permission' =>
										array(
											'id' => '2626',
											'aro_id' => '1',
											'aco_id' => '456',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							560 =>
								array(
									'id' => '457',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '953',
									'rght' => '954',
									'Permission' =>
										array(
											'id' => '2627',
											'aro_id' => '1',
											'aco_id' => '457',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							561 =>
								array(
									'id' => '458',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '955',
									'rght' => '956',
									'Permission' =>
										array(
											'id' => '2628',
											'aro_id' => '1',
											'aco_id' => '458',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							562 =>
								array(
									'id' => '460',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '959',
									'rght' => '960',
									'Permission' =>
										array(
											'id' => '2629',
											'aro_id' => '1',
											'aco_id' => '460',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							563 =>
								array(
									'id' => '461',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'deleteImage',
									'lft' => '961',
									'rght' => '962',
									'Permission' =>
										array(
											'id' => '2630',
											'aro_id' => '1',
											'aco_id' => '461',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							564 =>
								array(
									'id' => '462',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '963',
									'rght' => '964',
									'Permission' =>
										array(
											'id' => '2631',
											'aro_id' => '1',
											'aco_id' => '462',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							565 =>
								array(
									'id' => '463',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '965',
									'rght' => '966',
									'Permission' =>
										array(
											'id' => '2632',
											'aro_id' => '1',
											'aco_id' => '463',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							566 =>
								array(
									'id' => '464',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '967',
									'rght' => '968',
									'Permission' =>
										array(
											'id' => '2633',
											'aro_id' => '1',
											'aco_id' => '464',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							567 =>
								array(
									'id' => '465',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '969',
									'rght' => '970',
									'Permission' =>
										array(
											'id' => '2634',
											'aro_id' => '1',
											'aco_id' => '465',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							568 =>
								array(
									'id' => '466',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '971',
									'rght' => '972',
									'Permission' =>
										array(
											'id' => '2635',
											'aro_id' => '1',
											'aco_id' => '466',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							569 =>
								array(
									'id' => '467',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '973',
									'rght' => '974',
									'Permission' =>
										array(
											'id' => '2636',
											'aro_id' => '1',
											'aco_id' => '467',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							570 =>
								array(
									'id' => '468',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '975',
									'rght' => '976',
									'Permission' =>
										array(
											'id' => '2637',
											'aro_id' => '1',
											'aco_id' => '468',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							571 =>
								array(
									'id' => '472',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getSettings',
									'lft' => '983',
									'rght' => '984',
									'Permission' =>
										array(
											'id' => '2638',
											'aro_id' => '1',
											'aco_id' => '472',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							572 =>
								array(
									'id' => '473',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '985',
									'rght' => '986',
									'Permission' =>
										array(
											'id' => '2639',
											'aro_id' => '1',
											'aco_id' => '473',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							573 =>
								array(
									'id' => '474',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '987',
									'rght' => '988',
									'Permission' =>
										array(
											'id' => '2640',
											'aro_id' => '1',
											'aco_id' => '474',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							574 =>
								array(
									'id' => '475',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '989',
									'rght' => '990',
									'Permission' =>
										array(
											'id' => '2641',
											'aro_id' => '1',
											'aco_id' => '475',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							575 =>
								array(
									'id' => '476',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '991',
									'rght' => '992',
									'Permission' =>
										array(
											'id' => '2642',
											'aro_id' => '1',
											'aco_id' => '476',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							576 =>
								array(
									'id' => '477',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '993',
									'rght' => '994',
									'Permission' =>
										array(
											'id' => '2643',
											'aro_id' => '1',
											'aco_id' => '477',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							577 =>
								array(
									'id' => '478',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '995',
									'rght' => '996',
									'Permission' =>
										array(
											'id' => '2644',
											'aro_id' => '1',
											'aco_id' => '478',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							578 =>
								array(
									'id' => '479',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '997',
									'rght' => '998',
									'Permission' =>
										array(
											'id' => '2645',
											'aro_id' => '1',
											'aco_id' => '479',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							579 =>
								array(
									'id' => '482',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1003',
									'rght' => '1004',
									'Permission' =>
										array(
											'id' => '2646',
											'aro_id' => '1',
											'aco_id' => '482',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							580 =>
								array(
									'id' => '483',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1005',
									'rght' => '1006',
									'Permission' =>
										array(
											'id' => '2647',
											'aro_id' => '1',
											'aco_id' => '483',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							581 =>
								array(
									'id' => '484',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1007',
									'rght' => '1008',
									'Permission' =>
										array(
											'id' => '2648',
											'aro_id' => '1',
											'aco_id' => '484',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							582 =>
								array(
									'id' => '485',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1009',
									'rght' => '1010',
									'Permission' =>
										array(
											'id' => '2649',
											'aro_id' => '1',
											'aco_id' => '485',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							583 =>
								array(
									'id' => '486',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1011',
									'rght' => '1012',
									'Permission' =>
										array(
											'id' => '2650',
											'aro_id' => '1',
											'aco_id' => '486',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							584 =>
								array(
									'id' => '487',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1013',
									'rght' => '1014',
									'Permission' =>
										array(
											'id' => '2651',
											'aro_id' => '1',
											'aco_id' => '487',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							585 =>
								array(
									'id' => '488',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1015',
									'rght' => '1016',
									'Permission' =>
										array(
											'id' => '2652',
											'aro_id' => '1',
											'aco_id' => '488',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							586 =>
								array(
									'id' => '492',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1023',
									'rght' => '1024',
									'Permission' =>
										array(
											'id' => '2653',
											'aro_id' => '1',
											'aco_id' => '492',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							587 =>
								array(
									'id' => '493',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1025',
									'rght' => '1026',
									'Permission' =>
										array(
											'id' => '2654',
											'aro_id' => '1',
											'aco_id' => '493',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							588 =>
								array(
									'id' => '494',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1027',
									'rght' => '1028',
									'Permission' =>
										array(
											'id' => '2655',
											'aro_id' => '1',
											'aco_id' => '494',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							589 =>
								array(
									'id' => '495',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1029',
									'rght' => '1030',
									'Permission' =>
										array(
											'id' => '2656',
											'aro_id' => '1',
											'aco_id' => '495',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							590 =>
								array(
									'id' => '496',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1031',
									'rght' => '1032',
									'Permission' =>
										array(
											'id' => '2657',
											'aro_id' => '1',
											'aco_id' => '496',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							591 =>
								array(
									'id' => '497',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1033',
									'rght' => '1034',
									'Permission' =>
										array(
											'id' => '2658',
											'aro_id' => '1',
											'aco_id' => '497',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							592 =>
								array(
									'id' => '498',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1035',
									'rght' => '1036',
									'Permission' =>
										array(
											'id' => '2659',
											'aro_id' => '1',
											'aco_id' => '498',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							593 =>
								array(
									'id' => '500',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1039',
									'rght' => '1040',
									'Permission' =>
										array(
											'id' => '2660',
											'aro_id' => '1',
											'aco_id' => '500',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							594 =>
								array(
									'id' => '501',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'ajax',
									'lft' => '1041',
									'rght' => '1042',
									'Permission' =>
										array(
											'id' => '2661',
											'aro_id' => '1',
											'aco_id' => '501',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							595 =>
								array(
									'id' => '502',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1043',
									'rght' => '1044',
									'Permission' =>
										array(
											'id' => '2662',
											'aro_id' => '1',
											'aco_id' => '502',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							596 =>
								array(
									'id' => '503',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1045',
									'rght' => '1046',
									'Permission' =>
										array(
											'id' => '2663',
											'aro_id' => '1',
											'aco_id' => '503',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							597 =>
								array(
									'id' => '504',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1047',
									'rght' => '1048',
									'Permission' =>
										array(
											'id' => '2664',
											'aro_id' => '1',
											'aco_id' => '504',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							598 =>
								array(
									'id' => '505',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1049',
									'rght' => '1050',
									'Permission' =>
										array(
											'id' => '2665',
											'aro_id' => '1',
											'aco_id' => '505',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							599 =>
								array(
									'id' => '506',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1051',
									'rght' => '1052',
									'Permission' =>
										array(
											'id' => '2666',
											'aro_id' => '1',
											'aco_id' => '506',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							600 =>
								array(
									'id' => '507',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1053',
									'rght' => '1054',
									'Permission' =>
										array(
											'id' => '2667',
											'aro_id' => '1',
											'aco_id' => '507',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							601 =>
								array(
									'id' => '508',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1055',
									'rght' => '1056',
									'Permission' =>
										array(
											'id' => '2668',
											'aro_id' => '1',
											'aco_id' => '508',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							602 =>
								array(
									'id' => '510',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1059',
									'rght' => '1060',
									'Permission' =>
										array(
											'id' => '2669',
											'aro_id' => '1',
											'aco_id' => '510',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							603 =>
								array(
									'id' => '511',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'hostMacro',
									'lft' => '1061',
									'rght' => '1062',
									'Permission' =>
										array(
											'id' => '2670',
											'aro_id' => '1',
											'aco_id' => '511',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							604 =>
								array(
									'id' => '512',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceMacro',
									'lft' => '1063',
									'rght' => '1064',
									'Permission' =>
										array(
											'id' => '2671',
											'aro_id' => '1',
											'aco_id' => '512',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							605 =>
								array(
									'id' => '513',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1065',
									'rght' => '1066',
									'Permission' =>
										array(
											'id' => '2672',
											'aro_id' => '1',
											'aco_id' => '513',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							606 =>
								array(
									'id' => '514',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1067',
									'rght' => '1068',
									'Permission' =>
										array(
											'id' => '2673',
											'aro_id' => '1',
											'aco_id' => '514',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							607 =>
								array(
									'id' => '515',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1069',
									'rght' => '1070',
									'Permission' =>
										array(
											'id' => '2674',
											'aro_id' => '1',
											'aco_id' => '515',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							608 =>
								array(
									'id' => '516',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1071',
									'rght' => '1072',
									'Permission' =>
										array(
											'id' => '2675',
											'aro_id' => '1',
											'aco_id' => '516',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							609 =>
								array(
									'id' => '517',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1073',
									'rght' => '1074',
									'Permission' =>
										array(
											'id' => '2676',
											'aro_id' => '1',
											'aco_id' => '517',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							610 =>
								array(
									'id' => '518',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1075',
									'rght' => '1076',
									'Permission' =>
										array(
											'id' => '2677',
											'aro_id' => '1',
											'aco_id' => '518',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							611 =>
								array(
									'id' => '519',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1077',
									'rght' => '1078',
									'Permission' =>
										array(
											'id' => '2678',
											'aro_id' => '1',
											'aco_id' => '519',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							612 =>
								array(
									'id' => '522',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1083',
									'rght' => '1084',
									'Permission' =>
										array(
											'id' => '2679',
											'aro_id' => '1',
											'aco_id' => '522',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							613 =>
								array(
									'id' => '523',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1085',
									'rght' => '1086',
									'Permission' =>
										array(
											'id' => '2680',
											'aro_id' => '1',
											'aco_id' => '523',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							614 =>
								array(
									'id' => '524',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1087',
									'rght' => '1088',
									'Permission' =>
										array(
											'id' => '2681',
											'aro_id' => '1',
											'aco_id' => '524',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							615 =>
								array(
									'id' => '525',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1089',
									'rght' => '1090',
									'Permission' =>
										array(
											'id' => '2682',
											'aro_id' => '1',
											'aco_id' => '525',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							616 =>
								array(
									'id' => '526',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1091',
									'rght' => '1092',
									'Permission' =>
										array(
											'id' => '2683',
											'aro_id' => '1',
											'aco_id' => '526',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							617 =>
								array(
									'id' => '527',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1093',
									'rght' => '1094',
									'Permission' =>
										array(
											'id' => '2684',
											'aro_id' => '1',
											'aco_id' => '527',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							618 =>
								array(
									'id' => '528',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1095',
									'rght' => '1096',
									'Permission' =>
										array(
											'id' => '2685',
											'aro_id' => '1',
											'aco_id' => '528',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							619 =>
								array(
									'id' => '535',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1109',
									'rght' => '1110',
									'Permission' =>
										array(
											'id' => '2686',
											'aro_id' => '1',
											'aco_id' => '535',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							620 =>
								array(
									'id' => '536',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1111',
									'rght' => '1112',
									'Permission' =>
										array(
											'id' => '2687',
											'aro_id' => '1',
											'aco_id' => '536',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							621 =>
								array(
									'id' => '537',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1113',
									'rght' => '1114',
									'Permission' =>
										array(
											'id' => '2688',
											'aro_id' => '1',
											'aco_id' => '537',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							622 =>
								array(
									'id' => '538',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1115',
									'rght' => '1116',
									'Permission' =>
										array(
											'id' => '2689',
											'aro_id' => '1',
											'aco_id' => '538',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							623 =>
								array(
									'id' => '539',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1117',
									'rght' => '1118',
									'Permission' =>
										array(
											'id' => '2690',
											'aro_id' => '1',
											'aco_id' => '539',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							624 =>
								array(
									'id' => '540',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1119',
									'rght' => '1120',
									'Permission' =>
										array(
											'id' => '2691',
											'aro_id' => '1',
											'aco_id' => '540',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							625 =>
								array(
									'id' => '541',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1121',
									'rght' => '1122',
									'Permission' =>
										array(
											'id' => '2692',
											'aro_id' => '1',
											'aco_id' => '541',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							626 =>
								array(
									'id' => '548',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1137',
									'rght' => '1138',
									'Permission' =>
										array(
											'id' => '2693',
											'aro_id' => '1',
											'aco_id' => '548',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							627 =>
								array(
									'id' => '549',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1139',
									'rght' => '1140',
									'Permission' =>
										array(
											'id' => '2694',
											'aro_id' => '1',
											'aco_id' => '549',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							628 =>
								array(
									'id' => '550',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1141',
									'rght' => '1142',
									'Permission' =>
										array(
											'id' => '2695',
											'aro_id' => '1',
											'aco_id' => '550',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							629 =>
								array(
									'id' => '551',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1143',
									'rght' => '1144',
									'Permission' =>
										array(
											'id' => '2696',
											'aro_id' => '1',
											'aco_id' => '551',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							630 =>
								array(
									'id' => '552',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1145',
									'rght' => '1146',
									'Permission' =>
										array(
											'id' => '2697',
											'aro_id' => '1',
											'aco_id' => '552',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							631 =>
								array(
									'id' => '553',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1147',
									'rght' => '1148',
									'Permission' =>
										array(
											'id' => '2698',
											'aro_id' => '1',
											'aco_id' => '553',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							632 =>
								array(
									'id' => '554',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1149',
									'rght' => '1150',
									'Permission' =>
										array(
											'id' => '2699',
											'aro_id' => '1',
											'aco_id' => '554',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							633 =>
								array(
									'id' => '564',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1171',
									'rght' => '1172',
									'Permission' =>
										array(
											'id' => '2700',
											'aro_id' => '1',
											'aco_id' => '564',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							634 =>
								array(
									'id' => '565',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1173',
									'rght' => '1174',
									'Permission' =>
										array(
											'id' => '2701',
											'aro_id' => '1',
											'aco_id' => '565',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							635 =>
								array(
									'id' => '566',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1175',
									'rght' => '1176',
									'Permission' =>
										array(
											'id' => '2702',
											'aro_id' => '1',
											'aco_id' => '566',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							636 =>
								array(
									'id' => '567',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1177',
									'rght' => '1178',
									'Permission' =>
										array(
											'id' => '2703',
											'aro_id' => '1',
											'aco_id' => '567',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							637 =>
								array(
									'id' => '568',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1179',
									'rght' => '1180',
									'Permission' =>
										array(
											'id' => '2704',
											'aro_id' => '1',
											'aco_id' => '568',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							638 =>
								array(
									'id' => '569',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1181',
									'rght' => '1182',
									'Permission' =>
										array(
											'id' => '2705',
											'aro_id' => '1',
											'aco_id' => '569',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							639 =>
								array(
									'id' => '570',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1183',
									'rght' => '1184',
									'Permission' =>
										array(
											'id' => '2706',
											'aro_id' => '1',
											'aco_id' => '570',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							640 =>
								array(
									'id' => '595',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'grapherSwitch',
									'lft' => '1235',
									'rght' => '1236',
									'Permission' =>
										array(
											'id' => '2707',
											'aro_id' => '1',
											'aco_id' => '595',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							641 =>
								array(
									'id' => '596',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'grapher',
									'lft' => '1237',
									'rght' => '1238',
									'Permission' =>
										array(
											'id' => '2708',
											'aro_id' => '1',
											'aco_id' => '596',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							642 =>
								array(
									'id' => '597',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'grapherTemplate',
									'lft' => '1239',
									'rght' => '1240',
									'Permission' =>
										array(
											'id' => '2709',
											'aro_id' => '1',
											'aco_id' => '597',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							643 =>
								array(
									'id' => '598',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'grapherZoom',
									'lft' => '1241',
									'rght' => '1242',
									'Permission' =>
										array(
											'id' => '2710',
											'aro_id' => '1',
											'aco_id' => '598',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							644 =>
								array(
									'id' => '599',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'grapherZoomTemplate',
									'lft' => '1243',
									'rght' => '1244',
									'Permission' =>
										array(
											'id' => '2711',
											'aro_id' => '1',
											'aco_id' => '599',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							645 =>
								array(
									'id' => '600',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'createGrapherErrorPng',
									'lft' => '1245',
									'rght' => '1246',
									'Permission' =>
										array(
											'id' => '2712',
											'aro_id' => '1',
											'aco_id' => '600',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							646 =>
								array(
									'id' => '604',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1253',
									'rght' => '1254',
									'Permission' =>
										array(
											'id' => '2713',
											'aro_id' => '1',
											'aco_id' => '604',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							647 =>
								array(
									'id' => '605',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1255',
									'rght' => '1256',
									'Permission' =>
										array(
											'id' => '2714',
											'aro_id' => '1',
											'aco_id' => '605',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							648 =>
								array(
									'id' => '606',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1257',
									'rght' => '1258',
									'Permission' =>
										array(
											'id' => '2715',
											'aro_id' => '1',
											'aco_id' => '606',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							649 =>
								array(
									'id' => '607',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1259',
									'rght' => '1260',
									'Permission' =>
										array(
											'id' => '2716',
											'aro_id' => '1',
											'aco_id' => '607',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							650 =>
								array(
									'id' => '608',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1261',
									'rght' => '1262',
									'Permission' =>
										array(
											'id' => '2717',
											'aro_id' => '1',
											'aco_id' => '608',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							651 =>
								array(
									'id' => '609',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1263',
									'rght' => '1264',
									'Permission' =>
										array(
											'id' => '2718',
											'aro_id' => '1',
											'aco_id' => '609',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							652 =>
								array(
									'id' => '610',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1265',
									'rght' => '1266',
									'Permission' =>
										array(
											'id' => '2719',
											'aro_id' => '1',
											'aco_id' => '610',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							653 =>
								array(
									'id' => '620',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1295',
									'rght' => '1296',
									'Permission' =>
										array(
											'id' => '2720',
											'aro_id' => '1',
											'aco_id' => '620',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							654 =>
								array(
									'id' => '621',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1297',
									'rght' => '1298',
									'Permission' =>
										array(
											'id' => '2721',
											'aro_id' => '1',
											'aco_id' => '621',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							655 =>
								array(
									'id' => '622',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1299',
									'rght' => '1300',
									'Permission' =>
										array(
											'id' => '2722',
											'aro_id' => '1',
											'aco_id' => '622',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							656 =>
								array(
									'id' => '623',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1301',
									'rght' => '1302',
									'Permission' =>
										array(
											'id' => '2723',
											'aro_id' => '1',
											'aco_id' => '623',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							657 =>
								array(
									'id' => '624',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1303',
									'rght' => '1304',
									'Permission' =>
										array(
											'id' => '2724',
											'aro_id' => '1',
											'aco_id' => '624',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							658 =>
								array(
									'id' => '625',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1305',
									'rght' => '1306',
									'Permission' =>
										array(
											'id' => '2725',
											'aro_id' => '1',
											'aco_id' => '625',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							659 =>
								array(
									'id' => '626',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1307',
									'rght' => '1308',
									'Permission' =>
										array(
											'id' => '2726',
											'aro_id' => '1',
											'aco_id' => '626',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							660 =>
								array(
									'id' => '641',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1341',
									'rght' => '1342',
									'Permission' =>
										array(
											'id' => '2727',
											'aro_id' => '1',
											'aco_id' => '641',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							661 =>
								array(
									'id' => '642',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1343',
									'rght' => '1344',
									'Permission' =>
										array(
											'id' => '2728',
											'aro_id' => '1',
											'aco_id' => '642',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							662 =>
								array(
									'id' => '643',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1345',
									'rght' => '1346',
									'Permission' =>
										array(
											'id' => '2729',
											'aro_id' => '1',
											'aco_id' => '643',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							663 =>
								array(
									'id' => '644',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1347',
									'rght' => '1348',
									'Permission' =>
										array(
											'id' => '2730',
											'aro_id' => '1',
											'aco_id' => '644',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							664 =>
								array(
									'id' => '645',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1349',
									'rght' => '1350',
									'Permission' =>
										array(
											'id' => '2731',
											'aro_id' => '1',
											'aco_id' => '645',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							665 =>
								array(
									'id' => '646',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1351',
									'rght' => '1352',
									'Permission' =>
										array(
											'id' => '2732',
											'aro_id' => '1',
											'aco_id' => '646',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							666 =>
								array(
									'id' => '647',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1353',
									'rght' => '1354',
									'Permission' =>
										array(
											'id' => '2733',
											'aro_id' => '1',
											'aco_id' => '647',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							667 =>
								array(
									'id' => '651',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1369',
									'rght' => '1370',
									'Permission' =>
										array(
											'id' => '2734',
											'aro_id' => '1',
											'aco_id' => '651',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							668 =>
								array(
									'id' => '652',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1371',
									'rght' => '1372',
									'Permission' =>
										array(
											'id' => '2735',
											'aro_id' => '1',
											'aco_id' => '652',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							669 =>
								array(
									'id' => '653',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1373',
									'rght' => '1374',
									'Permission' =>
										array(
											'id' => '2736',
											'aro_id' => '1',
											'aco_id' => '653',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							670 =>
								array(
									'id' => '654',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1375',
									'rght' => '1376',
									'Permission' =>
										array(
											'id' => '2737',
											'aro_id' => '1',
											'aco_id' => '654',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							671 =>
								array(
									'id' => '655',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1377',
									'rght' => '1378',
									'Permission' =>
										array(
											'id' => '2738',
											'aro_id' => '1',
											'aco_id' => '655',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							672 =>
								array(
									'id' => '656',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1379',
									'rght' => '1380',
									'Permission' =>
										array(
											'id' => '2739',
											'aro_id' => '1',
											'aco_id' => '656',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							673 =>
								array(
									'id' => '657',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1381',
									'rght' => '1382',
									'Permission' =>
										array(
											'id' => '2740',
											'aro_id' => '1',
											'aco_id' => '657',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							674 =>
								array(
									'id' => '660',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getHostsAndConnections',
									'lft' => '1387',
									'rght' => '1388',
									'Permission' =>
										array(
											'id' => '2741',
											'aro_id' => '1',
											'aco_id' => '660',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							675 =>
								array(
									'id' => '661',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'clickHostStatus',
									'lft' => '1389',
									'rght' => '1390',
									'Permission' =>
										array(
											'id' => '2742',
											'aro_id' => '1',
											'aco_id' => '661',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							676 =>
								array(
									'id' => '663',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1393',
									'rght' => '1394',
									'Permission' =>
										array(
											'id' => '2743',
											'aro_id' => '1',
											'aco_id' => '663',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							677 =>
								array(
									'id' => '664',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1395',
									'rght' => '1396',
									'Permission' =>
										array(
											'id' => '2744',
											'aro_id' => '1',
											'aco_id' => '664',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							678 =>
								array(
									'id' => '665',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1397',
									'rght' => '1398',
									'Permission' =>
										array(
											'id' => '2745',
											'aro_id' => '1',
											'aco_id' => '665',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							679 =>
								array(
									'id' => '666',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1399',
									'rght' => '1400',
									'Permission' =>
										array(
											'id' => '2746',
											'aro_id' => '1',
											'aco_id' => '666',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							680 =>
								array(
									'id' => '667',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1401',
									'rght' => '1402',
									'Permission' =>
										array(
											'id' => '2747',
											'aro_id' => '1',
											'aco_id' => '667',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							681 =>
								array(
									'id' => '668',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1403',
									'rght' => '1404',
									'Permission' =>
										array(
											'id' => '2748',
											'aro_id' => '1',
											'aco_id' => '668',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							682 =>
								array(
									'id' => '669',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1405',
									'rght' => '1406',
									'Permission' =>
										array(
											'id' => '2749',
											'aro_id' => '1',
											'aco_id' => '669',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							683 =>
								array(
									'id' => '672',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1411',
									'rght' => '1412',
									'Permission' =>
										array(
											'id' => '2750',
											'aro_id' => '1',
											'aco_id' => '672',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							684 =>
								array(
									'id' => '673',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1413',
									'rght' => '1414',
									'Permission' =>
										array(
											'id' => '2751',
											'aro_id' => '1',
											'aco_id' => '673',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							685 =>
								array(
									'id' => '674',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1415',
									'rght' => '1416',
									'Permission' =>
										array(
											'id' => '2752',
											'aro_id' => '1',
											'aco_id' => '674',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							686 =>
								array(
									'id' => '675',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1417',
									'rght' => '1418',
									'Permission' =>
										array(
											'id' => '2753',
											'aro_id' => '1',
											'aco_id' => '675',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							687 =>
								array(
									'id' => '676',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1419',
									'rght' => '1420',
									'Permission' =>
										array(
											'id' => '2754',
											'aro_id' => '1',
											'aco_id' => '676',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							688 =>
								array(
									'id' => '677',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1421',
									'rght' => '1422',
									'Permission' =>
										array(
											'id' => '2755',
											'aro_id' => '1',
											'aco_id' => '677',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							689 =>
								array(
									'id' => '678',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1423',
									'rght' => '1424',
									'Permission' =>
										array(
											'id' => '2756',
											'aro_id' => '1',
											'aco_id' => '678',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							690 =>
								array(
									'id' => '685',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1437',
									'rght' => '1438',
									'Permission' =>
										array(
											'id' => '2757',
											'aro_id' => '1',
											'aco_id' => '685',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							691 =>
								array(
									'id' => '686',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1439',
									'rght' => '1440',
									'Permission' =>
										array(
											'id' => '2758',
											'aro_id' => '1',
											'aco_id' => '686',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							692 =>
								array(
									'id' => '687',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1441',
									'rght' => '1442',
									'Permission' =>
										array(
											'id' => '2759',
											'aro_id' => '1',
											'aco_id' => '687',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							693 =>
								array(
									'id' => '688',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1443',
									'rght' => '1444',
									'Permission' =>
										array(
											'id' => '2760',
											'aro_id' => '1',
											'aco_id' => '688',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							694 =>
								array(
									'id' => '689',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1445',
									'rght' => '1446',
									'Permission' =>
										array(
											'id' => '2761',
											'aro_id' => '1',
											'aco_id' => '689',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							695 =>
								array(
									'id' => '690',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1447',
									'rght' => '1448',
									'Permission' =>
										array(
											'id' => '2762',
											'aro_id' => '1',
											'aco_id' => '690',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							696 =>
								array(
									'id' => '691',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1449',
									'rght' => '1450',
									'Permission' =>
										array(
											'id' => '2763',
											'aro_id' => '1',
											'aco_id' => '691',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							697 =>
								array(
									'id' => '696',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1459',
									'rght' => '1460',
									'Permission' =>
										array(
											'id' => '2764',
											'aro_id' => '1',
											'aco_id' => '696',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							698 =>
								array(
									'id' => '697',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1461',
									'rght' => '1462',
									'Permission' =>
										array(
											'id' => '2765',
											'aro_id' => '1',
											'aco_id' => '697',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							699 =>
								array(
									'id' => '698',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1463',
									'rght' => '1464',
									'Permission' =>
										array(
											'id' => '2766',
											'aro_id' => '1',
											'aco_id' => '698',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							700 =>
								array(
									'id' => '699',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1465',
									'rght' => '1466',
									'Permission' =>
										array(
											'id' => '2767',
											'aro_id' => '1',
											'aco_id' => '699',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							701 =>
								array(
									'id' => '700',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1467',
									'rght' => '1468',
									'Permission' =>
										array(
											'id' => '2768',
											'aro_id' => '1',
											'aco_id' => '700',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							702 =>
								array(
									'id' => '701',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1469',
									'rght' => '1470',
									'Permission' =>
										array(
											'id' => '2769',
											'aro_id' => '1',
											'aco_id' => '701',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							703 =>
								array(
									'id' => '702',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1471',
									'rght' => '1472',
									'Permission' =>
										array(
											'id' => '2770',
											'aro_id' => '1',
											'aco_id' => '702',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							704 =>
								array(
									'id' => '705',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1477',
									'rght' => '1478',
									'Permission' =>
										array(
											'id' => '2771',
											'aro_id' => '1',
											'aco_id' => '705',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							705 =>
								array(
									'id' => '706',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1479',
									'rght' => '1480',
									'Permission' =>
										array(
											'id' => '2772',
											'aro_id' => '1',
											'aco_id' => '706',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							706 =>
								array(
									'id' => '707',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1481',
									'rght' => '1482',
									'Permission' =>
										array(
											'id' => '2773',
											'aro_id' => '1',
											'aco_id' => '707',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							707 =>
								array(
									'id' => '708',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1483',
									'rght' => '1484',
									'Permission' =>
										array(
											'id' => '2774',
											'aro_id' => '1',
											'aco_id' => '708',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							708 =>
								array(
									'id' => '709',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1485',
									'rght' => '1486',
									'Permission' =>
										array(
											'id' => '2775',
											'aro_id' => '1',
											'aco_id' => '709',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							709 =>
								array(
									'id' => '710',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1487',
									'rght' => '1488',
									'Permission' =>
										array(
											'id' => '2776',
											'aro_id' => '1',
											'aco_id' => '710',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							710 =>
								array(
									'id' => '711',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1489',
									'rght' => '1490',
									'Permission' =>
										array(
											'id' => '2777',
											'aro_id' => '1',
											'aco_id' => '711',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							711 =>
								array(
									'id' => '718',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1503',
									'rght' => '1504',
									'Permission' =>
										array(
											'id' => '2778',
											'aro_id' => '1',
											'aco_id' => '718',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							712 =>
								array(
									'id' => '719',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1505',
									'rght' => '1506',
									'Permission' =>
										array(
											'id' => '2779',
											'aro_id' => '1',
											'aco_id' => '719',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							713 =>
								array(
									'id' => '720',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1507',
									'rght' => '1508',
									'Permission' =>
										array(
											'id' => '2780',
											'aro_id' => '1',
											'aco_id' => '720',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							714 =>
								array(
									'id' => '721',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1509',
									'rght' => '1510',
									'Permission' =>
										array(
											'id' => '2781',
											'aro_id' => '1',
											'aco_id' => '721',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							715 =>
								array(
									'id' => '722',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1511',
									'rght' => '1512',
									'Permission' =>
										array(
											'id' => '2782',
											'aro_id' => '1',
											'aco_id' => '722',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							716 =>
								array(
									'id' => '723',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1513',
									'rght' => '1514',
									'Permission' =>
										array(
											'id' => '2783',
											'aro_id' => '1',
											'aco_id' => '723',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							717 =>
								array(
									'id' => '724',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1515',
									'rght' => '1516',
									'Permission' =>
										array(
											'id' => '2784',
											'aro_id' => '1',
											'aco_id' => '724',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							718 =>
								array(
									'id' => '733',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1535',
									'rght' => '1536',
									'Permission' =>
										array(
											'id' => '2785',
											'aro_id' => '1',
											'aco_id' => '733',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							719 =>
								array(
									'id' => '734',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1537',
									'rght' => '1538',
									'Permission' =>
										array(
											'id' => '2786',
											'aro_id' => '1',
											'aco_id' => '734',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							720 =>
								array(
									'id' => '735',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1539',
									'rght' => '1540',
									'Permission' =>
										array(
											'id' => '2787',
											'aro_id' => '1',
											'aco_id' => '735',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							721 =>
								array(
									'id' => '736',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1541',
									'rght' => '1542',
									'Permission' =>
										array(
											'id' => '2788',
											'aro_id' => '1',
											'aco_id' => '736',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							722 =>
								array(
									'id' => '737',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1543',
									'rght' => '1544',
									'Permission' =>
										array(
											'id' => '2789',
											'aro_id' => '1',
											'aco_id' => '737',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							723 =>
								array(
									'id' => '738',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1545',
									'rght' => '1546',
									'Permission' =>
										array(
											'id' => '2790',
											'aro_id' => '1',
											'aco_id' => '738',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							724 =>
								array(
									'id' => '739',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1547',
									'rght' => '1548',
									'Permission' =>
										array(
											'id' => '2791',
											'aro_id' => '1',
											'aco_id' => '739',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							725 =>
								array(
									'id' => '745',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1561',
									'rght' => '1562',
									'Permission' =>
										array(
											'id' => '2792',
											'aro_id' => '1',
											'aco_id' => '745',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							726 =>
								array(
									'id' => '746',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1563',
									'rght' => '1564',
									'Permission' =>
										array(
											'id' => '2793',
											'aro_id' => '1',
											'aco_id' => '746',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							727 =>
								array(
									'id' => '747',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1565',
									'rght' => '1566',
									'Permission' =>
										array(
											'id' => '2794',
											'aro_id' => '1',
											'aco_id' => '747',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							728 =>
								array(
									'id' => '748',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1567',
									'rght' => '1568',
									'Permission' =>
										array(
											'id' => '2795',
											'aro_id' => '1',
											'aco_id' => '748',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							729 =>
								array(
									'id' => '749',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1569',
									'rght' => '1570',
									'Permission' =>
										array(
											'id' => '2796',
											'aro_id' => '1',
											'aco_id' => '749',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							730 =>
								array(
									'id' => '750',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1571',
									'rght' => '1572',
									'Permission' =>
										array(
											'id' => '2797',
											'aro_id' => '1',
											'aco_id' => '750',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							731 =>
								array(
									'id' => '751',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1573',
									'rght' => '1574',
									'Permission' =>
										array(
											'id' => '2798',
											'aro_id' => '1',
											'aco_id' => '751',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							732 =>
								array(
									'id' => '759',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1591',
									'rght' => '1592',
									'Permission' =>
										array(
											'id' => '2799',
											'aro_id' => '1',
											'aco_id' => '759',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							733 =>
								array(
									'id' => '760',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1593',
									'rght' => '1594',
									'Permission' =>
										array(
											'id' => '2800',
											'aro_id' => '1',
											'aco_id' => '760',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							734 =>
								array(
									'id' => '761',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1595',
									'rght' => '1596',
									'Permission' =>
										array(
											'id' => '2801',
											'aro_id' => '1',
											'aco_id' => '761',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							735 =>
								array(
									'id' => '762',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1597',
									'rght' => '1598',
									'Permission' =>
										array(
											'id' => '2802',
											'aro_id' => '1',
											'aco_id' => '762',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							736 =>
								array(
									'id' => '763',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1599',
									'rght' => '1600',
									'Permission' =>
										array(
											'id' => '2803',
											'aro_id' => '1',
											'aco_id' => '763',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							737 =>
								array(
									'id' => '764',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1601',
									'rght' => '1602',
									'Permission' =>
										array(
											'id' => '2804',
											'aro_id' => '1',
											'aco_id' => '764',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							738 =>
								array(
									'id' => '765',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1603',
									'rght' => '1604',
									'Permission' =>
										array(
											'id' => '2805',
											'aro_id' => '1',
											'aco_id' => '765',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							739 =>
								array(
									'id' => '825',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1642',
									'rght' => '1643',
									'Permission' =>
										array(
											'id' => '2806',
											'aro_id' => '1',
											'aco_id' => '825',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							740 =>
								array(
									'id' => '826',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1644',
									'rght' => '1645',
									'Permission' =>
										array(
											'id' => '2807',
											'aro_id' => '1',
											'aco_id' => '826',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							741 =>
								array(
									'id' => '827',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1646',
									'rght' => '1647',
									'Permission' =>
										array(
											'id' => '2808',
											'aro_id' => '1',
											'aco_id' => '827',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							742 =>
								array(
									'id' => '828',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1648',
									'rght' => '1649',
									'Permission' =>
										array(
											'id' => '2809',
											'aro_id' => '1',
											'aco_id' => '828',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							743 =>
								array(
									'id' => '829',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1650',
									'rght' => '1651',
									'Permission' =>
										array(
											'id' => '2810',
											'aro_id' => '1',
											'aco_id' => '829',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							744 =>
								array(
									'id' => '830',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1652',
									'rght' => '1653',
									'Permission' =>
										array(
											'id' => '2811',
											'aro_id' => '1',
											'aco_id' => '830',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							745 =>
								array(
									'id' => '831',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1654',
									'rght' => '1655',
									'Permission' =>
										array(
											'id' => '2812',
											'aro_id' => '1',
											'aco_id' => '831',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							746 =>
								array(
									'id' => '871',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1734',
									'rght' => '1735',
									'Permission' =>
										array(
											'id' => '2813',
											'aro_id' => '1',
											'aco_id' => '871',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							747 =>
								array(
									'id' => '872',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1736',
									'rght' => '1737',
									'Permission' =>
										array(
											'id' => '2814',
											'aro_id' => '1',
											'aco_id' => '872',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							748 =>
								array(
									'id' => '873',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1738',
									'rght' => '1739',
									'Permission' =>
										array(
											'id' => '2815',
											'aro_id' => '1',
											'aco_id' => '873',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							749 =>
								array(
									'id' => '874',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1740',
									'rght' => '1741',
									'Permission' =>
										array(
											'id' => '2816',
											'aro_id' => '1',
											'aco_id' => '874',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							750 =>
								array(
									'id' => '875',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1742',
									'rght' => '1743',
									'Permission' =>
										array(
											'id' => '2817',
											'aro_id' => '1',
											'aco_id' => '875',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							751 =>
								array(
									'id' => '876',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1744',
									'rght' => '1745',
									'Permission' =>
										array(
											'id' => '2818',
											'aro_id' => '1',
											'aco_id' => '876',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							752 =>
								array(
									'id' => '877',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1746',
									'rght' => '1747',
									'Permission' =>
										array(
											'id' => '2819',
											'aro_id' => '1',
											'aco_id' => '877',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							753 =>
								array(
									'id' => '880',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1754',
									'rght' => '1755',
									'Permission' =>
										array(
											'id' => '2820',
											'aro_id' => '1',
											'aco_id' => '880',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							754 =>
								array(
									'id' => '881',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1756',
									'rght' => '1757',
									'Permission' =>
										array(
											'id' => '2821',
											'aro_id' => '1',
											'aco_id' => '881',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							755 =>
								array(
									'id' => '882',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1758',
									'rght' => '1759',
									'Permission' =>
										array(
											'id' => '2822',
											'aro_id' => '1',
											'aco_id' => '882',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							756 =>
								array(
									'id' => '883',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1760',
									'rght' => '1761',
									'Permission' =>
										array(
											'id' => '2823',
											'aro_id' => '1',
											'aco_id' => '883',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							757 =>
								array(
									'id' => '884',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1762',
									'rght' => '1763',
									'Permission' =>
										array(
											'id' => '2824',
											'aro_id' => '1',
											'aco_id' => '884',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							758 =>
								array(
									'id' => '885',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1764',
									'rght' => '1765',
									'Permission' =>
										array(
											'id' => '2825',
											'aro_id' => '1',
											'aco_id' => '885',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							759 =>
								array(
									'id' => '886',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1766',
									'rght' => '1767',
									'Permission' =>
										array(
											'id' => '2826',
											'aro_id' => '1',
											'aco_id' => '886',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							760 =>
								array(
									'id' => '890',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1771',
									'rght' => '1772',
									'Permission' =>
										array(
											'id' => '2827',
											'aro_id' => '1',
											'aco_id' => '890',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							761 =>
								array(
									'id' => '891',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'next',
									'lft' => '1773',
									'rght' => '1774',
									'Permission' =>
										array(
											'id' => '2828',
											'aro_id' => '1',
											'aco_id' => '891',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							762 =>
								array(
									'id' => '892',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '1775',
									'rght' => '1776',
									'Permission' =>
										array(
											'id' => '2829',
											'aro_id' => '1',
											'aco_id' => '892',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							763 =>
								array(
									'id' => '893',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'createTab',
									'lft' => '1777',
									'rght' => '1778',
									'Permission' =>
										array(
											'id' => '2830',
											'aro_id' => '1',
											'aco_id' => '893',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							764 =>
								array(
									'id' => '894',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'createTabFromSharing',
									'lft' => '1779',
									'rght' => '1780',
									'Permission' =>
										array(
											'id' => '2831',
											'aro_id' => '1',
											'aco_id' => '894',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							765 =>
								array(
									'id' => '895',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'updateSharedTab',
									'lft' => '1781',
									'rght' => '1782',
									'Permission' =>
										array(
											'id' => '2832',
											'aro_id' => '1',
											'aco_id' => '895',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							766 =>
								array(
									'id' => '896',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'disableUpdate',
									'lft' => '1783',
									'rght' => '1784',
									'Permission' =>
										array(
											'id' => '2833',
											'aro_id' => '1',
											'aco_id' => '896',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							767 =>
								array(
									'id' => '897',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'renameTab',
									'lft' => '1785',
									'rght' => '1786',
									'Permission' =>
										array(
											'id' => '2834',
											'aro_id' => '1',
											'aco_id' => '897',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							768 =>
								array(
									'id' => '898',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'deleteTab',
									'lft' => '1787',
									'rght' => '1788',
									'Permission' =>
										array(
											'id' => '2835',
											'aro_id' => '1',
											'aco_id' => '898',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							769 =>
								array(
									'id' => '899',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'restoreDefault',
									'lft' => '1789',
									'rght' => '1790',
									'Permission' =>
										array(
											'id' => '2836',
											'aro_id' => '1',
											'aco_id' => '899',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							770 =>
								array(
									'id' => '900',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'updateTitle',
									'lft' => '1791',
									'rght' => '1792',
									'Permission' =>
										array(
											'id' => '2837',
											'aro_id' => '1',
											'aco_id' => '900',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							771 =>
								array(
									'id' => '901',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'updateColor',
									'lft' => '1793',
									'rght' => '1794',
									'Permission' =>
										array(
											'id' => '2838',
											'aro_id' => '1',
											'aco_id' => '901',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							772 =>
								array(
									'id' => '902',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'updatePosition',
									'lft' => '1795',
									'rght' => '1796',
									'Permission' =>
										array(
											'id' => '2839',
											'aro_id' => '1',
											'aco_id' => '902',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							773 =>
								array(
									'id' => '903',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'deleteWidget',
									'lft' => '1797',
									'rght' => '1798',
									'Permission' =>
										array(
											'id' => '2840',
											'aro_id' => '1',
											'aco_id' => '903',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							774 =>
								array(
									'id' => '904',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'updateTabPosition',
									'lft' => '1799',
									'rght' => '1800',
									'Permission' =>
										array(
											'id' => '2841',
											'aro_id' => '1',
											'aco_id' => '904',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							775 =>
								array(
									'id' => '905',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'saveTabRotationInterval',
									'lft' => '1801',
									'rght' => '1802',
									'Permission' =>
										array(
											'id' => '2842',
											'aro_id' => '1',
											'aco_id' => '905',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							776 =>
								array(
									'id' => '906',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'startSharing',
									'lft' => '1803',
									'rght' => '1804',
									'Permission' =>
										array(
											'id' => '2843',
											'aro_id' => '1',
											'aco_id' => '906',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							777 =>
								array(
									'id' => '907',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'stopSharing',
									'lft' => '1805',
									'rght' => '1806',
									'Permission' =>
										array(
											'id' => '2844',
											'aro_id' => '1',
											'aco_id' => '907',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							778 =>
								array(
									'id' => '908',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'refresh',
									'lft' => '1807',
									'rght' => '1808',
									'Permission' =>
										array(
											'id' => '2845',
											'aro_id' => '1',
											'aco_id' => '908',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							779 =>
								array(
									'id' => '909',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'saveStatuslistSettings',
									'lft' => '1809',
									'rght' => '1810',
									'Permission' =>
										array(
											'id' => '2846',
											'aro_id' => '1',
											'aco_id' => '909',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							780 =>
								array(
									'id' => '910',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'saveTrafficLightService',
									'lft' => '1811',
									'rght' => '1812',
									'Permission' =>
										array(
											'id' => '2847',
											'aro_id' => '1',
											'aco_id' => '910',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							781 =>
								array(
									'id' => '911',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getTachoPerfdata',
									'lft' => '1813',
									'rght' => '1814',
									'Permission' =>
										array(
											'id' => '2848',
											'aro_id' => '1',
											'aco_id' => '911',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							782 =>
								array(
									'id' => '912',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'saveTachoConfig',
									'lft' => '1815',
									'rght' => '1816',
									'Permission' =>
										array(
											'id' => '2849',
											'aro_id' => '1',
											'aco_id' => '912',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							783 =>
								array(
									'id' => '913',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1817',
									'rght' => '1818',
									'Permission' =>
										array(
											'id' => '2850',
											'aro_id' => '1',
											'aco_id' => '913',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							784 =>
								array(
									'id' => '914',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1819',
									'rght' => '1820',
									'Permission' =>
										array(
											'id' => '2851',
											'aro_id' => '1',
											'aco_id' => '914',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							785 =>
								array(
									'id' => '915',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1821',
									'rght' => '1822',
									'Permission' =>
										array(
											'id' => '2852',
											'aro_id' => '1',
											'aco_id' => '915',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							786 =>
								array(
									'id' => '916',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1823',
									'rght' => '1824',
									'Permission' =>
										array(
											'id' => '2853',
											'aro_id' => '1',
											'aco_id' => '916',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							787 =>
								array(
									'id' => '917',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1825',
									'rght' => '1826',
									'Permission' =>
										array(
											'id' => '2854',
											'aro_id' => '1',
											'aco_id' => '917',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							788 =>
								array(
									'id' => '918',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1827',
									'rght' => '1828',
									'Permission' =>
										array(
											'id' => '2855',
											'aro_id' => '1',
											'aco_id' => '918',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							789 =>
								array(
									'id' => '919',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1829',
									'rght' => '1830',
									'Permission' =>
										array(
											'id' => '2856',
											'aro_id' => '1',
											'aco_id' => '919',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
						),
				),
			1 =>
				array(
					'Aro' =>
						array(
							'id' => '2',
							'parent_id' => NULL,
							'model' => 'Usergroup',
							'foreign_key' => '2',
							'alias' => NULL,
							'lft' => '3',
							'rght' => '4',
						),
					'Aco' =>
						array(
							0 =>
								array(
									'id' => '3',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'service',
									'lft' => '3',
									'rght' => '4',
									'Permission' =>
										array(
											'id' => '2857',
											'aro_id' => '2',
											'aco_id' => '3',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							1 =>
								array(
									'id' => '4',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'host',
									'lft' => '5',
									'rght' => '6',
									'Permission' =>
										array(
											'id' => '2858',
											'aro_id' => '2',
											'aco_id' => '4',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							2 =>
								array(
									'id' => '13',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '23',
									'rght' => '24',
									'Permission' =>
										array(
											'id' => '2859',
											'aro_id' => '2',
											'aco_id' => '13',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							3 =>
								array(
									'id' => '23',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '45',
									'rght' => '46',
									'Permission' =>
										array(
											'id' => '2860',
											'aro_id' => '2',
											'aco_id' => '23',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							4 =>
								array(
									'id' => '37',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '73',
									'rght' => '74',
									'Permission' =>
										array(
											'id' => '2861',
											'aro_id' => '2',
											'aco_id' => '37',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							5 =>
								array(
									'id' => '38',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'tenantBrowser',
									'lft' => '75',
									'rght' => '76',
									'Permission' =>
										array(
											'id' => '2862',
											'aro_id' => '2',
											'aco_id' => '38',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							6 =>
								array(
									'id' => '50',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '93',
									'rght' => '94',
									'Permission' =>
										array(
											'id' => '2863',
											'aro_id' => '2',
											'aco_id' => '50',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							7 =>
								array(
									'id' => '64',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '121',
									'rght' => '122',
									'Permission' =>
										array(
											'id' => '2864',
											'aro_id' => '2',
											'aco_id' => '64',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							8 =>
								array(
									'id' => '73',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '139',
									'rght' => '140',
									'Permission' =>
										array(
											'id' => '2865',
											'aro_id' => '2',
											'aco_id' => '73',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							9 =>
								array(
									'id' => '82',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '157',
									'rght' => '158',
									'Permission' =>
										array(
											'id' => '2866',
											'aro_id' => '2',
											'aco_id' => '82',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							10 =>
								array(
									'id' => '924',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '193',
									'rght' => '194',
									'Permission' =>
										array(
											'id' => '2867',
											'aro_id' => '2',
											'aco_id' => '924',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							11 =>
								array(
									'id' => '83',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'hostchecks',
									'lft' => '159',
									'rght' => '160',
									'Permission' =>
										array(
											'id' => '2868',
											'aro_id' => '2',
											'aco_id' => '83',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							12 =>
								array(
									'id' => '84',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'notifications',
									'lft' => '161',
									'rght' => '162',
									'Permission' =>
										array(
											'id' => '2869',
											'aro_id' => '2',
											'aco_id' => '84',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							13 =>
								array(
									'id' => '85',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'handler',
									'lft' => '163',
									'rght' => '164',
									'Permission' =>
										array(
											'id' => '2870',
											'aro_id' => '2',
											'aco_id' => '85',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							14 =>
								array(
									'id' => '101',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '199',
									'rght' => '200',
									'Permission' =>
										array(
											'id' => '2871',
											'aro_id' => '2',
											'aco_id' => '101',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							15 =>
								array(
									'id' => '926',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '225',
									'rght' => '226',
									'Permission' =>
										array(
											'id' => '2872',
											'aro_id' => '2',
											'aco_id' => '926',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							16 =>
								array(
									'id' => '115',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '229',
									'rght' => '230',
									'Permission' =>
										array(
											'id' => '2873',
											'aro_id' => '2',
											'aco_id' => '115',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							17 =>
								array(
									'id' => '927',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '257',
									'rght' => '258',
									'Permission' =>
										array(
											'id' => '2874',
											'aro_id' => '2',
											'aco_id' => '927',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							18 =>
								array(
									'id' => '129',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '261',
									'rght' => '262',
									'Permission' =>
										array(
											'id' => '2875',
											'aro_id' => '2',
											'aco_id' => '129',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							19 =>
								array(
									'id' => '929',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '287',
									'rght' => '288',
									'Permission' =>
										array(
											'id' => '2876',
											'aro_id' => '2',
											'aco_id' => '929',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							20 =>
								array(
									'id' => '928',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'nest',
									'lft' => '285',
									'rght' => '286',
									'Permission' =>
										array(
											'id' => '2877',
											'aro_id' => '2',
											'aco_id' => '928',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							21 =>
								array(
									'id' => '142',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '291',
									'rght' => '292',
									'Permission' =>
										array(
											'id' => '2878',
											'aro_id' => '2',
											'aco_id' => '142',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							22 =>
								array(
									'id' => '155',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '317',
									'rght' => '318',
									'Permission' =>
										array(
											'id' => '2879',
											'aro_id' => '2',
											'aco_id' => '155',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							23 =>
								array(
									'id' => '156',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'createPdfReport',
									'lft' => '319',
									'rght' => '320',
									'Permission' =>
										array(
											'id' => '2880',
											'aro_id' => '2',
											'aco_id' => '156',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							24 =>
								array(
									'id' => '165',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '337',
									'rght' => '338',
									'Permission' =>
										array(
											'id' => '2881',
											'aro_id' => '2',
											'aco_id' => '165',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							25 =>
								array(
									'id' => '174',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '355',
									'rght' => '356',
									'Permission' =>
										array(
											'id' => '2882',
											'aro_id' => '2',
											'aco_id' => '174',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							26 =>
								array(
									'id' => '934',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '377',
									'rght' => '378',
									'Permission' =>
										array(
											'id' => '2883',
											'aro_id' => '2',
											'aco_id' => '934',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							27 =>
								array(
									'id' => '186',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '381',
									'rght' => '382',
									'Permission' =>
										array(
											'id' => '2884',
											'aro_id' => '2',
											'aco_id' => '186',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							28 =>
								array(
									'id' => '187',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '383',
									'rght' => '384',
									'Permission' =>
										array(
											'id' => '2885',
											'aro_id' => '2',
											'aco_id' => '187',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							29 =>
								array(
									'id' => '188',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'wiki',
									'lft' => '385',
									'rght' => '386',
									'Permission' =>
										array(
											'id' => '2886',
											'aro_id' => '2',
											'aco_id' => '188',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							30 =>
								array(
									'id' => '197',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '403',
									'rght' => '404',
									'Permission' =>
										array(
											'id' => '2887',
											'aro_id' => '2',
											'aco_id' => '197',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							31 =>
								array(
									'id' => '198',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'createPdfReport',
									'lft' => '405',
									'rght' => '406',
									'Permission' =>
										array(
											'id' => '2888',
											'aro_id' => '2',
											'aco_id' => '198',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							32 =>
								array(
									'id' => '207',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'host',
									'lft' => '423',
									'rght' => '424',
									'Permission' =>
										array(
											'id' => '2889',
											'aro_id' => '2',
											'aco_id' => '207',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							33 =>
								array(
									'id' => '209',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '427',
									'rght' => '428',
									'Permission' =>
										array(
											'id' => '2890',
											'aro_id' => '2',
											'aco_id' => '209',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							34 =>
								array(
									'id' => '208',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'service',
									'lft' => '425',
									'rght' => '426',
									'Permission' =>
										array(
											'id' => '2891',
											'aro_id' => '2',
											'aco_id' => '208',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							35 =>
								array(
									'id' => '219',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '447',
									'rght' => '448',
									'Permission' =>
										array(
											'id' => '2892',
											'aro_id' => '2',
											'aco_id' => '219',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							36 =>
								array(
									'id' => '920',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'broadcast',
									'lft' => '463',
									'rght' => '464',
									'Permission' =>
										array(
											'id' => '2893',
											'aro_id' => '2',
											'aco_id' => '920',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							37 =>
								array(
									'id' => '921',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'launchExport',
									'lft' => '465',
									'rght' => '466',
									'Permission' =>
										array(
											'id' => '2894',
											'aro_id' => '2',
											'aco_id' => '921',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							38 =>
								array(
									'id' => '922',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'verifyConfig',
									'lft' => '467',
									'rght' => '468',
									'Permission' =>
										array(
											'id' => '2895',
											'aro_id' => '2',
											'aco_id' => '922',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							39 =>
								array(
									'id' => '237',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '489',
									'rght' => '490',
									'Permission' =>
										array(
											'id' => '2896',
											'aro_id' => '2',
											'aco_id' => '237',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							40 =>
								array(
									'id' => '239',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'display',
									'lft' => '493',
									'rght' => '494',
									'Permission' =>
										array(
											'id' => '2897',
											'aro_id' => '2',
											'aco_id' => '239',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							41 =>
								array(
									'id' => '250',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '517',
									'rght' => '518',
									'Permission' =>
										array(
											'id' => '2898',
											'aro_id' => '2',
											'aco_id' => '250',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							42 =>
								array(
									'id' => '267',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '553',
									'rght' => '554',
									'Permission' =>
										array(
											'id' => '2899',
											'aro_id' => '2',
											'aco_id' => '267',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							43 =>
								array(
									'id' => '276',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '571',
									'rght' => '572',
									'Permission' =>
										array(
											'id' => '2900',
											'aro_id' => '2',
											'aco_id' => '276',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							44 =>
								array(
									'id' => '937',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '595',
									'rght' => '596',
									'Permission' =>
										array(
											'id' => '2901',
											'aro_id' => '2',
											'aco_id' => '937',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							45 =>
								array(
									'id' => '289',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '599',
									'rght' => '600',
									'Permission' =>
										array(
											'id' => '2902',
											'aro_id' => '2',
											'aco_id' => '289',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							46 =>
								array(
									'id' => '938',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '623',
									'rght' => '624',
									'Permission' =>
										array(
											'id' => '2903',
											'aro_id' => '2',
											'aco_id' => '938',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							47 =>
								array(
									'id' => '302',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '627',
									'rght' => '628',
									'Permission' =>
										array(
											'id' => '2904',
											'aro_id' => '2',
											'aco_id' => '302',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							48 =>
								array(
									'id' => '310',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'listToPdf',
									'lft' => '643',
									'rght' => '644',
									'Permission' =>
										array(
											'id' => '2905',
											'aro_id' => '2',
											'aco_id' => '310',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							49 =>
								array(
									'id' => '939',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '659',
									'rght' => '660',
									'Permission' =>
										array(
											'id' => '2906',
											'aro_id' => '2',
											'aco_id' => '939',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							50 =>
								array(
									'id' => '303',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'extended',
									'lft' => '629',
									'rght' => '630',
									'Permission' =>
										array(
											'id' => '2907',
											'aro_id' => '2',
											'aco_id' => '303',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							51 =>
								array(
									'id' => '319',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '663',
									'rght' => '664',
									'Permission' =>
										array(
											'id' => '2908',
											'aro_id' => '2',
											'aco_id' => '319',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							52 =>
								array(
									'id' => '343',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getHostByAjax',
									'lft' => '711',
									'rght' => '712',
									'Permission' =>
										array(
											'id' => '2909',
											'aro_id' => '2',
											'aco_id' => '343',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							53 =>
								array(
									'id' => '344',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'listToPdf',
									'lft' => '713',
									'rght' => '714',
									'Permission' =>
										array(
											'id' => '2910',
											'aro_id' => '2',
											'aco_id' => '344',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							54 =>
								array(
									'id' => '320',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'notMonitored',
									'lft' => '665',
									'rght' => '666',
									'Permission' =>
										array(
											'id' => '2911',
											'aro_id' => '2',
											'aco_id' => '320',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							55 =>
								array(
									'id' => '325',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'disabled',
									'lft' => '675',
									'rght' => '676',
									'Permission' =>
										array(
											'id' => '2912',
											'aro_id' => '2',
											'aco_id' => '325',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							56 =>
								array(
									'id' => '332',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'browser',
									'lft' => '689',
									'rght' => '690',
									'Permission' =>
										array(
											'id' => '2913',
											'aro_id' => '2',
											'aco_id' => '332',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							57 =>
								array(
									'id' => '333',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'longOutputByUuid',
									'lft' => '691',
									'rght' => '692',
									'Permission' =>
										array(
											'id' => '2914',
											'aro_id' => '2',
											'aco_id' => '333',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							58 =>
								array(
									'id' => '357',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '745',
									'rght' => '746',
									'Permission' =>
										array(
											'id' => '2915',
											'aro_id' => '2',
											'aco_id' => '357',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							59 =>
								array(
									'id' => '943',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '777',
									'rght' => '778',
									'Permission' =>
										array(
											'id' => '2916',
											'aro_id' => '2',
											'aco_id' => '943',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							60 =>
								array(
									'id' => '364',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'usedBy',
									'lft' => '759',
									'rght' => '760',
									'Permission' =>
										array(
											'id' => '2917',
											'aro_id' => '2',
											'aco_id' => '364',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							61 =>
								array(
									'id' => '374',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '785',
									'rght' => '786',
									'Permission' =>
										array(
											'id' => '2918',
											'aro_id' => '2',
											'aco_id' => '374',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							62 =>
								array(
									'id' => '375',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'createPdfReport',
									'lft' => '787',
									'rght' => '788',
									'Permission' =>
										array(
											'id' => '2919',
											'aro_id' => '2',
											'aco_id' => '375',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							63 =>
								array(
									'id' => '376',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'expandServices',
									'lft' => '789',
									'rght' => '790',
									'Permission' =>
										array(
											'id' => '2920',
											'aro_id' => '2',
											'aco_id' => '376',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							64 =>
								array(
									'id' => '385',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '807',
									'rght' => '808',
									'Permission' =>
										array(
											'id' => '2921',
											'aro_id' => '2',
											'aco_id' => '385',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							65 =>
								array(
									'id' => '946',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '829',
									'rght' => '830',
									'Permission' =>
										array(
											'id' => '2922',
											'aro_id' => '2',
											'aco_id' => '946',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							66 =>
								array(
									'id' => '397',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '833',
									'rght' => '834',
									'Permission' =>
										array(
											'id' => '2923',
											'aro_id' => '2',
											'aco_id' => '397',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							67 =>
								array(
									'id' => '406',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '851',
									'rght' => '852',
									'Permission' =>
										array(
											'id' => '2924',
											'aro_id' => '2',
											'aco_id' => '406',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							68 =>
								array(
									'id' => '407',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'login',
									'lft' => '853',
									'rght' => '854',
									'Permission' =>
										array(
											'id' => '2925',
											'aro_id' => '2',
											'aco_id' => '407',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							69 =>
								array(
									'id' => '408',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'onetimetoken',
									'lft' => '855',
									'rght' => '856',
									'Permission' =>
										array(
											'id' => '2926',
											'aro_id' => '2',
											'aco_id' => '408',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							70 =>
								array(
									'id' => '409',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'logout',
									'lft' => '857',
									'rght' => '858',
									'Permission' =>
										array(
											'id' => '2927',
											'aro_id' => '2',
											'aco_id' => '409',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							71 =>
								array(
									'id' => '410',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'auth_required',
									'lft' => '859',
									'rght' => '860',
									'Permission' =>
										array(
											'id' => '2928',
											'aro_id' => '2',
											'aco_id' => '410',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							72 =>
								array(
									'id' => '411',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'lock',
									'lft' => '861',
									'rght' => '862',
									'Permission' =>
										array(
											'id' => '2929',
											'aro_id' => '2',
											'aco_id' => '411',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							73 =>
								array(
									'id' => '420',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '879',
									'rght' => '880',
									'Permission' =>
										array(
											'id' => '2930',
											'aro_id' => '2',
											'aco_id' => '420',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							74 =>
								array(
									'id' => '421',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'addMacro',
									'lft' => '881',
									'rght' => '882',
									'Permission' =>
										array(
											'id' => '2931',
											'aro_id' => '2',
											'aco_id' => '421',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							75 =>
								array(
									'id' => '430',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '899',
									'rght' => '900',
									'Permission' =>
										array(
											'id' => '2932',
											'aro_id' => '2',
											'aco_id' => '430',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							76 =>
								array(
									'id' => '439',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '917',
									'rght' => '918',
									'Permission' =>
										array(
											'id' => '2933',
											'aro_id' => '2',
											'aco_id' => '439',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							77 =>
								array(
									'id' => '440',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'hostNotification',
									'lft' => '919',
									'rght' => '920',
									'Permission' =>
										array(
											'id' => '2934',
											'aro_id' => '2',
											'aco_id' => '440',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							78 =>
								array(
									'id' => '441',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceNotification',
									'lft' => '921',
									'rght' => '922',
									'Permission' =>
										array(
											'id' => '2935',
											'aro_id' => '2',
											'aco_id' => '441',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							79 =>
								array(
									'id' => '450',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '939',
									'rght' => '940',
									'Permission' =>
										array(
											'id' => '2936',
											'aro_id' => '2',
											'aco_id' => '450',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							80 =>
								array(
									'id' => '470',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '979',
									'rght' => '980',
									'Permission' =>
										array(
											'id' => '2937',
											'aro_id' => '2',
											'aco_id' => '470',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							81 =>
								array(
									'id' => '481',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1001',
									'rght' => '1002',
									'Permission' =>
										array(
											'id' => '2938',
											'aro_id' => '2',
											'aco_id' => '481',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							82 =>
								array(
									'id' => '490',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1019',
									'rght' => '1020',
									'Permission' =>
										array(
											'id' => '2939',
											'aro_id' => '2',
											'aco_id' => '490',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							83 =>
								array(
									'id' => '491',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'check',
									'lft' => '1021',
									'rght' => '1022',
									'Permission' =>
										array(
											'id' => '2940',
											'aro_id' => '2',
											'aco_id' => '491',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							84 =>
								array(
									'id' => '521',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1081',
									'rght' => '1082',
									'Permission' =>
										array(
											'id' => '2941',
											'aro_id' => '2',
											'aco_id' => '521',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							85 =>
								array(
									'id' => '530',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1099',
									'rght' => '1100',
									'Permission' =>
										array(
											'id' => '2942',
											'aro_id' => '2',
											'aco_id' => '530',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							86 =>
								array(
									'id' => '947',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1123',
									'rght' => '1124',
									'Permission' =>
										array(
											'id' => '2943',
											'aro_id' => '2',
											'aco_id' => '947',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							87 =>
								array(
									'id' => '543',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1127',
									'rght' => '1128',
									'Permission' =>
										array(
											'id' => '2944',
											'aro_id' => '2',
											'aco_id' => '543',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							88 =>
								array(
									'id' => '948',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1151',
									'rght' => '1152',
									'Permission' =>
										array(
											'id' => '2945',
											'aro_id' => '2',
											'aco_id' => '948',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							89 =>
								array(
									'id' => '556',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1155',
									'rght' => '1156',
									'Permission' =>
										array(
											'id' => '2946',
											'aro_id' => '2',
											'aco_id' => '556',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							90 =>
								array(
									'id' => '563',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'listToPdf',
									'lft' => '1169',
									'rght' => '1170',
									'Permission' =>
										array(
											'id' => '2947',
											'aro_id' => '2',
											'aco_id' => '563',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							91 =>
								array(
									'id' => '949',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1185',
									'rght' => '1186',
									'Permission' =>
										array(
											'id' => '2948',
											'aro_id' => '2',
											'aco_id' => '949',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							92 =>
								array(
									'id' => '572',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1189',
									'rght' => '1190',
									'Permission' =>
										array(
											'id' => '2949',
											'aro_id' => '2',
											'aco_id' => '572',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							93 =>
								array(
									'id' => '602',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'listToPdf',
									'lft' => '1249',
									'rght' => '1250',
									'Permission' =>
										array(
											'id' => '2950',
											'aro_id' => '2',
											'aco_id' => '602',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							94 =>
								array(
									'id' => '590',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadServices',
									'lft' => '1225',
									'rght' => '1226',
									'Permission' =>
										array(
											'id' => '2951',
											'aro_id' => '2',
											'aco_id' => '590',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							95 =>
								array(
									'id' => '950',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1267',
									'rght' => '1268',
									'Permission' =>
										array(
											'id' => '2952',
											'aro_id' => '2',
											'aco_id' => '950',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							96 =>
								array(
									'id' => '573',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'notMonitored',
									'lft' => '1191',
									'rght' => '1192',
									'Permission' =>
										array(
											'id' => '2953',
											'aro_id' => '2',
											'aco_id' => '573',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							97 =>
								array(
									'id' => '574',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'disabled',
									'lft' => '1193',
									'rght' => '1194',
									'Permission' =>
										array(
											'id' => '2954',
											'aro_id' => '2',
											'aco_id' => '574',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							98 =>
								array(
									'id' => '592',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'browser',
									'lft' => '1229',
									'rght' => '1230',
									'Permission' =>
										array(
											'id' => '2955',
											'aro_id' => '2',
											'aco_id' => '592',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							99 =>
								array(
									'id' => '593',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'servicesByHostId',
									'lft' => '1231',
									'rght' => '1232',
									'Permission' =>
										array(
											'id' => '2956',
											'aro_id' => '2',
											'aco_id' => '593',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							100 =>
								array(
									'id' => '601',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'longOutputByUuid',
									'lft' => '1247',
									'rght' => '1248',
									'Permission' =>
										array(
											'id' => '2957',
											'aro_id' => '2',
											'aco_id' => '601',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							101 =>
								array(
									'id' => '594',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceList',
									'lft' => '1233',
									'rght' => '1234',
									'Permission' =>
										array(
											'id' => '2958',
											'aro_id' => '2',
											'aco_id' => '594',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							102 =>
								array(
									'id' => '612',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1279',
									'rght' => '1280',
									'Permission' =>
										array(
											'id' => '2959',
											'aro_id' => '2',
											'aco_id' => '612',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							103 =>
								array(
									'id' => '617',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getHostsByHostgroupByAjax',
									'lft' => '1289',
									'rght' => '1290',
									'Permission' =>
										array(
											'id' => '2960',
											'aro_id' => '2',
											'aco_id' => '617',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							104 =>
								array(
									'id' => '619',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadServicetemplatesByContainerId',
									'lft' => '1293',
									'rght' => '1294',
									'Permission' =>
										array(
											'id' => '2961',
											'aro_id' => '2',
											'aco_id' => '619',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							105 =>
								array(
									'id' => '955',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1311',
									'rght' => '1312',
									'Permission' =>
										array(
											'id' => '2962',
											'aro_id' => '2',
											'aco_id' => '955',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							106 =>
								array(
									'id' => '628',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1315',
									'rght' => '1316',
									'Permission' =>
										array(
											'id' => '2963',
											'aro_id' => '2',
											'aco_id' => '628',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							107 =>
								array(
									'id' => '956',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1355',
									'rght' => '1356',
									'Permission' =>
										array(
											'id' => '2964',
											'aro_id' => '2',
											'aco_id' => '956',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							108 =>
								array(
									'id' => '649',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'service',
									'lft' => '1365',
									'rght' => '1366',
									'Permission' =>
										array(
											'id' => '2965',
											'aro_id' => '2',
											'aco_id' => '649',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							109 =>
								array(
									'id' => '650',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'host',
									'lft' => '1367',
									'rght' => '1368',
									'Permission' =>
										array(
											'id' => '2966',
											'aro_id' => '2',
											'aco_id' => '650',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							110 =>
								array(
									'id' => '659',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1385',
									'rght' => '1386',
									'Permission' =>
										array(
											'id' => '2967',
											'aro_id' => '2',
											'aco_id' => '659',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							111 =>
								array(
									'id' => '662',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1391',
									'rght' => '1392',
									'Permission' =>
										array(
											'id' => '2968',
											'aro_id' => '2',
											'aco_id' => '662',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							112 =>
								array(
									'id' => '680',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1427',
									'rght' => '1428',
									'Permission' =>
										array(
											'id' => '2969',
											'aro_id' => '2',
											'aco_id' => '680',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							113 =>
								array(
									'id' => '693',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1453',
									'rght' => '1454',
									'Permission' =>
										array(
											'id' => '2970',
											'aro_id' => '2',
											'aco_id' => '693',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							114 =>
								array(
									'id' => '704',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1475',
									'rght' => '1476',
									'Permission' =>
										array(
											'id' => '2971',
											'aro_id' => '2',
											'aco_id' => '704',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							115 =>
								array(
									'id' => '713',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1493',
									'rght' => '1494',
									'Permission' =>
										array(
											'id' => '2972',
											'aro_id' => '2',
											'aco_id' => '713',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							116 =>
								array(
									'id' => '960',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1517',
									'rght' => '1518',
									'Permission' =>
										array(
											'id' => '2973',
											'aro_id' => '2',
											'aco_id' => '960',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							117 =>
								array(
									'id' => '726',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1521',
									'rght' => '1522',
									'Permission' =>
										array(
											'id' => '2974',
											'aro_id' => '2',
											'aco_id' => '726',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							118 =>
								array(
									'id' => '961',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1549',
									'rght' => '1550',
									'Permission' =>
										array(
											'id' => '2975',
											'aro_id' => '2',
											'aco_id' => '961',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							119 =>
								array(
									'id' => '741',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1553',
									'rght' => '1554',
									'Permission' =>
										array(
											'id' => '2976',
											'aro_id' => '2',
											'aco_id' => '741',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							120 =>
								array(
									'id' => '962',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1575',
									'rght' => '1576',
									'Permission' =>
										array(
											'id' => '2977',
											'aro_id' => '2',
											'aco_id' => '962',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							121 =>
								array(
									'id' => '753',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1579',
									'rght' => '1580',
									'Permission' =>
										array(
											'id' => '2978',
											'aro_id' => '2',
											'aco_id' => '753',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							122 =>
								array(
									'id' => '963',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '1605',
									'rght' => '1606',
									'Permission' =>
										array(
											'id' => '2979',
											'aro_id' => '2',
											'aco_id' => '963',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							123 =>
								array(
									'id' => '824',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1640',
									'rght' => '1641',
									'Permission' =>
										array(
											'id' => '2980',
											'aro_id' => '2',
											'aco_id' => '824',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							124 =>
								array(
									'id' => '869',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1730',
									'rght' => '1731',
									'Permission' =>
										array(
											'id' => '2981',
											'aro_id' => '2',
											'aco_id' => '869',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							125 =>
								array(
									'id' => '879',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1752',
									'rght' => '1753',
									'Permission' =>
										array(
											'id' => '2982',
											'aro_id' => '2',
											'aco_id' => '879',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							126 =>
								array(
									'id' => '5',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '7',
									'rght' => '8',
									'Permission' =>
										array(
											'id' => '2983',
											'aro_id' => '2',
											'aco_id' => '5',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							127 =>
								array(
									'id' => '6',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '9',
									'rght' => '10',
									'Permission' =>
										array(
											'id' => '2984',
											'aro_id' => '2',
											'aco_id' => '6',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							128 =>
								array(
									'id' => '7',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '11',
									'rght' => '12',
									'Permission' =>
										array(
											'id' => '2985',
											'aro_id' => '2',
											'aco_id' => '7',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							129 =>
								array(
									'id' => '8',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '13',
									'rght' => '14',
									'Permission' =>
										array(
											'id' => '2986',
											'aro_id' => '2',
											'aco_id' => '8',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							130 =>
								array(
									'id' => '9',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '15',
									'rght' => '16',
									'Permission' =>
										array(
											'id' => '2987',
											'aro_id' => '2',
											'aco_id' => '9',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							131 =>
								array(
									'id' => '10',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '17',
									'rght' => '18',
									'Permission' =>
										array(
											'id' => '2988',
											'aro_id' => '2',
											'aco_id' => '10',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							132 =>
								array(
									'id' => '11',
									'parent_id' => '2',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '19',
									'rght' => '20',
									'Permission' =>
										array(
											'id' => '2989',
											'aro_id' => '2',
											'aco_id' => '11',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							133 =>
								array(
									'id' => '15',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '27',
									'rght' => '28',
									'Permission' =>
										array(
											'id' => '2990',
											'aro_id' => '2',
											'aco_id' => '15',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							134 =>
								array(
									'id' => '16',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '29',
									'rght' => '30',
									'Permission' =>
										array(
											'id' => '2991',
											'aro_id' => '2',
											'aco_id' => '16',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							135 =>
								array(
									'id' => '17',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '31',
									'rght' => '32',
									'Permission' =>
										array(
											'id' => '2992',
											'aro_id' => '2',
											'aco_id' => '17',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							136 =>
								array(
									'id' => '18',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '33',
									'rght' => '34',
									'Permission' =>
										array(
											'id' => '2993',
											'aro_id' => '2',
											'aco_id' => '18',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							137 =>
								array(
									'id' => '19',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '35',
									'rght' => '36',
									'Permission' =>
										array(
											'id' => '2994',
											'aro_id' => '2',
											'aco_id' => '19',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							138 =>
								array(
									'id' => '20',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '37',
									'rght' => '38',
									'Permission' =>
										array(
											'id' => '2995',
											'aro_id' => '2',
											'aco_id' => '20',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							139 =>
								array(
									'id' => '21',
									'parent_id' => '12',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '39',
									'rght' => '40',
									'Permission' =>
										array(
											'id' => '2996',
											'aro_id' => '2',
											'aco_id' => '21',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							140 =>
								array(
									'id' => '29',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '57',
									'rght' => '58',
									'Permission' =>
										array(
											'id' => '2997',
											'aro_id' => '2',
											'aco_id' => '29',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							141 =>
								array(
									'id' => '30',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '59',
									'rght' => '60',
									'Permission' =>
										array(
											'id' => '2998',
											'aro_id' => '2',
											'aco_id' => '30',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							142 =>
								array(
									'id' => '31',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '61',
									'rght' => '62',
									'Permission' =>
										array(
											'id' => '2999',
											'aro_id' => '2',
											'aco_id' => '31',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							143 =>
								array(
									'id' => '32',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '63',
									'rght' => '64',
									'Permission' =>
										array(
											'id' => '3000',
											'aro_id' => '2',
											'aco_id' => '32',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							144 =>
								array(
									'id' => '33',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '65',
									'rght' => '66',
									'Permission' =>
										array(
											'id' => '3001',
											'aro_id' => '2',
											'aco_id' => '33',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							145 =>
								array(
									'id' => '34',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '67',
									'rght' => '68',
									'Permission' =>
										array(
											'id' => '3002',
											'aro_id' => '2',
											'aco_id' => '34',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							146 =>
								array(
									'id' => '35',
									'parent_id' => '22',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '69',
									'rght' => '70',
									'Permission' =>
										array(
											'id' => '3003',
											'aro_id' => '2',
											'aco_id' => '35',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							147 =>
								array(
									'id' => '42',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '77',
									'rght' => '78',
									'Permission' =>
										array(
											'id' => '3004',
											'aro_id' => '2',
											'aco_id' => '42',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							148 =>
								array(
									'id' => '43',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '79',
									'rght' => '80',
									'Permission' =>
										array(
											'id' => '3005',
											'aro_id' => '2',
											'aco_id' => '43',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							149 =>
								array(
									'id' => '44',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '81',
									'rght' => '82',
									'Permission' =>
										array(
											'id' => '3006',
											'aro_id' => '2',
											'aco_id' => '44',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							150 =>
								array(
									'id' => '45',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '83',
									'rght' => '84',
									'Permission' =>
										array(
											'id' => '3007',
											'aro_id' => '2',
											'aco_id' => '45',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							151 =>
								array(
									'id' => '46',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '85',
									'rght' => '86',
									'Permission' =>
										array(
											'id' => '3008',
											'aro_id' => '2',
											'aco_id' => '46',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							152 =>
								array(
									'id' => '47',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '87',
									'rght' => '88',
									'Permission' =>
										array(
											'id' => '3009',
											'aro_id' => '2',
											'aco_id' => '47',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							153 =>
								array(
									'id' => '48',
									'parent_id' => '36',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '89',
									'rght' => '90',
									'Permission' =>
										array(
											'id' => '3010',
											'aro_id' => '2',
											'aco_id' => '48',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							154 =>
								array(
									'id' => '56',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '105',
									'rght' => '106',
									'Permission' =>
										array(
											'id' => '3011',
											'aro_id' => '2',
											'aco_id' => '56',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							155 =>
								array(
									'id' => '57',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '107',
									'rght' => '108',
									'Permission' =>
										array(
											'id' => '3012',
											'aro_id' => '2',
											'aco_id' => '57',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							156 =>
								array(
									'id' => '58',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '109',
									'rght' => '110',
									'Permission' =>
										array(
											'id' => '3013',
											'aro_id' => '2',
											'aco_id' => '58',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							157 =>
								array(
									'id' => '59',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '111',
									'rght' => '112',
									'Permission' =>
										array(
											'id' => '3014',
											'aro_id' => '2',
											'aco_id' => '59',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							158 =>
								array(
									'id' => '60',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '113',
									'rght' => '114',
									'Permission' =>
										array(
											'id' => '3015',
											'aro_id' => '2',
											'aco_id' => '60',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							159 =>
								array(
									'id' => '61',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '115',
									'rght' => '116',
									'Permission' =>
										array(
											'id' => '3016',
											'aro_id' => '2',
											'aco_id' => '61',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							160 =>
								array(
									'id' => '62',
									'parent_id' => '49',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '117',
									'rght' => '118',
									'Permission' =>
										array(
											'id' => '3017',
											'aro_id' => '2',
											'aco_id' => '62',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							161 =>
								array(
									'id' => '65',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '123',
									'rght' => '124',
									'Permission' =>
										array(
											'id' => '3018',
											'aro_id' => '2',
											'aco_id' => '65',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							162 =>
								array(
									'id' => '66',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '125',
									'rght' => '126',
									'Permission' =>
										array(
											'id' => '3019',
											'aro_id' => '2',
											'aco_id' => '66',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							163 =>
								array(
									'id' => '67',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '127',
									'rght' => '128',
									'Permission' =>
										array(
											'id' => '3020',
											'aro_id' => '2',
											'aco_id' => '67',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							164 =>
								array(
									'id' => '68',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '129',
									'rght' => '130',
									'Permission' =>
										array(
											'id' => '3021',
											'aro_id' => '2',
											'aco_id' => '68',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							165 =>
								array(
									'id' => '69',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '131',
									'rght' => '132',
									'Permission' =>
										array(
											'id' => '3022',
											'aro_id' => '2',
											'aco_id' => '69',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							166 =>
								array(
									'id' => '70',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '133',
									'rght' => '134',
									'Permission' =>
										array(
											'id' => '3023',
											'aro_id' => '2',
											'aco_id' => '70',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							167 =>
								array(
									'id' => '71',
									'parent_id' => '63',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '135',
									'rght' => '136',
									'Permission' =>
										array(
											'id' => '3024',
											'aro_id' => '2',
											'aco_id' => '71',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							168 =>
								array(
									'id' => '74',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '141',
									'rght' => '142',
									'Permission' =>
										array(
											'id' => '3025',
											'aro_id' => '2',
											'aco_id' => '74',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							169 =>
								array(
									'id' => '75',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '143',
									'rght' => '144',
									'Permission' =>
										array(
											'id' => '3026',
											'aro_id' => '2',
											'aco_id' => '75',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							170 =>
								array(
									'id' => '76',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '145',
									'rght' => '146',
									'Permission' =>
										array(
											'id' => '3027',
											'aro_id' => '2',
											'aco_id' => '76',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							171 =>
								array(
									'id' => '77',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '147',
									'rght' => '148',
									'Permission' =>
										array(
											'id' => '3028',
											'aro_id' => '2',
											'aco_id' => '77',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							172 =>
								array(
									'id' => '78',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '149',
									'rght' => '150',
									'Permission' =>
										array(
											'id' => '3029',
											'aro_id' => '2',
											'aco_id' => '78',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							173 =>
								array(
									'id' => '79',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '151',
									'rght' => '152',
									'Permission' =>
										array(
											'id' => '3030',
											'aro_id' => '2',
											'aco_id' => '79',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							174 =>
								array(
									'id' => '80',
									'parent_id' => '72',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '153',
									'rght' => '154',
									'Permission' =>
										array(
											'id' => '3031',
											'aro_id' => '2',
											'aco_id' => '80',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							175 =>
								array(
									'id' => '93',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '179',
									'rght' => '180',
									'Permission' =>
										array(
											'id' => '3032',
											'aro_id' => '2',
											'aco_id' => '93',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							176 =>
								array(
									'id' => '94',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '181',
									'rght' => '182',
									'Permission' =>
										array(
											'id' => '3033',
											'aro_id' => '2',
											'aco_id' => '94',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							177 =>
								array(
									'id' => '95',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '183',
									'rght' => '184',
									'Permission' =>
										array(
											'id' => '3034',
											'aro_id' => '2',
											'aco_id' => '95',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							178 =>
								array(
									'id' => '96',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '185',
									'rght' => '186',
									'Permission' =>
										array(
											'id' => '3035',
											'aro_id' => '2',
											'aco_id' => '96',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							179 =>
								array(
									'id' => '97',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '187',
									'rght' => '188',
									'Permission' =>
										array(
											'id' => '3036',
											'aro_id' => '2',
											'aco_id' => '97',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							180 =>
								array(
									'id' => '98',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '189',
									'rght' => '190',
									'Permission' =>
										array(
											'id' => '3037',
											'aro_id' => '2',
											'aco_id' => '98',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							181 =>
								array(
									'id' => '99',
									'parent_id' => '81',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '191',
									'rght' => '192',
									'Permission' =>
										array(
											'id' => '3038',
											'aro_id' => '2',
											'aco_id' => '99',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							182 =>
								array(
									'id' => '107',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '211',
									'rght' => '212',
									'Permission' =>
										array(
											'id' => '3039',
											'aro_id' => '2',
											'aco_id' => '107',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							183 =>
								array(
									'id' => '108',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '213',
									'rght' => '214',
									'Permission' =>
										array(
											'id' => '3040',
											'aro_id' => '2',
											'aco_id' => '108',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							184 =>
								array(
									'id' => '109',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '215',
									'rght' => '216',
									'Permission' =>
										array(
											'id' => '3041',
											'aro_id' => '2',
											'aco_id' => '109',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							185 =>
								array(
									'id' => '110',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '217',
									'rght' => '218',
									'Permission' =>
										array(
											'id' => '3042',
											'aro_id' => '2',
											'aco_id' => '110',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							186 =>
								array(
									'id' => '111',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '219',
									'rght' => '220',
									'Permission' =>
										array(
											'id' => '3043',
											'aro_id' => '2',
											'aco_id' => '111',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							187 =>
								array(
									'id' => '112',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '221',
									'rght' => '222',
									'Permission' =>
										array(
											'id' => '3044',
											'aro_id' => '2',
											'aco_id' => '112',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							188 =>
								array(
									'id' => '113',
									'parent_id' => '100',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '223',
									'rght' => '224',
									'Permission' =>
										array(
											'id' => '3045',
											'aro_id' => '2',
											'aco_id' => '113',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							189 =>
								array(
									'id' => '121',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '241',
									'rght' => '242',
									'Permission' =>
										array(
											'id' => '3046',
											'aro_id' => '2',
											'aco_id' => '121',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							190 =>
								array(
									'id' => '122',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '243',
									'rght' => '244',
									'Permission' =>
										array(
											'id' => '3047',
											'aro_id' => '2',
											'aco_id' => '122',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							191 =>
								array(
									'id' => '123',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '245',
									'rght' => '246',
									'Permission' =>
										array(
											'id' => '3048',
											'aro_id' => '2',
											'aco_id' => '123',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							192 =>
								array(
									'id' => '124',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '247',
									'rght' => '248',
									'Permission' =>
										array(
											'id' => '3049',
											'aro_id' => '2',
											'aco_id' => '124',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							193 =>
								array(
									'id' => '125',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '249',
									'rght' => '250',
									'Permission' =>
										array(
											'id' => '3050',
											'aro_id' => '2',
											'aco_id' => '125',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							194 =>
								array(
									'id' => '126',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '251',
									'rght' => '252',
									'Permission' =>
										array(
											'id' => '3051',
											'aro_id' => '2',
											'aco_id' => '126',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							195 =>
								array(
									'id' => '127',
									'parent_id' => '114',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '253',
									'rght' => '254',
									'Permission' =>
										array(
											'id' => '3052',
											'aro_id' => '2',
											'aco_id' => '127',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							196 =>
								array(
									'id' => '131',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'byTenant',
									'lft' => '265',
									'rght' => '266',
									'Permission' =>
										array(
											'id' => '3053',
											'aro_id' => '2',
											'aco_id' => '131',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							197 =>
								array(
									'id' => '132',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'byTenantForSelect',
									'lft' => '267',
									'rght' => '268',
									'Permission' =>
										array(
											'id' => '3054',
											'aro_id' => '2',
											'aco_id' => '132',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							198 =>
								array(
									'id' => '134',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '271',
									'rght' => '272',
									'Permission' =>
										array(
											'id' => '3055',
											'aro_id' => '2',
											'aco_id' => '134',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							199 =>
								array(
									'id' => '135',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '273',
									'rght' => '274',
									'Permission' =>
										array(
											'id' => '3056',
											'aro_id' => '2',
											'aco_id' => '135',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							200 =>
								array(
									'id' => '136',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '275',
									'rght' => '276',
									'Permission' =>
										array(
											'id' => '3057',
											'aro_id' => '2',
											'aco_id' => '136',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							201 =>
								array(
									'id' => '137',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '277',
									'rght' => '278',
									'Permission' =>
										array(
											'id' => '3058',
											'aro_id' => '2',
											'aco_id' => '137',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							202 =>
								array(
									'id' => '138',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '279',
									'rght' => '280',
									'Permission' =>
										array(
											'id' => '3059',
											'aro_id' => '2',
											'aco_id' => '138',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							203 =>
								array(
									'id' => '139',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '281',
									'rght' => '282',
									'Permission' =>
										array(
											'id' => '3060',
											'aro_id' => '2',
											'aco_id' => '139',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							204 =>
								array(
									'id' => '140',
									'parent_id' => '128',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '283',
									'rght' => '284',
									'Permission' =>
										array(
											'id' => '3061',
											'aro_id' => '2',
											'aco_id' => '140',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							205 =>
								array(
									'id' => '147',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '301',
									'rght' => '302',
									'Permission' =>
										array(
											'id' => '3062',
											'aro_id' => '2',
											'aco_id' => '147',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							206 =>
								array(
									'id' => '148',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '303',
									'rght' => '304',
									'Permission' =>
										array(
											'id' => '3063',
											'aro_id' => '2',
											'aco_id' => '148',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							207 =>
								array(
									'id' => '149',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '305',
									'rght' => '306',
									'Permission' =>
										array(
											'id' => '3064',
											'aro_id' => '2',
											'aco_id' => '149',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							208 =>
								array(
									'id' => '150',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '307',
									'rght' => '308',
									'Permission' =>
										array(
											'id' => '3065',
											'aro_id' => '2',
											'aco_id' => '150',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							209 =>
								array(
									'id' => '151',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '309',
									'rght' => '310',
									'Permission' =>
										array(
											'id' => '3066',
											'aro_id' => '2',
											'aco_id' => '151',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							210 =>
								array(
									'id' => '152',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '311',
									'rght' => '312',
									'Permission' =>
										array(
											'id' => '3067',
											'aro_id' => '2',
											'aco_id' => '152',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							211 =>
								array(
									'id' => '153',
									'parent_id' => '141',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '313',
									'rght' => '314',
									'Permission' =>
										array(
											'id' => '3068',
											'aro_id' => '2',
											'aco_id' => '153',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							212 =>
								array(
									'id' => '157',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '321',
									'rght' => '322',
									'Permission' =>
										array(
											'id' => '3069',
											'aro_id' => '2',
											'aco_id' => '157',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							213 =>
								array(
									'id' => '158',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '323',
									'rght' => '324',
									'Permission' =>
										array(
											'id' => '3070',
											'aro_id' => '2',
											'aco_id' => '158',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							214 =>
								array(
									'id' => '159',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '325',
									'rght' => '326',
									'Permission' =>
										array(
											'id' => '3071',
											'aro_id' => '2',
											'aco_id' => '159',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							215 =>
								array(
									'id' => '160',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '327',
									'rght' => '328',
									'Permission' =>
										array(
											'id' => '3072',
											'aro_id' => '2',
											'aco_id' => '160',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							216 =>
								array(
									'id' => '161',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '329',
									'rght' => '330',
									'Permission' =>
										array(
											'id' => '3073',
											'aro_id' => '2',
											'aco_id' => '161',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							217 =>
								array(
									'id' => '162',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '331',
									'rght' => '332',
									'Permission' =>
										array(
											'id' => '3074',
											'aro_id' => '2',
											'aco_id' => '162',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							218 =>
								array(
									'id' => '163',
									'parent_id' => '154',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '333',
									'rght' => '334',
									'Permission' =>
										array(
											'id' => '3075',
											'aro_id' => '2',
											'aco_id' => '163',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							219 =>
								array(
									'id' => '166',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '339',
									'rght' => '340',
									'Permission' =>
										array(
											'id' => '3076',
											'aro_id' => '2',
											'aco_id' => '166',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							220 =>
								array(
									'id' => '167',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '341',
									'rght' => '342',
									'Permission' =>
										array(
											'id' => '3077',
											'aro_id' => '2',
											'aco_id' => '167',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							221 =>
								array(
									'id' => '168',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '343',
									'rght' => '344',
									'Permission' =>
										array(
											'id' => '3078',
											'aro_id' => '2',
											'aco_id' => '168',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							222 =>
								array(
									'id' => '169',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '345',
									'rght' => '346',
									'Permission' =>
										array(
											'id' => '3079',
											'aro_id' => '2',
											'aco_id' => '169',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							223 =>
								array(
									'id' => '170',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '347',
									'rght' => '348',
									'Permission' =>
										array(
											'id' => '3080',
											'aro_id' => '2',
											'aco_id' => '170',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							224 =>
								array(
									'id' => '171',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '349',
									'rght' => '350',
									'Permission' =>
										array(
											'id' => '3081',
											'aro_id' => '2',
											'aco_id' => '171',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							225 =>
								array(
									'id' => '172',
									'parent_id' => '164',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '351',
									'rght' => '352',
									'Permission' =>
										array(
											'id' => '3082',
											'aro_id' => '2',
											'aco_id' => '172',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							226 =>
								array(
									'id' => '178',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '363',
									'rght' => '364',
									'Permission' =>
										array(
											'id' => '3083',
											'aro_id' => '2',
											'aco_id' => '178',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							227 =>
								array(
									'id' => '179',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '365',
									'rght' => '366',
									'Permission' =>
										array(
											'id' => '3084',
											'aro_id' => '2',
											'aco_id' => '179',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							228 =>
								array(
									'id' => '180',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '367',
									'rght' => '368',
									'Permission' =>
										array(
											'id' => '3085',
											'aro_id' => '2',
											'aco_id' => '180',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							229 =>
								array(
									'id' => '181',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '369',
									'rght' => '370',
									'Permission' =>
										array(
											'id' => '3086',
											'aro_id' => '2',
											'aco_id' => '181',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							230 =>
								array(
									'id' => '182',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '371',
									'rght' => '372',
									'Permission' =>
										array(
											'id' => '3087',
											'aro_id' => '2',
											'aco_id' => '182',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							231 =>
								array(
									'id' => '183',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '373',
									'rght' => '374',
									'Permission' =>
										array(
											'id' => '3088',
											'aro_id' => '2',
											'aco_id' => '183',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							232 =>
								array(
									'id' => '184',
									'parent_id' => '173',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '375',
									'rght' => '376',
									'Permission' =>
										array(
											'id' => '3089',
											'aro_id' => '2',
											'aco_id' => '184',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							233 =>
								array(
									'id' => '189',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '387',
									'rght' => '388',
									'Permission' =>
										array(
											'id' => '3090',
											'aro_id' => '2',
											'aco_id' => '189',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							234 =>
								array(
									'id' => '190',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '389',
									'rght' => '390',
									'Permission' =>
										array(
											'id' => '3091',
											'aro_id' => '2',
											'aco_id' => '190',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							235 =>
								array(
									'id' => '191',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '391',
									'rght' => '392',
									'Permission' =>
										array(
											'id' => '3092',
											'aro_id' => '2',
											'aco_id' => '191',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							236 =>
								array(
									'id' => '192',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '393',
									'rght' => '394',
									'Permission' =>
										array(
											'id' => '3093',
											'aro_id' => '2',
											'aco_id' => '192',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							237 =>
								array(
									'id' => '193',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '395',
									'rght' => '396',
									'Permission' =>
										array(
											'id' => '3094',
											'aro_id' => '2',
											'aco_id' => '193',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							238 =>
								array(
									'id' => '194',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '397',
									'rght' => '398',
									'Permission' =>
										array(
											'id' => '3095',
											'aro_id' => '2',
											'aco_id' => '194',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							239 =>
								array(
									'id' => '195',
									'parent_id' => '185',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '399',
									'rght' => '400',
									'Permission' =>
										array(
											'id' => '3096',
											'aro_id' => '2',
											'aco_id' => '195',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							240 =>
								array(
									'id' => '199',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '407',
									'rght' => '408',
									'Permission' =>
										array(
											'id' => '3097',
											'aro_id' => '2',
											'aco_id' => '199',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							241 =>
								array(
									'id' => '200',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '409',
									'rght' => '410',
									'Permission' =>
										array(
											'id' => '3098',
											'aro_id' => '2',
											'aco_id' => '200',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							242 =>
								array(
									'id' => '201',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '411',
									'rght' => '412',
									'Permission' =>
										array(
											'id' => '3099',
											'aro_id' => '2',
											'aco_id' => '201',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							243 =>
								array(
									'id' => '202',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '413',
									'rght' => '414',
									'Permission' =>
										array(
											'id' => '3100',
											'aro_id' => '2',
											'aco_id' => '202',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							244 =>
								array(
									'id' => '203',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '415',
									'rght' => '416',
									'Permission' =>
										array(
											'id' => '3101',
											'aro_id' => '2',
											'aco_id' => '203',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							245 =>
								array(
									'id' => '204',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '417',
									'rght' => '418',
									'Permission' =>
										array(
											'id' => '3102',
											'aro_id' => '2',
											'aco_id' => '204',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							246 =>
								array(
									'id' => '205',
									'parent_id' => '196',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '419',
									'rght' => '420',
									'Permission' =>
										array(
											'id' => '3103',
											'aro_id' => '2',
											'aco_id' => '205',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							247 =>
								array(
									'id' => '210',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'validateDowntimeInputFromBrowser',
									'lft' => '429',
									'rght' => '430',
									'Permission' =>
										array(
											'id' => '3104',
											'aro_id' => '2',
											'aco_id' => '210',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							248 =>
								array(
									'id' => '211',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '431',
									'rght' => '432',
									'Permission' =>
										array(
											'id' => '3105',
											'aro_id' => '2',
											'aco_id' => '211',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							249 =>
								array(
									'id' => '212',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '433',
									'rght' => '434',
									'Permission' =>
										array(
											'id' => '3106',
											'aro_id' => '2',
											'aco_id' => '212',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							250 =>
								array(
									'id' => '213',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '435',
									'rght' => '436',
									'Permission' =>
										array(
											'id' => '3107',
											'aro_id' => '2',
											'aco_id' => '213',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							251 =>
								array(
									'id' => '214',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '437',
									'rght' => '438',
									'Permission' =>
										array(
											'id' => '3108',
											'aro_id' => '2',
											'aco_id' => '214',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							252 =>
								array(
									'id' => '215',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '439',
									'rght' => '440',
									'Permission' =>
										array(
											'id' => '3109',
											'aro_id' => '2',
											'aco_id' => '215',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							253 =>
								array(
									'id' => '216',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '441',
									'rght' => '442',
									'Permission' =>
										array(
											'id' => '3110',
											'aro_id' => '2',
											'aco_id' => '216',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							254 =>
								array(
									'id' => '217',
									'parent_id' => '206',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '443',
									'rght' => '444',
									'Permission' =>
										array(
											'id' => '3111',
											'aro_id' => '2',
											'aco_id' => '217',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							255 =>
								array(
									'id' => '220',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '449',
									'rght' => '450',
									'Permission' =>
										array(
											'id' => '3112',
											'aro_id' => '2',
											'aco_id' => '220',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							256 =>
								array(
									'id' => '221',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '451',
									'rght' => '452',
									'Permission' =>
										array(
											'id' => '3113',
											'aro_id' => '2',
											'aco_id' => '221',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							257 =>
								array(
									'id' => '222',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '453',
									'rght' => '454',
									'Permission' =>
										array(
											'id' => '3114',
											'aro_id' => '2',
											'aco_id' => '222',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							258 =>
								array(
									'id' => '223',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '455',
									'rght' => '456',
									'Permission' =>
										array(
											'id' => '3115',
											'aro_id' => '2',
											'aco_id' => '223',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							259 =>
								array(
									'id' => '224',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '457',
									'rght' => '458',
									'Permission' =>
										array(
											'id' => '3116',
											'aro_id' => '2',
											'aco_id' => '224',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							260 =>
								array(
									'id' => '225',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '459',
									'rght' => '460',
									'Permission' =>
										array(
											'id' => '3117',
											'aro_id' => '2',
											'aco_id' => '225',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							261 =>
								array(
									'id' => '226',
									'parent_id' => '218',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '461',
									'rght' => '462',
									'Permission' =>
										array(
											'id' => '3118',
											'aro_id' => '2',
											'aco_id' => '226',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							262 =>
								array(
									'id' => '228',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '471',
									'rght' => '472',
									'Permission' =>
										array(
											'id' => '3119',
											'aro_id' => '2',
											'aco_id' => '228',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							263 =>
								array(
									'id' => '229',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '473',
									'rght' => '474',
									'Permission' =>
										array(
											'id' => '3120',
											'aro_id' => '2',
											'aco_id' => '229',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							264 =>
								array(
									'id' => '230',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '475',
									'rght' => '476',
									'Permission' =>
										array(
											'id' => '3121',
											'aro_id' => '2',
											'aco_id' => '230',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							265 =>
								array(
									'id' => '231',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '477',
									'rght' => '478',
									'Permission' =>
										array(
											'id' => '3122',
											'aro_id' => '2',
											'aco_id' => '231',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							266 =>
								array(
									'id' => '232',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '479',
									'rght' => '480',
									'Permission' =>
										array(
											'id' => '3123',
											'aro_id' => '2',
											'aco_id' => '232',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							267 =>
								array(
									'id' => '233',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '481',
									'rght' => '482',
									'Permission' =>
										array(
											'id' => '3124',
											'aro_id' => '2',
											'aco_id' => '233',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							268 =>
								array(
									'id' => '234',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '483',
									'rght' => '484',
									'Permission' =>
										array(
											'id' => '3125',
											'aro_id' => '2',
											'aco_id' => '234',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							269 =>
								array(
									'id' => '235',
									'parent_id' => '227',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '485',
									'rght' => '486',
									'Permission' =>
										array(
											'id' => '3126',
											'aro_id' => '2',
											'aco_id' => '235',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							270 =>
								array(
									'id' => '241',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadCollectionGraphData',
									'lft' => '497',
									'rght' => '498',
									'Permission' =>
										array(
											'id' => '3127',
											'aro_id' => '2',
											'aco_id' => '241',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							271 =>
								array(
									'id' => '242',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '499',
									'rght' => '500',
									'Permission' =>
										array(
											'id' => '3128',
											'aro_id' => '2',
											'aco_id' => '242',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							272 =>
								array(
									'id' => '243',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '501',
									'rght' => '502',
									'Permission' =>
										array(
											'id' => '3129',
											'aro_id' => '2',
											'aco_id' => '243',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							273 =>
								array(
									'id' => '244',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '503',
									'rght' => '504',
									'Permission' =>
										array(
											'id' => '3130',
											'aro_id' => '2',
											'aco_id' => '244',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							274 =>
								array(
									'id' => '245',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '505',
									'rght' => '506',
									'Permission' =>
										array(
											'id' => '3131',
											'aro_id' => '2',
											'aco_id' => '245',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							275 =>
								array(
									'id' => '246',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '507',
									'rght' => '508',
									'Permission' =>
										array(
											'id' => '3132',
											'aro_id' => '2',
											'aco_id' => '246',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							276 =>
								array(
									'id' => '247',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '509',
									'rght' => '510',
									'Permission' =>
										array(
											'id' => '3133',
											'aro_id' => '2',
											'aco_id' => '247',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							277 =>
								array(
									'id' => '248',
									'parent_id' => '236',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '511',
									'rght' => '512',
									'Permission' =>
										array(
											'id' => '3134',
											'aro_id' => '2',
											'aco_id' => '248',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							278 =>
								array(
									'id' => '255',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadServicesByHostId',
									'lft' => '527',
									'rght' => '528',
									'Permission' =>
										array(
											'id' => '3135',
											'aro_id' => '2',
											'aco_id' => '255',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							279 =>
								array(
									'id' => '256',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadPerfDataStructures',
									'lft' => '529',
									'rght' => '530',
									'Permission' =>
										array(
											'id' => '3136',
											'aro_id' => '2',
											'aco_id' => '256',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							280 =>
								array(
									'id' => '257',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'loadServiceruleFromService',
									'lft' => '531',
									'rght' => '532',
									'Permission' =>
										array(
											'id' => '3137',
											'aro_id' => '2',
											'aco_id' => '257',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							281 =>
								array(
									'id' => '258',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'fetchGraphData',
									'lft' => '533',
									'rght' => '534',
									'Permission' =>
										array(
											'id' => '3138',
											'aro_id' => '2',
											'aco_id' => '258',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							282 =>
								array(
									'id' => '259',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '535',
									'rght' => '536',
									'Permission' =>
										array(
											'id' => '3139',
											'aro_id' => '2',
											'aco_id' => '259',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							283 =>
								array(
									'id' => '260',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '537',
									'rght' => '538',
									'Permission' =>
										array(
											'id' => '3140',
											'aro_id' => '2',
											'aco_id' => '260',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							284 =>
								array(
									'id' => '261',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '539',
									'rght' => '540',
									'Permission' =>
										array(
											'id' => '3141',
											'aro_id' => '2',
											'aco_id' => '261',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							285 =>
								array(
									'id' => '262',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '541',
									'rght' => '542',
									'Permission' =>
										array(
											'id' => '3142',
											'aro_id' => '2',
											'aco_id' => '262',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							286 =>
								array(
									'id' => '263',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '543',
									'rght' => '544',
									'Permission' =>
										array(
											'id' => '3143',
											'aro_id' => '2',
											'aco_id' => '263',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							287 =>
								array(
									'id' => '264',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '545',
									'rght' => '546',
									'Permission' =>
										array(
											'id' => '3144',
											'aro_id' => '2',
											'aco_id' => '264',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							288 =>
								array(
									'id' => '265',
									'parent_id' => '249',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '547',
									'rght' => '548',
									'Permission' =>
										array(
											'id' => '3145',
											'aro_id' => '2',
											'aco_id' => '265',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							289 =>
								array(
									'id' => '268',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '555',
									'rght' => '556',
									'Permission' =>
										array(
											'id' => '3146',
											'aro_id' => '2',
											'aco_id' => '268',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							290 =>
								array(
									'id' => '269',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '557',
									'rght' => '558',
									'Permission' =>
										array(
											'id' => '3147',
											'aro_id' => '2',
											'aco_id' => '269',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							291 =>
								array(
									'id' => '270',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '559',
									'rght' => '560',
									'Permission' =>
										array(
											'id' => '3148',
											'aro_id' => '2',
											'aco_id' => '270',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							292 =>
								array(
									'id' => '271',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '561',
									'rght' => '562',
									'Permission' =>
										array(
											'id' => '3149',
											'aro_id' => '2',
											'aco_id' => '271',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							293 =>
								array(
									'id' => '272',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '563',
									'rght' => '564',
									'Permission' =>
										array(
											'id' => '3150',
											'aro_id' => '2',
											'aco_id' => '272',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							294 =>
								array(
									'id' => '273',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '565',
									'rght' => '566',
									'Permission' =>
										array(
											'id' => '3151',
											'aro_id' => '2',
											'aco_id' => '273',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							295 =>
								array(
									'id' => '274',
									'parent_id' => '266',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '567',
									'rght' => '568',
									'Permission' =>
										array(
											'id' => '3152',
											'aro_id' => '2',
											'aco_id' => '274',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							296 =>
								array(
									'id' => '281',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '581',
									'rght' => '582',
									'Permission' =>
										array(
											'id' => '3153',
											'aro_id' => '2',
											'aco_id' => '281',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							297 =>
								array(
									'id' => '282',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '583',
									'rght' => '584',
									'Permission' =>
										array(
											'id' => '3154',
											'aro_id' => '2',
											'aco_id' => '282',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							298 =>
								array(
									'id' => '283',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '585',
									'rght' => '586',
									'Permission' =>
										array(
											'id' => '3155',
											'aro_id' => '2',
											'aco_id' => '283',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							299 =>
								array(
									'id' => '284',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '587',
									'rght' => '588',
									'Permission' =>
										array(
											'id' => '3156',
											'aro_id' => '2',
											'aco_id' => '284',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							300 =>
								array(
									'id' => '285',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '589',
									'rght' => '590',
									'Permission' =>
										array(
											'id' => '3157',
											'aro_id' => '2',
											'aco_id' => '285',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							301 =>
								array(
									'id' => '286',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '591',
									'rght' => '592',
									'Permission' =>
										array(
											'id' => '3158',
											'aro_id' => '2',
											'aco_id' => '286',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							302 =>
								array(
									'id' => '287',
									'parent_id' => '275',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '593',
									'rght' => '594',
									'Permission' =>
										array(
											'id' => '3159',
											'aro_id' => '2',
											'aco_id' => '287',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							303 =>
								array(
									'id' => '294',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '609',
									'rght' => '610',
									'Permission' =>
										array(
											'id' => '3160',
											'aro_id' => '2',
											'aco_id' => '294',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							304 =>
								array(
									'id' => '295',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '611',
									'rght' => '612',
									'Permission' =>
										array(
											'id' => '3161',
											'aro_id' => '2',
											'aco_id' => '295',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							305 =>
								array(
									'id' => '296',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '613',
									'rght' => '614',
									'Permission' =>
										array(
											'id' => '3162',
											'aro_id' => '2',
											'aco_id' => '296',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							306 =>
								array(
									'id' => '297',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '615',
									'rght' => '616',
									'Permission' =>
										array(
											'id' => '3163',
											'aro_id' => '2',
											'aco_id' => '297',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							307 =>
								array(
									'id' => '298',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '617',
									'rght' => '618',
									'Permission' =>
										array(
											'id' => '3164',
											'aro_id' => '2',
											'aco_id' => '298',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							308 =>
								array(
									'id' => '299',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '619',
									'rght' => '620',
									'Permission' =>
										array(
											'id' => '3165',
											'aro_id' => '2',
											'aco_id' => '299',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							309 =>
								array(
									'id' => '300',
									'parent_id' => '288',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '621',
									'rght' => '622',
									'Permission' =>
										array(
											'id' => '3166',
											'aro_id' => '2',
											'aco_id' => '300',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							310 =>
								array(
									'id' => '311',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '645',
									'rght' => '646',
									'Permission' =>
										array(
											'id' => '3167',
											'aro_id' => '2',
											'aco_id' => '311',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							311 =>
								array(
									'id' => '312',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '647',
									'rght' => '648',
									'Permission' =>
										array(
											'id' => '3168',
											'aro_id' => '2',
											'aco_id' => '312',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							312 =>
								array(
									'id' => '313',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '649',
									'rght' => '650',
									'Permission' =>
										array(
											'id' => '3169',
											'aro_id' => '2',
											'aco_id' => '313',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							313 =>
								array(
									'id' => '314',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '651',
									'rght' => '652',
									'Permission' =>
										array(
											'id' => '3170',
											'aro_id' => '2',
											'aco_id' => '314',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							314 =>
								array(
									'id' => '315',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '653',
									'rght' => '654',
									'Permission' =>
										array(
											'id' => '3171',
											'aro_id' => '2',
											'aco_id' => '315',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							315 =>
								array(
									'id' => '316',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '655',
									'rght' => '656',
									'Permission' =>
										array(
											'id' => '3172',
											'aro_id' => '2',
											'aco_id' => '316',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							316 =>
								array(
									'id' => '317',
									'parent_id' => '301',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '657',
									'rght' => '658',
									'Permission' =>
										array(
											'id' => '3173',
											'aro_id' => '2',
											'aco_id' => '317',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							317 =>
								array(
									'id' => '349',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '723',
									'rght' => '724',
									'Permission' =>
										array(
											'id' => '3174',
											'aro_id' => '2',
											'aco_id' => '349',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							318 =>
								array(
									'id' => '350',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '725',
									'rght' => '726',
									'Permission' =>
										array(
											'id' => '3175',
											'aro_id' => '2',
											'aco_id' => '350',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							319 =>
								array(
									'id' => '351',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '727',
									'rght' => '728',
									'Permission' =>
										array(
											'id' => '3176',
											'aro_id' => '2',
											'aco_id' => '351',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							320 =>
								array(
									'id' => '352',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '729',
									'rght' => '730',
									'Permission' =>
										array(
											'id' => '3177',
											'aro_id' => '2',
											'aco_id' => '352',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							321 =>
								array(
									'id' => '353',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '731',
									'rght' => '732',
									'Permission' =>
										array(
											'id' => '3178',
											'aro_id' => '2',
											'aco_id' => '353',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							322 =>
								array(
									'id' => '354',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '733',
									'rght' => '734',
									'Permission' =>
										array(
											'id' => '3179',
											'aro_id' => '2',
											'aco_id' => '354',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							323 =>
								array(
									'id' => '355',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '735',
									'rght' => '736',
									'Permission' =>
										array(
											'id' => '3180',
											'aro_id' => '2',
											'aco_id' => '355',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							324 =>
								array(
									'id' => '940',
									'parent_id' => '318',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'view',
									'lft' => '737',
									'rght' => '738',
									'Permission' =>
										array(
											'id' => '3181',
											'aro_id' => '2',
											'aco_id' => '940',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							325 =>
								array(
									'id' => '366',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '763',
									'rght' => '764',
									'Permission' =>
										array(
											'id' => '3182',
											'aro_id' => '2',
											'aco_id' => '366',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							326 =>
								array(
									'id' => '367',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '765',
									'rght' => '766',
									'Permission' =>
										array(
											'id' => '3183',
											'aro_id' => '2',
											'aco_id' => '367',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							327 =>
								array(
									'id' => '368',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '767',
									'rght' => '768',
									'Permission' =>
										array(
											'id' => '3184',
											'aro_id' => '2',
											'aco_id' => '368',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							328 =>
								array(
									'id' => '369',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '769',
									'rght' => '770',
									'Permission' =>
										array(
											'id' => '3185',
											'aro_id' => '2',
											'aco_id' => '369',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							329 =>
								array(
									'id' => '370',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '771',
									'rght' => '772',
									'Permission' =>
										array(
											'id' => '3186',
											'aro_id' => '2',
											'aco_id' => '370',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							330 =>
								array(
									'id' => '371',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '773',
									'rght' => '774',
									'Permission' =>
										array(
											'id' => '3187',
											'aro_id' => '2',
											'aco_id' => '371',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							331 =>
								array(
									'id' => '372',
									'parent_id' => '356',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '775',
									'rght' => '776',
									'Permission' =>
										array(
											'id' => '3188',
											'aro_id' => '2',
											'aco_id' => '372',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							332 =>
								array(
									'id' => '377',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '791',
									'rght' => '792',
									'Permission' =>
										array(
											'id' => '3189',
											'aro_id' => '2',
											'aco_id' => '377',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							333 =>
								array(
									'id' => '378',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '793',
									'rght' => '794',
									'Permission' =>
										array(
											'id' => '3190',
											'aro_id' => '2',
											'aco_id' => '378',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							334 =>
								array(
									'id' => '379',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '795',
									'rght' => '796',
									'Permission' =>
										array(
											'id' => '3191',
											'aro_id' => '2',
											'aco_id' => '379',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							335 =>
								array(
									'id' => '380',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '797',
									'rght' => '798',
									'Permission' =>
										array(
											'id' => '3192',
											'aro_id' => '2',
											'aco_id' => '380',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							336 =>
								array(
									'id' => '381',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '799',
									'rght' => '800',
									'Permission' =>
										array(
											'id' => '3193',
											'aro_id' => '2',
											'aco_id' => '381',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							337 =>
								array(
									'id' => '382',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '801',
									'rght' => '802',
									'Permission' =>
										array(
											'id' => '3194',
											'aro_id' => '2',
											'aco_id' => '382',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							338 =>
								array(
									'id' => '383',
									'parent_id' => '373',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '803',
									'rght' => '804',
									'Permission' =>
										array(
											'id' => '3195',
											'aro_id' => '2',
											'aco_id' => '383',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							339 =>
								array(
									'id' => '389',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '815',
									'rght' => '816',
									'Permission' =>
										array(
											'id' => '3196',
											'aro_id' => '2',
											'aco_id' => '389',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							340 =>
								array(
									'id' => '390',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '817',
									'rght' => '818',
									'Permission' =>
										array(
											'id' => '3197',
											'aro_id' => '2',
											'aco_id' => '390',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							341 =>
								array(
									'id' => '391',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '819',
									'rght' => '820',
									'Permission' =>
										array(
											'id' => '3198',
											'aro_id' => '2',
											'aco_id' => '391',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							342 =>
								array(
									'id' => '392',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '821',
									'rght' => '822',
									'Permission' =>
										array(
											'id' => '3199',
											'aro_id' => '2',
											'aco_id' => '392',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							343 =>
								array(
									'id' => '393',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '823',
									'rght' => '824',
									'Permission' =>
										array(
											'id' => '3200',
											'aro_id' => '2',
											'aco_id' => '393',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							344 =>
								array(
									'id' => '394',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '825',
									'rght' => '826',
									'Permission' =>
										array(
											'id' => '3201',
											'aro_id' => '2',
											'aco_id' => '394',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							345 =>
								array(
									'id' => '395',
									'parent_id' => '384',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '827',
									'rght' => '828',
									'Permission' =>
										array(
											'id' => '3202',
											'aro_id' => '2',
											'aco_id' => '395',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							346 =>
								array(
									'id' => '398',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '835',
									'rght' => '836',
									'Permission' =>
										array(
											'id' => '3203',
											'aro_id' => '2',
											'aco_id' => '398',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							347 =>
								array(
									'id' => '399',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '837',
									'rght' => '838',
									'Permission' =>
										array(
											'id' => '3204',
											'aro_id' => '2',
											'aco_id' => '399',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							348 =>
								array(
									'id' => '400',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '839',
									'rght' => '840',
									'Permission' =>
										array(
											'id' => '3205',
											'aro_id' => '2',
											'aco_id' => '400',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							349 =>
								array(
									'id' => '401',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '841',
									'rght' => '842',
									'Permission' =>
										array(
											'id' => '3206',
											'aro_id' => '2',
											'aco_id' => '401',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							350 =>
								array(
									'id' => '402',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '843',
									'rght' => '844',
									'Permission' =>
										array(
											'id' => '3207',
											'aro_id' => '2',
											'aco_id' => '402',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							351 =>
								array(
									'id' => '403',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '845',
									'rght' => '846',
									'Permission' =>
										array(
											'id' => '3208',
											'aro_id' => '2',
											'aco_id' => '403',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							352 =>
								array(
									'id' => '404',
									'parent_id' => '396',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '847',
									'rght' => '848',
									'Permission' =>
										array(
											'id' => '3209',
											'aro_id' => '2',
											'aco_id' => '404',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							353 =>
								array(
									'id' => '412',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '863',
									'rght' => '864',
									'Permission' =>
										array(
											'id' => '3210',
											'aro_id' => '2',
											'aco_id' => '412',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							354 =>
								array(
									'id' => '413',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '865',
									'rght' => '866',
									'Permission' =>
										array(
											'id' => '3211',
											'aro_id' => '2',
											'aco_id' => '413',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							355 =>
								array(
									'id' => '414',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '867',
									'rght' => '868',
									'Permission' =>
										array(
											'id' => '3212',
											'aro_id' => '2',
											'aco_id' => '414',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							356 =>
								array(
									'id' => '415',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '869',
									'rght' => '870',
									'Permission' =>
										array(
											'id' => '3213',
											'aro_id' => '2',
											'aco_id' => '415',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							357 =>
								array(
									'id' => '416',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '871',
									'rght' => '872',
									'Permission' =>
										array(
											'id' => '3214',
											'aro_id' => '2',
											'aco_id' => '416',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							358 =>
								array(
									'id' => '417',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '873',
									'rght' => '874',
									'Permission' =>
										array(
											'id' => '3215',
											'aro_id' => '2',
											'aco_id' => '417',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							359 =>
								array(
									'id' => '418',
									'parent_id' => '405',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '875',
									'rght' => '876',
									'Permission' =>
										array(
											'id' => '3216',
											'aro_id' => '2',
											'aco_id' => '418',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							360 =>
								array(
									'id' => '422',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '883',
									'rght' => '884',
									'Permission' =>
										array(
											'id' => '3217',
											'aro_id' => '2',
											'aco_id' => '422',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							361 =>
								array(
									'id' => '423',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '885',
									'rght' => '886',
									'Permission' =>
										array(
											'id' => '3218',
											'aro_id' => '2',
											'aco_id' => '423',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							362 =>
								array(
									'id' => '424',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '887',
									'rght' => '888',
									'Permission' =>
										array(
											'id' => '3219',
											'aro_id' => '2',
											'aco_id' => '424',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							363 =>
								array(
									'id' => '425',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '889',
									'rght' => '890',
									'Permission' =>
										array(
											'id' => '3220',
											'aro_id' => '2',
											'aco_id' => '425',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							364 =>
								array(
									'id' => '426',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '891',
									'rght' => '892',
									'Permission' =>
										array(
											'id' => '3221',
											'aro_id' => '2',
											'aco_id' => '426',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							365 =>
								array(
									'id' => '427',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '893',
									'rght' => '894',
									'Permission' =>
										array(
											'id' => '3222',
											'aro_id' => '2',
											'aco_id' => '427',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							366 =>
								array(
									'id' => '428',
									'parent_id' => '419',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '895',
									'rght' => '896',
									'Permission' =>
										array(
											'id' => '3223',
											'aro_id' => '2',
											'aco_id' => '428',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							367 =>
								array(
									'id' => '431',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '901',
									'rght' => '902',
									'Permission' =>
										array(
											'id' => '3224',
											'aro_id' => '2',
											'aco_id' => '431',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							368 =>
								array(
									'id' => '432',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '903',
									'rght' => '904',
									'Permission' =>
										array(
											'id' => '3225',
											'aro_id' => '2',
											'aco_id' => '432',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							369 =>
								array(
									'id' => '433',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '905',
									'rght' => '906',
									'Permission' =>
										array(
											'id' => '3226',
											'aro_id' => '2',
											'aco_id' => '433',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							370 =>
								array(
									'id' => '434',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '907',
									'rght' => '908',
									'Permission' =>
										array(
											'id' => '3227',
											'aro_id' => '2',
											'aco_id' => '434',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							371 =>
								array(
									'id' => '435',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '909',
									'rght' => '910',
									'Permission' =>
										array(
											'id' => '3228',
											'aro_id' => '2',
											'aco_id' => '435',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							372 =>
								array(
									'id' => '436',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '911',
									'rght' => '912',
									'Permission' =>
										array(
											'id' => '3229',
											'aro_id' => '2',
											'aco_id' => '436',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							373 =>
								array(
									'id' => '437',
									'parent_id' => '429',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '913',
									'rght' => '914',
									'Permission' =>
										array(
											'id' => '3230',
											'aro_id' => '2',
											'aco_id' => '437',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							374 =>
								array(
									'id' => '442',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '923',
									'rght' => '924',
									'Permission' =>
										array(
											'id' => '3231',
											'aro_id' => '2',
											'aco_id' => '442',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							375 =>
								array(
									'id' => '443',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '925',
									'rght' => '926',
									'Permission' =>
										array(
											'id' => '3232',
											'aro_id' => '2',
											'aco_id' => '443',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							376 =>
								array(
									'id' => '444',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '927',
									'rght' => '928',
									'Permission' =>
										array(
											'id' => '3233',
											'aro_id' => '2',
											'aco_id' => '444',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							377 =>
								array(
									'id' => '445',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '929',
									'rght' => '930',
									'Permission' =>
										array(
											'id' => '3234',
											'aro_id' => '2',
											'aco_id' => '445',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							378 =>
								array(
									'id' => '446',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '931',
									'rght' => '932',
									'Permission' =>
										array(
											'id' => '3235',
											'aro_id' => '2',
											'aco_id' => '446',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							379 =>
								array(
									'id' => '447',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '933',
									'rght' => '934',
									'Permission' =>
										array(
											'id' => '3236',
											'aro_id' => '2',
											'aco_id' => '447',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							380 =>
								array(
									'id' => '448',
									'parent_id' => '438',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '935',
									'rght' => '936',
									'Permission' =>
										array(
											'id' => '3237',
											'aro_id' => '2',
											'aco_id' => '448',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							381 =>
								array(
									'id' => '451',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getPackets',
									'lft' => '941',
									'rght' => '942',
									'Permission' =>
										array(
											'id' => '3238',
											'aro_id' => '2',
											'aco_id' => '451',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							382 =>
								array(
									'id' => '452',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '943',
									'rght' => '944',
									'Permission' =>
										array(
											'id' => '3239',
											'aro_id' => '2',
											'aco_id' => '452',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							383 =>
								array(
									'id' => '453',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '945',
									'rght' => '946',
									'Permission' =>
										array(
											'id' => '3240',
											'aro_id' => '2',
											'aco_id' => '453',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							384 =>
								array(
									'id' => '454',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '947',
									'rght' => '948',
									'Permission' =>
										array(
											'id' => '3241',
											'aro_id' => '2',
											'aco_id' => '454',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							385 =>
								array(
									'id' => '455',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '949',
									'rght' => '950',
									'Permission' =>
										array(
											'id' => '3242',
											'aro_id' => '2',
											'aco_id' => '455',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							386 =>
								array(
									'id' => '456',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '951',
									'rght' => '952',
									'Permission' =>
										array(
											'id' => '3243',
											'aro_id' => '2',
											'aco_id' => '456',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							387 =>
								array(
									'id' => '457',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '953',
									'rght' => '954',
									'Permission' =>
										array(
											'id' => '3244',
											'aro_id' => '2',
											'aco_id' => '457',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							388 =>
								array(
									'id' => '458',
									'parent_id' => '449',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '955',
									'rght' => '956',
									'Permission' =>
										array(
											'id' => '3245',
											'aro_id' => '2',
											'aco_id' => '458',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							389 =>
								array(
									'id' => '460',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'edit',
									'lft' => '959',
									'rght' => '960',
									'Permission' =>
										array(
											'id' => '3246',
											'aro_id' => '2',
											'aco_id' => '460',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							390 =>
								array(
									'id' => '461',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'deleteImage',
									'lft' => '961',
									'rght' => '962',
									'Permission' =>
										array(
											'id' => '3247',
											'aro_id' => '2',
											'aco_id' => '461',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							391 =>
								array(
									'id' => '462',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '963',
									'rght' => '964',
									'Permission' =>
										array(
											'id' => '3248',
											'aro_id' => '2',
											'aco_id' => '462',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							392 =>
								array(
									'id' => '463',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '965',
									'rght' => '966',
									'Permission' =>
										array(
											'id' => '3249',
											'aro_id' => '2',
											'aco_id' => '463',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							393 =>
								array(
									'id' => '464',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '967',
									'rght' => '968',
									'Permission' =>
										array(
											'id' => '3250',
											'aro_id' => '2',
											'aco_id' => '464',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							394 =>
								array(
									'id' => '465',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '969',
									'rght' => '970',
									'Permission' =>
										array(
											'id' => '3251',
											'aro_id' => '2',
											'aco_id' => '465',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							395 =>
								array(
									'id' => '466',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '971',
									'rght' => '972',
									'Permission' =>
										array(
											'id' => '3252',
											'aro_id' => '2',
											'aco_id' => '466',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							396 =>
								array(
									'id' => '467',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '973',
									'rght' => '974',
									'Permission' =>
										array(
											'id' => '3253',
											'aro_id' => '2',
											'aco_id' => '467',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							397 =>
								array(
									'id' => '468',
									'parent_id' => '459',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '975',
									'rght' => '976',
									'Permission' =>
										array(
											'id' => '3254',
											'aro_id' => '2',
											'aco_id' => '468',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							398 =>
								array(
									'id' => '472',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getSettings',
									'lft' => '983',
									'rght' => '984',
									'Permission' =>
										array(
											'id' => '3255',
											'aro_id' => '2',
											'aco_id' => '472',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							399 =>
								array(
									'id' => '473',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '985',
									'rght' => '986',
									'Permission' =>
										array(
											'id' => '3256',
											'aro_id' => '2',
											'aco_id' => '473',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							400 =>
								array(
									'id' => '474',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '987',
									'rght' => '988',
									'Permission' =>
										array(
											'id' => '3257',
											'aro_id' => '2',
											'aco_id' => '474',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							401 =>
								array(
									'id' => '475',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '989',
									'rght' => '990',
									'Permission' =>
										array(
											'id' => '3258',
											'aro_id' => '2',
											'aco_id' => '475',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							402 =>
								array(
									'id' => '476',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '991',
									'rght' => '992',
									'Permission' =>
										array(
											'id' => '3259',
											'aro_id' => '2',
											'aco_id' => '476',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							403 =>
								array(
									'id' => '477',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '993',
									'rght' => '994',
									'Permission' =>
										array(
											'id' => '3260',
											'aro_id' => '2',
											'aco_id' => '477',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							404 =>
								array(
									'id' => '478',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '995',
									'rght' => '996',
									'Permission' =>
										array(
											'id' => '3261',
											'aro_id' => '2',
											'aco_id' => '478',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							405 =>
								array(
									'id' => '479',
									'parent_id' => '469',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '997',
									'rght' => '998',
									'Permission' =>
										array(
											'id' => '3262',
											'aro_id' => '2',
											'aco_id' => '479',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							406 =>
								array(
									'id' => '482',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1003',
									'rght' => '1004',
									'Permission' =>
										array(
											'id' => '3263',
											'aro_id' => '2',
											'aco_id' => '482',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							407 =>
								array(
									'id' => '483',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1005',
									'rght' => '1006',
									'Permission' =>
										array(
											'id' => '3264',
											'aro_id' => '2',
											'aco_id' => '483',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							408 =>
								array(
									'id' => '484',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1007',
									'rght' => '1008',
									'Permission' =>
										array(
											'id' => '3265',
											'aro_id' => '2',
											'aco_id' => '484',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							409 =>
								array(
									'id' => '485',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1009',
									'rght' => '1010',
									'Permission' =>
										array(
											'id' => '3266',
											'aro_id' => '2',
											'aco_id' => '485',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							410 =>
								array(
									'id' => '486',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1011',
									'rght' => '1012',
									'Permission' =>
										array(
											'id' => '3267',
											'aro_id' => '2',
											'aco_id' => '486',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							411 =>
								array(
									'id' => '487',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1013',
									'rght' => '1014',
									'Permission' =>
										array(
											'id' => '3268',
											'aro_id' => '2',
											'aco_id' => '487',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							412 =>
								array(
									'id' => '488',
									'parent_id' => '480',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1015',
									'rght' => '1016',
									'Permission' =>
										array(
											'id' => '3269',
											'aro_id' => '2',
											'aco_id' => '488',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							413 =>
								array(
									'id' => '492',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1023',
									'rght' => '1024',
									'Permission' =>
										array(
											'id' => '3270',
											'aro_id' => '2',
											'aco_id' => '492',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							414 =>
								array(
									'id' => '493',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1025',
									'rght' => '1026',
									'Permission' =>
										array(
											'id' => '3271',
											'aro_id' => '2',
											'aco_id' => '493',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							415 =>
								array(
									'id' => '494',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1027',
									'rght' => '1028',
									'Permission' =>
										array(
											'id' => '3272',
											'aro_id' => '2',
											'aco_id' => '494',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							416 =>
								array(
									'id' => '495',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1029',
									'rght' => '1030',
									'Permission' =>
										array(
											'id' => '3273',
											'aro_id' => '2',
											'aco_id' => '495',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							417 =>
								array(
									'id' => '496',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1031',
									'rght' => '1032',
									'Permission' =>
										array(
											'id' => '3274',
											'aro_id' => '2',
											'aco_id' => '496',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							418 =>
								array(
									'id' => '497',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1033',
									'rght' => '1034',
									'Permission' =>
										array(
											'id' => '3275',
											'aro_id' => '2',
											'aco_id' => '497',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							419 =>
								array(
									'id' => '498',
									'parent_id' => '489',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1035',
									'rght' => '1036',
									'Permission' =>
										array(
											'id' => '3276',
											'aro_id' => '2',
											'aco_id' => '498',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							420 =>
								array(
									'id' => '500',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1039',
									'rght' => '1040',
									'Permission' =>
										array(
											'id' => '3277',
											'aro_id' => '2',
											'aco_id' => '500',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							421 =>
								array(
									'id' => '501',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'ajax',
									'lft' => '1041',
									'rght' => '1042',
									'Permission' =>
										array(
											'id' => '3278',
											'aro_id' => '2',
											'aco_id' => '501',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							422 =>
								array(
									'id' => '502',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1043',
									'rght' => '1044',
									'Permission' =>
										array(
											'id' => '3279',
											'aro_id' => '2',
											'aco_id' => '502',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							423 =>
								array(
									'id' => '503',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1045',
									'rght' => '1046',
									'Permission' =>
										array(
											'id' => '3280',
											'aro_id' => '2',
											'aco_id' => '503',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							424 =>
								array(
									'id' => '504',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1047',
									'rght' => '1048',
									'Permission' =>
										array(
											'id' => '3281',
											'aro_id' => '2',
											'aco_id' => '504',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							425 =>
								array(
									'id' => '505',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1049',
									'rght' => '1050',
									'Permission' =>
										array(
											'id' => '3282',
											'aro_id' => '2',
											'aco_id' => '505',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							426 =>
								array(
									'id' => '506',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1051',
									'rght' => '1052',
									'Permission' =>
										array(
											'id' => '3283',
											'aro_id' => '2',
											'aco_id' => '506',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							427 =>
								array(
									'id' => '507',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1053',
									'rght' => '1054',
									'Permission' =>
										array(
											'id' => '3284',
											'aro_id' => '2',
											'aco_id' => '507',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							428 =>
								array(
									'id' => '508',
									'parent_id' => '499',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1055',
									'rght' => '1056',
									'Permission' =>
										array(
											'id' => '3285',
											'aro_id' => '2',
											'aco_id' => '508',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							429 =>
								array(
									'id' => '510',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1059',
									'rght' => '1060',
									'Permission' =>
										array(
											'id' => '3286',
											'aro_id' => '2',
											'aco_id' => '510',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							430 =>
								array(
									'id' => '511',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'hostMacro',
									'lft' => '1061',
									'rght' => '1062',
									'Permission' =>
										array(
											'id' => '3287',
											'aro_id' => '2',
											'aco_id' => '511',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							431 =>
								array(
									'id' => '512',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceMacro',
									'lft' => '1063',
									'rght' => '1064',
									'Permission' =>
										array(
											'id' => '3288',
											'aro_id' => '2',
											'aco_id' => '512',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							432 =>
								array(
									'id' => '513',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1065',
									'rght' => '1066',
									'Permission' =>
										array(
											'id' => '3289',
											'aro_id' => '2',
											'aco_id' => '513',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							433 =>
								array(
									'id' => '514',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1067',
									'rght' => '1068',
									'Permission' =>
										array(
											'id' => '3290',
											'aro_id' => '2',
											'aco_id' => '514',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							434 =>
								array(
									'id' => '515',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1069',
									'rght' => '1070',
									'Permission' =>
										array(
											'id' => '3291',
											'aro_id' => '2',
											'aco_id' => '515',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							435 =>
								array(
									'id' => '516',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1071',
									'rght' => '1072',
									'Permission' =>
										array(
											'id' => '3292',
											'aro_id' => '2',
											'aco_id' => '516',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							436 =>
								array(
									'id' => '517',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1073',
									'rght' => '1074',
									'Permission' =>
										array(
											'id' => '3293',
											'aro_id' => '2',
											'aco_id' => '517',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							437 =>
								array(
									'id' => '518',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1075',
									'rght' => '1076',
									'Permission' =>
										array(
											'id' => '3294',
											'aro_id' => '2',
											'aco_id' => '518',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							438 =>
								array(
									'id' => '519',
									'parent_id' => '509',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1077',
									'rght' => '1078',
									'Permission' =>
										array(
											'id' => '3295',
											'aro_id' => '2',
											'aco_id' => '519',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							439 =>
								array(
									'id' => '522',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1083',
									'rght' => '1084',
									'Permission' =>
										array(
											'id' => '3296',
											'aro_id' => '2',
											'aco_id' => '522',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							440 =>
								array(
									'id' => '523',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1085',
									'rght' => '1086',
									'Permission' =>
										array(
											'id' => '3297',
											'aro_id' => '2',
											'aco_id' => '523',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							441 =>
								array(
									'id' => '524',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1087',
									'rght' => '1088',
									'Permission' =>
										array(
											'id' => '3298',
											'aro_id' => '2',
											'aco_id' => '524',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							442 =>
								array(
									'id' => '525',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1089',
									'rght' => '1090',
									'Permission' =>
										array(
											'id' => '3299',
											'aro_id' => '2',
											'aco_id' => '525',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							443 =>
								array(
									'id' => '526',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1091',
									'rght' => '1092',
									'Permission' =>
										array(
											'id' => '3300',
											'aro_id' => '2',
											'aco_id' => '526',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							444 =>
								array(
									'id' => '527',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1093',
									'rght' => '1094',
									'Permission' =>
										array(
											'id' => '3301',
											'aro_id' => '2',
											'aco_id' => '527',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							445 =>
								array(
									'id' => '528',
									'parent_id' => '520',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1095',
									'rght' => '1096',
									'Permission' =>
										array(
											'id' => '3302',
											'aro_id' => '2',
											'aco_id' => '528',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							446 =>
								array(
									'id' => '535',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1109',
									'rght' => '1110',
									'Permission' =>
										array(
											'id' => '3303',
											'aro_id' => '2',
											'aco_id' => '535',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							447 =>
								array(
									'id' => '536',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1111',
									'rght' => '1112',
									'Permission' =>
										array(
											'id' => '3304',
											'aro_id' => '2',
											'aco_id' => '536',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							448 =>
								array(
									'id' => '537',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1113',
									'rght' => '1114',
									'Permission' =>
										array(
											'id' => '3305',
											'aro_id' => '2',
											'aco_id' => '537',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							449 =>
								array(
									'id' => '538',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1115',
									'rght' => '1116',
									'Permission' =>
										array(
											'id' => '3306',
											'aro_id' => '2',
											'aco_id' => '538',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							450 =>
								array(
									'id' => '539',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1117',
									'rght' => '1118',
									'Permission' =>
										array(
											'id' => '3307',
											'aro_id' => '2',
											'aco_id' => '539',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							451 =>
								array(
									'id' => '540',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1119',
									'rght' => '1120',
									'Permission' =>
										array(
											'id' => '3308',
											'aro_id' => '2',
											'aco_id' => '540',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							452 =>
								array(
									'id' => '541',
									'parent_id' => '529',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1121',
									'rght' => '1122',
									'Permission' =>
										array(
											'id' => '3309',
											'aro_id' => '2',
											'aco_id' => '541',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							453 =>
								array(
									'id' => '548',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1137',
									'rght' => '1138',
									'Permission' =>
										array(
											'id' => '3310',
											'aro_id' => '2',
											'aco_id' => '548',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							454 =>
								array(
									'id' => '549',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1139',
									'rght' => '1140',
									'Permission' =>
										array(
											'id' => '3311',
											'aro_id' => '2',
											'aco_id' => '549',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							455 =>
								array(
									'id' => '550',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1141',
									'rght' => '1142',
									'Permission' =>
										array(
											'id' => '3312',
											'aro_id' => '2',
											'aco_id' => '550',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							456 =>
								array(
									'id' => '551',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1143',
									'rght' => '1144',
									'Permission' =>
										array(
											'id' => '3313',
											'aro_id' => '2',
											'aco_id' => '551',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							457 =>
								array(
									'id' => '552',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1145',
									'rght' => '1146',
									'Permission' =>
										array(
											'id' => '3314',
											'aro_id' => '2',
											'aco_id' => '552',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							458 =>
								array(
									'id' => '553',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1147',
									'rght' => '1148',
									'Permission' =>
										array(
											'id' => '3315',
											'aro_id' => '2',
											'aco_id' => '553',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							459 =>
								array(
									'id' => '554',
									'parent_id' => '542',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1149',
									'rght' => '1150',
									'Permission' =>
										array(
											'id' => '3316',
											'aro_id' => '2',
											'aco_id' => '554',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							460 =>
								array(
									'id' => '564',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1171',
									'rght' => '1172',
									'Permission' =>
										array(
											'id' => '3317',
											'aro_id' => '2',
											'aco_id' => '564',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							461 =>
								array(
									'id' => '565',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1173',
									'rght' => '1174',
									'Permission' =>
										array(
											'id' => '3318',
											'aro_id' => '2',
											'aco_id' => '565',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							462 =>
								array(
									'id' => '566',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1175',
									'rght' => '1176',
									'Permission' =>
										array(
											'id' => '3319',
											'aro_id' => '2',
											'aco_id' => '566',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							463 =>
								array(
									'id' => '567',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1177',
									'rght' => '1178',
									'Permission' =>
										array(
											'id' => '3320',
											'aro_id' => '2',
											'aco_id' => '567',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							464 =>
								array(
									'id' => '568',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1179',
									'rght' => '1180',
									'Permission' =>
										array(
											'id' => '3321',
											'aro_id' => '2',
											'aco_id' => '568',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							465 =>
								array(
									'id' => '569',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1181',
									'rght' => '1182',
									'Permission' =>
										array(
											'id' => '3322',
											'aro_id' => '2',
											'aco_id' => '569',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							466 =>
								array(
									'id' => '570',
									'parent_id' => '555',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1183',
									'rght' => '1184',
									'Permission' =>
										array(
											'id' => '3323',
											'aro_id' => '2',
											'aco_id' => '570',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							467 =>
								array(
									'id' => '595',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'grapherSwitch',
									'lft' => '1235',
									'rght' => '1236',
									'Permission' =>
										array(
											'id' => '3324',
											'aro_id' => '2',
											'aco_id' => '595',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							468 =>
								array(
									'id' => '596',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'grapher',
									'lft' => '1237',
									'rght' => '1238',
									'Permission' =>
										array(
											'id' => '3325',
											'aro_id' => '2',
											'aco_id' => '596',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							469 =>
								array(
									'id' => '597',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'grapherTemplate',
									'lft' => '1239',
									'rght' => '1240',
									'Permission' =>
										array(
											'id' => '3326',
											'aro_id' => '2',
											'aco_id' => '597',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							470 =>
								array(
									'id' => '598',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'grapherZoom',
									'lft' => '1241',
									'rght' => '1242',
									'Permission' =>
										array(
											'id' => '3327',
											'aro_id' => '2',
											'aco_id' => '598',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							471 =>
								array(
									'id' => '599',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'grapherZoomTemplate',
									'lft' => '1243',
									'rght' => '1244',
									'Permission' =>
										array(
											'id' => '3328',
											'aro_id' => '2',
											'aco_id' => '599',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							472 =>
								array(
									'id' => '600',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'createGrapherErrorPng',
									'lft' => '1245',
									'rght' => '1246',
									'Permission' =>
										array(
											'id' => '3329',
											'aro_id' => '2',
											'aco_id' => '600',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							473 =>
								array(
									'id' => '604',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1253',
									'rght' => '1254',
									'Permission' =>
										array(
											'id' => '3330',
											'aro_id' => '2',
											'aco_id' => '604',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							474 =>
								array(
									'id' => '605',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1255',
									'rght' => '1256',
									'Permission' =>
										array(
											'id' => '3331',
											'aro_id' => '2',
											'aco_id' => '605',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							475 =>
								array(
									'id' => '606',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1257',
									'rght' => '1258',
									'Permission' =>
										array(
											'id' => '3332',
											'aro_id' => '2',
											'aco_id' => '606',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							476 =>
								array(
									'id' => '607',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1259',
									'rght' => '1260',
									'Permission' =>
										array(
											'id' => '3333',
											'aro_id' => '2',
											'aco_id' => '607',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							477 =>
								array(
									'id' => '608',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1261',
									'rght' => '1262',
									'Permission' =>
										array(
											'id' => '3334',
											'aro_id' => '2',
											'aco_id' => '608',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							478 =>
								array(
									'id' => '609',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1263',
									'rght' => '1264',
									'Permission' =>
										array(
											'id' => '3335',
											'aro_id' => '2',
											'aco_id' => '609',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							479 =>
								array(
									'id' => '610',
									'parent_id' => '571',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1265',
									'rght' => '1266',
									'Permission' =>
										array(
											'id' => '3336',
											'aro_id' => '2',
											'aco_id' => '610',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							480 =>
								array(
									'id' => '620',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1295',
									'rght' => '1296',
									'Permission' =>
										array(
											'id' => '3337',
											'aro_id' => '2',
											'aco_id' => '620',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							481 =>
								array(
									'id' => '621',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1297',
									'rght' => '1298',
									'Permission' =>
										array(
											'id' => '3338',
											'aro_id' => '2',
											'aco_id' => '621',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							482 =>
								array(
									'id' => '622',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1299',
									'rght' => '1300',
									'Permission' =>
										array(
											'id' => '3339',
											'aro_id' => '2',
											'aco_id' => '622',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							483 =>
								array(
									'id' => '623',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1301',
									'rght' => '1302',
									'Permission' =>
										array(
											'id' => '3340',
											'aro_id' => '2',
											'aco_id' => '623',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							484 =>
								array(
									'id' => '624',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1303',
									'rght' => '1304',
									'Permission' =>
										array(
											'id' => '3341',
											'aro_id' => '2',
											'aco_id' => '624',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							485 =>
								array(
									'id' => '625',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1305',
									'rght' => '1306',
									'Permission' =>
										array(
											'id' => '3342',
											'aro_id' => '2',
											'aco_id' => '625',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							486 =>
								array(
									'id' => '626',
									'parent_id' => '611',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1307',
									'rght' => '1308',
									'Permission' =>
										array(
											'id' => '3343',
											'aro_id' => '2',
											'aco_id' => '626',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							487 =>
								array(
									'id' => '641',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1341',
									'rght' => '1342',
									'Permission' =>
										array(
											'id' => '3344',
											'aro_id' => '2',
											'aco_id' => '641',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							488 =>
								array(
									'id' => '642',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1343',
									'rght' => '1344',
									'Permission' =>
										array(
											'id' => '3345',
											'aro_id' => '2',
											'aco_id' => '642',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							489 =>
								array(
									'id' => '643',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1345',
									'rght' => '1346',
									'Permission' =>
										array(
											'id' => '3346',
											'aro_id' => '2',
											'aco_id' => '643',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							490 =>
								array(
									'id' => '644',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1347',
									'rght' => '1348',
									'Permission' =>
										array(
											'id' => '3347',
											'aro_id' => '2',
											'aco_id' => '644',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							491 =>
								array(
									'id' => '645',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1349',
									'rght' => '1350',
									'Permission' =>
										array(
											'id' => '3348',
											'aro_id' => '2',
											'aco_id' => '645',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							492 =>
								array(
									'id' => '646',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1351',
									'rght' => '1352',
									'Permission' =>
										array(
											'id' => '3349',
											'aro_id' => '2',
											'aco_id' => '646',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							493 =>
								array(
									'id' => '647',
									'parent_id' => '627',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1353',
									'rght' => '1354',
									'Permission' =>
										array(
											'id' => '3350',
											'aro_id' => '2',
											'aco_id' => '647',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							494 =>
								array(
									'id' => '651',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1369',
									'rght' => '1370',
									'Permission' =>
										array(
											'id' => '3351',
											'aro_id' => '2',
											'aco_id' => '651',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							495 =>
								array(
									'id' => '652',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1371',
									'rght' => '1372',
									'Permission' =>
										array(
											'id' => '3352',
											'aro_id' => '2',
											'aco_id' => '652',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							496 =>
								array(
									'id' => '653',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1373',
									'rght' => '1374',
									'Permission' =>
										array(
											'id' => '3353',
											'aro_id' => '2',
											'aco_id' => '653',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							497 =>
								array(
									'id' => '654',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1375',
									'rght' => '1376',
									'Permission' =>
										array(
											'id' => '3354',
											'aro_id' => '2',
											'aco_id' => '654',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							498 =>
								array(
									'id' => '655',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1377',
									'rght' => '1378',
									'Permission' =>
										array(
											'id' => '3355',
											'aro_id' => '2',
											'aco_id' => '655',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							499 =>
								array(
									'id' => '656',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1379',
									'rght' => '1380',
									'Permission' =>
										array(
											'id' => '3356',
											'aro_id' => '2',
											'aco_id' => '656',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							500 =>
								array(
									'id' => '657',
									'parent_id' => '648',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1381',
									'rght' => '1382',
									'Permission' =>
										array(
											'id' => '3357',
											'aro_id' => '2',
											'aco_id' => '657',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							501 =>
								array(
									'id' => '660',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getHostsAndConnections',
									'lft' => '1387',
									'rght' => '1388',
									'Permission' =>
										array(
											'id' => '3358',
											'aro_id' => '2',
											'aco_id' => '660',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							502 =>
								array(
									'id' => '661',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'clickHostStatus',
									'lft' => '1389',
									'rght' => '1390',
									'Permission' =>
										array(
											'id' => '3359',
											'aro_id' => '2',
											'aco_id' => '661',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							503 =>
								array(
									'id' => '663',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1393',
									'rght' => '1394',
									'Permission' =>
										array(
											'id' => '3360',
											'aro_id' => '2',
											'aco_id' => '663',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							504 =>
								array(
									'id' => '664',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1395',
									'rght' => '1396',
									'Permission' =>
										array(
											'id' => '3361',
											'aro_id' => '2',
											'aco_id' => '664',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							505 =>
								array(
									'id' => '665',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1397',
									'rght' => '1398',
									'Permission' =>
										array(
											'id' => '3362',
											'aro_id' => '2',
											'aco_id' => '665',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							506 =>
								array(
									'id' => '666',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1399',
									'rght' => '1400',
									'Permission' =>
										array(
											'id' => '3363',
											'aro_id' => '2',
											'aco_id' => '666',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							507 =>
								array(
									'id' => '667',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1401',
									'rght' => '1402',
									'Permission' =>
										array(
											'id' => '3364',
											'aro_id' => '2',
											'aco_id' => '667',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							508 =>
								array(
									'id' => '668',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1403',
									'rght' => '1404',
									'Permission' =>
										array(
											'id' => '3365',
											'aro_id' => '2',
											'aco_id' => '668',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							509 =>
								array(
									'id' => '669',
									'parent_id' => '658',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1405',
									'rght' => '1406',
									'Permission' =>
										array(
											'id' => '3366',
											'aro_id' => '2',
											'aco_id' => '669',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							510 =>
								array(
									'id' => '672',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1411',
									'rght' => '1412',
									'Permission' =>
										array(
											'id' => '3367',
											'aro_id' => '2',
											'aco_id' => '672',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							511 =>
								array(
									'id' => '673',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1413',
									'rght' => '1414',
									'Permission' =>
										array(
											'id' => '3368',
											'aro_id' => '2',
											'aco_id' => '673',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							512 =>
								array(
									'id' => '674',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1415',
									'rght' => '1416',
									'Permission' =>
										array(
											'id' => '3369',
											'aro_id' => '2',
											'aco_id' => '674',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							513 =>
								array(
									'id' => '675',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1417',
									'rght' => '1418',
									'Permission' =>
										array(
											'id' => '3370',
											'aro_id' => '2',
											'aco_id' => '675',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							514 =>
								array(
									'id' => '676',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1419',
									'rght' => '1420',
									'Permission' =>
										array(
											'id' => '3371',
											'aro_id' => '2',
											'aco_id' => '676',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							515 =>
								array(
									'id' => '677',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1421',
									'rght' => '1422',
									'Permission' =>
										array(
											'id' => '3372',
											'aro_id' => '2',
											'aco_id' => '677',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							516 =>
								array(
									'id' => '678',
									'parent_id' => '670',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1423',
									'rght' => '1424',
									'Permission' =>
										array(
											'id' => '3373',
											'aro_id' => '2',
											'aco_id' => '678',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							517 =>
								array(
									'id' => '685',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1437',
									'rght' => '1438',
									'Permission' =>
										array(
											'id' => '3374',
											'aro_id' => '2',
											'aco_id' => '685',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							518 =>
								array(
									'id' => '686',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1439',
									'rght' => '1440',
									'Permission' =>
										array(
											'id' => '3375',
											'aro_id' => '2',
											'aco_id' => '686',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							519 =>
								array(
									'id' => '687',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1441',
									'rght' => '1442',
									'Permission' =>
										array(
											'id' => '3376',
											'aro_id' => '2',
											'aco_id' => '687',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							520 =>
								array(
									'id' => '688',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1443',
									'rght' => '1444',
									'Permission' =>
										array(
											'id' => '3377',
											'aro_id' => '2',
											'aco_id' => '688',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							521 =>
								array(
									'id' => '689',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1445',
									'rght' => '1446',
									'Permission' =>
										array(
											'id' => '3378',
											'aro_id' => '2',
											'aco_id' => '689',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							522 =>
								array(
									'id' => '690',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1447',
									'rght' => '1448',
									'Permission' =>
										array(
											'id' => '3379',
											'aro_id' => '2',
											'aco_id' => '690',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							523 =>
								array(
									'id' => '691',
									'parent_id' => '679',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1449',
									'rght' => '1450',
									'Permission' =>
										array(
											'id' => '3380',
											'aro_id' => '2',
											'aco_id' => '691',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							524 =>
								array(
									'id' => '696',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1459',
									'rght' => '1460',
									'Permission' =>
										array(
											'id' => '3381',
											'aro_id' => '2',
											'aco_id' => '696',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							525 =>
								array(
									'id' => '697',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1461',
									'rght' => '1462',
									'Permission' =>
										array(
											'id' => '3382',
											'aro_id' => '2',
											'aco_id' => '697',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							526 =>
								array(
									'id' => '698',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1463',
									'rght' => '1464',
									'Permission' =>
										array(
											'id' => '3383',
											'aro_id' => '2',
											'aco_id' => '698',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							527 =>
								array(
									'id' => '699',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1465',
									'rght' => '1466',
									'Permission' =>
										array(
											'id' => '3384',
											'aro_id' => '2',
											'aco_id' => '699',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							528 =>
								array(
									'id' => '700',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1467',
									'rght' => '1468',
									'Permission' =>
										array(
											'id' => '3385',
											'aro_id' => '2',
											'aco_id' => '700',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							529 =>
								array(
									'id' => '701',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1469',
									'rght' => '1470',
									'Permission' =>
										array(
											'id' => '3386',
											'aro_id' => '2',
											'aco_id' => '701',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							530 =>
								array(
									'id' => '702',
									'parent_id' => '692',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1471',
									'rght' => '1472',
									'Permission' =>
										array(
											'id' => '3387',
											'aro_id' => '2',
											'aco_id' => '702',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							531 =>
								array(
									'id' => '705',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1477',
									'rght' => '1478',
									'Permission' =>
										array(
											'id' => '3388',
											'aro_id' => '2',
											'aco_id' => '705',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							532 =>
								array(
									'id' => '706',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1479',
									'rght' => '1480',
									'Permission' =>
										array(
											'id' => '3389',
											'aro_id' => '2',
											'aco_id' => '706',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							533 =>
								array(
									'id' => '707',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1481',
									'rght' => '1482',
									'Permission' =>
										array(
											'id' => '3390',
											'aro_id' => '2',
											'aco_id' => '707',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							534 =>
								array(
									'id' => '708',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1483',
									'rght' => '1484',
									'Permission' =>
										array(
											'id' => '3391',
											'aro_id' => '2',
											'aco_id' => '708',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							535 =>
								array(
									'id' => '709',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1485',
									'rght' => '1486',
									'Permission' =>
										array(
											'id' => '3392',
											'aro_id' => '2',
											'aco_id' => '709',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							536 =>
								array(
									'id' => '710',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1487',
									'rght' => '1488',
									'Permission' =>
										array(
											'id' => '3393',
											'aro_id' => '2',
											'aco_id' => '710',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							537 =>
								array(
									'id' => '711',
									'parent_id' => '703',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1489',
									'rght' => '1490',
									'Permission' =>
										array(
											'id' => '3394',
											'aro_id' => '2',
											'aco_id' => '711',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							538 =>
								array(
									'id' => '718',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1503',
									'rght' => '1504',
									'Permission' =>
										array(
											'id' => '3395',
											'aro_id' => '2',
											'aco_id' => '718',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							539 =>
								array(
									'id' => '719',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1505',
									'rght' => '1506',
									'Permission' =>
										array(
											'id' => '3396',
											'aro_id' => '2',
											'aco_id' => '719',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							540 =>
								array(
									'id' => '720',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1507',
									'rght' => '1508',
									'Permission' =>
										array(
											'id' => '3397',
											'aro_id' => '2',
											'aco_id' => '720',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							541 =>
								array(
									'id' => '721',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1509',
									'rght' => '1510',
									'Permission' =>
										array(
											'id' => '3398',
											'aro_id' => '2',
											'aco_id' => '721',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							542 =>
								array(
									'id' => '722',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1511',
									'rght' => '1512',
									'Permission' =>
										array(
											'id' => '3399',
											'aro_id' => '2',
											'aco_id' => '722',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							543 =>
								array(
									'id' => '723',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1513',
									'rght' => '1514',
									'Permission' =>
										array(
											'id' => '3400',
											'aro_id' => '2',
											'aco_id' => '723',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							544 =>
								array(
									'id' => '724',
									'parent_id' => '712',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1515',
									'rght' => '1516',
									'Permission' =>
										array(
											'id' => '3401',
											'aro_id' => '2',
											'aco_id' => '724',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							545 =>
								array(
									'id' => '733',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1535',
									'rght' => '1536',
									'Permission' =>
										array(
											'id' => '3402',
											'aro_id' => '2',
											'aco_id' => '733',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							546 =>
								array(
									'id' => '734',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1537',
									'rght' => '1538',
									'Permission' =>
										array(
											'id' => '3403',
											'aro_id' => '2',
											'aco_id' => '734',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							547 =>
								array(
									'id' => '735',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1539',
									'rght' => '1540',
									'Permission' =>
										array(
											'id' => '3404',
											'aro_id' => '2',
											'aco_id' => '735',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							548 =>
								array(
									'id' => '736',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1541',
									'rght' => '1542',
									'Permission' =>
										array(
											'id' => '3405',
											'aro_id' => '2',
											'aco_id' => '736',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							549 =>
								array(
									'id' => '737',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1543',
									'rght' => '1544',
									'Permission' =>
										array(
											'id' => '3406',
											'aro_id' => '2',
											'aco_id' => '737',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							550 =>
								array(
									'id' => '738',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1545',
									'rght' => '1546',
									'Permission' =>
										array(
											'id' => '3407',
											'aro_id' => '2',
											'aco_id' => '738',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							551 =>
								array(
									'id' => '739',
									'parent_id' => '725',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1547',
									'rght' => '1548',
									'Permission' =>
										array(
											'id' => '3408',
											'aro_id' => '2',
											'aco_id' => '739',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							552 =>
								array(
									'id' => '745',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1561',
									'rght' => '1562',
									'Permission' =>
										array(
											'id' => '3409',
											'aro_id' => '2',
											'aco_id' => '745',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							553 =>
								array(
									'id' => '746',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1563',
									'rght' => '1564',
									'Permission' =>
										array(
											'id' => '3410',
											'aro_id' => '2',
											'aco_id' => '746',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							554 =>
								array(
									'id' => '747',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1565',
									'rght' => '1566',
									'Permission' =>
										array(
											'id' => '3411',
											'aro_id' => '2',
											'aco_id' => '747',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							555 =>
								array(
									'id' => '748',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1567',
									'rght' => '1568',
									'Permission' =>
										array(
											'id' => '3412',
											'aro_id' => '2',
											'aco_id' => '748',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							556 =>
								array(
									'id' => '749',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1569',
									'rght' => '1570',
									'Permission' =>
										array(
											'id' => '3413',
											'aro_id' => '2',
											'aco_id' => '749',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							557 =>
								array(
									'id' => '750',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1571',
									'rght' => '1572',
									'Permission' =>
										array(
											'id' => '3414',
											'aro_id' => '2',
											'aco_id' => '750',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							558 =>
								array(
									'id' => '751',
									'parent_id' => '740',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1573',
									'rght' => '1574',
									'Permission' =>
										array(
											'id' => '3415',
											'aro_id' => '2',
											'aco_id' => '751',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							559 =>
								array(
									'id' => '759',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1591',
									'rght' => '1592',
									'Permission' =>
										array(
											'id' => '3416',
											'aro_id' => '2',
											'aco_id' => '759',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							560 =>
								array(
									'id' => '760',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1593',
									'rght' => '1594',
									'Permission' =>
										array(
											'id' => '3417',
											'aro_id' => '2',
											'aco_id' => '760',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							561 =>
								array(
									'id' => '761',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1595',
									'rght' => '1596',
									'Permission' =>
										array(
											'id' => '3418',
											'aro_id' => '2',
											'aco_id' => '761',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							562 =>
								array(
									'id' => '762',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1597',
									'rght' => '1598',
									'Permission' =>
										array(
											'id' => '3419',
											'aro_id' => '2',
											'aco_id' => '762',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							563 =>
								array(
									'id' => '763',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1599',
									'rght' => '1600',
									'Permission' =>
										array(
											'id' => '3420',
											'aro_id' => '2',
											'aco_id' => '763',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							564 =>
								array(
									'id' => '764',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1601',
									'rght' => '1602',
									'Permission' =>
										array(
											'id' => '3421',
											'aro_id' => '2',
											'aco_id' => '764',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							565 =>
								array(
									'id' => '765',
									'parent_id' => '752',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1603',
									'rght' => '1604',
									'Permission' =>
										array(
											'id' => '3422',
											'aro_id' => '2',
											'aco_id' => '765',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							566 =>
								array(
									'id' => '825',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1642',
									'rght' => '1643',
									'Permission' =>
										array(
											'id' => '3423',
											'aro_id' => '2',
											'aco_id' => '825',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							567 =>
								array(
									'id' => '826',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1644',
									'rght' => '1645',
									'Permission' =>
										array(
											'id' => '3424',
											'aro_id' => '2',
											'aco_id' => '826',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							568 =>
								array(
									'id' => '827',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1646',
									'rght' => '1647',
									'Permission' =>
										array(
											'id' => '3425',
											'aro_id' => '2',
											'aco_id' => '827',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							569 =>
								array(
									'id' => '828',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1648',
									'rght' => '1649',
									'Permission' =>
										array(
											'id' => '3426',
											'aro_id' => '2',
											'aco_id' => '828',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							570 =>
								array(
									'id' => '829',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1650',
									'rght' => '1651',
									'Permission' =>
										array(
											'id' => '3427',
											'aro_id' => '2',
											'aco_id' => '829',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							571 =>
								array(
									'id' => '830',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1652',
									'rght' => '1653',
									'Permission' =>
										array(
											'id' => '3428',
											'aro_id' => '2',
											'aco_id' => '830',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							572 =>
								array(
									'id' => '831',
									'parent_id' => '823',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1654',
									'rght' => '1655',
									'Permission' =>
										array(
											'id' => '3429',
											'aro_id' => '2',
											'aco_id' => '831',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							573 =>
								array(
									'id' => '871',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1734',
									'rght' => '1735',
									'Permission' =>
										array(
											'id' => '3430',
											'aro_id' => '2',
											'aco_id' => '871',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							574 =>
								array(
									'id' => '872',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1736',
									'rght' => '1737',
									'Permission' =>
										array(
											'id' => '3431',
											'aro_id' => '2',
											'aco_id' => '872',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							575 =>
								array(
									'id' => '873',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1738',
									'rght' => '1739',
									'Permission' =>
										array(
											'id' => '3432',
											'aro_id' => '2',
											'aco_id' => '873',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							576 =>
								array(
									'id' => '874',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1740',
									'rght' => '1741',
									'Permission' =>
										array(
											'id' => '3433',
											'aro_id' => '2',
											'aco_id' => '874',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							577 =>
								array(
									'id' => '875',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1742',
									'rght' => '1743',
									'Permission' =>
										array(
											'id' => '3434',
											'aro_id' => '2',
											'aco_id' => '875',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							578 =>
								array(
									'id' => '876',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1744',
									'rght' => '1745',
									'Permission' =>
										array(
											'id' => '3435',
											'aro_id' => '2',
											'aco_id' => '876',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							579 =>
								array(
									'id' => '877',
									'parent_id' => '868',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1746',
									'rght' => '1747',
									'Permission' =>
										array(
											'id' => '3436',
											'aro_id' => '2',
											'aco_id' => '877',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							580 =>
								array(
									'id' => '880',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1754',
									'rght' => '1755',
									'Permission' =>
										array(
											'id' => '3437',
											'aro_id' => '2',
											'aco_id' => '880',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							581 =>
								array(
									'id' => '881',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1756',
									'rght' => '1757',
									'Permission' =>
										array(
											'id' => '3438',
											'aro_id' => '2',
											'aco_id' => '881',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							582 =>
								array(
									'id' => '882',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1758',
									'rght' => '1759',
									'Permission' =>
										array(
											'id' => '3439',
											'aro_id' => '2',
											'aco_id' => '882',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							583 =>
								array(
									'id' => '883',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1760',
									'rght' => '1761',
									'Permission' =>
										array(
											'id' => '3440',
											'aro_id' => '2',
											'aco_id' => '883',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							584 =>
								array(
									'id' => '884',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1762',
									'rght' => '1763',
									'Permission' =>
										array(
											'id' => '3441',
											'aro_id' => '2',
											'aco_id' => '884',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							585 =>
								array(
									'id' => '885',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1764',
									'rght' => '1765',
									'Permission' =>
										array(
											'id' => '3442',
											'aro_id' => '2',
											'aco_id' => '885',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							586 =>
								array(
									'id' => '886',
									'parent_id' => '878',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1766',
									'rght' => '1767',
									'Permission' =>
										array(
											'id' => '3443',
											'aro_id' => '2',
											'aco_id' => '886',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							587 =>
								array(
									'id' => '890',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'index',
									'lft' => '1771',
									'rght' => '1772',
									'Permission' =>
										array(
											'id' => '3444',
											'aro_id' => '2',
											'aco_id' => '890',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							588 =>
								array(
									'id' => '891',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'next',
									'lft' => '1773',
									'rght' => '1774',
									'Permission' =>
										array(
											'id' => '3445',
											'aro_id' => '2',
											'aco_id' => '891',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							589 =>
								array(
									'id' => '892',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'add',
									'lft' => '1775',
									'rght' => '1776',
									'Permission' =>
										array(
											'id' => '3446',
											'aro_id' => '2',
											'aco_id' => '892',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							590 =>
								array(
									'id' => '893',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'createTab',
									'lft' => '1777',
									'rght' => '1778',
									'Permission' =>
										array(
											'id' => '3447',
											'aro_id' => '2',
											'aco_id' => '893',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							591 =>
								array(
									'id' => '894',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'createTabFromSharing',
									'lft' => '1779',
									'rght' => '1780',
									'Permission' =>
										array(
											'id' => '3448',
											'aro_id' => '2',
											'aco_id' => '894',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							592 =>
								array(
									'id' => '895',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'updateSharedTab',
									'lft' => '1781',
									'rght' => '1782',
									'Permission' =>
										array(
											'id' => '3449',
											'aro_id' => '2',
											'aco_id' => '895',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							593 =>
								array(
									'id' => '896',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'disableUpdate',
									'lft' => '1783',
									'rght' => '1784',
									'Permission' =>
										array(
											'id' => '3450',
											'aro_id' => '2',
											'aco_id' => '896',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							594 =>
								array(
									'id' => '897',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'renameTab',
									'lft' => '1785',
									'rght' => '1786',
									'Permission' =>
										array(
											'id' => '3451',
											'aro_id' => '2',
											'aco_id' => '897',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							595 =>
								array(
									'id' => '898',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'deleteTab',
									'lft' => '1787',
									'rght' => '1788',
									'Permission' =>
										array(
											'id' => '3452',
											'aro_id' => '2',
											'aco_id' => '898',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							596 =>
								array(
									'id' => '899',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'restoreDefault',
									'lft' => '1789',
									'rght' => '1790',
									'Permission' =>
										array(
											'id' => '3453',
											'aro_id' => '2',
											'aco_id' => '899',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							597 =>
								array(
									'id' => '900',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'updateTitle',
									'lft' => '1791',
									'rght' => '1792',
									'Permission' =>
										array(
											'id' => '3454',
											'aro_id' => '2',
											'aco_id' => '900',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							598 =>
								array(
									'id' => '901',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'updateColor',
									'lft' => '1793',
									'rght' => '1794',
									'Permission' =>
										array(
											'id' => '3455',
											'aro_id' => '2',
											'aco_id' => '901',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							599 =>
								array(
									'id' => '902',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'updatePosition',
									'lft' => '1795',
									'rght' => '1796',
									'Permission' =>
										array(
											'id' => '3456',
											'aro_id' => '2',
											'aco_id' => '902',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							600 =>
								array(
									'id' => '903',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'deleteWidget',
									'lft' => '1797',
									'rght' => '1798',
									'Permission' =>
										array(
											'id' => '3457',
											'aro_id' => '2',
											'aco_id' => '903',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							601 =>
								array(
									'id' => '904',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'updateTabPosition',
									'lft' => '1799',
									'rght' => '1800',
									'Permission' =>
										array(
											'id' => '3458',
											'aro_id' => '2',
											'aco_id' => '904',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							602 =>
								array(
									'id' => '905',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'saveTabRotationInterval',
									'lft' => '1801',
									'rght' => '1802',
									'Permission' =>
										array(
											'id' => '3459',
											'aro_id' => '2',
											'aco_id' => '905',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							603 =>
								array(
									'id' => '906',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'startSharing',
									'lft' => '1803',
									'rght' => '1804',
									'Permission' =>
										array(
											'id' => '3460',
											'aro_id' => '2',
											'aco_id' => '906',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							604 =>
								array(
									'id' => '907',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'stopSharing',
									'lft' => '1805',
									'rght' => '1806',
									'Permission' =>
										array(
											'id' => '3461',
											'aro_id' => '2',
											'aco_id' => '907',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							605 =>
								array(
									'id' => '908',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'refresh',
									'lft' => '1807',
									'rght' => '1808',
									'Permission' =>
										array(
											'id' => '3462',
											'aro_id' => '2',
											'aco_id' => '908',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							606 =>
								array(
									'id' => '909',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'saveStatuslistSettings',
									'lft' => '1809',
									'rght' => '1810',
									'Permission' =>
										array(
											'id' => '3463',
											'aro_id' => '2',
											'aco_id' => '909',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							607 =>
								array(
									'id' => '910',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'saveTrafficLightService',
									'lft' => '1811',
									'rght' => '1812',
									'Permission' =>
										array(
											'id' => '3464',
											'aro_id' => '2',
											'aco_id' => '910',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							608 =>
								array(
									'id' => '911',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getTachoPerfdata',
									'lft' => '1813',
									'rght' => '1814',
									'Permission' =>
										array(
											'id' => '3465',
											'aro_id' => '2',
											'aco_id' => '911',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							609 =>
								array(
									'id' => '912',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'saveTachoConfig',
									'lft' => '1815',
									'rght' => '1816',
									'Permission' =>
										array(
											'id' => '3466',
											'aro_id' => '2',
											'aco_id' => '912',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							610 =>
								array(
									'id' => '913',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'isAuthorized',
									'lft' => '1817',
									'rght' => '1818',
									'Permission' =>
										array(
											'id' => '3467',
											'aro_id' => '2',
											'aco_id' => '913',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							611 =>
								array(
									'id' => '914',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'flashBack',
									'lft' => '1819',
									'rght' => '1820',
									'Permission' =>
										array(
											'id' => '3468',
											'aro_id' => '2',
											'aco_id' => '914',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							612 =>
								array(
									'id' => '915',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'setFlash',
									'lft' => '1821',
									'rght' => '1822',
									'Permission' =>
										array(
											'id' => '3469',
											'aro_id' => '2',
											'aco_id' => '915',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							613 =>
								array(
									'id' => '916',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'serviceResponse',
									'lft' => '1823',
									'rght' => '1824',
									'Permission' =>
										array(
											'id' => '3470',
											'aro_id' => '2',
											'aco_id' => '916',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							614 =>
								array(
									'id' => '917',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'getNamedParameter',
									'lft' => '1825',
									'rght' => '1826',
									'Permission' =>
										array(
											'id' => '3471',
											'aro_id' => '2',
											'aco_id' => '917',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							615 =>
								array(
									'id' => '918',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'allowedByContainerId',
									'lft' => '1827',
									'rght' => '1828',
									'Permission' =>
										array(
											'id' => '3472',
											'aro_id' => '2',
											'aco_id' => '918',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
							616 =>
								array(
									'id' => '919',
									'parent_id' => '889',
									'model' => NULL,
									'foreign_key' => NULL,
									'alias' => 'render403',
									'lft' => '1829',
									'rght' => '1830',
									'Permission' =>
										array(
											'id' => '3473',
											'aro_id' => '2',
											'aco_id' => '919',
											'_create' => '1',
											'_read' => '1',
											'_update' => '1',
											'_delete' => '1',
										),
								),
						),
				),
		);
		return $data;
	}

}