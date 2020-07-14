angular.module('openITCOCKPIT')
    .controller('ServicesCopyController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        $scope.hostId = 0;

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('ServicesIndex');
            return;
        }

        $scope.loadHosts = function(searchString){
            $http.get("/hosts/loadHostsByString/1.json", {
                params: {
                    'angular': true,
                    'filter[Hosts.name]': searchString,
                    'selected[]': $scope.hostId,
                    'includeDisabled': 'false'
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        $scope.loadServices = function(){
            $http.get("/services/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                    'hostId': $scope.hostId
                }
            }).then(function(result){
                $scope.sourceServices = [];
                $scope.commands = result.data.commands;
                for(var key in result.data.services){
                    var sourceId = result.data.services[key].id;
                    delete result.data.services[key].id;

                    var service = {
                        Source: {
                            id: sourceId,
                            hostname: result.data.services[key].host.name,
                            _name: result.data.services[key]._name
                        },
                        Service: result.data.services[key]
                    };

                    $scope.sourceServices.push(service);
                }

                $scope.init = false;

            });
        };

        $scope.loadCommandArguments = function(sourceId, commandId, $index){
            var params = {
                'angular': true
            };

            $http.get("/services/loadCommandArguments/" + commandId + "/" + sourceId + ".json", {
                params: params
            }).then(function(result){
                $scope.sourceServices[$index].Service.servicecommandargumentvalues = result.data.servicecommandargumentvalues;
            });
        };

        $scope.copy = function(){
            $http.post("/services/copy/.json?angular=true",
                {
                    data: $scope.sourceServices,
                    hostId: $scope.hostId
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('ServicesIndex');

            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceServices = result.data.result;
            });
        };

        $scope.$watch('hostId', function(){
            if($scope.hostId > 0){
                $scope.loadServices();
            }
        }, true);

        $scope.loadHosts('');
    });