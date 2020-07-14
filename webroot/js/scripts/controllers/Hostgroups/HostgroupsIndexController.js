angular.module('openITCOCKPIT')
    .controller('HostgroupsIndexController', function($scope, $http, $stateParams, SortService, MassChangeService, QueryStringService){

        SortService.setSort('Containers.name');
        SortService.setDirection('asc');
        $scope.currentPage = 1;
        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                containers: {
                    name: ''
                },
                hostgroups: {
                    id: QueryStringService.getStateValue($stateParams, 'id', []),
                    description: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/hostgroups/delete/';

        $scope.init = true;
        $scope.showFilter = false;
        $scope.load = function(){
            $http.get("/hostgroups/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Hostgroups.id][]': $scope.filter.hostgroups.id,
                    'filter[Containers.name]': $scope.filter.containers.name,
                    'filter[Hostgroups.description]': $scope.filter.hostgroups.description
                }
            }).then(function(result){
                $scope.hostgroups = result.data.all_hostgroups;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function(){
            defaultFilter();
            $scope.undoSelection();
        };

        $scope.selectAll = function(){
            if($scope.hostgroups){
                for(var key in $scope.hostgroups){
                    if($scope.hostgroups[key].allowEdit){
                        var id = $scope.hostgroups[key].id;
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

        $scope.getObjectForDelete = function(hostgroup){
            var object = {};
            object[hostgroup.id] = hostgroup.container.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.hostgroups){
                for(var id in selectedObjects){
                    if(id == $scope.hostgroups[key].id){
                        objects[id] = $scope.hostgroups[key].container.name;
                    }

                }
            }
            return objects;
        };

        $scope.linkForPdf = function(){
            var baseUrl = '/hostgroups/listToPdf.pdf';
            baseUrl += '?filter[Containers.name]=' + encodeURI($scope.filter.containers.name);
            baseUrl += '&filter[Hostgroups.description]=' + encodeURI($scope.filter.hostgroups.description);
            return baseUrl;
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
