angular.module('openITCOCKPIT')
    .controller('UsercontainerrolesAddController', function($scope, $http, $state, NotyService, RedirectService){
        $scope.data = {
            createAnother: false
        };

        var clearForm = function(){
            $scope.selectedContainers = [];
            $scope.selectedContainerWithPermission = {};

            $scope.post = {
                Usercontainerrole: {
                    name: '',
                    ContainersUsercontainerrolesMemberships: {},
                    ldapgroups: {
                        _ids: []
                    }
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

        $scope.loadContainer = function(){
            $http.get("/containers/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
            });
        };

        $scope.loadLdapGroups = function(searchString){
            $http.get("/usercontainerroles/loadLdapgroupsForAngular.json", {
                params: {
                    'angular': true,
                    'filter[Ldapgroups.cn]': searchString,
                    'selected[]': $scope.post.Usercontainerrole.ldapgroups._ids
                }
            }).then(function(result){
                $scope.isLdapAuth = result.data.isLdapAuth;
                $scope.ldapgroups = result.data.ldapgroups;
            });
        };


        $scope.submit = function(){
            //Define $scope.post.Usercontainerrole.ContainersUsercontainerrolesMemberships
            var ContainersUsercontainerrolesMemberships = {};
            for(var containerId in $scope.selectedContainerWithPermission){
                ContainersUsercontainerrolesMemberships[containerId] = $scope.selectedContainerWithPermission[containerId].permission_level;
            }
            $scope.post.Usercontainerrole.ContainersUsercontainerrolesMemberships = ContainersUsercontainerrolesMemberships;

            $http.post("/usercontainerroles/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('UsercontainerrolesEdit', {id: result.data.usercontainerrole.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });


                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('UsercontainerrolesIndex');
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


        $scope.$watch('selectedContainers', function(){
            if($scope.selectedContainers.length === 0){
                //No user containers selected
                $scope.selectedContainerWithPermission = {};
                return;
            }

            //Add new selected containers
            for(var index in $scope.selectedContainers){
                var containerId = $scope.selectedContainers[index];
                if(!$scope.selectedContainerWithPermission.hasOwnProperty(containerId)){

                    var permission_level = 1;
                    if(containerId === 1){
                        // ROOT_CONTAINER is always read/write !
                        permission_level = 2;
                    }

                    $scope.selectedContainerWithPermission[containerId] = {
                        name: getContainerName(containerId),
                        container_id: parseInt(containerId, 10),
                        permission_level: permission_level.toString() //String is required for AngularJS Front End value="2"
                    };
                }
            }

            //Remove "unselected" containers
            for(var containerId in $scope.selectedContainerWithPermission){
                //Do not mix strings and integers with indexOf !
                containerId = parseInt(containerId, 10);
                if($scope.selectedContainers.indexOf(containerId) === -1){
                    //Container was removed from select box - remove it from permissions object
                    delete $scope.selectedContainerWithPermission[containerId];
                }
            }
        }, true);

        //Fire on page load
        $scope.loadContainer();
        $scope.loadLdapGroups();

    });
