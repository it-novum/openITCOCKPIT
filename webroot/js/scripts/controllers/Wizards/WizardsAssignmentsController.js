angular.module('openITCOCKPIT')
    .controller('WizardsAssignmentsController', function($scope, $http){

        /** public vars **/
        $scope.init = true;

        $scope.load = function(){
            $http.get("/wizards/assignments.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){

                $scope.wizardAssignments = result.data.wizardAssignments;
                $scope.init = false;
            });
        };

        //Fire on page load
        $scope.load();
    });
