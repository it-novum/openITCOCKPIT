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

/**
 * Class Oauth2ModuleSchema
 */
class Oauth2ModuleSchema extends CakeSchema
{
    /**
     * @param array $event
     *
     * @return bool
     */
    public function before($event = [])
    {
        $db = ConnectionManager::getDataSource($this->connection);
        $db->cacheSources = false;

        return true;
    }

    /**
     * @var array
     */
    public $oauth2clients = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'client_id'       => ['type' => 'string', 'length' => 255, 'null' => false],
        'client_secret'   => ['type' => 'string', 'length' => 255, 'null' => false],
        'redirect_uri'    => ['type' => 'string', 'length' => 255, 'null' => false],
        'url_authorize'   => ['type' => 'string', 'length' => 255, 'null' => false],
        'url_accessToken' => ['type' => 'string', 'length' => 255, 'null' => false],
        'provider'        => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => 'PingIdentity'],
        'show_login_page' => ['type' => 'integer', 'null' => false, 'default' => 1],
        'button_text'     => ['type' => 'string', 'length' => 255, 'null' => false],
        'active'          => ['type' => 'integer', 'null' => false, 'default' => 1],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];
}
