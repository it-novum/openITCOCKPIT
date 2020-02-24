angular.module('openITCOCKPIT')
    .controller('MattermostSettingsIndexController', function($scope, $http, $state, NotyService, RedirectService){

        $scope.post = {
            webhook_url: '',
            two_way: true,
            apikey: '',
            use_proxy: false
        };

        $scope.hasError = null;

        $scope.load = function(){
            $http.get("/mattermost_module/MattermostSettings/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post = result.data.mattermostSettings;

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
            $http.post("/mattermost_module/MattermostSettings/index.json?angular=true",
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

        };

        $scope.load();
    });
