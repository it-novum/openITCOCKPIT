angular.module('openITCOCKPIT')
    .controller('UsersIndexController', function($scope, $http, $rootScope, SortService, MassChangeService, QueryStringService){

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Users: {
                    full_name: '',
                    email: ''
                }
            };
        };

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function(){
            defaultFilter();
            $scope.undoSelection();
        };

        $scope.load = function(){
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Users.full_name]': $scope.filter.Users.full_name,
                'filter[Users.email]': $scope.filter.Users.email
            };

            $http.get("/users/index.json", {
                params: params
            }).then(function(result){
                console.log(result.data);
                $scope.Users = result.data.all_users;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        //Fire on page load
        defaultFilter();
        $scope.load();
    });

