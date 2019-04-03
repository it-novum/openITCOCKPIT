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

use itnovum\openITCOCKPIT\Core\Security\CSRF;

App::uses('SecurityComponent', 'Controller/Component');

/**
 * Class AppSecurityComponent
 * @property SessionComponent $Session
 * @property CookieComponent $Cookie
 */
class CSRFComponent extends Component {

    public $components = [
        'Session',
        'Cookie'
    ];

    public function initialize(Controller $controller) {
        //Resolve RVID: 1-445b21
        $CSRF = new CSRF($this->Session, $this->Cookie);

        $hasData = ($controller->request->data || $controller->request->is(['put', 'post', 'delete', 'patch']));
        if ($hasData) {
            $CSRF->validateCsrfToken($controller);

            //Store tokens to SESSION and generate a new token for current request
            $CSRF->storeTokens();
        }

        //Only generate a new token for a GET request if the user don't have any tokens
        //Avid generating tokens for all background tasks
        $CSRF->generateTokenIfNonExists();
    }
}

