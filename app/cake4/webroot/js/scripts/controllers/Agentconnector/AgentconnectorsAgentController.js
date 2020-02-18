angular.module('openITCOCKPIT')
    .controller('AgentconnectorsAgentController', function($scope, $http, QueryStringService, $state, $stateParams, NotyService, MassChangeService, SortService){

        $scope.hostId = $stateParams.hostId;

        SortService.setSort(QueryStringService.getValue('sort', 'Agentconnector.id'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));

        $scope.filter = {
            hostuuid: '',
            remote_addr: ''
        };
        $scope.currentPage = 1;
        $scope.useScroll = true;
        $scope.showFilter = false;
        $scope.agents = {};
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/agentconnector/delete/';

        $scope.load = function(){
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'page': $scope.currentPage,
                'sort': SortService.getSort(),
                'direction': SortService.getDirection(),
                'filter[Agentconnector.hostuuid]': $scope.filter.hostuuid,
                'filter[Agentconnector.remote_addr]': $scope.filter.remote_addr,
            };

            $http.get('/agentconnector/agents.json', {
                params: params
            }).then(function(result){
                $scope.agents = result.data.agents;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.changetrust = function(id, trust, singletrust){
            $http.post('/agentconnector/changetrust.json?angular=true', {
                'id': id,
                'trust': trust
            }).then(function(result){
                if(singletrust){
                    NotyService.genericSuccess();
                    $scope.load();
                }
            }, function errorCallback(result){
                if(singletrust){
                    NotyService.genericError({message: result.error});
                }
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.trustSelected = function(){
            var selectedObjects = MassChangeService.getSelected();
            if(Object.keys(selectedObjects).length > 0){
                for(var id in selectedObjects){
                    $scope.changetrust(id, 1, false);
                }
                setTimeout(function(){
                    $scope.load();
                }, 500);
            }
        };

        $scope.untrustSelected = function(){
            var selectedObjects = MassChangeService.getSelected();
            if(Object.keys(selectedObjects).length > 0){
                for(var id in selectedObjects){
                    $scope.changetrust(id, 0, false);
                }
                setTimeout(function(){
                    $scope.load();
                }, 500);
            }
        };

        $scope.selectAll = function(){
            if($scope.agents){
                for(var key in $scope.agents){
                    $scope.massChange[$scope.agents[key].Agentconnector.id] = true;
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
            for(var key in $scope.agents){
                for(var id in selectedObjects){
                    if(parseInt(id) === $scope.agents[key].Agentconnector.id){
                        objects[id] = $scope.agents[key].Agentconnector.hostuuid;
                    }
                }
            }
            return objects;
        };

        $scope.getObjectForDelete = function(agent){
            var object = {};
            object[agent.Agentconnector.id] = agent.Agentconnector.hostuuid;
            return object;
        };

        $scope.undoSelection();
        //$scope.load();
        SortService.setCallback($scope.load);

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

        $scope.triggerFilter = function(){
            $scope.showFilter = $scope.showFilter !== true;
        };

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
