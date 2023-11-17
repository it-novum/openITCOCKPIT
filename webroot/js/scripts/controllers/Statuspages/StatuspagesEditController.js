angular.module('openITCOCKPIT')
    .controller('StatuspagesEditController', function($scope, $http, SudoService, $state, $stateParams, NotyService){

        $scope.id = $stateParams.id;
        $scope.init = true;
        $scope.container_id = null;
        $scope.post = {
            Statuspage: {},
        };

        $scope.loadStatuspage = function(){
            var params = {
                'angular': true
            };

            $http.get("/statuspages/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                console.log(result.data.Statuspage);
                $scope.post.Statuspage = result.data.Statuspage;
                $scope.post.Statuspage.public = +result.data.Statuspage.public;
                $scope.post.Statuspage.show_comments = +result.data.Statuspage.show_comments;
                $scope.container_id = +result.data.Statuspage.containers._ids[0];
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
            if($scope.container_id){
                $http.get("/hosts/loadHostsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.container_id,
                        'filter[Hosts.name]': searchString,
                        'selected[]': $scope.hosts_ids,
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
            if($scope.container_id){
                $http.get("/services/loadServicesByStringCake4.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.container_id,
                        'filter[servicename]': searchString,
                        'selected[]': $scope.services_ids,
                        'resolveContainerIds': true
                    }
                }).then(function(result){
                    $scope.services = result.data.services;
                });
            }
        };

        $scope.loadHostgroups = function(searchString){
            if($scope.init){
                return;
            }
            if($scope.container_id){
                $http.get("/hostgroups/loadHostgroupsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Statuspage.containers._ids,
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
            if($scope.container_id){
                $http.get("/servicegroups/loadServicegroupsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.container_id,
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
                   // NotyService.scrollTop();
                });

            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.performIntersection = function (arr1, arr2) {
            // converting into Set
            const setA = new Set(arr1);
            const setB = new Set(arr2);

            let intersectionResult = [];

            for (let i of setB) {

                if (setA.has(i)) {
                    intersectionResult.push(i);
                }
            }
            return intersectionResult;
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
