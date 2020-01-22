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

namespace App\Authenticator;


use Authentication\Authenticator\AbstractAuthenticator;
use Authentication\Authenticator\Result;
use Authentication\Authenticator\ResultInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApikeyAuthenticator extends AbstractAuthenticator {

    /**
     * Authenticate a user based on the request information.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request to get authentication information from.
     * @return \Authentication\Authenticator\ResultInterface Returns a result object.
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface {
        $data = ['apikey' => null];

        if (!empty($request->getHeaderLine('Authorization'))) {
            $authHeaderKey = $this->getConfig('apikeyPrefix');
            $data['apikey'] = trim(substr($request->getHeaderLine($this->getConfig('header')), strlen($authHeaderKey)));
        }
        if (isset($request->getQueryParams()[$this->getConfig('queryParam')])) {
            $data['apikey'] = trim($request->getQueryParams()[$this->getConfig('queryParam')]);
        }

        //file_put_contents('/tmp/apikeyauth.txt', json_encode($this->getConfig('cookie'), true));

        $user = $this->_identifier->identify($data);
        if (empty($user) || $user === null) {
            return new Result(null, Result::FAILURE_IDENTITY_NOT_FOUND, $this->_identifier->getErrors());
        }
        //$request->withoutHeader('Set-Cookie');

        return new Result($user, Result::SUCCESS);
    }

}
