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


/***************************************************************
 * Auth Proxy for Grafana
 *
 *
 */

use Authentication\Identifier\IdentifierCollection;
use Authentication\Identifier\IdentifierInterface;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Log\Log;

$debug = false;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

require_once __DIR__ . DS . '..' . DS . 'vendor' . DS . 'autoload.php';
require_once __DIR__ . DS . '..' . DS . 'config' . DS . 'bootstrap.php';

if ($debug === true) {
    $file = fopen('debug.log', 'w+');
    fwrite($file, var_export($_SERVER, true));
    fwrite($file, var_export($_COOKIE, true));
    fwrite($file, var_export($_REQUEST, true));
}


try {
    if (isset($_COOKIE['CookieAuth'])) {
        //Validate if this is a valid openITCOCKPIT login cookie...
        $token = json_decode(rawurldecode($_COOKIE['CookieAuth']), true);

        if ($token === null || count($token) !== 2) {
            Log::error('GrafanaModule Auth: Cookie token is invalid.');
        }

        [$username, $tokenHash] = $token;

        $IdentifierCollection = new IdentifierCollection([
            'Authentication.Password' => [
                'fields' => [
                    IdentifierInterface::CREDENTIAL_USERNAME => 'email',
                    IdentifierInterface::CREDENTIAL_PASSWORD => 'password'
                ]
            ]
        ]);

        $identity = $IdentifierCollection->identify(['username' => $username]);

        if (empty($identity)) {
            throw new UnauthorizedException();
        }

        $plainToken = $identity['email'] . $identity['password'];

        $DefaultPasswordHasher = new DefaultPasswordHasher();
        if ($DefaultPasswordHasher->check($plainToken, $tokenHash)) {
            //Login success
            if ($debug) {
                fwrite($file, 'Login Ok !!');
            }
            header("HTTP/1.0 200 Ok");
            return;
        }

        throw new UnauthorizedException();
    }
} catch (\Exception $e) {

}

if ($debug) {
    fwrite($file, 'Unauthorized !!');
}
header("HTTP/1.0 401 Unauthorized");

if ($debug) {
    fclose($file);
}
