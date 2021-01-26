angular.module('openITCOCKPIT')
    .controller('AgentconnectorsConfigController', function($scope, $http, $state, $stateParams){

        $scope.connectorConfig = {};
        $state.hostId = $stateParams.hostId;
        var urlMode = $stateParams.mode || null;

        $scope.load = function(searchString, selected){
            $http.get("/agentconnector/config.json", {
                params: {
                    hostId: $state.hostId,
                    'angular': true
                }
            }).then(function(result){
                $scope.config = result.data.config;

                if(urlMode !== null){
                    // The current AngularJS state has an "mode"
                    // User came from the first wizard page

                    $scope.config.bool.enable_push_mode = urlMode === 'push';
                }
            });
        };

        $scope.changeOs = function(os){
            $scope.config.string.operating_system = os;
        };


        //Fire on page load
        $scope.load();
    });
