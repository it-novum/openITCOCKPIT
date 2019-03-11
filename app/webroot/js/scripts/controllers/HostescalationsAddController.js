angular.module('openITCOCKPIT')
    .controller('HostescalationsAddController', function($scope, $http, $state, NotyService) {

        $scope.post = {
            Hostescalation: {
                container_id: null,
            }
        };

        $scope.clearElementSelections = function() {
            $scope.post.Hostescalation = {
                container_id: $scope.post.Hostescalation.container_id,
                Host: [],
                Host_excluded: [],
                Hostgroup: [],
                Hostgroup_excluded: [],
                first_notification: null,
                last_notification: null,
                notification_interval: null,
                timeperiod_id: null,
                Contact: [],
                Contactgroup: [],
                escalate_on_recovery: 0,
                escalate_on_down: 0,
                escalate_on_unreachable: 0
            }
        };
        $scope.clearElementSelections();
        $scope.containers = {};

        $scope.load = function() {

            $http.get("/hostescalations/add.json", {
                params: {
                    'angular': true
                }
            }).then(function(result) {
                $scope.containers = result.data.containers;
            });

        };

        $scope.loadElementsByContainerId = function() {
            $http.get("/Hostescalations/loadElementsByContainerId/" + $scope.post.Hostescalation.container_id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result) {
                $scope.clearElementSelections();
                $scope.hosts = result.data.hosts;
                $scope.hostsExcluded = result.data.hostsExcluded;
                $scope.hostgroups = result.data.hostgroups;
                $scope.hostgroupsExcluded = result.data.hostgroupsExcluded;
                $scope.timeperiods = result.data.timeperiods;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
            });
        };

        $scope.submit = function() {
            $http.post("/hostescalations/add.json?angular=true",
                $scope.post
            ).then(function(result) {
                NotyService.genericSuccess();
                $state.go('HostescalationsIndex');
                NotyService.scrollTop();
            }, function errorCallback (result) {
                NotyService.genericError();

                if (result.data.hasOwnProperty('error')) {
                    $scope.errors = result.data.error;
                }
            });
        };


        $scope.processChosenHosts = function(){
            for (var key in $scope.hosts) {
                if (in_array($scope.hosts[key].key, $scope.post.Hostescalation.Host_excluded)) {
                    $scope.hosts[key].disabled = true;
                } else {
                    $scope.hosts[key].disabled = false;
                }
            }
        };

        $scope.processChosenExcludedHosts = function(){
            for (var key in $scope.hostsExcluded) {
                if (in_array($scope.hostsExcluded[key].key, $scope.post.Hostescalation.Host)) {
                    $scope.hostsExcluded[key].disabled = true;
                } else {
                    $scope.hostsExcluded[key].disabled = false;
                }
            }
        };

        $scope.processChosenHostgroups = function(){
            for (var key in $scope.hostgroups) {
                if (in_array($scope.hostgroups[key].key, $scope.post.Hostescalation.Hostgroup_excluded)) {
                    $scope.hostgroups[key].disabled = true;
                } else {
                    $scope.hostgroups[key].disabled = false;
                }
            }
        };

        $scope.processChosenExcludedHostgroups = function(){
            for (var key in $scope.hostgroupsExcluded) {
                if (in_array($scope.hostgroupsExcluded[key].key, $scope.post.Hostescalation.Hostgroup)) {
                    $scope.hostgroupsExcluded[key].disabled = true;
                } else {
                    $scope.hostgroupsExcluded[key].disabled = false;
                }
            }
        };


        $scope.$watch('post.Hostescalation.container_id', function() {
            if ($scope.post.Hostescalation.container_id != null) {
                $scope.loadElementsByContainerId();
            }
        }, true);

        $scope.$watch('post.Hostescalation.Host', function() {
            $scope.processChosenExcludedHosts();
        }, true);

        $scope.$watch('post.Hostescalation.Host_excluded', function() {
            $scope.processChosenHosts();
        }, true);

        $scope.$watch('post.Hostescalation.Hostgroup', function() {
            $scope.processChosenExcludedHostgroups();
        }, true);

        $scope.$watch('post.Hostescalation.Hostgroup_excluded', function() {
            $scope.processChosenHostgroups();
        }, true);

        //Fire on page load
        $scope.load();

    });