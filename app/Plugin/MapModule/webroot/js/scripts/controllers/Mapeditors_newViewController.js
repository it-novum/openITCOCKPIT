angular.module('openITCOCKPIT')
    .controller('Mapeditors_newViewController', function($scope, $http, QueryStringService, $timeout, $interval){

        $scope.init = true;
        $scope.id = QueryStringService.getCakeId();

        $scope.fullscreen = QueryStringService.getValue('fullscreen', false) === 'true';
        $scope.rotate = QueryStringService.getValue('rotation', null);
        $scope.rotationInterval = parseInt(QueryStringService.getValue('interval', 0), 10) * 1000;
        $scope.rotationPossition = 1;
        $scope.refreshInterval = 0;

        var timer;

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
                        $interval(function(){
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
                        url = '/hosts/browser/' + item.object_id;
                    }
                    break;

                case 'service':
                    if($scope.acl.services.browser){
                        url = '/services/browser/' + item.object_id;
                    }
                    break;

                case 'hostgroup':
                    if($scope.acl.hostgroups.extended){
                        url = '/hostgroups/extended/' + item.object_id;
                    }
                    break;

                case 'servicegroup':
                    if($scope.acl.servicegroups.extended){
                        url = '/servicegroups/extended/' + item.object_id;
                    }
                    break;

                case 'map':
                    url = '/map_module/mapeditors/view/' + item.object_id;
                    break;

                default:
                    url = 'javascript:void(0);';
                    break;
            }

            return url;
        };


        $scope.load();

        if($scope.rotate !== null && $scope.rotationInterval > 0){
            $scope.rotate = $scope.rotate.split(',');

            $interval(function(){
                $scope.rotationPossition++;
                if($scope.rotationPossition > $scope.rotate.length){
                    $scope.rotationPossition = 1;
                }

                $scope.id = $scope.rotate[$scope.rotationPossition - 1];
                $scope.load();

            }, $scope.rotationInterval);
        }

    });