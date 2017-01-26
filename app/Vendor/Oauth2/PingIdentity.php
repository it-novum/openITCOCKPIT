<?php
/**
 * // Copyright (C) <2016>  <it-novum GmbH>
 * //
 * // This file is dual licensed
 * //
 * // 1.
 * //	This program is free software: you can redistribute it and/or modify
 * //	it under the terms of the GNU General Public License as published by
 * //	the Free Software Foundation, version 3 of the License.
 * //
 * //	This program is distributed in the hope that it will be useful,
 * //	but WITHOUT ANY WARRANTY; without even the implied warranty of
 * //	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * //	GNU General Public License for more details.
 * //
 * //	You should have received a copy of the GNU General Public License
 * //	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * //
 *
 * // 2.
 * //	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
 * //	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
 * //	License agreement and license key will be shipped with the order
 * //	confirmation.
 */

require_once __DIR__.'/autoload.php';
require_once __DIR__.'/PingIdentityUser.php';

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
/**
 * Class PingIdentity
 */
class PingIdentity extends AbstractProvider
{
    use BearerAuthorizationTrait;

    const ACCESS_TOKEN_RESOURCE_OWNER_ID = 'id';

    /**
     * @var string If set, this will be sent to google as the "access_type" parameter.
     * @link https://developers.google.com/accounts/docs/OAuth2WebServer#offline
     */
    protected $accessType;

    /**
     * @var string If set, this will be sent to google as the "hd" parameter.
     * @link https://developers.google.com/accounts/docs/OAuth2Login#hd-param
     */
    protected $hostedDomain;

    /**
     * @var array Default fields to be requested from the user profile.
     * @link https://developers.google.com/+/web/api/rest/latest/people
     */
    protected $defaultUserFields = [
        'id',
        'name(familyName,givenName)',
        'displayName',
        'emails/value',
        'image/url',
    ];

    /**
     * @var array Additional fields to be requested from the user profile.
     *            If set, these values will be included with the defaults.
     */
    protected $userFields = [];

    /**
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->authEndpoint;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->tokenEndpoint;
    }

    /**
     * @param AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->userEndpoint;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function getAuthorizationParameters(array $options)
    {
        $params = array_merge(
            parent::getAuthorizationParameters($options),
            array_filter([
                'hd'          => $this->hostedDomain,
                'access_type' => $this->accessType,
                // if the user is logged in with more than one account ask which one to use for the login!
                'authuser'    => '-1'
            ])
        );
        return $params;
    }

    /**
     * @return array
     */
    protected function getDefaultScopes()
    {
        return [
            'openid',
            'profile',
        ];
    }

    /**
     * @return string
     */
    protected function getScopeSeparator()
    {
        return ' ';
    }

    /**
     * @param ResponseInterface $response
     * @param array|string      $data
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (!empty($data['error'])) {
            $code  = 0;
            $error = $data['error'];
            if (is_array($error)) {
                $code  = $error['code'];
                $error = $error['message'];
            }
            throw new IdentityProviderException($error, $code, $data);
        }
    }

    /**
     * @param array       $response
     * @param AccessToken $token
     *
     * @return PingIdentityUser
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new PingIdentityUser($response);
    }
}
