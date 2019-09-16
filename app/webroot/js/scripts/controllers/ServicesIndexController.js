angular.module('openITCOCKPIT')
    .controller('ServicesIndexController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, MassChangeService, QueryStringService){
        $rootScope.lastObjectName = null;

        SortService.setSort(QueryStringService.getValue('sort', ''));
        SortService.setDirection(QueryStringService.getValue('direction', ''));
        $scope.currentPage = 1;

        $scope.id = QueryStringService.getCakeId();

        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Servicestatus: {
                    current_state: QueryStringService.servicestate(),
                    acknowledged: QueryStringService.getValue('has_been_acknowledged', false) === '1',
                    not_acknowledged: QueryStringService.getValue('has_not_been_acknowledged', false) === '1',
                    in_downtime: QueryStringService.getValue('in_downtime', false) === '1',
                    not_in_downtime: QueryStringService.getValue('not_in_downtime', false) === '1',
                    passive: QueryStringService.getValue('passive', false) === '1',
                    active: QueryStringService.getValue('active', false) === '1',
                    output: ''
                },
                Service: {
                    id: QueryStringService.getIds('filter[Service.id][]', []),
                    name: QueryStringService.getValue('filter[Service.servicename]', ''),
                    keywords: '',
                    not_keywords: ''
                },
                Host: {
                    id: QueryStringService.getValue('filter[Host.id]', ''),
                    name: QueryStringService.getValue('filter[Host.name]', '')
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
        $scope.serverResult = [];
        $scope.isLoadingGraph = true;

        var lastHostUuid = null;


        var forTemplate = function(serverResponse){
            var services = [];
            var servicesstatus = [];
            var hosts = [];
            var hostsstatusArr = [];
            var saved_hostuuids = [];
            var result = [];
            var lastendhost = "";
            var tmp_hostservicegroup = null;
            var graphStart = 0;
            var graphEnd = 0;

            serverResponse.forEach(function(record){
                services.push(record.Service);
                servicesstatus.push([record.Service.id, record.Servicestatus]);
                if(saved_hostuuids.indexOf(record.Host.uuid) < 0){
                    hosts.push(record.Host);
                    hostsstatusArr.push({
                        host_id: record.Host.id,
                        Hoststatus: record.Hoststatus
                    });
                    saved_hostuuids.push(record.Host.uuid);
                }
            });


            services.forEach(function(service){
                //Notice, API return some IDs as string :/
                if(lastendhost != service.host_id){
                    if(tmp_hostservicegroup !== null){
                        result.push(tmp_hostservicegroup);
                    }

                    tmp_hostservicegroup = {};
                    var host = null;
                    var hoststatus = null;
                    hosts.forEach(function(hostelem){
                        //Notice, API return some IDs as string :/
                        if(hostelem.id == service.host_id){
                            host = hostelem;
                        }
                    });
                    hostsstatusArr.forEach(function(hoststatelem){
                        if(hoststatelem.host_id == service.host_id){
                            hoststatus = hoststatelem.Hoststatus;
                        }
                    });


                    tmp_hostservicegroup = {
                        Host: host,
                        Hoststatus: hoststatus,
                        Services: []
                    };
                    lastendhost = service.host_id;
                }

                var servicestatus = null;
                servicesstatus.forEach(function(servstatelem){
                    if(servstatelem[0] === service.id){
                        servicestatus = servstatelem[1];
                    }
                });

                tmp_hostservicegroup.Services.push({
                    Service: service,
                    Servicestatus: servicestatus
                });

            });

            if(tmp_hostservicegroup !== null){
                result.push(tmp_hostservicegroup);
            }

            return result;
        };

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
            lastHostUuid = null;
            var hasBeenAcknowledged = '';
            var inDowntime = '';
            if($scope.filter.Servicestatus.acknowledged ^ $scope.filter.Servicestatus.not_acknowledged){
                hasBeenAcknowledged = $scope.filter.Servicestatus.acknowledged === true;
            }
            if($scope.filter.Servicestatus.in_downtime ^ $scope.filter.Servicestatus.not_in_downtime){
                inDowntime = $scope.filter.Servicestatus.in_downtime === true;
            }

            var passive = '';
            if($scope.filter.Servicestatus.passive ^ $scope.filter.Servicestatus.active){
                passive = !$scope.filter.Servicestatus.passive;
            }

            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Host.id]': $scope.filter.Host.id,
                'filter[Host.name]': $scope.filter.Host.name,
                'filter[Service.id][]': $scope.filter.Service.id,
                'filter[Service.servicename]': $scope.filter.Service.name,
                'filter[Servicestatus.output]': $scope.filter.Servicestatus.output,
                'filter[Servicestatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Servicestatus.current_state),
                'filter[Service.keywords][]': $scope.filter.Service.keywords.split(','),
                'filter[Service.not_keywords][]': $scope.filter.Service.not_keywords.split(','),
                'filter[Servicestatus.problem_has_been_acknowledged]': hasBeenAcknowledged,
                'filter[Servicestatus.scheduled_downtime_depth]': inDowntime,
                'filter[Servicestatus.active_checks_enabled]': passive
            };
            if(QueryStringService.hasValue('BrowserContainerId')){
                params['BrowserContainerId'] = QueryStringService.getValue('BrowserContainerId');
            }

            $http.get("/services/index.json", {
                params: params
            }).then(function(result){
                $scope.services = [];
                $scope.serverResult = result.data.all_services;
                $scope.services = forTemplate(result.data.all_services);
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.triggerFilter = function(){
            if($scope.showFilter === true){
                $scope.showFilter = false;
            }else{
                $scope.showFilter = true;
            }
        };

        $scope.resetFilter = function(){
            defaultFilter();
            $scope.undoSelection();
        };

        $scope.isNextHost = function(service){
            if(service.Host.uuid !== lastHostUuid){
                lastHostUuid = service.Host.uuid;
                return true;
            }
            return false;
        };

        $scope.selectAll = function(){
            if($scope.services){
                for(var key in $scope.serverResult){
                    if($scope.serverResult[key].Service.allow_edit){
                        var id = $scope.serverResult[key].Service.id;
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

        $scope.getObjectForDelete = function(host, service){
            var object = {};
            object[service.Service.id] = host.Host.hostname + '/' + service.Service.servicename;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.serverResult){
                for(var id in selectedObjects){
                    if(id == $scope.serverResult[key].Service.id){
                        objects[id] =
                            $scope.serverResult[key].Host.hostname + '/' +
                            $scope.serverResult[key].Service.servicename;
                    }

                }
            }
            return objects;
        };

        $scope.getObjectsForExternalCommand = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.serverResult){
                for(var id in selectedObjects){
                    if(id == $scope.serverResult[key].Service.id){
                        objects[id] = $scope.serverResult[key];
                    }

                }
            }
            return objects;
        };

        $scope.linkForCopy = function(){
            var baseUrl = '/services/copy/';
            var ids = Object.keys(MassChangeService.getSelected());
            return baseUrl + ids.join('/');
        };

        $scope.linkForPdf = function(){

            var baseUrl = '/services/listToPdf.pdf?';

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

            return baseUrl + $httpParamSerializer({
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Host.id]': $scope.filter.Host.id,
                'filter[Host.name]': $scope.filter.Host.name,
                'filter[Service.id]': $scope.filter.Service.id,
                'filter[Service.servicename]': $scope.filter.Service.name,
                'filter[Servicestatus.output]': $scope.filter.Servicestatus.output,
                'filter[Servicestatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Servicestatus.current_state),
                'filter[Service.keywords][]': $scope.filter.Service.keywords.split(','),
                'filter[Service.not_keywords][]': $scope.filter.Service.not_keywords.split(','),
                'filter[Servicestatus.problem_has_been_acknowledged]': hasBeenAcknowledged,
                'filter[Servicestatus.scheduled_downtime_depth]': inDowntime,
                'filter[Servicestatus.active_checks_enabled]': passive
            });

        };

        $scope.changepage = function(page){
            $scope.undoSelection();
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.mouseenter = function($event, host, service){
            $scope.isLoadingGraph = true;
            var offset = {
                top: $event.relatedTarget.offsetTop + 40,
                left: $event.relatedTarget.offsetLeft + 40
            };

            offset.top += $event.relatedTarget.offsetParent.offsetTop;

            var currentScrollPosition = $(window).scrollTop();

            var margin = 15;
            var $popupGraphContainer = $('#serviceGraphContainer');


            if((offset.top - currentScrollPosition + margin + $popupGraphContainer.height()) > $(window).innerHeight()){
                //There is no space in the window for the popup, we need to set it to an higher point
                $popupGraphContainer.css({
                    'top': parseInt(offset.top - $popupGraphContainer.height() - margin + 10),
                    'left': parseInt(offset.left + margin),
                    'padding': '6px'
                });
            }else{
                //Default Popup
                $popupGraphContainer.css({
                    'top': parseInt(offset.top + margin),
                    'left': parseInt(offset.left + margin),
                    'padding': '6px'
                });
            }

            $popupGraphContainer.show();
            loadGraph(host, service);
        };

        $scope.mouseleave = function(){
            $('#serviceGraphContainer').hide();
            $('#serviceGraphFlot').html('');
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

        var loadGraph = function(host, service){
            graphEnd = Math.floor(Date.now() / 1000);
            graphStart = graphEnd - (3600 * 4);

            $http.get('/Graphgenerators/getPerfdataByUuid.json', {
                params: {
                    angular: true,
                    host_uuid: host.Host.uuid,
                    service_uuid: service.Service.uuid,
                    start: graphStart,
                    end: graphEnd,
                    jsTimestamp: 1
                }
            }).then(function(result){
                $scope.isLoadingGraph = false;
                renderGraph(result.data.performance_data);
            });
        };

        var renderGraph = function(performance_data){
            var graph_data = [];
            for(var dsCount in performance_data){
                graph_data[dsCount] = [];
                for(var timestamp in performance_data[dsCount].data){
                    var frontEndTimestamp = (parseInt(timestamp, 10) + ($scope.timezone.user_offset * 1000));
                    graph_data[dsCount].push([frontEndTimestamp, performance_data[dsCount].data[timestamp]]);
                }
                //graph_data.push(performance_data[key].data);
            }
            var color_amount = performance_data.length < 3 ? 3 : performance_data.length;

            var GraphDefaultsObj = new GraphDefaults();

            var colors = GraphDefaultsObj.getColors(color_amount);

            var options = GraphDefaultsObj.getDefaultOptions();
            options.height = '500px';
            options.colors = colors.border;
            options.xaxis.tickFormatter = function(val, axis){
                var fooJS = new Date(val + ($scope.timezone.user_offset * 1000));
                var fixTime = function(value){
                    if(value < 10){
                        return '0' + value;
                    }
                    return value;
                };
                return fixTime(fooJS.getUTCDate()) + '.' + fixTime(fooJS.getUTCMonth() + 1) + '.' + fooJS.getUTCFullYear() + ' ' + fixTime(fooJS.getUTCHours()) + ':' + fixTime(fooJS.getUTCMinutes());
            };
            options.xaxis.min = graphStart * 1000;
            options.xaxis.max = graphEnd * 1000;


            self.plot = $.plot('#serviceGraphFlot', graph_data, options);
        };

        //Fire on page load
        defaultFilter();
        $scope.loadTimezone();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.undoSelection();
            $scope.load();
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