angular.module('openITCOCKPIT')
    .controller('StatuspagesStepTwoController', function($scope, $http, SudoService, $state, $stateParams, NotyService, RedirectService){
        $scope.id = $stateParams.id;

        $scope.post = {
            Statuspages: {}
        };

        $scope.hosts = {};
        $scope.services = {};
        $scope.hostgroups = {};
        $scope.servicegroups = {};

        $scope.init = true;
        $scope.hasError = null;
        $scope.errors = {};

        $scope.loadStatuspage = function(){
            var params = {
                'angular': true
            };

            $http.get("/statuspages/stepTwo/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Statuspages = result.data.Statuspages;
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
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


        $scope.submit = function(){
            $http.post("/statuspages/stepTwo/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('StatuspagesEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

               RedirectService.redirectWithFallback('StatuspagesIndex');
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadStatuspage();

    });
