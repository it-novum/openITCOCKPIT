angular.module('openITCOCKPIT')
    .controller('ChangelogsIndexController', function($scope, $http, SortService, QueryStringService, $stateParams){

        SortService.setSort(QueryStringService.getStateValue($stateParams, 'sort', 'Changelogs.id'));
        SortService.setDirection(QueryStringService.getStateValue($stateParams, 'direction', 'desc'));
        $scope.useScroll = true;
        $scope.currentPage = 1;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            var now = new Date();

            $scope.filter = {
                Changelogs: {
                    name: ''
                },
                Models: {
                    Command: 1,
                    Contact: 1,
                    Contactgroup: 1,
                    Host: 1,
                    Hostgroup: 1,
                    Hosttemplate: 1,
                    Service: 1,
                    Servicegroup: 1,
                    Servicetemplate: 1,
                    Timeperiod: 1,
                    Location: 1,
                    Tenant: 1
                },
                Actions: {
                    add: 1,
                    edit: 1,
                    copy: 1,
                    delete: 1,
                    deactivate: 1,
                    activate: 1
                },
                from: date('d.m.Y H:i', now.getTime() / 1000 - (3600 * 24 * 30 * 4)),
                to: date('d.m.Y H:i', now.getTime() / 1000 + (3600 * 24 * 5)),
            };
        };

        $scope.showFilter = false;
        $scope.init = true;

        var getActionsFilter = function(){
            var selectedActions = [];
            for(var actionName in $scope.filter.Actions){
                if($scope.filter.Actions[actionName] === 1){
                    selectedActions.push(actionName);
                }
            }

            return selectedActions;
        };

        var getModelsFilter = function(){
            var selectedModels = [];
            for(var modelName in $scope.filter.Models){
                if($scope.filter.Models[modelName] === 1){
                    selectedModels.push(modelName);
                }
            }

            return selectedModels;
        };

        $scope.load = function(){
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Changelogs.name]': $scope.filter.Changelogs.name,
                'filter[Changelogs.action][]': getActionsFilter(),
                'filter[Changelogs.model][]': getModelsFilter(),
                'filter[from]': $scope.filter.from,
                'filter[to]': $scope.filter.to
            };

            $http.get("/changelogs/index.json", {
                params: params
            }).then(function(result){
                $scope.changes = result.data.all_changes;
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
        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        $scope.data_unserialized_notEmpty = function(data_unserialized){
            if(data_unserialized.constructor === Array){
                if(data_unserialized.length === 0){
                    return false;
                }
            }else if(data_unserialized.constructor === Object){
                if(Object.keys(data_unserialized).length <= 0){
                    return false;
                }
            }
            return true;
        };


        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        //Watch on filter change
        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.load();
        }, true);
    });

