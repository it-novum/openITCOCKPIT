angular.module('openITCOCKPIT')
    .controller('ContactgroupsUsedByController', function($scope, $http, $stateParams){

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
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
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
