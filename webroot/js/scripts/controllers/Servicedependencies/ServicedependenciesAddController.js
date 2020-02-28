angular.module('openITCOCKPIT')
    .controller('ServicedependenciesAddController', function($scope, $http, $state, $stateParams, $location, NotyService, RedirectService){
        $scope.init = true;
        $scope.id = $stateParams.id;
        $scope.post = {
            Servicedependency: {
                container_id: null,
                inherits_parent: 0,
                timeperiod_id: null,
                execution_fail_on_ok: 1,
                execution_fail_on_warning: 1,
                execution_fail_on_critical: 1,
                execution_fail_on_unknown: 1,
                execution_fail_on_pending: 1,
                execution_none: 0,
                notification_fail_on_ok: 1,
                notification_fail_on_warning: 1,
                notification_fail_on_critical: 1,
                notification_fail_on_unknown: 1,
                notification_fail_on_pending: 1,
                notification_none: 0,
                services: {
                    _ids: []
                },
                services_dependent: {
                    _ids: []
                },
                servicegroups: {
                    _ids: []
                },
                servicegroups_dependent: {
                    _ids: []
                }
            }
        };
        $scope.containers = {};

        $('#infoButtonNotificationOptions').popover({
            boundary: 'window',
            trigger: 'hover',
            placement: 'left'
        });

        $('#infoButtonExecutionOptions').popover({
            boundary: 'window',
            trigger: 'hover',
            placement: 'left'
        });

        $scope.loadContainer = function(){
            var params = {
                'angular': true
            };
            $http.get("/servicedependencies/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadElementsByContainerId = function(){
            $http.get("/servicedependencies/loadElementsByContainerId/" + $scope.post.Servicedependency.container_id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.servicegroups = result.data.servicegroups;
                $scope.servicegroups_dependent = result.data.servicegroupsDependent;
                $scope.timeperiods = result.data.timeperiods;
                $scope.processChosenServicegroups();
                $scope.processChosenDependentServicegroups();
            });
        };

        $scope.loadServices = function(searchString){
            if($scope.post.Servicedependency.container_id != null){
                $http.get("/services/loadServicesByStringCake4.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Servicedependency.container_id,
                        'filter[servicename]': searchString,
                        'selected[]': $scope.post.Servicedependency.services._ids
                    }
                }).then(function(result){
                    $scope.services = result.data.services;
                    $scope.processChosenServices();
                    $scope.processChosenDependentServices();
                });
            }
        };

        $scope.loadDependentServices = function(searchString){
            if($scope.post.Servicedependency.container_id != null){
                $http.get("/services/loadServicesByStringCake4.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Servicedependency.container_id,
                        'filter[servicename]': searchString,
                        'selected[]': $scope.post.Servicedependency.services_dependent._ids
                    }
                }).then(function(result){
                    $scope.services_dependent = result.data.services;
                    $scope.processChosenServices();
                    $scope.processChosenDependentServices();
                });
            }
        };

        $scope.submit = function(){
            $http.post("/servicedependencies/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var servicedependencyEditUrl = $state.href('ServicedependenciesEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + servicedependencyEditUrl + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('ServicedependenciesIndex');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };


        $scope.processChosenServices = function(){
            for(var key in $scope.services){
                if(in_array($scope.services[key].key, $scope.post.Servicedependency.services_dependent._ids)){
                    $scope.services[key].disabled = true;
                }else{
                    $scope.services[key].disabled = false;
                }
            }
        };

        $scope.processChosenDependentServices = function(){
            for(var key in $scope.services_dependent){
                if(in_array($scope.services_dependent[key].key, $scope.post.Servicedependency.services._ids)){
                    $scope.services_dependent[key].disabled = true;
                }else{
                    $scope.services_dependent[key].disabled = false;
                }
            }
        };

        $scope.processChosenServicegroups = function(){
            for(var key in $scope.servicegroups){
                if(in_array($scope.servicegroups[key].key, $scope.post.Servicedependency.servicegroups_dependent._ids)){
                    $scope.servicegroups[key].disabled = true;
                }else{
                    $scope.servicegroups[key].disabled = false;
                }
            }
        };

        $scope.processChosenDependentServicegroups = function(){
            for(var key in $scope.servicegroups){
                if(in_array($scope.servicegroups_dependent[key].key, $scope.post.Servicedependency.servicegroups._ids)){
                    $scope.servicegroups_dependent[key].disabled = true;
                }else{
                    $scope.servicegroups_dependent[key].disabled = false;
                }
            }
        };


        $scope.$watch('post.Servicedependency.container_id', function(){
            if($scope.post.Servicedependency.container_id != null){
                $scope.loadElementsByContainerId();
                $scope.loadServices();
                $scope.loadDependentServices();
            }
        }, true);

        $scope.$watch('post.Servicedependency.services._ids', function(){
            if($scope.init){
                return;
            }
            $scope.processChosenDependentServices();
        }, true);

        $scope.$watch('post.Servicedependency.services_dependent._ids', function(){
            if($scope.init){
                return;
            }
            $scope.processChosenServices();
        }, true);

        $scope.$watch('post.Servicedependency.servicegroups._ids', function(){
            if($scope.init){
                return;
            }
            $scope.processChosenDependentServicegroups();
        }, true);

        $scope.$watch('post.Servicedependency.servicegroups_dependent._ids', function(){
            if($scope.init){
                return;
            }
            $scope.processChosenServicegroups();
        }, true);

        $scope.$watchGroup([
            'post.Servicedependency.execution_fail_on_ok',
            'post.Servicedependency.execution_fail_on_warning',
            'post.Servicedependency.execution_fail_on_critical',
            'post.Servicedependency.execution_fail_on_unknown',
            'post.Servicedependency.execution_fail_on_pending'
        ], function(){
            if($scope.init){
                return;
            }
            if($scope.post.Servicedependency.execution_none === 0){
                return;
            }
            if($scope.post.Servicedependency.execution_fail_on_ok |
                $scope.post.Servicedependency.execution_fail_on_warning |
                $scope.post.Servicedependency.execution_fail_on_critical |
                $scope.post.Servicedependency.execution_fail_on_unknown |
                $scope.post.Servicedependency.execution_fail_on_pending
            ){
                $scope.post.Servicedependency.execution_none = 0;
            }

        }, true);

        $scope.$watch('post.Servicedependency.execution_none', function(){
            if($scope.init){
                return;
            }
            if($scope.post.Servicedependency.execution_none === 0){
                return;
            }
            $scope.post.Servicedependency.execution_fail_on_ok = 0;
            $scope.post.Servicedependency.execution_fail_on_warning = 0;
            $scope.post.Servicedependency.execution_fail_on_critical = 0;
            $scope.post.Servicedependency.execution_fail_on_unknown = 0;
            $scope.post.Servicedependency.execution_fail_on_pending = 0;
        }, true);


        $scope.$watchGroup([
            'post.Servicedependency.notification_fail_on_ok',
            'post.Servicedependency.notification_fail_on_warning',
            'post.Servicedependency.notification_fail_on_critical',
            'post.Servicedependency.notification_fail_on_unknown',
            'post.Servicedependency.notification_fail_on_pending'
        ], function(){
            if($scope.init){
                return;
            }
            if($scope.post.Servicedependency.notification_none === 0){
                return;
            }
            if($scope.post.Servicedependency.notification_fail_on_ok |
                $scope.post.Servicedependency.notification_fail_on_warning |
                $scope.post.Servicedependency.notification_fail_on_critical |
                $scope.post.Servicedependency.notification_fail_on_unknown |
                $scope.post.Servicedependency.notification_fail_on_pending
            ){
                $scope.post.Servicedependency.notification_none = 0;
            }

        }, true);

        $scope.$watch('post.Servicedependency.notification_none', function(){
            if($scope.init){
                return;
            }
            if($scope.post.Servicedependency.notification_none === 0){
                return;
            }
            $scope.post.Servicedependency.notification_fail_on_ok = 0;
            $scope.post.Servicedependency.notification_fail_on_warning = 0;
            $scope.post.Servicedependency.notification_fail_on_critical = 0;
            $scope.post.Servicedependency.notification_fail_on_unknown = 0;
            $scope.post.Servicedependency.notification_fail_on_pending = 0;
        }, true);

        //Fire on page load
        $scope.loadContainer();

    });
