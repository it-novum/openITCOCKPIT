angular.module('openITCOCKPIT').directive('parentOutagesWidget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/parentOutagesWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){

            $scope.filter = {
                Host: {
                    name: ''
                }
            };

            $scope.load = function(){
                $http.get("/dashboards/parentOutagesWidget.json", {
                    params: {
                        'angular': true,
                        'filter[Host.name]': $scope.filter.Host.name
                    }
                }).then(function(result){
                    $scope.parentOutages = result.data.parent_outages;
                    $scope.init = false;
                });
            };

            $scope.load();

            $scope.$watch('filter', function(){
                $scope.load();
            }, true);

        },

        link: function($scope, element, attr){

        }
    };
});
