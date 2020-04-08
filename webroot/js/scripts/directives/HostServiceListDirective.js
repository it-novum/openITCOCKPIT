angular.module('openITCOCKPIT').directive('hostServiceList', function($http){
    return {
        restrict: 'E',
        templateUrl: '/hosts/hostservicelist.html',
        scope: {
            'hostId': '=',
            'showServices': '=',
            'timezone': '=',
            'host': '='
        },
        controller: function($scope){

            $scope.deleteUrl = '/services/delete/';
            $scope.deactivateUrl = '/services/deactivate/';
            $scope.mouseout = true;

            $scope.init = true;
            /*** Filter Settings ***/
            var defaultFilter = function(){
                $scope.filter = {
                    Service: {
                        name: ''
                    }
                };
            };


            $scope.loadServicesWithStatus = function(){
                $http.get("/services/index.json", {
                    params: {
                        'angular': true,
                        'filter[Hosts.id]': $scope.hostId,
                        'filter[servicename]': $scope.filter.Service.name
                    }
                }).then(function(result){
                    $scope.services = result.data.all_services;
                    $scope.servicesStateFilter = {
                        0: true,
                        1: true,
                        2: true,
                        3: true
                    };
                    $scope.init = false;
                });
            };

            //Fire on page load
            defaultFilter();

            $scope.$watch('showServices', function(){
                if($scope.showServices[$scope.hostId]){
                    $scope.loadServicesWithStatus();
                }
            }, true);

            $scope.$watch('filter',
                function(){
                    if($scope.init){
                        return;
                    }
                    $scope.loadServicesWithStatus();
                }, true);

        }
    };

});
