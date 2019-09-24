angular.module('openITCOCKPIT')
    .controller('ServicetemplategroupsAllocateToHostController', function($scope, $http, SudoService, $state, NotyService, $stateParams, RedirectService){

        $scope.id = 0;
        var urlId = $stateParams.id;
        if(urlId !== null){
            $scope.id = parseInt(urlId, 10);
        }

        $scope.init = true;

        $scope.hostId = 0;

        var urlHostId = $stateParams.hostId;
        if(urlHostId !== null){
            $scope.hostId = parseInt(urlHostId, 10);
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

        $scope.loadHosts = function(searchString, selected){
            var params = {
                'angular': true,
                'filter[Hosts.name]': searchString,
                'includeDisabled': 'false'
            };

            if(typeof selected !== "undefined"){
                if(selected > 0){
                    params['selected[]'] = selected;
                }
            }

            if($scope.hostId){
                params['selected[]'] = [$scope.hostId];
            }

            $http.get("/hosts/loadHostsByString/1.json", {
                params: params
            }).then(function(result){
                $scope.hosts = result.data.hosts;
                $scope.init = false;

                if(selected && $scope.id > 0){
                    //A host was pre selected via URL
                    $scope.loadServices();
                }

            });
        };

        $scope.loadServices = function(){
            $http.get("/servicetemplategroups/allocateToHost/" + $scope.id + ".json", {
                params: {
                    'angular': true,
                    'hostId': $scope.hostId
                }
            }).then(function(result){
                $scope.servicesToDeploy = result.data.servicetemplatesForDeploy;
                setTimeout(function(){
                    jQuery("[rel=tooltip]").tooltip()
                }, 250);

            });
        };

        $scope.submit = function(){

            var servicetemplatestoDeploy = [];

            for(var index in $scope.servicesToDeploy){
                if($scope.servicesToDeploy[index].createServiceOnTargetHost === true){
                    servicetemplatestoDeploy.push($scope.servicesToDeploy[index].servicetemplate.id);
                }
            }


            $http.post("/servicetemplategroups/allocateToHost/" + $scope.id + ".json?angular=true",
                {
                    Host: {
                        id: $scope.hostId
                    },
                    Servicetemplates: {
                        _ids: servicetemplatestoDeploy
                    }
                }
            ).then(function(result){
                var url = $state.href('HostsBrowser', {id: $scope.hostId});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('ServicetemplategroupsIndex');

                console.log('Data saved successfully');
            }, function errorCallback(result){

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.selectAll = function(){
            if(typeof $scope.servicesToDeploy === "undefined"){
                return;
            }

            for(var index in $scope.servicesToDeploy){
                $scope.servicesToDeploy[index].createServiceOnTargetHost = true;
            }
        };

        $scope.undoSelection = function(){
            if(typeof $scope.servicesToDeploy === "undefined"){
                return;
            }

            for(var index in $scope.servicesToDeploy){
                $scope.servicesToDeploy[index].createServiceOnTargetHost = false;
            }
        };

        $scope.loadServicetemplategroups('', $scope.id);
        $scope.loadHosts('', $scope.hostId);

        $scope.$watch('hostId', function(){
            if($scope.init){
                return;
            }
            $scope.loadServices();
        }, true);

        $scope.$watch('id', function(){
            if($scope.init){
                return;
            }

            if($scope.hostId <= 0){
                return;
            }

            //Hostid was passed via URL
            //ServicetemplategroupId was 0
            $scope.loadServices();
        }, true);

    });
