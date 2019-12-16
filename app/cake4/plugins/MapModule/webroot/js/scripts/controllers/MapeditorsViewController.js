angular.module('openITCOCKPIT')
    .controller('MapeditorsViewController', function($scope, $http, QueryStringService, $timeout, $interval, $stateParams){

        $scope.init = true;
        $scope.id = $stateParams.id;
        $scope.rotate = null;

        $scope.fullscreen = ($stateParams.fullscreen === 'true');
        if($stateParams.rotation != null) $scope.rotate = $stateParams.rotation;
        $scope.rotationInterval = parseInt($stateParams.interval, 10) * 1000;
        $scope.rotationPossition = 1;

        $scope.interval = null;

        $scope.loadMapDetails = function(){
            $http.get("/map_module/mapeditors/mapDetails/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.map = result.data.map;
                $scope.refreshInterval = $scope.map.Map.refresh_interval;
                if($scope.refreshInterval !== 0 && $scope.refreshInterval < 5000){
                    $scope.refreshInterval = 5000;
                }
                $scope.init = false;
            });
        };


        $scope.loadMapDetails();

        if($scope.rotate !== null && $scope.rotationInterval > 0){
            $scope.rotate = $scope.rotate.split(',');

            $scope.interval = $interval(function(){
                $scope.rotationPossition++;
                if($scope.rotationPossition > $scope.rotate.length){
                    $scope.rotationPossition = 1;
                }

                $scope.id = $scope.rotate[$scope.rotationPossition - 1];
                $scope.loadMapDetails();

            }, $scope.rotationInterval);
        }

        $scope.enterFullscreen = function(){
            document.getElementById('left-panel').style.display = 'none';
            document.getElementById('ribbon').style.display = 'none';
            document.getElementById('header').style.display = 'none';
            document.getElementById('main').style.marginLeft = '0px';
            $('#content > .ng-scope > .row').css('display', 'none');
        };

        $scope.leaveFullscreen = function(){
            document.getElementById('left-panel').style.display = 'block';
            document.getElementById('ribbon').style.display = 'block';
            document.getElementById('header').style.display = 'block';
            document.getElementById('main').style.marginLeft = '220px';
            $('#content > .ng-scope > .row').css('display', 'block');
        };

        //Disable interval if object gets removed from DOM.
        $scope.$on('$destroy', function(){
            if($scope.interval !== null){
                $interval.cancel($scope.interval);
            }
        });

        $scope.$watch('fullscreen', function(){
            if($scope.fullscreen){
                $scope.enterFullscreen();
            }else{
                $scope.leaveFullscreen();
            }
        }, true);


    });
