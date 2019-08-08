angular.module('openITCOCKPIT')
    .controller('UsercontainerrolesIndexController', function($scope, $http, MassChangeService, QueryStringService, NotyService, SortService){
        $scope.currentPage = 1;
        $scope.deleteUrl = '/usercontainerroles/delete/';

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Usercontainerroles: {
                    name: '',
                }
            };
        };

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
                $scope.Usercontainerroles = result.data.allUsercontainerroles;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.getObjectForDelete = function(usercontainerrole){
            var object = {};
            object[usercontainerrole.Usercontainerrole.id] = usercontainerrole.Usercontainerrole.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.Usercontainerroles){
                for(var id in selectedObjects){
                    if(id == $scope.Usercontainerroles[key].Usercontainerrole.id){
                        if($scope.Usercontainerroles[key].Usercontainerrole.allow_edit === true){
                            objects[id] = $scope.Usercontainerroles[key].Usercontainerrole.name;
                        }
                    }
                }
            }
            return objects;
        };

        //Fire on page load
        defaultFilter();
        $scope.load();

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.load();
        }, true);


    });

