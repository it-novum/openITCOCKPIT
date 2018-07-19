angular.module('openITCOCKPIT')
    .controller('Mapeditors_newViewController', function($scope, $http, QueryStringService, $timeout){

        $scope.init = true;
        $scope.id = QueryStringService.getCakeId();

        $scope.load = function(){
            $http.get("/map_module/mapeditors_new/view/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.map = result.data.map;
                $scope.acl = result.data.ACL;


                $scope.init = false;
            });
        };

        $scope.showSummaryStateDelayed = function(item){
            var timer = $timeout(function(){
                //Method is in MapSummaryDirective
                $scope.showSummaryState(item);
            }, 500);
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
                    url = '/map_module/mapeditors_new/view/' + item.object_id;
                    break;

                default:
                    url = 'javascript:void(0);';
                    break;
            }

            return url;
        };

        $scope.load();

    });