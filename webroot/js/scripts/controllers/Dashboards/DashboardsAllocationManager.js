angular.module('openITCOCKPIT')
    .controller('DashboardsAllocationManager', function($scope, $http) {
        // I am the array of available dashboardTabs.
        $scope.dashboardTabs = [];

        $scope.load = function() {
            $http.get("/dashboards/allocationManager.json?angular=true", {}).then(function(result) {
                $scope.dashboardTabs = result.data.dashboardTabs;
            });
        }
        $scope.load();
    });
