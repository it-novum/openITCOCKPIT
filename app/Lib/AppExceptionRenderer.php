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

App::uses('ExceptionRenderer', 'Error');
class AppExceptionRenderer extends ExceptionRenderer {
/**
 * Handling special cases
 *
 * @return void
 */	
	public function render() {
		if($this->controller->request->is('ajax')) {
			$this->method = 'renderAjaxError';
		}
		if(isset($this->controller->request->params['widget']) && $this->controller->request->params['widget']) {
			$this->method = 'renderAjaxError';
		}
		parent::render();
	}

/**
 * Render a ajax request exception
 *
 * @param Exception $error
 * @return void
 */
	public function renderAjaxError($error) {
		$message = $error->getMessage();
		if (!Configure::read('debug') && $error instanceof CakeException) {
			$message = __d('cake', 'Not Found');
		}
		
		
		$response = array(
			'code' => Types::CODE_EXCEPTION,
			'data' => array(
				'message' => $error->getMessage()
			)
		);
		$this->controller->response = new ServiceResponse($response);

		$this->controller->response->statusCode($error->getCode());
		$this->controller->response->send();
	}
}