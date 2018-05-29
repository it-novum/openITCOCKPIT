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

App::uses('BaseAuthenticate', 'Controller/Component/Auth');

class ApiAuthenticate extends BaseAuthenticate {

    /**
     * Authenticate a user based on the request information.
     *
     * @param CakeRequest $request Request to get authentication information from.
     * @param CakeResponse $response A response object that can have headers added.
     * @return mixed Either false on failure, or an array of user data on success.
     */
    public function authenticate(CakeRequest $request, CakeResponse $response) {
        return $this->getUser($request);
    }

    /**
     * Handle unauthenticated access attempt.
     *
     * @param CakeRequest $request A request object.
     * @param CakeResponse $response A response object.
     * @return mixed Either true to indicate the unauthenticated request has been
     *  dealt with and no more action is required by AuthComponent or void (default).
     */
    public function unauthenticated(CakeRequest $request, CakeResponse $response) {
        return parent::unauthenticated($request, $response);
    }


    /**
     * Get a user based on information in the request. Primarily used by stateless authentication
     * systems like basic and digest auth.
     *
     * @param CakeRequest $request Request object.
     * @return mixed Either false or an array of user information
     */
    public function getUser(CakeRequest $request) {
        $headerContent = $request->header('Authorization');
        if ($headerContent && strpos($headerContent, 'X-OITC-API') === 0) {
            $apiKey = trim(str_replace('X-OITC-API', '', $headerContent));
            return $this->_findUser($apiKey, null);
        }

        $queryContent = $request->query('apikey');
        if ($queryContent && strlen($queryContent) > 10) {
            return $this->_findUser($queryContent, null);
        }

        return false;
    }

    /**
     * Find a user record using an apikey
     *
     * @param string $username The apikey to check
     * @param string $password Not used (Required because of interface/extends)
     * @return bool|array Either false on failure, or an array of user data.
     */
    public function _findUser($username, $password = null) {
        $result = ClassRegistry::init('Apikey')->find('first', [
            'conditions' => [
                'Apikey.apikey' => $username,
                'User.status'   => 1, //Active users only
            ],
            'contain'    => [
                'User' => [
                    'Usergroup'
                ]
            ]
        ]);

        if (empty($result) || empty($result['Apikey'])) {
            return false;
        }

        return $result['User'];
    }

}
