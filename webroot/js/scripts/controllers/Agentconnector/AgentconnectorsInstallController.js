angular.module('openITCOCKPIT')
    .controller('AgentconnectorsInstallController', function($scope, $http, $state, $stateParams, RedirectService, NotyService){

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

        $scope.submit = function(){
            if($scope.config.bool.enable_push_mode){
                console.log('Implement redirect to push mode')
            }else{
                $state.go('AgentconnectorsAutotls', {
                    hostId: $scope.hostId
                }).then(function(){
                    NotyService.scrollTop();
                });
            }
        };


        //Fire on page load
        $scope.load();
    });
