angular.module('openITCOCKPIT')
    .controller('SystemHealthUsersIndexController', function($scope, $http, $rootScope, SortService, MassChangeService, QueryStringService, NotyService){

        SortService.setSort(QueryStringService.getValue('sort', 'full_name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                full_name: '',
                Users: {
                    email: '',
                }
            };
        };

        $scope.currentPage = 1;
        $scope.useScroll = true;

        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/systemHealthUsers/delete/';

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
                'filter[full_name]': $scope.filter.full_name,
                'filter[Users.email]': $scope.filter.Users.email,
                'filter[Users.usergroup_id][]': $scope.filter.Users.usergroup_id,
                'filter[Users.company]': $scope.filter.Users.company
            };

            $http.get("/systemHealthUsers/index.json", {
                params: params
            }).then(function(result){
                $scope.users = result.data.all_users;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.selectAll = function(){
            if($scope.users){
                for(var key in $scope.users){
                    if($scope.users[key].allow_edit === true){
                        var id = $scope.users[key].system_health_user.id;
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

        $scope.getObjectForDelete = function(user){
            var object = {};
            object[user.system_health_user.id] = user.full_name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.users){
                for(var id in selectedObjects){
                    if(id == $scope.users[key].system_health_user.id){
                        if($scope.users[key].allow_edit === true){
                            objects[id] = $scope.users[key].full_name;
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

