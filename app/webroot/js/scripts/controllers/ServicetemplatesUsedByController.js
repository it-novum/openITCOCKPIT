angular.module('openITCOCKPIT')
    .controller('ServicetemplatesUsedByController', function($scope, $http, QueryStringService, MassChangeService){
        $scope.id = QueryStringService.getCakeId();
        $scope.total = 0;
        $scope.servicetemplate = null;
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/services/delete/';
        $scope.serverResult = [];

        var forTemplate = function(serverResponse){
            var services = [];
            var hosts = [];
            var saved_hostuuids = [];
            var result = [];
            var lastendhost = "";
            var tmp_hostservicegroup = null;

            serverResponse.forEach(function(record){
                services.push(record.Service);
                if(saved_hostuuids.indexOf(record.Host.uuid) < 0){
                    hosts.push(record.Host);
                    saved_hostuuids.push(record.Host.uuid);
                }
            });

            services.forEach(function(service){
                //Notice, API return some IDs as string :/
                if(lastendhost != service.host_id){
                    if(tmp_hostservicegroup !== null){
                        result.push(tmp_hostservicegroup);
                    }

                    tmp_hostservicegroup = {};
                    var host = null;
                    hosts.forEach(function(hostelem){
                        //Notice, API return some IDs as string :/
                        if(hostelem.id == service.host_id){
                            host = hostelem;
                        }
                    });

                    tmp_hostservicegroup = {
                        Host: host,
                        Services: []
                    };
                    lastendhost = service.host_id;
                }

                tmp_hostservicegroup.Services.push({
                    Service: service
                });

            });

            if(tmp_hostservicegroup !== null){
                result.push(tmp_hostservicegroup);
            }

            return result;
        };

        $scope.load = function(){
            $http.get("/servicetemplates/usedBy/"+$scope.id+".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.serverResult = result.data.all_services;
                if($scope.serverResult) {
                    $scope.services = forTemplate(result.data.all_services);
                    $scope.total = result.data.all_services.length;
                }
                $scope.servicetemplate = result.data.servicetemplate;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };

        $scope.selectAll = function(){
            if($scope.services){
                for(var key in $scope.serverResult){
                    if($scope.serverResult[key].Service.allow_edit){
                        var id = $scope.serverResult[key].Service.id;
                        $scope.massChange[id] = true;
                    }
                }
            }
        };

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

      /*  $scope.getObjectForDelete = function(host, service){
            var object = {};
            object[service.Service.id] = host.Host.hostname + '/' + service.Service.servicename;
            return object;
        };
*/
        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.serverResult){
                for(var id in selectedObjects){
                    if(id == $scope.serverResult[key].Service.id){
                        objects[id] =
                            $scope.serverResult[key].Host.hostname + '/' +
                            $scope.serverResult[key].Service.servicename;
                    }

                }
            }
            return objects;
        };

        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

        $scope.load();
    });