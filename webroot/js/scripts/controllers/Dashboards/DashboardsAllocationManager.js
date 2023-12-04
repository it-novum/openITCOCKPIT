angular.module('openITCOCKPIT')
    .controller('DashboardsAllocationManager', function ($scope, $http) {
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
        $scope.load = function () {
            $http.get("/dashboards/allocationManager.json?angular=true", {}).then(function (result) {
                $scope.dashboardTabs = result.data.dashboardTabs;
            });
        }

        // I will load all users.
        $scope.loadUsers = function () {
            $http.get("/users/loadUsersByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': 1
                }
            }).then(function (result) {
                $scope.users = result.data.users;
            });
        };

        // I will load all Usergroups.
        $scope.loadUsergroups = function () {
            $http.get("/usergroups/index.json", {
                params: {
                    'angular': true,
                    'sort': 'Usergroups.name',
                    'direction': 'asc'
                }
            }).then(function (result) {
                $scope.usergroups = result.data.allUsergroups;
            });
        };

        // I will soley return the complete tab object by the given tabId.
        $scope.getTab = function (tabId) {
            for (var tabIndex in $scope.dashboardTabs) {
                let currentTab = $scope.dashboardTabs[tabIndex];
                console.log("CTID: " + currentTab.id);
                if (currentTab.id !== tabId) {
                    continue;
                }
                return currentTab;
            }
            return null;
        }

        // I will show the Allocation Manager modal.
        $scope.manageAllocation = function (tabId) {
            let myTab = $scope.getTab(tabId);
            // Fetch users and groups.
            $scope.loadUsergroups();
            $scope.loadUsers();

            // Put the stuff into the form.
            $scope.allocation.DashboardTab.id = myTab.id;
            $scope.allocation.DashboardTab.usergroups._ids = myTab.usergroups;
            $scope.allocation.DashboardTab.AllocatedUsers._ids = myTab.allocated_users;
            $scope.allocation.DashboardTab.flags = myTab.flags;

            // Show the form.
            $('#allocateDashboardModal').modal('show');
        }

        // I will store the allocation details.
        $scope.saveAllocation = function () {
            $http.post("/dashboards/allocate.json?angular=true", $scope.allocation).then(function (result) {
                $scope.errors = {};
                genericSuccess();
            }, function errorCallback(result) {
                $scope.errors = result.data.error;
                genericError();
            });
        }

        // If the [pinned] flag is switched, pass it to the flag int.
        $scope.$watch('isPinned', function (val) {
            if (val) {
                $scope.allocation.flags |= 1;
                return;
            }
            $scope.allocation.flags ^= 1;
        });

        var genericError = function () {
            new Noty({
                theme: 'metroui',
                type: 'error',
                text: 'Error while saving data',
                timeout: 3500
            }).show();
        };

        var genericSuccess = function () {
            new Noty({
                theme: 'metroui',
                type: 'success',
                text: 'Data saved successfully',
                timeout: 3500
            }).show();
        };
        $scope.load();
    });
