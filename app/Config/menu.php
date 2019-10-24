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
            'tags'  => __('dashboard'),
        ],

        'maps' => [
            'title'    => 'Maps',
            'icon'     => 'map-marker',
            'order'    => 2,
            'tags'     => __('maps'),
            'children' => [
                [
                    'url'   => ['controller' => 'statusmaps', 'action' => 'index'],
                    'state' => 'StatusmapsIndex',
                    'title' => 'Status Map',
                    'icon'  => 'globe',
                    'tags'  => __('status map statusmap'),
                ],
                [
                    'url'   => ['controller' => 'automaps', 'action' => 'index'],
                    'title' => 'Auto Maps',
                    'state' => 'AutomapsIndex',
                    'icon'  => 'magic',
                    'tags'  => __('auto maps automaps'),
                ],
            ],
        ],

        'configuration' => [
            'title'    => 'Configuration',
            'icon'     => 'wrench',
            'order'    => 9,
            'tags'     => __('Configuration'),
            'children' => [
                [
                    'url'   => ['controller' => 'systemsettings', 'action' => 'index'],
                    'state' => 'SystemsettingsIndex',
                    'title' => 'System Settings',
                    'icon'  => 'wrench',
                    'tags'  => __('systemsettings System Settings'),
                ],
                [
                    'url'   => ['controller' => 'proxy', 'action' => 'index'],
                    'state' => 'ProxyIndex',
                    'title' => 'Proxy Settings',
                    'icon'  => 'bolt',
                    'tags'  => __('proxy Settings'),
                ],
                [
                    'url'   => ['controller' => 'ConfigurationFiles', 'action' => 'index'],
                    'state' => 'ConfigurationFilesIndex',
                    'title' => 'Config file editor',
                    'icon'  => 'file-text-o',
                    'tags'  => __('ConfigurationFiles Config file editor'),
                ],
            ],
        ],

        'admin' => [
            'title'    => 'Administration',
            'icon'     => 'cogs',
            'order'    => 10,
            'tags'     => __('Administration'),
            'children' => [
                [
                    'url'   => ['controller' => 'changelogs', 'action' => 'index'],
                    'title' => 'Change Log',
                    'icon'  => 'code-fork',
                    'tags'  => __('Change Log changelog changelogs'),
                ],
                [
                    'url'   => ['controller' => 'packetmanager', 'action' => 'index'],
                    'title' => 'Package Manager',
                    'icon'  => 'cloud-download',
                    'tags'  => __('packetmanager Package Manager packagemanager'),
                ],
                [
                    'url'   => ['plugin' => '', 'controller' => 'users', 'action' => 'index'],
                    'title' => 'Manage Users',
                    'state' => 'UsersIndex',
                    'icon'  => 'user',
                    'tags'  => __('users Manage'),
                ],
                [
                    'url'   => ['plugin' => '', 'controller' => 'usergroups', 'action' => 'index'],
                    'title' => 'Manage User Roles',
                    'state' => 'UsergroupsIndex',
                    'icon'  => 'users',
                    'tags'  => __('Manage User Roles userroles roles usergroups groups'),
                ],
                [
                    'url'   => ['plugin' => '', 'controller' => 'usercontainerroles', 'action' => 'index'],
                    'title' => 'User Container Roles',
                    'state' => 'UsercontainerrolesIndex',
                    'icon'  => 'users',
                    'tags'  => __('User Container Roles containerroles containerrole '),
                ],
                [
                    'url'   => ['controller' => 'Administrators', 'action' => 'debug'],
                    'title' => 'Debugging',
                    'state' => 'AdministratorsDebug',
                    'icon'  => 'bug',
                    'tags'  => __('debug debugging'),
                ],
                [
                    'url'   => ['controller' => 'systemfailures', 'action' => 'index'],
                    'title' => 'System Failures',
                    'state' => 'SystemfailuresIndex',
                    'icon'  => 'exclamation-circle',
                    'tags'  => __('systemfailures System Failures'),
                ],
                [
                    'url'   => ['controller' => 'cronjobs', 'action' => 'index'],
                    'state' => 'CronjobsIndex',
                    'title' => 'Cron Jobs',
                    'icon'  => 'clock-o',
                    'tags'  => __('cronjobs cronjob cron jobs'),
                ],
                [
                    'url'   => ['controller' => 'registers', 'action' => 'index'],
                    'state' => 'RegistersIndex',
                    'title' => 'Registration',
                    'icon'  => 'check-square-o ',
                    'tags'  => __('Registration registers license'),
                ],
                [
                    'url'   => ['controller' => 'backups', 'action' => 'index'],
                    'title' => 'Backup / Restore',
                    'icon'  => 'database ',
                    'tags'  => __('backups backup restore'),
                ],
                [
                    'url'   => ['controller' => 'statistics', 'action' => 'index'],
                    'state' => 'StatisticsIndex',
                    'title' => 'Anonymous statistics',
                    'icon'  => 'line-chart ',
                    'tags'  => __('statistics anonymous'),
                ],
            ],
        ],

        'itc' => [
            'title'    => 'Basic Monitoring',
            'icon'     => 'cogs',
            'order'    => 3,
            'tags'     => __('Basic Monitoring'),
            'children' => [
                [
                    'url'   => ['controller' => 'hosts', 'action' => 'index'],
                    'state' => 'HostsIndex',
                    'title' => 'Hosts',
                    'icon'  => 'desktop',
                    'tags'  => __('hosts host'),
                ],
                [
                    'url'   => ['controller' => 'services', 'action' => 'index'],
                    'state' => 'ServicesIndex',
                    'title' => 'Services',
                    'icon'  => 'cog',
                    'tags'  => __('services service'),
                ],
                [
                    'url'   => ['controller' => 'browsers', 'action' => 'index'],
                    'state' => 'BrowsersIndex',
                    'title' => 'Browser',
                    'icon'  => 'list',
                    'tags'  => __('browsers browser'),
                ],
                [
                    'url'   => ['controller' => 'hosttemplates', 'action' => 'index'],
                    'state' => 'HosttemplatesIndex',
                    'title' => 'Host Templates',
                    'icon'  => 'pencil-square-o',
                    'tags'  => __('hosttemplates hosttemplate template templates'),
                ],
                [
                    'url'   => ['controller' => 'servicetemplates', 'action' => 'index'],
                    'state' => 'ServicetemplatesIndex',
                    'title' => 'Service Templates',
                    'icon'  => 'pencil-square-o',
                    'tags'  => __('servicetemplates servicetemplate template templates'),
                ],
                [
                    'url'   => ['controller' => 'servicetemplategroups', 'action' => 'index'],
                    'title' => 'Service Template Grps.',
                    'state' => 'ServicetemplategroupsIndex',
                    'icon'  => 'pencil-square-o',
                    'tags'  => __('servicetemplategroups servicetemplategroup template templates'),
                ],
                [
                    'url'   => ['controller' => 'hostgroups', 'action' => 'index'],
                    'state' => 'HostgroupsIndex',
                    'title' => 'Host Groups',
                    'icon'  => 'sitemap',
                    'tags'  => __('hostgroups hostgroup group groups'),
                ],
                [
                    'url'   => ['controller' => 'servicegroups', 'action' => 'index'],
                    'state' => 'ServicegroupsIndex',
                    'title' => 'Service Groups',
                    'icon'  => 'cogs',
                    'tags'  => __('servicegroups servicegroup group groups'),
                ],
                [
                    'url'   => ['controller' => 'contacts', 'action' => 'index'],
                    'state' => 'ContactsIndex',
                    'title' => 'Contacts',
                    'icon'  => 'user',
                    'tags'  => __('contacts contact'),
                ],
                [
                    'url'   => ['controller' => 'contactgroups', 'action' => 'index'],
                    'state' => 'ContactgroupsIndex',
                    'title' => 'Contact Groups',
                    'icon'  => 'users',
                    'tags'  => __('contactgroups contactgroup group groups'),
                ],
                [
                    'url'   => ['controller' => 'calendars', 'action' => 'index'],
                    'state' => 'CalendarsIndex',
                    'title' => 'Calendar',
                    'icon'  => 'calendar',
                    'tags'  => __('calendars calendar'),
                ],
                [
                    'url'   => ['controller' => 'timeperiods', 'action' => 'index'],
                    'state' => 'TimeperiodsIndex',
                    'title' => 'Time Periods',
                    'icon'  => 'clock-o',
                    'tags'  => __('timeperiods timeperiod Time Periods period'),
                ],
                [
                    'url'   => ['controller' => 'commands', 'action' => 'index'],
                    'state' => 'CommandsIndex',
                    'title' => 'Commands',
                    'icon'  => 'terminal',
                    'tags'  => __('commands command'),
                ],
                [
                    'url'   => ['controller' => 'tenants', 'action' => 'index'],
                    'state' => 'TenantsIndex',
                    'title' => 'Tenants',
                    'icon'  => 'home',
                    'tags'  => __('tenants tenant'),
                ],
                [
                    'url'   => ['controller' => 'containers', 'action' => 'index'],
                    'state' => 'ContainersIndex',
                    'title' => 'Containers',
                    'icon'  => 'link',
                    'tags'  => __('containers container node nodes'),
                ],
                [
                    'url'   => ['controller' => 'locations', 'action' => 'index'],
                    'state' => 'LocationsIndex',
                    'title' => 'Locations',
                    'icon'  => 'location-arrow',
                    'tags'  => __('locations location'),
                ],
                [
                    'url'              => ['controller' => 'downtimes', 'action' => 'host'],
                    'state'            => 'DowntimesHost',
                    'title'            => 'Downtimes',
                    'icon'             => 'power-off',
                    'fallback_actions' => ['service' => 'DowntimesService'],
                    'tags'             => __('Downtimes Downtime'),
                ],
                [
                    'url'              => ['controller' => 'systemdowntimes', 'action' => 'host'],
                    'state'            => 'SystemdowntimesHost',
                    'title'            => 'Recurring downtimes',
                    'icon'             => 'history fa-flip-horizontal',
                    'fallback_actions' => ['service' => 'SystemdowntimesService'],
                    'tags'             => __('systemdowntimes systemdowntime Recurring downtimes downtime'),
                ],
                [
                    'url'   => ['controller' => 'logentries', 'action' => 'index'],
                    'state' => 'LogentriesIndex',
                    'title' => 'Log entries',
                    'icon'  => 'file-text-o',
                    'tags'  => __('logentries Log entries'),
                ],
                [
                    'url'   => ['controller' => 'notifications', 'action' => 'index'],
                    'state' => 'NotificationsIndex',
                    'title' => 'Notifications',
                    'icon'  => 'envelope',
                    'tags'  => __('Notifications Notification'),
                ],
                [
                    'url'   => ['controller' => 'nagiostats', 'action' => 'index'],
                    'state' => 'NagiostatsIndex',
                    'title' => 'Performance Info',
                    'icon'  => 'fighter-jet',
                    'tags'  => __('Performance Info nagiostats'),
                ],
            ],
        ],

        'itc_expert' => [
            'title'    => 'Expert Monitoring',
            'icon'     => 'fire',
            'order'    => 4,
            'tags'     => __('Expert Monitoring'),
            'children' => [
                [
                    'url'   => ['controller' => 'macros', 'action' => 'index'],
                    'state' => 'MacrosIndex',
                    'title' => 'User Defined Macros',
                    'icon'  => 'usd',
                    'tags'  => __('macros macro User Defined'),
                ],
                [
                    'url'   => ['controller' => 'hostescalations', 'action' => 'index'],
                    'state' => 'HostescalationsIndex',
                    'title' => 'Host Escalations',
                    'icon'  => 'bomb',
                    'tags'  => __('Host Escalations hostescalations hostescalation'),
                ],
                [
                    'url'   => ['controller' => 'serviceescalations', 'action' => 'index'],
                    'state' => 'ServiceescalationsIndex',
                    'title' => 'Service Escalations',
                    'icon'  => 'bomb',
                    'tags'  => __('Service Escalations serviceescalations'),
                ],
                [
                    'url'   => ['controller' => 'hostdependencies', 'action' => 'index'],
                    'state' => 'HostdependenciesIndex',
                    'title' => 'Host Dependencies',
                    'icon'  => 'sitemap',
                    'tags'  => __('hostdependencies Host Dependencies'),
                ],
                [
                    'url'   => ['controller' => 'servicedependencies', 'action' => 'index'],
                    'state' => 'ServicedependenciesIndex',
                    'title' => 'Service Dependencies',
                    'icon'  => 'sitemap',
                    'tags'  => __('servicedependencies Service Dependencies'),
                ],
                [
                    'url'   => ['controller' => 'cmd', 'action' => 'index', 'plugin' => 'nagios_module'],
                    'title' => 'External Commands',
                    'icon'  => 'terminal',
                    'tags'  => __('cmd External Commands'),
                ],
            ],
        ],

        'reporting' => [
            'title'    => 'Reporting',
            'icon'     => 'file-text-o',
            'order'    => 5,
            'tags'     => __('Reporting'),
            'children' => [
                [
                    'url'   => ['controller' => 'instantreports', 'action' => 'index'],
                    'state' => 'InstantreportsIndex',
                    'title' => 'Instant Report',
                    'icon'  => 'file-image-o',
                    'tags'  => __('instantreports Instant Report'),
                ],
                [
                    'url'   => ['controller' => 'downtimereports', 'action' => 'index'],
                    'state' => 'DowntimereportsIndex',
                    'title' => 'Downtime Report',
                    'icon'  => 'file-image-o',
                    'tags'  => __('downtimereports Downtime Report'),
                ],
                [
                    'url'   => ['controller' => 'currentstatereports', 'action' => 'index'],
                    'state' => 'CurrentstatereportsIndex',
                    'title' => 'Current State Report',
                    'icon'  => 'file-image-o',
                    'tags'  => __('currentstatereports Current State Report'),
                ],
            ],
        ],

        'documentation' => [
            'url'   => ['controller' => 'documentations', 'action' => 'wiki', 'plugin' => ''],
            'title' => 'Documentation',
            'state' => 'DocumentationsWiki',
            'icon'  => 'book',
            'order' => 6,
            'tags'  => __('documentations wiki'),
        ],

        'support' => [
            'title'    => 'Support',
            'icon'     => 'life-ring',
            'order'    => 999,
            'tags'     => __('Support'),
            'children' => [
                [
                    'url'   => ['controller' => 'supports', 'action' => 'issue'],
                    'state' => 'SupportsIssue',
                    'title' => 'Report an issue',
                    'icon'  => 'bug',
                    'tags'  => __('supports Report an issue'),
                ],
            ],
        ],

        'oitc_agent' => [
            'title'    => 'openITCOCKPIT Agent',
            'icon'     => 'user-secret',
            'order'    => 8,
            'tags'     => __('openITCOCKPIT Agent'),
            'children' => [
                [
                    'url'   => ['controller' => 'servicetemplates', 'action' => 'agent'],
                    'state' => 'ServicetemplatesAgent',
                    'title' => 'Agent Servicetemp.',
                    'icon'  => 'pencil-square-o',
                    'tags'  => __('Agent Servicetemplate ServicetemplatesAgent template'),
                ],
                [
                    'url'   => ['controller' => 'agentchecks', 'action' => 'index'],
                    'state' => 'AgentchecksIndex',
                    'title' => 'Agent Checks',
                    'icon'  => 'cogs',
                    'tags'  => __('agentchecks Agent Checks'),
                ],
            ],
        ],

    ],
];
