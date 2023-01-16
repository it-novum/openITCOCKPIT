angular.module('openITCOCKPIT')
    .controller('ServicesIndexController', function($scope, $http, $rootScope, $httpParamSerializer, $stateParams, SortService, MassChangeService, QueryStringService, NotyService){
        $rootScope.lastObjectName = null;
        var startTimestamp = new Date().getTime();

        SortService.setSort(QueryStringService.getStateValue($stateParams, 'sort', 'Servicestatus.current_state'));
        SortService.setDirection(QueryStringService.getStateValue($stateParams, 'direction', 'desc'));

        $scope.currentPage = 1;

        $scope.id = QueryStringService.getCakeId();

        $scope.useScroll = true;

        /*** Filter Settings ***/
            //filterId = QueryStringService.getStateValue($stateParams, 'filter');
        var defaultFilter = function(){
                $scope.filter = {
                    Servicestatus: {
                        current_state: QueryStringService.servicestate($stateParams),
                        acknowledged: QueryStringService.getStateValue($stateParams, 'has_been_acknowledged', false) == '1',
                        not_acknowledged: QueryStringService.getStateValue($stateParams, 'has_not_been_acknowledged', false) == '1',
                        in_downtime: QueryStringService.getStateValue($stateParams, 'in_downtime', false) == '1',
                        not_in_downtime: QueryStringService.getStateValue($stateParams, 'not_in_downtime', false) == '1',
                        passive: QueryStringService.getStateValue($stateParams, 'passive', false) == '1',
                        active: QueryStringService.getValue('active', false) === '1',
                        notifications_enabled: QueryStringService.getStateValue($stateParams, 'notifications_not_enabled', false) == '1',
                        output: ''
                    },
                    Services: {
                        id: QueryStringService.getStateValue($stateParams, 'id', []),
                        name: QueryStringService.getStateValue($stateParams, 'servicename', ''),
                        keywords: '',
                        not_keywords: '',
                        servicedescription: '',
                        priority: {
                            1: false,
                            2: false,
                            3: false,
                            4: false,
                            5: false
                        }
                    },
                    Hosts: {
                        id: QueryStringService.getStateValue($stateParams, 'host_id', []),
                        name: QueryStringService.getStateValue($stateParams, 'hostname', ''),
                        satellite_id: []
                    }
                };
            };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/services/delete/';
        $scope.deactivateUrl = '/services/deactivate/';

        $scope.init = true;
        $scope.showFilter = false;

        $scope.showBookmarkFilter = false;

        $scope.loadTimezone = function(){
            $http.get("/angular/user_timezone.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.timezone = result.data.timezone;
            });
        };

        $scope.load = function(){

            var hasBeenAcknowledged = '';
            var inDowntime = '';
            var notificationsEnabled = '';

            if($scope.filter.Servicestatus.acknowledged ^ $scope.filter.Servicestatus.not_acknowledged){
                hasBeenAcknowledged = $scope.filter.Servicestatus.acknowledged === true;
            }
            if($scope.filter.Servicestatus.in_downtime ^ $scope.filter.Servicestatus.not_in_downtime){
                inDowntime = $scope.filter.Servicestatus.in_downtime === true;
            }
            if($scope.filter.Servicestatus.notifications_enabled ^ $scope.filter.Servicestatus.notifications_not_enabled){
                notificationsEnabled = $scope.filter.Servicestatus.notifications_enabled === true;
            }

            var passive = '';
            if($scope.filter.Servicestatus.passive ^ $scope.filter.Servicestatus.active){
                passive = !$scope.filter.Servicestatus.passive;
            }

            var priorityFilter = [];
            for(var key in $scope.filter.Services.priority){
                if($scope.filter.Services.priority[key] === true){
                    priorityFilter.push(key);
                }
            }

            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosts.id]': $scope.filter.Hosts.id,
                'filter[Hosts.name]': $scope.filter.Hosts.name,
                'filter[Hosts.satellite_id][]': $scope.filter.Hosts.satellite_id,
                'filter[Services.id][]': $scope.filter.Services.id,
                'filter[servicename]': $scope.filter.Services.name,
                'filter[servicedescription]': $scope.filter.Services.servicedescription,
                'filter[Servicestatus.output]': $scope.filter.Servicestatus.output,
                'filter[Servicestatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Servicestatus.current_state),
                'filter[keywords][]': $scope.filter.Services.keywords.split(','),
                'filter[not_keywords][]': $scope.filter.Services.not_keywords.split(','),
                'filter[Servicestatus.problem_has_been_acknowledged]': hasBeenAcknowledged,
                'filter[Servicestatus.scheduled_downtime_depth]': inDowntime,
                'filter[Servicestatus.active_checks_enabled]': passive,
                'filter[Servicestatus.notifications_enabled]': notificationsEnabled,
                'filter[servicepriority][]': priorityFilter

            };
            if(QueryStringService.getStateValue($stateParams, 'BrowserContainerId') !== null){
                params['BrowserContainerId'] = QueryStringService.getStateValue($stateParams, 'BrowserContainerId');
            }

            $http.get("/services/index.json", {
                params: params
            }).then(function(result){
                $scope.services = result.data.all_services;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter;
            if($scope.showFilter === true){
            }
        };

        $scope.triggerBookmarkFilter = function(){
            $scope.showBookmarkFilter = !$scope.showBookmarkFilter === true;
        };


        $scope.resetFilter = function(){
            defaultFilter();
            $scope.undoSelection();

        };

        $scope.selectAll = function(){
            if($scope.services){
                for(var key in $scope.services){
                    if($scope.services[key].Service.allow_edit){
                        var id = $scope.services[key].Service.id;
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

        $scope.getObjectForDelete = function(service){
            var object = {};
            object[service.Service.id] = service.Host.hostname + '/' + service.Service.servicename;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.services){
                for(var id in selectedObjects){
                    if(id == $scope.services[key].Service.id){
                        objects[id] =
                            $scope.services[key].Host.hostname + '/' +
                            $scope.services[key].Service.servicename;
                    }

                }
            }
            return objects;
        };

        $scope.getObjectsForExternalCommand = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.services){
                for(var id in selectedObjects){
                    if(id == $scope.services[key].Service.id){
                        objects[id] = $scope.services[key];
                    }
                }
            }
            return objects;
        };

        $scope.linkForCopy = function(){
            var ids = Object.keys(MassChangeService.getSelected());
            return ids.join(',');
        };

        $scope.linkFor = function(format){
            var baseUrl = '/services/listToPdf.pdf?';
            if(format === 'csv'){
                baseUrl = '/services/listToCsv?';
            }

            var hasBeenAcknowledged = '';
            var inDowntime = '';
            if($scope.filter.Servicestatus.acknowledged ^ $scope.filter.Servicestatus.not_acknowledged){
                hasBeenAcknowledged = $scope.filter.Servicestatus.acknowledged === true;
            }
            if($scope.filter.Servicestatus.in_downtime ^ $scope.filter.Servicestatus.not_in_downtime){
                inDowntime = $scope.filter.Servicestatus.in_downtime === true;
            }

            var passive = '';
            if($scope.filter.Servicestatus.passive){
                passive = !$scope.filter.Servicestatus.passive;
            }

            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosts.id]': $scope.filter.Hosts.id,
                'filter[Hosts.name]': $scope.filter.Hosts.name,
                'filter[Hosts.satellite_id][]': $scope.filter.Hosts.satellite_id,
                'filter[Services.id]': $scope.filter.Services.id,
                'filter[servicename]': $scope.filter.Services.name,
                'filter[Servicestatus.output]': $scope.filter.Servicestatus.output,
                'filter[Servicestatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Servicestatus.current_state),
                'filter[keywords][]': $scope.filter.Services.keywords.split(','),
                'filter[not_keywords][]': $scope.filter.Services.not_keywords.split(','),
                'filter[Servicestatus.problem_has_been_acknowledged]': hasBeenAcknowledged,
                'filter[Servicestatus.scheduled_downtime_depth]': inDowntime,
                'filter[Servicestatus.active_checks_enabled]': passive
            };

            if(QueryStringService.hasValue('BrowserContainerId')){
                params['BrowserContainerId'] = QueryStringService.getValue('BrowserContainerId');
            }

            return baseUrl + $httpParamSerializer(params);

        };

        $scope.changepage = function(page){
            $scope.undoSelection();
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };


        $scope.problemsOnly = function(){
            defaultFilter();
            $scope.filter.Servicestatus.not_in_downtime = true;
            $scope.filter.Servicestatus.not_acknowledged = true;
            $scope.filter.Servicestatus.current_state = {
                ok: false,
                warning: true,
                critical: true,
                unknown: true
            };
            SortService.setSort('Servicestatus.last_state_change');
            SortService.setDirection('desc');
        };

        $scope.triggerLoadByBookmark = function(filter){
            if(typeof filter !== "undefined"){
                $scope.init = true; //Disable $watch to avoid two HTTP requests
                $scope.filter = filter;
            }else{
                $scope.init = true;
                $scope.resetFilter();
            }

            $("#ServicesKeywordsInput").tagsinput('removeAll');
            $("#ServicesKeywordsInput").tagsinput('add', $scope.filter.Services.keywords);

            $("#ServicesNotKeywordsInput").tagsinput('removeAll');
            $("#ServicesNotKeywordsInput").tagsinput('add', $scope.filter.Services.not_keywords);

            $scope.currentPage = 1;
            $scope.undoSelection();
            $scope.load();
        };


        //Fire on page load
        defaultFilter();
        $scope.loadTimezone();
        SortService.setCallback($scope.load);

        jQuery(function(){
            $("input[data-role=tagsinput]").tagsinput();
        });

        $scope.$watch('filter', function(){
            if($scope.init === false){
                $scope.currentPage = 1;
                $scope.undoSelection();
                $scope.load();
            }
        }, true);

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

    });
