angular.module('openITCOCKPIT')
    .controller('CommandsIndexController', function($scope, $http, $rootScope, SortService, MassChangeService, QueryStringService){
        $rootScope.lastObjectName = null;

        SortService.setSort(QueryStringService.getValue('sort', 'Commands.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Commands: {
                    name: '',
                    service_checks: true,
                    host_checks: true,
                    notifications: true,
                    eventhandler: true
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/commands/delete/';

        $scope.init = true;
        $scope.showFilter = false;

        var getSelectedCommandTypeFilters = function(){
            var filter = [];
            if($scope.filter.Commands.service_checks){
                filter.push(1);
            }
            if($scope.filter.Commands.host_checks){
                filter.push(2);
            }
            if($scope.filter.Commands.notifications){
                filter.push(3);
            }
            if($scope.filter.Commands.eventhandler){
                filter.push(4);
            }
            return filter;
        };

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
                'filter[Commands.name]': $scope.filter.Commands.name,
                'filter[Commands.command_type][]': getSelectedCommandTypeFilters()
            };

            $http.get("/commands/index.json", {
                params: params
            }).then(function(result){
                $scope.commands = result.data.all_commands;
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
            if($scope.commands){
                for(var key in $scope.commands){
                    var id = $scope.commands[key].Command.id;
                    $scope.massChange[id] = true;
                }
            }
        };

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.getObjectForDelete = function(command){
            var object = {};
            object[command.Command.id] = command.Command.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.commands){
                for(var id in selectedObjects){
                    if(id == $scope.commands[key].Command.id){
                        objects[id] = $scope.commands[key].Command.name;
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
