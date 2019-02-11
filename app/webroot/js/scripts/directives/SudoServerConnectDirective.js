angular.module('openITCOCKPIT').directive('sudoServerConnect', function($http, SudoService){
    return {
        restrict: 'A',

        controller: function($scope){

            //Connect to the SudoWebsocket Server
            //This connection is used by the rest of the openITCOCKPIT Interface

            $scope.connectToSudoServer = function(){
                $http.get("/angular/websocket_configuration.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.websocketConfig = result.data.websocket;
                    SudoService.setUrl($scope.websocketConfig['SUDO_SERVER.URL']);
                    SudoService.setApiKey($scope.websocketConfig['SUDO_SERVER.API_KEY']);
                    SudoService.onDispatch(function(event){
                        //console.log(event);
                    });
                    SudoService.connect();
                });

            };

            $scope.connectToSudoServer();

        },

        link: function(scope, element, attr){
            jQuery(element).find("[rel=tooltip]").tooltip();
        }
    };
});