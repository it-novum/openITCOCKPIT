angular.module('openITCOCKPIT')
    .controller('ServiceescalationsAddController', function($scope, $http, $state, NotyService, RedirectService){

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
                escalate_on_unknown: 0,
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
            });
        };

        $scope.loadServices = function(searchString){
            if($scope.post.Serviceescalation.container_id != null){
                $http.get("/services/loadServicesByStringCake4.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Serviceescalation.container_id,
                        'filter[Services.servicename]': searchString,
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
            $http.post("/serviceescalations/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var serviceescalatingEditUrl = $state.href('ServiceescalationsEdit', {id: result.data.id});
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
            $scope.processChosenExcludedServices();
        }, true);

        $scope.$watch('post.Serviceescalation.services_excluded._ids', function(){
            $scope.processChosenServices();
        }, true);

        $scope.$watch('post.Serviceescalation.servicegroups._ids', function(){
            $scope.processChosenExcludedServicegroups();
        }, true);

        $scope.$watch('post.Serviceescalation.servicegroups_excluded._ids', function(){
            $scope.processChosenServicegroups();
        }, true);

        //Fire on page load
        $scope.load();

    });
