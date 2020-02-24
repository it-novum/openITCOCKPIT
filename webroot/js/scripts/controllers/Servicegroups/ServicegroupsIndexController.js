angular.module('openITCOCKPIT')
    .controller('ServicegroupsIndexController', function($scope, $http, $stateParams, SortService, MassChangeService, QueryStringService){

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
                servicegroups: {
                    id: QueryStringService.getStateValue($stateParams, 'id', []),
                    description: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/servicegroups/delete/';

        $scope.init = true;
        $scope.showFilter = false;
        $scope.load = function(){
            $http.get("/servicegroups/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Servicegroups.id][]': $scope.filter.servicegroups.id,
                    'filter[Containers.name]': $scope.filter.containers.name,
                    'filter[Servicegroups.description]': $scope.filter.servicegroups.description
                }
            }).then(function(result){
                $scope.servicegroups = result.data.all_servicegroups;
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
            if($scope.servicegroups){
                for(var key in $scope.servicegroups){
                    if($scope.servicegroups[key].allow_edit){
                        var id = $scope.servicegroups[key].id;
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

        $scope.getObjectForDelete = function(servicegroup){
            var object = {};
            object[servicegroup.id] = servicegroup.container.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.servicegroups){
                for(var id in selectedObjects){
                    if(id == $scope.servicegroups[key].id){
                        objects[id] = $scope.servicegroups[key].container.name;
                    }

                }
            }
            return objects;
        };

        $scope.linkForPdf = function(){
            var baseUrl = '/servicegroups/listToPdf.pdf';
            baseUrl += '?filter[Containers.name]=' + encodeURI($scope.filter.containers.name);
            baseUrl += '&filter[Servicegroups.description]=' + encodeURI($scope.filter.servicegroups.description);
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
