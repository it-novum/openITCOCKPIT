angular.module('openITCOCKPIT')
    .controller('ContactsUsedByController', function($scope, $http, QueryStringService){
        $scope.id = QueryStringService.getCakeId();
        $scope.total = 0;
        $scope.load = function(){
            $http.get("/contacts/usedBy/"+$scope.id+".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.contactWithRelations = result.data.contactWithRelations;
                $scope.total = $scope.getTotal();
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };

        $scope.getTotal = function(){
            var total = 0;
            total +=$scope.contactWithRelations.Hosttemplate.length;
            total +=$scope.contactWithRelations.Host.length;
            total +=$scope.contactWithRelations.Servicetemplate.length;
            total +=$scope.contactWithRelations.Service.length;
            total +=$scope.contactWithRelations.Hostescalation.length;
            total +=$scope.contactWithRelations.Serviceescalation.length;
            total +=$scope.contactWithRelations.Contactgroup.length;

            return total;
        };

        $scope.load();
    });
