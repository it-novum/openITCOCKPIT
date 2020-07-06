angular.module('openITCOCKPIT')
    .controller('InstantreportsIndexController', function($scope, $http, SortService, MassChangeService){

        SortService.setSort('Instantreports.name');
        SortService.setDirection('asc');
        $scope.currentPage = 1;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                instantreport: {
                    name: '',
                    evaluation: {
                        hosts: false,
                        hostsandservices: false,
                        services: false
                    },
                    type: {
                        hostgroups: false,
                        hosts: false,
                        servicegroups: false,
                        services: false
                    }
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/instantreports/delete/';

        $scope.init = true;
        $scope.showFilter = false;
        $scope.load = function(){
            var evaluationTypes = _.filter($scope.filter.instantreport.evaluation, function(value){
                return value !== false;
            });
            var objectTypes = _.filter($scope.filter.instantreport.type, function(value){
                return value !== false;
            });
            $http.get("/instantreports/index.json", {
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Instantreports.name]': $scope.filter.instantreport.name,
                    'filter[Instantreports.evaluation][]': evaluationTypes,
                    'filter[Instantreports.type][]': objectTypes
                }
            }).then(function(result){
                $scope.instantreports = result.data.instantreports;
                $scope.paging = result.data.paging;
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

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.selectAll = function(){
            if($scope.instantreports){
                for(var key in $scope.instantreports){
                    var id = $scope.instantreports[key].Instantreport.id;
                    $scope.massChange[id] = true;
                }
            }
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.instantreports){
                for(var id in selectedObjects){
                    if(id == $scope.instantreports[key].Instantreport.id){
                        objects[id] = $scope.instantreports[key].Instantreport.name;
                    }

                }
            }
            return objects;
        };

        $scope.getObjectForDelete = function(instantreport){
            var object = {};
            object[instantreport.Instantreport.id] = instantreport.Instantreport.name;
            return object;
        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.getObjectForDelete = function(instantreport){
            var object = {};
            object[instantreport.Instantreport.id] = instantreport.Instantreport.name;
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
