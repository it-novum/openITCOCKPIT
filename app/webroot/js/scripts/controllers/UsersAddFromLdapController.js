angular.module('openITCOCKPIT')
    .controller('UsersAddFromLdapController', function($scope, $http, $state, NotyService){
        $scope.init = true;
        $scope.errors = false;
        $scope.post = {
            'User': {
                'ldap': 1,
                'status': '',
                'email': '',
                'samaccountname': null, //username
                'firstname': '',
                'lastname': '',
                'company': null,
                'position': null,
                'phone': null,
                'usergroup_id': '',
                'ldap_dn': null,
                'showstatsinmenu': 0,
                'paginatorlength': 25,
                'dashboard_tab_rotation': 0,
                'recursive_browser': 0,
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
                'ContainersUsersMemberships': {}
            }
        };

        $scope.data = {
            selectedSamAccountName: ''
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

        $scope.loadStatus = function(){
            $http.get("/users/loadStatus.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.status = result.data.status;
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

        $scope.loadUsersByString = function(searchString){
            $http.get("/users/loadLdapUserByString.json", {
                params: {
                    'angular': true,
                    'samaccountname': searchString
                }
            }).then(function(result){
                $scope.usersForSelect = result.data.usersForSelect;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
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
            $http.post("/users/addFromLdap.json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                $state.go('UsersIndex').then(function(){
                    NotyService.scrollTop();
                });

            }, function errorCallback(result){
                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.$watch('data.selectedSamAccountName', function(){
            $scope.post.User.firstname = $scope.data.selectedSamAccountName.givenname;
            $scope.post.User.lastname = $scope.data.selectedSamAccountName.sn;
            $scope.post.User.samaccountname = $scope.data.selectedSamAccountName.samaccountname;
            $scope.post.User.email = $scope.data.selectedSamAccountName.email;
            if($scope.data.selectedSamAccountName.hasOwnProperty('dn')){
                $scope.post.User.ldap_dn = $scope.data.selectedSamAccountName.dn;
            }
        }, true);

        $scope.loadContainer();
        $scope.loadStatus();
        $scope.loadDateformats();
        $scope.loadUsergroups();
        $scope.loadSystemsettings();
        $scope.loadUsersByString();
    });