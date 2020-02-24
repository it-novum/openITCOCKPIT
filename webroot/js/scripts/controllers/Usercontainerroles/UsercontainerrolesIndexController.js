angular.module('openITCOCKPIT')
    .controller('UsercontainerrolesIndexController', function($scope, $http, MassChangeService, QueryStringService, NotyService, SortService){
        SortService.setSort(QueryStringService.getValue('sort', 'Usercontainerroles.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Usercontainerroles: {
                    name: ''
                }
            };
        };

        $scope.currentPage = 1;
        $scope.useScroll = true;

        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/usercontainerroles/delete/';


        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function(){
            defaultFilter();
        };

        $scope.load = function(){
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Usercontainerroles.name]': $scope.filter.Usercontainerroles.name,
            };

            $http.get("/usercontainerroles/index.json", {
                params: params
            }).then(function(result){
                $scope.usercontainerroles = result.data.all_usercontainerroles;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.selectAll = function(){
            if($scope.usercontainerroles){
                for(var key in $scope.usercontainerroles){
                    if($scope.usercontainerroles[key].allow_edit === true){
                        var id = $scope.usercontainerroles[key].id;
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

        $scope.getObjectForDelete = function(usercontainerrole){
            var object = {};
            object[usercontainerrole.id] = usercontainerrole.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.usercontainerroles){
                for(var id in selectedObjects){
                    if(id == $scope.usercontainerroles[key].id){
                        if($scope.usercontainerroles[key].allow_edit === true){
                            objects[id] = $scope.usercontainerroles[key].name;
                        }
                    }
                }
            }
            return objects;
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
        $scope.load();

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.load();
        }, true);

        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);
    });

