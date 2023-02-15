angular.module('openITCOCKPIT')
    .controller('HostsIndexController', function($scope, $http, $window, $rootScope, $httpParamSerializer, SortService, MassChangeService, NotyService, QueryStringService, $stateParams){
        $rootScope.lastObjectName = null;
        SortService.setSort(QueryStringService.getStateValue($stateParams, 'sort', 'Hoststatus.current_state'));
        SortService.setDirection(QueryStringService.getStateValue($stateParams, 'direction', 'desc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;
        filterHostname = QueryStringService.getStateValue($stateParams, 'hostname');
        filterAddress = QueryStringService.getStateValue($stateParams, 'address');
        //console.log(QueryStringService.getStateValue($stateParams, 'filter'));
        /*** Filter Settings ***/
            //filterId = QueryStringService.getStateValue($stateParams, 'filter');
        var defaultFilter = function(){
                $scope.filter = {
                    Hoststatus: {
                        current_state: QueryStringService.hoststate($stateParams),
                        acknowledged: QueryStringService.getStateValue($stateParams, 'has_been_acknowledged', false) == '1',
                        not_acknowledged: QueryStringService.getStateValue($stateParams, 'has_not_been_acknowledged', false) == '1',
                        in_downtime: QueryStringService.getStateValue($stateParams, 'in_downtime', false) == '1',
                        not_in_downtime: QueryStringService.getStateValue($stateParams, 'not_in_downtime', false) == '1',
                        notifications_not_enabled: QueryStringService.getStateValue($stateParams, 'notifications_enabled', false) == '1',
                        notifications_enabled: QueryStringService.getStateValue($stateParams, 'notifications_not_enabled', false) == '1',
                        output: ''
                    },
                    Host: {
                        id: QueryStringService.getStateValue($stateParams, 'id', []),
                        name: (filterHostname) ? filterHostname : '',
                        hostdescription: '',
                        keywords: '',
                        not_keywords: '',
                        address: (filterAddress) ? filterAddress : '',
                        satellite_id: [],
                        priority: {
                            1: false,
                            2: false,
                            3: false,
                            4: false,
                            5: false
                        },
                        host_type: []
                    }
                };
            };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/hosts/delete/';
        $scope.deactivateUrl = '/hosts/deactivate/';

        $scope.init = true;
        $scope.showFilter = false;
        $scope.showBookmarkFilter = false;

        /*** column vars ***/
        $scope.fields = [];
        $scope.columnsLength = 17;
        $scope.columnsTableKey = 'HostsIndexColumns';

        /*** columns functions
         columns:
         ['Hoststatus',
         'is acknowledged',
         'is in downtime',
         'Notifications enabled',
         'Shared',
         'Passively transferred host',
         'Priority',
         'Host name',
         'Host description',
         'IP address',
         'Last state change',
         'Last check',
         'Host output',
         'Instance',
         'Service Summary ',
         'Host notes',
         'Host type'] ***/
        $scope.defaultColumns = function(){
            $scope.fields = [true, true, true, true, true, true, true, true, false, true, true, true, true, true, true, false, false];
            $window.localStorage.removeItem($scope.columnsTableKey);
        };

        $scope.saveColumns = function(){
            $window.localStorage.removeItem($scope.columnsTableKey);
            $window.localStorage.setItem($scope.columnsTableKey, JSON.stringify($scope.fields));

        }

        $scope.loadColumns = function(){
            var fields = JSON.parse($window.localStorage.getItem($scope.columnsTableKey));
            if(typeof fields !== undefined && Array.isArray(fields)){
                $scope.fields = fields;
            }else{
                $scope.defaultColumns()
            }
        }

        $scope.triggerLoadColumns = function(fields){
            $scope.fields = fields;
        };
        /*** end columns functions ***/

        $scope.load = function(){
            //console.trace();
            lastHostUuid = null;
            var hasBeenAcknowledged = '';
            var inDowntime = '';
            var notificationsEnabled = '';
            if($scope.filter.Hoststatus.acknowledged ^ $scope.filter.Hoststatus.not_acknowledged){
                hasBeenAcknowledged = $scope.filter.Hoststatus.acknowledged === true;
            }
            if($scope.filter.Hoststatus.in_downtime ^ $scope.filter.Hoststatus.not_in_downtime){
                inDowntime = $scope.filter.Hoststatus.in_downtime === true;
            }
            if($scope.filter.Hoststatus.notifications_enabled ^ $scope.filter.Hoststatus.notifications_not_enabled){
                notificationsEnabled = $scope.filter.Hoststatus.notifications_enabled === true;
            }
            var priorityFilter = [];
            for(var key in $scope.filter.Host.priority){
                if($scope.filter.Host.priority[key] === true){
                    priorityFilter.push(key);
                }
            }

            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection()
                // Old GET filters - only here for reference. Delete this block if the year is 2023
                //'filter[Hosts.id][]': $scope.filter.Host.id,
                //'filter[Hosts.name]': $scope.filter.Host.name,
                //'filter[hostdescription]': $scope.filter.Host.hostdescription,
                //'filter[Hoststatus.output]': $scope.filter.Hoststatus.output,
                //'filter[Hoststatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Hoststatus.current_state),
                //'filter[Hosts.keywords][]': $scope.filter.Host.keywords.split(','),
                //'filter[Hosts.not_keywords][]': $scope.filter.Host.not_keywords.split(','),
                //'filter[Hoststatus.problem_has_been_acknowledged]': hasBeenAcknowledged,
                //'filter[Hoststatus.scheduled_downtime_depth]': inDowntime,
                //'filter[Hoststatus.notifications_enabled]': notificationsEnabled,
                //'filter[Hosts.address]': $scope.filter.Host.address,
                //'filter[Hosts.satellite_id][]': $scope.filter.Host.satellite_id,
                //'filter[hostpriority][]': priorityFilter
            };

            // ITC-2599 Change load function to use POST
            var data = {
                filter: {
                    'Hosts.id': $scope.filter.Host.id,
                    'Hosts.name': $scope.filter.Host.name,
                    'Hosts.keywords': ($scope.filter.Host.keywords !== '' ? $scope.filter.Host.keywords.split(',') : []),
                    'Hosts.not_keywords': ($scope.filter.Host.not_keywords !== '' ? $scope.filter.Host.not_keywords.split(',') : []),
                    'Hosts.address': $scope.filter.Host.address,
                    'Hosts.satellite_id': $scope.filter.Host.satellite_id,
                    'Hosts.host_type': $scope.filter.Host.host_type,
                    'hostdescription': $scope.filter.Host.hostdescription,
                    'Hoststatus.output': $scope.filter.Hoststatus.output,
                    'Hoststatus.current_state': $rootScope.currentStateForApi($scope.filter.Hoststatus.current_state),
                    'Hoststatus.problem_has_been_acknowledged': hasBeenAcknowledged,
                    'Hoststatus.scheduled_downtime_depth': inDowntime,
                    'Hoststatus.notifications_enabled': notificationsEnabled,
                    'hostpriority': priorityFilter
                }
            };
            if(QueryStringService.getStateValue($stateParams, 'BrowserContainerId') !== null){
                params['BrowserContainerId'] = QueryStringService.getStateValue($stateParams, 'BrowserContainerId');
            }

            $http.post("/hosts/index.json", data, {
                params: params
            }).then(function(result){
                $scope.hosts = result.data.all_hosts;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
            if($scope.showFilter === true){
                // $scope.getBookmarks();
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
            if($scope.hosts){
                for(var key in $scope.hosts){
                    if($scope.hosts[key].Host.allow_edit){
                        var id = $scope.hosts[key].Host.id;
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

        $scope.getObjectForDelete = function(host){
            var object = {};
            object[host.Host.id] = host.Host.hostname;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.hosts){
                for(var id in selectedObjects){
                    if(id == $scope.hosts[key].Host.id){
                        objects[id] = $scope.hosts[key].Host.hostname;
                    }
                }
            }
            return objects;
        };

        $scope.getObjectsForExternalCommand = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.hosts){
                for(var id in selectedObjects){
                    if(id == $scope.hosts[key].Host.id){
                        objects[id] = $scope.hosts[key];
                    }

                }
            }
            //console.log(objects);
            return objects;
        };

        $scope.linkForCopy = function(){
            var ids = Object.keys(MassChangeService.getSelected());
            return ids.join(',');
        };

        $scope.linkForEditDetails = function(){
            var ids = Object.keys(MassChangeService.getSelected());
            return ids.join(',');
        };

        var buildUrl = function(baseUrl){
            var ids = Object.keys(MassChangeService.getSelected());
            return baseUrl + ids.join('/');
        };

        $scope.linkFor = function(format){
            var baseUrl = '/hosts/listToPdf.pdf?';
            if(format === 'csv'){
                baseUrl = '/hosts/listToCsv?';
            }

            var hasBeenAcknowledged = '';
            var inDowntime = '';
            if($scope.filter.Hoststatus.acknowledged ^ $scope.filter.Hoststatus.not_acknowledged){
                hasBeenAcknowledged = $scope.filter.Hoststatus.acknowledged === true;
            }
            if($scope.filter.Hoststatus.in_downtime ^ $scope.filter.Hoststatus.not_in_downtime){
                inDowntime = $scope.filter.Hoststatus.in_downtime === true;
            }

            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosts.name]': $scope.filter.Host.name,
                'filter[Hosts.description]': $scope.filter.Host.description,
                'filter[Hoststatus.output]': $scope.filter.Hoststatus.output,
                'filter[Hoststatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Hoststatus.current_state),
                'filter[Hosts.keywords][]': $scope.filter.Host.keywords.split(','),
                'filter[Hosts.not_keywords][]': $scope.filter.Host.not_keywords.split(','),
                'filter[Hoststatus.problem_has_been_acknowledged]': hasBeenAcknowledged,
                'filter[Hoststatus.scheduled_downtime_depth]': inDowntime,
                'filter[Hosts.address]': $scope.filter.Host.address,
                'filter[Hosts.satellite_id][]': $scope.filter.Host.satellite_id,
                'filter[Hosts.host_type][]': $scope.filter.Host.host_type
            };
            if(QueryStringService.hasValue('BrowserContainerId')){
                params['BrowserContainerId'] = QueryStringService.getValue('BrowserContainerId');
            }

            return baseUrl + $httpParamSerializer(params);
        };

        $scope.problemsOnly = function(){
            defaultFilter();
            $scope.filter.Hoststatus.not_in_downtime = true;
            $scope.filter.Hoststatus.not_acknowledged = true;
            $scope.filter.Hoststatus.current_state = {
                up: false,
                down: true,
                unreachable: true
            };
            SortService.setSort('Hoststatus.last_state_change');
            SortService.setDirection('desc');
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

        $scope.triggerLoadByBookmark = function(filter){
            if(typeof filter !== "undefined"){
                $scope.init = true; //Disable $watch to avoid two HTTP requests
                $scope.filter = filter;
            }else{
                $scope.init = true;
                $scope.resetFilter();
            }

            $("#HostKeywordsInput").tagsinput('removeAll');
            $("#HostKeywordsInput").tagsinput('add', $scope.filter.Host.keywords);

            $("#HostNotKeywordsInput").tagsinput('removeAll');
            $("#HostNotKeywordsInput").tagsinput('add', $scope.filter.Host.not_keywords);

            $scope.currentPage = 1;
            $scope.undoSelection();
            $scope.load();
        }

        //Fire on page load
        defaultFilter();
        $scope.loadColumns(); // load column config
        //$scope.loadDefaultFilterBookmark();
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


        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

    });
