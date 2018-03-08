angular.module('openITCOCKPIT')
    .controller('ServicegroupsExtendedController', function($scope, $http,  SortService, QueryStringService){

        SortService.setSort('Container.name');
        SortService.setDirection('asc');

        $scope.currentPage = 1;
        $scope.init = true;
        $scope.servicegroupsStateFilter = {};

        $scope.deleteUrl = '/services/delete/';
        $scope.deactivateUrl = '/services/deactivate/';
        $scope.activateUrl = '/services/enable/';

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Servicegroup: {
                    description: QueryStringService.getValue('filter[Servicegroup.description]', '')
                },
                Container: {
                    name: QueryStringService.getValue('filter[Container.name]', ''),
                }
            };
        };
        /*** Filter end ***/
        $scope.showFilter = false;

        $scope.load = function(){
            $http.get("/servicegroups/extended.json", {
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Container.name]': $scope.filter.Container.name,
                    'filter[Servicegroup.description]': $scope.filter.Servicegroup.description
                }
            }).then(function(result){
                $scope.servicegroups = result.data.all_servicegroups;
                angular.forEach($scope.servicegroups, function (value, key) {
                    $scope.servicegroupsStateFilter[value.Servicegroup.uuid] = {
                        0 : true,
                        1 : true,
                        2 : true,
                        3 : true
                    };
                });
                $scope.init = false;
                $scope.paging = result.data.paging;
            });
        };

        $scope.triggerFilter = function(){
            if($scope.showFilter === true){
                $scope.showFilter = false;
            }else{
                $scope.showFilter = true;
            }
        };

        $scope.resetFilter = function(){
            defaultFilter();
            $scope.load();

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

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        //Fire on page load
        defaultFilter();
        $scope.loadTimezone();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.load();
        }, true);
    });
