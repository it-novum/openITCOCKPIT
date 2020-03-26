angular.module('openITCOCKPIT')
    .controller('ServicesServiceListController', function($scope, $http, SortService, MassChangeService, $stateParams){

        SortService.setSort('Servicestatus.current_state');
        SortService.setDirection('desc');
        $scope.currentPage = 1;

        $scope.data = {
            hostId: parseInt($stateParams.id)
        };

        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/services/delete/';
        $scope.deactivateUrl = '/services/deactivate/';
        $scope.activateUrl = '/services/enable/';
        $scope.mouseout = true;

        $scope.activeTab = 'active';

        //There is no service status for not monitored services :)
        $scope.fakeServicestatus = {
            Servicestatus: {
                currentState: 5
            }
        };

        var graphStart = 0;
        var graphEnd = 0;

        $scope.changeTab = function(tab){
            if(tab !== $scope.activeTab){
                $scope.services = [];
                $scope.activeTab = tab;
                $scope.undoSelection();

                SortService.setSort('servicename');
                SortService.setDirection('asc');
                $scope.currentPage = 1;

                if($scope.activeTab === 'deleted'){
                    SortService.setSort('DeletedServices.name');
                }

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

                case 'deleted':
                    $scope.loadDeletedServices();
                    break;
            }
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

        $scope.loadHost = function(){
            $http.get("/hosts/loadHostById/" + $scope.data.hostId + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.host = result.data.host;
            });
        };

        $scope.loadActiveServices = function(){
            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosts.id]': $scope.data.hostId
            };

            $http.get("/services/index.json", {
                params: params
            }).then(function(result){
                $scope.services = [];
                $scope.services = result.data.all_services;

                $scope.paging = result.data.paging;
                $scope.init = false;
            });
        };

        $scope.loadNotMonitoredServices = function(){
            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosts.id]': $scope.hostId
            };

            $http.get("/services/notMonitored.json", {
                params: params
            }).then(function(result){
                $scope.services = [];
                $scope.services = result.data.all_services;

                $scope.paging = result.data.paging;
                $scope.init = false;
            });
        };

        $scope.loadDisabledServices = function(){
            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosts.id]': $scope.data.hostId
            };

            $http.get("/services/disabled.json", {
                params: params
            }).then(function(result){
                $scope.services = [];
                $scope.services = result.data.all_services;

                $scope.paging = result.data.paging;
                $scope.init = false;
            });
        };

        $scope.loadDeletedServices = function(){
            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[DeletedServices.host_id]': $scope.data.hostId
            };

            $http.get("/services/deleted.json", {
                params: params
            }).then(function(result){
                $scope.deletedServices = [];
                $scope.deletedServices = result.data.all_services;

                $scope.paging = result.data.paging;
                $scope.init = false;
            });
        };

        $scope.loadHosts = function(searchString){
            $http.get("/hosts/loadHostsByString.json", {
                params: {
                    'angular': true,
                    'filter[Hosts.name]': searchString,
                    'selected[]': $scope.data.hostId
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        $scope.changepage = function(page){
            $scope.undoSelection();
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

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

        $scope.getObjectForDelete = function(host, service){
            var object = {};
            object[service.Service.id] = host.Host.name + '/' + service.Service.servicename;
            return object;
        };

        $scope.getObjectsForDelete = function(){
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

        $scope.getObjectsForExternalCommand = function(){
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

        $scope.$watch('data.hostId', function(){
            $scope.loadHost();
            $scope.load();
        });

        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

        $scope.loadTimezone();
        SortService.setCallback($scope.load);

        $scope.loadHosts('');

    });
