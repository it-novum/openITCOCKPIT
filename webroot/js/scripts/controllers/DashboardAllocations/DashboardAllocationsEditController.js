angular.module('openITCOCKPIT')
    .controller('DashboardAllocationsEditController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){
        $scope.id = $stateParams.id;
        $scope.init = true;
        $scope.hasError = null;
        $scope.allocated_dashboard_tabs_ids = [];

        $scope.load = function(){
            $http.get("/DashboardAllocations/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post = result.data.allocation;

                $scope.init = false;

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.loadContainers = function(){
            return $http.get("/users/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.init = false;
                $scope.containers = result.data.containers;
            });
        };

        $scope.loadElements = function(){
            var containerId = $scope.post.DashboardAllocation.container_id;
            if(containerId === 0){
                return;
            }

            $http.get("/DashboardAllocations/loadElementsByContainerId/" + containerId + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.dashboard_tabs = result.data.dashboard_tabs;
                $scope.users = result.data.users;
                $scope.usergroups = result.data.usergroups;
                $scope.allocated_dashboard_tabs = result.data.allocated_dashboard_tabs;
                $scope.allocated_dashboard_tabs_ids = [];
                _.each($scope.allocated_dashboard_tabs, function(allocated_dashboard){
                    if(allocated_dashboard.id != $scope.id){
                        $scope.allocated_dashboard_tabs_ids.push(allocated_dashboard.dashboard_tab_id);
                    }
                });
            });
        };


        $scope.submit = function(){
            $http.post("/DashboardAllocations/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('DashboardAllocationsEdit', {id: result.data.allocation.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });
                RedirectService.redirectWithFallback('DashboardAllocationsIndex');
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };


        //Fire on page load
        $scope.load();
        $scope.loadContainers();

        $scope.$watch('post.DashboardAllocation.container_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadElements();
        }, true);

    });
