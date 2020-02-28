angular.module('openITCOCKPIT').directive('mapeditorView', function($http, $timeout, $interval, $state){
    return {
        restrict: 'A',
        templateUrl: '/map_module/mapeditors/viewDirective.html',
        scope: {
            'mapId': '='
        },

        controller: function($scope){
            $scope.init = true;

            $scope.refreshInterval = 0;

            var timer;
            var interval;

            $scope.load = function(){
                $http.get("/map_module/mapeditors/view/" + $scope.id + ".json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.refreshInterval = parseInt(result.data.map.Map.refresh_interval, 10);
                    $scope.map = result.data.map;
                    $scope.acl = result.data.ACL;

                    if($scope.init){
                        if($scope.refreshInterval > 1000 && $scope.rotate === null){
                            //Only refresh maps if they are not in a rotation.
                            //Rotation will also refresh maps on change of current map
                            $interval.cancel(interval);

                            interval = $interval(function(){
                                $scope.load();
                            }, $scope.refreshInterval);
                        }
                    }

                    $scope.init = false;
                });
            };

            $scope.showSummaryStateDelayed = function(item, summary){ //--> is summary item (true / false)
                timer = $timeout(function(){
                    //Method is in MapSummaryDirective
                    $scope.showSummaryState(item, summary);
                }, 500);
            };

            $scope.cancelTimer = function(){
                $timeout.cancel(timer);
            };

            $scope.getHref = function(item){
                var url = 'javascript:void(0);';

                switch(item.type){
                    case 'host':
                        if($scope.acl.hosts.browser){
                            url = $state.href('HostsBrowser', {id: item.object_id});
                        }
                        break;

                    case 'service':
                        if($scope.acl.services.browser){
                            url = $state.href('ServicesBrowser', {id: item.object_id});
                        }
                        break;

                    case 'hostgroup':
                        if($scope.acl.hostgroups.extended){
                            url = $state.href('HostgroupsExtended', {id: item.object_id});
                        }
                        break;

                    case 'servicegroup':
                        if($scope.acl.servicegroups.extended){
                            url = $state.href('ServicegroupsExtended', {id: item.object_id});
                        }
                        break;

                    case 'map':
                        url = $state.href('MapeditorsView', {id: item.object_id});
                        break;

                    default:
                        url = 'javascript:void(0);';
                        break;
                }

                return url;
            };


            $scope.$watch('mapId', function(){
                $scope.id = parseInt($scope.mapId, 10);
                $scope.load();
            });


        },

        link: function($scope, element, attr){

        }
    };
});
