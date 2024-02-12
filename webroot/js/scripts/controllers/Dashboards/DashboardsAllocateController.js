angular.module('openITCOCKPIT')
    .controller('DashboardsAllocateController', function($scope, $http, $stateParams, RedirectService){
        // I am the ID that will be allocated.
        $scope.id = $stateParams.id;

        // I am the array of available dashboardTabs.
        $scope.dashboardTab = [];

        // I am the list of containers.
        $scope.containers = [];

        // I am the ID of the current user.
        $scope.userId = 0;

        // I am the array of available users.
        $scope.users = [];

        // I am the array of available usergroups.
        $scope.usergroups = [];

        // I am the object that is being transported to JSON API to modify the DashboardTab allocation.
        $scope.dashboard = {
            usergroups: {
                _ids: []
            },
            allocated_users: {
                _ids: []
            },
            container_id: 0,
            is_pinned: false,
        };
        $scope.init = true;

        // I will prepeare the view.
        $scope.load = function(){
            // Fetch Containers.
            $scope.loadContainer();
            // Fetch UserGroups.
            $scope.loadUsergroups();
            // Fetch Allocation Setup.
            $scope.fetchAllocation($scope.id);
        }

        // I will load the current allocation status.
        $scope.fetchAllocation = function(tabId){
            // Fetch the desired Dashboard.
            $http.get("/dashboards/allocate/" + $scope.id + ".json?angular=true").then(function(result){
                $scope.dashboard = result.data.dashboardTab;
                $scope.userId = result.data.userId;
                $scope.init = false;
            });
        }

        // I will load all users.
        $scope.loadUsers = function(){
            if($scope.dashboard.container_id === 0){
                return;
            }
            $http.get("/users/loadUsersByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.dashboard.container_id
                }
            }).then(function(result){
                $scope.users = result.data.users;
                $scope.users = _.filter($scope.users, function(user){
                    return user.key !== $scope.dashboard.user_id;
                });
            });
        };

        // I will load all containers.
        $scope.loadContainer = function(){
            return $http.get("/users/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
            });
        };

        // I will load all Usergroups.
        $scope.loadUsergroups = function(){
            $http.get("/usergroups/index.json", {
                params: {
                    'angular': true,
                    'sort': 'Usergroups.name',
                    'direction': 'asc'
                }
            }).then(function(result){
                $scope.usergroups = result.data.allUsergroups;
            });
        };

        // If the containerId is changed, reload the users!
        $scope.$watch('dashboard.container_id', function(){
            if($scope.init){
                return;
            }
            if($scope.dashboard.container_id > 0){
                // Load new users from the container.
                $scope.loadUsers();
            }
        }, true);

        // I will store the allocation details.
        $scope.saveAllocation = function(){
            $scope.dashboard.allocated_users._ids = _.intersection(
                _.map($scope.users, 'key'),
                $scope.dashboard.allocated_users._ids
            );
            $http.post("/dashboards/allocate.json?angular=true",
                {
                    'DashboardTab': $scope.dashboard
                }
            ).then(function(){
                genericSuccess();
                RedirectService.redirectWithFallback('DashboardAllocation');
            }, function errorCallback(result){
                $scope.errors = result.data.error;
                genericError();
            });
        }

        var genericError = function(){
            new Noty({
                theme: 'metroui',
                type: 'error',
                text: 'Error while saving data',
                timeout: 3500
            }).show();
        };

        var genericSuccess = function(){
            new Noty({
                theme: 'metroui',
                type: 'success',
                text: 'Data saved successfully',
                timeout: 3500
            }).show();
        };
        $scope.load();
    });
