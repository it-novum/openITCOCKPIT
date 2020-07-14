angular.module('openITCOCKPIT')
    .controller('AgentconnectorsAgentController', function($scope, $http, QueryStringService, $state, $stateParams, NotyService, MassChangeService, SortService){

        SortService.setSort(QueryStringService.getValue('sort', 'Agentconnector.id'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));

        $scope.showFilter = false;
        $scope.navSelection = QueryStringService.getStateValue($stateParams, 'selection', 'untrustedAgents');
        console.log($scope.navSelection);
        $scope.lastLoadDate = Date.now();

        $scope.load = function(){
            $scope.lastLoadDate = Date.now();
        };

        $scope.triggerFilter = function(){
            $scope.showFilter = $scope.showFilter !== true;
        };

        $scope.setNavSelection = function(selection){
            $scope.navSelection = selection ? selection : 'untrustedAgents';
        }
    });
