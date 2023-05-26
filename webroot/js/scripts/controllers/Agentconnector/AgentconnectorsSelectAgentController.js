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

            // ITC-2879 & ITC-2932
            // Send back the agent_uuid to the server
            // so that openITCOCKPIT can update the record in the satellite_push_agents table (Import Module)
            var agentUuid = 'Unknown';
            for(var i in $scope.pushAgents){
                if($scope.pushAgents[i].id === $scope.selectedPushAgentId){
                    agentUuid = $scope.pushAgents[i].agent_uuid;
                }
            }

            $http.post("/agentconnector/select_agent.json", {
                    pushagent: {
                        id: $scope.selectedPushAgentId,
                        host_id: $scope.hostId,
                        agent_uuid: agentUuid
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
