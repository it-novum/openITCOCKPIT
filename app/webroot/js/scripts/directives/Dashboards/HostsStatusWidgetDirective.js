angular.module('openITCOCKPIT').directive('hostsStatusWidget', function($http, $rootScope, $interval){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/hostsStatusListWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            $scope.init = true;

            var $widget = $('#widget-' + $scope.widget.id);

            $widget.on('resize', function(event, items){
                hasResize();
            });

            $scope.hostListTimeout = null;

            $scope.sort = 'Hoststatus.current_state';
            $scope.direction = 'desc';
            $scope.currentPage = 1;

            $scope.filter = {
                Hoststatus: {
                    current_state: {
                        up: false,
                        down: false,
                        unreachable: false
                    },
                    acknowledged: false,
                    not_acknowledged: false,
                    in_downtime: false,
                    not_in_downtime: false,
                    output: ''
                },
                Host: {
                    name: ''
                }
            };

            var loadWidgetConfig = function(){
                $http.get("/dashboards/hostsStatusListWidget.json?angular=true&widgetId="+$scope.widget.id, $scope.filter).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.filter.Hoststatus = result.data.config.Hoststatus;
                    $scope.direction =  result.data.config.direction;
                    $scope.sort =  result.data.config.sort;
                    $scope.useScroll =  result.data.config.scroll;
                    $scope.scroll_interval =  result.data.config.scroll_interval;

                    $scope.load();
                });
            };

            $scope.load = function(options){

                options = options ||{};
                options.save = options.save || false;

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
                    'scroll': true,
                    'sort': $scope.sort,
                    'page': $scope.currentPage,
                    'direction': $scope.direction,
                    'filter[Host.name]': $scope.filter.Host.name,
                    'filter[Hoststatus.output]': $scope.filter.Hoststatus.output,
                    'filter[Hoststatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Hoststatus.current_state),
                    'filter[Hoststatus.problem_has_been_acknowledged]': hasBeenAcknowledged,
                    'filter[Hoststatus.scheduled_downtime_depth]': inDowntime,
                    'limit': $scope.limit
                };

                $http.get("/hosts/index.json", {
                    params: params
                }).then(function(result){
                    $scope.hosts = result.data.all_hosts;
                    $scope.scroll = result.data.scroll;

                    if(options.save === true){
                        saveSettings(params);
                    }

                    $scope.init = false;
                });
            };

            $scope.getSortClass = function(field){
                if(field === $scope.sort){
                    if($scope.direction === 'asc'){
                        return 'fa-sort-asc';
                    }
                    return 'fa-sort-desc';
                }
                return 'fa-sort';
            };

            $scope.orderBy = function(field){
                if(field !== $scope.sort){
                    $scope.direction = 'asc';
                    $scope.sort = field;
                    $scope.load();
                    return;
                }

                if($scope.direction === 'asc'){
                    $scope.direction = 'desc';
                }else{
                    $scope.direction = 'asc';
                }
                $scope.load();
            };

            var hasResize = function(){
                if($scope.hostListTimeout){
                    clearTimeout($scope.hostListTimeout);
                }
                $scope.hostListTimeout = setTimeout(function(){
                    $scope.hostListTimeout = null;
                    $scope.limit = getLimit($widget.height());
                    if($scope.limit <= 0){
                        $scope.limit = 1;
                    }
                    $scope.load();
                }, 500);
            };

            $scope.startScroll = function(){

            };

            $scope.pauseScroll = function(){

            };

            var getLimit = function(height){
                height = height - 34 - 128 - 61 - 10 - 37; //Unit: px
                //                ^ widget Header
                //                     ^ Widget filter
                //                           ^ Paginator
                //                                ^ Margin between header and table
                //                                     ^ Table header

                var limit = Math.floor(height / 36); // 36px = table row height;
                if(limit <= 0){
                    limit = 1;
                }
                return limit;
            };

            var saveSettings = function(settings){
                $http.post("/dashboards/hostsStatusListWidget.json?angular=true&widgetId="+$scope.widget.id, $scope.filter).then(function(result){
                    return true;
                });
            };

            $scope.changepage = function(page){
                if(page !== $scope.currentPage){
                    $scope.currentPage = page;
                    $scope.load();
                }
            };

            $scope.limit = getLimit($widget.height());

            $scope.$watch('filter', function(){
                $scope.currentPage = 1;

                if($scope.init === true){
                    loadWidgetConfig();
                }else{
                    $scope.load({
                        save: true
                    });
                }
            }, true);


        },

        link: function($scope, element, attr){

        }
    };
});
