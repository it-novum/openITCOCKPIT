angular.module('openITCOCKPIT')
    .controller('DashboardsAllocateController', function($scope, $http, $stateParams, RedirectService) {
        // I am initing the view rn.
        $scope.allocationInitializing = true;

        // I am the ID that will be allocated.
        $scope.id = $stateParams.id;

        // I am the array of available dashboardTabs.
        $scope.dashboardTabs = [];

        // I am the list of containers.
        $scope.containers = [];

        // I am the array of available users.
        $scope.users = [];

        // I am the array of available usergroups.
        $scope.usergroups = [];

        // I am the pinned flag.
        $scope.isPinned = true;

        // I am the object that is being transported to JSON API to modify the DashboardTab allocation.
        $scope.allocation = {
            DashboardTab: {
                id: 0,
                containers: {
                    _ids: []
                },
                usergroups: {
                    _ids: []
                },
                AllocatedUsers: {
                    _ids: []
                },
                flags: 0
            }
        };

        // I will prepeare the view.
        $scope.load = function() {
            // Fetch Containers.
            $scope.loadContainer();

            // Fetch UserGroups.
            $scope.loadUsergroups();

            // Fetch Allocation Setup.
            $scope.fetchAllocation($scope.id);
        }

        // I will load the current allocation status.
        $scope.fetchAllocation = function (tabId) {
            // Fetch the desired Dashboard.
            $http.get("/dashboards/allocate/" + tabId + ".json?angular=true&id=").then(function(result) {
                $scope.dashboard = result.data.dashboardTabs[0];
                $scope.allocation.DashboardTab.id = result.data.dashboardTabs[0].id;
                $scope.allocation.DashboardTab.containers._ids = result.data.dashboardTabs[0].containers[0];
                $scope.allocation.DashboardTab.usergroups._ids = result.data.dashboardTabs[0].usergroups;
                $scope.allocation.DashboardTab.AllocatedUsers._ids = result.data.dashboardTabs[0].allocated_users;
                $scope.allocation.DashboardTab.flags = result.data.dashboardTabs[0].flags;
                $scope.isPinned = Boolean($scope.allocation.DashboardTab.flags & 1);

                // I'm done.
                $scope.allocationInitializing = false;
            });
        }

        // I will load all users.
        $scope.loadUsers = function() {
            $http.get("/users/loadUsersByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.allocation.DashboardTab.containers._ids
                }
            }).then(function(result) {
                $scope.users = result.data.users;

                // Reset the selected users after changing the container.
                if ($scope.allocationInitializing) {
                    $scope.allocation.DashboardTab.AllocatedUsers._ids = [];
                }
            });
        };

        // I will load all containers.
        $scope.loadContainer = function() {
            return $http.get("/users/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result) {
                $scope.containers = result.data.containers;
            });
        };

        // I will load all Usergroups.
        $scope.loadUsergroups = function() {
            $http.get("/usergroups/index.json", {
                params: {
                    'angular': true,
                    'sort': 'Usergroups.name',
                    'direction': 'asc'
                }
            }).then(function(result) {
                $scope.usergroups = result.data.allUsergroups;
            });
        };

        // If the containerId is changed, reload the users!
        $scope.$watch('allocation.DashboardTab.containers._ids', function() {
            // Load new users from the container.
            $scope.loadUsers();
        }, true);

        // If the [pinned] flag is switched, pass it to the flag int.
        $scope.$watch('isPinned', function(val) {
            if (val) {
                $scope.allocation.DashboardTab.flags |= 1;
                return;
            }
            $scope.allocation.DashboardTab.flags ^= 1;
        });

        // I will store the allocation details.
        $scope.saveAllocation = function() {
            $http.post("/dashboards/allocate.json?angular=true", $scope.allocation).then(function() {
                genericSuccess();
                RedirectService.redirectWithFallback('DashboardAllocation');
            }, function errorCallback(result) {
                $scope.errors = result.data.error;
                genericError();
            });
        }

        var genericError = function() {
            new Noty({
                theme: 'metroui',
                type: 'error',
                text: 'Error while saving data',
                timeout: 3500
            }).show();
        };

        var genericSuccess = function() {
            new Noty({
                theme: 'metroui',
                type: 'success',
                text: 'Data saved successfully',
                timeout: 3500
            }).show();
        };
        $scope.load();
    });
