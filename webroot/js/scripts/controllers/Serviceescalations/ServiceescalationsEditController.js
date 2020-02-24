angular.module('openITCOCKPIT')
    .controller('ServiceescalationsEditController', function($scope, $http, $state, $stateParams, $location, NotyService, RedirectService){
        $scope.init = true;
        $scope.id = $stateParams.id;
        $scope.post = {
            Serviceescalation: {
                container_id: null,
                first_notification: 1,
                last_notification: 5,
                notification_interval: 7200,
                timeperiod_id: null,
                escalate_on_recovery: 0,
                escalate_on_warning: 0,
                escalate_on_critical: 0,
                escalate_on_unknown: 1,
                contacts: {
                    _ids: []
                },
                contactgroups: {
                    _ids: []
                },
                services: {
                    _ids: []
                },
                services_excluded: {
                    _ids: []
                },
                servicegroups: {
                    _ids: []
                },
                servicegroups_excluded: {
                    _ids: []
                }
            }
        };
        $scope.containers = {};

        $scope.load = function(){
            $http.get("/serviceescalations/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.serviceescalation = result.data.serviceescalation;
                $scope.post.Serviceescalation.container_id = $scope.serviceescalation.container_id;
                $scope.post.Serviceescalation.first_notification = $scope.serviceescalation.first_notification;
                $scope.post.Serviceescalation.last_notification = $scope.serviceescalation.last_notification;
                $scope.post.Serviceescalation.notification_interval = $scope.serviceescalation.notification_interval;
                $scope.post.Serviceescalation.timeperiod_id = $scope.serviceescalation.timeperiod_id;
                $scope.post.Serviceescalation.escalate_on_recovery = $scope.serviceescalation.escalate_on_recovery;
                $scope.post.Serviceescalation.escalate_on_warning = $scope.serviceescalation.escalate_on_warning;
                $scope.post.Serviceescalation.escalate_on_critical = $scope.serviceescalation.escalate_on_critical;
                $scope.post.Serviceescalation.escalate_on_unknown = $scope.serviceescalation.escalate_on_unknown;

                for(var contactIndex in $scope.serviceescalation.contacts){
                    $scope.post.Serviceescalation.contacts._ids.push($scope.serviceescalation.contacts[contactIndex].id);
                }
                for(var contactgroupIndex in $scope.serviceescalation.contactgroups){
                    $scope.post.Serviceescalation.contactgroups._ids.push($scope.serviceescalation.contactgroups[contactgroupIndex].id);
                }
                for(var serviceIndex in $scope.serviceescalation.services){
                    if($scope.serviceescalation.services[serviceIndex]._joinData.excluded === 0){
                        $scope.post.Serviceescalation.services._ids.push($scope.serviceescalation.services[serviceIndex].id);
                    }else{
                        $scope.post.Serviceescalation.services_excluded._ids.push($scope.serviceescalation.services[serviceIndex].id);
                    }

                }
                for(var servicegroupIndex in $scope.serviceescalation.servicegroups){
                    if($scope.serviceescalation.servicegroups[servicegroupIndex]._joinData.excluded === 0){
                        $scope.post.Serviceescalation.servicegroups._ids.push($scope.serviceescalation.servicegroups[servicegroupIndex].id);
                    }else{
                        $scope.post.Serviceescalation.servicegroups_excluded._ids.push($scope.serviceescalation.servicegroups[servicegroupIndex].id);
                    }

                }
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
            $scope.loadContainer();
        };

        $scope.loadContainer = function(){
            var params = {
                'angular': true
            };

            $http.get("/serviceescalations/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadElementsByContainerId = function(){
            $http.get("/serviceescalations/loadElementsByContainerId/" + $scope.post.Serviceescalation.container_id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.servicegroups = result.data.servicegroups;
                $scope.servicegroups_excluded = result.data.servicegroupsExcluded;
                $scope.timeperiods = result.data.timeperiods;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
                $scope.processChosenServices();
                $scope.processChosenExcludedServices();
                $scope.processChosenServicegroups();
                $scope.processChosenExcludedServicegroups();
            });
        };

        $scope.loadServices = function(searchString){
            if($scope.post.Serviceescalation.container_id != null){
                $http.get("/services/loadServicesByStringCake4.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Serviceescalation.container_id,
                        'filter[servicename]': searchString,
                        'selected[]': $scope.post.Serviceescalation.services._ids
                    }
                }).then(function(result){
                    $scope.services = result.data.services;
                    $scope.processChosenServices();
                    $scope.processChosenExcludedServices();
                });
            }
        };

        $scope.loadExcludedServices = function(searchString){
            if($scope.post.Serviceescalation.container_id != null){
                $http.get("/services/loadServicesByStringCake4.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Serviceescalation.container_id,
                        'filter[servicename]': searchString,
                        'selected[]': $scope.post.Serviceescalation.services_excluded._ids
                    }
                }).then(function(result){
                    $scope.services_excluded = result.data.services;
                    $scope.processChosenServices();
                    $scope.processChosenExcludedServices();
                });
            }
        };

        $scope.submit = function(){
            $http.post("/serviceescalations/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var serviceescalatingEditUrl = $state.href('ServiceescalationsEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + serviceescalatingEditUrl + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });
                RedirectService.redirectWithFallback('ServiceescalationsIndex');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };


        $scope.processChosenServices = function(){
            for(var key in $scope.services){
                if(in_array($scope.services[key].key, $scope.post.Serviceescalation.services_excluded._ids)){
                    $scope.services[key].disabled = true;
                }else{
                    $scope.services[key].disabled = false;
                }
            }
        };

        $scope.processChosenExcludedServices = function(){
            for(var key in $scope.services_excluded){
                if(in_array($scope.services_excluded[key].key, $scope.post.Serviceescalation.services._ids)){
                    $scope.services_excluded[key].disabled = true;
                }else{
                    $scope.services_excluded[key].disabled = false;
                }
            }
        };

        $scope.processChosenServicegroups = function(){
            for(var key in $scope.servicegroups){
                if(in_array($scope.servicegroups[key].key, $scope.post.Serviceescalation.servicegroups_excluded._ids)){
                    $scope.servicegroups[key].disabled = true;
                }else{
                    $scope.servicegroups[key].disabled = false;
                }
            }
        };

        $scope.processChosenExcludedServicegroups = function(){
            for(var key in $scope.servicegroups_excluded){
                if(in_array($scope.servicegroups_excluded[key].key, $scope.post.Serviceescalation.servicegroups._ids)){
                    $scope.servicegroups_excluded[key].disabled = true;
                }else{
                    $scope.servicegroups_excluded[key].disabled = false;
                }
            }
        };


        $scope.$watch('post.Serviceescalation.container_id', function(){
            if($scope.post.Serviceescalation.container_id != null){
                $scope.loadElementsByContainerId();
                $scope.loadServices();
                $scope.loadExcludedServices();
            }
        }, true);

        $scope.$watch('post.Serviceescalation.services._ids', function(){
            if($scope.init){
                return;
            }
            $scope.processChosenExcludedServices();
        }, true);

        $scope.$watch('post.Serviceescalation.services_excluded._ids', function(){
            if($scope.init){
                return;
            }
            $scope.processChosenServices();
        }, true);

        $scope.$watch('post.Serviceescalation.servicegroups._ids', function(){
            if($scope.init){
                return;
            }
            $scope.processChosenExcludedServicegroups();
        }, true);

        $scope.$watch('post.Serviceescalation.servicegroups_excluded._ids', function(){
            if($scope.init){
                return;
            }
            $scope.processChosenServicegroups();
        }, true);

        //Fire on page load
        $scope.load();
    });
