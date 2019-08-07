angular.module('openITCOCKPIT')
    .controller('UsersEditFromLdapController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){
        $scope.id = $stateParams.id;
        $scope.errors = false;
        $scope.chosenContainerroles = {};
        $scope.post = {
            'User': {
                'ldap': 1,
                'email': '',
                'samaccountname': null, //username
                'firstname': '',
                'lastname': '',
                'is_active':true,
                'company': null,
                'position': null,
                'phone': null,
                'usergroup_id': '',
                'ldap_dn': null,
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


        $scope.load = function(){
            $http.get("/users/editFromLdap/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post.User = result.data.user;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.loadUsercontainerroles = function(){
            $http.get("/usercontainerroles/loadUsercontainerrolesForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
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

        $scope.loadDateformats = function(){
            $http.get("/users/loadDateformats.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.dateformats = result.data.dateformats;
                $scope.post.User.dateformat = result.data.defaultDateFormat;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
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


        $scope.loadSystemsettings = function(){
            $http.get("/systemsettings/getSystemsettingsForAngularBySection.json", {
                params: {
                    'section': 'FRONTEND',
                    'angular': true,
                }
            }).then(function(result){
                $scope.systemsettings = result.data.systemsettings;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };


        $scope.submit = function(){
            $http.post("/users/editFromLdap/" + $scope.id + ".json?angular=true",
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
            var memberships = $scope.post.User.ContainersUsersMemberships
            for(var key in memberships){
                key = parseInt(key, 10);
                if($scope.post.User.containers._ids.indexOf(key) == -1){
                    delete memberships[key];
                }
            }
        };

        $scope.$watch('post.User.usercontainerroles._ids', function(){
            if($scope.post.User.usercontainerroles._ids.length > 0){
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

        $scope.loadUsercontainerroles();
        $scope.loadContainer();
        $scope.loadDateformats();
        $scope.loadUsergroups();
        $scope.loadSystemsettings();
        $scope.load();
    });