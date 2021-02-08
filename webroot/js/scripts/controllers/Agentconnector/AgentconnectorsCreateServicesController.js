angular.module('openITCOCKPIT')
    .controller('AgentconnectorsCreateServicesController', function($scope, $http, $state, $stateParams, RedirectService, NotyService){

        $scope.connectorConfig = {};
        $scope.hostId = $stateParams.hostId;
        $scope.disableNext = true;
        $scope.runningCheck = true;

        // Load current agent config if any exists
        $scope.load = function(searchString, selected){
            $scope.runningCheck = true;
            $http.get("/agentconnector/create_services.json", {
                params: {
                    hostId: $scope.hostId,
                    'angular': true
                }
            }).then(function(result){
                $scope.runningCheck = false;
                $scope.config = result.data.config;
                $scope.host = result.data.host;
                $scope.services = result.data.services;
                //$scope.connection_test = result.data.connection_test;
                //$scope.disableNext = $scope.connection_test.status !== 'success';
            });
        };


        $scope.submit = function(){
            console.log('submit');
        };


        //Fire on page load
        $scope.load();
    });
