angular.module('openITCOCKPIT').directive('statusmapsHostAndServicesSummaryStatusDirective', function($interval){
    return {
        restrict: 'E',
        templateUrl: '/statusmaps/host_and_services_summary_status.html',
        scope: {
            'host': '=?'
        },
        controller: function($scope){

            if(typeof $scope.host === "undefined"){
                $scope.host = {};
            }

            $scope.isFlapping = $scope.service.Servicestatus.isFlapping || false;
            $scope.flappingState = 0;
            $scope.serviceStateSummary = null;
            var interval;

            $scope.load = function(){
                if($scope.host.id){
                    $http.get("/statusmaps/hostAndServicesSummaryStatus/" + $scope.host.id + ".json?angular=true").then(function(result){
                        $scope.serviceStateSummary = result.data.serviceStateSummary;
                    }, function errorCallback(result){
                        console.log('Invalid JSON');
                    });
                }
            };

            /*
            function HostStatusColor($state = 2) {
                switch ($state) {
                    case 0:
                        return 'txt-color-green';

                    case 1:
                        return 'txt-color-red';

                    default:
                        return 'txt-color-blueLight';
                }
            }
             */

            $scope.load();
        },

        link: function(scope, element, attr){

        }
    };
});
