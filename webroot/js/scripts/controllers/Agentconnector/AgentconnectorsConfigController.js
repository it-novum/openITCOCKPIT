angular.module('openITCOCKPIT')
    .controller('AgentconnectorsConfigController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){

        $scope.connectorConfig = {};
        $scope.hostId = $stateParams.hostId;
        var urlMode = $stateParams.mode || null;

        // Load current agent config if any exists
        $scope.load = function(searchString, selected){
            $http.get("/agentconnector/config.json", {
                params: {
                    hostId: $scope.hostId,
                    'angular': true
                }
            }).then(function(result){
                $scope.config = result.data.config;
                $scope.host = result.data.host;

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


        // Validate and save agent config
        $scope.submit = function(){
            $http.post("/agentconnector/config.json",
                $scope.config
            ).then(function(result){
                console.log('this works fine!!!');
                //RedirectService.redirectWithFallback('CommandsIndex');
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
