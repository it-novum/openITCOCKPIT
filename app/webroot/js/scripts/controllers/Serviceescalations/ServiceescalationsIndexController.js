angular.module('openITCOCKPIT')
    .controller('ServiceescalationsIndexController', function($scope, $http, MassChangeService, SortService, QueryStringService){

        SortService.setSort(QueryStringService.getValue('sort', 'Serviceescalations.id'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;
        $scope.useScroll = true;

        $scope.serviceFocus = true;
        $scope.serviceExcludeFocus = false;

        $scope.servicegroupFocus = true;
        $scope.servicegroupExcludeFocus = false;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Serviceescalations: {
                    first_notification: '',
                    last_notification: '',
                    escalate_on_recovery: '',
                    escalate_on_warning: '',
                    escalate_on_critical: '',
                    escalate_on_unknown: '',
                    notification_interval: null
                },
                Services: {
                    servicename: ''
                },
                ServicesExcluded: {
                    servicename: ''
                },
                Servicegroups: {
                    name: ''
                },
                ServicegroupsExcluded: {
                    name: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/serviceescalations/delete/';

        $scope.init = true;
        $scope.showFilter = false;

        $scope.load = function(){
            $http.get("/serviceescalations/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'page': $scope.currentPage,
                    'filter[Serviceescalations.first_notification]': $scope.filter.Serviceescalations.first_notification,
                    'filter[Serviceescalations.last_notification]': $scope.filter.Serviceescalations.last_notification,
                    'filter[Serviceescalations.escalate_on_recovery]': $scope.filter.Serviceescalations.escalate_on_recovery,
                    'filter[Serviceescalations.escalate_on_warning]': $scope.filter.Serviceescalations.escalate_on_warning,
                    'filter[Serviceescalations.escalate_on_critical]': $scope.filter.Serviceescalations.escalate_on_critical,
                    'filter[Serviceescalations.escalate_on_unknown]': $scope.filter.Serviceescalations.escalate_on_unknown,
                    'filter[Serviceescalations.notification_interval]': $scope.filter.Serviceescalations.notification_interval,
                    'filter[Services.servicename]': $scope.filter.Services.servicename,
                    'filter[ServicesExcluded.servicename]': $scope.filter.ServicesExcluded.servicename,
                    'filter[Servicegroups.name]': $scope.filter.Servicegroups.name,
                    'filter[ServicegroupsExcluded.name]': $scope.filter.ServicegroupsExcluded.name
                }
            }).then(function(result){
                $scope.serviceescalations = result.data.all_serviceescalations;
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
            if($scope.serviceescalations){
                for(var key in $scope.serviceescalations){
                    if($scope.serviceescalations[key].allowEdit === true){
                        var id = $scope.serviceescalations[key].id;
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

        $scope.getObjectForDelete = function(serviceescalation){
            var object = {};
            object[serviceescalation.id] = $scope.objectName + serviceescalation.id;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.serviceescalations){
                for(var id in selectedObjects){
                    if(id == $scope.serviceescalations[key].id){
                        if($scope.serviceescalations[key].allowEdit === true){
                            objects[id] = $scope.objectName + $scope.serviceescalations[key].id;
                        }
                    }
                }
            }
            return objects;
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
