angular.module('openITCOCKPIT')
    .controller('UsersAddController', function($scope, $http, $state, NotyService, RedirectService){
        $scope.data = {
            createAnother: false
        };
        $scope.localeOptions = [];

        var clearForm = function(){
            $scope.selectedUserContainers = [];
            $scope.selectedUserContainerWithPermission = {};

            $scope.post = {
                User: {
                    firstname: '',
                    lastname: '',
                    email: '',
                    phone: '',
                    is_active: 1,
                    showstatsinmenu: 0,
                    paginatorlength: 25,
                    dashboard_tab_rotation: 0,
                    recursive_browser: 0,
                    dateformat: 'H:i:s - d.m.Y',
                    timezone: 'Europe/Berlin',
                    i18n: 'en_US',
                    password: '',
                    confirm_password: '',
                    is_oauth: 0,

                    usergroup_id: 0,
                    usercontainerroles: {
                        _ids: []
                    },
                    ContainersUsersMemberships: {},
                }
            };
        };
        clearForm();

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

        $scope.loadUserContaineRoles = function(){
            $http.get("/users/loadContainerRoles.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.usercontainerroles = result.data.usercontainerroles;
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

        $scope.loadLocaleOptions = function(){
            return $http.get("/users/getLocaleOptions.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.localeOptions = result.data.localeOptions;
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
            if($scope.post.User.usercontainerroles._ids.length === 0){
                $scope.userContainerRoleContainerPermissions = {};
                return;
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

            if($scope.post.User.is_oauth === 1){
                //oAuth 2 users don't have a password
                $scope.post.User.password = '';
                $scope.post.User.confirm_password = '';
            }

            $http.post("/users/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('UsersEdit', {id: result.data.user.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });


                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('UsersIndex');
                }else{
                    clearForm();
                    $scope.errors = {};
                    NotyService.scrollTop();
                }

                console.log('Data saved successfully');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.$watch('post.User.usercontainerroles._ids', function(){
            $scope.loadContainerPermissions();
        }, true);

        $scope.$watch('selectedUserContainers', function(){
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

        $scope.loadUserContaineRoles();
        $scope.loadContainer();
        $scope.loadUsergroups();
        $scope.loadDateformats();
        $scope.loadLocaleOptions();
    });
