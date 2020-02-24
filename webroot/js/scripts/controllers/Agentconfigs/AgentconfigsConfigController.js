angular.module('openITCOCKPIT')
    .controller('AgentconfigsConfigController', function($scope, $http, QueryStringService, $state, $stateParams, NotyService){

        $scope.hostId = $stateParams.hostId;

        $scope.post = {
            Agentconfig: {
                port: 3333,
                use_https: 0,
                insecure: 1,
                basic_auth: 0,
                username: '',
                password: ''
            }
        };

        $scope.load = function(){
            var params = {
                'angular': true
            };

            $http.get("/agentconfigs/config/" + $scope.hostId + ".json", {
                params: params
            }).then(function(result){
                $scope.host = result.data.host;
                $scope.post.Agentconfig = result.data.config;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.submit = function(){
            $http.post("/agentconfigs/config/" + $scope.hostId + ".json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess({
                    message: $scope.successMessage.objectName + ' ' + $scope.successMessage.message
                });
                $state.go('AgentconfigsScan', {
                    hostId: $scope.hostId
                });

                console.log('Data saved successfully');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.load();

    });