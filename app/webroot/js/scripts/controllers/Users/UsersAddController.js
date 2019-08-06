angular.module('openITCOCKPIT')
    .controller('UsersAddController', function($scope, $http, $rootScope, $state, NotyService, RedirectService){

        $scope.intervalText = 'disabled';
        $scope.post = {
            'User': {
                'email': '',
                'firstname': '',
                'lastname': '',
                'is_active': true,
                'company': null,
                'position': null,
                'phone': null,
                'password': '',
                'confirm_password': '',
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

        $scope.chosenContainerroles = {};


        $scope.loadUsercontainerroles = function(){
            $http.get("/usercontainerroles/loadUsercontainerrolesForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.usercontainerroles = result.data.usercontainerroles;
                $scope.usercontainerrolePermissions = result.data.usercontainerrolePermissions;
                console.log($scope.usercontainerroles);
                console.log($scope.usercontainerrolePermissions);
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
            $http.get("/usergroups/loadUsergroups.json", {
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

            $http.post("/users/add.json?angular=true",
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

        $scope.$watch('post.User.usercontainerroles._ids', function(){
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
        }, true);

        $scope.loadUsercontainerroles();
        $scope.loadContainer();
        $scope.loadUsergroups();
        $scope.loadDateformats();



    });

