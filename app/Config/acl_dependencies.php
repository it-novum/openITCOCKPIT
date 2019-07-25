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
    'acl_dependencies' => [
        'AppController'  => ['getNamedParameter', 'isAuthorized', 'flashBack', 'setFlash', 'serviceResponse', 'allowedByContainerId', 'render403', 'checkForUpdates', 'tableLocator', 'setTableLocator', 'getTableLocator'],
        'always_allowed' => [
            'Angular'          => [
                'index',
                'paginator',
                'scroll',
                'mass_delete',
                'mass_deactivate',
                'confirm_delete',
                'confirm_deactivate',
                'mass_activate',
                'user_timezone',
                'version_check',
                'menustats',
                'statuscount',
                'menu',
                'websocket_configuration',
                'push_configuration',
                'export',
                'not_found',
                'forbidden',
                'executing',
                'acknowledge_service',
                'downtime_service',
                'reschedule_host',
                'downtime_host',
                'acknowledge_host',
                'enable_host_notifications',
                'disable_host_notifications',
                'system_health',
                'getDowntimeData',
                'mass_delete_host_downtimes',
                'mass_delete_service_downtimes',
                'submit_host_result',
                'disable_host_flap_detection',
                'enable_host_flap_detection',
                'send_host_notification',
                'submit_service_result',
                'disable_service_flap_detection',
                'enable_service_flap_detection',
                'send_service_notification',
                'enable_service_notifications',
                'disable_service_notifications',
                'getPieChart',
                'getHalfPieChart',
                'getCumulatedHostAndServiceStateIcon',
                'getHostAndServiceStateSummaryIcon',
                'macros',
                'ldap_configuration',
                'priority',
                'intervalInput',
                'intervalInputWithDiffer',
                'humanTime',
                'template_diff',
                'template_diff_button',
                'queryhandler',
                'hostBrowserMenu',
                'serviceBrowserMenu'
            ],
            'Automaps'         => [
                'icon'
            ],
            'Calendars'        => [
                'loadCalendarsByContainerId'
            ],
            'Containers'       => [
                'byTenantForSelect', 'byTenant', 'loadContainersForAngular', 'loadContainers', 'loadContainersByContainerId'
            ],
            'Downtimes'        => [
                'validateDowntimeInputFromBrowser',
                'validateDowntimeInputFromAngular',
                'icon'
            ],
            'Forward'          => [
                'index',
            ],
            'GraphCollections' => [
                'loadCollectionGraphData',
            ],
            'Packetmanager'    => [
                'getPackets',
            ],
            'Profile'          => [
                'edit',
                'deleteImage',
                'apikey',
                'edit_apikey',
                'delete_apikey',
                'create_apikey'
            ],
            'Proxy'            => [
                'getSettings',
            ],
            'Rrds'             => [
                'index',
                'ajax',
            ],
            'Search'           => [
                'index',
                'hostMacro',
                'serviceMacro',
            ],
            'Services'         => [
                'icon',
                'servicecumulatedstatusicon',
                'details',
                'byUuid',
                'loadServicesByStringCake4',
                'loadServicesByContainerIdCake4'
            ],
            'Graphgenerators'  => [
                'fetchGraphData',
                'loadServicesByHostId',
                'loadPerfDataStructures',
                'loadServiceruleFromService',
                'getPerfdataByUuid'
            ],
            'Dashboards'       => [
                'index',
                'getWidgetsForTab',
                'dynamicDirective',
                'welcomeWidget',
                'saveGrid',
                'addWidgetToTab',
                'removeWidgetFromTab',
                'saveTabOrder',
                'addNewTab',
                'renameDashboardTab',
                'deleteDashboardTab',
                'startSharing',
                'stopSharing',
                'getSharedTabs',
                'createFromSharedTab',
                'checkForUpdates',
                'neverPerformUpdates',
                'updateSharedTab',
                'renameWidget',
                'lockOrUnlockTab',
                'restoreDefault',
                'hostsPiechartWidget',
                'hostsPiechart180Widget',
                'servicesPiechartWidget',
                'servicesPiechart180Widget',
                'hostsStatusListWidget',
                'servicesStatusListWidget',
                'saveTabRotateInterval',
                'parentOutagesWidget',
                'hostsDowntimeWidget',
                'servicesDowntimeWidget',
                'noticeWidget',
                'trafficLightWidget',
                'getServiceWithStateById',
                'hostStatusOverviewWidget',
                'tachoWidget',
                'serviceStatusOverviewWidget'
            ],
            'Hosts'            => [
                'view',
                'icon',
                'hostservicelist',
                'loadParentHostsByString',
                'hoststatus',
                'byUuid'
            ],
            'Statistics'       => [
                'ask_anonymous_statistics'
            ],
            'Login'            => [
                'index',
                'login',
                'onetimetoken',
                'logout',
                'auth_required',
                'lock'
            ],
        ],
        'dependencies'   => [
            'Automaps'              => [
                'view' => ['loadServiceDetails'],
            ],
            'Browsers'              => [
                'index' => ['tenantBrowser', 'locationBrowser', 'nodeBrowser'],
            ],
            'Calendars'             => [
                'add'    => ['loadHolidays'],
                'edit'   => ['loadHolidays'],
                'delete' => ['mass_delete'],
            ],
            'Commands'              => [
                'index' => ['view'],
                'add'   => ['getConsoleWelcome'],
                'edit'  => ['getConsoleWelcome']
            ],
            'Timeperiods'           => [
                'index' => [
                    'view',
                    'loadTimeperiodsByContainerId'
                ],
            ],
            'Contactgroups'         => [
                'index' => ['view'],
                'add'   => ['loadContacts', 'loadContainers'],
                'edit'  => ['loadContacts', 'loadContainers'],
            ],
            'Contacts'              => [
                'index' => ['view'],
                'add'   => ['loadContainers', 'loadCommands', 'loadTimeperiods', 'loadLdapUserByString', 'loadUsersByContainerId'],
                'edit'  => ['loadContainers', 'loadCommands', 'loadTimeperiods', 'loadLdapUserByString', 'loadUsersByContainerId'],
            ],
            'Cronjobs'              => [
                'add'  => ['getTasks'],
                'edit' => ['getTasks'],
            ],
            'Currentstatereports'   => [
                'index' => ['createPdfReport', 'createHtmlReport'],
            ],
            'Downtimereports'       => [
                'index' => ['createPdfReport'],
            ],
            'Hostdependencies'      => [
                'index' => ['view'],
                'add'   => ['loadContainers', 'loadElementsByContainerId'],
                'edit'  => ['loadContainers', 'loadElementsByContainerId'],
            ],
            'Hostescalations'       => [
                'index' => ['view'],
                'add'   => ['loadContainers', 'loadElementsByContainerId'],
                'edit'  => ['loadContainers', 'loadElementsByContainerId']
            ],
            'Hostgroups'            => [
                'index'    => ['listToPdf', 'view', 'loadHostgroupsByString', 'loadHosgroupsByContainerId'],
                'add'      => ['loadHosts', 'loadHosttemplates', 'loadContainers', 'addHostsToHostgroup', 'append'],
                'edit'     => ['loadHosts', 'loadHosttemplates', 'loadContainers', 'addHostsToHostgroup', 'append'],
                'extended' => ['loadHostgroupWithHostsById', 'listToPdf']
            ],
            'Hosts'                 => [
                'index'      => ['listToPdf', 'loadHostsByContainerId', 'loadHostsByString', 'loadHostById'],
                'delete'     => ['mass_delete'],
                'deactivate' => ['mass_deactivate'],
                'browser'    => ['longOutputByUuid', 'getGrafanaIframeUrlForDatepicker'],
                'add'        => ['loadContainers', 'loadCommands', 'loadElementsByContainerId', 'loadHosttemplate', 'runDnsLookup', 'loadCommandArguments'],
                'edit'       => ['loadContainers', 'loadCommands', 'loadElementsByContainerId', 'loadHosttemplate', 'runDnsLookup', 'loadCommandArguments'],
            ],
            'Hosttemplates'         => [
                'index' => ['view'],
                'add'   => ['loadElementsByContainerId', 'loadContainers', 'loadCommands', 'loadCommandArguments'],
                'edit'  => ['loadElementsByContainerId', 'loadContainers', 'loadCommands', 'loadCommandArguments'],
            ],
            'Instantreports'        => [
                'index' => ['createPdfReport'],
                'add'   => ['loadContainers']
            ],
            'Macros'                => [
                'index' => ['add', 'edit', 'delete', 'getAvailableMacroNames'],
            ],
            'Registers'             => [
                'index' => ['checkLicense'],
            ],
            'Servicedependencies'   => [
                'index' => ['view'],
                'add'   => ['loadElementsByContainerId'],
                'edit'  => ['loadElementsByContianerId'],
            ],
            'Serviceescalations'    => [
                'index' => ['view'],
                'add'   => ['loadContainers', 'loadElementsByContainerId'],
                'edit'  => ['loadContainers', 'loadElementsByContainerId']
            ],
            'Servicegroups'         => [
                'index'    => ['listToPdf', 'view', 'loadServicegroupsByContainerId', 'loadServicegroupsByString'],
                'add'      => ['loadServicetemplates', 'loadContainers', 'addServicesToServicegroup', 'append'],
                'edit'     => ['loadServicetemplates', 'loadContainers', 'addServicesToServicegroup', 'append'],
                'delete'   => ['mass_delete'],
                'extended' => ['loadServicegroupWithServicesById']
            ],
            'Services'              => [
                'deactivate'  => ['mass_deactivate'],
                'index'       => ['serviceByHostId', 'listToPdf', 'view', 'loadServicesByContainerId', 'loadServicesByString'],
                'browser'     => ['servicesByHostId', 'longOutputByUuid'],
                'add'         => ['loadElementsByHostId', 'loadServicetemplate', 'loadCommands', 'loadCommandArguments', 'loadEventhandlerCommandArguments'],
                'edit'        => ['loadElementsByHostId', 'loadServicetemplate', 'loadCommands', 'loadCommandArguments', 'loadEventhandlerCommandArguments'],
                'serviceList' => ['deleted']
            ],
            'Servicetemplategroups' => [
                'index' => ['loadServicetemplatesByContainerId', 'view', 'allocateToMatchingHostgroup', 'loadServicetemplategroupsByString', 'loadHostgroupsByString'],
                'add'   => ['loadContainers', 'loadServicetemplatesByContainerId', 'append', 'loadServicetemplategroupsByString'],
                'edit'  => ['loadContainers', 'loadServicetemplatesByContainerId', 'append', 'loadServicetemplategroupsByString']
            ],
            'Servicetemplates'      => [
                'index' => ['view', 'loadServicetemplatesByContainerId', 'addServicetemplatesToServicetemplategroup'],
                'add'   => ['loadContainers', 'loadCommands', 'loadCommandArguments', 'loadEventhandlerCommandArguments', 'loadElementsByContainerId'],
                'edit'  => ['loadContainers', 'loadCommands', 'loadCommandArguments', 'loadEventhandlerCommandArguments', 'loadElementsByContainerId'],
            ],
            'Users'                 => [
                'index' => ['view', 'loadUsersByContainerId'],
                'add'   => ['addFromLdap', 'loadLdapUserByString', 'loadDateformats'],
                'edit'  => ['resetPassword'],
            ],
            'Tenants'               => [
                'index'  => ['view'],
                'delete' => ['mass_delete'],
            ],
            'Downtimes'             => [
                'delete' => ['mass_delete'],
            ],
            'Administrators'        => [
                'debug' => ['testMail', 'querylog'],
            ],
            'Exports'               => [
                'index' => ['broadcast', 'launchExport', 'verifyConfig', 'saveInstanceConfigSyncSelection'],
            ],
            'Containers'            => [
                'index' => ['view', 'nest'],
            ],
            'Locations'             => [
                'index' => ['view'],
                'add'   => ['loadContainers'],
                'edit'  => ['loadContainers']
            ],
            /*'Devicegroups' => [
                'index' => ['view'],
            ],*/
            'Usergroups'            => [
                'index' => ['view'],
            ],
            'Backups'               => [
                'index' => ['checkBackupFinished'],
            ],
            'Notifications'         => [
                'index' => ['services'],
            ],
            'Statusmaps'            => [
                'index' => [
                    'hostAndServicesSummaryStatus'
                ]
            ],
            'Statistics'            => [
                'index' => ['saveStatisticDecision']
            ],
            'ConfigurationFiles'    => [
                'edit' => ['NagiosCfg', 'AfterExport', 'NagiosModuleConfig', 'phpNSTAMaster', 'DbBackend', 'PerfdataBackend', 'GraphingDocker', 'StatusengineCfg', 'GraphiteWeb', 'restorDefault', 'dynamicDirective']
            ]
        ],
        'roles_rights'   => [
            'Administrator' => ['*'],
            'Viewer'        => [
                'Acknowledgements'      => ['service', 'host'],
                'Administrators'        => ['index'],
                'Automaps'              => ['index'],
                'Browsers'              => ['index'],
                'Calendars'             => ['index'],
                'Category'              => ['index'],
                'Changelogs'            => ['index'],
                'Commands'              => ['index', 'hostchecks', 'notifications', 'handler'],
                'Contactgroups'         => ['index'],
                'Contacts'              => ['index'],
                'Containers'            => ['index'],
                'Cronjobs'              => ['index'],
                'Currentstatereports'   => ['index'],
                'DeletedHosts'          => ['index'],
                'Documentations'        => ['view', 'wiki'],
                'Downtimereports'       => ['index', 'host', 'service'],
                'Exports'               => ['index'],
                'GraphCollections'      => ['index', 'display'],
                'Hostchecks'            => ['index'],
                'Hostdependencies'      => ['index'],
                'Hostescalations'       => ['index'],
                'Hostgroups'            => ['index', 'extended'],
                'Hosts'                 => ['index', 'notMonitored', 'disabled', 'browser'],
                'Hosttemplates'         => ['index', 'usedBy'],
                'Locations'             => ['index'],
                'Logentries'            => ['index'],
                'Login'                 => ['index', 'login', 'onetimetoken', 'logout', 'auth_required', 'lock'],
                'Macros'                => ['index'],
                'Nagiostats'            => ['index'],
                'Notifications'         => ['index', 'hostNotification', 'serviceNotification'],
                'Packetmanager'         => ['index'],
                'Proxy'                 => ['index'],
                'Qr'                    => ['index'],
                'Registers'             => ['index'],
                'Servicechecks'         => ['index'],
                'Servicedependencies'   => ['index'],
                'Serviceescalations'    => ['index'],
                'Servicegroups'         => ['index'],
                'Services'              => ['index', 'notMonitored', 'disabled', 'browser', 'serviceList'],
                'Servicetemplategroups' => ['index'],
                'Servicetemplates'      => ['index'],
                'Statehistories'        => ['service', 'host'],
                'Statusmaps'            => ['index', 'hostAndServicesSummaryStatus'],
                'Systemfailures'        => ['index'],
                'Systemsettings'        => ['index', 'host', 'service', 'hostgroup', 'node'],
                'Tenants'               => ['index'],
                'Timeperiods'           => ['index'],
                'Usergroups'            => ['index'],
                'Users'                 => ['index'],
                'Backups'               => ['index'],
                'Supports'              => ['index', 'issue'],
                'Instantreports'        => ['index', 'sendEmailsList']
            ]

        ]
    ],
];
