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

namespace itnovum\openITCOCKPIT\Core\Menu;


use App\Lib\PluginManager;

class Menu {

    const MENU_OVERVIEW = 'overview';
    const MENU_MONITORING = 'monitoring';
    const MENU_ADMINISTRATION = 'administration';
    const MENU_CONFIGURATION = 'configuration';

    /**
     * @var MenuHeadline[]
     */
    private $headlines = [];

    /**
     * @var array
     */
    private $PERMISSIONS = [];

    /**
     * Menu constructor.
     * @param array $PERMISSIONS
     */
    public function __construct($PERMISSIONS = []) {
        $this->PERMISSIONS = $PERMISSIONS;

        $Overview = new MenuHeadline(self::MENU_OVERVIEW, __('Overview'), 1);
        $Overview
            ->addLink(new MenuLink(
                __('Dashboards'),
                'DashboardsIndex',
                'dashboards',
                'index',
                '',
                'fas fa-tachometer-alt',
                ['dashboard'],
                1
            ))
            ->addLink(new MenuLink(
                __('Browser'),
                'BrowsersIndex',
                'browsers',
                'index',
                '',
                'fa fa-list',
                ['browser'],
                2
            ))
            ->addCategory(
                (new MenuCategory(
                    'maps_category',
                    __('Maps'),
                    3,
                    'fa fa-map-marker'
                ))
                    ->addLink(new MenuLink(
                        __('Status Map'),
                        'StatusmapsIndex',
                        'statusmaps',
                        'index',
                        '',
                        'fa fa-globe',
                        ['statusmap', 'status', 'map'],
                        1
                    ))
                    ->addLink(new MenuLink(
                        __('Auto Map'),
                        'AutomapsIndex',
                        'automaps',
                        'index',
                        '',
                        'fa fa-magic',
                        ['automaps', 'auto', 'map'],
                        2
                    ))
            )
            ->addCategory(
                (new MenuCategory(
                    'reports_category',
                    __('Reports'),
                    4,
                    'fa fa-file-invoice'
                ))
                    ->addLink(new MenuLink(
                        __('Instant reports'),
                        'InstantreportsIndex',
                        'instantreports',
                        'index',
                        '',
                        'fa fa-file-invoice',
                        ['instantreports'],
                        1
                    ))
                    ->addLink(new MenuLink(
                        __('Downtime reports'),
                        'DowntimereportsIndex',
                        'downtimereports',
                        'index',
                        '',
                        'fa fa-file-invoice',
                        [],
                        2
                    ))
                    ->addLink(new MenuLink(
                        __('Current State reports'),
                        'CurrentstatereportsIndex',
                        'currentstatereports',
                        'index',
                        '',
                        'fa fa-file-invoice',
                        [],
                        3
                    ))
            )
            ->addCategory(
                (new MenuCategory(
                    'logs_category',
                    __('Logs'),
                    5,
                    'fa fa-file-text '
                ))
                    ->addLink(new MenuLink(
                        __('Notifications'),
                        'NotificationsIndex',
                        'notifications',
                        'index',
                        '',
                        'fa fa-envelope',
                        [],
                        1
                    ))
                    ->addLink(new MenuLink(
                        __('Log entries'),
                        'LogentriesIndex',
                        'logentries',
                        'index',
                        '',
                        'fa fa-file-text',
                        [],
                        2
                    ))
                    ->addLink(new MenuLink(
                        __('Change Log'),
                        'ChangelogsIndex',
                        'changelogs',
                        'index',
                        '',
                        'fa fa-code-fork',
                        [],
                        3
                    ))
            );

        $Monitoring = new MenuHeadline(self::MENU_MONITORING, __('Monitoring'), 2);
        $Monitoring
            ->addLink(new MenuLink(
                __('Hosts'),
                'HostsIndex',
                'hosts',
                'index',
                '',
                'fa fa-desktop',
                ['hosts'],
                1
            ))
            ->addLink(new MenuLink(
                __('Services'),
                'ServicesIndex',
                'services',
                'index',
                '',
                'fa fa-cogs',
                ['hosts'],
                2
            ))
            ->addCategory((new MenuCategory(
                'objects_category',
                __('Objects'),
                3,
                'fa fa-cubes'
            ))
                ->addLink(new MenuLink(
                    __('Contacts'),
                    'ContactsIndex',
                    'contacts',
                    'index',
                    '',
                    'fa fa-user',
                    [],
                    1
                ))
                ->addLink(new MenuLink(
                    __('Commands'),
                    'CommandsIndex',
                    'commands',
                    'index',
                    '',
                    'fa fa-terminal',
                    [],
                    2
                ))
                ->addLink(new MenuLink(
                    __('User Defined Macros'),
                    'MacrosIndex',
                    'macros',
                    'index',
                    '',
                    'fa fa-usd',
                    [],
                    3
                ))
                ->addLink(new MenuLink(
                    __('Time Periods'),
                    'TimeperiodsIndex',
                    'timeperiods',
                    'index',
                    '',
                    'fa fa-clock-o',
                    [],
                    4
                ))
                ->addLink(new MenuLink(
                    __('Calendar'),
                    'CalendarsIndex',
                    'calendars',
                    'index',
                    '',
                    'fa fa-calendar',
                    [],
                    5
                ))
                ->addLink(new MenuLink(
                    __('Host Escalations'),
                    'HostescalationsIndex',
                    'hostescalations',
                    'index',
                    '',
                    'fa fa-bomb',
                    [],
                    6
                ))
                ->addLink(new MenuLink(
                    __('Service Escalations'),
                    'ServiceescalationsIndex',
                    'serviceescalations',
                    'index',
                    '',
                    'fa fa-bomb',
                    [],
                    7
                ))
                ->addLink(new MenuLink(
                    __('Host Dependencies'),
                    'HostdependenciesIndex',
                    'hostdependencies',
                    'index',
                    '',
                    'fa fa-sitemap',
                    [],
                    8
                ))
                ->addLink(new MenuLink(
                    __('Service Dependencies'),
                    'ServicedependenciesIndex',
                    'servicedependencies',
                    'index',
                    '',
                    'fa fa-sitemap',
                    [],
                    9
                ))
            )
            ->addCategory((new MenuCategory(
                'groups_category',
                __('Groups'),
                4,
                'fa fa-object-group'
            ))
                ->addLink(new MenuLink(
                    __('Host Groups'),
                    'HostgroupsIndex',
                    'hostgroups',
                    'index',
                    '',
                    'fas fa-server',
                    [],
                    1
                ))
                ->addLink(new MenuLink(
                    __('Service Groups'),
                    'ServicegroupsIndex',
                    'servicegroups',
                    'index',
                    '',
                    'fa fa-cogs',
                    [],
                    2
                ))
                ->addLink(new MenuLink(
                    __('Contact Groups'),
                    'ContactgroupsIndex',
                    'contactgroups',
                    'index',
                    '',
                    'fa fa-users',
                    [],
                    3
                ))
                ->addLink(new MenuLink(
                    __('Service Template Grps.'),
                    'ServicetemplategroupsIndex',
                    'servicetemplategroups',
                    'index',
                    '',
                    'fa fa-pencil-square-o',
                    [],
                    4
                ))
            )
            ->addCategory((new MenuCategory(
                'downtimes_category',
                __('Downtimes'),
                5,
                'fa fa-power-off'
            ))
                ->addLink(new MenuLink(
                    __('Host Downtimes'),
                    'DowntimesHost',
                    'downtimes',
                    'host',
                    '',
                    'fa fa-power-off',
                    [],
                    1
                ))
                ->addLink(new MenuLink(
                    __('Service Downtimes'),
                    'DowntimesService',
                    'downtimes',
                    'service',
                    '',
                    'fa fa-power-off',
                    [],
                    2
                ))
                ->addLink(new MenuLink(
                    __('Recurring Host Downtimes'),
                    'SystemdowntimesHost',
                    'systemdowntimes',
                    'host',
                    '',
                    'fa fa-history fa-flip-horizontal',
                    [],
                    3
                ))
                ->addLink(new MenuLink(
                    __('Recurring Service Downtimes'),
                    'SystemdowntimesService',
                    'systemdowntimes',
                    'service',
                    '',
                    'fa fa-history fa-flip-horizontal',
                    [],
                    4
                ))
                ->addLink(new MenuLink(
                    __('System Failures'),
                    'SystemfailuresIndex',
                    'systemfailures',
                    'index',
                    '',
                    'fa fa-exclamation-circle',
                    [],
                    5
                ))
            )
            ->addCategory((new MenuCategory(
                'templates_category',
                __('Templates'),
                6,
                'fa fa-pencil-square-o'
            ))
                ->addLink(new MenuLink(
                    __('Host Templates'),
                    'HosttemplatesIndex',
                    'hosttemplates',
                    'index',
                    '',
                    'fa fa-pencil-square-o',
                    [],
                    1
                ))
                ->addLink(new MenuLink(
                    __('Service Templates'),
                    'ServicetemplatesIndex',
                    'servicetemplates',
                    'index',
                    '',
                    'fa fa-pencil-square-o',
                    [],
                    2
                ))
            );

        $Administration = new MenuHeadline(self::MENU_ADMINISTRATION, __('Administration'), 3);
        $Administration
            ->addCategory((new MenuCategory(
                'user_mgmt_category',
                __('User management'),
                1,
                'fa fa-users'
            ))
                ->addLink(new MenuLink(
                    __('Manage Users'),
                    'UsersIndex',
                    'users',
                    'index',
                    '',
                    'fa fa-user',
                    [],
                    1
                ))
                ->addLink(new MenuLink(
                    __('Manage User Roles'),
                    'UsergroupsIndex',
                    'usergroups',
                    'index',
                    '',
                    'fa fa-users',
                    [],
                    2
                ))
                ->addLink(new MenuLink(
                    __('User Container Roles'),
                    'UsercontainerrolesIndex',
                    'usercontainerroles',
                    'index',
                    '',
                    'fa fa-users',
                    [],
                    3
                ))
            )
            ->addCategory((new MenuCategory(
                'container_mgmt_category',
                __('Container management'),
                2,
                'fa fa-link'
            ))
                ->addLink(new MenuLink(
                    __('Tenants'),
                    'TenantsIndex',
                    'tenants',
                    'index',
                    '',
                    'fa fa-home',
                    [],
                    1
                ))
                ->addLink(new MenuLink(
                    __('Containers'),
                    'ContainersIndex',
                    'containers',
                    'index',
                    '',
                    'fa fa-link',
                    [],
                    2
                ))
                ->addLink(new MenuLink(
                    __('Locations'),
                    'LocationsIndex',
                    'locations',
                    'index',
                    '',
                    'fa fa-location-arrow',
                    [],
                    3
                ))
            )
            ->addCategory((new MenuCategory(
                'system_tools_category',
                __('System tools'),
                3,
                'fa fa-link'
            ))
                ->addLink(new MenuLink(
                    __('Cron Jobs'),
                    'CronjobsIndex',
                    'cronjobs',
                    'index',
                    '',
                    'fa fa-clock-o',
                    [],
                    1
                ))
                ->addLink(new MenuLink(
                    __('Package Manager'),
                    'PackageManagerIndex',
                    'packetmanager',
                    'index',
                    '',
                    'fa fa-cloud-download-alt',
                    [],
                    2
                ))
                ->addLink(new MenuLink(
                    __('Debugging'),
                    'AdministratorsDebug',
                    'Administrators',
                    'debug',
                    '',
                    'fa fa-bug',
                    [],
                    3
                ))
                ->addLink(new MenuLink(
                    __('Performance Info'),
                    'NagiostatsIndex',
                    'nagiostats',
                    'index',
                    '',
                    'fa fa-fighter-jet',
                    [],
                    4
                ))
                ->addLink(new MenuLink(
                    __('Backup / Restore'),
                    'BackupsIndex',
                    'backups',
                    'index',
                    '',
                    'fa fa-database',
                    [],
                    5
                ))
                ->addLink(new MenuLink(
                    __('Anonymous statistics'),
                    'StatisticsIndex',
                    'statistics',
                    'index',
                    '',
                    'fa fa-line-chart',
                    [],
                    7
                ))
            )
            ->addCategory((new MenuCategory(
                'agent_category',
                __('openITCOCKPIT Agent'),
                4,
                'fa fa-user-secret'
            ))
                ->addLink(new MenuLink(
                    __('Agent Configuration'),
                    'AgentconnectorsConfig',
                    'agentconnector',
                    'config',
                    '',
                    'fa fa-user-secret',
                    [],
                    1
                ))
                ->addLink(new MenuLink(
                    __('Untrusted Agents'),
                    'AgentconnectorsAgent',
                    'agentconnector',
                    'agents',
                    '',
                    'fa fa-user-secret',
                    [],
                    2
                ))
                ->addLink(new MenuLink(
                    __('Agent Checks'),
                    'AgentchecksIndex',
                    'agentchecks',
                    'index',
                    '',
                    'fa fa-cogs',
                    [],
                    3
                ))
            );
        $Configuration = new MenuHeadline(self::MENU_CONFIGURATION, __('System Configuration'), 4);
        $Configuration
            ->addCategory(new MenuCategory(
                'api_settings',
                __('APIs'),
                1,
                'fa fa-code'
            ))
            ->addCategory((new MenuCategory(
                'settings_category',
                __('System'),
                3,
                'fa fa-cogs'
            ))
                ->addLink(new MenuLink(
                    __('System Settings'),
                    'SystemsettingsIndex',
                    'systemsettings',
                    'index',
                    '',
                    'fa fa-wrench',
                    [],
                    2
                ))
                ->addLink(new MenuLink(
                    __('Config file editor'),
                    'ConfigurationFilesIndex',
                    'ConfigurationFiles',
                    'index',
                    '',
                    'fa fa-file-text',
                    [],
                    1
                ))
                ->addLink(new MenuLink(
                    __('Proxy Settings'),
                    'ProxyIndex',
                    'proxy',
                    'index',
                    '',
                    'fa fa-bolt',
                    [],
                    2
                ))
                ->addLink(new MenuLink(
                    __('Registration'),
                    'RegistersIndex',
                    'registers',
                    'index',
                    '',
                    'fa fa-check-square',
                    [],
                    3
                ))
                ->addLink(new MenuLink(
                    __('Report an issue'),
                    'SupportsIssue',
                    'supports',
                    'issue',
                    '',
                    'fa fa-bug',
                    [],
                    4
                ))
            );


        $this->addHeadline($Overview);
        $this->addHeadline($Monitoring);
        $this->addHeadline($Administration);
        $this->addHeadline($Configuration);
    }

    /**
     * @param MenuHeadline $MenuHeadline
     * @return $this
     */
    public function addHeadline(MenuHeadline $MenuHeadline) {
        $headlines = $this->headlines;
        $headlines[] = $MenuHeadline;

        $this->headlines = [];

        //Merge headlines with the same name together
        $names = [];
        $headlinesToRemove = [];
        foreach ($headlines as $index => $headline) {
            /** @var MenuHeadline $headline */
            if (isset($names[$headline->getName()])) {
                // Merge with existing headline
                /** @var MenuHeadline $targetHeadline */
                $targetHeadline = $names[$headline->getName()];
                $headlinesToRemove = [$index];
                foreach ($headline->getItems() as $item) {
                    if ($item instanceof MenuLink) {
                        $targetHeadline->addLink($item);
                    }
                    if ($item instanceof MenuCategory) {
                        $targetHeadline->addCategory($item);
                    }
                }
            } else {
                //Save name
                $names[$headline->getName()] = $headline;
            }
        }

        //Drop merged headlines to remove duplicates
        foreach ($headlinesToRemove as $index) {
            unset($headlines[$index]);
        }


        //Order headlines
        $indexesToOrder = [];
        foreach ($headlines as $index => $headline) {
            /** @var MenuHeadline $headline */
            $indexesToOrder[$index] = $headline->getOrder();
        }

        asort($indexesToOrder);

        foreach ($indexesToOrder as $index => $orderNumber) {
            $this->headlines[] = $headlines[$index];
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getAllMenuCategories() {
        $menuCategories = [];
        foreach ($this->headlines as $headline) {
            /** @var MenuHeadline $headline */
            if (!isset($menuCategories[$headline->getName()])) {
                $menuCategories[$headline->getName()] = [];
            }

            foreach ($headline->getItems() as $item) {
                if ($item instanceof MenuCategory) {
                    $menuCategories[$headline->getName()][] = $item->getName();
                }
            }
        }

        return $menuCategories;
    }

    /**
     * @return MenuHeadline[]
     */
    public function getMenuItems() {
        $modules = PluginManager::getAvailablePlugins();
        foreach ($modules as $module) {
            $className = sprintf('\\%s\\Lib\\Menu', $module);
            if (class_exists($className)) {

                /** @var MenuInterface $PluginMenu */
                $PluginMenu = new $className();

                foreach ($PluginMenu->getHeadlines() as $headline) {
                    /** @var MenuHeadline $headline */
                    $this->addHeadline($headline);
                }
            }
        }


        $this->filterMenuByPermissions();
        $this->remoteEmptyMenuItems();

        return $this->headlines;
    }

    private function filterMenuByPermissions() {
        foreach ($this->headlines as $headline) {
            foreach ($headline->getItems() as $index => $item) {
                if ($item instanceof MenuCategory) {
                    /** @var MenuCategory $item */
                    foreach ($item->getLinks() as $subIndex => $MenuLink) {
                        if ($this->hasPermissions($MenuLink) === false) {
                            $item->removeMenuLinkByIndex($subIndex);
                        }
                    }

                } else {
                    /** @var MenuLink $item */
                    if ($this->hasPermissions($item) === false) {
                        $headline->removeMenuLinkByIndex($index);
                    }

                }
            }
        }
    }

    private function remoteEmptyMenuItems() {
        foreach ($this->headlines as $headlineIndex => $headline) {
            foreach ($headline->getItems() as $index => $item) {
                if ($item instanceof MenuCategory) {
                    /** @var MenuCategory $item */
                    if ($item->hasLinks() === false) {
                        $headline->removeMenuCategoryByIndex($index);
                    }
                }
            }

            if ($headline->hasItems() === false) {
                unset($this->headlines[$headlineIndex]);
            }
        }

    }

    /**
     * @param MenuLink $MenuLink
     * @return bool
     */
    private function hasPermissions(MenuLink $MenuLink) {
        $plugin = $MenuLink->getLowerPlugin();
        $controller = $MenuLink->getLowerController();
        $action = $MenuLink->getLowerAction();

        if ($MenuLink->isModule()) {
            return isset($this->PERMISSIONS[$plugin][$controller][$action]);
        }

        return isset($this->PERMISSIONS[$controller][$action]);
    }

}
