angular.module('openITCOCKPIT')
    .controller('WizardsMysqlServerController', function($scope, $http, $stateParams, QueryStringService){
        $scope.hostId = QueryStringService.getStateValue($stateParams, 'hostId', false);
        /** public vars **/
        $scope.init = true;
        $scope.post = {
            username: null,
            password: null,
            database: null,
            serviceTemplatesToDeploy : [],
            serviceTemplateCommandArgumentValuesToDeploy : [],

        };

        $scope.load = function(){
            $http.get("/wizards/mysqlserver.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.wizardAssignments = result.data.wizardAssignments;
                $scope.servicetemplates = result.data.servicetemplates;
                for(var key in $scope.wizardAssignments.servicetemplates._ids){
                    var id = $scope.wizardAssignments.servicetemplates._ids[key];
                    $scope.post.serviceTemplatesToDeploy[id] = true;
                }

                for(var key in $scope.servicetemplates){
                    var serviceTemplateId = parseInt($scope.wizardAssignments.servicetemplates._ids[key], 10);
                    for(var subKey in $scope.servicetemplates[key].servicetemplatecommandargumentvalues){
                        var commandId = parseInt($scope.servicetemplates[key].servicetemplatecommandargumentvalues[subKey].id, 10);
                        if(!$scope.post.serviceTemplateCommandArgumentValuesToDeploy[serviceTemplateId]){
                            $scope.post.serviceTemplateCommandArgumentValuesToDeploy[serviceTemplateId] = [];
                        }
                        $scope.post.serviceTemplateCommandArgumentValuesToDeploy[serviceTemplateId][commandId] = 'test';
                    }

                }
                console.log($scope.post.serviceTemplateCommandArgumentValuesToDeploy);

                $scope.init = false;
            });
        };

        $scope.submit = function(){
            console.log('Submit !!!');
            console.log($scope.post);
        };

        //Fire on page load
        $scope.load();

    });
