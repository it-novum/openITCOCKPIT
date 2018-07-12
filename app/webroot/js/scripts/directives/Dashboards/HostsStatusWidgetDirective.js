angular.module('openITCOCKPIT').directive('hostsStatusWidget', function($http, QueryStringService){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/hostsStatusListWidget.html',
        scope: {},

        controller: function($scope){


            $scope.filter = {
                Hoststatus: {
                    current_state: QueryStringService.hoststate()
                },
                Host: {
                    name: '',
                    keywords: '',
                    address: ''
                }
            };


            $scope.load = function(){
                $http.get("/hosts/index.json", {
                    params: {
                        'angular': true,
                        'recursive': true
                    }
                }).then(function(result){
                    $scope.hoststatusCount = result.data.hoststatusCount;
                    $scope.hoststatusCountPercentage = result.data.hoststatusCountPercentage;
                    $scope.init = false;
                });
            };

            $scope.load();

        },

        link: function($scope, element, attr){

        }
    };
});
