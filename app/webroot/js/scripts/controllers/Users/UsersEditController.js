angular.module('openITCOCKPIT')
    .controller('UsersEditController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){
        $scope.intervalText = 'disabled';
        $scope.id = $stateParams.id;
        $scope.chosenContainerroles = {};
        $scope.post = {
            'User': {
                'email': '',
                'firstname': '',
                'lastname': '',
                'company': '',
                'is_active': true,
                'position': '',
                'phone': '',
                'password': '',
                'usergroup_id': '',
                'showstatsinmenu': false,
                'paginatorlength': 25,
                'dashboard_tab_rotation': 0,
                'recursive_browser': false,
                'dateformat': '',
                'containers': {
                    /* example data CURRENTLY NOT USED!
                    0: {
                        'id': null, //container ID
                        '_joinData':{ //saving additional data to "through" table
                            'permission_level':null //radio button value
                        }
                    }
                    */
                },
                'ContainersUsersMemberships': {},
                'usercontainerroles': {
                    '_ids': []
                }
            }
        };
        $scope.init = true;

        $scope.load = function(){
            $http.get("/users/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post.User = result.data.user;
            });
        };


        $scope.loadUsercontainerroles = function(){
            $http.get("/usercontainerroles/loadUsercontainerrolesForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.init = false;
                $scope.usercontainerroles = result.data.usercontainerroles;
                $scope.usercontainerrolePermissions = result.data.usercontainerrolePermissions;
            });
        };


        $scope.loadContainer = function(){
            $http.get("/containers/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
            });
        };

        $scope.loadUsergroups = function(){
            $http.get("/users/loadUsergroups.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.usergroups = result.data.usergroups;
            });
        };

        $scope.loadDateformats = function(){
            $http.get("/users/loadDateformats.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.dateformats = result.data.dateformats;
                $scope.post.User.dateformat = result.data.defaultDateFormat;
            });
        };

        $scope.getContainerName = function(id){
            for(var c in $scope.containers){
                if($scope.containers[c].key == id){
                    return $scope.containers[c].value;
                }
            }
            return null;
        };

        $scope.$watch('post.User.dashboard_tab_rotation', function(){
            var dashboardTabRotationInterval = $scope.post.User.dashboard_tab_rotation;
            if(dashboardTabRotationInterval === 0){
                $scope.intervalText = 'disabled';
            }else{
                var min = parseInt(dashboardTabRotationInterval / 60, 10);
                var sec = parseInt(dashboardTabRotationInterval % 60, 10);
                if(min > 0){
                    $scope.intervalText = min + ' minutes, ' + sec + ' seconds';
                    return;
                }
                $scope.intervalText = sec + ' seconds';
            }
        }, true);


        $scope.submit = function(){
            $http.post("/users/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('UsersIndex');
            }, function errorCallback(result){
                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };


        /**
         * sync the membership array with the containers array so we can cleanly remove a container from a user
         */
        $scope.syncMemberships = function(){
            var memberships = $scope.post.User.ContainersUsersMemberships;
            for(var key in memberships){
                key = parseInt(key, 10);
                if($scope.post.User.containers._ids.indexOf(key) == -1){
                    delete memberships[key];
                }
            }
        };

        $scope.$watch('post.User.usercontainerroles._ids', function(){
            if($scope.post.User.usercontainerroles._ids.length > 0 && !$scope.init){
                $scope.chosenContainerroles = {};
                $scope.post.User.usercontainerroles._ids.forEach(function(k){
                    for(var i in $scope.usercontainerrolePermissions[k]){
                        var currentValue = $scope.usercontainerrolePermissions[k][i];
                        if($scope.chosenContainerroles.hasOwnProperty(i)){
                            if($scope.chosenContainerroles[i] < currentValue){
                                $scope.chosenContainerroles[i] = currentValue;
                            }
                        }else{
                            $scope.chosenContainerroles[i] = currentValue;
                        }
                    }
                });
            }
        }, true);


        $scope.loadContainer();
        $scope.loadUsercontainerroles();
        $scope.loadUsergroups();
        $scope.loadDateformats();
        $scope.load();
    });

