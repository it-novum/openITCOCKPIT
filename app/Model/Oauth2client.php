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

//require_once __DIR__.'/../Lib/Provider/PingIdentity.php';
App::import('Vendor', 'Oauth2/PingIdentity');
App::uses('CakeSession', 'Model/Datasource');

/**
 * Class Oauth2client
 */
class Oauth2client {

    /**
     * @return array
     */
    public function connectToSSO() {
        $mySettings = ClassRegistry::init('Systemsetting');
        $systemsettings = $mySettings->findAsArraySection('FRONTEND');
        $clientId = isset($systemsettings['FRONTEND']['FRONTEND.SSO.CLIENT_ID']) ? trim($systemsettings['FRONTEND']['FRONTEND.SSO.CLIENT_ID']) : '';
        $clientSecret = isset($systemsettings['FRONTEND']['FRONTEND.SSO.CLIENT_SECRET']) ? trim($systemsettings['FRONTEND']['FRONTEND.SSO.CLIENT_SECRET']) : '';
        $authEndpoint = isset($systemsettings['FRONTEND']['FRONTEND.SSO.AUTH_ENDPOINT']) ? trim($systemsettings['FRONTEND']['FRONTEND.SSO.AUTH_ENDPOINT']) : '';
        $tokenEndpoint = isset($systemsettings['FRONTEND']['FRONTEND.SSO.TOKEN_ENDPOINT']) ? trim($systemsettings['FRONTEND']['FRONTEND.SSO.TOKEN_ENDPOINT']) : '';
        $userEndpoint = isset($systemsettings['FRONTEND']['FRONTEND.SSO.USER_ENDPOINT']) ? trim($systemsettings['FRONTEND']['FRONTEND.SSO.USER_ENDPOINT']) : '';

        if (empty($clientId) || empty($clientSecret) || empty($authEndpoint) || empty($tokenEndpoint) || empty($userEndpoint)) {
            return ['success' => false, 'message' => 'Configuration error: One or more SSO values are empty. The values can be now inserted only directly in db.'];
        }


        $redirectUri = $this->getReturnUrl();
        $provider = new PingIdentity(compact('clientId', 'clientSecret', 'redirectUri', 'authEndpoint', 'tokenEndpoint', 'userEndpoint'));

        if (empty($_GET['code'])) {
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            CakeSession::write('oauth2state', $provider->getState());
            return ['redirect' => $authUrl];
        } else if (empty($_GET['state']) || ($_GET['state'] !== CakeSession::read('oauth2state'))) {
            // State is invalid, possible CSRF attack in progress
            CakeSession::write('oauth2state', '');
            return ['success' => false, 'message' => 'Connection to SSO server was not secure. State is not provided.'];
        } else {
            // Try to get an access token (using the authorization code grant)
            $tokenArr = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code'],
            ]);
            if (!$tokenArr[0]) {
                return ['success' => false, 'message' => 'Can not get token. ' . (ENVIRONMENT === 'production' ? '' : $tokenArr[1])];
            }
            $userDataArr = $provider->getResourceOwner($tokenArr[1]);
            if (!$userDataArr[0]) {
                return ['success' => false, 'message' => 'Can not get user data: ' . (ENVIRONMENT === 'production' ? '' : $userDataArr[1])];
            }
            $userArray = $userDataArr[1]->toArray();
            return ['success' => true, 'email' => $userArray['mail']];
        }
    }

    public function getReturnUrl() {
        return Router::url(['controller' => 'login', 'action' => 'login'], true);
    }

    public function getPostErrorMessage($SsoLogOff) {
        $preText = '<br />Please, ';
        if (!empty($SsoLogOff)) {
            $preText = '<br />Please perform <a href="' . $SsoLogOff . '" target="_blank">log off from SSO Server</a>. And then ';
        }
        return $preText . '<a href="' . Router::url(['controller' => 'login', 'action' => 'login']) . '">retry to login</a>.';
    }
}
