angular.module('openITCOCKPIT')
    .controller('ServicesNotMonitoredController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, MassChangeService, QueryStringService){
        $rootScope.lastObjectName = null;

        SortService.setSort(QueryStringService.getValue('sort', 'Hosts.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;

        //There is no service status for not monitored services :)
        $scope.fakeServicestatus = {
            Servicestatus: {
                currentState: 5
            }
        };

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Hosts: {
                    name: ''
                },
                Services: {
                    name: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/services/delete/';
        $scope.deactivateUrl = '/services/deactivate/';


        $scope.init = true;
        $scope.showFilter = false;
        $scope.serverResult = [];

        $scope.load = function(){
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosts.name]': $scope.filter.Hosts.name,
                'filter[servicename]': $scope.filter.Services.name
            };


            $http.get("/services/notMonitored.json", {
                params: params
            }).then(function(result){
                $scope.services = result.data.all_services;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter;
        };

        $scope.resetFilter = function(){
            defaultFilter();
            $scope.undoSelection();
        };

        $scope.selectAll = function(){
            if($scope.services){
                for(var key in $scope.serverResult){
                    if($scope.serverResult[key].Service.allow_edit){
                        var id = $scope.serverResult[key].Service.id;
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
            object[service.id] = host.Host.hostname + '/' + service.servicename;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.serverResult){
                for(var id in selectedObjects){
                    if(id == $scope.serverResult[key].Service.id){
                        objects[id] =
                            $scope.serverResult[key].Host.hostname + '/' +
                            $scope.serverResult[key].Service.servicename;
                    }

                }
            }
            return objects;
        };

        $scope.linkForCopy = function(){
            var ids = Object.keys(MassChangeService.getSelected());
            return ids.join(',');
        };


        $scope.changepage = function(page){
            $scope.undoSelection();
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.undoSelection();
            $scope.load();
        }, true);


        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

    });
