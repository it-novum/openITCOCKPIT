angular.module('openITCOCKPIT')
    .controller('AgentchecksIndexController', function($scope, $http, $rootScope, SortService, MassChangeService, QueryStringService, $state){
        $rootScope.lastObjectName = null;

        SortService.setSort(QueryStringService.getValue('sort', 'Agentchecks.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Agentchecks: {
                    name: '',
                },
                Servicetemplates: {
                    template_name: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/agentchecks/delete/';

        $scope.init = true;
        $scope.showFilter = false;


        var buildUrl = function(baseUrl){
            var ids = Object.keys(MassChangeService.getSelected());
            return baseUrl + ids.join('/');
        };


        $scope.load = function(){
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Agentchecks.name]': $scope.filter.Agentchecks.name,
                'filter[Servicetemplates.template_name]': $scope.filter.Servicetemplates.template_name,
            };

            $http.get("/agentchecks/index.json", {
                params: params
            }).then(function(result){
                $scope.agentchecks = result.data.all_agentchecks;
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
            if($scope.agentchecks){
                for(var key in $scope.agentchecks){
                    if($scope.agentchecks[key].allow_edit === true){
                        var id = $scope.agentchecks[key].id;
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

        $scope.getObjectForDelete = function(agentcheck){
            var object = {};
            object[agentcheck.id] = agentcheck.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.agentchecks){
                for(var id in selectedObjects){
                    if(id == $scope.agentchecks[key].id){
                        if($scope.agentchecks[key].allow_edit === true){
                            objects[id] = $scope.agentchecks[key].name;
                        }
                    }
                }
            }
            return objects;
        };


        $scope.linkForCopy = function(){
            var ids = Object.keys(MassChangeService.getSelected());
            return ids.join(',');
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