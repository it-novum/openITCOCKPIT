angular.module('openITCOCKPIT')
    .controller('ServicegroupsExtendedController', function($scope, $http, $interval){

        $scope.init = true;
        $scope.servicegroupsStateFilter = {};

        $scope.deleteUrl = '/services/delete/';
        $scope.deactivateUrl = '/services/deactivate/';
        $scope.activateUrl = '/services/enable/';

        $scope.post = {
            Servicegroup: {
                id: null
            }
        };


        $scope.load = function(){
            $http.get("/servicegroups/extended.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.servicegroups = result.data.servicegroups;
                $scope.init = false;
            });
        };

        $scope.loadServicesWithStatus = function(){
            if($scope.post.Servicegroup.id) {
                $http.get("/servicegroups/loadServicegroupWithServicesById/" + $scope.post.Servicegroup.id +".json", {
                    params: {
                        'angular': true
                    }
                }).then(function (result) {
                    $scope.servicegroup = result.data.servicegroup;
                    $scope.servicegroupsStateFilter = {
                        0 : true,
                        1 : true,
                        2 : true,
                        3 : true
                    };
                });
            }

        };

        $scope.getObjectForDelete = function(host, service){
            var object = {};
            object[service.Service.id] = host.hostname + '/' + service.Service.servicename;
            return object;
        };

        $scope.mouseenter = function($event, host, service){
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
            loadGraph(host, service);
        };

        $scope.mouseleave = function(){
            $('#serviceGraphContainer').hide();
            $('#serviceGraphFlot').html('');
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

        var loadGraph = function(host, service){
            $http.get('/Graphgenerators/getPerfdataByUuid.json', {
                params: {
                    angular: true,
                    host_uuid: host.uuid,
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
                    markings: self.threshold_lines,
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

        $scope.getObjectsForExternalCommand = function(){
            var objects = {};
            if($scope.post.Servicegroup.id) {
                for(var key in $scope.servicegroup.Services){
                    if($scope.servicegroup.Services[key].Service.allow_edit){
                        objects[$scope.servicegroup.Services[key].Service.id] = $scope.servicegroup.Services[key];
                    }
                }
            }
            return objects;
        };

        $scope.getNotOkObjectsForExternalCommand = function(){
            var objects = {};
            if($scope.post.Servicegroup.id) {
                for(var key in $scope.servicegroup.Services){
                    if($scope.servicegroup.Services[key].Service.allow_edit &&
                        $scope.servicegroup.Services[key].Servicestatus.currentState > 0){
                        objects[$scope.servicegroup.Services[key].Service.id] = $scope.servicegroup.Services[key];
                    }
                }
            }
            return objects;
        };

        $scope.getObjectsForNotificationsExternalCommand = function(notificationsEnabled){
            var objects = {};
            if($scope.post.Servicegroup.id) {
                for(var key in $scope.servicegroup.Services){
                    if($scope.servicegroup.Services[key].Service.allow_edit &&
                        $scope.servicegroup.Services[key].Servicestatus.notifications_enabled === notificationsEnabled){

                        objects[$scope.servicegroup.Services[key].Service.id] = $scope.servicegroup.Services[key];
                    }
                }
            }
            return objects;
        };

        $scope.showFlashMsg = function(){
            $scope.showFlashSuccess = true;
            $scope.autoRefreshCounter = 5;
            var interval = $interval(function(){
                $scope.autoRefreshCounter--;
                if($scope.autoRefreshCounter === 0){
                    $scope.loadServicesWithStatus('');
                    $interval.cancel(interval);
                    $scope.showFlashSuccess = false;
                }
            }, 1000);
        };

        //Fire on page load
        $scope.loadTimezone();
        $scope.load();

        $scope.$watch('post.Servicegroup.id', function(){
            if($scope.init){
                return;
            }
            $scope.loadServicesWithStatus('');
        }, true);
    });
