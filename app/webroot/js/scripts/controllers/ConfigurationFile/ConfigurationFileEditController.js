angular.module('openITCOCKPIT')
    .controller('ConfigurationFilesEditController', function($scope, $http){

        $scope.isRestoring = false;

        $scope.askRestoreDefault = function(){
            $('#angularConfirmRestoreDefault').modal('show');
        };

        $scope.restoreDefault = function(dbKey){
            $scope.isRestoring = true;

            $http.post("/ConfigurationFiles/restorDefault/" + dbKey + ".json?angular=true",
                {}
            ).then(function(result){
                console.log('Data saved successfully');
                window.location.href = '/ConfigurationFiles/index';
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

    });