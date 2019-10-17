angular.module('openITCOCKPIT').directive('hostServiceList', function($http){
    return {
        restrict: 'E',
        templateUrl: '/hosts/hostservicelist.html',
        scope: {
            'hostId': '=',
            'showServices': '=',
            'timezone': '=',
            'host': '='
        },
        controller: function($scope){

            $scope.deleteUrl = '/services/delete/';
            $scope.deactivateUrl = '/services/deactivate/';

            $scope.init = true;
            /*** Filter Settings ***/
            var defaultFilter = function(){
                $scope.filter = {
                    Service: {
                        name: ''
                    }
                };
            };

            var graphStart = 0;
            var graphEnd = 0;


            $scope.loadServicesWithStatus = function(){
                $http.get("/services/index.json", {
                    params: {
                        'angular': true,
                        'filter[Host.id]': $scope.hostId,
                        'filter[Service.servicename]': $scope.filter.Service.name
                    }
                }).then(function(result){
                    $scope.services = result.data.all_services;
                    $scope.servicesStateFilter = {
                        0: true,
                        1: true,
                        2: true,
                        3: true
                    };
                    $scope.init = false;
                });
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

                self.plot = $.plot('#serviceGraphFlot', graph_data, options);
            };

            //Fire on page load
            defaultFilter();

            $scope.$watch('showServices', function(){
                if($scope.showServices[$scope.hostId]){
                    $scope.loadServicesWithStatus();
                }
            }, true);

            $scope.$watch('filter',
                function(){
                    if($scope.init){
                        return;
                    }
                    $scope.loadServicesWithStatus();
                }, true);

        }
    };

});
