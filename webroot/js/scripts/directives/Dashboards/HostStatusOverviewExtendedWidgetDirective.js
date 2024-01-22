angular.module('openITCOCKPIT').directive('hostStatusOverviewExtendedWidget', function($http, $state){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/hostStatusOverviewExtendedWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            $scope.init = true;

            var $widget = $('#widget-' + $scope.widget.id);
            $('#widget-content-' + $scope.widget.id).css('overflow', 'hidden');

            $scope.frontWidgetHeight = parseInt(($widget.height()), 10);

            $scope.fontSize = $scope.frontWidgetHeight / 2;

            $widget.on('resize', function(event, items){
                hasResize();
            });

            $scope.hostsStatusOverviewTimeout = null;
            $scope.filter = {
                Hoststatus: {
                    current_state: null,
                    acknowledged: false,
                    not_acknowledged: false,
                    in_downtime: false,
                    not_in_downtime: false,
                    state_older_than: null,
                    state_older_than_unit: 'minutes',
                },
                Host: {
                    name: '',
                    name_regex: false,
                    keywords: '',
                    not_keywords: '',
                    address: '',
                    address_regex: false
                },
                Hostgroup: {
                    _ids: []
                }
            };
            $scope.statusCount = null;
            $scope.hostIds = [];

            $scope.load = function(){
                $http.get("/dashboards/hostStatusOverviewExtendedWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.filter.Hoststatus = result.data.config.Hoststatus;
                    $scope.filter.Hoststatus.state_older_than = parseInt(result.data.config.Hoststatus.state_older_than, 10);
                    $scope.statusCount = result.data.statusCount;
                    $scope.hostIds = result.data.hostIds;
                    $scope.init = false;
                    $('#HostsKeywordsInput' + $scope.widget.id).tagsinput('add', $scope.filter.Host.keywords);
                    $('#HostsNotKeywordsInput' + $scope.widget.id).tagsinput('add', $scope.filter.Host.not_keywords);
                    $scope.loadHostgroups();
                });
            };


            $scope.hideConfig = function(){
                $('#widget-content-' + $scope.widget.id).css('overflow', 'hidden').animate({scrollTop: '0px'}, 500);
                $scope.$broadcast('FLIP_EVENT_IN');
            };
            $scope.showConfig = function(){
                $('#widget-content-' + $scope.widget.id).css('overflow', 'auto');
                $scope.$broadcast('FLIP_EVENT_OUT');
            };


            var hasResize = function(){
                if($scope.init){
                    return;
                }
                $scope.frontWidgetHeight = parseInt(($widget.height()), 10);
                $scope.fontSize = $scope.frontWidgetHeight / 2;

                if($scope.hostsStatusOverviewTimeout){
                    clearTimeout($scope.hostsStatusOverviewTimeout);
                }
                $scope.hostsStatusOverviewTimeout = setTimeout(function(){
                    $scope.load();
                }, 500);
            };

            $scope.loadHostgroups = function(searchString){
                var selected = [];

                if($scope.filter.Hostgroup._ids){
                    selected = $scope.filter.Hostgroup._ids;
                }

                $http.get("/hostgroups/loadHostgroupsByString.json", {
                    params: {
                        'angular': true,
                        'filter[Containers.name]': searchString,
                        'selected[]': selected
                    }
                }).then(function(result){
                    $scope.hostgroups = result.data.hostgroups;
                });
            };

            $scope.load();

            $scope.saveHoststatusOverviewExtended = function(){
                if($scope.init){
                    return;
                }
                $http.post("/dashboards/hostStatusOverviewExtendedWidget.json?angular=true",
                    {
                        Widget: {
                            id: $scope.widget.id
                        },
                        Hoststatus: $scope.filter.Hoststatus,
                        Host: $scope.filter.Host
                    }
                ).then(function(result){
                    //Update status
                    $scope.load();
                    $scope.hideConfig();
                });
            };

            $scope.goToState = function(){
                var params = {
                    hostname: $scope.filter.Host.name,
                    hoststate: [$scope.filter.Hoststatus.current_state],
                };

                if($scope.hostIds.length > 0){
                    params.id = $scope.hostIds;
                }

                if($scope.filter.Hoststatus.current_state > 0){
                    if($scope.filter.Hoststatus.acknowledged){
                        params.has_been_acknowledged = 1;
                    }

                    if($scope.filter.Hoststatus.not_acknowledged){
                        params.has_not_been_acknowledged = 1;
                    }

                    if($scope.filter.Hoststatus.in_downtime){
                        params.in_downtime = 1;
                    }

                    if($scope.filter.Hoststatus.not_in_downtime){
                        params.not_in_downtime = 1;
                    }
                }

                params = _.merge(params, $scope.filter.Host);
                $state.go('HostsIndex', params);
            };

        },

        link: function($scope, element, attr){

        }
    };
});
