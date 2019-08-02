angular.module('openITCOCKPIT')
    .controller('AgentconfigsScanController', function($scope, $http, QueryStringService, $state, $stateParams, NotyService){

        $scope.hostId = $stateParams.hostId;
        $scope.discoveryIsRunning = true;
        $scope.hasError = false;
        $scope.hasAgentOutput = false;

        $scope.load = function(){
            var params = {
                'angular': true
            };

            $http.get("/agentconfigs/scan/" + $scope.hostId + ".json", {
                params: params
            }).then(function(result){
                $scope.host = result.data.host;
                $scope.config = result.data.config;

                $scope.runDiscovery();

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.runDiscovery = function(){
            $scope.hasError = false;
            $scope.hasAgentOutput = false;

            var params = {
                'angular': true,
                'runDiscovery': 'true'
            };

            $http.get("/agentconfigs/scan/" + $scope.hostId + ".json", {
                params: params
            }).then(function(result){
                $scope.discoveryIsRunning = false;
                $scope.hasAgentOutput = true;
                $scope.mapping = result.data.mapping;

            }, function errorCallback(result){
                $scope.discoveryIsRunning = false;
                $scope.hasError = true;
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }

                if(result.status === 400){
                    //Bad request
                    $scope.error = result.data.error;
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