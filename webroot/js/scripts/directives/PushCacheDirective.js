angular.module('openITCOCKPIT').directive('pushCacheDirective', function($http, $filter, $timeout, $state, $stateParams, NotyService, MassChangeService, SortService, QueryStringService){
    return {
        restrict: 'E',
        templateUrl: '/agentconnector/pushCache.html',
        scope: {
            lastLoadDate: '=',
            showFilter: '='
        },

        controller: function($scope){
            var defaultFilter = function(){
                $scope.filter = {
                    Host: {
                        name: '',
                        uuid: QueryStringService.getStateValue($stateParams, 'hostuuid', ''),
                    }
                };
            };
            defaultFilter();
            $scope.currentPage = 1;
            $scope.useScroll = true;
            $scope.pushCache = {};
            $scope.massChange = {};
            $scope.selectedElements = 0;
            $scope.deleteUrl = '/agentconnector/pushCache/';
            $scope.initialized = false;

            $scope.load = function(){
                var params = {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'page': $scope.currentPage,
                    //'sort': SortService.getSort(),
                    'sort': 'Agenthostscache.id',
                    'direction': SortService.getDirection(),
                    'filter[Hosts.name]': $scope.filter.Host.name,
                    'filter[Hosts.uuid]': $scope.filter.Host.uuid,
                };

                $http.get('/agentconnector/pushCache.json', {
                    params: params
                }).then(function(result){
                    $scope.pushCache = result.data.pushCache;
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
            };

            $scope.resetFilter = function(){
                defaultFilter();
                $scope.undoSelection();
            };

            $scope.selectAll = function(){
                if($scope.pushCache){
                    for(var key in $scope.pushCache){
                        $scope.massChange[$scope.pushCache[key].Agentconfigs.id] = true;
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
                for(var key in $scope.pushCache){
                    for(var id in selectedObjects){
                        if(parseInt(id) === $scope.pushCache[key].Agenthostscache.id){
                            objects[id] = $scope.pushCache[key].Host.name;
                        }
                    }
                }
                return objects;
            };

            $scope.getObjectForDelete = function(agent){
                var object = {};
                object[agent.Agenthostscache.id] = agent.Host.name;
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
