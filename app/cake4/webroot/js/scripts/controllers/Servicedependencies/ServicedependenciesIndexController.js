angular.module('openITCOCKPIT')
    .controller('ServicedependenciesIndexController', function($scope, $http, $stateParams, MassChangeService, SortService, QueryStringService){

        SortService.setSort(QueryStringService.getValue('sort', 'ServicedependenciesIndexController.id'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;
        $scope.useScroll = true;

        $scope.sericeFocus = true;
        $scope.serviceDependentFocus = false;

        $scope.servicegroupFocus = true;
        $scope.servicegroupDependentFocus = false;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Servicedependencies: {
                    id: QueryStringService.getStateValue($stateParams, 'id', []),
                    inherits_parent: '',
                    execution_fail_on_ok: '',
                    execution_fail_on_warning: '',
                    execution_fail_on_critical: '',
                    execution_fail_on_unknown: '',
                    execution_fail_on_pending: '',
                    execution_none: '',
                    notification_fail_on_ok: '',
                    notification_fail_on_warning: '',
                    notification_fail_on_critical: '',
                    notification_fail_on_unknown: '',
                    notification_fail_on_pending: '',
                    notification_none: ''
                },
                Services: {
                    servicename: ''
                },
                ServicesDependent: {
                    servicename: ''
                },
                Servicegroups: {
                    name: ''
                },
                ServicegroupsDependent: {
                    name: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/servicedependencies/delete/';

        $scope.init = true;
        $scope.showFilter = false;

        $scope.load = function(){
            $http.get("/servicedependencies/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'page': $scope.currentPage,
                    'filter[Servicedependencies.id][]': $scope.filter.Servicedependencies.id,
                    'filter[Servicedependencies.inherits_parent]': $scope.filter.Servicedependencies.inherits_parent,
                    'filter[Servicedependencies.execution_fail_on_ok]': $scope.filter.Servicedependencies.execution_fail_on_ok,
                    'filter[Servicedependencies.execution_fail_on_warning]': $scope.filter.Servicedependencies.execution_fail_on_warning,
                    'filter[Servicedependencies.execution_fail_on_critical]': $scope.filter.Servicedependencies.execution_fail_on_critical,
                    'filter[Servicedependencies.execution_fail_on_unknown]': $scope.filter.Servicedependencies.execution_fail_on_unknown,
                    'filter[Servicedependencies.execution_fail_on_pending]': $scope.filter.Servicedependencies.execution_fail_on_pending,
                    'filter[Servicedependencies.execution_none]': $scope.filter.Servicedependencies.execution_none,
                    'filter[Servicedependencies.notification_fail_on_ok]': $scope.filter.Servicedependencies.notification_fail_on_ok,
                    'filter[Servicedependencies.notification_fail_on_warning]': $scope.filter.Servicedependencies.notification_fail_on_warning,
                    'filter[Servicedependencies.notification_fail_on_critical]': $scope.filter.Servicedependencies.notification_fail_on_critical,
                    'filter[Servicedependencies.notification_fail_on_unknown]': $scope.filter.Servicedependencies.notification_fail_on_unknown,
                    'filter[Servicedependencies.notification_fail_on_pending]': $scope.filter.Servicedependencies.notification_fail_on_pending,
                    'filter[Servicedependencies.notification_none]': $scope.filter.Servicedependencies.notification_none,
                    'filter[Services.servicename]': $scope.filter.Services.servicename,
                    'filter[ServicesDependent.servicename]': $scope.filter.ServicesDependent.servicename,
                    'filter[Servicegroups.name]': $scope.filter.Servicegroups.name,
                    'filter[ServicegroupsDependent.name]': $scope.filter.ServicegroupsDependent.name
                }
            }).then(function(result){
                $scope.servicedependencies = result.data.all_servicedependencies;
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
            if($scope.servicedependencies){
                for(var key in $scope.servicedependencies){
                    if($scope.servicedependencies[key].allowEdit === true){
                        var id = $scope.servicedependencies[key].id;
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

        $scope.getObjectForDelete = function(servicedependency){
            var object = {};
            object[servicedependency.id] = $scope.objectName + servicedependency.id;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.servicedependencies){
                for(var id in selectedObjects){
                    if(id == $scope.servicedependencies[key].id){
                        if($scope.servicedependencies[key].allowEdit === true){
                            objects[id] = $scope.objectName + $scope.servicedependencies[key].id;
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
