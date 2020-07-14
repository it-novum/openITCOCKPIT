angular.module('openITCOCKPIT')
    .controller('UsersEditController', function($scope, $http, $stateParams, $state, NotyService, RedirectService, $q){

        $scope.id = $stateParams.id;
        $scope.isLdapUser = false;
        $scope.localeOptions = [];

        var getContainerName = function(containerId){
            containerId = parseInt(containerId, 10);
            for(var index in $scope.containers){
                if($scope.containers[index].key === containerId){
                    return $scope.containers[index].value;
                }
            }

            return 'ERROR UNKNOWN CONTAINER';
        };

        $scope.intervalText = 'disabled';

        $scope.load = function(){
            $scope.selectedUserContainers = [];
            $scope.selectedUserContainerWithPermission = {};

            $http.get("/users/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){

                $scope.isLdapUser = result.data.isLdapUser;

                var data = result.data.user;
                data.password = '';
                data.confirm_password = '';

                //Reformat data that it looks like the same like it looks in the add method...
                $scope.selectedUserContainers = data.containers._ids;
                delete data.containers;

                //Add new selected containers
                for(var containerId in data.ContainersUsersMemberships){
                    $scope.selectedUserContainerWithPermission[containerId] = {
                        name: getContainerName(containerId),
                        container_id: parseInt(containerId, 10),
                        permission_level: data.ContainersUsersMemberships[containerId].toString() //String is required for AngularJS Front End value="2"
                    };
                }
                data.ContainersUsersMemberships = {};

                $scope.post = {
                    User: data
                };

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.loadUserContaineRoles = function(){
            return $http.get("/users/loadContainerRoles.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.usercontainerroles = result.data.usercontainerroles;
            });
        };

        $scope.loadContainer = function(){
            return $http.get("/containers/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
            });
        };

        $scope.loadLocaleOptions = function(){
            return $http.get("/users/getLocaleOptions.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.localeOptions = result.data.localeOptions;
            });
        };

        $scope.loadUsergroups = function(){
            return $http.get("/users/loadUsergroups.json", {
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

        $scope.loadContainerPermissions = function(){
            if(typeof $scope.post.User.usercontainerroles !== "undefined"){ //is undefined on initial page load
                if($scope.post.User.usercontainerroles._ids.length === 0){
                    $scope.userContainerRoleContainerPermissions = {};
                    return;
                }
            }

            $http.get("/users/loadContainerPermissions.json", {
                params: {
                    'angular': true,
                    'usercontainerRoleIds[]': $scope.post.User.usercontainerroles._ids
                }
            }).then(function(result){
                $scope.userContainerRoleContainerPermissions = result.data.userContainerRoleContainerPermissions;
            });
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
            //Define $scope.post.User.ContainersUsersMemberships
            var ContainersUsersMemberships = {};
            for(var containerId in $scope.selectedUserContainerWithPermission){
                ContainersUsersMemberships[containerId] = $scope.selectedUserContainerWithPermission[containerId].permission_level;
            }
            $scope.post.User.ContainersUsersMemberships = ContainersUsersMemberships;

            $http.post("/users/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('UsersEdit', {id: result.data.user.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('UsersIndex');
                console.log('Data saved successfully');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.$watch('post.User.usercontainerroles._ids', function(){
            if(typeof $scope.post.User.usercontainerroles === "undefined"){
                //Is undefined on initial page load
                return;
            }

            $scope.loadContainerPermissions();
        }, true);

        $scope.$watch('selectedUserContainers', function(){
            if(typeof $scope.selectedUserContainers === "undefined"){
                //Is undefined on initial page load
                return;
            }

            if($scope.selectedUserContainers.length === 0){
                //No user containers selected
                $scope.selectedUserContainerWithPermission = {};
                return;
            }

            //Add new selected containers
            for(var index in $scope.selectedUserContainers){
                var containerId = $scope.selectedUserContainers[index];
                if(!$scope.selectedUserContainerWithPermission.hasOwnProperty(containerId)){

                    var permission_level = 1;
                    if(containerId === 1){
                        // ROOT_CONTAINER is always read/write !
                        permission_level = 2;
                    }

                    $scope.selectedUserContainerWithPermission[containerId] = {
                        name: getContainerName(containerId),
                        container_id: parseInt(containerId, 10),
                        permission_level: permission_level.toString() //String is required for AngularJS Front End value="2"
                    };
                }
            }

            //Remove "unselected" containers
            for(var containerId in $scope.selectedUserContainerWithPermission){
                //Do not mix strings and integers with indexOf !
                containerId = parseInt(containerId, 10);
                if($scope.selectedUserContainers.indexOf(containerId) === -1){
                    //Container was removed from select box - remove it from permissions object
                    delete $scope.selectedUserContainerWithPermission[containerId];
                }
            }
        }, true);

        var promise1 = $scope.loadUserContaineRoles();
        var promise2 = $scope.loadContainer();
        var promise3 = $scope.loadLocaleOptions();

        $q.all([promise1, promise2, promise3]).then(function(result){
            //Load user config
            $scope.load();
        });

        $scope.loadUsergroups();
        $scope.loadDateformats();

    });
