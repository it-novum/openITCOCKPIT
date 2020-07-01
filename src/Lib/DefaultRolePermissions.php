<?php
// Copyright (C) <2018>  <it-novum GmbH>
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

namespace App\Lib;


use App\itnovum\openITCOCKPIT\Core\Permissions\DefaultRolePermissionsInterface;
use Cake\Utility\Hash;

class DefaultRolePermissions {

    /**
     * @return array
     */
    public static function getDefaultRolePermissions() {
        $default = [
            'Viewer' => [
                'Acknowledgements'      => ['service', 'host'],
                'Administrators'        => ['index'],
                'Automaps'              => ['index'],
                'Browsers'              => ['index'],
                'Calendars'             => ['index'],
                'Changelogs'            => ['index'],
                'Commands'              => ['index'],
                'Contactgroups'         => ['index'],
                'Contacts'              => ['index'],
                'Containers'            => ['index'],
                'Cronjobs'              => ['index'],
                'Currentstatereports'   => ['index'],
                'DeletedHosts'          => ['index'],
                'Documentations'        => ['view', 'wiki'],
                'Downtimereports'       => ['index', 'host', 'service'],
                //'Exports'               => ['index'],
                'Hostchecks'            => ['index'],
                'Hostdependencies'      => ['index'],
                'Hostescalations'       => ['index'],
                'Hostgroups'            => ['index', 'extended'],
                'Hosts'                 => ['index', 'notMonitored', 'disabled', 'browser'],
                'Hosttemplates'         => ['index', 'usedBy'],
                'Locations'             => ['index'],
                'Logentries'            => ['index'],
                'Macros'                => ['index'],
                'Nagiostats'            => ['index'],
                'Notifications'         => ['index', 'hostNotification', 'serviceNotification'],
                'Packetmanager'         => ['index'],
                'Servicechecks'         => ['index'],
                'Servicedependencies'   => ['index'],
                'Serviceescalations'    => ['index'],
                'Servicegroups'         => ['index', 'extended'],
                'Services'              => ['index', 'notMonitored', 'disabled', 'browser', 'serviceList'],
                'Servicetemplategroups' => ['index'],
                'Servicetemplates'      => ['index'],
                'Statehistories'        => ['service', 'host'],
                'Statusmaps'            => ['index'],
                'Systemfailures'        => ['index'],
                'Tenants'               => ['index'],
                'Timeperiods'           => ['index'],
                'Usergroups'            => ['index'],
                'Users'                 => ['index'],
                'Backups'               => ['index'],
                'Supports'              => ['index', 'issue'],
                'Instantreports'        => ['index', 'sendEmailsList']
            ]
        ];

        //Load defaults defined by plugins
        foreach (PluginManager::getAvailablePlugins() as $pluginName) {
            $className = sprintf('\%s\Lib\DefaultRolePermissions', $pluginName);
            if (class_exists($className)) {
                /** @var DefaultRolePermissionsInterface $PluginDefaultRolePermissions */
                $PluginDefaultRolePermissions = new $className();

                foreach ($PluginDefaultRolePermissions->getDefaultRolePermissions() as $usergroupName => $actions) {
                    $default[$usergroupName][$pluginName] = $actions;
                }

            }
        }

        return $default;
    }

}
