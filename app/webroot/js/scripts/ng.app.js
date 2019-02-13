angular.module('openITCOCKPIT', ['gridster', 'ui.router'])

    .factory("httpInterceptor", function($q, $rootScope, $timeout){
        return {
            response: function(result){
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
                if(response.hasOwnProperty('params')){
                    if(response.params.hasOwnProperty('disableGlobalLoader')){
                        onlyShowMenuLoader = true;
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
        $stateProvider

            .state('AdministratorsQuerylog', {
                url: '/Administrators/querylog',
                templateUrl: "/Administrators/querylog.html",
                controller: "AdministratorsQuerylogController"
            })

            .state('AutomapsView', {
                url: '/automaps/view/:id',
                templateUrl: "/automaps/view.html",
                controller: "AutomapsViewController"
            })

            .state('BrowsersIndex', {
                url: '/browsers/index',
                templateUrl: "/browsers/index.html",
                controller: "BrowsersIndexController"
            })

            .state('ConfigurationFilesIndex', {
                url: '/ConfigurationFiles/index',
                templateUrl: "/ConfigurationFiles/index.html",
                controller: "ConfigurationFilesIndexController"
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

            .state('HostsIndex', {
                url: '/hosts/index',
                templateUrl: "/hosts/index.html",
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
                url: '/services/index',
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
                url: '/systemdowntimes/addHostgroupdowntime',
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

            .state('HostgroupsIndex', {
                url: '/hostgroups/index',
                templateUrl: "/hostgroups/index.html",
                controller: "HostgroupsIndexController"
            })

            .state('HostgroupsAdd', {
                url: '/hostgroups/add',
                templateUrl: "/hostgroups/add.html",
                controller: "HostgroupsAddController"
            })

            .state('HostgroupsEdit', {
                url: '/hostgroups/edit/:id',
                templateUrl: "/hostgroups/edit.html",
                controller: "HostgroupsEditController"
            })

            .state('HostgroupsExtended', {
                url: '/hostgroups/extended',
                templateUrl: "/hostgroups/extended.html",
                controller: "HostgroupsExtendedController"
            })

            .state('HostchecksIndex', {
                url: '/hostchecks/index/:id',
                templateUrl: "/hostchecks/index.html",
                controller: "HostchecksIndexController"
            })

            .state('ServicegroupsIndex', {
                url: '/servicegroups/index',
                templateUrl: "/servicegroups/index.html",
                controller: "ServicegroupsIndexController"
            })

            .state('ServicegroupsAdd', {
                url: '/servicegroups/add',
                templateUrl: "/servicegroups/add.html",
                controller: "ServicegroupsAddController"
            })

            .state('ServicegroupsEdit', {
                url: '/servicegroups/edit/:id',
                templateUrl: "/servicegroups/edit.html",
                controller: "ServicegroupsEditController"
            })

            .state('ServicegroupsExtended', {
                url: '/servicegroups/extended',
                templateUrl: "/servicegroups/extended.html",
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

            .state('CommandsIndex', {
                url: '/commands/index',
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

            .state('DocumentationsView', {
                url: '/documentations/view/:uuid/:type',
                templateUrl: "/documentations/view.html",
                controller: "DocumentationsViewController"
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
                templateUrl: "/contacts/ldap.html",
                controller: "ContactsLdapController"
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
            let newSearchString = "";
            for (var i = 0; i < searchString.length; i++) {
                newSearchString += searchString.charAt(i)+"\\s*";
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

    .run(function($rootScope, SortService){

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
