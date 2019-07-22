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

$config = [
    'menu' => [
        'dashboard' => [
            'url'   => ['controller' => 'dashboards', 'action' => 'index', 'plugin' => ''],
            'state' => 'DashboardsIndex',
            'title' => 'Dashboard',
            'icon'  => 'dashboard',
            'order' => 1,
        ],

        'maps'          => [
            'title'    => 'Maps',
            'icon'     => 'map-marker',
            'order'    => 2,
            'children' => [
                [
                    'url'   => ['controller' => 'statusmaps', 'action' => 'index'],
                    'state' => 'StatusmapsIndex',
                    'title' => 'Status Map',
                    'icon'  => 'globe',
                ],
                [
                    'url'   => ['controller' => 'automaps', 'action' => 'index'],
                    'title' => 'Auto Map',
                    'icon'  => 'magic',
                ],
            ],
        ],
        'configuration' => [
            'title'    => 'Configuration',
            'icon'     => 'wrench',
            'order'    => 9,
            'children' => [
                [
                    'url'   => ['controller' => 'systemsettings', 'action' => 'index'],
                    'state' => 'SystemsettingsIndex',
                    'title' => 'System Settings',
                    'icon'  => 'wrench',
                ],
                [
                    'url'   => ['controller' => 'proxy', 'action' => 'index'],
                    'state' => 'ProxyIndex',
                    'title' => 'Proxy Settings',
                    'icon'  => 'bolt',
                ],
                [
                    'url'   => ['controller' => 'ConfigurationFiles', 'action' => 'index'],
                    'state' => 'ConfigurationFilesIndex',
                    'title' => 'Config file editor',
                    'icon'  => 'file-text-o',
                ],
            ],
        ],
        'admin'         => [
            'title'    => 'Administration',
            'icon'     => 'cogs',
            'order'    => 10,
            'children' => [
                [
                    'url'   => ['controller' => 'changelogs', 'action' => 'index'],
                    'title' => 'Change Log',
                    'icon'  => 'code-fork',
                ],
                [
                    'url'   => ['controller' => 'packetmanager', 'action' => 'index'],
                    'title' => 'Package Manager',
                    'icon'  => 'cloud-download',
                ],
                [
                    'url'   => ['plugin' => '', 'controller' => 'users', 'action' => 'index'],
                    'title' => 'Manage Users',
                    'state' => 'UsersIndex',
                    'icon'  => 'user',
                ],
                [
                    'url'   => ['plugin' => '', 'controller' => 'usergroups', 'action' => 'index'],
                    'title' => 'Manage User Roles',
                    'icon'  => 'users',
                ],
                [
                    'url'   => ['controller' => 'Administrators', 'action' => 'debug'],
                    'title' => 'Debugging',
                    'icon'  => 'bug',
                ],
                [
                    'url'   => ['controller' => 'systemfailures', 'action' => 'index'],
                    'title' => 'System Failures',
                    'state' => 'SystemfailuresIndex',
                    'icon'  => 'exclamation-circle',
                ],
                [
                    'url'   => ['controller' => 'cronjobs', 'action' => 'index'],
                    'state' => 'CronjobsIndex',
                    'title' => 'Cron Jobs',
                    'icon'  => 'clock-o',
                ],
                [
                    'url'   => ['controller' => 'registers', 'action' => 'index'],
                    'state' => 'RegistersIndex',
                    'title' => 'Registration',
                    'icon'  => 'check-square-o ',
                ],
                [
                    'url'   => ['controller' => 'backups', 'action' => 'index'],
                    'title' => 'Backup / Restore',
                    'icon'  => 'database ',
                ],
                [
                    'url'   => ['controller' => 'statistics', 'action' => 'index'],
                    'state' => 'StatisticsIndex',
                    'title' => 'Anonymous statistics',
                    'icon'  => 'line-chart ',
                ],
            ],
        ],
        'itc'           => [
            'title'    => 'Basic Monitoring',
            'icon'     => 'cogs',
            'order'    => 3,
            'children' => [
                [
                    'url'   => ['controller' => 'hosts', 'action' => 'index'],
                    'state' => 'HostsIndex',
                    'title' => 'Hosts',
                    'icon'  => 'desktop',
                ],
                [
                    'url'   => ['controller' => 'services', 'action' => 'index'],
                    'state' => 'ServicesIndex',
                    'title' => 'Services',
                    'icon'  => 'cog',
                ],
                [
                    'url'   => ['controller' => 'browsers', 'action' => 'index'],
                    'state' => 'BrowsersIndex',
                    'title' => 'Browser',
                    'icon'  => 'list',
                ],
                [
                    'url'   => ['controller' => 'hosttemplates', 'action' => 'index'],
                    'state' => 'HosttemplatesIndex',
                    'title' => 'Host Templates',
                    'icon'  => 'pencil-square-o',
                ],
                [
                    'url'   => ['controller' => 'servicetemplates', 'action' => 'index'],
                    'state' => 'ServicetemplatesIndex',
                    'title' => 'Service Templates',
                    'icon'  => 'pencil-square-o',
                ],
                [
                    'url'   => ['controller' => 'servicetemplategroups', 'action' => 'index'],
                    'title' => 'Service Template Grps.',
                    'state' => 'ServicetemplategroupsIndex',
                    'icon'  => 'pencil-square-o',
                ],
                [
                    'url'   => ['controller' => 'hostgroups', 'action' => 'index'],
                    'state' => 'HostgroupsIndex',
                    'title' => 'Host Groups',
                    'icon'  => 'sitemap',
                ],
                [
                    'url'   => ['controller' => 'servicegroups', 'action' => 'index'],
                    'state' => 'ServicegroupsIndex',
                    'title' => 'Service Groups',
                    'icon'  => 'cogs',
                ],
                [
                    'url'   => ['controller' => 'contacts', 'action' => 'index'],
                    'state' => 'ContactsIndex',
                    'title' => 'Contacts',
                    'icon'  => 'user',
                ],
                [
                    'url'   => ['controller' => 'contactgroups', 'action' => 'index'],
                    'state' => 'ContactgroupsIndex',
                    'title' => 'Contact Groups',
                    'icon'  => 'users',
                ],
                [
                    'url'   => ['controller' => 'calendars', 'action' => 'index'],
                    'title' => 'Calendar',
                    'icon'  => 'calendar',
                ],
                [
                    'url'   => ['controller' => 'timeperiods', 'action' => 'index'],
                    'state' => 'TimeperiodsIndex',
                    'title' => 'Time Periods',
                    'icon'  => 'clock-o',
                ],
                [
                    'url'   => ['controller' => 'commands', 'action' => 'index'],
                    'state' => 'CommandsIndex',
                    'title' => 'Commands',
                    'icon'  => 'terminal',
                ],
                [
                    'url'   => ['controller' => 'tenants', 'action' => 'index'],
                    'state' => 'TenantsIndex',
                    'title' => 'Tenants',
                    'icon'  => 'home',
                ],
                [
                    'url'   => ['controller' => 'containers', 'action' => 'index'],
                    'state' => 'ContainersIndex',
                    'title' => 'Containers',
                    'icon'  => 'link',
                ],
                [
                    'url'   => ['controller' => 'locations', 'action' => 'index'],
                    'state' => 'LocationsIndex',
                    'title' => 'Locations',
                    'icon'  => 'location-arrow',
                ],
                [
                    'url'              => ['controller' => 'downtimes', 'action' => 'host'],
                    'state'            => 'DowntimesHost',
                    'title'            => 'Downtimes',
                    'icon'             => 'power-off',
                    'fallback_actions' => ['service' => 'DowntimesService'],
                ],
                [
                    'url'              => ['controller' => 'systemdowntimes', 'action' => 'host'],
                    'state'            => 'SystemdowntimesHost',
                    'title'            => 'Recurring downtimes',
                    'icon'             => 'history fa-flip-horizontal',
                    'fallback_actions' => ['service' => 'SystemdowntimesService'],
                ],
                [
                    'url'   => ['controller' => 'logentries', 'action' => 'index'],
                    'state' => 'LogentriesIndex',
                    'title' => 'Log Entries',
                    'icon'  => 'file-text-o',
                ],
                [
                    'url'   => ['controller' => 'notifications', 'action' => 'index'],
                    'state' => 'NotificationsIndex',
                    'title' => 'Notifications',
                    'icon'  => 'envelope',
                ],
                [
                    'url'   => ['controller' => 'nagiostats', 'action' => 'index'],
                    'title' => 'Performance Info',
                    'icon'  => 'fighter-jet',
                ],
            ],
        ],
        'itc_expert'    => [
            'title'    => 'Expert Monitoring',
            'icon'     => 'fire',
            'order'    => 4,
            'children' => [
                [
                    'url'   => ['controller' => 'macros', 'action' => 'index'],
                    'state' => 'MacrosIndex',
                    'title' => 'User Defined Macros',
                    'icon'  => 'usd',
                ],
                [
                    'url'   => ['controller' => 'hostescalations', 'action' => 'index'],
                    'state' => 'HostescalationsIndex',
                    'title' => 'Host Escalations',
                    'icon'  => 'bomb',
                ],
                [
                    'url'   => ['controller' => 'serviceescalations', 'action' => 'index'],
                    'state' => 'ServiceescalationsIndex',
                    'title' => 'Service Escalations',
                    'icon'  => 'bomb',
                ],
                [
                    'url'   => ['controller' => 'hostdependencies', 'action' => 'index'],
                    'state' => 'HostdependenciesIndex',
                    'title' => 'Host Dependencies',
                    'icon'  => 'sitemap',
                ],
                [
                    'url'   => ['controller' => 'servicedependencies', 'action' => 'index'],
                    'state' => 'ServicedependenciesIndex',
                    'title' => 'Service Dependencies',
                    'icon'  => 'sitemap',
                ],
                [
                    'url'   => ['controller' => 'cmd', 'action' => 'index', 'plugin' => 'nagios_module'],
                    'title' => 'External Commands',
                    'icon'  => 'terminal',
                ],
            ],
        ],
        'reporting'     => [
            'title'    => 'Reporting',
            'icon'     => 'file-text-o',
            'order'    => 5,
            'children' => [
                [
                    'url'   => ['controller' => 'instantreports', 'action' => 'index'],
                    'state' => 'InstantreportsIndex',
                    'title' => 'Instant Report',
                    'icon'  => 'file-image-o',
                ],
                [
                    'url'   => ['controller' => 'downtimereports', 'action' => 'index'],
                    'title' => 'Downtime Report',
                    'icon'  => 'file-image-o',
                ],
                [
                    'url'   => ['controller' => 'currentstatereports', 'action' => 'index'],
                    'state' => 'CurrentstatereportsIndex',
                    'title' => 'Current State Report',
                    'icon'  => 'file-image-o',
                ],
            ],
        ],
        'documentation' => [
            'url'   => ['controller' => 'documentations', 'action' => 'wiki', 'plugin' => ''],
            'title' => 'Documentation',
            'icon'  => 'book',
            'order' => 6,
        ],
        'support'       => [
            'title'    => 'Support',
            'icon'     => 'life-ring',
            'order'    => 999,
            'children' => [
                [
                    'url'   => ['controller' => 'supports', 'action' => 'issue'],
                    'state' => 'SupportsIssue',
                    'title' => 'Report an issue',
                    'icon'  => 'bug',
                ],
            ],
        ],

    ],
];
