var openITCOCKPIT = angular.module('openITCOCKPIT', ['gridster', 'ui.router', 'ng-nestable'])
    .factory("httpInterceptor", function($q, $rootScope, $timeout){
        return {
            response: function(result){
                if(result.data.hasOwnProperty('_csrfToken')){
                    if(result.data._csrfToken !== null){
                        $rootScope._csrfToken = result.data._csrfToken;
                    }
                }

                var url = result.config.url;
                if(url === '/angular/system_health.json' || url === '/angular/menustats.json'){
                    return result || $.then(result);
                }

                //If we want to hide all loaders one day
                /*if(result.config.hasOwnProperty('params')){
                    if(result.config.params.hasOwnProperty('disableGlobalLoader')){
                        return result || $.then(result);
                    }
                }*/

                $rootScope.runningAjaxCalls--;

                if($rootScope.runningAjaxCalls === 0){
                    //No other ajax call is running, hide loader
                    $('#global_ajax_loader').fadeOut('slow');
                    $('#global-loading').fadeOut('slow');
                }
                return result || $.then(result);
            },
            request: function(response){
                if(response.method !== 'GET'){
                    response.headers['X-CSRF-Token'] = $rootScope._csrfToken;
                }

                var url = response.url;
                if(url === '/angular/system_health.json' || url === '/angular/menustats.json'){
                    return response || $q.when(response);
                }

                //If we want to hide all loaders one day
                /*if(response.hasOwnProperty('params')){
                    if(response.params.hasOwnProperty('disableGlobalLoader')){
                        return response || $q.when(response);
                    }
                }*/

                //Reference Counting Basics Garbage Collection
                $rootScope.runningAjaxCalls++;


                var onlyShowMenuLoader = false;
                if(response.hasOwnProperty('params')){ //GET
                    if(response.params.hasOwnProperty('disableGlobalLoader')){
                        onlyShowMenuLoader = true;
                    }
                }

                if(response.hasOwnProperty('data')){ //POST
                    if(typeof response.data !== "undefined"){
                        if(response.data.hasOwnProperty('disableGlobalLoader')){
                            onlyShowMenuLoader = true;
                        }
                    }
                }

                $('#global_ajax_loader').show();
                if(onlyShowMenuLoader === false){
                    $('#global-loading').show();
                }
                return response || $q.when(response);
            },
            responseError: function(rejection){
                var url = rejection.config.url;
                if(url === '/angular/system_health.json' || url === '/angular/menustats.json'){
                    return $q.reject(rejection);
                }

                if(rejection.status === 401){
                    window.location = '/users/login';
                    return;
                }

                //If we want to hide all loaders one day
                /*if(rejection.config.hasOwnProperty('params')){
                    if(rejection.config.params.hasOwnProperty('disableGlobalLoader')){
                        return $q.reject(rejection);
                    }
                }*/

                $rootScope.runningAjaxCalls--;
                if($rootScope.runningAjaxCalls === 0){
                    //No other ajax call is running, hide loader
                    $('#global_ajax_loader').fadeOut('slow');
                    $('#global-loading').fadeOut('slow');
                }

                return $q.reject(rejection);
            }
        };
    })

    .config(function($httpProvider){
        $httpProvider.interceptors.push("httpInterceptor");

        $httpProvider.defaults.cache = false;
        if(!$httpProvider.defaults.headers.get){
            $httpProvider.defaults.headers.get = {};
        }
        // disable IE ajax request caching
        $httpProvider.defaults.headers.get['If-Modified-Since'] = '0';

    })


    .config(function($urlRouterProvider, $stateProvider){
        $urlRouterProvider.otherwise("/dashboards/index");

        $stateProvider

            .state('403', {
                url: '/error/403',
                templateUrl: "/angular/forbidden.html",
                controller: "Error403Controller"
            })

            .state('404', {
                url: '/error/404',
                templateUrl: "/angular/not_found.html",
                controller: "Error404Controller"
            })

            .state('ExportsIndex', {
                url: '/exports/index',
                templateUrl: "/exports/index.html",
                controller: "ExportsIndexController"
            })

            .state('AdministratorsDebug', {
                url: '/Administrators/debug',
                templateUrl: "/Administrators/debug.html",
                controller: "AdministratorsDebugController"
            })

            .state('AdministratorsQuerylog', {
                url: '/Administrators/querylog',
                templateUrl: "/Administrators/querylog.html",
                controller: "AdministratorsQuerylogController"
            })

            .state('AgentchecksIndex', {
                url: '/agentchecks/index',
                templateUrl: "/agentchecks/index.html",
                controller: "AgentchecksIndexController"
            })

            .state('AgentchecksAdd', {
                url: '/agentchecks/add',
                templateUrl: "/agentchecks/add.html",
                controller: "AgentchecksAddController"
            })

            .state('AgentchecksEdit', {
                url: '/agentchecks/edit/:id',
                templateUrl: "/agentchecks/edit.html",
                controller: "AgentchecksEditController"
            })

            .state('AgentconfigsConfig', {
                url: '/agentconfigs/config/:hostId',
                templateUrl: "/agentconfigs/config.html",
                controller: "AgentconfigsConfigController"
            })

            .state('AgentconfigsScan', {
                url: '/agentconfigs/scan/:hostId',
                templateUrl: "/agentconfigs/scan.html",
                controller: "AgentconfigsScanController"
            })

            .state('AgentconnectorsConfig', {
                url: '/agentconnector/config',
                params: {
                    hostId: {
                        value: null
                    }
                },
                templateUrl: "/agentconnector/config.html",
                controller: "AgentconnectorsConfigController"
            })

            .state('AgentconnectorsAgent', {
                url: '/agentconnector/agents?hostuuid',
                params: {
                    hostuuid: {
                        value: null
                    }
                },
                templateUrl: "/agentconnector/agents.html",
                controller: "AgentconnectorsAgentController"
            })

            .state('BrowsersIndex', {
                url: '/browsers/index?containerId',
                params: {
                    containerId: {
                        value: null
                    }
                },
                templateUrl: "/browsers/index.html",
                controller: "BrowsersIndexController"
            })

            .state('ConfigurationFilesIndex', {
                url: '/ConfigurationFiles/index',
                templateUrl: "/ConfigurationFiles/index.html",
                controller: "ConfigurationFilesIndexController"
            })

            .state('ConfigurationFilesEdit', {
                url: '/ConfigurationFiles/edit/:configfile',
                templateUrl: "/ConfigurationFiles/edit.html",
                controller: "ConfigurationFilesEditController"
            })

            .state('ContainersIndex', {
                url: '/containers/index/:id',
                params: {
                    id: {
                        value: null,
                        squash: true
                    }
                },
                templateUrl: "/containers/index.html",
                controller: "ContainersIndexController"
            })

            .state('ContainersShowDetails', {
                url: '/containers/showDetails/:id/:tenant',
                params: {
                    tenant: {
                        value: null,
                        squash: true
                    }
                },
                templateUrl: "/containers/showDetails.html",
                controller: "ContainersShowDetailsController"
            })

            .state('CurrentstatereportsIndex', {
                url: '/currentstatereports/index',
                templateUrl: "/currentstatereports/index.html",
                controller: "CurrentstatereportsIndexController"
            })

            .state('CronjobsIndex', {
                url: '/cronjobs/index',
                templateUrl: "/cronjobs/index.html",
                controller: "CronjobsIndexController"
            })

            .state('DashboardsIndex', {
                url: '/dashboards/index',
                templateUrl: "/dashboards/index.html",
                controller: "DashboardsIndexController"
            })

            .state('DeletedHostsIndex', {
                url: '/deletedHosts',
                templateUrl: "/deletedHosts/index.html",
                controller: "DeletedHostsIndexController"
            })

            .state('DowntimesHost', {
                url: '/downtimes/host',
                templateUrl: "/downtimes/host.html",
                controller: "DowntimesHostController"
            })

            .state('DowntimesService', {
                url: '/downtimes/service',
                templateUrl: "/downtimes/service.html",
                controller: "DowntimesServiceController"
            })

            .state('DowntimereportsIndex', {
                url: '/downtimereports/index',
                templateUrl: "/downtimereports/index.html",
                controller: "DowntimereportsIndexController"
            })

            .state('InstantreportsIndex', {
                url: '/instantreports/index',
                templateUrl: "/instantreports/index.html",
                controller: "InstantreportsIndexController"
            })

            .state('InstantreportsAdd', {
                url: '/instantreports/add',
                templateUrl: "/instantreports/add.html",
                controller: "InstantreportsAddController"
            })

            .state('InstantreportsEdit', {
                url: '/instantreports/edit/:id',
                templateUrl: "/instantreports/edit.html",
                controller: "InstantreportsEditController"
            })

            .state('InstantreportsGenerate', {
                url: '/instantreports/generate/:id',
                templateUrl: "/instantreports/generate.html",
                controller: "InstantreportsGenerateController"
            })

            .state('LogentriesIndex', {
                url: '/logentries/index',
                templateUrl: "/logentries/index.html",
                controller: "LogentriesIndexController"
            })

            .state('NotificationsIndex', {
                url: '/notifications/index',
                templateUrl: "/notifications/index.html",
                controller: "NotificationsIndexController"
            })

            .state('NotificationsServices', {
                url: '/notifications/services',
                templateUrl: "/notifications/services.html",
                controller: "NotificationsServicesController"
            })

            .state('ServicesIndex', {
                url: '/services/index?servicename&servicestate&sort&host_id&direction&BrowserContainerId&has_been_acknowledged&has_not_been_acknowledged&in_downtime&not_in_downtime&passive',
                params: {
                    servicename: {
                        value: null
                    },
                    servicestate: {
                        value: null,
                        array: true,
                        squash: true
                    },
                    sort: {
                        value: null
                    },
                    direction: {
                        value: null
                    },
                    has_been_acknowledged: {
                        value: null
                    },
                    has_not_been_acknowledged: {
                        value: null
                    },
                    in_downtime: {
                        value: null
                    },
                    not_in_downtime: {
                        value: null
                    },
                    passive: {
                        value: null
                    },
                    BrowserContainerId: {
                        value: null
                    },
                    host_id: {
                        value: null,
                        array: true,
                        squash: true
                    },
                    id: {
                        value: null,
                        array: true
                    },
                    hostname: {
                        value: null
                    },
                },
                templateUrl: "/services/index.html",
                controller: "ServicesIndexController"
            })

            .state('ServicesNotMonitored', {
                url: '/services/notMonitored',
                templateUrl: "/services/notMonitored.html",
                controller: "ServicesNotMonitoredController"
            })

            .state('ServicesBrowser', {
                url: '/services/browser/:id',
                templateUrl: "/services/browser.html",
                controller: "ServicesBrowserController"
            })

            .state('ServicesDisabled', {
                url: '/services/disabled',
                templateUrl: "/services/disabled.html",
                controller: "ServicesDisabledController"
            })

            .state('ServicesServiceList', {
                url: '/services/serviceList/:id',
                templateUrl: "/services/serviceList.html",
                controller: "ServicesServiceListController"
            })

            .state('ServicesAdd', {
                url: '/services/add/:hostId',
                params: {
                    hostId: {
                        value: null,
                        squash: true
                    }
                },
                templateUrl: "/services/add.html",
                controller: "ServicesAddController"
            })

            .state('ServicesEdit', {
                url: '/services/edit/:id',
                templateUrl: "/services/edit.html",
                controller: "ServicesEditController"
            })

            .state('ServicesCopy', {
                url: '/services/copy/:ids',
                templateUrl: "/services/copy.html",
                controller: "ServicesCopyController"
            })

            .state('ServicechecksIndex', {
                url: '/servicechecks/index/:id',
                templateUrl: "/servicechecks/index.html",
                controller: "ServicechecksIndexController"
            })

            .state('StatisticsIndex', {
                url: '/statistics/index',
                templateUrl: "/statistics/index.html",
                controller: "StatisticsIndexController"
            })

            .state('StatusmapsIndex', {
                url: '/statusmaps/index',
                templateUrl: "/statusmaps/index.html",
                controller: "StatusmapsIndexController"
            })

            .state('SystemdowntimesHost', {
                url: '/systemdowntimes/host',
                templateUrl: "/systemdowntimes/host.html",
                controller: "SystemdowntimesHostController"
            })

            .state('SystemdowntimesService', {
                url: '/systemdowntimes/service',
                templateUrl: "/systemdowntimes/service.html",
                controller: "SystemdowntimesServiceController"
            })

            .state('SystemdowntimesHostgroup', {
                url: '/systemdowntimes/hostgroup',
                templateUrl: "/systemdowntimes/hostgroup.html",
                controller: "SystemdowntimesHostgroupController"
            })

            .state('SystemdowntimesNode', {
                url: '/systemdowntimes/node',
                templateUrl: "/systemdowntimes/node.html",
                controller: "SystemdowntimesNodeController"
            })

            .state('SystemdowntimesAddHostdowntime', {
                url: '/systemdowntimes/addHostdowntime/:id',
                params: {
                    id: {
                        value: null,
                        squash: true
                    }
                },
                templateUrl: "/systemdowntimes/addHostdowntime.html",
                controller: "SystemdowntimesAddHostdowntimeController"
            })

            .state('SystemdowntimesAddHostgroupdowntime', {
                url: '/systemdowntimes/addHostgroupdowntime/:id',
                params: {
                    id: {
                        value: null,
                        squash: true
                    }
                },
                templateUrl: "/systemdowntimes/addHostgroupdowntime.html",
                controller: "SystemdowntimesAddHostgroupdowntimeController"
            })

            .state('SystemdowntimesAddServicedowntime', {
                url: '/systemdowntimes/addServicedowntime/:id',
                params: {
                    id: {
                        value: null,
                        squash: true
                    }
                },
                templateUrl: "/systemdowntimes/addServicedowntime.html",
                controller: "SystemdowntimesAddServicedowntimeController"
            })

            .state('SystemdowntimesAddContainerdowntime', {
                url: '/systemdowntimes/addContainerdowntime',
                templateUrl: "/systemdowntimes/addContainerdowntime.html",
                controller: "SystemdowntimesAddContainerdowntimeController"
            })

            .state('SystemsettingsIndex', {
                url: '/systemsettings/index',
                templateUrl: "/systemsettings/index.html",
                controller: "SystemsettingsIndexController"
            })

            .state('HostgroupsIndex', {
                url: '/hostgroups/index',
                params: {
                    id: {
                        value: null,
                        array: true
                    }
                },
                templateUrl: "/hostgroups/index.html",
                controller: "HostgroupsIndexController"
            })

            .state('HostgroupsAdd', {
                url: '/hostgroups/add/:ids',
                templateUrl: "/hostgroups/add.html",
                params: {
                    ids: {
                        value: null,
                        squash: true
                    }
                },
                controller: "HostgroupsAddController"
            })

            .state('HostgroupsEdit', {
                url: '/hostgroups/edit/:id',
                templateUrl: "/hostgroups/edit.html",
                controller: "HostgroupsEditController"
            })

            .state('HostgroupsExtended', {
                url: '/hostgroups/extended/:id',
                templateUrl: "/hostgroups/extended.html",
                params: {
                    id: {
                        value: null,
                        squash: true
                    }
                },
                controller: "HostgroupsExtendedController"
            })

            .state('HostgroupsAppend', {
                url: '/hostgroups/append/:ids',
                templateUrl: "/hostgroups/append.html",
                controller: "HostgroupsAppendController"
            })

            .state('HostchecksIndex', {
                url: '/hostchecks/index/:id',
                templateUrl: "/hostchecks/index.html",
                controller: "HostchecksIndexController"
            })

            .state('RegistersIndex', {
                url: '/registers/index',
                templateUrl: "/registers/index.html",
                controller: "RegistersIndexController"
            })

            .state('ServicedependenciesIndex', {
                url: '/servicedependencies/index',
                params: {
                    id: {
                        value: null
                    }
                },
                templateUrl: "/servicedependencies/index.html",
                controller: "ServicedependenciesIndexController"
            })

            .state('ServicedependenciesAdd', {
                url: '/servicedependencies/add',
                templateUrl: "/servicedependencies/add.html",
                controller: "ServicedependenciesAddController"
            })

            .state('ServicedependenciesEdit', {
                url: '/servicedependencies/edit/:id',
                templateUrl: "/servicedependencies/edit.html",
                controller: "ServicedependenciesEditController"
            })

            .state('ServiceescalationsIndex', {
                url: '/serviceescalations/index',
                params: {
                    id: {
                        value: null
                    }
                },
                templateUrl: "/serviceescalations/index.html",
                controller: "ServiceescalationsIndexController"
            })

            .state('ServiceescalationsAdd', {
                url: '/serviceescalations/add',
                templateUrl: "/serviceescalations/add.html",
                controller: "ServiceescalationsAddController"
            })

            .state('ServiceescalationsEdit', {
                url: '/serviceescalations/edit/:id',
                templateUrl: "/serviceescalations/edit.html",
                controller: "ServiceescalationsEditController"
            })

            .state('ServicegroupsIndex', {
                url: '/servicegroups/index',
                params: {
                    id: {
                        value: null,
                        array: true
                    }
                },
                templateUrl: "/servicegroups/index.html",
                controller: "ServicegroupsIndexController"
            })

            .state('ServicegroupsAdd', {
                url: '/servicegroups/add/:ids',
                templateUrl: "/servicegroups/add.html",
                params: {
                    ids: {
                        value: null,
                        squash: true
                    }
                },
                controller: "ServicegroupsAddController"
            })

            .state('ServicegroupsEdit', {
                url: '/servicegroups/edit/:id',
                templateUrl: "/servicegroups/edit.html",
                controller: "ServicegroupsEditController"
            })

            .state('ServicegroupsAppend', {
                url: '/servicegroups/append/:ids',
                templateUrl: "/servicegroups/append.html",
                controller: "ServicegroupsAppendController"
            })

            .state('ServicegroupsExtended', {
                url: '/servicegroups/extended/:id',
                templateUrl: "/servicegroups/extended.html",
                params: {
                    id: {
                        value: null,
                        squash: true
                    }
                },
                controller: "ServicegroupsExtendedController"
            })

            .state('StatehistoriesHost', {
                url: '/statehistories/host/:id',
                templateUrl: "/statehistories/host.html",
                controller: "StatehistoriesHostController"
            })

            .state('StatehistoriesService', {
                url: '/statehistories/service/:id',
                templateUrl: "/statehistories/service.html",
                controller: "StatehistoriesServiceController"
            })

            .state('SupportsIssue', {
                url: '/supports/issue',
                templateUrl: "/supports/issue.html",
                controller: "SupportsIssueController"
            })

            .state('CommandsIndex', {
                url: '/commands/index',
                params: {
                    id: {
                        value: null,
                        array: true
                    }
                },
                templateUrl: "/commands/index.html",
                controller: "CommandsIndexController"
            })

            .state('CommandsAdd', {
                url: '/commands/add',
                templateUrl: "/commands/add.html",
                controller: "CommandsAddController"
            })

            .state('CommandsEdit', {
                url: '/commands/edit/:id',
                templateUrl: "/commands/edit.html",
                controller: "CommandsEditController"
            })

            .state('CommandsCopy', {
                url: '/commands/copy/:ids',
                templateUrl: "/commands/copy.html",
                controller: "CommandsCopyController"
            })

            .state('CommandsUsedBy', {
                url: '/commands/usedBy/:id',
                templateUrl: "/commands/usedBy.html",
                controller: "CommandsUsedByController"
            })

            .state('ProxyIndex', {
                url: '/proxy/index',
                templateUrl: "/proxy/index.html",
                controller: "ProxyIndexController"
            })

            .state('TenantsIndex', {
                url: '/tenants/index',
                templateUrl: "/tenants/index.html",
                controller: "TenantsIndexController"
            })

            .state('TenantsAdd', {
                url: '/tenants/add',
                templateUrl: "/tenants/add.html",
                controller: "TenantsAddController"
            })

            .state('TenantsEdit', {
                url: '/tenants/edit/:id',
                templateUrl: "/tenants/edit.html",
                controller: "TenantsEditController"
            })

            .state('TimeperiodsIndex', {
                url: '/timeperiods/index',
                params: {
                    id: {
                        value: null,
                        array: true
                    }
                },
                templateUrl: "/timeperiods/index.html",
                controller: "TimeperiodsIndexController"
            })

            .state('TimeperiodsAdd', {
                url: '/timeperiods/add',
                templateUrl: "/timeperiods/add.html",
                controller: "TimeperiodsAddController"
            })

            .state('TimeperiodsEdit', {
                url: '/timeperiods/edit/:id',
                templateUrl: "/timeperiods/edit.html",
                controller: "TimeperiodsEditController"
            })

            .state('TimeperiodsCopy', {
                url: '/timeperiods/copy/:ids',
                templateUrl: "/timeperiods/copy.html",
                controller: "TimeperiodsCopyController"
            })

            .state('TimeperiodsUsedBy', {
                url: '/timeperiods/usedBy/:id',
                templateUrl: "/timeperiods/usedBy.html",
                controller: "TimeperiodsUsedByController"
            })

            .state('DocumentationsView', {
                url: '/documentations/view/:uuid/:type',
                templateUrl: "/documentations/view.html",
                controller: "DocumentationsViewController"
            })

            .state('DocumentationsWiki', {
                url: '/documentations/wiki/:documentation',
                params: {
                    documentation: {
                        value: null,
                        squash: true
                    }
                },
                templateUrl: "/documentations/wiki.html",
                controller: "DocumentationsWikiController"
            })

            .state('MacrosIndex', {
                url: '/macros/index',
                templateUrl: "/macros/index.html",
                controller: "MacrosIndexController"
            })

            .state('NotificationsHostNotification', {
                url: '/notifications/hostNotification/:id',
                templateUrl: "/notifications/hostNotification.html",
                controller: "NotificationsHostNotificationController"
            })

            .state('NotificationsServiceNotification', {
                url: '/notifications/serviceNotification/:id',
                templateUrl: "/notifications/serviceNotification.html",
                controller: "NotificationsServiceNotificationController"
            })

            .state('AcknowledgementsHost', {
                url: '/acknowledgements/host/:id',
                templateUrl: "/acknowledgements/host.html",
                controller: "AcknowledgementsHostController"
            })

            .state('AcknowledgementsService', {
                url: '/acknowledgements/service/:id',
                templateUrl: "/acknowledgements/service.html",
                controller: "AcknowledgementsServiceController"
            })


            .state('ContactsIndex', {
                url: '/contacts/index',
                params: {
                    id: {
                        value: null,
                        array: true
                    }
                },
                templateUrl: "/contacts/index.html",
                controller: "ContactsIndexController"
            })

            .state('ContactsAdd', {
                url: '/contacts/add',
                templateUrl: "/contacts/add.html",
                controller: "ContactsAddController"
            })

            .state('ContactsEdit', {
                url: '/contacts/edit/:id',
                templateUrl: "/contacts/edit.html",
                controller: "ContactsEditController"
            })

            .state('ContactsCopy', {
                url: '/contacts/copy/:ids',
                templateUrl: "/contacts/copy.html",
                controller: "ContactsCopyController"
            })

            .state('ContactsLdap', {
                url: '/contacts/ldap',
                templateUrl: "/contacts/addFromLdap.html",
                controller: "ContactsLdapController"
            })

            .state('ContactsUsedBy', {
                url: '/contacts/usedBy/:id',
                templateUrl: "/contacts/usedBy.html",
                controller: "ContactsUsedByController"
            })

            .state('ContactgroupsIndex', {
                url: '/contactgroups/index',
                params: {
                    id: {
                        value: null,
                        array: true
                    }
                },
                templateUrl: "/contactgroups/index.html",
                controller: "ContactgroupsIndexController"
            })

            .state('ContactgroupsAdd', {
                url: '/contactgroups/add',
                templateUrl: "/contactgroups/add.html",
                controller: "ContactgroupsAddController"
            })

            .state('ContactgroupsEdit', {
                url: '/contactgroups/edit/:id',
                templateUrl: "/contactgroups/edit.html",
                controller: "ContactgroupsEditController"
            })

            .state('ContactgroupsCopy', {
                url: '/contactgroups/copy/:ids',
                templateUrl: "/contactgroups/copy.html",
                controller: "ContactgroupsCopyController"
            })

            .state('ContactgroupsUsedBy', {
                url: '/contactgroups/usedBy/:id',
                templateUrl: "/contactgroups/usedBy.html",
                controller: "ContactgroupsUsedByController"
            })

            .state('HostescalationsIndex', {
                url: '/hostescalations/index',
                params: {
                    id: {
                        value: null,
                        array: true
                    }
                },
                templateUrl: "/hostescalations/index.html",
                controller: "HostescalationsIndexController"
            })

            .state('HostescalationsAdd', {
                url: '/hostescalations/add',
                templateUrl: "/hostescalations/add.html",
                controller: "HostescalationsAddController"
            })

            .state('HostescalationsEdit', {
                url: '/hostescalations/edit/:id',
                templateUrl: "/hostescalations/edit.html",
                controller: "HostescalationsEditController"
            })

            .state('HosttemplatesIndex', {
                url: '/hosttemplates/index',
                params: {
                    id: {
                        value: null,
                        array: true
                    }
                },
                templateUrl: "/hosttemplates/index.html",
                controller: "HosttemplatesIndexController"
            })

            .state('HosttemplatesAdd', {
                url: '/hosttemplates/add',
                templateUrl: "/hosttemplates/add.html",
                controller: "HosttemplatesAddController"
            })

            .state('HosttemplatesEdit', {
                url: '/hosttemplates/edit/:id',
                templateUrl: "/hosttemplates/edit.html",
                controller: "HosttemplatesEditController"
            })

            .state('HosttemplatesCopy', {
                url: '/hosttemplates/copy/:ids',
                templateUrl: "/hosttemplates/copy.html",
                controller: "HosttemplatesCopyController"
            })

            .state('HosttemplatesUsedBy', {
                url: '/hosttemplates/usedBy/:id',
                templateUrl: "/hosttemplates/usedBy.html",
                controller: "HosttemplatesUsedByController"
            })

            .state('UsersIndex', {
                url: '/users/index',
                templateUrl: "/users/index.html",
                controller: "UsersIndexController"
            })

            .state('UsersAdd', {
                url: '/users/add',
                templateUrl: "/users/add.html",
                controller: "UsersAddController"
            })

            .state('UsersEdit', {
                url: '/users/edit/:id',
                templateUrl: "/users/edit.html",
                controller: "UsersEditController"
            })

            .state('UsersLdap', {
                url: '/users/ldap',
                templateUrl: "/users/addFromLdap.html",
                controller: "UsersLdapController"
            })

            .state('UsersEditFromLdap', {
                url: '/users/editFromLdap/:id',
                templateUrl: "/users/editFromLdap.html",
                controller: "UsersEditFromLdapController"
            })

            .state('UsercontainerrolesIndex', {
                url: '/usercontainerroles/index',
                templateUrl: "/usercontainerroles/index.html",
                controller: "UsercontainerrolesIndexController"
            })

            .state('UsercontainerrolesAdd', {
                url: '/usercontainerroles/add',
                templateUrl: "/usercontainerroles/add.html",
                controller: "UsercontainerrolesAddController"
            })

            .state('UsercontainerrolesEdit', {
                url: '/usercontainerroles/edit/:id',
                templateUrl: "/usercontainerroles/edit.html",
                controller: "UsercontainerrolesEditController"
            })

            .state('UsergroupsIndex', {
                url: '/usergroups/index',
                templateUrl: "/usergroups/index.html",
                controller: "UsergroupsIndexController"
            })

            .state('UsergroupsAdd', {
                url: '/usergroups/add',
                templateUrl: "/usergroups/add.html",
                controller: "UsergroupsAddController"
            })

            .state('UsergroupsEdit', {
                url: '/usergroups/edit/:id',
                templateUrl: "/usergroups/edit.html",
                controller: "UsergroupsEditController"
            })

            .state('ProfileEdit', {
                url: '/profile/edit/',
                templateUrl: "/profile/edit.html",
                controller: "ProfileEditController"
            })

            .state('HostsIndex', {
                url: '/hosts/index?hostname&hoststate&sort&direction&BrowserContainerId',
                templateUrl: "/hosts/index.html",
                params: {
                    hostname: {
                        value: null
                    },
                    hoststate: {
                        value: null,
                        array: true,
                        squash: true
                    },
                    sort: {
                        value: null
                    },
                    direction: {
                        value: null
                    },
                    BrowserContainerId: {
                        value: null
                    },
                    id: {
                        value: null,
                        array: true
                    },
                    has_been_acknowledged: {
                        value: null
                    },
                    has_not_been_acknowledged: {
                        value: null
                    },
                    in_downtime: {
                        value: null
                    },
                    not_in_downtime: {
                        value: null
                    }
                },
                controller: "HostsIndexController"
            })

            .state('HostsNotMonitored', {
                url: '/hosts/notMonitored',
                templateUrl: "/hosts/notMonitored.html",
                controller: "HostsNotMonitoredController"
            })

            .state('HostsDisabled', {
                url: '/hosts/disabled',
                templateUrl: "/hosts/disabled.html",
                controller: "HostsDisabledController"
            })

            .state('HostsBrowser', {
                url: '/hosts/browser/:id',
                templateUrl: "/hosts/browser.html",
                controller: "HostsBrowserController"
            })

            .state('HostsAdd', {
                url: '/hosts/add',
                templateUrl: "/hosts/add.html",
                controller: "HostsAddController"
            })

            .state('HostsEdit', {
                url: '/hosts/edit/:id',
                templateUrl: "/hosts/edit.html",
                controller: "HostsEditController"
            })

            .state('HostsSharing', {
                url: '/hosts/sharing/:id',
                templateUrl: "/hosts/sharing.html",
                controller: "HostsSharingController"
            })

            .state('HostsCopy', {
                url: '/hosts/copy/:ids',
                templateUrl: "/hosts/copy.html",
                controller: "HostsCopyController"
            })

            .state('HostsEditDetails', {
                url: '/hosts/edit_details/:ids',
                templateUrl: "/hosts/edit_details.html",
                controller: "HostsEditDetailsController"
            })

            .state('HostdependenciesIndex', {
                url: '/hostdependencies/index',
                params: {
                    id: {
                        value: null,
                        array: true
                    }
                },
                templateUrl: "/hostdependencies/index.html",
                controller: "HostdependenciesIndexController"
            })

            .state('HostdependenciesAdd', {
                url: '/hostdependencies/add',
                templateUrl: "/hostdependencies/add.html",
                controller: "HostdependenciesAddController"
            })

            .state('HostdependenciesEdit', {
                url: '/hostdependencies/edit/:id',
                templateUrl: "/hostdependencies/edit.html",
                controller: "HostdependenciesEditController"
            })

            .state('ServicetemplatesIndex', {
                url: '/servicetemplates/index',
                params: {
                    id: {
                        value: null,
                        array: true
                    }
                },
                templateUrl: "/servicetemplates/index.html",
                controller: "ServicetemplatesIndexController"
            })

            .state('ServicetemplatesAdd', {
                url: '/servicetemplates/add',
                params: {
                    servicetemplateTypeId: {
                        value: null,
                        squash: true
                    }
                },
                templateUrl: "/servicetemplates/add.html",
                controller: "ServicetemplatesAddController"
            })

            .state('ServicetemplatesEdit', {
                url: '/servicetemplates/edit/:id',
                params: {
                    servicetemplateTypeId: {
                        value: null,
                        squash: true
                    }
                },
                templateUrl: "/servicetemplates/edit.html",
                controller: "ServicetemplatesEditController"
            })

            .state('ServicetemplatesCopy', {
                url: '/servicetemplates/copy/:ids',
                templateUrl: "/servicetemplates/copy.html",
                controller: "ServicetemplatesCopyController"
            })

            .state('ServicetemplatesUsedBy', {
                url: '/servicetemplates/usedBy/:id',
                templateUrl: "/servicetemplates/usedBy.html",
                controller: "ServicetemplatesUsedByController"
            })

            .state('ServicetemplategroupsIndex', {
                url: '/servicetemplategroups/index',
                templateUrl: "/servicetemplategroups/index.html",
                controller: "ServicetemplategroupsIndexController"
            })

            .state('ServicetemplategroupsAdd', {
                url: '/servicetemplategroups/add/:ids',
                params: {
                    ids: {
                        value: null,
                        squash: true
                    }
                },
                templateUrl: "/servicetemplategroups/add.html",
                controller: "ServicetemplategroupsAddController",
            })

            .state('ServicetemplategroupsAppend', {
                url: '/servicetemplategroups/append/:ids',
                templateUrl: "/servicetemplategroups/append.html",
                controller: "ServicetemplategroupsAppendController"
            })

            .state('ServicetemplategroupsEdit', {
                url: '/servicetemplategroups/edit/:id',
                templateUrl: "/servicetemplategroups/edit.html",
                controller: "ServicetemplategroupsEditController"
            })

            .state('ServicetemplategroupsAllocateToHost', {
                url: '/servicetemplategroups/allocateToHost/:id/:hostId',
                params: {
                    id: {
                        value: null,
                        squash: true
                    },
                    hostId: {
                        value: null,
                        squash: true
                    }
                },
                templateUrl: "/servicetemplategroups/allocateToHost.html",
                controller: "ServicetemplategroupsAllocateToHostController"
            })

            .state('ServicetemplategroupsAllocateToHostgroup', {
                url: '/servicetemplategroups/allocateToHostgroup/:id/:hostgroupId',
                params: {
                    id: {
                        value: null,
                        squash: true
                    },
                    hostgroupId: {
                        value: null,
                        squash: true
                    }
                },
                templateUrl: "/servicetemplategroups/allocateToHostgroup.html",
                controller: "ServicetemplategroupsAllocateToHostgroupController"
            })

            .state('LocationsIndex', {
                url: '/locations/index',
                templateUrl: "/locations/index.html",
                controller: "LocationsIndexController"
            })

            .state('LocationsAdd', {
                url: '/locations/add',
                templateUrl: "/locations/add.html",
                controller: "LocationsAddController"
            })

            .state('LocationsEdit', {
                url: '/locations/edit/:id',
                templateUrl: "/locations/edit.html",
                controller: "LocationsEditController"
            })

            .state('SystemfailuresIndex', {
                url: '/systemfailures/index',
                templateUrl: "/systemfailures/index.html",
                controller: "SystemfailuresIndexController"
            })

            .state('SystemfailuresAdd', {
                url: '/systemfailures/add',
                templateUrl: "/systemfailures/add.html",
                controller: "SystemfailuresAddController"
            })

            .state('NagiostatsIndex', {
                url: '/nagiostats/index',
                templateUrl: "/nagiostats/index.html",
                controller: "NagiostatsIndexController"
            })

            .state('CalendarsIndex', {
                url: '/calendars/index',
                templateUrl: "/calendars/index.html",
                controller: "CalendarsIndexController"
            })

            .state('CalendarsAdd', {
                url: '/calendars/add',
                templateUrl: "/calendars/add.html",
                controller: "CalendarsAddController"
            })

            .state('CalendarsEdit', {
                url: '/calendars/edit/:id',
                templateUrl: "/calendars/edit.html",
                controller: "CalendarsEditController"
            })

            .state('AutomapsIndex', {
                url: '/automaps/index',
                templateUrl: "/automaps/index.html",
                controller: "AutomapsIndexController"
            })

            .state('AutomapsAdd', {
                url: '/automaps/add',
                templateUrl: "/automaps/add.html",
                controller: "AutomapsAddController"
            })

            .state('AutomapsEdit', {
                url: '/automaps/edit/:id',
                templateUrl: "/automaps/edit.html",
                controller: "AutomapsEditController"
            })

            .state('AutomapsView', {
                url: '/automaps/view/:id',
                templateUrl: "/automaps/view.html",
                controller: "AutomapsViewController"
            })

            .state('SearchIndex', {
                url: '/search/index',
                templateUrl: "/search/index.html",
                controller: "SearchIndexController"
            })

            .state('PackageManagerIndex', {
                url: '/packetmanager/index',
                templateUrl: "/packetmanager/index.html",
                controller: "PackagemanagerIndexController"
            })

            .state('ChangelogsIndex', {
                url: '/changelogs/index',
                templateUrl: "/changelogs/index.html",
                controller: "ChangelogsIndexController"
            })

    })

    /*
    .config(function($urlRouterProvider, $stateProvider){
        //$urlRouterProvider.otherwise("/dashboard");

        $stateProvider
            .state('HostgroupsIndex', {
                url: '/hostgroups/index',
                templateUrl: "/hostgroups/indexAngular.html",
                controller: "HostgroupsIndexController"
            })
    })
    */

    /*
    .config(function($routeProvider){
        $routeProvider
            .when("/hostgroups/index", {
                templateUrl : "/hostgroups/indexAngular.html",
                controller : "HostgroupsIndexController"
            })
            .otherwise({
                template : "<h1>None</h1><p>Nothing has been selected</p>"
            });
    })
    */


    .filter('hostStatusName', function(){
        return function(hoststatusId){
            if(typeof hoststatusId === 'undefined'){
                return false;
            }

            switch(hoststatusId){
                case 0:
                case '0':
                    return 'Up';
                case 1:
                case '1':
                    return 'Down';
                case 2:
                case '2':
                    return 'Unreachable';
                default:
                    return 'Not in monitoring';
            }

        }
    })

    .filter('serviceStatusName', function(){
        return function(servicestatusId){
            if(typeof servicestatusId === 'undefined'){
                return false;
            }

            switch(servicestatusId){
                case 0:
                case '0':
                    return 'Ok';
                case 1:
                case '1':
                    return 'Warning';
                case 2:
                case '2':
                    return 'Critical';
                case 3:
                case '3':
                    return 'Unknown';
                default:
                    return 'Not in monitoring';
            }

        }
    })

    .filter('encodeURI', function(){
        return function(str){
            return encodeURI(str);
        }
    })

    .filter('highlight', function($sce){
        return function(title, searchString){
            searchString = searchString.replace(/\s/g, "");
            var newSearchString = "";
            for(var i = 0; i < searchString.length; i++){
                newSearchString += searchString.charAt(i) + "\\s*";
            }
            if(searchString) title = title.replace(new RegExp('(' + newSearchString + ')', 'gi'),
                '<span class="search-highlight">$1</span>');

            return $sce.trustAsHtml(title)
        }
    })

    .filter('trustAsHtml', function($sce){
        return function(text){
            return $sce.trustAsHtml(text);
        };
    })

    .filter('underscoreless', function () {
        return function (input) {
            return input.replace(/_/g, ' ');
        };
    })

    .filter('capitalizeFirstLetter', function () {
        return function (input) {
            return input.charAt(0).toUpperCase() + input.slice(1);
        };
    })

    .run(function($rootScope, SortService, $state){

        $rootScope.$on('$stateChangeStart', function(event, to, toParams, from, fromParams){
            from.params = fromParams;
            $state.previous = from;
        });

        $rootScope.runningAjaxCalls = 0;

        $rootScope.currentStateForApi = function(current_state){
            var states = [];
            for(var key in current_state){
                if(current_state[key] === true){
                    states.push(key);
                }
            }
            return states;
        };

        $rootScope.getSortClass = function(field){
            if(field === SortService.getSort()){
                if(SortService.getDirection() === 'asc'){
                    return 'fa-sort-asc';
                }
                return 'fa-sort-desc';
            }

            return 'fa-sort';
        };

        $rootScope.orderBy = function(field){
            if(field !== SortService.getSort()){
                SortService.setDirection('asc');
                SortService.setSort(field);
                SortService.triggerReload();
                return;
            }

            if(SortService.getDirection() === 'asc'){
                SortService.setDirection('desc');
            }else{
                SortService.setDirection('asc');
            }
            SortService.triggerReload();
        };

    })
;
