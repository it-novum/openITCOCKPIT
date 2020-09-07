angular.module('openITCOCKPIT')
    .controller('WizardsAssignmentsController', function($scope, $http){

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

        //Fire on page load
        $scope.load();
    });
