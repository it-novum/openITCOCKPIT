angular.module('openITCOCKPIT').directive('automapView', function($http, $state){
    return {
        restrict: 'A',
        templateUrl: '/automaps/viewDirective.html',
        scope: {
            'automapId': '='
        },

        controller: function($scope){
            $scope.init = true;

            $scope.load = function(){
                $http.get("/automaps/view/" + $scope.id + ".json", {
                    params: {
                        angular: true
                    }
                }).then(function(result){
                    $scope.automap = result.data.automap;

                    if($scope.init){
                        $scope.load();
                    }
                    $scope.init = false;

                }, function errorCallback(result){
                    if(result.status === 403){
                        $state.go('403');
                    }

                    if(result.status === 404){
                        $state.go('404');
                    }
                });

            };//load function

            $scope.$watch('automapId', function(){
                $scope.id = parseInt($scope.automapId, 10);
                $scope.load();
            });
        },

        link: function($scope, element, attr){

        }
    };
});
