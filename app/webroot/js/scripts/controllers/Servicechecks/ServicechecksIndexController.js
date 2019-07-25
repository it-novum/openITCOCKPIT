angular.module('openITCOCKPIT')
    .controller('ServicechecksIndexController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, QueryStringService, $stateParams, $interval, StatusHelperService){

        SortService.setSort(QueryStringService.getValue('sort', 'Servicechecks.start_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;

        $scope.id = $stateParams.id;
        $scope.useScroll = true;

        var now = new Date();
        var flappingInterval;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Servicechecks: {
                    state: {
                        ok: false,
                        warning: false,
                        critical: false,
                        unknown: false
                    },
                    state_types: {
                        soft: false,
                        hard: false
                    },
                    output: '',
                    perfdata: ''
                },
                from: date('d.m.Y H:i', now.getTime() / 1000 - (3600 * 24 * 30)),
                to: date('d.m.Y H:i', now.getTime() / 1000 + (3600 * 24 * 30 * 2))
            };
        };
        /*** Filter end ***/

        $scope.init = true;
        $scope.showFilter = false;


        $scope.load = function(){

            var state_type = '';
            if($scope.filter.Servicechecks.state_types.soft ^ $scope.filter.Servicechecks.state_types.hard){
                state_type = 0;
                if($scope.filter.Servicechecks.state_types.hard === true){
                    state_type = 1;
                }
            }

            $http.get("/servicechecks/index/" + $scope.id + ".json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Servicechecks.output]': $scope.filter.Servicechecks.output,
                    'filter[Servicechecks.state][]': $rootScope.currentStateForApi($scope.filter.Servicechecks.state),
                    'filter[Servicechecks.state_type]': state_type,
                    'filter[from]': $scope.filter.from,
                    'filter[to]': $scope.filter.to
                }
            }).then(function(result){
                //console.log(result.data.all_statehistories[0]["StatehistoryService"]);
                $scope.servicechecks = result.data.all_servicechecks;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });

            $http.get("/services/serviceBrowserMenu/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.service = result.data.service;
                $scope.servicestatus = result.data.servicestatus;
                $scope.serviceStatusTextClass = StatusHelperService.getServicestatusTextColor($scope.servicestatus.currentState);

                $scope.serviceBrowserMenu = {
                    hostId: $scope.service.Host.id,
                    hostUuid: $scope.service.Host.uuid,
                    serviceId: $scope.service.Service.id,
                    serviceUuid: $scope.service.Service.uuid,
                    serviceType: $scope.service.Service.service_type,
                    allowEdit: $scope.service.Service.allowEdit,
                    serviceUrl: $scope.service.Service.service_url_replaced,
                    docuExists: result.data.docuExists,
                    isServiceBrowser: false
                };
            });
        };

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function(){
            defaultFilter();
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

        $scope.startFlapping = function(){
            $scope.stopFlapping();
            flappingInterval = $interval(function(){
                if($scope.flappingState === 0){
                    $scope.flappingState = 1;
                }else{
                    $scope.flappingState = 0;
                }
            }, 750);
        };

        $scope.stopFlapping = function(){
            if(flappingInterval){
                $interval.cancel(flappingInterval);
            }
            flappingInterval = null;
        };

        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.load();
        }, true);

        $scope.$watch('servicestatus.isFlapping', function(){
            if($scope.servicestatus){
                if($scope.servicestatus.hasOwnProperty('isFlapping')){
                    if($scope.servicestatus.isFlapping === true){
                        $scope.startFlapping();
                    }

                    if($scope.servicestatus.isFlapping === false){
                        $scope.stopFlapping();
                    }

                }
            }
        });

    });