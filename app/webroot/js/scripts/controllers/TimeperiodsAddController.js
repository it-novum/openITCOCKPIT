angular.module('openITCOCKPIT')
    .controller('TimeperiodsAddController', function($scope, $http, SudoService, $state, NotyService){
        $scope.post = {
            Timeperiod: {
                container_id: '',
                name: '',
                timeperiodranges: []
            },
            Calendar: {
                id: ''
            }
        };

        $scope.init = true;
        $scope.hasError = null;

        $scope.timeperiod = {
            ranges: []
        };

        $scope.load = function(){
            $http.get("/containers/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };


        $scope.removeTimerange = function(rangeIndex){
            var timeperiodranges = [];
            for(var i in $scope.timeperiod.ranges){
                if(i !== rangeIndex){
                    timeperiodranges.push($scope.timeperiod.ranges[i])
                }
            }
            $scope.timeperiod.ranges = _(timeperiodranges)
                .chain()
                .flatten()
                .sortBy(
                    function(range){
                        return [range.day, range.start];
                    })
                .value();
        };

        $scope.addTimerange = function(){
            var count = $scope.timeperiod.ranges.length + 1;

            $scope.timeperiod.ranges.push({
                id: count,
                day: '1',
                start: '',
                end: ''

            });
            $scope.timeperiod.ranges = _($scope.timeperiod.ranges)
                .chain()
                .flatten()
                .sortBy(
                    function(range){
                        return [range.day, range.start];
                    })
                .value();
        };


        $scope.submit = function(){
            var index = 0;
            for(var i in $scope.timeperiod.ranges){
                if(!/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/.test($scope.timeperiod.ranges[i].start) || !/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/.test($scope.timeperiod.ranges[i].end)){
                    continue;
                }
                $scope.post.Timeperiod.timeperiodranges[index] = {
                    'day': $scope.timeperiod.ranges[i].day,
                    'start': $scope.timeperiod.ranges[i].start,
                    'end': $scope.timeperiod.ranges[i].end
                };
                index++;
            }
            console.log($scope.post.Timeperiod);
            return;
            $http.post("/timeperiods/add.json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                $state.go('TimeperiodsIndex');
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.load();
    });
