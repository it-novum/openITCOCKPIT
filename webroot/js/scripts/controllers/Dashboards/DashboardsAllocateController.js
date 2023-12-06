angular.module('openITCOCKPIT')
    .controller('DashboardsAllocateController', function($scope, $http) {
        // I am the array of available dashboardTabs.
        $scope.dashboardTabs = [];

        // I am the array of available users.
        $scope.users = [];

        // I am the array of available usergroups.
        $scope.usergroups = [];

        // I am the object that is being transported to JSON API.
        $scope.allocation = {
            DashboardTab: {
                id: 0,
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
            // Fetch UserGroups.
            $scope.loadUsergroups();

            // Fetch Users.
            $scope.loadUsers();

            // Fetch the desired Dashboard.
            $http.get("/dashboards/allocate.json?angular=true&id=66").then(function(result) {
                $scope.allocation.DashboardTab.id = result.data.dashboardTabs[0].id;
                $scope.allocation.DashboardTab.usergroups._ids = result.data.dashboardTabs[0].usergroups;
                $scope.allocation.DashboardTab.AllocatedUsers._ids = result.data.dashboardTabs[0].allocated_users;
                $scope.allocation.DashboardTab.flags = result.data.dashboardTabs[0].flags;
            });
        }

        // I will load all users.
        $scope.loadUsers = function() {
            $http.get("/users/loadUsersByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': 1
                }
            }).then(function(result) {
                $scope.users = result.data.users;
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

        // I will store the allocation details.
        $scope.saveAllocation = function() {
            $http.post("/dashboards/allocate.json?angular=true", $scope.allocation).then(function(result) {
                // Yes it worked.
                $scope.errors = {};
                genericSuccess();

                // Reload table.
                $scope.load();

                // Hide the form.
                $('#allocateDashboardModal').modal('hide');
            }, function errorCallback(result) {
                $scope.errors = result.data.error;
                genericError();
            });
        }

        // If the [pinned] flag is switched, pass it to the flag int.
        $scope.$watch('isPinned', function(val) {
            if (val) {
                $scope.allocation.flags |= 1;
                return;
            }
            $scope.allocation.flags ^= 1;
        });

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
