angular.module('openITCOCKPIT')
    .controller('ServicetemplategroupsAllocateToHostController', function($scope, $http, SudoService, $state, NotyService, $stateParams, RedirectService){

        $scope.id = $stateParams.id;

        $scope.init = true;

        $scope.hostId = 0;

        if($scope.id === null || $scope.id === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('ServicetemplategroupsIndex');
            return;
        }

        $scope.id = parseInt($scope.id, 10);

        var loadServicetemplategroup = function(){
            $http.get("/servicetemplategroups/view/" + $scope.id + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.servicetemplategroup = result.data.servicetemplategroup;
            });
        };

        $scope.loadHosts = function(searchString, selected){
            if(typeof selected === "undefined"){
                selected = [];
            }

            $http.get("/hosts/loadHostsByString/1.json", {
                params: {
                    'angular': true,
                    'filter[Hosts.name]': searchString,
                    'selected[]': selected,
                    'includeDisabled': 'false'
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
                $scope.init = false;
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

        loadServicetemplategroup();
        $scope.loadHosts();

        $scope.$watch('hostId', function(){
            if($scope.init){
                return;
            }
            $scope.loadServices();
        }, true);

    });
