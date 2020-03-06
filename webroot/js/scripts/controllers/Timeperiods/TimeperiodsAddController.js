angular.module('openITCOCKPIT')
    .controller('TimeperiodsAddController', function($scope, $http, SudoService, $state, NotyService, RedirectService){
        $scope.post = {
            Timeperiod: {
                container_id: '',
                name: '',
                calendar_id: '',
                timeperiod_timeranges: [],
                validate_timeranges: true
            }
        };

        $scope.init = true;
        $scope.hasError = null;
        $scope.errors = {};

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

        $scope.loadCalendars = function(searchString){
            $http.get("/calendars/loadCalendarsByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Timeperiod.container_id,
                    'filter[Calendar.name]': searchString
                }
            }).then(function(result){
                $scope.calendars = result.data.calendars;
            });
        };

        $scope.removeTimerange = function(rangeIndex){
            var timeperiodranges = [];
            for(var i in $scope.timeperiod.ranges){
                if($scope.timeperiod.ranges[i]['index'] !== rangeIndex){
                    timeperiodranges.push($scope.timeperiod.ranges[i])
                }
            }
            if(typeof $scope.errors.validate_timeranges !== 'undefined' ||
                typeof $scope.errors.timeperiod_timeranges !== 'undefined'){
                $scope.timeperiod.ranges = timeperiodranges;
            }else{
                $scope.timeperiod.ranges = _(timeperiodranges)
                    .chain()
                    .flatten()
                    .sortBy(
                        function(range){
                            return [range.day, range.start];
                        })
                    .value();
            }
        };

        $scope.addTimerange = function(){
            var count = $scope.timeperiod.ranges.length + 1;

            $scope.timeperiod.ranges.push({
                id: count,
                day: '1',
                start: '',
                end: '',
                index: Object.keys($scope.timeperiod.ranges).length
            });

            if(typeof $scope.errors.validate_timeranges !== 'undefined' ||
                typeof $scope.errors.timeperiod_timeranges !== 'undefined'){
                $scope.timeperiod.ranges = $scope.timeperiod.ranges;
            }else{
                $scope.timeperiod.ranges = _($scope.timeperiod.ranges)
                    .chain()
                    .flatten()
                    .sortBy(
                        function(range){
                            return [range.day, range.start];
                        })
                    .value();
            }
        };

        $scope.hasTimeRange = function(errors, range){
            if(errors.validate_timeranges){
                errors = errors.validate_timeranges;
                if(errors[parseInt(range['day'])] && errors[parseInt(range['day'])]['state-error']){
                    const stateErrors = errors[parseInt(range['day'])]['state-error'];
                    for(var i in stateErrors){
                        if(stateErrors[i]['start'] === range['start'] && stateErrors[i]['end'] === range['end']){
                            return true;
                        }
                    }
                }
            }

            return false;
        };

        $scope.submit = function(){
            var index = 0;
            $scope.post.Timeperiod.timeperiod_timeranges = [];
            for(var i in $scope.timeperiod.ranges){
                $scope.post.Timeperiod.timeperiod_timeranges[index] = {
                    'day': $scope.timeperiod.ranges[i].day,
                    'start': $scope.timeperiod.ranges[i].start,
                    'end': $scope.timeperiod.ranges[i].end
                };
                index++;
            }

            $http.post("/timeperiods/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('TimeperiodsEdit', {id: result.data.timeperiod.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('TimeperiodsIndex');
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.$watch('post.Timeperiod.container_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadCalendars('');
        }, true);

        $scope.load();
    });
