angular.module('openITCOCKPIT')
    .controller('AgentconnectorsAutotlsController', function($scope, $http, $state, $stateParams, RedirectService, NotyService){

        $scope.connectorConfig = {};
        $scope.hostId = $stateParams.hostId;
        $scope.disableNext = true;
        $scope.runningCheck = true;

        // Load current agent config if any exists
        $scope.load = function(searchString, selected){
            $scope.runningCheck = true;
            $http.get("/agentconnector/autotls.json", {
                params: {
                    hostId: $scope.hostId,
                    'angular': true
                }
            }).then(function(result){
                $scope.runningCheck = false;
                $scope.config = result.data.config;
                $scope.host = result.data.host;
                $scope.connection_test = result.data.connection_test;
                $scope.disableNext = $scope.connection_test.status !== 'success';
            });
        };

        $scope.reExchangeAutoTLS = function(){
            $scope.runningCheck = true;
            $http.get("/agentconnector/autotls.json", {
                params: {
                    hostId: $scope.hostId,
                    'angular': true,
                    'reExchangeAutoTLS': 'true'
                }
            }).then(function(result){
                $scope.runningCheck = false;
                $scope.config = result.data.config;
                $scope.host = result.data.host;
                $scope.connection_test = result.data.connection_test;

                $scope.disableNext = $scope.connection_test.status !== 'success';

                if($scope.connection_test.status === 'success'){
                    $scope.disableNext = false;
                }
            });
        };

        $scope.submit = function(){
            console.log('submit');
        };


        //Fire on page load
        $scope.load();
    });
