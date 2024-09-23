angular.module('openITCOCKPIT')
    .controller('UsercontainerrolesEditController', function($scope, $http, $stateParams, $state, NotyService, RedirectService) {

        $scope.id = $stateParams.id;
        $scope.init = true;

        var getContainerName = function(containerId) {
            containerId = parseInt(containerId, 10);
            for(var index in $scope.containers) {
                if($scope.containers[index].key === containerId) {
                    return $scope.containers[index].value;
                }
            }

            return 'ERROR UNKNOWN CONTAINER';
        };


        $scope.load = function() {
            $scope.selectedContainers = [];
            $scope.selectedContainerWithPermission = {};

            $http.get("/usercontainerroles/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result) {


                var data = result.data.usercontainerrole;

                //Reformat data that it looks like the same like it looks in the add method...
                $scope.selectedContainers = data.containers._ids;
                delete data.containers;

                //Add new selected containers
                for(var containerId in data.ContainersUsercontainerrolesMemberships) {
                    var notPermittetCheck = $scope.containers.find(({key}) => key === parseInt(containerId, 10));
                    $scope.selectedContainerWithPermission[containerId] = {
                        name: getContainerName(containerId),
                        container_id: parseInt(containerId, 10),
                        permission_level: data.ContainersUsercontainerrolesMemberships[containerId].toString(), //String is required for AngularJS Front End value="2",
                        readonly: ( typeof notPermittetCheck === "undefined" )
                    };

                }
                data.ContainersUsercontainerrolesMemberships = {};

                $scope.post = {
                    Usercontainerrole: data
                };
                $scope.init = false;

                // Load ldap groups
                $scope.loadLdapGroups();

            }, function errorCallback(result) {
                if(result.status === 403) {
                    $state.go('403');
                }

                if(result.status === 404) {
                    $state.go('404');
                }
            });
        };


        $scope.loadContainers = function() {
            $http.get("/containers/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result) {
                $scope.containers = result.data.containers;
                $scope.load();
            });
        };

        $scope.loadLdapGroups = function(searchString) {
            $http.get("/usercontainerroles/loadLdapgroupsForAngular.json", {
                params: {
                    'angular': true,
                    'filter[Ldapgroups.cn]': searchString,
                    'selected[]': $scope.post.Usercontainerrole.ldapgroups._ids
                }
            }).then(function(result) {
                $scope.isLdapAuth = result.data.isLdapAuth;
                $scope.ldapgroups = result.data.ldapgroups;
            });
        };

        $scope.submit = function() {
            //Define $scope.post.Usercontainerrole.ContainersUsercontainerrolesMemberships
            var ContainersUsercontainerrolesMemberships = {};
            for(var containerId in $scope.selectedContainerWithPermission) {
                ContainersUsercontainerrolesMemberships[containerId] = $scope.selectedContainerWithPermission[containerId].permission_level;
            }
            $scope.post.Usercontainerrole.ContainersUsercontainerrolesMemberships = ContainersUsercontainerrolesMemberships;

            $http.post("/usercontainerroles/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result) {
                var url = $state.href('UsercontainerrolesEdit', {id: result.data.usercontainerrole.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('UsercontainerrolesIndex');
                console.log('Data saved successfully');
            }, function errorCallback(result) {
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')) {
                    $scope.errors = result.data.error;
                }
            });
        };


        $scope.$watch('selectedContainers', function() {
            if($scope.init) {
                return;
            }
            if(typeof $scope.selectedContainers === "undefined") {
                //Is undefined on initial page load
                return;
            }

            //Add new selected containers
            for(var index in $scope.selectedContainers) {
                var containerId = $scope.selectedContainers[index];
                if(!$scope.selectedContainerWithPermission.hasOwnProperty(containerId)) {

                    var permission_level = 1;
                    if(containerId === 1) {
                        // ROOT_CONTAINER is always read/write !
                        permission_level = 2;
                    }
                    $scope.selectedContainerWithPermission[containerId] = {
                        name: getContainerName(containerId),
                        container_id: parseInt(containerId, 10),
                        permission_level: permission_level.toString(), //String is required for AngularJS Front End value="2",
                        readonly: false
                    };
                }
            }
            //Remove "unselected" containers
            for(var containerId in $scope.selectedContainerWithPermission) {
                //Do not mix strings and integers with indexOf !
                containerId = parseInt(containerId, 10);
                if($scope.selectedContainers.indexOf(containerId) === -1 && $scope.selectedContainerWithPermission[containerId].readonly === false) {
                    //Container was removed from select box - remove it from permissions object
                    delete $scope.selectedContainerWithPermission[containerId];
                }
            }
        }, true);

        $scope.loadContainers();
    });
