angular.module('openITCOCKPIT')
    .controller('AdministratorsQuerylogController', function($scope, $http){

        $scope.init = true;
        $scope.connectionError = false;
        $scope.connected = false;
        $scope.manualReconnect = true;

        $scope.queryLog = [];

        $scope.onError = function(event){
            $scope.connectionError = true;
            $scope.manualReconnect = false;
            console.log(event);
        };

        $scope.onClose = function(event){
            if($scope.connected){
                $scope.connectionError = true;
                $scope.connected = false;
                $scope.manualReconnect = false;
                console.log(event);
            }
        };

        $scope.onOpen = function(event){
            $scope.connectionError = false;
            $scope.connected = true;
        };

        $scope.onMessage = function(event){
            $scope.queryLog.unshift(JSON.parse(event.data));

            if($scope.queryLog.length > 15){
                $scope.queryLog.pop() //Remote the last element
            }
        };

        $scope.truncate = function(){
            $scope.queryLog = [];
        };

        $scope.connectToQueryLogServer = function(){
            $scope.manualReconnect = true;
            $http.get("/angular/websocket_configuration.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.websocketConfig = result.data.websocket;

                $scope.connection = new WebSocket($scope.websocketConfig['QUERY_LOG.URL']);
                $scope.connection.onerror = $scope.onError;
                $scope.connection.onopen = $scope.onOpen;
                $scope.connection.onmessage = $scope.onMessage;
                $scope.connection.onclose = $scope.onClose;
            });

        };

        $scope.$on('$destroy', function(){
            if($scope.connection && $scope.connected){
                $scope.connection.close();
            }
        });

        $scope.connectToQueryLogServer();
    });
