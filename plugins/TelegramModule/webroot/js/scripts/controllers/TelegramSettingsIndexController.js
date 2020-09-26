angular.module('openITCOCKPIT')
    .controller('TelegramSettingsIndexController', function($scope, $http, $state, NotyService, RedirectService){

        $scope.post = {
            token: '',
            access_key: '',
            two_way: true,
            use_proxy: false,
            external_webhook_domain: '',
            webhook_api_key: ''
        };

        $scope.hasError = null;

        $scope.load = function(){
            $http.get("/telegram_module/TelegramSettings/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post = result.data.telegramSettings;

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };


        $scope.submit = function(){
            if($scope.post.two_way && ($scope.post.external_webhook_domain === "" || $scope.post.webhook_api_key === "")){
                NotyService.genericError({message: "Fill out all required fields!"});
            }else{
                $http.post("/telegram_module/TelegramSettings/index.json?angular=true",
                    $scope.post
                ).then(function(result){
                    NotyService.genericSuccess();
                    $scope.errors = null;
                }, function errorCallback(result){
                    NotyService.genericError();
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            }
        };

        $scope.load();
    });
