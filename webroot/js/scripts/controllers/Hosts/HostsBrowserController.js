angular.module('openITCOCKPIT')
    .controller('HostsBrowserController', function($scope, $rootScope, $http, QueryStringService, $stateParams, $state, SortService, $interval, StatusHelperService, UuidService, MassChangeService){

        //$scope.id = QueryStringService.getCakeId();
        $scope.id = $stateParams.id;

        $scope.activeTab = 'active';
        SortService.setSort('Servicestatus.current_state');
        SortService.setDirection('desc');
        $scope.currentPage = 1;
        $scope.selectedTab = 'tab1';

        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/services/delete/';
        $scope.deactivateUrl = '/services/deactivate/';
        $scope.activateUrl = '/services/enable/';

        $scope.parentHostProblems = {};
        $scope.hasParentHostProblems = false;

        $scope.showFlashSuccess = false;

        $scope.canSubmitExternalCommands = false;

        $scope.tags = [];

        $scope.pingResult = [];

        $scope.priorityClasses = {
            1: 'ok-soft',
            2: 'ok',
            3: 'warning',
            4: 'critical-soft',
            5: 'critical'
        };

        //There is no service status for not monitored services :)
        $scope.fakeServicestatus = {
            Servicestatus: {
                currentState: 5
            }
        };

        $scope.activeServiceFilter = {
            Servicestatus: {
                current_state: {
                    ok: false,
                    warning: false,
                    critical: false,
                    unknown: false
                },
                output: ''
            },
            Service: {
                name: QueryStringService.getValue('filter[Service.servicename]', '')
            }
        };

        $scope.init = true;

        $scope.hostStatusTextClass = 'txt-primary';

        $scope.visTimeline = null;
        $scope.visTimelineInit = true;
        $scope.visTimelineStart = -1;
        $scope.visTimelineEnd = -1;
        $scope.visTimeout = null;
        $scope.visChangeTimeout = null;
        $scope.showTimelineTab = false;
        $scope.timelineIsLoading = false;
        $scope.failureDurationInPercent = null;
        $scope.lastLoadDate = Date.now();       //required for status color update in service-browser-menu

        $scope.selectedGrafanaTimerange = 'now-3h';
        $scope.selectedGrafanaAutorefresh = '60s';

        $scope.showFlashMsg = function(){
            new Noty({
                theme: 'metroui',
                type: 'success',
                layout: 'topCenter',
                text: $scope.flashMshStr,
                timeout: 4000
            }).show();

            $scope.showFlashSuccess = true;
            $scope.autoRefreshCounter = 5;
            var interval = $interval(function(){
                $scope.autoRefreshCounter--;
                if($scope.autoRefreshCounter === 0){
                    $scope.loadHost();
                    $interval.cancel(interval);
                    $scope.showFlashSuccess = false;
                }
            }, 1000);
        };

        $scope.hostBrowserMenuReschedulingCallback = function(){
            $scope.rescheduleHost($scope.getObjectsForExternalCommand());
        };

        $scope.loadHost = function(){
            $scope.lastLoadDate = Date.now();
            $http.get("/hosts/browser/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.mergedHost = result.data.mergedHost;
                $scope.checkCommand = result.data.checkCommand;
                $scope.areContactsFromHost = result.data.areContactsFromHost;
                $scope.areContactsInheritedFromHosttemplate = result.data.areContactsInheritedFromHosttemplate;
                $scope.checkPeriod = result.data.checkPeriod;
                $scope.notifyPeriod = result.data.notifyPeriod;


                $scope.mergedHost.disabled = parseInt($scope.mergedHost.disabled, 10);
                $scope.tags = $scope.mergedHost.tags.split(',');
                $scope.hoststatus = result.data.hoststatus;
                $scope.hoststateForIcon = {
                    Hoststatus: $scope.hoststatus
                };

                $scope.mainContainer = result.data.mainContainer;
                $scope.sharedContainers = result.data.sharedContainers;
                $scope.hostStatusTextClass = getHoststatusTextColor();

                $scope.parenthosts = result.data.parenthosts;
                $scope.parentHoststatus = result.data.parentHostStatus;
                buildParentHostProblems();

                $scope.acknowledgement = result.data.acknowledgement;

                $scope.downtime = result.data.downtime;

                $scope.canSubmitExternalCommands = result.data.canSubmitExternalCommands;

                $scope.priorities = {
                    1: false,
                    2: false,
                    3: false,
                    4: false,
                    5: false
                };
                var priority = parseInt($scope.mergedHost.priority, 10);
                for(var i = 1; i <= priority; i++){
                    $scope.priorities[i] = true;
                }

                $scope.load();
                $scope.loadGrafanaIframeUrl();
                $scope.loadAdditionalInformation();


                if(typeof $scope.hostBrowserMenuConfig === "undefined"){
                    $scope.hostBrowserMenuConfig = {
                        autoload: true,
                        hostId: $scope.mergedHost.id,
                        includeHoststatus: true,
                        showReschedulingButton: true,
                        rescheduleCallback: $scope.hostBrowserMenuReschedulingCallback,
                        showBackButton: false
                    };
                }

                $scope.init = false;
            });
        };

        $scope.loadTimezone = function(){
            $http.get("/angular/user_timezone.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.timezone = result.data.timezone;
            });
        };

        $scope.changeTab = function(tab){
            if(tab !== $scope.activeTab){
                $scope.services = [];
                $scope.activeTab = tab;

                SortService.setSort('servicename');
                SortService.setDirection('asc');
                $scope.currentPage = 1;

                $scope.load();
            }

        };

        $scope.load = function(){
            switch($scope.activeTab){
                case 'active':
                    $scope.loadActiveServices();
                    break;

                case 'notMonitored':
                    $scope.loadNotMonitoredServices();
                    break;

                case 'disabled':
                    $scope.loadDisabledServices();
                    break;
            }
        };

        $scope.loadActiveServices = function(){
            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosts.id]': $scope.id,
                'filter[servicename]': $scope.activeServiceFilter.Service.name,
                'filter[Servicestatus.output]': $scope.activeServiceFilter.Servicestatus.output,
                'filter[Servicestatus.current_state][]': $rootScope.currentStateForApi($scope.activeServiceFilter.Servicestatus.current_state),
                'filter[Service.disabled]': false
            };

            $http.get("/services/index.json", {
                params: params
            }).then(function(result){
                $scope.services = [];
                $scope.services = result.data.all_services;

                $scope.paging = result.data.paging;
            });
        };

        $scope.loadNotMonitoredServices = function(){
            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosts.id]': $scope.id
            };

            $http.get("/services/notMonitored.json", {
                params: params
            }).then(function(result){
                $scope.services = [];
                $scope.services = result.data.all_services;

                $scope.paging = result.data.paging;
            });
        };

        $scope.loadDisabledServices = function(){
            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosts.id]': $scope.id
            };

            $http.get("/services/disabled.json", {
                params: params
            }).then(function(result){
                $scope.services = [];
                $scope.services = result.data.all_services;

                $scope.paging = result.data.paging;
            });
        };

        $scope.getObjectForDelete = function(hostname, service){
            var object = {};
            object[service.Service.id] = hostname + '/' + service.Service.servicename;
            return object;
        };

        $scope.getObjectForDowntimeDelete = function(){
            var object = {};
            object[$scope.downtime.internalDowntimeId] = $scope.mergedHost.name;
            return object;
        };

        $scope.getObjectsForExternalCommand = function(){
            return [{
                Host: $scope.mergedHost
            }];
        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.undoSelection();
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.stateIsUp = function(){
            return parseInt($scope.hoststatus.currentState, 10) === 0;
        };

        $scope.stateIsDown = function(){
            return parseInt($scope.hoststatus.currentState, 10) === 1;
        };

        $scope.stateIsUnreachable = function(){
            return parseInt($scope.hoststatus.currentState, 10) === 2;
        };

        $scope.stateIsNotInMonitoring = function(){
            return !$scope.hoststatus.isInMonitoring;
        };


        var getHoststatusTextColor = function(){
            return StatusHelperService.getHoststatusTextColor($scope.hoststatus.currentState);
        };

        var buildParentHostProblems = function(){
            $scope.hasParentHostProblems = false;
            for(var key in $scope.parenthosts){
                var parentHostUuid = $scope.parenthosts[key].uuid;
                if($scope.parentHoststatus.hasOwnProperty(parentHostUuid)){
                    if($scope.parentHoststatus[parentHostUuid].currentState > 0){
                        $scope.parentHostProblems[parentHostUuid] = {
                            id: $scope.parenthosts[key].id,
                            name: $scope.parenthosts[key].name,
                            state: $scope.parentHoststatus[parentHostUuid].currentState
                        };
                        $scope.hasParentHostProblems = true;
                    }
                }
            }
        };

        $scope.loadTimelineData = function(_properties){
            var properties = _properties || {};
            var start = properties.start || -1;
            var end = properties.end || -1;

            $scope.timelineIsLoading = true;

            if(start > $scope.visTimelineStart && end < $scope.visTimelineEnd){
                $scope.timelineIsLoading = false;
                //Zoom in data we already have
                return;
            }

            $http.get("/hosts/timeline/" + $scope.id + ".json", {
                params: {
                    'angular': true,
                    start: start,
                    end: end
                }
            }).then(function(result){

                var timelinedata = {
                    items: new vis.DataSet(result.data.statehistory),
                    groups: new vis.DataSet(result.data.groups)
                };
                timelinedata.items.add(result.data.downtimes);
                timelinedata.items.add(result.data.notifications);
                timelinedata.items.add(result.data.acknowledgements);
                timelinedata.items.add(result.data.timeranges);

                $scope.visTimelineStart = result.data.start;
                $scope.visTimelineEnd = result.data.end;

                var options = {
                    //orientation: "bottom",
                    orientation: "both",
                    xss: {
                        disabled: false,
                        filterOptions: {
                            whiteList: {i: ['class', 'not-xss-filtered-html']},
                        },
                    },
                    //showCurrentTime: true,
                    start: new Date(result.data.start * 1000),
                    end: new Date(result.data.end * 1000),
                    min: new Date(new Date(result.data.start * 1000).setFullYear(new Date(result.data.start * 1000).getFullYear() - 1)), //May 1 year of zoom
                    max: new Date(result.data.end * 1000),    // upper limit of visible range
                    zoomMin: 1000 * 10 * 60 * 5,   // every 5 minutes
                    format: {
                        minorLabels: {
                            millisecond: 'SSS',
                            second: 's',
                            minute: 'H:mm',
                            hour: 'H:mm',
                            weekday: 'ddd D',
                            day: 'D',
                            week: 'w',
                            month: 'MMM',
                            year: 'YYYY'
                        },
                        majorLabels: {
                            millisecond: 'H:mm:ss',
                            second: 'D MMMM H:mm',
                            // minute:     'ddd D MMMM',
                            // hour:       'ddd D MMMM',
                            minute: 'DD.MM.YYYY',
                            hour: 'DD.MM.YYYY',
                            weekday: 'MMMM YYYY',
                            day: 'MMMM YYYY',
                            week: 'MMMM YYYY',
                            month: 'YYYY',
                            year: ''
                        }
                    }
                };
                renderTimeline(timelinedata, options);
                $scope.timelineIsLoading = false;
            });
        };

        var renderTimeline = function(timelinedata, options){
            var container = document.getElementById('visualization');
            if($scope.visTimeline === null){
                $scope.visTimeline = new vis.Timeline(container, timelinedata.items, timelinedata.groups, options);
                $scope.visTimeline.on('rangechanged', function(properties){
                    if($scope.visTimelineInit){
                        $scope.visTimelineInit = false;
                        return;
                    }

                    if($scope.timelineIsLoading){
                        console.warn('Timeline already loading date. Waiting for server result before sending next request.');
                        return;
                    }

                    if($scope.visTimeout){
                        clearTimeout($scope.visTimeout);
                    }
                    $scope.visTimeout = setTimeout(function(){
                        $scope.visTimeout = null;
                        $scope.loadTimelineData({
                            start: parseInt(properties.start.getTime() / 1000, 10),
                            end: parseInt(properties.end.getTime() / 1000, 10)
                        });
                    }, 500);
                });
            }else{
                //Update existing timeline
                $scope.visTimeline.setItems(timelinedata.items);
            }

            $scope.visTimeline.on('changed', function(){
                if($scope.visTimelineInit){
                    return;
                }
                if($scope.visChangeTimeout){
                    clearTimeout($scope.visChangeTimeout);
                }
                $scope.visChangeTimeout = setTimeout(function(){
                    $scope.visChangeTimeout = null;
                    var timeRange = $scope.visTimeline.getWindow();
                    var visTimelineStartAsTimestamp = new Date(timeRange.start).getTime();
                    var visTimelineEndAsTimestamp = new Date(timeRange.end).getTime();
                    var criticalItems = $scope.visTimeline.itemsData.get({
                        fields: ['start', 'end', 'className', 'group'],    // output the specified fields only
                        type: {
                            start: 'Date',
                            end: 'Date'
                        },
                        filter: function(item){
                            return (item.group == 5 &&
                                (item.className === 'bg-down' || item.className === 'bg-down-soft') &&
                                $scope.CheckIfItemInRange(
                                    visTimelineStartAsTimestamp,
                                    visTimelineEndAsTimestamp,
                                    item
                                )
                            );

                        }
                    });
                    $scope.failureDurationInPercent = $scope.calculateFailures(
                        (visTimelineEndAsTimestamp - visTimelineStartAsTimestamp), //visible time range
                        criticalItems,
                        visTimelineStartAsTimestamp,
                        visTimelineEndAsTimestamp
                    );
                    $scope.$apply();
                }, 500);

            });
        };

        $scope.showTimeline = function(){
            $scope.showTimelineTab = true;
            $scope.loadTimelineData();
        };

        $scope.hideTimeline = function(){
            $scope.showTimelineTab = false;
        };

        $scope.CheckIfItemInRange = function(start, end, item){
            var itemStart = item.start.getTime();
            var itemEnd = item.end.getTime();
            if(itemEnd < start){
                return false;
            }else if(itemStart > end){
                return false;
            }else if(itemStart >= start && itemEnd <= end){
                return true;
            }else if(itemStart >= start && itemEnd > end){ //item started behind the start and ended behind the end
                return true;
            }else if(itemStart < start && itemEnd > start && itemEnd < end){ //item started before the start and ended behind the end
                return true;
            }else if(itemStart < start && itemEnd >= end){ // item startet before the start and enden before the end
                return true;
            }
            return false;
        };

        $scope.calculateFailures = function(totalTime, criticalItems, start, end){
            var failuresDuration = 0;

            criticalItems.forEach(function(criticalItem){
                var itemStart = criticalItem.start.getTime();
                var itemEnd = criticalItem.end.getTime();
                failuresDuration += ((itemEnd > end) ? end : itemEnd) - ((itemStart < start) ? start : itemStart);
            });
            return (failuresDuration / totalTime * 100).toFixed(3);
        };

        $scope.loadGrafanaIframeUrl = function(){
            $http.get("/hosts/getGrafanaIframeUrlForDatepicker/.json", {
                params: {
                    'uuid': $scope.mergedHost.uuid,
                    'angular': true,
                    'from': $scope.selectedGrafanaTimerange,
                    'refresh': $scope.selectedGrafanaAutorefresh
                }
            }).then(function(result){
                $scope.GrafanaDashboardExists = result.data.GrafanaDashboardExists;
                $scope.GrafanaIframeUrl = result.data.iframeUrl;
            });
        };

        $scope.grafanaTimepickerCallback = function(selectedTimerange, selectedAutorefresh){
            $scope.selectedGrafanaTimerange = selectedTimerange;
            $scope.selectedGrafanaAutorefresh = selectedAutorefresh;
            $scope.loadGrafanaIframeUrl();
        };

        $scope.loadIdOrUuid = function(){
            if(UuidService.isUuid($scope.id)){
                // UUID was passed via URL
                $http.get("/hosts/byUuid/" + $scope.id + ".json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.id = result.data.host.id;
                    $scope.loadHost();
                }, function errorCallback(result){
                    if(result.status === 403){
                        $state.go('403');
                    }

                    if(result.status === 404){
                        $state.go('404');
                    }
                });

            }else{
                // Integer id was passed via URL
                $scope.loadHost();
            }
        };

        $scope.loadAdditionalInformation = function(){
            $http.get("/hosts/loadAdditionalInformation/.json", {
                params: {
                    'id': $scope.mergedHost.id,
                    'angular': true
                }
            }).then(function(result){
                $scope.AdditionalInformationExists = result.data.AdditionalInformationExists;
            });
        };


        /*** Service mass change methods ***/
        $scope.selectAll = function(){
            if($scope.services){
                for(var key in $scope.services){
                    if($scope.services[key].Service.allow_edit){
                        var id = $scope.services[key].Service.id;
                        $scope.massChange[id] = true;
                    }
                }
            }
        };

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.getServiceObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.services){
                for(var id in selectedObjects){
                    if(id == $scope.services[key].Service.id){
                        objects[id] =
                            $scope.services[key].Host.hostname + '/' +
                            $scope.services[key].Service.servicename;
                    }

                }
            }
            return objects;
        };

        $scope.getServiceObjectsForExternalCommand = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.services){
                for(var id in selectedObjects){
                    if(id == $scope.services[key].Service.id){
                        objects[id] = $scope.services[key];
                    }
                }
            }
            return objects;
        };

        $scope.linkForCopy = function(){
            var ids = Object.keys(MassChangeService.getSelected());
            return ids.join(',');
        };
        /*** End of service mass change methods ***/

        //Fire on page load
        $scope.loadIdOrUuid();
        $scope.loadTimezone();
        SortService.setCallback($scope.load);

        $scope.$watch('activeServiceFilter', function(){
            if($scope.init){
                return;
            }
            $scope.undoSelection();
            $scope.currentPage = 1;
            $scope.load();
        }, true);

        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

    });
