angular.module('openITCOCKPIT')
    .controller('HostescalationsIndexController', function($scope, $http, $stateParams, MassChangeService, SortService, QueryStringService){

        SortService.setSort(QueryStringService.getValue('sort', 'Hostescalations.id'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;
        $scope.useScroll = true;

        $scope.hostFocus = true;
        $scope.hostExcludeFocus = false;

        $scope.hostgroupFocus = true;
        $scope.hostgroupExcludeFocus = false;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Hostescalations: {
                    id: QueryStringService.getStateValue($stateParams, 'id', []),
                    first_notification: '',
                    last_notification: '',
                    escalate_on_recovery: '',
                    escalate_on_down: '',
                    escalate_on_unreachable: '',
                    notification_interval: null
                },
                Hosts: {
                    name: ''
                },
                HostsExcluded: {
                    name: ''
                },
                Hostgroups: {
                    name: ''
                },
                HostgroupsExcluded: {
                    name: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/hostescalations/delete/';

        $scope.init = true;
        $scope.showFilter = false;

        $scope.load = function(){
            $http.get("/hostescalations/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'page': $scope.currentPage,
                    'filter[Hostescalations.id][]': $scope.filter.Hostescalations.id,
                    'filter[Hostescalations.first_notification]': $scope.filter.Hostescalations.first_notification,
                    'filter[Hostescalations.last_notification]': $scope.filter.Hostescalations.last_notification,
                    'filter[Hostescalations.escalate_on_recovery]': $scope.filter.Hostescalations.escalate_on_recovery,
                    'filter[Hostescalations.escalate_on_down]': $scope.filter.Hostescalations.escalate_on_down,
                    'filter[Hostescalations.escalate_on_unreachable]': $scope.filter.Hostescalations.escalate_on_unreachable,
                    'filter[Hostescalations.notification_interval]': $scope.filter.Hostescalations.notification_interval,
                    'filter[Hosts.name]': $scope.filter.Hosts.name,
                    'filter[HostsExcluded.name]': $scope.filter.HostsExcluded.name,
                    'filter[Hostgroups.name]': $scope.filter.Hostgroups.name,
                    'filter[HostgroupsExcluded.name]': $scope.filter.HostgroupsExcluded.name
                }
            }).then(function(result){
                $scope.hostescalations = result.data.all_hostescalations;
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
            if($scope.hostescalations){
                for(var key in $scope.hostescalations){
                    if($scope.hostescalations[key].allowEdit === true){
                        var id = $scope.hostescalations[key].id;
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

        $scope.getObjectForDelete = function(hostescalation){
            var object = {};
            object[hostescalation.id] = hostescalation.id;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.hostescalations){
                for(var id in selectedObjects){
                    if(id == $scope.hostescalations[key].id){
                        if($scope.hostescalations[key].allowEdit === true){
                            objects[id] = $scope.hostescalations[key].id;
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
