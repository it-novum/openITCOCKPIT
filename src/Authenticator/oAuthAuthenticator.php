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
use Authentication\Authenticator\CookieAuthenticator;
use Authentication\Authenticator\PersistenceInterface;
use Authentication\Authenticator\Result;
use Authentication\Authenticator\ResultInterface;
use Cake\Http\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class oAuthAuthenticator extends CookieAuthenticator implements PersistenceInterface {

    /**
     * Authenticate a user based on the request information.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request to get authentication information from.
     * @return \Authentication\Authenticator\ResultInterface Returns a result object.
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface {
        $query = $request->getQueryParams();

        $code = $query['code'] ?? null;
        $state = $query['state'] ?? null;

        if (!empty($code) && !empty($state)) {
            // We have a code and a state in $_GET.
            // Lets see if this is an oAuth token...

            /** @var Session $session */
            $session = $request->getAttribute('session');

            // string null so that $state from $_GET and the state out of $_SESSION will not be equal if both are not set !!
            $oauth2state = $session->read('oauth2state', 'null');

            //Check state from $_SESSION against current state from $_GET to mitigate CSRF attack
            if ($oauth2state === $state) {
                //Valid state - remove it from session
                $session->delete('oauth2state');

                $data = [
                    'code' => $code
                ];
                $user = $this->_identifier->identify($data);
                if (empty($user) || $user === null) {
                    return new Result(null, Result::FAILURE_IDENTITY_NOT_FOUND, $this->_identifier->getErrors());
                }

                $session->write('is_oauth_login', true);
                return new Result($user, Result::SUCCESS);

            }

        }


        return new Result(null, Result::FAILURE_IDENTITY_NOT_FOUND, $this->_identifier->getErrors());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array|\ArrayAccess $identity
     * @return array
     */
    public function persistIdentity(ServerRequestInterface $request, ResponseInterface $response, $identity): array {


        $field = $this->getConfig('rememberMeField');
        /** @var Session $session */
        $session = $request->getAttribute('session');

        if ($session->read($field, false) !== true || !$this->_checkUrl($request)) {
            // No auth cookie to store
            return [
                'request'  => $request,
                'response' => $response,
            ];
        }

        //Send auth cookie to client
        $value = $this->_createToken($identity);
        $cookie = $this->_createCookie($value);
        $session->delete('remember_me');

        return [
            'request'  => $request,
            'response' => $response->withAddedHeader('Set-Cookie', $cookie->toHeaderValue()),
        ];
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return array
     */
    public function clearIdentity(ServerRequestInterface $request, ResponseInterface $response): array {
        $cookie = $this->_createCookie('')->withExpired();

        return [
            'request'  => $request,
            'response' => $response->withAddedHeader('Set-Cookie', $cookie->toHeaderValue()),
        ];
    }
}
