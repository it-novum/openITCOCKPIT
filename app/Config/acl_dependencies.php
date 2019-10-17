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
        'AppController'  => ['getNamedParameter', 'isAuthorized', 'flashBack', 'setFlash', 'serviceResponse', 'allowedByContainerId', 'render403', 'checkForUpdates'],
        'always_allowed' => [
            'Angular'          => [
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
                'nested_list',
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
                'getHostAndServiceStateSummaryIcon'
            ],
            'Automaps'         => [
                'icon'
            ],
            'Commands'         => [
                'sortByCommandType',
            ],
            'Containers'       => [
                'byTenantForSelect', 'byTenant', 'loadContainersForAngular'
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
                'details'
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
                'loadParentHostsById',
                'hoststatus'
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
                'index'  => ['view'],
                'add'    => ['addCommandArg', 'loadMacros'],
                'edit'   => ['addCommandArg', 'loadMacros'],
                'delete' => ['mass_delete'],
            ],
            'Timeperiods'           => [
                'index'  => [
                    'view',
                    'loadTimeperiodsByContainerId'
                ],
                'delete' => ['mass_delete'],
            ],
            'Contactgroups'         => [
                'index'  => ['view'],
                'add'    => ['loadContacts'],
                'edit'   => ['loadContacts'],
                'delete' => ['mass_delete'],
            ],
            'Contacts'              => [
                'index' => ['view'],
                'add'   => ['loadTimeperiods', 'addCustomMacro', 'loadLdapUserByString', 'loadUsersByContainerId'],
                'edit'  => ['loadTimeperiods', 'addCustomMacro', 'loadLdapUserByString', 'loadUsersByContainerId'],
            ],
            'Cronjobs'              => [
                'add'  => ['loadTasksByPlugin'],
                'edit' => ['loadTasksByPlugin'],
            ],
            'Currentstatereports'   => [
                'index' => ['createPdfReport', 'createHtmlReport'],
            ],
            'Downtimereports'       => [
                'index' => ['createPdfReport'],
            ],
            'Hostdependencies'      => [
                'index' => ['view'],
                'add'   => ['loadElementsByContainerId'],
                'edit'  => ['loadElementsByContainerId'],
            ],
            'Hostescalations'       => [
                'index' => ['view'],
                'add'   => ['loadElementsByContainerId'],
                'edit'  => ['loadElementsByContainerId'],
            ],
            'Hostgroups'            => [
                'index'    => ['listToPdf', 'view', 'loadHostgroupsByString', 'loadHosgroupsByContainerId'],
                'add'      => ['loadHosts', 'mass_add', 'loadHosttemplates', 'loadContainers'],
                'edit'     => ['loadHosts', 'loadHosttemplates', 'loadContainers'],
                'extended' => ['loadHostgroupWithHostsById', 'listToPdf']
            ],
            'Hosts'                 => [
                'index'      => ['getHostByAjax', 'listToPdf', 'ajaxList', 'loadHostsByContainerId', 'loadHostsByString', 'loadHostById', 'allocateServiceTemplateGroup', 'getServiceTemplatesfromGroup'],
                'delete'     => ['mass_delete'],
                'deactivate' => ['mass_deactivate'],
                'browser'    => ['longOutputByUuid', 'getGrafanaIframeUrlForDatepicker'],
                'add'        => ['gethostbyname', 'gethostbyaddr', 'loadHosttemplate', 'addCustomMacro', 'loadTemplateMacros', 'loadParametersByCommandId', 'loadArguments', 'loadArgumentsAdd', 'loadHosttemplatesArguments', 'addParentHosts', 'loadElementsByContainerId', 'getSharingContainers'],
                'edit'       => ['gethostbyname', 'gethostbyaddr', 'loadHosttemplate', 'addCustomMacro', 'loadTemplateMacros', 'loadParametersByCommandId', 'loadArguments', 'loadArgumentsAdd', 'loadHosttemplatesArguments', 'addParentHosts', 'loadElementsByContainerId', 'getSharingContainers'],
            ],
            'Hosttemplates'         => [
                'index' => ['view'],
                'add'   => ['addCustomMacro', 'loadArguments', 'loadArgumentsAdd', 'loadElementsByContainerId'],
                'edit'  => ['addCustomMacro', 'loadArguments', 'loadArgumentsAdd', 'loadElementsByContainerId'],
            ],
            'Instantreports'        => [
                'index' => ['createPdfReport'],
                'add'   => ['loadContainers']
            ],
            'Macros'                => [
                'index' => ['addMacro'],
            ],
            'Registers'             => [
                'index' => ['check'],
            ],
            'Servicedependencies'   => [
                'index' => ['view'],
                'add'   => ['loadElementsByContainerId'],
                'edit'  => ['loadElementsByContianerId'],
            ],
            'Serviceescalations'    => [
                'index' => ['view'],
                'add'   => ['loadElementsByContainerId'],
                'edit'  => ['loadElementsByContainerId'],
            ],
            'Servicegroups'         => [
                'index'    => ['listToPdf', 'view', 'loadServicegroupsByContainerId', 'loadServicegroupsByString'],
                'add'      => ['loadServices', 'mass_add', 'loadServicetemplates', 'loadContainers'],
                'edit'     => ['loadServices', 'loadServicetemplates'],
                'delete'   => ['mass_delete'],
                'extended' => ['loadServicegroupWithServicesById']
            ],
            'Services'              => [
                'deactivate'  => ['mass_deactivate'],
                'index'       => ['serviceByHostId', 'listToPdf', 'loadServices', 'view', 'loadServicesByContainerId', 'loadServicesByString', 'getSelectedServices'],
                'browser'     => ['servicesByHostId', 'longOutputByUuid'],
                'add'         => ['loadContactsAndContactgroups', 'loadParametersByCommandId', 'loadNagParametersByCommandId', 'loadArgumentsAdd', 'loadServicetemplatesArguments', 'loadTemplateData', 'addCustomMacro', 'loadTemplateMacros', 'loadElementsByHostId'],
                'edit'        => ['loadContactsAndContactgroups', 'loadParametersByCommandId', 'loadNagParametersByCommandId', 'loadArgumentsAdd', 'loadServicetemplatesArguments', 'loadTemplateData', 'addCustomMacro', 'loadTemplateMacros', 'loadElementsByHostId'],
                'serviceList' => ['deleted']
            ],
            'Servicetemplategroups' => [
                'index' => ['getHostsByHostgroupByAjax', 'loadServicetemplatesByContainerId', 'view', 'allocateToMatchingHostgroup'],
                'add'   => ['getHostsByHostgroupByAjax', 'loadServicetemplatesByContainerId'],
                'edit'  => ['getHostsByHostgroupByAjax', 'loadServicetemplatesByContainerId'],
            ],
            'Servicetemplates'      => [
                'index' => ['view', 'loadUsersByContainerId', 'loadServicetemplatesByContainerId', 'assignGroup'],
                'add'   => ['loadArguments', 'loadContactsAndContactgroups', 'loadArgumentsAdd', 'loadNagArgumentsAdd', 'addCustomMacro', 'loadParametersByCommandId', 'loadNagParametersByCommandId', 'loadElementsByContainerId'],
                'edit'  => ['loadArguments', 'loadContactsAndContactgroups', 'loadArgumentsAdd', 'loadNagArgumentsAdd', 'addCustomMacro', 'loadParametersByCommandId', 'loadNagParametersByCommandId', 'loadElementsByContainerId'],
            ],
            'Users'                 => [
                'index' => ['view', 'loadUsersByContainerId'],
                'add'   => ['addFromLdap', 'loadLdapUserByString'],
                'edit'  => ['resetPassword'],
            ],
            'Tenants'               => [
                'index'  => ['view'],
                'delete' => ['mass_delete'],
            ],
            'Downtimes'             => [
                'host'    => ['index'],
                'service' => ['index'],
                'delete'  => ['mass_delete'],
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
            'ConfigurationFiles' => [
                'edit' => ['NagiosCfg', 'AfterExport', 'NagiosModuleConfig', 'phpNSTAMaster', 'DbBackend', 'PerfdataBackend', 'GraphingDocker', 'StatusengineCfg', 'GraphiteWeb', 'restorDefault']
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
                'Documentations'        => ['index', 'view', 'wiki'],
                'Downtimereports'       => ['index', 'host', 'service'],
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
                'Systemdowntimes'       => ['index'],
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
