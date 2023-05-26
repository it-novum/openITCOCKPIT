angular.module('openITCOCKPIT')
    .controller('AgentconnectorsShowOutputController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){

        $scope.mode = $stateParams.mode || 'pull';

        var renderJsoneditor = function(){
            var container = document.getElementById('jsoneditor');

            var options = {
                mode: 'view',
                colorPicker: false,
                timestampTag: false
            };

            $scope.editor = new JSONEditor(container, options, JSON.parse($scope.outputAsJson));
        };

        $scope.load = function(searchString, selected){
            $http.get("/agentconnector/showOutput.json", {
                params: {
                    'angular': true,
                    mode: $scope.mode,
                    id: $stateParams.id // hostId or push_agents.id in push mode
                }
            }).then(function(result){
                $scope.config = result.data.config;
                $scope.host = result.data.host;
                $scope.outputAsJson = result.data.outputAsJson;
                $scope.pushAgentUuid = result.data.pushAgentUuid;
                renderJsoneditor();
            });
        };

        //Fire on page load
        $scope.load();
    });
