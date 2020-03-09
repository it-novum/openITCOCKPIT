angular.module('openITCOCKPIT')
    .controller('TimeperiodsEditController', function($scope, $http, SudoService, $state, $stateParams, $location, NotyService, RedirectService){
        $scope.post = {
            Timeperiod: {
                container_id: '',
                name: '',
                calendar_id: '',
                timeperiod_timeranges: [],
                validate_timeranges: true
            }
        };
        $scope.id = $stateParams.id;

        $scope.init = true;
        $scope.hasError = null;
        $scope.errors = {};

        $scope.timeperiod = {
            ranges: []
        };

        $scope.load = function(){
            $http.get("/timeperiods/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.timeperiod = result.data.timeperiod;

                $scope.timeperiod.ranges = [];

                for(key in $scope.timeperiod.timeperiod_timeranges){
                    $scope.timeperiod.ranges.push({
                        id: $scope.timeperiod.timeperiod_timeranges[key].id,
                        day: $scope.timeperiod.timeperiod_timeranges[key].day.toString(),
                        start: $scope.timeperiod.timeperiod_timeranges[key].start,
                        end: $scope.timeperiod.timeperiod_timeranges[key].end,
                        index: key
                    });
                }

                $scope.timeperiod.ranges = _($scope.timeperiod.ranges)
                    .chain()
                    .flatten()
                    .sortBy(
                        function(range){
                            return [range.day, range.start];
                        })
                    .value();

                $scope.post.Timeperiod.name = $scope.timeperiod.name;
                $scope.post.Timeperiod.description = $scope.timeperiod.description;
                $scope.post.Timeperiod.container_id = $scope.timeperiod.container_id;
                $scope.post.Timeperiod.calendar_id = $scope.timeperiod.calendar_id;
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
            $scope.loadContainer();
        };

        $scope.loadContainer = function(){
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
            $scope.timeperiod.ranges.push({
                id: null,
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
                for(var j in $scope.timeperiod.ranges){
                    if(parseInt($scope.timeperiod.ranges[j].index) === parseInt(i)){
                        $scope.post.Timeperiod.timeperiod_timeranges[index] = {
                            'id': $scope.timeperiod.ranges[j].id,
                            'day': $scope.timeperiod.ranges[j].day,
                            'start': $scope.timeperiod.ranges[j].start,
                            'end': $scope.timeperiod.ranges[j].end
                        };
                        index++;
                        break;
                    }
                }
            }

            $http.post("/timeperiods/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess({
                    message: '<u><a href="' + $location.absUrl() + '" class="txt-color-white"> '
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
