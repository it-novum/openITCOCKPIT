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

/**
 * Class PingIdentityUser
 */
class PingIdentityUser implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->response['id'];
    }
    /**
     * Get preferred display name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->response['displayName'];
    }
    /**
     * Get preferred first name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->response['name']['givenName'];
    }
    /**
     * Get preferred last name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->response['name']['familyName'];
    }
    /**
     * Get email address.
     *
     * @return string|null
     */
    public function getEmail()
    {
        if (!empty($this->response['emails'])) {
            return $this->response['emails'][0]['value'];
        }
    }
    /**
     * Get avatar image URL.
     *
     * @return string|null
     */
    public function getAvatar()
    {
        if (!empty($this->response['image']['url'])) {
            return $this->response['image']['url'];
        }
    }
    /**
     * Get user data as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}