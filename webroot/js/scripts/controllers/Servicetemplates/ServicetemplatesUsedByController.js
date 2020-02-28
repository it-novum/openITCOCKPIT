angular.module('openITCOCKPIT')
    .controller('ServicetemplatesUsedByController', function($scope, $http, QueryStringService, MassChangeService, $state, $stateParams){
        $scope.id = $stateParams.id;

        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/services/delete/';

        $scope.filter = {
            includeDisabled: true
        };

        $scope.load = function(){
            $http.get("/servicetemplates/usedBy/" + $scope.id + ".json", {
                params: {
                    'angular': true,
                    'filter[Services.disabled]': $scope.filter.includeDisabled
                }
            }).then(function(result){
                $scope.servicetemplate = result.data.servicetemplate;
                $scope.hostsWithServices = result.data.hostsWithServices;
                $scope.count = result.data.count;

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.selectAll = function(){
            if($scope.hostsWithServices){
                for(var hostId in $scope.hostsWithServices){
                    if($scope.hostsWithServices[hostId].allow_edit){
                        for(var key in $scope.hostsWithServices[hostId].services){
                            var serviceId = $scope.hostsWithServices[hostId].services[key].id;
                            $scope.massChange[serviceId] = true;
                        }
                    }
                }
            }
        };

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var hostId in $scope.hostsWithServices){
                for(var id in selectedObjects){
                    for(var key in $scope.hostsWithServices[hostId].services){
                        if(id == $scope.hostsWithServices[hostId].services[key].id){
                            objects[id] =
                                $scope.hostsWithServices[hostId].name + '/' +
                                $scope.hostsWithServices[hostId].services[key].servicename;
                        }
                    }
                }
            }
            return objects;
        };

        $scope.getObjectForDelete = function(host, service){
            var object = {};
            object[service.id] = service.servicename;
            return object;
        };

        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

        $scope.$watch('filter', function(){
            $scope.undoSelection();
            $scope.load();
        }, true);
    });