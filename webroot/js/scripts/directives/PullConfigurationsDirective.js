angular.module('openITCOCKPIT').directive('pullConfigurationsDirective', function($http, $filter, $timeout, $state, $stateParams, NotyService, MassChangeService, SortService, QueryStringService){
    return {
        restrict: 'E',
        templateUrl: '/agentconnector/pullConfigurations.html',
        scope: {
            lastLoadDate: '=',
            showFilter: '='
        },

        controller: function($scope){
            $scope.resetEditFields = function(){
                $scope.editId = null;
                $scope.edit = {
                    port: null,
                    use_https: null,
                    insecure: null,
                    proxy: null,
                    basic_auth: null,
                    username: null,
                    password: null,
                    push_noticed: null,
                };
            };
            $scope.resetEditFields();
            var defaultFilter = function(){
                $scope.filter = {
                    Host: {
                        name: '',
                        address: '',
                        uuid: QueryStringService.getStateValue($stateParams, 'hostuuid', ''),
                    }
                };
            };
            defaultFilter();
            $scope.currentPage = 1;
            $scope.useScroll = true;
            $scope.pullConfigurations = {};
            $scope.massChange = {};
            $scope.selectedElements = 0;
            $scope.deleteUrl = '/agentconnector/pullConfigurations/delete/';
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
                    'filter[Hosts.uuid]': $scope.filter.Host.uuid,
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
            };

            $scope.resetFilter = function(){
                defaultFilter();
                $scope.undoSelection();
            };

            $scope.selectAll = function(){
                if($scope.pullConfigurations){
                    for(var key in $scope.pullConfigurations){
                        $scope.massChange[$scope.pullConfigurations[key].Agentconfig.id] = true;
                    }
                }
            };

            $scope.openEdit = function(agentconfig){
                $scope.resetEditFields();
                $scope.editId = agentconfig.id;
                $scope.edit.port = agentconfig.port;
                $scope.edit.use_https = agentconfig.use_https;
                $scope.edit.insecure = agentconfig.insecure;
                $scope.edit.proxy = agentconfig.proxy;
                $scope.edit.basic_auth = agentconfig.basic_auth;
                $scope.edit.username = agentconfig.username;
                $scope.edit.password = agentconfig.password;
                $scope.edit.push_noticed = agentconfig.push_noticed;
                $('#editAgentPullConfiguration').modal('show');
            };

            $scope.editConfig = function(){
                $http.post('/agentconnector/pullConfigurations/edit/' + $scope.editId + '.json', {
                    Agentconfig: $scope.edit
                }).then(function(result){
                    if(result.data.success && result.data.success === true){
                        NotyService.genericSuccess();
                        $scope.load();
                        $('#editAgentPullConfiguration').modal('hide');
                    }else{
                        NotyService.genericError();
                        if(result.data.errors){
                            $scope.errors = result.data.errors;
                        }
                    }
                }, function errorCallback(result){
                    if(result.status === 403){
                        $state.go('403');
                    }

                    if(result.status === 404){
                        $state.go('404');
                    }
                });
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
                            objects[id] = $scope.pullConfigurations[key].Host.name;
                        }
                    }
                }
                return objects;
            };

            $scope.getObjectForDelete = function(agent){
                var object = {};
                object[agent.Agentconfig.id] = agent.Host.name;
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
