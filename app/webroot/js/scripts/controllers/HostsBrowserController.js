angular.module('openITCOCKPIT')
    .controller('HostsBrowserController', function($scope, $rootScope, $http, QueryStringService, SortService, $interval){

        $scope.id = QueryStringService.getCakeId();

        $scope.activeTab = 'active';
        SortService.setSort('Servicestatus.current_state');
        SortService.setDirection('desc');
        $scope.currentPage = 1;

        $scope.deleteUrl = '/services/delete/';
        $scope.deactivateUrl = '/services/deactivate/';
        $scope.activateUrl = '/services/enable/';

        $scope.parentHostProblems = {};
        $scope.hasParentHostProblems = false;

        $scope.showFlashSuccess = false;

        $scope.canSubmitExternalCommands = false;

        $scope.tags = [];

        $scope.pingResult = [];

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

        var flappingInterval;

        $scope.showFlashMsg = function(){
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

        $scope.loadHost = function(){
            $http.get("/hosts/browser/"+$scope.id+".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.mergedHost = result.data.mergedHost;
                $scope.mergedHost.Host.disabled = parseInt($scope.mergedHost.Host.disabled, 10);
                $scope.tags = $scope.mergedHost.Host.tags.split(',');
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
                var priority = parseInt($scope.mergedHost.Host.priority, 10);
                for(var i = 1; i <= priority; i++){
                    $scope.priorities[i] = true;
                }

                $scope.load();

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

                SortService.setSort('Service.servicename');
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
                'filter[Host.id]': $scope.id,
                'filter[Service.servicename]': $scope.activeServiceFilter.Service.name,
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
                'filter[Host.id]': $scope.id
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
                'filter[Host.id]': $scope.id
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
            object[$scope.downtime.internalDowntimeId] = $scope.mergedHost.Host.name;
            return object;
        };

        $scope.getObjectsForExternalCommand = function(){
            return [$scope.mergedHost];
        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.mouseenter = function($event, hostUuid, service){
            $scope.isLoadingGraph = true;
            var offset = {
                top: $event.relatedTarget.offsetTop + 40,
                left: $event.relatedTarget.offsetLeft + 40
            };

            offset.top += $event.relatedTarget.offsetParent.offsetTop;

            var currentScrollPosition = $(window).scrollTop();

            var margin = 15;
            var $popupGraphContainer = $('#serviceGraphContainer');


            if((offset.top - currentScrollPosition + margin + $popupGraphContainer.height()) > $(window).innerHeight()){
                //There is no space in the window for the popup, we need to set it to an higher point
                $popupGraphContainer.css({
                    'top': parseInt(offset.top - $popupGraphContainer.height() - margin + 10),
                    'left': parseInt(offset.left + margin),
                    'padding': '6px'
                });
            }else{
                //Default Popup
                $popupGraphContainer.css({
                    'top': parseInt(offset.top + margin),
                    'left': parseInt(offset.left + margin),
                    'padding': '6px'
                });
            }

            $popupGraphContainer.show();
            loadGraph(hostUuid, service);
        };

        $scope.mouseleave = function(){
            $('#serviceGraphContainer').hide();
            $('#serviceGraphFlot').html('');
        };

        var loadGraph = function(hostUuid, service){
            $http.get('/Graphgenerators/getPerfdataByUuid.json', {
                params: {
                    angular: true,
                    host_uuid: hostUuid,
                    service_uuid: service.Service.uuid,
                    hours: 4,
                    jsTimestamp: 1
                }
            }).then(function(result){
                $scope.isLoadingGraph = false;
                renderGraph(result.data.performance_data);
            });
        };

        var renderGraph = function(performance_data){
            var graph_data = [];
            for(var dsCount in performance_data){
                graph_data[dsCount] = [];
                for(var timestamp in performance_data[dsCount].data){
                    graph_data[dsCount].push([timestamp, performance_data[dsCount].data[timestamp]]);
                }
                //graph_data.push(performance_data[key].data);
            }
            var color_amount = performance_data.length < 3 ? 3 : performance_data.length;
            var color_generator = new ColorGenerator();
            var options = {
                width: '100%',
                height: '500px',
                colors: color_generator.generate(color_amount, 90, 120),
                legend: false,
                grid: {
                    hoverable: true,
                    markings: [],
                    borderWidth: {
                        top: 1,
                        right: 1,
                        bottom: 1,
                        left: 1
                    },
                    borderColor: {
                        top: '#CCCCCC'
                    }
                },
                tooltip: false,
                xaxis: {
                    mode: 'time',
                    timeformat: '%d.%m.%y %H:%M:%S', // This is handled by a plugin, if it is used -> jquery.flot.time.js
                    tickFormatter: function(val, axis){
                        var fooJS = new Date(val + ($scope.timezone.server_timezone_offset * 1000));
                        var fixTime = function(value){
                            if(value < 10){
                                return '0' + value;
                            }
                            return value;
                        };
                        return fixTime(fooJS.getUTCDate()) + '.' + fixTime(fooJS.getUTCMonth() + 1) + '.' + fooJS.getUTCFullYear() + ' ' + fixTime(fooJS.getUTCHours()) + ':' + fixTime(fooJS.getUTCMinutes());
                    }
                },
                lines: {
                    show: true,
                    lineWidth: 1,
                    fill: true,
                    steps: 0,
                    fillColor: {
                        colors: [{
                            opacity: 0.5
                        },
                            {
                                opacity: 0.3
                            }]
                    }
                },
                points: {
                    show: false,
                    radius: 1
                },
                series: {
                    show: true,
                    labelFormatter: function(label, series){
                        // series is the series object for the label
                        return '<a href="#' + label + '">' + label + '</a>';
                    }
                },
                selection: {
                    mode: "x"
                }
            };

            self.plot = $.plot('#serviceGraphFlot', graph_data, options);
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

        $scope.startFlapping = function(){
            $scope.stopFlapping();
            flappingInterval = $interval(function(){
                if($scope.flappingState === 0){
                    $scope.flappingState = 1;
                }else{
                    $scope.flappingState = 0;
                }
            }, 750);
        };

        $scope.stopFlapping = function(){
            if(flappingInterval){
                $interval.cancel(flappingInterval);
            }
            flappingInterval = null;
        };

        $scope.ping = function(){
            $scope.pingResult = [];
            $scope.isPinging = true;
            $http.get("/hosts/ping.json", {
                params: {
                    'angular': true,
                    'address': $scope.mergedHost.Host.address
                }
            }).then(function(result){
                $scope.pingResult = result.data.output;
                $scope.isPinging = false;
            });
        };

        var getHoststatusTextColor = function(){
            switch($scope.hoststatus.currentState){
                case 0:
                case '0':
                    return 'txt-color-green';

                case 1:
                case '1':
                    return 'txt-color-red';

                case 2:
                case '2':
                    return 'txt-color-blueLight';
            }
            return 'txt-primary';
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



        $scope.loadHost();
        $scope.loadTimezone();
        SortService.setCallback($scope.load);

        $scope.$watch('activeServiceFilter', function(){
            if($scope.init){
                return;
            }
            $scope.currentPage = 1;
            $scope.load();
        }, true);

        $scope.$watch('hoststatus.isFlapping', function(){
            if($scope.hoststatus){
                if($scope.hoststatus.hasOwnProperty('isFlapping')){
                    if($scope.hoststatus.isFlapping === true){
                        $scope.startFlapping();
                    }

                    if($scope.hoststatus.isFlapping === false){
                        $scope.stopFlapping();
                    }

                }
            }
        });

    });
