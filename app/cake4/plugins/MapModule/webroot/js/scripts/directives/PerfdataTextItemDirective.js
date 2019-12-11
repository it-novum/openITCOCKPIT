angular.module('openITCOCKPIT').directive('perfdataTextItem', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors/perfdatatext.html',
        scope: {
            'item': '=',
            'refreshInterval': '='
        },
        controller: function($scope){
            $scope.init = true;
            $scope.statusUpdateInterval = null;

            //$scope.width = '100%';
            //$scope.height = '100%';
            $scope.width = $scope.item.size_x;
            $scope.height = $scope.item.size_y;

            $scope.load = function(){
                $http.get("/map_module/mapeditors/mapitem/.json", {
                    params: {
                        'angular': true,
                        'disableGlobalLoader': true,
                        'objectId': $scope.item.object_id,
                        'mapId': $scope.item.map_id,
                        'type': $scope.item.type
                    }
                }).then(function(result){
                    $scope.responsePerfdata = result.data.data.Perfdata;

                    switch(result.data.data.color){
                        case 'txt-color-green':
                            $scope.color = '#356e35';
                            break;

                        case 'warning':
                            $scope.color = '#DF8F1D';
                            break;

                        case 'txt-color-red':
                            $scope.color = '#a90329';
                            break;

                        case 'txt-color-blueDark':
                            $scope.color = '#4c4f53';
                            break;

                        default:
                            $scope.color = '#337ab7'; //text-primary
                            break;
                    }

                    processPerfdata();

                    /*
                    setTimeout(function(){
                        //Resolve strange resize bug on draggable
                        var $mapPerfdatatext = $('#map-perfdatatext-'+$scope.item.id);
                        $scope.width = $mapPerfdatatext.width();
                        $scope.height = $mapPerfdatatext.height();

                    }, 150);*/
                    initRefreshTimer();

                    $scope.init = false;
                });
            };

            $scope.stop = function(){
                if($scope.statusUpdateInterval !== null){
                    $interval.cancel($scope.statusUpdateInterval);
                }
            };

            //Disable status update interval, if the object gets removed from DOM.
            //E.g in Map rotations
            $scope.$on('$destroy', function(){
                $scope.stop();
            });

            var processPerfdata = function(){

                if($scope.responsePerfdata !== null){
                    if($scope.item.metric !== null && $scope.responsePerfdata.hasOwnProperty($scope.item.metric)){
                        $scope.perfdataName = $scope.item.metric;
                        $scope.perfdata = $scope.responsePerfdata[$scope.item.metric];
                    }else{
                        //Use first metric.
                        for(var metricName in $scope.responsePerfdata){
                            $scope.perfdataName = metricName;
                            $scope.perfdata = $scope.responsePerfdata[metricName];
                            break;
                        }
                    }
                }

                var text = $scope.perfdata.current;
                if($scope.perfdata.unit !== null && $scope.perfdata.unit !== ''){
                    text = text + ' ' + $scope.perfdata.unit;
                }

                if($scope.item.show_label){
                    text = $scope.perfdataName + ' ' + text;
                }

                if($scope.width <= 0){
                    $scope.width = text.length * 11;
                }

                if($scope.height <= 0){
                    $scope.height = 18;
                }

                $scope.text = text;
            };

            var initRefreshTimer = function(){
                if($scope.refreshInterval > 0 && $scope.statusUpdateInterval === null){
                    $scope.statusUpdateInterval = $interval(function(){
                        $scope.load();
                    }, $scope.refreshInterval);
                }
            };

            $scope.$watchGroup(['item.size_x', 'item.show_label', 'item.metric'], function(){
                if($scope.init){
                    return;
                }

                processPerfdata();
                $scope.width = $scope.item.size_x;
                $scope.height = $scope.item.size_y;
            });

            $scope.$watch('item.object_id', function(){
                if($scope.init || $scope.item.object_id === null){
                    //Avoid ajax error if user search a service in Gadget config modal
                    return;
                }

                $scope.load();
            });

            $scope.load();
        },

        link: function(scope, element, attr){

        }
    };
});
