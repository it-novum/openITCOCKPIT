angular.module('openITCOCKPIT')
    .controller('WizardsMysqlServerController', function($scope, $http, $stateParams, QueryStringService){
        //$scope.host.id = QueryStringService.getStateValue($stateParams, 'hostId', false);
        console.log($stateParams);
        /** public vars **/
        $scope.init = true;

        $scope.load = function(){
            $http.get("/wizards/mysqlserver.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){

                $scope.wizardAssignments = result.data.wizardAssignments;
                $scope.servicetemplates = result.data.servicetemplates;
                $scope.init = false;
            });
        };

        //Fire on page load
        $scope.load();

    });
