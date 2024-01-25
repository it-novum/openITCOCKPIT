angular.module('openITCOCKPIT').directive('serviceStatusOverviewExtendedWidget', function($http, $state){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/serviceStatusOverviewExtendedWidget.html',
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

            $scope.serviceStatusOverviewTimeout = null;
            $scope.filter = {
                Servicestatus: {
                    current_state: null,
                    acknowledged: false,
                    not_acknowledged: false,
                    in_downtime: false,
                    not_in_downtime: false,
                },
                Host: {
                    name: '',
                    name_regex: false,
                    keywords: '',
                    not_keywords: '',
                },
                Service: {
                    name: '',
                    name_regex: false,
                    keywords: '',
                    not_keywords: '',
                },
                Servicegroup: {
                    _ids: []
                }
            };
            $scope.statusCount = null;

            $scope.load = function(){
                $http.get("/dashboards/serviceStatusOverviewExtendedWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.filter.Service = result.data.config.Service;
                    $scope.filter.Servicestatus = result.data.config.Servicestatus;
                    $scope.filter.Servicestatus.state_older_than = parseInt(result.data.config.Servicestatus.state_older_than, 10);
                    $scope.filter.Servicegroup._ids = result.data.config.Servicegroup._ids.split(',').map(Number);
                    $scope.statusCount = result.data.statusCount;
                    $scope.serviceIds = result.data.serviceIds;

                    $scope.init = false;
                    $('#HostsKeywordsInput' + $scope.widget.id).tagsinput('add', $scope.filter.Host.keywords);
                    $('#HostsNotKeywordsInput' + $scope.widget.id).tagsinput('add', $scope.filter.Host.not_keywords);
                    $('#ServicesKeywordsInput' + $scope.widget.id).tagsinput('add', $scope.filter.Service.keywords);
                    $('#ServicesNotKeywordsInput' + $scope.widget.id).tagsinput('add', $scope.filter.Service.not_keywords);
                    $scope.loadServicegroups();
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

                if($scope.serviceStatusOverviewTimeout){
                    clearTimeout($scope.serviceStatusOverviewTimeout);
                }
                $scope.serviceStatusOverviewTimeout = setTimeout(function(){
                    $scope.load();
                }, 500);
            };

            $scope.loadServicegroups = function(searchString){
                var selected = [];

                if($scope.filter.Servicegroup._ids){
                    selected = $scope.filter.Servicegroup._ids;
                }

                $http.get("/servicegroups/loadServicegroupsByString.json", {
                    params: {
                        'angular': true,
                        'filter[Containers.name]': searchString,
                        'selected[]': selected
                    }
                }).then(function(result){
                    $scope.servicegroups = result.data.servicegroups;
                });
            };


            $scope.load();
            jQuery(function(){
                $("input[data-role=tagsinput]").tagsinput();
            });

            $scope.saveServicestatusOverviewExtended = function(){
                if($scope.init){
                    return;
                }
                $http.post("/dashboards/serviceStatusOverviewExtendedWidget.json?angular=true",
                    {
                        Widget: {
                            id: $scope.widget.id
                        },
                        Servicestatus: $scope.filter.Servicestatus,
                        Host: $scope.filter.Host,
                        Service: $scope.filter.Service,
                        Servicegroup: $scope.filter.Servicegroup
                    }
                ).then(function(result){
                    //Update status
                    $scope.load();
                    $scope.hideConfig();
                });
            };

            $scope.goToState = function(){
                var params = {
                    servicename: $scope.filter.Service.name,
                    servicename_regex: $scope.filter.Service.name_regex,
                    hostname: $scope.filter.Host.name,
                    hostname_regex: $scope.filter.Host.name_regex,
                    servicestate: [$scope.filter.Servicestatus.current_state]
                };

                if($scope.serviceIds.length > 0){
                    params.id = $scope.serviceIds;
                }


                if($scope.filter.Servicestatus.current_state > 0){
                    if($scope.filter.Servicestatus.acknowledged){
                        params.has_been_acknowledged = 1;
                    }

                    if($scope.filter.Servicestatus.not_acknowledged){
                        params.has_not_been_acknowledged = 1;
                    }

                    if($scope.filter.Servicestatus.in_downtime){
                        params.in_downtime = 1;
                    }

                    if($scope.filter.Servicestatus.not_in_downtime){
                        params.not_in_downtime = 1;
                    }
                }

                params = _.merge(params, $scope.filter.Host);
                params = _.merge(params, $scope.filter.Service);


                $state.go('ServicesIndex', params);
            };
        },

        link:
            function($scope, element, attr){
            }
    };
});
