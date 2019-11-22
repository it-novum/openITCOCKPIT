angular.module('openITCOCKPIT')
    .controller('ProxyIndexController', function($scope, $http, NotyService){

        $scope.post = {
            Proxy: {
                id: 1, //its 1 every time
                ipaddress: '',
                port: null,
                enabled: false
            }
        };

        $scope.hasError = null;

        $scope.load = function(){
            $http.get("/proxy/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post.Proxy = result.data.proxy;
                if($scope.post.Proxy.port === 0){
                    $scope.post.Proxy.port = null;
                }
            });
        };


        $scope.submit = function(){
            $http.post("/proxy/index.json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess({
                    message: $scope.successMessage.objectName + ' ' + $scope.successMessage.message
                });
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        $scope.load();
    });