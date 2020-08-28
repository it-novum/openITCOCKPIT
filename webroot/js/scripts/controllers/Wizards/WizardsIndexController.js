angular.module('openITCOCKPIT')
    .controller('WizardsIndexController', function($scope, $http){

        /** public vars **/
        $scope.init = true;


        $scope.load = function(){
            $http.get("/wizards/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){

                $scope.wizards = result.data.wizards;
                $scope.init = false;
            });
        };


        $scope.load();
    });
