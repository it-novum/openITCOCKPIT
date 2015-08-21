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


App::uses('Widget', 'Admin.Lib');

class Welcome extends WidgetBase{
	protected $iconname = 'comment';
	protected $bodyClasses = 'text-left';
	protected $bodyStyles = 'min-height: 180px;';
	protected $viewName = 'Dashboard/widget_welcome';

	public function compileTemplateData(){
		$stateArrayHost = [];
		for($i = 0; $i < 3; $i++){
			$stateArrayHost[$i] = $this->Hoststatus->find('count', [
				'conditions' => [
					'current_state' => $i,
				]
			]);
		}

		$stateArrayService = [];
		for($i = 0; $i < 4; $i++){
			$stateArrayService[$i] = $this->Servicestatus->find('count', [
				'conditions' => [
					'current_state' => $i,
				]
			]);
		}

		$templateVariables = [
			'state_array_host' => $stateArrayHost,
			'state_array_service' => $stateArrayService,
		];

		$this->setTemplateVariables($templateVariables);
	}
}
