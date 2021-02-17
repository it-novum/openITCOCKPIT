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

use Acl\Model\Table\AcosTable;

/**
 * Class AclDependencies
 * @package App
 */
class AclDependencies {

    /**
     * Hold a list of controllers and actions which
     * should always be allowed and could not be disabled by the user
     *
     * For Example:
     * PagesController::paginator()
     * PagesController::csrf()
     * Users::login()
     * Users::logout()
     *
     * @var array
     */
    private $allow = [];

    /**
     * Controller actions that depends on other controller actions.
     * This often happens when using a lot of Ajax in your frontend.
     *
     * For example:
     * Users::edit() make an Ajax call to Users::loadUsergroups() etc...
     *
     * @var array
     */
    private $dependencies = [];

    /**
     * Action names that should be ignored and not displayed by the frontend
     * @var array
     */
    private $ignore = [
        'initializePluginTables'
    ];

    /**
     * AclDependencies constructor.
     */
    public function __construct() {
        // Add actions that should always be allowed.
        $this
            ->allow('Angular', 'index')
            ->allow('Angular', 'paginator')
            ->allow('Angular', 'scroll')
            ->allow('Angular', 'mass_delete')
            ->allow('Angular', 'mass_deactivate')
            ->allow('Angular', 'confirm_delete')
            ->allow('Angular', 'confirm_deactivate')
            ->allow('Angular', 'mass_activate')
            ->allow('Angular', 'user_timezone')
            ->allow('Angular', 'version_check')
            ->allow('Angular', 'menustats')
            ->allow('Angular', 'statuscount')
            ->allow('Angular', 'menu')
            ->allow('Angular', 'menuControl')
            ->allow('Angular', 'topSearch')
            ->allow('Angular', 'sidebar')
            ->allow('Angular', 'websocket_configuration')
            ->allow('Angular', 'push_configuration')
            ->allow('Angular', 'export')
            ->allow('Angular', 'not_found')
            ->allow('Angular', 'forbidden')
            ->allow('Angular', 'executing')
            ->allow('Angular', 'acknowledge_service')
            ->allow('Angular', 'downtime_service')
            ->allow('Angular', 'reschedule_host')
            ->allow('Angular', 'downtime_host')
            ->allow('Angular', 'acknowledge_host')
            ->allow('Angular', 'enable_host_notifications')
            ->allow('Angular', 'disable_host_notifications')
            ->allow('Angular', 'system_health')
            ->allow('Angular', 'getDowntimeData')
            ->allow('Angular', 'mass_delete_host_downtimes')
            ->allow('Angular', 'mass_delete_service_downtimes')
            ->allow('Angular', 'submit_host_result')
            ->allow('Angular', 'disable_host_flap_detection')
            ->allow('Angular', 'enable_host_flap_detection')
            ->allow('Angular', 'send_host_notification')
            ->allow('Angular', 'submit_service_result')
            ->allow('Angular', 'disable_service_flap_detection')
            ->allow('Angular', 'enable_service_flap_detection')
            ->allow('Angular', 'send_service_notification')
            ->allow('Angular', 'enable_service_notifications')
            ->allow('Angular', 'disable_service_notifications')
            ->allow('Angular', 'getPieChart')
            ->allow('Angular', 'getHalfPieChart')
            ->allow('Angular', 'getCumulatedHostAndServiceStateIcon')
            ->allow('Angular', 'getHostAndServiceStateSummaryIcon')
            ->allow('Angular', 'macros')
            ->allow('Angular', 'ldap_configuration')
            ->allow('Angular', 'priority')
            ->allow('Angular', 'intervalInput')
            ->allow('Angular', 'intervalInputWithDiffer')
            ->allow('Angular', 'humanTime')
            ->allow('Angular', 'template_diff')
            ->allow('Angular', 'template_diff_button')
            ->allow('Angular', 'queryhandler')
            ->allow('Angular', 'hostBrowserMenu')
            ->allow('Angular', 'serviceBrowserMenu')
            ->allow('Angular', 'durationInput')
            ->allow('Angular', 'calendar')
            ->allow('Angular', 'reload_required')
            ->allow('Angular', 'colorpicker')
            ->allow('Angular', 'popover_graph')
            ->allow('Angular', 'thresholds');

        $this
            ->allow('Agentconnector', 'register_agent')
            ->allow('Agentconnector', 'submit_checkdata');

        $this
            ->allow('Automaps', 'icon')
            ->allow('Automaps', 'loadContainers');

        $this
            ->allow('Calendars', 'loadCalendarsByContainerId');

        $this
            ->allow('Containers', 'byTenantForSelect')
            ->allow('Containers', 'byTenant')
            ->allow('Containers', 'loadContainersForAngular')
            ->allow('Containers', 'loadContainers')
            ->allow('Containers', 'loadContainersByContainerId')
            ->allow('Containers', 'loadSatellitesByContainerIds');

        $this
            ->allow('Downtimes', 'validateDowntimeInputFromAngular')
            ->allow('Downtimes', 'icon');

        $this
            ->allow('Packetmanager', 'getPackets');

        $this
            ->allow('Profile', 'edit')
            ->allow('Profile', 'changePassword')
            ->allow('Profile', 'upload_profile_icon')
            ->allow('Profile', 'deleteImage')
            ->allow('Profile', 'apikey')
            ->allow('Profile', 'edit_apikey')
            ->allow('Profile', 'delete_apikey')
            ->allow('Profile', 'create_apikey')
            ->allow('Profile', 'updateI18n');

        $this
            ->allow('Proxy', 'getSettings');

        $this
            ->allow('Services', 'icon')
            ->allow('Services', 'servicecumulatedstatusicon')
            ->allow('Services', 'details')
            ->allow('Services', 'byUuid')
            ->allow('Services', 'loadServicesByStringCake4')
            ->allow('Services', 'loadServicesByContainerIdCake4');

        $this
            ->allow('Graphgenerators', 'getPerfdataByUuid');

        $this
            ->allow('Dashboards', 'index')
            ->allow('Dashboards', 'getWidgetsForTab')
            ->allow('Dashboards', 'dynamicDirective')
            ->allow('Dashboards', 'welcomeWidget')
            ->allow('Dashboards', 'saveGrid')
            ->allow('Dashboards', 'addWidgetToTab')
            ->allow('Dashboards', 'removeWidgetFromTab')
            ->allow('Dashboards', 'saveTabOrder')
            ->allow('Dashboards', 'addNewTab')
            ->allow('Dashboards', 'renameDashboardTab')
            ->allow('Dashboards', 'deleteDashboardTab')
            ->allow('Dashboards', 'startSharing')
            ->allow('Dashboards', 'stopSharing')
            ->allow('Dashboards', 'getSharedTabs')
            ->allow('Dashboards', 'createFromSharedTab')
            ->allow('Dashboards', 'checkForUpdates')
            ->allow('Dashboards', 'neverPerformUpdates')
            ->allow('Dashboards', 'updateSharedTab')
            ->allow('Dashboards', 'renameWidget')
            ->allow('Dashboards', 'lockOrUnlockTab')
            ->allow('Dashboards', 'restoreDefault')
            ->allow('Dashboards', 'hostsPiechartWidget')
            ->allow('Dashboards', 'hostsPiechart180Widget')
            ->allow('Dashboards', 'servicesPiechartWidget')
            ->allow('Dashboards', 'servicesPiechart180Widget')
            ->allow('Dashboards', 'hostsStatusListWidget')
            ->allow('Dashboards', 'servicesStatusListWidget')
            ->allow('Dashboards', 'saveTabRotateInterval')
            ->allow('Dashboards', 'parentOutagesWidget')
            ->allow('Dashboards', 'hostsDowntimeWidget')
            ->allow('Dashboards', 'servicesDowntimeWidget')
            ->allow('Dashboards', 'noticeWidget')
            ->allow('Dashboards', 'trafficLightWidget')
            ->allow('Dashboards', 'getServiceWithStateById')
            ->allow('Dashboards', 'hostStatusOverviewWidget')
            ->allow('Dashboards', 'tachoWidget')
            ->allow('Dashboards', 'serviceStatusOverviewWidget')
            ->allow('Dashboards', 'websiteWidget');

        $this
            ->allow('Hosts', 'view')
            ->allow('Hosts', 'icon')
            ->allow('Hosts', 'hostservicelist')
            ->allow('Hosts', 'loadParentHostsByString')
            ->allow('Hosts', 'hoststatus')
            ->allow('Hosts', 'byUuid');

        $this
            ->allow('Statistics', 'ask_anonymous_statistics');

        $this
            ->allow('Pages', 'index');

        $this
            ->allow('Users', 'login')
            ->allow('Users', 'logout')
            ->allow('Users', 'getLocaleOptions');

        ///////////////////////////////
        //    Add dependencies       //
        //////////////////////////////
        $this
            ->dependency('Agentchecks', 'add', 'Agentchecks', 'loadServicetemplates')
            ->dependency('Agentchecks', 'edit', 'Agentchecks', 'loadServicetemplates');


        $this
            ->dependency('Agentconnector', 'wizard', 'Agentconnector', 'loadHostsByString')
            ->dependency('Agentconnector', 'wizard', 'Agentconnector', 'install')
            ->dependency('Agentconnector', 'wizard', 'Agentconnector', 'autotls')
            ->dependency('Agentconnector', 'overview', 'Agentconnector', 'pull')
            ->dependency('Agentconnector', 'overview', 'Agentconnector', 'push')
            ->dependency('Agentconnector', 'delete', 'Agentconnector', 'delete_push_agent');

        $this
            ->dependency('Automaps', 'add', 'Automaps', 'getMatchingHostAndServices')
            ->dependency('Automaps', 'edit', 'Automaps', 'getMatchingHostAndServices');


        $this
            ->dependency('Browsers', 'index', 'Browsers', 'tenantBrowser')
            ->dependency('Browsers', 'index', 'Browsers', 'locationBrowser')
            ->dependency('Browsers', 'index', 'Browsers', 'nodeBrowser');


        $this
            ->dependency('Calendars', 'add', 'Calendars', 'loadHolidays')
            ->dependency('Calendars', 'add', 'Calendars', 'loadCountryList')
            ->dependency('Calendars', 'edit', 'Calendars', 'loadHolidays')
            ->dependency('Calendars', 'edit', 'Calendars', 'loadCountryList');


        $this
            ->dependency('Commands', 'index', 'Commands', 'view');


        $this
            ->dependency('Timeperiods', 'index', 'Timeperiods', 'view')
            ->dependency('Timeperiods', 'index', 'Timeperiods', 'loadTimeperiodsByContainerId');


        $this
            ->dependency('Contactgroups', 'index', 'Contactgroups', 'view')
            ->dependency('Contactgroups', 'add', 'Contactgroups', 'loadContacts')
            ->dependency('Contactgroups', 'add', 'Contactgroups', 'loadContainers')
            ->dependency('Contactgroups', 'edit', 'Contactgroups', 'loadContacts')
            ->dependency('Contactgroups', 'edit', 'Contactgroups', 'loadContainers');


        $this
            ->dependency('Contacts', 'index', 'Contacts', 'view')
            ->dependency('Contacts', 'add', 'Contacts', 'loadContainers')
            ->dependency('Contacts', 'add', 'Contacts', 'loadCommands')
            ->dependency('Contacts', 'add', 'Contacts', 'loadTimeperiods')
            ->dependency('Contacts', 'add', 'Contacts', 'loadLdapUserByString')
            ->dependency('Contacts', 'add', 'Contacts', 'loadUsersByContainerId')
            ->dependency('Contacts', 'edit', 'Contacts', 'loadContainers')
            ->dependency('Contacts', 'edit', 'Contacts', 'loadCommands')
            ->dependency('Contacts', 'edit', 'Contacts', 'loadTimeperiods')
            ->dependency('Contacts', 'edit', 'Contacts', 'loadLdapUserByString')
            ->dependency('Contacts', 'edit', 'Contacts', 'loadUsersByContainerId');


        $this
            ->dependency('Cronjobs', 'add', 'Cronjobs', 'getTasks')
            ->dependency('Cronjobs', 'edit', 'Cronjobs', 'getTasks');


        $this
            ->dependency('Currentstatereports', 'index', 'Currentstatereports', 'createPdfReport')
            ->dependency('Currentstatereports', 'index', 'Currentstatereports', 'createHtmlReport');


        $this
            ->dependency('Downtimereports', 'index', 'Downtimereports', 'createPdfReport')
            ->dependency('Downtimereports', 'index', 'Downtimereports', 'hostsBarChart')
            ->dependency('Downtimereports', 'index', 'Downtimereports', 'hostAvailabilityOverview')
            ->dependency('Downtimereports', 'index', 'Downtimereports', 'serviceAvailabilityOverview');


        $this
            ->dependency('Hostdependencies', 'index', 'Hostdependencies', 'view')
            ->dependency('Hostdependencies', 'add', 'Hostdependencies', 'loadContainers')
            ->dependency('Hostdependencies', 'add', 'Hostdependencies', 'loadElementsByContainerId')
            ->dependency('Hostdependencies', 'edit', 'Hostdependencies', 'loadContainers')
            ->dependency('Hostdependencies', 'edit', 'Hostdependencies', 'loadElementsByContainerId');


        $this
            ->dependency('Hostescalations', 'index', 'Hostescalations', 'view')
            ->dependency('Hostescalations', 'add', 'Hostescalations', 'loadContainers')
            ->dependency('Hostescalations', 'add', 'Hostescalations', 'loadElementsByContainerId')
            ->dependency('Hostescalations', 'edit', 'Hostescalations', 'loadContainers')
            ->dependency('Hostescalations', 'edit', 'Hostescalations', 'loadElementsByContainerId');


        $this
            ->dependency('Hostgroups', 'index', 'Hostgroups', 'listToPdf')
            ->dependency('Hostgroups', 'index', 'Hostgroups', 'view')
            ->dependency('Hostgroups', 'index', 'Hostgroups', 'loadHostgroupsByString')
            ->dependency('Hostgroups', 'index', 'Hostgroups', 'loadHosgroupsByContainerId')
            ->dependency('Hostgroups', 'add', 'Hostgroups', 'loadHosts')
            ->dependency('Hostgroups', 'add', 'Hostgroups', 'loadHosttemplates')
            ->dependency('Hostgroups', 'add', 'Hostgroups', 'loadContainers')
            ->dependency('Hostgroups', 'add', 'Hostgroups', 'addHostsToHostgroup')
            ->dependency('Hostgroups', 'add', 'Hostgroups', 'append')
            ->dependency('Hostgroups', 'edit', 'Hostgroups', 'loadHosts')
            ->dependency('Hostgroups', 'edit', 'Hostgroups', 'loadHosttemplates')
            ->dependency('Hostgroups', 'edit', 'Hostgroups', 'loadContainers')
            ->dependency('Hostgroups', 'edit', 'Hostgroups', 'addHostsToHostgroup')
            ->dependency('Hostgroups', 'edit', 'Hostgroups', 'append')
            ->dependency('Hostgroups', 'extended', 'Hostgroups', 'loadHostgroupWithHostsById')
            ->dependency('Hostgroups', 'extended', 'Hostgroups', 'listToPdf');


        $this
            ->dependency('Hosts', 'index', 'Hosts', 'listToPdf')
            ->dependency('Hosts', 'index', 'Hosts', 'loadHostsByContainerId')
            ->dependency('Hosts', 'index', 'Hosts', 'loadHostsByString')
            ->dependency('Hosts', 'index', 'Hosts', 'loadHostById')
            ->dependency('Hosts', 'delete', 'Hosts', 'mass_delete')
            ->dependency('Hosts', 'deactivate', 'Hosts', 'mass_deactivate')
            ->dependency('Hosts', 'browser', 'Hosts', 'getGrafanaIframeUrlForDatepicker')
            ->dependency('Hosts', 'add', 'Hosts', 'loadContainers')
            ->dependency('Hosts', 'add', 'Hosts', 'loadCommands')
            ->dependency('Hosts', 'add', 'Hosts', 'loadElementsByContainerId')
            ->dependency('Hosts', 'add', 'Hosts', 'loadHosttemplate')
            ->dependency('Hosts', 'add', 'Hosts', 'runDnsLookup')
            ->dependency('Hosts', 'add', 'Hosts', 'loadCommandArguments')
            ->dependency('Hosts', 'edit', 'Hosts', 'loadContainers')
            ->dependency('Hosts', 'edit', 'Hosts', 'loadCommands')
            ->dependency('Hosts', 'edit', 'Hosts', 'loadElementsByContainerId')
            ->dependency('Hosts', 'edit', 'Hosts', 'loadHosttemplate')
            ->dependency('Hosts', 'edit', 'Hosts', 'runDnsLookup')
            ->dependency('Hosts', 'edit', 'Hosts', 'loadCommandArguments');


        $this
            ->dependency('Hosttemplates', 'index', 'Hosttemplates', 'view')
            ->dependency('Hosttemplates', 'add', 'Hosttemplates', 'loadElementsByContainerId')
            ->dependency('Hosttemplates', 'add', 'Hosttemplates', 'loadContainers')
            ->dependency('Hosttemplates', 'add', 'Hosttemplates', 'loadCommands')
            ->dependency('Hosttemplates', 'add', 'Hosttemplates', 'loadCommandArguments')
            ->dependency('Hosttemplates', 'edit', 'Hosttemplates', 'loadElementsByContainerId')
            ->dependency('Hosttemplates', 'edit', 'Hosttemplates', 'loadContainers')
            ->dependency('Hosttemplates', 'edit', 'Hosttemplates', 'loadCommands')
            ->dependency('Hosttemplates', 'edit', 'Hosttemplates', 'loadCommandArguments');


        $this
            ->dependency('Instantreports', 'index', 'Instantreports', 'createPdfReport')
            ->dependency('Instantreports', 'add', 'Instantreports', 'loadContainers')
            ->dependency('Instantreports', 'generate', 'Instantreports', 'hostAvailabilityPieChart')
            ->dependency('Instantreports', 'generate', 'Instantreports', 'serviceAvailabilityPieChart')
            ->dependency('Instantreports', 'generate', 'Instantreports', 'serviceAvailabilityBarChart');


        $this
            ->dependency('Macros', 'index', 'Macros', 'add')
            ->dependency('Macros', 'index', 'Macros', 'edit')
            ->dependency('Macros', 'index', 'Macros', 'delete')
            ->dependency('Macros', 'index', 'Macros', 'getAvailableMacroNames');


        $this
            ->dependency('Registers', 'index', 'Registers', 'checkLicense');


        $this
            ->dependency('Servicedependencies', 'index', 'Servicedependencies', 'view')
            ->dependency('Servicedependencies', 'add', 'Servicedependencies', 'loadElementsByContainerId')
            ->dependency('Servicedependencies', 'edit', 'Servicedependencies', 'loadElementsByContianerId');


        $this
            ->dependency('Serviceescalations', 'index', 'Serviceescalations', 'view')
            ->dependency('Serviceescalations', 'add', 'Serviceescalations', 'loadContainers')
            ->dependency('Serviceescalations', 'add', 'Serviceescalations', 'loadElementsByContainerId')
            ->dependency('Serviceescalations', 'edit', 'Serviceescalations', 'loadContainers')
            ->dependency('Serviceescalations', 'edit', 'Serviceescalations', 'loadElementsByContainerId');


        $this
            ->dependency('Servicegroups', 'index', 'Servicegroups', 'listToPdf')
            ->dependency('Servicegroups', 'index', 'Servicegroups', 'view')
            ->dependency('Servicegroups', 'index', 'Servicegroups', 'loadServicegroupsByContainerId')
            ->dependency('Servicegroups', 'index', 'Servicegroups', 'loadServicegroupsByString')
            ->dependency('Servicegroups', 'add', 'Servicegroups', 'loadServicetemplates')
            ->dependency('Servicegroups', 'add', 'Servicegroups', 'loadContainers')
            ->dependency('Servicegroups', 'add', 'Servicegroups', 'addServicesToServicegroup')
            ->dependency('Servicegroups', 'add', 'Servicegroups', 'append')
            ->dependency('Servicegroups', 'edit', 'Servicegroups', 'loadServicetemplates')
            ->dependency('Servicegroups', 'edit', 'Servicegroups', 'loadContainers')
            ->dependency('Servicegroups', 'edit', 'Servicegroups', 'addServicesToServicegroup')
            ->dependency('Servicegroups', 'edit', 'Servicegroups', 'append')
            ->dependency('Servicegroups', 'delete', 'Servicegroups', 'mass_delete')
            ->dependency('Servicegroups', 'extended', 'Servicegroups', 'loadServicegroupWithServicesById');


        $this
            ->dependency('Services', 'deactivate', 'Services', 'mass_deactivate')
            ->dependency('Services', 'index', 'Services', 'listToPdf')
            ->dependency('Services', 'index', 'Services', 'view')
            ->dependency('Services', 'index', 'Services', 'loadServicesByContainerId')
            ->dependency('Services', 'index', 'Services', 'loadServicesByString')
            ->dependency('Services', 'add', 'Services', 'loadElementsByHostId')
            ->dependency('Services', 'add', 'Services', 'loadServicetemplate')
            ->dependency('Services', 'add', 'Services', 'loadCommands')
            ->dependency('Services', 'add', 'Services', 'loadCommandArguments')
            ->dependency('Services', 'add', 'Services', 'loadEventhandlerCommandArguments')
            ->dependency('Services', 'edit', 'Services', 'loadElementsByHostId')
            ->dependency('Services', 'edit', 'Services', 'loadServicetemplate')
            ->dependency('Services', 'edit', 'Services', 'loadCommands')
            ->dependency('Services', 'edit', 'Services', 'loadCommandArguments')
            ->dependency('Services', 'edit', 'Services', 'loadEventhandlerCommandArguments')
            ->dependency('Services', 'serviceList', 'Services', 'deleted');


        $this
            ->dependency('Servicetemplategroups', 'index', 'Servicetemplategroups', 'loadServicetemplatesByContainerId')
            ->dependency('Servicetemplategroups', 'index', 'Servicetemplategroups', 'view')
            ->dependency('Servicetemplategroups', 'index', 'Servicetemplategroups', 'allocateToMatchingHostgroup')
            ->dependency('Servicetemplategroups', 'index', 'Servicetemplategroups', 'loadServicetemplategroupsByString')
            ->dependency('Servicetemplategroups', 'index', 'Servicetemplategroups', 'loadHostgroupsByString')
            ->dependency('Servicetemplategroups', 'add', 'Servicetemplategroups', 'loadContainers')
            ->dependency('Servicetemplategroups', 'add', 'Servicetemplategroups', 'loadServicetemplatesByContainerId')
            ->dependency('Servicetemplategroups', 'add', 'Servicetemplategroups', 'append')
            ->dependency('Servicetemplategroups', 'add', 'Servicetemplategroups', 'loadServicetemplategroupsByString')
            ->dependency('Servicetemplategroups', 'edit', 'Servicetemplategroups', 'loadContainers')
            ->dependency('Servicetemplategroups', 'edit', 'Servicetemplategroups', 'loadServicetemplatesByContainerId')
            ->dependency('Servicetemplategroups', 'edit', 'Servicetemplategroups', 'append')
            ->dependency('Servicetemplategroups', 'edit', 'Servicetemplategroups', 'loadServicetemplategroupsByString');


        $this
            ->dependency('Servicetemplates', 'index', 'Servicetemplates', 'view')
            ->dependency('Servicetemplates', 'index', 'Servicetemplates', 'loadServicetemplatesByContainerId')
            ->dependency('Servicetemplates', 'index', 'Servicetemplates', 'addServicetemplatesToServicetemplategroup')
            ->dependency('Servicetemplates', 'add', 'Servicetemplates', 'loadContainers')
            ->dependency('Servicetemplates', 'add', 'Servicetemplates', 'loadCommands')
            ->dependency('Servicetemplates', 'add', 'Servicetemplates', 'loadCommandArguments')
            ->dependency('Servicetemplates', 'add', 'Servicetemplates', 'loadEventhandlerCommandArguments')
            ->dependency('Servicetemplates', 'add', 'Servicetemplates', 'loadElementsByContainerId')
            ->dependency('Servicetemplates', 'edit', 'Servicetemplates', 'loadContainers')
            ->dependency('Servicetemplates', 'edit', 'Servicetemplates', 'loadCommands')
            ->dependency('Servicetemplates', 'edit', 'Servicetemplates', 'loadCommandArguments')
            ->dependency('Servicetemplates', 'edit', 'Servicetemplates', 'loadEventhandlerCommandArguments')
            ->dependency('Servicetemplates', 'edit', 'Servicetemplates', 'loadElementsByContainerId');

        $this
            ->dependency('Users', 'index', 'Users', 'view')
            ->dependency('Users', 'index', 'Users', 'loadUsersByContainerId')
            ->dependency('Users', 'index', 'Users', 'loadUsergroups')
            ->dependency('Users', 'add', 'Users', 'addFromLdap')
            ->dependency('Users', 'add', 'Users', 'loadLdapUserByString')
            ->dependency('Users', 'add', 'Users', 'loadDateformats')
            ->dependency('Users', 'add', 'Users', 'loadUsergroups')
            ->dependency('Users', 'add', 'Users', 'loadContainerRoles')
            ->dependency('Users', 'add', 'Users', 'loadContainerPermissions')
            ->dependency('Users', 'edit', 'Users', 'resetPassword')
            ->dependency('Users', 'edit', 'Users', 'loadDateformats')
            ->dependency('Users', 'edit', 'Users', 'loadUsergroups')
            ->dependency('Users', 'edit', 'Users', 'loadContainerRoles')
            ->dependency('Users', 'edit', 'Users', 'loadContainerPermissions');


        $this
            ->dependency('Tenants', 'index', 'Tenants', 'view')
            ->dependency('Tenants', 'delete', 'Tenants', 'mass_delete');


        $this
            ->dependency('Downtimes', 'delete', 'Downtimes', 'mass_delete');


        $this
            ->dependency('Administrators', 'debug', 'Administrators', 'testMail')
            ->dependency('Administrators', 'debug', 'Administrators', 'querylog');


        $this
            ->dependency('Exports', 'index', 'Exports', 'broadcast')
            ->dependency('Exports', 'index', 'Exports', 'launchExport')
            ->dependency('Exports', 'index', 'Exports', 'verifyConfig')
            ->dependency('Exports', 'index', 'Exports', 'saveInstanceConfigSyncSelection');


        $this
            ->dependency('Containers', 'index', 'Containers', 'view')
            ->dependency('Containers', 'index', 'Containers', 'nest');


        $this
            ->dependency('Locations', 'index', 'Locations', 'view')
            ->dependency('Locations', 'add', 'Locations', 'loadContainers')
            ->dependency('Locations', 'edit', 'Locations', 'loadContainers');


        $this
            ->dependency('Usergroups', 'index', 'Usergroups', 'view');


        $this
            ->dependency('Backups', 'index', 'Backups', 'checkBackupFinished');


        $this
            ->dependency('Notifications', 'index', 'Notifications', 'services');


        $this
            ->dependency('Statusmaps', 'index', 'Statusmaps', 'hostAndServicesSummaryStatus');


        $this
            ->dependency('Statistics', 'index', 'Statistics', 'saveStatisticDecision');


        $this
            ->dependency('ConfigurationFiles', 'edit', 'ConfigurationFiles', 'NagiosCfg')
            ->dependency('ConfigurationFiles', 'edit', 'ConfigurationFiles', 'AfterExport')
            ->dependency('ConfigurationFiles', 'edit', 'ConfigurationFiles', 'NagiosModuleConfig')
            ->dependency('ConfigurationFiles', 'edit', 'ConfigurationFiles', 'phpNSTAMaster')
            ->dependency('ConfigurationFiles', 'edit', 'ConfigurationFiles', 'DbBackend')
            ->dependency('ConfigurationFiles', 'edit', 'ConfigurationFiles', 'PerfdataBackend')
            ->dependency('ConfigurationFiles', 'edit', 'ConfigurationFiles', 'GraphingDocker')
            ->dependency('ConfigurationFiles', 'edit', 'ConfigurationFiles', 'StatusengineCfg')
            ->dependency('ConfigurationFiles', 'edit', 'ConfigurationFiles', 'Statusengine3Cfg')
            ->dependency('ConfigurationFiles', 'edit', 'ConfigurationFiles', 'GraphiteWeb')
            ->dependency('ConfigurationFiles', 'edit', 'ConfigurationFiles', 'restorDefault')
            ->dependency('ConfigurationFiles', 'edit', 'ConfigurationFiles', 'dynamicDirective')
            ->dependency('ConfigurationFiles', 'edit', 'ConfigurationFiles', 'NSTAMaster');

        //Load Plugin ALC Dependencies
        foreach (PluginManager::getAvailablePlugins() as $pluginName) {
            $className = sprintf('\%s\Lib\AclDependencies', $pluginName);
            if (class_exists($className)) {
                /** @var PluginAclDependencies $PluginAclDependencies */
                $PluginAclDependencies = new $className();

                $this->allow[$pluginName] = $PluginAclDependencies->getAllow();
                $this->dependencies[$pluginName] = $PluginAclDependencies->getDependencies();
            }
        }

    }

    /**
     * @param string $controller
     * @param string $action
     * @return $this
     */
    public function allow(string $controller, string $action): self {
        if (!isset($this->allow[$controller])) {
            $this->allow[$controller] = [];
        }

        $this->allow[$controller][$action] = $action;
        return $this;
    }

    /**
     * @param string $controller
     * @param string $action
     * @param string $dependentController
     * @param $dependentAction
     * @return $this
     */
    public function dependency(string $controller, string $action, string $dependentController, $dependentAction): self {
        if (!isset($this->dependencies[$controller][$action])) {
            $this->dependencies[$controller][$action] = [];
        }

        if (!isset($this->dependencies[$controller][$action][$dependentController])) {
            $this->dependencies[$controller][$action][$dependentController] = [];
        }

        $this->dependencies[$controller][$action][$dependentController][] = $dependentAction;


        return $this;
    }

    /**
     * @param AcosTable $AcosTable
     * @param array $selectedAcos
     * @return array
     */
    public function getDependentAcos(AcosTable $AcosTable, array $selectedAcos): array {
        $threadedAcos = $AcosTable->find('threaded')
            ->disableHydration()
            ->all();

        $acos = [];
        foreach ($threadedAcos as $threadedAco) {
            foreach ($threadedAco['children'] as $controllerAcos) {
                if (substr($controllerAcos['alias'], -6) === 'Module') {
                    $pluginAcos = $controllerAcos;
                    foreach ($pluginAcos['children'] as $pluginControllerAcos) {
                        $acos[$pluginAcos['alias']][$pluginControllerAcos['alias']] = [
                            'id'      => $pluginControllerAcos['id'],
                            'actions' => []
                        ];
                        foreach ($pluginControllerAcos['children'] as $pluginActionAcos) {
                            $acos[$pluginAcos['alias']][$pluginControllerAcos['alias']]['actions'][$pluginActionAcos['alias']] = [
                                'id' => $pluginActionAcos['id'],
                            ];
                        }
                    }
                } else {
                    //Core
                    $acos[$controllerAcos['alias']] = [
                        'id'      => $controllerAcos['id'],
                        'actions' => []
                    ];
                    foreach ($controllerAcos['children'] as $actionAco) {
                        $acos[$controllerAcos['alias']]['actions'][$actionAco['alias']] = [
                            'id' => $actionAco['id'],
                        ];
                    }
                }
            }
        }

        //Add always allowed ACL actions to $selectedAcos
        foreach ($this->allow as $controller => $actions) {
            if (substr($controller, -6) === 'Module') {
                $pluginName = $controller;
                foreach ($actions as $pluginController => $pluginActions) {
                    foreach ($pluginActions as $pluginAction) {
                        if (isset($acos[$pluginName][$pluginController]['actions'][$pluginAction]['id'])) {
                            //debug(implode('/', [$pluginName, $pluginController, $pluginAction]));
                            $acoId = $acos[$pluginName][$pluginController]['actions'][$pluginAction]['id'];

                            $selectedAcos[$acoId] = 1;
                        }
                    }
                }
            } else {
                //Core
                foreach ($actions as $action) {
                    if (isset($acos[$controller]['actions'][$action]['id'])) {
                        $acoId = $acos[$controller]['actions'][$action]['id'];

                        $selectedAcos[$acoId] = 1;
                    }
                }
            }
        }

        //Build up dependency tree (dependent ACL actions)
        $dependencyTree = [];
        foreach ($this->dependencies as $controller => $actions) { //1 core
            if (substr($controller, -6) === 'Module') {
                $pluginName = $controller;
                $pluginControllers = $actions;
                foreach ($pluginControllers as $pluginController => $pluginControllerActions) { // like 1 for core
                    foreach ($pluginControllerActions as $pluginAction => $dependentPluginControllers) { //like 2 for core
                        foreach ($dependentPluginControllers as $dependentPluginController => $dependentPluginActions) { // like 3 for core
                            foreach ($dependentPluginActions as $dependentPluginAction) { // like 4 for core
                                //debug(sprintf(
                                //    '[%s] %s/%s depends on %s/%s',
                                //    $pluginName,
                                //    $pluginController,
                                //    $pluginAction,
                                //    $dependentPluginController,
                                //    $dependentPluginAction
                                //));

                                if (isset($acos[$pluginName][$pluginController]['actions'][$pluginAction]['id'])) {
                                    if (isset($acos[$pluginName][$dependentPluginController]['actions'][$dependentPluginAction]['id'])) {
                                        $acoId = $acos[$pluginName][$pluginController]['actions'][$pluginAction]['id'];
                                        $dependentAcoId = $acos[$pluginName][$dependentPluginController]['actions'][$dependentPluginAction]['id'];

                                        $dependencyTree[$acoId][] = $dependentAcoId;
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                //Core
                foreach ($actions as $action => $dependentControllers) { //2 core
                    foreach ($dependentControllers as $dependentController => $dependentActions) { //3 core
                        foreach ($dependentActions as $dependentAction) { //4 core
                            //debug(sprintf(
                            //    '%s/%s depends on %s/%s',
                            //    $controller,
                            //    $action,
                            //    $dependentController,
                            //    $dependentAction
                            //));

                            if (isset($acos[$controller]['actions'][$action]['id'])) {
                                if (isset($acos[$dependentController]['actions'][$dependentAction]['id'])) {
                                    $acoId = $acos[$controller]['actions'][$action]['id'];
                                    $dependentAcoId = $acos[$dependentController]['actions'][$dependentAction]['id'];

                                    $dependencyTree[$acoId][] = $dependentAcoId;
                                }
                            }
                        }
                    }
                }
            }
        }

        //Add dependent ACL actions to $selectedAcos
        foreach ($selectedAcos as $acoId => $permissions) {
            if ($permissions === 1) {
                if (isset($dependencyTree[$acoId])) {
                    //Add dependencies to $selectedAcos;

                    foreach ($dependencyTree[$acoId] as $dependentAcoId) {
                        $selectedAcos[$dependentAcoId] = 1;
                    }
                }
            }
        }

        return $selectedAcos;
    }

    public function filterAcosForFrontend($acosResultThreaded) {
        $allDependenciesSimplified = [];
        foreach ($this->dependencies as $controllerName => $actions) { //1
            if (substr($controllerName, -6) === 'Module') {
                $pluginName = $controllerName;
                $pluginControllers = $actions;

                foreach ($pluginControllers as $pluginControllerName => $pluginActions) { //Like 1 for core
                    foreach ($pluginActions as $pluginActionName => $dependentPluginController) { //Like 2 for core
                        foreach ($dependentPluginController as $dependentPluginControllerName => $dependentPluginActions) { //Like 3 for core
                            foreach ($dependentPluginActions as $dependentPluginAction) { //Like 4 for core
                                if (!isset($allDependenciesSimplified[$pluginName][$dependentPluginControllerName])) {
                                    $allDependenciesSimplified[$pluginName][$dependentPluginControllerName] = [];
                                }

                                $allDependenciesSimplified[$pluginName][$dependentPluginControllerName][$dependentPluginAction] = $dependentPluginAction;
                            }
                        }
                    }
                }
            } else {
                //Core
                foreach ($actions as $actionName => $dependentController) { //2
                    foreach ($dependentController as $dependentControllerName => $dependentActions) { //3
                        foreach ($dependentActions as $dependentAction) { //4
                            if (!isset($allDependenciesSimplified[$dependentControllerName])) {
                                $allDependenciesSimplified[$dependentControllerName] = [];
                            }

                            $allDependenciesSimplified[$dependentControllerName][$dependentAction] = $dependentAction;
                        }
                    }
                }
            }
        }

        foreach ($acosResultThreaded[0]['children'] as $controllerIndex => $controller) {
            if (substr($controller['alias'], -6) === 'Module') {
                $pluginName = $controller['alias'];
                foreach ($controller['children'] as $pluginControllerIndex => $pluginController) {
                    $pluginControllerName = $pluginController['alias'];
                    foreach ($pluginController['children'] as $pluginActionIndex => $pluginAction) {
                        $pluginActionName = $pluginAction['alias'];

                        // Remove ACOs that are always allow (the user cannot untick them!)
                        if (isset($this->allow[$pluginName][$pluginControllerName][$pluginActionName])) {
                            unset($acosResultThreaded[0]['children'][$controllerIndex]['children'][$pluginControllerIndex]['children'][$pluginActionIndex]);
                        }

                        // Remove ACOs if they are dependencies of other ACOs (the user cannot untick them!)
                        if (isset($allDependenciesSimplified[$pluginName][$pluginControllerName][$pluginActionName])) {
                            unset($acosResultThreaded[0]['children'][$controllerIndex]['children'][$pluginControllerIndex]['children'][$pluginActionIndex]);
                        }

                        // Remove ACOs that shoud be ignored like public functions form AppController that exists in all controllers due to class FooController extends AppController and so on
                        if (in_array($pluginActionName, $this->ignore, true)) {
                            unset($acosResultThreaded[0]['children'][$controllerIndex]['children'][$pluginControllerIndex]['children'][$pluginActionIndex]);
                        }
                    }
                }

            } else {
                //Core
                $controllerName = $controller['alias'];
                foreach ($controller['children'] as $actionIndex => $action) {
                    $actionName = $action['alias'];

                    // Remove ACOs that are always allow (the user cannot untick them!)
                    if (isset($this->allow[$controllerName][$actionName])) {
                        unset($acosResultThreaded[0]['children'][$controllerIndex]['children'][$actionIndex]);
                    }

                    // Remove ACOs if they are dependencies of other ACOs (the user cannot untick them!)
                    if (isset($allDependenciesSimplified[$controllerName][$actionName])) {
                        unset($acosResultThreaded[0]['children'][$controllerIndex]['children'][$actionIndex]);
                    }

                    // Remove ACOs that shoud be ignored like public functions form AppController that exists in all controllers due to class FooController extends AppController and so on
                    if (in_array($actionName, $this->ignore, true)) {
                        unset($acosResultThreaded[0]['children'][$controllerIndex]['children'][$actionIndex]);
                    }
                }
            }

            //Make sure we have arrays [] not hasmaps {} !!!
            $acosResultThreaded[0]['children'][$controllerIndex]['children'] = array_values($acosResultThreaded[0]['children'][$controllerIndex]['children']);

        }

        return $acosResultThreaded;
    }

}

