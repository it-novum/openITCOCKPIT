angular.module('openITCOCKPIT')
    .controller('AgentconfigsScanController', function($scope, $http, QueryStringService, $state, $stateParams, NotyService){

        $scope.hostId = $stateParams.hostId;
        $scope.discoveryIsRunning = true;
        $scope.hasError = false;
        $scope.hasAgentOutput = false;

        $scope.selectedHealthChecks = [];
        $scope.selectedProcessChecks = [];

        $scope.totalServices = 0;
        $scope.isCreatingServices = false;
        $scope.percentage = 0;
        $scope.ajaxCount = 0;

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

        $scope.createServices = function(){
            $scope.totalServices = $scope.selectedHealthChecks.length + $scope.selectedProcessChecks.length;
            if($scope.totalServices === 0){
                NotyService.genericWarning({
                    message: 'No objects selected'
                });
                return;
            }

            $scope.isCreatingServices = true;

            var postData = {};

            if($scope.selectedHealthChecks.length > 0){
                for(var index in $scope.selectedHealthChecks){
                    var healthCheck = $scope.selectedHealthChecks[index];

                    postData = {
                        Service: healthCheck.agentcheck.service
                    };
                    postData.Service.host_id = $scope.hostId;

                    $scope.createService(postData);
                }
            }

            if($scope.selectedProcessChecks.length > 0){
                for(var index in $scope.selectedProcessChecks){
                    var processCheck = $scope.selectedProcessChecks[index];

                    postData = {
                        Service: processCheck.agentcheck.service
                    };
                    postData.Service.host_id = $scope.hostId;

                    $scope.createService(postData);
                }
            }
        };

        $scope.createService = function(postData){
            $http.post("/agentconfigs/createService.json?angular=true",
                postData
            ).then(function(result){
                $scope.ajaxCount++;

                $scope.percentage = Math.round($scope.ajaxCount / $scope.totalServices * 100);
                if($scope.ajaxCount === $scope.totalServices){
                    $state.go('ServicesServiceList', {
                        id: $scope.hostId
                    });
                }

                console.log('Data saved successfully');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        //Fire on page load
        $scope.load();
    });