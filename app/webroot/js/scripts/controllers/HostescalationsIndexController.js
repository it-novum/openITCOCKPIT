angular.module('openITCOCKPIT')
    .controller('HostescalationsIndexController', function($scope, $http, MassChangeService, SortService, QueryStringService){

        SortService.setSort(QueryStringService.getValue('sort', 'Hostescalations.id'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;
        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Hostescalation: {
                    first_notification: '',
                    last_notification: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/hostescalations/delete/';

        $scope.init = true;
        $scope.showFilter = false;

        $scope.load = function(){

            $http.get("/hostescalations/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'page': $scope.currentPage
                }
            }).then(function(result){
                $scope.hostescalations = result.data.all_hostescalations;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;

                $scope.init = false;
            });

        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function(){
            defaultFilter();
            $scope.undoSelection();
        };

        $scope.selectAll = function(){
            if($scope.hostescalations){
                for(var key in $scope.hostescalations){
                    if($scope.hostescalations[key].Hostescalation.allowEdit === true){
                        var id = $scope.hostescalations[key].Hostescalation.id;
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

        $scope.getObjectForDelete = function(hostescalation){
            var object = {};
            object[hostescalation.Hostescalation.id] = $scope.objectName + hostescalation.Hostescalation.id;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.hostescalations){
                for(var id in selectedObjects){
                    if(id == $scope.hostescalations[key].Hostescalation.id){
                        if($scope.hostescalations[key].Hostescalation.allowEdit === true){
                            objects[id] = $scope.objectName + $scope.hostescalations[key].Hostescalation.id;
                        }
                    }
                }
            }
            return objects;
        };

        //Fire on page load
        $scope.load();

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
