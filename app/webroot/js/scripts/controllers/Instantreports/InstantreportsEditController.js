angular.module('openITCOCKPIT')
    .controller('InstantreportsEditController', function($scope, $state, $stateParams, $location, $http, NotyService, RedirectService){

        $scope.id = $stateParams.id;
        $scope.init = true;

        $scope.loadContainer = function(){
            $http.get("/instantreports/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadInstantreport = function(){
            var params = {
                'angular': true
            };

            $http.get("/instantreports/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post = result.data.instantreport;
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
            $scope.loadContainer();
        };

        $scope.loadTimeperiods = function(){
            $http.get("/timeperiods/loadTimeperiodsByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Instantreport.container_id
                }
            }).then(function(result){
                $scope.timeperiods = result.data.timeperiods;
            });
        };

        $scope.loadHostgroups = function(){
            if($scope.init){
                return;
            }
            if($scope.post.Instantreport.container_id){
                $http.get("/hostgroups/loadHosgroupsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'selected[]': $scope.post.Instantreport.hostgroups._ids
                    }
                }).then(function(result){
                    $scope.hostgroups = result.data.hostgroups;
                });
            }
        };

        $scope.loadHosts = function(searchString){
            if($scope.post.Instantreport.container_id){
                $http.get("/hosts/loadHostsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'filter[Hosts.name]': searchString,
                        'selected[]': $scope.post.Instantreport.hosts._ids
                    }
                }).then(function(result){
                    $scope.hosts = result.data.hosts;
                });
            }
        };

        $scope.loadServicegroups = function(){
            if($scope.init){
                return;
            }
            if($scope.post.Instantreport.container_id){
                $http.get("/servicegroups/loadServicegroupsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'selected[]': $scope.post.Instantreport.servicegroups._ids

                    }
                }).then(function(result){
                    $scope.servicegroups = result.data.servicegroups;
                });
            }
        };

        $scope.loadServices = function(searchString){
            if($scope.post.Instantreport.container_id){
                $http.get("/services/loadServicesByStringCake4.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'filter[Host.name]': searchString,
                        'filter[Service.servicename]': searchString,
                        'selected[]': $scope.post.Instantreport.services._ids
                    }
                }).then(function(result){
                    $scope.services = result.data.services;
                });
            }
        };

        $scope.loadUsers = function(){
            $http.get("/users/loadUsersByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Instantreport.container_id,
                    'selected[]': $scope.post.Instantreport.users._ids
                }
            }).then(function(result){
                $scope.users = result.data.users;
            });
        };

        $scope.submit = function(){
            $http.post("/instantreports/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess({
                    message: '<u><a href="' + $location.absUrl() + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });
                RedirectService.redirectWithFallback('InstantreportsIndex');
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.resetOnTypeChange = function(){
            $scope.post.Instantreport.hostgroups._ids = [];
            $scope.post.Instantreport.hosts._ids = [];
            $scope.post.Instantreport.servicegroups._ids = [];
            $scope.post.Instantreport.services._ids = [];
        };

        $scope.$watch('post.Instantreport.container_id', function(){
            if($scope.init){
                return;
            }
            switch($scope.post.Instantreport.type){
                case 1:
                    $scope.loadHostgroups('');
                    break;
                case 2:
                    $scope.loadHosts('');
                    break;
                case 3:
                    $scope.loadServicegroups('');
                    break;
                case 4:
                    $scope.loadServices('');
                    break;
            }
            $scope.loadTimeperiods('');
            $scope.loadUsers('');
        }, true);

        $scope.$watch('post.Instantreport.type', function(newValue, oldValue){
            if($scope.init){
                return;
            }
            if(!$scope.post.Instantreport.container_id){
                return;
            }
            if(oldValue ===  null){
                return;
            }

            $scope.resetOnTypeChange();
            switch($scope.post.Instantreport.type){
                case 1:
                    $scope.loadHostgroups('');
                    break;
                case 2:
                    $scope.loadHosts('');
                    break;
                case 3:
                    $scope.loadServicegroups('');
                    break;
                case 4:
                    $scope.loadServices('');
                    break;
            }
        }, true);

        $scope.$watch('post.Instantreport.send_email', function(){
            if($scope.init){
                return;
            }
            if(!$scope.post.Instantreport.send_email){
                $scope.post.Instantreport.send_interval = 0;
                $scope.post.Instantreport.users._ids = [];
            }
        }, true);

        $scope.loadInstantreport();
    });
