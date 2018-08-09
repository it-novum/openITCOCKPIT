angular.module('openITCOCKPIT').directive('perfdataTextItem', function($http){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors_new/perfdatatext.html',
        scope: {
            'item': '='
        },
        controller: function($scope){
            $scope.init = true;

            $scope.showLabel = $scope.item.show_label;

            $scope.width = '100%';
            $scope.height = '100%';

            $scope.load = function(){
                $http.get("/map_module/mapeditors_new/mapitem/.json", {
                    params: {
                        'angular': true,
                        'objectId': $scope.item.object_id,
                        'mapId': $scope.item.map_id,
                        'type': $scope.item.type
                    }
                }).then(function(result){
                    var perfdata = result.data.data.Perfdata;

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

                    if(perfdata !== null){
                        if(Object.keys(perfdata).length > 0){
                            $scope.perfdataName = Object.keys(perfdata)[0];
                            $scope.perfdata = perfdata[$scope.perfdataName];
                        }
                    }

                    var text = $scope.perfdata.current;
                    if($scope.perfdata.unit !== null && $scope.perfdata.unit !== ''){
                        text = text + ' ' + $scope.perfdata.unit;
                    }

                    if($scope.showLabel){
                        text = $scope.perfdataName + text;
                    }
                    $scope.text = text;

                    setTimeout(function(){
                        //Resolve strange resize bug on draggable
                        var $mapPerfdatatext = $('#map-perfdatatext-'+$scope.item.id);
                        $scope.width = $mapPerfdatatext.width();
                        $scope.height = $mapPerfdatatext.height();

                    }, 150);

                    $scope.init = false;
                });
            };

            $scope.$watch('item.size_x', function(){
                if($scope.init){
                    return;
                }

                $scope.width = $scope.item.size_x;
                $scope.height = $scope.item.size_y;
            });

            $scope.load();
        },

        link: function(scope, element, attr){

        }
    };
});
