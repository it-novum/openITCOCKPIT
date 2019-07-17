angular.module('openITCOCKPIT')
    .controller('ConfigurationFilesIndexController', function($scope, $http){

        $scope.load = function(){

            var params = {
                'angular': true,
            };

            $http.get("/ConfigurationFiles/index.json", {
                params: params
            }).then(function(result){
                $scope.configFileCategories = result.data.configFileCategories;
                $scope.init = false;
            });
        };

        $scope.load();

    });