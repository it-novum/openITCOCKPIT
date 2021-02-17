angular.module('openITCOCKPIT')
    .controller('AgentconnectorsPushController', function($scope, $http, $rootScope, $stateParams, SortService, MassChangeService, QueryStringService){

        SortService.setSort(QueryStringService.getValue('sort', 'Hosts.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;


        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Hosts: {
                    name: ''
                },
                host_assignment: false,
                no_host_assignment: false
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/agentconnector/delete_push_agent/';

        $scope.init = true;
        $scope.showFilter = false;


        var buildUrl = function(baseUrl){
            var ids = Object.keys(MassChangeService.getSelected());
            return baseUrl + ids.join('/');
        };


        $scope.load = function(){
            var hasHostAssignment = '';
            if($scope.filter.host_assignment ^ $scope.filter.no_host_assignment){
                hasHostAssignment = $scope.filter.host_assignment === true;
            }

            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosts.name]': $scope.filter.Hosts.name,
                'filter[hasHostAssignment]': hasHostAssignment
            };

            $http.get("/agentconnector/push.json", {
                params: params
            }).then(function(result){
                $scope.agents = result.data.agents;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function(){
            defaultFilter();
            $scope.undoSelection();
        };

        $scope.selectAll = function(){
            if($scope.agents){
                for(var key in $scope.agents){
                    if($scope.agents[key].allow_edit === true){
                        var id = $scope.agents[key].id;
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

        $scope.getObjectForDelete = function(agent){
            var object = {};
            if(agent.Hosts.name !== null){
                object[agent.id] = agent.Hosts.name;
            }else{
                object[agent.id] = agent.uuid;
            }
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.agents){
                for(var id in selectedObjects){
                    if(id == $scope.agents[key].id){
                        if($scope.agents[key].allow_edit === true){
                            if($scope.agents[key].Hosts.name !== null){
                                objects[id] = $scope.agents[key].Hosts.name;
                            }else{
                                objects[id] = $scope.agents[key].uuid;
                            }
                        }
                    }
                }
            }
            return objects;
        };


        $scope.changepage = function(page){
            $scope.undoSelection();
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.undoSelection();
            $scope.load();
        }, true);


        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

    });
