angular.module('openITCOCKPIT')
    .controller('HostsIndexController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, MassChangeService, QueryStringService, $stateParams){
        $rootScope.lastObjectName = null;

        SortService.setSort(QueryStringService.getStateValue($stateParams, 'sort', 'Hoststatus.current_state'));
        SortService.setDirection(QueryStringService.getStateValue($stateParams, 'direction', 'desc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;
        $scope.id = QueryStringService.getStateValue($stateParams, 'id', 1);

        filterHostname = QueryStringService.getStateValue($stateParams, 'hostname');
        filterAddress = QueryStringService.getStateValue($stateParams, 'address');

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Hoststatus: {
                    current_state: QueryStringService.hoststate($stateParams),
                    acknowledged: QueryStringService.getStateValue($stateParams, 'has_been_acknowledged', false) == '1',
                    not_acknowledged: QueryStringService.getStateValue($stateParams, 'has_not_been_acknowledged', false) == '1',
                    in_downtime: QueryStringService.getStateValue($stateParams, 'in_downtime', false) == '1',
                    not_in_downtime: QueryStringService.getStateValue($stateParams, 'not_in_downtime', false) == '1',
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
                    container: '',
                    priority: {
                        1: false,
                        2: false,
                        3: false,
                        4: false,
                        5: false
                    }
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/hosts/delete/';
        $scope.deactivateUrl = '/hosts/deactivate/';
        /*** Dynamic custom table ***/
        $scope.post = {
            id: '',
            user_id: '',
            table_name: '',
            dynamictable: {
                custom_hoststatus: '',
                custom_acknowledgement: '',
                custom_indowntime: '',
                custom_shared: '',
                custom_passive: '',
                custom_priority: '',
                custom_hostname: '',
                custom_ip_address: '',
                custom_last_change: '',
                custom_last_check: '',
                custom_host_output: '',
                custom_instance: '',
                custom_service_summary: '',
                custom_description: '',
                custom_container_name: ''
            }
        }
        /*** Dynamic custom table end ***/
        var getContainerName = function(containerId){
            containerId = parseInt(containerId, 10);
            for(var index in $scope.containers){
                if($scope.containers[index].key === containerId){
                    return $scope.containers[index].value;
                }
            }
            return 'ERROR UNKNOWN CONTAINER';
        };

        $scope.init = true;
        $scope.showFilter = false;


        $scope.load = function(){

            lastHostUuid = null;
            var hasBeenAcknowledged = '';
            var inDowntime = '';
            if($scope.filter.Hoststatus.acknowledged ^ $scope.filter.Hoststatus.not_acknowledged){
                hasBeenAcknowledged = $scope.filter.Hoststatus.acknowledged === true;
            }
            if($scope.filter.Hoststatus.in_downtime ^ $scope.filter.Hoststatus.not_in_downtime){
                inDowntime = $scope.filter.Hoststatus.in_downtime === true;
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
                'direction': SortService.getDirection(),
                'filter[Hosts.id][]': $scope.filter.Host.id,
                'filter[Hosts.name]': $scope.filter.Host.name,
                'filter[hostdescription]': $scope.filter.Host.hostdescription,
                'filter[Hoststatus.output]': $scope.filter.Hoststatus.output,
                'filter[Hoststatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Hoststatus.current_state),
                'filter[Hosts.keywords][]': $scope.filter.Host.keywords.split(','),
                'filter[Hosts.not_keywords][]': $scope.filter.Host.not_keywords.split(','),
                'filter[Hoststatus.problem_has_been_acknowledged]': hasBeenAcknowledged,
                'filter[Hoststatus.scheduled_downtime_depth]': inDowntime,
                'filter[Hosts.address]': $scope.filter.Host.address,
                'filter[Hosts.satellite_id][]': $scope.filter.Host.satellite_id,
                'filter[Hosts.container]': $scope.filter.Host.container,
                'filter[hostpriority][]': priorityFilter
            };
            if(QueryStringService.getStateValue($stateParams, 'BrowserContainerId') !== null){
                params['BrowserContainerId'] = QueryStringService.getStateValue($stateParams, 'BrowserContainerId');
            }

            $http.get("/hosts/index.json", {
                params: params
            }).then(function(result){
                $scope.hosts = result.data.all_hosts;
                $scope.containerName = getContainerName($scope.hosts[0].Host.containerId);
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.loadContainer = function(){
            $http.get("/containers/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
            });
        };

        $scope.customaizedTableConfig = function(){

            $http.get("/hosts/CustomDynamicTable.json", {
                angular: true
            }).then(function(result){

                if(result.data.table_data){
                    // $scope.post.id = '';
                    // $scope.post.user_id = result.data.table_data.user_id;
                    // $scope.post.dynamictable = JSON.parse(result.data.table_data.json_data);
                    // $scope.post.table_name = result.data.table_data.table_name;
                    // console.log(result.data.table_data);

                }
                if(result.data.table_data[0]){
                    $scope.post.id = result.data.table_data[0].id;
                    $scope.post.id = result.data.table_data[0].user_id;
                    $scope.post.dynamictable = JSON.parse(result.data.table_data[0].json_data);
                    $scope.post.table_name = result.data.table_data[0].table_name;
                    console.log(result.data.table_data[0]);
                }
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }
                if(result.status === 404){
                    $state.go('404');
                }
            });
        };
        $scope.customaizedTableConfig();

        $scope.toggleColumn = function(){
            $http.post("/hosts/CustomDynamicTable.json?angular=true",
                $scope.post
            ).then(function(result){
                console.log(result);
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

        $scope.linkForPdf = function(){

            var baseUrl = '/hosts/listToPdf.pdf?';

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
                'filter[Hosts.satellite_id][]': $scope.filter.Host.satellite_id
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

        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        jQuery(function(){
            $("input[data-role=tagsinput]").tagsinput();
        });

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.undoSelection();
            $scope.load();
        }, true);


        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

        $scope.loadContainer();

    });
