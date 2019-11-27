angular.module('openITCOCKPIT').directive('reloadRequired', function($http, SudoService, $interval){
    return {
        restrict: 'E',
        templateUrl: '/angular/reload_required.html',

        controller: function($scope){

            var reloadInterval = null;
            $scope.delay = 3;
            $scope.percentage = 0;

            $scope.doPageReloadRequired = function(){
                $scope.i = 0;
                if(reloadInterval !== null){
                    $interval.cancel(reloadInterval);
                }

                reloadInterval = $interval(function(){
                    $scope.i++;
                    $scope.percentage = Math.round($scope.i / $scope.delay * 100);

                    if($scope.i === $scope.delay){
                        $interval.cancel(reloadInterval);
                        location.reload(true);
                    }
                }, 1000);

            };

        },

        link: function($scope, element, attr){
            $scope.showPageReloadRequired = function(){
                $('#angularRequirePageReloadModal').modal('show');
                $scope.doPageReloadRequired();
            };
        }
    };
});