angular.module('openITCOCKPIT')
    .controller('ServicesIndexController', function($scope, $http, $rootScope, SortService, MassChangeService, QueryStringService){
        $rootScope.lastObjectName = null;

        SortService.setSort('Servicestatus.current_state');
        SortService.setDirection('desc');
        $scope.currentPage = 1;

        $scope.id = QueryStringService.getCakeId();

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Servicestatus: {
                    current_state: [
                        'ok',
                        'warning',
                        'critical',
                        'unknown'
                    ]
                },
                Service: {
                    name: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/hostgroups/delete/';

        $scope.init = true;
        $scope.showFilter = false;

        var lastHostUuid = null;


        var forTemplate = function(serverResponse){
            var services = [];
            var servicesstatus = [];
            var hosts = [];
            var hostsstatus = [];
            var saved_hostuuids = [];
            var result = [];
            var lastendhost = "";
            var tmp_hostservicegroup = null;

            serverResponse.forEach(function(record){
                services.push(record.Service);
                servicesstatus.push([record.Service.id, record.Servicestatus]);
                if(saved_hostuuids.indexOf(record.Host.uuid) < 0){
                    hosts.push(record.Host);
                    hostsstatus.push([record.Host.id, record.Hoststatus]);
                    saved_hostuuids.push(record.Host.uuid);
                }
            });

            services.forEach(function(service){
                if(lastendhost != service.host_id){
                    if(tmp_hostservicegroup !== null){
                        result.push(tmp_hostservicegroup);
                    }

                    tmp_hostservicegroup = {};
                    var host = null;
                    var hoststatus = null;
                    hosts.forEach(function(hostelem){
                        if(hostelem.id == service.host_id){
                            host = hostelem;
                        }
                    });
                    hostsstatus.forEach(function(hoststatelem){
                        if(hoststatelem[0] === service.host_id){
                            hoststatus = hoststatelem[1];
                        }
                    });

                    tmp_hostservicegroup = {
                        Host: host,
                        Hoststatus: hoststatus,
                        Services: []
                    };
                    lastendhost = service.host_id;
                }

                var servicestatus = null;
                servicesstatus.forEach(function(servstatelem){
                    if(servstatelem[0] === service.id){
                        servicestatus = servstatelem[1];
                    }
                });

                tmp_hostservicegroup.Services.push({
                    Service: service,
                    Servicestatus: servicestatus
                });

            });

            if(tmp_hostservicegroup !== null){
                result.push(tmp_hostservicegroup);
            }

            return result;
        };

        $scope.load = function(){
            lastHostUuid = null;
            $http.get("/services/index.json", {
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    //'filter[Container.name]': $scope.filter.container.name,
                    //'filter[Hostgroup.description]': $scope.filter.hostgroup.description
                }
            }).then(function(result){
                $scope.services = [];
                $scope.services = forTemplate(result.data.all_services);
                $scope.paging = result.data.paging;
                $scope.init = false;
            });
        };

        $scope.triggerFilter = function(){
            if($scope.showFilter === true){
                $scope.showFilter = false;
            }else{
                $scope.showFilter = true;
            }
        };

        $scope.resetFilter = function(){
            defaultFilter();
            $scope.undoSelection();
        };

        $scope.isNextHost = function(service){
            if(service.Host.uuid !== lastHostUuid){
                lastHostUuid = service.Host.uuid;
                return true;
            }
            return false;
        };

        $scope.selectAll = function(){
            if($scope.hostgroups){
                for(var key in $scope.hostgroups){
                    var id = $scope.hostgroups[key].Hostgroup.id;
                    $scope.massChange[id] = true;
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
            for(var key in $scope.hostgroups){
                for(var id in selectedObjects){
                    if(id == $scope.hostgroups[key].Hostgroup.id){
                        objects[id] = $scope.hostgroups[key].Container.name;
                    }

                }
            }
            return objects;
        };

        $scope.linkForPdf = function(){
            return;
        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.deleteSelected = function(){
            console.log('Delete');
            console.log();
        };


        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            $scope.undoSelection();
            $scope.load();
        }, true);


        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);


    });