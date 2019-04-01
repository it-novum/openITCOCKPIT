angular.module('openITCOCKPIT')
    .controller('UsersIndexController', function($scope, $http, $rootScope, SortService, MassChangeService, QueryStringService){

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Users: {
                    full_name: '',
                    email: '',
                    phone: '',
                    status: [],
                    usergroup_id: '',
                    company: '',
                }
            };
        };

        $scope.currentPage = 1;
        $scope.deleteUrl = '/users/delete/';

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
                'filter[Users.full_name]': $scope.filter.Users.full_name,
                'filter[Users.email]': $scope.filter.Users.email,
                'filter[Users.phone]': $scope.filter.Users.phone,
                'filter[Users.status]': $scope.filter.Users.status,
                'filter[Users.usergroup_id]': $scope.filter.Users.usergroup_id,
                'filter[Users.company]': $scope.filter.Users.company
            };
console.log(params);
            $http.get("/users/index.json", {
                params: params
            }).then(function(result){
                $scope.Users = result.data.all_users;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.loadSystemsettings = function(){
            $http.get("/systemsettings/getSystemsettingsForAngularBySection.json", {
                params: {
                    'section': 'FRONTEND',
                    'angular': true,
                }
            }).then(function(result){
                $scope.isLdapAuth = result.data.systemsettings.FRONTEND['FRONTEND.AUTH_METHOD']
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.loadUsergroups = function(){
            $http.get("/usergroups/loadUsergroups.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.usergroups = result.data.usergroups;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.getObjectForDelete = function(user){
            var object = {};
            object[user.User.id] = user.User.full_name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.users){
                for(var id in selectedObjects){
                    if(id == $scope.users[key].User.id){
                        if($scope.users[key].User.allow_edit === true){
                            objects[id] = $scope.users[key].User.full_name;
                        }
                    }
                }
            }
            return objects;
        };

        //Fire on page load
        defaultFilter();
        $scope.load();
        $scope.loadSystemsettings();
        $scope.loadUsergroups();


        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.load();
        }, true);


    });

