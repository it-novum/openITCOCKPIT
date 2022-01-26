angular.module('openITCOCKPIT')
    .controller('AgentconnectorsSelectAgentController', function($scope, $http, $state, $stateParams, RedirectService, NotyService){

        $scope.hostId = $stateParams.hostId;
        $scope.isLoading = true;

        // Load current agent config if any exists
        $scope.load = function(searchString, selected){
            $scope.isLoading = true;
            $http.get("/agentconnector/select_agent.json", {
                params: {
                    hostId: $scope.hostId,
                    'angular': true
                }
            }).then(function(result){
                $scope.isLoading = false;
                $scope.config = result.data.config;
                $scope.host = result.data.host;
                $scope.pushAgents = result.data.pushAgents;
                $scope.selectedPushAgentId = result.data.selectedPushAgentId;
            });
        };


        $scope.submit = function(){
            $http.post("/agentconnector/select_agent.json", {
                    pushagent: {
                        id: $scope.selectedPushAgentId,
                        host_id: $scope.hostId
                    }
                }
            ).then(function(result){
                $state.go('AgentconnectorsCreateServices', {
                    hostId: $scope.hostId,
                    testConnection: 'false'
                }).then(function(){
                    NotyService.scrollTop();
                });
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };


        //Fire on page load
        $scope.load();
    });
