angular.module('openITCOCKPIT')
    .controller('SatellitesIndexController', function($scope, $http, SortService, MassChangeService){

        SortService.setSort('Satellite.name');
        SortService.setDirection('asc');
        $scope.currentPage = 1;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Satellite: {
                    name: '',
                    address: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/distribute_module/satellites/delete/';

        $scope.init = true;
        $scope.showFilter = false;
        $scope.load = function(){
            $http.get("/distribute_module/satellites/index.json", {
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Satellite.name]': $scope.filter.Satellite.name,
                    'filter[Satellite.address]': $scope.filter.Satellite.address
                }
            }).then(function(result){
                $scope.satellites = result.data.satellites;
                $scope.paging = result.data.paging;
                $scope.init = false;
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
            $scope.undoSelection();
        };

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.selectAll = function(){
            if($scope.satellites){
                for(var key in $scope.satellites){
                    var id = $scope.satellites[key].Satellite.id;
                    $scope.massChange[id] = true;
                }
            }
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.satellites){
                for(var id in selectedObjects){
                    if(id == $scope.satellites[key].Satellite.id){
                        objects[id] = $scope.satellites[key].Satellite.name;
                    }

                }
            }
            return objects;
        };

        $scope.getObjectForDelete = function(satellite){
            var object = {};
            object[satellite.Satellite.id] = satellite.Satellite.name;
            return object;
        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.getObjectForDelete = function(satellite){
            var object = {};
            object[satellite.Satellite.id] = satellite.Satellite.name;
            return object;
        };


        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            $scope.load();
        }, true);

        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);
    });
