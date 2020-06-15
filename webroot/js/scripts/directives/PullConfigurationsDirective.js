angular.module('openITCOCKPIT').directive('pullConfigurationsDirective', function($http, $filter, $timeout, $state, $stateParams, NotyService, MassChangeService, SortService){
    return {
        restrict: 'E',
        templateUrl: '/agentconnector/pullConfigurations.html',
        scope: {
            lastLoadDate: '=',
            showFilter: '='
        },

        controller: function($scope){
            var defaultFilter = function(){
                $scope.filter = {
                    Host: {
                        name: '',
                        address: ''
                    }
                };
            }
            defaultFilter();
            $scope.currentPage = 1;
            $scope.useScroll = true;
            $scope.pullConfigurations = {};
            $scope.massChange = {};
            $scope.selectedElements = 0;
            $scope.deleteUrl = '/agentconnector/delete/';
            $scope.initialized = false;

            $scope.load = function(){
                var params = {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'page': $scope.currentPage,
                    //'sort': SortService.getSort(),
                    'sort': 'Agentconfigs.id',
                    'direction': SortService.getDirection(),
                    'filter[Hosts.name]': $scope.filter.Host.name,
                    'filter[Hosts.address]': $scope.filter.Host.address,
                };

                $http.get('/agentconnector/pullConfigurations.json', {
                    params: params
                }).then(function(result){
                    $scope.pullConfigurations = result.data.pullConfigurations;
                    $scope.paging = result.data.paging;
                    $scope.scroll = result.data.scroll;
                    $scope.initialized = true;
                }, function errorCallback(result){
                    if(result.status === 403){
                        $state.go('403');
                    }

                    if(result.status === 404){
                        $state.go('404');
                    }
                });
            }

            $scope.resetFilter = function(){
                defaultFilter();
                $scope.undoSelection();
            };

            $scope.selectAll = function(){
                if($scope.pullConfigurations){
                    for(var key in $scope.pullConfigurations){
                        $scope.massChange[$scope.pullConfigurations[key].Agentconfigs.id] = true;
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
                for(var key in $scope.pullConfigurations){
                    for(var id in selectedObjects){
                        if(parseInt(id) === $scope.pullConfigurations[key].Agentconfig.id){
                            objects[id] = $scope.pullConfigurations[key].Agentconfig.host.name;
                        }
                    }
                }
                return objects;
            };

            $scope.getObjectForDelete = function(agent){
                var object = {};
                object[agent.Agentconfig.id] = agent.Agentconnector.hostuuid;
                return object;
            };

            $scope.undoSelection();
            //$scope.load();

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

            $scope.$watch('filter', function(){
                $scope.currentPage = 1;
                $scope.undoSelection();
                $scope.load();
            }, true);

            $scope.$watch('massChange', function(){
                MassChangeService.setSelected($scope.massChange);
                $scope.selectedElements = MassChangeService.getCount();
            }, true);

            $scope.$watch('lastLoadDate', function(){
                if($scope.initialized){
                    $scope.load();
                }
            }, true);
        }
    };
});
