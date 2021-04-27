angular.module('openITCOCKPIT')
    .controller('ServicetemplategroupsAllocateToHostgroupController', function($scope, $http, SudoService, $state, NotyService, $stateParams, RedirectService){

        $scope.isProcessing = false;
        $scope.percentage = 0;

        $scope.id = 0;
        var urlId = $stateParams.id;
        if(urlId !== null){
            $scope.id = parseInt(urlId, 10);
        }

        $scope.init = true;

        $scope.hostgroupId = 0;

        var urlHostgroupId = $stateParams.hostgroupId;
        if(urlHostgroupId !== null){
            $scope.hostgroupId = parseInt(urlHostgroupId, 10);
        }

        $scope.loadServicetemplategroups = function(searchString, selected){
            var params = {
                'angular': true,
                'filter[Containers.name]': searchString,
                'selected[]': $scope.id
            };

            if(typeof selected !== "undefined"){
                if(selected > 0){
                    params['selected[]'] = selected;
                }
            }

            $http.get("/servicetemplategroups/loadServicetemplategroupsByString.json", {
                params: params
            }).then(function(result){
                $scope.servicetemplategroups = result.data.servicetemplategroups;
            });
        };

        $scope.loadHostgroups = function(searchString, selected){
            var params = {
                'angular': true,
                'filter[Containers.name]': searchString
            };

            if(typeof selected !== "undefined"){
                if(selected > 0){
                    params['selected[]'] = selected;
                }
            }

            if($scope.hostgroupId){
                params['selected[]'] = [$scope.hostgroupId];
            }

            $http.get("/servicetemplategroups/loadHostgroupsByString.json", {
                params: params
            }).then(function(result){
                $scope.hostgroups = result.data.hostgroups;
                $scope.init = false;

                if(selected && $scope.id > 0){
                    //A host group was pre selected via URL
                    $scope.loadServices();
                }

            });
        };

        $scope.loadServices = function(){
            $http.get("/servicetemplategroups/allocateToHostgroup/" + $scope.id + ".json", {
                params: {
                    'angular': true,
                    'hostgroupId': $scope.hostgroupId
                }
            }).then(function(result){
                $scope.hostsWithServicesToDeploy = result.data.hostsWithServicetemplatesForDeploy;
                setTimeout(function(){
                    jQuery("[rel=tooltip]").tooltip()
                }, 250);

            });
        };

        $scope.submit = function(){

            var count = $scope.hostsWithServicesToDeploy.length;
            $scope.isProcessing = true;
            var i = 0;

            for(var hostIndex in $scope.hostsWithServicesToDeploy){
                var hostId = $scope.hostsWithServicesToDeploy[hostIndex].host.id;

                var servicetemplateIds = [];
                for(var serviceIndex in $scope.hostsWithServicesToDeploy[hostIndex].services){
                    if($scope.hostsWithServicesToDeploy[hostIndex].services[serviceIndex].createServiceOnTargetHost === true){
                        servicetemplateIds.push($scope.hostsWithServicesToDeploy[hostIndex].services[serviceIndex].servicetemplate.id);
                    }
                }

                if(servicetemplateIds.length > 0){
                    $http.post("/servicetemplategroups/allocateToHost/" + $scope.id + ".json?angular=true",
                        {
                            Host: {
                                id: hostId
                            },
                            Servicetemplates: {
                                _ids: servicetemplateIds
                            }
                        }
                    ).then(function(result){
                        i++;
                        $scope.percentage = Math.round(i / count * 100);

                        if(i === count){
                            NotyService.genericSuccess();

                            RedirectService.redirectWithFallback('ServicesNotMonitored');

                            console.log('Data saved successfully');
                        }
                    }, function errorCallback(result){

                        NotyService.genericError();

                        if(result.data.hasOwnProperty('error')){
                            $scope.errors = result.data.error;
                        }
                    });
                }else{
                    i++;
                    $scope.percentage = Math.round(i / count * 100);
                }
            }
        };

        $scope.selectAll = function(){
            if(typeof $scope.hostsWithServicesToDeploy === "undefined"){
                return;
            }

            for(var hostIndex in $scope.hostsWithServicesToDeploy){
                for(var serviceIndex in $scope.hostsWithServicesToDeploy[hostIndex].services){
                    $scope.hostsWithServicesToDeploy[hostIndex].services[serviceIndex].createServiceOnTargetHost = true;
                }
            }
        };

        $scope.undoSelection = function(){
            if(typeof $scope.hostsWithServicesToDeploy === "undefined"){
                return;
            }

            for(var hostIndex in $scope.hostsWithServicesToDeploy){
                for(var serviceIndex in $scope.hostsWithServicesToDeploy[hostIndex].services){
                    $scope.hostsWithServicesToDeploy[hostIndex].services[serviceIndex].createServiceOnTargetHost = false;
                }
            }
        };

        $scope.loadServicetemplategroups('', $scope.id);
        $scope.loadHostgroups('', $scope.hostgroupId);

        $scope.$watch('hostgroupId', function(){
            if($scope.init){
                return;
            }
            $scope.loadServices();
        }, true);

        $scope.$watch('id', function(){
            if($scope.init){
                return;
            }

            if($scope.hostgroupId <= 0){
                return;
            }

            //HostgroupId was passed via URL
            //ServicetemplategroupId was 0
            $scope.loadServices();
        }, true);

    });
