angular.module('openITCOCKPIT').directive('statusmapsHostAndServicesSummaryStatusDirective', function($http, $interval, $state){
    return {
        restrict: 'E',
        templateUrl: '/statusmaps/hostAndServicesSummaryStatus.html',
        scope: {
            'host': '=?',
            'hasBrowserRight': '=?',
            'ts': '=?'
        },
        controller: function($scope){

            if(typeof $scope.host === "undefined"){
                $scope.host = {};
            }
            if(typeof $scope.hasBrowserRight === "undefined"){
                $scope.hasBrowserRight = false;
            }

            $scope.additionalFilterKeys = ['acknowledged', 'in_downtime', 'not_handled', 'passive'];
            $scope.additionalFilters = {
                acknowledged: 'has_been_acknowledged',
                in_downtime: 'in_downtime',
                not_handled: 'has_not_been_acknowledged',
                passive: 'passive'
            };

            $scope.serviceStateSummary = null;
            var interval;

            $scope.load = function(){
                if($scope.host.hostId){
                    $http.get("/statusmaps/hostAndServicesSummaryStatus/" + $scope.host.hostId + ".json?angular=true").then(function(result){
                        $scope.serviceStateSummary = result.data.serviceStateSummary;
                        $(".map-summary-state-popover").switchClass("slideOutRight", "slideInRight");
                        $scope.startInterval();
                    }, function errorCallback(result){
                        console.log('Invalid JSON');
                    });
                }
            };

            $scope.startInterval = function(){
                var showFor = 5000;
                var intervalSpeed = 10;
                $scope.percentValue = 100;

                $scope.stopInterval();

                $scope.intervalRef = $interval(function(){
                    showFor = showFor - intervalSpeed;
                    if(showFor === 0){
                        $scope.stopInterval();
                        $(".map-summary-state-popover").switchClass("slideInRight", "slideOutRight");
                    }

                    $scope.percentValue = showFor / 5000 * 100;
                }, intervalSpeed);
            };

            $scope.stopInterval = function(){
                if(typeof $scope.intervalRef !== "undefined"){
                    $interval.cancel($scope.intervalRef);
                }
            };

            $scope.hideTooltip = function($event){
                $($event.currentTarget).switchClass("slideInRight", "slideOutRight");
                $scope.stopInterval();
            };

            $scope.buildSrefParams = function(key, servicestate, host_id, asString = false){
                var params = {
                    servicestate: [servicestate],
                    sort: 'Servicestatus.last_state_change',
                    direction: 'desc',
                    host_id: [host_id]
                };

                if($scope.additionalFilterKeys.indexOf(key) >= 0){
                    params[$scope.additionalFilters[key]] = 1;
                }

                if(asString){
                    return JSON.stringify(params);
                }
                return params;
            };

            $scope.goToState = function(key, servicestate, host_id){
                $state.go('ServicesIndex', $scope.buildSrefParams(key, servicestate, host_id));
            };

            $scope.$watch('ts', function(){
                $scope.load();
            }, true);
        },

        link: function(scope, element, attr){

        }
    };
});
