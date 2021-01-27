angular.module('openITCOCKPIT')
    .controller('AgentconnectorsInstallController', function($scope, $http, $state, $stateParams, RedirectService){

        $scope.connectorConfig = {};
        $scope.hostId = $stateParams.hostId;

        // Load current agent config if any exists
        $scope.load = function(searchString, selected){
            $http.get("/agentconnector/install.json", {
                params: {
                    hostId: $scope.hostId,
                    'angular': true
                }
            }).then(function(result){
                $scope.config = result.data.config;
                $scope.host = result.data.host;
                $scope.config_as_ini = result.data.config_as_ini;
            });
        };


        //Fire on page load
        $scope.load();
    });
