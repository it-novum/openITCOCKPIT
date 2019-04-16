angular.module('openITCOCKPIT')
    .controller('UsersAddController', function($scope, $http, $rootScope, $state, NotyService){
        $scope.intervalText = 'disabled';
        $scope.post = {
            'User': {
                'status': '',
                'email': '',
                'firstname': '',
                'lastname': '',
                'company': null,
                'position': null,
                'phone': null,
                'password': '',
                'confirm_password': '',
                'usergroup_id': '',
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
            $http.get("/usergroups/loadUsergroups.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.usergroups = result.data.usergroups;
            });
        };

        $scope.loadStatus = function(){
            $http.get("/users/loadStatus.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.status = result.data.status;
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
            $http.post("/users/add.json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                $state.go('UsersIndex');

            }, function errorCallback(result){
                NotyService.genericError();
                console.log(result);
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };


        $scope.loadContainer();
        $scope.loadUsergroups();
        $scope.loadStatus();
        $scope.loadDateformats();
    });

