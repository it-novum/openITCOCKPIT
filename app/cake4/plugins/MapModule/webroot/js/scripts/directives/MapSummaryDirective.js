angular.module('openITCOCKPIT').directive('mapSummary', function($http, $interval, $httpParamSerializer){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors/mapsummary.html',

        controller: function($scope){
            $scope.loadSumaryState = function(item, summary){
                $http.get("/map_module/mapeditors/mapsummary/.json", {
                    params: {
                        'angular': true,
                        'objectId': item.object_id,
                        'disableGlobalLoader': true,
                        'type': item.type,
                        'summary': summary
                    }
                }).then(function(result){
                    $('.map-summary-state-popover').switchClass('slideOutRight', 'slideInRight');
                    $scope.summaryState = result.data.summary;
                    $scope.iconType = item.type;
                    $scope.startInterval();
                });
            };
            $scope.hideTooltip = function($event){
                $($event.currentTarget).switchClass('slideInRight', 'slideOutRight');
                $scope.stopInterval();
            };

            $scope.startInterval = function(){
                var showFor = 5000;
                var intervalSpeed = 10;
                $scope.percentValue = 100;

                $scope.stopInterval();

                $scope.intervalRef = $interval(function(){
                    showFor = showFor - intervalSpeed;
                    if(showFor === 0){
                        $scope.stopInterval();
                        $('.map-summary-state-popover').switchClass('slideInRight', 'slideOutRight');
                    }

                    $scope.percentValue = showFor / 5000 * 100;
                }, intervalSpeed);
            };

            $scope.stopInterval = function(){
                if(typeof $scope.intervalRef !== "undefined"){
                    $interval.cancel($scope.intervalRef);
                }

            };

            $scope.getObjectHref = function(type, objectId){
                var url = 'javascript:void(0);';
                switch(type){
                    case 'host':
                        if($scope.acl.hosts.browser){
                            url = '/#!/hosts/browser/' + objectId;
                        }
                        break;

                    case 'service':
                        if($scope.acl.services.browser){
                            url = '/#!/services/browser/' + objectId;
                        }
                        break;

                    case 'hostgroup':
                        if($scope.acl.hostgroups.extended){
                            url = '/#!/hostgroups/extended/' + objectId;
                        }
                        break;

                    case 'servicegroup':
                        if($scope.acl.servicegroups.extended){
                            url = '/#!/servicegroups/extended/' + objectId;
                        }
                        break;

                    case 'map':
                        url = '/#!/map_module/mapeditors/view/' + objectId;
                        break;

                    default:
                        url = 'javascript:void(0);';
                        break;
                }

                return url;
            };

            $scope.getObjectsHref = function(type, objectIds){
                var url = 'javascript:void(0);';
                if(objectIds.length === 0){
                    return url;
                }
                switch(type){
                    case 'host':
                        if($scope.acl.hosts.index){
                            url = '/#!/hosts/index?' + $httpParamSerializer({
                                'angular': true,
                                'filter[Host.id][]': objectIds
                            });
                        }
                        break;

                    case 'service':
                        if($scope.acl.services.index){
                            url = '/#!/services/index?' + $httpParamSerializer({
                                'angular': true,
                                'filter[Service.id][]': objectIds
                            });
                        }
                        break;
                    default:
                        url = 'javascript:void(0);';
                        break;
                }

                return url;
            };

        },

        link: function(scope, element, attr){
            scope.showSummaryState = function(item, summary){ //--> is summary item (true / false)
                scope.loadSumaryState(item, summary);
            };
        }
    };
});
