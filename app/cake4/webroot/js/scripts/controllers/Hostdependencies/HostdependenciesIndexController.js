angular.module('openITCOCKPIT')
    .controller('HostdependenciesIndexController', function($scope, $http, $stateParams, MassChangeService, SortService, QueryStringService){

        SortService.setSort(QueryStringService.getValue('sort', 'HostdependenciesIndexController.id'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;
        $scope.useScroll = true;

        $scope.hostFocus = true;
        $scope.hostDependentFocus = false;

        $scope.hostgroupFocus = true;
        $scope.hostgroupDependentFocus = false;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Hostdependencies: {
                    id: QueryStringService.getStateValue($stateParams, 'id', []),
                    inherits_parent: [],
                    execution_fail_on_up: '',
                    execution_fail_on_down: '',
                    execution_fail_on_unreachable: '',
                    execution_fail_on_pending: '',
                    execution_none: '',
                    notification_fail_on_up: '',
                    notification_fail_on_down: '',
                    notification_fail_on_unreachable: '',
                    notification_fail_on_pending: '',
                    notification_none: ''
                },
                Hosts: {
                    name: ''
                },
                HostsDependent: {
                    name: ''
                },
                Hostgroups: {
                    name: ''
                },
                HostgroupsDependent: {
                    name: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/hostdependencies/delete/';

        $scope.init = true;
        $scope.showFilter = false;

        $scope.load = function(){
            $http.get("/hostdependencies/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'page': $scope.currentPage,
                    'filter[Hostdependencies.id][]': $scope.filter.Hostdependencies.id,
                    'filter[Hostdependencies.inherits_parent][]': $scope.filter.Hostdependencies.inherits_parent,
                    'filter[Hostdependencies.not_inherits_parent]': $scope.filter.Hostdependencies.not_inherits_parent,
                    'filter[Hostdependencies.execution_fail_on_up]': $scope.filter.Hostdependencies.execution_fail_on_up,
                    'filter[Hostdependencies.execution_fail_on_down]': $scope.filter.Hostdependencies.execution_fail_on_down,
                    'filter[Hostdependencies.execution_fail_on_unreachable]': $scope.filter.Hostdependencies.execution_fail_on_unreachable,
                    'filter[Hostdependencies.execution_fail_on_pending]': $scope.filter.Hostdependencies.execution_fail_on_pending,
                    'filter[Hostdependencies.execution_none]': $scope.filter.Hostdependencies.execution_none,
                    'filter[Hostdependencies.notification_fail_on_up]': $scope.filter.Hostdependencies.notification_fail_on_up,
                    'filter[Hostdependencies.notification_fail_on_down]': $scope.filter.Hostdependencies.notification_fail_on_down,
                    'filter[Hostdependencies.notification_fail_on_unreachable]': $scope.filter.Hostdependencies.notification_fail_on_unreachable,
                    'filter[Hostdependencies.notification_fail_on_pending]': $scope.filter.Hostdependencies.notification_fail_on_pending,
                    'filter[Hostdependencies.notification_none]': $scope.filter.Hostdependencies.notification_none,
                    'filter[Hosts.name]': $scope.filter.Hosts.name,
                    'filter[HostsDependent.name]': $scope.filter.HostsDependent.name,
                    'filter[Hostgroups.name]': $scope.filter.Hostgroups.name,
                    'filter[HostgroupsDependent.name]': $scope.filter.HostgroupsDependent.name
                }
            }).then(function(result){
                $scope.hostdependencies = result.data.all_hostdependencies;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;

                $scope.init = false;
            });

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

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function(){
            defaultFilter();
            $scope.undoSelection();
        };

        $scope.selectAll = function(){
            if($scope.hostdependencies){
                for(var key in $scope.hostdependencies){
                    if($scope.hostdependencies[key].allowEdit === true){
                        var id = $scope.hostdependencies[key].id;
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

        $scope.getObjectForDelete = function(hostdependency){
            var object = {};
            object[hostdependency.id] = $scope.objectName + hostdependency.id;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.hostdependencies){
                for(var id in selectedObjects){
                    if(id == $scope.hostdependencies[key].id){
                        if($scope.hostdependencies[key].allowEdit === true){
                            objects[id] = $scope.objectName + $scope.hostdependencies[key].id;
                        }
                    }
                }
            }
            return objects;
        };

        //Fire on page load
        defaultFilter();
        $scope.load();


        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            if($scope.init){
                return;
            }
            $scope.currentPage = 1;
            $scope.undoSelection();
            $scope.load();
        }, true);


        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);
    });
