angular.module('openITCOCKPIT')
    .controller('DashboardAllocationsAddController', function($scope, $http, $state, NotyService, RedirectService){
        $scope.post = {
            DashboardAllocation: {
                name: '',
                container_id: 0,
                dashboard_tab_id: 0,
                pinned: false,
                users: {
                    _ids: []
                },
                usergroups: {
                    _ids: [],
                }
            }
        };

        $scope.init = true;
        $scope.hasError = null;
        $scope.allocated_dashboard_tabs_ids = [];

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
                    $scope.allocated_dashboard_tabs_ids.push(allocated_dashboard.dashboard_tab_id);
                });
            });
        };


        $scope.submit = function(){
            $http.post("/DashboardAllocations/add.json?angular=true",
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
        $scope.loadContainers();

        $scope.$watch('post.DashboardAllocation.container_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadElements();
        }, true);

    });
