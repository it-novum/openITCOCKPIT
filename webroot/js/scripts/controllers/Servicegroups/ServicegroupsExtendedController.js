angular.module('openITCOCKPIT')
    .controller('ServicegroupsExtendedController', function($scope, $http, $interval, $stateParams){

        $scope.init = true;
        $scope.servicegroupsStateFilter = {};

        $scope.deleteUrl = '/services/delete/';
        $scope.deactivateUrl = '/services/deactivate/';
        $scope.activateUrl = '/services/enable/';
        $scope.mouseout = true;

        $scope.filter = {
            servicename: ''
        };

        $scope.post = {
            Servicegroup: {
                id: null
            }
        };

        $scope.post.Servicegroup.id = $stateParams.id;
        if($scope.post.Servicegroup.id !== null){
            $scope.post.Servicegroup.id = parseInt($scope.post.Servicegroup.id, 10);
        }

        var graphStart = 0;
        var graphEnd = 0;

        $scope.load = function(){
            $http.get("/servicegroups/loadServicegroupsByString.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.servicegroups = result.data.servicegroups;

                if($scope.post.Servicegroup.id === null){
                    if($scope.servicegroups.length > 0){
                        $scope.post.Servicegroup.id = $scope.servicegroups[0].key;
                    }
                }else{
                    //HostgroupId was passed in URL
                    $scope.loadServicesWithStatus();
                }

                $scope.init = false;
            });
        };

        $scope.loadServicegroupsCallback = function(searchString){
            $http.get("/servicegroups/loadServicegroupsByString.json", {
                params: {
                    'angular': true,
                    'filter[Containers.name]': searchString,
                    'selected[]': $scope.post.Servicegroup.id
                }
            }).then(function(result){
                $scope.servicegroups = result.data.servicegroups;
            });
        };

        $scope.loadServicesWithStatus = function(){
            if($scope.post.Servicegroup.id){
                $http.get("/servicegroups/loadServicegroupWithServicesById/" + $scope.post.Servicegroup.id + ".json", {
                    params: {
                        'angular': true,
                        'filter[servicename]': $scope.filter.servicename,
                    }
                }).then(function(result){
                    $scope.servicegroup = result.data.servicegroup;
                    $scope.servicegroupsStateFilter = {
                        0: true,
                        1: true,
                        2: true,
                        3: true
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
            $scope.mouseout = false;
            $scope.isLoadingGraph = true;
            var offset = {
                top: $event.relatedTarget.offsetTop + 40,
                left: $event.relatedTarget.offsetLeft + 40
            };

            if($event.relatedTarget.offsetParent && $event.relatedTarget.offsetParent.offsetTop){
                offset.top += $event.relatedTarget.offsetParent.offsetTop;
            }

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
            $scope.mouseout = true;
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
            var serverTime = new Date($scope.timezone.server_time);
            graphEnd = Math.floor(serverTime.getTime() / 1000);
            graphStart = graphEnd - (3600 * 4);

            $http.get('/Graphgenerators/getPerfdataByUuid.json', {
                params: {
                    angular: true,
                    host_uuid: host.uuid,
                    service_uuid: service.Service.uuid,
                    start: graphStart,
                    end: graphEnd,
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
                    var frontEndTimestamp = (parseInt(timestamp, 10) + ($scope.timezone.user_time_to_server_offset * 1000));
                    graph_data[dsCount].push([frontEndTimestamp, performance_data[dsCount].data[timestamp]]);
                }
            }


            var GraphDefaultsObj = new GraphDefaults();
            var color_amount = performance_data.length < 3 ? 3 : performance_data.length;
            var colors = GraphDefaultsObj.getColors(color_amount);
            var options = GraphDefaultsObj.getDefaultOptions();
            options.colors = colors.border;
            options.xaxis.tickFormatter = function(val, axis){
                var fooJS = new Date(val);
                var fixTime = function(value){
                    if(value < 10){
                        return '0' + value;
                    }
                    return value;
                };
                return fixTime(fooJS.getDate()) + '.' + fixTime(fooJS.getMonth() + 1) + '.' + fooJS.getFullYear() + ' ' + fixTime(fooJS.getHours()) + ':' + fixTime(fooJS.getMinutes());
            };

            options.xaxis.min = (graphStart + $scope.timezone.user_time_to_server_offset) * 1000;
            options.xaxis.max = (graphEnd + $scope.timezone.user_time_to_server_offset) * 1000;

            if(document.getElementById('serviceGraphFlot') && !$scope.mouseout){
                try{
                    self.plot = $.plot('#serviceGraphFlot', graph_data, options);
                }catch(e){
                    console.error(e);
                }
            }
        };

        $scope.getObjectsForExternalCommand = function(){
            var objects = {};
            if($scope.post.Servicegroup.id){
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
            if($scope.post.Servicegroup.id){
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
            if($scope.post.Servicegroup.id){
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

        $scope.$watch('filter', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Servicegroup.id > 0){
                $scope.loadServicesWithStatus('');
            }
        }, true);

    });
