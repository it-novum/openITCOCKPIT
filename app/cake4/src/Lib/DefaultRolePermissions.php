<?php
/**
 * Statusengine Worker
 * Copyright (C) 2016-2020  Daniel Ziegler
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Lib;


class DefaultRolePermissions {

    public static function getDefaultRolePermissions() {
        return [
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
                'Exports'               => ['index'],
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
    }

}
