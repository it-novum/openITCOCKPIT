angular.module('openITCOCKPIT').directive('hostServiceList', function ($http) {
    return {
        restrict: 'E',
        templateUrl: '/hosts/hostservicelist.html',
        scope: {
            'hostId': '='
        },
        controller: function ($scope) {
            console.log('!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');
            console.log($scope.hostId);
            /*** Filter Settings ***/



            var defaultFilter = function(){
                $scope.filter = {
                    Service: {
                        name: '',
                    }
                };
            };

            //$scope.hostId = 8;



            $scope.loadServicesWithStatus = function(){
                $http.get("/services/index.json", {
                    params: {
                        'angular': true,
                        'filter[Host.id]': $scope.hostId,
                 //       'filter[Service.servicename]': $scope.filter.Service.name,
                    }
                }).then(function (result) {
                    $scope.services = result.data.all_services;
                    $scope.servicesStateFilter = {
                        0 : true,
                        1 : true,
                        2 : true,
                        3 : true
                    };
                });
            };

            $scope.loadTimezone = function(){
                $http.get("/angular/user_timezone.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.timezone = result.data.timezone;
                });
            };

            //Fire on page load
            $scope.loadTimezone();
            $scope.loadServicesWithStatus();
            defaultFilter();


        }
    };

});
