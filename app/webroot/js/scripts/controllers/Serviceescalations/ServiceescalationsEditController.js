angular.module('openITCOCKPIT')
    .controller('ServiceescalationsEditController', function($scope, $http, $state, $stateParams, NotyService) {

        $scope.post = {
            Serviceescalation: {
                id: $stateParams.id,
                uuid: null,
                container_id: null,
                timeperiod_id: null,
                first_notification: null,
                last_notification: null,
                notification_interval: null,
                escalate_on_recovery: 0,
                escalate_on_warning: 0,
                escalate_on_critical: 0,
                escalate_on_unknown: 0,
                Service: [],
                Service_excluded: [],
                Servicegroup: [],
                Servicegroup_excluded: [],
                Contact: [],
                Contactgroup: [],
            }
        };

        $scope.deleteUrl = "/serviceescalations/delete/" + $scope.post.Serviceescalation.id + ".json?angular=true";
        $scope.successState = 'ServiceescalationsIndex';

        $scope.load = function() {

            $http.get("/serviceescalations/edit/" + $scope.post.Serviceescalation.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result) {
                $scope.post.Serviceescalation = result.data.serviceescalation.Serviceescalation;

                $scope.containers = result.data.containers;
                $scope.services = result.data.services;
                $scope.servicesExcluded = result.data.servicesExcluded;
                $scope.servicegroups = result.data.servicegroups;
                $scope.servicegroupsExcluded = result.data.servicegroupsExcluded;
                $scope.timeperiods = result.data.timeperiods;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
            });

        };

        $scope.loadElementsByContainerId = function() {
            $http.get("/serviceescalations/loadElementsByContainerId/" + $scope.post.Serviceescalation.container_id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result) {
                $scope.services = result.data.services;
                $scope.servicesExcluded = result.data.servicesExcluded;
                $scope.servicegroups = result.data.servicegroups;
                $scope.servicegroupsExcluded = result.data.servicegroupsExcluded;
                $scope.timeperiods = result.data.timeperiods;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;

                $scope.processChosenExcludedServices();
                $scope.processChosenServices();
                $scope.processChosenExcludedServicegroups();
                $scope.processChosenServicegroups();
            });
        };

        $scope.submit = function() {
            $http.post("/serviceescalations/edit/" + $scope.post.Serviceescalation.id + ".json?angular=true",
                $scope.post
            ).then(function(result) {
                NotyService.genericSuccess();
                $state.go('ServiceescalationsIndex');
                NotyService.scrollTop();
            }, function errorCallback (result) {
                NotyService.genericError();

                if (result.data.hasOwnProperty('error')) {
                    $scope.errors = result.data.error;
                }
            });
        };


        $scope.processChosenServices = function() {
            for (var key in $scope.services) {
                if (in_array($scope.services[key].key, $scope.post.Serviceescalation.Service_excluded)) {
                    $scope.services[key].disabled = true;
                } else {
                    $scope.services[key].disabled = false;
                }
            }
        };

        $scope.processChosenExcludedServices = function() {
            for (var key in $scope.servicesExcluded) {
                if (in_array($scope.servicesExcluded[key].key, $scope.post.Serviceescalation.Service)) {
                    $scope.servicesExcluded[key].disabled = true;
                } else {
                    $scope.servicesExcluded[key].disabled = false;
                }
            }
        };

        $scope.processChosenServicegroups = function() {
            for (var key in $scope.servicegroups) {
                if (in_array($scope.servicegroups[key].key, $scope.post.Serviceescalation.Servicegroup_excluded)) {
                    $scope.servicegroups[key].disabled = true;
                } else {
                    $scope.servicegroups[key].disabled = false;
                }
            }
        };

        $scope.processChosenExcludedServicegroups = function() {
            for (var key in $scope.servicegroupsExcluded) {
                if (in_array($scope.servicegroupsExcluded[key].key, $scope.post.Serviceescalation.Servicegroup)) {
                    $scope.servicegroupsExcluded[key].disabled = true;
                } else {
                    $scope.servicegroupsExcluded[key].disabled = false;
                }
            }
        };


        $scope.$watch('post.Serviceescalation.container_id', function() {
            if (typeof $scope.post.Serviceescalation != "undefined" && $scope.post.Serviceescalation.container_id != null) {
                $scope.loadElementsByContainerId();
            }
        }, true);

        $scope.$watch('post.Serviceescalation.Service', function() {
            $scope.processChosenExcludedServices();
        }, true);

        $scope.$watch('post.Serviceescalation.Service_excluded', function() {
            $scope.processChosenServices();
        }, true);

        $scope.$watch('post.Serviceescalation.Servicegroup', function() {
            $scope.processChosenExcludedServicegroups();
        }, true);

        $scope.$watch('post.Serviceescalation.Servicegroup_excluded', function() {
            $scope.processChosenServicegroups();
        }, true);

        //Fire on page load
        $scope.load();

    });