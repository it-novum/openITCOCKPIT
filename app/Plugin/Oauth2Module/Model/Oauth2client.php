<?php
// Copyright (C) <2017>  <it-novum GmbH>
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

require_once __DIR__.'/../Lib/Provider/PingIdentity.php';

/**
 * Class Oauth2client
 */
class Oauth2client extends Oauth2ModuleAppModel
{
    /**
     * @var array
     */
    public $validate = [
        'client_id'       => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'client_secret'   => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
//        'redirect_uri'    => [
//            'notBlank' => [
//                'rule'     => 'notBlank',
//                'message'  => 'This field cannot be left blank.',
//                'required' => true,
//            ],
//        ],
        'url_authorize'   => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'url_accessToken' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'provider'        => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
//        'button_text'     => [
//            'notBlank' => [
//                'rule'     => 'notBlank',
//                'message'  => 'This field cannot be left blank.',
//                'required' => true,
//            ],
//        ],
    ];

    /**
     * @return array
     */
    public function getConnects()
    {
        $allIds = $this->find('all', ['conditions' => ['Oauth2client.active' => 1]]);

        foreach ($allIds as $login) {
            if ($login['Oauth2client']['provider'] == 'PingIdentity') {
                $clientId     = $login['Oauth2client']['client_id'];
                $clientSecret = $login['Oauth2client']['client_secret'];
                $redirectUri  = $login['Oauth2client']['redirect_uri'];
                $provider = new PingIdentity(compact('clientId', 'clientSecret', 'redirectUri'));
                if (empty($_GET['code'])) {
                    // If we don't have an authorization code then get one
                    $authUrl = $provider->getAuthorizationUrl();
                    $_SESSION['oauth2state'] = $provider->getState();
                    header('Location: '.$authUrl);
                } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
                    // State is invalid, possible CSRF attack in progress
                    unset($_SESSION['oauth2state']);
                    exit('Invalid state');
                } else {
                    // Try to get an access token (using the authorization code grant)
                    $token = $provider->getAccessToken('authorization_code', [
                        'code' => $_GET['code'],
                    ]);
                    $_SESSION['token'] = serialize($token);
                    // Optional: Now you have a token you can look up a users profile data
                    header('Location: '.$login['Oauth2client']['redirect_uri']);
                }

            } else {
                return [];
            }
        }

        return [];

    }

    public function getReturnUrl($id){
        return Router::url(['plugin' => 'oauth2_module', 'controller' => 'oauth2', 'action' => 'checkAndLogin', 'id' => $id], true );
    }
}
