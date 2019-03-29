angular.module('openITCOCKPIT')
    .controller('ContactgroupsUsedByController', function($scope, $http, $stateParams, $state){

        $scope.id = $stateParams.id;

        $scope.total = 0;
        $scope.load = function(){
            $http.get("/contactgroups/usedBy/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.contactgroupWithRelations = result.data.contactgroupWithRelations;
                $scope.total = $scope.getTotal();
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.getTotal = function(){
            var total = 0;
            total += $scope.contactgroupWithRelations.Hosttemplate.length;
            total += $scope.contactgroupWithRelations.Host.length;
            total += $scope.contactgroupWithRelations.Servicetemplate.length;
            total += $scope.contactgroupWithRelations.Service.length;
            total += $scope.contactgroupWithRelations.Hostescalation.length;
            total += $scope.contactgroupWithRelations.Serviceescalation.length;

            return total;
        };

        $scope.load();
    });
