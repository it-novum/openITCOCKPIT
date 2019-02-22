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

        $scope.load = function(){
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Users.full_name]': $scope.filter.Users.full_name,
                'filter[Timeperiods.email]': $scope.filter.Users.email
            };

            $http.get("/users/index.json", {
                params: params
            }).then(function(result){
                console.log(result.data);
                $scope.users = result.data.all_users;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        //Fire on page load
        defaultFilter();
        $scope.load();
    });

