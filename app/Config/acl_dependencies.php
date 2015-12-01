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
		'AppController' => ['getNamedParameter', 'isAuthorized', 'flashBack', 'setFlash', 'serviceResponse', 'allowedByContainerId', 'render403'],
		'always_allowed' => [
			'Commands' => [
				'sortByCommandType'
			],
			'Containers' => [
				'byTenantForSelect', 'byTenant'
			],
			'Downtimes' => [
				'validateDowntimeInputFromBrowser'
			],
			'Forward' => [
				'index'
			],
			'GraphCollections' => [
				'loadCollectionGraphData'
			],
			'Packetmanager' => [
				'getPackets'
			],
			'Profile' => [
				'edit',
				'deleteImage'
			],
			'Proxy' => [
				'getSettings'
			],
			'Rrds' => [
				'index',
				'ajax'
			],
			'Search' => [
				'index',
				'hostMacro',
				'serviceMacro'
			],
			'Services' => [
				'grapherSwitch',
				'grapher',
				'grapherTemplate',
				'grapherZoom',
				'grapherZoomTemplate',
				'createGrapherErrorPng',
			],
			'Statusmaps' => [
				'getHostsAndConnections',
				'clickHostStatus'
			],
			'Graphgenerators' => [
				'fetchGraphData',
				'loadServicesByHostId',
				'loadPerfDataStructures',
				'loadServiceruleFromService',
			],
			'Dashboards' => [
				'index',
				'next',
				'add',
				'createTab',
				'createTabFromSharing',
				'updateSharedTab',
				'disableUpdate',
				'renameTab',
				'deleteTab',
				'restoreDefault',
				'updateTitle',
				'updateColor',
				'updatePosition',
				'deleteWidget',
				'updateTabPosition',
				'saveTabRotationInterval',
				'startSharing',
				'stopSharing',
				'refresh',
				'saveStatuslistSettings',
				'saveTrafficLightService',
				'getTachoPerfdata',
				'saveTachoConfig',
			],
			'Hosts' => [
				'view'
			]
		],
		'dependencies' => [
			'Automaps' => [
				'view' => ['loadServiceDetails']
			],
			'Browsers' => [
				'index' => ['tenantBrowser', 'locationBrowser', 'devicegroupBrowser', 'nodeBrowser']
			],
			'Calendars' => [
				'add' => ['loadHolidays'],
				'edit' => ['loadHolidays'],
				'delete' => ['mass_delete']
			],
			'Commands' => [
				'index' => ['view'],
				'add' => ['addCommandArg', 'loadMacros'],
				'edit' => ['addCommandArg', 'loadMacros'],
				'delete' => ['mass_delete']
			],
			'Timeperiods' => [
				'index' => ['view'],
				'delete' => ['mass_delete'],
			],
			'Contactgroups' => [
				'add' => ['loadContacts'],
				'edit' => ['loadContacts'],
				'delete' => ['mass_delete']
			],
			'Contacts' => [
				'add' => ['loadTimeperiods'],
				'edit' => ['loadTimeperiods']
			],
			'Cronjobs' => [
				'add' => ['loadTasksByPlugin'],
				'edit' => ['loadTasksByPlugin']
			],
			'Currentstatereports' => [
				'index' => ['createPdfReport']
			],
			'Downtimereports' => [
				'index' => ['createPdfReport']
			],
			'Graphgenerators' => [
				'listing' => ['saveGraphTemplate', 'loadGraphTemplate']
			],
			'Hostdependencies' => [
				'add' => ['loadElementsByContainerId'],
				'edit' => ['loadElementsByContainerId']
			],
			'Hostescalations' => [
				'add' => ['loadElementsByContainerId'],
				'edit' => ['loadElementsByContainerId']
			],
			'Hostgroups' => [
				'index' => ['listToPdf', 'view'],
				'extended' => ['listToPdf'],
				'add' => ['loadHosts', 'mass_add'],
				'edit' => ['loadHosts'],
				'delete' => ['mass_delete']
			],
			'Hosts' => [
				'index' => ['getHostByAjax', 'listToPdf'],
				'delete' => ['mass_delete'],
				'deactivate' => ['mass_deactivate'],
				'browser' => ['longOutputByUuid'],
				'add' => ['gethostbyname', 'gethostbyaddr', 'loadHosttemplate', 'addCustomMacro', 'loadTemplateMacros', 'loadParametersByCommandId', 'loadArguments', 'loadArgumentsAdd', 'loadHosttemplatesArguments', 'addParentHosts', 'loadElementsByContainerId'],
				'edit' => ['gethostbyname', 'gethostbyaddr', 'loadHosttemplate', 'addCustomMacro', 'loadTemplateMacros', 'loadParametersByCommandId', 'loadArguments', 'loadArgumentsAdd', 'loadHosttemplatesArguments', 'addParentHosts', 'loadElementsByContainerId']
			],
			'Hosttemplates' => [
				'index' => ['view'],
				'add' => ['addCustomMacro', 'loadArguments', 'loadArgumentsAdd', 'loadElementsByContainerId'],
				'edit' => ['addCustomMacro', 'loadArguments', 'loadArgumentsAdd', 'loadElementsByContainerId']
			],
			'Instantreports' => [
				'index' => ['createPdfReport', 'expandServices']
			],
			'Macros' => [
				'index' => ['addMacro']
			],
			'Registers' => [
				'index' => ['check']
			],
			'Servicedependencies' => [
				'add' => ['loadElementsByContainerId'],
				'edit' => ['loadElementsByContianerId']
			],
			'Serviceescalations' => [
				'add' => ['loadElementsByContainerId'],
				'edit' => ['loadElementsByContainerId']
			],
			'Servicegroups' => [
				'index' => ['listToPdf'],
				'add' => ['loadServices', 'mass_add'],
				'edit' => ['loadServices'],
				'delete' => ['mass_delete'],
			],
			'Services' => [
				'deactivate' => ['mass_deactivate'],
				'index' => ['serviceByHostId', 'listToPdf', 'loadServices', 'view'],
				'browser' => ['servicesByHostId', 'longOutputByUuid'],
				'delete' => ['mass_delete'],
				'add' => ['loadContactsAndContactgroups', 'loadParametersByCommandId', 'loadNagParametersByCommandId', 'loadArgumentsAdd', 'loadServicetemplatesArguments', 'loadTemplateData', 'addCustomMacro', 'loadTemplateMacros'],
				'edit' => ['loadContactsAndContactgroups', 'loadParametersByCommandId', 'loadNagParametersByCommandId', 'loadArgumentsAdd', 'loadServicetemplatesArguments', 'loadTemplateData', 'addCustomMacro', 'loadTemplateMacros']
			],
			'Servicetemplategroups' => [
				'index' => ['getHostsByHostgroupByAjax', 'loadServicetemplatesByContainerId', 'view'],
				'add' => ['getHostsByHostgroupByAjax', 'loadServicetemplatesByContainerId'],
				'edit' => ['getHostsByHostgroupByAjax', 'loadServicetemplatesByContainerId']
			],
			'Servicetemplates' => [
				'index' => ['view'],
				'add' => ['loadArguments', 'loadContactsAndContactgroups', 'loadArgumentsAdd', 'loadNagArgumentsAdd', 'addCustomMacro', 'loadParametersByCommandId', 'loadNagParametersByCommandId', 'loadElementsByContainerId'],
				'edit' => ['loadArguments', 'loadContactsAndContactgroups', 'loadArgumentsAdd', 'loadNagArgumentsAdd', 'addCustomMacro', 'loadParametersByCommandId', 'loadNagParametersByCommandId', 'loadElementsByContainerId'],
			],
			'Users' => [
				'add' => ['addFromLdap'],
				'edit' => ['resetPassword']
			],
			'Tenants' => [
				'delete' => ['mass_delete'],
			],
			'Downtimes' => [
				'host' => ['index'],
				'service' => ['index'],
			],
			'Administrators' => [
				'debug' => ['testMail'],
			],
			'Exports' => [
				'index' => ['broadcast', 'launchExport', 'verifyConfig'],
			],
		]
	]
];
