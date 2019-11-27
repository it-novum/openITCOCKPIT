angular.module('openITCOCKPIT')
    .controller('ConfigurationFilesEditController', function($scope, $http, $state, RedirectService, NotyService, $stateParams){

        $scope.isRestoring = false;

        $scope.load = function(){

            var params = {
                'angular': true
            };

            $http.get("/ConfigurationFiles/edit/" + $stateParams.configfile + ".json", {
                params: params
            }).then(function(result){
                $scope.ConfigFile = result.data.ConfigFile;
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });

        };

        $scope.askRestoreDefault = function(){
            $('#angularConfirmRestoreDefault').modal('show');
        };

        $scope.restoreDefault = function(dbKey){
            $scope.isRestoring = true;

            $http.post("/ConfigurationFiles/restorDefault/" + dbKey + ".json?angular=true",
                {}
            ).then(function(result){
                console.log('Data saved successfully');

                NotyService.genericSuccess({
                    message: result.data.message
                });

                RedirectService.redirectWithFallback('ConfigurationFilesIndex');


            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.load();

    });