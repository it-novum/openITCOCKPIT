angular.module('openITCOCKPIT')
    .controller('StatuspagesEditController', function($scope, $http, SudoService, $state, $stateParams, NotyService){

        $scope.id = $stateParams.id;
        $scope.init = true;
        $scope.container_id = null;
        $scope.post = {
            Statuspage: {
                id: 0,
                name: '',
                show_comments: false,
                created: "",
                modified: "",
                hosts: {
                    _ids: [],
                },
                services: {
                    _ids: [],
                },
                hostgroups: {
                    _ids: [],
                },
                servicegroups: {
                    _ids: [],
                }
            }
        };

        $scope.loadStatuspage = function(){
            var params = {
                'angular': true
            };

            $http.get("/statuspages/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Statuspage = result.data.Statuspage;
                console.log($scope.post.Statuspage);
                $scope.post.Statuspage.public = +result.data.Statuspage.public;
                $scope.post.Statuspage.show_comments = +result.data.Statuspage.show_comments;
                $scope.container_id =  result.data.Statuspage.containers._ids[0];
                console.log($scope.container_id);
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
            $scope.loadContainers();
        };

        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            /* $http.get("/containers/loadContainersForAngular.json", {
                 params: params
             }).then(function(result){
                 $scope.containers = result.data.containers;
                 $scope.init = false;
             }); */
            $http.get("/statuspages/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });

        };

        $scope.loadHosts = function(searchString){
            if($scope.post.Statuspage.containers._ids.length === 0){
                return;
            }
            if($scope.container_id !== null){
                $http.get("/hosts/loadHostsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.container_id,
                        'filter[Hosts.name]': searchString,
                        'selected[]': $scope.post.Statuspage.hosts._ids,
                        'resolveContainerIds': true
                    }
                }).then(function(result){
                    $scope.hosts = result.data.hosts;
                });
            }
        };

        $scope.loadServices = function(searchString){
            if($scope.post.Statuspage.containers._ids.length === 0){
                return;
            }

            if ($scope.container_id !== null) {

                $scope.params = {
                    'containerId': $scope.container_id,
                    'filter': {
                        'servicename': searchString,
                    },
                    'selected': $scope.post.Statuspage.services._ids
                };

                $http.post("/services/loadServicesByContainerIdCake4.json?angular=true",
                    $scope.params
                ).then(function(result) {
                    $scope.services = result.data.services;
                });
            }
        };

        $scope.loadHostgroups = function(searchString){
            if($scope.init){
                return;
            }
            if($scope.container_id !== null){
                $http.get("/hostgroups/loadHostgroupsByString.json", {
                    params: {
                        'angular': true,
                        'filter[Containers.name]': searchString,
                        'selected[]': $scope.hostgroups_ids,
                        'resolveContainerIds': true
                    }
                }).then(function(result){
                    $scope.hostgroups = result.data.hostgroups;
                });
            }
        };

        $scope.loadServicegroups = function(searchString){
            if($scope.post.Statuspage.containers._ids.length === 0){
                return;
            }
            if($scope.container_id !== null){
                $http.get("/servicegroups/loadServicegroupsByString.json", {
                    params: {
                        'angular': true,
                        'filter[Containers.name]': searchString,
                        'selected[]': $scope.servicegroups_ids,
                        'resolveContainerIds': true

                    }
                }).then(function(result){
                    $scope.servicegroups = result.data.servicegroups;
                });
            }
        };




        $scope.submit = function() {
            $http.post("/statuspages/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('StatuspagesEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: 'Statuspage update succesfull'
                });

                $state.go('StatuspagesIndex').then(function(){
                     NotyService.scrollTop();
                });

            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };


        $scope.$watch('container_id', function(){
            if($scope.init){
                return;
            }
            $scope.post.Statuspage.containers._ids = [],
            $scope.post.Statuspage.containers._ids.push($scope.container_id);
            $scope.loadHosts('');
            $scope.loadServices('');
            $scope.loadHostgroups('');
            $scope.loadServicegroups('');


        }, true);



        //Fire on page load
        $scope.loadStatuspage();

    });
