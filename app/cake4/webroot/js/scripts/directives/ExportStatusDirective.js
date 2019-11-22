angular.module('openITCOCKPIT').directive('exportStatus', function($http, SudoService){
    return {
        restrict: 'A',

        controller: function($scope){

            $scope.exportRunning = false;

            $scope.callback = function(event){
                var data = JSON.parse(event.data);
                $scope.exportRunning = data.running;
            };

            $scope.connectToSudoServer = function(){
                $http.get("/angular/websocket_configuration.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.websocketConfig = result.data.websocket;
                    SudoService.setUrl($scope.websocketConfig['SUDO_SERVER.URL']);
                    SudoService.setApiKey($scope.websocketConfig['SUDO_SERVER.API_KEY']);
                    SudoService.onDispatch($scope.callback);
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