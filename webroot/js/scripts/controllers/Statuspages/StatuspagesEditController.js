angular.module('openITCOCKPIT')
    .controller('StatuspagesEditController', function($scope, $http, SudoService, $state, $stateParams, NotyService){

        $scope.id = $stateParams.id;

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
                $scope.post.Statuspage = result.data.Statuspage;
                $scope.post.Statuspage.public = +result.data.Statuspage.public;
                $scope.post.Statuspage.show_comments = +result.data.Statuspage.show_comments;
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

            $http.get("/containers/loadContainersForAngular.json", {
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
            $http.get("/statuspages/loadHostsByContainerIds.json", {
                params: {
                    'angular': true,
                    'containerIds[]': $scope.post.Statuspage.containers._ids,
                    'filter[Hosts.name]': searchString,
                    'selected[]': $scope.post.Statuspage.hosts._ids
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        $scope.loadServices = function(searchString){
            if($scope.post.Statuspage.containers._ids.length === 0){
                return;
            }
            $http.get("/statuspages/loadServicesByContainerIds.json", {
                params: {
                    'angular': true,
                    'containerIds[]': $scope.post.Statuspage.containers._ids,
                    'filter[servicename]': searchString,
                    'selected[]': $scope.post.Statuspage.services._ids
                }
            }).then(function(result){
                $scope.services = result.data.services;
            });
        };



        $scope.submit = function() {
            $http.post("/statuspages/setAlias/" + $scope.id + ".json?angular=true",
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
        }

        $scope.$watch('post.Statuspage.containers._ids', function(){
            if($scope.init){
                return;
            }
            $scope.loadHosts('');
            $scope.loadServices('');
            // $scope.loadHostgroups('');
            //$scope.loadServicegroups('');

            if($scope.post.Statuspage.containers._ids.length === 0){
                //reset host,service hostgroup and service chosen boxes if all containers are deselected
                $scope.post.Statuspage.hosts._ids = [];
                $scope.hosts = [];
                $scope.post.Statuspage.services._ids = [];
                $scope.services = [];
                //$scope.post.Statuspages.hostgroups._ids = [];
               // $scope.hostgroups = [];
               // $scope.post.Statuspages.servicegroups._ids = [];
               // $scope.servicegroups = [];
            }
        }, true);

        $scope.submit = function(){
            $http.post("/statuspages/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('StatuspagesEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });
                $state.go('StatuspagesIndex');
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };

        //Fire on page load
        $scope.loadStatuspage();

    });
