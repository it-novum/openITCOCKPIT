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
            'url'      => ['controller' => 'dashboards', 'action' => 'index', 'plugin' => ''],
            'title'    => 'Dashboard',
            'icon'     => 'dashboard',
            'order'    => 1,
            'children' => [
            ],
        ],

        'maps'          => [
            'url'      => ['controller' => 'statusmaps', 'action' => 'index', 'plugin' => ''],
            'title'    => 'Maps',
            'icon'     => 'map-marker',
            'order'    => 2,
            'children' => [
                'statusmap' => [
                    'url'               => ['controller' => 'statusmaps', 'action' => 'index'],
                    'title'             => 'Status Map',
                    'icon'              => 'globe',
                    'parent_controller' => 'itc',
                ],
                'automap'   => [
                    'url'               => ['controller' => 'automaps', 'action' => 'index'],
                    'title'             => 'Auto Map',
                    'icon'              => 'magic',
                    'parent_controller' => 'itc',
                ],
            ],
        ],
        'admin'         => [
            'url'      => ['controller' => 'changelogs', 'action' => 'index', 'plugin' => ''],
            'title'    => 'Administration',
            'icon'     => 'cogs',
            'order'    => 9,
            'children' => [
                'changelog'          => [
                    'url'               => ['controller' => 'changelogs', 'action' => 'index'],
                    'title'             => 'Change Log',
                    'icon'              => 'code-fork',
                    'parent_controller' => 'Administrators',
                ],
                'proxy'              => [
                    'url'               => ['controller' => 'proxy', 'action' => 'index'],
                    'title'             => 'Proxy Settings',
                    'icon'              => 'bolt',
                    'parent_controller' => 'Administrators',
                ],
                'packetmanager'      => [
                    'url'               => ['controller' => 'packetmanager', 'action' => 'index'],
                    'title'             => 'Package Manager',
                    'icon'              => 'cloud-download',
                    'parent_controller' => 'Administrators',
                ],
                'users'              => [
                    'url'               => ['plugin' => '', 'controller' => 'users', 'action' => 'index'],
                    'title'             => 'Manage Users',
                    'icon'              => 'user',
                    'parent_controller' => 'Administrators',
                ],
                'usergroups'         => [
                    'url'               => ['plugin' => '', 'controller' => 'usergroups', 'action' => 'index'],
                    'title'             => 'Manage User Roles',
                    'icon'              => 'users',
                    'parent_controller' => 'Administrators',
                ],
                'debug'              => [
                    'url'               => ['controller' => 'Administrators', 'action' => 'debug'],
                    'title'             => 'Debugging',
                    'icon'              => 'bug',
                    'parent_controller' => 'Administrators',
                ],
                'itc_systemfailures' => [
                    'url'               => ['controller' => 'systemfailures', 'action' => 'index'],
                    'title'             => 'System Failures',
                    'icon'              => 'medkit',
                    'parent_controller' => 'Administrators',
                ],
                'itc_systemsettings' => [
                    'url'               => ['controller' => 'systemsettings', 'action' => 'index'],
                    'title'             => 'System Settings',
                    'icon'              => 'wrench',
                    'parent_controller' => 'Administrators',
                ],
                'itc_cronjobs'       => [
                    'url'               => ['controller' => 'cronjobs', 'action' => 'index'],
                    'title'             => 'Cron Jobs',
                    'icon'              => 'clock-o',
                    'parent_controller' => 'Administrators',
                ],
                'itc_registration'   => [
                    'url'               => ['controller' => 'registers', 'action' => 'index'],
                    'title'             => 'Registration',
                    'icon'              => 'check-square-o ',
                    'parent_controller' => 'Administrators',
                ],
                'itc_backup'         => [
                    'url'               => ['controller' => 'backups', 'action' => 'index'],
                    'title'             => 'Backup / Restore',
                    'icon'              => 'database ',
                    'parent_controller' => 'Administrators',
                ],
            ],
        ],
        'itc'           => [
            'url'      => ['controller' => 'hosts', 'action' => 'index', 'plugin' => ''],
            'title'    => 'Basic Monitoring',
            'icon'     => 'cogs',
            'order'    => 3,
            'children' => [
                'itc_hosts'                => [
                    'url'               => ['controller' => 'hosts', 'action' => 'index'],
                    'title'             => 'Hosts',
                    'icon'              => 'desktop',
                    'parent_controller' => 'itc',
                ],
                'itc_services'             => [
                    'url'               => ['controller' => 'services', 'action' => 'index'],
                    'title'             => 'Services',
                    'icon'              => 'cog',
                    'parent_controller' => 'itc',
                ],
                'itc_browser'              => [
                    'url'               => ['controller' => 'browsers', 'action' => 'index'],
                    'title'             => 'Browser',
                    'icon'              => 'list',
                    'parent_controller' => 'itc',
                ],
                'itc_hosttemplates'        => [
                    'url'               => ['controller' => 'hosttemplates', 'action' => 'index'],
                    'title'             => 'Host Templates',
                    'icon'              => 'pencil-square-o',
                    'parent_controller' => 'itc',
                ],
                'itc_servicetemplates'     => [
                    'url'               => ['controller' => 'servicetemplates', 'action' => 'index'],
                    'title'             => 'Service Templates',
                    'icon'              => 'pencil-square-o',
                    'parent_controller' => 'itc',
                ],
                'itc_servicetemplategroup' => [
                    'url'               => ['controller' => 'servicetemplategroups', 'action' => 'index'],
                    'title'             => 'Service Template Grps.',
                    'icon'              => 'pencil-square-o',
                    'parent_controller' => 'itc',
                ],
                'itc_hostgroups'           => [
                    'url'               => ['controller' => 'hostgroups', 'action' => 'index'],
                    'title'             => 'Host Groups',
                    'icon'              => 'sitemap',
                    'parent_controller' => 'itc',
                ],
                'itc_servicegroups'        => [
                    'url'               => ['controller' => 'servicegroups', 'action' => 'index'],
                    'title'             => 'Service Groups',
                    'icon'              => 'cogs',
                    'parent_controller' => 'itc',
                ],
                'itc_contacts'             => [
                    'url'               => ['controller' => 'contacts', 'action' => 'index'],
                    'title'             => 'Contacts',
                    'icon'              => 'user',
                    'parent_controller' => 'itc',
                ],
                'itc_contactgroups'        => [
                    'url'               => ['controller' => 'contactgroups', 'action' => 'index'],
                    'title'             => 'Contact Groups',
                    'icon'              => 'users',
                    'parent_controller' => 'itc',
                ],
                'itc_calendars'            => [
                    'url'               => ['controller' => 'calendars', 'action' => 'index'],
                    'title'             => 'Calendar',
                    'icon'              => 'calendar',
                    'parent_controller' => 'itc',
                ],
                'itc_timeperiods'          => [
                    'url'               => ['controller' => 'timeperiods', 'action' => 'index'],
                    'title'             => 'Time Periods',
                    'icon'              => 'clock-o',
                    'parent_controller' => 'itc',
                ],
                'itc_commands'             => [
                    'url'               => ['controller' => 'commands', 'action' => 'index'],
                    'title'             => 'Commands',
                    'icon'              => 'terminal',
                    'parent_controller' => 'itc',
                ],
                'itc_tenants'              => [
                    'url'               => ['controller' => 'tenants', 'action' => 'index'],
                    'title'             => 'Tenants',
                    'icon'              => 'home',
                    'parent_controller' => 'itc',
                ],
                'itc_node'                 => [
                    'url'               => ['controller' => 'containers', 'action' => 'index'],
                    'title'             => 'Nodes',
                    'icon'              => 'link',
                    'parent_controller' => 'itc',
                ],
                'itc_locations'            => [
                    'url'               => ['controller' => 'locations', 'action' => 'index'],
                    'title'             => 'Locations',
                    'icon'              => 'location-arrow',
                    'parent_controller' => 'itc',
                ],
                'itc_graphgenerator'       => [
                    'url'               => ['controller' => 'graphgenerators', 'action' => 'index'],
                    'title'             => 'Graph Generator',
                    'icon'              => 'area-chart',
                    'parent_controller' => 'itc',
                ],
                'itc_graph_collections'    => [
                    'url'               => ['controller' => 'graph_collections', 'action' => 'index'],
                    'title'             => 'Graph Collections',
                    'icon'              => 'list-alt',
                    'parent_controller' => 'itc',
                ],
                'itc_downtimes'            => [
                    'url'               => ['controller' => 'downtimes', 'action' => 'index'],
                    'title'             => 'Downtimes',
                    'icon'              => 'power-off',
                    'parent_controller' => 'itc',
                    'fallback_actions'  => ['host', 'service'],
                ],
                'itc_recurring_downtimes'  => [
                    'url'               => ['controller' => 'systemdowntimes', 'action' => 'index'],
                    'title'             => 'Recurring downtimes',
                    'icon'              => 'history fa-flip-horizontal',
                    'parent_controller' => 'itc',
                    'fallback_actions'  => ['host', 'service'],
                ],
                'itc_logentries'           => [
                    'url'               => ['controller' => 'logentries', 'action' => 'index'],
                    'title'             => 'Log Entries',
                    'icon'              => 'file-text-o',
                    'parent_controller' => 'itc',
                ],
                'itc_notifications'        => [
                    'url'               => ['controller' => 'notifications', 'action' => 'index'],
                    'title'             => 'Notifications',
                    'icon'              => 'envelope',
                    'parent_controller' => 'itc',
                ],
                'itc_nagiostats'           => [
                    'url'               => ['controller' => 'nagiostats', 'action' => 'index'],
                    'title'             => 'Performance Info',
                    'icon'              => 'fighter-jet',
                    'parent_controller' => 'itc',
                ],
            ],
        ],
        'itc_expert'    => [
            'url'      => ['controller' => 'macros', 'action' => 'index', 'plugin' => ''],
            'title'    => 'Expert Monitoring',
            'icon'     => 'fire',
            'order'    => 4,
            'children' => [
                'itc_macros'            => [
                    'url'               => ['controller' => 'macros', 'action' => 'index'],
                    'title'             => 'User Defined Macros',
                    'icon'              => 'usd',
                    'parent_controller' => 'itc',
                ],
                'itc_hostescalation'    => [
                    'url'               => ['controller' => 'hostescalations', 'action' => 'index'],
                    'title'             => 'Host Escalations',
                    'icon'              => 'bomb',
                    'parent_controller' => 'itc',
                ],
                'itc_serviceescalation' => [
                    'url'               => ['controller' => 'serviceescalations', 'action' => 'index'],
                    'title'             => 'Service Escalations',
                    'icon'              => 'bomb',
                    'parent_controller' => 'itc',
                ],
                'itc_hostdependency'    => [
                    'url'               => ['controller' => 'hostdependencies', 'action' => 'index'],
                    'title'             => 'Host Dependencies',
                    'icon'              => 'sitemap',
                    'parent_controller' => 'itc',
                ],
                'itc_servicedependency' => [
                    'url'               => ['controller' => 'servicedependencies', 'action' => 'index'],
                    'title'             => 'Service Dependencies',
                    'icon'              => 'sitemap',
                    'parent_controller' => 'itc',
                ],
                'external_commands'     => [
                    'url'               => ['controller' => 'cmd', 'action' => 'index', 'plugin' => 'nagios_module'],
                    'title'             => 'External Commands',
                    'icon'              => 'terminal',
                    'parent_controller' => 'itc',
                ],
            ],
        ],
        'reporting'     => [
            'url'      => ['controller' => 'instantreports', 'action' => 'index', 'plugin' => ''],
            'title'    => 'Reporting',
            'icon'     => 'file-text-o',
            'order'    => 5,
            'children' => [
                'instantreports'      => [
                    'url'               => ['controller' => 'instantreports', 'action' => 'index'],
                    'title'             => 'Instant Report',
                    'icon'              => 'file-image-o',
                    'parent_controller' => 'reporting',
                ],
                'downtimereports'     => [
                    'url'               => ['controller' => 'downtimereports', 'action' => 'index'],
                    'title'             => 'Downtime Report',
                    'icon'              => 'file-image-o',
                    'parent_controller' => 'reporting',
                ],
                'currentstatereports' => [
                    'url'               => ['controller' => 'currentstatereports', 'action' => 'index'],
                    'title'             => 'Current State Report',
                    'icon'              => 'file-image-o',
                    'parent_controller' => 'reporting',
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
            'url'      => ['controller' => 'supports', 'action' => 'index', 'plugin' => ''],
            'title'    => 'Support',
            'icon'     => 'life-ring',
            'order'    => 999,
            'children' => [
                'issue_collector' => [
                    'url'               => ['controller' => 'supports', 'action' => 'issue'],
                    'title'             => 'Report an issue',
                    'icon'              => 'bug',
                    'parent_controller' => 'reporting',
                ],
            ],
        ],

    ],
];
